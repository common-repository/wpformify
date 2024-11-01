<?php
	$subscription_id = get_the_ID();
	$status          = get_post_meta( $subscription_id, 'wpstp_subscription_status', true );
	$next_payment    = get_post_meta( $subscription_id, 'wpstp_stripe_subscription_next_payment', true );
	$start_date      = get_post_meta( $subscription_id, 'wpstp_stripe_subscription_created_at', true );
	$form_id         = get_post_meta( $subscription_id, 'wpstp_current_form_id', true );
?>

<div class="wpep_subscription_details_page wpep_container">

	<div class="wpep_row m-0">

		<div class="wpep_col-12">
		<div class="sep30px">&nbsp;</div>
			<h3 class="wpep_title">Schedule</h3 class="wpep_title">
			<div class="sep30px">&nbsp;</div>
		</div>

		<div class="wpep_col-12 ifnospace">
			<span class="wpep_label"><strong>Current Status:</strong> <strong> <?php echo esc_html( $status ); ?> </strong></span>
			<span class="wpep_label"><strong>Start Date:</strong> <?php echo date( 'D/M/Y', $start_date ); ?>  </span>
			<div class="sep30px">&nbsp;</div>
		</div>

		<div class="wpep_col-12">
			<div class="wpep-btn-wrap">

				<?php
				if ( $status == 'Active' ) {

					$status_button = "<input type='button' value='Pause' data-form='" . $form_id . "' data-action='Paused' data-subscription='$subscription_id' class='wpep_subscription_action wpep-btn wpep-btn-primary wpep-full wpep-btn-square wpep-btn-warning' />";
				}

				if ( $status == 'Paused' ) {

					$status_button = "<input type='button' value='Start' data-form='" . esc_attr( $form_id ) . "' data-action='Active' data-subscription='$subscription_id' class='wpep_subscription_action wpep-btn wpep-btn-primary wpep-full wpep-btn-square wpep-btn-success' />";


				}

				if ( $status == 'Completed' ) {

					$status_button = "<input type='button' data-form='" . esc_attr( $form_id ) . "' value='Completed' class='wpep_subscription_action wpep-btn wpep-btn-primary wpep-full wpep-btn-square wpep-btn-success' />";

				}


					echo esc_html( $status_button );
				?>

			
			</div> 
		</div>

	</div>

</div> 
