<?php
/**
 * WP EASY PAY
 *
 * PHP version 7
 *
 * @category Wordpress_Plugin
 * @package  wp_formify
 * @author   Author <contact@apiexperts.io>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://wpeasypay.com/
 */

// require_once WPEP_ROOT_PATH . 'modules/payments/square_configuration.php';

add_action( 'admin_init', 'wpep_authorize_with_square' );
add_action( 'admin_init', 'wpep_square_callback_success' );
add_action( 'admin_init', 'wpep_square_disconnect' );

function wpep_authorize_with_square() {

	if ( ! empty( $_GET['wpep_prepare_connection_call'] ) ) {
		$form_id = $_GET['form_id'];
		if ( isset( $_GET['sandbox'] ) ) {

			$mode = 'test';

			if ( $_GET['wpep_page_post'] == 'individual' ) {

				$redirect_url = admin_url( 'admin.php?identifier=individual_test_' . $form_id );

			} else {

				$redirect_url = admin_url( 'admin.php?identifier=global_test' );
				
			}

		} else {

			$mode = 'live';

			if ( $_GET['wpep_page_post'] == 'individual' ) {

				$redirect_url = admin_url( 'admin.php?identifier=individual_live_' . $form_id );
			} else {

				$redirect_url = admin_url( 'admin.php?identifier=global_live' );
			}
		}

		$authorization_url = 'https://wordpress-224605-788534.cloudwaysapps.com/';
		$params            = array(

			'oauth_mode'   => $mode,
			'redirect_url' => $redirect_url,
			'request_type' => 'auth_request',
		);

		$authorization_url = add_query_arg( $params, $authorization_url );

		wp_redirect( $authorization_url );

	}

}

function wpep_square_callback_success() {
	if ( ! empty( $_REQUEST['access_token'] ) and ! empty( $_REQUEST['stripe_publishable_key'] ) and ! empty( $_REQUEST['stripe_user_id'] ) ) {

		$identifier = explode( '_', $_REQUEST['identifier'] );

		$mode        = $identifier[1];
		$outh_screen = $identifier[0];
		$form_id     = $identifier[2];

		if ( $outh_screen == 'global' ) {

			if ( 'test' == $mode ) {

				update_option( 'wpf_test_access_token', sanitize_text_field( $_REQUEST['access_token'] ) );
				update_option( 'wpf_test_refresh_token', sanitize_text_field( $_REQUEST['refresh_token'] ) );
				update_option( 'wpf_test_publishable_key', sanitize_text_field( $_REQUEST['stripe_publishable_key'] ) );
				update_option( 'wpf_test_stripe_user_id', sanitize_text_field( $_REQUEST['stripe_user_id'] ) );

			}

			if ( 'live' == $mode ) {

				update_option( 'wpf_access_token', sanitize_text_field( $_REQUEST['access_token'] ) );
				update_option( 'wpf_refresh_token', sanitize_text_field( $_REQUEST['refresh_token'] ) );
				update_option( 'wpf_publishable_key', sanitize_text_field( $_REQUEST['stripe_publishable_key'] ) );
				update_option( 'wpf_stripe_user_id', sanitize_text_field( $_REQUEST['stripe_user_id'] ) );
			}

			$initialPage = admin_url( 'edit.php?post_type=wp_formify&page=wpstp-settings' );

		}

		if ( $outh_screen == 'individual' ) {

			if ( 'test' == $mode ) {

				update_post_meta( $form_id, 'wpf_test_access_token', sanitize_text_field( $_REQUEST['access_token'] ) );
				update_post_meta( $form_id, 'wpf_test_refresh_token', sanitize_text_field( $_REQUEST['refresh_token'] ) );
				update_post_meta( $form_id, 'wpf_test_publishable_key', sanitize_text_field( $_REQUEST['stripe_publishable_key'] ) );
				update_post_meta( $form_id, 'wpf_test_stripe_user_id', sanitize_text_field( $_REQUEST['stripe_user_id'] ) );
			}

			if ( 'live' == $mode ) {

				update_post_meta( $form_id, 'wpf_access_token', sanitize_text_field( $_REQUEST['access_token'] ) );
				update_post_meta( $form_id, 'wpf_refresh_token', sanitize_text_field( $_REQUEST['refresh_token'] ) );
				update_post_meta( $form_id, 'wpf_publishable_key', sanitize_text_field( $_REQUEST['stripe_publishable_key'] ) );
				update_post_meta( $form_id, 'wpf_stripe_user_id', sanitize_text_field( $_REQUEST['stripe_user_id'] ) );

			}

			$initialPage = admin_url( 'post.php?post=' . $form_id . '&action=edit' );

		}

		wp_redirect( $initialPage );

	}

}

function wpep_square_disconnect() {
	if ( isset( $_REQUEST['wpep_disconnect_global'] ) ) {

		if ( $_REQUEST['wpep_disconnect_global'] == 'true' ) {

			if ( 'live' === $_REQUEST['mode'] ) {

				delete_option( 'wpf_access_token' );
				delete_option( 'wpf_access_token' );
				delete_option( 'wpf_publishable_key' );
				delete_option( 'wpf_stripe_user_id' );

			}

			if ( 'test' === $_REQUEST['mode'] ) {

				delete_option( 'wpf_test_access_token' );
				delete_option( 'wpf_test_refresh_token' );
				delete_option( 'wpf_test_publishable_key' );
				delete_option( 'wpf_test_stripe_user_id' );

			}

			$initialPage = admin_url( 'edit.php?post_type=wp_formify&page=wpstp-settings' );

			wp_redirect( $initialPage );

		}
	}

	if ( isset( $_REQUEST['wpep_disconnect_individual'] ) ) {

		$form_id = $_REQUEST['form_id'];

		if ( $_REQUEST['wpep_disconnect_individual'] == 'true' ) {

			if ( 'test' == $_REQUEST['mode'] ) {

				delete_post_meta( $form_id, 'wpf_test_access_token' );
				delete_post_meta( $form_id, 'wpf_test_refresh_token' );
				delete_post_meta( $form_id, 'wpf_test_publishable_key' );
				delete_post_meta( $form_id, 'wpf_test_stripe_user_id' );
			}

			if ( 'live' == $_REQUEST['mode'] ) {

				delete_post_meta( $form_id, 'wpf_access_token' );
				delete_post_meta( $form_id, 'wpf_refresh_token' );
				delete_post_meta( $form_id, 'wpf_publishable_key' );
				delete_post_meta( $form_id, 'wpf_stripe_user_id' );

			}

			$initialPage = admin_url( 'post.php?post=' . $form_id . '&action=edit' );

			wp_redirect( $initialPage );

		}
	}

}
