<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_get_services_archive_options_slug' ) ) {
	function wipe_clean_get_services_archive_options_slug() {
		return 'wipe-clean-services-archive';
	}
}

if ( ! function_exists( 'wipe_clean_get_services_archive_settings_url' ) ) {
	function wipe_clean_get_services_archive_settings_url() {
		$slug = wipe_clean_get_services_archive_options_slug();
		$url  = function_exists( 'menu_page_url' ) ? menu_page_url( $slug, false ) : '';

		if ( ! $url ) {
			$url = admin_url( 'admin.php?page=' . $slug );
		}

		return $url;
	}
}

if ( ! function_exists( 'wipe_clean_get_services_archive_raw_rows' ) ) {
	function wipe_clean_get_services_archive_raw_rows() {
		$rows = array();

		if ( function_exists( 'get_field' ) ) {
			$rows = get_field( 'services_page_sections', 'option' );
		}

		return is_array( $rows ) ? $rows : array();
	}
}

if ( ! function_exists( 'wipe_clean_services_archive_merge_section' ) ) {
	function wipe_clean_services_archive_merge_section( $defaults, $row ) {
		if ( ! is_array( $defaults ) ) {
			$defaults = array();
		}

		if ( ! is_array( $row ) ) {
			return $defaults;
		}

		foreach ( $row as $key => $value ) {
			if ( is_array( $value ) && isset( $defaults[ $key ] ) && is_array( $defaults[ $key ] ) && wp_is_numeric_array( $value ) === wp_is_numeric_array( $defaults[ $key ] ) ) {
				$defaults[ $key ] = wipe_clean_services_archive_merge_section( $defaults[ $key ], $value );
			} else {
				$defaults[ $key ] = $value;
			}
		}

		return $defaults;
	}
}

if ( ! function_exists( 'wipe_clean_get_services_archive_layout_order' ) ) {
	function wipe_clean_get_services_archive_layout_order() {
		return array(
			'services_intro',
			'services_benefits',
			'faq',
			'contacts',
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_services_archive_sections' ) ) {
	function wipe_clean_get_services_archive_sections() {
		$defaults = function_exists( 'wipe_clean_get_services_page_default_sections_map' )
			? wipe_clean_get_services_page_default_sections_map()
			: array();

		$rows           = wipe_clean_get_services_archive_raw_rows();
		$rows_by_layout = array();

		foreach ( $rows as $row ) {
			$layout = $row['acf_fc_layout'] ?? '';
			if ( $layout ) {
				$rows_by_layout[ $layout ] = $row;
			}
		}

		$sections       = array();
		$fallback_cards = function_exists( 'wipe_clean_get_services_page_default_service_cards' )
			? wipe_clean_get_services_page_default_service_cards()
			: array();

		foreach ( wipe_clean_get_services_archive_layout_order() as $layout ) {
			$section = $defaults[ $layout ] ?? array( 'acf_fc_layout' => $layout );

			if ( isset( $rows_by_layout[ $layout ] ) ) {
				$section = wipe_clean_services_archive_merge_section( $section, $rows_by_layout[ $layout ] );
			}

			if ( 'services_intro' === $layout ) {
				$cards = function_exists( 'wipe_clean_get_services_page_cpt_service_cards' )
					? wipe_clean_get_services_page_cpt_service_cards( $fallback_cards )
					: $fallback_cards;

				$section['service_cards'] = $cards;
				$section['cards']         = $cards;
				$section['hero_primary_action']     = wipe_clean_force_link_url( $section['hero_primary_action'] ?? array(), '#popup-order-service' );
				$section['hero_secondary_action']   = wipe_clean_force_link_url( $section['hero_secondary_action'] ?? array(), '#popup-question' );
				$section['footer_primary_action']   = wipe_clean_force_link_url( $section['footer_primary_action'] ?? array(), '#popup-calc' );
				$section['footer_secondary_action'] = wipe_clean_force_link_url( $section['footer_secondary_action'] ?? array(), '#popup-question' );
			}

			if ( 'services_benefits' === $layout ) {
				$section['offer_button'] = wipe_clean_force_link_url( $section['offer_button'] ?? array(), '#popup-order-service' );
			}

			$sections[] = $section;
		}

		return $sections;
	}
}

if ( ! function_exists( 'wipe_clean_render_services_archive_sections' ) ) {
	function wipe_clean_render_services_archive_sections() {
		$sections = wipe_clean_get_services_archive_sections();

		foreach ( $sections as $section ) {
			$layout = $section['acf_fc_layout'] ?? '';

			if ( ! $layout ) {
				continue;
			}

			$slug = str_replace( '_', '-', $layout );

			if ( locate_template( "template-parts/section/services-page/{$slug}.php" ) ) {
				get_template_part( "template-parts/section/services-page/{$slug}", null, array( 'section' => $section ) );
				continue;
			}

			if ( locate_template( "template-parts/section/front-page/{$slug}.php" ) ) {
				get_template_part( "template-parts/section/front-page/{$slug}", null, array( 'section' => $section ) );
				continue;
			}

			if ( locate_template( "template-parts/section/{$slug}.php" ) ) {
				get_template_part( "template-parts/section/{$slug}", null, array( 'section' => $section ) );
			}
		}
	}
}
