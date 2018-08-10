<?php
// Blog Appearance
$wp_customize->add_setting('ds_edivi_blog_appearance', array(
    'default' => 'no_change',
    'type' => $option_type,
));

$wp_customize->add_control('ds_edivi_blog_appearance', array(
    'label' => __('Blog Appearance', 'ds_edivi'),
    'description' => 'Change the layout of the default Divi Blog Module',
    'section' => 'ds_edivi_blog_section',
    'type' => 'radio',
    'priority' => 10,
    'settings' => 'ds_edivi_blog_appearance',
    'choices' => array(
    'no_change' => 'Default Layout',
    'pic_left' => 'Smaller Picture on Left',
    ),
));