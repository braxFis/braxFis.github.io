<?php
if (!defined('ABSPATH')) { exit; }

class GDS_Admin {
    private const OPTION = 'gds_settings';
    private const MODEL = 'gpt-4.1-mini';

    public function __construct() {
        add_action('admin_menu', [$this, 'menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_post_gds_generate', [$this, 'generate']);
        add_action('wp_ajax_gds_fetch_missions', [$this, 'fetch_missions']);
        add_action('wp_ajax_gds_batch_generate', [$this, 'batch_generate']);
    }

    public function menu(): void {
        add_menu_page('GameDefiners Studio', 'GD Studio', 'edit_posts', 'gds-guides', [$this, 'guides_page'], 'dashicons-games', 26);
        add_submenu_page('gds-guides', 'Guides Studio', 'Guides Studio', 'edit_posts', 'gds-guides', [$this, 'guides_page']);
        add_submenu_page('gds-guides', 'Inställningar', 'Inställningar', 'manage_options', 'gds-settings', [$this, 'settings_page']);
    }

    public function register_settings(): void {
        register_setting('gds_settings_group', self::OPTION, [
            'type' => 'array',
            'sanitize_callback' => function ($input) {
                $old = get_option(self::OPTION, []);
                $result = is_array($old) ? $old : [];
                if (!is_array($input)) { return $result; }
                if (isset($input['api_key'])) {
                    $key = trim((string) $input['api_key']);
                    if ($key !== '') { $result['api_key'] = preg_replace('/\s+/', '', $key); }
                }
                if (isset($input['model'])) {
                    $model = sanitize_text_field($input['model']);
                    if (preg_match('/^[a-zA-Z0-9._-]{1,80}$/', $model)) { $result['model'] = $model; }
                }
                return $result;
            },
            'default' => [],
        ]);
    }

    private function settings(): array {
        $v = get_option(self::OPTION, []);
        return is_array($v) ? $v : [];
    }

    private function api_key(): string {
        if (defined('GDS_OPENAI_API_KEY') && GDS_OPENAI_API_KEY) { return (string) GDS_OPENAI_API_KEY; }
        return (string) ($this->settings()['api_key'] ?? '');
    }

    private function header(string $title): void {
        echo '<div class="wrap"><h1>' . esc_html($title) . '</h1>';
        $status = isset($_GET['gds_status']) ? sanitize_key(wp_unslash($_GET['gds_status'])) : '';
        $messages = [
            'missing' => 'Fyll i spel och ämne/uppdrag.',
            'no_key' => 'Lägg till en OpenAI API-nyckel under Inställningar.',
            'failed' => 'Genereringen misslyckades. Kontrollera API-nyckel, saldo och modell. Se detaljer nedan.',
            'saved' => 'Utkastet har skapats!',
        ];
        if (isset($messages[$status])) {
            $class = $status === 'saved' ? 'notice-success' : 'notice-error';
            echo '<div class="notice ' . esc_attr($class) . '"><p>' . esc_html($messages[$status]) . '</p></div>';
        }
        if ($status === 'failed') {
            $detail = get_transient('gds_error_' . get_current_user_id());
            if ($detail) { echo '<div class="notice notice-warning"><p>' . esc_html($detail) . '</p></div>'; delete_transient('gds_error_' . get_current_user_id()); }
        }
        if ($status === 'saved' && isset($_GET['post_id'])) {
            $id = absint($_GET['post_id']);
            if ($id && current_user_can('edit_post', $id)) {
                echo '<p><a class="button button-primary" href="' . esc_url(get_edit_post_link($id)) . '">Öppna utkastet</a></p>';
            }
        }
    }

    private function form(string $type): void {
        $guide = $type === 'guide';
        echo '<p>' . esc_html($guide ? 'Skapa en uppdragsguide eller walkthrough som WordPress-utkast. Granska alla steg mot spelet innan publicering.' : 'Skapa en redaktionell artikel som WordPress-utkast. Verifiera datum, påståenden och källor innan publicering.') . '</p>';
        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        wp_nonce_field('gds_generate', 'gds_nonce');
        echo '<input type="hidden" name="action" value="gds_generate"><input type="hidden" name="gds_type" value="' . esc_attr($type) . '">';
        echo '<table class="form-table" role="presentation">';
        $this->field('Spel', 'gds_game', 'text', 'Exempel: Kingdom Come: Deliverance II', true);
        if ($guide) {
            echo '<tr><th scope="row"><label for="gds_topic">Uppdrag eller kapitel</label></th><td>';
            echo '<select id="gds_topic" name="gds_topic" required disabled><option value="">Ange spel och klicka på Hämta uppdrag</option></select> ';
            echo '<button type="button" class="button" id="gds_fetch_missions">Hämta uppdrag</button>';
            echo '<p id="gds_missions_status" class="description" aria-live="polite">Uppdragslistan föreslås av AI och är inte automatiskt verifierad eller garanterat komplett.</p>';
            echo '</td></tr>';
        } else {
            $this->field('Ämne / artikelvinkel', 'gds_topic', 'text', 'Exempel: Vad vet vi om nästa uppdatering?', true);
        }
        echo '<tr><th scope="row"><label for="gds_kind">' . ($guide ? 'Guidetyp' : 'Artikeltyp') . '</label></th><td><select id="gds_kind" name="gds_kind">';
        $kinds = $guide ? ['walkthrough' => 'Walkthrough', 'boss' => 'Bossguide', 'collectibles' => 'Collectibles', 'tips' => 'Tips och strategi'] : ['preview' => 'Preview', 'news' => 'Nyhet', 'feature' => 'Analys / feature', 'list' => 'Topplista'];
        foreach ($kinds as $key => $label) { echo '<option value="' . esc_attr($key) . '">' . esc_html($label) . '</option>'; }
        echo '</select></td></tr>';
        echo '<tr><th scope="row"><label for="gds_notes">Egna anteckningar / verifierade fakta</label></th><td><textarea id="gds_notes" name="gds_notes" rows="8" class="large-text" maxlength="15000" placeholder="Klistra in egna spelanteckningar, bekräftade steg, källor och andra fakta här."></textarea><p class="description">Ju bättre faktaunderlag, desto mer tillförlitligt utkast. AI:n har inte automatisk tillgång till aktuella webbkällor.</p></td></tr>';
        echo '<tr><th scope="row"><label for="gds_language">Språk</label></th><td><select id="gds_language" name="gds_language"><option value="en">Engelska</option><option value="sv">Svenska</option></select></td></tr>';
        echo '</table>';
        if ($guide) {
            echo '<p><button type="button" class="button button-primary" id="gds_generate_all" disabled>Generera alla uppdrag som utkast</button> <button type="button" class="button" id="gds_stop_batch" hidden>Stoppa efter aktuellt uppdrag</button></p>';
            echo '<p id="gds_batch_status" role="status" aria-live="polite"></p><progress id="gds_batch_progress" max="100" value="0" style="width:360px;max-width:100%;display:none"></progress><ol id="gds_batch_results"></ol>';
        }
        submit_button('Generera WordPress-utkast');
        echo '</form>';
        if ($guide) { $this->mission_script(); }
        echo '</div>';
    }

    private function mission_script(): void {
        $ajax_url = wp_json_encode(admin_url('admin-ajax.php'));
        $nonce = wp_json_encode(wp_create_nonce('gds_fetch_missions'));
        $batch_nonce = wp_json_encode(wp_create_nonce('gds_batch_generate'));
        echo '<script>
        (function() {
            const game = document.getElementById("gds_game");
            const select = document.getElementById("gds_topic");
            const button = document.getElementById("gds_fetch_missions");
            const status = document.getElementById("gds_missions_status");
            const ajaxUrl = ' . $ajax_url . ';
            const nonce = ' . $nonce . ';
            const batchNonce = ' . $batch_nonce . ';
            const allButton = document.getElementById("gds_generate_all");
            const stopButton = document.getElementById("gds_stop_batch");
            const batchStatus = document.getElementById("gds_batch_status");
            const progress = document.getElementById("gds_batch_progress");
            const results = document.getElementById("gds_batch_results");
            const form = game.closest("form");
            let running = false;
            let stopRequested = false;
            function reset(message) {
                select.replaceChildren(new Option(message, ""));
                select.disabled = true;
                allButton.disabled = true;
            }
            game.addEventListener("input", function() {
                reset("Hämta uppdrag för det här spelet");
            });
            button.addEventListener("click", async function() {
                const name = game.value.trim();
                if (!name) { status.textContent = "Ange ett spel först."; game.focus(); return; }
                button.disabled = true;
                reset("Hämtar uppdrag...");
                status.textContent = "Hämtar uppdragsförslag. Det kan ta en stund...";
                const params = new URLSearchParams({action:"gds_fetch_missions", nonce:nonce, game:name});
                try {
                    const response = await fetch(ajaxUrl, {method:"POST", credentials:"same-origin", headers:{"Content-Type":"application/x-www-form-urlencoded;charset=UTF-8"}, body:params.toString()});
                    const data = await response.json();
                    if (!response.ok || !data.success || !Array.isArray(data.data.missions) || !data.data.missions.length) {
                        throw new Error(data.data && data.data.message ? data.data.message : "Kunde inte hämta uppdrag.");
                    }
                    if (game.value.trim() !== name) { reset("Spelet har ändrats – hämta uppdrag igen"); return; }
                    select.replaceChildren(new Option("Välj uppdrag eller kapitel", ""));
                    data.data.missions.forEach(function(mission) { select.add(new Option(mission, mission)); });
                    select.disabled = false;
                    allButton.disabled = false;
                    status.textContent = data.data.missions.length + " uppdragsförslag hittades. AI-listan kan vara ofullständig eller felaktig – kontrollera mot spelet.";
                } catch (error) {
                    reset("Kunde inte hämta uppdrag");
                    status.textContent = error.message || "Ett fel uppstod.";
                } finally { button.disabled = false; }
            });
            stopButton.addEventListener("click", function() { stopRequested = true; stopButton.disabled = true; batchStatus.textContent = "Stoppar efter pågående uppdrag..."; });
            allButton.addEventListener("click", async function() {
                if (running || select.disabled) return;
                const missions = Array.from(select.options).map(o => o.value).filter(Boolean);
                const gameName = game.value.trim();
                if (!missions.length || !gameName) return;
                if (!window.confirm("Skapa upp till " + missions.length + " separata WordPress-utkast för " + gameName + "? Detta använder ett OpenAI API-anrop per uppdrag och kan kosta pengar.")) return;
                running = true; stopRequested = false;
                allButton.disabled = true; button.disabled = true; game.disabled = true;
                stopButton.hidden = false; stopButton.disabled = false;
                results.replaceChildren(); progress.style.display = "block"; progress.max = missions.length; progress.value = 0;
                let created = 0, skipped = 0, failed = 0;
                const kind = document.getElementById("gds_kind").value;
                const notes = document.getElementById("gds_notes").value;
                const language = document.getElementById("gds_language").value;
                for (let i = 0; i < missions.length; i++) {
                    if (stopRequested) break;
                    const mission = missions[i];
                    batchStatus.textContent = "Bearbetar " + (i + 1) + " av " + missions.length + ": " + mission;
                    const params = new URLSearchParams({action:"gds_batch_generate", nonce:batchNonce, game:gameName, mission:mission, kind:kind, notes:notes, language:language});
                    try {
                        const response = await fetch(ajaxUrl, {method:"POST", credentials:"same-origin", headers:{"Content-Type":"application/x-www-form-urlencoded;charset=UTF-8"}, body:params.toString()});
                        const data = await response.json();
                        if (!response.ok || !data.success) throw new Error(data.data && data.data.message ? data.data.message : "Ett okänt fel uppstod.");
                        const item = document.createElement("li");
                        if (data.data.skipped) { skipped++; item.textContent = mission + " – redan skapat, hoppades över"; }
                        else { created++; const link = document.createElement("a"); link.href = data.data.edit_url; link.textContent = mission + " – öppna utkast"; item.appendChild(link); }
                        results.appendChild(item);
                    } catch (error) {
                        failed++;
                        const item = document.createElement("li"); item.textContent = mission + " – FEL: " + (error.message || "Okänt fel"); results.appendChild(item);
                        stopRequested = true;
                    }
                    progress.value = i + 1;
                }
                batchStatus.textContent = "Klart. Skapade: " + created + ", redan befintliga: " + skipped + ", fel: " + failed + (stopRequested ? ". Körningen stoppades; du kan starta igen utan att duplicera färdiga utkast." : ". Granska alla utkast innan publicering.");
                running = false; game.disabled = false; button.disabled = false; allButton.disabled = false;
                stopButton.hidden = true;
            });
        })();
        </script>';
    }

    public function fetch_missions(): void {
        if (!current_user_can('edit_posts')) { wp_send_json_error(['message' => 'Åtkomst nekad.'], 403); }
        check_ajax_referer('gds_fetch_missions', 'nonce');
        $game = sanitize_text_field(wp_unslash($_POST['game'] ?? ''));
        if ($game === '' || mb_strlen($game) > 200) { wp_send_json_error(['message' => 'Ange ett giltigt spelnamn.'], 400); }
        $key = $this->api_key();
        if ($key === '') { wp_send_json_error(['message' => 'Lägg till din OpenAI API-nyckel under Inställningar.'], 400); }
        $cache_key = 'gds_missions_' . md5(strtolower($game));
        $cached = get_transient($cache_key);
        if (is_array($cached) && $cached) { wp_send_json_success(['missions' => $cached]); }
        $settings = $this->settings();
        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', [
            'timeout' => 90,
            'headers' => ['Authorization' => 'Bearer ' . $key, 'Content-Type' => 'application/json'],
            'body' => wp_json_encode([
                'model' => $settings['model'] ?? self::MODEL,
                'messages' => [
                    ['role' => 'system', 'content' => 'You suggest known video game mission or chapter names. Return ONLY a valid JSON array of strings in the original game language, in story order when possible. Include main story missions/chapters, not side quests. Never invent names. If uncertain or you cannot identify the game, return []. No markdown or commentary. The list may be incomplete.'],
                    ['role' => 'user', 'content' => 'List the main story missions or chapters for the video game: ' . $game],
                ],
                'temperature' => 0,
            ]),
        ]);
        if (is_wp_error($response)) { wp_send_json_error(['message' => $response->get_error_message()], 502); }
        $body = json_decode(wp_remote_retrieve_body($response), true);
        $code = wp_remote_retrieve_response_code($response);
        if ($code < 200 || $code >= 300) {
            $message = $body['error']['message'] ?? 'API-fel (HTTP ' . $code . ').';
            wp_send_json_error(['message' => sanitize_text_field((string) $message)], 502);
        }
        $content = trim((string) ($body['choices'][0]['message']['content'] ?? ''));
        $missions = json_decode($content, true);
        if (!is_array($missions) || !array_is_list($missions)) {
            wp_send_json_error(['message' => 'AI:n returnerade ingen giltig uppdragslista. Försök igen.'], 502);
        }
        $missions = array_values(array_unique(array_filter(array_map(static function ($item) {
            return is_string($item) ? sanitize_text_field(mb_substr($item, 0, 200)) : '';
        }, array_slice($missions, 0, 150)))));
        if (!$missions) { wp_send_json_error(['message' => 'Inga säkra uppdragsnamn kunde föreslås för det här spelet.'], 404); }
        set_transient($cache_key, $missions, DAY_IN_SECONDS);
        wp_send_json_success(['missions' => $missions]);
    }

    public function batch_generate(): void {
        if (!current_user_can('edit_posts')) { wp_send_json_error(['message' => 'Åtkomst nekad.'], 403); }
        check_ajax_referer('gds_batch_generate', 'nonce');
        $game = sanitize_text_field(wp_unslash($_POST['game'] ?? ''));
        $mission = sanitize_text_field(wp_unslash($_POST['mission'] ?? ''));
        $kind = sanitize_key(wp_unslash($_POST['kind'] ?? 'walkthrough'));
        $notes = sanitize_textarea_field(wp_unslash($_POST['notes'] ?? ''));
        $language = isset($_POST['language']) && $_POST['language'] === 'sv' ? 'Swedish' : 'English';
        if ($game === '' || $mission === '' || mb_strlen($game) > 200 || mb_strlen($mission) > 200 || strlen($notes) > 20000) { wp_send_json_error(['message' => 'Ogiltig inmatning.'], 400); }
        if (!in_array($kind, ['walkthrough', 'boss', 'collectibles', 'tips'], true)) { $kind = 'walkthrough'; }
        $missions = get_transient('gds_missions_' . md5(strtolower($game)));
        if (!is_array($missions) || !in_array($mission, $missions, true)) { wp_send_json_error(['message' => 'Uppdragslistan har gått ut eller uppdraget saknas. Hämta uppdrag igen.'], 400); }
        $existing = get_posts(['post_type' => 'post', 'post_status' => ['draft', 'pending', 'future', 'publish', 'private'], 'author' => get_current_user_id(), 'meta_query' => [
            ['key' => '_gds_studio', 'value' => 'guide'], ['key' => '_gds_game', 'value' => $game], ['key' => '_gds_mission', 'value' => $mission],
        ], 'fields' => 'ids', 'posts_per_page' => 1]);
        if ($existing) { wp_send_json_success(['skipped' => true]); }
        $key = $this->api_key();
        if (!$key) { wp_send_json_error(['message' => 'OpenAI API-nyckel saknas.'], 400); }
        $settings = $this->settings();
        $system = 'You are a careful video game guide writer. Write in ' . $language . '. Produce a substantial, well-structured HTML WordPress guide (h2, h3, p, ul, ol, li only). Never invent quest steps, characters, locations, mechanics, sources or personal play experience. Provide numbered actionable steps only when supported by verified notes. Mark unsupported details [VERIFY: ...]. Do not pretend to have browsed the web. No fake citations or surrounding explanation.';
        $prompt = "Game: {$game}\nGuide type: {$kind}\nMission: {$mission}\nVerified notes (may be empty):\n{$notes}\n\nWrite a useful draft, explicitly marking steps that need verification. Do not fabricate gameplay progression.";
        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', [
            'timeout' => 100,
            'headers' => ['Authorization' => 'Bearer ' . $key, 'Content-Type' => 'application/json'],
            'body' => wp_json_encode(['model' => $settings['model'] ?? self::MODEL, 'messages' => [['role' => 'system', 'content' => $system], ['role' => 'user', 'content' => $prompt]], 'temperature' => 0.4]),
        ]);
        if (is_wp_error($response)) { wp_send_json_error(['message' => $response->get_error_message()], 502); }
        $code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);
        $content = $body['choices'][0]['message']['content'] ?? '';
        if ($code < 200 || $code >= 300 || !is_string($content) || trim($content) === '') {
            wp_send_json_error(['message' => sanitize_text_field((string) ($body['error']['message'] ?? 'Tomt svar från OpenAI (HTTP ' . $code . ').'))], 502);
        }
        $post_id = wp_insert_post([
            'post_title' => $game . ': ' . $mission,
            'post_content' => wp_kses_post($content),
            'post_status' => 'draft', 'post_type' => 'post', 'post_author' => get_current_user_id(),
            'meta_input' => ['_gds_studio' => 'guide', '_gds_kind' => $kind, '_gds_game' => $game, '_gds_mission' => $mission],
        ], true);
        if (is_wp_error($post_id)) { wp_send_json_error(['message' => $post_id->get_error_message()], 500); }
        wp_send_json_success(['skipped' => false, 'post_id' => $post_id, 'edit_url' => get_edit_post_link($post_id, 'raw')]);
    }

    private function field(string $label, string $name, string $type, string $placeholder, bool $required): void {
        echo '<tr><th scope="row"><label for="' . esc_attr($name) . '">' . esc_html($label) . '</label></th><td><input class="regular-text" type="' . esc_attr($type) . '" id="' . esc_attr($name) . '" name="' . esc_attr($name) . '" placeholder="' . esc_attr($placeholder) . '" maxlength="200" ' . ($required ? 'required' : '') . '></td></tr>';
    }

    public function guides_page(): void { $this->header('Guides Studio'); $this->form('guide'); }
    public function editorial_page(): void { $this->header('Editorial Studio'); $this->form('editorial'); }

    public function settings_page(): void {
        if (!current_user_can('manage_options')) { wp_die('Åtkomst nekad.'); }
        $settings = $this->settings();
        $this->header('GameDefiners Studio – Inställningar');
        echo '<p>OpenAI API-fakturering är separat från ChatGPT. Nyckeln skickas endast från servern. För extra säkerhet kan du definiera <code>GDS_OPENAI_API_KEY</code> i <code>wp-config.php</code>.</p>';
        echo '<form action="options.php" method="post">';
        settings_fields('gds_settings_group');
        echo '<table class="form-table"><tr><th scope="row"><label for="gds_api_key">OpenAI API-nyckel</label></th><td><input id="gds_api_key" type="password" class="regular-text" name="' . esc_attr(self::OPTION) . '[api_key]" value="" autocomplete="new-password" placeholder="Lämna tomt för att behålla befintlig nyckel"><p class="description">' . ($this->api_key() ? 'En nyckel är konfigurerad.' : 'Ingen nyckel konfigurerad.') . '</p></td></tr>';
        echo '<tr><th scope="row"><label for="gds_model">OpenAI-modell</label></th><td><input id="gds_model" class="regular-text" name="' . esc_attr(self::OPTION) . '[model]" value="' . esc_attr($settings['model'] ?? self::MODEL) . '"><p class="description">Standard: ' . esc_html(self::MODEL) . '. Modellen måste vara tillgänglig för ditt API-konto.</p></td></tr></table>';
        submit_button('Spara inställningar');
        echo '</form></div>';
    }

    private function redirect(string $type, string $status, int $post_id = 0): void {
        $page = $type === 'guide' ? 'gds-guides' : 'gds-editorial';
        $args = ['page' => $page, 'gds_status' => $status];
        if ($post_id) { $args['post_id'] = $post_id; }
        wp_safe_redirect(add_query_arg($args, admin_url('admin.php')));
        exit;
    }

    public function generate(): void {
        if (!current_user_can('edit_posts')) { wp_die('Åtkomst nekad.', '', ['response' => 403]); }
        check_admin_referer('gds_generate', 'gds_nonce');
        $type = isset($_POST['gds_type']) && sanitize_key(wp_unslash($_POST['gds_type'])) === 'editorial' ? 'editorial' : 'guide';
        $game = sanitize_text_field(wp_unslash($_POST['gds_game'] ?? ''));
        $topic = sanitize_text_field(wp_unslash($_POST['gds_topic'] ?? ''));
        $notes = sanitize_textarea_field(wp_unslash($_POST['gds_notes'] ?? ''));
        $kind = sanitize_key(wp_unslash($_POST['gds_kind'] ?? ''));
        $language = isset($_POST['gds_language']) && $_POST['gds_language'] === 'sv' ? 'Swedish' : 'English';
        $allowed = $type === 'guide' ? ['walkthrough', 'boss', 'collectibles', 'tips'] : ['preview', 'news', 'feature', 'list'];
        if (!in_array($kind, $allowed, true)) { $kind = $allowed[0]; }
        if ($game === '' || $topic === '') { $this->redirect($type, 'missing'); }
        $key = $this->api_key();
        if ($key === '') { $this->redirect($type, 'no_key'); }
        $settings = $this->settings();
        $model = $settings['model'] ?? self::MODEL;
        $system = 'You are a careful video game editorial writer. Write in ' . $language . '. Produce a substantial, well-structured article in clean HTML suitable for a WordPress post (h2, h3, p, ul, ol, li; no html/head/body tags). Never invent release dates, quest steps, characters, locations, game mechanics, sources, personal play experience, or quotations. If facts are missing or uncertain, clearly mark [VERIFY: ...] rather than guess. Do not pretend you browsed the web. Do not include fake citations. No introductory explanation outside the article.';
        if ($type === 'guide') {
            $system .= ' For guides, provide numbered actionable steps only when supported by supplied verified notes. Clearly separate confirmed steps from anything requiring verification. Never fabricate walkthrough progression.';
        } else {
            $system .= ' For previews and news, separate confirmed facts from speculation. Never present a review as first-hand experience without supplied play notes.';
        }
        $prompt = "Game: {$game}\nContent type: {$kind}\nTopic or mission: {$topic}\nVerified source material / author notes (may be empty):\n{$notes}\n\nCreate an engaging, specific, practical draft. Use [VERIFY: ...] for unverified details. Do not make up gameplay instructions to fill gaps.";
        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', [
            'timeout' => 100,
            'headers' => ['Authorization' => 'Bearer ' . $key, 'Content-Type' => 'application/json'],
            'body' => wp_json_encode([
                'model' => $model,
                'messages' => [['role' => 'system', 'content' => $system], ['role' => 'user', 'content' => $prompt]],
                'temperature' => 0.4,
            ]),
        ]);
        if (is_wp_error($response)) {
            set_transient('gds_error_' . get_current_user_id(), $response->get_error_message(), 120);
            $this->redirect($type, 'failed');
        }
        $code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);
        $content = $body['choices'][0]['message']['content'] ?? '';
        if ($code < 200 || $code >= 300 || !is_string($content) || trim($content) === '') {
            $detail = $body['error']['message'] ?? 'API returnerade HTTP ' . $code . ' eller tomt innehåll.';
            set_transient('gds_error_' . get_current_user_id(), sanitize_text_field((string) $detail), 120);
            $this->redirect($type, 'failed');
        }
        $title = $game . ': ' . $topic;
        $post_id = wp_insert_post([
            'post_title' => $title,
            'post_content' => wp_kses_post($content),
            'post_status' => 'draft',
            'post_type' => 'post',
            'post_author' => get_current_user_id(),
            'meta_input' => ['_gds_studio' => $type, '_gds_kind' => $kind, '_gds_game' => $game],
        ], true);
        if (is_wp_error($post_id)) {
            set_transient('gds_error_' . get_current_user_id(), $post_id->get_error_message(), 120);
            $this->redirect($type, 'failed');
        }
        $this->redirect($type, 'saved', (int) $post_id);
    }
}
