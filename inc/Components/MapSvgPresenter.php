<?php
/**
 * @package AK_Mapping_Service
 */

namespace Inc\Components;

use \Inc\Api\MappingApi; 

class MapSvgPresenter 
{
    public $_map;  // mapping service
    public $shortcode;

    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
    }
    public function register(){
        $this->_map = new MappingApi();
        $this->shortcode = 'path_map';
        add_shortcode($this->shortcode, array($this,'print_map')); //TODO: Link to var in admin panel
    }
    function print_map(){
        $this->console_log("...loading [$this->shortcode].");

        $svg = MappingApi::$map;
        // $this->console_log($svg);
        return $svg;
    }
}   