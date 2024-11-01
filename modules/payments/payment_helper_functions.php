<?php
require_once WPEP_ROOT_PATH . 'modules/payments/square_configuration.php';

function wpep_create_wordpress_user( $first_name, $last_name, $email ) {

	$username = strtolower( $email );
	$password = wpep_generate_random_password();
	$user_id  = wp_create_user( $username, $password, $email );

	require_once WPEP_ROOT_PATH . 'modules/email_notifications/new_user_email.php';
	wpep_new_user_email_notification( $username, $password, $email );

	return $user_id;

}

function wpep_generate_random_password() {
	$alphabet    = 'abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789';
	$pass        = array();
	$alphaLength = strlen( $alphabet ) - 1;
	for ( $i = 0; $i < 8; $i++ ) {
		$n      = rand( 0, $alphaLength );
		$pass[] = $alphabet[ $n ];
	}
	return implode( $pass );
}

function wpep_retrieve_square_customer_to_verify( $apiClient, $square_customer_id ) {

	$apiInstance = new SquareConnect\Api\CustomersApi( $apiClient );
	try {
		$result = $apiInstance->retrieveCustomer( $square_customer_id );
		return $result->getCustomer()->getId();
	} catch ( Exception $e ) {
		return false;
		// wp_die(json_encode($e->getResponseBody()->errors[0]));
	}

}

function wpep_create_square_customer_card( $apiClient, $square_customer_id, $nonce, $first_name, $last_name, $verificationToken ) {

	$apiInstance      = new SquareConnect\Api\CustomersApi( $apiClient );
	$card_holder_name = $first_name . ' ' . $last_name;

	$body = new \SquareConnect\Model\CreateCustomerCardRequest();
	$body->setCardNonce( $nonce );
	$body->setCardholderName( $card_holder_name );
	$body->setVerificationToken( $verificationToken );

	try {

		$result = $apiInstance->createCustomerCard( $square_customer_id, $body );
		return $result->getCard()->getId();

	} catch ( Exception $e ) {
		wp_die( json_encode( $e->getResponseBody()->errors[0] ) );
	}

}

function wpep_create_square_customer( $apiClient ) {

	$apiInstance = new SquareConnect\Api\CustomersApi( $apiClient );
	$body        = new \SquareConnect\Model\CreateCustomerRequest();
	$unique_key  = uniqid() . 'wpexperts';

	$body->setIdempotencyKey( $unique_key );
	$body->setGivenName( sanitize_text_field( $_POST['first_name'] ) );
	$body->setFamilyName( sanitize_text_field( $_POST['last_name'] ) );
	$body->setEmailAddress( sanitize_email( $_POST['email'] ) );
	$body->setReferenceId( $unique_key );

	try {

		$result = $apiInstance->createCustomer( $body );
		return $result->getCustomer()->getId();

	} catch ( Exception $e ) {
		wp_die( json_encode( $e->getResponseBody()->errors[0] ) );
	}

}


function wpep_weekly_refresh_tokens() {

	$oauth_connect_url    = WPEP_MIDDLE_SERVER_URL;
	$refresh_access_token = get_option( 'wpep_refresh_token' );

	$args_renew = array(

		'body'    => array(

			'request_type'  => 'renew_token',
			'refresh_token' => $refresh_access_token,
			'oauth_version' => 2,
			'app_name'      => WPEP_SQUARE_APP_NAME,

		),
		'timeout' => 45,
	);

	$oauth_response      = wp_remote_post( $oauth_connect_url, $args_renew );
	$oauth_response_body = json_decode( $oauth_response['body'] );

	update_option( 'wpep_live_token_upgraded', sanitize_text_field( $oauth_response_body->access_token ) );
	update_option( 'wpep_refresh_token', $oauth_response_body->refresh_token );
	update_option( 'wpep_token_expires_at', $oauth_response_body->expires_at );

}


