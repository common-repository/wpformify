<?php

function wpep_new_user_email_notification( $username, $password, $email ) {
	$to       = $email;
	$subject  = 'Your login details for ' . site_url();
	$body     = 'username:' . $username . 'Password:' . $password;
	$headers  = array( 'Content-Type: text/html; charset=UTF-8' );
	$headers .= $headers = 'From: ' . get_bloginfo( 'name' ) . ' <' . strip_tags( $from ) . ">\r\n";

	wp_mail( $to, $subject, $body, $headers );

}
