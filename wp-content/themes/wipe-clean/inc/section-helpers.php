<?php
/**
 * Section rendering helpers.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_theme_image( $path, $alt = '', $width = 0, $height = 0 ) {
	return array(
		'path'   => ltrim( $path, '/' ),
		'alt'    => $alt,
		'width'  => $width,
		'height' => $height,
	);
}

function wipe_clean_theme_link( $url, $title, $target = '' ) {
	return array(
		'url'    => $url,
		'title'  => $title,
		'target' => $target,
	);
}

function wipe_clean_resolve_link( $link ) {
	$normalized = array(
		'url'    => '',
		'title'  => '',
		'target' => '',
	);

	if ( empty( $link ) ) {
		return $normalized;
	}

	if ( is_string( $link ) ) {
		$normalized['url'] = wipe_clean_resolve_static_url( $link );
		return $normalized;
	}

	if ( is_array( $link ) ) {
		$normalized['url']    = wipe_clean_resolve_static_url( (string) ( $link['url'] ?? '' ) );
		$normalized['title']  = (string) ( $link['title'] ?? '' );
		$normalized['target'] = (string) ( $link['target'] ?? '' );
	}

	return $normalized;
}

function wipe_clean_force_link_url( $link, $url ) {
	$link        = wipe_clean_resolve_link( $link );
	$link['url'] = (string) $url;

	return $link;
}

function wipe_clean_render_media( $media, $attributes = array() ) {
	if ( empty( $media ) ) {
		return '';
	}

	$attributes = array_filter(
		array_merge(
			array(
				'alt'      => '',
				'loading'  => 'lazy',
				'decoding' => 'async',
			),
			$attributes
		),
		static function ( $value ) {
			return '' !== $value && null !== $value;
		}
	);

	if ( is_numeric( $media ) ) {
		return wp_get_attachment_image( (int) $media, 'full', false, $attributes );
	}

	if ( is_array( $media ) && ! empty( $media['ID'] ) ) {
		return wp_get_attachment_image( (int) $media['ID'], 'full', false, $attributes );
	}

	$url = '';
	$alt = '';

	if ( is_array( $media ) ) {
		if ( ! empty( $media['url'] ) ) {
			$url = wipe_clean_resolve_static_url( (string) $media['url'] );
		} elseif ( ! empty( $media['path'] ) ) {
			$url = wipe_clean_asset_uri( (string) $media['path'] );
		}

		$alt = (string) ( $media['alt'] ?? '' );

		if ( empty( $attributes['width'] ) && ! empty( $media['width'] ) ) {
			$attributes['width'] = (int) $media['width'];
		}

		if ( empty( $attributes['height'] ) && ! empty( $media['height'] ) ) {
			$attributes['height'] = (int) $media['height'];
		}
	} elseif ( is_string( $media ) ) {
		$url = wipe_clean_resolve_static_url( $media );
	}

	if ( '' === $url ) {
		return '';
	}

	$attribute_alt      = array_key_exists( 'alt', $attributes ) ? (string) $attributes['alt'] : '';
	$attributes['src']  = $url;
	$attributes['alt']  = '' !== $attribute_alt ? $attribute_alt : $alt;

	$parts = array();

	foreach ( $attributes as $name => $value ) {
		$parts[] = sprintf( '%1$s="%2$s"', esc_attr( $name ), esc_attr( (string) $value ) );
	}

	return '<img ' . implode( ' ', $parts ) . '>';
}

function wipe_clean_allowed_inline_html() {
	return array(
		'br'     => array(),
		'span'   => array(
			'class' => true,
		),
		'strong' => array(),
		'sup'    => array(),
	);
}

function wipe_clean_format_text( $text ) {
	$text = trim( (string) $text );

	if ( '' === $text ) {
		return '';
	}

	return wpautop( esc_html( $text ) );
}

function wipe_clean_format_rich_text( $text ) {
	$text = trim( (string) $text );

	if ( '' === trim( wp_strip_all_tags( $text ) ) ) {
		return '';
	}

	if ( $text !== wp_strip_all_tags( $text ) ) {
		return wp_kses_post( $text );
	}

	return wpautop( esc_html( $text ) );
}
