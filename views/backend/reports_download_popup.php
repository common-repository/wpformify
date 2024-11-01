<?php // silence is golden

?>
<div id="wpep-reports-popup-container" style="display:none;">
	<div class="wpep-reports-popup-overlay">&nbsp;</div>
	<div id="wpep-reports-popup">
		<div id="wpep-reports-content">
			<form action=""  method="POST" id="formSelector">
				<div class="wpep-reports-header">
					<h3><?php _e( 'Download Transaction Details', 'wpformify' ); ?></h3>
						<a href="#" class="wpep-reports-close">x</a>
				</div>
				<div class="wpep-reports-body">
					<ul class="wpep-reports-list">
						<li class="firstLi"><label for="checkAll"><input type="checkbox" id="checkAll"><?php echo __( 'Check All', 'wpformify' ); ?></label></li>
						<li><label for="1"><input id="1" type="checkbox" name="wpep_reports_export_fields" value="First_Name"> <?php echo __( 'First Name', 'wpformify' ); ?></li>
						<li><label for="2"><input id="2" type="checkbox" name="wpep_reports_export_fields" value="Last_Name"> <?php echo __( 'Last Name', 'wpformify' ); ?></li>
						<li><label for="3"><input id="3" type="checkbox" name="wpep_reports_export_fields" value="Email_Address"> <?php echo __( 'Email Address', 'wpformify' ); ?></li>
						<li><label for="4"><input id="4" type="checkbox" name="wpep_reports_export_fields" value="Transaction_type"> <?php echo __( 'Transaction type', 'wpformify' ); ?></li>
						<li><label for="5"><input id="5" type="checkbox" name="wpep_reports_export_fields" value="Transaction_ID"> <?php echo __( 'Transaction ID', 'wpformify' ); ?></li>
						<li><label for="6"><input id="6" type="checkbox" name="wpep_reports_export_fields" value="Refund_ID"> <?php echo __( 'Refund ID', 'wpformify' ); ?></li>
						<li><label for="7"><input id="7" type="checkbox" name="wpep_reports_export_fields" value="Charge_Amount"> <?php echo __( 'Charge Amount', 'wpformify' ); ?></li>
						<li><label for="8"><input id="8" type="checkbox" name="wpep_reports_export_fields" value="Transaction_Status"> <?php echo __( 'Transaction Status', 'wpformify' ); ?></li>
						<li><label for="9"><input id="9" type="checkbox" name="wpep_reports_export_fields" value="Form_ID"> <?php echo __( 'Form ID', 'wpformify' ); ?></li>
					</ul>
				</div>
				<div class="wpep-reports-footer">
					<button id="wpep-download-now" class="button button-primary"><?php _e( 'Download Now', 'wpformify' ); ?></button>
				</div>
			</form>
		</div>
	</div>
</div>
