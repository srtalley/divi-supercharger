<?php
/**
* Contains methods for customizing the theme customization screen.
*
* @link http://codex.wordpress.org/Theme_Customization_API
* @since DUSTY SUN SUPERCHARGER PLUGIN 1.0
*/
class DSEDIVI_Theme_Customizer {

  private $customizer_css;
  private $customizer_js;

  public function __construct() {
    // Setup the Theme Customizer settings and controls...
    add_action( 'customize_register' , array($this, 'ds_edivi_register' ) );

    // Output custom CSS to live site
    add_action( 'wp_head' , array($this, 'ds_edivi_header_output' ) );

    // Enqueue live preview javascript in Theme Customizer admin screen
    add_action( 'customize_preview_init' , array($this, 'ds_edivi_live_preview' ) );

    // Add a class to the body tag for the supercharger classes
    add_filter('body_class', array($this, 'ds_edivi_body_class'));

  } // end function construct
  /**
  * This hooks into 'customize_register' (available as of WP 3.4) and allows
  * you to add new sections and controls to the Theme Customize screen.
  *
  * Note: To enable instant preview, we have to actually write a bit of custom
  * javascript. See live_preview() for more.
  *
  * @see add_action('customize_register',$func)
  * @param \WP_Customize_Manager $wp_customize
  * @link http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
  */

  public static function get_option_storage_type() {
    // see how we should store data
    $ds_edivi_option_storage = get_option('ds_edivi_general_settings_options', true)['options_or_theme_mod'];
    if($ds_edivi_option_storage == null || $ds_edivi_option_storage == '') { $ds_edivi_option_storage = 'default';}

    $ds_edivi_option = array();
    $ds_edivi_option['type'] = 'theme_mod';
    $ds_edivi_option['function'] = 'get_theme_mod';
    if($ds_edivi_option_storage == 'global'){
      $ds_edivi_option['type'] = 'option';
      $ds_edivi_option['function']  = 'get_option';
    } //end if($option_storage == 'global')

    return $ds_edivi_option;
  } //end function get_option_storage_type()

  public static function ds_edivi_register ( $wp_customize ) {

    $option_type = self::get_option_storage_type()['type'];

    // ========================================================== 
    //    MAIN PANEL
    // ========================================================== 

    $wp_customize->add_panel( 'ds_edivi_customizations_option', array(
      'priority' => 1,
      'capability' => 'edit_theme_options',
      'title' => __('Divi SUPERCHARGER Options', 'ds_edivi'),
    ));

    // ========================================================== 
    //  BLOG PANEL   //
    // ========================================================== 
    // add section to panel
    $wp_customize->add_section('ds_edivi_blog_section', array(
      'priority' => 5,
      'title' => __('Blog Options', 'ds_edivi'),
      'panel' => 'ds_edivi_customizations_option',
      'description' => __('If you want to change the appearance of all Divi blog modules, use the settings below.', 'ds_edivi'),
    ));

    include_once(dirname( __FILE__ ) . '/theme-customizer/blog-options.php');

    // ========================================================== 
    //  Footer Options
    // ========================================================== 

    $wp_customize->add_section('ds_edivi_footer_options', array(
      'priority' => 15,
      'title' => __('Footer Options', 'ds_edivi'),
      'panel' => 'ds_edivi_customizations_option',
    ));
    
    include_once(dirname( __FILE__ ) . '/theme-customizer/footer-options.php');

    // ========================================================== 
    //  Form Elements //
    // ========================================================== 

    $wp_customize->add_section('ds_edivi_form_options', array(
      'priority' => 20,
      'title' => __('Form Element Options', 'ds_edivi'),
      'panel' => 'ds_edivi_customizations_option',
    ));
    
    include_once(dirname( __FILE__ ) . '/theme-customizer/form-elements.php');

    // ========================================================== */
    //  Link Hover Colors & Image Effects
    // ========================================================== */

    $wp_customize->add_section('ds_edivi_link_hover_options', array(
      'priority' => 30,
      'title' => __('Link Hover Colors & Image Effects', 'ds_edivi'),
      'panel' => 'ds_edivi_customizations_option',
    ));

    include_once(dirname( __FILE__ ) . '/theme-customizer/link-hover.php');

    // ========================================================== 
    //  Menu Options 
    // ========================================================== 

    $wp_customize->add_section('ds_edivi_menu_options', array(
      'priority' => 50,
      'title' => __('Menu Options', 'ds_edivi'),
      'panel' => 'ds_edivi_customizations_option',
    ));
   
    include_once(dirname( __FILE__ ) . '/theme-customizer/menu-options.php');

    // ========================================================== 
    //  SLIDERS
    // ========================================================== 
    // add section to panel
    $wp_customize->add_section('ds_edivi_sliders_section', array(
      'priority' => 60,
      'title' => __('Slider Options', 'ds_edivi'),
      'panel' => 'ds_edivi_customizations_option',
      'description' => __('Set defaults for sliders', 'ds_edivi'),
    ));
    include_once(dirname( __FILE__ ) . '/theme-customizer/slider-options.php');

    // ========================================================== 
    //  WIDGETS PANEL
    // ========================================================== 
    // add section to panel
    $wp_customize->add_section('ds_edivi_widgets_section', array(
      'priority' => 85,
      'title' => __('Widget Options', 'ds_edivi'),
      'panel' => 'ds_edivi_customizations_option',
      'description' => __('Change the font options for widget titles. Note that the font size and font style for footer widgets are controlled in the standard Divi > Footer section of the theme customizer. However, you can set the font title here for footer widgets.', 'ds_edivi'),
    ));
    include_once(dirname( __FILE__ ) . '/theme-customizer/widget-options.php');

    // ========================================================== 
    //  WOOCOMMERCE PANEL 
    // ========================================================== 
    $wp_customize->add_section('ds_edivi_woocommerce_section', array(
      'priority' => 95,
      'title' => __('WooCommerce Options', 'ds_edivi'),
      'panel' => 'ds_edivi_customizations_option',
      'description' => __('Additional options for WooCommerce within the Divi theme.', 'ds_edivi'),
    ));
    include_once(dirname( __FILE__ ) . '/theme-customizer/woocommerce-options.php');

  } //end public static function ds_edivi_register

