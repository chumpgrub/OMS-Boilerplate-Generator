import React from 'react';

const styles = {
	h5: {
		marginBottom: 10
	},
	code: {
		backgroundColor: '#f9f9f9',
		padding: 10,
		margin: 0
	}
}

const PostTypeLabels = ({singular, plural, fileName}) => (
	<div>
		<h5 style={styles.h5}>Post Type Names</h5>
		<code>
			<pre style={styles.code}>{`
'labels' => [
  'name'                  => _x( '${plural}', 'Post Type General Name', '${fileName}' ),
  'singular_name'         => _x( '${singular}', 'Post Type Singular Name', '${fileName}' ),
  'menu_name'             => __( '${plural}', '${fileName}' ),
  'name_admin_bar'        => __( '${singular}', '${fileName}' ),
  'archives'              => __( '${singular} Archives', '${fileName}' ),
  'attributes'            => __( '${singular} Attributes', '${fileName}' ),
  'parent_item_colon'     => __( 'Parent ${singular}:', '${fileName}' ),
  'all_items'             => __( 'All ${plural}', '${fileName}' ),
  'add_new_item'          => __( 'Add New ${singular}', '${fileName}' ),
  'add_new'               => __( 'Add New', '${fileName}' ),
  'new_item'              => __( 'New ${singular}', '${fileName}' ),
  'edit_item'             => __( 'Edit ${singular}', '${fileName}' ),
  'update_item'           => __( 'Update ${singular}', '${fileName}' ),
  'view_item'             => __( 'View ${singular}', '${fileName}' ),
  'view_items'            => __( 'View ${plural}', '${fileName}' ),
  'search_items'          => __( 'Search ${singular}', '${fileName}' ),
  'not_found'             => __( 'Not found', '${fileName}' ),
  'not_found_in_trash'    => __( 'Not found in Trash', '${fileName}' ),
  'featured_image'        => __( 'Featured Image', '${fileName}' ),
  'set_featured_image'    => __( 'Set featured image', '${fileName}' ),
  'remove_featured_image' => __( 'Remove featured image', '${fileName}' ),
  'use_featured_image'    => __( 'Use as featured image', '${fileName}' ),
  'insert_into_item'      => __( 'Insert into ${singular.toLowerCase()}', '${fileName}' ),
  'uploaded_to_this_item' => __( 'Uploaded to this ${singular.toLowerCase()}', '${fileName}' ),
  'items_list'            => __( '${plural} list', '${fileName}' ),
  'items_list_navigation' => __( '${plural} list navigation', '${fileName}' ),
  'filter_items_list'     => __( 'Filter ${plural.toLowerCase()} list', '${fileName}' ),
]
			`}
			</pre>
		</code>
	</div>
)

export default PostTypeLabels;
