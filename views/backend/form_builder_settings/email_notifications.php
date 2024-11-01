<?php

$wpep_square_admin_email_to_field = get_post_meta( get_the_ID(), 'wpep_square_admin_email_to_field', true );

if ( empty($wpep_square_admin_email_to_field) ) {
    $current_user = wp_get_current_user();
    $wpep_square_admin_email_to_field = $current_user->user_email;
}

$wpep_square_admin_email_cc_field = get_post_meta( get_the_ID(), 'wpep_square_admin_email_cc_field', true );
$wpep_square_admin_email_bcc_field = get_post_meta( get_the_ID(), 'wpep_square_admin_email_bcc_field', true );
$wpep_square_admin_email_from_field = get_post_meta( get_the_ID(), 'wpep_square_admin_email_from_field', true );
$wpep_square_admin_email_subject_field = get_post_meta( get_the_ID(), 'wpep_square_admin_email_subject_field', true );
$wpep_square_admin_email_content_field = get_post_meta( get_the_ID(), 'wpep_square_admin_email_content_field', true );
$wpep_square_admin_email_exclude_blank_tags_lines = get_post_meta( get_the_ID(), 'wpep_square_admin_email_exclude_blank_tags_lines', true );
$wpep_square_admin_email_content_type_html = get_post_meta( get_the_ID(), 'wpep_square_admin_email_content_type_html', true );
$wpep_square_user_email_to_field = get_post_meta( get_the_ID(), 'wpep_square_user_email_to_field', true );
$wpep_square_user_email_cc_field = get_post_meta( get_the_ID(), 'wpep_square_user_email_cc_field', true );
$wpep_square_user_email_bcc_field = get_post_meta( get_the_ID(), 'wpep_square_user_email_bcc_field', true );
$wpep_square_user_email_from_field = get_post_meta( get_the_ID(), 'wpep_square_user_email_from_field', true );
$wpep_square_user_email_subject_field = get_post_meta( get_the_ID(), 'wpep_square_user_email_subject_field', true );
$wpep_square_user_email_content_field = get_post_meta( get_the_ID(), 'wpep_square_user_email_content_field', true );
$wpep_square_user_email_exclude_blank_tags_lines = get_post_meta( get_the_ID(), 'wpep_square_user_email_exclude_blank_tags_lines', true );
$wpep_square_user_email_content_type_html = get_post_meta( get_the_ID(), 'wpep_square_user_email_content_type_html', true );
?>

<main>
	<div class="notificationsWrap clearfix">

		<?php 
?>

	</div>
	<div class="clearfix">
		<ul class="accordion">
			<li>
				<a class="toggle" href="#">Admin Email Template
					<i class="fa fa-chevron-right" aria-hidden="true"></i></a>
				<div class="inner">
					<div class="form-group">
						<label>To:</label>
						<input type="text" class="form-control" value="<?php 
echo  esc_html( $wpep_square_admin_email_to_field ) ;
?>"
							   name="wpep_square_admin_email_to_field" placeholder="examaple@mail.com"/>
					</div>

					<div class="form-group">
						<label>CC:</label>
						<input type="text" class="form-control" value="<?php 
echo  esc_html( $wpep_square_admin_email_cc_field ) ;
?>"
							   name="wpep_square_admin_email_cc_field" placeholder="examaple@mail.com"/>

					</div>

					<div class="form-group">
						<label>BCC:</label>
						<input type="text" class="form-control"
							   value="<?php 
echo  esc_html( $wpep_square_admin_email_bcc_field ) ;
?>"
							   name="wpep_square_admin_email_bcc_field" placeholder="examaple@mail.com"/>

					</div>

					<div class="form-group">
						<label>From:</label>
						<input type="text" class="form-control"
							   value="<?php 
echo  esc_html( $wpep_square_admin_email_from_field ) ;
?>"
							   name="wpep_square_admin_email_from_field" placeholder="example@mail.com"/>

					</div>

					<div class="form-group">
						<label>Subject:</label>
						<input type="text" class="form-control"
							   value="<?php 
echo  esc_html( $wpep_square_admin_email_subject_field ) ;
?>"
							   name="wpep_square_admin_email_subject_field" placeholder="Please Enter subject"/>

					</div>

					<div class="form-group">
						<label>Message Body:</label>
						<textarea type="text" class="form-control form-control-longtext"
								  placeholder="Please Enter subject" name="wpep_square_admin_email_content_field"
								  style="line-height:1.5"><?php 
echo  esc_html( $wpep_square_admin_email_content_field ) ;
?></textarea>

					</div>

					<div class="form-group">
						<label for="exclude">
							<input type="checkbox" id="exclude"
								   name="wpep_square_admin_email_exclude_blank_tags_lines" 
								   <?php 
if ( $wpep_square_admin_email_exclude_blank_tags_lines == 'on' ) {
    echo  'checked' ;
}
?>
							>
							Exclude lines with blank mail-tags from output
						</label>
						<br/>
						<label for="htmltype">
							<input type="checkbox" id="htmltype"
								   value="<?php 
echo  ( !empty($wpep_square_admin_email_content_type_html) ? $wpep_square_admin_email_content_type_html : 'on' ) ;
?>"
								   name="wpep_square_admin_email_content_type_html" 
								   <?php 
if ( $wpep_square_admin_email_content_type_html == 'on' ) {
    echo  'checked' ;
}
?>
							>
							Use HTML content type
						</label>
					</div>
				</div>
			</li>

			<li>
				<a class="toggle" href="#">User Email Template
					<i class="fa fa-chevron-right" aria-hidden="true"></i></a>
				<div class="inner">
					<div class="form-group">
						<label>From:</label>
						<input type="text" class="form-control"
							   value="<?php 
echo  esc_html( $wpep_square_user_email_from_field ) ;
?>"
							   name="wpep_square_user_email_from_field" placeholder="example@mail.com"/>

					</div>

					<div class="form-group">
						<label>Subject:</label>
						<input type="text" class="form-control"
							   value="<?php 
echo  esc_html( $wpep_square_user_email_subject_field ) ;
?>"
							   name="wpep_square_user_email_subject_field" placeholder="Please Enter subject"/>

					</div>

					<div class="form-group">
						<label>Message Body:</label>
						<textarea type="text" class="form-control form-control-longtext"
								  placeholder="Please Enter subject" name="wpep_square_user_email_content_field"
								  style="line-height:1.5"> <?php 
echo  esc_html( $wpep_square_user_email_content_field ) ;
?> </textarea>

					</div>

					<div class="form-group">
						<label for="exclude">
							<input type="checkbox" name="wpep_square_user_email_exclude_blank_tags_lines"
								   id="exclude" 
								   <?php 
if ( $wpep_square_user_email_exclude_blank_tags_lines == 'on' ) {
    echo  'checked' ;
}
?>
							>
							Exclude lines with blank mail-tags from output
						</label>
						<br/>
						<label for="htmltype">
							<input type="checkbox" name="wpep_square_user_email_content_type_html"
								   id="htmltype" 
								   <?php 
if ( $wpep_square_user_email_content_type_html == 'on' ) {
    echo  'checked' ;
}
?>
							>
							Use HTML content type
						</label>
					</div>
				</div>
			</li>
		</ul>
	</div>
</main>
