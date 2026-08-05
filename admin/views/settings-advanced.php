<?php
/**
 * Settings page — Advanced tab.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s = WPRaffle_Theme_Settings::instance()->get_settings();
?>
<div class="wpr-panel">
	<p class="wpr-panel-intro"><?php esc_html_e( 'Control the layout and structural options for the theme.', 'wpraffle-theme' ); ?></p>

	<h3><?php esc_html_e( 'Layout', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr>
			<th scope="row"><label for="wpraffle_container_width"><?php esc_html_e( 'Container width', 'wpraffle-theme' ); ?></label></th>
			<td>
				<input type="number" min="800" max="1920" step="10" id="wpraffle_container_width" name="wpr_settings[container_width]" value="<?php echo esc_attr( $s['container_width'] ); ?>"> px
				<p class="description"><?php esc_html_e( 'Maximum content width for the site (800–1920px).', 'wpraffle-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="wpraffle_radius"><?php esc_html_e( 'Card corner radius', 'wpraffle-theme' ); ?></label></th>
			<td>
				<input type="number" min="0" max="30" step="1" id="wpraffle_radius" name="wpr_settings[radius]" value="<?php echo esc_attr( $s['radius'] ); ?>"> px
				<p class="description"><?php esc_html_e( 'Base border-radius for cards and inputs (0–30px). Larger and smaller variants are derived automatically.', 'wpraffle-theme' ); ?></p>
			</td>
		</tr>
	</tbody></table>

	<h3><?php esc_html_e( 'Header', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr>
			<th scope="row"><?php esc_html_e( 'Sticky header', 'wpraffle-theme' ); ?></th>
			<td>
				<label><input type="checkbox" name="wpr_settings[sticky_header]" value="on" <?php checked( $s['sticky_header'], 'on' ); ?>> <?php esc_html_e( 'Keep the header fixed at the top while scrolling', 'wpraffle-theme' ); ?></label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Full-width header', 'wpraffle-theme' ); ?></th>
			<td>
				<label><input type="checkbox" name="wpr_settings[fullwidth_header]" value="on" <?php checked( $s['fullwidth_header'], 'on' ); ?>> <?php esc_html_e( 'Let the header span the full viewport width', 'wpraffle-theme' ); ?></label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Show top bar', 'wpraffle-theme' ); ?></th>
			<td>
				<label><input type="checkbox" name="wpr_settings[show_topbar]" value="on" <?php checked( $s['show_topbar'], 'on' ); ?>> <?php esc_html_e( 'Display the dark top bar above the header', 'wpraffle-theme' ); ?></label>
			</td>
		</tr>
	</tbody></table>

	<hr>

	<h3><?php esc_html_e( 'Updates', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr>
			<th scope="row"><?php esc_html_e( 'Installed version', 'wpraffle-theme' ); ?></th>
			<td><code>v<?php echo esc_html( WPRaffle_Theme_Updater::current_version() ); ?></code></td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Latest available', 'wpraffle-theme' ); ?></th>
			<td>
				<?php
				$latest = WPRaffle_Theme_Updater::latest_version();
				if ( $latest ) :
					?>
					<code>v<?php echo esc_html( $latest ); ?></code>
					<?php
					if ( version_compare( $latest, WPRaffle_Theme_Updater::current_version(), '>' ) ) :
						?>
						&nbsp;<span class="dashicons dashicons-update" style="color:#d63638;"></span>
						<strong style="color:#d63638;"><?php esc_html_e( 'Update available', 'wpraffle-theme' ); ?></strong>
						<?php
					else :
						?>
						&nbsp;<span class="dashicons dashicons-yes-alt" style="color:#00a32a;"></span>
						<?php esc_html_e( 'Up to date', 'wpraffle-theme' ); ?>
					<?php endif; ?>
				<?php else : ?>
					<span class="description"><?php esc_html_e( 'Not checked yet. Click "Check for updates" below.', 'wpraffle-theme' ); ?></span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Check for updates', 'wpraffle-theme' ); ?></th>
			<td>
				<a href="<?php echo esc_url( WPRaffle_Theme_Updater::check_url() ); ?>" class="button button-secondary">
					<span class="dashicons dashicons-update" style="vertical-align:text-top;"></span>
					<?php esc_html_e( 'Check for updates now', 'wpraffle-theme' ); ?>
				</a>
				<p class="description"><?php esc_html_e( 'Polls the GitHub repository for a new release. Updates are also checked automatically twice daily.', 'wpraffle-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'GitHub repository', 'wpraffle-theme' ); ?></th>
			<td>
				<code style="font-size:13px;padding:3px 8px;background:#f0f0f1;border-radius:4px;">wpraffle/wpraffle-theme</code>
				<a href="https://github.com/wpraffle/wpraffle-theme" target="_blank" rel="noopener noreferrer" style="margin-left:8px;"><?php esc_html_e( 'View on GitHub ↗', 'wpraffle-theme' ); ?></a>
				<p class="description"><?php esc_html_e( 'The update source is hard-coded and cannot be changed. Theme releases are pulled from this repository.', 'wpraffle-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Auto-update', 'wpraffle-theme' ); ?></th>
			<td>
				<label><input type="checkbox" name="wpraffle_theme_update[auto_update]" value="1" <?php checked( get_option( 'wpraffle_theme_update_settings', array() )['auto_update'] ?? 0, '1' ); ?>> <?php esc_html_e( 'Install updates automatically when available', 'wpraffle-theme' ); ?></label>
			</td>
		</tr>
	</tbody></table>

	<hr>

	<h3><?php esc_html_e( 'Import / Export', 'wpraffle-theme' ); ?></h3>
	<p class="description" style="margin-bottom:1rem;"><?php esc_html_e( 'Back up or transfer your theme settings between sites.', 'wpraffle-theme' ); ?></p>
	<table class="form-table" role="presentation"><tbody>
		<tr>
			<th scope="row"><?php esc_html_e( 'Export settings', 'wpraffle-theme' ); ?></th>
			<td>
				<a href="<?php echo esc_url( wp_nonce_url( admin_url( '?wprt_export=1' ), 'wprt_export' ) ); ?>" class="button button-secondary">
					<span class="dashicons dashicons-download" style="vertical-align:text-top;"></span>
					<?php esc_html_e( 'Download settings (JSON)', 'wpraffle-theme' ); ?>
				</a>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Import settings', 'wpraffle-theme' ); ?></th>
			<td>
				<form method="post" enctype="multipart/form-data" style="display:inline;">
					<?php wp_nonce_field( 'wprt_import' ); ?>
					<input type="file" name="wprt_import_file" accept=".json" style="vertical-align:baseline;">
					<button type="submit" name="wprt_import_submit" value="1" class="button button-secondary">
						<span class="dashicons dashicons-upload" style="vertical-align:text-top;"></span>
						<?php esc_html_e( 'Upload & Apply', 'wpraffle-theme' ); ?>
					</button>
				</form>
				<?php if ( isset( $_GET['import'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
					<?php if ( 'success' === $_GET['import'] ) : ?>
						<p style="color:#00a32a;font-weight:600;"><?php esc_html_e( '✓ Settings imported successfully.', 'wpraffle-theme' ); ?></p>
					<?php elseif ( 'error' === $_GET['import'] ) : ?>
						<p style="color:#d63638;font-weight:600;"><?php esc_html_e( '✗ Import failed. Please check the file is a valid settings JSON.', 'wpraffle-theme' ); ?></p>
					<?php endif; ?>
				<?php endif; ?>
			</td>
		</tr>
	</tbody></table>

	<hr>

	<h3 style="color:#d63638;"><?php esc_html_e( 'Reset', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr>
			<th scope="row"><?php esc_html_e( 'Reset all settings', 'wpraffle-theme' ); ?></th>
			<td>
				<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'themes.php?page=wpraffle-theme-settings&tab=advanced&wprt_reset=1' ), 'wprt_reset' ) ); ?>"
					class="button button-link-delete"
					onclick="return confirm('<?php esc_attr_e( 'This will reset ALL theme settings to their defaults. This cannot be undone. Continue?', 'wpraffle-theme' ); ?>');">
					<span class="dashicons dashicons-warning" style="vertical-align:text-top;"></span>
					<?php esc_html_e( 'Reset everything to defaults', 'wpraffle-theme' ); ?>
				</a>
			</td>
		</tr>
	</tbody></table>
</div>
