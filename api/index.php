<?php

require_once __DIR__ . '/vendor/autoload.php';

// Change origin from * to application url.
header( "Access-Control-Allow-Origin: *" );
header( "Access-Control-Allow-Methods: POST, OPTIONS" );
header( "Access-Control-Allow-Headers: Content-Disposition, Content-Type, Content-Length, Accept-Encoding" );
header( "Content-type: application/json" );

// Plugin data from frontend.
$data = json_decode( file_get_contents( 'php://input' ), TRUE );

if ( ! empty( $data ) ) {
    $boilerplate = ( new OMS_Boilerplate( $data ) )->makePlugin();
}
?>

<?php class OMS_Boilerplate {

    const SEARCH = [
        'PLUGIN_NAME',
        'BASE_CLASS_NAME',
        'FILE_PREFIX',
        'FUNCTION_PREFIX',
    ];

    protected $data;

    public $hasPostTypes = FALSE;
    public $hasTaxonomies = FALSE;

    public $includeFunctions = TRUE;
    public $includeQuery = TRUE;
    public $includeTemplate = TRUE;

    public $includeACF = TRUE;

    public function __construct( $data = FALSE ) {

        define( 'SOURCE_DIR', __DIR__ . '/source-templates' );
        define( 'ACF', [
            'start' => '/* ACF_START */',
            'end'   => '/* ACF_END */',
        ] );
        define( 'SIDEBARS', [
            'start' => '/* SIDEBARS_START */',
            'end'   => '/* SIDEBARS_END */',
        ] );
        define( 'FUNCTIONS', [
            'start' => '/* FUNCTIONS_START */',
            'end'   => '/* FUNCTIONS_END */',
        ] );
        define( 'QUERY', [
            'start' => '/* QUERY_START */',
            'end'   => '/* QUERY_END */',
        ] );
        define( 'TEMPLATE', [
            'start' => '/* TEMPLATE_START */',
            'end'   => '/* TEMPLATE_END */',
        ] );
        define( 'POST_TYPE', [
            'start' => '/* POST_TYPE_START */',
            'end'   => '/* POST_TYPE_END */',
        ] );
        define( 'TAXONOMY', [
            'start' => '/* TAXONOMY_START */',
            'end'   => '/* TAXONOMY_END */',
        ] );

        $this->data = $data;
        $this->setProperties();
        $this->setDestinationDirectory();
    }

    /**
     * Dynamically set class properties from $data.
     */
    private function setProperties() {
        $data = $this->data;
        foreach ( $data as $property => $value ) {

            if ( 'filePrefix' == $property ) {

                $this->filePrefix = ( ! empty( $value ) ) ? $value : 'oms-plugin';

            } elseif ( is_array( $value ) && ! empty( $value ) ) {

                switch ( $property ) {
                    case 'postTypes':
                        $this->{$property}  = $value;
                        $this->hasPostTypes = TRUE;
                        break;
                    case 'taxonomies':
                        $this->{$property}   = $value;
                        $this->hasTaxonomies = TRUE;
                        break;
                    case 'includeFiles':
                        $this->{$property}      = $value;
                        $this->includeFunctions = ( (bool) $value[ 'functions' ] ) ? TRUE : FALSE;
                        $this->includeQuery     = ( $value[ 'query' ] ) ? TRUE : FALSE;
                        $this->includeTemplate  = ( $value[ 'template' ] ) ? TRUE : FALSE;
                        break;
                    case 'otherSettings':
                        $this->{$property} = $value;
                        $this->includeACF  = ( (bool) $value[ 'acf' ] ) ? TRUE : FALSE;
                        break;
                    default:
                        $this->{$property} = $value;
                        break;
                }

            } else {
                $this->{$property} = filter_var( $value, FILTER_SANITIZE_STRING );
            }
        }
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

    /**
     * Create plugin directory.
     */
    private function setDestinationDirectory() {
        // Ensure we have a destination directory.
        $pluginDir = $this->filePrefix ? $this->filePrefix : 'oms-plugin';

        define( 'DESTINATION_DIR', __DIR__ . '/destination-templates/' . $pluginDir );

        if ( ! is_dir( DESTINATION_DIR ) ) {
            mkdir( DESTINATION_DIR );
        }
    }

    /**
     * @param       $sourceFile
     * @param       $file
     * @param false $dir
     */
    private function writeFile( $sourceFile, $file, $dir = FALSE ) {
        $destinationDir = $dir ? DESTINATION_DIR . '/' . $dir : DESTINATION_DIR;

        // String replacement operation on file contents.
        $newFile = str_replace( self::SEARCH, $this->getReplacementProperties(), $sourceFile );

        // String replacement on source file name for use in new file name.
        $handle = fopen( str_replace( self::SEARCH, $this->getReplacementProperties(), $destinationDir . '/' . $file ), 'w' );

        // Write updated file content to new destination file.
        fwrite( $handle, $newFile );
    }

    /**
     * Add root files for plugin.
     */
    private function writeRootFiles() {
        // Get files, but exclude '.', '..', 'templates','includes', 'blocks'.
        $files = array_diff( scandir( SOURCE_DIR ), ['.', '..', 'templates', 'includes', 'blocks'] );

        foreach ( $files as $file ) {

            $currentFile = SOURCE_DIR . '/' . $file;

            if ( ! is_dir( $currentFile ) && file_exists( $currentFile ) ) {

                // Read file contents into variable.
                $sourceFile = file_get_contents( $currentFile );

                // Handle functions file include.
                if ( ! $this->includeFunctions ) {
                    $sourceFile = $this->replaceBetween( $sourceFile, FUNCTIONS[ 'start' ], FUNCTIONS[ 'end' ], '', TRUE );
                } else {
                    $sourceFile = str_replace( FUNCTIONS, '', $sourceFile );
                }

                // Handle query file include.
                if ( ! $this->includeQuery ) {
                    $sourceFile = $this->replaceBetween( $sourceFile, QUERY[ 'start' ], QUERY[ 'end' ], '', TRUE );
                } else {
                    $sourceFile = str_replace( QUERY, '', $sourceFile );
                }

                // Handle template file include.
                if ( ! $this->includeTemplate ) {
                    $sourceFile = $this->replaceBetween( $sourceFile, TEMPLATE[ 'start' ], TEMPLATE[ 'end' ], '', TRUE );
                } else {
                    $sourceFile = str_replace( TEMPLATE, '', $sourceFile );
                }

                // Remove ACF requirement check.
                if ( ! $this->includeACF ) {
                    $sourceFile = $this->replaceBetween( $sourceFile, ACF[ 'start' ], ACF[ 'end' ], '', TRUE, 0, TRUE );
                } else {
                    $sourceFile = str_replace( ACF, '', $sourceFile );
                }

                // Handle post types file include.
                // If no post types or taxonomies, remove placeholder and content.
                if ( ! $this->hasPostTypes && ! $this->hasTaxonomies ) {
                    $sourceFile = $this->replaceBetween( $sourceFile, POST_TYPE[ 'start' ], POST_TYPE[ 'end' ], '', TRUE, 0, TRUE );
                } else {
                    $sourceFile = str_replace( POST_TYPE, '', $sourceFile );

                    if ( $this->hasPostTypes ) {

                        // ACF Sub-Page Partial.
                        $acf_source           = file_get_contents( __DIR__. '/partials/acf-options-sub-page.txt' );
                        $acf_options_sub_page = '';

                        foreach ( $this->postTypes as $postType ) {

                            // Search values.
                            $search = ['PLURAL', 'SINGULAR', 'POST_TYPE', 'SLUG'];

                            // Replacement values for partials.
                            $replacements = [
                                'PLURAL'    => $postType[ 'plural' ],
                                'SINGULAR'  => $postType[ 'singular' ],
                                'POST_TYPE' => sprintf( 'POST_TYPE_%s', $this->stringToConst( $postType[ 'singular' ] ) ),
                                'SLUG'      => $this->stringToSlug( $postType[ 'plural' ] ),
                            ];

                            // Replace ACF placeholder.
                            $acf_options_sub_page .= str_replace( $search, $replacements, $acf_source );
                        }

                        // Update ACF Options Sub-Page section.
//                        $sourceFile = str_replace('/* ACF_OPTIONS_SUB_PAGE_PARTIAL */', $acf_options_sub_page, $sourceFile);
                    }
                }

                $this->writeFile( $sourceFile, $file );

            }
        }
    }

    /**
     * Add Block Registration JSON file for plugin.
     */
    private function writeBlockRegistration() : void {
        // Get files and exclude '.' and '..'.
        $files = array_diff( scandir( SOURCE_DIR . '/blocks' ), ['.', '..'] );

        if ( ! is_dir( DESTINATION_DIR . '/blocks' ) && ! empty( $files ) ) {
            mkdir( DESTINATION_DIR . '/blocks' );
        }

        if ( is_dir( DESTINATION_DIR . '/blocks' ) && ! empty( $files ) ) {

            foreach ( $files as $file ) {

                $currentFile = SOURCE_DIR . '/blocks/' . $file;

                if ( ! is_dir( $currentFile ) && file_exists( $currentFile ) ) {

                    // Read file contents into variable.
                    $sourceFile = file_get_contents( $currentFile );

                    $this->writeFile( $sourceFile, $file, 'blocks' );
                }
            }
        }
    }

    /**
     * Add include files for plugin.
     */
    private function writeIncludeFiles() {
        // Get files and exclude '.' and '..'.
        $files = array_diff( scandir( SOURCE_DIR . '/includes' ), ['.', '..'] );

        // Flip array to easily remove unnecessary files by key.
        $files = array_flip( $files );

        if ( ! $this->includeFunctions ) {
            unset( $files[ 'FILE_PREFIX-functions.php' ] );
        }

        if ( ! $this->includeTemplate ) {
            unset( $files[ 'class-FILE_PREFIX-template-loader.php' ] );
        }

        if ( ! $this->includeQuery ) {
            unset( $files[ 'class-FILE_PREFIX-query.php' ] );
        }

        if ( ! $this->hasPostTypes && ! $this->hasTaxonomies ) {
            unset( $files[ 'class-FILE_PREFIX-post-type.php' ] );
        }

        // Return array key/value orientation.
        $files = array_flip( $files );

        if ( ! is_dir( DESTINATION_DIR . '/includes' ) && ! empty( $files ) ) {
            mkdir( DESTINATION_DIR . '/includes' );
        }

        if ( is_dir( DESTINATION_DIR . '/includes' ) && ! empty( $files ) ) {

            foreach ( $files as $file ) {

                $currentFile = SOURCE_DIR . '/includes/' . $file;

                if ( ! is_dir( $currentFile ) && file_exists( $currentFile ) ) {

                    // Read file contents into variable.
                    $sourceFile = file_get_contents( $currentFile );

                    // Cleanup source file if no post types or taxonomies
                    if ( ! $this->hasPostTypes && ! $this->hasTaxonomies ) {
                        $sourceFile = $this->replaceBetween( $sourceFile, POST_TYPE[ 'start' ], POST_TYPE[ 'end' ], '', TRUE, 0, TRUE );
                        $sourceFile = $this->replaceBetween( $sourceFile, TAXONOMY[ 'start' ], TAXONOMY[ 'end' ], '', TRUE, 0, TRUE );
                    } else {

                        $sourceFile = str_replace( POST_TYPE, '', $sourceFile );
                        $sourceFile = str_replace( TAXONOMY, '', $sourceFile );

                        if ( $this->hasPostTypes ) {
                            $postTypeProperties   = $this->includePostTypeProperties();
                            $sourceFile           = str_replace( '/* POST_TYPE_CONST */', $postTypeProperties, $sourceFile );
                            $postTypeRegistration = $this->includePostTypes();
                            $sourceFile           = str_replace( '/* POST_TYPE_REGISTRATION */', $postTypeRegistration, $sourceFile );
                        } else {
                            $sourceFile = str_replace( '/* POST_TYPE_REGISTRATION */', '', $sourceFile );
                        }

                        if ( $this->taxonomies ) {
                            $taxonomyProperties   = $this->includeTaxonomyProperties();
                            $sourceFile           = str_replace( '/* TAXONOMY_CONST */', $taxonomyProperties, $sourceFile );
                            $taxonomyRegistration = $this->includeTaxonomies();
                            $sourceFile           = str_replace( '/* TAXONOMY_REGISTRATION */', $taxonomyRegistration, $sourceFile );
                        } else {
                            $sourceFile = str_replace( '/* TAXONOMY_REGISTRATION */', '', $sourceFile );
                        }

                    }

                    $this->writeFile( $sourceFile, $file, 'includes' );

                }
            }
        }
    }

    /**
     * Add template files for plugin.
     */
    private function writeTemplateFiles() {
        // If false, no template files needed.
        if ( ! $this->includeTemplate ) {
            return;
        }

        // Get files and exclude '.' and '..'.
        $files = array_diff( scandir( SOURCE_DIR . '/templates' ), ['.', '..'] );

        if ( ! is_dir( DESTINATION_DIR . '/templates' ) && ! empty( $files ) ) {

            mkdir( DESTINATION_DIR . '/templates' );

            foreach ( $files as $file ) {

                $currentFile = SOURCE_DIR . '/templates/' . $file;

                if ( ! is_dir( $currentFile ) && file_exists( $currentFile ) ) {

                    // Read file contents into variable.
                    $sourceFile = file_get_contents( $currentFile );

                    $this->writeFile( $sourceFile, $file, 'templates' );

                }
            }
        }

        /*
        * @TODO refactor this whole section to rely on a checkbox. Need to update App.js and the react for a checkbox
        *
        * ACF BLOCK RENDER TEMPLATES
        *
        **/
        $block_templates = array_diff( scandir( SOURCE_DIR . '/templates/block' ), ['.', '..'] );

        if ( ! is_dir( DESTINATION_DIR . '/templates/block' ) && ! empty( $block_templates ) ) {

            mkdir( DESTINATION_DIR . '/templates/block' );

            foreach ( $block_templates as $file ) {

                $currentFile = SOURCE_DIR . '/templates/block/' . $file;

                if ( ! is_dir( $currentFile ) && file_exists( $currentFile ) ) {

                    // Read file contents into variable.
                    $sourceFile = file_get_contents( $currentFile );

                    $this->writeFile( $sourceFile, $file, 'templates/block' );
                }
            }
        }
    }

    /**
     * Create post type registration code.
     *
     * @return false|string
     */
    private function includePostTypes() {
        $return = '';

        // Skip this if no post types are defined.
        if ( empty( $this->postTypes ) ) {
            return FALSE;
        }

        $search  = [];
        $replace = [];

        // Set up $search and $replace arrays.
        foreach ( $this->postTypes as $index => $postType ) {
            foreach ( $postType as $key => $value ) {
                $search[ $index ][]  = strtoupper( $key );
                $replace[ $index ][] = $value;
            }
            $search[ $index ][]  = 'SLUG';
            $replace[ $index ][] = str_replace( ' ', '-', strtolower( $value ) );
            $search[ $index ][]  = 'CONST';
            $replace[ $index ][] = 'POST_TYPE_' . str_replace( ' ', '_', strtoupper( $postType[ 'singular' ] ) );
        }

        // Create post type registration code for each post type.
        foreach ( $search as $index => $s ) {
            $sourceFile = file_get_contents( './partials/post-type/register-post-type.txt' );
            $return     .= str_replace( $s, $replace[ $index ], $sourceFile );
        }

        return $return;
    }

    /**
     * Create post type registration code.
     *
     * @return false|string
     */
    private function includeTaxonomies() {
        $return = '';

        // Skip this if no taxonomies are defined.
        if ( empty( $this->taxonomies ) ) {
            return FALSE;
        }

        $search  = [];
        $replace = [];

        // Set up $search and $replace arrays.
        foreach ( $this->taxonomies as $index => $taxonomy ) {
            foreach ( $taxonomy as $key => $value ) {
                $search[ $index ][]  = strtoupper( $key );
                $replace[ $index ][] = $value;
            }
            $search[ $index ][]  = 'SLUG';
            $replace[ $index ][] = str_replace( ' ', '-', strtolower( $value ) );
            $search[ $index ][]  = 'CONST';
            $replace[ $index ][] = 'TAXONOMY_' . str_replace( ' ', '_', strtoupper( $taxonomy[ 'singular' ] ) );
        }

        // Create taxonomy registration code for each taxonomy.
        foreach ( $search as $index => $s ) {
            $sourceFile = file_get_contents( './partials/post-type/register-taxonomy.txt' );
            $return     .= str_replace( $s, $replace[ $index ], $sourceFile );
        }

        return $return;
    }

    /**
     * Generate CONST properties for Post Types.
     *
     * @return bool|string
     */
    private function includePostTypeProperties() {
        $return = '';

        if ( empty( $this->postTypes ) ) {
            return FALSE;
        }

        foreach ( $this->postTypes as $postType ) {
            $constKey   = sprintf( 'POST_TYPE_%s', $this->stringToConst( $postType[ 'singular' ] ) );
            $constValue = strtolower( str_replace( ' ', '_', $postType[ 'singular' ] ) );
            $return     .= "\t// Post Type {$postType['singular']}.\n\tconst {$constKey} = '{$constValue}';\n";
        }

        return $return;
    }

    /**
     * Generate CONST properties for Taxonomies.
     *
     * @return bool|string
     */
    private function includeTaxonomyProperties() {
        $return = '';

        if ( empty( $this->taxonomies ) ) {
            return FALSE;
        }

        foreach ( $this->taxonomies as $taxonomy ) {
            // Prepend with file prefix value if taxonomy is "category".
            $taxonomyPrefix = ( strtolower( $taxonomy[ 'singular' ] ) === 'category' ) ? $this->filePrefix . '_' : '';
            $constKey       = sprintf( 'TAXONOMY_%s', $this->stringToConst( $taxonomy[ 'singular' ] ) );
            $constValue     = $taxonomyPrefix . strtolower( str_replace( ' ', '_', $taxonomy[ 'singular' ] ) );
            $return         .= "\t// Taxonomy {$taxonomy['singular']}.\n\tconst {$constKey} = '{$constValue}';\n";
        }

        return $return;
    }

    /**
     * Utility for replacing string content.
     *
     * @see https://stackoverflow.com/questions/6875913/simple-how-to-replace-all-between-with-php#answer-55745025
     *
     * @param      $string
     * @param      $needleStart
     * @param      $needleEnd
     * @param      $replacement
     * @param bool $replaceNeedles
     * @param int  $startPos
     * @param bool $replaceAll
     *
     * @return mixed
     */
    public function replaceBetween( $string, $needleStart, $needleEnd, $replacement, $replaceNeedles = FALSE, $startPos = 0, $replaceAll = FALSE ) {
        $posStart = mb_strpos( $string, $needleStart, $startPos );

        if ( $posStart === FALSE ) {
            return $string;
        }

        $start  = $posStart + ( $replaceNeedles ? 0 : mb_strlen( $needleStart ) );
        $posEnd = mb_strpos( $string, $needleEnd, $start );

        if ( $posEnd === FALSE ) {
            return $string;
        }

        $length = $posEnd - $start + ( $replaceNeedles ? mb_strlen( $needleEnd ) : 0 );

        $result = substr_replace( $string, $replacement, $start, $length );

        if ( $replaceAll ) {
            $nextStartPos = $start + mb_strlen( $replacement ) + mb_strlen( $needleEnd );

            if ( $nextStartPos >= mb_strlen( $string ) ) {
                return $result;
            }

            return $this->replaceBetween( $result, $needleStart, $needleEnd, $replacement, $replaceNeedles, $nextStartPos, TRUE );
        }

        return $result;
    }

    public function makePlugin() {
        $this->writeRootFiles();
        $this->writeBlockRegistration();
        $this->writeIncludeFiles();
        $this->writeTemplateFiles();

        $hashedPrefix = md5( $this->filePrefix . strtotime( 'now' ) );

        $filename = sprintf( 'oms-plugin-%s-%s.zip', strtolower( str_replace( ' ', '-', $this->pluginName ) ), $hashedPrefix );

        $zipper = new \Chumper\Zipper\Zipper;

        $zipper->make( sprintf( './tmp/%s', $filename ) )
               ->folder( $this->filePrefix )
               ->add( DESTINATION_DIR )->close();

        $tmpFile = sprintf( './tmp/%s', $filename );

        if ( file_exists( $tmpFile ) ) {
            self::delTree( DESTINATION_DIR );
            echo $filename;
            die();
        }
    }

    /**
     * Convert sting to slug.
     *
     * @param $string
     *
     * @return string|string[]|null
     */
    public function stringToSlug( $string ) {
        return preg_replace( '/\W+/', '-', strtolower( $string ) );
    }

    /**
     * Convert string to const.
     *
     * @param $string
     *
     * @return string|string[]|null
     */
    public function stringToConst( $string ) {
        return preg_replace( '/\W+/', '_', strtoupper( $string ) );
    }

    public static function delTree( $dir ) {
        $files = array_diff( scandir( $dir ), ['.', '..'] );
        foreach ( $files as $file ) {
            ( is_dir( "$dir/$file" ) ) ? self::delTree( "$dir/$file" ) : unlink( "$dir/$file" );
        }

        return rmdir( $dir );
    }

}
