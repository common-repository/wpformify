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

require_once WPEP_ROOT_PATH . 'modules/payments/square_configuration.php';
require_once WPEP_ROOT_PATH . 'modules/payments/payment_helper_functions.php';
require_once WPEP_ROOT_PATH . 'modules/error_logging.php';
require_once WPEP_ROOT_PATH . 'assets/lib/vendor/autoload.php';


add_action( 'wp_ajax_wpep_payment_request', 'wpep_payment_request' );
add_action( 'wp_ajax_nopriv_wpep_payment_request', 'wpep_payment_request' );

add_action( 'wp_ajax_wpep_file_upload', 'wpep_file_upload' );
add_action( 'wp_ajax_nopriv_wpep_file_upload', 'wpep_file_upload' );

add_action( 'wp_ajax_wpep_recaptcha_verification', 'wpep_recaptcha_verification' );
add_action( 'wp_ajax_nopriv_wpep_recaptcha_verification', 'wpep_recaptcha_verification' );

add_action( 'wp_ajax_wpep_payment_refund', 'wpep_payment_refund' );


function wpep_file_upload() {

	$transaction_report_id = sanitize_text_field( $_POST['transaction_report_id'] );
	$uploadedfile          = sanitize_text_field( $_FILES['file'] );
	$upload_overrides      = array(
		'test_form' => false,
	);
	$movefile              = wp_handle_upload( $uploadedfile, $upload_overrides );

	if ( $movefile && ! isset( $movefile['error'] ) ) {
		$return_response = array(
			'uploaded_file_url' => $movefile['url'],
		);
		$form_values     = get_post_meta( $transaction_report_id, 'wpep_form_values' );
		array_push(
			$form_values[0],
			array(
				'label' => 'Uploaded File URL',
				'value' => $movefile['url'],
			)
		);
		
		update_post_meta( $transaction_report_id, 'wpep_form_values', $form_values[0] );

		wp_die( json_encode($return_response) );

	} else {
		echo esc_html( $movefile['error'] );
		wp_die();
	}

}

function wpep_payment_request() {

	$payment_type = sanitize_text_field( $_POST['payment_type'] );

	if ( $payment_type == 'single' ) {

		wpep_single_square_payment();
	}

	if ( $payment_type == 'donation_recurring' ) {

		wpep_subscription_square_payment();

	}

	if ( $payment_type == 'subscription' ) {

		wpstp_subscription_stripe_payment();

	}

}

function wpep_recaptcha_verification( $recaptcha_response ) {


		$recaptcha_response = sanitize_text_field( $_POST['recaptcha_response'] );
		$url    = 'https://www.google.com/recaptcha/api/siteverify';
		$fields = array(
			'secret'   => get_option( 'wpep_recaptcha_secret_key' ),
			'response' => $recaptcha_response,
		);
		foreach ( $fields as $key => $value ) {
			$fields_string .= $key . '=' . $value . '&';
		}
		$response = wp_remote_post( $url, $fields_string );
		echo esc_html( $response );

		wp_die();
}


