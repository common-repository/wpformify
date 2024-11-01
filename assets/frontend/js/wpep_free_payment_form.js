const paymentForm = new SqPaymentForm(
	{
		// Initialize the payment form elements

		// TODO: Replace with your sandbox application ID
		applicationId: wpep_local_vars.square_application_id,
		inputClass: 'sq-input',
		autoBuild: false,
		// Customize the CSS for SqPaymentForm iframe elements
		inputStyles: [{
			fontSize: '16px',
			lineHeight: '24px',
			padding: '16px',
			placeholderColor: '#a0a0a0',
			backgroundColor: 'transparent',
		}],
		// Initialize the credit card placeholders
		cardNumber: {
			elementId: 'sq-card-number',
			placeholder: 'Card Number'
		},
		cvv: {
			elementId: 'sq-cvv',
			placeholder: 'CVV'
		},
		expirationDate: {
			elementId: 'sq-expiration-date',
			placeholder: 'MM/YY'
		},
		postalCode: {
			elementId: 'sq-postal-code',
			placeholder: 'Postal'
		},

		callbacks: {

			cardNonceResponseReceived: function (errors, nonce, cardData) {

				jQuery( '.wpep-alert' ).remove();

				jQuery( "#theForm-1" ).parents( 'body' ).append( jQuery( '<div />' ).attr( 'class', 'wpepLoader' ).html( '<div class="initial-load-animation"><div class="payment-image icomoonLib"><span class="icon-pay"></span></div><div class="loading-bar"><div class="blue-bar"></div></div></div>' ) );

				if (errors) {

					setTimeout(
						function() {
							jQuery( '.wpepLoader' ).remove();
							errors.forEach(
								function (error) {

									if (error.message) {
										jQuery( "#theForm-1 #creditCard" ).prepend( '<div class="wpep-alert wpep-alert-danger wpep-alert-dismissable"><a href="#" data-dismiss="alert" class="wpep-alert-close">×</a>' + error.message + '</div>' );
									}
								}
							);
						},
						1500
					);
				} else {

					if (wpep_local_vars.form_type == 'donation' && wpep_local_vars.user_defined_amount == 'on') {

						var amount = jQuery( '#wpep_user_defined_amount' ).val();

					} else {

						var amount = wpep_local_vars.form_user_defined_amount;

					}

					var data = {

						'action': 'wpep_free_payment_request',
						'nonce': nonce,
						'amount': amount

					};

					jQuery.post(
						wpep_local_vars.ajax_url,
						data,
						function(response) {
							// console.log(response);
							if ('success' == response) {

								var current = jQuery( 'form#theForm-1' );
								current.find( '.wizard-fieldset' ).removeClass( "show", "400" );
								current.find( '.wizard-fieldset:last-child' ).addClass( "show wpep-ptb-150", "400" );
								current.find( '.wizard-fieldset:last-child' ).siblings().remove();

								jQuery( 'html, body' ).animate(
									{
										scrollTop: jQuery( "#theForm-1" ).offset().top - 50
									},
									800
								);

							} else {

								var json_response = JSON.parse( response );
								jQuery( "#theForm-1 #creditCard" ).prepend( '<div class="wpep-alert wpep-alert-danger wpep-alert-dismissable"><a href="#" data-dismiss="alert" class="wpep-alert-close">×</a>' + json_response.detail + '</div>' );
								jQuery( 'html, body' ).animate(
									{
										scrollTop: jQuery( "#theForm-1 #creditCard" ).offset().top
									},
									800
								);

							}

						}
					).done(
						function() {

							jQuery( '.wpepLoader' ).remove();

						}
					);

				}

			}

		}
	}
);


function onGetCardNonce(event) {
	// Don't submit the form until SqPaymentForm returns with a nonce
	event.preventDefault();
	// Request a nonce from the SqPaymentForm object
	paymentForm.requestCardNonce();
}

  paymentForm.build();

  // focus on input field check empty or not
