<?php

/**
 *  @package AK_Mapping_Service
 */

namespace Inc\Pages;

use \Inc\Base\BaseController;
use \Inc\Api\SettingsApi;

class Admin extends BaseController
{
    public $settings;
    public $pages;
    public $subpages;

    public function __construct(){
        $this->settings = new SettingsApi();
        $this->pages = [
            [
                'page_title' => "AK Mapping Service Page",
                'menu_title' => "AK Mapping Service",
                'capability' => "manage_options",
                'menu_slug' => "AK_Mapping_Service",
                'callback' => function() { echo '<h1>AK Plugin Test</h1>';},
                'icon_url' => "dashicons-admin-site-alt3", // from https://developer.wordpress.org/resource/dashicons/#admin-site-alt3
                'position' => 110
            ]
        ];
        $this->subpages = [
            [
                'parent_slug' => 'AK_Mapping_Service',
                'page_title' => 'Best Path Map',
                'menu_title' => 'Map',
                'capability' => 'manage_options',
                'menu_slug' => 'ak_mapping_map',
                'callback' => function() { echo '<h1>AK Mapping: Best Path Map</h1>';}
            ],[
                'parent_slug' => 'AK_Mapping_Service',
                'page_title' => 'Best Path Walking Instructions',
                'menu_title' => 'Instructions',
                'capability' => 'manage_options',
                'menu_slug' => 'ak_mapping_instructions',
                'callback' => function() { echo '<h1>AK Mapping: Best Path Walking Instructions</h1>';}
            ],[
                'parent_slug' => 'AK_Mapping_Service',
                'page_title' => 'Brick Search',
                'menu_title' => 'Search',
                'capability' => 'manage_options',
                'menu_slug' => 'ak_mapping_brick_search',
                'callback' => function() { echo '<h1>AK Mapping: Best Path Brick Search</h1>';}
            ]

        ];
    }
    public function register()
    {                
        $this->settings->addPages( $this->pages )->withSubPage('Dashboard')->addSubPages($this->subpages)->register();
    }
}