function wpep_square_refresh_token( $expires_at, $refresh_access_token, $type, $current_form_id ) {

	$expiry_status = wpep_check_give_square_expiry( $expires_at );

	if ( $expiry_status == 'expired' ) {

		 $oauth_connect_url = WPEP_MIDDLE_SERVER_URL;

		 $args_renew = array(

			 'body'    => array(

				 'request_type'  => 'renew_token',
				 'refresh_token' => $refresh_access_token,
				 'oauth_version' => 2,
				 'app_name'      => WPEP_SQUARE_APP_NAME,
			 ),
			 'timeout' => 45,
		 );

		 $oauth_response      = wp_remote_post( $oauth_connect_url, $args_renew );
		 $oauth_response_body = json_decode( $oauth_response['body'] );

		 if ( $type == 'global' ) {

			 update_option( 'wpep_live_token_upgraded', sanitize_text_field( $oauth_response_body->access_token ) );
			 update_option( 'wpep_refresh_token', $oauth_response_body->refresh_token );
			 update_option( 'wpep_token_expires_at', $oauth_response_body->expires_at );

			 echo esc_html( $type );

		 }

		 if ( $type == 'specific' ) {

			 update_post_meta( $current_form_id, 'wpep_live_token_upgraded', sanitize_text_field( $oauth_response_body->access_token ) );
			 update_post_meta( $current_form_id, 'wpep_refresh_token', $oauth_response_body->refresh_token );
			 update_post_meta( $current_form_id, 'wpep_token_expires_at', $oauth_response_body->expires_at );

		 }
	}

}


function wpep_check_give_square_expiry( $expires_at ) {

	$date_time    = explode( 'T', $expires_at );
	$date_time[1] = str_replace( 'Z', '', $date_time[1] );
	$expires_at   = strtotime( $date_time[0] . ' ' . $date_time[1] );
	$today        = strtotime( 'now' );

	if ( $today >= $expires_at ) {

		return 'expired';

	} else {

		return 'active';

	}

}

function wpep_retrieve_square_customer( $apiClient, $square_customer_id ) {

	try {

		$apiInstance = new SquareConnect\Api\CustomersApi( $apiClient );
		$result      = $apiInstance->retrieveCustomer( $square_customer_id );
		return $result->getCustomer()->getId();

	} catch ( Exception $e ) {

		return false;
	}

}


function wpep_retrieve_square_customer_result( $apiClient, $square_customer_id ) {

	try {

		$apiInstance = new SquareConnect\Api\CustomersApi( $apiClient );
		$result      = $apiInstance->retrieveCustomer( $square_customer_id );
		return $result;

	} catch ( Exception $e ) {

		return false;
	}

}

function wpep_retrieve_customer_cards( $apiClient, $square_customer_id ) {
	try {

		$apiInstance = new SquareConnect\Api\CustomersApi( $apiClient );
		$result      = $apiInstance->retrieveCustomer( $square_customer_id );
		return $result->getCustomer()->getCards();

	} catch ( Exception $e ) {
		return false;
	}

}

function wpep_update_cards_on_file( $apiClient, $square_customer_id, $wp_user_id ) {

	$square_cards_on_file = wpep_retrieve_customer_cards( $apiClient, $square_customer_id );

	$card_on_files_to_store_locally = array();
	foreach ( $square_cards_on_file as $card ) {

		$card_container                     = array();
		$card_container['card_customer_id'] = $square_customer_id;
		$card_container['card_id']          = $card->getId();
		$card_container['card_holder_name'] = $card->getCardholderName();
		$card_container['card_brand']       = $card->getCardBrand();
		$card_container['card_last_4']      = $card->getLast4();
		$card_container['card_exp_month']   = $card->getExpMonth();
		$card_container['card_exp_year']    = $card->getExpYear();

		array_push( $card_on_files_to_store_locally, $card_container );

	}

	update_user_meta( $wp_user_id, 'wpep_square_customer_cof', $card_on_files_to_store_locally );

}


function wpep_delete_cof() {

	$square_customer_id = sanitize_text_field( $_POST['customer_id'] );
	$card_on_file       = str_replace( 'doc:', 'ccof:', sanitize_text_field( $_POST['card_on_file'] ) );
	$current_form_id    = $_POST['current_form_id'];
	$apiClient          = wpep_setup_square_configuration_by_form_id( $current_form_id );
	$apiInstance        = new SquareConnect\Api\CustomersApi( $apiClient );

	try {

		$result = $apiInstance->deleteCustomerCard( $square_customer_id, $card_on_file );
		wpep_update_cards_on_file( $apiClient, $square_customer_id, get_current_user_id() );
		echo 'success';
		wp_die();

	} catch ( Exception $e ) {
		wpep_update_cards_on_file( $apiClient, $square_customer_id, get_current_user_id() );
		wp_die( json_encode( $e->getResponseBody()->errors[0] ) );
	}

}

