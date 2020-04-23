<?php

/**
 * Controls the PLUGIN_NAME queries.
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class BASE_CLASS_NAME_Query {

	public static function init() {
		add_action( 'pre_get_posts', array( __CLASS__, 'pre_get_posts' ) );
	}

	/**
	 * Hook into pre_get_posts to do the main BASE_CLASS_NAME query.
	 * @todo Consider another token for above comment.
	 *
	 * @param mixed $query Query object
	 * @return mixed
	 */
	public static function pre_get_posts( $query ) {

		// We only want to affect the main query - don't modify search or admin queries.
		if ( ! $query->is_main_query() || is_search() || is_admin() ) {
			return $query;
		}

		// If on the standard BASE_CLASS_NAME post_type archive page.
		// @todo Consider another token for above comment.
		if ( $query->is_post_type_archive( BASE_CLASS_NAME_Post_Types::POST_TYPE ) ) {

			$query->set( 'posts_per_page', -1 ); // Display all.
			$query->set( 'orderby', 'menu_order' ); // Order by menu order.

			return $query;
		}

		return $query;
	}

}

BASE_CLASS_NAME_Query::init();
