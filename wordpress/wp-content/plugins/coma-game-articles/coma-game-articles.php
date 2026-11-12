<?php
/**
 * Plugin Name: COMA Game Articles
 * Description: Associate WordPress posts with games using RAWG search and automatic IGDB fallback; provide articles through REST.
 * Version: 1.0.0
 * Requires PHP: 7.4
 * Text Domain: coma-game-articles
 */

if (!defined('ABSPATH')) { exit; }

final class COMA_Game_Articles {
    const VERSION = '1.0.0';
    const SETTINGS = 'coma_game_articles_settings';
    const NONCE_ACTION = 'coma_game_articles_save';
    const AJAX_NONCE = 'coma_game_articles_search';
    const META_SOURCE = '_coma_game_source';
    const META_ID = '_coma_game_id';
    const META_TITLE = '_coma_game_title';

    public static function init() {
        add_action('admin_menu', [__CLASS__, 'admin_menu']);
        add_action('admin_init', [__CLASS__, 'register_settings']);
        add_action('add_meta_boxes_post', [__CLASS__, 'add_meta_box']);
        add_action('save_post_post', [__CLASS__, 'save_post']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_admin']);
        add_action('wp_ajax_coma_game_search', [__CLASS__, 'ajax_search']);
        add_action('rest_api_init', [__CLASS__, 'register_routes']);
    }

    private static function options() {
        $v = get_option(self::SETTINGS, []);
        return is_array($v) ? $v : [];
    }

    public static function admin_menu() {
        add_options_page('COMA Game Articles', 'COMA Game Articles', 'manage_options', 'coma-game-articles', [__CLASS__, 'settings_page']);
    }

    public static function register_settings() {
        register_setting('coma_game_articles', self::SETTINGS, [
            'type' => 'array', 'sanitize_callback' => [__CLASS__, 'sanitize_settings'],
            'default' => [],
        ]);
    }

    public static function sanitize_settings($input) {
        $input = is_array($input) ? $input : [];
        $old = self::options();
        $out = [];
        foreach (['rawg_key','igdb_client_id','igdb_client_secret'] as $key) {
            $entered = isset($input[$key]) ? trim((string) wp_unslash($input[$key])) : '';
            // Leave blank to keep the previously saved credential.
            $out[$key] = $entered !== '' ? sanitize_text_field($entered) : ($old[$key] ?? '');
        }
        $out['preferred'] = in_array(($input['preferred'] ?? ''), ['rawg', 'igdb'], true) ? $input['preferred'] : 'rawg';
        return $out;
    }

    public static function settings_page() {
        if (!current_user_can('manage_options')) { return; }
        $o = self::options();
        ?>
        <div class="wrap"><h1>COMA Game Articles</h1>
        <p>Fyll i dina API-uppgifter. RAWG används i första hand som standard, men om RAWG inte svarar eller ger fel används IGDB automatiskt. Du kan byta ordning nedan.</p>
        <form method="post" action="options.php">
        <?php settings_fields('coma_game_articles'); ?>
        <table class="form-table" role="presentation">
          <?php foreach ([
            'rawg_key' => 'RAWG API-nyckel',
            'igdb_client_id' => 'IGDB / Twitch Client ID',
            'igdb_client_secret' => 'IGDB / Twitch Client Secret',
          ] as $field => $label): ?>
          <tr><th scope="row"><label for="coma-<?php echo esc_attr($field); ?>"><?php echo esc_html($label); ?></label></th>
          <td><input type="password" autocomplete="new-password" class="regular-text" id="coma-<?php echo esc_attr($field); ?>" name="<?php echo esc_attr(self::SETTINGS . '[' . $field . ']'); ?>" value="" placeholder="<?php echo !empty($o[$field]) ? esc_attr('Sparad (lämna tomt för att behålla)') : ''; ?>"><p class="description"><?php echo !empty($o[$field]) ? 'Nyckel sparad.' : 'Ingen nyckel sparad.'; ?></p></td></tr>
          <?php endforeach; ?>
          <tr><th scope="row">Föredragen källa</th><td><select name="<?php echo esc_attr(self::SETTINGS); ?>[preferred]">
          <option value="rawg" <?php selected(($o['preferred'] ?? 'rawg'), 'rawg'); ?>>RAWG → IGDB</option>
          <option value="igdb" <?php selected(($o['preferred'] ?? 'rawg'), 'igdb'); ?>>IGDB → RAWG</option>
          </select></td></tr>
        </table><?php submit_button('Spara inställningar'); ?></form>
        <p><strong>REST API:</strong> <code><?php echo esc_html(rest_url('coma/v1/games/igdb/123/articles')); ?></code> eller <code><?php echo esc_html(rest_url('coma/v1/games/rawg/123/articles')); ?></code>.</p>
        <p><strong>Viktigt:</strong> RAWG-ID och IGDB-ID är olika. För att artikeln ska synas på din MVC-spelsida måste den sidan känna till både spelets källa och ID eller ha en ID-mappning.</p>
        </div>
        <?php
    }

    public static function add_meta_box() {
        add_meta_box('coma-game-articles', 'COMA: koppla till spel', [__CLASS__, 'render_metabox'], 'post', 'side', 'default');
    }

    public static function render_metabox($post) {
        wp_nonce_field(self::NONCE_ACTION, 'coma_game_articles_nonce');
        $source = get_post_meta($post->ID, self::META_SOURCE, true);
        $id = get_post_meta($post->ID, self::META_ID, true);
        $title = get_post_meta($post->ID, self::META_TITLE, true);
        ?>
        <div id="coma-game-picker">
          <p><label for="coma-game-query">Sök efter ett spel (RAWG med IGDB-reserv)</label></p>
          <input type="search" id="coma-game-query" style="width:100%" autocomplete="off" placeholder="Exempel: Tomb Raider">
          <div id="coma-game-search-status" role="status" aria-live="polite"></div>
          <div id="coma-game-results" aria-label="Sökresultat"></div>
          <input type="hidden" name="coma_game_source" id="coma-game-source" value="<?php echo esc_attr($source); ?>">
          <input type="hidden" name="coma_game_id" id="coma-game-id" value="<?php echo esc_attr($id); ?>">
          <input type="hidden" name="coma_game_title" id="coma-game-title" value="<?php echo esc_attr($title); ?>">
          <p id="coma-game-selected"><?php echo $id && $source ? 'Valt: ' . esc_html($title ?: 'Spel') . ' (' . esc_html(strtoupper($source)) . ' #' . esc_html($id) . ')' : 'Inget spel valt.'; ?></p>
          <button type="button" class="button" id="coma-game-clear">Ta bort spelkoppling</button>
          <p class="description">Välj rätt spel i listan. ID och källa sparas automatiskt när artikeln sparas.</p>
        </div>
        <?php
    }

    public static function enqueue_admin($hook) {
        if (!in_array($hook, ['post.php','post-new.php'], true)) { return; }
        $screen = get_current_screen();
        if (!$screen || $screen->post_type !== 'post') { return; }
        wp_enqueue_script('coma-game-picker', plugins_url('assets/game-picker.js', __FILE__), [], self::VERSION, true);
        wp_localize_script('coma-game-picker', 'COMAGamePicker', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce(self::AJAX_NONCE),
        ]);
        wp_enqueue_style('coma-game-picker', plugins_url('assets/game-picker.css', __FILE__), [], self::VERSION);
    }

