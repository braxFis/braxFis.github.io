<?php
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }

/** RAWG discovery for the existing gamedefiners generate command. */
class GDES_Game_Auto {
    public static function run( $options, $author ) {
        $source = sanitize_key( $options['source'] ?? 'rawg' );
        if ( 'rawg' !== $source ) WP_CLI::error( 'Automatic game discovery currently supports only --source=rawg.' );
        $type = sanitize_key( $options['type'] ?? 'preview' );
        if ( ! in_array( $type, array( 'preview', 'news' ), true ) ) WP_CLI::error( 'Auto mode supports only preview or news, never guides or reviews.' );
        if ( 'news' === $type ) WP_CLI::error( 'RAWG does not provide independently sourced news announcements. Use --type=preview for now; news automation requires verified news sources.' );
        $max = isset( $options['max'] ) ? (int) $options['max'] : 3;
        $days = isset( $options['days'] ) ? (int) $options['days'] : 90;
        if ( $max < 1 || $max > 10 || $days < 1 || $days > 365 ) WP_CLI::error( 'Use --max=1..10 and --days=1..365.' );
        $dry = isset( $options['dry-run'] );
        $key = defined( 'RAWG_API_KEY' ) ? RAWG_API_KEY : getenv( 'RAWG_API_KEY' );
        if ( ! $key ) WP_CLI::error( 'RAWG_API_KEY is missing.' );
        $today = new DateTimeImmutable( 'today', wp_timezone() );
        $end = $today->modify( '+' . $days . ' days' );
        $made = 0; $skipped = 0; $failed = 0;
        for ( $page = 1; $page <= 3 && $made < $max; $page++ ) {
            $url = add_query_arg( array( 'key'=>$key, 'dates'=>$today->format('Y-m-d').','.$end->format('Y-m-d'), 'ordering'=>'-added', 'page_size'=>40, 'page'=>$page ), 'https://api.rawg.io/api/games' );
            $data = self::get_json( $url );
            if ( is_wp_error( $data ) ) WP_CLI::error( $data->get_error_message() );
            if ( empty( $data['results'] ) || ! is_array( $data['results'] ) ) break;
            foreach ( $data['results'] as $game ) {
                if ( $made >= $max ) break;
                $id = absint( $game['id'] ?? 0 );
                $name = sanitize_text_field( $game['name'] ?? '' );
                $date = (string) ( $game['released'] ?? '' );
                if ( ! $id || ! $name || ! preg_match('/^\d{4}-\d{2}-\d{2}$/D', $date) || $date < $today->format('Y-m-d') || $date > $end->format('Y-m-d') ) { $skipped++; continue; }
                $key_hash = hash( 'sha256', 'rawg:auto:preview:' . $id );
                if ( get_posts( array( 'post_type'=>'post', 'post_status'=>'any', 'numberposts'=>1, 'fields'=>'ids', 'meta_key'=>'_gdes_auto_game_key', 'meta_value'=>$key_hash ) ) ) { $skipped++; continue; }
                // RAWG's discovery response is too thin for a meaningful preview. Request detail first.
                $detail = self::get_json( add_query_arg( 'key', $key, 'https://api.rawg.io/api/games/' . $id ) );
                if ( is_wp_error( $detail ) ) { $failed++; WP_CLI::warning( $name . ': ' . $detail->get_error_message() ); continue; }
                $description = trim( wp_strip_all_tags( (string) ( $detail['description'] ?? '' ) ) );
                if ( mb_strlen( $description ) < 350 ) { $skipped++; WP_CLI::log( 'SKIP (insufficient information): ' . $name ); continue; }
                $slug = sanitize_title( (string) ( $detail['slug'] ?? $game['slug'] ?? '' ) );
                $source_url = $slug ? 'https://rawg.io/games/' . $slug : 'https://rawg.io/games/' . $id;
                $genres = implode( ', ', array_filter( array_map( static function($x) { return sanitize_text_field($x['name'] ?? ''); }, (array)($detail['genres'] ?? array()) ) ) );
                $platforms = implode( ', ', array_filter( array_map( static function($x) { return sanitize_text_field($x['platform']['name'] ?? ''); }, (array)($detail['platforms'] ?? array()) ) ) );
                $developers = implode( ', ', array_filter( array_map( static function($x) { return sanitize_text_field($x['name'] ?? ''); }, (array)($detail['developers'] ?? array()) ) ) );
                $publishers = implode( ', ', array_filter( array_map( static function($x) { return sanitize_text_field($x['name'] ?? ''); }, (array)($detail['publishers'] ?? array()) ) ) );
                $title = $name . ' — What We Know So Far';
                WP_CLI::log( ($dry ? 'WOULD GENERATE: ' : 'GENERATING: ') . $title . ' | ' . $date );
                if ( $dry ) { $made++; continue; }
                $facts = "RAWG database metadata, not independently verified:\nGame: {$name}\nListed release date: {$date}\nGenres: {$genres}\nPlatforms: {$platforms}\nDevelopers: {$developers}\nPublishers: {$publishers}\nRAWG listing: {$source_url}\nRAWG description:\n" . mb_substr( sanitize_textarea_field($description), 0, 6500 );
                $payload = array(
                    'title'=>$title, 'article_type'=>'preview', 'language'=>'en', 'length'=>'short',
                    'brief'=>'Write a concise, useful upcoming-game preview using ONLY the supplied RAWG metadata. Attribute uncertain facts to RAWG. Do not invent gameplay experience, trailers, studio statements, release territories, or announcements. Do not pad to reach a word count; flag facts needing official verification.',
                    'facts'=>$facts, 'tone'=>'Restrained, factual gaming journalism',
                );
                $post_id = GDES_Generator::generate( $payload, $author );
                if ( is_wp_error( $post_id ) ) { $failed++; WP_CLI::warning( $name . ': ' . $post_id->get_error_message() ); continue; }
                update_post_meta( $post_id, '_gdes_auto_game_key', $key_hash );
                update_post_meta( $post_id, '_gdes_source_url', esc_url_raw( $source_url ) );
                update_post_meta( $post_id, '_gdes_release_date', $date );
                update_post_meta( $post_id, '_gdes_auto_source', 'rawg' );
                $made++;
                WP_CLI::log( 'DRAFT: ' . $name . ' (post ' . $post_id . ')' );
            }
            if ( empty( $data['next'] ) ) break;
        }
        WP_CLI::success( ($dry ? 'Dry run: ' : '') . $made . ' previews ' . ($dry ? 'selected' : 'created') . "; {$skipped} skipped; {$failed} failures." );
    }
    private static function get_json( $url ) {
        $response = wp_remote_get( $url, array( 'timeout'=>20, 'headers'=>array('Accept'=>'application/json') ) );
        if ( is_wp_error( $response ) ) return $response;
        $status = (int) wp_remote_retrieve_response_code( $response );
        if ( 200 !== $status ) return new WP_Error( 'gdes_rawg_http', 'RAWG returned HTTP ' . $status );
        $data = json_decode( wp_remote_retrieve_body( $response ), true );
        return is_array( $data ) ? $data : new WP_Error( 'gdes_rawg_json', 'RAWG returned invalid JSON.' );
    }
}
