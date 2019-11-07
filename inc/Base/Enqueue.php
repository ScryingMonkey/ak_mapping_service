<?php

/**
 *  @package AK_Mapping_Service
 */

namespace Inc\Base;

class Enqueue
{
    public function register()
    {
        add_action('admin_enqueue_scripts', array($this,'enqueue'));
        add_action('wp_enqueue_scripts', array($this,'enqueue'));     
    }
    function enqueue() 
    {
        wp_enqueue_style('mypluginstyle', PLUGIN_URL . 'assets/mystyle.css' );
        wp_enqueue_style('mypluginscript', PLUGIN_URL . 'assets/myscript.js' );
    }
}