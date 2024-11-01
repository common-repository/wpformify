<?php

function wpep_add_cron_subscription_transaction( $transaction_data, $post_id, $personal_information ) {

	update_option( 'personal_info', $personal_information );
	update_option( 'post_id', $post_id );
	update_option( 'postransaction_data', $transaction_data );

	$transaction_report = array(

		'post_title'  => $transaction_data['transaction_id'],
		'post_type'   => 'wpstp_reports',
		'post_status' => 'publish',
	);

	$transaction_report_id = wp_insert_post( $transaction_report );
	$payment_type          = get_post_meta( $personal_information['current_form_id'], 'wpep_square_payment_type', true );

	update_post_meta( $transaction_report_id, 'wpep_first_name', $personal_information['first_name'] );
	update_post_meta( $transaction_report_id, 'wpep_last_name', $personal_information['last_name'] );
	update_post_meta( $transaction_report_id, 'wpep_email', $personal_information['email'] );
	update_post_meta( $transaction_report_id, 'wpep_square_charge_amount', $personal_information['amount'] );
	update_post_meta( $transaction_report_id, 'wpep_square_discount', $personal_information['discount'] );
	if ( isset( $personal_information['taxes'] ) ) {
		update_post_meta( $transaction_report_id, 'wpep_square_taxes', $personal_information['taxes'] );
	}
	update_post_meta( $transaction_report_id, 'wpep_form_id', $personal_information['current_form_id'] );
	update_post_meta( $transaction_report_id, 'wpep_subscription_post_id', $post_id );
	update_post_meta( $transaction_report_id, 'wpep_transaction_status', $transaction_data['transaction_status'] );
	update_post_meta( $transaction_report_id, 'wpep_transaction_type', $payment_type );

	if ( isset( $personal_information['form_values'] ) ) {

		update_post_meta( $transaction_report_id, 'wpep_form_values', $personal_information['form_values'] );

	}

}

function wpep_add_cron_subscription_failed_transaction( $post_id, $personal_information, $error_message ) {

	$transaction_report = array(

		'post_title'  => '-',
		'post_type'   => 'wpstp_reports',
		'post_status' => 'publish',
	);

	$transaction_report_id = wp_insert_post( $transaction_report );

	$payment_type = get_post_meta( $personal_information['current_form_id'], 'wpep_square_payment_type', true );

	update_post_meta( $transaction_report_id, 'wpep_first_name', $personal_information['first_name'] );
	update_post_meta( $transaction_report_id, 'wpep_last_name', $personal_information['last_name'] );
	update_post_meta( $transaction_report_id, 'wpep_email', $personal_information['email'] );
	update_post_meta( $transaction_report_id, 'wpep_square_charge_amount', $personal_information['amount'] );
	update_post_meta( $transaction_report_id, 'wpep_form_id', $personal_information['current_form_id'] );
	update_post_meta( $transaction_report_id, 'wpep_subscription_post_id', $post_id );
	update_post_meta( $transaction_report_id, 'wpep_transaction_status', 'Failed' );
	update_post_meta( $transaction_report_id, 'wpep_transaction_type', $payment_type );
	update_post_meta( $transaction_report_id, 'wpep_transaction_error', $error_message );

	if ( isset( $personal_information['form_values'] ) ) {
		update_post_meta( $transaction_report_id, 'wpep_form_values', $personal_information['form_values'] );
	}

}
