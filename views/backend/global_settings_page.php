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

add_action( 'admin_enqueue_scripts', 'call_backend_scripts' );

function call_backend_scripts() {

	wp_enqueue_script( 'wpep_backend_js', WPEP_ROOT_URL . 'assets/backend/js/wpep_backend_scripts.js' );
}


if ( isset( $_POST ) && ! empty( $_POST ) ) {


	$payment_mode            = 0;
	$wpf_digital_wallets     = 0;
	$wpep_square_apple_pay   = 0;
	$wpep_square_master_pass = 0;

	$wpf_digital_wallets_test = 0;
	$location_id_test         = null;

	if ( isset( $_POST['wpep_square_test_location_id_global'] ) ) {
		$location_id_test = sanitize_text_field( $_POST['wpep_square_test_location_id_global'] );
	}

	if ( isset( $_POST['wpf_digital_wallets'] ) ) {
		$wpf_digital_wallets = sanitize_text_field( $_POST['wpf_digital_wallets'] );
	}

	if ( isset( $_POST['wpf_digital_wallets_test'] ) ) {
		$wpf_digital_wallets_test = sanitize_text_field( $_POST['wpf_digital_wallets_test'] );
	}

	if ( isset( $_POST['wpep_square_apple_pay'] ) ) {
		$wpep_square_apple_pay = sanitize_text_field( $_POST['wpep_square_apple_pay'] );
	}

	if ( isset( $_POST['wpep_square_master_pass'] ) ) {
		$wpep_square_master_pass = sanitize_text_field( $_POST['wpep_square_master_pass'] );
	}

	if ( isset( $_POST['wpep_square_payment_mode_global'] ) ) {
		$payment_mode = sanitize_text_field( $_POST['wpep_square_payment_mode_global'] );
	}


	if ( isset( $_POST['wpf_test_currency'] ) ) {
		$wpf_test_currency = sanitize_text_field( $_POST['wpf_test_currency'] );
		update_option( 'wpf_test_currency', $wpf_test_currency );
	}

	if ( isset( $_POST['wpf_live_currency'] ) ) {
		$wpf_live_currency = sanitize_text_field( $_POST['wpf_live_currency'] );
		update_option( 'wpf_live_currency', $wpf_live_currency );
	}



	if ( isset( $_POST['wpep_square_location_id'] ) ) {
		$location_id = sanitize_text_field( $_POST['wpep_square_location_id'] );
		update_option( 'wpep_square_location_id', $location_id );
	}

	if ( isset( $_POST['wpep_square_currency_test'] ) ) {
		$currency = sanitize_text_field( $_POST['wpep_square_currency_test'] );
		update_option( 'wpep_square_currency_test', $currency );
	}

	update_option( 'wpep_square_test_location_id_global', $location_id_test );
	update_option( 'wpf_digital_wallets_test', $wpf_digital_wallets_test );
	update_option( 'wpep_square_payment_mode_global', $payment_mode );
	update_option( 'wpf_digital_wallets', $wpf_digital_wallets );
	update_option( 'wpep_square_apple_pay', $wpep_square_apple_pay );
	update_option( 'wpep_square_master_pass', $wpep_square_master_pass );

} else {
	$current_user            = wp_get_current_user();
	$wpep_email_notification = $current_user->user_email;
}

$wpep_square_payment_mode_global = get_option( 'wpep_square_payment_mode_global', true );
$wpf_digital_wallets             = get_option( 'wpf_digital_wallets', true );
$wpf_digital_wallets_test        = get_option( 'wpf_digital_wallets_test', true );
$wpep_email_notification         = get_option( 'wpep_email_notification', false );

if ( empty( $wpep_email_notification ) || false == $wpep_email_notification ) {

	$current_user            = wp_get_current_user();
	$wpep_email_notification = $current_user->user_email;

}

$wpf_connect_url_live = wpf_create_connect_url( 'global' );
$wpf_connect_url_test = wpf_create_connect_url( 'global', true );



