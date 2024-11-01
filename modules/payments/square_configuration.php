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

require_once WPEP_ROOT_PATH . 'assets/lib/square-sdk/autoload.php';


function wpep_setup_square_configuration() {
	$liveMode = get_option( 'wpep_square_payment_mode_global' );

	$apiConfig = new \SquareConnect\Configuration();

	if ( $liveMode == 'on' ) {

		$accessToken = get_option( 'wpep_live_token_upgraded' );
		$apiConfig->setHost( 'https://connect.squareup.com' );
		$apiConfig->setAccessToken( $accessToken );

	} else {

		$accessToken = get_option( 'wpep_square_test_token_global' );
		$apiConfig->setHost( 'https://connect.squareupsandbox.com' );
		$apiConfig->setAccessToken( $accessToken );
	}

	$apiClient = new \SquareConnect\ApiClient( $apiConfig );

	return $apiClient;
}


function wpep_setup_square_with_access_token( $wpep_access_token, $wpep_sandbox = false ) {
	$apiConfig = new \SquareConnect\Configuration();

	if ( 'yes' == $wpep_sandbox ) {

		$apiConfig->setHost( 'https://connect.squareupsandbox.com' );

	} else {

		$apiConfig->setHost( 'https://connect.squareup.com' );
	}

	$apiConfig->setAccessToken( $wpep_access_token );

	$apiClient = new \SquareConnect\ApiClient( $apiConfig );

	return $apiClient;
}

function wpep_setup_square_configuration_by_form_id( $current_form_id ) {

	$apiConfig = new \SquareConnect\Configuration();

	$wpep_individual_form_global = get_post_meta( $current_form_id, 'wpep_individual_form_global', true );

	/* If form is using global settings */
	if ( $wpep_individual_form_global == 'on' ) {

		$wpep_payment_mode = get_option( 'wpep_square_payment_mode_global' );

		if ( $wpep_payment_mode == 'on' ) {
			/* if live is on */
			$accessToken = get_option( 'wpep_live_token_upgraded', true );
			$apiConfig->setHost( 'https://connect.squareup.com' );
			$apiConfig->setAccessToken( $accessToken );
			$square_currency = get_option( 'wpf_live_currency', true );

		}

		if ( $wpep_payment_mode !== 'on' ) {

			/* if test is on */
			$accessToken = get_option( 'wpep_square_test_token_global', true );
			$apiConfig->setHost( 'https://connect.squareupsandbox.com' );
			$apiConfig->setAccessToken( $accessToken );

			$square_currency = get_option( 'wpf_test_currency', true );

		}
	}

	/* If form is using its own settings */
	if ( $wpep_individual_form_global == '' ) {

		$wpep_payment_mode = get_post_meta( $current_form_id, 'wpep_payment_mode', true );

		if ( $wpep_payment_mode == 'on' ) {

			/* if live is on */
			$accessToken = get_post_meta( $current_form_id, 'wpep_live_token_upgraded', true );
			$apiConfig->setHost( 'https://connect.squareup.com' );
			$apiConfig->setAccessToken( $accessToken );

		}

		if ( $wpep_payment_mode !== 'on' ) {

			/* if test is on */
			$accessToken = get_post_meta( $current_form_id, 'wpep_square_test_token', true );
			$apiConfig->setHost( 'https://connect.squareupsandbox.com' );
			$apiConfig->setAccessToken( $accessToken );

		}
	}

	$apiClient = new \SquareConnect\ApiClient( $apiConfig );

	return $apiClient;

}


function wpep_get_location_by_form_id( $current_form_id ) {

	$wpep_individual_form_global = get_post_meta( $current_form_id, 'wpep_individual_form_global', true );

	/* If form is using global settings */
	if ( $wpep_individual_form_global == 'on' ) {

		$wpep_payment_mode = get_option( 'wpep_square_payment_mode_global' );

		if ( $wpep_payment_mode == 'on' ) {
			/* if live is on */
			$location_id = get_option( 'wpep_square_location_id', true );

		}

		if ( $wpep_payment_mode !== 'on' ) {

			/* if test is on */
			$location_id = get_option( 'wpep_square_test_location_id_global', true );

		}
	}

	/* If form is using its own settings */
	if ( $wpep_individual_form_global == '' ) {

		$wpep_payment_mode = get_post_meta( $current_form_id, 'wpep_payment_mode', true );

		if ( $wpep_payment_mode == 'on' ) {

			/* if live is on */
			$location_id = get_post_meta( $current_form_id, 'wpep_square_location_id', true );

		}

		if ( $wpep_payment_mode !== 'on' ) {

			/* if test is on */
			$location_id = get_post_meta( $current_form_id, 'wpep_square_test_location_id', true );

		}
	}

	return $location_id;

}


