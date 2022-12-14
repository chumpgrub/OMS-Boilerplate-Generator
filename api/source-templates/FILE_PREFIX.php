<?php

/*
Plugin Name: PLUGIN_NAME
Plugin URI: http://www.orbitmedia.com
Description: A brief description of the PLUGIN_NAME.
Version: 2.0.0
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
	public static function instance() : null|\BASE_CLASS_NAME {
		if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

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
	public function init() : void {}

	/**
	 * Include files necessary for plugin functionality.
	 */
	public function includes() : void {
	    /* FUNCTIONS_START */// Global functions necessary for plugin.
        require_once plugin_dir_path( __FILE__ ) . 'includes/FILE_PREFIX-functions.php';/* FUNCTIONS_END */
        /* TEMPLATE_START */// Support for plugin-level templating.
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-FILE_PREFIX-template-loader.php';/* TEMPLATE_END */
        /* QUERY_START */// Query customization are handled here.
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-FILE_PREFIX-query.php';/* QUERY_END */
        /* POST_TYPE_START */// Post Type, Taxonomy and Term Definitions.
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-FILE_PREFIX-post-type.php';/* POST_TYPE_END */
	}

	/**
	 * Handle all actions here.
	 */
	public function init_hooks() : void {
        /* ACF_START */// Enforce Advanced Custom Fields plugin as dependency.
		add_action( 'admin_init', [ $this, 'acf_is_active' ] );/* ACF_END */
        /* POST_TYPE_START */// Create Advanced Custom Fields options sub-page.
		add_action( 'init', [ $this, 'add_options_sub_page' ] );/* SIDEBARS_START */
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public static function plugin_path() : string {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

    /* ACF_START *//**
	 * Check for ACF Pro before activating this plugin
	 */
	public function acf_is_active() : void {
		if ( is_admin() && current_user_can( 'activate_plugins' ) && ! is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {

            add_action( 'admin_notices', [ $this, 'plugin_notice' ] );
            deactivate_plugins( plugin_basename( __FILE__ ) );

            if ( isset( $_GET['activate'] ) ) {
                unset( $_GET['activate'] );
            }
        }
	}/* ACF_END */

    /* POST_TYPE_START *//**
	 * Add ACF Options Sub Page to post type menus.
	 */
	public function add_options_sub_page() : void {
		if ( function_exists( 'acf_add_options_page' ) && BASE_CLASS_NAME_Post_Types::getPostTypes() ) {
            foreach ( BASE_CLASS_NAME_Post_Types::getPostTypes() as $post_type ) {
                $post_type_obj = get_post_type_object( $post_type );
                $post_type_name = ( ! is_wp_error( $post_type_obj ) ) ? sprintf( '%s ', $post_type_obj->labels->name ) : '';
                acf_add_options_sub_page( array(
                    'page_title' => __( $post_type_name . ' Settings', 'FILE_PREFIX' ),
                    'parent'     => 'edit.php?post_type=' . $post_type,
                    'post_id'    => $post_type,
                ) );
            }
        }
	}

    /* POST_TYPE_END *//* ACF_START *//**
	 * Error notice if ACF Pro not installed.
	 */
	public function plugin_notice() : void {
        echo '<div class="error"><p>PLUGIN_NAME requires Advanced Custom Fields Pro to be installed and active.</p></div>';
	}/* ACF_END */
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
function BASE_CLASS_NAME() : null|BASE_CLASS_NAME {
	return BASE_CLASS_NAME::instance();
}

add_action( 'plugins_loaded', 'BASE_CLASS_NAME' );
