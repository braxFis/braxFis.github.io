<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
/** Image workflow for Editorial Studio. Never runs automatically during article/bulk generation. */
class GDES_Images {
    const OPTION = 'gdes_image_settings';
    const DEFAULT_PROMPT = 'Create an original, high-quality cinematic editorial illustration for GameDefiners, based on the article title and excerpt below. Adapt the visual language to the genre and subject. Do not pretend to depict actual gameplay, characters, events, or locations from the named game. Do not reproduce official artwork. No text, logos, captions or watermarks. Landscape composition, strong focal point, suitable for a gaming website featured image.';
    public static function boot() {
        add_action( 'admin_menu', array( __CLASS__, 'menu' ), 35 );
        add_action( 'admin_post_gdes_image_settings', array( __CLASS__, 'save' ) );
        add_action( 'admin_post_gdes_image_action', array( __CLASS__, 'action' ) );
    }
    public static function menu() {
        add_submenu_page( 'gdes-editorial', 'Bilder', 'Bilder', 'edit_posts', 'gdes-images', array( __CLASS__, 'page' ) );
    }
    private static function settings() {
        return wp_parse_args( get_option( self::OPTION, array() ), array( 'prompt' => self::DEFAULT_PROMPT, 'model' => 'gpt-image-1.5', 'quality' => 'medium', 'rawg_key' => '', 'max_usd' => '5' ) );
    }
    private static function redirect( $notice, $post = 0 ) {
        wp_safe_redirect( add_query_arg( array( 'page' => 'gdes-images', 'gdes_img_notice' => $notice, 'post_id' => absint( $post ) ), admin_url( 'admin.php' ) ) ); exit;
    }
    public static function save() {
        if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Behörighet saknas.' ); }
        check_admin_referer( 'gdes_image_settings' );
        $old = self::settings();
        $model = sanitize_text_field( wp_unslash( $_POST['model'] ?? 'gpt-image-1.5' ) );
        $new = array(
            'prompt' => sanitize_textarea_field( wp_unslash( $_POST['prompt'] ?? self::DEFAULT_PROMPT ) ),
            'model' => in_array( $model, array( 'gpt-image-1.5', 'gpt-image-1', 'gpt-image-1-mini' ), true ) ? $model : 'gpt-image-1.5',
            'quality' => in_array( $_POST['quality'] ?? '', array( 'low', 'medium', 'high' ), true ) ? sanitize_key( $_POST['quality'] ) : 'medium',
            'rawg_key' => $old['rawg_key'],
            'max_usd' => (string) max( 0, min( 100, (float) ( $_POST['max_usd'] ?? 5 ) ) ),
        );
        if ( isset( $_POST['rawg_key'] ) && trim( (string) wp_unslash( $_POST['rawg_key'] ) ) !== '' ) { $new['rawg_key'] = sanitize_text_field( wp_unslash( $_POST['rawg_key'] ) ); }
        if ( ! empty( $_POST['clear_rawg_key'] ) ) { $new['rawg_key'] = ''; }
        update_option( self::OPTION, $new, false ); self::redirect( 'Inställningarna sparades.' );
    }
    private static function selected_post( $id ) {
        $post = get_post( absint( $id ) );
        return $post && $post->post_type === 'post' && current_user_can( 'edit_post', $post->ID ) ? $post : null;
    }
    private static function cost( $model, $quality ) {
        // Conservative approximate estimates, NOT live provider pricing or a hard spending guarantee.
        $prices = array( 'gpt-image-1.5' => array( 'low' => .013, 'medium' => .05, 'high' => .20 ), 'gpt-image-1' => array( 'low' => .011, 'medium' => .042, 'high' => .167 ), 'gpt-image-1-mini' => array( 'low' => .005, 'medium' => .015, 'high' => .05 ) );
        return $prices[$model][$quality] ?? .20;
    }
    public static function page() {
        if ( ! current_user_can( 'edit_posts' ) ) { return; }
        $s = self::settings(); $id = absint( $_GET['post_id'] ?? 0 ); $post = self::selected_post( $id );
        $posts = get_posts( array( 'post_type' => 'post', 'post_status' => array( 'draft', 'pending', 'future', 'publish' ), 'posts_per_page' => 100, 'orderby' => 'modified', 'order' => 'DESC', 'meta_query' => array( array( 'key' => '_thumbnail_id', 'compare' => 'NOT EXISTS' ) ) ) );
        echo '<div class="wrap"><h1>Editorial Studio → Bilder</h1><p>Välj en artikel. Inga bilder genereras eller köps automatiskt. AI-bilder är illustrationer, inte autentiska spelbilder.</p>';
        if ( isset( $_GET['gdes_img_notice'] ) ) { echo '<div class="notice notice-info"><p>' . esc_html( sanitize_text_field( wp_unslash( $_GET['gdes_img_notice'] ) ) ) . '</p></div>'; }
        echo '<form method="get"><input type="hidden" name="page" value="gdes-images"><label for="gdes-post">Artikel: </label><select name="post_id" id="gdes-post"><option value="">Välj artikel</option>';
        if ( $post && has_post_thumbnail( $post ) ) { echo '<option selected value="' . esc_attr( $post->ID ) . '">' . esc_html( get_the_title( $post ) ) . ' (har bild)</option>'; }
        foreach ( $posts as $p ) { echo '<option value="' . esc_attr( $p->ID ) . '" ' . selected( $id, $p->ID, false ) . '>' . esc_html( get_the_title( $p ) ) . '</option>'; }
        echo '</select> <button class="button">Visa</button></form>';
        if ( $post ) {
            echo '<hr><h2>' . esc_html( get_the_title( $post ) ) . '</h2>';
            if ( has_post_thumbnail( $post ) ) { echo '<p>Nuvarande utvald bild:</p>' . get_the_post_thumbnail( $post, 'medium' ); }
            $preview = get_post_meta( $id, '_gdes_image_preview', true );
            if ( $preview && get_post( $preview ) && wp_attachment_is_image( $preview ) ) {
                echo '<h3>Bild att granska</h3>' . wp_get_attachment_image( $preview, 'medium_large' );
                echo '<p><strong>Granska bilden innan du använder den.</strong></p>';
                self::form_button( $id, 'approve', 'Godkänn som utvald bild', 'button button-primary' );
            }
            echo '<h3>Officiell spelbild från RAWG</h3><p>RAWG-bilder måste kontrolleras avseende motiv, upphovsrätt och användningsvillkor innan användning. Ingen automatisk licensgaranti.</p>';
            self::form_button( $id, 'rawg', 'Sök omslagsbild via RAWG', 'button' );
            $candidate = get_transient( 'gdes_rawg_' . get_current_user_id() . '_' . $id );
            if ( is_array( $candidate ) && ! empty( $candidate['url'] ) ) {
                echo '<p><img style="max-width:450px;width:100%;height:auto" src="' . esc_url( $candidate['url'] ) . '" alt="RAWG-förslag"></p><p>RAWG-matchning: ' . esc_html( $candidate['name'] ) . '</p>';
                echo '<p>Genom att importera bekräftar du att du har rätt att använda bilden.</p>';
                self::form_button( $id, 'import', 'Importera RAWG-bild för granskning', 'button' );
            }
            echo '<h3>AI-illustration</h3><p>Modell: <strong>' . esc_html( $s['model'] ) . '</strong>, kvalitet: ' . esc_html( $s['quality'] ) . '. Uppskattad kostnad för en bild: <strong>$' . esc_html( number_format( self::cost( $s['model'], $s['quality'] ), 3 ) ) . '</strong> (ej bindande pris, kontrollera aktuell API-prissättning).</p>';
            self::form_button( $id, 'generate', 'Generera AI-bild (API-kostnad)', 'button button-primary' );
        }
        if ( current_user_can( 'manage_options' ) ) {
            echo '<hr><h2>Bildinställningar</h2><form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"><input type="hidden" name="action" value="gdes_image_settings">'; wp_nonce_field( 'gdes_image_settings' );
            echo '<table class="form-table"><tr><th>Generell bildinstruktion</th><td><textarea name="prompt" rows="8" class="large-text">' . esc_textarea( $s['prompt'] ) . '</textarea></td></tr>';
            echo '<tr><th>Bildmodell</th><td><select name="model">'; foreach ( array( 'gpt-image-1.5', 'gpt-image-1', 'gpt-image-1-mini' ) as $m ) { echo '<option value="' . esc_attr( $m ) . '" ' . selected( $s['model'], $m, false ) . '>' . esc_html( $m ) . '</option>'; } echo '</select></td></tr>';
            echo '<tr><th>Kvalitet</th><td><select name="quality">'; foreach ( array( 'low', 'medium', 'high' ) as $q ) { echo '<option value="' . esc_attr( $q ) . '" ' . selected( $s['quality'], $q, false ) . '>' . esc_html( ucfirst( $q ) ) . '</option>'; } echo '</select></td></tr>';
            echo '<tr><th>RAWG API-nyckel</th><td><input type="password" name="rawg_key" class="regular-text" autocomplete="new-password" placeholder="' . ( $s['rawg_key'] ? 'Nyckel sparad – lämna tomt för att behålla' : 'Ange RAWG-nyckel' ) . '"><label><input type="checkbox" name="clear_rawg_key" value="1"> Radera nyckeln</label><p class="description">Alternativt definiera RAWG_API_KEY i wp-config.php.</p></td></tr>';
            echo '<tr><th>Kostnadsspärr per bild (USD)</th><td><input type="number" min="0" max="100" step="0.01" name="max_usd" value="' . esc_attr( $s['max_usd'] ) . '"><p class="description">Spärrar anrop vars uppskattade kostnad överstiger beloppet. Inte en garanterad API-budgetgräns.</p></td></tr></table>';
            submit_button( 'Spara bildinställningar' ); echo '</form>';
        }
        echo '</div>';
    }
    private static function form_button( $id, $operation, $label, $class ) {
        echo '<form style="display:inline-block;margin:6px 8px 6px 0" method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"><input type="hidden" name="action" value="gdes_image_action"><input type="hidden" name="post_id" value="' . esc_attr( $id ) . '"><input type="hidden" name="operation" value="' . esc_attr( $operation ) . '">'; wp_nonce_field( 'gdes_image_' . $id ); echo '<button class="' . esc_attr( $class ) . '">' . esc_html( $label ) . '</button></form>';
    }
    public static function action() {
        $id = absint( $_POST['post_id'] ?? 0 ); $post = self::selected_post( $id );
        if ( ! $post || ! current_user_can( 'upload_files' ) ) { wp_die( 'Behörighet saknas.' ); }
        check_admin_referer( 'gdes_image_' . $id );
        $op = sanitize_key( $_POST['operation'] ?? '' ); $s = self::settings();
        if ( $op === 'approve' ) {
            $attachment = absint( get_post_meta( $id, '_gdes_image_preview', true ) );
            if ( $attachment && wp_attachment_is_image( $attachment ) ) { set_post_thumbnail( $id, $attachment ); delete_post_meta( $id, '_gdes_image_preview' ); self::redirect( 'Bilden är nu utvald bild.', $id ); }
            self::redirect( 'Ingen giltig bild att godkänna.', $id );
        }
        if ( $op === 'rawg' ) {
            $key = defined( 'RAWG_API_KEY' ) && RAWG_API_KEY ? RAWG_API_KEY : $s['rawg_key'];
            if ( ! $key ) { self::redirect( 'RAWG API-nyckel saknas.', $id ); }
            $response = wp_safe_remote_get( add_query_arg( array( 'key' => $key, 'search' => get_the_title( $post ), 'page_size' => 5 ), 'https://api.rawg.io/api/games' ), array( 'timeout' => 20 ) );
            if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) { self::redirect( 'RAWG-sökningen misslyckades.', $id ); }
            $data = json_decode( wp_remote_retrieve_body( $response ), true ); $candidate = null;
            foreach ( $data['results'] ?? array() as $game ) { if ( ! empty( $game['background_image'] ) && self::allowed_image_url( $game['background_image'] ) ) { $candidate = array( 'url' => esc_url_raw( $game['background_image'] ), 'name' => sanitize_text_field( $game['name'] ) ); break; } }
            if ( ! $candidate ) { self::redirect( 'Ingen lämplig RAWG-bild hittades.', $id ); }
            set_transient( 'gdes_rawg_' . get_current_user_id() . '_' . $id, $candidate, 30 * MINUTE_IN_SECONDS ); self::redirect( 'Granska RAWG-förslaget nedan.', $id );
        }
        if ( $op === 'import' ) {
            $candidate = get_transient( 'gdes_rawg_' . get_current_user_id() . '_' . $id );
            if ( ! is_array( $candidate ) || ! self::allowed_image_url( $candidate['url'] ?? '' ) ) { self::redirect( 'RAWG-förslaget har löpt ut.', $id ); }
            require_once ABSPATH . 'wp-admin/includes/file.php'; require_once ABSPATH . 'wp-admin/includes/media.php'; require_once ABSPATH . 'wp-admin/includes/image.php';
            $tmp = download_url( $candidate['url'], 30 ); if ( is_wp_error( $tmp ) ) { self::redirect( 'Kunde inte ladda ned bilden.', $id ); }
            $file = array( 'name' => 'gdes-rawg-' . $id . '.jpg', 'tmp_name' => $tmp );
            $attachment = media_handle_sideload( $file, $id, 'RAWG: ' . $candidate['name'] );
            if ( is_wp_error( $attachment ) ) { @unlink( $tmp ); self::redirect( 'Kunde inte importera RAWG-bilden.', $id ); }
            update_post_meta( $attachment, '_gdes_image_source', 'rawg' ); update_post_meta( $id, '_gdes_image_preview', $attachment );
            delete_transient( 'gdes_rawg_' . get_current_user_id() . '_' . $id ); self::redirect( 'RAWG-bilden är importerad. Granska och godkänn.', $id );
        }
        if ( $op === 'generate' ) {
            if ( self::cost( $s['model'], $s['quality'] ) > (float) $s['max_usd'] ) { self::redirect( 'Bildens uppskattade kostnad överstiger din spärr.', $id ); }
            $key = GDES_OpenAI::get_api_key(); if ( ! $key ) { self::redirect( 'OpenAI API-nyckel saknas i Editorial Studio.', $id ); }
            $excerpt = wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 180, '' );
            $prompt = $s['prompt'] . "\n\nARTICLE TITLE: " . get_the_title( $post ) . "\nARTICLE SUMMARY: " . $excerpt;
            $response = wp_remote_post( 'https://api.openai.com/v1/images/generations', array( 'timeout' => 180, 'redirection' => 0, 'headers' => array( 'Authorization' => 'Bearer ' . $key, 'Content-Type' => 'application/json' ), 'body' => wp_json_encode( array( 'model' => $s['model'], 'prompt' => ( function_exists( 'mb_substr' ) ? mb_substr( $prompt, 0, 14000 ) : substr( $prompt, 0, 14000 ) ), 'size' => '1536x1024', 'quality' => $s['quality'], 'n' => 1, 'output_format' => 'png' ) ) ) );
            if ( is_wp_error( $response ) ) { self::redirect( 'OpenAI-anslutningen misslyckades: ' . $response->get_error_message(), $id ); }
            $data = json_decode( wp_remote_retrieve_body( $response ), true );
            if ( wp_remote_retrieve_response_code( $response ) !== 200 || empty( $data['data'][0]['b64_json'] ) ) { self::redirect( 'OpenAI kunde inte skapa bilden: ' . sanitize_text_field( $data['error']['message'] ?? 'Okänt API-fel' ), $id ); }
            $binary = base64_decode( $data['data'][0]['b64_json'], true );
            if ( ! $binary || strlen( $binary ) > 16000000 || ! function_exists( 'getimagesizefromstring' ) || ! getimagesizefromstring( $binary ) ) { self::redirect( 'Ogiltig bilddata från OpenAI.', $id ); }
            $upload = wp_upload_bits( 'gdes-ai-' . $id . '-' . time() . '.png', null, $binary );
            if ( ! empty( $upload['error'] ) ) { self::redirect( 'Kunde inte spara bilden i WordPress.', $id ); }
            require_once ABSPATH . 'wp-admin/includes/image.php';
            $attachment = wp_insert_attachment( array( 'post_mime_type' => 'image/png', 'post_title' => 'GameDefiners AI illustration – ' . get_the_title( $post ), 'post_status' => 'inherit', 'post_content' => '' ), $upload['file'], $id, true );
            if ( is_wp_error( $attachment ) ) { @unlink( $upload['file'] ); self::redirect( 'Kunde inte registrera bilden.', $id ); }
            wp_update_attachment_metadata( $attachment, wp_generate_attachment_metadata( $attachment, $upload['file'] ) );
            update_post_meta( $attachment, '_gdes_image_source', 'ai-illustration' ); update_post_meta( $attachment, '_wp_attachment_image_alt', 'Redaktionell AI-illustration till ' . get_the_title( $post ) );
            update_post_meta( $id, '_gdes_image_preview', $attachment ); self::redirect( 'AI-bilden skapades och sparades för granskning. API-kostnad kan ha debiterats.', $id );
        }
        self::redirect( 'Okänd åtgärd.', $id );
    }
    private static function allowed_image_url( $url ) {
        $parts = wp_parse_url( $url );
        return is_array( $parts ) && ( $parts['scheme'] ?? '' ) === 'https' && ( $parts['host'] ?? '' ) === 'media.rawg.io';
    }
}
