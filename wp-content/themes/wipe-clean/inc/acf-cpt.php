<?php
/**
 * ACF bootstrap for section-related custom post types.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wipe_clean_cpt_acf_files = array(
	__DIR__ . '/acf/cpt/service.php',
	__DIR__ . '/acf/cpt/review.php',
);

foreach ( $wipe_clean_cpt_acf_files as $wipe_clean_cpt_acf_file ) {
	if ( file_exists( $wipe_clean_cpt_acf_file ) ) {
		require_once $wipe_clean_cpt_acf_file;
	}
}
