<?php

namespace CityOfHelsinki\WordPress\CustomThemeColors;

/**
 * Main class
 */


class ThemeColors {
    private static $instance;

    private $allowed_blocks = array(
        'hds-wp/image-text',
    );

    public static function get_instance(){
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    public static function register(){
        add_action( 'helsinki_customizer_config', array( static::get_instance(), 'customizer_init'), 10 );
        add_filter('helsinki_customizer_choices_style_schemes', array( static::get_instance(), 'scheme') );
        add_filter( 'helsinki_colors', array( static::get_instance(), 'colors') );
        add_filter( 'admin_init', array( static::get_instance(), 'add_accent_background_to_hds_blocks') );
        add_action( 'wp_enqueue_scripts', array( static::get_instance(), 'inline_styles' ) );

    }

    public function customizer_init( $config ){
        $config['helsinki_general']['panel_sections']['style']['section_settings']['primary'] = helsinki_setting_select(
			__('Primary', 'helsinki-universal'),
			'',
			helsinki_customizer_choices_style_schemes(),
			''
		);
        $config['helsinki_general']['panel_sections']['style']['section_settings']['secondary'] = helsinki_setting_select(
			__('Secondary', 'helsinki-universal'),
			'',
			helsinki_customizer_choices_style_schemes(),
			''
		);
        $config['helsinki_general']['panel_sections']['style']['section_settings']['accent'] = helsinki_setting_select(
			__('Accent', 'helsinki-universal'),
			'',
			helsinki_customizer_choices_style_schemes(),
			''
		);
        return $config;
    }

    public function scheme( $scheme ){
        $scheme['custom'] = __('Custom', 'helfi-ctcol');
        return $scheme;
    }

    public function colors( $color_array ){
        $primary = helsinki_theme_mod('helsinki_general_style', 'primary');
        $secondary = helsinki_theme_mod('helsinki_general_style', 'secondary');
        $accent = helsinki_theme_mod('helsinki_general_style', 'accent');
        
        if(! array_key_exists( $primary, $color_array ) ){
            $primary = 'coat-of-arms';
        }

        if(! array_key_exists( $secondary, $color_array ) ){
            $secondary = 'coat-of-arms';
        }

        if(! array_key_exists( $accent, $color_array ) ){
            $accent = 'coat-of-arms';
        }

        $color_array['custom'] = array(
			'primary' => array(
				'color' => $color_array[$primary]['primary']['color'],
				'light' => $color_array[$primary]['primary']['light'],
				'medium' => $color_array[$primary]['primary']['color'],
				'dark' => $color_array[$primary]['primary']['medium'],
				'content' => $color_array[$primary]['primary']['content'],
				'content-secondary' => $color_array[$primary]['primary']['content-secondary'],
			),
			'secondary' => $color_array[$secondary]['primary']['color'],
			'accent' => $color_array[$accent]['primary']['color'],
		);

        return $color_array;
    }

    public function add_accent_background_to_hds_blocks(){
        foreach( $this->allowed_blocks as $block ){
            \register_block_style( $block , array(
                'name' => 'background-accent',
                'label' => __('Accent', 'txtdomain'),
            ) );
        }
    }

    public function inline_styles(){
        $secondary = helsinki_theme_mod('helsinki_general_style', 'secondary');
        $config = helsinki_colors( $secondary ); 
        $custom_css = sprintf('
        :root {
            --secondary-content-color: %s;
        }', $config['primary']["content"] );

        \wp_add_inline_style( 'helsinki-wp-styles', $custom_css );
    }
}