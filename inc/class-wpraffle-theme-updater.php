<?php
/**
 * WPRaffle Theme — GitHub release updater.
 *
 * A faithful theme-port of the WPRaffles plugin's Raffle_Updater. Polls the
 * GitHub releases API for wpraffle/wpraffle-theme (configurable), caches the
 * result for 12 hours, and injects an update into WordPress's theme-update
 * transient when a newer tag is found. Public repos only (no auth header).
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WPRaffle_Theme_Updater
 */
final class WPRaffle_Theme_Updater {

	/**
	 * Hard-coded GitHub repo. Per project rules the update source is fixed and
	 * no longer user-editable from the settings screen, so theme updates can
	 * never be redirected to an arbitrary third-party repo.
	 *
	 * @var string
	 */
	const REPO = 'wpraffle/wpraffle-theme';

	/**
	 * Public GitHub URL for the repository.
	 *
	 * @var string
	 */
	const REPO_URL = 'https://github.com/wpraffle/wpraffle-theme';

	/**
	 * Hook everything in.
	 */
	public function __construct() {
		add_filter( 'pre_set_site_transient_update_themes', array( $this, 'check_for_update' ) );
		add_filter( 'themes_api', array( $this, 'themes_api_info' ), 10, 3 );
		add_filter( 'upgrader_post_install', array( $this, 'after_install' ), 10, 3 );
		add_action( 'admin_init', array( $this, 'handle_manual_check' ) );

		// Cron-driven cache refresh (mirrors the plugin's twicedaily schedule).
		if ( ! wp_next_scheduled( 'wpraffle_theme_check_updates' ) ) {
			wp_schedule_event( time(), 'twicedaily', 'wpraffle_theme_check_updates' );
		}
		add_action( 'wpraffle_theme_check_updates', array( $this, 'refresh_version_cache' ) );
	}

	/**
	 * Get the hard-coded repo (owner/name). The value is a constant and is
	 * intentionally NOT read from options, so the update source cannot be
	 * changed from the admin UI or via a crafted option value.
	 *
	 * @return string
	 */
	private function get_repo() {
		return self::REPO;
	}

	/**
	 * Whether auto-updates are enabled for this theme.
	 *
	 * @return bool
	 */
	private function auto_update_enabled() {
		$settings = get_option( 'wpraffle_theme_update_settings', array() );
		$settings = is_array( $settings ) ? $settings : array();
		return ! empty( $settings['auto_update'] );
	}

	/**
	 * Fetch the latest release from the GitHub API.
	 *
	 * @return array|false Release array or false on failure.
	 */
	private function fetch_latest_release() {
		$repo = $this->get_repo();
		$url  = 'https://api.github.com/repos/' . $repo . '/releases/latest';

		$response = wp_remote_get( $url, array(
			'timeout' => 15,
			'headers' => array(
				'Accept'     => 'application/vnd.github.v3+json',
				'User-Agent' => 'WPRaffleTheme/' . WPRAFFLE_THEME_VERSION,
			),
		) );

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return false;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( ! is_array( $body ) || empty( $body['tag_name'] ) ) {
			return false;
		}

		// Pick the download URL: first .zip asset, else the zipball.
		$download_url = '';
		if ( ! empty( $body['assets'] ) && is_array( $body['assets'] ) ) {
			foreach ( $body['assets'] as $asset ) {
				if ( ! empty( $asset['browser_download_url'] ) && preg_match( '/\.zip$/i', $asset['name'] ) ) {
					$download_url = $asset['browser_download_url'];
					break;
				}
			}
		}
		if ( ! $download_url && ! empty( $body['zipball_url'] ) ) {
			$download_url = $body['zipball_url'];
		}

		return array(
			'version'      => ltrim( $body['tag_name'], 'v' ),
			'download_url' => $download_url,
			'released_at'  => isset( $body['published_at'] ) ? $body['published_at'] : '',
			'changelog'    => isset( $body['body'] ) ? $body['body'] : '',
			'name'         => isset( $body['name'] ) ? $body['name'] : $body['tag_name'],
			'url'          => isset( $body['html_url'] ) ? $body['html_url'] : '',
		);
	}

	/**
	 * Refresh the cached version + release info (called by cron + manual check).
	 */
	public function refresh_version_cache() {
		$release = $this->fetch_latest_release();
		if ( $release ) {
			set_transient( 'wpraffle_theme_latest_version', $release['version'], 12 * HOUR_IN_SECONDS );
			set_transient( 'wpraffle_theme_release_info', $release, 12 * HOUR_IN_SECONDS );
		}
	}

