<?php

/**
 * Get template part.
 *
 * @param mixed $slug
 * @param string $name (default: '')
 */
function FUNCTION_PREFIX_get_template_part( mixed $slug, string $name = '' ) :void {

	$template = '';

	// Look in your_theme/FILE_PREFIX/slug-name.php
	if ( $name ) {
        $template = locate_template( array( "FILE_PREFIX/{$slug}-{$name}.php" ) );
    }

	// Look in your_theme/FILE_PREFIX/slug.php
	if ( ( ! $template && ! $name ) && file_exists( get_stylesheet_directory() . "/FILE_PREFIX/{$slug}.php" ) ) {
        $template = locate_template( array( "FILE_PREFIX/{$slug}.php" ) );
    }

	// Get default slug-name.php
	if ( ! $template && $name && file_exists( BASE_CLASS_NAME::plugin_path() . "/templates/{$slug}-{$name}.php" ) ) {
        $template = BASE_CLASS_NAME::plugin_path() . "/templates/{$slug}-{$name}.php";
    }

	// Get default slug.php
	if ( ( ! $template && ! $name ) && file_exists( BASE_CLASS_NAME::plugin_path() . "/templates/{$slug}.php" ) ) {
        $template = BASE_CLASS_NAME::plugin_path() . "/templates/{$slug}.php";
    }

	if ( $template ) {
        load_template( $template, FALSE );
    }

}

/**
 * Callback for the block template from the block.json
 *
 * @return void
 */
function block_render_template() : void {
    $template = '';

    if ( file_exists( get_stylesheet_directory() . '/FILE_PREFIX/block/block-render-template.php' ) ) :
        $template = locate_template( array('FILE_PREFIX/block/block-render-template.php') );
    else :
        $template = BASE_CLASS_NAME::plugin_path() . '/templates/block/block-render-template.php';
    endif;

    ob_start();

    if ( $template ) {
        load_template( $template, FALSE );
    }

    $content = ob_get_clean();
    echo $content;
}