import React from 'react';
import InputLabel from './InputLabel';

const FormGroup = (props) => {
	const {label} = props
	return (
		<div className={`formGroup`}>
			{label && <InputLabel label={label}/>}
			{props.children}
		</div>
	)
}

export default FormGroup;
