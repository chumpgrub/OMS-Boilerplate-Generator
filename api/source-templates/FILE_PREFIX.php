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
	public function init() {}

	/**
	 * Include files necessary for plugin functionality.
	 */
	public function includes() {
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
	public function init_hooks() {
	    // Enforce Advanced Custom Fields plugin as dependency.
		add_action( 'admin_init', [ $this, 'acf_is_active' ] );
        /* POST_TYPE_START */
		// Create Advanced Custom Fields options sub-page.
		add_action( 'init', [ $this, 'add_options_sub_page' ] );
		// Add WooSidebars support for custom post types.
        add_action( 'init', [ $this, 'add_woosidebars_support' ] );
        /* POST_TYPE_END */// Orbit page title filter.
		add_filter( 'orbitmedia_page_title', [ $this, 'page_title' ] );
        // Orbit header image record_id filter.
		add_filter( 'orbitmedia_header_image_record_id', [ $this, 'header_image_record_id' ] );
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
		if ( is_admin() && current_user_can( 'activate_plugins' ) && ! is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {

            add_action( 'admin_notices', [ $this, 'plugin_notice' ] );
            deactivate_plugins( plugin_basename( __FILE__ ) );

            if ( isset( $_GET['activate'] ) ) {
                unset( $_GET['activate'] );
            }
        }
	}
/* POST_TYPE_START */
    /**
	 * Add ACF Options Sub Page to post type menus.
	 */
	public function add_options_sub_page() {
		if ( function_exists( 'acf_add_options_page' ) ) {
/* ACF_OPTIONS_SUB_PAGE_PARTIAL */
        }
	}

    /**
     * Add WooSidebars support for custom post types.
     */
    public function add_woosidebars_support() {
        if ( class_exists( 'Woo_Sidebars' ) && BASE_CLASS_NAME_Post_Types::getPostTypes() ) {
            foreach ( BASE_CLASS_NAME_Post_Types::getPostTypes() as $post_type ) {
                add_post_type_support( $post_type, 'woosidebars' );
            }
        }
    }
    /* POST_TYPE_END */
	/**
	 * Error notice if ACF Pro not installed.
	 */
	public function plugin_notice() {
        echo '<div class="error"><p>PLUGIN_NAME requires Advanced Custom Fields Pro to be installed and active.</p></div>';
	}

	/**
	 * Returns Title value entered on CPT > Settings.
	 *
	 * @param $title
	 *
	 * @return string
	 */
	public function page_title( $title ) {
        if ( BASE_CLASS_NAME_Post_Types::getPostTypes() ) {
            if ( is_post_type_archive( BASE_CLASS_NAME_Post_Types::getPostTypes() ) ) {
                $title = get_field( 'title', get_queried_object()->name );
                return ( $title ) ? sprintf( '<h1>%s</h1>', $title ) : $title;
            }
        }
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
        if ( BASE_CLASS_NAME_Post_Types::getPostTypes() ) {
            if ( is_post_type_archive( BASE_CLASS_NAME_Post_Types::getPostTypes() ) ) {
                return get_queried_object()->name;
            }
        }
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
