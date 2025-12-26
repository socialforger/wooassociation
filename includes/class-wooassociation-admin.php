<?php
namespace WooAssociation;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Admin {

    const OPTION_GROUP   = 'wa_settings_group';
    const OPTION_NAME    = 'wa_settings';
    const PAGE_SLUG      = 'wooassociation-settings';

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
    }

    public function add_settings_page() {
        add_submenu_page(
            'woocommerce',
            __( 'Impostazioni Associazione', 'wooassociation' ),
            __( 'Associazione', 'wooassociation' ),
            'manage_woocommerce',
            self::PAGE_SLUG,
            [ $this, 'render_settings_page' ]
        );
    }

    public function register_settings() {
        register_setting(
            self::OPTION_GROUP,
            self::OPTION_NAME,
            [ $this, 'sanitize_settings' ]
        );

        add_settings_section(
            'wa_main_section',
            __( 'Configurazione adesione associativa', 'wooassociation' ),
            '__return_false',
            self::PAGE_SLUG
        );

        add_settings_field(
            'quota_product_id',
            __( 'ID prodotto quota associativa', 'wooassociation' ),
            [ $this, 'field_quota_product_id' ],
            self::PAGE_SLUG,
            'wa_main_section'
        );

        add_settings_field(
            'membership_duration_days',
            __( 'Durata adesione (giorni)', 'wooassociation' ),
            [ $this, 'field_membership_duration_days' ],
            self::PAGE_SLUG,
            'wa_main_section'
        );

        add_settings_field(
            'require_profile_complete',
            __( 'Richiedi profilo completo prima del checkout', 'wooassociation' ),
            [ $this, 'field_require_profile_complete' ],
            self::PAGE_SLUG,
            'wa_main_section'
        );
    }

    public function sanitize_settings( $input ) {
        $output = [];

        $output['quota_product_id'] = isset( $input['quota_product_id'] )
            ? absint( $input['quota_product_id'] )
            : 0;

        $output['membership_duration_days'] = isset( $input['membership_duration_days'] )
            ? max( 1, absint( $input['membership_duration_days'] ) )
            : 365;

        $output['require_profile_complete'] = ! empty( $input['require_profile_complete'] ) ? 1 : 0;

        return $output;
    }

    public function get_settings() {
        $defaults = [
            'quota_product_id'        => 0,
            'membership_duration_days'=> 365,
            'require_profile_complete'=> 1,
        ];

        $settings = get_option( self::OPTION_NAME, [] );
        return wp_parse_args( $settings, $defaults );
    }

    public function field_quota_product_id() {
        $settings = $this->get_settings();
        ?>
        <input type="number" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[quota_product_id]"
               value="<?php echo esc_attr( $settings['quota_product_id'] ); ?>" min="0" class="small-text" />
        <p class="description">
            <?php esc_html_e( 'ID del prodotto WooCommerce usato come quota associativa. L’importo può essere modificato dall’amministratore nelle impostazioni del prodotto.', 'wooassociation' ); ?>
        </p>
        <?php
    }

    public function field_membership_duration_days() {
        $settings = $this->get_settings();
        ?>
        <input type="number" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[membership_duration_days]"
               value="<?php echo esc_attr( $settings['membership_duration_days'] ); ?>" min="1" class="small-text" />
        <p class="description">
            <?php esc_html_e( 'Numero di giorni di validità dell’adesione (es. 365).', 'wooassociation' ); ?>
        </p>
        <?php
    }

    public function field_require_profile_complete() {
        $settings = $this->get_settings();
        ?>
        <label>
            <input type="checkbox" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[require_profile_complete]"
                   value="1" <?php checked( $settings['require_profile_complete'], 1 ); ?> />
            <?php esc_html_e( 'Blocca il checkout se il profilo non è completo.', 'wooassociation' ); ?>
        </label>
        <?php
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Impostazioni Associazione', 'wooassociation' ); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( self::OPTION_GROUP );
                do_settings_sections( self::PAGE_SLUG );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
