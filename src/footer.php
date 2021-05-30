<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package thin
 */

?>

		</div><!-- .col-full -->
	</div><!-- #content -->

	<?php do_action( 'thin_before_footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="col-full">

			<?php
			/**
			 * Functions hooked in to thin_footer action
			 *
			 * @hooked thin_footer_widgets - 10
			 * @hooked thin_credit         - 20
			 */
			do_action( 'thin_footer' );
			?>

		</div><!-- .col-full -->
	</footer><!-- #colophon -->

	<?php do_action( 'thin_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
