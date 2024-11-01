<?php
// add subscription backend scripts into frontend
add_thickbox();
wp_enqueue_style( 'dashicons' );
wp_enqueue_style( 'wpep_user_subscriptions_style', WPEP_ROOT_URL . 'assets/frontend/css/wpep_user_subscriptions.css' );
wp_enqueue_script(
	'wpep_frontend_subscription_script',
	WPEP_ROOT_URL . 'assets/frontend/js/wpep_subscription_frontend_actions.js',
	array(),
	'3.0.1',
	true
);
wp_localize_script(
	'wpep_frontend_subscription_script',
	'wpep_subscription_vars',
	array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
	)
);
?>
<div class="subscruptionWrap">
	<div class="wpep_overlay_bg">Loading . . .</div>
	<?php do_action( 'before_subscriptions_report' ); ?>
	<!-- <h1>Subscriptions</h1> -->
	<div class="wpep-subscription-tabs">
		<a class="wpep-tab-link wpep-tab-active" data-id="tab-1"><?php echo esc_html__( 'Dashboard', 'wpformify' ); ?></a>
		<a class="wpep-tab-link" data-id="tab-2"><?php echo esc_html__( 'Transaction History', 'wpformify' ); ?></a>
		<a class="wpep-tab-link" data-id="tab-3"><?php echo esc_html__( 'Subscriptions', 'wpformify' ); ?></a>
		<a class="wpep-tab-link" data-id="tab-4"><?php echo esc_html__( 'Saved Cards', 'wpformify' ); ?></a>
	</div>

	<div id="tab-1" class="wpep-subscriptions-content table-responsive tablestyle wpep-subs-active">
		<p><?php echo __( 'You can view all your Payment Transactions, Manage your Subscriptions and Saved Cards from this Dashboard.', 'wpformify' ); ?></p>
	</div>

	<div id="tab-2" class="wpep-subscriptions-content table-responsive tablestyle">
		<?php
		if ( ! empty( $reports ) ) {
			?>
			<table>
				<thead>
				<tr>
					<th><?php echo esc_html__( 'Transaction ID', 'wpformify' ); ?></th>
					<th><?php echo esc_html__( 'Date', 'wpformify' ); ?></th>
					<th><?php echo esc_html__( 'Status', 'wpformify' ); ?></th>
					<th><?php echo esc_html__( 'Total', 'wpformify' ); ?></th>
					<th><?php echo esc_html__( 'Type', 'wpformify' ); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php
				foreach ( $reports as $report ) :

					$reportID       = $report->ID;
					$transaction_id = $report->post_title;
					$postDate       = $report->post_date;
					$status         = get_post_meta( $reportID, 'wpep_transaction_status', true );
					$total          = get_post_meta( $reportID, 'wpep_square_charge_amount', true );
					$type           = get_post_meta( $reportID, 'wpep_transaction_type', true );

					echo "<tr>
						<td>". esc_html( $transaction_id ). "</td>
						<td>". $postDate . "</td>
						<td><span class='". esc_attr( $status ) ."'>". esc_html( $status ) ."</span></td>
						<td>". esc_html( $total ). "</td>
						<td>". esc_html( $type )."</td>
					</tr>";
				endforeach;
				?>
				</tbody>
			</table>
			<?php
		} else {
			echo '<p>' . __( 'No Transaction History', 'wpformify' ) . '</p>';
		}
		?>
	</div>

	<div id="tab-3" class="wpep-subscriptions-content table-responsive tablestyle">
		<?php
		if ( ! empty( $subscriptions ) ) {
			?>
			<table>
				<thead>
				<tr>
					<th><?php echo esc_html__( 'ID', 'wpformify' ); ?></th>
					<th><?php echo esc_html__( 'Paid By', 'wpformify' ); ?></th>
					<th><?php echo esc_html__( 'Subscription Interval', 'wpformify' ); ?></th>
					<th><?php echo esc_html__( 'Remaining Cycles', 'wpformify' ); ?></th>
					<th><?php echo esc_html__( 'Total Cycles', 'wpformify' ); ?></th>
					<th><?php echo esc_html__( 'Next Payment', 'wpformify' ); ?></th>
					<th><?php echo esc_html__( 'Subscription Status', 'wpformify' ); ?></th>
					<th><?php echo esc_html__( 'Type', 'wpformify' ); ?></th>
					<th><?php echo esc_html__( 'Actions', 'wpformify' ); ?></th>
					<th><?php echo esc_html__( 'Date', 'wpformify' ); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php
				foreach ( $subscriptions as $subscription ) :

					$postId   = $subscription->ID;
					$paid_by  = get_post_meta( $postId, 'wpep_first_name', true ) . ' ' . get_post_meta( $postId, 'wpep_last_name', true );
					$interval = get_post_meta( $postId, 'wpep_subscription_interval', true );
					$cycle    = get_post_meta( $postId, 'wpep_subscription_cycle', true );
					$length   = get_post_meta( $postId, 'wpep_subscription_length', true );
					// $email            = get_post_meta( $postId, 'wpep_email', true );
					$remaining_cycles = get_post_meta( $postId, 'wpep_subscription_remaining_cycles', true );
					$next_payment     = get_post_meta( $postId, 'wpep_subscription_next_payment', true );
					$status           = get_post_meta( $postId, 'wpep_subscription_status', true );
					$form_type        = get_post_meta( $postId, 'wpep_form_type', true );

					if ( $length == 0 ) {

						$length = 'Never Expire';
					}

					if ( $remaining_cycles <= 0 ) {

						$remaining_cycles = '-';
					}

					if ( $status == 'Active' ) {

						$status_button = "<input type='button' value='Pause' data-action='Paused' data-subscription='$postId' class='wpep_subscription_action wpep-btn-pending' />";
					}

					if ( $status == 'Paused' ) {
						$status_button = "<input type='button' value='Start' data-action='Active' data-subscription='$postId' class='wpep_subscription_action wpep-btn-start' />";
					}

					if ( $status == 'Completed' ) {
						$status_button = '-';
					}

					$next_payment == '-' ? '-' : date( 'Y-m-d', $next_payment );

					echo '<tr>';
					echo '<td><a title="Subscription #' . esc_html($postId) . '" href="#" class="sub_details" data-id="' . esc_html($postId) . '">' . esc_html($postId) . '</a></td>';
					echo '<td>' . esc_html($paid_by) . '</td>';
					echo '<td>' . esc_html($interval) . '</td>';
					echo '<td>' . esc_html($remaining_cycles) . '</td>';
					echo '<td>' . esc_html($length) . '</td>';
					echo '<td>' . esc_html( date( 'Y-m-d', $next_payment ) ) . '</td>';
					echo '<td class="wpep_sub_status_' . esc_html( $postId ) . '">' . "<span class='$status'>" . esc_html( $status ) . '</span>' . '</td>';
					echo '<td>' . esc_html( $form_type ) . '</td>';
					echo '<td>' . esc_html( $status_button ) . '</td>';
					echo '<td>' . esc_html( $subscription->post_date ) . '</td>';
					echo '</tr>';
				endforeach;
				?>
				</tbody>
			</table>
			<?php
		} else {
			echo '<p>' . __( 'No Subscription History', 'wpformify' ) . '</p>';
		}
		?>
	</div>

	<div id="tab-4" class="wpep-subscriptions-content table-responsive tablestyle">
		<?php
		if ( ! empty( $card_on_files ) ) {
			foreach ( $card_on_files as $cards ) {
				?>
				<div class="credit-card">
					<div class="card_brand">
						<span class="title">Brand</span>
						<span class=""><?php echo esc_html( $cards['card_brand'] ); ?></span>
					</div>
					<div class="card_num">
						<span class="title">Card Number</span>
						<span class="">**** **** **** <?php echo esc_html( $cards['card_last_4'] ); ?></span>
					</div>
					<div class="card_exp">
						<span class="title">Expiry</span>
						<span class=""><?php echo esc_html( $cards['card_exp_month'] ); ?>/<?php echo esc_html( $cards['card_exp_year'] ); ?>  <a href="#" class="wpep_card_delete" data-card-id="<?php echo esc_html( $cards['card_id'] ); ?>"><span class="dashicons dashicons-trash"></span></a></span>
					</div>
				</div>
				<?php
			}
		}
		?>
		<!-- <a href="#" id="wpep_card_add">Add Card</a> -->
	</div>
</div>
<a title="" href="#TB_inline?&width=100%&height=550&inlineId=single-subscription" class="thickbox" id="wpep_thickbox_trigger">Thickbox trigger</a>
<div id="single-subscription" style="display:none;"></div>
