<?php
/**
 * The header template. Fires wpraffle_theme_header() which either renders an
 * Elementor Theme Builder header (if active) or the native header partial.
 *
 * @package Diamond
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="diamond-skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'wpraffle-theme' ); ?></a>

<div class="site">
	<?php wpraffle_theme_header(); ?>
