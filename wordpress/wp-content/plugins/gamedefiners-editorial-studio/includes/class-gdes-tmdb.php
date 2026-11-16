<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Server-side TMDB movie lookup. No credentials or arbitrary upstream URLs reach JavaScript. */
class GDES_TMDB {
    public static function boot() {
        add_action( 'wp_ajax_gdes_tmdb_search', array( __CLASS__, 'ajax_search' ) );
    }

    public static function token() {
        if ( defined( 'GDES_TMDB_READ_TOKEN' ) && GDES_TMDB_READ_TOKEN ) {
            return trim( (string) GDES_TMDB_READ_TOKEN );
        }
        $env = getenv( 'TMDB_API_READ_TOKEN' );
        if ( $env ) { return trim( $env ); }
        $settings = get_option( 'gdes_settings', array() );
        return is_array( $settings ) ? trim( (string) ( $settings['tmdb_read_token'] ?? '' ) ) : '';
    }

    /** Keep the pre-existing release scanner's v3 API-key configuration working. */
    private static function api_key() {
        return trim( (string) ( defined( 'TMDB_API_KEY' ) ? TMDB_API_KEY : getenv( 'TMDB_API_KEY' ) ) );
    }

    public static function request( $path, $params = array() ) {
        if ( ! preg_match( '#^/(?:search/movie|discover/movie|movie/[1-9][0-9]*)$#D', $path ) ) {
            return new WP_Error( 'gdes_tmdb_path', 'Ogiltigt TMDB-anrop.' );
        }
        $token = self::token();
        $key = self::api_key();
        if ( '' === $token && '' === $key ) {
            return new WP_Error( 'gdes_tmdb_credentials', 'TMDB saknar åtkomst. Lägg in API Read Access Token under Editorial Studio → Inställningar.' );
        }
        $headers = array( 'Accept' => 'application/json' );
        if ( $token ) { $headers['Authorization'] = 'Bearer ' . $token; }
        else { $params['api_key'] = $key; }
        $cache_key = 'gdes_tmdb_' . hash( 'sha256', $path . wp_json_encode( $params ) . $token . ':' . $key );
        $cached = get_transient( $cache_key );
        if ( is_array( $cached ) ) { return $cached; }
        $response = wp_remote_get( add_query_arg( $params, 'https://api.themoviedb.org/3' . $path ),
            array( 'timeout' => 15, 'redirection' => 0, 'headers' => $headers, 'limit_response_size' => 2000000 ) );
        if ( is_wp_error( $response ) ) {
            return new WP_Error( 'gdes_tmdb_network', 'TMDB kunde inte nås. Försök igen senare.' );
        }
        $status = (int) wp_remote_retrieve_response_code( $response );
        if ( 200 !== $status ) {
            $message = in_array( $status, array( 401, 403 ), true )
                ? 'TMDB nekade åtkomst. Kontrollera ditt API Read Access Token.'
                : ( 429 === $status ? 'TMDB har begränsat anropen. Vänta en stund och försök igen.' : 'TMDB svarade med HTTP ' . $status . '.' );
            return new WP_Error( 'gdes_tmdb_http', $message );
        }
        $data = json_decode( wp_remote_retrieve_body( $response ), true );
        if ( ! is_array( $data ) || ( isset( $data['success'] ) && false === $data['success'] ) ) {
            return new WP_Error( 'gdes_tmdb_json', 'TMDB returnerade ett ogiltigt svar.' );
        }
        // Cache only validated successful API objects; never cache error responses.
        if ( '/search/movie' === $path || '/discover/movie' === $path ) {
            if ( ! isset( $data['results'] ) || ! is_array( $data['results'] ) ) {
                return new WP_Error( 'gdes_tmdb_json', 'TMDB returnerade en ogiltig resultatlista.' );
            }
        } elseif ( empty( $data['id'] ) || (int) $data['id'] !== (int) basename( $path ) || empty( $data['title'] ) ) {
            return new WP_Error( 'gdes_tmdb_json', 'TMDB returnerade fel film eller ett ofullständigt svar.' );
        }
        set_transient( $cache_key, $data, 5 * MINUTE_IN_SECONDS );
        return $data;
    }

