<?php
/**
 *  @package AK_Mapping_Service
 */

 namespace Inc\Base;

 class Deactivate
 {
    public static function deactivate() {
        flush_rewrite_rules();
    }
 }