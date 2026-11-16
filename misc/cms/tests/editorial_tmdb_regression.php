<?php
declare(strict_types=1);
define('ABSPATH', __DIR__);
define('MINUTE_IN_SECONDS', 60);
$options = ['api_key'=>'openai-fixture', 'tmdb_read_token'=>'tmdb-fixture'];
$cache=[]; $calls=[]; $posts=[]; $meta=[]; $allowed=true; $nonce=true; $httpStatus=200;
$movie=['id'=>11,'title'=>'Fixture Film','release_date'=>'2027-01-01','overview'=>'Fixture synopsis',
    'genres'=>[['name'=>'Drama']], 'credits'=>['crew'=>[['name'=>'Fixture Director','job'=>'Director']], 'cast'=>[['name'=>'Fixture Actor']]],
    'production_companies'=>[['name'=>'Fixture Studio']], 'runtime'=>100];
class WP_Error {
    public function __construct(public $code, private $message) {}
    public function get_error_message() { return $this->message; }
}
class JsonResult extends Exception {
    public function __construct(public bool $success, public array $data, public int $status=200) { parent::__construct(); }
}
function add_action(...$args) {}
function get_option($name, $default=[]) { return $GLOBALS['options']; }
function wp_parse_args($a,$b) { return array_merge($b,$a); }
function current_user_can(...$args) { return $GLOBALS['allowed']; }
function check_ajax_referer(...$args) { return $GLOBALS['nonce']; }
function wp_unslash($v) { return $v; }
function sanitize_text_field($v) { return trim(strip_tags((string)$v)); }
function sanitize_textarea_field($v) { return sanitize_text_field($v); }
function sanitize_key($v) { return strtolower((string)$v); }
function wp_json_encode($v) { return json_encode($v); }
function get_transient($k) { return $GLOBALS['cache'][$k] ?? false; }
function set_transient($k,$v,$ttl) { $GLOBALS['cache'][$k]=$v; }
function wp_send_json_success($data) { throw new JsonResult(true,$data); }
function wp_send_json_error($data,$status) { throw new JsonResult(false,$data,$status); }
function is_wp_error($v) { return $v instanceof WP_Error; }
function add_query_arg($params,$url) { return $url.'?'.http_build_query($params); }
function wp_remote_retrieve_response_code($r) { return $r['status']; }
function wp_remote_retrieve_body($r) { return json_encode($r['data']); }
function wp_remote_get($url,$args) {
    $GLOBALS['calls'][]=[$url,$args];
    check(strpos($url,'tmdb-fixture')===false, 'Bearer token must never be in URL');
    check(($args['headers']['Authorization'] ?? '')==='Bearer '.$GLOBALS['options']['tmdb_read_token'], 'Server sends bearer header');
    check($args['redirection']===0, 'No authenticated redirects');
    if ($GLOBALS['httpStatus']!==200) return ['status'=>$GLOBALS['httpStatus'],'data'=>['status_message'=>'secret upstream body']];
    if (strpos($url,'search/movie')!==false) return ['status'=>200,'data'=>['results'=>[$GLOBALS['movie']]]];
    if (strpos($url,'discover/movie')!==false) return ['status'=>200,'data'=>['results'=>[]]];
    return ['status'=>200,'data'=>$GLOBALS['movie']];
}
function wp_remote_post($url,$args) {
    $GLOBALS['aiBody']=json_decode($args['body'],true);
    $GLOBALS['aiCalls']=($GLOBALS['aiCalls'] ?? 0)+1;
    return ['status'=>200,'data'=>['status'=>'completed','output_text'=>'<p>'.str_repeat('Supported background text for a draft. ',30).'</p>']];
}
function wp_insert_post($post,$error) { $GLOBALS['posts'][]=$post; return count($GLOBALS['posts']); }
function update_post_meta($id,$key,$value) { $GLOBALS['meta'][$id][$key]=$value; }
function wp_kses($text,$allowed) { return strip_tags($text,'<p><h2>'); }
function wp_strip_all_tags($text) { return strip_tags($text); }
function __($s,$domain='') { return $s; }
function check($ok,$message) { if (!$ok) throw new RuntimeException($message); }
$plugin=dirname(__DIR__,3).'/wordpress/wp-content/plugins/gamedefiners-editorial-studio/';
foreach (['tmdb','movie-reporter','plugin','openai','generator'] as $part) require $plugin.'includes/class-gdes-'.$part.'.php';
function search() {
    $_GET=['query'=>'Fixture','language'=>'sv'];
    try { GDES_TMDB::ajax_search(); } catch (JsonResult $r) { return $r; }
    throw new RuntimeException('No AJAX response');
}
$allowed=false; check(search()->status===403,'Reject non-editors'); $allowed=true;
$nonce=false; check(search()->status===403,'Require nonce'); $nonce=true;
$r=search(); check($r->success && $r->data['movies'][0]['id']===11,'Return TMDB films');
check(strpos(json_encode($r->data),'tmdb-fixture')===false,'Never expose credentials to JavaScript');
$before=count($calls); search(); check(count($calls)===$before,'Reuse cache');
$options['tmdb_read_token']='rotated-token'; search(); check(count($calls)>$before,'Invalidate cache after token changes');
$payload=['title'=>'Film article','article_type'=>'preview','reporter_role'=>'movie','tmdb_id'=>'11',
    'language'=>'sv','length'=>'short','brief'=>'Explain the upcoming film using supplied facts.','facts'=>'Editorial source note.'];
$id=GDES_Generator::generate($payload,7);
check(!is_wp_error($id) && $posts[0]['post_status']==='draft','Creates draft only');
check($meta[$id]['_gdes_tmdb_id']===11 && $meta[$id]['_gdes_reporter_role']==='movie','Persist movie reference separately');
check(strpos($aiBody['input'],'Fixture Director')!==false && strpos($aiBody['input'],'Editorial source note.')!==false,'Pass fetched facts and retain editorial notes');
check(strpos($aiBody['instructions'],'PROFESSIONAL MOVIE NEWS REPORTER')!==false,'Use movie reporting policy');
check(strpos($aiBody['instructions'],'Write the article in Swedish')!==false,'Honor selected language');
check(strpos($aiBody['input'],'Film preview')!==false && strpos($aiBody['input'],'Game preview')===false,'No gaming article label for films');
$cache=[]; $httpStatus=401; $before=$aiCalls;
$error=GDES_Generator::generate($payload,7);
check(is_wp_error($error) && $aiCalls===$before && count($posts)===1,'Do not call OpenAI or save a post after TMDB failure');
check(strpos($error->get_error_message(),'secret upstream body')===false,'Do not leak upstream response');
$httpStatus=200; $cache=[]; $movie['id']=12;
check(is_wp_error(GDES_TMDB::detail(11)),'Reject mismatched movie ID');
$cache=[]; $options['tmdb_read_token']='';
check(is_wp_error(GDES_TMDB::detail(11)),'Missing token gives explicit error');
$payload['reporter_role']='game'; $payload['tmdb_id']=''; $payload['title']='Game article';
check(!is_wp_error(GDES_Generator::generate($payload,7)),'Existing game generation still works without TMDB');
$payload['reporter_role']='movie'; $payload['article_type']='review'; $payload['hands_on_notes']='';
check(is_wp_error(GDES_Generator::generate($payload,7)),'Film reviews need viewing notes');
echo "Editorial TMDB regression tests passed.\n";
