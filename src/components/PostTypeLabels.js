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
		<h5 style={styles.h5}>Post Type: {plural}</h5>
		<code><pre style={styles.code}>{`$labels' => [
  'name'                  => _x( '${plural}', 'Post Type General Name', '${fileName}' ),
  'singular_name'         => _x( '${singular}', 'Post Type Singular Name', '${fileName}' ),
  'menu_name'             => __( '${plural}', '${fileName}' ),
  ...
]`}
		</pre></code>
	</div>
)

const TaxonomyLabels = ({singular, plural, fileName}) => (
	<div>
		<h5 style={styles.h5}>Taxonomy: {plural}</h5>
		<code><pre style={styles.code}>
			$labels = [<br/>
			&nbsp;&nbsp;'name'          => _x( '<b>{plural}</b>, 'Taxonomy General Name', '<b>{fileName}</b>' ),<br/>
			&nbsp;&nbsp;'singular_name' => _x( '<b>{singular}</b>', 'Taxonomy Singular Name', '<b>{fileName}</b>' ),<br/>
			&nbsp;&nbsp;'menu_name'     => __( '<b>{singular}</b>', '<b>{fileName}</b>' ),<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;...<br/>
			]
		</pre></code>
	</div>
)

export {
	PostTypeLabels,
	TaxonomyLabels
}
