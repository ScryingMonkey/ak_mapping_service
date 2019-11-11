<?php
/**
 * @package AK_Mapping_Service
 */

namespace Inc\Api;

class MappingApi
{
    public function register(){
        add_shortcode('test1', array($this,'html_return_1'));
        add_shortcode('test2', array($this,'html_return_2'));
    }
    function html_return_1(){
        $html = "";
        $html .= "<div>";
        $html .= "<h1>This is a test for shortcodes.</h1>";
        $html .= "<p>Lots and lots of text.Lots and lots of text.Lots and lots of text.Lots and lots of text.Lots and lots of text.Lots and lots of text.</p>";
        $html .= "<p>Lots and lots of text.Lots and lots of text.Lots and lots of text.Lots and lots of text.Lots and lots of text.Lots and lots of text.</p>";
        $html .= "<p>Lots and lots of text.Lots and lots of text.Lots and lots of text.Lots and lots of text.Lots and lots of text.Lots and lots of text.</p>";
        $html .= "<p>Lots and lots of text.Lots and lots of text.Lots and lots of text.Lots and lots of text.Lots and lots of text.Lots and lots of text.</p>";
        $html .= "<h2>This is the end of the test text.</h2>";
        $html .= "</div>";
        return $html;
    }
    function html_return_2(){
        $html = '<div class="testText">';
        $html .= "TEST <b>TEST</b> TEST";
        $html .= "</div>";
        return $html;
    }
}
    
    // /**
    //  * Appends a message to the bottom of a single post including the number of followers and the last Tweet.
    //  *
    //  * @access public
    //  * @param  $content    The post content
    //  * @return $content    The post content with the mapping api information appended to it.
    //  */
    // function display_mapping_information( $content ) {
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
    // /**
    //  * Attempts to request the specified user's JSON feed from Twitter
    //  *
    //  * @access private
    //  * @param  $url     The url for the JSON feed we're attempting to retrieve
    //  * @return $params  A string of all the params for the api call
    //  * @return $json    The JSON feed or null if the request failed
    //  */
    // private function make_api_request( $url, $params ) {
    //     $response = wp_remote_get( $url . "?" . $params );
    //     // $response = wp_remote_get( 'https://twitter.com/users/' . $username . '.json' );
    //     try {
     
    //         // Note that we decode the body's response since it's the actual JSON feed
    //         $json = json_decode( $response['body'] );
     
    //     } catch ( Exception $ex ) {
    //         $json = null;
    //     } // end try/catch
     
    //     return $json;
 
    // }
 
    // /**
    //  * Retrieves the number of followers from the JSON feed
    //  *
    //  * @access private
    //  * @param  $json     The mapping json
    //  * @return           The mag svg.
    //  */
    // private function get_map_image( $json ) {
    //     return ( -1 < $json->data->Svg ) ? $json->data->Svg : -1;
    // }
 
    // /**
    //  * Retrieves the walking instructions
    //  *
    //  * @access private
    //  * @param  $json     The mapping json
    //  * @return           The walking instructions.
    //  */
    // private function get_steps( $json ) {
    //     return ( 0 < strlen( $json->data->Steps ) ) ? $json->data->Steps : '[ No steps found. ]';
    // }


