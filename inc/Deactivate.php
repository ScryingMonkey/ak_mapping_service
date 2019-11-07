<?php
/**
 *  @package AK_Mapping_Service
 */

 namespace Inc;

 class Deactivate
 {
    public static function deactivate() {
        flush_rewrite_rules();
    }
 }