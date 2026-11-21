<?php
/**
 * Plugin Name: GameDefiners Editorial Studio
 * Description: A modular editorial workspace for creating AI-assisted WordPress drafts and standardized game review scorecards.
 * Version: 0.1.15
 * Requires at least: 6.5
 * Requires PHP: 7.4
 * Author: GameDefiners
 * Text Domain: gamedefiners-editorial-studio
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'GDES_VERSION', '0.1.15' );
define( 'GDES_FILE', __FILE__ );
define( 'GDES_PATH', plugin_dir_path( __FILE__ ) );
define( 'GDES_URL', plugin_dir_url( __FILE__ ) );

require_once GDES_PATH . 'includes/class-gdes-movie-reporter.php';
require_once GDES_PATH . 'includes/class-gdes-openai.php';
require_once GDES_PATH . 'includes/class-gdes-plugin.php';
require_once GDES_PATH . 'includes/class-gdes-generator.php';
require_once GDES_PATH . 'includes/class-gdes-bulk.php';
require_once GDES_PATH . 'includes/class-gdes-images.php';
GDES_Images::boot();
GDES_Bulk::boot();
if ( defined( 'WP_CLI' ) && WP_CLI ) {
    require_once GDES_PATH . 'includes/class-gdes-game-auto.php';
    require_once GDES_PATH . 'includes/class-gdes-cli.php';
    require_once GDES_PATH . 'includes/class-gdes-release-scan.php';
}

register_activation_hook( __FILE__, array( 'GDES_Plugin', 'activate' ) );

add_action( 'plugins_loaded', function () {
    GDES_Plugin::instance();
} );
