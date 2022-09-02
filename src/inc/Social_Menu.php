<?php

namespace thin;

use vnh\contracts\Bootable;
use vnh\contracts\Displayable;
use WP_Customize_Manager;

use function vnh\get_support;
use function vnh\get_svg_icon;

class Social_Menu implements Bootable, Displayable {
	public $args;
	public $icons;

	public $default_icons = [
		'500px.com'       => '500px',
		'behance.net'     => 'behance',
		'codepen.io'      => 'codepen',
		'dribbble.com'    => 'dribbble',
		'dropbox.com'     => 'dropbox',
		'facebook.com'    => 'facebook',
		'flickr.com'      => 'flickr',
		'foursquare.com'  => 'foursquare',
		'plus.google.com' => 'google',
		'github.com'      => 'github',
		'instagram.com'   => 'instagram',
		'linkedin.com'    => 'linkedin',
		'mailto:'         => 'email',
		'medium.com'      => 'medium',
		'meetup.com'      => 'meetup',
		'pinterest.com'   => 'pinterest',
		'reddit.com'      => 'reddit',
		'smugmug.net'     => 'smugmug',
		'snapchat.com'    => 'snapchat-ghost',
		'soundcloud.com'  => 'soundcloud',
		'spotify.com'     => 'spotify',
		'stumbleupon.com' => 'stumbleupon',
		'tumblr.com'      => 'tumblr',
		'twitch.tv'       => 'twitch',
		'twitter.com'     => 'twitter',
		'vimeo.com'       => 'vimeo',
		'vine.co'         => 'vine',
		'wordpress.org'   => 'wordpress',
		'wordpress.com'   => 'wordpress',
		'yelp.com'        => 'yelp',
		'youtube.com'     => 'youtube',
	];

	public function __construct() {
		$this->args = wp_parse_args(
			get_support( 'thin_social_menu' ),
			[
				'menu_location' => 'social',
				'menu_desc'     => esc_html__( 'Social', 'vnh_textdomain' ),
				'menu_class'    => 'social-menu',
				'hint'          => 'bottom',
				'widget'        => false,
				'icons'         => $this->default_icons,
				'fallback'      => apply_filters( 'thin/social_menu/fallback', [ $this, 'fallback' ] ),
			]
		);
		$this->args = apply_filters( 'thin/social_menu/default_args', $this->args );

		$this->icons = apply_filters( 'thin/social_menu/icons', $this->args['icons'] );

		$this->boot();
	}

	public function boot() {
		add_action( 'after_setup_theme', [ $this, 'register_menu_locations' ] );
		add_filter( 'walker_nav_menu_start_el', [ $this, 'nav_menu_social_icons' ], 10, 4 );
		add_action( 'thin/social_menu', [ $this, 'display' ] );
	}

	public function register_menu_locations() {
		register_nav_menus( [ $this->args['menu_location'] => $this->args['menu_desc'] ] );
	}

	public function nav_menu_social_icons( $item_output, $item, $depth, $args ) {
		if ( $args->theme_location !== $this->args['menu_location'] ) {
			return $item_output;
		}

		foreach ( $this->icons as $icon_uri => $icon_name ) {
			if ( strpos( $item_output, $icon_uri ) !== false ) {
				$icon        = sprintf( '<i class="icon icon-%s"></i>', $icon_name );
				$item_output = str_replace( $args->link_after, $args->link_after . $icon, $item_output );
			}
		}

		return $item_output;
	}

	public function display() {
		return wp_nav_menu(
			[
				'theme_location' => $this->args['menu_location'],
				'menu_class'     => $this->args['menu_class'],
				'container'      => '',
				'depth'          => 1,
				'link_before'    => '<span class="screen-reader-text">',
				'link_after'     => '</span>',
				'fallback_cb'    => $this->args['fallback'],
			]
		);
	}

	public function fallback() {
		if ( $GLOBALS['wp_customize'] instanceof WP_Customize_Manager ) {
			$url = "javascript: wp.customize.panel( 'nav_menus' ).focus();";
		} else {
			$url = esc_url( admin_url( 'nav-menus.php' ) );
		}

		printf(
			'<div>%s<a href="%s">%s</a></div>',
			esc_html__( 'No social menu have been created yet.', 'vnh_textdomain' ),
			$url, //phpcs:disable
			esc_html__( ' Create one', 'vnh_textdomain' )
		);
	}
}
