<?php

/**
 * Get template part.
 *
 * @param mixed $slug
 * @param string $name (default: '')
 */
function FUNCTION_PREFIX_get_template_part( $slug, $name = '' ) {

	$template = '';

	// Look in yourtheme/FILE_PREFIX/slug-name.php
	if ( $name ) :
		$template = locate_template( array( "FILE_PREFIX/{$slug}-{$name}.php" ) );
	endif;

	// Look in yourthme/FILE_PREFIX/slug.php
	if ( ( ! $template && ! $name ) && file_exists( get_stylesheet_directory() . "/FILE_PREFIX/{$slug}.php" ) ) :
		$template = locate_template( array( "FILE_PREFIX/{$slug}.php" ) );
	endif;

	// Get default slug-name.php
	if ( ! $template && $name && file_exists( BASE_CLASS_NAME::plugin_path() . "/templates/{$slug}-{$name}.php" ) ) :
		$template = BASE_CLASS_NAME::plugin_path() . "/templates/{$slug}-{$name}.php";
	endif;

	// Get default slug.php
	if ( ( ! $template && ! $name ) && file_exists( BASE_CLASS_NAME::plugin_path() . "/templates/{$slug}.php" ) ) :
		$template = BASE_CLASS_NAME::plugin_path() . "/templates/{$slug}.php";
	endif;

	if ( $template ) :
		load_template( $template, FALSE );
	endif;

}


/**
 * Gets the edit link for PLUGIN_NAME.
 *
 * @param int $record_id ID of particular PLUGIN_NAME.
 * @todo Consider replacing token in dockblock with something more specific
 * once single or multiple post type direction is determined.
 * @return string
 */
function FUNCTION_PREFIX_get_edit_link( $record_id ) {

	if ( is_user_logged_in() ) :

		// Get current user.
		$user = wp_get_current_user();

		// Roles with permission to edit content.
		$allowed_users = apply_filters( 'FUNCTION_PREFIX_edit_permissions', array(
			'client_admin',
			'administrator'
		) );

		if ( ! empty( $user->roles ) ) :

			// If the current user roles intersect with the allowed users array, then proceed.
			if ( array_intersect( $allowed_users, $user->roles ) ) :

				return '
                    <div class="editLink">
                        <a target="_blank" href="' . get_edit_post_link( $record_id ) . '">Edit</a>
                    </div>
                ';

			endif;
		endif;
	endif;
}
