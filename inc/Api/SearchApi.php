<?php
/**
 * @package AK_Mapping_Service
 */

namespace Inc\Api;

use \Inc\Base\BaseController;

class SearchApi extends BaseController
{
    public $search_shortcode = 'search_bar';
    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
    }
    public function register(){
        // $autoCompleteList = $this->getAutoCompleteList();
        add_shortcode('search_bar', array($this,'print_searchbar')); //TODO: Link to var in admin panel
    }
    public function print_searchbar(){
        $this->console_log("...loading [search_bar].");
        ob_start();
        include "$this->plugin_path/templates/search-bar-template.php";
        return ob_get_clean(); 
    }

    // private function make_api_request( $url, $params ) 
    // {
    //     try {
    //         $response = wp_remote_get( $url . "?" . $params );
    //     } catch ( Exception $ex ) {
    //         return "{data: \"Api request returned an error. $ex\"}";
    //     }
    //     try {
    //         // Note that we decode the body's response since it's the actual JSON feed
    //         $json = json_decode( $response['body'] );
    //     } catch ( Exception $ex ) {
    //         // $json = null;
    //         return "{data: \"Could not decode json. $ex\"}";
    //     }
    //     $this->console_log("...reponse from api get [$url?$params]");
    //     $this->console_log($json);
    //     return $json;
 
    // }
    // function getAutoCompleteList()
    // {
    //     // $url = 'https://us-central1-map-annotation-tool.cloudfunctions.net/path_narrative';
    //     // $params = 'userId=auth0|5d976539de2c080c4f8913ff&originSectionName=129A&destinationBrickNumber=34141';
    //     // $res = $this->make_api_request($url, $params);

        
        
    //     $dummy_res = ['Mark', 'John', 'Robert', 'Bill', 'Joe', '102345', '102378', '101345'];
    //     $res = $dummy_res;
    //     return $res;
    // }
    // function search($searchTerm, $data)
    // {
    //     $this->console_log(">> clab-search.clabSearch( $searchTerm, [ data ] )");
    //     $res = [];
    //     if(strlen($searchTerm) > 0 && count($data) > 0)
    //     {
    //         $searchTerm = $searchTerm.toLowerCase();
    //         foreach($data as $d)
    //         {
    //             if($d.toLowerCase().includes(searchTerm))
    //             {
    //                 res.push(item);
    //             }
    //         }
    //     }
    //     return res;
    // }
}