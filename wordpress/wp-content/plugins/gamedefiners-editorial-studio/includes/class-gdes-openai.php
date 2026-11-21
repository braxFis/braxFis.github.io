<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/** Handles the server-side OpenAI Responses API request. */
class GDES_OpenAI {
    /** Return configured key, preferring wp-config.php constant. */
    public static function get_api_key() {
        if ( defined( 'GDES_OPENAI_API_KEY' ) && GDES_OPENAI_API_KEY ) {
            return trim( (string) GDES_OPENAI_API_KEY );
        }
        $settings = get_option( 'gdes_settings', array() );
        return isset( $settings['api_key'] ) ? trim( (string) $settings['api_key'] ) : '';
    }

    /** Generate an article draft and return its HTML content or WP_Error. */
    public static function generate_article( $payload, $settings ) {
        $api_key = self::get_api_key();
        if ( '' === $api_key ) {
            return new WP_Error( 'gdes_missing_key', __( 'OpenAI API key is missing. Add it under Editorial Studio → Settings.', 'gamedefiners-editorial-studio' ) );
        }

        $model = ! empty( $settings['model'] ) ? $settings['model'] : 'gpt-6.1-terra';
        $criteria = isset( $settings['review_criteria'] ) && is_array( $settings['review_criteria'] ) ? $settings['review_criteria'] : array();
        $type_labels = array(
            'news'    => 'News article',
            'preview' => 'Game preview',
            'review'  => 'Game review',
            'guide'   => 'Game guide',
            'list'    => 'List article',
        );
        $article_type = isset( $type_labels[ $payload['article_type'] ] ) ? $type_labels[ $payload['article_type'] ] : 'Editorial article';

        $system = "You are the editorial writing assistant for GameDefiners, a gaming publication. Create a useful, original and readable article draft.\n"
            . "NON-NEGOTIABLE FACT RULES:\n"
            . "- Use supplied facts as primary editorial evidence. You may also use well-established background knowledge about the named game, but never imply that you performed live research or independently verified facts. Do not invent release dates, prices, platforms, quotes, gameplay details, or updates.\n"
            . "- If a fact is missing or uncertain, omit it or describe the uncertainty naturally. Never output [VERIFY] or editorial placeholders.\n"
            . "- Never invent sources, citations, interviews, quotes, hands-on experience or events.\n"
            . "- For reviews, do not claim the writer played or tested anything beyond the supplied hands-on notes. Do not invent a verdict or score. The human editor makes the final assessment.\n"
            . "- Distinguish confirmed information from expectations, interpretation and speculation.\n"
            . "OUTPUT RULES:\n"
            . "- Return article body only as clean semantic HTML. Use only h2, h3, p, ul, ol, li, blockquote, strong and em tags. Do not include html/head/body wrappers, markdown fences, an H1, or a separate title.\n"
            . "- Do not add a generic AI disclaimer. Do not pad the article or repeat the introduction.\n"
            . "- Prefer supplied material for factual claims; do not fabricate citations or pretend that general writing instructions are game-specific sources.\n";

        if ( ! empty( $payload['reporter_role'] ) && 'movie' === $payload['reporter_role'] ) {
            $system = GDES_Movie_Reporter::instructions();
            if ( ! empty( $settings['movie_reporter_instructions'] ) ) {
                $system .= "\nADDITIONAL MOVIE EDITOR INSTRUCTIONS:\n" . $settings['movie_reporter_instructions'] . "\n";
            }
        }

        if ( ! empty( $settings['editorial_instructions'] ) ) {
            $system .= "\nADDITIONAL EDITORIAL GUIDANCE FROM THE EDITOR:\n" . $settings['editorial_instructions'] . "\n";
        }

        $word_targets = array(
            'short'  => 'approximately 600–800 words',
            'medium' => 'approximately 900–1,200 words',
            'long'   => 'approximately 1,400–1,800 words',
        );
        $length = isset( $word_targets[ $payload['length'] ] ) ? $word_targets[ $payload['length'] ] : $word_targets['medium'];
        $tone = isset( $payload['tone'] ) ? $payload['tone'] : 'Engaging and professional';
        $language = ( 'en' === $payload['language'] ) ? 'English' : 'Swedish';

        $publication = (!empty($payload['reporter_role']) && $payload['reporter_role'] === 'movie') ? 'an independent entertainment publication' : 'GameDefiners';
        $user = "Create a {$article_type} draft for {$publication}.\n"
            . "Working title: {$payload['title']}\n"
            . "Language: {$language}\n"
            . "Target length: {$length}\n"
            . "Tone: {$tone}\n\n"
            . "EDITOR'S ANGLE / BRIEF:\n{$payload['brief']}\n\n"
            . "OPTIONAL EDITOR-SUPPLIED FACTS AND SOURCE NOTES (may contain only general instructions; not independently verified by this plugin):\n{$payload['facts']}\n";

        if ( 'review' === $payload['article_type'] ) {
            $user .= "\nHANDS-ON NOTES FROM THE WRITER:\n{$payload['hands_on_notes']}\n\n"
                . "This is a review draft. Stay strictly within the writer's notes. If the notes do not support a personal experience claim, omit it. Do not manufacture a personal experience or final score. The review scorecard will be added and completed separately in WordPress.\n";
            if ( ! empty( $criteria ) ) {
                $user .= "The publication's standard review criteria are: " . implode( '; ', array_map( 'sanitize_text_field', $criteria ) ) . ". Do not assign scores to them.\n";
            }
        }

        $request_body = array(
            'model'             => $model,
            'instructions'      => $system,
            'input'             => $user,
            'max_output_tokens' => 5000,
        );

        $response = wp_remote_post(
            'https://api.openai.com/v1/responses',
            array(
                'timeout'     => 120,
                'redirection' => 0,
                'headers'     => array(
                    'Authorization' => 'Bearer ' . $api_key,
                    'Content-Type'  => 'application/json',
                ),
                'body'        => wp_json_encode( $request_body ),
                'data_format' => 'body',
            )
        );

        if ( is_wp_error( $response ) ) {
            return new WP_Error( 'gdes_http_error', 'Could not connect to OpenAI: ' . $response->get_error_message() );
        }

        $status = (int) wp_remote_retrieve_response_code( $response );
        $body   = json_decode( wp_remote_retrieve_body( $response ), true );
        if ( $status < 200 || $status >= 300 ) {
            $message = 'OpenAI returned HTTP ' . $status . '.';
            if ( is_array( $body ) && ! empty( $body['error']['message'] ) ) {
                $message .= ' ' . sanitize_text_field( $body['error']['message'] );
            }
            return new WP_Error( 'gdes_api_error', $message );
        }

        if ( ! is_array( $body ) ) {
            return new WP_Error( 'gdes_invalid_response', 'OpenAI returned an unreadable response.' );
        }

        $text = '';
        if ( ! empty( $body['output_text'] ) && is_string( $body['output_text'] ) ) {
            $text = $body['output_text'];
        } elseif ( ! empty( $body['output'] ) && is_array( $body['output'] ) ) {
            foreach ( $body['output'] as $item ) {
                if ( empty( $item['content'] ) || ! is_array( $item['content'] ) ) {
                    continue;
                }
                foreach ( $item['content'] as $part ) {
                    if ( isset( $part['type'], $part['text'] ) && 'output_text' === $part['type'] ) {
                        $text .= $part['text'];
                    }
                }
            }
        }

        // Do not save partial output as a finished article.
        if ( isset( $body['status'] ) && 'completed' !== $body['status'] ) {
            $reason = isset( $body['incomplete_details']['reason'] ) ? sanitize_text_field( $body['incomplete_details']['reason'] ) : sanitize_text_field( $body['status'] );
            return new WP_Error( 'gdes_incomplete', 'OpenAI response was not completed (' . $reason . '). No draft was saved.' );
        }
        $text = trim( $text );
        if ( '' === $text ) {
            return new WP_Error( 'gdes_empty_response', 'OpenAI did not return article text. Try again or use another model.' );
        }

        // Strip accidental fenced wrappers, then allow only safe post HTML.
        $text = preg_replace( '/^```(?:html)?\s*/i', '', $text );
        $text = preg_replace( '/\s*```$/', '', $text );
        $allowed = array(
            'h2' => array(), 'h3' => array(), 'p' => array(), 'ul' => array(), 'ol' => array(), 'li' => array(),
            'blockquote' => array(), 'strong' => array(), 'em' => array(),
        );
        $text = wp_kses( $text, $allowed );
        if ( '' === trim( wp_strip_all_tags( $text ) ) ) {
            return new WP_Error( 'gdes_empty_html', 'The generated response contained no usable article text.' );
        }
        // Reject accidental short fragments, but do not demand a fixed word count
        // when the supplied facts genuinely do not support a long article.
        $plain = trim( wp_strip_all_tags( $text ) );
        if ( str_word_count( $plain ) < 100 ) {
            return new WP_Error( 'gdes_too_short', 'The model returned fewer than 100 words. No draft was saved. Check model settings and prompt inputs.' );
        }
        return $text;
    }
}