  /**
  * This will output the custom WordPress settings to the live theme's WP head.
  *
  * Used by hook: 'wp_head'
  *
  * @see add_action('wp_head',$func)
  * @since DustySunTheme 1.0
  */
  public function ds_edivi_header_output() {
    $option_function = self::get_option_storage_type()['function'];
    
    // =================================================
    // BLOG APPEARANCE
    // Smaller picture on left
    // ================================================= 
    $ds_edivi_blog_appearance = call_user_func_array($option_function, array('ds_edivi_blog_appearance', ''));

    if($ds_edivi_blog_appearance == 'pic_left'): 
      $this->customizer_css .= $this->get_script_file(dirname( __FILE__ ) . '/css/blog-left.css');

      $this->customizer_js .= $this->get_script_file(dirname( __FILE__ ) . '/js/blog-left.js', true, true);

    endif; 


    //=================================================
    //  FOOTER OPTIONS
    // =================================================
    $ds_edivi_footer_background_status = call_user_func_array($option_function, array('ds_edivi_footer_background_status', ''));
    if($ds_edivi_footer_background_status == 'background_img') :
      $this->customizer_css .= self::generate_css('#main-footer', 'background-image', 'ds_edivi_footer_background_img', 'url(\'', '\')', false);
      $this->customizer_css .= '#main-footer { background-repeat: no-repeat; background-position: center center; background-size: cover; }';
    endif;
    
    //=================================================
    //  STYLE ADDITIONAL FORM ELEMENTS
    // =================================================
    $ds_edivi_style_form_elements = call_user_func_array($option_function, array('ds_edivi_style_form_elements', ''));
    if($ds_edivi_style_form_elements != 'style_form_off'):

      // get some of the divi options to apply default styles to buttons
      $accent_color = et_get_option( 'accent_color', '#2ea3f2' );
      $button_text_size = absint( et_get_option( 'all_buttons_font_size', '20' ) );
      $button_text_color = et_get_option( 'all_buttons_text_color', '#ffffff' );
      $button_bg_color = et_get_option( 'all_buttons_bg_color', 'rgba(0,0,0,0)' );
      $button_border_width = absint( et_get_option( 'all_buttons_border_width', '2' ) );
      $button_border_color = et_get_option( 'all_buttons_border_color', '#ffffff' );
      $button_border_radius = absint( et_get_option( 'all_buttons_border_radius', '3' ) );
      $button_text_style = et_get_option( 'all_buttons_font_style', '', '', true );
      $button_icon = et_get_option( 'all_buttons_selected_icon', '5' );
      $button_spacing = intval( et_get_option( 'all_buttons_spacing', '0' ) );
      $button_icon_color = et_get_option( 'all_buttons_icon_color', '#ffffff' );
      $button_text_color_hover = et_get_option( 'all_buttons_text_color_hover', '#ffffff' );
      $button_bg_color_hover = et_get_option( 'all_buttons_bg_color_hover', 'rgba(255,255,255,0.2)' );
      $button_border_color_hover = et_get_option( 'all_buttons_border_color_hover', 'rgba(0,0,0,0)' );
      $button_border_radius_hover = absint( et_get_option( 'all_buttons_border_radius_hover', '3' ) );
      $button_spacing_hover = intval( et_get_option( 'all_buttons_spacing_hover', '0' ) );
      $button_icon_size = 1.6 * intval( $button_text_size );

      $this->customizer_css .= $this->get_script_file(dirname( __FILE__ ) . '/css/form-element-styles.css');

      // buttons
      $this->customizer_css .= 'body.divi-supercharger input[type="submit"], button{';
        $this->customizer_css .= 'color:' . esc_html( $accent_color ) . ';';
        $this->customizer_css .= 'color:' . esc_html( $button_text_color ) .';';
        if ( 20 !== $button_text_size ) { $this->customizer_css .= 'font-size:' . esc_html( $button_text_size ). 'px;'; } 
        if ( 'rgba(0,0,0,0)' !== $button_bg_color ) { $this->customizer_css .= 'background:' .  esc_html( $button_bg_color ) . ';'; } 
        if ( 2 !== $button_border_width ) { $this->customizer_css .= 'border-width:' . esc_html( $button_border_width ) . 'px !important;';  } 
        if ( '#ffffff' !== $button_border_color ) { $this->customizer_css .= 'border-color:' . esc_html( $button_border_color ) . ';'; } 
        if ( 3 !== $button_border_radius ) { $this->customizer_css .= 'border-radius:' . esc_html( $button_border_radius ) . 'px;'; } 
        if ( '' !== $button_text_style ) { $this->customizer_css .= esc_html( et_pb_print_font_style( $button_text_style ) ) . ';';  }
        if ( 0 !== $button_spacing ) { $this->customizer_css .=  'letter-spacing:' . esc_html( $button_spacing ); 'px;'; } 
      $this->customizer_css .= '}';
      
      // buttons
      $this->customizer_css .= 'body.divi-supercharger input[type="submit"]:hover {';
        if ( '#ffffff' !== $button_text_color_hover ) {  'color:' . esc_html( $button_text_color_hover ) . '!important;'; }
        if ( 'rgba(255,255,255,0.2)' !== $button_bg_color_hover ) {  'background:' . esc_html( $button_bg_color_hover ) . '!important;'; }
        if ( 'rgba(0,0,0,0)' !== $button_border_color_hover ) {  'border-color:' . esc_html( $button_border_color_hover ) . '!important;'; }
        if ( 3 !== $button_border_radius_hover ) {  'border-radius:' . esc_html( $button_border_radius_hover ) . 'px;'; }
        if ( 0 !== $button_spacing_hover ) { 'letter-spacing:' . esc_html( $button_spacing_hover ) . 'px;'; }
      $this->customizer_css .= '}';
      
      // inputs
      $this->customizer_css .= 'body.divi-supercharger input.text:focus, input.title:focus, input[type=text]:focus, select:focus, textarea:focus {';
        $this->customizer_css .= 'border-color:' . $accent_color . '!important;';
      $this->customizer_css .= '}';

      // woocommerce
      $this->customizer_css .= 'body.divi-supercharger .woocommerce-product-search input[type="search"]:focus,';
      $this->customizer_css .= 'body.divi-supercharger .woocommerce-product-search input[type="search"]:active {';
        $this->customizer_css .= 'border-color:' . $accent_color . '!important;';
      $this->customizer_css .= '}';
    endif;
        
    //=================================================
    //  GLOBAL HREF HOVER COLOR 
    // =================================================
    $ds_edivi_href_hover_color_status = call_user_func_array($option_function, array('ds_edivi_href_hover_color_status', ''));
    $ds_edivi_general_href_hover_color = call_user_func_array($option_function, array('ds_edivi_href_hover_color', ''));
    if($ds_edivi_href_hover_color_status == 'href_hover_selected') :
      $this->customizer_css .= self::generate_css('a:hover', 'color', 'ds_edivi_href_hover_color', '', '', false);

      $this->customizer_css .= 'body.divi-supercharger a:hover {';
        $this->customizer_css .= 'color: ' . $ds_edivi_href_hover_color . ';';
       $this->customizer_css .= 'opacity: 1;';
      $this->customizer_css .= '}';
    endif;

    //=================================================
    //  FOOTER HOVER COLOR 
    // =================================================
    $ds_edivi_footer_href_hover_color = call_user_func_array($option_function, array('ds_edivi_footer_href_hover_color', ''));
    if($ds_edivi_footer_href_hover_color != et_get_option( 'bottom_bar_text_color' )) :
      $this->customizer_css .= 'body.divi-supercharger #footer-info a:hover {';
        $this->customizer_css .= 'color: ' . $ds_edivi_footer_href_hover_color . ';';
       $this->customizer_css .= 'opacity: 1;';
      $this->customizer_css .= '}';
    endif;

    //=================================================
    //  IMAGE HOVER HIGHLIGHT                           
    // =================================================

    $image_link_hover = call_user_func_array($option_function, array('ds_edivi_image_link_hover', ''));
    if($image_link_hover == 'white_border'): 
      $this->customizer_css .= $this->get_script_file(dirname( __FILE__ ) . '/css/image-hover-white-border.css');
    endif; 

    //=================================================
    //  MENU HIGHLIGHTING WITH CHILDREN
    // =================================================
    $entire_menu_highlight_status = call_user_func_array($option_function, array('ds_edivi_entire_menu_highlight_status', ''));
    if($entire_menu_highlight_status == 'highlight_on'):

      $menu_height = absint( et_get_option( 'menu_height', '66' ) );
      $fixed_menu_height = absint( et_get_option( 'minimized_menu_height', '40' ) );
      $primary_nav_bg = et_get_option( 'primary_nav_bg', '#ffffff' );
      $primary_nav_dropdown_bg = et_get_option( 'primary_nav_dropdown_bg', $primary_nav_bg );
      $primary_nav_dropdown_link_color = et_get_option( 'primary_nav_dropdown_link_color', '#FFF' );

      $this->customizer_css .= $this->get_script_file(dirname( __FILE__ ) . '/css/menu-primary-parent-highlight.css');
      $this->customizer_css .= '@media only screen and (min-width: 981px) {';

        $this->customizer_css .= 'body.divi-supercharger #top-menu-nav #top-menu > li {';
          $this->customizer_css .= 'padding-top:' . esc_html( round ( $menu_height / 2 ) ) . 'px;';
        $this->customizer_css .= '}';

        $this->customizer_css .= 'body.divi-supercharger .et-fixed-header #top-menu-nav #top-menu > li {';
          $this->customizer_css .= 'padding-top:' . esc_html( round ( $fixed_menu_height / 2 ) ) . 'px;';
        $this->customizer_css .= '}';

        $this->customizer_css .= 'body.divi-supercharger #et_top_search {';
          $this->customizer_css .= 'margin-top:' . esc_html( round ( $menu_height / 2 ) ) . 'px;';
        $this->customizer_css .= '}';

        $this->customizer_css .= 'body.divi-supercharger #top-menu-nav #top-menu > li.menu-item-has-children:hover {';
          $this->customizer_css .= 'background-color:' . esc_html( $primary_nav_dropdown_bg );
        $this->customizer_css .= '}';
          
        $this->customizer_css .= 'body.divi-supercharger #top-menu-nav #top-menu > li.menu-item-has-children:hover a {';
          $this->customizer_css .= 'color:' . esc_html( $primary_nav_dropdown_link_color );
        $this->customizer_css .= '}';

      $this->customizer_css .= '} /* end media min width 981 px */';
    endif; //if($entire_menu_highlight_status == 'highlight_on') 

    //=================================================
    //  SUBMENU AUTO WIDTH 
    // =================================================
    $submenu_auto_width = call_user_func_array($option_function, array('ds_edivi_submenu_auto_width', ''));
    if($submenu_auto_width == 'autowidth_on'):
      $this->customizer_css .= $this->get_script_file(dirname( __FILE__ ) . '/css/menu-primary-submenu-autowidth.css');
    endif; 

    //=================================================
    //  AUTO RESIZE HEADER 
    // =================================================
    $ds_edivi_resize_header = call_user_func_array($option_function, array('ds_edivi_resize_header', ''));

    if($ds_edivi_resize_header == 'resize_on'): 
      $this->customizer_js .= $this->get_script_file(dirname( __FILE__ ) . '/js/menu-resize-header.js', true, true);
      $this->customizer_css .= 'body.divi-supercharger #page-container { transition: all 0.4s ease-in-out; }';
      $this->customizer_css .= '#et_top_search { margin-right: 10px; margin-left: 10px; position: absolute;right: 10px; }';
    endif; 

    //=================================================
    //  SUBMENU BACKGROUND HOVER COLOR
    // =================================================
    $ds_edivi_submenu_background_hover_color_status = call_user_func_array($option_function, array('ds_edivi_submenu_background_hover_color_status', ''));
    if($ds_edivi_submenu_background_hover_color_status == 'submenu_hover_selected') :
      $this->customizer_css .= self::generate_css('.et_mobile_menu li a:hover, .nav ul li a:hover', 'background-color', 'ds_edivi_submenu_background_hover_color', '', '', false);
      $this->customizer_css .= self::generate_css('.et_mobile_menu li a:hover, .nav ul li a:hover', 'opacity', '1', '', '', false);
    endif;
    //=================================================
    //  SLIDER DOTS
    // =================================================
    $ds_edivi_slider_dot_color_status = call_user_func_array($option_function, array('ds_edivi_slider_dot_color_status', ''));
    if($ds_edivi_slider_dot_color_status == 'slider_dots_selected') :
      $this->customizer_css .= self::generate_css('.et-pb-controllers a', 'background-color', 'ds_edivi_slider_dot_inactive_color', '', '', false);
      $this->customizer_css .= self::generate_css('.et-pb-controllers a.et-pb-active-control', 'background-color', 'ds_edivi_slider_dot_active_color', '', '!important', false);
    endif;

    
    //==================================================
    //  SIDEBAR WIDGET FONTS
    // =================================================
    $sidebar_widget_title_font_face = call_user_func_array($option_function, array('ds_edivi_sidebar_widget_title_font', ''));

    $sidebar_widget_title_font_face = sanitize_text_field( et_pb_get_specific_default_font( $sidebar_widget_title_font_face ) );

    $sidebar_widget_title_font_size = call_user_func_array($option_function, array('ds_edivi_sidebar_widget_title_font_size', ''));
    $sidebar_widget_title_font_style = call_user_func_array($option_function, array('ds_edivi_sidebar_widget_title_font_style', ''));
    if(isset($sidebar_widget_title_font_face) && $sidebar_widget_title_font_face != '' && $sidebar_widget_title_font_face != 'none'): 
      $this->customizer_css .= '.widgettitle { ';
      $this->customizer_css .= sanitize_text_field( et_builder_get_font_family( $sidebar_widget_title_font_face ) );
      et_builder_enqueue_font($sidebar_widget_title_font_face);
        
      if(isset($sidebar_widget_title_font_size)) $this->customizer_css .= 'font-size: ' . $sidebar_widget_title_font_size . 'px';

      if(isset($sidebar_widget_title_font_style)) $this->customizer_css .= esc_html( et_pb_print_font_style( $sidebar_widget_title_font_style ) );
      $this->customizer_css .= ' }';
  
    endif; 

    //=================================================
    //  FOOTER WIDGET FONTS
    // =================================================
    $footer_widget_title_font_face = call_user_func_array($option_function, array('ds_edivi_footer_widget_title_font', ''));

    $footer_widget_title_font_face = sanitize_text_field( et_pb_get_specific_default_font( $footer_widget_title_font_face ) );

    if(isset($footer_widget_title_font_face) && $footer_widget_title_font_face != '' && $footer_widget_title_font_face != 'none'): 
      $this->customizer_css .= '#main-footer #footer-widgets h4.title {';
      $this->customizer_css .= sanitize_text_field( et_builder_get_font_family( $footer_widget_title_font_face ) ); 
        et_builder_enqueue_font($footer_widget_title_font_face);
        $this->customizer_css .= '}';
    endif; 

    //=================================================
    //  WOOCOMMERCE SETTINGS
    // =================================================
    $ds_edivi_woocommerce_sale_color_status = call_user_func_array($option_function, array('ds_edivi_woocommerce_sale_color_status', ''));
    if($ds_edivi_woocommerce_sale_color_status == 'sale_color_on') :
      $this->customizer_css .= '/* shizer */';
      $this->customizer_css .= self::generate_css('.woocommerce span.onsale, .woocommerce-page span.onsale', 'background', 'ds_edivi_woocommerce_sale_color', '', '', false);
    endif;

    // close the customizer css and send the css and any js to the head
    $this->customizer_css = '<style type="text/css">' . $this->customizer_css . '</style><!--/Customizer CSS-->';

    echo $this->customizer_css;
    echo $this->customizer_js;
  } // end function ds_edivi_header_output