add_action( 'wp_ajax_wpep_delete_cof', 'wpep_delete_cof' );
add_action( 'wp_ajax_nopriv_wpep_delete_cof', 'wpep_delete_cof' );


function wpep_calculate_fee_data() {

	if ( isset( $_POST['current_form_id'] ) && isset( $_POST['total_amount'] ) && ! empty( sanitize_text_field( $_POST['current_form_id'] ) ) && ! empty( sanitize_text_field( $_POST['total_amount'] ) ) ) {

		$sub_total_amount = floatval( sanitize_text_field( $_POST['total_amount'] ) );
		$total_amount     = $sub_total_amount;
		$discount         = floatval( sanitize_text_field( $_POST['discount'] ) );
		$fees_data        = get_post_meta( sanitize_text_field( $_POST['current_form_id'] ), 'fees_data' );
		$currency         = isset( $_POST['currency'] ) ? sanitize_text_field( $_POST['currency'] ) : '$';
		if ( ! empty( sanitize_text_field( $fees_data[0]['check'] ) ) ) {
			?>
			<ul>				
			<?php
			if ( $discount > 0 ) {
				?>
				<li class="wpep-fee-subtotal">
					<span class="fee_name"><?php echo esc_html__( 'Subtotal', 'wpformify' ); ?></span>					
					<span class="fee_value"><?php echo esc_attr( number_format( $sub_total_amount, 2 ) + number_format( $discount, 2 ) ) . ' ' . esc_attr( $currency ); ?></span>					
				</li>
				<li class="wpep-fee-discount">
					<span class="fee_name"><?php echo esc_html__( 'Discount', 'wpformify' ); ?></span>
					<span class="fee_value"><?php echo '-' . esc_attr( number_format( $discount, 2 ) ) . ' ' . esc_attr( $currency ); ?></span>
				</li>
				<?php
			} else {
				?>
				<li class="wpep-fee-subtotal">
					<span class="fee_name"><?php echo esc_html__( 'Subtotal', 'wpformify' ); ?></span>					
					<span class="fee_value"><?php echo esc_attr( number_format( $sub_total_amount, 2 ) ) . ' ' . esc_attr( $currency ); ?></span>					
				</li>
				<?php
			}

			foreach ( $fees_data[0]['check'] as $key => $fees ) :
				if ( 'yes' === $fees ) :

					if ( 'percentage' == sanitize_text_field( $fees_data[0]['type'][ $key ] ) ) {
						$tax = $sub_total_amount * ( sanitize_text_field( $fees_data[0]['value'][ $key ] ) / 100 );
					} else {
						$tax = sanitize_text_field( $fees_data[0]['value'][ $key ] );
					}

					$total_amount = $total_amount + $tax;
					?>
					<li>
						<span class="fee_name"><?php echo esc_html( $fees_data[0]['name'][ $key ] ); ?></span>
						<span class="fee_value"><?php echo esc_attr( number_format( $tax, 2 ) ) . ' ' . esc_attr( $currency ); ?></span>
					</li>
					<?php
				endif;
			endforeach;
			?>
				<li class="wpep-fee-total">
					<span class="fee_name"><?php echo esc_html__( 'Total', 'wpformify' ); ?></span>
					<span class="fee_value"><?php echo esc_attr( number_format( $total_amount, 2 ) ) . ' ' . esc_attr( $currency ); ?></span>
				</li>
			</ul>
			<?php
		}

		wp_die();
	}
}
add_action( 'wp_ajax_wpep_calculate_fee_data', 'wpep_calculate_fee_data' );
add_action( 'wp_ajax_nopriv_wpep_calculate_fee_data', 'wpep_calculate_fee_data' );








function wpstp_create_wordpress_user( $first_name, $last_name, $email ) {

	$username = strtolower( $email );
	$password = wpstp_generate_random_password();
	$user_id  = wp_create_user( $username, $password, $email );

	require_once WPEP_ROOT_PATH . 'modules/email_notifications/new_user_email.php';
	wpstp_new_user_email_notification( $username, $password, $email );
	return $user_id;
}

