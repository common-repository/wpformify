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

$wpep_test_app_id = get_post_meta( get_the_ID(), 'wpep_square_test_app_id', true );
$wpep_test_token  = get_post_meta( get_the_ID(), 'wpf_test_access_token', true );

$wpep_test_location_id   = get_post_meta( get_the_ID(), 'wpep_square_test_location_id', true );
$wpep_test_location_data = get_post_meta( get_the_ID(), 'wpep_test_location_data', true );

$wpep_live_token_upgraded = get_post_meta( get_the_ID(), 'wpf_access_token', true );
$wpep_refresh_token       = get_post_meta( get_the_ID(), 'wpep_refresh_token', true );
$wpep_token_expires_at    = get_post_meta( get_the_ID(), 'wpep_token_expires_at', true );
$wpep_square_btn_auth     = get_post_meta( get_the_ID(), 'wpep_square_btn_auth', true );
$wpep_live_location_data  = get_post_meta( get_the_ID(), 'wpep_live_location_data', true );

$wpep_payment_mode           = get_post_meta( get_the_ID(), 'wpep_payment_mode', true );
$wpf_digital_wallets         = get_post_meta( get_the_ID(), 'wpf_digital_wallets', true );
$wpep_individual_form_global = get_post_meta( get_the_ID(), 'wpep_individual_form_global', true );
$wpep_square_location_id     = get_post_meta( get_the_ID(), 'wpep_square_location_id', true );


// get test currency
$wpf_individual_currency_test = get_post_meta( get_the_ID(), 'wpf_individual_currency_test', true );

// get test currency
$wpf_individual_currency_live = get_post_meta( get_the_ID(), 'wpf_individual_currency_live', true );


$wpf_connect_url_live = wpf_create_connect_url( 'individual' );
$wpf_connect_url_test = wpf_create_connect_url( 'individual', true );
$form_id              = get_the_ID();
// $wpep_create_connect_sandbox_url = wpep_create_connect_sandbox_url( 'individual_form' );
// $wpep_square_connect_url     = wpep_create_connect_url( 'individual_form' );
?>
<form class="wpeasyPay-form">
	<main>
		<div class="globalSettings">
			<label for="chkGlobal">
				<input type="checkbox" name="wpep_individual_form_global" id="chkGlobal" 
				<?php
				if ( $wpep_individual_form_global == 'on' ) {
					echo 'checked';
				}
				?>
				>
				Use Global Settings
			</label>
		</div>
		<div id="globalSettings" style="display: none">
			<div class="globalSettingsa">
				<div class="globalSettingswrap">
					<h2>Global settings is active</h2>
					<?php $global_setting_url = admin_url( 'edit.php?post_type=wp_formify&page=wpstp-settings', 'https' ); ?>
					<a href="<?php echo esc_url( $global_setting_url ); ?>" class="btn btn-primary btnglobal">Go to Stripe Connect
						Settings</a>
				</div>
			</div>
		</div>
		<div id="normalSettings">
			<div class="swtichWrap">
				<input type="checkbox" id="on-off-single" name="wpep_payment_mode" class="switch-input"
					<?php
					if ( $wpep_payment_mode == 'on' ) {

						echo 'checked';
					}
					?>
				>
				<label for="on-off-single" class="switch-label">
					<span class="toggle--on toggle--option">Live Payment</span>
					<span class="toggle--off toggle--option">Test Payment</span>
				</label>
			</div>

			<div class="paymentView" id="wpep_spmst">
				<?php


				if ( $wpep_test_token == false ) {

					?>

					<div class="squareConnect">
						<div class="squareConnectwrap">
							<h2>Connect your stripe (Sandbox) account now!</h2>
							<a href="<?php echo esc_url( $wpf_connect_url_test ); ?>" class="btn btn-primary btn-square">Connect
								Stripe (Sandbox)</a>
							<p><small> The sandbox OAuth is for testing purpose by connecting and activating this you will be able to make test transactions and to see how your form will work for the customers.  </small></p>

						</div>
					</div>

					<?php

				} else {
					?>

					<div class="squareConnected">
						<h3 class="titleSquare">Stripe is Connected <i class="fa fa-check-square" aria-hidden="true"></i></h3>
						<div class="wpeasyPay__body">

								<div class="form-group">
									<label>Country Currency</label>
									<select name="wpf_individual_currency_test" class="form-control">
										<option
											value="USD" 
											<?php
											if ( ! empty( $wpf_individual_currency_test ) && 'USD' == $wpf_individual_currency_test ) :
												echo "selected='selected'";
endif;
											?>
											>
											USD
										</option>
										<option
											value="CAD" 
											<?php
											if ( ! empty( $wpf_individual_currency_test ) && 'CAD' == $wpf_individual_currency_test ) :
												echo "selected='selected'";
endif;
											?>
											 >
											CAD
										</option>
										<option
											value="AUD" 
											<?php
											if ( ! empty( $wpf_individual_currency_test ) && 'AUD' == $wpf_individual_currency_test ) :
												echo "selected='selected'";
endif;
											?>
											 >
											AUD
										</option>
										<option
											value="JPY" 
											<?php
											if ( ! empty( $wpf_individual_currency_test ) && 'JPY' == $wpf_individual_currency_test ) :
												echo "selected='selected'";
endif;
											?>
											 >
											JPY
										</option>
										<option
											value="GBP" 
											<?php
											if ( ! empty( $wpf_individual_currency_test ) && 'GBP' == $wpf_individual_currency_test ) :
												echo "selected='selected'";
endif;
											?>
											 >
											GBP
										</option>
									</select>
								</div>
							

						
						</div>

						<!-- <div class="paymentint">
							<label class="title">Other Payment Options</label>
							<div class="wizard-form-checkbox">
								<input name="wpf_digital_wallets" id="googlePay"
									   type="checkbox" 
									   <?php
										if ( $wpf_digital_wallets == 'on' ) {
											echo 'checked';
										}
										?>
								<label for="googlePay">Digital Wallets</label>
							</div>
						</div> -->


						<div class="btnFooter d-btn">
										
							<a href="<?php echo esc_url( admin_url( 'admin.php?wpep_disconnect_square=1&wpep_disconnect_individual=true&mode=test&form_id=' . $form_id ) ); ?>"

							   class="btn btnDiconnect">Disconnect
								Stripe</a>

						</div>

					</div>

					<?php
				}
				?>


