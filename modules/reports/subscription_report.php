<?php

function wpstp_add_subscription_report( $report_data ) {

	$cycle_interval      = $report_data['subscription_cycle_interval'];
	$cycle               = $report_data['subscription_cycle_interval_count'];
	$cycle_length        = get_post_meta( $report_data['current_form_id'], 'wpep_subscription_length', true );
	$status              = 'Active';
	$subscription_id     = $report_data['stripe_subscription_id'];
	$subscription_report = array(
		'post_title'  => $report_data['transaction_id'],
		'post_type'   => 'wpstp_subscriptions',
		'post_status' => 'publish',
	);

	$report_id = wp_insert_post( $subscription_report );

	$form_expiry = get_post_meta( $report_data['current_form_id'], 'wpep_subscription_length', true );

	if ( $cycle_length > 0 && $cycle_length !== 'never_expire' ) {
		$remaining_cycles = $form_expiry - 1;
	}

	if ( $form_expiry == 0 && $form_expiry !== 'never_expire' ) {
		$status            = 'Completed';
		$next_payment_date = '-';
	}

	parse_str( $report_data['form_values'], $form_values );

	update_post_meta( $report_id, 'wpstp_subscription_interval', $cycle_interval );
	update_post_meta( $report_id, 'wpstp_subscription_cycle', $cycle );
	update_post_meta( $report_id, 'wpstp_stripe_subscription_expiry', $form_expiry );

	if ( isset( $remaining_cycles ) && ! empty( $remaining_cycles ) ) {
		update_post_meta( $report_id, 'wpstp_stripe_subscription_remaining_cycles', $remaining_cycles );
	}

	update_post_meta( $report_id, 'wpstp_stripe_customer_name', $report_data['first_name'] );
	update_post_meta( $report_id, 'wpstp_stripe_email', $report_data['email'] );
	update_post_meta( $report_id, 'wpstp_stripe_subscription_next_payment', '-' );// next payment is unknown untill the first invoice is triggered
	update_post_meta( $report_id, 'wpstp_subscription_status', $status );
	update_post_meta( $report_id, 'wpstp_current_form_id', $report_data['current_form_id'] );
	update_post_meta( $report_id, 'wpstp_form_id', $report_data['current_form_id'] );
	update_post_meta( $report_id, 'wpstp_stripe_customer_id', $report_data['stripe_customer_id'] );
	update_post_meta( $report_id, 'wpstp_stripe_charge_amount', $report_data['amount'] );
	update_post_meta( $report_id, 'wpstp_form_values', $form_values );
	update_post_meta( $report_id, 'wpstp_stripe_payment_type', $report_data['form_type'] );
	update_post_meta( $report_id, 'wpstp_stripe_subscription_id', $subscription_id );
	update_post_meta( $report_id, 'wpstp_stripe_subscription_created_at', $report_data['wpstp_stripe_subscription_created_at'] );
	update_post_meta( $report_id, 'wpstp_stripe_api_key', $report_data['stripe_api_key'] );

	return $report_id;
}
