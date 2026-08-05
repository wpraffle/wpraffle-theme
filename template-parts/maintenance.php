<?php
/**
 * Maintenance / Coming Soon page.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();
$bg = $s['maintenance_bg'] ? 'background-image:url(' . esc_url( $s['maintenance_bg'] ) . ');' : '';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex,nofollow">
	<title><?php echo esc_html( $s['maintenance_title'] ); ?> — <?php bloginfo( 'name' ); ?></title>
	<style>
		*{margin:0;padding:0;box-sizing:border-box;}
		body{font-family:'Montserrat',system-ui,sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;background:#1a1a1a;color:#fff;<?php echo $bg; // phpcs:ignore WordPress.Security. ?>background-size:cover;background-position:center;position:relative;}
		body::before{content:"";position:absolute;inset:0;background:rgba(0,0,0,0.6);}
		.wprt-maintenance{position:relative;z-index:1;text-align:center;max-width:600px;padding:2rem;}
		.wprt-maintenance h1{font-size:2.5rem;margin-bottom:1rem;font-weight:800;}
		.wprt-maintenance p{font-size:1.1rem;opacity:0.85;margin-bottom:1.5rem;line-height:1.6;}
		.wprt-maintenance form{margin-top:1.5rem;display:flex;gap:0.5rem;justify-content:center;flex-wrap:wrap;}
		.wprt-maintenance input{padding:0.75rem 1rem;border:0;border-radius:50rem;font-size:1rem;min-width:250px;}
		.wprt-maintenance button{padding:0.75rem 1.5rem;background:#e4678a;color:#fff;border:0;border-radius:50rem;font-weight:600;cursor:pointer;font-size:1rem;}
		<?php echo $s['custom_css']; // phpcs:ignore WordPress.Security. ?>
	</style>
</head>
<body>
	<div class="wprt-maintenance">
		<h1><?php echo esc_html( $s['maintenance_title'] ); ?></h1>
		<p><?php echo esc_html( $s['maintenance_text'] ); ?></p>
		<?php if ( 'on' === $s['maintenance_email'] ) : ?>
			<form method="post">
				<input type="email" name="wprt_email" placeholder="<?php esc_attr_e( 'Enter your email', 'wpraffle-theme' ); ?>" required>
				<button type="submit"><?php esc_html_e( 'Notify Me', 'wpraffle-theme' ); ?></button>
			</form>
		<?php endif; ?>
	</div>
</body>
</html>
