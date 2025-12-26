<?php
/**
 * Plugin Name: Woo Association
 * Description: Gestione adesione associativa con quota automatica e rinnovo annuale tramite WooCommerce.
 * Version: 1.0.0
 * Author: Socialforger
 * Text Domain: wooassociation
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'WA_VERSION', '1.0.0' );
define( 'WA_DIR', plugin_dir_path( __FILE__ ) );
define( 'WA_URL', plugin_dir_url( __FILE__ ) );

require_once WA_DIR . 'includes/class-wooassociation-plugin.php';

function wa_init_plugin() {
    \WooAssociation\Plugin::instance();
}
add_action( 'plugins_loaded', 'wa_init_plugin' );
