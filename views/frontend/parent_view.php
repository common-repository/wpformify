<?php

$_SESSION['URL'] = false;

global $post;
$wpep_open_in_popup           = get_post_meta( $wpep_current_form_id, 'wpep_open_in_popup', true );
$wpep_show_wizard             = get_post_meta( $wpep_current_form_id, 'wpep_show_wizard', true );
$wpep_show_shadow             = get_post_meta( $wpep_current_form_id, 'wpep_show_shadow', true );
$wpep_btn_theme               = get_post_meta( $wpep_current_form_id, 'wpep_btn_theme', true );
$form_content                 = get_post( $wpep_current_form_id );
$wpep_button_title            = empty( get_post_meta( $wpep_current_form_id, 'wpep_button_title', true ) ) ? 'Pay' : get_post_meta( $wpep_current_form_id, 'wpep_button_title', true );
$square_application_id_in_use = null;
$square_location_id_in_use    = null;

$wpep_payment_success_url   = ! empty( get_post_meta( $wpep_current_form_id, 'wpep_square_payment_success_url', true ) ) ? get_post_meta( $wpep_current_form_id, 'wpep_square_payment_success_url', true ) : '';
$wpep_payment_success_label = ! empty( get_post_meta( $wpep_current_form_id, 'wpep_square_payment_success_label', true ) ) ? get_post_meta( $wpep_current_form_id, 'wpep_square_payment_success_label', true ) : '';
$wpep_payment_success_msg   = ! empty( get_post_meta( $wpep_current_form_id, 'wpep_payment_success_msg', true ) ) ? get_post_meta( $wpep_current_form_id, 'wpep_payment_success_msg', true ) : '';

$currencySymbolType = ! empty( get_post_meta( $wpep_current_form_id, 'currencySymbolType', true ) ) ? get_post_meta( $wpep_current_form_id, 'currencySymbolType', true ) : 'code';

$wantRedirection  = ! empty( get_post_meta( $wpep_current_form_id, 'wantRedirection', true ) ) ? get_post_meta( $wpep_current_form_id, 'wantRedirection', true ) : 'No';
$redirectionDelay = ! empty( get_post_meta( $wpep_current_form_id, 'redirectionDelay', true ) ) ? get_post_meta( $wpep_current_form_id, 'redirectionDelay', true ) : '';

$enableTermsCondition = get_post_meta( $wpep_current_form_id, 'enableTermsCondition', true );
$termsLabel           = ! empty( get_post_meta( $wpep_current_form_id, 'termsLabel', true ) ) ? get_post_meta( $wpep_current_form_id, 'termsLabel', true ) : 'no';
$termsLink            = ! empty( get_post_meta( $wpep_current_form_id, 'termsLink', true ) ) ? get_post_meta( $wpep_current_form_id, 'termsLink', true ) : 'no';
$postalPh             = ! empty( get_post_meta( $wpep_current_form_id, 'postalPh', true ) ) ? get_post_meta( $wpep_current_form_id, 'postalPh', true ) : 'Postal';

if ( is_user_logged_in() ) {
	$current_user = wp_get_current_user();
	$user_email   = $current_user->user_email;
} else {
	$user_email = '';
}

$fees_data = get_post_meta( $wpep_current_form_id, 'fees_data' );

wp_enqueue_style( 'wpep_wizard_form_style', WPEP_ROOT_URL . 'assets/frontend/css/multi_wizard.css' );
wp_enqueue_style( 'wpep_single_form_style', WPEP_ROOT_URL . 'assets/frontend/css/single_page.css' );
wp_enqueue_script( 'jquery' );

if ( $wpep_show_wizard == 'on' ) {


	wp_enqueue_script( 'wpep_multi_wizard_script', WPEP_ROOT_URL . 'assets/frontend/js/script_wizard.js' );
	wp_enqueue_script( 'wpep_wizard_script', WPEP_ROOT_URL . 'assets/frontend/js/script_single.js' );

}

if ( $wpep_show_wizard !== 'on' ) {


	wp_enqueue_script( 'wpep_wizard_script', WPEP_ROOT_URL . 'assets/frontend/js/script_single.js' );

}


$form_payment_global = get_post_meta( $wpep_current_form_id, 'wpep_individual_form_global', true );


