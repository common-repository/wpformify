<?php
$wpep_radio_amounts = get_post_meta( $wpep_current_form_id, 'wpep_radio_amounts', true );

$form_payment_global = get_post_meta( $wpep_current_form_id, 'wpep_individual_form_global', true );

$PriceSelected = ! empty( get_post_meta( $wpep_current_form_id, 'PriceSelected', true ) ) ? get_post_meta( $wpep_current_form_id, 'PriceSelected', true ) : '1';

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
		$square_currency = get_post_meta( $wpep_current_form_id, 'wpf_individual_currency_live', true );
	}

	if ( $individual_payment_mode !== 'on' ) {

		/* If Individual Form Test Mode */
		$square_currency = get_post_meta( $wpep_current_form_id, 'wpf_individual_currency_test', true );
	}
}

$currencySymbolType = ! empty( get_post_meta( $wpep_current_form_id, 'currencySymbolType', true ) ) ? get_post_meta( $wpep_current_form_id, 'currencySymbolType', true ) : 'code';

if ( $currencySymbolType == 'symbol' ) {

	if ( $square_currency == 'USD' ) :
		$square_currency = '$';
	endif;

	if ( $square_currency == 'CAD' ) :
		$square_currency = 'C$';
	endif;

	if ( $square_currency == 'AUD' ) :
		$square_currency = 'A$';
	endif;

	if ( $square_currency == 'JPY' ) :
		$square_currency = '¥';
	endif;

	if ( $square_currency == 'GBP' ) :
		$square_currency = '£';
	endif;

}

?>

<div class="subscriptionPlan selectedPlan">
	<label class="cusLabel">*Select Amount</label>
	<?php

	if ( isset( $wpep_radio_amounts[0]['amount'] ) && ! empty( $wpep_radio_amounts[0]['amount'] ) ) {
		?>
		<?php
		foreach ( $wpep_radio_amounts as $key => $amount ) {

			$count = $key;
			$count ++;

			if ( empty( $amount['label'] ) ) {
				$amount['label'] = $amount['amount'];
			}

			if ( $count == $PriceSelected ) {
				$checked = 'checked';
			} else {
				$checked = '';
			}

			if ( $currencySymbolType == 'symbol' ) {
				echo '<div class="wizard-form-radio">';
				echo '<input name="radio-name" id="subsp-' . esc_html( $wpep_current_form_id ) . '-' . esc_html( $key ) . '" type="radio" value="' . esc_html( $square_currency ) . esc_html( $amount['amount'] ) . '" ' . $checked . '>';
				echo '<label for="subsp-' . esc_attr( $wpep_current_form_id ) . '-' . esc_attr( $key ) . '" class=""> ' . esc_html( $amount['label'] ) . '</label>';
				echo '</div>';
			} else {
				echo '<div class="wizard-form-radio">';
				echo '<input name="radio-name" id="subsp-' . esc_html( $wpep_current_form_id ) . '-' . esc_html( $key ) . '" type="radio" value="' . esc_html( $amount['amount'] ) . ' ' . esc_html( $square_currency ) . '" ' . $checked . '>';
				echo '<label for="subsp-' . esc_html( $wpep_current_form_id ) . '-' . esc_html( $key ) . '" class=""> ' . esc_html( $amount['label'] ) . '</label>';
				echo '</div>';
			}
		}
		?>
	<?php } else { ?>
		<div class="wpep-alert wpep-alert-danger wpep-alert-dismissable">Please set the amount from backend</div>
	<?php } ?>

</div>