  /**
  * This outputs the javascript needed to automate the live settings preview.
  * Also keep in mind that this function isn't necessary unless your settings
  * are using 'transport'=>'postMessage' instead of the default 'transport'
  * => 'refresh'
  *
  * Used by hook: 'customize_preview_init'
  *
  * @see add_action('customize_preview_init',$func)
  * @since Divi SUPERCHARGER 1.0
  */
  public static function ds_edivi_live_preview() {
    wp_enqueue_script( 'ds-edivi-admin', plugins_url( '../js/theme-customizer.js', __FILE__ ), array(), false, true );
  }
  /**
  * Reads in a file and minifies it, and returns it to the calling function
  *
  * @since Divi SUPERCHARGER 1.0
  */
  public function get_script_file($file_path, $minify = true, $script_wrapper = false) {
    if(file_exists($file_path)) {
      try {

        $script_prefix = '';
        $script_appendix = '';

        if($script_wrapper){
          // get the file type 
          $file_extension = pathinfo($file_path, PATHINFO_EXTENSION);
          if($file_extension == 'css') {
            $script_prefix = '<style type="text/css">';
            $script_appendix = '</style>';
          } else if($file_extension == 'js') {
            $script_prefix = '<script type="text/javascript">';
            $script_appendix = '</script>';
          } // end if file_extension
        } // end if script_wrapper

        $file_contents = "\r\n" . $script_prefix . "\r\n" . file_get_contents($file_path) . "\r\n" . $script_appendix . "\r\n";
        if($minify) {
          $pattern = '/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\')\/\/.*))/';
          $file_contents = str_replace(array("\r", "\n"), ' ', preg_replace($pattern, '', $file_contents));
        } // end if minify
        
        return $file_contents;
      } catch (Exception $e){
          $this->wl($e->getMessage());
      } // end try// end try
    } else {
      $this->wl('Passed file does not exist: ' . $file_path);
    }// end if
  } // end static function minify_file


