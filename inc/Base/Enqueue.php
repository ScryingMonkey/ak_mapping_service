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
        wp_enqueue_style('mypluginstyle', $this->plugin_url . 'assets/mystyle.css' );
        wp_enqueue_style('mypluginscript', $this->plugin_url . 'assets/myscript.js' );
    }
}