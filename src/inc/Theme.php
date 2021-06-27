<?php

namespace thin;

class Theme {
	public function __construct() {
		$this->load();
		$this->boot();
	}

	public function load() {
		new Customizer();
		new Comments();
	}

	public function boot() {
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_action( 'after_setup_theme', [ $this, 'content_width' ], 0 );
		add_action( 'widgets_init', [ $this, 'widgets_init' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ] );
		add_filter( 'body_class', [ $this, 'body_classes' ] );
		add_action( 'wp_head', [ $this, 'pingback_header' ] );
		add_action( 'tgmpa_register', [ $this, 'required_plugins' ] );
	}

	public function setup() {
		load_theme_textdomain( 'vnh_textdomain', get_template_directory() . '/languages' );
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( [ 'menu-1' => esc_html__( 'Primary', 'vnh_textdomain' ) ] );
		add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
		add_theme_support( 'responsive-embeds' );

		add_theme_support( 'wp-block-styles' ); // Add support for Block Styles.
		add_theme_support( 'align-wide' ); // Add support for full and wide align images.

		add_theme_support( 'editor-styles' ); // Add support for editor styles.
		add_editor_style( 'style-editor.css' ); // Enqueue editor styles.

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		add_theme_support(
			'custom-logo',
			[
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			]
		);
	}

	public function content_width() {
		$GLOBALS['content_width'] = apply_filters( 'thin_content_width', 640 );
	}

	public function widgets_init() {
		register_sidebar(
			[
				'name'          => esc_html__( 'Sidebar', 'vnh_textdomain' ),
				'id'            => 'sidebar-1',
				'description'   => esc_html__( 'Add widgets here.', 'vnh_textdomain' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			]
		);
	}

	public function scripts() {
		wp_enqueue_style( 'thin-style', get_stylesheet_uri(), [], THEME_VERSION );

		wp_enqueue_script( 'thin-main', get_template_directory_uri() . '/js/main.js', [], THEME_VERSION, true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	public function body_classes( $classes ) {
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		// Adds a class of no-sidebar when there is no sidebar present.
		if ( ! is_active_sidebar( 'sidebar-1' ) ) {
			$classes[] = 'no-sidebar';
		}

		return $classes;
	}

	public function pingback_header() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}

	public function required_plugins() {
		$plugins = [
			[
				'name'     => 'BuddyPress',
				'slug'     => 'buddypress',
				'required' => false,
			],
			[
				'name'        => 'WordPress SEO by Yoast',
				'slug'        => 'wordpress-seo',
				'is_callable' => 'wpseo_init',
			],
		];

		$config = [
			'id'           => 'vnh_textdomain',
			'default_path' => '',
			'menu'         => 'thin-install-plugins',
			'parent_slug'  => 'themes.php',
			'capability'   => 'edit_theme_options',
			'has_notices'  => true,
			'dismissable'  => true,
			'dismiss_msg'  => '',
			'is_automatic' => false,
			'message'      => '',
		];

		tgmpa( $plugins, $config );
	}
}
