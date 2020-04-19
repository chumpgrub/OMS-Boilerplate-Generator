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

const PhpClass = ({classBase}) => (
	<div style={{marginTop: '-19px'}}>
		<h5 style={styles.h5}>Base Class Name</h5>
		<code>
			<pre style={styles.code}>
				&lt;?php<br/><br/>
				class <var style={{fontWeight: 'bold'}}>{classBase}</var>_Query {`{`}<br/>
				&nbsp;&nbsp;&hellip;
			</pre>
		</code>
	</div>
)

const FileName = ({filePrefix}) => (
	<div style={{marginTop: 10}}>
		<h5 style={styles.h5}>File Name Prefix</h5>
		<code>
			<pre style={styles.code}>
				plugins/<var style={{fontWeight: 'bold'}}>{filePrefix}</var>/includes/<var
				style={{fontWeight: 'bold'}}>{filePrefix}</var>-template-loader.php
			</pre>
		</code>
	</div>
)

const FunctionName = ({functionPrefix}) => (
	<div style={{marginTop: 10}}>
		<h5 style={styles.h5}>Function Name Prefix</h5>
		<code>
				<pre style={styles.code}>
					function <var
					style={{fontWeight: 'bold'}}>{functionPrefix}</var>_get_template_part( $slug, $name = '' ) {`{`}<br/>
					&nbsp;&nbsp;&hellip;
				</pre>
		</code>
	</div>
)

export {
	PhpClass,
	FileName,
	FunctionName
}

