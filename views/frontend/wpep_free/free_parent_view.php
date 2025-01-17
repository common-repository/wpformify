<?php

require_once WPEP_ROOT_PATH . '/modules/render_forms/form_helper_functions.php';

$predefined_amount       = get_option( 'wpep_free_amount', true );
$free_form_type          = get_option( 'wpep_free_form_type', true );
$user_defined_amount     = get_option( 'wpep_free_user_set_amount', true );
$wpep_free_form_currency = get_option( 'wpep_square_currency_new' );
$global_payment_mode     = get_option( 'wpep_square_payment_mode_global', true );

if ( $global_payment_mode == 'on' ) {

	/* If Global Form Live Mode */

		wp_enqueue_script( 'square_payment_form_external', '//js.squareup.com/v2/paymentform', array(), '3', true );

		$square_application_id_in_use = WPEP_SQUARE_APP_ID;
		$square_location_id_in_use    = get_option( 'wpep_square_location_id', true );

}

if ( $global_payment_mode !== 'on' ) {

	/* If Global Form Test Mode */

		wp_enqueue_script( 'square_payment_form_external', '//js.squareupsandbox.com/v2/paymentform', array(), '3', true );

		$square_application_id_in_use = get_option( 'wpep_square_test_app_id_global', true );
		$square_location_id_in_use    = get_option( 'wpep_square_test_location_id_global', true );

}



	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'square_payment_form_free_internal', WPEP_ROOT_URL . 'assets/frontend/js/wpep_free_payment_form.js', array(), '3', true );
	wp_enqueue_style( 'wpep_free_form_style', WPEP_ROOT_URL . 'assets/frontend/css/free_payment_form.css' );

	wp_localize_script(
		'square_payment_form_free_internal',
		'wpep_local_vars',
		array(
			'ajax_url'                  => admin_url( 'admin-ajax.php' ),
			'square_application_id'     => $square_application_id_in_use,
			'square_location_id_in_use' => $square_location_id_in_use,
			'form_user_defined_amount'  => $predefined_amount,
			'form_type'                 => $free_form_type,
			'user_defined_amount'       => $user_defined_amount,
			'wpep_free_form_currency'   => $wpep_free_form_currency,
			'front_img_url'             => WPEP_ROOT_URL . 'assets/frontend/img',
		)
	);



	if ( ! isset( $wpep_current_form_id ) ) {
		$wpep_current_form_id = 1; // free form
	}



	?>
<div class="freepage">
<section class="free_form_section ">

	<div class="free_form_page" style="display:none">

		<form action="" method="post" role="form" class="wpep_payment_form" data-id="<?php echo esc_attr( $wpep_current_form_id ); ?>" id="theForm-<?php echo esc_attr( $wpep_current_form_id ); ?>">

		  

			<!-- wizard header -->
			<div class="wizardWrap clearfix">


				<?php

					$payment_type = get_option( 'wpep_free_form_type' );

				if ( $payment_type == 'simple' ) {

					require WPEP_ROOT_PATH . 'views/frontend/wpep_free/simple_payment_form_free.php';

				}


				if ( $payment_type == 'donation' ) {

					require WPEP_ROOT_PATH . 'views/frontend/wpep_free/donation_payment_form_free.php';

				}
				?>

			</div>
			<!-- wizard partials -->

		</form>
		<!-- end form -->

	</div>
	<!-- end form wizard -->

</section>
<!-- end wizard section -->
</div>
