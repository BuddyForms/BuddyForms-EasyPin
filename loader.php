<?php

/**
 * Plugin Name: BuddyForms EasyPin
 * Plugin URI:  https://themekraft.com/buddyforms/
 * Description: Pin your posts on images
 * Version: 1.0
 * Author: ThemeKraft
 * Author URI: https://themekraft.com/buddyforms/
 * Licence: GPLv3
 * Network: false
 * Text Domain: easypin
 * Domain Path: /languages
 *
 * @fs_premium_only /includes/admin/form-metabox.php
 *
 * ****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA    02111-1307    USA
 *
 ****************************************************************************
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class BuddyForms_EasyPin {

	/**
	 * Initiate the class
	 *
	 * @package buddyforms-easypin
	 * @since 0.1
	 */
	public function __construct() {

		// Load the plugin constants
		$this->load_constants();

		// Include all necessary files
		add_action( 'init', array( $this, 'includes' ), 4, 1 );
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Load the EasyPin js
		add_action( 'wp_enqueue_scripts', array( $this, 'front_js' ), 10, 1 );

	}

	/**
	 * Defines constants needed throughout the plugin.
	 *
	 * These constants can be overridden in bp-custom.php or wp-config.php.
	 *
	 * @package buddyforms-easypin
	 * @since 0.1
	 */
	public function load_constants() {


		if ( ! defined( 'BUDDYFORMS_EASYPIN_PLUGIN_URL' ) ) {
			/**
			 * Define the plugin url
			 */
			define( 'BUDDYFORMS_EASYPIN_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
		}

		if ( ! defined( 'BUDDYFORMS_EASYPIN_INSTALL_PATH' ) ) {
			/**
			 * Define the install path
			 */
			define( 'BUDDYFORMS_EASYPIN_INSTALL_PATH', dirname( __FILE__ ) . '/' );
		}

		if ( ! defined( 'BUDDYFORMS_EASYPIN_INCLUDES_PATH' ) ) {
			/**
			 * Define the include path
			 */
			define( 'BUDDYFORMS_EASYPIN_INCLUDES_PATH', BUDDYFORMS_EASYPIN_INSTALL_PATH . 'includes/' );
		}

		if ( ! defined( 'BUDDYFORMS_EASYPIN_TEMPLATE_PATH' ) ) {
			/**
			 * Define the template path
			 */
			define( 'BUDDYFORMS_EASYPIN_TEMPLATE_PATH', BUDDYFORMS_EASYPIN_INSTALL_PATH . 'templates/' );
		}

	}

	/**
	 * Include files needed by BuddyForms EasyPin
	 *
	 * @package buddyforms
	 * @since 0.1
	 */
	public function includes() {

		require_once( BUDDYFORMS_EASYPIN_INCLUDES_PATH . 'functions.php' );
		require_once( BUDDYFORMS_EASYPIN_INCLUDES_PATH . 'admin.php' );
		require_once( BUDDYFORMS_EASYPIN_INCLUDES_PATH . 'form-elements.php' );

	}

	/**
	 * Load the textdomain for the plugin
	 *
	 * @package buddyforms
	 * @since 0.1-beta
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'easypin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	public function front_js(){

		$easypin_settings = get_option( 'buddyforms_easypin_settings', true );

		if( ! isset( $easypin_settings['bootstrap'] ) ){
			wp_enqueue_script( 'bootstrap-js', plugins_url( 'assets/resources/bootstrap/js/bootstrap.js', __FILE__ ), array( 'jquery' ) );
		}

		wp_enqueue_style('bootstrap-css', plugins_url( 'assets/resources/bootstrap/css/bootstrap.css', __FILE__ ) );

		wp_enqueue_script( 'jquery-effects-core' );
		wp_enqueue_script( 'jquery-easypin', plugins_url( 'assets/resources/jquery-easypin/dist/jquery.easypin.js', __FILE__ ), array( 'jquery' ) );

		wp_enqueue_style('easypin-css', plugins_url( 'assets/css/easypin.css', __FILE__ ) );

		$script_data = array(
			'image_path' => BUDDYFORMS_EASYPIN_PLUGIN_URL . 'assets/images/'
		);
		wp_localize_script(
			'jquery-easypin',
			'jquery_easypin_data',
			$script_data
		);
	}
}
new BuddyForms_EasyPin();


//
// Check the plugin dependencies
//
add_action( 'init', function () {

	// Only Check for requirements in the admin
	if ( ! is_admin() ) {
		return;
	}

	// Require TGM
	require( dirname( __FILE__ ) . '/includes/resources/tgm/class-tgm-plugin-activation.php' );

	// Hook required plugins function to the tgmpa_register action
	add_action( 'tgmpa_register', function () {

		$plugins['buddyforms-hierarchical-posts'] = array(
			'name'     => 'BuddyForms Hierarchical Posts',
			'slug'     => 'buddyforms-hierarchical-posts',
			'required' => true,
		);

		$plugins['buddyforms-hook-fields'] = array(
			'name'     => 'BuddyForms Hook Fields',
			'slug'     => 'buddyforms-hook-fields',
			'required' => false,
		);

		if ( ! defined( 'BUDDYFORMS_PRO_VERSION' ) ) {
			$plugins['buddyforms'] = array(
				'name'     => 'BuddyForms',
				'slug'     => 'buddyforms',
				'required' => true,
			);
		}

		$config = array(
			'id'           => 'tgmpa',
			// Unique ID for hashing notices for multiple instances of TGMPA.
			'parent_slug'  => 'plugins.php',
			// Parent menu slug.
			'capability'   => 'manage_options',
			// Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,
			// Show admin notices or not.
			'dismissable'  => false,
			// If false, a user cannot dismiss the nag message.
			'is_automatic' => true,
			// Automatically activate plugins after installation or not.
		);

		// Call the tgmpa function to register the required plugins
		tgmpa( $plugins, $config );

	} );
}, 1, 1 );