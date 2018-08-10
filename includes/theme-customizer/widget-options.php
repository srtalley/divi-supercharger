<?php
$font_choices = array();
$font_choices['none'] = array(
    'label' => 'Default Theme Font'
);
$site_domain = get_locale();
$google_fonts = et_builder_get_fonts( array(
    'prepend_standard_fonts' => false,
) );

foreach ( $google_fonts as $google_font_name => $google_font_properties ) {
    $use_parent_font = false;

    if ( isset( $removed_fonts_mapping[ $google_font_name ] ) ) {
        $parent_font = $removed_fonts_mapping[ $google_font_name ]['parent_font'];
        $google_font_properties['character_set'] = $google_fonts[ $parent_font ]['character_set'];
        $use_parent_font = true;
    }

    if ( '' !== $site_domain && isset( $et_domain_fonts[$site_domain] ) && isset( $google_font_properties['character_set'] ) && false === strpos( $google_font_properties['character_set'], $et_domain_fonts[$site_domain] ) ) {
        continue;
    }

    $font_choices[ $google_font_name ] = array(
        'label' => $google_font_name,
        'data'  => array(
            'parent_font'    => $use_parent_font ? $google_font_properties['parent_font'] : '',
            'parent_styles'  => $use_parent_font ? $google_fonts[$parent_font]['styles'] : $google_font_properties['styles'],
            'current_styles' => $use_parent_font && isset( $google_fonts[$parent_font]['styles'] ) && isset( $google_font_properties['styles'] ) ? $google_font_properties['styles'] : '',
            'parent_subset'  => $use_parent_font && isset( $google_fonts[$parent_font]['character_set'] ) ? $google_fonts[$parent_font]['character_set'] : '',
            'standard'       => isset( $google_font_properties['standard'] ) && $google_font_properties['standard'] ? 'on' : 'off',
        )
    );
}

$wp_customize->add_setting('ds_edivi_footer_widget_title_font', array(
    'default' => 'none',
    'description' => 'Change the font face for footer widget titles - font style and font size are in the standard Divi settings section.',
    'type' => $option_type,
    'capability'	=> 'edit_theme_options',
    'transport'		=> 'postMessage',
    'sanitize_callback' => 'et_sanitize_font_choices',
));
$wp_customize->add_control( new ET_Divi_Select_Option ( $wp_customize, 'ds_edivi_footer_widget_title_font', array(
    'label'		=> esc_html__( 'footer Widget Header Font', 'Divi' ),
    'section'	=> 'ds_edivi_widgets_section',
    'settings'	=> 'ds_edivi_footer_widget_title_font',
    'type'		=> 'select',
    'choices'	=> $font_choices
) ) );

$wp_customize->add_setting( 'ds_edivi_sidebar_widget_title_font_size', array(
    'default'       => '18',
    'type' => $option_type,
    'capability'    => 'edit_theme_options',
    'transport'     => 'postMessage',
    'sanitize_callback' => 'absint',
) );

$wp_customize->add_control( new ET_Divi_Range_Option ( $wp_customize, 'ds_edivi_sidebar_widget_title_font_size', array(
    'label'	      => esc_html__( 'Sidebar Widget Header Font Size', 'Divi' ),
    'section'     => 'ds_edivi_widgets_section',
    'type'        => 'range',
    'input_attrs' => array(
    'min'  => 12,
    'max'  => 40,
    'step' => 1
    ),
) ) );
$wp_customize->add_setting('ds_edivi_sidebar_widget_title_font', array(
    'default' => 'none',
    'type' => $option_type,
    'capability'	=> 'edit_theme_options',
    'transport'		=> 'postMessage',
    'sanitize_callback' => 'et_sanitize_font_choices',
));
    $wp_customize->add_control( new ET_Divi_Select_Option ( $wp_customize, 'ds_edivi_sidebar_widget_title_font', array(
        'label'		=> esc_html__( 'Sidebar Widget Header Font', 'Divi' ),
        'section'	=> 'ds_edivi_widgets_section',
        'settings'	=> 'ds_edivi_sidebar_widget_title_font',
        'type'		=> 'select',
        'choices'	=> $font_choices
    ) ) );

$wp_customize->add_setting( 'ds_edivi_sidebar_widget_title_font_style', array(
    'default'       => '',
    'type' => $option_type,
    'capability'    => 'edit_theme_options',
    'transport'     => 'postMessage',
    'sanitize_callback' => 'et_sanitize_font_style',
) );

$wp_customize->add_control( new ET_Divi_Font_Style_Option ( $wp_customize, 'ds_edivi_sidebar_widget_title_font_style', array(
    'label'	      => esc_html__( 'Sidebar Widget Header Font Style', 'Divi' ),
    'section'     => 'ds_edivi_widgets_section',
    'type'        => 'font_style',
    'choices'     => et_divi_font_style_choices(),
) ) );