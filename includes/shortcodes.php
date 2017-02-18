<?php

add_shortcode( 'buddyforms_easypin', 'buddyforms_easypin' );

function buddyforms_easypin(){

    ob_start(); ?>
	<script>
        jQuery(document).ready(function () {

            console.log(jquery_easypin_data);

            var $instance = jQuery('.pin').easypin({
                done: function(element) {
                    return true;
                }
            });

	        // set the 'get.coordinates' event
            $instance.easypin.event( "get.coordinates", function($instance, data, params ) {

                console.log(data, params);

            });

            jQuery( ".coords" ).click(function(e) {
                $instance.easypin.fire( "get.coordinates", {param1: 1, param2: 2, param3: 3}, function(data) {
                    return JSON.stringify(data);
                });
            });

        });

	</script>
	<img src="https://ps.w.org/buddyforms/assets/screenshot-1.png?rev=1525886" class="pin" width="800" easypin-id="example_image1" />
    <input class="coords" type="button" value="Get coordinates!" />
	<div class="easy-modal" style="display:none;" modal-position="free">
		<form>
			type something: <input name="content" type="text">
			<input type="button" value="save pin!" class="easy-submit">
		</form>
	</div>
	<div style="display:none;" width="130" shadow="true" popover>
		<div style="width:100%;text-align:center;">{[content]}</div>
	</div>
	<?php
    $easypin = ob_get_clean();
	return $easypin;
}