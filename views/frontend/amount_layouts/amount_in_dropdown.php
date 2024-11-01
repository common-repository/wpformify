<?php
$wpep_dropdown_amounts = get_post_meta( $wpep_current_form_id, 'wpep_dropdown_amounts', true );

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

<label class="selectAmount">*Select Amount</label>

<div class="form-group cusPaymentSec paydlayout">
	<?php if ( ! empty( $wpep_dropdown_amounts ) ) { ?>
		<select class="form-control custom-select paynowDrop" name="" id="">
			<option value="" selected="selected">Select...</option>

			<?php
			foreach ( $wpep_dropdown_amounts as $key => $amount ) {
				$key ++;

				if ( $key == $PriceSelected ) {
					$checked = 'selected="selected"';
				} else {
					$checked = '';
				}

				if ( empty( $amount['label'] ) ) {
					$amount['label'] = $amount['amount'];
				}

				if ( $currencySymbolType == 'symbol' ) {
					echo '<option value="' . esc_html( $square_currency ) . esc_html( $amount['amount'] ) . '" ' . $checked . '>' . esc_html( $amount['label'] ) . '</option>';
				} else {
					echo '<option value="' . esc_html( $amount['amount'] ) . ' ' . esc_html( $square_currency ) . '" ' . $checked . '>' . esc_html( $amount['label'] ) . '</option>';
				}
			}
			?>
		</select>
	<?php } else { ?>
		<div class="wpep-alert wpep-alert-danger wpep-alert-dismissable">Please set the amount from backend</div>
	<?php } ?>
</div>
