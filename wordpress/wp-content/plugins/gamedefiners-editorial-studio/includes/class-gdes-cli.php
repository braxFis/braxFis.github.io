<?php
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { return; }

class GDES_CLI {
    /**
     * Generate an AI-assisted WordPress draft.
     *
     * ## OPTIONS
     *
     * [--title=<title>]
     * : Article title.
     *
     * [--brief=<brief>]
     * : Editorial angle/instructions.
     *
     * [--type=<type>]
     * : news, preview, review, guide or list. Default: news.
     *
     * [--language=<language>]
     * : sv or en. Default: sv.
     *
     * [--length=<length>]
     * : short, medium or long. Default: medium.
     *
     * [--tone=<tone>]
     * : Optional tone.
     *
     * [--facts=<facts>]
     * : Optional supplied facts.
     *
     * [--hands-on-notes=<notes>]
     * : Required for reviews.
     *
     * [--auto]
     * : Discover upcoming games through RAWG; generates previews only.
     *
     * [--source=<source>]
     * : Auto mode source. Currently rawg only.
     *
     * [--max=<count>]
     * : Maximum drafts in auto mode (1-10, default 3).
     *
     * [--days=<days>]
     * : Upcoming release window in auto mode (1-365, default 90).
     *
     * [--dry-run]
     * : Show auto selections without OpenAI calls or posts.
     *
     * [--author=<id>]
     * : WordPress user ID with edit_posts permission.
     */
    public function generate( $args, $assoc_args ) {
        $author = isset( $assoc_args['author'] ) ? absint( $assoc_args['author'] ) : 0;
        if ( ! $author || ! get_user_by( 'id', $author ) || ! user_can( $author, 'edit_posts' ) ) {
            WP_CLI::error( 'Ange --author med ett giltigt användar-ID som får redigera inlägg.' );
        }
        if ( isset( $assoc_args['auto'] ) ) {
            GDES_Game_Auto::run( $assoc_args, $author );
            return;
        }
        $payload = array(
            'title' => sanitize_text_field( $assoc_args['title'] ?? '' ),
            'article_type' => sanitize_key( $assoc_args['type'] ?? 'news' ),
            'brief' => sanitize_textarea_field( $assoc_args['brief'] ?? '' ),
            'language' => ( ( $assoc_args['language'] ?? 'sv' ) === 'en' ) ? 'en' : 'sv',
            'length' => sanitize_key( $assoc_args['length'] ?? 'medium' ),
            'tone' => sanitize_text_field( $assoc_args['tone'] ?? '' ),
            'facts' => sanitize_textarea_field( $assoc_args['facts'] ?? '' ),
            'hands_on_notes' => sanitize_textarea_field( $assoc_args['hands-on-notes'] ?? '' ),
        );
        if ( ! in_array( $payload['length'], array( 'short', 'medium', 'long' ), true ) ) {
            WP_CLI::error( 'Ogiltig --length.' );
        }
        $post_id = GDES_Generator::generate( $payload, $author );
        if ( is_wp_error( $post_id ) ) { WP_CLI::error( $post_id->get_error_message() ); }
        WP_CLI::success( 'Utkast skapat. Inläggs-ID: ' . $post_id );
    }
}
WP_CLI::add_command( 'gamedefiners', 'GDES_CLI' );