  /**
  * Add a body tag for our customizer CSS to use to override anything else.
  *
  * Used by hook: 'customize_preview_init'
  *
  * @see add_action('customize_preview_init',$func)
  * @since Divi SUPERCHARGER 1.0
  */
  public static function ds_edivi_body_class($classes) {
    $classes[] = 'divi-supercharger';
    return $classes;
  }

  /**
  * Logging function to write to the WordPress debug.log
  *
  * @since Divi SUPERCHARGER 1.0
  */

  public static function wl ( $log )  {
		if ( true === WP_DEBUG ) {
				if ( is_array( $log ) || is_object( $log ) ) {
						error_log( print_r( $log, true ) );
				} else {
						error_log( $log );
				}
			}
  } // end write_log
  
  /**
  * This will generate a line of CSS for use in header output. If the setting
  * ($mod_name) has no defined value, the CSS will not be output.
  *
  * @uses get_theme_mod() or get_option
  * @param string $selector CSS selector
  * @param string $style The name of the CSS *property* to modify
  * @param string $mod_name The name of the 'theme_mod' option to fetch
  * @param string $prefix Optional. Anything that needs to be output before the CSS property
  * @param string $suffix Optional. Anything that needs to be output after the CSS property
  * @param bool $echo Optional. Whether to print directly to the page (default: true).
  * @return string Returns a single line of CSS with selectors and a property.
  * @since Divi SUPERCHARGER 1.0
  */
  public static function generate_css( $selector, $style, $mod_name, $prefix='', $suffix='', $echo=true ) {
    $return = '';

    $option_function = self::get_option_storage_type()['function'];

    if($option_function == 'get_theme_mod'){
      $mod = get_theme_mod($mod_name);
    } elseif ($option_function == 'get_option')  {
      $mod = get_option($mod_name);
    } //end if($option_function == 'get_theme_mod')


    // add our body class
    $selector = 'body.divi-supercharger ' . $selector;

    if ( ! empty( $mod ) ) {
      $return = sprintf('%s { %s:%s; }',
      $selector,
      $style,
      $prefix.$mod.$suffix
      );
      if ( $echo ) {
        echo $return;
      }
    }
  return $return;
  }
} // end class DSEDIVI_Theme_Customizer
$ds_edivi_theme_customizer = new DSEDIVI_Theme_Customizer();