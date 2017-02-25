<?php

//add_filter( 'the_content', 'buddyforms_easypin_display_image', 10, 1 );
function buddyforms_easypin_display_image(  ) {
	global $post, $paged, $buddyforms, $form_slug;


//	if ( !$post->post_parent ) {
//		return;
//	}

//	$form_slug = get_post_meta( $post->ID, '_bf_form_slug', true );

//	if ( ! $form_slug ) {
//		return $content;
//	}

	$parent_id = wp_get_post_parent_id( $post->ID );

	$parent_id = $parent_id == 0 ? $post->ID : $parent_id;


//	$thumbnail_id = get_post_thumbnail_id( $parent_id );
//	$image = wp_get_attachment_image_src( $thumbnail_id, "full" );

	$buddyforms_easypin_image = get_post_meta( $parent_id, 'buddyforms_easypin_image', true );

//	echo '<pre>';
//    print_r($buddyforms_easypin_image);
//	echo '</pre>';

//	if( isset( $buddyforms_easypin_image[ $post->ID ] ) && is_array( $buddyforms_easypin_image[ $post->ID ]  ) ) {


//		$cords = $buddyforms_easypin_image[ $post->ID ];


		$easy_init = '';
		if( is_array( $buddyforms_easypin_image ) ){
			foreach ( $buddyforms_easypin_image as $img_id => $cords){
				$easy_init .= '"' . $img_id . '":{';
				$i = 0;
			    foreach ($cords as $cord){
				    if( !empty( $cord['id'] ) ) {

					    $pin_post = get_post($cord['post_id']);
					    
					    $easy_init .= '"' . $i . '":{';
					    $easy_init .= '"title":"' . $pin_post->post_title . '",';
					    $easy_init .= '"description":"' . $pin_post->post_content . '",';
					    $easy_init .= '"permalink":"' . get_the_permalink( $cord['post_id'] ) . '",';
					    $easy_init .= '"coords":{"lat":"' . $cord['lat'] . '","long":"' .  $cord['long'] . '"}},"canvas":{"src":"' . $cord['src'] . '", "width":"' . $cord['width'] . '","height":"' . $cord['height'] . '"},';
				    }
				    $i++;
                }
				$easy_init = substr($easy_init, 0, -1);
                $easy_init .= '},';
			}
			$easy_init = substr($easy_init, 0, -1);
		}



//	echo $easy_init;

        $gallery_string = get_post_meta( $parent_id, 'bilder', true );

        if( empty($gallery_string) ){
            return;
        }

        $gallery = explode( ',', $gallery_string );

        if( ! is_array($gallery) ){
            return;
        }


?>


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
                $i = 0;
                foreach( $gallery as $img_id ) {

                    $image = wp_get_attachment_image_src( $img_id, "full" ); ?>
                    <div class="item <?php echo $active ?>" data-slide-number="<?php echo $i ?>">
                        <img width="100%" src="<?php echo $image[0]; ?>" class="pin" easypin-id="<?php echo $img_id ?>"/>
                    </div>
                    <?php
                    $i++;

                }
                ?>
                <!-- Carousel controls -->
                <a class="carousel-control left" href="#bf-easypin-carousel" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                </a>
                <a class="carousel-control right" href="#bf-easypin-carousel" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                </a>
            </div>
        </div>

    </div>



     <?php










//		$data = '{"' . $cords . '":{';
//
//		$height = 'auto';
//		$width = 'auto';
//
//		$i = 0;
//	    foreach($cords as $post_id => $cort){
//
//	        $post_id = $cort['post_id'];
//
//	        $pin_post = get_post($post_id);
//
//		    $height =  $cort['height'];
//		    $width =  $cort['width'];
//
//		    $data .= '"' . $i . '":{';
//		    $data .= '"title":"' . $pin_post->post_title . '",';
//		    $data .= '"description":"' . $pin_post->post_content . '",';
//		    $data .= '"permalink":"' . get_the_permalink($post_id) . '",';
//		    $data .= '"coords":{';
//		    $data .= '"lat":"' . $cort['lat'] . '",';
//		    $data .= '"long":"' . $cort['long'] . '"}},';
//
//		    $i++;
//        }
//
//		$data .= '"canvas":{';
//		$data .= '"src":"http://1ertuning/wp-content/uploads/2017/02/IMG_0257.jpg",';
//		$data .= '"width":"' . $width . '",';
//		$data .= '"height":"' . $height . '"';
//		$data .= '}}';
//		$data .= '}';


//	}

	ob_start();

//	if( is_array( $buddyforms_easypin_image ) ){
//		foreach ( $buddyforms_easypin_image as $img_id => $cords){
//			foreach ($cords as $img_id => $cord){
//				if( !empty( $cord['id'] ) ) {
//					?><!--<img width="750" src="--><?php //echo $cord['src'] ?><!--" class="pin" easypin-id="--><?php //echo $cord['id'] ?><!--"style="opacity: 1;">--><?php
//				}
//			}
//		}
//	}

	?>

    <div>
        <div class="easypin" style="width: auto; height: auto;">
            <div style="position: relative; height: 100%;">
                </div>
        </div>
    </div>
    <div style="display:none;" easypin-tpl="">
        <popover>
            <div class="exPopoverContainer">
                <div class="popBg borderRadius"></div>
                <div class="popBody">
                    <div class="arrow-down" style="top: 150px;left: 13px;"></div>
                    <h1>{[title]}</h1>
                    <div class="popHeadLine"></div>
                    <div class="popContentLeft">
                        {[description]}
                        <br><br><br>
                        <a href="{[permalink]}">More info</a>
                    </div>
                </div>
            </div>
        </popover>

        <marker>
            <div class="marker2">+</div>
        </marker>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('.pin').easypinShow({

                data:'{<?php echo $easy_init ?>}',
                responsive: false,
                variables: {
                    firstname: function (canvas_id, pin_id, data) {
                        //console.log(canvas_id, pin_id, data);
                        return data;
                    },
                    surname: function (canvas_id, pin_id, data) {
                        //console.log(canvas_id, pin_id, data);
                        return data;
                    }
                },
                popover: {
                    show: false,
                    animate: true
                },
                each: function (index, data) {
                    return data;
                },
                error: function (e) {
                    console.log(e);
                },
                success: function () {
                    console.log('başarılı');
                }
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
    </script><?php

	$tmp = ob_get_clean();

	echo $tmp;
}
