import React, {Component} from 'react';

import {
	Grid,
	Container,
	Box,
	TextField,
	FormGroup,
	FormLabel,
	FormControl,
	FormHelperText,
	FormControlLabel,
	Checkbox,
	Button
} from '@material-ui/core';

import {PhpClass, FileName, FunctionName} from './components/CodeSamples';

const getClassName = (name) => {
	return name.replace(/[\W_]+/g, '_')
}

const getFilePrefixName = (name) => {
	return name.toLowerCase().replace(/[\W_]+/g, '-')
}

const getFunctionPrefixName = (name) => {
	return name.toLowerCase().replace(/[\W_]+/g, '_')
}

class App extends Component {
	
	constructor(props) {
		super(props)
		this.state = {
			name: '',
			baseClassName: '',
			filePrefix: '',
			functionPrefix: '',
			postTypeName: '',
			taxonomies: '',
			files: {
				functions: true,
				query: false,
				template: true
			},
			errors: {}
		}
	}
	
	handlePluginNameChange = (e) => {
		this.setState({name: e.target.value})
	}
	
	handlePluginNameBlur = (e) => {
		const name = e.target.value.trim()
		const baseClassName = getClassName(name)
		const filePrefix = getFilePrefixName(name)
		const functionPrefix = getFunctionPrefixName(name)
		if (name.length) {
			this.setState({name, baseClassName, filePrefix, functionPrefix})
		}
	}
	
	handleChange = (input, event) => {
		this.setState({[input]: event.target.value})
	}
	
	handleBlur = (input) => {
		// Trim whitespace on prop.
		this.setState({[input]: this.state[input].trim()})
	}
	
	handleHover = (input) => {
		console.log(input)
	}
	
	handleFilesChange = (event) => {
		// Get files prop.
		const {files} = this.state
		// Update files object.
		files[event.target.name] = event.target.checked
		// Create new object with update files and replace to avoid mutation.
		this.setState({files: Object.assign({}, {...files})})
	}
	
	render() {
		// Get properties from state.
		const {name, baseClassName, filePrefix, functionPrefix, postTypeName, taxonomies, files} = this.state
		// Get properties from files variable.
		const {functions, query, template} = files
		// Example code styles.
		const codeStyles = {display: 'block', padding: '9px 10px', lineHeight: 1.5, color: '#999'}
		return (
			<Container>
				
				<Box fontFamily="fontFamily" fontSize={30} fontWeight={500} style={{margin: '30px 0'}}>
					OMS Boilerplate Plugin Generator
				</Box>
				
				<Grid container spacing={8}>
					<Grid item xs={6}>
						
						<TextField id="plugin-name"
						           label="Plugin Name"
						           required
						           style={{width: '100%', marginBottom: 30}}
						           value={name}
						           variant="outlined"
						           onChange={this.handlePluginNameChange}
						           onBlur={this.handlePluginNameBlur}
						           onMouseEnter={() => this.handleHover('name')}
						/>
						<TextField id="base-class-name"
						           label="Base Class Name"
						           disabled={baseClassName ? false : true}
						           variant="outlined"
						           style={{width: '100%', marginBottom: 30}}
						           value={baseClassName}
						           onChange={(e) => this.handleChange('baseClassName', e)}
						           helperText={`Classes within plugin will be prepended with this value.`}
						/>
						<TextField id="file-prefix"
						           label="File Prefix"
						           disabled={filePrefix ? false : true}
						           variant="outlined"
						           style={{width: '100%', marginBottom: 30}}
						           value={filePrefix}
						           onChange={(e) => this.handleChange('filePrefix', e)}
						           helperText={`Plugin directory name and file names will be prepended with this value.`}
						/>
						<TextField id="function-prefix"
						           label="Function Prefix"
						           style={{width: '100%', marginBottom: 30}}
						           variant="outlined"
						           value={functionPrefix}
						           disabled={functionPrefix ? false : true}
						           onChange={(e) => this.handleChange('functionPrefix', e)}
						           helperText={`Global functional functions will be prefixed with this value to prevent code collisions.`}
						/>
						<TextField id="post-type-name"
						           label="Post Type Name"
						           required
						           style={{width: '100%', marginBottom: 30}}
						           variant="outlined"
						           value={postTypeName}
						           onChange={(e) => this.handleChange('postTypeName', e)}
						/>
						<TextField id="taxonomies"
						           label="Taxonomies"
						           required
						           variant="outlined"
						           style={{width: '100%', marginBottom: 30}}
						           value={taxonomies}
						           onChange={(e) => this.handleChange('taxonomies', e)}
						/>
						
						<h3>Include Files</h3>
						<FormControl component="fieldset">
							<FormLabel component="legend">Choose files for you project</FormLabel>
							<FormGroup>
								<FormControlLabel
									control={
										<Checkbox name="functions"
										          checked={functions}
										          onChange={this.handleFilesChange}
										/>
									}
									label={`Functions File`}/>
								<FormControlLabel
									control={
										<Checkbox name="query"
										          checked={query}
										          onChange={this.handleFilesChange}
										/>
									}
									label={`Query File`}/>
								<FormControlLabel
									control={
										<Checkbox name="template"
										          checked={template}
										          onChange={this.handleFilesChange}
										/>
									}
									label={`Templating File`}/>
							</FormGroup>
							<FormHelperText>Select the files needed for your plugin.</FormHelperText>
						</FormControl>
					
					</Grid>
					
					<Grid item xs={6}>
						<div style={{position: 'sticky', 'top': 0}}>
							{baseClassName && <PhpClass classBase={baseClassName}/>}
							{filePrefix && <FileName filePrefix={filePrefix}/>}
							{functionPrefix && <FunctionName functionPrefix={functionPrefix}/>}
						</div>
					</Grid>
				
				</Grid>
				
				<Grid container>
					<Grid item xs={6} style={{marginTop: 20}}>
						{name &&
						<>
							<div className="code" style={{height: 40}}>
								{functions && <code style={codeStyles}>{`${filePrefix}-functions.php`}</code>}
							</div>
							<div className="code" style={{height: 40}}>
								{query && <code style={codeStyles}>{`class-${filePrefix}-query.php`}</code>}
							</div>
							<div className="code" style={{height: 40}}>
								{template && <code style={codeStyles}>{`class-${filePrefix}-template-loader.php`}</code>}
							</div>
						</>
						}
					</Grid>
				</Grid>
				<Box style={{marginTop: 30}}>
					<Button variant="contained" color="primary">Generate Plugin</Button>
				</Box>
				<Box>
				</Box>
			</Container>
		)
	}
}

export default App;
