<?php

/**
 * Controls the Coffee Shop queries.
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class Coffee_Shop_Query {

	public static function init() {
		add_action( 'pre_get_posts', [ __CLASS__, 'pre_get_posts' ] );
	}

	/**
	 * Hook into pre_get_posts to do the main Coffee_Shop query.
	 *
	 * @param mixed $query Query object
	 * @return mixed
	 */
	public static function pre_get_posts( $query ) {

		// We only want to affect the main query, skipping search or admin queries.
		if ( ! $query->is_main_query() || is_search() || is_admin() ) {
			return $query;
		}

		// If archive matches post type.
		if ( $query->is_post_type_archive( Coffee_Shop_Post_Types::getPostTypes() ) ) {

			$query->set( 'posts_per_page', -1 ); // Display all.
			$query->set( 'orderby', 'menu_order' ); // Order by menu order.

			return $query;
		}

		return $query;
	}

}

Coffee_Shop_Query::init();
