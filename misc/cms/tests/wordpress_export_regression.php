<?php
declare(strict_types=1);
define('ABSPATH', __DIR__);
$revision = 'one';
$exportPosts = [];
$queryArgs = [];
function add_action(...$args) {}
function get_option($name, $default = null) { return $GLOBALS['revision']; }
function untrailingslashit($value) { return rtrim($value, '/'); }
function home_url($path) { return 'https://wp.example/'; }
function is_post_publicly_viewable($post) { return $post->post_status === 'publish'; }
function setup_postdata($post) {}
function wp_reset_postdata() {}
function get_post_meta($id, $key, $single) { return $key === '_coma_game_source' ? 'igdb' : 42; }
function get_the_title($post) { return 'Article ' . $post->ID; }
function html($value) { return strip_tags($value); }
function wp_strip_all_tags($value) { return strip_tags($value); }
function wp_kses_post($value) { return $value; }
function apply_filters($filter, $content) {
    if (!empty($GLOBALS['changeDuringRender'])) { $GLOBALS['revision'] = 'two'; }
    return $content;
}
function get_the_excerpt($post) { return 'Excerpt'; }
function get_permalink($post) { return 'https://wp.example/' . $post->ID; }
function get_post_time(...$args) { return '2026-11-15T10:00:00+00:00'; }
function get_post_modified_time(...$args) { return '2026-11-15T10:00:00+00:00'; }
function get_the_author_meta(...$args) { return 'Editor'; }
function get_the_post_thumbnail_url(...$args) { return ''; }
function wp_get_post_categories(...$args) { return ['News']; }
function wp_get_post_tags(...$args) { return []; }
class WP_Error { public function __construct(public $code, public $message, public $data) {} }
class WP_REST_Response {
    public function __construct(public $data) {}
    public function header(...$args) {}
}
class WP_Query {
    public array $posts;
    public int $max_num_pages = 1;
    public int $found_posts;
    public function __construct($args) {
        $GLOBALS['queryArgs'] = $args;
        // Include private fixtures deliberately to exercise the exporter's own guard.
        $this->posts = $GLOBALS['exportPosts'];
        $this->found_posts = count($this->posts);
    }
}
class Request {
    public function __construct(private array $params = []) {}
    public function get_param($key) { return $this->params[$key] ?? null; }
}
require dirname(__DIR__, 3) . '/wordpress/wp-content/plugins/coma-content-export/coma-content-export.php';
function check($ok, $message) { if (!$ok) { throw new RuntimeException($message); } }
foreach ([[1,'publish',''],[2,'draft',''],[3,'private',''],[4,'publish','secret']] as [$id,$status,$password]) {
    $exportPosts[] = (object) ['ID' => $id, 'post_status' => $status, 'post_password' => $password,
        'post_content' => '<p>Public content</p>', 'post_author' => 1];
}
$response = COMA_Content_Export::articles(new Request());
check($queryArgs['post_status'] === 'publish' && $queryArgs['has_password'] === false, 'Query excludes protected posts');
check(count($response->data['articles']) === 1, 'Only publicly visible, unprotected articles exported');
check($response->data['articles'][0]['game'] === ['source' => 'igdb', 'id' => 42], 'Provider mapping exported');
check(COMA_Content_Export::articles(new Request(['generation' => str_repeat('0',64)])) instanceof WP_Error, 'Stale generation rejected');
check(COMA_Content_Export::articles(new Request(['page' => -1])) instanceof WP_Error, 'Invalid page rejected');
$changeDuringRender = true;
check(COMA_Content_Export::articles(new Request()) instanceof WP_Error, 'Concurrent render changes rejected');
echo "WordPress export regression tests passed.\n";
