<?php

/**
 * Show a notice when plugin dependencies are not met
 *
 * @version 1.4.1
 */
final class ACA_Extra_Columns_Dependencies {

	/**
	 * Missing dependency messages
	 *
	 * @var string[]
	 */
	private $messages;

	/**
	 * Basename of this plugin
	 *
	 * @var string
	 */
	private $basename;

	/**
	 * @param string $basename Plugin basename
	 */
	public function __construct( $basename ) {
		$this->messages = array();
		$this->basename = $basename;
	}

	/**
	 * Register hooks
	 */
	private function register() {
		add_action( 'after_plugin_row_' . $this->basename, array( $this, 'display_notice' ), 5 );
		add_action( 'admin_head', array( $this, 'display_notice_css' ) );
	}

	/**
	 * Add missing dependency
	 *
	 * @param string $message
	 */
	public function add_missing( $message ) {
		// Register on first missing dependency
		if ( ! $this->messages ) {
			$this->register();
		}

		$this->messages[] = $message;
	}

	/**
	 * @return bool
	 */
	public function has_missing() {
		return ! empty( $this->messages );
	}

	/**
	 * Add missing dependency
	 *
	 * @param string $plugin
	 * @param string $url
	 * @param string $version
	 */
	public function add_missing_plugin( $plugin, $url = null, $version = null ) {
		$plugin = esc_html( $plugin );

		if ( $url ) {
			$plugin = sprintf( '<a href="%s">%s</a>', esc_url( $url ), $plugin );
		}

		if ( $version ) {
			$plugin .= ' ' . sprintf( __( 'version %s+', 'codepress-admin-columns' ), esc_html( $version ) );
		}

		$message = sprintf( __( '%s needs to be installed and activated.', 'codepress-admin-columns' ), $plugin );

		$this->add_missing( $message );
	}

	/**
	 * Check if Admin Columns Pro is installed and meets the minimum required version
	 *
	 * @param string $version
	 *
	 * @return bool
	 */
	public function check_acp( $version ) {
		$version = apply_filters( 'ac/dependencies/acp_version_gte', $version, $this->basename );

		if ( function_exists( 'ACP' ) && method_exists( ACP(), 'is_version_gte' ) && ACP()->is_version_gte( $version ) ) {
			return true;
		}

		$this->add_missing_plugin( 'Admin Column Pro', 'https://www.admincolumns.com', $version );

		return false;
	}

	/**
	 * Check current PHP version
	 *
	 * @param string $version
	 *
	 * @return bool
	 */
	public function check_php_version( $version ) {
		if ( version_compare( PHP_VERSION, $version, '>=' ) ) {
			return true;
		}

		$documentation_url = 'https://www.admincolumns.com/documentation/getting-started/requirements/';

		$parts[] = sprintf( __( 'PHP %s+ is required.', 'codepress-admin-columns' ), $version );
		$parts[] = sprintf( __( 'Your server currently runs PHP %s.', 'codepress-admin-columns' ), PHP_VERSION );
		$parts[] = sprintf( __( '<a href="%s" target="_blank">Learn more about requirements.</a>', 'codepress-admin-columns' ), esc_url( $documentation_url ) );

		$this->add_missing( implode( ' ', $parts ) );

		return false;
	}

	/**
	 * URL that performs a search in the WordPress repository
	 *
	 * @param string $keywords
	 *
	 * @return string
	 */
	public function get_search_url( $keywords ) {
		$url = add_query_arg( array(
			'tab' => 'search',
			's'   => $keywords,
		), admin_url( 'plugin-install.php' ) );

		return $url;
	}

	/**
	 * Show a warning when dependencies are not met
	 */
	public function display_notice() {
		$intro = __( "This plugin can't load because", 'codepress-admin-columns' );

		?>

		<tr class="plugin-update-tr active">
			<td colspan="3" class="plugin-update colspanchange">
				<div class="update-message notice inline notice-error notice-alt">
					<?php if ( count( $this->messages ) > 1 )  : ?>
						<p>
							<?php echo $intro . ':' ?>
						</p>

						<ul>
							<?php foreach ( $this->messages as $message ) : ?>
								<li><?php echo $message; ?></li>
							<?php endforeach; ?>
						</ul>
					<?php else : ?>
						<p>
							<?php echo $intro . ' ' . current( $this->messages ); ?>
						</p>
					<?php endif; ?>
				</div>
			</td>
		</tr>

		<?php
	}

	/**
	 * Load additional CSS for the warning
	 */
	public function display_notice_css() {
		?>

		<style>
			.plugins tr[data-plugin='<?php echo $this->basename; ?>'] th,
			.plugins tr[data-plugin='<?php echo $this->basename; ?>'] td {
				box-shadow: none;
			}
		</style>

		<?php
	}

}