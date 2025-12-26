<?php
/**
 * Plugin Name: WooAssociation
 * Description: Gestione adesione ad associazione, con quota automatica e rinnovo annuale, tramite WooCommerce.
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

/**
 * Carica il plugin principale
 */
require_once WA_DIR . 'includes/class-wooassociation-plugin.php';

/**
 * Inizializza il plugin
 */
function wa_init_plugin() {
    \WooAssociation\Plugin::instance();
}
add_action( 'plugins_loaded', 'wa_init_plugin' );
