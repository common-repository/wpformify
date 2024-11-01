<?php

	$current_post_id = get_the_ID();

	$firstname              = get_post_meta( $current_post_id, 'wpep_first_name', true );
	$lastname               = get_post_meta( $current_post_id, 'wpep_last_name', true );
	$email                  = get_post_meta( $current_post_id, 'wpep_email', true );
	$charge_amount          = get_post_meta( $current_post_id, 'wpep_square_charge_amount', true );
	$discount_amount        = get_post_meta( $current_post_id, 'wpep_square_discount', true );
	$taxes                  = get_post_meta( $current_post_id, 'wpep_square_taxes', true );
	$transaction_status     = get_post_meta( $current_post_id, 'wpep_transaction_status', true );
	$transaction_id         = get_the_title( $current_post_id );
	$transaction_type       = get_post_meta( $current_post_id, 'wpep_transaction_type', true );
	$form_id                = get_post_meta( $current_post_id, 'wpep_form_id', true );
	$form_values            = get_post_meta( $current_post_id, 'wpep_form_values', true );
	$wpep_transaction_error = get_post_meta( $current_post_id, 'wpep_transaction_error', true );
	$wpep_refund_id         = get_post_meta( $current_post_id, 'wpep_square_refund_id', true );

?>

<div class="reportDetailsContainer">
  <div class="reportDetails">
	  <h3>Payment Details</h3>
	  <table>
		<tbody>
		<!-- <tr>
			<th>Refund Now</th>
			<td>
			<?php
			/*
			if ( false !== $wpep_refund_id && isset( $wpep_refund_id ) && ! empty( $wpep_refund_id ) ) {
				echo '<button disabled> Refunded </button>';
			} else {
				echo '<button class="give_refund_button" data-postid="' . $current_post_id . '" data-amount="' . $charge_amount . '" data-transactionid="' . $transaction_id . '"> Refund </button>';
			} */
			?>
				</td>
		</tr> -->

		  <tr>
			<th>Payment type</th>
			<td><?php echo esc_html( $transaction_type ); ?></td>
		  </tr>
		  <tr>
			<th>Transaction ID</th>
			<td><?php echo esc_html( get_the_title() ); ?></td>
		  </tr>
	  
		  <tr>
			<th>Payments Amount</th>
			<td><?php echo esc_html( $charge_amount ); ?></td>
		  </tr>

		  <?php
			if ( ! empty( $taxes ) ) {
				foreach ( $taxes['name'] as $key => $fees ) {
					$fees_check  = isset( $taxes['check'][ $key ] ) ? $taxes['check'][ $key ] : 'no';
					$fees_name   = isset( $taxes['name'][ $key ] ) ? $taxes['name'][ $key ] : '';
					$fees_value  = isset( $taxes['value'][ $key ] ) ? $taxes['value'][ $key ] : '';
					$charge_type = isset( $taxes['type'][ $key ] ) ? $taxes['type'][ $key ] : '';

					if ( 'yes' === $fees_check ) {

						if ( 'percentage' == $charge_type ) {
							$charge_type = '%';
						} else {
							$charge_type = 'fixed';
						}

						?>
					<tr>
						<th><?php echo esc_html( $fees_name ); ?></th>
						<td><?php echo esc_html( $fees_value ) . ' <small>(' . esc_html( $charge_type ) . ')</small>'; ?></td>
					</tr>
						<?php
					}
				}
			}
			?>
		
		  <tr>
			<th>Discount</th>
			<td><?php echo esc_html( $discount_amount ); ?></td>
		  </tr>

		  <tr>
			<th>Payments Status</th>
			<td><?php echo esc_html( $transaction_status ); ?></td>
		  </tr>
	  

		  <?php
			if ( isset( $wpep_transaction_error ) && ! empty( $wpep_transaction_error ) ) {
				?>
		  <tr>
			<th>Payment Error</th>
			<td><?php print_r( $wpep_transaction_error ); ?></td>
		  </tr>
				<?php
			}
			?>

		  <tr>
			<th>WP Formify Form</th>
			<td><a  target="_blank" href="<?php echo esc_url( get_edit_post_link( $form_id ) ); ?>"> click here </a></td>
		  </tr>

		  <tr>
			<th>User Name</th>
			<td><?php echo esc_html( $firstname ) . ' ' . esc_html( $lastname ); ?></td>
		  </tr>
		  
		  <tr>
			<th>User Email</th>
			<td><?php echo esc_html( $email ); ?></td>
		  </tr>

		  <tr>
			  <th>Refund ID</th>
			  <td><?php echo esc_html( $wpep_refund_id ); ?></td>
		  </tr>
		</tbody>
	  </table>
	</div>


  <?php

	if ( isset( $form_values ) && ! empty( $form_values ) ) {

		echo '<div class="reportDetails">
    <h3>Form Field</h3>
    <table>
      <tbody>';

		foreach ( $form_values as $key => $value ) {

			echo '<tr>';
			if ( isset( $value['label'] ) ) {

				$label = ucfirst( str_replace( '_', ' ', $value['label'] ) );

				echo '<th scope="col">' . esc_html( $label ) . '</th>';

			}

			


			if ( isset( $value['label'] ) ) {

				if ( 'Uploaded File URL' == $value['label'] ) {
					$uploaded_file_link = "<a target='_blank' href='" . esc_url($value['value']) . "'> Click to see uploaded file </a>";
					echo '<td scope="col">' .  $uploaded_file_link . '</td>';
				} elseif ( 'Products Data' == $value['label'] ) {
					$json = json_decode( $value['value'], true );
					echo '<td scope="col">';
					echo '<ol>';
					foreach ( $json as $key => $value ) {
						echo '<li class="prodData"> Label: ' . esc_html( $value['label'] ) . ' Quantity: ' . esc_html( $value['quantity'] ) . ' Price: ' . esc_html( $value['price'] ) . ' Cost: ' . esc_html( $value['cost'] ) . '</div>';
					}
					echo '</ol>';
					echo '</td>';
				} else {
					if ( isset( $value['value'] ) ) {
						echo '<td scope="col">' . esc_html( $value['value'] ) . '</td>';
					}
				}
			}


			echo '</tr>';


		}

		echo '</tbody>
    </table>
  </div>';
	}
	?>
  </div>
