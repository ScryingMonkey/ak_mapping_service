<?php

/**
 *  @package AK_Mapping_Service
 */

namespace Inc\Base;

class Enqueue extends BaseController
{
    public function register()
    {
        add_action('admin_enqueue_scripts', array($this,'enqueue'));
        add_action('wp_enqueue_scripts', array($this,'enqueue'));     
    }
    function enqueue() 
    {
        wp_enqueue_style('ak-mapping-script', $this->plugin_url . 'assets/ak_styles.css' );
        wp_register_script( 'ak-mapping-script', $this->plugin_url . 'assets/ak_scripts.js', array(), $this->version, false );	  
        wp_enqueue_script('ak-mapping-script');
       

    }
    function setUpScriptVars(){
        $mockBrickJsonFilePath = $this->plugin_url . 'assets/datamocks/map-annotation-tool-bricks-info-export.json';
        $mockBrickObj = json_decode(file_get_contents($mockBrickJsonFilePath), true); // decode the JSON into an associative array

        $mockOriginsJsonFilePath = $this->plugin_url . 'assets/datamocks/map-origin-options.json';
        $mockOriginsObj = json_decode(file_get_contents($mockOriginsJsonFilePath), true); // decode the JSON into an associative array
        
        $mockBestPathJsonFilePath = $this->plugin_url . 'assets/datamocks/map-bestpath-mock.json';
        $mockBestPath = json_decode(file_get_contents($mockBestPathJsonFilePath), true); // decode the JSON into an associative array

        wp_localize_script( 
            'ak-mapping-script', 
            'ak_mapping_vars', 
            array( 
                'AUTOSUGGEST_INDEX' => $mockBrickObj, 
                'ORIGIN_SECTION_OPTIONS' => $mockOriginsObj,
                'MOCK_BEST_PATH' => $mockBestPath
            ) 
        );
    }
}