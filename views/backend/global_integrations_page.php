<?php

if ( isset( $_POST['recaptcha_site_key'] ) || isset( $_POST['recaptcha_secret_key'] ) ) {

	if ( isset( $_POST['recaptcha_site_key'] ) ) {
		$recaptcha_site_key = sanitize_text_field( $_POST['recaptcha_site_key'] );
		update_option( 'wpep_recaptcha_site_key', $recaptcha_site_key );
	}


	if ( isset( $_POST['recaptcha_secret_key'] ) ) {
		$recaptcha_secret_key = sanitize_text_field( $_POST['recaptcha_secret_key'] );
		update_option( 'wpep_recaptcha_secret_key', $recaptcha_secret_key );
	}


	if ( isset( $_POST['enable_recaptcha'] ) ) {
		$enable_recaptcha = sanitize_text_field( $_POST['enable_recaptcha'] );
		update_option( 'wpep_enable_recaptcha', $enable_recaptcha );

	} else {

		update_option( 'wpep_enable_recaptcha', '' );
	}
}


$enabled = get_option( 'wpep_enable_recaptcha', false );
?>
<div class="integrations">
<form class="wpeasyPay-form" method="post" action="#">
	<div class="contentWrap wpeasyPay">
		<div class="contentHeader">
			<h3 class="blocktitle">Integrations</h3>
		</div>
		<div class="contentBlock">
			<h3 class="stitle">Enable Recaptcha v3 on my form</h3>
			<div class="form-group">
				<label for="">Site Key
					<div class="help-tip">
						<span> for more info visit documentation: <a target="_blank" href="https://developers.google.com/recaptcha/docs/v3">click here</a></span>
					</div>
				</label>

			<input class="form-control" type="text" name="recaptcha_site_key" value="<?php echo get_option( 'wpep_recaptcha_site_key' ); ?>" placeholder="Site Key"/>
			</div>
			<div class="form-group">
				<label for="">Site Secret 
					<div class="help-tip">
						<span> for more info visit documentation: <a target="_blank" href="https://developers.google.com/recaptcha/docs/v3">click here</a></span>
					</div>
				</label>
			<input class="form-control" type="text" name="recaptcha_secret_key" value="<?php echo get_option( 'wpep_recaptcha_secret_key' ); ?>" placeholder="Site Secret"/>
			</div>


			<div class="form-group">
				<input type="checkbox" name="enable_recaptcha" value="on" 
				<?php
				if ( ! empty( $enabled ) && false !== $enabled && 'on' == $enabled ) {
					echo 'checked'; }
				?>
				/>
				<label for="">Enable reCaptcha</label>
			</div>
			<div class="btnWrap">
			<button type="submit" class="btn btn-primary"> Save Keys </button>
			</div>
		</div>
	</div>
</form>
</div>
