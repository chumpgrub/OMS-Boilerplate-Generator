<?php

/**
 * Get template part.
 *
 * @param mixed $slug
 * @param string $name (default: '')
 */
function coffee_shop_get_template_part( $slug, $name = '' ) {

	$template = '';

	// Look in yourtheme/coffee-shop/slug-name.php
	if ( $name ) {
        $template = locate_template( array( "coffee-shop/{$slug}-{$name}.php" ) );
    }

	// Look in yourthme/coffee-shop/slug.php
	if ( ( ! $template && ! $name ) && file_exists( get_stylesheet_directory() . "/coffee-shop/{$slug}.php" ) ) {
        $template = locate_template( array( "coffee-shop/{$slug}.php" ) );
    }

	// Get default slug-name.php
	if ( ! $template && $name && file_exists( Coffee_Shop::plugin_path() . "/templates/{$slug}-{$name}.php" ) ) {
        $template = Coffee_Shop::plugin_path() . "/templates/{$slug}-{$name}.php";
    }

	// Get default slug.php
	if ( ( ! $template && ! $name ) && file_exists( Coffee_Shop::plugin_path() . "/templates/{$slug}.php" ) ) {
        $template = Coffee_Shop::plugin_path() . "/templates/{$slug}.php";
    }

	if ( $template ) {
        load_template( $template, FALSE );
    }

}


/**
 * Gets the edit link for Coffee Shop.
 *
 * @param int $record_id ID of particular Coffee Shop.
 * @return string
 */
function coffee_shop_get_edit_link( $record_id ) {

	if ( is_user_logged_in() ) {

        // Get current user.
        $user = wp_get_current_user();

        // Roles with permission to edit content.
        $allowed_users = apply_filters( 'coffee_shop_edit_permissions', [
            'client_admin',
            'administrator',
        ] );

        if ( ! empty( $user->roles ) ) {

            // If the current user roles intersect with the allowed users array, then proceed.
            if ( array_intersect( $allowed_users, $user->roles ) ) {

                return '
                    <div class="editLink">
                        <a target="_blank" href="' . get_edit_post_link( $record_id ) . '">Edit</a>
                    </div>
                ';
            }
        }
    }
}
