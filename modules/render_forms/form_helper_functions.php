<?php

function wpep_print_checkbox_group( $checkbox_group ) {

	$ifRequired = " <span class='fieldReq'>*</span>";
	echo "<label data-label-show='" . esc_html( $checkbox_group->hideLabel ) . "'>". esc_html($checkbox_group->label). "
	" . ( ( isset( $checkbox_group->required ) ) ? $ifRequired : '' ) . '</label>';
	echo "<div class='wpep-checkboxWrapper'>";
	foreach ( $checkbox_group->values as $value ) {
		echo "<div class='wizard-form-checkbox " . ( ( isset( $checkbox_group->required ) ) ? 'wpep-required' : '' ) . "'><div class='form-group wpep-m-0'><input type='checkbox' name='" . esc_html( $checkbox_group->name ) . "' data-label='" . esc_html( $value->label ) . "' data-main-label='" . esc_html( $checkbox_group->label ) . "'  id='radio_id_" . esc_html( $value->value ) . "' value='" . esc_html( $value->value ) . "' required='" . ( ( isset( $checkbox_group->required ) ) ? 'true' : 'false' ) . "'><label for='radio_id_" . esc_html( $value->value ) . "'>" . esc_html($value->label). "</label></div></div>";
	}
	if ( isset( $checkbox_group->description ) && $checkbox_group->description != '' ) {
		echo "<span class='wpep-help-text'>" . esc_html( $checkbox_group->description ) . '</span>';
	}
	echo '</div>';

}

function wpep_print_radio_group( $radio_group ) {

	$ifRequired = " <span class='fieldReq'>*</span>";
	echo "<label data-label-show='" . esc_html( $radio_group->hideLabel ) . "'> ". esc_html( $radio_group->label ) ." 
	" . ( ( isset( $radio_group->required ) ) ? $ifRequired : '' ) . '</label>';
	echo "<div class='wpep-radioWrapper'>";
	foreach ( $radio_group->values as $value ) {
		echo "<div class='wizard-form-radio " . ( ( isset( $radio_group->required ) ) ? 'wpep-required' : '' ) . "'><div class='form-group wpep-m-0'><input type='radio' name='" . esc_html( $radio_group->name ) . "' id='radio_id_" . esc_html( $value->value ) . "' data-label='" . esc_html( $value->label ) . "' data-main-label='" . esc_html( $radio_group->label ) . "' value='" . esc_html( $value->value ) . "' required='" . ( ( isset( $radio_group->required ) ) ? 'true' : 'false' ) . "'><label for='radio_id_" . esc_html( $value->value ) . "'> ". esc_html( $value->label ) . "</label></div></div>";
	}
	if ( isset( $radio_group->description ) && $radio_group->description != '' ) {
		echo "<span class='wpep-help-text'>" . esc_html( $radio_group->description ) . '</span>';
	}
	echo '</div>';

}

function wpep_print_select_dropdown( $select_dropdown ) {
	
	$ifRequired = " <span class='fieldReq'>*</span>";
	echo "<label data-label-show='" . esc_html( $select_dropdown->hideLabel ) . "'>" . esc_html( $select_dropdown->label ) ."
	" . ( ( isset( $select_dropdown->required ) ) ? $ifRequired : '' ) . '</label>';

	echo "<div class='form-group " . ( ( isset( $select_dropdown->required ) ) ? 'wpep-required' : '' ) . "'><select data-label='" . esc_html( $select_dropdown->label ) . "' class='" . esc_html( $select_dropdown->className ) . "' name='" . esc_html( $select_dropdown->name ) . "' " . ( isset( $select_dropdown->multiple ) ? 'multiple style="height:auto;"' : '' ) . "  required='" . ( ( isset( $select_dropdown->required ) ) ? 'true' : 'false' ) . "'>";

	foreach ( $select_dropdown->values as $value ) {
		echo "<option value='" . esc_html( $value->value ) . "'>" . esc_html( $value->label ) . '</option>';
	}

	echo '</select>';
	if ( isset( $select_dropdown->description ) && $select_dropdown->description != '' ) {
		echo "<span class='wpep-help-text'>" . esc_html( $select_dropdown->description ) . '</span>';
	}
	echo '</div>';

}

function wpep_print_textarea( $textarea ) {
	$label       = isset( $textarea->label ) ? $textarea->label : '';
	$placeholder = isset( $textarea->placeholder ) ? $textarea->placeholder : 'Text Area';
	$classname   = isset( $textarea->className ) ? $textarea->className : '';
	$value       = isset( $textarea->value ) ? $textarea->value : '';
	$name        = isset( $textarea->name ) ? $textarea->name : '';
	$required    = isset( $textarea->required ) ? 'true' : 'false';
	$ifRequired  = " <span class='fieldReq'>*</span>";
	if ( 'true' == $required ) {
		echo '<div class="form-group text-field wpep-required">
		<label class="wizard-form-text-label"> ' . ( ( isset( $label ) ) ? $label : '' ) . $ifRequired . '</label><textarea rows="6" data-label="' . esc_html( $label ) . '" name="' . esc_html( $name ) . '" placeholder="' . esc_html( $placeholder ) . '" class="' . esc_attr( $classname ) . ' form-control" rows="4" cols="100" required="' . esc_html( $required ) . '">' . esc_html( $value ) . '</textarea></div>';
	} else {
		echo '<div class="form-group text-field"><label class="wizard-form-text-label"> ' . ( ( isset( $label ) ) ? $label : '' ) . ' </label><textarea rows="6" data-label="' . esc_attr( $label ) . '" name="' . esc_attr( $name ) . '" placeholder="' . esc_html( $placeholder ) . '" class="' . esc_attr( $classname ) . ' form-control" rows="4" cols="100" required="' . esc_html( $required ) . '">' . esc_html( $value ) . '</textarea></div>';
	}
}

function wpep_print_credit_card_fields( $current_form_id ) {
	ob_start();
	?>

	<div id="form-container">

		<div class="form-group form-control-wrap cred-card-wrap">
			<div class="CardIcon">
			</div>
			<div style="padding:10px;" id="card-element-<?php echo esc_html( $current_form_id ); ?>" ></div>
			<p id="card-error-<?php echo esc_html( $current_form_id ); ?>" role="alert"></p>
		</div>

	</div>

	<?php
	ob_end_flush();
}

function wpep_print_file_upload( $file_upload ) {
	$ifRequired = " <span class='fieldReq'>*</span>";
	echo '<label class="labelupload">' . esc_html( $file_upload->label ) ( ( isset( $file_upload->required ) ) ? esc_html( $ifRequired ) : '' ) . '</label>';
	echo "<div class='form-group file-upload-wrapper' data-text='Select your file!'><input accept='.gif, .jpg, .png, .doc, .pdf' type='". esc_attr( $file_upload->type ) ."'name='" . esc_attr( $file_upload->name ) ."'id='wpep_file_upload_field' class='file-upload-field" . esc_attr( $file_upload->className ) . "'></div>";
}
