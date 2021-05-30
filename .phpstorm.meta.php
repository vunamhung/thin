<?php

namespace PHPSTORM_META {
	registerArgumentsSet('textdomain_values', 'vnh_textdomain');
	expectedArguments(\__(), 1, argumentsSet("textdomain_values"));
	expectedArguments(\_nx(), 4, argumentsSet("textdomain_values"));
	expectedArguments(\esc_html__(), 1, argumentsSet("textdomain_values"));
	expectedArguments(\esc_attr__(), 1, argumentsSet("textdomain_values"));
	expectedArguments(\esc_attr_e(), 1, argumentsSet("textdomain_values"));
	expectedArguments(\esc_html_e(), 1, argumentsSet("textdomain_values"));
	expectedArguments(\_n(), 3, argumentsSet("textdomain_values"));
	expectedArguments(\load_plugin_textdomain(), 0, argumentsSet("textdomain_values"));
}
