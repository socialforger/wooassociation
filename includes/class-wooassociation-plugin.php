<?php
namespace WooAssociation;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once WA_DIR . 'includes/class-wooassociation-admin.php';
require_once WA_DIR . 'includes/class-wooassociation-profile.php';
require_once WA_DIR . 'includes/class-wooassociation-membership.php';
require_once WA_DIR . 'includes/class-wooassociation-checkout.php';
require_once WA_DIR . 'includes/class-wooassociation-api.php';

class Plugin {

    /**
     * Singleton instance
     */
    private static $instance = null;

    /**
     * Get instance
     */
    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->load_textdomain();
        $this->init_modules();
    }

    /**
     * Load translations
     */
    private function load_textdomain() {
        load_plugin_textdomain(
            'wooassociation',
            false,
            dirname( plugin_basename( WA_DIR . 'wooassociation.php' ) ) . '/languages'
        );
    }

    /**
     * Initialize all plugin modules
     */
    private function init_modules() {
        new Admin();        // Impostazioni admin
        new Profile();      // Campi profilo utente
        new Membership();   // Logica adesione e rinnovo
        new Checkout();     // Controlli checkout
        // API è statica, non serve istanziarla
    }
}
