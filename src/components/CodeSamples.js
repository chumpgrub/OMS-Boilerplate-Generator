import React from 'react';

const codeStyles = {
	backgroundColor: '#f9f9f9',
	padding: 10,
	margin: 0
}

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

const PhpClass = ({classBase}) => (
	<div>
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
	<div>
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
	<div>
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

