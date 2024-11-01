<?php
require_once WPEP_ROOT_PATH . 'modules/payments/square_authorization.php';
require_once WPEP_ROOT_PATH . 'modules/payments/square_payments.php';
?>
<input type="hidden" class="g-recaptcha-response" name="g-recaptcha-response"/>
<fieldset class="wizard-fieldset show">
	<div class="s_ft noMulti">
		<h2>Basic Info</h2>
	</div>

	<h5 class="noSingle">Personal Information</h5>
	<div id="wpep_personal_information" class="fieldMainWrapper">

		<?php
		foreach ( $open_form_json as $value ) {

			if ( $value->type == 'checkbox-group' ) {

				wpep_print_checkbox_group( $value );

			} elseif ( $value->type == 'radio-group' ) {

				wpep_print_radio_group( $value );

			} elseif ( $value->type == 'select' ) {

				wpep_print_select_dropdown( $value );

			} elseif ( $value->type == 'textarea' ) {

				wpep_print_textarea( $value );

			} elseif ( $value->type == 'file' ) {

				wpep_print_file_upload( $value );

			} else {

				$type       = $value->type;
				$ifRequired = " <span class='fieldReq'>*</span>";

				if ( isset( $value->subtype ) ) {
					$type = $value->subtype;
				}
				echo "<div class='" . esc_attr($type) . '-field form-group ' . ( ( isset( $value->required ) ) ? 'wpep-required' : '' ) . "'>";
				echo "<label class='wizard-form-text-label' data-label-show='" . esc_attr($value->hideLabel) . "'> " . ( ( isset( $value->label ) ) ? $value->label : '' ) . ( ( isset( $value->required ) ) ? $ifRequired : '' ) . ' </label>';
				echo "<input type='" . esc_attr($type) . "' maxlength='" . ( isset( $value->maxlength ) ? $value->maxlength : '' ) . "' min='" . ( isset( $value->min ) ? $value->min : '' ) . "' max='" . ( isset( $value->max ) ? $value->max : '' ) . "' step='" . ( isset( $value->step ) ? $value->step : '' ) . "' class='" . ( ( isset( $value->className ) ) ? $value->className : '' ) . "' data-label='" . ( ( isset( $value->label ) ) ? $value->label : '' ) . "' name='" . esc_attr($value->name) . "' required='" . ( ( isset( $value->required ) ) ? 'true' : 'false' ) . "' />";
				if ( isset( $value->description ) && $value->description != '' ) {
					echo "<span class='wpep-help-text'>" . esc_html( $value->description ) . '</span>';
				}
				echo '</div>';

			}
		}
		?>
		<input type="hidden" id="wpep_payment_form_type_<?php echo esc_html( $wpep_current_form_id ); ?>" value="subscription"/>

		<?php

		if ( isset( $enableQuantity ) ) {
			if ( 'on' == $enableQuantity ) {
				?>
				<div class="value-button" id="decrease"
					 onclick="wpep_decreaseValue(<?php echo esc_html( $wpep_current_form_id ); ?>)" value="Decrease Value">-
				</div>
				<input type="number" class="form-control" id="wpep_quantity_<?php echo esc_html( $wpep_current_form_id ); ?>"
					   name="wpep_quantity" value="0"/>
				<div class="value-button" id="increase"
					 onclick="wpep_increaseValue(<?php echo esc_html( $wpep_current_form_id ); ?>)" value="Increase Value">+
				</div>

				<?php
			}
		}
		?>

	</div>


	<div class="btnGroup btnGroupFirst noSingle">
		<!-- <a href="javascript:;" class="form-wizard-previous-btn float-left">Previous</a> -->
		<a href="javascript:;" class="form-wizard-next-btn float-right">Next</a>
	</div>
</fieldset>


