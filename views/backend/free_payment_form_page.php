<?php

if ( isset( $_POST ) && ! empty( $_POST ) ) {

	$wpep_free_notify_email      = sanitize_email( $_POST['wpep_free_notify_email'] );
	$wpep_free_btn_text          = sanitize_text_field( $_POST['wpep_free_btn_text'] );
	$wpep_free_amount            = sanitize_text_field( $_POST['wpep_free_amount'] );
	$wpep_free_success_url       = esc_url_raw( $_POST['wpep_free_success_url'] );
	$wpep_free_organization_name = sanitize_text_field( $_POST['wpep_free_org_name'] );

	if ( ! isset( $_POST['wpep_free_user_set_amount'] ) ) {

		$wpep_free_user_set_amount = 'off';

	} else {

		$wpep_free_user_set_amount = sanitize_text_field( $_POST['wpep_free_user_set_amount'] );
	}

	$wpep_free_form_type = sanitize_text_field( $_POST['wpep_free_form_type'] );


	update_option( 'wpep_free_notify_email', $wpep_free_notify_email );
	update_option( 'wpep_free_btn_text', $wpep_free_btn_text );
	update_option( 'wpep_free_amount', $wpep_free_amount );
	update_option( 'wpep_free_success_url', $wpep_free_success_url );
	update_option( 'wpep_free_org_name', $wpep_free_organization_name );
	update_option( 'wpep_free_user_set_amount', $wpep_free_user_set_amount );
	update_option( 'wpep_free_form_type', $wpep_free_form_type );

	/*
		if ( isset($_POST['wpep_free_form_currency']) ) {
		$currency = sanitize_text_field($_POST['wpep_free_form_currency']);
		update_option('wpep_free_form_currency', $currency);

		if ( 'USD'==$currency ) {
		  update_option('wpep_free_form_currency_symbol', 'USD');
		}

		if ( 'CAD'==$currency ) {
		  update_option('wpep_free_form_currency_symbol', 'CAD');
		}

		if ( 'AUD'==$currency ) {
		  update_option('wpep_free_form_currency_symbol', 'AUD');
		}

		if ( 'JPY'==$currency ) {
		  update_option('wpep_free_form_currency_symbol', 'YEN');
		}

		if ( 'GBP'==$currency ) {
		  update_option('wpep_free_form_currency_symbol', 'GBP');
		}
	}

	$wpep_free_form_currency = get_option('wpep_free_form_currency');*/


}

