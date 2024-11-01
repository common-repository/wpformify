<p> Sorry, your website is not using HTTPS. Please click the link below to convert your website to HTTPS.</p>

<?php
$site_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>
<a href="<?php echo esc_url( $site_url ); ?>" class="form-control"> Convert to HTTPS (Secure)</a>