<fieldset class="wizard-fieldset">
	<div class="s_ft noMulti">
	<?php
	if ( $wpep_amount_layout_type !== 'payment_tabular' ) {

		echo '<h2>Payment</h2>';

	}
	?>
	</div>

	<h5 class="noSingle">Payment Information</h5>
	<?php

	if ( $wpep_amount_layout_type == 'payment_drop' ) {
		require WPEP_ROOT_PATH . 'views/frontend/amount_layouts/amount_in_dropdown.php';

		if ( $currencySymbolType == 'symbol' ) {
			$showDefaultAmount = $square_currency . $wpep_dropdown_amounts[ $PriceSelected - 1 ]['amount'];
		} else {
			$showDefaultAmount = $wpep_dropdown_amounts[ $PriceSelected - 1 ]['amount'] . ' ' . $square_currency;
		}
	}

	if ( $wpep_amount_layout_type == 'payment_radio' ) {
		require WPEP_ROOT_PATH . 'views/frontend/amount_layouts/amount_in_radio.php';

		if ( $currencySymbolType == 'symbol' ) {
			$showDefaultAmount = $square_currency . $wpep_radio_amounts[ $PriceSelected - 1 ]['amount'];
		} else {
			$showDefaultAmount = $wpep_radio_amounts[ $PriceSelected - 1 ]['amount'] . ' ' . $square_currency;
		}
	}

	if ( $wpep_amount_layout_type == 'payment_custom' ) {

		require WPEP_ROOT_PATH . 'views/frontend/amount_layouts/amount_custom.php';

		if ( $defaultPriceSelected == 'dollar1' || $defaultPriceSelected == '' ) {
			if ( $currencySymbolType == 'symbol' ) {
				$showDefaultAmount = $square_currency . $wpep_square_payment_box_1;
			} else {
				$showDefaultAmount = $wpep_square_payment_box_1 . ' ' . $square_currency;
			}
		}

		if ( $defaultPriceSelected == 'dollar2' ) {
			if ( $currencySymbolType == 'symbol' ) {
				$showDefaultAmount = $square_currency . $wpep_square_payment_box_2;
			} else {
				$showDefaultAmount = $wpep_square_payment_box_2 . ' ' . $square_currency;
			}
		}

		if ( $defaultPriceSelected == 'dollar3' ) {
			if ( $currencySymbolType == 'symbol' ) {
				$showDefaultAmount = $square_currency . $wpep_square_payment_box_3;
			} else {
				$showDefaultAmount = $wpep_square_payment_box_3 . ' ' . $square_currency;
			}
		}

		if ( $defaultPriceSelected == 'dollar4' ) {
			if ( $currencySymbolType == 'symbol' ) {
				$showDefaultAmount = $square_currency . $wpep_square_payment_box_4;
			} else {
				$showDefaultAmount = $wpep_square_payment_box_4 . ' ' . $square_currency;
			}
		}
	}

	if ( $wpep_amount_layout_type == 'payment_tabular' ) {
		require WPEP_ROOT_PATH . 'views/frontend/amount_layouts/amount_in_tabular.php';
	}

	require WPEP_ROOT_PATH . 'views/frontend/payment_methods.php';

	$wpep_btn_label = get_post_meta( $wpep_current_form_id, 'wpep_payment_btn_label', true );
	if ( isset( $wpep_btn_label ) && ! empty( $wpep_btn_label ) ) {
		$pay_button_label = $wpep_btn_label;
	} else {

		$pay_button_label = 'Pay';
	}
	?>
	<div class="btnGroup ifSingle">
		<a href="javascript:;" class="form-wizard-previous-btn float-left noSingle">Previous</a>

		<div style="display:flex">
			<?php
			$sub_total_amount = isset( $showDefaultAmount ) ? floatval( $showDefaultAmount ) : 0.00;
			$total_amount     = $sub_total_amount;
			$currency         = isset( $square_currency ) ? $square_currency : '$';
			$fees_data        = get_post_meta( $wpep_current_form_id, 'fees_data' );
			// echo '<pre>';
			// print_r($fees_data[0]['check']);
			// echo '</pre>';
			if ( ! empty( $fees_data[0]['check'] ) && in_array( 'yes', $fees_data[0]['check'] ) ) :
				?>
				<div class="wpep-payment-details-wrapper">
					<a href="#" class="wpep-open-details" data-id="<?php echo esc_html( $wpep_current_form_id ); ?>"><?php echo esc_html__( 'Payment details', 'wpformify' ); ?></a>
					<div class="wpep-payment-details" id="wpep-payment-details-<?php echo esc_html( $wpep_current_form_id ); ?>">
					<ul>
						<li class="wpep-fee-subtotal">
							<span class="fee_name"><?php echo esc_html__( 'Subtotal', 'wpformify' ); ?></span>
							<span class="fee_value"><?php echo esc_attr( number_format( $sub_total_amount, 2 ) ) . ' ' . esc_attr( $currency ); ?></span>
						</li>
						<?php
						foreach ( $fees_data[0]['check'] as $key => $fees ) :
							if ( 'yes' === $fees ) :

								if ( 'percentage' == $fees_data[0]['type'][ $key ] ) {
									$tax = $sub_total_amount * ( $fees_data[0]['value'][ $key ] / 100 );
								} else {
									$tax = $fees_data[0]['value'][ $key ];
								}

								$total_amount = $total_amount + $tax;
								?>
								<li>
									<span class="fee_name"><?php echo esc_html( $fees_data[0]['name'][ $key ] ); ?></span>
									<span class="fee_value"><?php echo esc_attr( number_format( $tax, 2 ) ) . ' ' . esc_attr( $currency ); ?></span>
								</li>
								<?php
							endif;
						endforeach;
						?>
						<li class="wpep-fee-total">
							<span class="fee_name"><?php echo esc_html__( 'Total', 'wpformify' ); ?></span>
							<span class="fee_value"><?php echo esc_attr( number_format( $total_amount, 2 ) ) . ' ' . esc_attr( $currency ); ?></span>
						</li>
					</ul>
					</div>
				</div>
				<?php
			endif;
			?>
			<button class="
			<?php
			if ( 'on' == $wpep_show_wizard ) :
				echo 'wpep-wizard-form-submit-btn';
			else :
				echo 'wpep-single-form-submit-btn';
			endif;
			?>
			<?php
			if ( ! isset( $showDefaultAmount ) ) :
				echo 'wpep-disabled';
				endif;
			?>
				float-right"><?php echo esc_html( $pay_button_label ); ?>
				<span>
					<b id="dosign" style="display: none;">$</b><small id="amount_display_<?php echo esc_html( $wpep_current_form_id ); ?>"
																	class="display">
																	<?php
																	if ( ! empty( $fees_data[0]['check'] ) && in_array( 'yes', $fees_data[0]['check'] ) && isset( $total_amount ) ) :
																		echo number_format( $total_amount, 2 );
																		elseif ( isset( $showDefaultAmount ) ) :
																			echo esc_html( $showDefaultAmount );
																		endif;
																		?>
																		</small>
					<input type="hidden" name="wpep-selected-amount"
						value="
						<?php
						if ( ! empty( $fees_data[0]['check'] ) && in_array( 'yes', $fees_data[0]['check'] ) && isset( $total_amount ) ) :
								echo esc_html( number_format( $total_amount, 2 ) );
							elseif ( isset( $showDefaultAmount ) ) :
								echo esc_html( $showDefaultAmount );
							endif;
							?>
							">
					<input type="hidden" name="one_unit_cost" id="one_unit_cost"
						value="
						<?php
						if ( isset( $showDefaultAmount ) ) :
							echo esc_html( $showDefaultAmount );
							endif;
						?>
							"/>
				</span>
			</button>
			<?php
			if ( ! empty( $fees_data[0]['check'] ) && in_array( 'yes', $fees_data[0]['check'] ) && isset( $total_amount ) ) :
				$gross_total = number_format( $total_amount, 2 );
			elseif ( isset( $showDefaultAmount ) ) :
				$gross_total = $showDefaultAmount;
			endif;
			?>
			<input type="hidden" name="gross_total" value="<?php echo esc_html( $gross_total ); ?>">
		</div>
	</div>


</fieldset>
<input type="hidden" id="wpep_form_currency" name="wpep_currency" value="<?php echo esc_html($square_currency); ?>"/>
<fieldset class="wizard-fieldset orderCompleted blockIfSingle">
	<div class="confIfSingleTop">
		<img src="<?php echo esc_url( WPEP_ROOT_URL . 'assets/frontend/img/order-done.svg' ); ?>" alt="Avatar" width="70"
			 class="doneorder">
		<h2>Payment Successful</h2>
	</div>

	<?php if ( '' != $wpep_payment_success_msg ) { ?>
		<p><?php echo esc_html($wpep_payment_success_msg); ?></p>
	<?php } else { ?>
		<p>Thank you for your purchase.</p>
	<?php } ?>

	<?php if ( '' != $wpep_payment_success_url && '' != $wpep_payment_success_label ) { ?>
		<a href="<?php echo esc_url($wpep_payment_success_url); ?>"
		   class="form-wizard-submit float-right"><?php echo esc_html($wpep_payment_success_label); ?></a><br><br>
	<?php } ?>

	<small class="counterText">Page will be redirected in <span id="counter-<?php echo esc_html($wpep_current_form_id); ?>">5</span>
		seconds.
	</small>
</fieldset>