function wpstp_generate_random_password() {
	$alphabet    = 'abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789';
	$pass        = array();
	$alphaLength = strlen( $alphabet ) - 1;
	for ( $i = 0; $i < 8; $i++ ) {
		$n      = rand( 0, $alphaLength );
		$pass[] = $alphabet[ $n ];
	}
	return implode( $pass );
}

function wpstp_retrieve_stripe_customer_to_verify( $apiClient, $stripe_customer_id ) {
	try {
		$customer = \Stripe\Customer::retrieve( $stripe_customer_id );
	} catch ( \Exception $e ) {
		echo esc_html( $e->getMessage() );
		$customer = false;
	}
	return $customer;
}

function wpstp_create_stripe_customer( $stripe_api_key, $wp_user_id ) {

	global $wpdb;
	$table_name = $wpdb->prefix . 'wpstp_stripe_customers';

	try {
		$customer = \Stripe\Customer::create(
			array(
				'name'   => sanitize_text_field( $_POST['first_name'] ) . ' ' . sanitize_text_field( $_POST['last_name'] ),
				'email'  => sanitize_email( $_POST['email'] ),
				'source' => sanitize_text_field( $_POST['nonce']['token']['id'] ),
			)
		);
		$data     = array(
			'user_id'            => $wp_user_id,
			'stripe_customer_id' => $customer->id,
			'stripe_api_key'     => $stripe_api_key,
		);
		$format   = array( '%d', '%s', '%s' );
		$wpdb->insert( $table_name, $data, $format );
		return $customer;
	} catch ( \Exception $e ) {
		wp_send_json_error( 'Customer::create:' . esc_html( $e->getMessage() ) );
	}
}

function wpstp_stripe_create_plan_id() {

	$wpstp_form_id                = sanitize_text_field( $_POST['current_form_id'] );
	$wpstp_payment_cycle_interval = get_post_meta( $wpstp_form_id, 'wpep_subscription_cycle_interval', true ); // number
	$wpstp_billing_cycle_unit     = get_post_meta( $wpstp_form_id, 'wpep_subscription_cycle', true ); // day||month||year
	$wpstp_billing_cycle          = $wpstp_payment_cycle_interval . $wpstp_billing_cycle_unit;
	$wpstp_payment_amount         = sanitize_text_field( $_POST['amount'] );
	$stripe_keys                  = wpstp_setup_stripe_configuration_by_form_id( $wpstp_form_id );
	$currency                     = $stripe_keys['currency'];
	$plan_id                      = implode( '_', array_filter( array( 'wpstpstripe' . $wpstp_form_id, $wpstp_billing_cycle, $wpstp_payment_amount, $currency ) ) );

	return $plan_id;
}

function wpstp_stripe_create_plan( $plan_id ) {

	$wpstp_form_id = sanitize_text_field( $_POST['current_form_id'] );
	$stripe_keys   = wpstp_setup_stripe_configuration_by_form_id( $wpstp_form_id );
	$currency      = $stripe_keys['currency'];
	$plan_meta     = array(
		'interval'       => get_post_meta( $wpstp_form_id, 'wpep_subscription_cycle', true ),
		'interval_count' => get_post_meta( $wpstp_form_id, 'wpep_subscription_length', true ),
		'product'        => array( 'name' => 'wpstp_stripe' . $wpstp_form_id ),
		'currency'       => strtolower( $currency ),
		'amount'         => sanitize_text_field( $_POST['amount'] ) * 100,
	);
	try {
		$plan = \Stripe\Plan::create( $plan_meta );
		return $plan;
	} catch ( \Exception $e ) {
		wp_send_json_error( 'Create Plan:' . esc_html( $e->getMessage() ) );
	}
}

/**
 * Retrieve Stripe plan id
 *
 * @param string $plan_id
 * @return boolean
 */
function wpstp_stripe_retrieve_plan( $plan_id ) {

	try {
		// Get Stripe plan.
		$plan = \Stripe\Plan::retrieve( $plan_id );
	} catch ( \Exception $e ) {
		$plan = false;
	}

	return $plan;
}

