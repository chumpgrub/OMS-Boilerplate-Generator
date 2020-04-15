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

import CodeSample from './components/CodeSample';

const filePrefixName = (name) => {
	return name.toLowerCase().replace(/[\W_]+/g, '-')
}

class App extends Component {
	
	constructor(props) {
		super(props)
		this.state = {
			name: '',
			filePrefix: '',
			customFilePrefix: false,
			codePrefix: '',
			customCodePrefix: false,
			baseClassName: '',
			postTypeName: '',
			taxonomies: '',
			files: {
				functions: true,
				query: false,
				template: true
			}
		}
	}
	
	handleChange = (input, event) => {
		this.setState({[input]: event.target.value})
		switch (input) {
			case 'customFilePrefix':
				this.setState({customCodePrefix: filePrefixName()})
				break;
		}
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
		const {name, filePrefix, customFilePrefix, codePrefix, customCodePrefix, baseClassName, postTypeName, taxonomies, files} = this.state
		const {functions, query, template} = files
		// const filePrefix = name.toLowerCase().replace(/[\W_]+/g, '-')
		// const codePrefix = name.toLowerCase().replace(/[\W_]+/g, '_')
		// console.log(filePrefix, codePrefix)
		const codeStyles = {display: 'block', padding: '9px 10px', lineHeight: 1.5, color: '#999'}
		return (
			<Container>
				
				<Box fontFamily="fontFamily" fontSize={30} fontWeight={500} style={{margin: '30px 0'}}>
					OMS Boilerplate Plugin Generator
				</Box>
				
				<Grid container spacing={8}>
					<Grid item xs={4}>
						
						<TextField id="plugin-name"
						           label="Plugin Name"
						           required
						           style={{width: '100%'}}
						           value={name}
						           onChange={(e) => this.handleChange('name', e)}
						           onBlur={(e) => this.handleBlur('name')}
						           onMouseEnter={() => this.handleHover('name')}
						/>
						<TextField id="code-prefix"
						           label="Custom Code Prefix"
						           style={{width: '100%'}}
						           value={codePrefix}
						           onChange={(e) => this.handleChange('codePrefix', e)}
						           helperText={`Plugin directory name and file names will be prepended with this value.`}
						/>
						<TextField id="base-class-name"
						           label="Base Class Name"
						           required
						           style={{width: '100%'}}
						           value={baseClassName}
						           onChange={(e) => this.handleChange('baseClassName', e)}
						/>
						<TextField id="post-type-name"
						           label="Post Type Name"
						           required
						           style={{width: '100%'}}
						           value={postTypeName}
						           onChange={(e) => this.handleChange('postTypeName', e)}
						/>
						<TextField id="taxonomies"
						           label="Taxonomies"
						           required
						           style={{width: '100%'}}
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
					
					<Grid item xs={8}>
						<CodeSample codePrefix={codePrefix} filePrefix={filePrefix}/>
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
