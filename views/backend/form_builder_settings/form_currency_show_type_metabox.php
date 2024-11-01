<?php
$currencySymbolType = ! empty( get_post_meta( get_the_ID(), 'currencySymbolType', true ) ) ? get_post_meta( get_the_ID(), 'currencySymbolType', true ) : 'code';
?>

<div class="form-group">
	<label for="code">
		<input type="radio" class="currencySymbolType" name="currencySymbolType" id="code"
			   value="code" 
			   <?php
				if ( $currencySymbolType == 'code' ) :
					echo 'checked';
endif;
				?>
				 > Currency Code (e.x:
		USD)
	</label><br><br>

	<label for="symbol">
		<input type="radio" class="currencySymbolType" name="currencySymbolType" id="symbol"
			   value="symbol" 
			   <?php
				if ( $currencySymbolType == 'symbol' ) :
					echo 'checked';
endif;
				?>
				 > Currency Symbol
		(e.x: $)
	</label>
</div>
