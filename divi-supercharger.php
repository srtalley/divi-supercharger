<?php
/*
Plugin Name: Divi SUPERCHARGER
Description: Adds great features to Divi in the Theme Customizer under Divi SUPERCHARGER Options. Choose new blog layouts, hover effects, link hover colors, slider navigation, additional menu options and more!
Author: Dusty Sun
Author URI: http://dustysun.com
Plugin URI: https://dustysun.com/divi-supercharger/
Version: 1.1.1
Text Domain: ds_edivi
License: GPLv2
*/
// Namespace
namespace DS_Divi_Supercharger;

//Include the admin panel page
require_once( dirname( __FILE__ ) . '/lib/dustysun-wp-settings-api/ds_wp_settings_api.php');
require_once( dirname( __FILE__ ) . '/includes/theme-customizer.php');
require_once( dirname( __FILE__ ) . '/includes/video-embed-filters.php');
require_once( dirname( __FILE__ ) . '/divi-supercharger-admin.php');
//Add update checker
require_once( dirname( __FILE__ ) . '/lib/wp-license-agent-update-checker.php');

class Enhanced_Divi {

  private $ds_edivi_json_file;
  private $ds_edivi_settings_obj;
  private $current_settings;
  private $ds_edivi_video_embed_filters;


  public function __construct() {

    // get the settings
    $this->ds_edivi_get_current_settings();

    // Video embed filters
    $this->ds_edivi_video_embed_filters = new VideoEmbedding\v1\OembedFilters();

    // set the default settings
		register_activation_hook( __FILE__, array($this, 'ds_edivi_default_settings' ));

    // auto update
		add_action('init', array($this, 'ds_wpla_build_update_checker') );

    // Register scripts
    add_action( 'wp_enqueue_scripts', array( $this, 'ds_edivi_register_styles_scripts' ) );

  } // end public function __construct

  public function ds_edivi_get_current_settings() {

    // set the settings api options
		$ds_api_settings = array(
      'json_file' => plugin_dir_path( __FILE__ ) . '/divi-supercharger.json'
    );
    
    $this->ds_edivi_settings_obj = new \Dusty_Sun\WP_Settings_API\v1\DustySun_WP_Settings_API($ds_api_settings);
    // get the settings
    $this->current_settings = $this->ds_edivi_settings_obj->get_current_settings();
    // Get the plugin options
    $this->ds_edivi_plugin_options = $this->ds_edivi_settings_obj->get_plugin_options();
  } // end function ds_wpla_get_current_settings

  public function ds_edivi_default_settings() {
    $this->ds_edivi_settings_obj->set_current_settings(true);
    $this->ds_edivi_settings_obj->set_plugin_options(true);
  } // end function ds_edivi_default_settings()

  public function ds_edivi_register_styles_scripts() {

    // wp_enqueue_script('divi-supercharger-main', plugins_url('js/divi-supercharger.js', __FILE__), '', '', true);

    // wp_enqueue_style('divi-supercharger-styles', plugins_url('/css/divi-supercharger.css', __FILE__));

  } //end function ds_edivi_modify_load_css

  public function ds_edivi_conditional_style_enqueue($ds_handle='', $ds_path = '', $ds_version = '', $ds_dependencies = 'array()', $ds_media = 'all'){
    if(wp_style_is($ds_handle, $ds_list = 'enqueued')){
      return;
    } else {
      wp_enqueue_style($ds_handle, $ds_path, $ds_dependencies, $ds_version, $ds_media);
    }
  } // end public function ds_conditional_enqueue


  public function ds_wpla_build_update_checker() {

    // update server URL 
    $update_url = 'https://dustysun.com';
    if(get_site_url() == $update_url) {
      $update_url = 'https://maximus.client.dustysun.com';
    } // end if(get_site_url() == $update_url)

    $ds_wpla_plugin_slug = $this->ds_edivi_plugin_options['page_slug'];
    $email_address = $this->current_settings['ds_edivi_update_settings_options']['ds_edivi_update_email'];
    $license_key = $this->current_settings['ds_edivi_update_settings_options']['ds_edivi_update_serialnumber'];
    $settings = array(
      'update_url' => $update_url,
      'plugin_slug' => $ds_wpla_plugin_slug,
      'plugin_file' => __FILE__,
      // 'type' => $license_type,
      'license' => $license_key,
      'email' => $email_address,
      'news_widget' => true,
      'puc_errors' => false
    );
    $this->update_checker = new \WP_License_Agent\Updater\v1\UpdateChecker($settings);
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
