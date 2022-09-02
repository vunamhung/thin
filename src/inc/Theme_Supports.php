<?php

namespace thin;

add_theme_support( 'thin_widget_areas', [
	'sidebar-1' => [
		'name'        => esc_html__( 'Sidebar', 'vnh_textdomain' ),
		'description' => esc_html__( 'Add widgets here.', 'vnh_textdomain' ),
	],
	'footer-1'  => [
		'name'        => esc_html__( 'Footer 1', 'vnh_textdomain' ),
		'description' => esc_html__( 'Add widgets here.', 'vnh_textdomain' ),
	],
	'footer-2'  => [
		'name'        => esc_html__( 'Footer 2', 'vnh_textdomain' ),
		'description' => esc_html__( 'Add widgets here.', 'vnh_textdomain' ),
	],
	'footer-3'  => [
		'name'        => esc_html__( 'Footer 3', 'vnh_textdomain' ),
		'description' => esc_html__( 'Add widgets here.', 'vnh_textdomain' ),
	],
] );

trait Theme_Supports {
}
