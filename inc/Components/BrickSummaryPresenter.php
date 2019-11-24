<?php
/**
 * @package AK_Mapping_Service
 */

namespace Inc\Components;

use \Inc\Api\MappingApi; 

class BrickSummaryPresenter 
{
    public $_map;  // mapping service
    public $shortcode;

    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
    }
    public function register(){
        // $this->_map = new MappingApi();
        $this->shortcode = 'brick_summary';
        add_shortcode($this->shortcode, array($this,'print_brick_summary')); //TODO: Link to var in admin panel
    }
    function print_brick_summary(){
        $this->console_log("...loading [$this->shortcode].");

        $brick = (object) MappingApi::$destinationBrick;
        //     "brickNumber"=>"34141",
        //     "description"=>"Col. Leonard G Hicks\r\nWW II, Korean War\r\nUSMC, Bronze Star",
        //     "donor"=>"albert abe",
        //     "honor"=>"adele adrian",
        //     "searchTerm"=>"34141|adele adrian|albert abe",
        //     "section"=>"186"
        // ];

        $html = '<div class="ak-mapping-brick-summary">';
        $html .= "<h3>Number:</h3>";
        $html .= "<p>$brick->brickNumber</p>";
        $html .= "<h3>Honoree:</h3>";
        $html .= "<p>" . ucwords($brick->honor) . "</p>";
        $html .= "<h3>Inscription:</h3>";
        $html .= "<p>$brick->description</p>";
        $html .= "</div>";
        $html .= "</div>";
        $html .= MappingApi::getIntersectionThumbnail("TEST");
        return $html;
    }   
    // public function print_searchbar(){
    //     $this->console_log("...loading [search_bar].");
    //     ob_start();
    //     include "$this->plugin_path/templates/search-bar-template.php";
    //     return ob_get_clean(); 
    // }
}