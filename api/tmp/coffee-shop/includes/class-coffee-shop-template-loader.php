<?php

/**
 * Templating functionality for Coffee Shop
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class Coffee_Shop_Template_Loader {

	public static function init() {
		add_filter( 'template_include', [__CLASS__, 'load_template'] );
	}

	/**
	 * Pick the correct template to include.
	 *
	 * @param  string $template Path to template
	 * @return string           Path to template
	 */
	public static function load_template( $template ) {

		$file = '';

		if ( is_singular( Coffee_Shop_Post_Types::getPostTypes() ) ) {
            $file = 'single-coffee-shop.php';
        } elseif ( is_post_type_archive( Coffee_Shop_Post_Types::getPostTypes() ) && ! is_search() ) {
            $file = 'archive-coffee-shop.php';
        }

		if ( $file ) {
            // Check theme directory for template file, first.
            $template = locate_template( [ 'coffee-shop/' . $file, $file ] );

            if ( ! $template ) {
                $template = Coffee_Shop::plugin_path() . '/templates/' . $file;
            }
        }

		return $template;
	}

}

Coffee_Shop_Template_Loader::init();
