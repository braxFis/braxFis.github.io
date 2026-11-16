<?php
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }

/** Create editorial release leads from RAWG, TMDB, demo or JSON data. */
class GDES_Release_Scan {
    /**
     * Scan upcoming releases and create WordPress draft leads.
     *
     * ## OPTIONS
     *
     * [--days=<days>]
     * : Look-ahead period, 1-365 days. Default: 90.
     *
     * [--demo]
     * : Use three fictional releases.
     *
     * [--source=<source>]
     * : rawg, tmdb, demo, or file. Default: rawg.
     *
     * [--file=<path>]
     * : Read a JSON array of releases from a local file.
     *
     * [--min-popularity=<number>]
     * : Minimum TMDB popularity score. Default: 10. TMDB scores change over time.
     *
     * [--max=<number>]
     * : Maximum new TMDB draft leads per scan. Default: 10, maximum: 50.
     *
     * [--mode=<mode>]
     * : mainstream (default), indie or all. TMDB only.
     *
     * [--leads-only]
     * : Create basic leads rather than AI-written drafts (TMDB only).
     *
     * [--dry-run]
     * : Preview selections without creating posts.
     *
     * [--author=<id>]
     * : WordPress author with edit_posts permission.
     */
    public function __invoke( $args, $options ) {
        $days = isset($options['days']) ? (int)$options['days'] : 90;
        if ($days < 1 || $days > 365) WP_CLI::error('Use --days between 1 and 365.');
        $author = isset($options['author']) ? absint($options['author']) : 1;
        if (!$author || !get_user_by('id',$author) || !user_can($author,'edit_posts')) {
            WP_CLI::error('Use --author with a user permitted to edit posts.');
        }
        $dry_run = isset($options['dry-run']);
        $max = isset($options['max']) ? (int)$options['max'] : 10;
        if ($max < 1 || $max > 50) WP_CLI::error('Use --max between 1 and 50.');
        $mode = sanitize_key($options['mode'] ?? 'mainstream');
        if (!in_array($mode, array('mainstream','indie','all'), true)) WP_CLI::error('Use --mode=mainstream, indie or all.');
        $min_popularity = isset($options['min-popularity']) ? (float)$options['min-popularity'] : ($mode === 'indie' ? 1.0 : ($mode === 'all' ? 0.0 : 7.0));
        $max_popularity = $mode === 'indie' ? 7.0 : INF;
        $ai_enabled = !isset($options['leads-only']);
        if (!is_finite($min_popularity) || $min_popularity < 0) WP_CLI::error('Invalid --min-popularity.');
        $today = new DateTimeImmutable('today', wp_timezone());
        $end = $today->modify('+' . $days . ' days');
        $source = isset($options['demo']) ? 'demo' : (isset($options['file']) ? 'file' : ($options['source'] ?? 'rawg'));
        if ($source === 'demo') {
            $releases = array(
                array('source'=>'demo','id'=>'game-001','title'=>'Example Game','category'=>'game','release_date'=>$today->modify('+14 days')->format('Y-m-d')),
                array('source'=>'demo','id'=>'film-001','title'=>'Example Film','category'=>'film','release_date'=>$today->modify('+40 days')->format('Y-m-d')),
                array('source'=>'demo','id'=>'album-001','title'=>'Example Album','category'=>'music','release_date'=>$today->modify('+120 days')->format('Y-m-d')),
            );
        } elseif ($source === 'file') {
            $path = (string)($options['file'] ?? '');
            if (!$path || !is_readable($path)) WP_CLI::error('JSON file not readable.');
            $releases = json_decode(file_get_contents($path),true);
            if (!is_array($releases) || !array_is_list($releases)) WP_CLI::error('JSON must be an array.');
        } elseif ($source === 'tmdb') {
            $releases = array();
            // Sort by TMDB popularity to prioritize movies with broader audience interest.
            for ($page = 1; $page <= 5; $page++) {
                $payload = GDES_TMDB::request('/discover/movie', array(
                    'primary_release_date.gte'=>$today->format('Y-m-d'),
                    'primary_release_date.lte'=>$end->format('Y-m-d'),
                    'sort_by'=>'popularity.desc', 'include_adult'=>'false', 'page'=>$page,
                ));
                if (is_wp_error($payload)) WP_CLI::error($payload->get_error_message());
                foreach ($payload['results'] as $movie) {
                    if (empty($movie['id']) || empty($movie['title']) || empty($movie['release_date'])) continue;
                    if ((float)($movie['popularity'] ?? 0) < $min_popularity || (float)($movie['popularity'] ?? 0) > $max_popularity) continue;
                    // A useful synopsis helps distinguish editorial leads from empty listings.
                    if (empty(trim((string)($movie['overview'] ?? '')))) continue;
                    $releases[] = array(
                        'popularity'=>(float)($movie['popularity'] ?? 0),
                        'vote_average'=>(float)($movie['vote_average'] ?? 0),
                        'vote_count'=>(int)($movie['vote_count'] ?? 0),
                        'source'=>'tmdb',
                        'id'=>(string)$movie['id'],
                        'title'=>$movie['title'],
                        'overview'=>sanitize_textarea_field((string)$movie['overview']),
                        'category'=>'film',
                        'release_date'=>$movie['release_date'],
                        'source_url'=>'https://www.themoviedb.org/movie/'.rawurlencode((string)$movie['id']),
                    );
                }
                if ($mode === 'indie' && $page === 1) {
                    // Browse the less popular portion of the result set, rather than top-ranked blockbusters.
                    $total = min(500, max(1, (int)($payload['total_pages'] ?? 1)));
                    $page = max(1, (int)floor($total * 0.65) - 1);
                }
                if ($page >= (int)($payload['total_pages'] ?? 1)) break;
            }
        } elseif ($source === 'rawg') {
            // Set RAWG_API_KEY as a server environment variable or wp-config.php constant.
            $key = defined('RAWG_API_KEY') ? RAWG_API_KEY : getenv('RAWG_API_KEY');
            if (!$key) WP_CLI::error('RAWG_API_KEY missing. Set it in the environment or wp-config.php.');
            $releases = array();
            for ($page = 1; $page <= 3; $page++) {
                $url = add_query_arg(array(
                    'key'=>$key,
                    'dates'=>$today->format('Y-m-d').','.$end->format('Y-m-d'),
                    'ordering'=>'released','page_size'=>40,'page'=>$page,
                ), 'https://api.rawg.io/api/games');
                $response = wp_remote_get($url,array('timeout'=>18,'headers'=>array('Accept'=>'application/json')));
                if (is_wp_error($response)) WP_CLI::error('RAWG unavailable: '.$response->get_error_message());
                $status = wp_remote_retrieve_response_code($response);
                if ($status !== 200) WP_CLI::error('RAWG returned HTTP '.$status.'; no drafts created.');
                $payload = json_decode(wp_remote_retrieve_body($response),true);
                if (!is_array($payload) || !isset($payload['results']) || !is_array($payload['results'])) {
                    WP_CLI::error('RAWG returned invalid response.');
                }
                foreach ($payload['results'] as $game) {
                    if (empty($game['id']) || empty($game['name']) || empty($game['released'])) continue;
                    $releases[] = array(
                        'source'=>'rawg','id'=>(string)$game['id'],
                        'title'=>$game['name'],'category'=>'game',
                        'release_date'=>$game['released'],
                        'source_url'=>!empty($game['slug']) ? 'https://rawg.io/games/'.rawurlencode($game['slug']) : '',
                    );
                }
                if (empty($payload['next'])) break;
            }
        } else {
            WP_CLI::error('Unknown source. Choose rawg, tmdb, demo or file.');
        }
        if ($source === 'tmdb') {
            usort($releases, static function($a,$b) use ($mode) {
                return ($b['popularity'] ?? 0) <=> ($a['popularity'] ?? 0);
            });
            WP_CLI::log('TMDB candidates passing filters: '.count($releases));
        }
        $created=0; $skipped=0; $failed=0;
        foreach ($releases as $item) {
            if ($source === 'tmdb' && $created >= $max) break;
            if (!is_array($item)) { $skipped++; continue; }
            $item_source=sanitize_key($item['source'] ?? '');
            $id=sanitize_text_field((string)($item['id'] ?? ''));
            $title=sanitize_text_field((string)($item['title'] ?? ''));
            $category=sanitize_key($item['category'] ?? '');
            $date_string=(string)($item['release_date'] ?? '');
            $url=esc_url_raw((string)($item['source_url'] ?? ''));
            if (!$item_source || !$id || !$title || !in_array($category,array('game','film','music'),true) ||
                !preg_match('/^\\d{4}-\\d{2}-\\d{2}$/D',$date_string)) { $skipped++; continue; }
            $date=DateTimeImmutable::createFromFormat('!Y-m-d',$date_string,wp_timezone());
            if (!$date || $date->format('Y-m-d')!==$date_string || $date<$today || $date>$end) {
                $skipped++; continue;
            }
            $key=hash('sha256',$item_source.':'.$category.':'.$id);
            $existing=get_posts(array('post_type'=>'post','post_status'=>'any','numberposts'=>1,
                'fields'=>'ids','meta_key'=>'_gdes_release_key','meta_value'=>$key));
            if ($existing) { $skipped++; continue; }
            if ($dry_run) {
                WP_CLI::log('WOULD CREATE: '.$title.' | '.$date_string.' | '.$category.
                    ($item_source === 'tmdb' ? ' | popularity '.number_format((float)($item['popularity'] ?? 0),1) : ''));
                $created++;
                continue;
            }
            if ($item_source === 'tmdb' && $ai_enabled) {
                $facts = "TMDB movie metadata (NOT independently verified):\n";
                $facts .= "Title: {$title}\nRelease date (TMDB primary, region not confirmed): {$date_string}\n";
                $facts .= "Synopsis (TMDB metadata): " . sanitize_textarea_field((string)($item['overview'] ?? '')) . "\n";
                $facts .= "TMDB listing: {$url}\n";
                $facts .= "Popularity score: " . (float)($item['popularity'] ?? 0) . " (discovery metric, not a rating)\n";
                $payload = array(
                    'title' => $title . ' — What We Know So Far',
                    'article_type' => 'preview', 'reporter_role' => 'movie', 'language' => 'en', 'length' => 'short',
                    'tone' => 'Professional, concise, fact-focused entertainment journalism',
                    'brief' => 'Write a film preview for an upcoming release using ONLY the supplied metadata. Clearly distinguish TMDB listing information from official confirmation. Do not invent cast, director, trailer, studio, quotes, or release territory. If the material is too thin, keep it brief and flag missing verification. No review or viewing claims.',
                    'facts' => $facts,
                );
                $post_id = GDES_Generator::generate($payload, $author);
                if (is_wp_error($post_id)) {
                    $failed++;
                    WP_CLI::warning('AI failed for ' . $title . ': ' . $post_id->get_error_message());
                    continue;
                }
                update_post_meta($post_id, '_gdes_release_key', $key);
                update_post_meta($post_id, '_gdes_release_date', $date_string);
                update_post_meta($post_id, '_gdes_release_category', $category);
                update_post_meta($post_id, '_gdes_source_url', $url);
                update_post_meta($post_id, '_gdes_release_mode', $mode);
                $created++;
                WP_CLI::log('AI DRAFT: ' . $title . ' (post ' . $post_id . ')');
                continue;
            }
            $content="EDITORIAL LEAD — NOT VERIFIED FOR PUBLICATION\\n\\n";
            $content.="Category: ".$category."\\nRelease date: ".$date_string."\\n";
            $content.="Source: ".$item_source."\\n";
            if ($url) $content.="Source URL: ".$url."\\n";
            $content.="\\nVerify release details against official sources before writing or publishing.";
            $post_id=wp_insert_post(array('post_type'=>'post','post_status'=>'draft','post_author'=>$author,
                'post_title'=>$title.' — upcoming release','post_content'=>$content),true);
            if (is_wp_error($post_id)) { WP_CLI::warning($post_id->get_error_message()); continue; }
            update_post_meta($post_id,'_gdes_release_key',$key);
            update_post_meta($post_id,'_gdes_release_date',$date_string);
            update_post_meta($post_id,'_gdes_release_category',$category);
            $created++;
        }
        if ($dry_run) {
            WP_CLI::success("Dry run: {$created} potential drafts; skipped {$skipped} releases. No posts created.");
        } else {
            WP_CLI::success("Created {$created} drafts; skipped {$skipped} releases; AI failures {$failed}.");
        }
    }
}
WP_CLI::add_command('gamedefiners releases scan','GDES_Release_Scan');
