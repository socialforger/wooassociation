<?php
namespace WooAssociation;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Checkout {

    public function __construct() {
        add_action( 'woocommerce_checkout_process', [ $this, 'maybe_block_checkout_if_profile_incomplete' ] );
    }

    protected function get_settings() {
        $admin = new Admin();
        return $admin->get_settings();
    }

    public function maybe_block_checkout_if_profile_incomplete() {
        if ( ! is_user_logged_in() ) {
            return;
        }

        $settings = $this->get_settings();
        if ( empty( $settings['require_profile_complete'] ) ) {
            return;
        }

        $user_id = get_current_user_id();
        $complete = get_user_meta( $user_id, 'wml_profile_complete', true );

        if ( 'yes' !== $complete ) {
            wc_add_notice(
                __( 'Per procedere con l’ordine devi prima completare il tuo profilo nella sezione "Il mio account".', 'wooassociation' ),
                'error'
            );
        }
    }
}
