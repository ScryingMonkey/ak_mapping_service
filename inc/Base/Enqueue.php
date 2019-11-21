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
        // wp_enqueue_style('mypluginstyle', $this->plugin_url . 'assets/ak_styles.css' );
        wp_enqueue_style('ak-mapping-script', $this->plugin_url . 'assets/ak_styles.css' );
        
        wp_register_script( 'ak-mapping-script', $this->plugin_url . 'assets/ak_scripts.js', array(), $this->version, false );	  
        // wp_register_script( 'mypluginscript', $this->plugin_url . 'assets/ak_scripts.js', array( 'jquery' ), $this->version, false );	  
        wp_enqueue_script('ak-mapping-script');
       
        $mockBrickJsonFilePath = $this->plugin_url . 'assets/datamocks/map-annotation-tool-bricks-info-export.json';
        $mockBrickObj = json_decode(file_get_contents($mockBrickJsonFilePath), true); // decode the JSON into an associative array

        // $mockBrickListJsonFilePath = $this->plugin_url . 'assets/datamocks/map-annotation-tool-bricks-info-export-list.json';
        // $mockBrickList = json_decode(file_get_contents($mockBrickListJsonFilePath), true); // decode the JSON into an associative array
        
        $mockOriginsJsonFilePath = $this->plugin_url . 'assets/datamocks/map-origin-options.json';
        $mockOriginsObj = json_decode(file_get_contents($mockOriginsJsonFilePath), true); // decode the JSON into an associative array

        wp_localize_script( 
            'ak-mapping-script', 
            'ak_mapping_vars', 
            array( 
                'AUTOSUGGEST_INDEX' => $mockBrickObj, 
                // 'AUTOSUGGEST_INDEX_LIST' => $mockBrickList,
                'ORIGIN_SECTION_OPTIONS' => $mockOriginsObj
            ) 
        );
    }
}