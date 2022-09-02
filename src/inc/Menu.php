<?php

namespace thin;

class Menu {
	public function boot() {
		$this->header();
	}

	public function header() {
		add_action( 'thin/menu', [ $this, 'main_menu' ] );
		add_action( 'wp_footer', [ $this, 'mobile_menu' ] );
	}

	public function main_menu() {
		wp_nav_menu(
			[
				'theme_location' => 'menu-1',
				'menu_id'        => 'primary-menu',
			]
		);
	}

	public function mobile_menu() {
		echo '<nav id="mobile-menu">';
		$this->main_menu();
		echo '</nav>';
	}
}
