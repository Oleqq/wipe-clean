<?php
/**
 * Theme bootstrap for Wipe Clean.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'WIPE_CLEAN_VERSION' ) ) {
	$wipe_clean_theme = wp_get_theme( 'wipe-clean' );
	define( 'WIPE_CLEAN_VERSION', $wipe_clean_theme->get( 'Version' ) ?: '1.0.0' );
}

$wipe_clean_includes = array(
	'/inc/setup.php',
	'/inc/assets.php',
	'/inc/static-content.php',
	'/inc/section-helpers.php',
	'/inc/cpt.php',
	'/inc/data/front-page.php',
	'/inc/data/front-page-cpt.php',
	'/inc/front-page.php',
	'/inc/front-page-cpt.php',
	'/inc/acf.php',
	'/inc/acf-cpt.php',
	'/inc/acf-front-page.php',
	'/inc/front-page-seeding.php',
	'/inc/editor-shortcuts.php',
	'/inc/admin-section-preview.php',
);

foreach ( $wipe_clean_includes as $wipe_clean_include ) {
	$wipe_clean_file = get_template_directory() . $wipe_clean_include;

	if ( file_exists( $wipe_clean_file ) ) {
		require_once $wipe_clean_file;
	}
}
