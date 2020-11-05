<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

$data = '{
  "pluginName": "OMS Events",
  "baseClassName": "OMS_Events",
  "filePrefix": "oms-events",
  "functionPrefix": "oms_events",
  "postTypeNames": "Events, Webinars",
  "postTypes": [
    {
      "singular": "Event",
      "plural": "Events"
    },
    {
      "singular": "Webinar",
      "plural": "Webinars"
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
    "template": true,
    "query": true
  },
  "errors": {}
}';

$boilerplate = new OMS_Boilerplate($data);
$boilerplate->getPluginCode();
echo '<pre>' . print_r($boilerplate, true) . '</pre>';
//echo '<pre>' . print_r($boilerplate->getConstants(), true) . '</pre>';
//(new OMS_Boilerplate($data))->getPluginCode();

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

    public function __construct($data = false)
    {

        include 'class-test.php';
        echo '<pre>' . print_r(MyClass::getConstants(), true) . '</pre>';
        echo '<pre>' . print_r(MyClass::getPostTypes(), true) . '</pre>';
        echo '<pre>' . print_r(MyClass::getTaxonomies(), true) . '</pre>';
//        die();

        define('SOURCE_DIR', dirname(__FILE__) . '/source-templates');
        define('FUNCTIONS', [
            'start' => '/* FUNCTIONS_START */',
            'end' => '/* FUNCTIONS_END */'
        ]);
        define('QUERY', [
            'start' => '/* QUERY_START */',
            'end' => '/* QUERY_END */'
        ]);
        define('TEMPLATE', [
            'start' => '/* TEMPLATE_START */',
            'end' => '/* TEMPLATE_END */'
        ]);
        define('POST_TYPE', [
            'start' => '/* POST_TYPE_START */',
            'end' => '/* POST_TYPE_END */',
//            'registration' => '/* POST_TYPE_REGISTRATION */',
        ]);
        define('TAXONOMY', [
            'start' => '/* TAXONOMY_START */',
            'end' => '/* TAXONOMY_END */',
//            'registration' => '/* TAXONOMY_REGISTRATION */',
        ]);

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

    public function getConstants()
    {
        return (new ReflectionClass($this))->getConstants();
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
        $this->writeRootFiles();
        $this->writeIncludeFiles();
        $this->writeTemplateFiles();
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

                // Handle functions file include.
                if (!$this->includeFunctions) {
                    $sourceFile = $this->replaceBetween($sourceFile, FUNCTIONS['start'], FUNCTIONS['end'], '', true);
                } else {
                    $sourceFile = str_replace(FUNCTIONS, '', $sourceFile);
                }

                // Handle query file include.
                if (!$this->includeQuery) {
                    $sourceFile = $this->replaceBetween($sourceFile, QUERY['start'], QUERY['end'], '', true);
                } else {
                    $sourceFile = str_replace(QUERY, '', $sourceFile);
                }

                // Handle template file include.
                if (!$this->includeTemplate) {
                    $sourceFile = $this->replaceBetween($sourceFile, TEMPLATE['start'], TEMPLATE['end'], '', true);
                } else {
                    $sourceFile = str_replace(TEMPLATE, '', $sourceFile);
                }

                // Handle post types file include.
                // If no post types or taxonomies, remove placeholder and content.
                if (!$this->hasPostTypes && !$this->hasTaxonomies) {
                    $sourceFile = $this->replaceBetween($sourceFile, POST_TYPE['start'], POST_TYPE['end'], '', true, 0, true);
                } else {
                    $sourceFile = str_replace(POST_TYPE, '', $sourceFile);

                    if ($this->hasPostTypes) {

                        // ACF Sub-Page Partial.
                        $acf_source = file_get_contents( dirname(__FILE__) .'/partials/acf-options-sub-page.txt' );
                        // Page Title Partial.
                        $page_title_source = file_get_contents(dirname(__FILE__).'/partials/page-title.txt');
                        // Header Image Partial.
                        $header_image_source = file_get_contents(dirname(__FILE__).'/partials/header-image.txt');
                        $acf_options_sub_page = '';
                        $page_title = '';
                        $header_image = '';

                        foreach($this->postTypes as $postType) {

                            // Search values.
                            $search = ['PLURAL','SINGULAR','POST_TYPE','SLUG'];

                            // Replacement values for partials.
                            $replacements = [
                                'PLURAL' => $postType['plural'],
                                'SINGULAR' => $postType['singular'],
                                'POST_TYPE' => sprintf('POST_TYPE_%s', $this->stringToConst($postType['singular'])),
                                'SLUG' => $this->stringToSlug($postType['plural']),
                            ];

                            // Replace ACF placeholder.
                            $acf_options_sub_page .= str_replace($search, $replacements, $acf_source);
                            // Replace Page Title placeholder.
                            $page_title .= str_replace($search, $replacements, $page_title_source);
                            // Replace Header Image placeholder.
                            $header_image .= str_replace($search, $replacements, $header_image_source);
                        }

                        // Update ACF Options Sub-Page section.
                        $sourceFile = str_replace('/* ACF_OPTIONS_SUB_PAGE_PARTIAL */', $acf_options_sub_page, $sourceFile);

                        // Update Page Title section.
                        $sourceFile = str_replace('/* PAGE_TITLE_PARTIAL */', $page_title, $sourceFile);

                        // Update Header Image section.
                        $sourceFile = str_replace('/* HEADER_IMAGE_PARTIAL */', $header_image, $sourceFile);
                    }
                }

                $this->writeFile($sourceFile, $file);

            }
        }
    }

    private function includeSidebarSupport(){}

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

        if (!$this->hasPostTypes && !$this->hasTaxonomies) {
            unset($files['class-FILE_PREFIX-post-type.php']);
        }

        // Return array key/value orientation.
        $files = array_flip($files);

        if (!is_dir(DESTINATION_DIR . '/includes') && !empty($files)) {
            mkdir(DESTINATION_DIR . '/includes');
        }

        if (is_dir(DESTINATION_DIR . '/includes') && !empty($files)) {

            foreach ($files as $file) {

                $currentFile = SOURCE_DIR . '/includes/' . $file;

                if (!is_dir($currentFile) && file_exists($currentFile)) {

                    // Read file contents into variable.
                    $sourceFile = file_get_contents($currentFile);

                    // Cleanup source file if no post types or taxonomies
                    if (!$this->hasPostTypes && !$this->hasTaxonomies) {
                        $sourceFile = $this->replaceBetween($sourceFile, POST_TYPE['start'], POST_TYPE['end'], '', true, 0, true);
                        $sourceFile = $this->replaceBetween($sourceFile, TAXONOMY['start'], TAXONOMY['end'], '', true, 0, true);
                    } else {

                        $sourceFile = str_replace(POST_TYPE, '', $sourceFile);
                        $sourceFile = str_replace(TAXONOMY, '', $sourceFile);

                        if ($this->hasPostTypes) {
                            $postTypeProperties = $this->includePostTypeProperties();
                            $sourceFile = str_replace('/* POST_TYPE_CONST */', $postTypeProperties, $sourceFile);
                            $postTypeRegistration = $this->includePostTypes();
                            $sourceFile = str_replace('/* POST_TYPE_REGISTRATION */', $postTypeRegistration, $sourceFile);
                        } else {
                            $sourceFile = str_replace('/* POST_TYPE_REGISTRATION */', '', $sourceFile);
                        }

                        if ($this->taxonomies) {
                            $taxonomyProperties = $this->includeTaxonomyProperties();
                            $sourceFile = str_replace('/* TAXONOMY_CONST */', $taxonomyProperties, $sourceFile);
                            $taxonomyRegistration = $this->includeTaxonomies();
                            $sourceFile = str_replace('/* TAXONOMY_REGISTRATION */', $taxonomyRegistration, $sourceFile);
                        } else {
                            $sourceFile = str_replace('/* TAXONOMY_REGISTRATION */', '', $sourceFile);
                        }

                    }

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

            foreach ($files as $file) {

                $currentFile = SOURCE_DIR . '/templates/' . $file;

                if (!is_dir($currentFile) && file_exists($currentFile)) {

                    // Read file contents into variable.
                    $sourceFile = file_get_contents($currentFile);

                    $this->writeFile($sourceFile, $file, 'templates');

                }
            }
        }
    }

    private function includePostTypes()
    {
        $return = '';

        if (empty($this->postTypes)) return false;

        $search = [];
        $replace = [];

        // Set up $search and $replace arrays.
        foreach ($this->postTypes as $index => $postType) {
            foreach ($postType as $key => $value) {
                $search[$index][] = strtoupper($key);
                $replace[$index][] = $value;
            }
            $search[$index][] = 'SLUG';
            $replace[$index][] = str_replace(' ', '-', strtolower($value));
            $search[$index][] = 'CONST';
            $replace[$index][] = 'POST_TYPE_' . str_replace(' ', '_', strtoupper($postType['singular']));
        }

        foreach ($search as $index => $s) {
            $sourceFile = file_get_contents('./partials/post-type/register-post-type.txt');
            $return .= str_replace($s, $replace[$index], $sourceFile);
        }

        return $return;
    }

    private function includeTaxonomies()
    {
        $return = '';

        if (empty($this->taxonomies)) return false;

        $search = [];
        $replace = [];

        echo '<pre>' . print_r($this->taxonomies, true) . '</pre>';

        // Set up $search and $replace arrays.
        foreach ($this->taxonomies as $index => $taxonomy) {
            foreach ($taxonomy as $key => $value) {
                $search[$index][] = strtoupper($key);
                $replace[$index][] = $value;
            }
            $search[$index][] = 'SLUG';
            $replace[$index][] = str_replace(' ', '-', strtolower($value));
            $search[$index][] = 'CONST';
            $replace[$index][] = 'TAXONOMY_' . str_replace(' ', '_', strtoupper($taxonomy['singular']));
        }

        foreach ($search as $index => $s) {
            $sourceFile = file_get_contents('./partials/post-type/register-taxonomy.txt');
            $return .= str_replace($s, $replace[$index], $sourceFile);
        }

        return $return;
    }

    /**
     * Generate CONST properties for Post Types.
     * @return bool|string
     */
    private function includePostTypeProperties()
    {
        $return = '';

        if (empty($this->postTypes)) return false;

        foreach ($this->postTypes as $postType) {
            $constKey = sprintf('POST_TYPE_%s', $this->stringToConst($postType['singular']));
            $constValue = strtolower(str_replace(' ', '_', $postType['singular']));
            $return .= "\t// Post Type {$postType['singular']}.\n\tconst {$constKey} = '{$constValue}';\n";
        }

        return $return;
    }

    /**
     * Generate CONST properties for Taxonomies.
     * @return bool|string
     */
    private function includeTaxonomyProperties()
    {
        $return = '';

        if (empty($this->taxonomies)) return false;

        foreach ($this->taxonomies as $taxonomy) {
            $constKey = sprintf('TAXONOMY_%s', $this->stringToConst($taxonomy['singular']));
            $constValue = strtolower(str_replace(' ', '_', $taxonomy['singular']));
            $return .= "\t// Taxonomy {$taxonomy['singular']}.\n\tconst {$constKey} = '{$constValue}';\n";
        }

        return $return;
    }

    private function queryPostTypeReplacements()
    {
        $return = '';

        if (empty($this->postTypes)) return false;

        if (count($this->postTypes) > 1) {
            $return = '[ ';
            foreach ($this->postTypes as $postType) {
                $postTypeConst = str_replace(' ', '_', strtoupper($postType['singular']));
                $return .= sprintf('%s_Post_Type::POST_TYPE_%s, ', $this->baseClassName, $postTypeConst);
            }
            $return .= ']';
        } else {
            $postTypeConst = str_replace(' ', '_', strtoupper($this->postTypes[0]['singular']));
            $return .= sprintf('%s_Post_Type::POST_TYPE_%s ', $this->baseClassName, $postTypeConst);
        }
        return $return;
    }

    /**
     * Utility for replacing string content.
     * @see https://stackoverflow.com/questions/6875913/simple-how-to-replace-all-between-with-php#answer-55745025
     * @param $string
     * @param $needleStart
     * @param $needleEnd
     * @param $replacement
     * @param bool $replaceNeedles
     * @param int $startPos
     * @param bool $replaceAll
     * @return mixed
     */
    public function replaceBetween($string, $needleStart, $needleEnd, $replacement, $replaceNeedles = false, $startPos = 0, $replaceAll = false)
    {
        $posStart = mb_strpos($string, $needleStart, $startPos);

        if ($posStart === false) {
            return $string;
        }

        $start = $posStart + ($replaceNeedles ? 0 : mb_strlen($needleStart));
        $posEnd = mb_strpos($string, $needleEnd, $start);

        if ($posEnd === false) {
            return $string;
        }

        $length = $posEnd - $start + ($replaceNeedles ? mb_strlen($needleEnd) : 0);

        $result = substr_replace($string, $replacement, $start, $length);

        if ($replaceAll) {
            $nextStartPos = $start + mb_strlen($replacement) + mb_strlen($needleEnd);

            if ($nextStartPos >= mb_strlen($string)) {
                return $result;
            }

            return $this->replaceBetween($result, $needleStart, $needleEnd, $replacement, $replaceNeedles, $nextStartPos, true);
        }

        return $result;
    }

    public function getPluginCode()
    {
//        echo json_encode($this->data);
    }

    /**
     * Convert sting to slug.
     * @param $string
     * @return string|string[]|null
     */
    public function stringToSlug($string)
    {
        return preg_replace('/\W+/', '-', strtolower($string));
    }

    /**
     * Convert string to const.
     * @param $string
     * @return string|string[]|null
     */
    public function stringToConst($string)
    {
        return preg_replace('/\W+/', '_', strtoupper($string));
    }

}