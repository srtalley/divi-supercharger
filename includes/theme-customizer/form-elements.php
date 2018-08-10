<?php 
//  Apply Divi styles to additional buttons and form inputs //
$wp_customize->add_setting('ds_edivi_style_form_elements', array(
    'default' => 'style_form_on',
    'type' => $option_type,
));
$wp_customize->add_control('ds_edivi_style_form_elements', array(
    'label' => __('Apply Divi styles to additional form buttons and inputs', 'ds_edivi'),
    'description' => "Applies the Divi button and form element styles you've chosen to items that Divi doesn't style by default.",
    'section' => 'ds_edivi_form_options',
    'type' => 'radio',
    'priority' => 10,
    'settings' => 'ds_edivi_style_form_elements',
    'choices' => array(
    'style_form_off' => 'Off',
    'style_form_on' => 'On',
    ),
));