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
import axios from 'axios';

import {PhpClass, FileName, FunctionName} from './components/CodeSamples';
import {PostTypeLabels, TaxonomyLabels} from './components/PostTypeLabels';

// const API_PATH = `http://localhost:8888/api/index.php`
// const API_PATH = `https://boilerbackend.markfurrow.com/api/index.php`

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
			pluginName: '',
			baseClassName: '',
			filePrefix: '',
			functionPrefix: '',
			postTypeNames: '',
			postTypes: null,
			taxonomyNames: '',
			taxonomies: null,
			includeFiles: {
				functions: true,
				query: true,
				template: true
			},
			otherSettings: {
				acf: true,
			},
			download: null,
			errors: {}
		}
	}
	
	componentDidMount() {
		let {download} = this.state
		if (download) {
			console.log('download here')
		} else {
			console.log('no download')
		}
	}
	
	handlePluginNameChange = (e) => {
		this.setState({pluginName: e.target.value})
	}
	
	handlePluginNameBlur = (e) => {
		const pluginName = e.target.value.trim()
		const baseClassName = getClassName(pluginName)
		const filePrefix = getFilePrefixName(pluginName)
		const functionPrefix = getFunctionPrefixName(pluginName)
		if (pluginName.length) {
			this.setState({pluginName, baseClassName, filePrefix, functionPrefix})
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
		const {includeFiles} = this.state
		// Update files object.
		includeFiles[event.target.name] = event.target.checked
		// Create new object with update files and replace to avoid mutation.
		this.setState({includeFiles: Object.assign({}, {...includeFiles})})
	}
	
	handleOtherSettingsChange = (event) => {
		// Get files prop.
		const {otherSettings} = this.state
		// Update files object.
		otherSettings[event.target.name] = event.target.checked
		// Create new object with update files and replace to avoid mutation.
		this.setState({otherSettings: Object.assign({}, {...otherSettings})})
	}

    // Change url - not sure how to use the env files for this : const API_PATH = `http://localhost:8888/api/
    // this.setState: not sure how to use the env files for this const API_PATH = `http://localhost:8888/api/`
	handleSubmit = (e) => {
		e.preventDefault()
		axios({
			method: 'post',
			url: `http://localhost:8888/api/index.php`,
			proxy: {
				host: 'localhost',
				port: 8888,
			},
			data: this.state
			})
			.then((body) => {
				this.setState({'download': `http://localhost:8888/api/tmp/${body.data}`})
			})
			.catch((error) => {
				console.log(error)
			})
	}
	
	render() {
		// Get properties from state.
		const {pluginName, baseClassName, filePrefix, functionPrefix, postTypeNames, postTypes, taxonomyNames, taxonomies, includeFiles, otherSettings, download} = this.state
		// Get properties from files variable.
		const {functions, query, template} = includeFiles
		// Get other properties.
		// Get other properties.
		const {acf} = otherSettings
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
				
				<Typography variant="h2" style={{margin: '30px 0'}}>OMS Boilerplate Plugin Generator</Typography>
				
				{download &&
					<Fragment>
						<Typography variant="h5" style={{marginBottom: 30}}>Your plugin starting point is ready!</Typography>
						<Button variant="contained"
						        color="primary"
						        size="large"
						        href={download}
						>Download your plugin</Button>
					</Fragment>
				}
				
				{!download &&
					<Fragment>
						<Grid container spacing={4} style={styles.grouping}>
							<Grid item xs={12} md={8} lg={5}>
								<TextField id="plugin-name"
								           label="Plugin Name"
								           required
								           style={{width: '100%', marginBottom: 30}}
								           value={pluginName}
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
							<Grid item xs={12} sm={5}>
								<TextField id="base-class-name"
								           label="Base Class Name"
								           disabled={baseClassName ? false : true}
								           variant="outlined"
								           style={{width: '100%', marginBottom: 30}}
								           value={baseClassName}
								           onChange={(e) => this.handleChange('baseClassName', e)}
								           onBlur={(e) => this.handleBlur('baseClassName', e)}
								           helperText={`Plugin classes will be prepended with this value.`}
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
								           helperText={`Global functions will be prefixed with this value to prevent code collisions.`}
								/>
							</Grid>
							<Grid item xs={12} sm={7}>
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
							<Grid item xs={12} sm={5}>
								<TextField id="post-types-names"
								           label="Post Types"
								           style={{width: '100%', marginBottom: 30}}
								           variant="outlined"
								           value={postTypeNames}
								           onChange={(e) => this.handleChange('postTypeNames', e)}
								           onBlur={this.handlePostTypeBlur}
								           helperText={`Add multiple post types by separating their names with a comma.`}
								/>
							</Grid>
							<Grid item xs={12} sm={7}>
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
							<Grid item xs={12} sm={5}>
								<TextField id="taxonomies"
								           label="Taxonomies"
								           variant="outlined"
								           style={{width: '100%', marginBottom: 30}}
								           value={taxonomyNames}
								           onChange={(e) => this.handleChange('taxonomyNames', e)}
								           onBlur={this.handleTaxonomiesBlur}
								           helperText={`Add multiple taxonomies by separating their names with a comma.`}
								/>
							</Grid>
							<Grid item xs={12} sm={7}>
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
							<Grid item xs={12} sm={5}>
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
							<Grid item xs={12} sm={7}>
								{pluginName &&
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
						
						<Grid container spacing={4} style={styles.grouping}>
							<Grid item xs={12}>
								<Typography variant="h5">Other Settings</Typography>
							</Grid>
							<Grid item xs={12}>
								<FormControl component="fieldset">
									<FormGroup>
										<FormControlLabel
											control={
												<Checkbox name="acf"
												          checked={acf}
												          onChange={this.handleOtherSettingsChange}
												/>
											}
											label={`Requires Advanced Custom Fields`}/>
									</FormGroup>
								</FormControl>
							</Grid>
						</Grid>
						
						<Box>
							<hr style={styles.hr}/>
						</Box>
						
						<Grid container spacing={4}>
							<Grid item xs={12} style={{margin: '30px 0 60px'}}>
								<Button variant="contained"
								        color="primary"
								        size="large"
								        onClick={this.handleSubmit}
								>Generate Plugin</Button>
							</Grid>
						</Grid>
					</Fragment>
					
				}
			
			</Container>
		)
	}
}

export default App;
