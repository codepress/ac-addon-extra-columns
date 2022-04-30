<?php

namespace ACA\ExtraColumns\Admin\Page;

use AC;
use AC\Admin\RenderableHead;
use AC\Renderable;
use ACA\ExtraColumns\Controller\SaveColumns;
use ACA\ExtraColumns\Option\ActiveColumns;

class ExperimentalColumns implements Renderable, RenderableHead {

	const SETTINGS_NAME = 'ac_active_extra_columns';

	const SETTINGS_GROUP = 'ac-general-extra-columns';

	/**
	 * @var ActiveColumns
	 */
	private $option;

	public function __construct( ActiveColumns $option ) {
		$this->option = $option;
	}

	public function render_head() {
		$setting = ( new AC\Admin\MenuFactory( admin_url( 'options-general.php' ), AC()->get_location() ) )->create( self::SETTINGS_NAME );

		echo ( new AC\Admin\View\Menu( $setting ) )->render();
	}

	public function render() {
		ob_start();

		$request = new AC\Request();
		$active_columns = $this->option->get();
		$available_columns = ac_addon_extra_columns()->get_available_extra_columns();
		?>
		<section class="ac-settings-box">
			<h2 class="ac-lined-header">Toggle Extra Columns</h2>

			<form method="post">
				<input type="hidden" name="page" value="<?= $request->get( 'page' ) ?>">
				<input type="hidden" name="tab" value="<?= $request->get( 'tab' ) ?>">
				<input type="hidden" value="<?= SaveColumns::ACTION ?>" name="ec_action">

				<?php foreach ( $available_columns as $type => $label ): ?>

					<p>
						<label for="show_edit_button_<?= esc_attr( sanitize_key( $type ) ); ?>">
							<input id="show_edit_button_<?= esc_attr( sanitize_key( $type ) ); ?>" name="<?= esc_attr( self::SETTINGS_NAME ); ?>[]" type="checkbox" value="<?= $type; ?>" <?php checked( in_array( $type, $active_columns ) ); ?>>
							<?= esc_html( $label ); ?>
						</label>
					</p>

				<?php endforeach; ?>

				<div class="submit">
					<input type="submit" class="button button-primary" value="Save columns">
				</div>

			</form>
		</section>
		<?php

		return ob_get_clean();
	}

}