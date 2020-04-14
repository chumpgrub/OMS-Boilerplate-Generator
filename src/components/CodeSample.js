import React from 'react';

const CodeSample = ({codePrefix, filePrefix}) => (
	<code><pre>{
		`
function ${codePrefix}_get_template_part( $slug, $name = '' ) {

\t$template = '';

\t// Look in yourtheme/${filePrefix}/slug-name.php
\tif ( $name ) :
\t\t$template = locate_template( array( "${filePrefix}/{$slug}-{$name}.php" ) );
\tendif;

\t// Look in yourtheme/${filePrefix}/slug.php
\tif ( ( ! $template && ! $name ) && file_exists( get_stylesheet_directory() . "/${filePrefix}/{$slug}.php" ) ) :
\t\t$template = locate_template( array( "${filePrefix}/{$slug}.php" ) );
\tendif;

\t// Get default slug-name.php
\tif ( ! $template && $name && file_exists( ${filePrefix}::plugin_path() . "/templates/{$slug}-{$name}.php" ) ) :
\t\t$template = ${filePrefix}::plugin_path() . "/templates/{$slug}-{$name}.php";
\tendif;

\t// Get default slug.php
\tif ( ( ! $template && ! $name ) && file_exists( ${filePrefix}::plugin_path() . "/templates/{$slug}.php" ) ) :
\t\t$template = ${filePrefix}::plugin_path() . "/templates/{$slug}.php";
\tendif;

\tif ( $template ) :
\t\tload_template( $template, FALSE );
\tendif;

}
		`
	}</pre></code>
)

export default CodeSample;
