<?php

namespace thin;

use WP_Customize_Manager;
use WP_Widget;

abstract class Widget extends WP_Widget {
	public $defaults;
	public $fields;
	public $display;

	public function __construct() {
		$config = $this->config();

		$id_base         = isset( $config['id_base'] ) ? $config['id_base'] : null;
		$name            = sprintf( '#%s', esc_html( $config['name'] ) );
		$widget_options  = [
			'classname'                   => $config['classname'],
			'description'                 => wp_kses_post( $config['description'] ),
			'customize_selective_refresh' => true,
		];
		$control_options = ! empty( $config['control_options'] ) ? $config['control_options'] : [];

		$this->fields  = ! empty( $config['fields'] ) ? $config['fields'] : null;
		$this->display = ! empty( $config['display'] ) ? $config['display'] : null;

		if ( ! empty( $this->fields ) ) {
			$this->defaults = [];
			foreach ( $this->fields as $field_id => $field_args ) {
				if ( array_key_exists( 'default', $field_args ) ) {
					$this->defaults[ $field_id ] = esc_html( $field_args['default'] );
				} else {
					$this->defaults[ $field_id ] = '';
				}
			}
		}

		parent::__construct( $id_base, $name, $widget_options, $control_options );
	}

	abstract public function config();

	/**
	 * Outputs the settings update form.
	 *
	 * @param array $instance
	 *
	 * @return void
	 * @uses display_field_custom, display_field_text, display_field_checkbox
	 * @uses display_field_textarea, display_field_number, display_field_select
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );

		if ( empty( $this->fields ) ) {
			printf( '<p>%s</p>', esc_html__( 'There are no options for this widget.', 'vnh_textdomain' ) );

			return null;
		}

		foreach ( $this->fields as $field_name => $field_values ) {
			$display_field = "display_field_{$field_values['type']}";

			if ( method_exists( $this, $display_field ) ) {
				$this->$display_field( $field_name, $field_values, $instance );
			}
		}
	}

	public function display_field_custom( $field_name, $field_values, $instance ) {
		echo wp_kses_post( $field_values['content'] );
	}

	public function display_field_text( $field_name, $field_values, $instance ) {
		printf(
			'<p><label for="%1$s">%2$s</label><input type="text" field_name="%1$s" class="widefat" name="%3$s" value="%4$s"></p>',
			esc_attr( $this->get_field_id( $field_name ) ),
			esc_html( $field_values['label'] ),
			esc_attr( $this->get_field_name( $field_name ) ),
			esc_attr( $instance[ $field_name ] )
		);
	}

	public function display_field_checkbox( $field_name, $field_values, $instance ) {
		printf(
			'<p><input type="checkbox" field_name="%1$s" class="checkbox" name="%3$s" %4$s value="1"><label for="%1$s">%2$s</label></p>',
			esc_attr( $this->get_field_id( $field_name ) ),
			esc_html( $field_values['label'] ),
			esc_attr( $this->get_field_name( $field_name ) ),
			checked( $instance[ $field_name ], '1', false )
		);
	}

	public function display_field_textarea( $field_name, $field_values, $instance ) {
		printf(
			'<p><label for="%1$s">%2$s</label><textarea field_name="%1$s" class="widefat" name="%3$s" rows="%5$s">%4$s</textarea></p>',
			esc_attr( $this->get_field_id( $field_name ) ),
			esc_html( $field_values['label'] ),
			esc_attr( $this->get_field_name( $field_name ) ),
			esc_textarea( $instance[ $field_name ] ),
			! empty( $field_values['row'] ) ? esc_html( $field_values['row'] ) : 8
		);
	}

	public function display_field_number( $field_name, $field_values, $instance ) {
		printf(
			'<p><label for="%1$s">%2$s</label><input type="number" field_name="%1$s" class="widefat" name="%3$s" min="%5$s" max="%6$s" value="%4$s"/></p>',
			esc_attr( $this->get_field_id( $field_name ) ),
			esc_html( $field_values['label'] ),
			esc_attr( $this->get_field_name( $field_name ) ),
			esc_attr( $instance[ $field_name ] ),
			esc_attr( $field_values['options']['min'] ),
			esc_attr( $field_values['options']['max'] )
		);
	}

	public function display_field_select( $field_name, $field_values, $instance ) {
		if ( empty( $field_values['subtype'] ) ) {
			$all_options = '';

			foreach ( $field_values['options'] as $value => $name ) {
				$selected = selected( $instance[ $field_name ], $value, false );

				$all_options .= sprintf( '<option %1$s value="%2$s">%3$s</option>', $selected, $value, $name );
			}

			printf(
				'<p><label for="%1$s">%2$s</label><select field_name="%1$s" class="widefat" name="%3$s">%4$s</select></p>',
				esc_attr( $this->get_field_id( $field_name ) ),
				esc_html( $field_values['label'] ),
				esc_attr( $this->get_field_name( $field_name ) ),
				wp_kses( $all_options, 'widget_field' )
			);
		} elseif ( $field_values['subtype'] === 'taxonomy' ) {
			$total_tax  = '';
			$taxonomies = get_terms( $field_values['options'] );

			if ( ! $taxonomies ) {
				if ( $field_values['options']['taxonomy'] === 'nav_menu' ) {
					$is_customize = $GLOBALS['wp_customize'] instanceof WP_Customize_Manager;
					printf(
						'<p>%1$s <a href="%2$s">%3$s</a>.</p>',
						esc_html__( 'No menus have been created yet.', 'vnh_textdomain' ),
						$is_customize ? "javascript: wp.customize.panel( 'nav_menus' ).focus();" : esc_url( admin_url( 'nav-menus.php' ) ),
						esc_html__( 'Create some', 'vnh_textdomain' )
					);
				} else {
					printf( '<p>%s</p>', esc_html__( 'No taxonomy have been created yet. Create some', 'vnh_textdomain' ) );
				}

				return;
			}

			foreach ( $taxonomies as $taxonomy ) {
				if ( empty( $field_values['multiple'] ) ) {
					$selected = selected( $instance[ $field_name ], $taxonomy->slug, false );
				} else {
					$selected = in_array( $taxonomy->slug, $instance[ $field_name ], true ) ? 'selected="selected"' : '';
				}

				$total_tax .= sprintf( '<option %1$s value="%2$s">%3$s</option>', $selected, $taxonomy->slug, $taxonomy->name );
			}

			printf(
				'<p><label for="%1$s">%2$s</label><select field_name="%1$s" class="widefat" name="%3$s">%4$s</select></p>',
				esc_attr( $this->get_field_id( $field_name ) ),
				esc_html( $field_values['label'] ),
				esc_attr( $this->get_field_name( $field_name ) ),
				wp_kses( $total_tax, 'widget_field' )
			);
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		if ( is_array( $this->fields ) && count( $this->fields ) ) {
			foreach ( $this->fields as $id => $param ) {
				if ( $param['type'] === 'text' ) {
					$instance[ $id ] = sanitize_text_field( $new_instance[ $id ] );
				} else {
					$instance[ $id ] = $new_instance[ $id ];
				}
			}
		}

		return $instance;
	}

	protected function widget_start( $args, $instance ) {
		$html = $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			$html .= $args['before_title'];
			$html .= apply_filters( 'widget_title', $instance['title'] );
			$html .= $args['after_title'];
		}

		echo wp_kses( $html, 'default' );
	}

	protected function widget_end( $args ) {
		echo wp_kses( $args['after_widget'], 'default' );
	}
}
