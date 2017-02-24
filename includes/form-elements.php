<?php

function buddyforms_easypin_add_form_element_to_select( $elements_select_options ) {
	global $post;

	if ( $post->post_type != 'buddyforms' ) {
		return;
	}

	$elements_select_options['easypin']['label'] = 'EasyPin';
	$elements_select_options['easypin']['class'] = 'bf_show_if_f_type_post';
	$elements_select_options['easypin']['fields']['easypin'] = array(
		'label'     => __( 'EasyPin', 'buddyforms' ),
		'unique'    => 'unique'
	);

	return $elements_select_options;
}

add_filter( 'buddyforms_add_form_element_select_option', 'buddyforms_easypin_add_form_element_to_select', 1, 2 );


/*
 * Create the new Form Builder Form Element
 *
 */
function buddyforms_easypin_create_new_form_builder_form_element( $form_fields, $form_slug, $field_type, $field_id ) {
	global $buddyforms;
	$buddyforms_options = $buddyforms;

	switch ( $field_type ) {

		case 'easypin':
			unset( $form_fields );

			$name = isset( $buddyforms_options[ $form_slug ]['form_fields'][ $field_id ]['name'] ) ? $buddyforms_options[ $form_slug ]['form_fields'][ $field_id ]['name'] : 'easypin';
			$form_fields['general']['name'] = new Element_Textbox( '<b>' . __( 'Name', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][name]", array( 'value' => $name ) );

			$gallery_slug = isset( $buddyforms_options[ $form_slug ]['form_fields'][ $field_id ]['gallery_slug'] ) ? $buddyforms_options[ $form_slug ]['form_fields'][ $field_id ]['gallery_slug'] : 'featured_image';
			$form_fields['general']['gallery_slug'] = new Element_Textbox( '<b>' . __( 'Gallery Slug', 'buddyforms' ) . '</b>', "buddyforms_options[form_fields][" . $field_id . "][gallery_slug]", array( 'value' => $gallery_slug ) );

			$form_fields['advanced']['slug'] = new Element_Hidden( "buddyforms_options[form_fields][" . $field_id . "][slug]", 'easypin' );

			$form_fields['general']['type'] = new Element_Hidden( "buddyforms_options[form_fields][" . $field_id . "][type]", $field_type );
			break;

	}

	return $form_fields;
}

add_filter( 'buddyforms_form_element_add_field', 'buddyforms_easypin_create_new_form_builder_form_element', 1, 5 );


/*
 * Display the new Form Element in the Frontend Form
 *
 */
function buddyforms_easypin_create_frontend_form_element( $form, $form_args ) {
	global $wp_query;

	extract( $form_args );

	if ( ! isset( $customfield['type'] ) ) {
		return $form;
	}

	switch ( $customfield['type'] ) {
		case 'easypin':

			if($post_parent == 0){
				return $form;
			}

            $form->addElement( new Element_HTML( do_shortcode('[buddyforms_easypin post_id="' . $post_id . '" post_parent="' . $post_parent . '" gallery_slug="' . $customfield['gallery_slug'] . '"]<br><br>')));
            break;
	}

	return $form;

}

add_filter( 'buddyforms_create_edit_form_display_element', 'buddyforms_easypin_create_frontend_form_element', 1, 2 );

add_action( 'buddyforms_after_save_post', 'buddyforms_easypin_after_save_post', 10, 1 );
function buddyforms_easypin_after_save_post( $post_id ) {

	if( ! isset( $_POST['easypin'] ) ){
		return;
	}

	// Save the coordinates in the post for the edit screen
	update_post_meta( $post_id, 'buddyforms_easypin_post', $_POST['easypin'] );

	// Save the coordinates as attachment meta for easy access if the image is displayed.
	// Each attachment can be use in multible posts as featured image with different pins
	$thumbnail_id = get_post_thumbnail_id( $_POST['easypin-id'] );
	$buddyforms_easypin_image = get_post_meta( $thumbnail_id, 'buddyforms_easypin_image', true );

	$post_parent = (int)$_POST['easypin-id'];

	if( ! is_array( $buddyforms_easypin_image ) ){
		$buddyforms_easypin_image = Array();
	}

	$buddyforms_easypin_image[$post_parent][$post_id]['post_id'] =  $_POST['easypin-id'];
	$buddyforms_easypin_image[$post_parent][$post_id]['long'] = isset( $_POST['easypin-long'] ) ? $_POST['easypin-long'] : 0 ;
	$buddyforms_easypin_image[$post_parent][$post_id]['lat'] = isset( $_POST['easypin-lat'] ) ? $_POST['easypin-lat'] : 0 ;
	$buddyforms_easypin_image[$post_parent][$post_id]['width'] = isset( $_POST['easypin-width'] ) ? $_POST['easypin-width'] : 'auto';
	$buddyforms_easypin_image[$post_parent][$post_id]['height'] = isset( $_POST['easypin-height'] ) ? $_POST['easypin-height'] : 'auto';

	update_post_meta( $thumbnail_id,'buddyforms_easypin_image', $buddyforms_easypin_image );

}


