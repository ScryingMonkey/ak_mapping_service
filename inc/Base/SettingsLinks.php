<?php

/**
 *  @package AK_Mapping_Service
 */

namespace Inc\Base;
use \Inc\Base\BaseController;

class SettingsLinks extends BaseController
{
    function register() {
        add_filter("plugin_action_links_$this->plugin", array( $this, 'settings_link'));
    }
    function settings_link( $links ) {
        $settings_link = '<a href="admin.php?page=AK_Mapping_Service">Settings</a>';
        array_push($links, $settings_link);
        return $links;
    }
}