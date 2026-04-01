<?php
require_once __DIR__ . '/data/services-page.php';
require_once __DIR__ . '/services-page-cpt.php';
require_once __DIR__ . '/services-page.php';
require_once __DIR__ . '/service-routing.php';
require_once __DIR__ . '/service-single.php';
require_once __DIR__ . '/services-archive.php';
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
			$defaults_list = array_values( $defaults );
			$section_list  = array_values( $section );

			if ( empty( $section_list ) ) {
				return $defaults_list;
			}

			$merged_list = array();

			foreach ( $section_list as $index => $value ) {
				if ( array_key_exists( $index, $defaults_list ) ) {
					$merged_list[] = wipe_clean_merge_section_with_fallback( $defaults_list[ $index ], $value );
					continue;
				}

				$merged_list[] = $value;
			}

			return $merged_list;
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
		$defaults = wipe_clean_get_front_page_section_defaults( $layout );
		$section  = wipe_clean_merge_section_with_fallback( $defaults, $acf_sections[ $layout ] ?? array() );

		if ( 'services_preview' === $layout ) {
			$section['secondary_action'] = wipe_clean_force_link_url( $section['secondary_action'] ?? array(), '#popup-calc' );
		}

		if ( 'home_wave_group' === $layout ) {
			$price_preview = isset( $section['price_preview'] ) && is_array( $section['price_preview'] )
				? $section['price_preview']
				: array();

			$price_preview['secondary_button'] = wipe_clean_force_link_url( $price_preview['secondary_button'] ?? array(), '#popup-order-service' );
			$section['price_preview']          = $price_preview;
		}

		if ( 'reviews_preview' === $layout ) {
			$section['primary_action']   = wipe_clean_force_link_url( $section['primary_action'] ?? array(), home_url( '/reviews/' ) );
			$section['secondary_action'] = wipe_clean_force_link_url( $section['secondary_action'] ?? array(), '#popup-review' );
		}

		$normalized_sections[] = $section;
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
