<?php
/**
 * Asset helpers and enqueues.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_asset_uri( $path = '' ) {
	$path = ltrim( $path, '/' );

	if ( '' === $path ) {
		return get_template_directory_uri();
	}

	return get_template_directory_uri() . '/' . $path;
}

function wipe_clean_asset_path( $path = '' ) {
	$path = ltrim( $path, '/' );

	if ( '' === $path ) {
		return get_template_directory();
	}

	return get_template_directory() . '/' . $path;
}

function wipe_clean_get_preferred_asset_path( $path ) {
	$path = ltrim( $path, '/' );

	if ( preg_match( '/\.min\.(css|js)$/', $path ) ) {
		$minified_file_path = wipe_clean_asset_path( $path );
		$source_path        = preg_replace( '/\.min\.(css|js)$/', '.$1', $path );
		$source_file_path   = wipe_clean_asset_path( $source_path );

		clearstatcache( true, $minified_file_path );
		clearstatcache( true, $source_file_path );

		if ( file_exists( $minified_file_path ) ) {
			if ( ! $source_path || ! file_exists( $source_file_path ) || filemtime( $minified_file_path ) > filemtime( $source_file_path ) ) {
				return $path;
			}
		}

		return $source_path;
	}

	if ( preg_match( '/\.(css|js)$/', $path, $matches ) ) {
		$minified_path      = preg_replace( '/\.' . preg_quote( $matches[1], '/' ) . '$/', '.min.' . $matches[1], $path );
		$source_file_path   = wipe_clean_asset_path( $path );
		$minified_file_path = wipe_clean_asset_path( $minified_path );

		clearstatcache( true, $source_file_path );
		clearstatcache( true, $minified_file_path );

		if ( $minified_path && file_exists( $minified_file_path ) ) {
			if ( ! file_exists( $source_file_path ) || filemtime( $minified_file_path ) > filemtime( $source_file_path ) ) {
				return $minified_path;
			}
		}
	}

	return $path;
}

function wipe_clean_asset_version( $path ) {
	$file_path = wipe_clean_asset_path( $path );

	clearstatcache( true, $file_path );

	if ( file_exists( $file_path ) ) {
		return (string) filemtime( $file_path );
	}

	if ( preg_match( '/\.min\.(css|js)$/', $path ) ) {
		$fallback_path      = preg_replace( '/\.min\.(css|js)$/', '.$1', $path );
		$fallback_file_path = wipe_clean_asset_path( $fallback_path );

		clearstatcache( true, $fallback_file_path );

		if ( $fallback_path && file_exists( $fallback_file_path ) ) {
			return (string) filemtime( $fallback_file_path );
		}
	}

	return WIPE_CLEAN_VERSION;
}

function wipe_clean_enqueue_assets() {
	$main_style = wipe_clean_get_preferred_asset_path( 'static/css/style.css' );
	$main_app   = wipe_clean_get_preferred_asset_path( 'static/js/script.js' );

	wp_enqueue_style(
		'wipe-clean-fonts',
		'https://fonts.googleapis.com/css2?family=Golos+Text:wght@400..900&family=Manrope:wght@200..800&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'wipe-clean-fancybox',
		wipe_clean_asset_uri( 'static/css/vendor/fancybox.css' ),
		array(),
		wipe_clean_asset_version( 'static/css/vendor/fancybox.css' )
	);

	wp_enqueue_style(
		'wipe-clean-swiper',
		wipe_clean_asset_uri( 'static/css/vendor/swiper-bundle.css' ),
		array(),
		wipe_clean_asset_version( 'static/css/vendor/swiper-bundle.css' )
	);

	wp_enqueue_style(
		'wipe-clean-app',
		wipe_clean_asset_uri( $main_style ),
		array( 'wipe-clean-fonts', 'wipe-clean-fancybox', 'wipe-clean-swiper' ),
		wipe_clean_asset_version( $main_style )
	);

	wp_enqueue_script(
		'wipe-clean-fancybox',
		wipe_clean_asset_uri( 'static/js/vendor/fancybox.umd.js' ),
		array(),
		wipe_clean_asset_version( 'static/js/vendor/fancybox.umd.js' ),
		true
	);

	wp_enqueue_script(
		'wipe-clean-swiper',
		wipe_clean_asset_uri( 'static/js/vendor/swiper-bundle.js' ),
		array(),
		wipe_clean_asset_version( 'static/js/vendor/swiper-bundle.js' ),
		true
	);

	wp_enqueue_script(
		'wipe-clean-app',
		wipe_clean_asset_uri( $main_app ),
		array( 'wipe-clean-fancybox', 'wipe-clean-swiper' ),
		wipe_clean_asset_version( $main_app ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'wipe_clean_enqueue_assets' );

function wipe_clean_filter_script_tag( $tag, $handle, $src ) {
	if ( 'wipe-clean-app' !== $handle ) {
		return $tag;
	}

	return sprintf(
		'<script type="module" src="%1$s" id="%2$s-js"></script>',
		esc_url( $src ),
		esc_attr( $handle )
	);
}
add_filter( 'script_loader_tag', 'wipe_clean_filter_script_tag', 10, 3 );

function wipe_clean_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' !== $relation_type ) {
		return $urls;
	}

	$urls[] = 'https://fonts.googleapis.com';
	$urls[] = array(
		'href'        => 'https://fonts.gstatic.com',
		'crossorigin' => 'anonymous',
	);

	return $urls;
}
add_filter( 'wp_resource_hints', 'wipe_clean_resource_hints', 10, 2 );

function wipe_clean_preload_resources( $preloads ) {
	$preloads[] = array(
		'href'        => wipe_clean_asset_uri( 'static/fonts/Involve-Bold.ttf' ),
		'as'          => 'font',
		'type'        => 'font/ttf',
		'crossorigin' => 'anonymous',
	);

	return $preloads;
}
add_filter( 'wp_preload_resources', 'wipe_clean_preload_resources' );
