<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
class GDES_Bulk {
    const QUEUE = 'gdes_bulk_2018_queue';
    const CONFIG = 'gdes_bulk_2018_config';
    public static function boot() {
        // Register after GDES_Plugin::admin_menu() has created the parent menu.
        add_action( 'admin_menu', array( __CLASS__, 'menu' ), 30 );
        add_action( 'admin_post_gdes_bulk_import', array( __CLASS__, 'import' ) );
        add_action( 'admin_post_gdes_bulk_config', array( __CLASS__, 'config' ) );
        add_action( 'wp_ajax_gdes_bulk_next', array( __CLASS__, 'next' ) );
    }
    public static function menu() {
        add_submenu_page( 'gdes-editorial', 'Bulkimport', 'Bulkimport', 'edit_posts', 'gdes-bulk', array( __CLASS__, 'page' ) );
    }
    private static function queue() { $q = get_option( self::QUEUE, array() ); return is_array( $q ) ? $q : array(); }
    private static function normalized( $v ) { return strtolower( remove_accents( trim( (string) $v ) ) ); }
    private static function existing( $title ) {
        global $wpdb;
        $ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type='post' AND post_status NOT IN ('trash','auto-draft') AND post_title=%s LIMIT 1", $title ) );
        return ! empty( $ids );
    }
    private static function xlsx( $path ) {
        if ( ! class_exists( 'ZipArchive' ) || ! function_exists( 'simplexml_load_string' ) ) { return new WP_Error( 'extensions', 'XLSX kräver PHP zip och SimpleXML. Exportera CSV om de saknas.' ); }
        $z = new ZipArchive(); if ( $z->open( $path ) !== true ) { return new WP_Error( 'zip', 'Ogiltig XLSX.' ); }
        $shared = array(); $str = $z->getFromName( 'xl/sharedStrings.xml' );
        if ( $str !== false && strlen( $str ) < 10000000 ) {
            $sx = simplexml_load_string( $str );
            if ( $sx ) { foreach ( $sx->si as $si ) { $parts = $si->xpath( './/*[local-name()="t"]' ); $shared[] = implode( '', array_map( 'strval', $parts ?: array() ) ); } }
        }
        $sheet = $z->getFromName( 'xl/worksheets/sheet1.xml' ); $z->close();
        if ( $sheet === false || strlen( $sheet ) > 15000000 ) { return new WP_Error( 'sheet', 'Första arbetsbladet saknas eller är för stort.' ); }
        $sx = simplexml_load_string( $sheet ); if ( ! $sx ) { return new WP_Error( 'xml', 'Kan inte läsa Excel-bladet.' ); }
        $rows = array();
        foreach ( $sx->sheetData->row as $r ) {
            if ( count( $rows ) >= 3000 ) { break; }
            $cells = array();
            foreach ( $r->c as $c ) {
                if ( ! preg_match( '/^([A-Z]{1,3})[0-9]+$/', (string) $c['r'], $m ) ) { continue; }
                $col = 0; foreach ( str_split( $m[1] ) as $ch ) { $col = $col * 26 + ord( $ch ) - 64; }
                if ( $col > 40 ) { continue; }
                $value = (string) $c->v;
                if ( (string) $c['t'] === 's' ) { $value = $shared[(int) $value] ?? ''; }
                if ( (string) $c['t'] === 'inlineStr' ) { $parts = $c->xpath( './/*[local-name()="t"]' ); $value = implode( '', array_map( 'strval', $parts ?: array() ) ); }
                $cells[$col - 1] = $value;
            }
            if ( $cells ) { $rows[] = $cells; }
        }
        return $rows;
    }
    private static function csv( $path ) {
        $f = fopen( $path, 'rb' ); if ( ! $f ) { return new WP_Error( 'csv', 'Kan inte läsa CSV.' ); }
        $first = fgets( $f ); rewind( $f ); $delimiter = substr_count( (string) $first, ';' ) > substr_count( (string) $first, ',' ) ? ';' : ',';
        $rows = array(); while ( count( $rows ) < 3000 && ( $r = fgetcsv( $f, 0, $delimiter ) ) !== false ) { $rows[] = $r; } fclose( $f ); return $rows;
    }
    private static function redirect( $msg ) { wp_safe_redirect( add_query_arg( array( 'page' => 'gdes-bulk', 'notice' => $msg ), admin_url( 'admin.php' ) ) ); exit; }
    public static function import() {
        if ( ! current_user_can( 'edit_posts' ) ) { wp_die( 'Ej behörig.' ); } check_admin_referer( 'gdes_bulk_import' );
        $f = $_FILES['games'] ?? null;
        if ( ! $f || ! empty( $f['error'] ) || ! is_uploaded_file( $f['tmp_name'] ) || $f['size'] > 5242880 ) { self::redirect( 'Uppladdning misslyckades eller filen är större än 5 MB.' ); }
        $ext = strtolower( pathinfo( sanitize_file_name( $f['name'] ), PATHINFO_EXTENSION ) );
        if ( ! in_array( $ext, array( 'csv', 'xlsx' ), true ) ) { self::redirect( 'Använd CSV eller XLSX.' ); }
        $raw = $ext === 'csv' ? self::csv( $f['tmp_name'] ) : self::xlsx( $f['tmp_name'] );
        if ( is_wp_error( $raw ) ) { self::redirect( $raw->get_error_message() ); }
        if ( count( $raw ) < 2 ) { self::redirect( 'Inga spelrader hittades.' ); }
        $headers = array_map( function( $v ) { return self::normalized( preg_replace( '/^\xEF\xBB\xBF/', '', (string) $v ) ); }, $raw[0] );
        $title_col = false; $year_col = false;
        foreach ( $headers as $i => $h ) {
            if ( in_array( $h, array( 'spel', 'speltitel', 'titel', 'title', 'game', 'game title', 'namn', 'spel/ titel', 'game_title' ), true ) ) { $title_col = $i; }
            if ( in_array( $h, array( 'ar', 'year', 'release year', 'release_year', 'lanseringsar' ), true ) ) { $year_col = $i; }
        }
        if ( $title_col === false || $year_col === false ) { self::redirect( 'Hittar inte kolumnerna för speltitel och år. Kontrollera rubrikraden.' ); }
        $q = self::queue(); $added = 0; $skipped = 0;
        foreach ( array_slice( $raw, 1 ) as $r ) {
            $title = sanitize_text_field( trim( (string) ( $r[$title_col] ?? '' ) ) );
            $year = absint( $r[$year_col] ?? 0 );
            if ( $title === '' || $year < 2018 || $year > 2025 ) { $skipped++; continue; }
            $key = md5( self::normalized( $title ) );
            if ( isset( $q[$key] ) || self::existing( $title ) ) { $skipped++; continue; }
            $q[$key] = array( 'title' => $title, 'year' => $year, 'status' => 'pending', 'post_id' => 0, 'error' => '' ); $added++;
        }
        update_option( self::QUEUE, $q, false );
        self::redirect( sprintf( 'Importerade %d spel (2018–2025). Hoppade över %d äldre, ogiltiga eller redan registrerade.', $added, $skipped ) );
    }
    public static function config() {
        if ( ! current_user_can( 'edit_posts' ) ) { wp_die( 'Ej behörig.' ); } check_admin_referer( 'gdes_bulk_config' );
        $brief = sanitize_textarea_field( wp_unslash( $_POST['brief'] ?? '' ) );
        if ( trim( $brief ) === '' ) { self::redirect( 'Artikelvinkel krävs.' ); }
        $type = sanitize_key( $_POST['article_type'] ?? 'news' );
        if ( ! in_array( $type, array( 'news', 'preview', 'review', 'guide', 'list' ), true ) ) { $type = 'news'; }
        if ( $type === 'review' || $type === 'guide' ) { self::redirect( 'Massgenerering av recensioner och guider kräver spelspecifikt underlag. Välj news eller preview.' ); }
        update_option( self::CONFIG, array(
            'brief' => $brief, 'facts' => sanitize_textarea_field( wp_unslash( $_POST['facts'] ?? '' ) ),
            'article_type' => $type, 'language' => in_array( $_POST['language'] ?? '', array( 'sv', 'en' ), true ) ? $_POST['language'] : 'en',
            'length' => in_array( $_POST['length'] ?? '', array( 'short', 'medium', 'long' ), true ) ? $_POST['length'] : 'medium',
        ), false );
        self::redirect( 'Gemensamma instruktioner sparade.' );
    }
    public static function next() {
        if ( ! current_user_can( 'edit_posts' ) ) { wp_send_json_error( 'Ej behörig.', 403 ); }
        check_ajax_referer( 'gdes_bulk_next' );
        $cfg = get_option( self::CONFIG, array() ); if ( empty( $cfg['brief'] ) ) { wp_send_json_error( 'Spara artikelvinkeln först.' ); }
        $q = self::queue();
        foreach ( $q as $key => $item ) {
            if ( $item['status'] !== 'pending' ) { continue; }
            if ( self::existing( $item['title'] ) ) {
                $q[$key]['status'] = 'skipped'; $q[$key]['error'] = 'Artikeln finns redan i WordPress.'; update_option( self::QUEUE, $q, false );
                wp_send_json_success( array( 'status' => 'skipped', 'title' => $item['title'] ) );
            }
            $payload = array(
                'title' => $item['title'], 'article_type' => $cfg['article_type'], 'brief' => $cfg['brief'],
                'facts' => $cfg['facts'], 'language' => $cfg['language'], 'length' => $cfg['length'],
            );
            $result = GDES_Generator::generate( $payload, get_current_user_id() );
            if ( is_wp_error( $result ) ) {
                $q[$key]['status'] = 'error'; $q[$key]['error'] = $result->get_error_message(); update_option( self::QUEUE, $q, false );
                wp_send_json_error( $item['title'] . ': ' . $result->get_error_message() );
            }
            update_post_meta( $result, '_gdes_bulk_import', '2018-2025' );
            $q[$key]['status'] = 'created'; $q[$key]['post_id'] = $result;
            update_option( self::QUEUE, $q, false );
            wp_send_json_success( array( 'status' => 'created', 'title' => $item['title'], 'post_id' => $result ) );
        }
        wp_send_json_success( array( 'status' => 'finished' ) );
    }
    public static function page() {
        if ( ! current_user_can( 'edit_posts' ) ) { return; }
        $cfg = get_option( self::CONFIG, array() ); $q = self::queue();
        $counts = array_count_values( array_column( $q, 'status' ) );
        echo '<div class="wrap"><h1>Editorial Studio – Bulkimport (2018–2025)</h1><p>Spel från 2012–2017 ignoreras. Importen skapar inga artiklar; varje AI-anrop behandlar ett spel och sparar ett WordPress-utkast.</p>';
        if ( isset( $_GET['notice'] ) ) { echo '<div class="notice notice-info"><p>' . esc_html( sanitize_text_field( wp_unslash( $_GET['notice'] ) ) ) . '</p></div>'; }
        echo '<h2>1. Importera speltitlar</h2><form method="post" enctype="multipart/form-data" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
        wp_nonce_field( 'gdes_bulk_import' ); echo '<input type="hidden" name="action" value="gdes_bulk_import"><input type="file" name="games" accept=".csv,.xlsx" required> '; submit_button( 'Importera spel', 'secondary', 'submit', false ); echo '</form>';
        echo '<h2>2. Gemensamma instruktioner (en gång)</h2><form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">'; wp_nonce_field( 'gdes_bulk_config' );
        echo '<input type="hidden" name="action" value="gdes_bulk_config"><table class="form-table"><tr><th>Artikeltyp</th><td><select name="article_type">';
        foreach ( array( 'news' => 'News', 'preview' => 'Preview', 'list' => 'List' ) as $key => $label ) { echo '<option value="' . esc_attr( $key ) . '" ' . selected( $cfg['article_type'] ?? 'news', $key, false ) . '>' . esc_html( $label ) . '</option>'; }
        echo '</select></td></tr><tr><th>Språk</th><td><select name="language"><option value="en" ' . selected( $cfg['language'] ?? 'en', 'en', false ) . '>English</option><option value="sv" ' . selected( $cfg['language'] ?? 'en', 'sv', false ) . '>Svenska</option></select></td></tr><tr><th>Längd</th><td><select name="length">';
        foreach ( array( 'short' => 'Kort', 'medium' => 'Normal', 'long' => 'Lång' ) as $key => $label ) { echo '<option value="' . esc_attr( $key ) . '" ' . selected( $cfg['length'] ?? 'medium', $key, false ) . '>' . esc_html( $label ) . '</option>'; }
        echo '</select></td></tr><tr><th>Artikelvinkel</th><td><textarea name="brief" rows="12" cols="95" required>' . esc_textarea( $cfg['brief'] ?? '' ) . '</textarea></td></tr><tr><th>Fakta och källanteckningar (valfritt)</th><td><textarea name="facts" rows="5" cols="95">' . esc_textarea( $cfg['facts'] ?? '' ) . '</textarea><p class="description">Gemensamma instruktioner ersätter inte verifierade spelfakta. Granska varje utkast.</p></td></tr></table>';
        submit_button( 'Spara instruktioner' ); echo '</form>';
        echo '<h2>3. Generera utkast</h2><p>I kö: ' . (int) ( $counts['pending'] ?? 0 ) . ' | Skapade: ' . (int) ( $counts['created'] ?? 0 ) . ' | Överhoppade: ' . (int) ( $counts['skipped'] ?? 0 ) . ' | Fel: ' . (int) ( $counts['error'] ?? 0 ) . '</p>';
        echo '<p><label>Max utkast per omgång: <input id="gdes-bulk-limit" type="number" min="1" max="10" value="1" style="width:65px"></label> <button type="button" id="gdes-bulk-start" class="button button-primary">Starta generering</button> <button type="button" id="gdes-bulk-stop" class="button" disabled>Stoppa efter aktuellt spel</button></p>';
        echo '<p id="gdes-bulk-status" aria-live="polite">Genereringen startar bara när du klickar på knappen. Håll sidan öppen under körningen.</p>';
        echo '<h2>Senaste köposter</h2><table class="widefat striped"><thead><tr><th>Spel</th><th>År</th><th>Status</th><th>Kommentar</th></tr></thead><tbody>';
        foreach ( array_slice( array_reverse( $q ), 0, 80 ) as $item ) { echo '<tr><td>' . esc_html( $item['title'] ) . '</td><td>' . (int) $item['year'] . '</td><td>' . esc_html( $item['status'] ) . '</td><td>' . esc_html( $item['error'] ) . '</td></tr>'; }
        echo '</tbody></table></div>';
        $nonce = wp_create_nonce( 'gdes_bulk_next' );
        echo '<script>(function(){var start=document.getElementById("gdes-bulk-start"),stop=document.getElementById("gdes-bulk-stop"),status=document.getElementById("gdes-bulk-status"),limit=document.getElementById("gdes-bulk-limit");var running=false,halt=false,done=0,max=1;stop.onclick=function(){halt=true;stop.disabled=true;};start.onclick=function(){if(running)return;max=Math.min(10,Math.max(1,parseInt(limit.value,10)||1));done=0;halt=false;running=true;start.disabled=true;stop.disabled=false;step();};function finish(message){running=false;start.disabled=false;stop.disabled=true;status.textContent=message+" Uppdatera sidan för att se köstatus.";}function step(){if(halt||done>=max){finish("Körningen avslutad: "+done+" utkast.");return;}status.textContent="Genererar artikel "+(done+1)+" av högst "+max+" …";var form=new FormData();form.append("action","gdes_bulk_next");form.append("_ajax_nonce",' . wp_json_encode( $nonce ) . ');fetch(ajaxurl,{method:"POST",credentials:"same-origin",body:form}).then(function(r){return r.json();}).then(function(d){if(!d.success){finish("Fel: "+(typeof d.data==="string"?d.data:"Genereringen misslyckades."));return;}if(d.data.status==="finished"){finish("Inga fler spel i kön. Skapade "+done+" utkast.");return;}if(d.data.status==="created"){done++;}status.textContent=d.data.title+" – "+d.data.status+". Skapade "+done+" utkast.";step();}).catch(function(e){finish("Anropet misslyckades: "+e.message);});}})();</script>';
    }
}
