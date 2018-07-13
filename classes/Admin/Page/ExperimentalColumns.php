<?php
namespace ACA\ExtraColumns\Admin\Page;

use AC;

class ExperimentalColumns extends AC\Admin\Page {

	const SETTINGS_NAME = 'ac_active_extra_columns';

	const SETTINGS_GROUP = 'ac-general-extra-columns';

	public function __construct() {
		$this
			->set_slug( 'experimental-columns' )
			->set_label( __( 'Experimental Columns', 'codepress-admin-columns' ) );

		register_setting( self::SETTINGS_GROUP, self::SETTINGS_NAME );

	}

	public function handle_store_active_columns() {
		if ( ! $this->is_current_screen() ) {
			return;
		}
	}

	public function display() {
		$available_columns = ac_addon_extra_columns()->get_available_extra_columns();
		?>
		<table class="form-table ac-form-table">
			<tbody>
			<tr>
				<th scope="row" valign="top">
					<h2>Columns</h2>
					<p>Select the columns you want to activate.</p>
				</th>
				<td>
					<div class="ac-export">

						<form method="post" action="options.php">
							<?php settings_fields( self::SETTINGS_GROUP ); ?>

							<?php foreach ( $available_columns as $type => $label ): ?>

								<p>
									<label for="show_edit_button">
										<input name="<?php echo esc_attr( self::SETTINGS_NAME ); ?>[]" type="checkbox" value="<?php echo $type; ?>" <?php checked( in_array( $type, (array) get_option( self::SETTINGS_NAME ) ) ); ?>>
										<?php echo esc_html( $label ); ?>
									</label>
								</p>

							<?php endforeach; ?>

							<div class="submit">
								<input type="submit" class="button button-primary" name="ac-export-php" value="Save columns">
							</div>

						</form>
					</div>
				</td>
			</tr>
			</tbody>
		</table>
		<?php

	}

}