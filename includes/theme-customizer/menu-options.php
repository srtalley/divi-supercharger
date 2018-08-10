<?php
//  MENU HIGHLIGHTING WITH CHILD ITEMS //
// Allow entire menu highlight with children items on or off
$wp_customize->add_setting('ds_edivi_entire_menu_highlight_status', array(
    'default' => 'highlight_off',
    'type' => $option_type,
));

$wp_customize->add_control('ds_edivi_entire_menu_highlight_status', array(
    'label' => __('Highlight parent menu item when there is a child menu', 'ds_edivi'),
    'section' => 'ds_edivi_menu_options',
    'type' => 'radio',
    'priority' => 10,
    'settings' => 'ds_edivi_entire_menu_highlight_status',
    'choices' => array(
    'highlight_off' => 'Off',
    'highlight_on' => 'On',
    ),
));

// Allow submenus to have an auto width
$wp_customize->add_setting('ds_edivi_submenu_auto_width', array(
    'default' => 'autowidth_off',
    'type' => $option_type,
));

$wp_customize->add_control('ds_edivi_submenu_auto_width', array(
    'label' => __('Make submenus as wide as each entry item', 'ds_edivi'),
    'section' => 'ds_edivi_menu_options',
    'type' => 'radio',
    'priority' => 15,
    'settings' => 'ds_edivi_submenu_auto_width',
    'choices' => array(
    'autowidth_off' => 'Off',
    'autowidth_on' => 'On',
    ),
));

// Sets whether the submenus have a background hover color 
$wp_customize->add_setting('ds_edivi_submenu_background_hover_color_status', array(
    'default' => 'submenu_hover_default',
    'type' => $option_type,
));
$wp_customize->add_control('ds_edivi_submenu_background_hover_color_status', array(
    'label'		=> esc_html__( 'Use the default Divi submenu hover style or choose a background hover color below', 'ds_edivi' ),
    'section' => 'ds_edivi_menu_options',
    'type' => 'radio',
    'priority' => 24,
    'settings' => 'ds_edivi_submenu_background_hover_color_status',
    'choices' => array(
    'submenu_hover_default' => 'Use Divi default',
    'submenu_hover_selected' => 'Use selected color below',
    ),
));
// Submenu hover background color 
$wp_customize->add_setting('ds_edivi_submenu_background_hover_color', array(
    'default'		=> 'rgba(0,0,0,.3)',
    'transport' => 'postMessage',
    'sanitize_callback' => 'et_sanitize_alpha_color',
    'type' => $option_type,
));

$wp_customize->add_control( new ET_Divi_Customize_Color_Alpha_Control( $wp_customize, 'ds_edivi_submenu_background_hover_color', array(
    'label'		=> esc_html__( 'Submenu Hover Background Color', 'ds_edivi' ),
    'section'	=> 'ds_edivi_menu_options',
    'priority' => 25,
    'settings'	=> 'ds_edivi_submenu_background_hover_color',
) ) );

// Enable JavaScript to set page-container margin top 
// if the header wraps to two rows
$wp_customize->add_setting('ds_edivi_resize_header', array(
    'default' => 'resize_off',
    'type' => $option_type,
));

$wp_customize->add_control('ds_edivi_resize_header', array(
    'label' => __('Enable the page container to automatically shift down, instead of being covered, if the primary menu wraps to more than one line.', 'ds_edivi'),
    'section' => 'ds_edivi_menu_options',
    'type' => 'radio',
    'priority' => 10,
    'settings' => 'ds_edivi_resize_header',
    'choices' => array(
    'resize_off' => 'Off',
    'resize_on' => 'On',
    ),
));
