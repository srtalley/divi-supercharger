<?php

namespace DustySun\Divi_Supercharger;
use \DustySun\WP_Settings_API\v2 as DSWPSettingsAPI;

class Enhanced_Divi_Settings {

	private $ds_edivi_plugin_hook;

	private $ds_edivi_settings_page;

	private $ds_edivi_settings = array();

	private $ds_edivi_main_settings = array();

	private $ds_edivi_theme_customizer;

	// Create the object
	public function __construct() {

		// create the various menu pages 
		add_action( 'admin_menu', array($this, 'ds_edivi_create_admin_page'));

		// Register the menu
		add_action( 'admin_menu', array($this, 'ds_edivi_admin_menu' ));

		// add admin scripts
		add_action( 'admin_enqueue_scripts', array($this,  'ds_edivi_admin_scripts' ));

    	// make sure Divi is active
   		add_action( 'admin_notices', array($this, 'ds_edivi_admin_notices' ));

		// Add settings & support links
   		 add_filter( 'plugin_action_links', array($this,'ds_edivi_add_action_plugin'), 10, 5 );

		// check if we need to do any updates after plugin update
		add_action( 'upgrader_process_complete', array($this,'ds_edivi_wp_upgrade_complete'), 10, 2);

	} // end public function __construct()

	public function ds_edivi_create_admin_page() {
		// set the settings api options
		$ds_api_settings = array(
			'json_file' => plugin_dir_path( __FILE__ ) . '/divi-supercharger.json',
			'register_settings' => true,
			'views_dir' => plugin_dir_path( __FILE__ ) . '/admin/views'
		);
		// Create the settings object
		$this->ds_edivi_settings_page = new DSWPSettingsAPI\SettingsBuilder($ds_api_settings);

		// Create the customizer
 		// Get the current settings
		$this->ds_edivi_settings = $this->ds_edivi_settings_page->get_current_settings();

		// Get the plugin options
		$this->ds_edivi_main_settings = $this->ds_edivi_settings_page->get_main_settings();
	} // end function ds_edivi_create_admin_page

	// Adds admin menu under the Sections section in the Dashboard
	public function ds_edivi_admin_menu() {

		$this->ds_edivi_plugin_hook = add_menu_page(
				__('Divi SUPERCHARGER', 'ds_edivi'),
				__('Divi SUPERCHARGER', 'ds_edivi'),
				'manage_options',
				'divi-supercharger',
				array($this, 'ds_edivi_menu_options'),
				'dashicons-performance'
			);

	} // end public function ds_edivi_admin_menu()

	public function ds_edivi_admin_scripts( $hook ) {

		if($hook == $this->ds_edivi_plugin_hook) {
			wp_enqueue_style('divi-supercharger-admin', plugins_url('/css/divi-supercharger-admin.css', __FILE__));

		}
		if($hook == 'widgets.php'){
			//Load the JS for the theme customizer
			// wp_enqueue_script( 'divi-supercharger-admin', plugins_url( '/js/theme-customizer.js', __FILE__ ), array(), false, true );
		}
	} // end public function ds_edivi_add_color_picker

	// Admin notice
	public function ds_edivi_admin_notices() {

		if( get_transient( 'ds_edivi_updated' ) ) {
			echo '<div class="notice notice-success"><p>' . __( 'Thanks for updating Contact Form 7 SUPERCHARGER!', 'ds_edivi' ) . '</p></div>';
			delete_transient( 'ds_edivi_updated' );
		}

		// check if we need to upgrade any settings
		$this->ds_edivi_upgrade_process();

		$current_theme = wp_get_theme()->template;
		if ( $current_theme != 'Divi' ) {
				echo '<div class="error"><p>You have activated the <strong><a href="' . $this->ds_edivi_main_settings['item_uri']  . '">' .  $this->ds_edivi_main_settings['name'] .  '</a></strong> plugin, but you also need to install and activate the <a href="https://elegantthemes.com" target="_blank"><strong>Divi</strong></a> theme.</p></div>';
		} // end if

	} // end function ds_edivi_admin_notices

	// Create the actual options page
	public function ds_edivi_menu_options() {
		$ds_edivi_settings_title = $this->ds_edivi_main_settings['name'];

		// Create the main page HTML
		$this->ds_edivi_settings_page->build_settings_panel($ds_edivi_settings_title);
	} // end function

