<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

$data = '{
  "pluginName": "OMS Events",
  "baseClassName": "OMS_Events",
  "filePrefix": "oms-events",
  "functionPrefix": "oms_events",
  "postTypeNames": "Events",
  "postTypes": [
    {
      "singular": "Event",
      "plural": "Events"
    }
  ],
  "taxonomyNames": "Category, Location",
  "taxonomies": [
    {
      "singular": "Category",
      "plural": "Categories"
    },
    {
      "singular": "Location",
      "plural": "Locations"
    }
  ],
  "includeFiles": {
    "functions": false,
    "template": false,
    "query": true
  },
  "errors": {}
}';

(new OMS_Boilerplate($data))->getPluginCode();

?>

<?php class OMS_Boilerplate
{

    const SEARCH = [
        'PLUGIN_NAME',
        'BASE_CLASS_NAME',
        'FILE_PREFIX',
        'FUNCTION_PREFIX',
    ];

    const SEARCH_INCLUDES = [
        'INCLUDE_FUNCTIONS',
        'INCLUDE_TEMPLATE',
        'INCLUDE_QUERY',
        'INCLUDE_POST_TYPE',
    ];

    protected $sourceDir;
    protected $destinationDir;
    protected $data;

    public $hasPostTypes = false;
    public $hasTaxonomies = false;

    public $includeFunctions = true;
    public $includeQuery = true;
    public $includeTemplate = true;

    protected $includeReplacements = [
        'functions' => "
        // Global functions necessary for plugin.
        require_once plugin_dir_path( __FILE__ ) . 'includes/FILE_PREFIX-functions.php';",
        'template' => "
        // Support for plugin-level templating.
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-FILE_PREFIX-template-loader.php';",
        'query' => "
        // Query customization are handled here.
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-FILE_PREFIX-query.php';",
        'postTypes' => "
        // Post Type, Taxonomy and Term Definitions.
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-FILE_PREFIX-post-type.php';",
    ];

    public function __construct($data = false)
    {
        define('SOURCE_DIR', dirname(__FILE__) . '/source-templates');
//        $this->data = json_decode(file_get_contents('php://input', true));
        $this->data = json_decode($data, true);
        $this->setProperties();
        $this->setDestinationDirectory();
        // Set params.
        // Set plugin dir.
        // Get contents for each file.
        // String replace tokens.
        // Write files with new file names to plugin dir.
        // Force download.
        // Empty plugin dir.
        $this->getSourceFiles();
    }

    /**
     * Dynamically set class properties from $data.
     */
    private function setProperties()
    {
        $data = $this->data;
        foreach ($data as $property => $value) {

            if ('filePrefix' == $property) {

                $this->filePrefix = (!empty($value)) ? $value : 'oms-plugin';

            } elseif (is_array($value) && !empty($value)) {

                switch ($property) {
                    case 'postTypes':
                        $this->{$property} = $value;
                        $this->hasPostTypes = true;
                        break;
                    case 'taxonomies':
                        $this->{$property} = $value;
                        $this->hasTaxonomies = true;
                        break;
                    case 'includeFiles':
                        $this->{$property} = $value;
                        $this->includeFunctions = ((bool)$value['functions']) ? true : false;
                        $this->includeQuery = ($value['query']) ? true : false;
                        $this->includeTemplate = ($value['template']) ? true : false;
                        break;
                    default:
                        $this->{$property} = $value;
                        break;
                }

            } else {

                $this->{$property} = filter_var($value, FILTER_SANITIZE_STRING);

            }
        }
//        echo '<pre>' . print_r($data, true) . '</pre>';
    }

    /**
     * Properties containing values that will replace the tokens.
     *
     * @return array
     */
    public function getReplacementProperties()
    {
        return [
            $this->pluginName,
            $this->baseClassName,
            $this->filePrefix,
            $this->functionPrefix,
        ];
    }

    private function setDestinationDirectory()
    {
        // Ensure we have a destination directory.
        $pluginDir = $this->filePrefix ? $this->filePrefix : 'oms-plugin';

        define('DESTINATION_DIR', dirname(__FILE__) . '/destination-templates/' . $pluginDir);

        if (!is_dir(DESTINATION_DIR)) {
            mkdir(DESTINATION_DIR);
        }
    }

    private function getSourceFiles()
    {

        // Get files and directories, exclude '.' and '..'.
        $files = array_diff(scandir(SOURCE_DIR), ['.', '..', 'templates', 'includes']);
        $includeFiles = array_diff(scandir(SOURCE_DIR . '/includes'), ['.', '..']);
        $templateFiles = array_diff(scandir(SOURCE_DIR . '/templates'), ['.', '..']);

        echo '<h4>Files</h4>';
        echo '<pre>' . print_r($files, true) . '</pre>';
        echo '<h4>Includes</h4>';
        echo '<pre>' . print_r($includeFiles, true) . '</pre>';
        echo '<h4>Templates</h4>';
        echo '<pre>' . print_r($templateFiles, true) . '</pre>';

        $this->writeRootFiles();
        $this->writeIncludeFiles();
        $this->writeTemplateFiles();

    }

    private function mainPluginFile($sourceFile)
    {

        $replacements = $this->includeReplacements;

        if (!$this->includeFunctions) {
            $replacements['functions'] = '';
        }

        if (!$this->includeTemplate) {
            $replacements['template'] = '';
        }

        if (!$this->includeQuery) {
            $replacements['query'] = '';
        }

        if (!$this->hasPostTypes) {
            $replacements['postTypes'] = '';
        }

        // String replacement operation on file contents.
        return str_replace(self::SEARCH_INCLUDES, $replacements, $sourceFile);
    }

    private function writeFile($sourceFile, $file, $dir = FALSE)
    {
        $destinationDir = $dir ? DESTINATION_DIR . '/' . $dir : DESTINATION_DIR;

        // String replacement operation on file contents.
        $newFile = str_replace(self::SEARCH, $this->getReplacementProperties(), $sourceFile);

        // String replacement on source file name for use in new file name.
        $handle = fopen(str_replace(self::SEARCH, $this->getReplacementProperties(), $destinationDir . '/' . $file), 'w');

        // Write updated file content to new destination file.
        fwrite($handle, $newFile);

    }

    private function writeRootFiles()
    {
        // Get files, but exclude '.', '..', 'templates', and 'includes'.
        $files = array_diff(scandir(SOURCE_DIR), ['.', '..', 'templates', 'includes']);

        foreach ($files as $file) {

            $currentFile = SOURCE_DIR . '/' . $file;

            if (!is_dir($currentFile) && file_exists($currentFile)) {

                // Read file contents into variable.
                $sourceFile = file_get_contents($currentFile);

                // Special operation for main plugin file.
                if ($file === 'FILE_PREFIX.php') {
                    // Move this stuff into current method.
                    $sourceFile = $this->mainPluginFile($sourceFile);
                }

                $this->writeFile($sourceFile, $file);
            }
        }

    }

    private function writeIncludeFiles()
    {
        // Get files and exclude '.' and '..'.
        $files = array_diff(scandir(SOURCE_DIR . '/includes'), ['.', '..']);

        // Flip array to easily remove unnecessary files by key.
        $files = array_flip($files);

        if (!$this->includeFunctions) {
            unset($files['FILE_PREFIX-functions.php']);
        }

        if (!$this->includeTemplate) {
            unset($files['class-FILE_PREFIX-template-loader.php']);
        }

        if (!$this->includeQuery) {
            unset($files['class-FILE_PREFIX-query.php']);
        }

        if (!$this->hasPostTypes) {
            unset($files['class-FILE_PREFIX-post-type.php']);
        }

        // Return array key/value orientation.
        $files = array_flip($files);

        if (!is_dir(DESTINATION_DIR . '/includes') && !empty($files)) {

            mkdir(DESTINATION_DIR . '/includes');

            foreach($files as $file) {

                $currentFile = SOURCE_DIR . '/includes/' . $file;

                if (!is_dir($currentFile) && file_exists($currentFile)) {

                    // Read file contents into variable.
                    $sourceFile = file_get_contents($currentFile);

                    $this->writeFile($sourceFile, $file, 'includes');

                }
            }
        }
    }

    private function writeTemplateFiles()
    {
        // Get files and exclude '.' and '..'.
        $files = array_diff(scandir(SOURCE_DIR . '/templates'), ['.', '..']);

        if (!is_dir(DESTINATION_DIR . '/templates') && !empty($files)) {

            mkdir(DESTINATION_DIR . '/templates');

            foreach($files as $file) {

                $currentFile = SOURCE_DIR . '/templates/' . $file;

                if (!is_dir($currentFile) && file_exists($currentFile)) {

                    // Read file contents into variable.
                    $sourceFile = file_get_contents($currentFile);

                    $this->writeFile($sourceFile, $file, 'templates');

                }
            }
        }
    }

    public function getPluginCode()
    {
//        echo json_encode($this->data);
    }

}
