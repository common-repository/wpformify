<?php

if ( isset( $_POST ) && ! empty( $_POST ) ) {

	if ( isset( $_POST['wpep_switch_to_new'] ) && $_POST['wpep_switch_to_new'] == 'on' ) {

		update_option( 'wpep_switch_to_new', 'off' );
		$revert_reason = sanitize_text_field( $_POST['revert_back_reason'] );
		update_option( 'revert_back_reason', $revert_reason );


			$url = 'https://connect.apiexperts.io/';

			$response = wp_remote_post(
				$url,
				array(
					'method'      => 'POST',
					'timeout'     => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array(),
					'body'        => array(
						'site_url'           => site_url(),
						'rollback'           => 1,
						'switch_to_new'      => 0,
						'current_version'    => 'OLD',
						'reason'             => $revert_reason,
						'wpep_tracking_data' => 1,
					),
					'cookies'     => array(),
				)
			);




		?>
			<script type="text/javascript">
				document.location.href="<?php echo admin_url( '/' ); ?>";

			</script>
		<?php

	}
}

$revert_to_old = get_option( 'wpep_switch_to_new', true );

?>
<div class="wpep_rollback_page wpep_container">

		<div class="wpep_row">

			<div class="wpep_col-12">
				
				<h3 class="title">WP Easy Pay 3.0</h3>

				<div class="sep25px">&nbsp;</div>

			</div>

		</div>
	
	<div class="wpep_row">

		<div class="wpep_col-12 rollbackplugin">

			<form action="#" method="POST" class="wpep-form" enctype="multipart/form-data">

				<strong>If you have a moment, please let us know why you are deactivating:</strong><br>

				<ul class="rbr-reason-list">
					<li>
						<input type="radio" name="revert_back_reason" value="I no longer need the plugin" id="rbr-1">
						<label for="rbr-1">I no longer need the plugin</label>
					</li>
					<li>
						<input type="radio" name="revert_back_reason" value="The plugin broke my site" id="rbr-2">
						<label for="rbr-2">The plugin broke my site</label>
					</li>
					<li>
						<input type="radio" name="revert_back_reason" value="The plugin suddenly stopped working" id="rbr-3">
						<label for="rbr-3">The plugin suddenly stopped working</label>
					</li>
					<li>
						<input type="radio" name="revert_back_reason" value="I can't pay it anymore" id="rbr-4">
						<label for="rbr-4">I can't pay it anymore</label>
					</li>
					<li>
						<input type="radio" name="revert_back_reason" value="I found a better plugin" id="rbr-5">
						<label for="rbr-5">I found a better plugin</label>
					</li>
					<li>
						<input type="radio" name="revert_back_reason" value="I only needed the plugin for a short period" id="rbr-6">
						<label for="rbr-6">I only needed the plugin for a short period</label>
					</li>
					<li>
						<input type="radio" name="revert_back_reason" value="I only needed the plugin for a short period" id="rbr-7">
						<label for="rbr-7">I only needed the plugin for a short period</label>
					</li>
					<li>
						<input type="radio" name="revert_back_reason" value="" id="rbr-8">
						<label for="rbr-8">Other</label>
					</li>
				</ul>
				<textarea name="rbr-8-other" id="rbr-8-other" class="wpep-form-control"></textarea>

				<div class="wpep-form-group switchWrap">

					<input type="checkbox" id="revert" name="wpep_switch_to_new">

					<label for="revert">Do you really want to switch back to old <strong>version 2.6.7</strong></label>

				</div>

				<input type="submit" name="wpep_revert_back_submit" class="wpep-btn wpep-btn-primary wpep-btn-square wpep-disabled" value="Switch to v2.6.7">


			</form>

		</div>

	</div>

</div>

