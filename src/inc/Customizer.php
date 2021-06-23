<?php

namespace thin;

class Customizer {
	public function __construct() {
		$this->boot();
	}

	public function boot() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_action( 'customize_preview_init', [ $this, 'customize_preview_js' ] );
	}

	public function customize_register( $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial(
				'blogname',
				[
					'selector'        => '.site-title a',
					'render_callback' => [ $this, 'customize_partial_blog_name' ],
				]
			);
			$wp_customize->selective_refresh->add_partial(
				'blogdescription',
				[
					'selector'        => '.site-description',
					'render_callback' => [ $this, 'customize_partial_blog_description' ],
				]
			);
		}
	}

	public function customize_partial_blog_name() {
		bloginfo( 'name' );
	}

	public function customize_partial_blog_description() {
		bloginfo( 'description' );
	}

	public function customize_preview_js() {
		wp_enqueue_script( 'thin-customizer', get_template_directory_uri() . '/js/customizer.js', [ 'customize-preview' ], THEME_VERSION, true );
	}

}
