<?php

add_action( 'wp_footer', 'wpep_popup_into_footer' );
function wpep_popup_into_footer() {

	if ( isset( $_SESSION['form_ids'] ) ) {

		foreach ( array_unique( $_SESSION['form_ids'] ) as $ids ) {

			$wpep_current_form_id = $ids;

			if ( ! empty( $wpep_current_form_id ) ) {

				global $post;
				$payment_type                 = get_post_meta( $wpep_current_form_id, 'wpep_square_payment_type', true );
				$wpep_open_in_popup           = get_post_meta( $wpep_current_form_id, 'wpep_open_in_popup', true );
				$wpep_show_wizard             = get_post_meta( $wpep_current_form_id, 'wpep_show_wizard', true );
				$wpep_show_shadow             = get_post_meta( $wpep_current_form_id, 'wpep_show_shadow', true );
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

				$form_payment_global = get_post_meta( $wpep_current_form_id, 'wpep_individual_form_global', true );

				$wpep_amount_layout_type         = get_post_meta( $wpep_current_form_id, 'wpep_square_amount_type', true );
				$wpep_square_form_builder_fields = get_post_meta( $wpep_current_form_id, 'wpep_square_form_builder_fields', true );
				$json_form                       = $wpep_square_form_builder_fields;
				$open_form_json                  = json_decode( $json_form );
				if ( $wpep_show_shadow == 'on' ) {
					$shadow_class = 'wpep_form_shadow';

				} else {

					$shadow_class = '';

				}

				if ( $form_payment_global == 'on' ) {

					$global_payment_mode = get_option( 'wpep_square_payment_mode_global', true );

					if ( $global_payment_mode == 'on' ) {

						/* If Global Form Live Mode */

						wp_enqueue_script( 'square_payment_form_external', '//js.squareup.com/v2/paymentform', array(), '3', true );

						$square_application_id_in_use = WPEP_SQUARE_APP_ID;
						$square_location_id_in_use    = get_option( 'wpep_square_location_id', true );
						$square_currency              = get_option( 'wpf_live_currency' );

					}

					if ( $global_payment_mode !== 'on' ) {

						/* If Global Form Test Mode */

						wp_enqueue_script( 'square_payment_form_external', '//js.squareupsandbox.com/v2/paymentform', array(), '3', true );

						$square_application_id_in_use = get_option( 'wpep_square_test_app_id_global', true );
						$square_location_id_in_use    = get_option( 'wpep_square_test_location_id_global', true );
						$square_currency              = get_option( 'wpf_test_currency' );

					}
				}

				if ( $form_payment_global !== 'on' ) {

					$individual_payment_mode = get_post_meta( $wpep_current_form_id, 'wpep_payment_mode', true );

					if ( $individual_payment_mode == 'on' ) {

						/* If Individual Form Live Mode */

						wp_enqueue_script( 'square_payment_form_external', '//js.squareup.com/v2/paymentform', array(), '3', true );

						$square_application_id_in_use = WPEP_SQUARE_APP_ID;
						$square_location_id_in_use    = get_post_meta( $wpep_current_form_id, 'wpep_square_location_id', true );
						$square_currency              = get_post_meta( $wpep_current_form_id, 'wpf_individual_currency_live', true );

					}

					if ( $individual_payment_mode !== 'on' ) {

						/* If Individual Form Test Mode */

						wp_enqueue_script( 'square_payment_form_external', '//js.squareupsandbox.com/v2/paymentform', array(), '3', true );

						$square_application_id_in_use = get_post_meta( $wpep_current_form_id, 'wpep_square_test_app_id', true );
						$square_location_id_in_use    = get_post_meta( $wpep_current_form_id, 'wpep_square_test_location_id', true );
						$square_currency              = get_post_meta( $wpep_current_form_id, 'wpf_individual_currency_test', true );

					}
				}
				?>


				<div id="wpep_popup-<?php echo esc_html( $wpep_current_form_id ); ?>" class="wpep-overlay">
					<div class="wpep-popup">
						<?php $logo = get_the_post_thumbnail_url( $wpep_current_form_id ); ?>
						<?php
						if ( isset( $logo ) && $logo != '' ) {
							echo '<span class="wpep-popup-logo"><img src="' . esc_url($logo) . '" class="wpep-popup-logo-img"></span>';
						}
						?>
						<a class="wpep-close" href="#wpep_popup-<?php echo esc_html( $wpep_current_form_id ); ?>">
							<span></span>
							<span></span>
						</a>
						<div class="wpep-content">
							<div class="wizard-<?php echo esc_html( $wpep_current_form_id ); ?> <?php
							if ( $wpep_show_wizard !== 'on' ) {
								echo 'singlepage';
							} else {
								echo 'multipage';
							}
							?>
							" style="position:relative">
								<section class="wizard-section <?php echo esc_attr( $shadow_class ); ?>" style="visibility:hidden">
									<div class="form-wizard">
										<form action="" method="post" role="form" class="wpep_payment_form"
											  data-id="<?php echo esc_html( $wpep_current_form_id ); ?>"
											  id="theForm-<?php echo esc_html( $wpep_current_form_id ); ?>" autocomplete="off"
											  data-currency="<?php echo esc_html( $square_currency ); ?>"
											  data-currency-type="<?php echo esc_html( $currencySymbolType ); ?>"
											  data-redirection="<?php echo esc_html( $wantRedirection ); ?>"
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
													 --wpep-currency: '<?php echo esc_html($wpep_square_currency); ?>';

													<?php
												} else {
													?>
													<?php
													if ( $wpep_square_currency == 'USD' ) :
														?>
	 --wpep-currency: '$';
																									<?php endif; ?>
															 <?php
																if ( $wpep_square_currency == 'CAD' ) :
																	?>
													 --wpep-currency: 'C$';
																<?php endif; ?>
															 <?php
																if ( $wpep_square_currency == 'AUD' ) :
																	?>
													 --wpep-currency: 'A$';
																<?php endif; ?>
															 <?php
																if ( $wpep_square_currency == 'JPY' ) :
																	?>
													 --wpep-currency: '¥';
																<?php endif; ?>
															 <?php
																if ( $wpep_square_currency == 'GBP' ) :
																	?>
													 --wpep-currency: '£';
																<?php endif; ?><?php } ?>

												}

												#wpep_popup-<?php echo esc_html( $wpep_current_form_id ); ?> {

													--wpep-theme-color: #<?php echo esc_html( get_post_meta( $wpep_current_form_id, 'wpep_form_theme_color', true ) ); ?>;
												}

											</style>

											<?php if ( ! empty( $form_content->post_content ) ) { ?>

												<h3> <?php echo esc_html( $form_content->post_title ); ?> </h3>

												<p class="wpep-form-desc"><?php echo esc_html( $form_content->post_content ); ?></p>

											<?php } ?>

											<?php if ( $wpep_open_in_popup != 'on' ) { ?>
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
											<?php } ?>


											<!-- wizard header -->
											<div class="wizardWrap clearfix">


												<div class="form-wizard-header 
												<?php
												if ( isset( $logo ) ) {
													echo 'form-wizard-header-logo';
												}
												?>
												">
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
											</div>
											<!-- wizard partials -->

										</form>
										<!-- end form -->

									</div>
								</section>
							</div>

						</div>
					</div>
				</div>

				<?php
			}
		}
	}

}

add_action( 'init', 'wpep_session_start' );
function wpep_session_start() {
	if ( session_status() == PHP_SESSION_NONE ) {
		session_start();
	}
	if ( isset( $_SESSION['form_ids'] ) ) {
		$_SESSION['form_ids'] = array();
	}
}


?>
