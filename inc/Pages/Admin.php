<?php

/**
 *  @package AK_Mapping_Service
 */

namespace Inc\Pages;

use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;
use \Inc\Api\Callbacks\AdminCallbacks;

class Admin extends BaseController
{
    public $settings;
    public $callbacks;
    public $pages;
    public $subpages;

    public function register()
    {                
        $this->settings = new SettingsApi();
        $this->callbacks = new AdminCallbacks();
        $this->setPages();
        $this->setSubPages();
        $this->setSettings();
        $this->setSections();
        $this->setFields();
        $this->settings->addPages( $this->pages )->withSubPage('Dashboard')->addSubPages($this->subpages)->register();
    }
    public function setPages()
    {
        $this->pages = [
            [
                'page_title' => "AK Mapping Service Page",
                'menu_title' => "AK Mapping Service",
                'capability' => "manage_options",
                'menu_slug' => "AK_Mapping_Service",
                'callback' => array($this->callbacks, 'adminDashboard'), //array($this,'adminPage'),
                'icon_url' => "dashicons-admin-site-alt3", // from https://developer.wordpress.org/resource/dashicons/#admin-site-alt3
                'position' => 110
            ]
        ];
    }
    public function setSubPages()
    {
        $this->subpages = [
            [
                'parent_slug' => 'AK_Mapping_Service',
                'page_title' => 'Best Path Map',
                'menu_title' => 'Map',
                'capability' => 'manage_options',
                'menu_slug' => 'ak_mapping_map',
                'callback' => array($this->callbacks,'mapPage')
            ],[
                'parent_slug' => 'AK_Mapping_Service',
                'page_title' => 'Best Path Walking Instructions',
                'menu_title' => 'Instructions',
                'capability' => 'manage_options',
                'menu_slug' => 'ak_mapping_instructions',
                'callback' => array($this->callbacks,'instructionsDashboard')
            ],[
                'parent_slug' => 'AK_Mapping_Service',
                'page_title' => 'Brick Search',
                'menu_title' => 'Search',
                'capability' => 'manage_options',
                'menu_slug' => 'ak_mapping_brick_search',
                'callback' => function() { }
            ]

        ];
    }
    public function setSettings()
    {
        $args = [
            [
                'option_group' => 'ak_options_group',
                'option_name' => 'shortcode_map',
                'callback' => array( $this->callbacks, 'akOptionsGroup')
            ],[
                'option_group' => 'ak_options_group',
                'option_name' => 'shortcode_instructions',
            ],[
                'option_group' => 'ak_options_group',
                'option_name' => 'shortcode_search',
            ],[
                'option_group' => 'ak_options_group',
                'option_name' => 'shortcode_landmark_image',
            ],[
                'option_group' => 'ak_options_group',
                'option_name' => 'shortcode_landmark_description',
            ]
        ];
        $this->settings->setSettings($args);
    }
    public function setSections()
    {
        $args = [
            [
                'id' => 'ak_admin_index',
                'title' => 'Settings',
                'callback' => array( $this->callbacks, 'akAdminSection'),
                'page' => 'AK_Mapping_Service'
            ]
        ];
        $this->settings->setSections($args);
    }
    public function setFields()
    {
        $args = [
            [
                'id' => 'shortcode_map',
                'title' => 'Shortcode for map image',
                'callback' => array( $this->callbacks, 'akMapShortCode'),
                'page' => 'AK_Mapping_Service',
                'section' => 'ak_admin_index',
                'args' => array(
                    'label_for' => 'text_example',
                    'class' => 'example-class'
                )
            ],[
                'id' => 'shortcode_instructions',
                'title' => 'Shortcode for best path walking instructions',
                'callback' => array( $this->callbacks, 'akInstructionsShortCode'),
                'page' => 'AK_Mapping_Service',
                'section' => 'ak_admin_index',
                'args' => array(
                    'label_for' => 'shortcode_instructions',
                    'class' => 'example-class'
                )
            ],[
                'id' => 'shortcode_search',
                'title' => 'Shortcode for best path search bar',
                'callback' => array( $this->callbacks, 'akSearchShortCode'),
                'page' => 'AK_Mapping_Service',
                'section' => 'ak_admin_index',
                'args' => array(
                    'label_for' => 'shortcode_search',
                    'class' => 'example-class'
                )
            ],[
                'id' => 'shortcode_landmark_image',
                'title' => 'Shortcode for Landmark Image',
                'callback' => array( $this->callbacks, 'akLandmarkImageShortCode'),
                'page' => 'AK_Mapping_Service',
                'section' => 'ak_admin_index',
                'args' => array(
                    'label_for' => 'shortcode_landmark_image',
                    'class' => 'example-class'
                )
            ],[
                'id' => 'shortcode_landmark_description',
                'title' => 'Shortcode for Landmark Description',
                'callback' => array( $this->callbacks, 'akLandmarkDescriptionShortCode'),
                'page' => 'AK_Mapping_Service',
                'section' => 'ak_admin_index',
                'args' => array(
                    'label_for' => 'shortcode_landmark_description',
                    'class' => 'example-class'
                )
            ]
        ];
        $this->settings->setFields($args);
    }
}
