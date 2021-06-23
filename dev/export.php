<?php

class Export {
	public function __construct() {
		if (class_exists('WP_CLI')) {
			WP_CLI::add_command('vnh export menus', [$this, 'export_menus']);
			WP_CLI::add_command('vnh export cptui_cpt', [$this, 'export_cptui_post_types']);
			WP_CLI::add_command('vnh export cptui_ctax', [$this, 'export_cptui_taxonomies']);
			WP_CLI::add_command('vnh export homepage_settings', [$this, 'export_homepage_settings']);
			WP_CLI::add_command('vnh export woo_settings', [$this, 'export_woo_settings']);
			WP_CLI::add_command('vnh export widgets', [$this, 'export_widgets']);
			WP_CLI::add_command('vnh export customizer', [$this, 'export_customizer_options']);
			WP_CLI::add_command('vnh reset customizer', [$this, 'reset_customizer']);
		}
	}

	public function export_widgets() {
		// Get all available widgets site
		$available_widgets = $this->available_widgets();

		// Get all widget instances for each widget
		$widget_instances = [];
		foreach ($available_widgets as $widget_data) {
			// Get all instances for this ID base
			$instances = get_option('widget_' . $widget_data['id_base']);

			// Have instances
			if (!empty($instances)) {
				// Loop instances
				foreach ($instances as $instance_id => $instance_data) {
					// Key is ID (not _multiwidget)
					if (is_numeric($instance_id)) {
						$unique_instance_id = $widget_data['id_base'] . '-' . $instance_id;
						$widget_instances[$unique_instance_id] = $instance_data;
					}
				}
			}
		}

		// Gather sidebars with their widget instances
		$sidebars_widgets = get_option('sidebars_widgets'); // get sidebars and their unique widgets IDs
		$sidebars_widget_instances = [];

		foreach ($sidebars_widgets as $sidebar_id => $widget_ids) {
			// Skip inactive widgets
			if ($sidebar_id === 'wp_inactive_widgets') {
				continue;
			}

			// Skip if no data or not an array (array_version)
			if (!is_array($widget_ids) || empty($widget_ids)) {
				continue;
			}

			// Loop widget IDs for this sidebar
			foreach ($widget_ids as $widget_id) {
				// Is there an instance for this widget ID?
				if (isset($widget_instances[$widget_id])) {
					// Add to array
					$sidebars_widget_instances[$sidebar_id][$widget_id] = $widget_instances[$widget_id];
				}
			}
		}

		$data = wp_json_encode($sidebars_widget_instances);

		echo $data; // phpcs:ignore
	}

	public function export_menus() {
		global $wpdb;

		$data = [];
		$locations = get_nav_menu_locations();

		foreach ((array) $locations as $location => $menu_id) {
			$sql = "
				SELECT * 
				FROM $wpdb->terms 
				WHERE term_id={$menu_id}
			";

			$menu_slug = $wpdb->get_results($sql, ARRAY_A); //phpcs:disable

			if (!empty($menu_slug)) {
				$data[$location] = $menu_slug[0]['slug'];
			}
		}

		$output = wp_json_encode($data);

		echo $output; // WPCS: xss ok.
	}

	public function export_cptui_post_types() {
		$output = wp_json_encode(get_option('cptui_post_types', []));

		echo $output; // WPCS: xss ok.
	}

	public function export_cptui_taxonomies() {
		$output = wp_json_encode(get_option('cptui_taxonomies', []));

		echo $output; // WPCS: xss ok.
	}

	public function export_homepage_settings() {
		$show_on_front = get_option('show_on_front');
		$post_page_id = get_option('page_for_posts');
		$static_page_id = get_option('page_on_front');
		$settings_pages = [
			'show_on_front' => $show_on_front,
		];

		if ($static_page_id) {
			$settings_pages['page_on_front'] = get_post($static_page_id)->post_title;
		}

		if ($post_page_id) {
			$settings_pages['page_for_posts'] = get_post($post_page_id)->post_title;
		}

		$output = wp_json_encode($settings_pages);

		echo $output; // WPCS: xss ok.
	}

	public function export_woo_settings() {
		$shop_page_id = get_option('woocommerce_shop_page_id');
		$cart_page_id = get_option('woocommerce_cart_page_id');
		$checkout_page_id = get_option('woocommerce_checkout_page_id');
		$myaccount_page_id = get_option('woocommerce_myaccount_page_id');

		$settings = [];

		if ($shop_page_id) {
			$settings['shop_page_title'] = get_post($shop_page_id)->post_title;
		}

		if ($cart_page_id) {
			$settings['cart_page_title'] = get_post($cart_page_id)->post_title;
		}

		if ($checkout_page_id) {
			$settings['checkout_page_title'] = get_post($checkout_page_id)->post_title;
		}

		if ($myaccount_page_id) {
			$settings['myaccount_page_title'] = get_post($myaccount_page_id)->post_title;
		}

		$output = wp_json_encode($settings);

		echo $output; //phpcs:disable
	}

	public function export_customizer_options() {
		$options = get_theme_mods();
		unset($options['nav_menu_locations']);

		$output = wp_json_encode($options);

		echo $output; // WPCS: xss ok.
	}

	public function reset_customizer() {
		remove_theme_mods();
		WP_CLI::success(esc_html__('Customizer has been reset successful.', 'vnh_textdomain'));
	}

	private function available_widgets() {
		global $wp_registered_widget_controls;

		$widget_controls = $wp_registered_widget_controls;
		$available_widgets = [];

		foreach ($widget_controls as $widget) {
			if (!empty($widget['id_base']) && !isset($available_widgets[$widget['id_base']])) {
				// no dupes
				$available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
				$available_widgets[$widget['id_base']]['name'] = $widget['name'];
			}
		}

		return $available_widgets;
	}
}

new Export();
