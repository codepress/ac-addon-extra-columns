<?php

/**
 * Show a notice when add-on dependencies are not met
 *
 * @version 1.1
 */
class ACA_Extra_Columns_Dependencies {

	/**
	 * Holds references to missing dependencies
	 *
	 * @var array
	 */
	protected $missing = array();

	/**
	 * Main file of the add-on
	 *
	 * @var string
	 */
	protected $plugin_file;

	/*
	 *
	 * @param $file string Path to the main plugin file
	 */
	function __construct( $plugin_file ) {
		$this->plugin_file = $plugin_file;

		add_action( 'after_plugin_row_' . plugin_basename( $plugin_file ), array( $this, 'display_notice' ), 11 );
	}

	/**
	 * Get list of missing dependencies
	 *
	 * @return bool
	 */
	public function has_missing() {
		return ! empty( $this->missing );
	}

	/**
	 * Add missing dependency
	 *
	 * @param $label string <a> tag is allowed
	 */
	public function add_missing( $name ) {
		$this->missing[] = $name;
	}

	public function is_acp_active( $version ) {
		if ( ! function_exists( 'acp_is_version_gte' ) || ! acp_is_version_gte( $version ) ) {
			$name = $this->get_html_link( 'https://www.admincolumns.com', 'Admin Column Pro' );
			$name .= ' (' . sprintf( __( 'version %s or later', 'codepress-admin-columns' ), $version ) . ')';

			$this->add_missing( $name );
		}
	}

	/**
	 * Link that performs a search in the WordPress repository
	 *
	 * @param $keywords string
	 * @param $name     string
	 *
	 * @return string
	 */
	public function get_search_link( $keywords, $name ) {
		$url = add_query_arg( array(
			'tab' => 'search',
			's'   => $keywords,
		), admin_url( 'plugin-install.php' ) );

		return $this->get_html_link( $url, $name );
	}

	/**
	 * @param string $url
	 * @param string $label
	 *
	 * @return string
	 */
	public function get_html_link( $url, $label ) {
		return sprintf( '<a href="%s">%s</a>', esc_url( $url ), esc_html( $label ) );
	}

	/**
	 * Show a warning when dependencies are not met
	 *
	 */
	public function display_notice() {
		if ( ! $this->has_missing() ) {
			return;
		}

		// parse missing
		$last_plugin = end( $this->missing );
		$plugin_list = str_replace( ', ' . $last_plugin, __( ' and ', 'codepress-admin-columns' ) . $last_plugin, implode( ', ', $this->missing ) );

		$notice = sprintf( __( 'This add-on requires %s to be installed and activated.', 'codepress-admin-columns' ), $plugin_list );
		$sanitized_notice = wp_kses( $notice, array(
			'a' => array(
				'class' => true,
				'data'  => true,
				'href'  => true,
				'id'    => true,
				'title' => true,
			),
		) );

		?>

		<tr class="plugin-update-tr active">
			<td colspan="3" class="plugin-update colspanchange">
				<div class="update-message notice inline notice-error notice-alt">
					<p><?php echo $sanitized_notice; ?></p>
				</div>
			</td>
		</tr>

		<?php

		add_action( 'admin_footer', array( $this, 'display_notice_css' ) );
	}

	public function display_notice_css() {
		$plugin_basename = plugin_basename( $this->plugin_file );

		?>

		<style>
			.plugins tr[data-plugin='<?php echo $plugin_basename; ?>'] th,
			.plugins tr[data-plugin='<?php echo $plugin_basename; ?>'] td {
				box-shadow: none;
			}
		</style>

		<?php
	}

}