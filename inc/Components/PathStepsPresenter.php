<?php
/**
 * @package AK_Mapping_Service
 */

namespace Inc\Components;

use \Inc\Api\MappingApi; 

class PathStepsPresenter 
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
        $this->shortcode = 'path_instructions';
        add_shortcode($this->shortcode, array($this,'print_instructions')); //TODO: Link to var in admin panel
    }
    function print_instructions(){
        $this->console_log("...loading [$this->shortcode].");

        $steps = MappingApi::$pathSteps;
        $this->console_log($steps);
        
        $html = '<div class="testText">';
        $html .= "Test Instructions";
        $html .= "</div>";
        $html .= '<div>';
        $html .= "<ol>";
        foreach($steps as $step){
            $step = $step;
            $this->console_log("...processing step <" . gettype($step) .">;");
            $this->console_log($step);
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
}   