function include_stripe_api( $form_id ) {

	require_once WPEP_ROOT_PATH . 'assets/lib/square-sdk/autoload.php';
	$keys       = wpstp_setup_stripe_configuration_by_form_id( $form_id );
	$secret_key = $keys['secret_key'];
	\Stripe\Stripe::setApiKey( $secret_key );

	return $keys;
}

function wpstp_subscripe_user_to_plan( $customer_id, $plan, $form_values ) {

	$current_form_id = sanitize_text_field( $_POST['current_form_id'] );
	$form_type       = get_post_meta( $current_form_id, 'wpstp_stripe_payment_type', true );

	try {

		$subscription = \Stripe\Subscription::create(
			array(

				'customer'          => $customer_id,
				'expand'            => array( 'customer' ),
				'collection_method' => 'charge_automatically',
				'items'             => array( array( 'plan' => $plan->id ) ),
				'metadata'          => array(
					'form_id'   => $current_form_id,
					'form_type' => $form_type,
					'site_url'  => get_site_url(),
				),
			)
		);
		return $subscription;

	} catch ( \Exception $e ) {

		wp_send_json_error( esc_html( $e->getMessage() ) );

	}

}

function wep_stripe_create_charge() {

	$wpstp_form_id = sanitize_text_field( $_POST['current_form_id'] );
	$stripe_keys   = wpstp_setup_stripe_configuration_by_form_id( $wpstp_form_id );
	$currency      = $stripe_keys['currency'];
	try {
		$charge = \Stripe\Charge::create(
			array(
				'amount'   => sanitize_text_field( $_POST['amount'] ) * 100,
				'currency' => strtolower( $currency ),
				'source'   => sanitize_text_field( $_POST['source'] ),
				'metadata' => array(
					'form_id' => $wpstp_form_id,
				),
			// 'description' => 'My First Test Charge (created for API docs)',
			)
		);
		return $charge;
	} catch ( \Exception $e ) {
		wp_send_json_error( esc_html( $e->getMessage() ) );
	}
}

function wpstp_stripe_pause_subscription( $subscription_id, $form_id ) {

	include_stripe_api( $form_id );

	try {
		$subscription = \Stripe\Subscription::update(
			$subscription_id,
			array(
				'pause_collection' => array( 'behavior' => 'void' ),
			)
		);
		return $subscription;
	} catch ( \Exception $e ) {
		wp_send_json_error( 'Subscription::update' . esc_html( $e->getMessage() ) );
	}
}

function wpstp_stripe_resume_subscription( $subscription_id, $form_id ) {

	include_stripe_api( $form_id );

	try {
		$subscription = \Stripe\Subscription::update(
			$subscription_id,
			array(
				'pause_collection' => '',
			)
		);
		return $subscription;
	} catch ( \Exception $e ) {
		wp_send_json_error( 'Subscription::update' . esc_html( $e->getMessage() ) );
	}
}

add_action( 'init', 'wpstp_stripe_webhook' );

function wpstp_stripe_webhook() {

	$request_body = file_get_contents( 'php://input' );
	$notification = json_decode( $request_body );
	if ( isset( $notification->data->object ) ) {

		switch ( $notification->type ) {
			case 'invoice.payment_succeeded':
				wpstp_stripe_invoice_succeded( $notification );
				break;
			case 'invoice.payment_failed':
				wpstp_stripe_invoice_failed( $notification );
		}
		return wp_send_json_success();
	}
}

function wpstp_stripe_get_subscription_line_item( $lines, $is_webhook ) {

	foreach ( $lines as $line ) {

		if ( $is_webhook ) {

			if ( 'subscription' === $line->type ) {
				return $line;
			}
		} else {

			if ( 'subscription_item' === $line->object ) {
				return $line;
			}
		}
	}

	return false;
}

