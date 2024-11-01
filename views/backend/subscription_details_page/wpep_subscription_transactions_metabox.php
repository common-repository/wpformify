<?php
	$args = array(

		'post_type'       => 'wpstp_subscriptions',
		'post_status'     => 'publish',
		'posts_per_pages' => -1,

	);

	$subscriptions = new wp_Query( $args );

	foreach ( $subscriptions->posts as $post ) {
		if ( $post->ID == get_the_ID() ) {
			$access_token = get_option( 'wpf_test_access_token' );
			$stripe       = new \Stripe\StripeClient( $access_token );
			$response     = $stripe->invoices->all( array( 'subscription' => $post->post_title ) );

		}
	}

	?>


<div class="wpep_subscription_details_page wpep_container">

	<div class="wpep_row">

		<div class="wpep_col-12">
			
			<table class="wp-list-table widefat fixed striped wpep_table_muf">
				<thead>
					<tr>
						<th class="manage-column">Transaction ID</th>
						<th class="manage-column">Date</th>
						<th class="manage-column">Status</th>
						<th class="manage-column">Total</th>
					</tr>
				</thead>

				<tbody>

				<?php

				$count = 1;
				foreach ( $response->data as $value ) {

							echo '<tr>';
							echo "<td><a href='#'>#" . $count . '</a></td>';
							echo '<td>' . date( 'Y-m-d H:i:s', substr( $value->created, 0, 10 ) ) . '</td>';
							echo '<td><span class="wpep_success_text">' . $value->status . '</span></td>';
							echo '<td>' . $value->subtotal / 100 . ' ' . strtoupper( $value->currency ) . '</td>';
							echo '</tr>';


						$count++;
				}

				?>

				</tbody>
			</table>

		</div>

	</div>

</div>
