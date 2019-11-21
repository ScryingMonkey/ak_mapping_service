<?php
/**
 * @package AK_Mapping_Service
 */

namespace Inc\Api;

class MappingApi
{
    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
    }
    public function register(){
        add_shortcode('path_map', array($this,'print_map')); //TODO: Link to var in admin panel
        add_shortcode('path_instructions', array($this,'print_instructions')); //TODO: Link to var in admin panel

    }
    function print_instructions(){
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
    function print_map(){
        $svg = $this->get_map_image();
        $html = $svg;
        return $html;
    }
    /**
     * Appends a message to the bottom of a single post including the number of followers and the last Tweet.
     *
     * @access public
     * @param  $content    The post content
     * @return $content    The post content with the mapping api information appended to it.
     */
    function display_mapping_information() {
        $content = "";
        $url = 'https://us-central1-map-annotation-tool.cloudfunctions.net/path_narrative';
        $params = 'userId=auth0|5d976539de2c080c4f8913ff&origin=129A&destination=220';
        // If we're on a single post or page...
        if ( is_single() ) {
            
            // ...attempt to make a response to twitter. Note that you should replace your username here!
            if ( null == ( $json_response = $this->make_api_request( $url, $params ) ) ) {
                // ...display a message that the request failed
                $html = '
        <div id="ak-mapping-content">';
        $html .= 'There was a problem communicating with the AK Mapping API..';
        $html .= '</div>;
        <!-- /#ak-mapping-content -->';
            // ...otherwise, read the information provided by api
            } else {
                $steps = get_steps();
                $html = '
        <div id="ak-mapping-content">';
        $html .= "<ul>";
                foreach( $steps as $step ) {
                $html .= "<li>{$step['OriginSectionName']} to {$step['DestinationSectionName']}</li>";
        $html .= "</ul>"; 
        $html .= '</div>
        <!-- /#ak-mapping-content -->';
                }
            } 
            $content .= $html;
        } 
        return $content;
    } 
    /**
     * Attempts to request the specified user's JSON feed from Twitter
     *
     * @access private
     * @param  $url     The url for the JSON feed we're attempting to retrieve
     * @return $params  A string of all the params for the api call
     * @return $json    The JSON feed or null if the request failed
     */
    private function make_api_request( $url, $params ) {
        try {
            $response = wp_remote_get( $url . "?" . $params );
        } catch ( Exception $ex ) {
            return "{data: \"Api request returned an error. $ex\"}";
        }
        try {
            // Note that we decode the body's response since it's the actual JSON feed
            $json = json_decode( $response['body'] );
        } catch ( Exception $ex ) {
            // $json = null;
            return "{data: \"Could not decode json. $ex\"}";
        }
        $this->console_log("...reponse from api get [$url?$params]");
        $this->console_log($json);
        return $json;
 
    }

    function getBestPathFromApi(){
        // $url = 'https://fb-functions-getting-started.firebaseapp.com/api/v1/instructions';
        // $params = '';
        $url = 'https://us-central1-map-annotation-tool.cloudfunctions.net/path_narrative';
        $params = 'userId=auth0|5d976539de2c080c4f8913ff&originSectionName=129A&destinationBrickNumber=34141';
        $res = $this->make_api_request($url, $params);
        return $res;
    }
 
    /**
     * Retrieves the number of followers from the JSON feed
     *
     * @access private
     * @param  $json     The mapping json
     * @return           The mag svg.
     */
    private function get_map_image() {
        $json = $this->getBestPathFromApi();
        $img = ( !empty($json->data->svg) ) ? $json->data->svg : '[ No image found. ]';
        $this->console_log("...retrieved map image:");
        $this->console_log($img);
        return $img;
    }
 
    /**
     * Retrieves the walking instructions
     *
     * @access private
     * @param  $json     The mapping json
     * @return           The walking instructions.
     */
    private function get_steps() {
        $json = $this->getBestPathFromApi();
        $steps = ( !empty($json->data->steps) ) ? $json->data->steps : [ 'No steps found.' ];
        $this->console_log("...retrieved best path steps:");
        $this->console_log($steps);
        return $steps;
    }
}
