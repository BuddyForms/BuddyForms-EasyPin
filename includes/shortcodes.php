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
//                init: '{"example_image11":{"0":{"coords":{"lat":"200","long":"189"}},"canvas":{"width":"auto","height":"auto"}}}',
                limit: 1,
                exceeded: function(element) {
                    alert('You only able to create one pin at the time ;)');
                },
                responsive: true,
                popover: {
                    show: true,
                },
                drop: function(x, y, element) {
                    console.log(x, y);
                    console.log(element);
                },
                drag: function(x, y, element) {
                    console.log(x, y);
                    easypin_get_corts(x, y);
                }
            });

            $instance.easypinShow({
                responsive: true
            });

            // set the 'get.coordinates' event
            $instance.easypin.event( "get.coordinates", function($instance, data, params ) {
                return data;
            });

            function easypin_get_corts(x, y) {
                $instance.easypin.fire( "get.coordinates", {param1: 1, param2: 2}, function(data) {
                    $cords = JSON.stringify(data);
                });
                jQuery('input[name="easypin"]').val($cords);
                return false;
            }

        });
    </script>
    <input name="easypin" type="hidden" value="">

    <img src="<?php echo $image[0]; ?>" class="buddyforms-pin" width="auto" easypin-id="example_image11" />
	<div style="display:none;" popover></div>
	<?php
    $easypin = ob_get_clean();
	return $easypin;
}