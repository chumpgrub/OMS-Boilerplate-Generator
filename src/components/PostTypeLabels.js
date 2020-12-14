import React from 'react';

const styles = {
	h5: {
		position: 'relative',
		top: 9,
		left: 20,
		margin: 0,
		display: 'inline-block',
		padding: '2px 5px',
		backgroundColor: '#fff',
		color: 'rgba(0, 0, 0, 0.54)',
		fontWeight: 400,
		fontSize: 12,
		borderRadius: 3,
	},
	code: {
		borderRadius: 4,
		border: '1px solid #ddd',
		backgroundColor: '#f9f9f9',
		padding: '30px 20px 20px 20px',
		margin: 0
	}
}

const PostTypeLabels = ({index, singular, plural, fileName}) => (
	<div style={{marginTop: index > 0 ? 10 : '-19px'}}>
		<h5 style={styles.h5}>Post Type: {plural}</h5>
		<code><pre style={styles.code}>
			$labels = [<br/>
			&nbsp;&nbsp;'name'          => _x( '<b>{plural}</b>', 'Post Type General Name', '<b>{fileName}</b>' ),<br/>
			&nbsp;&nbsp;'singular_name' => _x( '<b>{singular}</b>', 'Post Type Singular Name', '<b>{fileName}</b>' ),<br/>
			&nbsp;&nbsp;'menu_name'     => __( '<b>{singular}</b>', '<b>{fileName}</b>' ),<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;...<br/>
			]
		</pre></code>
	</div>
)

const TaxonomyLabels = ({index, singular, plural, fileName}) => (
	<div style={{marginTop: index > 0 ? 10 : '-19px'}}>
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
