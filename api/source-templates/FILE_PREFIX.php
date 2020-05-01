<?php

/*
Plugin Name: PLUGIN_NAME
Plugin URI: http://www.orbitmedia.com
Description: A brief description of the PLUGIN_NAME.
Version: 1.0
Author: Orbit Media Studios
Author URI: http://www.orbitmedia.com
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class BASE_CLASS_NAME
 */
class BASE_CLASS_NAME {

	/**
     * The single instance of the class.
	 */
	protected static $_instance = NULL;

	/**
     * Main BASE_CLASS_NAME Instance.
     *
     * @see BASE_CLASS_NAME()
	 * @return null|\BASE_CLASS_NAME
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) :
			self::$_instance = new self();
		endif;

		return self::$_instance;
	}

	/**
	 * BASE_CLASS_NAME constructor.
	 */
	public function __construct() {
		$this->init();
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Initialize code required by plugin.
	 */
	public function init() {}

	/**
	 * Include files necessary for plugin functionality.
	 */
	public function includes() {
        INCLUDE_FUNCTIONS
        INCLUDE_TEMPLATE
        INCLUDE_QUERY
        INCLUDE_POST_TYPE

	}

	/**
	 * Handle all actions here.
	 */
	public function init_hooks() {

	    // Enforce Advanced Custom Fields plugin as dependency.
		add_action( 'admin_init', [ $this, 'acf_is_active' ] );

		// Create Advanced Custom Fields options sub-page.
		add_action( 'init', [ $this, 'add_options_sub_page' ] );

		add_filter( 'orbitmedia_page_title', [ $this, 'page_title' ] );

		add_filter( 'orbitmedia_header_image_record_id', [ $this, 'header_image_record_id' ] );

		// Add WooSidebars support for POST_TYPE_NAMES.
		// @todo Support for multiple post types...
		if ( class_exists( 'Woo_Sidebars' ) ) :
			add_post_type_support( BASE_CLASS_NAME_Post_Types::POST_TYPE, 'woosidebars' );
		endif;
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public static function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Check for ACF Pro before activating this plugin
	 */
	public function acf_is_active() {
		if ( is_admin() && current_user_can( 'activate_plugins' ) && ! is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) :

			add_action( 'admin_notices', [ $this, 'plugin_notice' ] );

			deactivate_plugins( plugin_basename( __FILE__ ) );

			if ( isset( $_GET['activate'] ) ) :
				unset( $_GET['activate'] );
			endif;

		endif;
	}

	/**
	 * Add ACF Options Sub Page to BASE_CLASS_NAME menu.
	 */
	public function add_options_sub_page() {
		if ( function_exists( 'acf_add_options_page' ) ) :
			acf_add_options_sub_page( array(
				'page_title' => 'BASE_CLASS_NAME Settings',
				'parent'     => 'edit.php?post_type=' . BASE_CLASS_NAME_Post_Types::POST_TYPE,
				'post_id'    => BASE_CLASS_NAME_Post_Types::POST_TYPE,
			) );
		endif;
	}

	/**
	 * Error notice if ACF Pro not installed.
	 */
	public function plugin_notice() {
		?>
        <div class="error"><p>PLUGIN_NAME requires Advanced Custom Fields Pro to be installed and active.</p></div>
        <?php
	}

	/**
	 * Returns Title value entered on CPT > Settings.
	 *
	 * @param $title
	 *
	 * @return string
	 */
	public function page_title( $title ) {
		if ( is_post_type_archive( BASE_CLASS_NAME_Post_Types::POST_TYPE ) ) :
            $title = get_field( 'title', BASE_CLASS_NAME_Post_Types::POST_TYPE );
		    if ( $title ) :
    			return '<h1>' . $title . '</h1>';
		    endif;
		endif;

		return $title;
	}

	/**
	 * Returns CPT slug so that header values can be pulled from the CPT >
	 * Settings options page.
	 *
	 * @param $record_id
	 *
	 * @return string|integer $record_id Either Post ID or CPT slug.
	 */
	public function header_image_record_id( $record_id ) {
		if ( is_post_type_archive( BASE_CLASS_NAME_Post_Types::POST_TYPE ) ) :
			return BASE_CLASS_NAME_Post_Types::POST_TYPE;
		endif;

		return $record_id;
	}
}

/**
 * Main instance of BASE_CLASS_NAME.
 *
 * Returns the main instance of BASE_CLASS_NAME to prevent the need to use
 * globals.
 *
 * @return null|BASE_CLASS_NAME
 * @author Orbit Media Studios <wordpress@orbitmedia.com>
 */
function BASE_CLASS_NAME() {
	return BASE_CLASS_NAME::instance();
}

add_action( 'plugins_loaded', 'BASE_CLASS_NAME' );