function wpep_single_square_payment( $square_customer_id = false, $square_customer_card_on_file = false, $current_form_id = false, $amount = false ) {

	// print_r($_POST);

	// wp_die();
	$form_values  = $_POST['form_values'];
	$payment_type = sanitize_text_field( $_POST['payment_type'] );

	if ( $current_form_id == false ) {
		$current_form_id = sanitize_text_field( $_POST['current_form_id'] );
	}

	if ( ! empty( $_POST ) && $amount == false && isset( $_POST['nonce'] ) ) {
		$nonce  = filter_var_array($_POST['nonce'], FILTER_SANITIZE_STRING);
		$amount = sanitize_text_field( $_POST['amount'] ) * 100;
	}

	$note               = get_post_meta( $current_form_id, 'wpep_transaction_notes_box', true );
	$fees_data          = get_post_meta( $current_form_id, 'fees_data' );
	$form_values_object = (object) $form_values;

	foreach ( $form_values_object as $form_value ) {

		if ( isset( $form_value['label'] ) && isset( $form_value['value'] ) ) {

			$label = $form_value['label'];
			$value = $form_value['value'];

			if ( $label !== null ) {

				if ( $label == 'Email' ) {
					$label = 'user_email';
					$to    = $value;
				}
				$tag = '[' . str_replace( ' ', '_', strtolower( $label ) ) . ']';
				$note = str_replace( $tag, $value, $note );

				if ( isset( $fees_data[0] ) && count( $fees_data[0] ) > 0 ) {
					foreach ( $fees_data[0]['name'] as $key => $fees ) {
						$fees_name  = isset( $fees_data[0]['name'][ $key ] ) ? $fees_data[0]['name'][ $key ] : '';
						$fees_value = isset( $fees_data[0]['value'][ $key ] ) ? $fees_data[0]['value'][ $key ] : '';
					}
				}
			}
		}
	}

	$keys = include_stripe_api( $current_form_id );

	$charge = \Stripe\Charge::create(
		array(
			'amount'      => $amount,
			'currency'    => $keys['currency'],
			'description' => $note,
			'source'      => $nonce['token']['id'],
		)
	);

	if ( isset( $charge->id ) && isset( $charge->captured ) && $charge->captured == true ) {

			/* Adding Single Transaction Report */
			$transaction_id   = $charge->id;
			$transaction_data = array(
				'transaction_id'     => $transaction_id,
				'transaction_status' => $charge->captured,
			);

			foreach ( $form_values as $value ) {

				if ( isset( $value['label'] ) && $value['label'] == 'total_amount' ) {

					$report_amount = $value['value'];
				}
			}

			$personal_information = array(

				'first_name'      => sanitize_text_field( $_POST['first_name'] ),
				'last_name'       => sanitize_text_field( $_POST['last_name'] ),
				'email'           => sanitize_email( $_POST['email'] ),
				'amount'          => $report_amount,
				'discount'        => isset( $_POST['discount'] ) ? sanitize_text_field( $_POST['discount'] ): 0,
				'current_form_id' => $current_form_id,
				'form_values'     => $form_values,
			);

			if ( isset( $fees_data[0] ) && count( $fees_data[0] ) > 0 ) {
				$personal_information['taxes'] = $fees_data[0];
			}

			require_once WPEP_ROOT_PATH . 'modules/reports/transaction_report.php';
			$wpep_transaction_id = wpep_single_transaction_report( $transaction_data, $current_form_id, $personal_information );

			require_once WPEP_ROOT_PATH . 'modules/email_notifications/admin_email.php';
			wpep_send_admin_email( $current_form_id, $form_values, $transaction_id );

			require_once WPEP_ROOT_PATH . 'modules/email_notifications/user_email.php';
			wpep_send_user_email( $current_form_id, $form_values, $transaction_id );

			$response = array(
				'status'                => 'success',
				'transaction_report_id' => $wpep_transaction_id,
			);

			wp_die( json_encode( $response ) );

	} else {

		$personal_information = array(

			'first_name'      => sanitize_text_field( $_POST['first_name'] ),
			'last_name'       => sanitize_text_field( $_POST['last_name'] ),
			'email'           => sanitize_email( $_POST['email'] ),
			'amount'          => sanitize_text_field( $_POST['amount'] ),
			'discount'        => isset( $_POST['discount'] ) ? sanitize_text_field( $_POST['discount'] ) : 0,
			'current_form_id' => $current_form_id,
			'form_values'     => $form_values,

		);

			// adding additional tax values to subscription reports
		if ( isset( $fees_data[0] ) && count( $fees_data[0] ) > 0 ) {
			$personal_information['taxes'] = $fees_data[0];
		}

			require_once WPEP_ROOT_PATH . 'modules/reports/transaction_report.php';
			wpep_failed_single_transaction_report( $current_form_id, $personal_information, 'card_declined' );

			$error = array(
				'status' => 'failed',
				'code'   => $e->getResponseBody()->errors[0]->code,
				'detail' => $e->getResponseBody()->errors[0]->detail,
			);

			wp_die( json_encode( $error ) );

	}

}