jQuery( ".form-control" ).on(
	'focus',
	function () {
		var tmpThis = jQuery( this ).val();
		if (tmpThis == '') {
			jQuery( this ).parent().addClass( "focus-input" );
		} else if (tmpThis != '') {
			jQuery( this ).parent().addClass( "focus-input" );
		}
	}
).on(
	'blur',
	function () {
		var tmpThis = jQuery( this ).val();
		if (tmpThis == '') {
			jQuery( this ).parent().removeClass( "focus-input" );
			jQuery( this ).siblings( '.wizard-form-error' ).slideDown( "3000" );
		} else if (tmpThis != '') {
			jQuery( this ).parent().addClass( "focus-input" );
			jQuery( this ).siblings( '.wizard-form-error' ).slideUp( "3000" );
		}
	}
);


if ('off' == wpep_local_vars.user_defined_amount) {

	jQuery( '.wpep-free-form-submit-btn' ).removeClass( 'wpep-disabled' );

	jQuery( '#showPayment' ).removeClass( 'shcusIn' );

	jQuery( '.wpep-free-form-submit-btn .display' ).text( wpep_local_vars.form_user_defined_amount + ' ' + wpep_local_vars.wpep_free_form_currency );

	jQuery( '.wpep-free-form-submit-btn .display' ).next( 'input[name="wpep-selected-amount"]' ).val( wpep_local_vars.form_user_defined_amount ).trigger( 'change' );

} else {

	jQuery( '.wpep-free-form-submit-btn' ).addClass( 'wpep-disabled' );

	jQuery( '#showPayment' ).addClass( 'shcusIn' );

	jQuery( '.wpep-free-form-submit-btn .display' ).text( '' );

	jQuery( '.wpep-free-form-submit-btn .display' ).next( 'input[name="wpep-selected-amount"]' ).val( '' ).trigger( 'change' );

}

jQuery( '#showPayment' ).on(
	'copy paste keyup click',
	'.customPayment',
	function(){

		var amount = jQuery( this ).val();

		if ('' == amount) {

			jQuery( '.wpep-free-form-submit-btn' ).addClass( 'wpep-disabled' );

			jQuery( '.wpep-free-form-submit-btn .display' ).text( '' );

			jQuery( '.wpep-free-form-submit-btn .display' ).next( 'input[name="wpep-selected-amount"]' ).val( '' ).trigger( 'change' );

		} else {

			jQuery( '.wpep-free-form-submit-btn' ).removeClass( 'wpep-disabled' );

			jQuery( '.wpep-free-form-submit-btn .display' ).text( amount + ' ' + wpep_local_vars.wpep_free_form_currency );

			jQuery( '.wpep-free-form-submit-btn .display' ).next( 'input[name="wpep-selected-amount"]' ).val( amount ).trigger( 'change' );

		}
	}
);

// click on form submit for single form type button mufaddal version
jQuery( document ).on(
	"click",
	".free_form_page .wpep-free-form-submit-btn",
	function () {

		var form_id = jQuery( this ).parents( 'form' ).data( 'id' );

		var current = jQuery( this );

		if (current.find( '.display' ).next( 'input[name="wpep-selected-amount"]' ).length > 0) {

			// console.log('I am here!');

			if (current.find( '.display' ).next( 'input[name="wpep-selected-amount"]' ).val() == '' || current.find( '.display' ).next( 'input[name="wpep-selected-amount"]' ).val() == undefined) {

				return false;

			} else {

				onGetCardNonce( event, form_id );
			}
		}
	}
);

jQuery( document ).on(
	'click',
	'a[data-dismiss="alert"]',
	function(e){

		e.preventDefault();

		var This = jQuery( this ).parent();

		This.fadeOut( '500' );

		This.remove();

	}
);

jQuery( window ).load(
	function(){

		jQuery( '.free_form_page' ).show();
		// jQuery('form').find('input[type="text"], input[type="email"], input[type="number"], input[type="date"]').attr('autocomplete', 'off');
	}
);



// loading for payment
setInterval(
	function () {

		setTimeout(
			function () {
				jQuery( '.initial-load-animation' ).addClass( 'fade-load' );
			},
			5000
		);

		setTimeout(
			function () {
				jQuery( '.initial-load-animation' ).removeClass( 'fade-load' );
			},
			7500
		);

	},
	7500
);
