<?php
	$wpf_digital_wallets_ind  = get_post_meta( $wpep_current_form_id, 'wpep_square_google_pay', true );
	$wpf_digital_wallets      = get_option( 'wpf_digital_wallets', true );
	$wpf_digital_wallets_test = get_option( 'wpf_digital_wallets_test', true );
	$wpep_square_customer_cof = get_user_meta( get_current_user_id(), 'wpep_square_customer_cof', true );
	$wpep_save_card           = get_post_meta( $wpep_current_form_id, 'wpep_save_card', true );
	$enableQuantity           = get_post_meta( $wpep_current_form_id, 'enableCoupon', true );
?>

	<?php
	if ( 'on' == $enableQuantity ) {
		// continue on Monday 8 feb 2021
		require WPEP_ROOT_PATH . 'views/frontend/coupons.php';
	}
	?>
	<div class="paymentsBlocks">
		<ul class="wpep_tabs">
			<li class="tab-link current" data-tab="creditCard">

				<img src="<?php echo esc_url( WPEP_ROOT_URL . 'assets/frontend/img/creditcard.svg' ); ?>" alt="Avatar" width="45"
					 class="doneorder" alt="Credit Card">
				<!-- <h4 class="">Credit Card</h4> -->
				<span>Credit Card</span>
			</li>
			<?php
			if ( 'on' == $wpf_digital_wallets || 'on' == $wpf_digital_wallets_ind || 'on' == $wpf_digital_wallets_test ) {
				?>
				<li class="tab-link" data-tab="googlePay">
					<img src="<?php echo esc_url( WPEP_ROOT_URL . 'assets/frontend/img/googlepay.svg' ); ?>" alt="Avatar" width="45"
						 class="doneorder" alt="Google Pay">
					<span>Digital Wallets</span>
				</li>
				<?php
			}
			?>
		</ul>

		<div id="creditCard" class="tab-content current">
			<div class="clearfix">
				<h3 style="display:none">Credit Card</h3>

				<div class="cardsBlock01">

					<div class="cardsBlock02">
						<div class="wizard-form-radio">
							<label for="newCard"><input type="radio" name="savecards" id="newCard" checked="checked"
														value="2"/>Add New Card</label>
						</div>

						<?php
						// if ( isset( $wpep_square_customer_cof ) && ! empty( $wpep_square_customer_cof ) ) {
						// 	?>
						<!-- // 	<div class="wizard-form-radio">
						// 		<label for="existingCard"><input type="radio" name="savecards" id="existingCard"
						// 										 value="3"/>Use
						// 			Existing Card</label>

						// 	</div> -->
						 	<?php
						// }
						?>
					</div>

					<div id="cardContan2" class="desc">
						<?php
						wpep_print_credit_card_fields( $wpep_current_form_id );

						if ( $wpep_save_card == 'on' ) {
							?>

							<div class="wizard-form-checkbox saveCarLater">
								<input name="savecardforlater" id="saveCardLater" type="checkbox" required="true">
								<label for="saveCardLater">Save card for later use</label>
							</div>

							<?php
						}
						?>
					</div>

					<div id="cardContan3" class="desc" style="display: none;">
						<div class="wpep_saved_cards">
							<?php require WPEP_ROOT_PATH . 'views/frontend/saved_cards.php'; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="googlePay" class="tab-content ">
			<div id="payment-request-button-<?php echo esc_html( $wpep_current_form_id ); ?>"></div>
		</div>
	</div>

<?php if ( $enableTermsCondition == 'on' && $termsLabel != '' && $termsLabel != 'no' && $termsLink != '' && $termsLink != 'no' ) { ?>
	<div class="termsCondition wpep-required form-group">
		<div class="wizard-form-checkbox">
			<input name="terms-condition-checkbox" id="termsCondition-<?php echo esc_html( $wpep_current_form_id ); ?>" type="checkbox"
				   required="true">
			<label for="termsCondition-<?php echo esc_html( $wpep_current_form_id ); ?>">I accept the</label> <a
				href="<?php echo esc_url( $termsLink ); ?>"><?php echo esc_html( $termsLabel ); ?></a>
		</div>
	</div>
<?php } else { ?>
	<div class="termsCondition wpep-required form-group" style="display:none">
		<div class="wizard-form-checkbox">
			<input name="terms-condition-checkbox" id="termsCondition-<?php echo esc_html( $wpep_current_form_id ); ?>" type="checkbox"
				   required="true" checked>
			<label for="termsCondition-<?php echo esc_html( $wpep_current_form_id ); ?>">I accept the</label> <a
				href="<?php echo esc_url( $termsLink ); ?>"><?php echo esc_html( $termsLabel ); ?></a>
		</div>
	</div>
<?php } ?>