function wpep_get_currency( $wpep_current_form_id ) {

	$form_payment_global = get_post_meta( $wpep_current_form_id, 'wpep_individual_form_global', true );

	if ( $form_payment_global == 'on' ) {

		$global_payment_mode = get_option( 'wpep_square_payment_mode_global', true );

		if ( $global_payment_mode == 'on' ) {

			/* If Global Form Live Mode */

			$square_currency = get_option( 'wpf_live_currency' );

		}

		if ( $global_payment_mode !== 'on' ) {

			/* If Global Form Test Mode */

			$square_currency = get_option( 'wpf_test_currency' );

		}
	}

	if ( $form_payment_global !== 'on' ) {

		$individual_payment_mode = get_post_meta( $wpep_current_form_id, 'wpep_payment_mode', true );

		if ( $individual_payment_mode == 'on' ) {

			/* If Individual Form Live Mode */

			$square_currency = get_post_meta( $wpep_current_form_id, 'wpf_individual_currency_live', true );

		}

		if ( $individual_payment_mode !== 'on' ) {

			/* If Individual Form Test Mode */

			$square_currency = get_post_meta( $wpep_current_form_id, 'wpf_individual_currency_test', true );

		}
	}

	return $square_currency;

}


function wpep_refresh_token_details( $wpep_current_form_id ) {

	$refresh_token_details = array();

	$form_payment_global = get_post_meta( $wpep_current_form_id, 'wpep_individual_form_global', true );

	if ( $form_payment_global == 'on' ) {

		$global_payment_mode = get_option( 'wpep_square_payment_mode_global', true );

		if ( $global_payment_mode == 'on' ) {

			/* If Global Form Live Mode */

			$refresh_token_details['refresh_token'] = get_option( 'wpep_refresh_token', false );
			$refresh_token_details['expires_at']    = get_option( 'wpep_token_expires_at', false );
			$refresh_token_details['type']          = 'global';

		}
	}

	if ( $form_payment_global !== 'on' ) {

		$individual_payment_mode = get_post_meta( $wpep_current_form_id, 'wpep_payment_mode', true );

		if ( $individual_payment_mode == 'on' ) {

			/* If Individual Form Live Mode */

			$refresh_token_details['refresh_token'] = get_post_meta( $wpep_current_form_id, 'wpep_refresh_token', true );
			$refresh_token_details['expires_at']    = get_post_meta( $wpep_current_form_id, 'wpep_token_expires_at', true );
			$refresh_token_details['type']          = 'specific';

		}
	}

	return $refresh_token_details;

}



function wpstp_setup_stripe_configuration_by_form_id( $current_form_id ) {

	$wpstp_stripe_individual_form_global = get_post_meta( $current_form_id, 'wpep_individual_form_global', true );
	/* If form is using global settings */
	if ( $wpstp_stripe_individual_form_global == 'on' ) {

		$wpstp_stripe_payment_mode = get_option( 'wpep_square_payment_mode_global' );

		if ( $wpstp_stripe_payment_mode == 'on' ) { // live
			/* if live is on */
			$publish_key = get_option( 'wpf_publishable_key', true );
			$secret_key  = get_option( 'wpf_access_token', true );
			$currency    = get_option( 'wpf_live_currency', true );
		}

		if ( $wpstp_stripe_payment_mode !== 'on' ) { // test

			/* if test is on */
			$publish_key = get_option( 'wpf_test_publishable_key', true );
			$secret_key  = get_option( 'wpf_test_access_token', true );
			$currency    = get_option( 'wpf_test_currency', true );
		}
	}

	/* If form is using its own settings */
	if ( $wpstp_stripe_individual_form_global == '' ) {

		$wpstp_stripe_payment_mode = get_post_meta( $current_form_id, 'wpep_payment_mode', true ); // test || live

		if ( $wpstp_stripe_payment_mode == 'on' ) {
			/* if live is on */
			$publish_key = get_post_meta( $current_form_id, 'wpf_publishable_key', true );
			$secret_key  = get_post_meta( $current_form_id, 'wpf_access_token', true );
			$currency    = get_post_meta( $current_form_id, 'wpf_individual_currency_live', true );
		}

		if ( $wpstp_stripe_payment_mode !== 'on' ) {
			/* if test is on */
			$publish_key = get_post_meta( $current_form_id, 'wpf_test_publishable_key', true );
			$secret_key  = get_post_meta( $current_form_id, 'wpf_test_access_token', true );
			$currency    = get_post_meta( $current_form_id, 'wpf_individual_currency_test', true );
		}
	}

	$keys = array(
		'currency'    => $currency,
		'secret_key'  => $secret_key,
		'publish_key' => $publish_key,
	);

	return $keys;

}
