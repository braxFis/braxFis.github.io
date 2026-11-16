<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class GDES_Plugin {
    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function activate() {
        if ( ! get_option( 'gdes_settings' ) ) {
            add_option( 'gdes_settings', self::default_settings(), '', false );
        }
    }

    public static function default_settings() {
        return array(
            'api_key' => '',
            'tmdb_read_token' => '',
            'model' => 'gpt-6.1-terra',
            'default_language' => 'sv',
            'default_tone' => 'Engagerad och professionell',
            'default_length' => 'medium',
            'movie_reporter_instructions' => '',
            'editorial_instructions' => 'Skriv med tydlig journalistisk röst. Undvik tomma superlativ, upprepningar och generiska AI-formuleringar. Prioritera konkreta detaljer och läsarnytta.',
            'review_criteria' => array(
                'Gameplay och kontroller',
                'Speldesign och innehåll',
                'Grafik och design',
                'Ljud och musik',
                'Teknisk kvalitet',
            ),
        );
    }

    private function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
        add_action( 'admin_post_gdes_save_settings', array( $this, 'save_settings' ) );
        add_action( 'admin_post_gdes_generate_draft', array( $this, 'generate_draft' ) );
        add_action( 'init', array( $this, 'register_review_block' ) );
        add_action( 'admin_notices', array( $this, 'admin_notices' ) );
    }

    private function settings() {
        $saved = get_option( 'gdes_settings', array() );
        return wp_parse_args( is_array( $saved ) ? $saved : array(), self::default_settings() );
    }

    public function admin_menu() {
        add_menu_page(
            'AI Editorial Studio',
            'Editorial Studio',
            'edit_posts',
            'gdes-editorial',
            array( $this, 'render_dashboard' ),
            'dashicons-edit-page',
            27
        );
        add_submenu_page( 'gdes-editorial', 'Översikt', 'Översikt', 'edit_posts', 'gdes-editorial', array( $this, 'render_dashboard' ) );
        add_submenu_page( 'gdes-editorial', 'Skapa artikel', 'Skapa artikel', 'edit_posts', 'gdes-editorial-new', array( $this, 'render_writer' ) );
        add_submenu_page( 'gdes-editorial', 'Inställningar', 'Inställningar', 'manage_options', 'gdes-editorial-settings', array( $this, 'render_settings' ) );
    }

    public function admin_assets( $hook ) {
        if ( false === strpos( (string) $hook, 'gdes-editorial' ) ) {
            return;
        }
        wp_enqueue_style( 'gdes-admin', GDES_URL . 'assets/admin.css', array(), GDES_VERSION );
        if ( false !== strpos( (string) $hook, 'gdes-editorial-new' ) ) {
            wp_enqueue_script( 'gdes-tmdb', GDES_URL . 'assets/tmdb-picker.js', array(), GDES_VERSION, true );
            wp_localize_script( 'gdes-tmdb', 'GDESTmdb', array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'gdes_tmdb_search' ),
            ) );
        }
    }

    private function wrap_start( $title, $intro = '' ) {
        echo '<div class="wrap gdes-wrap"><h1>' . esc_html( $title ) . '</h1>';
        if ( $intro ) {
            echo '<p class="gdes-intro">' . esc_html( $intro ) . '</p>';
        }
    }

    private function wrap_end() {
        echo '</div>';
    }

    public function render_dashboard() {
        if ( ! current_user_can( 'edit_posts' ) ) {
            return;
        }
        $settings = $this->settings();
        $has_key = (bool) GDES_OpenAI::get_api_key();
        $this->wrap_start( 'AI Editorial Studio', 'En redaktionell arbetsyta för spel- och filmartiklar. Börja med ett utkast och granska det före publicering.' );
        ?>
        <div class="gdes-status <?php echo $has_key ? 'is-ready' : 'is-pending'; ?>">
            <strong><?php echo $has_key ? 'OpenAI-anslutning konfigurerad' : 'OpenAI är inte anslutet ännu'; ?></strong>
            <span><?php echo $has_key ? 'Du kan skapa ett första artikelutkast.' : 'Lägg till en API-nyckel under Inställningar när du är redo. Du kan utforska pluginet redan nu.'; ?></span>
        </div>
        <div class="gdes-grid">
            <section class="gdes-card"><span class="dashicons dashicons-edit"></span><h2>Artikelverkstad</h2><p>Skapa ett AI-assisterat utkast för nyhet, preview, recension, guide eller topplista. Utkastet sparas i WordPress – det publiceras inte automatiskt.</p><a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=gdes-editorial-new' ) ); ?>">Skapa artikel</a></section>
            <section class="gdes-card"><span class="dashicons dashicons-awards"></span><h2>Recensionsbedömning</h2><p>Ett separat Gutenberg-block med fem gemensamma kriterier. Redaktören väljer bedömning och skriver motivering till varje punkt.</p><span class="gdes-tag">Ingår i version 0.1.0</span></section>
            <section class="gdes-card"><span class="dashicons dashicons-admin-generic"></span><h2>Redaktionella inställningar</h2><p>Ändra modell, standardspråk, ton, längd, grundinstruktioner och de fem centrala recensionskriterierna.</p><a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=gdes-editorial-settings' ) ); ?>">Öppna inställningar</a></section>
        </div>
        <div class="gdes-card gdes-roadmap"><h2>Filmunderlag och artikelutkast</h2><p>Välj Film (TMDB) i artikelverkstaden för att söka på filmtitel och hämta metadata. Ange en egen artikelvinkel och källor. Filmreporterns instruktioner används för filmartiklar; befintliga spelflöden finns kvar.</p><p class="description">AI-utkast är inte faktagranskade. Kontrollera datum, citat och andra sakuppgifter innan publicering. Recensioner kräver egna test- eller visningsanteckningar.</p></div>
        <?php
        $this->wrap_end();
    }

    public function render_writer() {
        if ( ! current_user_can( 'edit_posts' ) ) {
            return;
        }
        $settings = $this->settings();
        $has_key = (bool) GDES_OpenAI::get_api_key();
        $this->wrap_start( 'Skapa artikel', 'Fyll i underlaget. AI:n skapar ett vanligt WordPress-utkast som du kan granska och redigera i blockredigeraren.' );
        if ( ! $has_key ) {
            echo '<div class="notice notice-warning inline"><p>OpenAI-nyckel saknas. Lägg till den under <a href="' . esc_url( admin_url( 'admin.php?page=gdes-editorial-settings' ) ) . '">Inställningar</a> innan du genererar ett utkast.</p></div>';
        }
        ?>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="gdes-form">
            <input type="hidden" name="action" value="gdes_generate_draft">
            <?php wp_nonce_field( 'gdes_generate_draft', 'gdes_nonce' ); ?>
            <div class="gdes-form-grid">
                <label class="gdes-field"><span>Ämne</span><select name="reporter_role" id="gdes-reporter-role"><option value="game">Spel</option><option value="movie">Film (TMDB)</option></select></label>
                <section class="gdes-field gdes-span-2" id="gdes-tmdb-picker" hidden>
                    <h2>Hämta filmunderlag från TMDB</h2>
                    <label for="gdes-tmdb-query">Filmtitel</label>
                    <input type="search" id="gdes-tmdb-query" placeholder="Exempel: Alien" maxlength="120">
                    <button type="button" class="button" id="gdes-tmdb-search">Sök film</button>
                    <p id="gdes-tmdb-status" role="status" aria-live="polite"></p>
                    <div id="gdes-tmdb-results"></div>
                    <input type="hidden" name="tmdb_id" id="gdes-tmdb-id" value="">
                    <p id="gdes-tmdb-selected">Ingen film vald.</p>
                    <button type="button" class="button" id="gdes-tmdb-clear">Ta bort filmval</button>
                    <p class="description">Vald films metadata hämtas på servern när utkastet skapas. Ange själv artikelvinkel och verifierade nyhetskällor nedan. TMDB är bakgrundsunderlag, inte faktagranskning.</p>
                    <p class="description">This product uses the TMDB API but is not endorsed or certified by TMDB.</p>
                </section>
                <label class="gdes-field gdes-span-2"><span>Artikelrubrik / arbetstitel</span><input type="text" name="title" class="regular-text" required maxlength="180" placeholder="Exempel: Första intrycket av ..."></label>
                <label class="gdes-field"><span>Artikeltyp</span><select name="article_type" id="gdes-article-type">
                    <option value="news">Nyhet</option><option value="preview">Preview</option><option value="review">Recension</option><option value="guide">Guide</option><option value="list">Topplista</option>
                </select></label>
                <label class="gdes-field"><span>Språk</span><select name="language"><option value="sv" <?php selected( $settings['default_language'], 'sv' ); ?>>Svenska</option><option value="en" <?php selected( $settings['default_language'], 'en' ); ?>>Engelska</option></select></label>
                <label class="gdes-field"><span>Textlängd</span><select name="length"><option value="short" <?php selected( $settings['default_length'], 'short' ); ?>>Kort</option><option value="medium" <?php selected( $settings['default_length'], 'medium' ); ?>>Normal</option><option value="long" <?php selected( $settings['default_length'], 'long' ); ?>>Lång</option></select></label>
                <label class="gdes-field"><span>Ton</span><input type="text" name="tone" value="<?php echo esc_attr( $settings['default_tone'] ); ?>" maxlength="100"></label>
                <label class="gdes-field gdes-span-2"><span>Artikelvinkel / instruktioner</span><textarea name="brief" rows="4" required placeholder="Vad ska artikeln handla om? Vilken vinkel vill du ha? Vad ska läsaren få ut av den?"></textarea></label>
                <label class="gdes-field gdes-span-2"><span>Fakta och källanteckningar</span><textarea name="facts" rows="6" placeholder="Klistra in verifierade uppgifter, källänkar och egna anteckningar. För film kan du även välja TMDB-underlag ovan."></textarea><small>Uppgifter här skickas till OpenAI som underlag. Lägg gärna in källans URL och vad den bekräftar.</small></label>
                <label class="gdes-field gdes-span-2" id="gdes-hands-on-wrap"><span>Egna test- eller visningsanteckningar (krävs för recensioner)</span><textarea name="hands_on_notes" rows="5" placeholder="Beskriv vad du själv har spelat, testat eller sett. Ge konkreta observationer, styrkor och problem."></textarea><small>För recensioner krävs egna anteckningar. AI:n får inte hitta på att skribenten har spelat spelet eller sett filmen.</small></label>
            </div>
            <p class="gdes-form-actions"><button type="submit" class="button button-primary button-hero" <?php disabled( ! $has_key ); ?>>Generera WordPress-utkast</button> <a class="button button-link" href="<?php echo esc_url( admin_url( 'admin.php?page=gdes-editorial' ) ); ?>">Tillbaka</a></p>
            <p class="description">Utkastet skapas med statusen ”Utkast”. Ingen artikel publiceras automatiskt. Kontrollera alltid fakta och källor innan publicering.</p>
        </form>
        <script>
        (function(){var type=document.getElementById('gdes-article-type');var wrap=document.getElementById('gdes-hands-on-wrap');var notes=wrap?wrap.querySelector('textarea'):null;function toggle(){var isReview=type&&type.value==='review';if(wrap){wrap.style.display=isReview?'block':'none';}if(notes){notes.required=!!isReview;}}if(type){type.addEventListener('change',toggle);toggle();}}());
        </script>
        <?php
        $this->wrap_end();
    }

    public function render_settings() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        $settings = $this->settings();
        $constant_key = defined( 'GDES_OPENAI_API_KEY' ) && GDES_OPENAI_API_KEY;
        $this->wrap_start( 'Inställningar', 'Dessa inställningar styr hur nya artikelutkast och recensionsblock skapas. Befintliga artiklar och redan infogade recensionsblock ändras inte när du ändrar standarderna här.' );
        ?>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="gdes-form gdes-settings-form">
            <input type="hidden" name="action" value="gdes_save_settings">
            <?php wp_nonce_field( 'gdes_save_settings', 'gdes_settings_nonce' ); ?>
            <section class="gdes-settings-section"><h2>OpenAI</h2>
                <?php if ( $constant_key ) : ?>
                    <div class="notice notice-success inline"><p>API-nyckel läses från konstanten <code>GDES_OPENAI_API_KEY</code> i wp-config.php. Den kan inte ändras här.</p></div>
                <?php else : ?>
                    <label class="gdes-field"><span>OpenAI API-nyckel</span><input type="password" name="api_key" value="" autocomplete="new-password" placeholder="<?php echo GDES_OpenAI::get_api_key() ? 'Nyckel sparad – lämna tomt för att behålla' : 'sk-…'; ?>"><small>Nyckeln sparas på servern i WordPress-inställningarna och skickas aldrig till webbläsarens JavaScript. För en skarp webbplats rekommenderas att lägga den som GDES_OPENAI_API_KEY i wp-config.php.</small></label>
                    <?php if ( GDES_OpenAI::get_api_key() ) : ?><label class="gdes-check"><input type="checkbox" name="remove_api_key" value="1"> Ta bort sparad API-nyckel</label><?php endif; ?>
                <?php endif; ?>
                <label class="gdes-field"><span>OpenAI-modell</span><input type="text" name="model" value="<?php echo esc_attr( $settings['model'] ); ?>" required><small>Standard: gpt-6.1-terra. Du kan ändra modell-ID när du vill.</small></label>
            </section>
            <section class="gdes-settings-section"><h2>TMDB – filmunderlag</h2>
                <label class="gdes-field"><span>TMDB API Read Access Token</span><input type="password" name="tmdb_read_token" value="" autocomplete="new-password" placeholder="<?php echo GDES_TMDB::token() ? 'Token konfigurerad – lämna tomt för att behålla' : 'API Read Access Token'; ?>"><small>Serverbaserad filmsökning. Du kan också använda konstanten GDES_TMDB_READ_TOKEN eller miljövariabeln TMDB_API_READ_TOKEN. Token visas aldrig i sökresultat eller JavaScript.</small></label>
                <label class="gdes-check"><input type="checkbox" name="remove_tmdb_token" value="1"> Ta bort sparad TMDB-token (påverkar inte wp-config.php/miljövariabler)</label>
                <p class="description">Kontrollera TMDB:s licens- och attribueringsvillkor för din användning. Filmdata och bildrättigheter är separata; inga affischer importeras automatiskt.</p>
            </section>
            <section class="gdes-settings-section"><h2>Standard för artikelutkast</h2>
                <label class="gdes-field"><span>Standardspråk</span><select name="default_language"><option value="sv" <?php selected( $settings['default_language'], 'sv' ); ?>>Svenska</option><option value="en" <?php selected( $settings['default_language'], 'en' ); ?>>Engelska</option></select></label>
                <label class="gdes-field"><span>Standardton</span><input type="text" name="default_tone" value="<?php echo esc_attr( $settings['default_tone'] ); ?>" maxlength="100"></label>
                <label class="gdes-field"><span>Standardlängd</span><select name="default_length"><option value="short" <?php selected( $settings['default_length'], 'short' ); ?>>Kort</option><option value="medium" <?php selected( $settings['default_length'], 'medium' ); ?>>Normal</option><option value="long" <?php selected( $settings['default_length'], 'long' ); ?>>Lång</option></select></label>
                <label class="gdes-field"><span>AI Movie Reporter – extra instruktioner</span><textarea name="movie_reporter_instructions" rows="8"><?php echo esc_textarea( $settings['movie_reporter_instructions'] ); ?></textarea><small>Filmreporterns journalistiska grundregler är inbyggda. Lägg till egna önskemål här.</small></label>
                <label class="gdes-field"><span>Egna redaktionella grundinstruktioner</span><textarea name="editorial_instructions" rows="6"><?php echo esc_textarea( $settings['editorial_instructions'] ); ?></textarea><small>Exempel: önskad tonalitet, språkbruk, målgrupp och sådant AI:n ska undvika.</small></label>
            </section>
            <section class="gdes-settings-section"><h2>Gemensamma recensionskriterier</h2><p class="description">Ändringar påverkar nya recensionsblock. Block som redan finns i publicerade artiklar behåller sina befintliga kriterier.</p>
                <?php for ( $i = 0; $i < 5; $i++ ) : ?>
                    <label class="gdes-field"><span>Kriterium <?php echo esc_html( (string) ( $i + 1 ) ); ?></span><input type="text" name="review_criteria[]" value="<?php echo esc_attr( isset( $settings['review_criteria'][ $i ] ) ? $settings['review_criteria'][ $i ] : '' ); ?>" maxlength="80" required></label>
                <?php endfor; ?>
                <p class="description">Varje kriterium bedöms som ”Uppfyller” (1 poäng) eller ”Uppfyller inte” (0 poäng). I redigeraren finns även ”Ej bedömt ännu” tills skribenten har gjort sin bedömning.</p>
            </section>
            <?php if ( ! $constant_key ) : ?><p class="gdes-check"><label><input type="checkbox" name="remove_api_key" value="1" style="display:none"></label></p><?php endif; ?>
            <p><button type="submit" class="button button-primary button-hero">Spara inställningar</button></p>
        </form>
        <?php
        $this->wrap_end();
    }

    public function save_settings() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You are not allowed to manage these settings.', 'gamedefiners-editorial-studio' ) );
        }
        check_admin_referer( 'gdes_save_settings', 'gdes_settings_nonce' );
        $old = $this->settings();
        $new = $old;
        if ( ! ( defined( 'GDES_OPENAI_API_KEY' ) && GDES_OPENAI_API_KEY ) ) {
            if ( ! empty( $_POST['remove_api_key'] ) ) {
                $new['api_key'] = '';
            } elseif ( isset( $_POST['api_key'] ) && '' !== trim( (string) wp_unslash( $_POST['api_key'] ) ) ) {
                $new['api_key'] = sanitize_text_field( trim( (string) wp_unslash( $_POST['api_key'] ) ) );
            }
        }
        if ( ! empty( $_POST['remove_tmdb_token'] ) ) {
            $new['tmdb_read_token'] = '';
        } elseif ( isset( $_POST['tmdb_read_token'] ) && is_string( $_POST['tmdb_read_token'] ) && '' !== trim( wp_unslash( $_POST['tmdb_read_token'] ) ) ) {
            $new['tmdb_read_token'] = sanitize_text_field( trim( wp_unslash( $_POST['tmdb_read_token'] ) ) );
        }
        $model = isset( $_POST['model'] ) ? sanitize_text_field( wp_unslash( $_POST['model'] ) ) : $old['model'];
        $new['model'] = preg_match( '/^[a-zA-Z0-9._-]{2,80}$/', $model ) ? $model : $old['model'];
        $new['default_language'] = ( isset( $_POST['default_language'] ) && 'en' === $_POST['default_language'] ) ? 'en' : 'sv';
        $new['default_tone'] = isset( $_POST['default_tone'] ) ? sanitize_text_field( wp_unslash( $_POST['default_tone'] ) ) : $old['default_tone'];
        $new['default_length'] = isset( $_POST['default_length'] ) && in_array( $_POST['default_length'], array( 'short', 'medium', 'long' ), true ) ? sanitize_key( $_POST['default_length'] ) : 'medium';
        $new['movie_reporter_instructions'] = isset( $_POST['movie_reporter_instructions'] ) ? sanitize_textarea_field( wp_unslash( $_POST['movie_reporter_instructions'] ) ) : '';
        $new['editorial_instructions'] = isset( $_POST['editorial_instructions'] ) ? sanitize_textarea_field( wp_unslash( $_POST['editorial_instructions'] ) ) : '';
        $criteria = isset( $_POST['review_criteria'] ) ? (array) wp_unslash( $_POST['review_criteria'] ) : array();
        $new['review_criteria'] = array();
        for ( $i = 0; $i < 5; $i++ ) {
            $value = isset( $criteria[ $i ] ) ? sanitize_text_field( $criteria[ $i ] ) : '';
            $new['review_criteria'][] = '' !== $value ? wp_html_excerpt( $value, 80, '' ) : self::default_settings()['review_criteria'][ $i ];
        }
        update_option( 'gdes_settings', $new, false );
        wp_safe_redirect( add_query_arg( array( 'page' => 'gdes-editorial-settings', 'gdes_notice' => 'settings-saved' ), admin_url( 'admin.php' ) ) );
        exit;
    }

    public function generate_draft() {
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_die( esc_html__( 'You are not allowed to create posts.', 'gamedefiners-editorial-studio' ) );
        }
        check_admin_referer( 'gdes_generate_draft', 'gdes_nonce' );
        $title = isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : '';
        $type = isset( $_POST['article_type'] ) ? sanitize_key( $_POST['article_type'] ) : 'news';
        $allowed_types = array( 'news', 'preview', 'review', 'guide', 'list' );
        if ( '' === $title || ! in_array( $type, $allowed_types, true ) ) {
            $this->redirect_with_notice( 'error', 'Ange en rubrik och välj en giltig artikeltyp.' );
        }
        $payload = array(
            'title' => $title,
            'reporter_role' => isset( $_POST['reporter_role'] ) && 'movie' === $_POST['reporter_role'] ? 'movie' : 'game',
            'tmdb_id' => isset( $_POST['tmdb_id'] ) && is_string( $_POST['tmdb_id'] ) ? sanitize_text_field( wp_unslash( $_POST['tmdb_id'] ) ) : '',
            'article_type' => $type,
            'language' => ( isset( $_POST['language'] ) && 'en' === $_POST['language'] ) ? 'en' : 'sv',
            'length' => isset( $_POST['length'] ) && in_array( $_POST['length'], array( 'short', 'medium', 'long' ), true ) ? sanitize_key( $_POST['length'] ) : 'medium',
            'tone' => isset( $_POST['tone'] ) ? sanitize_text_field( wp_unslash( $_POST['tone'] ) ) : '',
            'brief' => isset( $_POST['brief'] ) ? sanitize_textarea_field( wp_unslash( $_POST['brief'] ) ) : '',
            'facts' => isset( $_POST['facts'] ) ? sanitize_textarea_field( wp_unslash( $_POST['facts'] ) ) : '',
            'hands_on_notes' => isset( $_POST['hands_on_notes'] ) ? sanitize_textarea_field( wp_unslash( $_POST['hands_on_notes'] ) ) : '',
        );
        $post_id = GDES_Generator::generate( $payload, get_current_user_id() );
        if ( is_wp_error( $post_id ) ) {
            $this->redirect_with_notice( 'error', $post_id->get_error_message() );
        }
        wp_safe_redirect( add_query_arg( array( 'post' => (int) $post_id, 'action' => 'edit', 'gdes_generated' => '1' ), admin_url( 'post.php' ) ) );
        exit;
    }

    private function redirect_with_notice( $type, $message ) {
        $key = 'gdes_notice_' . get_current_user_id();
        set_transient( $key, array( 'type' => $type, 'message' => $message ), 90 );
        wp_safe_redirect( admin_url( 'admin.php?page=gdes-editorial-new' ) );
        exit;
    }

    public function admin_notices() {
        if ( ! is_admin() ) {
            return;
        }
        if ( isset( $_GET['gdes_generated'] ) && '1' === $_GET['gdes_generated'] ) {
            echo '<div class="notice notice-success is-dismissible"><p><strong>GameDefiners Editorial Studio:</strong> Utkastet har skapats. Granska texten och kontrollera fakta innan publicering.</p></div>';
        }
        if ( isset( $_GET['gdes_notice'] ) && 'settings-saved' === $_GET['gdes_notice'] ) {
            echo '<div class="notice notice-success is-dismissible"><p>Editorial Studio-inställningarna har sparats.</p></div>';
        }
        $key = 'gdes_notice_' . get_current_user_id();
        $notice = get_transient( $key );
        if ( $notice && is_array( $notice ) ) {
            delete_transient( $key );
            $class = ( isset( $notice['type'] ) && 'error' === $notice['type'] ) ? 'notice-error' : 'notice-success';
            echo '<div class="notice ' . esc_attr( $class ) . ' is-dismissible"><p>' . esc_html( isset( $notice['message'] ) ? $notice['message'] : '' ) . '</p></div>';
        }
    }

    public function register_review_block() {
        $settings = $this->settings();
        $criteria = array_values( array_slice( $settings['review_criteria'], 0, 5 ) );
        wp_register_script(
            'gdes-review-score-editor',
            GDES_URL . 'blocks/review-score/index.js',
            array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
            GDES_VERSION,
            true
        );
        wp_localize_script( 'gdes-review-score-editor', 'GDESReviewScore', array( 'criteria' => $criteria ) );
        register_block_type(
            GDES_PATH . 'blocks/review-score',
            array(
                'editor_script'   => 'gdes-review-score-editor',
                'render_callback' => array( $this, 'render_review_block' ),
            )
        );
    }

    public function render_review_block( $attributes ) {
        $criteria = isset( $attributes['criteria'] ) && is_array( $attributes['criteria'] ) ? $attributes['criteria'] : array();
        if ( empty( $criteria ) ) {
            $criteria = array();
            foreach ( $this->settings()['review_criteria'] as $label ) {
                $criteria[] = array( 'label' => $label, 'status' => 'unrated', 'note' => '' );
            }
        }
        $criteria = array_slice( $criteria, 0, 5 );
        $score = 0;
        $assessed = 0;
        $rows = '';
        foreach ( $criteria as $item ) {
            $label = isset( $item['label'] ) ? sanitize_text_field( $item['label'] ) : '';
            $status = isset( $item['status'] ) && in_array( $item['status'], array( 'meets', 'does_not_meet', 'unrated' ), true ) ? $item['status'] : 'unrated';
            $note = isset( $item['note'] ) ? sanitize_textarea_field( $item['note'] ) : '';
            if ( 'meets' === $status ) {
                $score++;
            }
            if ( 'unrated' !== $status ) {
                $assessed++;
            }
            $status_label = 'meets' === $status ? 'Uppfyller · 1 poäng' : ( 'does_not_meet' === $status ? 'Uppfyller inte · 0 poäng' : 'Ej bedömt ännu' );
            $rows .= '<li class="gdes-score-item is-' . esc_attr( $status ) . '"><div class="gdes-score-item-head"><strong>' . esc_html( $label ) . '</strong><span>' . esc_html( $status_label ) . '</span></div>';
            if ( '' !== $note ) {
                $rows .= '<p>' . nl2br( esc_html( $note ) ) . '</p>';
            }
            $rows .= '</li>';
        }
        $count = count( $criteria );
        return '<section class="gdes-review-score" aria-label="Recensionsbedömning"><header><h2>Vår bedömning</h2><div class="gdes-score-total"><strong>' . esc_html( (string) $score ) . '/' . esc_html( (string) $count ) . '</strong><span>' . esc_html( (string) $assessed ) . '/' . esc_html( (string) $count ) . ' bedömda</span></div></header><ol>' . $rows . '</ol><p class="gdes-score-footnote">Varje kriterium ger 1 poäng om spelet uppfyller bedömningen och 0 poäng om det inte gör det.</p></section>';
    }
}
