<?php
/**
 * @package AK_Mapping_Service
 */
namespace Inc\Base;

class Activate
{
    public static function activate(){
        flush_rewrite_rules();
    }
}