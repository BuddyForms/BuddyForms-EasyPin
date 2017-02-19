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

                <?php if( isset($easypin_post['x']) ){ ?>
                    init: '{"<?php echo $post_parent ?>":{"0":{"coords":{"lat":"<?php echo $easypin_post['x'] ?>","long":"<?php echo $easypin_post['y'] ?>"}},"canvas":{"width":"<?php echo $easypin_post['w'] ?>","height":"<?php echo $easypin_post['h'] ?>"}}}',
                <?php } ?>

                limit: 1,
                exceeded: function(element) {
                    alert('You only able to create one pin at the time ;)');
                },
                responsive: true,
                popover: {
                    show: true,
                },
                drop: function(x, y, element) {
                    easypin_set_corts(x, y);
                    jQuery(".pinCanvas").remove();
                },
                drag: function(x, y, element) {
                    easypin_set_corts(x, y);
                    jQuery(".pinCanvas").remove();
                },
                success: function() {
                    alert('ende');
                }
            });

            $instance.easypinShow({
                responsive: true
            });

            function easypin_set_corts(x, y) {

                var image = jQuery('.buddyforms-pin');
                var ep_id = image.attr('easypin-id');

                var width  = image.width();
                var height = image.height();

                console.log('{"' + ep_id + '":{"0":{"coords":{"lat":"' + x + '","long":"' + y + '"}},"canvas":{"width":"' + width + '","height":"' + height + '"}}}');

                jQuery('input[name="easypin-id"]').val(ep_id);
                jQuery('input[name="easypin-x"]').val(x);
                jQuery('input[name="easypin-y"]').val(y);
                jQuery('input[name="easypin-w"]').val(width);
                jQuery('input[name="easypin-h"]').val(height);

                return false;
            }

            jQuery(document).on('click', '.easy-delete', function () {

                jQuery('input[name="easypin-id"]').val('');
                jQuery('input[name="easypin-x"]').val('');
                jQuery('input[name="easypin-y"]').val('');
                jQuery('input[name="easypin-w"]').val('');
                jQuery('input[name="easypin-h"]').val('');

            });

        });
    </script>

    <input name="easypin-id" type="hidden" value="">
    <input name="easypin-x" type="hidden" value="">
    <input name="easypin-y" type="hidden" value="">
    <input name="easypin-w" type="hidden" value="">
    <input name="easypin-h" type="hidden" value="">

    <img src="<?php echo $image[0]; ?>" class="buddyforms-pin" width="auto" easypin-id="<?php echo $post_parent ?>" />
	<div style="display:none;" popover></div>
	<?php
    $easypin = ob_get_clean();
	return $easypin;
}