<fieldset class="wizard-fieldset show">
	<div class="s_ft noMulti">
		<h2>Simple Payment</h2>
	</div>

   
	<div id="creditCard" class="tab-content current">
		<h3><img src="<?php echo esc_url( WPEP_ROOT_URL . 'assets/frontend/img/creditcard.svg' ); ?>" alt="Avatar" width="25"
			class="credicon" alt="Credit Card"> Credit Card</h3>
			<?php
			wpep_print_credit_card_fields_free();
			?>
	</div>


</fieldset>

<fieldset class="wizard-fieldset orderCompleted blockIfSingle">
	<div class="confIfSingleTop">
		<img src="<?php echo esc_url( WPEP_ROOT_URL . 'assets/frontend/img/order-done.svg' ); ?>" alt="Avatar" width="70"
			class="doneorder">
		<h2>Order Confirmation</h2>
	</div>


	<div class="btnGroup lastPage">
		<!-- <a href="javascript:;" class="form-wizard-previous-btn float-left">Previous</a> -->
		<a href="javascript:;" class="form-wizard-submit float-right">Go to your order</a>
	</div>
</fieldset>