    public static function ajax_search() {
        if ( ! current_user_can( 'edit_posts' ) || ! check_ajax_referer( 'gdes_tmdb_search', 'nonce', false ) ) {
            wp_send_json_error( array( 'message' => 'Åtkomst nekad.' ), 403 );
            return;
        }
        $query = isset( $_GET['query'] ) && is_string( $_GET['query'] ) ? trim( sanitize_text_field( wp_unslash( $_GET['query'] ) ) ) : '';
        if ( strlen( $query ) < 2 || strlen( $query ) > 120 ) {
            wp_send_json_error( array( 'message' => 'Skriv mellan 2 och 120 tecken.' ), 400 );
            return;
        }
        $language = isset( $_GET['language'] ) && 'en' === $_GET['language'] ? 'en-US' : 'sv-SE';
        $data = self::request( '/search/movie', array( 'query' => $query, 'language' => $language, 'include_adult' => 'false', 'page' => 1 ) );
        if ( is_wp_error( $data ) ) {
            wp_send_json_error( array( 'message' => $data->get_error_message() ), 502 );
            return;
        }
        $movies = array();
        foreach ( array_slice( $data['results'], 0, 10 ) as $movie ) {
            if ( ! is_array( $movie ) || empty( $movie['id'] ) || (int) $movie['id'] < 1 || empty( $movie['title'] ) || ! empty( $movie['adult'] ) ) { continue; }
            $movies[] = array( 'id' => (int) $movie['id'], 'title' => sanitize_text_field( $movie['title'] ),
                'year' => preg_match( '/^[0-9]{4}-/', (string) ( $movie['release_date'] ?? '' ) ) ? substr( $movie['release_date'], 0, 4 ) : '',
                'overview' => sanitize_textarea_field( (string) ( $movie['overview'] ?? '' ) ) );
        }
        wp_send_json_success( array( 'movies' => $movies ) );
    }

    public static function detail( $id, $language = 'sv' ) {
        if ( ! ctype_digit( (string) $id ) || (int) $id < 1 ) {
            return new WP_Error( 'gdes_tmdb_id', 'Välj en giltig TMDB-film.' );
        }
        return self::request( '/movie/' . (int) $id, array( 'language' => 'en' === $language ? 'en-US' : 'sv-SE',
            'append_to_response' => 'credits,release_dates,videos' ) );
    }

    public static function facts( $movie ) {
        $names = static function ( $rows ) {
            $result = array();
            foreach ( (array) $rows as $row ) {
                if ( is_array( $row ) && ! empty( $row['name'] ) ) { $result[] = sanitize_text_field( $row['name'] ); }
            }
            return implode( ', ', $result );
        };
        $directors = array();
        foreach ( (array) ( $movie['credits']['crew'] ?? array() ) as $person ) {
            if ( is_array( $person ) && 'Director' === ( $person['job'] ?? '' ) ) { $directors[] = $person; }
        }
        return "TMDB metadata — supporting background, NOT independently verified reporting. Do not treat this database lookup as a new announcement.\n"
            . 'Film: ' . sanitize_text_field( $movie['title'] ) . "\n"
            . 'TMDB ID: ' . (int) $movie['id'] . "\n"
            . 'Primary release date (territory not confirmed): ' . sanitize_text_field( $movie['release_date'] ?? '' ) . "\n"
            . 'Synopsis: ' . sanitize_textarea_field( $movie['overview'] ?? '' ) . "\n"
            . 'Genres: ' . $names( $movie['genres'] ?? array() ) . "\n"
            . 'Director: ' . $names( $directors ) . "\n"
            . 'Cast: ' . $names( array_slice( (array) ( $movie['credits']['cast'] ?? array() ), 0, 10 ) ) . "\n"
            . 'Production companies (not necessarily distributor/publisher): ' . $names( $movie['production_companies'] ?? array() ) . "\n"
            . 'Runtime in minutes: ' . (int) ( $movie['runtime'] ?? 0 ) . "\n"
            . 'Source: https://www.themoviedb.org/movie/' . (int) $movie['id'] . "\n";
    }
}