function wpstp_stripe_insert_first_billing_in_subscription( $subscription, $stripe_api_key ) {
	
	if ( $subscription->status == 'active' ) {
		$stripe_subscription_id = $subscription->id;

		$subscription_cycle_interval       = $subscription->items->data[0]->plan->interval;
		$subscription_cycle_interval_count = $subscription->items->data[0]->plan->interval_count;

		$sub_line         = $subscription->items->data;
		$subscription_obj = wpstp_stripe_get_subscription_line_item( $sub_line, false );

		$start_date               = $subscription->start_date;
		$subscription_report_data = array(
			'first_name'                           => $subscription->customer->name,
			'email'                                => $subscription->customer->email,
			'transaction_id'                       => $stripe_subscription_id,
			'subscription_cycle_interval'          => $subscription_obj->plan->interval,
			'subscription_cycle_interval_count'    => $subscription_obj->plan->interval_count,
			'stripe_customer_id'                   => $subscription->customer->name,
			'stripe_subscription_id'               => $stripe_subscription_id,
			'amount'                               => sanitize_text_field( $_POST['amount'] ),
			'form_values'                          => $subscription->metadata,
			'wpstp_stripe_subscription_created_at' => $subscription->start_date,
			'current_form_id'                      => $subscription->metadata->form_id,
			'form_type'                            => $subscription->metadata->form_type,
			'stripe_api_key'                       => $stripe_api_key,
		);

		require_once WPEP_ROOT_PATH . 'modules/reports/subscription_report.php';
		$subscription_report_id = wpstp_add_subscription_report( $subscription_report_data );

	}
}

function wpstp_stripe_cancel_subscription( $form_id, $subscription_id ) {
	include_stripe_api( $form_id );
	$subscription = \Stripe\Subscription::retrieve(
		$subscription_id
	);
	$subscription->delete();
}

function wpstp_stripe_invoice_succeded( $notification ) {
	$lines        = $notification->data->object->lines->data;
	$subscription = wpstp_stripe_get_subscription_line_item( $lines, true );
	if ( ! $subscription ) {
		return wp_send_json_error( 'invalid_request :Subscription line item not found in request' );
	}
	if ( $subscription->metadata->site_url !== get_site_url() ) {
		return;
	}
	$subscription_id = $subscription->subscription;
	$entry_id        = get_post_id_using_subscription_id( $subscription_id );
	$expiry          = get_post_meta( $entry_id, 'wpstp_stripe_subscription_expiry', true ); // in each recurring success get meta
	$remaining_cycle = get_post_meta( $entry_id, 'wpstp_stripe_subscription_remaining_cycles', true );

	if ( $expiry != 'never_expire' ) {
		update_post_meta( $entry_id, 'wpstp_stripe_subscription_remaining_cycles', $remaining_cycle - 1 );
	}
	$transaction_id             = $notification->data->object->charge;
	$transaction_created_at     = date( $notification->data->object->created );
	$form_id                    = $subscription->metadata->form_id;
	$subscription_plan_interval = array(
		'interval'       => $subscription->plan->interval,
		'interval_count' => $subscription->plan->interval_count,
	);
	$customer_info              = array(

		'wpstp_stripe_customer_name' => $notification->data->object->customer_name,
		'wpstp_stripe_email'         => $notification->data->object->customer_email,
		'wpstp_stripe_charge_amount' => ( $notification->data->object->amount_due ) / 100,
	);
	$transaction_data           = array(

		'transaction_id'                         => $transaction_id,
		'transaction_status'                     => ( $notification->data->object->status == 'paid' ? 'Completed' : 'failed' ),
		'transaction_created_at'                 => $transaction_created_at,
		'wpstp_stripe_subscription_next_payment' => $subscription->period->end,
		'current_form_id'                        => $form_id,
		'payment_type'                           => $subscription->metadata->form_type,

	);
	if ( $expiry != 'never_expire' && $remaining_cycle == 0 ) {
		wpstp_stripe_cancel_subscription( $form_id, $subscription_id );
	}
	require_once WPEP_ROOT_PATH . 'modules/reports/transaction_report.php';

	wpstp_single_transaction_report( $transaction_data, $entry_id, $customer_info );
}

function wpstp_stripe_invoice_failed( $notification ) {
	$lines        = $notification->data->object->lines->data;
	$subscription = wpstp_stripe_get_subscription_line_item( $lines, true );
	if ( ! $subscription ) {
		return wp_send_json_error( 'invalid_request :Subscription line item not found in request' );
	}
	if ( $subscription->metadata->site_url !== get_site_url() ) {
		return;
	}
	$subscription_id = $subscription->subscription;
	$entry_id        = get_post_id_using_subscription_id( $subscription_id );
	// update subscription status
	update_post_meta( $entry_id, 'wpstp_subscription_status', $notification->data->object->status );
}


