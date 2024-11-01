<?php

function wpep_render_payment_form( $atts ) {

	if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) {

		if ( ! is_admin() ) {

			if ( isset( $atts['id'] ) ) {

				$form_post            = get_post( $atts['id'] );
				$wpep_current_form_id = $atts['id'];

				if ( $form_post !== null && $form_post->post_status == 'trash' ) {

					return 'This form has been trashed by the admin';
				}

				if ( $form_post == null ) {

					return 'Form does not exist';
				}

				// $square_token = wpep_get_square_token( $wpep_current_form_id );

				// if ( ! isset( $square_token ) || empty( $square_token ) ) {

				// ob_start();

				// require WPEP_ROOT_PATH . 'views/frontend/no-square-setup.php';
				// return ob_get_clean();

				// }

				ob_start();
				$payment_type = get_post_meta( $wpep_current_form_id, 'wpep_square_payment_type', true );
				require WPEP_ROOT_PATH . 'views/frontend/parent_view.php';

				return ob_get_clean();

			} else {

				return "Please provide 'id' in shortcode to display the respective form";

			}
		}
	}

	if ( ! isset( $_SERVER['HTTPS'] ) || $_SERVER['HTTPS'] !== 'on' ) {

		ob_start();

		require WPEP_ROOT_PATH . 'views/frontend/no-ssl.php';
		return ob_get_clean();

	}

}

add_action( 'init', 'wpep_register_premium_shortcode' );

function wpep_register_premium_shortcode() {

	add_shortcode( 'wpf_form', 'wpep_render_payment_form' );
}



function wpep_get_square_token( $wpep_current_form_id ) {

	$form_payment_global = get_post_meta( $wpep_current_form_id, 'wpep_individual_form_global', true );

	if ( $form_payment_global == 'on' ) {

		$global_payment_mode = get_option( 'wpep_square_payment_mode_global', true );

		if ( $global_payment_mode == 'on' ) {

			/* If Global Form Live Mode */
			$accessToken = get_option( 'wpep_live_token_upgraded', true );

		}

		if ( $global_payment_mode !== 'on' ) {

			/* If Global Form Test Mode */
			$accessToken = get_option( 'wpep_square_test_token_global', true );

		}
	}

	if ( $form_payment_global !== 'on' ) {

		$individual_payment_mode = get_post_meta( $wpep_current_form_id, 'wpep_payment_mode', true );

		if ( $individual_payment_mode == 'on' ) {

			/* If Individual Form Live Mode */
			$accessToken = get_post_meta( $wpep_current_form_id, 'wpep_live_token_upgraded', true );

		}

		if ( $individual_payment_mode !== 'on' ) {

			/* If Individual Form Test Mode */
			$accessToken = get_post_meta( $wpep_current_form_id, 'wpep_square_test_token', true );

		}
	}

	return $accessToken;

}





