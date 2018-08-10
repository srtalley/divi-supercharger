<?php 

// Should we change the default link hover
$wp_customize->add_setting('ds_edivi_href_hover_color_status', array(
  'default' => 'href_hover_default',
  'type' => $option_type,
));
$wp_customize->add_control('ds_edivi_href_hover_color_status', array(
  'label'		=> esc_html__( 'Use the default Divi link/href hover effect or choose your own color below', 'Divi' ),
  'section' => 'ds_edivi_link_hover_options',
  'type' => 'radio',
  'priority' => 2,
  'settings' => 'ds_edivi_href_hover_color_status',
  'choices' => array(
    'href_hover_default' => 'Use Divi default',
    'href_hover_selected' => 'Use selected color below',
  ),
));
//General Link Hover Color
$wp_customize->add_setting( 'ds_edivi_href_hover_color', array(
  'default'		=> et_get_option( 'accent_color', '#000' ),
  'transport' => 'postMessage',
  'sanitize_callback' => 'et_sanitize_alpha_color',
  'type' => $option_type,
) );

$wp_customize->add_control( new ET_Divi_Customize_Color_Alpha_Control( $wp_customize, 'ds_edivi_href_hover_color', array(
  'label'		=> esc_html__( 'Body Link Hover Color', 'Divi' ),
  'section'	=> 'ds_edivi_link_hover_options',
  'priority' => 5,
  'settings'	=> 'ds_edivi_href_hover_color',
) ) );

//Footer Link Hover Color
$wp_customize->add_setting( 'ds_edivi_footer_href_hover_color', array(
  'default'		=> et_get_option( 'bottom_bar_text_color', '#666666' ),
  'transport' => 'postMessage',
  'sanitize_callback' => 'et_sanitize_alpha_color',
  'type' => $option_type,
) );

$wp_customize->add_control( new ET_Divi_Customize_Color_Alpha_Control( $wp_customize, 'ds_edivi_footer_href_hover_color', array(
  'label'		=> esc_html__( 'Footer Link Hover Color', 'Divi' ),
  'section'	=> 'ds_edivi_link_hover_options',
  'priority' => 10,
  'settings'	=> 'ds_edivi_footer_href_hover_color',
)));

// Image link hover appearance
$wp_customize->add_setting('ds_edivi_image_link_hover', array(
  'default' => 'no_change',
  'type' => $option_type,
));

$wp_customize->add_control('ds_edivi_image_link_hover', array(
  'label' => __('Linked Image Hover Effect', 'ds_edivi'),
  'description' => 'Change or add stylized effects when hovering over image links',
  'section' => 'ds_edivi_link_hover_options',
  'type' => 'radio',
  'priority' => 20,
  'settings' => 'ds_edivi_image_link_hover',
  'choices' => array(
    'no_change' => 'Default',
    'white_border' => 'Inset white border highlight on hover',
  ),
));