</div>

<!-- test block end -->

<div class="livePayment paymentView" id="wpep_spmsl">
	<?php


	if ( $wpep_live_token_upgraded == false ) {

		?>

		<div class="squareConnect">
			<div class="squareConnectwrap">
				<h2>Connect your stripe account now!</h2>
				<a href="<?php echo esc_url( $wpf_connect_url_live ); ?>" class="btn btn-primary btn-square">Connect
					Stripe</a>

			</div>
		</div>

		<?php

	} else {
		?>

		<div class="squareConnected">
			<h3 class="titleSquare">Stripe is Connected <i class="fa fa-check-square" aria-hidden="true"></i></h3>
			<div class="wpeasyPay__body">


					<div class="form-group">
						<label>Country Currency</label>
						<select name="wpf_individual_currency_live" class="form-control">
							<option
								value="USD" 
								<?php
								if ( ! empty( $wpf_individual_currency_live ) && 'USD' == $wpf_individual_currency_live ) :
									echo "selected='selected'";
endif;
								?>
								>
								USD
							</option>
							<option
								value="CAD" 
								<?php
								if ( ! empty( $wpf_individual_currency_live ) && 'CAD' == $wpf_individual_currency_live ) :
									echo "selected='selected'";
endif;
								?>
								 >
								CAD
							</option>
							<option
								value="AUD" 
								<?php
								if ( ! empty( $wpf_individual_currency_live ) && 'AUD' == $wpf_individual_currency_live ) :
									echo "selected='selected'";
endif;
								?>
								 >
								AUD
							</option>
							<option
								value="JPY" 
								<?php
								if ( ! empty( $wpf_individual_currency_live ) && 'JPY' == $wpf_individual_currency_live ) :
									echo "selected='selected'";
endif;
								?>
								 >
								JPY
							</option>
							<option
								value="GBP" 
								<?php
								if ( ! empty( $wpf_individual_currency_live ) && 'GBP' == $wpf_individual_currency_live ) :
									echo "selected='selected'";
endif;
								?>
								 >
								GBP
							</option>
						</select>
					</div>
			</div>

			<!-- <div class="paymentint">
				<label class="title">Other Payment Options</label>
				<div class="wizard-form-checkbox">
					<input name="wpep_square_google_pay" id="googlePay"
						   type="checkbox" 
						   <?php
							// if ( $wpep_square_google_pay == 'on' ) {
							// echo 'checked';
							// }
							?>
					<label for="googlePay">Google Pay</label>
				</div>
				<div class="wizard-form-checkbox ">
					<input name="radio-name" id="masterPay" type="checkbox" disabled>
					<label for="masterPay">Master Pay</label>
				</div>
				<div class="wizard-form-checkbox">
					<input name="radio-name" id="applePay" type="checkbox" disabled>
					<label for="applePay">Apple Pay</label>
				</div>
			</div> -->


			<div class="btnFooter d-btn">

				<a href="<?php echo esc_url( admin_url( 'admin.php?wpep_disconnect_square=1&wpep_disconnect_individual=true&mode=live&form_id=' . $form_id ) ); ?>"
				   class="btn btnDiconnect">Disconnect
					Stripe</a>

			</div>

		</div>

		<?php
	}
	?>
</div>
<!-- live block end -->
</div>

</form>
</main>
