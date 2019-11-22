<?php
/**
 * @package AK_Mapping_Service
 */

namespace Inc\Api;

class MappingApi
{
    public $brick;

    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
    }
    public function register(){
        add_shortcode('brick_summary', array($this,'print_brick_summary')); //TODO: Link to var in admin panel

        $this->brick = $this->getBrick();
    }
    function print_brick_summary(){
        $this->console_log("...loading [search_bar].");

        $steps = $this->get_steps();
        $html = '<div class="testText">';
        $html .= "Test Instructions";
        $html .= "</div>";
        $html .= '<div>';
        $html .= "<ol>";
        foreach($steps as $step){
            if(array_key_exists('landmark', $step)){
                $html .= '<li>';
                $html .= '<div class="ak-instruction-image">';
                if(array_key_exists('fileName', $step->landmark)){
                    $html .= '<img src="' . $step->landmark->fileName . '" >';
                } else {
                    $html .= '<span></span>';
                }
                $html .= '</div>';
                $html .= '<div class="ak-instruction-text">' . $step->landmark->instructions . '</div>';
                $html .= '</li>'; 
                if(array_key_exists('placement', $step)){
                    $html .= "<li>The brick is on the $step->placement. </li>";
                }
            }           
        }
        $html .= "</ol>";
        $html .= "</div>";
        return $html;
    }   
    public function print_searchbar(){
        $this->console_log("...loading [search_bar].");
        ob_start();
        include "$this->plugin_path/templates/search-bar-template.php";
        return ob_get_clean(); 
    }
    public function getBrick(){
        $brick = [];
        $brick = [
            "brickNumber"=>"34141",
            "description"=>"Col. Leonard G Hicks\r\nWW II, Korean War\r\nUSMC, Bronze Star",
            "donor"=>"albert abe",
            "honor"=>"adele adrian",
            "searchTerm"=>"34141|adele adrian|albert abe",
            "section"=>"186"
        ];
        return $brick;
    }
}