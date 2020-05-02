<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BASE_CLASS_NAME_Post_Types {

    /* POST_TYPE_START */// @todo Support for multiple post types
	const POST_TYPE = 'BOILERPLATE';/* POST_TYPE_END */
    /* TAXONOMY_START */// @todo Support for multiple taxonomies
	const TAXONOMY = 'BOILERPLATE_cat';/* TAXONOMY_END */

	/**
	 * Initiate post type and taxonomy registration hooks.
	 */
	public static function init() {
        /* POST_TYPE_START */add_action( 'init', [ __CLASS__, 'register_post_types' ], 5 );/* POST_TYPE_END */
        /* TAXONOMY_START */add_action( 'init', [ __CLASS__, 'register_taxonomies' ], 5 );/* TAXONOMY_END */
		register_activation_hook( __FILE__, [ __CLASS__, 'flush_rewrites' ] );
	}

	/* TAXONOMY_START *//**
	 * Register Taxonomies.
	 */
	public static function register_taxonomies() {

		if ( ! taxonomy_exists( BASE_CLASS_NAME_Post_Types::TAXONOMY ) ) :

			register_taxonomy( BASE_CLASS_NAME_Post_Types::TAXONOMY,

				// Filter to modify on which post types this taxonomy can appear.
				apply_filters( 'taxonomy_objects_TAXONOMY_SINGULAR', [ BASE_CLASS_NAME_Post_Types::POST_TYPE ] ),

				// Filter to modify taxonomy.
				apply_filters( 'taxonomy_args_TAXONOMY_SINGULAR', [
					'hierarchical'      => TRUE,
					'label'             => __( 'TAXONOMY_PLURAL', 'FILE_PREFIX' ),
					'labels'            => [
						'name'              => __( 'TAXONOMY_PLURAL', 'FILE_PREFIX' ),
						'singular_name'     => __( 'TAXONOMY_SINGULAR', 'FILE_PREFIX' ),
						'menu_name'         => _x( 'TAXONOMY_PLURAL', 'Admin menu name', 'FILE_PREFIX' ),
						'search_items'      => __( 'Search TAXONOMY_PLURAL', 'FILE_PREFIX' ),
						'all_items'         => __( 'All TAXONOMY_PLURAL', 'FILE_PREFIX' ),
						'parent_item'       => __( 'Parent TAXONOMY_SINGULAR', 'FILE_PREFIX' ),
						'parent_item_colon' => __( 'Parent TAXONOMY_SINGULAR', 'FILE_PREFIX' ),
						'edit_item'         => __( 'Edit TAXONOMY_SINGULAR', 'FILE_PREFIX' ),
						'update_item'       => __( 'Update TAXONOMY_SINGULAR', 'FILE_PREFIX' ),
						'add_new_item'      => __( 'Add New TAXONOMY_SINGULAR', 'FILE_PREFIX' ),
						'new_item_name'     => __( 'New TAXONOMY_SINGULAR Name', 'FILE_PREFIX' ),
					],
					'show_ui'           => TRUE,
					'query_var'         => TRUE,
					'public'            => TRUE,
					'show_admin_column' => TRUE,
				] )
			);

		endif;
	}/* TAXONOMY_END */

    /* POST_TYPE_START *//**
	 * Register Post Types.
	 */
	public static function register_post_types() {

		if ( ! post_type_exists( BASE_CLASS_NAME_Post_Types::POST_TYPE ) ) :

			register_post_type( BASE_CLASS_NAME_Post_Types::POST_TYPE,

				// Filter to modify post type.
				apply_filters( 'register_post_type_POST_TYPE_SINGULAR',
					[
						'labels'              => [
                            'name'               => _x( 'POST_TYPE_PLURAL', 'Post Type General Name', 'FILE_PREFIX' ),
                            'singular_name'      => _x( 'POST_TYPE_SINGULAR', 'Post Type Singular Name', 'FILE_PREFIX' ),
                            'add_new'            => __( 'Add New', 'FILE_PREFIX' ),
                            'add_new_item'       => __( 'Add New POST_TYPE_SINGULAR', 'FILE_PREFIX' ),
                            'edit_item'          => __( 'Edit POST_TYPE_SINGULAR', 'FILE_PREFIX' ),
                            'new_item'           => __( 'New POST_TYPE_SINGULAR', 'FILE_PREFIX' ),
                            'view_item'          => __( 'View POST_TYPE_SINGULAR', 'FILE_PREFIX' ),
                            'search_items'       => __( 'Search POST_TYPE_PLURAL', 'FILE_PREFIX' ),
                            'not_found'          => __( 'No POST_TYPE_SINGULAR found', 'FILE_PREFIX' ),
                            'not_found_in_trash' => __( 'No POST_TYPE_SINGULAR found in Trash', 'FILE_PREFIX' ),
                            'parent_item_colon'  => __( 'Parent POST_TYPE_SINGULAR:', 'FILE_PREFIX' ),
                            'menu_name'          => __( 'POST_TYPE_PLURAL', 'FILE_PREFIX' ),
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
							'slug'       => 'POST_TYPE_SINGULAR', // Can also be "about/team" or whatever.
							'with_front' => FALSE,
						],
						'capability_type'     => 'post',
					]
				)
			);

		endif;

	}/* POST_TYPE_END */

	/**
	 * Flush the rewrites.
	 */
	public static function flush_rewrites() {
		flush_rewrite_rules();
	}
}

BASE_CLASS_NAME_Post_Types::init();
