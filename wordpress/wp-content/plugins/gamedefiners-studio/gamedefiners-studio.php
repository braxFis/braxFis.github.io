<?php
/**
 * Plugin Name: GameDefiners Studio
 * Description: Separate Editorial and Guides workspaces with OpenAI-assisted WordPress draft creation.
 * Version: 0.3.1
 * Requires PHP: 7.4
 * Author: GameDefiners
 * Text Domain: gamedefiners-studio
 */
if (!defined('ABSPATH')) { exit; }
define('GDS_VERSION', '0.3.1');
define('GDS_PATH', plugin_dir_path(__FILE__));
require_once GDS_PATH . 'includes/class-gds-admin.php';
add_action('plugins_loaded', static function () { new GDS_Admin(); });

if (defined('WP_CLI') && WP_CLI) {
    require_once GDS_PATH . 'includes/class-gds-cli.php';
    WP_CLI::add_command('gdstudio', 'GDS_CLI');
}
