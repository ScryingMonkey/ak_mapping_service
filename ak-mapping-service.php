<?php
/**
 * @package AK_Mapping_Service
 * @version 0.0.1
 */
 /*
 * Plugin Name: AK Mapping Service
 * Description: Provide functions to call the AK Mapping API.
 * Plugin URI: https://www.advancedkiosks.com
 * Version:     0.0.1
 * Author:      Clif Boyd
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(file_exists(dirname(__FILE__) . '/vendor/autoload.php')){
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

use Inc\Activate;
use Inc\Deactivate;

// if(!class_exists('AK_Mapping_Service')){

// }



class AK_Mapping_Service 
{
    public $plugin;
    private $my_plugin_screen_name;
    private static $instance;
 
    function __construct() 
    {
        $this->plugin = plugin_basename( __FILE__ );
    }
    static function GetInstance() 
    {
        if (!isset(self::$instance))
        {
            self::$instance = new self();
        }
        return self::$instance;
    }
    function register()
    {
        add_action('admin_menu', array($this,'add_admin_pages'));
        add_action('admin_enqueue_scripts', array($this,'enqueue'));
        add_action('wp_enqueue_scripts', array($this,'enqueue'));
        add_filter("plugin_action_links_$this->plugin", array( $this, 'settings_link'));
    }
    function settings_link( $links ){
        $settings_link = '<a href="admin.php?page=AK_Mapping_Service">Settings</a>';
        array_push($links, $settings_link);
        return $links;
    }
    function enqueue() {
        wp_enqueue_style('mypluginstyle', plugins_url('/assets/mystyle.css', __FILE__));
    }
    public function add_admin_pages()
    {
        /* 
            page_title: The page title.
            menu_title: The menu title displayed on dashboard.
            capability: Minimum capability to view the menu.
            menu_slug: Unique name used as a slug for menu item.
            function: A callback function used to display page content.
            icon_url: URL to custom image used as icon.
            position: Location in the menu order.
            
            add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
            */
        $page_title = "AK Mapping Service Page";
        $menu_title = "AK Mapping Service Menu";
        $capability = "manage_options";
        $menu_slug = "AK_Mapping_Service";
        $function = array($this, 'admin_index');
        $icon_url = "dashicons-admin-site-alt3"; // from https://developer.wordpress.org/resource/dashicons/#admin-site-alt3
        $position = plugins_url('/img/AK-logo.png',__DIR__);
        $this->my_plugin_screen_name  = add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
    }
    function admin_index(){
        require_once plugin_dir_path( __FILE__ ) . 'templates/admin-index.php';
    }
    function ak_render_custom_page(){
        ?>
        <div class='wrap'>
         <h2></h2>
        </div>
        <?php
      }
      function ak_render_about_page(){
        ?>
        <div class='wrap'>
         <h2></h2>
        </div>
        <?php
      }
 
    /**
     * Appends a message to the bottom of a single post including the number of followers and the last Tweet.
     *
     * @access public
     * @param  $content    The post content
     * @return $content    The post content with the mapping api information appended to it.
     */
    function display_mapping_information( $content ) {
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
        $response = wp_remote_get( $url . "?" . $params );
        // $response = wp_remote_get( 'https://twitter.com/users/' . $username . '.json' );
        try {
     
            // Note that we decode the body's response since it's the actual JSON feed
            $json = json_decode( $response['body'] );
     
        } catch ( Exception $ex ) {
            $json = null;
        } // end try/catch
     
        return $json;
 
    }
 
    /**
     * Retrieves the number of followers from the JSON feed
     *
     * @access private
     * @param  $json     The mapping json
     * @return           The mag svg.
     */
    private function get_map_image( $json ) {
        return ( -1 < $json->data->Svg ) ? $json->data->Svg : -1;
    }
 
    /**
     * Retrieves the walking instructions
     *
     * @access private
     * @param  $json     The mapping json
     * @return           The walking instructions.
     */
    private function get_steps( $json ) {
        return ( 0 < strlen( $json->data->Steps ) ) ? $json->data->Steps : '[ No steps found. ]';
    }
    function activate(){
        Activate::activate();
    }
} // end class


if(class_exists('AK_Mapping_Service')){
    $akMappingService = new AK_Mapping_Service();
    $akMappingService->register();
    //activation
    register_activation_hook(__FILE__, array($akMappingService, 'activate'));
    //deactivation
    register_deactivation_hook(__FILE__, array('Deactivate', 'deactivate'));
}
// TODO: Change define( 'WP_DEBUG', true ); to false.  wp-config-sample.php