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
    "functions": true,
    "query": true,
    "template": false
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
        'POST_TYPE_NAMES',
    ];

    protected $sourceDir;
    protected $destinationDir;
    protected $data;

    public $hasPostTypes = false;
    public $hasTaxonomies = false;

    public $includeFunctions = true;
    public $includeQuery = true;
    public $includeTemplate = true;

    public function __construct($data = false)
    {
//        $this->data = json_decode(file_get_contents('php://input', true));
        $this->data = json_decode($data, true);
        $this->setProperties();
        $this->setSourceDirectory();
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

            if (is_array($value) && ! empty($value)) {

                switch($property) {
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
                        $this->includeFunctions = ((bool) $value['functions']) ? true : false;
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
        echo '<pre>' . print_r($data, true) . '</pre>';
    }

    /**
     * Properties containing values that will replace the tokens.
     *
     * @return array
     */
    public function getReplacementProperties() {
        return [
            $this->pluginName,
            $this->baseClassName,
            $this->filePrefix,
            $this->functionPrefix,
        ];
    }

    private function setSourceDirectory()
    {
        $this->sourceDir = dirname(__FILE__) . '/source-templates/';
    }

    public function getSourceDirectory()
    {
        return $this->sourceDir;
    }

    private function setDestinationDirectory()
    {
        $this->destinationDir = dirname(__FILE__) . '/destination-templates/' . $this->filePrefix;
        if ( ! is_dir( $this->destinationDir ) ) {
            mkdir( $this->destinationDir );
        }
    }

    private function getSourceFiles()
    {
        $sourceDir = $this->getSourceDirectory();
//        $file = file_get_contents($sourceDir . 'test.php');
        // Get files and directories, exclude '.' and '..'.
        $files = array_diff(scandir($sourceDir), ['.','..']);
        echo '<pre>' . print_r($files, true) . '</pre>';
        foreach($files as $file) {
            $currentFile = $sourceDir.'/'.$file;
            if (is_dir($currentFile)) {

            }
            if (file_exists($currentFile)) {
                $sourceFile = file_get_contents($currentFile);
                $newFile = str_replace(self::SEARCH, $this->getReplacementProperties(), $sourceFile);
                echo '<pre>' . print_r( str_replace(self::SEARCH, $this->getReplacementProperties(), $file), true) . '</pre>';
                $handle = fopen( str_replace(self::SEARCH, $this->getReplacementProperties(), $file), 'w' );
            }
        }
//        var_dump(dirname(__FILE__) . 'test.php');
//        var_dump(file_exists(dirname(__FILE__) . 'test.php'));
//        echo $file;


    }

    public function getPluginCode()
    {
//        echo json_encode($this->data);
    }

}
