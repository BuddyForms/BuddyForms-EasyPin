<?php

add_shortcode( 'buddyforms_easypin', 'buddyforms_easypin' );

function buddyforms_easypin($shortcode_args){
	global $wp_query, $buddyforms;


	extract( shortcode_atts( array(
		'post_parent'   => 0,
		'post_id'       => 0,
        'gallery_slug'  => 'featured_image'
	), $shortcode_args ) );

	//$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_parent ), "full");

	$gallery_string = get_post_meta( $post_parent, $gallery_slug, true );

	if( empty($gallery_string) ){
		return;
	}

	$gallery = explode( ',', $gallery_string );


	if( ! is_array($gallery) ){
		return;
	}

	$form_slug = $wp_query->query_vars['bf_form_slug'];



	$easypin_post = get_post_meta( $post_id, 'buddyforms_easypin_post', true );

//	echo '<pre>';
//	print_r($easypin_post);
//	echo '</pre>';

	$easy_init = '';
	if( is_array( $easypin_post ) ){
		foreach ( $easypin_post as $img_id => $cords){
			if( !empty( $cords['id'] ) ) {
				$easy_init .= '"' . $cords['id'] . '":{"0":{"coords":{"lat":"' . $cords['lat'] . '","long":"' .  $cords['long'] . '"}},"canvas":{"src":"' . $cords['src'] . '", "width":"' . $cords['width'] . '","height":"' . $cords['height'] . '"}},';
			}
		}
		$easy_init = substr($easy_init, 0, -1);
	}
	
	
	
	ob_start();
	?>

    <script>

        jQuery(document).ready(function () {

            inittest();
            jQuery(document).on('click', '.easy-delete', function () {

                jQuery('#easypin-id-<?php echo $img_id ?>').val('');
                jQuery('#easypin-long-<?php echo $img_id ?>').val('');
                jQuery('#easypin-lat-<?php echo $img_id ?>').val('');
                jQuery('#easypin-width-<?php echo $img_id ?>').val('');
                jQuery('#easypin-height-<?php echo $img_id ?>').val('');

            });

            jQuery('#bf-easypin-carousel').carousel({
                interval: false
            });
            jQuery('#bf-easypin-carousel .item').removeClass('active');
            jQuery('#bf-easypin-carousel .item:first').addClass('active');

            jQuery('#slider-thumbs a:first').addClass('selected');


            // handles the carousel thumbnails
            jQuery('.carousel-selector').click( function(){
                var id_selector = jQuery(this).attr("id");
                var id = id_selector.substr(id_selector.length -1);
                id = parseInt(id);
                jQuery('#bf-easypin-carousel').carousel(id);
                jQuery('[id^=carousel-selector-]').removeClass('selected');
                jQuery(this).addClass('selected');
            });

            // when the carousel slides, auto update
            jQuery('.bf-easypin-carousel').on('slid', function (e) {
                var id = jQuery('.item.active').data('slide-number');
                id = parseInt(id);
                jQuery('.carousel-selector').removeClass('selected');
                jQuery('[id=carousel-selector-'+id+']').addClass('selected');
            });

        });

    </script>



    <div  class="row">
        <!-- thumb navigation carousel -->
        <div class="col-md-12 hidden-sm hidden-xs" id="slider-thumbs">
            <!-- thumb navigation carousel items -->
            <ul class="list-inline">
				<?php
				$i = 0;
				foreach( $gallery as $img_id ) {
					$image = wp_get_attachment_image_src( $img_id, "thumbnail" ); ?>
                    <li> <a id="carousel-selector-<?php echo $i ?>" class="carousel-selector">
                            <img class="img-responsive"  src="<?php echo $image[0]; ?>"/>
                        </a></li>
					<?php
					$i++;
				}

				?>
            </ul>
        </div>
    </div>



    <div style="margin-top: 20px" class="row">
        <div id="bf-easypin-carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
            <?php
            $active = 'active';
            foreach( $gallery as $img_id ) {

                $image = wp_get_attachment_image_src( $img_id, "full" ); ?>
                <div class="item <?php echo $active ?>" data-slide-number="<?php echo $i ?>">
                    <input name="easypin[<?php echo $img_id ?>][post_id]"     id="easypin-post_id-<?php echo $img_id ?>"     type="hidden" value="<?php echo $post_id; ?>">
                    <input name="easypin[<?php echo $img_id ?>][id]"     id="easypin-id-<?php echo $img_id ?>"     type="hidden" value="">
                    <input name="easypin[<?php echo $img_id ?>][src]"    id="easypin-id-<?php echo $img_id ?>"    type="hidden" value="<?php echo $image[0] ?>">
                    <input name="easypin[<?php echo $img_id ?>][long]"   id="easypin-long-<?php echo $img_id ?>"   type="hidden" value="">
                    <input name="easypin[<?php echo $img_id ?>][lat]"    id="easypin-lat-<?php echo $img_id ?>"    type="hidden" value="">
                    <input name="easypin[<?php echo $img_id ?>][width]"  id="easypin-width-<?php echo $img_id ?>"  type="hidden" value="">
                    <input name="easypin[<?php echo $img_id ?>][height]" id="easypin-height-<?php echo $img_id ?>" type="hidden" value="">

                    <img width="100%" src="<?php echo $image[0]; ?>" class="buddyforms-pin" easypin-id="<?php echo $img_id ?>"/>

                </div>
                <?php
    //	        $active = '';
            }

            ?>
            </div>
            <!-- Carousel controls -->
<!--            <a class="carousel-control left" href="#bf-easypin-carousel" data-slide="prev">-->
<!--                <span class="glyphicon glyphicon-chevron-left"></span>-->
<!--            </a>-->
<!--            <a class="carousel-control right" href="#bf-easypin-carousel" data-slide="next">-->
<!--                <span class="glyphicon glyphicon-chevron-right"></span>-->
<!--            </a>-->
        </div>
    </div>
    <div style="display:none;" popover></div>
    <script>

        function inittest(){
            $instance = jQuery('.buddyforms-pin').easypin({

                init: '{<?php echo $easy_init ?>}',
                limit: 1,
                exceeded: function (element) {
                    alert('You only able to create one pin at the time ;)');
                },
//                responsive: true,
                popover: {
                    show: false,
                },
                drop: function (lat, long, element, parentid) {
                    easypin_set_corts(lat, long, parentid);
                    jQuery(".pinCanvas").remove();
                    jQuery(".popover").remove();

                },
                drag: function (lat, long, element, parentid) {
                    console.log
                    easypin_set_corts(lat, long, parentid);
                    jQuery(".pinCanvas").remove();
                    jQuery(".popover").remove();
                }
            });
            $instance.easypinShow();
        }



        function easypin_set_corts(lat, long, img_id) {



            var image = jQuery("[easypin-id=" + img_id + "]");
            var ep_id = image.attr('easypin-id');


            console.log(image);

            var width = image.width();
            var height = image.height();

//                console.log('{"' + ep_id + '":{"0":{"coords":{"lat":"' + lat + '","long":"' + long + '"}},"canvas":{"width":"' + width + '","height":"' + height + '"}}}');

            jQuery('#easypin-id-' + img_id).val(ep_id);
            jQuery('#easypin-long-' + img_id).val(long);
            jQuery('#easypin-lat-' + img_id).val(lat);
            jQuery('#easypin-width-' + img_id).val(width);
            jQuery('#easypin-height-' + img_id).val(height);

            return false;
        }

    </script>




<?php

    $easypin = ob_get_clean();
	return $easypin;
}