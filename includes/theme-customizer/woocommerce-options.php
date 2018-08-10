<?php

$wp_customize->add_setting('ds_edivi_woocommerce_sale_color_status', array(
    'default' => 'sale_color_off',
    'type' => $option_type,
));

$wp_customize->add_control('ds_edivi_woocommerce_sale_color_status', array(
    'label' => __('Change the default WooCommerce sale color with the one below', 'ds_edivi'),
    'section' => 'ds_edivi_woocommerce_section',
    'type' => 'radio',
    'priority' => 10,
    'settings' => 'ds_edivi_woocommerce_sale_color_status',
    'choices' => array(
    'sale_color_off' => 'Use WooCommerce default sale color',
    'sale_color_on' => 'Use color below',
    ),
));

// Sale Items Color
$wp_customize->add_setting('ds_edivi_woocommerce_sale_color', array(
    'default' => et_get_option( 'accent_color', '#ef8f61' ),
    'type' => $option_type,
));
$wp_customize->add_control( new ET_Divi_Customize_Color_Alpha_Control( $wp_customize, 'ds_edivi_woocommerce_sale_color', array(
    'label' => __('Sale Items Color', 'ds_edivi'),
    'section' => 'ds_edivi_woocommerce_section',
    'priority' => 11,
    'settings' => 'ds_edivi_woocommerce_sale_color'
    )
));