<?php

$form_fields = get_post_meta( get_the_ID(), 'wpep_square_form_builder_fields', true );
?>

<main>
	<div class="sectionFold">
		<div id="build-wrap"></div>
	</div>
	<input type="hidden" id="wpep_form_builder_json" name="wpep_square_form_builder_fields"
		   value='<?php 
echo  esc_html( $form_fields ) ;
?>'>
</main>

<style>
	.form-wrap.form-builder .frmb-control li {
		margin: 0px 0px -2px 0;
		padding: 20px;
		/* border-radius: 0px !important; */
		transition: all 0.3s ease;
		box-shadow: inset 0 0 0 1px #ebebeb;
	}
</style>


