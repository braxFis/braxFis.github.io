<?php
declare(strict_types=1);
define('ABSPATH', __DIR__);
define('MINUTE_IN_SECONDS', 60);
$options=['preferred'=>'igdb','rawg_key'=>'rawg-fixture','igdb_client_id'=>'id-fixture','igdb_client_secret'=>'secret-fixture','tmdb_read_token'=>'tmdb-fixture'];
$meta=[7=>['_coma_game_source'=>'igdb','_coma_game_id'=>11,'_coma_game_title'=>'Game']];
$cache=[]; $calls=[]; $allowed=true; $nonce=true; $revision=false; $status=200;
$payload=['results'=>[['id'=>11,'title'=>'Alien','release_date'=>'1979-05-25'],['id'=>12,'title'=>'Alien remake','release_date'=>'2027-01-01']]];
$publicPosts=[];
class WP_Error {
    public function __construct(public $code, private $message, public $data=[]) {}
    public function get_error_message() { return $this->message; }
}
class JsonResult extends Exception {
    public function __construct(public bool $success, public array $data, public int $status=200) { parent::__construct(); }
}
class WP_REST_Response {
    public array $headers=[];
    public function __construct(public $data) {}
    public function header($k,$v) { $this->headers[$k]=$v; }
}
function add_action(...$args) {}
function get_option(...$args) { return $GLOBALS['options']; }
function current_user_can(...$args) { return $GLOBALS['allowed']; }
function check_ajax_referer(...$args) { return $GLOBALS['nonce']; }
function wp_verify_nonce(...$args) { return $GLOBALS['nonce']; }
function wp_is_post_revision(...$args) { return $GLOBALS['revision']; }
function wp_is_post_autosave(...$args) { return false; }
function wp_unslash($v) { return $v; }
function sanitize_text_field($v) { return trim(strip_tags((string)$v)); }
function sanitize_key($v) { return strtolower((string)$v); }
function absint($v) { return abs((int)$v); }
function get_post_meta($id,$key,$single) { return $GLOBALS['meta'][$id][$key]??''; }
function update_post_meta($id,$key,$value) { $GLOBALS['meta'][$id][$key]=$value; }
function delete_post_meta($id,$key) { unset($GLOBALS['meta'][$id][$key]); }
function get_transient($key) { return $GLOBALS['cache'][$key]??false; }
function set_transient($key,$value,$ttl) { $GLOBALS['cache'][$key]=$value; }
function wp_send_json_success($data) { throw new JsonResult(true,$data); }
function wp_send_json_error($data,$status) { throw new JsonResult(false,$data,$status); }
function is_wp_error($v) { return $v instanceof WP_Error; }
function add_query_arg($params,$url) { return $url.'?'.http_build_query($params); }
function wp_remote_retrieve_response_code($res) { return $res['status']; }
function wp_remote_retrieve_body($res) { return json_encode($res['data']); }
function wp_remote_get($url,$args) {
    $GLOBALS['calls'][]=[$url,$args];
    check(strpos($url,$GLOBALS['options']['tmdb_read_token'])===false,'Token must not enter URLs');
    check($args['headers']['Authorization']==='Bearer '.$GLOBALS['options']['tmdb_read_token'],'Use server-side Bearer header');
    check($args['redirection']===0,'Do not forward token through redirects');
    return ['status'=>$GLOBALS['status'],'data'=>$GLOBALS['payload']];
}
function register_rest_route($namespace,$route,$args) { $GLOBALS['routes'][$route]=$args; }
function get_posts($args) { $GLOBALS['postQuery']=$args; return $GLOBALS['publicPosts']; }
function is_post_publicly_viewable($post) { return $post->post_status==='publish'; }
function get_the_title($post) { return 'Article '.$post->ID; }
function get_permalink($post) { return 'https://wp.example/'.$post->ID; }
function get_the_date(...$args) { return '2026-11-16'; }
function get_the_excerpt(...$args) { return 'Excerpt'; }
function wp_strip_all_tags($v) { return strip_tags($v); }
function get_the_post_thumbnail_url(...$args) { return ''; }
function wp_get_post_categories(...$args) { return ['Film']; }
function rest_ensure_response($data) { return new WP_REST_Response($data); }
require dirname(__DIR__,3).'/wordpress/wp-content/plugins/coma-game-articles/coma-game-articles.php';
function check($ok,$message) { if (!$ok) throw new RuntimeException($message); }
function search():JsonResult {
    $_GET=['query'=>'Alien'];
    try { COMA_Movie_Articles::ajax_search(); } catch(JsonResult $r) { return $r; }
    throw new RuntimeException('Missing response');
}
$allowed=false; check(search()->status===403,'Only editors may search'); $allowed=true;
$nonce=false; check(search()->status===403,'Search needs nonce'); $nonce=true;
$result=search(); check($result->success && count($result->data['movies'])===2,'Find movies by title');
check($result->data['movies'][0]===['id'=>11,'source'=>'tmdb','name'=>'Alien','year'=>'1979'],'Return film ID and release year');
check(strpos(json_encode($result->data),'tmdb-fixture')===false,'No token in search results');
$before=count($calls); search(); check(count($calls)===$before,'Successful results cached');
$options['tmdb_read_token']='changed-fixture'; search(); check(count($calls)>$before,'Token rotation invalidates search cache');
$cache=[]; $status=401; check(!search()->success,'Authentication failures do not fall back to games');
$status=429; check(strpos(search()->data['message'],'begränsar')!==false,'Rate limit has actionable message');
$status=200; $payload=['wrong'=>'shape']; check(!search()->success,'Reject invalid results');
$options['tmdb_read_token']=''; check(search()->status===503,'Missing token has explicit error');
$saved=COMA_Game_Articles::sanitize_settings(['tmdb_read_token'=>'new-token','preferred'=>'igdb']);
check($saved['tmdb_read_token']==='new-token' && $saved['rawg_key']==='rawg-fixture' && $saved['igdb_client_secret']==='secret-fixture','Saving TMDB keeps game credentials');
$options['tmdb_read_token']='keep-token';
check(COMA_Game_Articles::sanitize_settings([])['tmdb_read_token']==='keep-token','Blank settings retain token');
check(COMA_Game_Articles::sanitize_settings(['remove_tmdb_token'=>1])['tmdb_read_token']==='','Explicit removal clears token');
$_POST=['coma_movie_articles_nonce'=>'valid','coma_movie_id'=>'11','coma_movie_title'=>'Alien'];
COMA_Movie_Articles::save_post(7);
check($meta[7]['_coma_movie_source']==='tmdb' && $meta[7]['_coma_movie_id']===11,'Save movie source and ID');
check($meta[7]['_coma_game_source']==='igdb' && $meta[7]['_coma_game_id']===11,'Same numeric game ID remains separate');
COMA_Game_Articles::save_post(7);
check($meta[7]['_coma_movie_id']===11 && $meta[7]['_coma_game_id']===11,'Game save without game panel leaves associations intact');
$_POST=[]; COMA_Movie_Articles::save_post(7); check($meta[7]['_coma_movie_id']===11,'Quick edit without movie fields preserves selection');
$_POST=['coma_movie_articles_nonce'=>'valid','coma_movie_id'=>'12','coma_movie_title'=>'Other'];
$nonce=false; COMA_Movie_Articles::save_post(7); check($meta[7]['_coma_movie_id']===11,'Invalid save nonce denied'); $nonce=true;
$allowed=false; COMA_Movie_Articles::save_post(7); check($meta[7]['_coma_movie_id']===11,'Unauthorized save denied'); $allowed=true;
$revision=true; COMA_Movie_Articles::save_post(7); check($meta[7]['_coma_movie_id']===11,'Revision cannot change movie'); $revision=false;
foreach(['-1','0','11bad',str_repeat('9',30)] as $bad) {
    $_POST['coma_movie_id']=$bad; COMA_Movie_Articles::save_post(7); check($meta[7]['_coma_movie_id']===11,'Malformed ID preserves previous selection');
}
$_POST['coma_movie_id']=''; COMA_Movie_Articles::save_post(7);
check(!isset($meta[7]['_coma_movie_id']) && $meta[7]['_coma_game_id']===11,'Removing movie never removes game');
foreach([[1,'publish',''],[2,'draft',''],[3,'private',''],[4,'publish','secret']] as [$id,$state,$password]) {
    $publicPosts[]=(object)['ID'=>$id,'post_status'=>$state,'post_password'=>$password];
}
COMA_Movie_Articles::register_routes();
check(isset($routes['/movies/tmdb/(?P<id>\d+)/articles']),'Register separate movie endpoint');
$result=COMA_Movie_Articles::get_articles(['id'=>'11']);
check(count($result->data)===1 && $result->data[0]['movie']===['source'=>'tmdb','id'=>11],'Only public unprotected movies returned');
check($postQuery['meta_query'][0]['key']==='_coma_movie_source' && $postQuery['meta_query'][1]['key']==='_coma_movie_id','REST never searches game IDs');
check($postQuery['has_password']===false && $postQuery['post_status']==='publish','Public query excludes drafts and passwords');
check(is_wp_error(COMA_Movie_Articles::get_articles(['id'=>'-1'])),'REST rejects invalid IDs');
echo "Movie association regression tests passed.\n";
