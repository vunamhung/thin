<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package thin
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php do_action( 'thin_before_site' ); ?>

<div id="page" class="hfeed site">
	<?php do_action( 'thin_before_header' ); ?>

	<header id="masthead" class="site-header" role="banner">

		<?php
		/**
		 * Functions hooked into thin_header action
		 *
		 * @hooked thin_header_container                 - 0
		 * @hooked thin_skip_links                       - 5
		 * @hooked thin_social_icons                     - 10
		 * @hooked thin_site_branding                    - 20
		 * @hooked thin_secondary_navigation             - 30
		 * @hooked thin_product_search                   - 40
		 * @hooked thin_header_container_close           - 41
		 * @hooked thin_primary_navigation_wrapper       - 42
		 * @hooked thin_primary_navigation               - 50
		 * @hooked thin_header_cart                      - 60
		 * @hooked thin_primary_navigation_wrapper_close - 68
		 */
		do_action( 'thin_header' );
		?>

	</header><!-- #masthead -->

	<?php
	/**
	 * Functions hooked in to thin_before_content
	 *
	 * @hooked thin_header_widget_region - 10
	 * @hooked woocommerce_breadcrumb - 10
	 */
	do_action( 'thin_before_content' );
	?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">

<?php
do_action( 'thin_content_top' );
