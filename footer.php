<?php
/**
 * The footer template. Fires wpraffle_theme_footer() which either renders an
 * Elementor Theme Builder footer (if active) or the native footer partial.
 *
 * @package Diamond
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	<footer class="diamond-footer site-footer">
		<?php wpraffle_theme_footer(); ?>
	</footer>
</div><!-- .site -->

<?php wp_footer(); ?>
</body>
</html>
