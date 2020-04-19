import React, {Component, Fragment} from 'react';
import pluralize from 'pluralize';
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
	Button,
	Typography
} from '@material-ui/core';

import {PhpClass, FileName, FunctionName} from './components/CodeSamples';
import {PostTypeLabels, TaxonomyLabels} from './components/PostTypeLabels';

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
			postTypeNames: '',
			postTypes: null,
			taxonomyNames: '',
			taxonomies: null,
			files: {
				functions: true,
				query: true,
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
	
	handlePostTypeBlur = (e) => {
		const postTypeNames = e.target.value
		if (postTypeNames.trim().length > 0) {
			const postTypesArray = postTypeNames.split(',')
			const postTypes = postTypesArray.map(type => {
				return (
					{
						singular: pluralize(type.trim(), 1),
						plural: pluralize(type.trim(), 2)
					}
				)
			})
			this.setState({postTypes})
		}
	}
	
	handleTaxonomiesBlur = (e) => {
		const taxonomiesNames = e.target.value
		if (taxonomiesNames.trim().length > 0) {
			const taxonomiesArray = taxonomiesNames.split(',')
			const taxonomies = taxonomiesArray.map(type => {
				return (
					{
						singular: pluralize(type.trim(), 1),
						plural: pluralize(type.trim(), 2)
					}
				)
			})
			this.setState({taxonomies})
		}
	}
	
	handleChange = (input, event) => {
		this.setState({[input]: event.target.value})
	}
	
	handleBlur = (input) => {
		// Trim whitespace on prop.
		this.setState({[input]: this.state[input].trim()})
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
		const {name, baseClassName, filePrefix, functionPrefix, postTypeNames, postTypes, taxonomyNames, taxonomies, files} = this.state
		// Get properties from files variable.
		const {functions, query, template} = files
		// Example code styles.
		const styles = {
			h3: {
				marginTop: 0,
				marginBottom: 0,
				// textTransform: 'uppercase',
				letterSpacing: 1,
				fontWeight: 600,
			},
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
			},
			grouping: {
				marginTop: 0,
				marginBottom: 0,
			},
			hr: {
				border: 'none',
				borderTop: '1px solid #ccc'
			}
		}
		return (
			<Container>
				
				<Typography variant="h2"
				            style={{margin: '30px 0'}}>
					OMS Boilerplate Plugin Generator
				</Typography>
				
				<Grid container spacing={4} style={styles.grouping}>
					<Grid item xs={5}>
						<TextField id="plugin-name"
						           label="Plugin Name"
						           required
						           style={{width: '100%', marginBottom: 30}}
						           value={name}
						           variant="outlined"
						           onChange={this.handlePluginNameChange}
						           onBlur={this.handlePluginNameBlur}
						/>
					</Grid>
				</Grid>
				
				<Box>
					<hr style={styles.hr}/>
				</Box>
				
				<Grid container spacing={4} style={styles.grouping}>
					<Grid item xs={12}>
						<Typography variant="h5">Class/File/Function Names</Typography>
					</Grid>
					<Grid item xs={5}>
						<TextField id="base-class-name"
						           label="Base Class Name"
						           disabled={baseClassName ? false : true}
						           variant="outlined"
						           style={{width: '100%', marginBottom: 30}}
						           value={baseClassName}
						           onChange={(e) => this.handleChange('baseClassName', e)}
						           onBlur={(e) => this.handleBlur('baseClassName', e)}
						           helperText={`Classes within plugin will be prepended with this value.`}
						/>
						<TextField id="file-prefix"
						           label="File Prefix"
						           disabled={filePrefix ? false : true}
						           variant="outlined"
						           style={{width: '100%', marginBottom: 30}}
						           value={filePrefix}
						           onChange={(e) => this.handleChange('filePrefix', e)}
						           onBlur={(e) => this.handleBlur('filePrefix', e)}
						           helperText={`Plugin directory name and file names will be prepended with this value.`}
						/>
						<TextField id="function-prefix"
						           label="Function Prefix"
						           style={{width: '100%', marginBottom: 30}}
						           variant="outlined"
						           value={functionPrefix}
						           disabled={functionPrefix ? false : true}
						           onChange={(e) => this.handleChange('functionPrefix', e)}
						           onBlur={(e) => this.handleBlur('functionPrefix', e)}
						           helperText={`Global functional functions will be prefixed with this value to prevent code collisions.`}
						/>
					</Grid>
					<Grid item xs={7}>
						{baseClassName && <PhpClass classBase={baseClassName}/>}
						{filePrefix && <FileName filePrefix={filePrefix}/>}
						{functionPrefix && <FunctionName functionPrefix={functionPrefix}/>}
					</Grid>
				</Grid>
				
				<Box>
					<hr style={styles.hr}/>
				</Box>
				
				<Grid container spacing={4} style={styles.grouping}>
					<Grid item xs={12}>
						<Typography variant="h5">Post Types</Typography>
					</Grid>
					<Grid item xs={5}>
						<TextField id="post-types-names"
						           label="Post Types"
						           style={{width: '100%', marginBottom: 30}}
						           variant="outlined"
						           value={postTypeNames}
						           onChange={(e) => this.handleChange('postTypeNames', e)}
						           onBlur={this.handlePostTypeBlur}
						/>
					</Grid>
					<Grid item xs={7}>
						{
							postTypes &&
							postTypes.map((postType, index) => {
								return (
									<PostTypeLabels key={index.toString()} index={index} fileName={filePrefix}
									                singular={postType.singular} plural={postType.plural}/>
								)
							})
						}
					</Grid>
				</Grid>
				
				<Box>
					<hr style={styles.hr}/>
				</Box>
				
				<Grid container spacing={4} style={styles.grouping}>
					<Grid item xs={12}>
						<Typography variant="h5">Taxonomies</Typography>
					</Grid>
					<Grid item xs={5}>
						<TextField id="taxonomies"
						           label="Taxonomies"
						           variant="outlined"
						           style={{width: '100%', marginBottom: 30}}
						           value={taxonomyNames}
						           onChange={(e) => this.handleChange('taxonomyNames', e)}
						           onBlur={this.handleTaxonomiesBlur}
						/>
					</Grid>
					<Grid item xs={7}>
						{
							taxonomies &&
							taxonomies.map((taxonomy, index) => {
								return (
									<TaxonomyLabels key={index.toString()} index={index} fileName={filePrefix}
									                singular={taxonomy.singular} plural={taxonomy.plural}/>
								)
							})
						}
					</Grid>
				</Grid>
				
				<Box>
					<hr style={styles.hr}/>
				</Box>
				
				<Grid container spacing={4} style={styles.grouping}>
					<Grid item xs={12}>
						<Typography variant="h5">Include Files</Typography>
					</Grid>
					<Grid item xs={5}>
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
					<Grid item xs={7}>
						{name &&
						<Fragment>
							{functions && (
								<div>
									<h5 style={styles.h5}>Functions File</h5>
									<code>
										<pre style={styles.code}><var style={{fontWeight: 'bold'}}>{filePrefix}</var>-functions.php</pre>
									</code>
								</div>
							)}
							{query && (
								<div style={{marginTop: functions ? 10 : 0}}>
									<h5 style={styles.h5}>Custom Query File</h5>
									<code>
										<pre style={styles.code}>{`class-`}<var
											style={{fontWeight: 'bold'}}>{filePrefix}</var>-query.php</pre>
									</code>
								</div>
							)}
							{template && (
								<div style={{marginTop: (functions || query) ? 10 : 0}}>
									<h5 style={styles.h5}>Template Loader File</h5>
									<code>
										<pre style={styles.code}>{`class-`}<var style={{fontWeight: 'bold'}}>{filePrefix}</var>-template-loader.php</pre>
									</code>
								</div>
							)
							}
						</Fragment>
						}
					</Grid>
				</Grid>
				
				<Box>
					<hr style={styles.hr}/>
				</Box>
				
				<Grid container spacing={4}>
					<Grid item xs={12} style={{margin: '30px 0 100px'}}>
						<Button variant="contained"
						        color="primary"
						        size="large"
						>Generate Plugin</Button>
					</Grid>
				</Grid>
				
			</Container>
		)
	}
}

export default App;
