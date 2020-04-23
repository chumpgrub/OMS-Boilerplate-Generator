<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BASE_CLASS_NAME_Post_Types {

	// @todo Support for multiple post types
	const POST_TYPE = 'BOILERPLATE';
	// @todo Support for multiple taxonomies
	const TAXONOMY = 'BOILERPLATE_cat';

	/**
	 * Initiate post type and taxonomy registration hooks.
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_taxonomies' ], 5 );
		add_action( 'init', [ __CLASS__, 'register_post_types' ], 5 );
		register_activation_hook( __FILE__, [ __CLASS__, 'flush_rewrites' ] );
	}

	/**
	 * Register Taxonomies.
	 */
	public static function register_taxonomies() {

		if ( ! taxonomy_exists( BASE_CLASS_NAME_Post_Types::TAXONOMY ) ) :

			register_taxonomy( BASE_CLASS_NAME_Post_Types::TAXONOMY,

				// Filter to modify on which post types this taxonomy can appear.
				apply_filters( 'oms_taxonomy_objects_BOILERPLATE_cat', [ BASE_CLASS_NAME_Post_Types::POST_TYPE ] ),

				// Filter to modify taxonomy.
				apply_filters( 'oms_taxonomy_args_BOILERPLATE_cat', [
					'hierarchical'      => TRUE,
					'label'             => __( 'BOILERPLATE Categories', 'oms_BOILERPLATE' ),
					'labels'            => [
						'name'              => __( 'BOILERPLATE Categories', 'oms_BOILERPLATE' ),
						'singular_name'     => __( 'BOILERPLATE Category', 'oms_BOILERPLATE' ),
						'menu_name'         => _x( 'Categories', 'Admin menu name', 'oms_BOILERPLATE' ),
						'search_items'      => __( 'Search BOILERPLATE Categories', 'oms_BOILERPLATE' ),
						'all_items'         => __( 'All BOILERPLATE Categories', 'oms_BOILERPLATE' ),
						'parent_item'       => __( 'Parent BOILERPLATE Category', 'oms_BOILERPLATE' ),
						'parent_item_colon' => __( 'Parent BOILERPLATE Category:', 'oms_BOILERPLATE' ),
						'edit_item'         => __( 'Edit BOILERPLATE Category', 'oms_BOILERPLATE' ),
						'update_item'       => __( 'Update BOILERPLATE Category', 'oms_BOILERPLATE' ),
						'add_new_item'      => __( 'Add New BOILERPLATE Category', 'oms_BOILERPLATE' ),
						'new_item_name'     => __( 'New BOILERPLATE Category Name', 'oms_BOILERPLATE' ),
					],
					'show_ui'           => TRUE,
					'query_var'         => TRUE,
					'public'            => TRUE,
					'show_admin_column' => TRUE,
				] )
			);

		endif;
	}

	/**
	 * Register Post Types.
	 */
	public static function register_post_types() {

		if ( ! post_type_exists( BASE_CLASS_NAME_Post_Types::POST_TYPE ) ) :

			register_post_type( BASE_CLASS_NAME_Post_Types::POST_TYPE,

				// Filter to modify post type.
				apply_filters( 'oms_register_post_type_BOILERPLATE',
					[
						'labels'              => [
							'name'               => _x( 'BOILERPLATE', 'oms_BOILERPLATE' ),
							'singular_name'      => _x( 'BOILERPLATE', 'oms_BOILERPLATE' ),
							'add_new'            => _x( 'Add New', 'oms_BOILERPLATE' ),
							'add_new_item'       => _x( 'Add New BOILERPLATE', 'oms_BOILERPLATE' ),
							'edit_item'          => _x( 'Edit BOILERPLATE', 'oms_BOILERPLATE' ),
							'new_item'           => _x( 'New BOILERPLATE', 'oms_BOILERPLATE' ),
							'view_item'          => _x( 'View BOILERPLATE', 'oms_BOILERPLATE' ),
							'search_items'       => _x( 'Search BOILERPLATE', 'oms_BOILERPLATE' ),
							'not_found'          => _x( 'No BOILERPLATE found', 'oms_BOILERPLATE' ),
							'not_found_in_trash' => _x( 'No BOILERPLATE found in Trash', 'oms_BOILERPLATE' ),
							'parent_item_colon'  => _x( 'Parent BOILERPLATE:', 'oms_BOILERPLATE' ),
							'menu_name'          => _x( 'BOILERPLATE', 'oms_BOILERPLATE' ),
						],
						'hierarchical'        => TRUE,
						'description'         => '', // A short descriptive summary of what the post type is.
						'supports'            => [
							'title',
							'editor',
							'excerpt',
							'thumbnail',
						],
						'public'              => TRUE,
						'menu_icon'           => 'dashicons-heart', // For more options see: https://developer.wordpress.org/resource/dashicons/
						'show_ui'             => TRUE,
						'show_in_menu'        => TRUE,
						'menu_position'       => 20,
						'show_in_nav_menus'   => TRUE,
						'publicly_queryable'  => TRUE,
						'exclude_from_search' => FALSE,
						'has_archive'         => TRUE,
						'query_var'           => TRUE,
						'can_export'          => TRUE,
						'rewrite'             => [
							'slug'       => 'boilerplate', // Can also be "about/team" or whatever.
							'with_front' => FALSE,
						],
						'capability_type'     => 'post',
					]
				)
			);

		endif;

	}

	/**
	 * Flush the rewrites.
	 */
	public static function flush_rewrites() {
		flush_rewrite_rules();
	}
}

BASE_CLASS_NAME_Post_Types::init();