function wpstp_subscription_stripe_payment() {
	global $wpdb;

	$table_name           = $wpdb->prefix . 'wpstp_stripe_customers';
	$first_name           = sanitize_text_field( $_POST['first_name'] );
	$last_name            = sanitize_text_field( $_POST['last_name'] );
	$email                = sanitize_email( $_POST['email'] );
	$amount               = sanitize_text_field( $_POST['amount'] );
	$current_form_id      = sanitize_text_field( $_POST['current_form_id'] );
	$form_values          = filter_var_array( $_POST['form_values'], FILTER_SANITIZE_STRING );
	$wp_user_id           = email_exists( $email );
	$stripe_customer_id   = null;
	$stripe_customer_card = null;

	include_stripe_api( $current_form_id );

	try {

		$stripe_api_key = \Stripe\Stripe::getApiKey();
		$plan_id        = wpstp_stripe_create_plan_id();
		$plan           = wpstp_stripe_retrieve_plan( $plan_id );

		if ( ! $plan ) {
			$plan = wpstp_stripe_create_plan( $plan_id );
		}

		if ( ! $wp_user_id ) {
			$wp_user_id = wpstp_create_wordpress_user( $first_name, $last_name, $email );
		}

		$prepared_statement = $wpdb->prepare( "SELECT stripe_customer_id FROM {$table_name} WHERE  user_id = %d AND stripe_api_key=%s ", $wp_user_id, $stripe_api_key );
		$stripe_customer_id = $wpdb->get_var( $prepared_statement );

		if ( $stripe_customer_id == null ) {
			$stripe_customer    = wpstp_create_stripe_customer( $stripe_api_key, $wp_user_id );
			$stripe_customer_id = $stripe_customer->id;
			update_user_meta( $wp_user_id, 'wpstp_stripe_customer_id', $stripe_customer_id );
		}

		$subscription = wpstp_subscripe_user_to_plan( $stripe_customer_id, $plan, $form_values );

		if ( $subscription->id ) {

			wpstp_stripe_insert_first_billing_in_subscription( $subscription, $stripe_api_key );
			$response = array(
				'status'                => 'success',
				'transaction_report_id' => $subscription->id,
			);

			wp_die( json_encode( $response ) );
		}
	} catch ( \Exception $e ) {

		$error = array(
			'status' => 'failed',
			'code'   => 'Could not complete',
			'detail' => esc_html( $e->getMessage() ),
		);

		wp_die( json_encode( $error ) );

	}

}


function wpstp_subscription_status_update() {

	$subscription_post_id = sanitize_text_field( $_POST['subscription_id'] );
	$form_id              = sanitize_text_field( $_POST['form_id'] );

	$subscription_id     = get_post_meta( $subscription_post_id, 'wpstp_stripe_subscription_id', true );
	$subscription_action = sanitize_text_field( $_POST['subscription_action'] );

	if ( $subscription_action == 'Paused' ) {
		wpstp_stripe_pause_subscription( $subscription_id, $form_id );
	}
	if ( $subscription_action == 'Active' ) {
		wpstp_stripe_resume_subscription( $subscription_id, $form_id );
	}
	update_post_meta( $subscription_post_id, 'wpstp_subscription_status', $subscription_action );

	wp_die( 'success' );
}

function wpep_get_creds( $wpep_current_form_id ) {

	$form_payment_global = get_post_meta( $wpep_current_form_id, 'wpep_individual_form_global', true );

	if ( $form_payment_global == 'on' ) {

		$global_payment_mode = get_option( 'wpep_square_payment_mode_global', true );

		if ( $global_payment_mode == 'on' ) {

			/* If Global Form Live Mode */
			$accessToken = get_option( 'wpep_live_token_upgraded', true );

			$creds['access_token'] = $accessToken;
			$creds['url']          = 'https://connect.squareup.com';

		}

		if ( $global_payment_mode !== 'on' ) {

			/* If Global Form Test Mode */
			$accessToken = get_option( 'wpep_square_test_token_global', true );

			$creds['access_token'] = $accessToken;
			$creds['url']          = 'https://connect.squareupsandbox.com';

		}
	}

	if ( $form_payment_global !== 'on' ) {

		$individual_payment_mode = get_post_meta( $wpep_current_form_id, 'wpep_payment_mode', true );

		if ( $individual_payment_mode == 'on' ) {

			/* If Individual Form Live Mode */
			$accessToken = get_post_meta( $wpep_current_form_id, 'wpep_live_token_upgraded', true );

			$creds['access_token'] = $accessToken;
			$creds['url']          = 'https://connect.squareup.com';

		}

		if ( $individual_payment_mode !== 'on' ) {

			/* If Individual Form Test Mode */
			$accessToken = get_post_meta( $current_form_id, 'wpep_square_test_token', true );

			$creds['access_token'] = $accessToken;
			$creds['url']          = 'https://connect.squareupsandbox.com';

		}
	}

	return $creds;
}
