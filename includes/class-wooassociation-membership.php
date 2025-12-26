<?php
namespace WooAssociation;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Membership {

    private static $quota_added_flag = false;

    public function __construct() {
        add_action( 'woocommerce_before_calculate_totals', [ $this, 'maybe_add_quota_to_cart' ], 5 );
        add_action( 'woocommerce_order_status_completed', [ $this, 'maybe_activate_membership_from_order' ], 10, 1 );
    }

    protected function get_settings() {
        $admin = new Admin();
        return $admin->get_settings();
    }

    protected function get_quota_product_id() {
        $settings = $this->get_settings();
        return absint( $settings['quota_product_id'] );
    }

    protected function get_membership_duration_days() {
        $settings = $this->get_settings();
        return absint( $settings['membership_duration_days'] );
    }

    public function maybe_add_quota_to_cart( $cart ) {
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
            return;
        }
        if ( self::$quota_added_flag ) {
            return;
        }
        if ( ! is_user_logged_in() ) {
            return;
        }

        $quota_product_id = $this->get_quota_product_id();
        if ( ! $quota_product_id ) {
            return;
        }

        $user_id = get_current_user_id();

        // se mai socio e profilo completo → prima adesione
        if ( ! API::is_member( $user_id ) && ! API::needs_renewal( $user_id ) ) {
            if ( ! $this->is_profile_complete( $user_id ) ) {
                return;
            }

            if ( $this->cart_contains_product( $cart, $quota_product_id ) ) {
                return;
            }

            $cart->add_to_cart( $quota_product_id );
            self::$quota_added_flag = true;
            return;
        }

        // se serve rinnovo → aggiungi quota
        if ( API::needs_renewal( $user_id ) ) {
            if ( $this->cart_contains_product( $cart, $quota_product_id ) ) {
                return;
            }
            $cart->add_to_cart( $quota_product_id );
            self::$quota_added_flag = true;
        }
    }

    protected function cart_contains_product( $cart, $product_id ) {
        foreach ( $cart->get_cart() as $item ) {
            if ( (int) $item['product_id'] === (int) $product_id ) {
                return true;
            }
        }
        return false;
    }

    protected function is_profile_complete( $user_id ) {
        $flag = get_user_meta( $user_id, 'wml_profile_complete', true );
        return ( 'yes' === $flag );
    }

    public function maybe_activate_membership_from_order( $order_id ) {
        $order = wc_get_order( $order_id );
        if ( ! $order ) {
            return;
        }

        $user_id = $order->get_user_id();
        if ( ! $user_id ) {
            return;
        }

        $quota_product_id = $this->get_quota_product_id();
        if ( ! $quota_product_id ) {
            return;
        }

        $found = false;
        foreach ( $order->get_items() as $item ) {
            if ( (int) $item->get_product_id() === (int) $quota_product_id ) {
                $found = true;
                break;
            }
        }

        if ( ! $found ) {
            return;
        }

        $duration = $this->get_membership_duration_days();
        API::set_membership( $user_id, $duration );
    }
}
