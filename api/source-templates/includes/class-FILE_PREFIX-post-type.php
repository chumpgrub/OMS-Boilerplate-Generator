<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BASE_CLASS_NAME_Post_Types {

/* POST_TYPE_CONST */
/* TAXONOMY_CONST */
	/**
	 * Initiate post type and taxonomy registration hooks.
	 */
	public static function init() {
        /* POST_TYPE_START */add_action( 'init', [ __CLASS__, 'register_post_types' ], 5 );/* POST_TYPE_END */
        /* TAXONOMY_START */add_action( 'init', [ __CLASS__, 'register_taxonomies' ], 5 );/* TAXONOMY_END */
		register_activation_hook( __FILE__, [ __CLASS__, 'flush_rewrites' ] );
	}

    /**
     * Register Post Types.
     */
    public static function register_post_types() {
/* POST_TYPE_REGISTRATION */
    }

    /**
     * Register Taxonomies.
     */
    public static function register_taxonomies() {
/* TAXONOMY_REGISTRATION */
    }

	/**
	 * Flush the rewrites.
	 */
	public static function flush_rewrites() : void {
		flush_rewrite_rules();
	}

    /**
     * Get all constants in class in key/value array.
     * @return array
     */
	public static function getConstants() : array {
        $reflection = new ReflectionClass( __CLASS__ );
        return $reflection->getConstants();
    }

    /**
     * Get all post types from class.
     * @return array
     */
    public static function getPostTypes() : array {
        $consts = self::getConstants();
        if ( ! empty( $consts ) ) {
            $post_types = array_filter( $consts, function( $const ) {
                return strpos( $const,'POST_TYPE_' ) === 0;
            }, ARRAY_FILTER_USE_KEY );
            return array_values( $post_types );
        }

        return [];
    }

    /**
     * Get all taxonomies from class.
     * @return array
     */
    public static function getTaxonomies() : array  {
        $consts = self::getConstants();
        if ( ! empty( $consts ) ) {
            $taxonomies = array_filter( $consts, function( $const ) {
                return strpos( $const,'TAXONOMY_' ) === 0;
            }, ARRAY_FILTER_USE_KEY );
            return array_values( $taxonomies );
        }

        return [];
    }
}

BASE_CLASS_NAME_Post_Types::init();
