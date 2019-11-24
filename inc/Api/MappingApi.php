<?php
/**
 * @package AK_Mapping_Service
 */

namespace Inc\Api;

use \Inc\Base\BaseController; 

class MappingApi extends BaseController
{
    public static $url;

    public static $destinationBrick;
    public static $pathSteps;
    public static $pathStepsPlacement;
    public static $map;
    public static $originSectionName;
    public static $destinationBrickNumber;

    private function console_log( $data ){
        // echo '<script>';
        // echo 'console.log('. json_encode( $data ) .')';
        // echo '</script>';
    }
    private function init_static_vars(){
        self::$destinationBrick  = 'NO_DATA';
        self::$pathSteps  = 'NO_DATA';
        self::$pathStepsPlacement  = 'NO_DATA';
        self::$map  = 'NO_DATA';

        self::$originSectionName = 'NO_DATA';
        self::$destinationBrickNumber  = 'NO_DATA';
    }
    private function get_static_vars(){
        // $class = new ReflectionClass($this);
        // $vars = $class->getStaticProperties();
        $vars = [
            'url' => self::$url,
            'destinationBrick' => self::$destinationBrick,
            'pathSteps' => self::$pathSteps,
            'pathStepsPlacement' => self::$pathStepsPlacement,
            'map' => self::$map,
            'originSectionName' => self::$originSectionName,
            'destinationBrickNumber' => self::$destinationBrickNumber
        ];
        return $vars;
    }
    private function log_static_vars(){
        $this->console_log("...printing static vars:");
        $this->console_log( $this->get_static_vars() );
    }
    public function register(){
        // $url = 'https://us-central1-map-annotation-tool.cloudfunctions.net/path_narrative';
        self::$url = 'https://us-central1-ak-mapping-api.cloudfunctions.net/path_narrative';
        
        $this->init_static_vars();
        $this->getBestPathFromApi(); //TODO: Replace this with a call when results page is loaded.
        // $this->log_static_vars();

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
        $this->console_log("> ak-mapping-mapping.make_api_request( $url, $params )");
        $this->console_log("...api call wp_remote_get( \"$url?$params\" )");
        try {
            $response = '{ERROR:"NO_DATA"}';
            // $response = wp_remote_get( "$url?$params" );
            $response = wp_remote_get( "https://us-central1-ak-mapping-api.cloudfunctions.net/path_narrative?userId=auth0|5d976539de2c080c4f8913ff&originSectionName=129A&destinationBrickNumber=34141");
            // $response =  Requests::get( $url . "?" . $params );
            $this->console_log("...response: $response");
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
    private function getParamsFromUrl($key){
        if (isset($_GET[$key])) {
            return $_GET[$key];
          } else {
            //Handle the case where there is no parameter
            $this->console_log("...could not find \$_GET[$key] in url.");
            return 'No_DATA_FOUND';
          }
    }
    public function getBestPathFromApi(){
        // $url = 'https://fb-functions-getting-started.firebaseapp.com/api/v1/instructions';
        // $params = '';
        self::$originSectionName = $this->getParamsFromUrl('originSectionName');
        self::$destinationBrickNumber = $this->getParamsFromUrl('destinationBrickNumber');

        $url = self::$url;
        $params = "";
        $params .= "userId=auth0|5d976539de2c080c4f8913ff";
        // $params .= "&originSectionName=$originSectionName";
        $params .= "&originSectionName=129A";
        // $params .= "&destinationBrickNumber=$destinationBrickNumber";
        $params .= "&destinationBrickNumber=34141";
        // $params = 'userId=auth0|5d976539de2c080c4f8913ff&originSectionName=129A&destinationBrickNumber=34141';
        // http://marine.advancedkiosksmarketing.com/result/?originSectionName=129A&destinationBrickNumber=34141
        $this->console_log("... making api request ( $url, $params )");
        
        // get mock data from file
        $pathMockDataPath = $this->plugin_url . 'assets/datamocks/map-bestpath-mock.json';
        $res = json_decode(file_get_contents($pathMockDataPath), false);

        // make api call
        // $res = $this->make_api_request( $this->url, $params );  //TODO: Need to fix authentication issue before making api call.
        
        // parse api response and set global vars
        $this->parse_api_response( $res );

        return $res;
    }
    private function parse_api_response($json) {
        $this->console_log("> parse_api_response(\$json);");
        $this->console_log($json);

        //parse object
        // parse steps
        $steps = ( !empty($json->data->steps) ) ? $json->data->steps : ['  ERROR: No steps found.'];
        self::$pathSteps = $steps;
        // parse steps destination brick
        if (count($steps) > 0){
            self::$destinationBrick = ( !empty(end($steps)->brick)  ) ? end($steps)->brick : '  ERROR: No destination brick found.';
            // parse steps placement
            self::$pathStepsPlacement = ( !empty(end($steps)->placement )  ) ? end($steps)->placement : '  ERROR: No destination brick found.';
            // parse map
            self::$map = ( !empty($json->data->svg) ) ? $json->data->svg : '  ERROR: No destination brick found.';
        }
    }
    public function getIntersectionThumbnail($mapCroppedArea){
        // $mapCroppedArea = (object) [
        //     'height' => 100,
        //     'width' => 100,
        //     'x' => 767.87164578272,
        //     'y' => 257.21360501678
        // ];
        // $this->console_log("...\$mappedCroppedArea<" . gettype($mapCroppedArea) . ">:");
        // $this->console_log($mapCroppedArea);
        $itn = self::$map;  //intersection thumbnail
        // $this->console_log("...inital \$itn");
        // $this->console_log($itn);

        $view_box_dims = implode(' ', [$mapCroppedArea->x, $mapCroppedArea->y, $mapCroppedArea->height, $mapCroppedArea->width]);
        // $this->console_log("...new \$view_box_dims?");
        // $this->console_log($view_box_dims);

        $itn = $this->replaceAttr('viewBox="', $view_box_dims, $itn); //TODO: Ugly.  Replace with a way to set attr directly.

        // $this->console_log("...new \$itn?");
        // $this->console_log($itn);

        return $itn;
    }
    private function replaceAttr($attrKey, $newValue, $itn){

        $avsi = strpos( $itn , $attrKey ) + strlen($attrKey); //index of beginning of attr value
        $avei = strpos( $itn , '" ', $avsi ); //index of end of attr value
        $oldValue = substr($itn,$avsi, ($avei-$avsi));
        $itn = str_replace ($oldValue, $newValue, $itn);
        return $itn;
    }
    public function getLandmarkImage($filename){ //TODO: Landmark filenames are wrong.  Need to fix filenames on firestore.
        $imgtag = "";
        $imagePath = $this->plugin_url . 'assets/img/landmarks/' . $filename;
        $imgtag = '<img src="' . $imagePath . '" >';
        // $this->console_log("...returing imgtag:");
        // $this->console_log($imgtag);
        return $imgtag;
    }

    /**
     * Appends a message to the bottom of a single post including the number of followers and the last Tweet.
     *
     * @access public
     * @param  $content    The post content
     * @return $content    The post content with the mapping api information appended to it.
     */
    // public function display_mapping_information() {
        //     $content = "";
        //     $url = 'https://us-central1-map-annotation-tool.cloudfunctions.net/path_narrative';
        //     $params = 'userId=auth0|5d976539de2c080c4f8913ff&origin=129A&destination=220';
        //     // If we're on a single post or page...
        //     if ( is_single() ) {
                
        //         // ...attempt to make a response to twitter. Note that you should replace your username here!
        //         if ( null == ( $json_response = $this->make_api_request( $url, $params ) ) ) {
        //             // ...display a message that the request failed
        //             $html = '
        //     <div id="ak-mapping-content">';
        //     $html .= 'There was a problem communicating with the AK Mapping API..';
        //     $html .= '</div>;
        //     <!-- /#ak-mapping-content -->';
        //         // ...otherwise, read the information provided by api
        //         } else {
        //             $steps = get_steps();
        //             $html = '
        //     <div id="ak-mapping-content">';
        //     $html .= "<ul>";
        //             foreach( $steps as $step ) {
        //             $html .= "<li>{$step['OriginSectionName']} to {$step['DestinationSectionName']}</li>";
        //     $html .= "</ul>"; 
        //     $html .= '</div>
        //     <!-- /#ak-mapping-content -->';
        //             }
        //         } 
        //         $content .= $html;
        //     } 
        //     return $content;
        // } 
}

