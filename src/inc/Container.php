<?php

namespace thin;

use DI\ContainerBuilder;
use vnh\Allowed_HTML;
use vnh\Register_Widget_Areas;
use vnh\Register_Widgets;
use vnh\Singleton;
use function DI\create;
use function vnh\get_support;

final class Container extends Singleton {
	public $services;

	protected function __construct() {
		$builder = new ContainerBuilder();
		$builder->addDefinitions(
			[
				ACF::class                   => create(),
				Customizer::class            => create(),
				Allowed_HTML::class          => create(),
				Social_Menu::class           => create(),
				Register_Widgets::class      => create()->constructor( get_support( 'thin_widgets' ) ),
				Register_Widget_Areas::class => create()->constructor( get_support( 'thin_widget_areas' ) ),
				Comments::class              => create(),
				Menu::class                  => create(),
			]
		);

		$this->services = $builder->build();
	}
}
