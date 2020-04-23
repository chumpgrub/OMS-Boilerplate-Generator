<?php

/**
 * Templating functionality for PLUGIN_NAME
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class BASE_CLASS_NAME_Template_Loader {

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
		global $wp_query;

		$file = '';

		if ( is_singular( BASE_CLASS_NAME_Post_Types::POST_TYPE ) ) :

			$file = 'single-BOILERPLATE.php';

		elseif ( is_post_type_archive( BASE_CLASS_NAME_Post_Types::POST_TYPE ) && ! is_search() ) :

			$file = 'archive-BOILERPLATE.php';

		endif;

		if ( $file ) :

			// Check theme directory for template file, first.
			$template = locate_template( [ 'FILE_PREFIX/' . $file, $file ] );

			if ( ! $template ) :
				$template = BASE_CLASS_NAME::plugin_path() . '/templates/' . $file;
			endif;

		endif;

		return $template;
	}

}

BASE_CLASS_NAME_Template_Loader::init();
