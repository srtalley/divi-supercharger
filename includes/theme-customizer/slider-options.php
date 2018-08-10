<?php
// Sets whether sliders are modified or left default
$wp_customize->add_setting('ds_edivi_slider_dot_color_status', array(
    'default' => 'slider_dots_default',
    'type' => $option_type,
));
$wp_customize->add_control('ds_edivi_slider_dot_color_status', array(
    'label'		=> esc_html__( 'Use the default Divi slider navigation dot colors or choose your own colors below', 'Divi' ),
    'section' => 'ds_edivi_sliders_section',
    'type' => 'radio',
    'priority' => 2,
    'settings' => 'ds_edivi_slider_dot_color_status',
    'choices' => array(
    'slider_dots_default' => 'Use Divi default',
    'slider_dots_selected' => 'Use selected colors below',
    ),
));
//Active slider dot color
$wp_customize->add_setting( 'ds_edivi_slider_dot_active_color', array(
    'default'		=> et_get_option( 'accent_color', '#000' ),
    'transport' => 'postMessage',
    'sanitize_callback' => 'et_sanitize_alpha_color',
    'type' => $option_type,
) );

$wp_customize->add_control( new ET_Divi_Customize_Color_Alpha_Control( $wp_customize, 'ds_edivi_slider_dot_active_color', array(
    'label'		=> esc_html__( 'Active Slider Dot Color', 'Divi' ),
    'section'	=> 'ds_edivi_sliders_section',
    'priority' => 25,
    'settings'	=> 'ds_edivi_slider_dot_active_color',
) ) );

//Inactive slider dot color
$wp_customize->add_setting( 'ds_edivi_slider_dot_inactive_color', array(
    'default'		=> 'rgba(0,0,0,.3)',
    'transport' => 'postMessage',
    'sanitize_callback' => 'et_sanitize_alpha_color',
    'type' => $option_type,
) );

$wp_customize->add_control( new ET_Divi_Customize_Color_Alpha_Control( $wp_customize, 'ds_edivi_slider_dot_inactive_color', array(
    'label'		=> esc_html__( 'Inactive Slider Dot Color', 'Divi' ),
    'section'	=> 'ds_edivi_sliders_section',
    'priority' => 30,
    'settings'	=> 'ds_edivi_slider_dot_inactive_color',
) ) );
