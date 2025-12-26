<?php
namespace WooAssociation;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class API {

    const META_IS_MEMBER   = 'wa_is_member';
    const META_STARTED     = 'wa_membership_started';
    const META_EXPIRES     = 'wa_membership_expires';

    public static function is_member( $user_id ) {
        $flag = get_user_meta( $user_id, self::META_IS_MEMBER, true );
        $expires = (int) get_user_meta( $user_id, self::META_EXPIRES, true );

        if ( 'yes' !== $flag ) {
            return false;
        }

        if ( $expires && $expires < time() ) {
            return false;
        }

        return true;
    }

    public static function needs_renewal( $user_id ) {
        $flag    = get_user_meta( $user_id, self::META_IS_MEMBER, true );
        $expires = (int) get_user_meta( $user_id, self::META_EXPIRES, true );

        if ( 'yes' !== $flag ) {
            return false; // mai stato socio
        }

        if ( ! $expires ) {
            return false;
        }

        return ( $expires < time() );
    }

    public static function get_membership_data( $user_id ) {
        return [
            'is_member' => self::is_member( $user_id ),
            'started'   => (int) get_user_meta( $user_id, self::META_STARTED, true ),
            'expires'   => (int) get_user_meta( $user_id, self::META_EXPIRES, true ),
        ];
    }

    public static function set_membership( $user_id, $duration_days ) {
        $now     = time();
        $expires = strtotime( '+' . absint( $duration_days ) . ' days', $now );

        update_user_meta( $user_id, self::META_IS_MEMBER, 'yes' );
        update_user_meta( $user_id, self::META_STARTED, $now );
        update_user_meta( $user_id, self::META_EXPIRES, $expires );
    }
}
