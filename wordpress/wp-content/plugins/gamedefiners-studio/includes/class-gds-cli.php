<?php
if (!defined('ABSPATH') || !defined('WP_CLI')) { exit; }

/** Automated RAWG game preview drafts for GD Studio. */
class GDS_CLI {
    /**
     * Generate preview drafts from RAWG game discovery.
     *
     * ## OPTIONS
     *
     * [--auto] Required for RAWG discovery.
     * [--source=<source>] Only rawg is supported.
     * [--type=<type>] Only preview is supported in auto mode.
     * [--max=<number>] Maximum new drafts, default 3 (maximum 10).
     * [--days=<number>] Upcoming release window, default 90.
     * [--author=<id>] WordPress author ID, default 1.
     * [--language=<code>] en or sv, default en.
     * [--dry-run] Preview selections without creating drafts or calling OpenAI.
     */
    public function generate($args, $assoc) {
        if (!isset($assoc['auto'])) { WP_CLI::error('This command currently supports --auto only. Manual generation remains in GD Studio admin.'); }
        if (($assoc['source'] ?? 'rawg') !== 'rawg') { WP_CLI::error('Only --source=rawg is supported.'); }
        if (($assoc['type'] ?? 'preview') !== 'preview') { WP_CLI::error('Auto mode only supports --type=preview. Guides and news are not auto-generated.'); }
        $max = max(1, min(10, (int) ($assoc['max'] ?? 3)));
        $days = max(1, min(365, (int) ($assoc['days'] ?? 90)));
        $author = (int) ($assoc['author'] ?? 1);
        $language = ($assoc['language'] ?? 'en') === 'sv' ? 'Swedish' : 'English';
        $dry = isset($assoc['dry-run']);
        if (!get_user_by('id', $author)) { WP_CLI::error('Author ID does not exist.'); }
        $rawg_key = defined('RAWG_API_KEY') ? (string) RAWG_API_KEY : (string) getenv('RAWG_API_KEY');
        if (!$rawg_key) { WP_CLI::error('Set RAWG_API_KEY in wp-config.php or environment.'); }
        $settings = get_option('gds_settings', []);
        if (!is_array($settings)) { $settings = []; }
        $openai_key = defined('GDS_OPENAI_API_KEY') && GDS_OPENAI_API_KEY ? (string) GDS_OPENAI_API_KEY : (string) ($settings['api_key'] ?? '');
        if (!$dry && !$openai_key) { WP_CLI::error('Set GDS_OPENAI_API_KEY or configure the API key in GD Studio settings.'); }
        $from = wp_date('Y-m-d');
        $to = wp_date('Y-m-d', current_time('timestamp') + $days * DAY_IN_SECONDS);
        $selected = [];
        for ($page = 1; $page <= 3 && count($selected) < $max; $page++) {
            $url = add_query_arg(['key' => $rawg_key, 'dates' => $from . ',' . $to, 'ordering' => '-added', 'page_size' => 40, 'page' => $page], 'https://api.rawg.io/api/games');
            $response = wp_remote_get($url, ['timeout' => 25]);
            if (is_wp_error($response)) { WP_CLI::error('RAWG request failed: ' . $response->get_error_message()); }
            $status = wp_remote_retrieve_response_code($response);
            $data = json_decode(wp_remote_retrieve_body($response), true);
            if ($status !== 200 || !isset($data['results']) || !is_array($data['results'])) { WP_CLI::error('RAWG returned HTTP ' . $status . '. Check API access.'); }
            foreach ($data['results'] as $game) {
                $id = (int) ($game['id'] ?? 0);
                $name = sanitize_text_field((string) ($game['name'] ?? ''));
                $date = (string) ($game['released'] ?? '');
                if (!$id || !$name || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || $date < $from || $date > $to) { continue; }
                if ((int) ($game['added'] ?? 0) < 10) { continue; }
                $existing = get_posts(['post_type' => 'post', 'post_status' => 'any', 'fields' => 'ids', 'posts_per_page' => 1, 'meta_key' => '_gds_rawg_game_id', 'meta_value' => (string) $id]);
                if ($existing) { continue; }
                $selected[] = ['id' => $id, 'name' => $name, 'date' => $date, 'added' => (int) ($game['added'] ?? 0)];
                if (count($selected) >= $max) { break; }
            }
            if (empty($data['next'])) { break; }
        }
        if (!$selected) { WP_CLI::success('No new qualifying upcoming games found.'); return; }
        foreach ($selected as $game) { WP_CLI::log(($dry ? '[DRY RUN] ' : 'Selected: ') . $game['name'] . ' (' . $game['date'] . ', RAWG ' . $game['id'] . ')'); }
        if ($dry) { WP_CLI::success('Previewed ' . count($selected) . ' games. No AI calls or posts created.'); return; }
        $created = 0;
        foreach ($selected as $game) {
            $detail_url = add_query_arg(['key' => $rawg_key], 'https://api.rawg.io/api/games/' . $game['id']);
            $detail_response = wp_remote_get($detail_url, ['timeout' => 25]);
            if (is_wp_error($detail_response) || wp_remote_retrieve_response_code($detail_response) !== 200) { WP_CLI::warning('Could not retrieve details for ' . $game['name']); continue; }
            $detail = json_decode(wp_remote_retrieve_body($detail_response), true);
            if (!is_array($detail)) { continue; }
            $description = trim(wp_strip_all_tags((string) ($detail['description'] ?? '')));
            if (mb_strlen($description) < 180) { WP_CLI::warning('Skipping ' . $game['name'] . ': too little source material.'); continue; }
            $genres = implode(', ', array_map(static function ($g) { return sanitize_text_field((string) ($g['name'] ?? '')); }, (array) ($detail['genres'] ?? [])));
            $developers = implode(', ', array_map(static function ($d) { return sanitize_text_field((string) ($d['name'] ?? '')); }, (array) ($detail['developers'] ?? [])));
            $source_url = 'https://rawg.io/games/' . rawurlencode((string) ($detail['slug'] ?? $game['id']));
            $brief = "Game: {$game['name']}\nReported release date (verify by region): {$game['date']}\nGenres: {$genres}\nDevelopers: {$developers}\nRAWG summary: {$description}\nMetadata source: {$source_url}";
            $system = 'You are a careful independent video game journalist. Write a concise, original, fact-focused upcoming-game PREVIEW in ' . $language . ' as clean WordPress HTML (p, h2, h3, ul, li). Treat RAWG as unverified metadata, not independent reporting. Do not claim to have played the game, reviewed it, seen trailers, checked official sources, or interviewed anyone. Never invent gameplay, quotes, platforms, dates, sources, or claims. Clearly identify uncertainties and avoid generic filler. If information is thin, write a short factual overview rather than a long article. Do not include fabricated citations. Include a short Sources section linking to the provided RAWG URL, identified as metadata only. No markdown fences.';
            $prompt = "Create a useful upcoming game preview from only the following supplied metadata. Avoid claims not supported by it.\n\n" . $brief;
            $api = wp_remote_post('https://api.openai.com/v1/chat/completions', ['timeout' => 100, 'headers' => ['Authorization' => 'Bearer ' . $openai_key, 'Content-Type' => 'application/json'], 'body' => wp_json_encode(['model' => $settings['model'] ?? 'gpt-4.1-mini', 'messages' => [['role' => 'system', 'content' => $system], ['role' => 'user', 'content' => $prompt]], 'temperature' => 0.3])]);
            if (is_wp_error($api)) { WP_CLI::warning('OpenAI failed for ' . $game['name'] . ': ' . $api->get_error_message()); continue; }
            $result = json_decode(wp_remote_retrieve_body($api), true);
            $content = $result['choices'][0]['message']['content'] ?? '';
            if (wp_remote_retrieve_response_code($api) !== 200 || !is_string($content) || trim($content) === '') { WP_CLI::warning('OpenAI did not return an article for ' . $game['name'] . ': ' . sanitize_text_field((string) ($result['error']['message'] ?? 'unknown API error'))); continue; }
            $post_id = wp_insert_post(['post_type' => 'post', 'post_status' => 'draft', 'post_author' => $author, 'post_title' => $game['name'] . ' – Preview', 'post_content' => wp_kses_post($content), 'meta_input' => ['_gds_studio' => 'editorial', '_gds_kind' => 'preview', '_gds_game' => $game['name'], '_gds_rawg_game_id' => (string) $game['id'], '_gds_rawg_source' => $source_url]], true);
            if (is_wp_error($post_id)) { WP_CLI::warning('WordPress draft failed: ' . $post_id->get_error_message()); continue; }
            $created++;
            WP_CLI::log('Draft #' . $post_id . ': ' . $game['name']);
        }
        WP_CLI::success('Created ' . $created . ' game preview drafts.');
    }
}
