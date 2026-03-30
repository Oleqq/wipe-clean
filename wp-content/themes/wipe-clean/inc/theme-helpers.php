<?php
/**
 * Theme helper functions.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns a theme-relative asset URI.
 *
 * @param string $path Relative asset path.
 * @return string
 */
function wipe_clean_asset_uri( $path = '' ) {
	$path = ltrim( $path, '/' );

	if ( '' === $path ) {
		return get_template_directory_uri();
	}

	return get_template_directory_uri() . '/' . $path;
}

/**
 * Returns a theme-relative asset path.
 *
 * @param string $path Relative asset path.
 * @return string
 */
function wipe_clean_asset_path( $path = '' ) {
	$path = ltrim( $path, '/' );

	if ( '' === $path ) {
		return get_template_directory();
	}

	return get_template_directory() . '/' . $path;
}

/**
 * Returns a file-based version for cache busting.
 *
 * @param string $path Relative asset path.
 * @return string
 */
function wipe_clean_asset_version( $path ) {
	$file_path = wipe_clean_asset_path( $path );

	if ( file_exists( $file_path ) ) {
		return (string) filemtime( $file_path );
	}

	return WIPE_CLEAN_VERSION;
}

/**
 * Returns a page URL map for static-to-WP replacements.
 *
 * @return array<string, string>
 */
function wipe_clean_page_url_map() {
	return array(
		'index.html'              => home_url( '/' ),
		'about-us.html'           => home_url( '/about-us/' ),
		'services.html'           => home_url( '/services/' ),
		'apartment-cleaning.html' => home_url( '/apartment-cleaning/' ),
		'prices.html'             => home_url( '/prices/' ),
		'blog.html'               => home_url( '/blog/' ),
		'single-blog.html'        => home_url( '/single-blog/' ),
		'reviews.html'            => home_url( '/reviews/' ),
		'faq.html'                => home_url( '/faq/' ),
		'promotions.html'         => home_url( '/promotions/' ),
		'contacts.html'           => home_url( '/contacts/' ),
		'policy.html'             => home_url( '/policy/' ),
		'popups.html'             => home_url( '/popups/' ),
		'404.html'                => home_url( '/404/' ),
	);
}

/**
 * Transforms a static HTML fragment into theme-aware markup.
 *
 * @param string $markup Static fragment markup.
 * @return string
 */
function wipe_clean_transform_static_markup( $markup ) {
	$asset_base = trailingslashit( get_template_directory_uri() ) . 'static/';
	$markup     = str_replace( '/static/', $asset_base, $markup );

	$link_replacements = array();

	foreach ( wipe_clean_page_url_map() as $static_path => $wp_url ) {
		$link_replacements[ 'href="' . $static_path . '"' ] = 'href="' . esc_url( $wp_url ) . '"';
	}

	return strtr( $markup, $link_replacements );
}

/**
 * Returns a transformed static template fragment.
 *
 * @param string $slug Relative static fragment slug.
 * @return string
 */
function wipe_clean_get_static_markup( $slug ) {
	$file_path = wipe_clean_asset_path( 'template-parts/static/' . trim( $slug, '/' ) . '.php' );

	if ( ! file_exists( $file_path ) ) {
		return '';
	}

	ob_start();
	include $file_path;
	$markup = ob_get_clean();

	if ( false === $markup || '' === $markup ) {
		return '';
	}

	return wipe_clean_transform_static_markup( $markup );
}

/**
 * Echoes a transformed static template fragment.
 *
 * @param string $slug Relative static fragment slug.
 * @return void
 */
function wipe_clean_render_static_markup( $slug ) {
	echo wipe_clean_get_static_markup( $slug ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
