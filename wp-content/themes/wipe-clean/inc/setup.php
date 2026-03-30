<?php
/**
 * Theme setup.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers theme supports and menus.
 *
 * @return void
 */
function wipe_clean_setup() {
	load_theme_textdomain( 'wipe-clean', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'wipe-clean' ),
			'footer'  => esc_html__( 'Footer Menu', 'wipe-clean' ),
		)
	);
}
add_action( 'after_setup_theme', 'wipe_clean_setup' );
