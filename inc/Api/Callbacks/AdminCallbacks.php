<?php
/**
 * @package AK_Mapping_Service
 */

namespace Inc\Api\Callbacks;

 use \Inc\Base\BaseController;
 

 class AdminCallbacks extends BaseController
 {
    public function adminDashboard()
    {
        return require_once("$this->plugin_path/templates/admin-dashboard.php" );
    }
    public function adminMapDashboard()
    {
        return require_once("$this->plugin_path/templates/admin-map-dashboard.php" );
    }
    public function adminInstructionsDashboard()
    {
        return require_once("$this->plugin_path/templates/admin-instructions-dashboard.php" );
    }
    public function adminSearchDashboard()
    {
        return require_once("$this->plugin_path/templates/admin-search-dashboard.php" );
    }
    public function akOptionsGroup( $input )
    {
        return $input;
    }
    public function akAdminSection()
    {
        echo 'Check this beautiful section!';
    }
    public function akMapShortCode()
    {
        $value = esc_attr( get_option( 'shortcode_map' ) );
        echo '<input type="text" class="regular-text" name="shortcode_map" value="' . $value . '" placeholder="path_map">';
    }
    public function akInstructionsShortCode()
    {
        $value = esc_attr( get_option( 'shortcode_instructions' ) );
        echo '<input type="text" class="regular-text" name="shortcode_instructions" value="' . $value . '" placeholder="path_instructions">';
    }
    public function akSearchShortCode()
    {
        $value = esc_attr( get_option( 'shortcode_search' ) );
        echo '<input type="text" class="regular-text" name="shortcode_search" value="' . $value . '" placeholder="path_search_bar">';
    }
    public function akLandmarkImageShortCode()
    {
        $value = esc_attr( get_option( 'shortcode_landmark_image' ) );
        echo '<input type="text" class="regular-text" name="shortcode_landmark_image" value="' . $value . '" placeholder="landmark_image">';
    }
    public function akLandmarkDescriptionShortCode()
    {
        $value = esc_attr( get_option( 'shortcode_landmark_description' ) );
        echo '<input type="text" class="regular-text" name="shortcode_landmark_description" value="' . $value . '" placeholder="landmark_description">';
    }
 }

