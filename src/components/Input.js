import React, {useState} from 'react';

const Input = ({name, type, value, handleChange}) => {
	console.log(value)
	return (
		<input className={name} type={type} value={value} onChange={handleChange}/>
	)
}

export default Input;
