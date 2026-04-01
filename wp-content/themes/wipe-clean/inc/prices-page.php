<?php
/**
 * Prices page rendering.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_is_prices_page_post' ) ) {
	function wipe_clean_is_prices_page_post( $post_id = 0 ) {
		$post_id = $post_id ? (int) $post_id : (int) get_queried_object_id();

		if ( ! $post_id || 'page' !== get_post_type( $post_id ) ) {
			return false;
		}

		$template_slug = (string) get_page_template_slug( $post_id );
		$post_slug     = (string) get_post_field( 'post_name', $post_id );

		return 'template-prices-page.php' === $template_slug || 'prices' === $post_slug;
	}
}

if ( ! function_exists( 'wipe_clean_get_prices_page_layout_order' ) ) {
	function wipe_clean_get_prices_page_layout_order() {
		return array(
			'prices_hero',
			'prices_services_preview',
			'area_pricing',
			'price_factors',
			'price_advantages',
			'company_highlight',
			'contacts',
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_prices_page_sections' ) ) {
	function wipe_clean_get_prices_page_sections( $post_id = 0 ) {
		$post_id             = $post_id ? (int) $post_id : (int) get_queried_object_id();
		$normalized_sections = array();
		$acf_sections        = array();

		if ( function_exists( 'get_field' ) ) {
			$sections = get_field( 'prices_page_sections', $post_id );

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

		foreach ( wipe_clean_get_prices_page_layout_order() as $layout ) {
			$defaults              = wipe_clean_get_prices_page_section_defaults( $layout );
			$normalized_sections[] = function_exists( 'wipe_clean_merge_section_with_fallback' )
				? wipe_clean_merge_section_with_fallback( $defaults, $acf_sections[ $layout ] ?? array() )
				: array_replace_recursive( $defaults, (array) ( $acf_sections[ $layout ] ?? array() ) );
		}

		foreach ( $normalized_sections as &$section ) {
			$layout = (string) ( $section['acf_fc_layout'] ?? '' );

			if ( in_array( $layout, array( 'prices_hero', 'prices_services_preview', 'price_factors' ), true ) ) {
				$section['primary_action'] = wipe_clean_force_link_url( $section['primary_action'] ?? array(), '#popup-calc' );
			}
		}
		unset( $section );

		return $normalized_sections;
	}
}

if ( ! function_exists( 'wipe_clean_render_prices_page_sections' ) ) {
	function wipe_clean_render_prices_page_sections( $post_id = 0 ) {
		$wave_group_open = false;

		foreach ( wipe_clean_get_prices_page_sections( $post_id ) as $section ) {
			$layout = (string) ( $section['acf_fc_layout'] ?? '' );

			if ( '' === $layout ) {
				continue;
			}

			if ( 'prices_services_preview' === $layout && ! $wave_group_open ) {
				$wave_group_open = true;
				echo '<div class="ui-wave-group">';
			}

			switch ( $layout ) {
				case 'prices_hero':
					get_template_part( 'template-parts/section/prices-page/prices-hero', null, array( 'section' => $section ) );
					break;

				case 'prices_services_preview':
					get_template_part( 'template-parts/section/prices-page/prices-services-preview', null, array( 'section' => $section ) );
					break;

				case 'area_pricing':
					get_template_part( 'template-parts/section/prices-page/area-pricing', null, array( 'section' => $section ) );
					break;

				case 'price_factors':
					get_template_part( 'template-parts/section/prices-page/price-factors', null, array( 'section' => $section ) );
					break;

				case 'price_advantages':
					get_template_part( 'template-parts/section/prices-page/price-advantages', null, array( 'section' => $section ) );
					break;

				case 'company_highlight':
					get_template_part( 'template-parts/section/prices-page/company-highlight', null, array( 'section' => $section ) );
					break;

				case 'contacts':
					get_template_part( 'template-parts/section/front-page/contacts', null, array( 'section' => $section ) );
					break;
			}

			if ( 'area_pricing' === $layout && $wave_group_open ) {
				$wave_group_open = false;
				echo '</div>';
			}
		}

		if ( $wave_group_open ) {
			echo '</div>';
		}
	}
}

if ( ! function_exists( 'wipe_clean_render_prices_page_template' ) ) {
	function wipe_clean_render_prices_page_template() {
		get_header();
		?>
		<main id="primary" class="main site-main">
			<div class="prices-page">
				<?php wipe_clean_render_prices_page_sections(); ?>
			</div>
		</main>
		<?php
		get_footer();
	}
}
