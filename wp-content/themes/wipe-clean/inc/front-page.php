<?php
/**
 * Front-page section registry and rendering.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_get_front_page_layout_order() {
	return array(
		'home_hero',
		'services_preview',
		'home_wave_group',
		'company_preview',
		'reviews_preview',
		'gallery_preview',
		'faq',
		'contacts',
	);
}

function wipe_clean_get_front_page_layout_map() {
	return array(
		'home_hero'       => 'home-hero',
		'services_preview' => 'services-preview',
		'home_wave_group' => 'home-wave-group',
		'company_preview' => 'company-preview',
		'reviews_preview' => 'reviews-preview',
		'gallery_preview' => 'gallery-preview',
		'faq'             => 'faq',
		'contacts'        => 'contacts',
	);
}

function wipe_clean_array_is_list_compatible( $value ) {
	if ( ! is_array( $value ) ) {
		return false;
	}

	if ( function_exists( 'array_is_list' ) ) {
		return array_is_list( $value );
	}

	return array_values( $value ) === $value;
}

function wipe_clean_has_meaningful_section_value( $value ) {
	if ( is_string( $value ) ) {
		return '' !== trim( $value );
	}

	if ( is_array( $value ) ) {
		return ! empty( $value );
	}

	return null !== $value;
}

function wipe_clean_merge_section_with_fallback( $defaults, $section ) {
	if ( is_array( $defaults ) ) {
		if ( ! is_array( $section ) || empty( $section ) ) {
			return $defaults;
		}

		if ( wipe_clean_array_is_list_compatible( $defaults ) ) {
			return ! empty( $section ) ? array_values( $section ) : $defaults;
		}

		$merged = $defaults;

		foreach ( $section as $key => $value ) {
			if ( array_key_exists( $key, $defaults ) ) {
				$merged[ $key ] = wipe_clean_merge_section_with_fallback( $defaults[ $key ], $value );
				continue;
			}

			if ( wipe_clean_has_meaningful_section_value( $value ) ) {
				$merged[ $key ] = $value;
			}
		}

		return $merged;
	}

	return wipe_clean_has_meaningful_section_value( $section ) ? $section : $defaults;
}

function wipe_clean_get_front_page_sections() {
	$normalized_sections = array();
	$acf_sections        = array();

	if ( function_exists( 'get_field' ) ) {
		$sections = get_field( 'front_page_sections', get_queried_object_id() );

		if ( is_array( $sections ) && ! empty( $sections ) ) {
			foreach ( $sections as $section ) {
				$layout = $section['acf_fc_layout'] ?? '';

				if ( ! $layout ) {
					continue;
				}

				$acf_sections[ $layout ] = $section;
			}
		}
	}

	foreach ( wipe_clean_get_front_page_layout_order() as $layout ) {
		$defaults              = wipe_clean_get_front_page_section_defaults( $layout );
		$normalized_sections[] = wipe_clean_merge_section_with_fallback( $defaults, $acf_sections[ $layout ] ?? array() );
	}

	return $normalized_sections;
}

function wipe_clean_render_front_page_sections() {
	$layout_map = wipe_clean_get_front_page_layout_map();

	foreach ( wipe_clean_get_front_page_sections() as $section ) {
		$layout = $section['acf_fc_layout'] ?? '';

		if ( empty( $layout ) || empty( $layout_map[ $layout ] ) ) {
			continue;
		}

		get_template_part(
			'template-parts/section/front-page/' . $layout_map[ $layout ],
			null,
			array(
				'section' => is_array( $section ) ? $section : array(),
			)
		);
	}
}
