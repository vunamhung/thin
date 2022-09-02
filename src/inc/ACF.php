<?php

namespace thin;

use vnh\contracts\Bootable;

class ACF implements Bootable {

	public function boot() {
		add_filter( 'acf/settings/save_json', [ $this, 'json_save_point' ] );
		add_filter( 'acf/settings/load_json', [ $this, 'json_load_point' ] );
		add_filter( 'acf/settings/show_admin', [ $this, 'show_admin' ] );
		$this->options_page();
	}

	public function json_save_point() {
		return get_theme_file_path( 'acf-json/' );
	}

	public function json_load_point( $paths ) {
		unset( $paths[0] ); // remove original path (optional).

		$paths[] = get_theme_file_path( 'acf-json/' ); // append path.

		return $paths;
	}

	public function show_admin() {
		return WP_DEBUG;
	}

	public function options_page() {
		acf_add_options_page(
			[
				'page_title' => 'Theme Settings',
				'menu_title' => 'Theme Settings',
				'menu_slug'  => 'theme-settings',
				'capability' => 'edit_posts',
				'redirect'   => false,
			]
		);
	}
}