?>

<form class="wpeasyPay-form" method="post" action="#">
  <div class="contentWrap wpeasyPay">
	<div class="contentHeader">
	  <h3 class="blocktitle">Stripe Connect</h3>
	  <div class="swtichWrap">
		<input type="checkbox" id="on-off" name="wpep_square_payment_mode_global" class="switch-input" 
		<?php
		if ( $wpep_square_payment_mode_global == 'on' || ( isset( $_COOKIE['wpep-payment-mode'] ) && 'live' == $_COOKIE['wpep-payment-mode'] ) ) {
			echo 'checked';
		}
		?>
	   />
		<label for="on-off" class="switch-label">
		  <span class="toggle--on toggle--option wpep_global_mode_switch" data-mode="live">Live Payment</span>
		  <span class="toggle--off toggle--option wpep_global_mode_switch" data-mode="test">Test Payment</span>
		</label>
	  </div>
	</div>
	<div class="contentBlock">

	  <div class="testPayment paymentView testActive" id="wpep_spmgt">
		<?php

			 $wpf_test_access_token = get_option( 'wpf_test_access_token' );

		if ( $wpf_test_access_token == false ) {
			?>
			<div class="squareConnect">
			  <div class="squareConnectwrap">
				<h2>Connect your stripe (sandbox) account now!</h2>
				<a href="<?php echo esc_url( $wpf_connect_url_test ); ?>" class="btn btn-primary btn-square">Connect Stripe (sandbox)</a>

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
				  <select name="wpf_test_currency" class="form-control">
					  <option value="USD" 
					  <?php
						if ( ! empty( get_option( 'wpf_test_currency' ) ) && 'USD' == get_option( 'wpf_test_currency' ) ) :
							echo "selected='selected'";
endif;
						?>
						>USD</option>
					  <option value="CAD" 
					  <?php
						if ( ! empty( get_option( 'wpf_test_currency' ) ) && 'CAD' == get_option( 'wpf_test_currency' ) ) :
							echo "selected='selected'";
endif;
						?>
						 >CAD</option>
					  <option value="AUD" 
					  <?php
						if ( ! empty( get_option( 'wpf_test_currency' ) ) && 'AUD' == get_option( 'wpf_test_currency' ) ) :
							echo "selected='selected'";
endif;
						?>
						 >AUD</option>
					  <option value="JPY" 
					  <?php
						if ( ! empty( get_option( 'wpf_test_currency' ) ) && 'JPY' == get_option( 'wpf_test_currency' ) ) :
							echo "selected='selected'";
endif;
						?>
						 >JPY</option>
					  <option value="GBP" 
					  <?php
						if ( ! empty( get_option( 'wpf_test_currency' ) ) && 'GBP' == get_option( 'wpf_test_currency' ) ) :
							echo "selected='selected'";
endif;
						?>
						 >GBP</option>
				  </select>
				</div>

			
			  </div>
			  
			  <!-- <div class="paymentint">
				<label class="title">Other Payment Options</label>
				<div class="wizard-form-checkbox">
				<input id="googlePayTest" name="wpf_digital_wallets_test" value="on" type="checkbox"
					<?php
					// if ( 'on' === $wpf_digital_wallets_test ) {
					// echo 'checked';
					// }
					?>
					>
				  <label for="googlePayTest">Digital Wallets</label>

				</div>

			  </div> -->

			  <?php

				$disconnectUrl = get_option( 'wpep_square_disconnect_url', false );

				$queryArg = array(

					'wpep_disconnect_square' => 1,
					'wpep_disconnect_global' => 'true',
					'mode'                   => 'test',

				);

				$queryArg['wpep_disconnect_global'] = 'true';

				$disconnectUrl = admin_url( 'admin.php' );
				$disconnectUrl = add_query_arg( $queryArg, $disconnectUrl );

				?>


			  <div class="btnFooter d-btn">
				<button type="submit" class="btn btn-primary"> Save Settings </button>
				<a href="<?php echo esc_url( $disconnectUrl ); ?>" class="btn btnDiconnect">Disconnect
				  Stripe</a>
			  </div>
			</div>
			<?php
		}
		?>

	  </div>

	  <div class="livePayment paymentView" id="wpep_spmgl">
		<?php


		$wpf_access_token = get_option( 'wpf_access_token' );

		if ( $wpf_access_token == false ) {

			?>

		<div class="squareConnect">
		  <div class="squareConnectwrap">
			<h2>Connect your stripe account now!</h2>
			<a href="<?php echo esc_url( $wpf_connect_url_live ); ?>" class="btn btn-primary btn-square">Connect Stripe</a>

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
			  <select name="wpf_live_currency" class="form-control">
				  <option value="USD" 
				  <?php
					if ( ! empty( get_option( 'wpf_live_currency' ) ) && 'USD' == get_option( 'wpf_live_currency' ) ) :
						echo "selected='selected'";
endif;
					?>
					>USD</option>
				  <option value="CAD" 
				  <?php
					if ( ! empty( get_option( 'wpf_live_currency' ) ) && 'CAD' == get_option( 'wpf_live_currency' ) ) :
						echo "selected='selected'";
endif;
					?>
					 >CAD</option>
				  <option value="AUD" 
				  <?php
					if ( ! empty( get_option( 'wpf_live_currency' ) ) && 'AUD' == get_option( 'wpf_live_currency' ) ) :
						echo "selected='selected'";
endif;
					?>
					 >AUD</option>
				  <option value="JPY" 
				  <?php
					if ( ! empty( get_option( 'wpf_live_currency' ) ) && 'JPY' == get_option( 'wpf_live_currency' ) ) :
						echo "selected='selected'";
endif;
					?>
					 >JPY</option>
				  <option value="GBP" 
				  <?php
					if ( ! empty( get_option( 'wpf_live_currency' ) ) && 'GBP' == get_option( 'wpf_live_currency' ) ) :
						echo "selected='selected'";
endif;
					?>
					 >GBP</option>
			  </select>
			</div>

			  
		  </div>

<!-- 
		<div class="paymentint">
		  <label class="title">Other Payment Options</label>
		  <div class="wizard-form-checkbox">
		  <input id="googlePayLive" name="wpf_digital_wallets" value="on" type="checkbox" -->
				<?php
				// if ( 'on' === $wpf_digital_wallets ) {
				// 	echo 'checked';
				// }
				?>
			  <!-- > -->
			<!-- <label for="googlePayLive">Google Pay</label>

		  </div>



		  <div class="wizard-form-checkbox ">
			<input id="masterPayLive" name="wpep_square_master_pass" type="checkbox" disabled>
			<label for="masterPayLive">Master Pay</label>
		  </div>
		  <div class="wizard-form-checkbox">
			<input id="applePayLive" name="wpep_square_apple_pay" type="checkbox" disabled>
			<label for="applePayLive">Apple Pay</label>
		  </div>

		</div> -->


			<?php

			$disconnectUrl = get_option( 'wpep_square_disconnect_url', false );

			$queryArg = array(

				'wpep_disconnect_square' => 1,
				'wpep_disconnect_global' => 'true',
				'mode'                   => 'live',

			);

			$queryArg['wpep_disconnect_global'] = 'true';

			$disconnectUrl = admin_url( 'admin.php' );
			$disconnectUrl = add_query_arg( $queryArg, $disconnectUrl );

			?>


		
		<div class="btnFooter d-btn">
		  <button type="submit" class="btn btn-primary"> Save Settings </button>
		  <a href="<?php echo esc_url( $disconnectUrl ); ?>" class="btn btnDiconnect">Disconnect
			Stripe</a>
		</div>
			<?php
		}
		?>
	  </div>



	</div>
</form>
</div>
