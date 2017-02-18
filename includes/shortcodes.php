<?php

add_shortcode( 'buddyforms_easypin', 'buddyforms_easypin' );

function buddyforms_easypin($shortcode_args){

	extract( shortcode_atts( array(
		'post_parent' => 0,
	), $shortcode_args ) );

	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_parent ), "full");

	ob_start(); ?>
    <script>
        jQuery(document).ready(function () {

            var $instance = jQuery('.buddyforms-pin').easypin({
                init: '{"example_image11":{"0":{"content":"Captan America","coords":{"lat":"200","long":"189"}},"canvas":{"width":"auto","height":"auto"}}}',
                modalWidth: 300,
                limit: 1,
                exceeded: function(element) {
                    alert('You only able to create one pin at the time ;)');
                },
                responsive: true,
                done: function(element) {
                },
                popover: {
                    show: true,
                },
                drop: function(x, y, element) {

                },
                drag: function(x, y, element) {
                    easypin_get_corts(x, y, element);
                }
            });

            $instance.easypinShow({
                responsive: true
            });

            // set the 'get.coordinates' event
            $instance.easypin.event( "get.coordinates", function($instance, data, params ) {
                return data;
            });

            function easypin_get_corts(x, y, element) {
                $instance.easypin.fire( "get.coordinates", {param1: 1, param2: 2, param3: 3}, function(data) {
                    $cords = JSON.stringify(data);
                });
                jQuery('input[name="easypin"]').val($cords);
                return false;
            }

        });
    </script>
    <input name="easypin" type="hidden" value="">

    <img src="<?php echo $image[0]; ?>" class="buddyforms-pin" width="auto" easypin-id="example_image11" />
    <a href="#" class="coords" >Get coordinates!</a>
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