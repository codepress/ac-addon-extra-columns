<?php

namespace ACA\ExtraColumns\Admin\Page;

use AC;

class ExperimentalColumns implements AC\Renderable {

	const SETTINGS_NAME = 'ac_active_extra_columns';

	const SETTINGS_GROUP = 'ac-general-extra-columns';

	public function render() {
		ob_start();

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
                                    <label for="show_edit_button_<?= esc_attr( sanitize_key( $type ) ); ?>">
                                        <input id="show_edit_button_<?= esc_attr( sanitize_key( $type ) ); ?>" name="<?= esc_attr( self::SETTINGS_NAME ); ?>[]" type="checkbox" value="<?= $type; ?>" <?php checked( in_array( $type, (array) get_option( self::SETTINGS_NAME ) ) ); ?>>
										<?= esc_html( $label ); ?>
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

		return ob_get_clean();
	}

}