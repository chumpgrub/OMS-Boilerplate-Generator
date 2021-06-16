<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Coffee_Shop_Post_Types {

	// Post Type Coffee.
	const POST_TYPE_COFFEE = 'coffee';

	// Taxonomy Category.
	const TAXONOMY_CATEGORY = 'coffee-shop_category';

	/**
	 * Initiate post type and taxonomy registration hooks.
	 */
	public static function init() {
        add_action( 'init', [ __CLASS__, 'register_post_types' ], 5 );
        add_action( 'init', [ __CLASS__, 'register_taxonomies' ], 5 );
		register_activation_hook( __FILE__, [ __CLASS__, 'flush_rewrites' ] );
	}

    /**
     * Register Post Types.
     */
    public static function register_post_types() {

		if ( ! post_type_exists( Coffee_Shop_Post_Types::POST_TYPE_COFFEE ) ) :

			register_post_type( Coffee_Shop_Post_Types::POST_TYPE_COFFEE,

				// Filter to modify post type.
				apply_filters( 'register_post_type_coffees',
					[
						'labels'              => [
                            'name'               => _x( 'Coffees', 'Post Type General Name', 'coffee-shop' ),
                            'singular_name'      => _x( 'Coffee', 'Post Type Singular Name', 'coffee-shop' ),
                            'add_new'            => __( 'Add New', 'coffee-shop' ),
                            'add_new_item'       => __( 'Add New Coffee', 'coffee-shop' ),
                            'edit_item'          => __( 'Edit Coffee', 'coffee-shop' ),
                            'new_item'           => __( 'New Coffee', 'coffee-shop' ),
                            'view_item'          => __( 'View Coffee', 'coffee-shop' ),
                            'search_items'       => __( 'Search Coffees', 'coffee-shop' ),
                            'not_found'          => __( 'No Coffee found', 'coffee-shop' ),
                            'not_found_in_trash' => __( 'No Coffee found in Trash', 'coffee-shop' ),
                            'parent_item_colon'  => __( 'Parent Coffee:', 'coffee-shop' ),
                            'menu_name'          => __( 'Coffees', 'coffee-shop' ),
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
							'slug'       => 'coffees', // Can also be "about/team" or whatever.
							'with_front' => FALSE,
						],
						'capability_type'     => 'post',
					]
				)
			);

		endif;

    }

    /**
     * Register Taxonomies.
     */
    public static function register_taxonomies() {

		if ( ! taxonomy_exists( Coffee_Shop_Post_Types::TAXONOMY_CATEGORY ) ) :

			register_taxonomy( Coffee_Shop_Post_Types::TAXONOMY_CATEGORY,

				// Filter to modify on which post types this taxonomy can appear.
				apply_filters( 'oms_taxonomy_objects_categories', self::getPostTypes() ),

				// Filter to modify taxonomy.
				apply_filters( 'oms_taxonomy_args_categories', [
					'hierarchical'      => TRUE,
					'label'             => __( 'Categories', 'coffee-shop' ),
					'labels'            => [
						'name'              => __( 'Categories', 'coffee-shop' ),
						'singular_name'     => __( 'Category', 'coffee-shop' ),
						'menu_name'         => _x( 'Categories', 'Admin menu name', 'coffee-shop' ),
						'search_items'      => __( 'Search Categories', 'coffee-shop' ),
						'all_items'         => __( 'All Categories', 'coffee-shop' ),
						'parent_item'       => __( 'Parent Category', 'coffee-shop' ),
						'parent_item_colon' => __( 'Parent Category:', 'coffee-shop' ),
						'edit_item'         => __( 'Edit Category', 'coffee-shop' ),
						'update_item'       => __( 'Update Category', 'coffee-shop' ),
						'add_new_item'      => __( 'Add New Category', 'coffee-shop' ),
						'new_item_name'     => __( 'New Category Name', 'coffee-shop' ),
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
	 * Flush the rewrites.
	 */
	public static function flush_rewrites() {
		flush_rewrite_rules();
	}

    /**
     * Get all constants in class in key/value array.
     * @return array
     */
	public static function getConstants() {
        $reflection = new ReflectionClass( __CLASS__ );
        return $reflection->getConstants();
    }

    /**
     * Get all post types from class.
     * @return array
     */
    public static function getPostTypes() {
        $consts = self::getConstants();
        if ( ! empty( $consts ) ) {
            $post_types = array_filter( $consts, function( $const ) {
                return strpos( $const,'POST_TYPE_' ) === 0;
            }, ARRAY_FILTER_USE_KEY );
            return array_values( $post_types );
        }
    }

    /**
     * Get all taxonomies from class.
     * @return array
     */
    public static function getTaxonomies() {
        $consts = self::getConstants();
        if ( ! empty( $consts ) ) {
            $taxonomies = array_filter( $consts, function( $const ) {
                return strpos( $const,'TAXONOMY_' ) === 0;
            }, ARRAY_FILTER_USE_KEY );
            return array_values( $taxonomies );
        }
    }
}

Coffee_Shop_Post_Types::init();
