<?php
function ac_addon_extra_columns() {
	return new \ACA\ExtraColumns\ExtraColumns( ACA_EXTRA_COLUMNS_FILE );
}

AC\Autoloader::instance()->register_prefix( 'ACA\ExtraColumns', plugin_dir_path( ACA_EXTRA_COLUMNS_FILE ) . 'classes/' );

$addon = new ACA\ExtraColumns\ExtraColumns( ACA_EXTRA_COLUMNS_FILE );
$addon->register();