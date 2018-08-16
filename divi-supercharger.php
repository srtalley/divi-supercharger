<?php
/*
Plugin Name: Divi SUPERCHARGER
Description: Adds great features to Divi in the Theme Customizer under Divi SUPERCHARGER Options. Choose new blog layouts, hover effects, link hover colors, slider navigation, additional menu options and more!
Author: Dusty Sun
Author URI: http://dustysun.com
Plugin URI: https://dustysun.com/divi-supercharger/
Version: 1.2
Text Domain: ds_edivi
License: GPLv2
*/

namespace DustySun\Divi_Supercharger;
use \DustySun\WP_Settings_API\v2 as DSWPSettingsAPI;
use \DustySun\WP_License_Agent\Client\v1_5 as WPLA;
use \DustySun\Divi_Supercharger\VideoEmbedding\v1 as VideoEmbed;

//Include the admin panel page
require_once( dirname( __FILE__ ) . '/lib/dustysun-wp-settings-api/ds_wp_settings_api.php');
require_once( dirname( __FILE__ ) . '/includes/theme-customizer.php');
require_once( dirname( __FILE__ ) . '/includes/video-embed-filters.php');
require_once( dirname( __FILE__ ) . '/divi-supercharger-admin.php');

//Add update checker
require_once( dirname( __FILE__ ) . '/lib/wp-license-agent-client/wp-license-agent.php');

class Enhanced_Divi {

  private $ds_edivi_json_file;
  private $ds_edivi_settings_obj;
  private $current_settings;
  private $ds_edivi_video_embed_filters;

  public function __construct() {
    
    // auto update
    add_action('plugins_loaded', array($this, 'ds_wpla_build_update_checker') );
    
    // get the settings
    $this->ds_edivi_get_current_settings();

    // Video embed filters
    $this->ds_edivi_video_embed_filters = new VideoEmbed\OembedFilters();    
    // Register scripts
    add_action( 'wp_enqueue_scripts', array( $this, 'ds_edivi_register_styles_scripts' ) );

    // set the default settings
    register_activation_hook( __FILE__, array($this, 'ds_edivi_default_settings' ));
  } // end public function __construct

  public function ds_edivi_get_current_settings() {

    // set the settings api options
		$ds_api_settings = array(
      'json_file' => plugin_dir_path( __FILE__ ) . '/divi-supercharger.json'
    );
    
    $this->ds_edivi_settings_obj = new DSWPSettingsAPI\SettingsBuilder($ds_api_settings);
    // get the settings
    $this->current_settings = $this->ds_edivi_settings_obj->get_current_settings();
    // Get the plugin options
    $this->ds_edivi_main_settings = $this->ds_edivi_settings_obj->get_main_settings();
  } // end function ds_wpla_get_current_settings

  public function ds_edivi_default_settings() {
    // Must be called after the settings_obj is set
    if(!$this->ds_edivi_settings_obj || $this->ds_edivi_settings_obj == '') {
      $this->ds_edivi_get_current_settings();
    } // end if
    $this->ds_edivi_settings_obj->set_current_settings(true);
    $this->ds_edivi_settings_obj->set_main_settings(true);
  } // end function ds_edivi_default_settings()

  public function ds_edivi_register_styles_scripts() {

    // wp_enqueue_script('divi-supercharger-main', plugins_url('js/divi-supercharger.js', __FILE__), '', '', true);

    // wp_enqueue_style('divi-supercharger-styles', plugins_url('/css/divi-supercharger.css', __FILE__));

  } //end function ds_edivi_modify_load_css

  public function ds_wpla_build_update_checker() {

    $settings = array(
      'update_url' => $this->ds_edivi_main_settings['wpla_update_url'],
      'update_slug' => $this->ds_edivi_main_settings['item_slug'],
      'main_file' => __FILE__,
      'news_widget' => true,
      'puc_errors' => true
    );
    $this->update_checker = new WPLA\Licensing_Agent($settings);

  } // end function ds_wpla_build_update_checker
    // Logging function 
    public function wl ( $log )  {
      if ( true === WP_DEBUG ) {
          if ( is_array( $log ) || is_object( $log ) ) {
              error_log( print_r( $log, true ) );
          } else {
              error_log( $log );
          }
      }
    } // end public function wl 
} // end class Enhanced_Divi

$ds_edivi = new Enhanced_Divi();
