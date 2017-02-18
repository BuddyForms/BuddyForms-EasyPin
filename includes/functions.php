<?php

add_filter( 'the_content', 'buddyforms_easypin_display_image', 10, 1 );
function buddyforms_easypin_display_image( $content ) {
	global $post, $paged, $buddyforms, $form_slug;


	if ( ! is_single() ) {
		return $content;
	}

	if ( is_admin() ) {
		return $content;
	}

	if ( ! isset( $buddyforms ) ) {
		return $content;
	}

	if ( $post->post_parent ) {
		return $content;
	}

	$form_slug = get_post_meta( $post->ID, '_bf_form_slug', true );

	if ( ! $form_slug ) {
		return $content;
	}


	$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), "full" );

	ob_start();
	?>

    <div>
        <div class="easypin" style="width: auto; height: auto;">
            <div style="position: relative; height: 100%;">
                <img src="<?php echo $image[0] ?>" class="pin" width="1000" easypin-id="demo_image_1"
                     style="opacity: 1; position: relative;"></div>
            <div class="marker2 element-animation easypin-marker"
                 style="position: absolute; cursor: pointer; left: 80%; top: 36.48%; opacity: 0.9; z-index: 0;">
            </div>
        </div>
    </div>
    <div style="display:none;" easypin-tpl="">
        <popover>
            <div class="exPopoverContainer">
                <div class="popBg borderRadius"></div>
                <div class="popBody">
                    <div class="arrow-down" style="top: 170px;left: 13px;"></div>
                    <h1>{[name]}</h1>
                    <div class="popHeadLine"></div>
                    <div class="popContentLeft">
                        {[description]}
                        <br>
                        <br>
                        <a href="#">Buy</a>
                        &nbsp;
                        &nbsp;
                        <a href="#">More info</a>
                    </div>
                    <div class="popContentRight">{[price]}</div>
                </div>
            </div>
        </popover>

        <marker>
            <div class="marker2 element-animation">
                +
            </div>
        </marker>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('.pin').easypinShow({
                data: '{"demo_image_1":{"0":{"name":"Pierre Cardin","description":"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ","price":"$67","coords":{"lat":"800","long":"228"}},"1":{"name":"Pierre Cardin","description":"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ","price":"$98","coords":{"lat":"597","long":"357"}},"2":{"name":"Pierre Cardin","description":"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ","price":"$100","coords":{"lat":"241","long":"352"}},"3":{"name":"Pierre Cardin","description":"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ","price":"$54","coords":{"lat":"365","long":"283"}},"4":{"name":"Sweater","description":"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ","price":"$32","coords":{"lat":"713","long":"276"}},"5":{"name":"Pierre Cardin","description":"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ","price":"$123","coords":{"lat":"771","long":"510"}},"6":{"name":"Pierre Cardin","description":"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ","price":"$54","coords":{"lat":"496","long":"277"}},"canvas":{"src":"img/fashion-trends.jpg","width":"1000","height":"625"}}}',
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
        });
    </script><?php

	$tmp = ob_get_clean();

	echo $tmp.$content;
}
