<?php

// Should we change the default link hover
$wp_customize->add_setting('ds_edivi_footer_background_status', array(
    'default' => 'divi_default',
    'type' => $option_type,
  ));
  $wp_customize->add_control('ds_edivi_footer_background_status', array(
    'label'		=> esc_html__( 'Do you wish to use a background image for the footer?', 'Divi' ),
    'section' => 'ds_edivi_footer_options',
    'type' => 'radio',
    'priority' => 2,
    'settings' => 'ds_edivi_footer_background_status',
    'choices' => array(
      'divi_default' => 'Use Divi default',
      'background_img' => 'Use selected background image below',
    ),
  ));
  
//Footer Background Image
$wp_customize->add_setting('ds_edivi_footer_background_img');

$wp_customize->add_control(
new WP_Customize_Image_Control( $wp_customize, 'ds_edivi_footer_background_img',
    array(
        'label' => __('Footer Background Image','ds_edivi_child_theme'),
        'section' => 'ds_edivi_footer_options',
        'settings' => 'ds_edivi_footer_background_img',
        'priority'   => 15
    )
)
);