jQuery( '.wpep_subscription_action' ).click(
	function() {

		var subscription_action = jQuery( this ).data( 'action' );
		var subscription_id     = jQuery( this ).data( 'subscription' );
		var form_id 			= jQuery( this ).data( 'form' );

		var data = {

			'action': 'wpstp_subscription_action_update',
			'subscription_action': subscription_action,
			'subscription_id': subscription_id,
			'form_id': form_id

		};

		jQuery.post(
			ajaxurl,
			data,
			function(response) {

				if (response == 'success') {

					location.reload();

				}

			}
		);

	}
);

jQuery( document ).ready(
	function(){

		jQuery( '#fetchSubTransactions' ).click(
			function(){

				var subscription_id = jQuery( this ).data( 'subid' );

				var data = {
					'action': 'wpf_fetch_subscription',
					'sub_id': subscription_id
				};

				jQuery.post(
					subscription_elements.ajax_url,
					data,
					function(response) {
						alert( 'Got this from the server: ' + response );
					}
				);

				return false;
			}
		);

	}
);
