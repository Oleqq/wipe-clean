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
	'/inc/data/blog.php',
	'/inc/data/prices-page.php',
	'/inc/data/about-page.php',
	'/inc/data/faq-page.php',
	'/inc/data/contacts-page.php',
	'/inc/data/error-page.php',
	'/inc/front-page.php',
	'/inc/front-page-cpt.php',
	'/inc/blog.php',
	'/inc/reviews.php',
	'/inc/promotions.php',
	'/inc/prices-page.php',
	'/inc/about-page.php',
	'/inc/faq-page.php',
	'/inc/contacts-page.php',
	'/inc/error-page.php',
	'/inc/document-page.php',
	'/inc/site-shell.php',
	'/inc/acf.php',
	'/inc/acf-cpt.php',
	'/inc/acf-front-page.php',
	'/inc/acf-services-page.php',
	'/inc/acf-blog.php',
	'/inc/acf-reviews.php',
	'/inc/acf-promotions.php',
	'/inc/acf-prices-page.php',
	'/inc/acf-about-page.php',
	'/inc/acf-faq-page.php',
	'/inc/acf-contacts-page.php',
	'/inc/acf-error-page.php',
	'/inc/acf-site-shell.php',
	'/inc/services-page-seeding.php',
	'/inc/blog-seeding.php',
	'/inc/reviews-seeding.php',
	'/inc/promotions-seeding.php',
	'/inc/front-page-seeding.php',
	'/inc/service-single-seeding.php',
	'/inc/prices-page-seeding.php',
	'/inc/about-page-seeding.php',
	'/inc/faq-page-seeding.php',
	'/inc/contacts-page-seeding.php',
	'/inc/error-page-seeding.php',
	'/inc/document-page-seeding.php',
	'/inc/site-shell-seeding.php',
	'/inc/forms.php',
	'/inc/editor-shortcuts.php',
	'/inc/forms-admin.php',
	'/inc/admin-section-preview.php',
);

foreach ( $wipe_clean_includes as $wipe_clean_include ) {
	$wipe_clean_file = get_template_directory() . $wipe_clean_include;

	if ( file_exists( $wipe_clean_file ) ) {
		require_once $wipe_clean_file;
	}
}
