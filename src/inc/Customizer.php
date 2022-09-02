<?php

namespace thin;

use Kirki;

class Customizer {
	public function __construct() {
		$this->boot();
	}

	public function boot() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
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

	public function fields() {
	}
}
