<?php
/**
 * Static content helpers.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_page_url_map() {
	return array(
		'index.html'              => home_url( '/' ),
		'about-us.html'           => home_url( '/about-us/' ),
		'services.html'           => home_url( '/services/' ),
		'prices.html'             => home_url( '/prices/' ),
		'blog.html'               => home_url( '/blog/' ),
		'reviews.html'            => home_url( '/reviews/' ),
		'faq.html'                => home_url( '/faq/' ),
		'promotions.html'         => home_url( '/promotions/' ),
		'contacts.html'           => home_url( '/contacts/' ),
		'policy.html'             => home_url( '/policy/' ),
	);
}

function wipe_clean_resolve_static_url( $value ) {
	$value = trim( (string) $value );

	if ( '' === $value ) {
		return '';
	}

	$page_url_map = wipe_clean_page_url_map();

	if ( isset( $page_url_map[ $value ] ) ) {
		return $page_url_map[ $value ];
	}

	if ( str_starts_with( $value, '/static/' ) ) {
		return trailingslashit( get_template_directory_uri() ) . ltrim( $value, '/' );
	}

	if ( str_starts_with( $value, 'static/' ) ) {
		return wipe_clean_asset_uri( $value );
	}

	$parsed_url = wp_parse_url( $value );
	$home_url   = wp_parse_url( home_url( '/' ) );

	if ( ! empty( $parsed_url['host'] ) && ! empty( $home_url['host'] ) ) {
		$source_host = strtolower( (string) $parsed_url['host'] );
		$current_host = strtolower( (string) $home_url['host'] );
		$source_port = isset( $parsed_url['port'] ) ? (string) $parsed_url['port'] : '';
		$current_port = isset( $home_url['port'] ) ? (string) $home_url['port'] : '';
		$is_local_source = in_array( $source_host, array( 'localhost', '127.0.0.1' ), true );
		$is_local_current = in_array( $current_host, array( 'localhost', '127.0.0.1' ), true );

		if ( ( $is_local_source || $is_local_current ) && ( $source_host !== $current_host || $source_port !== $current_port ) ) {
			$normalized = rtrim( home_url( '/' ), '/' );
			$path       = isset( $parsed_url['path'] ) ? (string) $parsed_url['path'] : '/';
			$query      = isset( $parsed_url['query'] ) ? '?' . (string) $parsed_url['query'] : '';
			$fragment   = isset( $parsed_url['fragment'] ) ? '#' . (string) $parsed_url['fragment'] : '';

			return $normalized . $path . $query . $fragment;
		}
	}

	return $value;
}
