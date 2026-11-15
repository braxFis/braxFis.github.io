<?php
declare(strict_types=1);
define('ABSPATH', __DIR__);
define('MINUTE_IN_SECONDS', 60);
$options = ['preferred' => 'igdb', 'igdb_client_id' => 'fixture-id', 'igdb_client_secret' => 'fixture-secret', 'rawg_key' => 'fixture-rawg'];
$cache = []; $calls = []; $tokenStatus = 401; $games = [['id' => 42, 'name' => 'Fixture game']];
class WP_Error {
    public function __construct(private $code, private $message) {}
    public function get_error_message() { return $this->message; }
}
class JsonResult extends Exception {
    public function __construct(public bool $success, public array $data, public int $status = 200) { parent::__construct(); }
}
function add_action(...$args) {}
function get_option(...$args) { return $GLOBALS['options']; }
function check_ajax_referer(...$args) { return true; }
function current_user_can(...$args) { return true; }
function wp_unslash($v) { return $v; }
function sanitize_text_field($v) { return trim(strip_tags((string) $v)); }
function sanitize_key($v) { return strtolower((string) $v); }
function absint($v) { return abs((int) $v); }
function esc_url_raw($v) { return $v; }
function get_transient($key) { return $GLOBALS['cache'][$key] ?? false; }
function set_transient($key, $value, $ttl) { $GLOBALS['cache'][$key] = $value; }
function wp_send_json_success($data) { throw new JsonResult(true, $data); }
function wp_send_json_error($data, $status) { throw new JsonResult(false, $data, $status); }
function is_wp_error($value) { return $value instanceof WP_Error; }
function add_query_arg($params, $url) { return $url . '?' . http_build_query($params); }
function wp_remote_retrieve_response_code($res) { return $res['status']; }
function wp_remote_retrieve_body($res) { return json_encode($res['data']); }
function wp_remote_get($url, $args) {
    $GLOBALS['calls'][] = 'rawg';
    return ['status' => 200, 'data' => ['results' => [['id' => 99, 'name' => 'RAWG fixture']]]];
}
function wp_remote_post($url, $args) {
    if (str_contains($url, 'oauth2/token')) {
        $GLOBALS['calls'][] = 'token';
        if (str_contains($url, 'client_secret')) { throw new RuntimeException('Credentials must stay out of URLs'); }
        if (empty($args['body']['client_secret'])) { throw new RuntimeException('Token credentials must be in POST body'); }
        return ['status' => $GLOBALS['tokenStatus'], 'data' => ['access_token' => 'fixture-token', 'expires_in' => 3600]];
    }
    $GLOBALS['calls'][] = 'igdb';
    return ['status' => 200, 'data' => $GLOBALS['games']];
}
require $argv[1] ?? dirname(__DIR__, 3) . '/wordpress/wp-content/plugins/coma-game-articles/coma-game-articles.php';
function check($ok, $message) { if (!$ok) throw new RuntimeException($message); }
function search(string $source): JsonResult {
    $_GET = ['query' => 'Fixture', 'source' => $source];
    try { COMA_Game_Articles::ajax_search(); } catch (JsonResult $result) { return $result; }
    throw new RuntimeException('No AJAX response');
}
$result = search('igdb');
check(!$result->success && str_contains($result->data['message'], 'HTTP 401'), 'Exact IGDB mode must expose token failure');
check(!in_array('rawg', $calls, true), 'Exact IGDB mode must not fall back');
$result = search('auto');
check($result->success && $result->data['source'] === 'rawg' && $result->data['fallback'], 'Auto mode may use RAWG');
check(str_contains($result->data['fallback_reason'], 'HTTP 401'), 'Fallback must explain original error');
$cache = []; $calls = []; $tokenStatus = 200;
$result = search('igdb');
check($result->success && $result->data['source'] === 'igdb' && $result->data['games'][0]['id'] === 42, 'Exact mode returns IGDB IDs');
$before = count($calls);
search('igdb');
check(count($calls) === $before, 'Unchanged configuration reuses successful results');
$options['igdb_client_secret'] = 'changed-fixture-secret';
search('igdb');
check(count($calls) > $before, 'Changed credentials invalidate results and token cache');
$cache = []; $calls = []; $games = [];
$result = search('igdb');
check($result->success && $result->data['games'] === [] && !in_array('rawg', $calls, true), 'Empty IGDB results never switch provider');
$calls = [];
$result = search('rawg');
check($result->success && $result->data['source'] === 'rawg' && !in_array('igdb', $calls, true), 'Explicit RAWG stays RAWG');
$result = search('invalid');
check(!$result->success && $result->status === 400, 'Reject unknown source');
echo "Game picker provider regression tests passed.\n";