	/**
	 * Handle the manual "Check for updates" action.
	 */
	public function handle_manual_check() {
		if ( ! isset( $_GET['check_theme_updates'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		check_admin_referer( 'wpraffle_theme_check_updates' );

		$this->refresh_version_cache();
		delete_site_transient( 'update_themes' );

		wp_safe_redirect( admin_url( 'themes.php?page=wpraffle-theme-settings&tab=advanced&updated=1' ) );
		exit;
	}

	/**
	 * Inject the update into the themes transient.
	 *
	 * @param object $transient The update_themes site transient.
	 * @return object
	 */
	public function check_for_update( $transient ) {
		if ( ! is_object( $transient ) ) {
			$transient = new stdClass();
		}

		$stylesheet = get_stylesheet();

		// No need to re-check if an update is already known.
		if ( isset( $transient->response[ $stylesheet ] ) ) {
			return $transient;
		}

		$release_info = get_transient( 'wpraffle_theme_release_info' );
		if ( ! $release_info ) {
			$release_info = $this->fetch_latest_release();
			if ( $release_info ) {
				set_transient( 'wpraffle_theme_release_info', $release_info, 12 * HOUR_IN_SECONDS );
				set_transient( 'wpraffle_theme_latest_version', $release_info['version'], 12 * HOUR_IN_SECONDS );
			}
		}

		if ( ! $release_info || empty( $release_info['version'] ) ) {
			return $transient;
		}

		if ( version_compare( $release_info['version'], WPRAFFLE_THEME_VERSION, '>' ) ) {
			$obj                   = new stdClass();
			$obj->theme            = $stylesheet;
			$obj->new_version      = $release_info['version'];
			$obj->url              = $release_info['url'];
			$obj->package          = $release_info['download_url'];
			$obj->requires         = '6.5';
			$obj->requires_php     = '8.1';

			$transient->response[ $stylesheet ] = $obj;
		} else {
			// Mark as up-to-date so WP doesn't keep re-checking.
			if ( ! isset( $transient->checked[ $stylesheet ] ) ) {
				$transient->checked[ $stylesheet ] = WPRAFFLE_THEME_VERSION;
			}
		}

		return $transient;
	}

	/**
	 * Provide release info for the theme details modal.
	 *
	 * @param false|object|array $result The result object.
	 * @param string             $action The action requested.
	 * @param object             $args   Arguments.
	 * @return false|object
	 */
	public function themes_api_info( $result, $action, $args ) {
		if ( 'theme_information' !== $action ) {
			return $result;
		}
		$stylesheet = get_stylesheet();
		$slug       = isset( $args->slug ) ? $args->slug : '';
		if ( $slug !== $stylesheet && 'wpraffle-theme' !== $slug && 'diamond' !== $slug ) {
			return $result;
		}

		$release_info = get_transient( 'wpraffle_theme_release_info' );
		if ( ! $release_info ) {
			return $result;
		}

		$info             = new stdClass();
		$info->name       = 'WPRaffle Theme';
		$info->slug       = $stylesheet;
		$info->version    = $release_info['version'];
		$info->author     = '<a href="https://wpraffles.dev">WPRaffles</a>';
		$info->homepage   = $release_info['url'];
		$info->requires   = '6.5';
		$info->tested     = '6.5';
		$info->requires_php = '8.1';
		$info->last_updated = $release_info['released_at'];
		$info->sections     = array(
			'description' => __( 'A premium WordPress theme for the WPRaffles plugin.', 'wpraffle-theme' ),
			'changelog'   => '<pre>' . esc_html( $release_info['changelog'] ) . '</pre>',
		);

		return $info;
	}

	/**
	 * After install: ensure the theme folder is named correctly.
	 *
	 * GitHub's zipball extracts to a folder like "wpraffle-wpraffle-theme-abc123".
	 * Rename it to the stylesheet slug so WordPress finds the theme after update.
	 *
	 * @param bool  $response   Install success.
	 * @param array $hook_extra Extra args (contains 'theme').
	 * @param array $result     Install result.
	 * @return array
	 */
	public function after_install( $response, $hook_extra, $result ) {
		global $wp_filesystem;

		$stylesheet = get_stylesheet();
		if ( ! isset( $hook_extra['theme'] ) || $hook_extra['theme'] !== $stylesheet ) {
			return $result;
		}

		$destination = isset( $result['destination'] ) ? $result['destination'] : '';
		if ( ! $destination ) {
			return $result;
		}

		$new_destination = trailingslashit( $wp_filesystem->wp_content_dir() . 'themes' ) . $stylesheet;

		if ( $destination !== $new_destination && isset( $wp_filesystem ) ) {
			$wp_filesystem->move( $destination, $new_destination );
			$result['destination'] = $new_destination;
		}

		return $result;
	}

	/* ---------------------------------------------------------------------
	 * Admin helpers (used by the settings view).
	 * ------------------------------------------------------------------- */

	/**
	 * Current version string.
	 *
	 * @return string
	 */
	public static function current_version() {
		return WPRAFFLE_THEME_VERSION;
	}

	/**
	 * Latest available version (from cache), or false.
	 *
	 * @return string|false
	 */
	public static function latest_version() {
		return get_transient( 'wpraffle_theme_latest_version' );
	}

	/**
	 * URL for the manual "Check for updates" action.
	 *
	 * @return string
	 */
	public static function check_url() {
		return wp_nonce_url(
			admin_url( 'themes.php?page=wpraffle-theme-settings&tab=advanced&check_theme_updates=1' ),
			'wpraffle_theme_check_updates'
		);
	}
}
