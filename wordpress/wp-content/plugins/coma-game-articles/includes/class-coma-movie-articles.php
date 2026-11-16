<?php
if (!defined('ABSPATH')) { exit; }

/** Movie associations are independent of the existing RAWG/IGDB game associations. */
final class COMA_Movie_Articles {
    const META_SOURCE = '_coma_movie_source';
    const META_ID = '_coma_movie_id';
    const META_TITLE = '_coma_movie_title';
    const SAVE_NONCE = 'coma_movie_articles_save';
    const SEARCH_NONCE = 'coma_movie_articles_search';

    public static function init() {
        add_action('add_meta_boxes_post', [__CLASS__, 'add_meta_box']);
        add_action('save_post_post', [__CLASS__, 'save_post']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_admin']);
        add_action('wp_ajax_coma_movie_search', [__CLASS__, 'ajax_search']);
        add_action('rest_api_init', [__CLASS__, 'register_routes']);
    }

    public static function add_meta_box() {
        add_meta_box('coma-movie-articles', 'COMA: koppla till film', [__CLASS__, 'render_metabox'], 'post', 'side', 'default');
    }

    public static function render_metabox($post) {
        wp_nonce_field(self::SAVE_NONCE, 'coma_movie_articles_nonce');
        $id = get_post_meta($post->ID, self::META_ID, true);
        $title = get_post_meta($post->ID, self::META_TITLE, true);
        ?>
        <div id="coma-movie-picker">
          <p><label for="coma-movie-query">Sök filmtitel i TMDB</label></p>
          <input type="search" id="coma-movie-query" style="width:100%" autocomplete="off" maxlength="120" placeholder="Exempel: Alien">
          <div id="coma-movie-search-status" role="status" aria-live="polite"></div>
          <div id="coma-movie-results" aria-label="Filmer från TMDB"></div>
          <input type="hidden" name="coma_movie_id" id="coma-movie-id" value="<?php echo esc_attr($id); ?>">
          <input type="hidden" name="coma_movie_title" id="coma-movie-title" value="<?php echo esc_attr($title); ?>">
          <p id="coma-movie-selected"><?php echo $id ? 'Vald film: ' . esc_html($title ?: 'Film') . ' (TMDB #' . esc_html($id) . ')' : 'Ingen film vald.'; ?></p>
          <button type="button" class="button" id="coma-movie-clear">Ta bort filmkoppling</button>
          <p class="description">Välj rätt film och årtal. Kopplingen sparas när du uppdaterar artikeln. Spelkopplingen påverkas inte.</p>
          <p class="description">This product uses the TMDB API but is not endorsed or certified by TMDB.</p>
        </div>
        <?php
    }

    public static function enqueue_admin($hook) {
        if (!in_array($hook, ['post.php', 'post-new.php'], true)) { return; }
        $screen = get_current_screen();
        if (!$screen || $screen->post_type !== 'post') { return; }
        $plugin_file = dirname(__DIR__) . '/coma-game-articles.php';
        wp_enqueue_script('coma-movie-picker', plugins_url('assets/movie-picker.js', $plugin_file), [], COMA_Game_Articles::VERSION, true);
        wp_localize_script('coma-movie-picker', 'COMAMoviePicker', [
            'ajaxUrl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce(self::SEARCH_NONCE),
        ]);
    }

    public static function save_post($post_id) {
        if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) { return; }
        if (!isset($_POST['coma_movie_articles_nonce']) || !is_string($_POST['coma_movie_articles_nonce'])) { return; }
        if (!current_user_can('edit_post', $post_id) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['coma_movie_articles_nonce'])), self::SAVE_NONCE)) { return; }
        // Quick edit, bulk edit and unrelated saves must never clear a saved association.
        if (!isset($_POST['coma_movie_id']) || !is_string($_POST['coma_movie_id'])) { return; }
        $value = trim(wp_unslash($_POST['coma_movie_id']));
        if ($value === '') {
            foreach ([self::META_SOURCE, self::META_ID, self::META_TITLE] as $key) { delete_post_meta($post_id, $key); }
            return;
        }
        // Invalid/tampered input leaves existing associations intact.
        if (!self::validate_id($value)) { return; }
        $title = isset($_POST['coma_movie_title']) && is_string($_POST['coma_movie_title'])
            ? sanitize_text_field(wp_unslash($_POST['coma_movie_title'])) : '';
        update_post_meta($post_id, self::META_SOURCE, 'tmdb');
        update_post_meta($post_id, self::META_ID, (int) $value);
        update_post_meta($post_id, self::META_TITLE, $title);
    }

    public static function validate_id($value) {
        return (is_string($value) || is_int($value)) && ctype_digit((string) $value)
            && filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) !== false;
    }

    private static function token() {
        if (defined('COMA_TMDB_READ_TOKEN') && COMA_TMDB_READ_TOKEN) { return trim((string) COMA_TMDB_READ_TOKEN); }
        $o = get_option(COMA_Game_Articles::SETTINGS, []);
        return is_array($o) && isset($o['tmdb_read_token']) && is_string($o['tmdb_read_token']) ? trim($o['tmdb_read_token']) : '';
    }

    public static function ajax_search() {
        if (!current_user_can('edit_posts') || !check_ajax_referer(self::SEARCH_NONCE, 'nonce', false)) {
            wp_send_json_error(['message' => 'Åtkomst nekad.'], 403);
            return;
        }
        $query = isset($_GET['query']) && is_string($_GET['query']) ? trim(sanitize_text_field(wp_unslash($_GET['query']))) : '';
        if (strlen($query) < 2 || strlen($query) > 120) {
            wp_send_json_error(['message' => 'Skriv mellan 2 och 120 tecken.'], 400);
            return;
        }
        $token = self::token();
        if ($token === '') {
            wp_send_json_error(['message' => 'TMDB-token saknas. Ange API Read Access Token under Inställningar → COMA Game Articles.'], 503);
            return;
        }
        $cache_key = 'coma_movie_search_' . hash('sha256', $query . ':sv-SE:' . $token);
        $cached = get_transient($cache_key);
        if (is_array($cached)) { wp_send_json_success($cached); return; }
        $url = add_query_arg(['query' => $query, 'language' => 'sv-SE', 'include_adult' => 'false', 'page' => 1],
            'https://api.themoviedb.org/3/search/movie');
        $res = wp_remote_get($url, ['timeout' => 10, 'redirection' => 0, 'limit_response_size' => 1000000,
            'headers' => ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $token]]);
        if (is_wp_error($res)) {
            wp_send_json_error(['message' => 'TMDB kunde inte nås. Försök igen senare.'], 502);
            return;
        }
        $status = (int) wp_remote_retrieve_response_code($res);
        if ($status !== 200) {
            $message = in_array($status, [401, 403], true) ? 'TMDB nekade åtkomst. Kontrollera ditt API Read Access Token.'
                : ($status === 429 ? 'TMDB begränsar anropen. Vänta en stund och försök igen.' : 'TMDB svarade med HTTP ' . $status . '.');
            wp_send_json_error(['message' => $message], 502);
            return;
        }
        $data = json_decode(wp_remote_retrieve_body($res), true);
        if (!is_array($data) || !isset($data['results']) || !is_array($data['results']) || !array_is_list($data['results'])) {
            wp_send_json_error(['message' => 'TMDB returnerade ett ogiltigt svar.'], 502);
            return;
        }
        $movies = [];
        foreach (array_slice($data['results'], 0, 10) as $movie) {
            if (!is_array($movie) || !self::validate_id($movie['id'] ?? null) || !isset($movie['title']) || !is_string($movie['title']) || !empty($movie['adult'])) { continue; }
            $name = sanitize_text_field($movie['title']);
            if ($name === '') { continue; }
            $release = is_string($movie['release_date'] ?? null) ? $movie['release_date'] : '';
            $movies[] = ['id' => (int) $movie['id'], 'source' => 'tmdb', 'name' => $name,
                'year' => preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/D', $release) ? substr($release, 0, 4) : ''];
        }
        $result = ['source' => 'tmdb', 'movies' => $movies];
        set_transient($cache_key, $result, 5 * MINUTE_IN_SECONDS);
        wp_send_json_success($result);
    }

    public static function register_routes() {
        register_rest_route('coma/v1', '/movies/tmdb/(?P<id>\d+)/articles', [
            'methods' => 'GET', 'callback' => [__CLASS__, 'get_articles'], 'permission_callback' => '__return_true',
            'args' => ['id' => ['validate_callback' => [__CLASS__, 'validate_id']]],
        ]);
    }

    public static function get_articles($request) {
        if (!self::validate_id($request['id'] ?? null)) { return new WP_Error('invalid_movie', 'Ogiltigt film-ID.', ['status' => 400]); }
        $id = (int) $request['id'];
        $posts = get_posts([
            'post_type' => 'post', 'post_status' => 'publish', 'has_password' => false, 'posts_per_page' => 20,
            'meta_query' => [
                'relation' => 'AND',
                ['key' => self::META_SOURCE, 'value' => 'tmdb', 'compare' => '='],
                ['key' => self::META_ID, 'value' => $id, 'compare' => '=', 'type' => 'NUMERIC'],
            ],
        ]);
        $articles = [];
        foreach ($posts as $post) {
            if ($post->post_status !== 'publish' || $post->post_password !== '' || !is_post_publicly_viewable($post)) { continue; }
            $articles[] = [
                'id' => (int) $post->ID, 'title' => get_the_title($post), 'url' => get_permalink($post),
                'date' => get_the_date('Y-m-d', $post), 'excerpt' => wp_strip_all_tags(get_the_excerpt($post)),
                'image' => get_the_post_thumbnail_url($post, 'medium') ?: '',
                'category' => wp_get_post_categories($post->ID, ['fields' => 'names']),
                'movie' => ['source' => 'tmdb', 'id' => $id],
            ];
        }
        $response = rest_ensure_response($articles);
        $response->header('Cache-Control', 'no-store');
        return $response;
    }
}
