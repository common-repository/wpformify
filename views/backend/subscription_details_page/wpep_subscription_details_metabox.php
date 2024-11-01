<div class="wpep_subscription_details_page wpep_container">

	<div class="wpep_row m-0">

			<?php

				$subscription_id = get_the_ID();
				$first_name      = get_post_meta( $subscription_id, 'wpstp_stripe_customer_name', true );
				$email           = get_post_meta( $subscription_id, 'wpstp_stripe_email', true );

				$card_brand = get_post_meta( $subscription_id, 'wpep_card_brand', true );
				$last_4     = get_post_meta( $subscription_id, 'wpep_last_4', true );
				$exp_month  = get_post_meta( $subscription_id, 'wpep_exp_month', true );
				$exp_year   = get_post_meta( $subscription_id, 'wpep_exp_year', true );

			?>

		<div class="wpep_col-12">
			<div class="sep30px">&nbsp;</div>
			<h3 class="wpep_title">Subscription #<span class="subscription-id"><?php echo esc_html( $subscription_id ); ?></span></h3 class="wpep_title">
			<div class="sep30px">&nbsp;</div>
		</div>

	</div>

	<div class="wpep_row m-0">

		<div class="wpep_col-4">

			<h3 class="wpep_title">Customer Details</h3>
			<div class="sep20px">&nbsp;</div>

			<div class="wpep_flex">
					
					
					<span class="wpep_label"><strong>Name:</strong> <?php echo esc_html( $first_name ); ?></span>
					<span class="wpep_label"><strong>Email:</strong> <?php echo esc_html( $email ); ?></span>


				
			</div>

		</div>


		<?php
		if ( isset( $card_brand ) && ! empty( $card_brand ) ) {
			?>
		<div class="wpep_col-4">
			<h3 class="wpep_title">Card Details</h3>
			<div class="sep20px">&nbsp;</div>
			<div class="wpep_flex">
				<span class="wpep_label"><strong>Card Brand:</strong> <?php echo esc_html( $card_brand ); ?></span>
				<span class="wpep_label"><strong>Last 4 Digit:</strong> <?php echo esc_html( $last_4 ); ?></span>
				<span class="wpep_label"><strong>Expiry Month:</strong> <?php echo esc_html( $exp_month ); ?></span>
				<span class="wpep_label"><strong>Expiry Year:</strong> <?php echo esc_html( $exp_year ); ?></span>
			</div>
		</div>
			<?php
		}
		?>


	</div>
</div>
