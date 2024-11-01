<?php

add_action( 'wpep_subscription_payments', 'wpep_process_subscription_payments' );

function wpep_schedule_subscription_payments() {
	if ( ! wp_next_scheduled( 'wpep_subscription_payments' ) ) {
		wp_schedule_event( time(), 'daily', 'wpep_subscription_payments' );
	}

}


function wpep_process_subscription_payments() {

	$args = array(

		'post_type'       => 'wpstp_subscriptions',
		'post_status'     => 'publish',
		'posts_per_pages' => -1,

	);

		$subscriptions = new wp_Query( $args );

	foreach ( $subscriptions->posts as $post ) {

		$post_id = $post->ID;

		$interval         = get_post_meta( $post_id, 'wpep_subscription_interval', true );
		$cycle            = get_post_meta( $post_id, 'wpep_subscription_cycle', true );
		$length           = get_post_meta( $post_id, 'wpep_subscription_length', true );
		$remaining_cycles = get_post_meta( $post_id, 'wpep_subscription_remaining_cycles', true );
		$first_name       = get_post_meta( $post_id, 'wpep_first_name', true );
		$last_name        = get_post_meta( $post_id, 'wpep_last_name', true );
		$email            = get_post_meta( $post_id, 'wpep_email', true );
		$next_payment     = get_post_meta( $post_id, 'wpep_subscription_next_payment', true );
		$status           = get_post_meta( $post_id, 'wpep_subscription_status', true );

		$next_payment_date = $next_payment;
		$current_date      = current_time( 'timestamp' );

		$current_form_id = get_post_meta( $post_id, 'wpep_current_form_id', true );
		$customer_id     = get_post_meta( $post_id, 'wpep_square_customer_id', true );
		$card_on_file    = get_post_meta( $post_id, 'wpep_square_card_on_file', true );
		$amount          = get_post_meta( $post_id, 'wpep_square_charge_amount', true );
		$discount        = get_post_meta( $post_id, 'wpep_square_discount', true );

		if ( $current_date >= $next_payment_date && $status == 'Active' ) {

				$amount = str_replace( 'A$', '', $amount );
				$amount = str_replace( 'C$', '', $amount );
				$amount = str_replace( '$', '', $amount );
				$amount = str_replace( '¥', '', $amount );
				$amount = str_replace( '£', '', $amount );

				$transaction_data = wpep_single_square_payment( $customer_id, $card_on_file, $current_form_id, $amount );
				$today_date       = new DateTime();
				$today_date->setTimestamp( current_time( 'timestamp' ) );
				$today_date->modify( "+$interval $cycle" );
				$next_payment_date = $today_date->getTimestamp();
				update_post_meta( $post_id, 'wpep_subscription_next_payment', $next_payment_date );

			if ( $length !== 'never_expire' ) {

				$remaining_cycles = $remaining_cycles - 1;
				update_post_meta( $post_id, 'wpep_subscription_remaining_cycles', $remaining_cycles );

				if ( $remaining_cycles <= 0 ) {

					   update_post_meta( $post_id, 'wpep_subscription_status', 'Completed' );

				}
			}

			  $personal_information = array(

				  'first_name'      => $first_name,
				  'last_name'       => $last_name,
				  'email'           => $email,
				  'amount'          => $amount,
				  'discount'        => $discount,
				  'current_form_id' => $current_form_id,
			  );

			  require_once WPEP_ROOT_PATH . 'modules/reports/cron_transaction_report.php';
			  wpep_add_cron_subscription_transaction( $transaction_data, $post_id, $personal_information );

		}
	}

}
