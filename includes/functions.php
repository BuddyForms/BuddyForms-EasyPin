<?php

//add_filter( 'the_content', 'buddyforms_easypin_display_image', 10, 1 );
function buddyforms_easypin_display_image(  ) {
	global $post, $paged, $buddyforms, $form_slug;


	if ( $post->post_parent ) {
		return;
	}

	$form_slug = get_post_meta( $post->ID, '_bf_form_slug', true );

	if ( ! $form_slug ) {
		return $content;
	}

	$thumbnail_id = get_post_thumbnail_id( get_the_ID() );
	$image = wp_get_attachment_image_src( $thumbnail_id, "full" );

	$buddyforms_easypin_image = get_post_meta( $thumbnail_id, 'buddyforms_easypin_image', true );

//	echo '<pre>';
//    print_r($buddyforms_easypin_image);
//	echo '</pre>';


	if( isset( $buddyforms_easypin_image[get_the_ID()] ) && is_array( $buddyforms_easypin_image[get_the_ID()] ) ) {


		$cords = $buddyforms_easypin_image[get_the_ID()];


		$data = '{"demo_image_1":{';

		$height = 'auto';
		$width = 'auto';

		$i = 0;
	    foreach($cords as $post_id => $cort){

	        $post_id = $cort['post_id'];

	        $pin_post = get_post($post_id);

		    $height =  $cort['height'];
		    $width =  $cort['width'];

		    $data .= '"' . $i . '":{';
		    $data .= '"name":"' . $pin_post->post_title . '",';
		    $data .= '"description":"' . $pin_post->post_content . '",';
		    $data .= '"permalink":"' . get_the_permalink($post_id) . '",';
		    $data .= '"coords":{';
		    $data .= '"lat":"' . $cort['lat'] . '",';
		    $data .= '"long":"' . $cort['long'] . '"}},';

		    $i++;
        }

		$data .= '"canvas":{';
		$data .= '"width":"' . $width . '",';
		$data .= '"height":"' . $height . '"';
		$data .= '}}';
		$data .= '}';


	}

	ob_start();
	?>

    <div>
        <div class="easypin" style="width: auto; height: auto;">
            <div style="position: relative; height: 100%;">
                <img src="<?php echo $image[0] ?>" class="pin" easypin-id="demo_image_1"
                     style="opacity: 1;"></div>
        </div>
    </div>
    <div style="display:none;" easypin-tpl="">
        <popover>
            <div class="exPopoverContainer">
                <div class="popBg borderRadius"></div>
                <div class="popBody">
                    <div class="arrow-down" style="top: 150px;left: 13px;"></div>
                    <h1>{[name]}</h1>
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

                data:'<?php echo $data ?>',
                responsive: true,
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
        });
    </script><?php

	$tmp = ob_get_clean();

	echo $tmp;
}
