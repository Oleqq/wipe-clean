<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_is_services_page_post( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : (int) get_queried_object_id();

	if ( ! $post_id || 'page' !== get_post_type( $post_id ) ) {
		return false;
	}

	return 'template-services-page.php' === get_page_template_slug( $post_id );
}

function wipe_clean_get_services_page_layout_order() {
	return array(
		'services_intro',
		'services_benefits',
		'faq',
		'contacts',
	);
}

function wipe_clean_get_services_page_sections( $post_id = 0 ) {
	$post_id             = $post_id ? (int) $post_id : (int) get_queried_object_id();
	$normalized_sections = array();
	$acf_sections        = array();

	if ( function_exists( 'get_field' ) ) {
		$sections = get_field( 'services_page_sections', $post_id );

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

	foreach ( wipe_clean_get_services_page_layout_order() as $layout ) {
		$defaults              = wipe_clean_get_services_page_section_defaults( $layout );
		$normalized_sections[] = wipe_clean_merge_section_with_fallback( $defaults, $acf_sections[ $layout ] ?? array() );
	}

	foreach ( $normalized_sections as &$section ) {
		$layout = (string) ( $section['acf_fc_layout'] ?? '' );

		if ( 'services_intro' === $layout ) {
			$section['hero_primary_action']     = wipe_clean_force_link_url( $section['hero_primary_action'] ?? array(), '#popup-order-service' );
			$section['hero_secondary_action']   = wipe_clean_force_link_url( $section['hero_secondary_action'] ?? array(), '#popup-question' );
			$section['footer_primary_action']   = wipe_clean_force_link_url( $section['footer_primary_action'] ?? array(), '#popup-calc' );
			$section['footer_secondary_action'] = wipe_clean_force_link_url( $section['footer_secondary_action'] ?? array(), '#popup-question' );
		}

		if ( 'services_benefits' === $layout ) {
			$section['offer_button'] = wipe_clean_force_link_url( $section['offer_button'] ?? array(), '#popup-order-service' );
		}
	}
	unset( $section );

	return $normalized_sections;
}

function wipe_clean_render_services_page_sections() {
	foreach ( wipe_clean_get_services_page_sections() as $section ) {
		$layout = $section['acf_fc_layout'] ?? '';

		if ( ! $layout ) {
			continue;
		}

		switch ( $layout ) {
			case 'services_intro':
				get_template_part( 'template-parts/section/services-page/services-intro', null, array( 'section' => $section ) );
				break;

			case 'services_benefits':
				get_template_part( 'template-parts/section/services-page/services-benefits', null, array( 'section' => $section ) );
				break;

			case 'faq':
				get_template_part( 'template-parts/section/front-page/faq', null, array( 'section' => $section ) );
				break;

			case 'contacts':
				get_template_part( 'template-parts/section/front-page/contacts', null, array( 'section' => $section ) );
				break;
		}
	}
}
