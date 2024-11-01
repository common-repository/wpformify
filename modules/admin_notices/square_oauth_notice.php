<?php


function wpep_square_oauth_admin_notice() {
	$wpf_live_token_upgraded = get_option( 'wpf_access_token', false );
	if ( ! $wpf_live_token_upgraded ) {
		?>

	<div class="notice notice-success is-dismissible">
		<p><?php _e( 'Seems like you have not connected your Stripe account yet. <a href="edit.php?page=wpstp-settings&amp;post_type=wp_formify&amp;wpep_admin_url=edit.php&amp;wpep_post_type=wp_formify&amp;wpep_prepare_connection_call=1&amp;wpep_page_post=global" class="btn btn-primary btn-square"> Connect Stripe </a>', 'wp-easy-pay' ); ?></p>
	</div>

		<?php
	}

}

add_action( 'admin_notices', 'wpep_square_oauth_admin_notice' );
