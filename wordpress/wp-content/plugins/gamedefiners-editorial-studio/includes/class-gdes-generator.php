<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class GDES_Generator {
    public static function generate( $payload, $author_id ) {
        $title = sanitize_text_field( $payload['title'] ?? '' );
        $type = sanitize_key( $payload['article_type'] ?? 'news' );
        if ( '' === $title || ! in_array( $type, array( 'news', 'preview', 'review', 'guide', 'list' ), true ) ) {
            return new WP_Error( 'gdes_invalid', 'Ange en rubrik och en giltig artikeltyp.' );
        }
        $payload['title'] = $title;
        $payload['article_type'] = $type;
        if ( '' === trim( $payload['brief'] ?? '' ) ) {
            return new WP_Error( 'gdes_brief', 'En artikelvinkel/instruktion krävs.' );
        }
        if ( 'review' === $type && '' === trim( $payload['hands_on_notes'] ?? '' ) ) {
            return new WP_Error( 'gdes_review', 'Recensioner kräver egna test- eller visningsanteckningar.' );
        }
        $role = isset( $payload['reporter_role'] ) && 'movie' === $payload['reporter_role'] ? 'movie' : 'game';
        $payload['reporter_role'] = $role;
        $movie = null;
        if ( 'movie' === $role && isset( $payload['tmdb_id'] ) && '' !== trim( (string) $payload['tmdb_id'] ) ) {
            $movie = GDES_TMDB::detail( $payload['tmdb_id'], $payload['language'] ?? 'sv' );
            if ( is_wp_error( $movie ) ) { return $movie; }
            $payload['facts'] = (string) ( $payload['facts'] ?? '' ) . "\n\n" . GDES_TMDB::facts( $movie );
        }
        $saved = get_option( 'gdes_settings', array() );
        $settings = wp_parse_args( is_array( $saved ) ? $saved : array(), GDES_Plugin::default_settings() );
        if ( function_exists( 'set_time_limit' ) ) { @set_time_limit( 180 ); }
        $content = GDES_OpenAI::generate_article( $payload, $settings );
        if ( is_wp_error( $content ) ) { return $content; }
        $post_id = wp_insert_post( array(
            'post_type' => 'post', 'post_status' => 'draft',
            'post_title' => $title, 'post_content' => $content,
            'post_author' => (int) $author_id,
        ), true );
        if ( is_wp_error( $post_id ) ) { return $post_id; }
        update_post_meta( $post_id, '_gdes_reporter_role', $role );
        if ( is_array( $movie ) ) {
            update_post_meta( $post_id, '_gdes_tmdb_id', (int) $movie['id'] );
            update_post_meta( $post_id, '_gdes_source_url', 'https://www.themoviedb.org/movie/' . (int) $movie['id'] );
        }
        update_post_meta( $post_id, '_gdes_article_type', $type );
        update_post_meta( $post_id, '_gdes_ai_draft', '1' );
        return $post_id;
    }
}
