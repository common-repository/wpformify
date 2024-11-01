<?php

/**
 * WP Formify
 * PHP version 7
 * Plugin Name: WP Formify (Free)
 * Plugin URI: https://wpformify.com/demo/
 * Description: Easily collect payments for Simple Payment or donations online
 * without coding it yourself or hiring a developer. Skip setting up a complex shopping cart system.
 * Author: WP Formify
 * Author URI: https://wpformify.com/
 * Version: 1.1.0
 * Text Domain: wpformify
 * License: GPLv2 or later
 *
 * @category Wordpress_Plugin
 * @package  wp_formify
 * @author   Author <contact@apiexperts.io>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://wpformify.com/
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
define( 'WPEP_ROOT_URL', plugin_dir_url( __FILE__ ) );
define( 'WPEP_ROOT_PATH', plugin_dir_path( __FILE__ ) );


if ( !function_exists( 'wf_fs' ) ) {
    function wf_fs()
    {
        global  $wf_fs ;
        
        if ( !isset( $wf_fs ) ) {
            // Include Freemius SDK.
            require_once WPEP_ROOT_PATH . 'libraries/freemius/start.php';
            $wf_fs = fs_dynamic_init( array(
                'id'               => '8166',
                'slug'             => 'wp-formify',
                'type'             => 'plugin',
                'public_key'       => 'pk_689d40d5f193ba4badcb8e95e0200',
                'is_premium'       => false,
                'has_addons'       => false,
                'has_paid_plans'   => false,
                'is_org_compliant' => false,
                'menu'             => array(
                'slug'       => 'edit.php?post_type=wp_formify',
                'first-path' => 'edit.php?post_type=wp_formify&page=wpstp-settings',
                'contact'    => false,
                'support'    => false,
                'pricing'    => false,
            ),
                'is_live'          => true,
            ) );
        }
        
        return $wf_fs;
    }
    
    // Init Freemius.
    wf_fs();
    // Signal that SDK was initiated.
    do_action( 'wf_fs_loaded' );
}

if ( !function_exists( 'add_viewport_meta_tag' ) ) {
    function add_viewport_meta_tag()
    {
        echo  '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />' ;
    }

}
require_once WPEP_ROOT_PATH . 'assets/lib/vendor/autoload.php';
add_action( 'wp_head', 'add_viewport_meta_tag', '1' );
require_once WPEP_ROOT_PATH . 'wpf-setup.php';
require_once WPEP_ROOT_PATH . 'modules/payments/square_authorization.php';
require_once WPEP_ROOT_PATH . 'modules/payments/square_payments.php';
require_once WPEP_ROOT_PATH . 'modules/render_forms/form_render_shortcode.php';
require_once WPEP_ROOT_PATH . 'modules/admin_notices/ssl_notice.php';
require_once WPEP_ROOT_PATH . 'modules/admin_notices/square_oauth_notice.php';
add_action(
    'plugins_loaded',
    'wpep_set_refresh_token_cron',
    10,
    2
);
add_action(
    'wpep_weekly_refresh_tokens',
    'wpep_weekly_refresh_tokens',
    10,
    2
);

if ( isset( $_REQUEST ) ) {
    if ( isset( $_REQUEST['post'] ) ) {
        $post_type = get_post_type( sanitize_text_field( wp_unslash( $_REQUEST['post'] ) ) );
    }
    if ( isset( $_REQUEST['post_type'] ) ) {
        $post_type = sanitize_text_field( wp_unslash( $_REQUEST['post_type'] ) );
    }
}


if ( isset( $post_type ) ) {
    
    if ( $post_type == 'wp_formify' ) {
        add_action( 'edit_form_after_editor', 'wpep_render_add_form_ui' );
        add_action( 'admin_enqueue_scripts', 'wpep_include_scripts_easy_pay_type_only' );
        add_action( 'admin_enqueue_scripts', 'wpep_include_stylesheets' );
    }
    
    
    if ( $post_type == 'wpstp_reports' ) {
        add_action( 'admin_enqueue_scripts', 'wpep_include_stylesheets' );
        add_action( 'admin_enqueue_scripts', 'wpep_include_reports_scripts' );
    }
    
    
    if ( $post_type == 'wpstp_subscriptions' ) {
        add_action( 'admin_enqueue_scripts', 'wpep_include_stylesheets' );
        add_action( 'admin_enqueue_scripts', 'wpep_include_scripts_subscription_type_only' );
        add_action( 'admin_enqueue_scripts', 'wpep_include_reports_scripts' );
    }
    
    if ( $post_type == 'wpep-payment' ) {
        // add_action( 'admin_enqueue_scripts', 'wpep_include_stylesheets' );
    }
}

function wpep_render_add_coupon_ui()
{
    require_once WPEP_ROOT_PATH . '/views/backend/coupon_meta_view.php';
}

function wpep_set_refresh_token_cron()
{
    if ( !wp_next_scheduled( 'wpep_weekly_refresh_tokens' ) ) {
        wp_schedule_event( time(), 'weekly', 'wpep_weekly_refresh_tokens' );
    }
}

function wpep_include_reports_scripts()
{
    wp_enqueue_script(
        'wpep_reports_scripts',
        WPEP_ROOT_URL . 'assets/backend/js/reports_scripts.js',
        array(),
        '3.0.0',
        true
    );
}

function wpep_include_stylesheets()
{
    wp_enqueue_style(
        'wpep_backend_style',
        WPEP_ROOT_URL . 'assets/backend/css/wpep_backend_styles.css',
        array(),
        '1.0.0'
    );
}

function wpep_include_scripts_easy_pay_type_only()
{
    wp_enqueue_script(
        'wpep_form-builder',
        WPEP_ROOT_URL . 'assets/backend/js/form-builder.min.js',
        array(),
        '3.0.0',
        true
    );
    wp_enqueue_script(
        'wpep_backend_scripts_multiinput',
        WPEP_ROOT_URL . 'assets/backend/js/wpep_backend_scripts_multiinput.js',
        array(),
        '3.0.0',
        true
    );
    $post_type = get_post_type( get_the_ID() );
    wp_enqueue_script(
        'wpep_backend_script',
        WPEP_ROOT_URL . 'assets/backend/js/wpep_backend_scripts.js',
        array(),
        '3.0.0',
        true
    );
    if ( 'wp_formify' === $post_type ) {
        wp_localize_script( 'wpep_backend_script', 'wpep_hide_elements', array(
            'ajax_url'          => admin_url( 'admin-ajax.php' ),
            'hide_publish_meta' => 'true',
            'wpep_site_url'     => WPEP_ROOT_URL,
        ) );
    }
    wp_enqueue_script(
        'wpep_jscolor_script',
        WPEP_ROOT_URL . 'assets/backend/js/jscolor.js',
        array(),
        '1.0',
        true
    );
}

function wpep_include_scripts_subscription_type_only()
{
    wp_enqueue_script(
        'wpep_backend_subscription_script',
        WPEP_ROOT_URL . 'assets/backend/js/wpep_subscription_actions.js',
        array(),
        '3.0.0',
        true
    );
    wp_localize_script( 'wpep_backend_subscription_script', 'subscription_elements', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
    ) );
}

function wpep_render_add_form_ui()
{
    require_once 'views/backend/form_builder_settings/add_payment_form_custom_fields.php';
}

define( 'WPEP_SQUARE_PLUGIN_NAME', 'WP_EASY_PAY' );
define( 'WPEP_SQUARE_APP_NAME', 'WP_EASY_PAY_SQUARE_APP' );
define( 'WPEP_MIDDLE_SERVER_URL', 'https://connect.apiexperts.io' );
define( 'WPEP_SQUARE_APP_ID', 'sq0idp-k0r5c0MNIBIkTd5pXmV-tg' );
define( 'WPEP_SQUARE_TEST_APP_ID', 'sandbox-sq0idb-H_7j0M8Q7PoDNmMq_YCHKQ' );
add_action( 'init', 'wpep_register_gutenberg_blocks' );
function wpep_register_gutenberg_blocks()
{
    $args = array(
        'numberposts' => 10,
        'post_type'   => 'wp_formify',
    );
    $latest_books = get_posts( $args );
    $wpep_payment_forms = array();
    $count = 0;
    foreach ( $latest_books as $value ) {
        $wpep_payment_forms[$count]['ID'] = $value->ID;
        $wpep_payment_forms[$count]['title'] = $value->post_title;
        $count++;
    }
    wp_register_script( 'wpep_shortcode_block', WPEP_ROOT_URL . 'assets/backend/js/gutenberg_shortcode_block/build/index.js', array( 'wp-blocks' ) );
    wp_enqueue_script( 'wpep_shortcode_block' );
    $wpep_forms = array(
        'forms' => $wpep_payment_forms,
    );
    wp_localize_script( 'wpep_shortcode_block', 'wpep_forms', $wpep_forms );
}

register_block_type( 'wpep/shortcode', array(
    'editor_script'   => 'wpep_shortcode_block',
    'render_callback' => 'custom_gutenberg_render_html',
) );
function custom_gutenberg_render_html( $attributes, $content )
{
    
    if ( isset( $attributes['type'] ) ) {
        $shortcode = '[wpf_form id="' . $attributes['type'] . '"]';
        return $shortcode;
    }

}

// add_action( 'wp_enqueue_scripts', 'wpf_call_stripe_sdk' );
register_activation_hook( __FILE__, 'wpstp_stripe_create_table' );
function wpstp_stripe_create_table()
{
    global  $wpdb ;
    $table_name = $wpdb->prefix . 'wpstp_stripe_customers';
    $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (" . 'stripe_customer_id VARCHAR(200), ' . 'stripe_api_key VARCHAR(200), ' . 'user_id INT NOT NULL, ' . 'PRIMARY KEY (stripe_customer_id)' . "){$charset_collate}; ";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
}
