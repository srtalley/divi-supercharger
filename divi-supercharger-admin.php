<?php
class Enhanced_Divi_Settings {

	private $ds_edivi_plugin_hook;

	private $ds_edivi_settings_page;

	private $ds_edivi_settings = array();

	private $ds_edivi_plugin_options = array();

	private $ds_edivi_theme_customizer;

	// Create the object
	public function __construct() {

		// set the settings api options
		$ds_api_settings = array(
			'json_file' => plugin_dir_path( __FILE__ ) . '/divi-supercharger.json',
			'register_settings' => true,
			'views_dir' => plugin_dir_path( __FILE__ ) . '/admin/views'
		);
		// Create the settings object
		$this->ds_edivi_settings_page = new \Dusty_Sun\WP_Settings_API\v1\DustySun_WP_Settings_API($ds_api_settings);

		// Create the customizer
 		// Get the current settings
		$this->ds_edivi_settings = $this->ds_edivi_settings_page->get_current_settings();

		// Get the plugin options
		$this->ds_edivi_plugin_options = $this->ds_edivi_settings_page->get_plugin_options();

		// Register the menu
		add_action( 'admin_menu', array($this, 'ds_edivi_admin_menu' ));

		// add admin scripts
		add_action( 'admin_enqueue_scripts', array($this,  'ds_edivi_admin_scripts' ));

    // make sure CF7 is active
    add_action( 'admin_notices', array($this, 'ds_edivi_admin_notices' ));

		// Add settings & support links
    add_filter( 'plugin_action_links', array($this,'ds_edivi_add_action_plugin'), 10, 5 );

	} // end public function __construct()

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
		$current_theme = wp_get_theme()->template;
			if ( $current_theme != 'Divi' ) {
					echo '<div class="error"><p>You have activated the <strong><a href="' . $this->ds_edivi_plugin_options['plugin_uri']  . '">' .  $this->ds_edivi_plugin_options['plugin_name'] .  '</a></strong> plugin, but you also need to install and activate the <a href="https://elegantthemes.com" target="_blank"><strong>Divi</strong></a> theme.</p></div>';
			}
	} // end function ds_edivi_admin_notices

	// Create the actual options page
	public function ds_edivi_menu_options() {
		$ds_edivi_settings_title = $this->ds_edivi_plugin_options['plugin_name'];

		// Create the main page HTML
		$this->ds_edivi_settings_page->build_plugin_panel($ds_edivi_settings_title);
	} // end function

	//function to add settings links to plugins area
	public function ds_edivi_add_action_plugin( $actions, $plugin_file )
	{

			static $plugin;

			if (!isset($plugin))
				$plugin = plugin_basename(__DIR__) . '/divi-supercharger.php';

				if ($plugin == $plugin_file) {

					$site_link = array('support' => '<a href="' . $this->ds_edivi_plugin_options['plugin_uri'] . '" target="_blank">' . __('Support', $this->ds_edivi_plugin_options['plugin_domain']) . '</a>');
					$actions = array_merge($site_link, $actions);

					if ( is_plugin_active( 'divi/wp-divi.php' ) ) {
						$settings = array('settings' => '<a href="admin.php?page=' . $this->ds_edivi_plugin_options['page_slug'] . '">' . __('Settings', $this->ds_edivi_plugin_options['plugin_domain']) . '</a>');
						$actions = array_merge($settings, $actions);
					} //end if is_plugin_active

				}
			return $actions;

	} // end function ds_ucfml_add_action_plugin

} // end class Enhanced_Divi_Settings
if( is_admin() )
    $ds_edivi_settings_page = new Enhanced_Divi_Settings();
