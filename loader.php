<?php

/**
 * Plugin Name: BuddyForms EasyPin
 * Plugin URI:  https://themekraft.com/buddyforms/
 * Description: Pin your posts
 * Version: 0.1
 * Author: Sven Lehnert
 * Author URI: https://profiles.wordpress.org/svenl77
 * Licence: GPLv3
 * Network: false
 * Text Domain: buddyforms
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
		add_action( 'wp_enqueue_scripts', array( $this, 'front_js' ), 102, 1 );

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

		require_once( BUDDYFORMS_EASYPIN_INCLUDES_PATH . 'shortcodes.php' );
		require_once( BUDDYFORMS_EASYPIN_INCLUDES_PATH . 'form-elements.php' );

		if ( is_admin() ) {
//			require_once( BUDDYFORMS_INCLUDES_PATH . '/admin/admin.php' );
		}

	}

	/**
	 * Load the textdomain for the plugin
	 *
	 * @package buddyforms
	 * @since 0.1-beta
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'buddyforms', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	public function front_js(){
		wp_enqueue_script( 'jquery-effects-core' );
		wp_enqueue_script( 'jquery-easypin', plugins_url( 'assets/resources/jquery-easypin/dist/jquery.easypin.min.js', __FILE__ ), array( 'jquery' ) );


//		wp_enqueue_script( 'jquery-ui-core' );
//		wp_enqueue_script( 'jquery-ui-widget' );
//		wp_enqueue_script( 'jquery-effects-core' );
//		wp_enqueue_script( 'jquery-droppin', plugins_url( 'assets/resources/droppin/droppin.min.js', __FILE__ ), array( 'jquery' ), '1.7.2' );




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
