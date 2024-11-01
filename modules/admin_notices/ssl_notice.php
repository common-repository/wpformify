<?php


function wpep_ssl_admin_notice() {
	if ( empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'off' ) {
		?>

	<div class="notice notice-success is-dismissible">
		<p><?php _e( 'Seems like you do not have a valid SSL certificate installed or you are not accessing the website using HTTPS protocol.', 'wp-easy-pay' ); ?></p>
	</div>

		<?php
	}

}

add_action( 'admin_notices', 'wpep_ssl_admin_notice' );