if ( $form_payment_global == 'on' ) {

	$global_payment_mode = get_option( 'wpep_square_payment_mode_global', true );

	if ( $global_payment_mode == 'on' ) {

		/*
		 If Global Form Live Mode */
		// wp_enqueue_script( 'square_payment_form_external', 'https://js.squareup.com/v2/paymentform', array(), false, true );
		$square_application_id_in_use = WPEP_SQUARE_APP_ID;
		$square_location_id_in_use    = get_option( 'wpep_square_location_id', true );
		$square_currency              = get_option( 'wpf_live_currency' );

		$access_token        = get_option( 'wpf_access_token', false );
		$wpf_publishable_key = get_option( 'wpf_publishable_key', false );


	}

	if ( $global_payment_mode !== 'on' ) {

		/*
		 If Global Form Test Mode */
		// wp_enqueue_script( 'square_payment_form_external', 'https://js.squareupsandbox.com/v2/paymentform.js', array(), false, true );
		$square_application_id_in_use = get_option( 'wpep_square_test_app_id_global', true );
		$square_location_id_in_use    = get_option( 'wpep_square_test_location_id_global', true );
		$square_currency              = get_option( 'wpf_test_currency' );

		$access_token        = get_option( 'wpf_test_access_token', false );
		$wpf_publishable_key = get_option( 'wpf_test_publishable_key', false );


	}
}

if ( $form_payment_global !== 'on' ) {

	$individual_payment_mode = get_post_meta( $wpep_current_form_id, 'wpep_payment_mode', true );

	if ( $individual_payment_mode == 'on' ) {
		/* If Individual Form Live Mode */
		wp_enqueue_script( 'square_payment_form_external', 'https://js.squareup.com/v2/paymentform', array(), false, true );
		$square_application_id_in_use = WPEP_SQUARE_APP_ID;
		$square_location_id_in_use    = get_post_meta( $wpep_current_form_id, 'wpep_square_location_id', true );
		$square_currency              = get_post_meta( $wpep_current_form_id, 'wpf_individual_currency_live', true );

		$access_token        = get_option( 'wpf_access_token', false );
		$wpf_publishable_key = get_option( 'wpf_publishable_key', false );
	}

	if ( $individual_payment_mode !== 'on' ) {
		/* If Individual Form Test Mode */
		wp_enqueue_script( 'square_payment_form_external', 'https://js.squareupsandbox.com/v2/paymentform', array(), false, true );
		$square_application_id_in_use = get_post_meta( $wpep_current_form_id, 'wpep_square_test_app_id', true );
		$square_location_id_in_use    = get_post_meta( $wpep_current_form_id, 'wpep_square_test_location_id', true );
		$square_currency              = get_post_meta( $wpep_current_form_id, 'wpf_individual_currency_test', true );

		$access_token        = get_option( 'wpf_test_access_token', false );
		$wpf_publishable_key = get_option( 'wpf_test_publishable_key', false );
	}
}


$recaptcha_site_key = get_option( 'wpep_recaptcha_site_key' );
wp_enqueue_script( 'wpep_recaptcha', 'https://www.google.com/recaptcha/api.js?render=' . $recaptcha_site_key, array(), false, true );
wp_enqueue_script(
	'stripe-sdk',
	'https://js.stripe.com/v3/',
	array(),
	'1.0',
	true
);
wp_enqueue_script( 'wpep_wizard_cart', WPEP_ROOT_URL . 'assets/frontend/js/cart.js' );
wp_enqueue_script( 'square_payment_form_internal', WPEP_ROOT_URL . 'assets/frontend/js/wpep_paymentform.js', array(), '1.0.1', 'true' );
wp_localize_script(
	'square_payment_form_internal',
	'wpep_local_vars',
	array(
		'ajax_url'                  => admin_url( 'admin-ajax.php' ),
		'square_application_id'     => $square_application_id_in_use,
		'square_location_id_in_use' => $square_location_id_in_use,
		'wpep_square_currency_new'  => $square_currency,
		'currencySymbolType'        => $currencySymbolType,
		'current_form_id'           => $wpep_current_form_id,
		'wpep_form_theme_color'     => get_post_meta( $wpep_current_form_id, 'wpep_form_theme_color', true ),
		'front_img_url'             => WPEP_ROOT_URL . 'assets/frontend/img',
		'wpep_payment_success_url'  => $wpep_payment_success_url,
		'logged_in_user_email'      => $user_email,
		'recaptcha_site_key'        => $recaptcha_site_key,
		'first_name'                => get_user_meta( get_current_user_id(), 'first_name', true ),
		'last_name'                 => get_user_meta( get_current_user_id(), 'last_name', true ),
		'extra_fees'                => ( ! empty( $fees_data[0]['check'] ) && in_array( 'yes', $fees_data[0]['check'] ) ),
		'wpf_access_token'          => $access_token,
		'wpf_test_publishable_key'  => $wpf_publishable_key,
	)
);