?>
<div class="wpep_free_payment_form_page wpep_container">
	
	<div class="wpep_row">

		<div class="wpep_col-12">
			
			<a href="#" class="free-signup" target="_blank">
					
				<img src="<?php echo esc_url( WPEP_ROOT_URL . '/assets/backend/img/signup.png' ); ?>" class="wpep-img-responsive" alt="">
		
			</a>

			<div class="sep25px">&nbsp;</div>

		</div>

	</div>

	<div class="wpep_row">

		<div class="wpep_col-12">
			
			<form action="#" method="POST" class="wpep-form" enctype="multipart/form-data">
				
				<div class="wpep-form-group">
					<label>Shortcode:</label>
					<input type="text" class="wpep-form-control" value="[wpep_form]" readonly>
				</div>

				<?php if ( '' != get_option( 'wpep_square_currency_new' ) ) { ?>
					<div class="wpep-form-group">
						<label>Country Currency</label>
						<select name="wpep_square_currency_new" class="wpep-form-control" disabled="disabled">
							<option value="USD" 
							<?php
							if ( ! empty( get_option( 'wpep_square_currency_new' ) ) && 'USD' == get_option( 'wpep_square_currency_new' ) ) :
								echo "selected='selected'";
endif;
							?>
							>USD</option>
							<option value="CAD" 
							<?php
							if ( ! empty( get_option( 'wpep_square_currency_new' ) ) && 'CAD' == get_option( 'wpep_square_currency_new' ) ) :
								echo "selected='selected'";
endif;
							?>
							 >CAD</option>
							<option value="AUD" 
							<?php
							if ( ! empty( get_option( 'wpep_square_currency_new' ) ) && 'AUD' == get_option( 'wpep_square_currency_new' ) ) :
								echo "selected='selected'";
endif;
							?>
							 >AUD</option>
							<option value="JPY" 
							<?php
							if ( ! empty( get_option( 'wpep_square_currency_new' ) ) && 'JPY' == get_option( 'wpep_square_currency_new' ) ) :
								echo "selected='selected'";
endif;
							?>
							 >JPY</option>
							<option value="GBP" 
							<?php
							if ( ! empty( get_option( 'wpep_square_currency_new' ) ) && 'GBP' == get_option( 'wpep_square_currency_new' ) ) :
								echo "selected='selected'";
endif;
							?>
							 >GBP</option>
						</select>
					</div>
				<?php } ?>

				<div class="wpep-form-group">
					<label>Notification Email:</label>
					<input type="text" class="wpep-form-control" Placeholder="Please enter your email" name="wpep_free_notify_email" value="<?php echo esc_url( get_option( 'wpep_free_notify_email' ) ); ?>" >
				</div>

				<div class="wpep-form-group">
					<label>Type:&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input type="radio" class="" name="wpep_free_form_type" id="simple-check" value="simple" <?php echo get_option( 'wpep_free_form_type' ) == 'simple' ? 'checked' : ''; ?>>&nbsp;<label for="simple-check">Simple</label>&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" class="" name="wpep_free_form_type" id="donation-check" value="donation" <?php echo get_option( 'wpep_free_form_type' ) == 'donation' ? 'checked' : ''; ?>>&nbsp;<label for="donation-check">Donation</label>&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" class="" name="wpep_free_form_type" disabled>&nbsp;<label style="opacity:0.8">subscription</label>&nbsp;&nbsp; -- <span class="wpep-info"><a href="#">You can process Subscription/Recurring payments with <strong>Pro Version</strong></a></span>
				</div>

				<div class="wpep-form-group">
					<label>Button Text:</label>
					<input type="text" class="wpep-form-control" Placeholder="Please enter the submit button text" name="wpep_free_btn_text" value="<?php echo esc_html( get_option( 'wpep_free_btn_text' ) ); ?>" >
				</div>

				<div class="wpep-form-group" id="donation-depended-3">
					<label>Amount:</label>
					<input type="text" class="wpep-form-control" Placeholder="Please enter amount to capture" name="wpep_free_amount" value="<?php echo esc_html( get_option( 'wpep_free_amount' ) ); ?>" >
				</div>

				<div class="wpep-form-group" id="donation-depended-1">
					<label>Organization Name:</label>
					<input type="text" class="wpep-form-control" Placeholder="Please enter organization name" name="wpep_free_org_name" value="<?php echo esc_html( get_option( 'wpep_free_org_name' ) ); ?>" >
				</div>

				<div class="wpep-form-group" id="donation-depended-2">  
					<label>User set donation:&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input type="checkbox" class="" name="wpep_free_user_set_amount" id="user-donation" value="on" <?php echo get_option( 'wpep_free_user_set_amount' ) == 'on' ? 'checked' : ''; ?> >
				</div>

				<div class="wpep-form-group">
					<label>Payment Success URL:</label>
					<input type="text" class="wpep-form-control" Placeholder="Please enter successful payment url" name="wpep_free_success_url" value="<?php echo esc_url( get_option( 'wpep_free_success_url' ) ); ?>" >
				</div>

				<div class="wpep-form-group">
					<input type="submit" name="wpep_free_submit" class="wpep-btn wpep-btn-primary wpep-btn-square" value="Save Setting">
				</div>

			</form>

		</div>

	</div>

</div>

<script>

	jQuery(document).ready(function(){

		if (jQuery("#simple-check").is(":checked")) {
				jQuery("#donation-depended-1").hide();
				jQuery("#donation-depended-2").hide();
				jQuery("#donation-depended-3").show();
				jQuery("#user-donation").attr("checked", false);
			} else {
				jQuery("#donation-depended-1").show();
				jQuery("#donation-depended-2").show();
			}


			if (jQuery("#donation-check").is(":checked")) {
				jQuery("#donation-depended-1").show();
				jQuery("#donation-depended-2").show();
			} else {
				jQuery("#donation-depended-1").hide();
				jQuery("#donation-depended-2").hide();
			}

			if (jQuery("#user-donation").is(":checked")) {
				jQuery("#donation-depended-3").hide();
			} else {
				jQuery("#donation-depended-3").show();
			}

	});

	jQuery(function($){
		jQuery("#simple-check").click(function () {
			if (jQuery(this).is(":checked")) {
				jQuery("#donation-depended-1").hide();
				jQuery("#donation-depended-2").hide();
				jQuery("#donation-depended-3").show();
				jQuery("#user-donation").attr("checked", false);
			} else {
				jQuery("#donation-depended-1").show();
				jQuery("#donation-depended-2").show();
			}
		});

		jQuery("#donation-check").click(function () {
			if (jQuery(this).is(":checked")) {
				jQuery("#donation-depended-1").show();
				jQuery("#donation-depended-2").show();
			} else {
				jQuery("#donation-depended-1").hide();
				jQuery("#donation-depended-2").hide();
			}
		});

		jQuery("#user-donation").click(function () {
			if (jQuery(this).is(":checked")) {
				jQuery("#donation-depended-3").hide();
			} else {
				jQuery("#donation-depended-3").show();
			}
		});
	});
</script>