	//function to add settings links to plugins area
	public function ds_edivi_add_action_plugin( $actions, $plugin_file ) {

		$plugin = plugin_basename(__DIR__) . '/divi-supercharger.php';

		if ($plugin == $plugin_file) {

			$site_link = array('support' => '<a href="' . $this->ds_edivi_main_settings['item_uri'] . '" target="_blank">' . __('Support', $this->ds_edivi_main_settings['text_domain']) . '</a>');
			$actions = array_merge($site_link, $actions);

			if ( is_plugin_active( $plugin) ) {
				$settings = array('settings' => '<a href="admin.php?page=' . $this->ds_edivi_main_settings['page_slug'] . '">' . __('Settings', $this->ds_edivi_main_settings['text_domain']) . '</a>');
				$actions = array_merge($settings, $actions);
			} //end if is_plugin_active
		}
		return $actions;

	} // end function ds_edivi_add_action_plugin

	public function ds_edivi_upgrade_process(){

		$update_db_flag = false;

		// check the database version
		// Try the older version first
		$db_plugin_settings = get_option('ds_edivi_plugin_settings');
		if($db_plugin_settings != '') {
			// move the settings from the older key and delete it
			delete_option('ds_edivi_plugin_settings');
			update_option('ds_edivi_main_settings', $db_plugin_settings);
		} else {
			$db_plugin_settings = get_option('ds_edivi_main_settings');
		} // end if

		if($db_plugin_settings['version'] < '1.2') {

			// Remove the daily_news_check cron event 
			$timestamp = wp_next_scheduled ( $this->ds_edivi_main_settings['item_slug'] . '_daily_news_check' );
			wp_unschedule_event($timestamp, $this->ds_edivi_main_settings['item_slug'] . '_daily_news_check');

			// get the old license key option
			$ds_edivi_update_settings = get_option('ds_edivi_update_settings_options', true);
			if(isset($ds_edivi_update_settings['ds_edivi_update_email']) && $ds_edivi_update_settings['ds_edivi_update_email'] != '' )
			{
				update_option($this->ds_edivi_main_settings['item_slug'] . '_wpla_license_email', $ds_edivi_update_settings['ds_edivi_update_email']);
			} // end if

			if(isset($ds_edivi_update_settings['ds_edivi_update_serialnumber']) && $ds_edivi_update_settings['ds_edivi_update_serialnumber'] != '' )
			{
				update_option($this->ds_edivi_main_settings['item_slug'] . '_wpla_license_key', $ds_edivi_update_settings['ds_edivi_update_serialnumber']);
			} // end if

			// transfer the daily news check to daily license check
			$ds_edivi_daily_news_check =  get_option($this->ds_edivi_main_settings['item_slug'] . '_daily_news_check', true);

			update_option($this->ds_edivi_main_settings['item_slug'] . '_daily_license_check', $ds_edivi_daily_news_check);
			
			$update_db_flag = true;
		} //end if < 1.2
		
		if($db_plugin_settings['version'] != $this->ds_edivi_main_settings['version']) {
			$update_db_flag = true;
		} // end if 

		if($update_db_flag) {
			//update the version info stored in the DB
			$this->ds_edivi_settings_page->wl('Updating Divi SUPERCHARGER settings in DB...');
			$this->ds_edivi_settings_page->set_main_settings(true);
		} // end if($update_db_flag) 
		
   } // end function ds_edivi_upgrade_process

	public function ds_edivi_wp_upgrade_complete( $upgrader_object, $options ) {
		$current_plugin_path_name = plugin_basename(__DIR__) . '/contact-form-7-supercharger.php';
		if ($options['action'] == 'update' && $options['type'] == 'plugin' ){
	   foreach($options['plugins'] as $each_plugin){
				 set_transient('ds_edivi_updated', 1);
			 } // end foreach($options['plugins'] as $each_plugin)
		 } // end if ($options['action'] == 'update' && $options['type'] == 'plugin' )
	} // end function ds_edivi_wp_upgrade_complete

} // end class Enhanced_Divi_Settings
if( is_admin() )
    $ds_edivi_settings_page = new Enhanced_Divi_Settings();
