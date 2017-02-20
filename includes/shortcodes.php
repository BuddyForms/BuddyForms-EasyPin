<?php

add_shortcode( 'buddyforms_easypin', 'buddyforms_easypin' );

function buddyforms_easypin($shortcode_args){
	global $wp_query;
	extract( shortcode_atts( array(
		'post_parent' => 0,
		'post_id' => 0,
	), $shortcode_args ) );

	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_parent ), "full");

	$form_slug = $wp_query->query_vars['bf_form_slug'];

	$easypin_post = get_post_meta( $post_id, 'buddyforms_easypin_post', true );


	print_r($easypin_post);


	ob_start(); ?>
    <script>
        jQuery(document).ready(function () {



            var $instance = jQuery('.buddyforms-pin').easypin({
                <?php if( isset($easypin_post['lat']) ){ ?>
                init: '{"<?php echo $post_parent ?>":{"0":{"coords":{"lat":"<?php echo $easypin_post['lat'] ?>","long":"<?php echo $easypin_post['long'] ?>"}},"canvas":{"src":"img/2011-12-18-23.51.12.jpg","width":"auto","height":"auto"}}}',
                <?php } ?>
                limit: 1,
                exceeded: function(element) {
                    alert('You only able to create one pin at the time ;)');
                },
//                responsive: true,
                popover: {
                    show: true,
                },
                drop: function(long, lat, element) {
                    console.log(long, lat);
                    easypin_set_corts(long, lat);

                },
                drag: function(long, lat, element) {
                    easypin_set_corts(long, lat);
                }
            });

            jQuery('.buddyforms-pin').easypinShow({
//                resonsive: true,
	            <?php if( isset($easypin_post['lat']) ){ ?>
                data: '{"<?php echo $post_parent ?>":{"0":{"coords":{"lat":"<?php echo $easypin_post['lat'] ?>","long":"<?php echo $easypin_post['long'] ?>"}},"canvas":{"src":"img/2011-12-18-23.51.12.jpg","width":"<?php echo $easypin_post['width'] ?>","height":"<?php echo $easypin_post['height'] ?>"}}}'
	            <?php } ?>

            });

            jQuery(".pinCanvas").remove();
            jQuery(".popover").remove();

            function easypin_set_corts(long, lat) {

                console.log('long ' + long, 'lat ' + lat);

                var image = jQuery('.buddyforms-pin');
                var ep_id = image.attr('easypin-id');

                var width  = image.width();
                var height = image.height();

//                console.log('{"' + ep_id + '":{"0":{"coords":{"lat":"' + lat + '","long":"' + long + '"}},"canvas":{"width":"' + width + '","height":"' + height + '"}}}');

                jQuery('input[name="easypin-id"]').val(ep_id);
                jQuery('input[name="easypin-long"]').val(long);
                jQuery('input[name="easypin-lat"]').val(lat);
                jQuery('input[name="easypin-width"]').val(width);
                jQuery('input[name="easypin-height"]').val(height);

                return false;
            }

            jQuery(document).on('click', '.easy-delete', function () {

                jQuery('input[name="easypin-id"]').val('');
                jQuery('input[name="easypin-long"]').val('');
                jQuery('input[name="easypin-lat"]').val('');
                jQuery('input[name="easypin-width"]').val('');
                jQuery('input[name="easypin-height"]').val('');

            });

        });
    </script>

    <input name="easypin-id" type="hidden" value="">
    <input name="easypin-long" type="hidden" value="">
    <input name="easypin-lat" type="hidden" value="">
    <input name="easypin-width" type="hidden" value="">
    <input name="easypin-height" type="hidden" value="">

    <img src="<?php echo $image[0]; ?>" class="buddyforms-pin" width="auto" easypin-id="<?php echo $post_parent ?>" />
	<div style="display:none;" popover></div>
	<?php
    $easypin = ob_get_clean();
	return $easypin;
}