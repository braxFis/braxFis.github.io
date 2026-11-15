<?php
/**
 * Plugin Name: COMA Content Export
 * Description: Public, versioned export of published articles for the COMA CMS mirror.
 * Version: 1.0.0
 * Requires PHP: 8.0
 */
if (!defined('ABSPATH')) { exit; }

final class COMA_Content_Export
{
    const REVISION = 'coma_content_export_revision';

    public static function changed(...$args): void
    {
        update_option(self::REVISION, wp_generate_uuid4(), false);
    }

    public static function register(): void
    {
        register_rest_route('coma-sync/v1', '/articles', [
            'methods' => 'GET',
            'callback' => [__CLASS__, 'articles'],
            'permission_callback' => '__return_true',
        ]);
    }

    public static function articles($request)
    {
        // This is a PUBLIC feed, independent of the requesting user's privileges.
        $page = filter_var($request->get_param('page') ?? 1, FILTER_VALIDATE_INT);
        if ($page === false || $page < 1 || $page > 1000) {
            return new WP_Error('invalid_page', 'Invalid page.', ['status' => 400]);
        }
        $revision = (string) get_option(self::REVISION, 'initial');
        $generation = hash('sha256', 'v1:' . $revision);
        $expected = (string) ($request->get_param('generation') ?? '');
        if ($expected !== '' && !hash_equals($generation, $expected)) {
            return new WP_Error('snapshot_changed', 'Content changed; restart sync.', ['status' => 409]);
        }
        $query = new WP_Query([
            'post_type' => 'post', 'post_status' => 'publish',
            'has_password' => false, 'posts_per_page' => 50,
            'paged' => $page, 'orderby' => 'ID', 'order' => 'ASC',
            'ignore_sticky_posts' => true,
        ]);
        $pages = max(1, (int) $query->max_num_pages);
        if ($page > $pages) {
            return new WP_Error('invalid_page', 'Page is out of range.', ['status' => 400]);
        }
        $items = [];
        global $post;
        $previous = $post ?? null;
        foreach ($query->posts as $entry) {
            if ($entry->post_status !== 'publish' || $entry->post_password !== ''
                || !is_post_publicly_viewable($entry)) {
                continue;
            }
            $post = $entry;
            setup_postdata($post);
            $gameSource = (string) get_post_meta($entry->ID, '_coma_game_source', true);
            $gameId = (int) get_post_meta($entry->ID, '_coma_game_id', true);
            $game = in_array($gameSource, ['igdb', 'rawg'], true) && $gameId > 0
                ? ['source' => $gameSource, 'id' => $gameId] : null;
            // Render Gutenberg/dynamic blocks on WordPress, then remove unsafe markup.
            $items[] = [
                'id' => (int) $entry->ID,
                'status' => 'publish',
                'title' => html_entity_decode(wp_strip_all_tags(get_the_title($entry)), ENT_QUOTES, 'UTF-8'),
                'content_html' => wp_kses_post(apply_filters('the_content', $entry->post_content)),
                'excerpt' => wp_strip_all_tags(get_the_excerpt($entry)),
                'url' => get_permalink($entry),
                'published_at' => get_post_time('c', true, $entry),
                'modified_at' => get_post_modified_time('c', true, $entry),
                'author' => get_the_author_meta('display_name', $entry->post_author),
                'image' => get_the_post_thumbnail_url($entry, 'large') ?: '',
                'categories' => wp_get_post_categories($entry->ID, ['fields' => 'names']),
                'tags' => wp_get_post_tags($entry->ID, ['fields' => 'names']),
                'game' => $game,
            ];
        }
        $post = $previous;
        if ($previous) { setup_postdata($previous); } else { wp_reset_postdata(); }
        // A concurrent edit must not produce a partially consistent mirror.
        if ((string) get_option(self::REVISION, 'initial') !== $revision) {
            return new WP_Error('snapshot_changed', 'Content changed; restart sync.', ['status' => 409]);
        }
        $response = new WP_REST_Response([
            'schema' => 1, 'source' => untrailingslashit(home_url('/')),
            'generation' => $generation, 'page' => $page, 'pages' => $pages,
            'total' => (int) $query->found_posts, 'articles' => $items,
        ]);
        $response->header('Cache-Control', 'no-store');
        return $response;
    }
}

add_action('rest_api_init', ['COMA_Content_Export', 'register']);
foreach (['save_post', 'deleted_post', 'set_object_terms', 'edited_term', 'delete_term', 'profile_update', 'added_post_meta', 'updated_post_meta', 'deleted_post_meta'] as $hook) {
    add_action($hook, ['COMA_Content_Export', 'changed']);
}