require_once WPEP_ROOT_PATH . 'modules/render_forms/form_helper_functions.php';

$wpep_amount_layout_type         = get_post_meta( $wpep_current_form_id, 'wpep_square_amount_type', true );
$wpep_square_form_builder_fields = get_post_meta( $wpep_current_form_id, 'wpep_square_form_builder_fields', true );
$json_form                       = $wpep_square_form_builder_fields;
$open_form_json                  = json_decode( $json_form );

if ( $wpep_show_shadow == 'on' ) {
	$shadow_class = 'wpep_form_shadow';

} else {

	$shadow_class = '';

}

if ( $wpep_btn_theme == 'on' ) {

	$btn_theme_class = 'class= wpep-btn wpep-btn-primary wpep-popup-btn ' . 'style=background-color:#' . get_post_meta( $wpep_current_form_id, 'wpep_form_theme_color', true ) . '';

} else {

	$btn_theme_class = "class=wpep-popup-btn";
}
?>

<?php

if ( $wpep_open_in_popup == 'on' ) {

	wp_enqueue_style( 'wpep_popup_form_style', WPEP_ROOT_URL . 'assets/frontend/css/wpep_popup.css' );
	wp_enqueue_script( 'wpep_frontend_scripts', WPEP_ROOT_URL . 'assets/frontend/js/wpep_scripts.js' );

	$_SESSION['form_ids'][] = $wpep_current_form_id;

	$wpep_button_title = get_option( 'wpep_button_title', false );

	if ( $wpep_button_title == false ) {

		$wpep_button_title = 'Open Form';
	}
	$get_btn_val = get_post_meta( $wpep_current_form_id, 'wpep_button_title', true );
	$button_txt  = ! empty( $get_btn_val ) ? $get_btn_val : esc_html__( 'Open Form' );
	?>


	<button type="button" <?php echo esc_html( $btn_theme_class ); ?>
			data-btn-id="<?php echo esc_html( $wpep_current_form_id ); ?>"><?php echo esc_html( $button_txt ); ?></button>
	<?php
	require_once WPEP_ROOT_PATH . 'views/frontend/popup-form.php';

} else {
	?>
	<style>

		.wizard-form-checkbox input[type="checkbox"],
		.wizard-form-radio input[type="radio"] {
			-webkit-appearance: none;
			-moz-appearance: none;
			-ms-appearance: none;
			-o-appearance: none;
			appearance: none;
		}

		.wizard-<?php echo esc_html( $wpep_current_form_id ); ?> {
			--parent-loader-color: #<?php echo esc_html( get_post_meta( $wpep_current_form_id, 'wpep_form_theme_color', true ) ); ?>;
		}

		.parent-loader {
			position: absolute;
			left: 20px;
			right: 20px;
			top: 20px;
			bottom: 20px;
			width: auto;
			height: auto;
			font-size: 16px;
			color: #000;
			/* background: rgb(255, 255, 255, 0.70); */
			background: rgba(253, 253, 253, 0.98);
			border-radius: 4px;
			border: 1px solid #fbfbfb;
			z-index: 9999;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.parent-loader .initial-load-animation .payment-image .icon-pay {
			height: 48px;
			width: 48px;
		}

		.parent-loader .initial-load-animation .payment-image .icon-pay {
			fill: var(--parent-loader-color);
		}

		.parent-loader .initial-load-animation .loading-bar .blue-bar {
			background-color: var(--parent-loader-color);
		}
	</style>
	<div class="wizard-<?php echo esc_html( $wpep_current_form_id ); ?> <?php
	if ( $wpep_show_wizard !== 'on' ) {
		echo 'singlepage';
	} else {
		echo 'multipage';
	}
	?>
	" style="position:relative">
		<div class="wpepLoader parent-loader">
			<div class="initial-load-animation">
				<div class="loading-bar">
					<div class="blue-bar"></div>
				</div>
			</div>
		</div>
		<section class="wizard-section <?php echo esc_html( $shadow_class ); ?>" >
			<div class="form-wizard">
				<form action="" method="post" role="form" class="wpep_payment_form"
					  data-id="<?php echo esc_html( $wpep_current_form_id ); ?>"
					  id="theForm-<?php echo esc_html( $wpep_current_form_id ); ?>" autocomplete="off"
					  data-currency="<?php echo esc_html( $square_currency ); ?>"
					  data-currency-type="<?php echo esc_html( $currencySymbolType ); ?>" data-redirection="<?php echo esc_html( $wantRedirection ); ?>"
					  data-delay="<?php echo esc_html( $redirectionDelay ); ?>"
					  data-postal="<?php echo esc_html( $postalPh ); ?>" data-user-email="<?php echo esc_html( $user_email ); ?>"
					  data-redirectionurl="<?php echo esc_url( $wpep_payment_success_url ); ?>">
					<style>
						:root {
							--wpep-theme-color: '';
							--wpep-currency: '';
						}

						<?php
							$form_payment_global = get_post_meta( $wpep_current_form_id, 'wpep_individual_form_global', true );

						if ( $form_payment_global == 'on' ) {

							$global_payment_mode = get_option( 'wpep_square_payment_mode_global', true );

							if ( $global_payment_mode == 'on' ) {

								/* If Global Form Live Mode */
								$wpep_square_currency = get_option( 'wpf_live_currency' );

							}

							if ( $global_payment_mode !== 'on' ) {

								/* If Global Form Test Mode */
								$wpep_square_currency = get_option( 'wpf_test_currency' );

							}
						}

						if ( $form_payment_global !== 'on' ) {


							$individual_payment_mode = get_post_meta( $wpep_current_form_id, 'wpep_payment_mode', true );

							if ( $individual_payment_mode == 'on' ) {

								/* If Individual Form Live Mode */
								$wpep_square_currency = get_post_meta( $wpep_current_form_id, 'wpf_individual_currency_live', true );

							}

							if ( $individual_payment_mode !== 'on' ) {
								/* If Individual Form Test Mode */
								$wpep_square_currency = get_post_meta( $wpep_current_form_id, 'wpf_individual_currency_test', true );

							}
						}
						?>

						#theForm-<?php echo esc_html( $wpep_current_form_id ); ?> {
							--wpep-theme-color: #<?php echo get_post_meta( $wpep_current_form_id, 'wpep_form_theme_color', true ); ?>;

						<?php
						if ( $currencySymbolType == 'code' ) {
							?>
							 --wpep-currency: '<?php echo esc_html( $wpep_square_currency ); ?>';

							<?php
						} else {
							?>
							 <?php
								if ( $wpep_square_currency == 'USD' ) :
									?>
	 --wpep-currency: '$';
														<?php endif; ?> <?php
														if ( $wpep_square_currency == 'CAD' ) :
															?>
							 --wpep-currency: 'C$';
														<?php endif; ?> <?php
														if ( $wpep_square_currency == 'AUD' ) :
															?>
							 --wpep-currency: 'A$';
														<?php endif; ?> <?php
														if ( $wpep_square_currency == 'JPY' ) :
															?>
							 --wpep-currency: '¥';
														<?php endif; ?> <?php
														if ( $wpep_square_currency == 'GBP' ) :
															?>
							 --wpep-currency: '£';
														<?php endif; ?> <?php } ?>

						}
					</style>
					<div class="wizardWrap clearfix">
						<div class="form-wizard-header">
							<ul class="list-unstyled form-wizard-steps clearfix">
								<li class="active">
									<span></span>
									<small>Basic Info</small>
								</li>
								<li>
									<span></span>
									<small>Payment</small>
								</li>
								<li>
									<span></span>
									<small>Confirm</small>
								</li>
							</ul>
						</div>

						<?php
						if ( $payment_type == 'simple' ) {

							require WPEP_ROOT_PATH . 'views/frontend/simple_payment_form.php';
						}

						if ( $payment_type == 'donation' ) {

							require WPEP_ROOT_PATH . 'views/frontend/donation_payment_form.php';

						}

						if ( $payment_type == 'subscription' ) {

							require WPEP_ROOT_PATH . 'views/frontend/subscription_payment_form.php';

						}


						if ( $payment_type == 'donation_recurring' ) {

							require WPEP_ROOT_PATH . 'views/frontend/subscription_payment_form.php';

						}

						?>


						<div class="wpep_saved_cards" style="display:none">
							<?php require WPEP_ROOT_PATH . 'views/frontend/saved_cards.php'; ?>
						</div>
					</div>
				</form>
			</div>
		</section>
	</div>
<?php } ?>
