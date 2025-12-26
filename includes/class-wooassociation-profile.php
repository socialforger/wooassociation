<?php
namespace WooAssociation;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Profile {

    public function __construct() {
        add_action( 'woocommerce_edit_account_form', [ $this, 'render_extra_fields' ] );
        add_action( 'woocommerce_save_account_details', [ $this, 'save_extra_fields' ], 10, 1 );
    }

    public function render_extra_fields() {
        $user_id = get_current_user_id();
        if ( ! $user_id ) {
            return;
        }

        $cf        = get_user_meta( $user_id, 'wa_codice_fiscale', true );
        $statuto   = get_user_meta( $user_id, 'wa_statuto_accepted', true );
        $privacy   = get_user_meta( $user_id, 'wa_privacy_accepted', true );
        ?>
        <fieldset>
            <legend><?php esc_html_e( 'Dati per adesione all’associazione', 'wooassociation' ); ?></legend>

            <p class="form-row form-row-first">
                <label for="wa_codice_fiscale"><?php esc_html_e( 'Codice fiscale', 'wooassociation' ); ?> *</label>
                <input type="text" name="wa_codice_fiscale" id="wa_codice_fiscale"
                       value="<?php echo esc_attr( $cf ); ?>" />
            </p>

            <div class="clear"></div>

            <p class="form-row">
                <label>
                    <input type="checkbox" name="wa_statuto_accepted" value="1"
                        <?php checked( $statuto, 'yes' ); ?> />
                    <?php esc_html_e( 'Dichiaro di aver letto e accettato lo statuto dell’associazione.', 'wooassociation' ); ?>
                </label>
            </p>

            <p class="form-row">
                <label>
                    <input type="checkbox" name="wa_privacy_accepted" value="1"
                        <?php checked( $privacy, 'yes' ); ?> />
                    <?php esc_html_e( 'Autorizzo il trattamento dei miei dati personali ai fini di adesione all’associazione.', 'wooassociation' ); ?>
                </label>
            </p>

        </fieldset>
        <?php
    }

    public function save_extra_fields( $user_id ) {
        if ( ! $user_id ) {
            return;
        }

        $cf = isset( $_POST['wa_codice_fiscale'] )
            ? sanitize_text_field( wp_unslash( $_POST['wa_codice_fiscale'] ) )
            : '';

        $statuto = ! empty( $_POST['wa_statuto_accepted'] ) ? 'yes' : 'no';
        $privacy = ! empty( $_POST['wa_privacy_accepted'] ) ? 'yes' : 'no';

        update_user_meta( $user_id, 'wa_codice_fiscale', $cf );
        update_user_meta( $user_id, 'wa_statuto_accepted', $statuto );
        update_user_meta( $user_id, 'wa_privacy_accepted', $privacy );

        // calcolo "profilo completo" basilare
        if ( ! empty( $cf ) && 'yes' === $statuto && 'yes' === $privacy ) {
            update_user_meta( $user_id, 'wml_profile_complete', 'yes' );
        } else {
            update_user_meta( $user_id, 'wml_profile_complete', 'no' );
        }
    }
}
