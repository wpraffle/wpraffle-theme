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
<div class="diamond-panel">
	<p class="diamond-panel-intro"><?php esc_html_e( 'Control the layout and structural options for the theme.', 'wpraffle-theme' ); ?></p>

	<h3><?php esc_html_e( 'Layout', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr>
			<th scope="row"><label for="wpraffle_container_width"><?php esc_html_e( 'Container width', 'wpraffle-theme' ); ?></label></th>
			<td>
				<input type="number" min="800" max="1920" step="10" id="wpraffle_container_width" name="diamond[container_width]" value="<?php echo esc_attr( $s['container_width'] ); ?>"> px
				<p class="description"><?php esc_html_e( 'Maximum content width for the site (800–1920px).', 'wpraffle-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="wpraffle_radius"><?php esc_html_e( 'Card corner radius', 'wpraffle-theme' ); ?></label></th>
			<td>
				<input type="number" min="0" max="30" step="1" id="wpraffle_radius" name="diamond[radius]" value="<?php echo esc_attr( $s['radius'] ); ?>"> px
				<p class="description"><?php esc_html_e( 'Base border-radius for cards and inputs (0–30px). Larger and smaller variants are derived automatically.', 'wpraffle-theme' ); ?></p>
			</td>
		</tr>
	</tbody></table>

	<h3><?php esc_html_e( 'Header', 'wpraffle-theme' ); ?></h3>
	<table class="form-table" role="presentation"><tbody>
		<tr>
			<th scope="row"><?php esc_html_e( 'Sticky header', 'wpraffle-theme' ); ?></th>
			<td>
				<label><input type="checkbox" name="diamond[sticky_header]" value="on" <?php checked( $s['sticky_header'], 'on' ); ?>> <?php esc_html_e( 'Keep the header fixed at the top while scrolling', 'wpraffle-theme' ); ?></label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Full-width header', 'wpraffle-theme' ); ?></th>
			<td>
				<label><input type="checkbox" name="diamond[fullwidth_header]" value="on" <?php checked( $s['fullwidth_header'], 'on' ); ?>> <?php esc_html_e( 'Let the header span the full viewport width', 'wpraffle-theme' ); ?></label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Show top bar', 'wpraffle-theme' ); ?></th>
			<td>
				<label><input type="checkbox" name="diamond[show_topbar]" value="on" <?php checked( $s['show_topbar'], 'on' ); ?>> <?php esc_html_e( 'Display the dark top bar above the header', 'wpraffle-theme' ); ?></label>
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
			<th scope="row"><label for="wpraffle_github_repo"><?php esc_html_e( 'GitHub repository', 'wpraffle-theme' ); ?></label></th>
			<td>
				<input type="text" id="wpraffle_github_repo" name="wpraffle_theme_update[github_repo]" value="<?php echo esc_attr( get_option( 'wpraffle_theme_update_settings', array( 'github_repo' => 'wpraffle/wpraffle-theme' ) )['github_repo'] ?? 'wpraffle/wpraffle-theme' ); ?>" class="regular-text" placeholder="owner/repo">
				<p class="description"><?php esc_html_e( 'The owner/repo to check for releases (default: wpraffle/wpraffle-theme).', 'wpraffle-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Auto-update', 'wpraffle-theme' ); ?></th>
			<td>
				<label><input type="checkbox" name="wpraffle_theme_update[auto_update]" value="1" <?php checked( get_option( 'wpraffle_theme_update_settings', array() )['auto_update'] ?? 0, '1' ); ?>> <?php esc_html_e( 'Install updates automatically when available', 'wpraffle-theme' ); ?></label>
			</td>
		</tr>
	</tbody></table>
</div>
