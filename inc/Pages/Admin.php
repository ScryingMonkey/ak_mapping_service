<?php

/**
 *  @package AK_Mapping_Service
 */

namespace Inc\Pages;

class Admin
{
    public function register()
    {
        add_action('admin_menu', array($this,'add_admin_pages'));      
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
        require_once PLUGIN_PATH . 'templates/admin-index.php';
    }
}