    public static function save_post($post_id) {
        if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) { return; }
        if (!isset($_POST['coma_game_articles_nonce'])) { return; }
        $nonce = sanitize_text_field(wp_unslash($_POST['coma_game_articles_nonce']));
        if (!wp_verify_nonce($nonce, self::NONCE_ACTION) || !current_user_can('edit_post', $post_id)) { return; }
        if (!isset($_POST['coma_game_source'], $_POST['coma_game_id'])) { return; }
        $source = sanitize_key(wp_unslash($_POST['coma_game_source']));
        $id = absint(wp_unslash($_POST['coma_game_id']));
        $title = isset($_POST['coma_game_title']) ? sanitize_text_field(wp_unslash($_POST['coma_game_title'])) : '';
        if (!$id || !in_array($source, ['rawg','igdb'], true)) {
            delete_post_meta($post_id, self::META_SOURCE);
            delete_post_meta($post_id, self::META_ID);
            delete_post_meta($post_id, self::META_TITLE);
            return;
        }
        update_post_meta($post_id, self::META_SOURCE, $source);
        update_post_meta($post_id, self::META_ID, $id);
        update_post_meta($post_id, self::META_TITLE, $title);
    }

    public static function ajax_search() {
        if (!check_ajax_referer(self::AJAX_NONCE, 'nonce', false) || !current_user_can('edit_posts')) {
            wp_send_json_error(['message' => 'Åtkomst nekad.'], 403);
        }
        $query = isset($_GET['query']) ? sanitize_text_field(wp_unslash($_GET['query'])) : '';
        $query = trim($query);
        if (strlen($query) < 2 || strlen($query) > 120) {
            wp_send_json_error(['message' => 'Skriv mellan 2 och 120 tecken.'], 400);
        }
        $cache_key = 'coma_search_' . md5($query . ':' . (self::options()['preferred'] ?? 'rawg'));
        $cached = get_transient($cache_key);
        if (is_array($cached)) { wp_send_json_success($cached); }
        $o = self::options();
        $primary = ($o['preferred'] ?? 'rawg') === 'igdb' ? 'igdb' : 'rawg';
        $secondary = $primary === 'rawg' ? 'igdb' : 'rawg';
        $errors = [];
        foreach ([$primary, $secondary] as $source) {
            $result = $source === 'rawg' ? self::search_rawg($query, $o) : self::search_igdb($query, $o);
            if (!is_wp_error($result)) {
                // A legitimate empty result is not evidence of an outage. Do not mix identifiers.
                $payload = ['source' => $source, 'games' => $result, 'fallback' => ($source !== $primary)];
                set_transient($cache_key, $payload, 5 * MINUTE_IN_SECONDS);
                wp_send_json_success($payload);
            }
            $errors[$source] = $result->get_error_message();
        }
        wp_send_json_error(['message' => 'Varken RAWG eller IGDB kunde användas. Kontrollera API-uppgifter och anslutning.', 'details' => $errors], 502);
    }

    private static function search_rawg($query, $o) {
        $key = defined('COMA_RAWG_API_KEY') ? COMA_RAWG_API_KEY : ($o['rawg_key'] ?? '');
        if (!$key) { return new WP_Error('missing_rawg', 'RAWG-nyckel saknas'); }
        $url = add_query_arg(['key' => $key, 'search' => $query, 'page_size' => 10], 'https://api.rawg.io/api/games');
        $res = wp_remote_get($url, ['timeout' => 4, 'redirection' => 2]);
        if (is_wp_error($res)) { return $res; }
        if (wp_remote_retrieve_response_code($res) !== 200) { return new WP_Error('rawg_http', 'RAWG HTTP ' . wp_remote_retrieve_response_code($res)); }
        $data = json_decode(wp_remote_retrieve_body($res), true);
        if (!is_array($data) || !isset($data['results']) || !is_array($data['results'])) { return new WP_Error('rawg_json', 'Ogiltigt RAWG-svar'); }
        $games = [];
        foreach ($data['results'] as $g) {
            $id = absint($g['id'] ?? 0);
            if (!$id) { continue; }
            $games[] = [
                'id' => $id, 'source' => 'rawg',
                'name' => sanitize_text_field($g['name'] ?? ''),
                'year' => !empty($g['released']) ? substr($g['released'], 0, 4) : '',
                'image' => esc_url_raw($g['background_image'] ?? ''),
            ];
        }
        return $games;
    }

    private static function igdb_token($o) {
        $client_id = defined('COMA_IGDB_CLIENT_ID') ? COMA_IGDB_CLIENT_ID : ($o['igdb_client_id'] ?? '');
        $secret = defined('COMA_IGDB_CLIENT_SECRET') ? COMA_IGDB_CLIENT_SECRET : ($o['igdb_client_secret'] ?? '');
        if (!$client_id || !$secret) { return new WP_Error('missing_igdb', 'IGDB/Twitch Client ID eller Secret saknas'); }
        $cache_key = 'coma_igdb_token_' . md5($client_id . ':' . $secret);
        $token = get_transient($cache_key);
        if (is_string($token) && $token) { return ['client_id' => $client_id, 'token' => $token]; }
        $url = add_query_arg(['client_id' => $client_id, 'client_secret' => $secret, 'grant_type' => 'client_credentials'], 'https://id.twitch.tv/oauth2/token');
        $res = wp_remote_post($url, ['timeout' => 8, 'body' => []]);
        if (is_wp_error($res)) { return $res; }
        $data = json_decode(wp_remote_retrieve_body($res), true);
        if (wp_remote_retrieve_response_code($res) !== 200 || empty($data['access_token'])) { return new WP_Error('igdb_token', 'Kunde inte hämta Twitch access token'); }
        $ttl = max(60, min(3600, absint($data['expires_in'] ?? 3600) - 120));
        set_transient($cache_key, $data['access_token'], $ttl);
        return ['client_id' => $client_id, 'token' => $data['access_token']];
    }

    private static function search_igdb($query, $o) {
        $auth = self::igdb_token($o);
        if (is_wp_error($auth)) { return $auth; }
        $safe_query = str_replace(['\\', '"', "\n", "\r"], ['\\\\', '\\"', ' ', ' '], $query);
        $body = 'search "' . $safe_query . '"; fields name,first_release_date,cover.image_id; limit 10;';
        $res = wp_remote_post('https://api.igdb.com/v4/games', [
            'timeout' => 8,
            'headers' => ['Client-ID' => $auth['client_id'], 'Authorization' => 'Bearer ' . $auth['token'], 'Content-Type' => 'text/plain'],
            'body' => $body,
        ]);
        if (is_wp_error($res)) { return $res; }
        if (wp_remote_retrieve_response_code($res) !== 200) { return new WP_Error('igdb_http', 'IGDB HTTP ' . wp_remote_retrieve_response_code($res)); }
        $data = json_decode(wp_remote_retrieve_body($res), true);
        if (!is_array($data) || !array_is_list($data)) { return new WP_Error('igdb_json', 'Ogiltigt IGDB-svar'); }
        $games = [];
        foreach ($data as $g) {
            $id = absint($g['id'] ?? 0);
            if (!$id) { continue; }
            $image_id = $g['cover']['image_id'] ?? '';
            $image = $image_id && preg_match('/^[a-z0-9_]+$/', $image_id) ? 'https://images.igdb.com/igdb/image/upload/t_cover_small/' . $image_id . '.jpg' : '';
            $games[] = [
                'id' => $id, 'source' => 'igdb',
                'name' => sanitize_text_field($g['name'] ?? ''),
                'year' => !empty($g['first_release_date']) ? gmdate('Y', (int) $g['first_release_date']) : '',
                'image' => $image,
            ];
        }
        return $games;
    }

    public static function register_routes() {
        register_rest_route('coma/v1', '/games/(?P<source>rawg|igdb)/(?P<id>\d+)/articles', [
            'methods' => 'GET', 'callback' => [__CLASS__, 'get_articles'], 'permission_callback' => '__return_true',
            'args' => ['id' => ['validate_callback' => [__CLASS__, 'validate_id']]],
        ]);
        // Backwards compatibility for pre-existing RAWG-specific MVC consumers.
        register_rest_route('coma/v1', '/games/(?P<id>\d+)/articles', [
            'methods' => 'GET', 'callback' => [__CLASS__, 'get_rawg_articles'], 'permission_callback' => '__return_true',
            'args' => ['id' => ['validate_callback' => [__CLASS__, 'validate_id']]],
        ]);
    }

    public static function validate_id($value) { return ctype_digit((string) $value) && (int) $value > 0; }
    public static function get_rawg_articles($request) {
        $request->set_param('source', 'rawg');
        return self::get_articles($request);
    }
    public static function get_articles($request) {
        $source = sanitize_key($request['source']);
        $id = absint($request['id']);
        if (!in_array($source, ['rawg','igdb'], true) || !$id) { return new WP_Error('invalid_game', 'Ogiltigt spel', ['status' => 400]); }
        $posts = get_posts([
            'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 20,
            'meta_query' => [
                'relation' => 'AND',
                ['key' => self::META_SOURCE, 'value' => $source, 'compare' => '='],
                ['key' => self::META_ID, 'value' => $id, 'compare' => '=', 'type' => 'NUMERIC'],
            ],
        ]);
        $articles = [];
        foreach ($posts as $post) {
            $articles[] = [
                'id' => (int) $post->ID,
                'title' => get_the_title($post),
                'url' => get_permalink($post),
                'date' => get_the_date('Y-m-d', $post),
                'excerpt' => wp_strip_all_tags(get_the_excerpt($post)),
                'image' => get_the_post_thumbnail_url($post, 'medium') ?: '',
                'category' => wp_get_post_categories($post->ID, ['fields' => 'names']),
                'game' => ['source' => $source, 'id' => $id],
            ];
        }
        return rest_ensure_response($articles);
    }
}

COMA_Game_Articles::init();
