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

    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->load_textdomain();

        new Admin();
        new Profile();
        new Membership();
        new Checkout();
    }

    private function load_textdomain() {
        load_plugin_textdomain(
            'wooassociation',
            false,
            dirname( plugin_basename( WA_DIR . 'wooassociation.php' ) ) . '/languages'
        );
    }
}
