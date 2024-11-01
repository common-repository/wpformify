<?php

function wpep_send_admin_email( $current_form_id, $form_values, $transaction_id ) {

	$to                = get_post_meta( $current_form_id, 'wpep_square_admin_email_to_field', true );
	$cc                = get_post_meta( $current_form_id, 'wpep_square_admin_email_cc_field', true );
	$bcc               = get_post_meta( $current_form_id, 'wpep_square_admin_email_bcc_field', true );
	$from              = get_post_meta( $current_form_id, 'wpep_square_admin_email_from_field', true );
	$subject           = get_post_meta( $current_form_id, 'wpep_square_admin_email_subject_field', true );
	$message           = get_post_meta( $current_form_id, 'wpep_square_admin_email_content_field', true );
	$html_content_type = get_post_meta( $current_form_id, 'wpep_square_admin_email_content_type_html', true );
	$current_user      = wp_get_current_user();

	$payment_received = file_get_contents( WPEP_ROOT_PATH . 'modules/email_notifications/templates/payment_received.php' );

	$img_url = WPEP_ROOT_URL . 'assets/frontend/img/payment.png';

	$payment_received = str_replace( '[wpep_payment_received_img]', $img_url, $payment_received );
	$payment_received = str_replace( '[wpep_email_body]', $message, $payment_received );
	$transaction_tag  = '[transaction_id]';

	$form_values = (object) $form_values;

	/* Parsing Tags */
	foreach ( $form_values as $form_value ) {
		if ( isset( $form_value['label'] ) && isset( $form_value['value'] ) ) {

			$label = $form_value['label'];
			$value = $form_value['value'];

			if ( $label !== null ) {

				if ( $label == 'Email' ) {
					$label = 'user_email';
				}
				$tag = '[' . str_replace( ' ', '_', strtolower( $label ) ) . ']';

				$payment_received = str_replace( $tag, $value, $payment_received );

				$subject = str_replace( $tag, $value, $subject );
			}
		}
	}

	$payment_received = str_replace( $transaction_tag, $transaction_id, $payment_received );
	$subject          = str_replace( $transaction_tag, $transaction_id, $subject );

	if ( $html_content_type == 'on' ) {

		$headers  = 'From: ' . get_bloginfo( 'name' ) . ' <' . strip_tags( $from ) . ">\r\n";
		$headers .= 'Reply-To: ' . strip_tags( $from ) . "\r\n";
		$headers .= 'Cc: ' . $cc . "\r\n";
		$headers .= 'Bcc: ' . $bcc . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		$message  = $payment_received;
		wp_mail( $to, $subject, $message, $headers );

	}

	if ( $html_content_type !== 'on' ) {

		$headers  = 'From: ' . get_bloginfo( 'name' ) . ' <' . strip_tags( $from ) . ">\r\n";
		$headers .= 'Reply-To: ' . strip_tags( $from ) . "\r\n";
		$headers .= 'CC: ' . $cc . "\r\n";
		$headers .= 'BCC: ' . $bcc . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

		/* Parsing Tags */
		foreach ( $form_values as $form_value ) {
			if ( isset( $form_value['label'] ) && isset( $form_value['value'] ) ) {
				$label = $form_value['label'];
				$value = $form_value['value'];

				if ( $label !== null ) {

					if ( $label == 'Email' ) {
						$label = 'user_email';
					}
					$tag     = '[' . str_replace( ' ', '_', strtolower( $label ) ) . ']';
					$message = str_replace( $tag, $value, $message );

					$subject = str_replace( $tag, $value, $subject );

				}
			}
		}

		$message = str_replace( $transaction_tag, $transaction_id, $message );
		$subject = str_replace( $transaction_tag, $transaction_id, $subject );

		wp_mail( $to, $subject, $message, $headers );

	}

}
