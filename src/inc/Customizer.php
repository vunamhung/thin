<?php

namespace thin;

use Kirki;

class Customizer {
	public function __construct() {
		$this->boot();
	}

	public function boot() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_filter( 'kirki_config', [ $this, 'url_path' ] );
		add_action( 'widgets_init', [ $this, 'add_config' ], 99 );
		add_action( 'widgets_init', [ $this, 'fields' ], 99 );
	}

	public function customize_register( \WP_Customize_Manager $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial(
				'blogname',
				[
					'selector'        => '.site-title a',
					'render_callback' => function () {
						bloginfo( 'name' );
					},
				]
			);
			$wp_customize->selective_refresh->add_partial(
				'blogdescription',
				[
					'selector'        => '.site-description',
					'render_callback' => function () {
						bloginfo( 'description' );
					},
				]
			);
		}
	}

	public function url_path( $config ) {
		$config['url_path'] = get_theme_file_uri( 'vendor/aristath/kirki/' );

		return $config;
	}

	public function add_config() {
		Kirki::add_config(
			THEME_SLUG,
			[
				'option_type' => 'theme_mod',
				'capability'  => 'edit_theme_options',
			]
		);
	}

	public function fields() {
		Kirki::add_field(
			THEME_SLUG,
			[
				'type'        => 'color',
				'settings'    => 'color_setting_hex',
				'label'       => esc_html__( 'Color Control (hex-only)', 'vnh_textdomain' ),
				'description' => esc_html__( 'This is a color control - without alpha channel.', 'vnh_textdomain' ),
				'section'     => 'colors',
				'default'     => '#0088CC',
				'transport'   => 'postMessage',
			]
		);
	}
}
