<?php
/**
 * About page rendering.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_is_about_page_post' ) ) {
	function wipe_clean_is_about_page_post( $post_id = 0 ) {
		$post_id = $post_id ? (int) $post_id : (int) get_queried_object_id();

		if ( ! $post_id || 'page' !== get_post_type( $post_id ) ) {
			return false;
		}

		$template_slug = (string) get_page_template_slug( $post_id );
		$post_slug     = (string) get_post_field( 'post_name', $post_id );

		return 'template-about-page.php' === $template_slug || in_array( $post_slug, array( 'about-us', 'about' ), true );
	}
}

if ( ! function_exists( 'wipe_clean_get_about_page_layout_order' ) ) {
	function wipe_clean_get_about_page_layout_order() {
		return array(
			'about_hero',
			'company_story',
			'work_approach',
			'team_preview',
			'why_us',
			'about_services_preview',
			'about_reviews_preview',
			'about_order_cta',
			'contacts',
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_about_page_sections' ) ) {
	function wipe_clean_get_about_page_sections( $post_id = 0 ) {
		$post_id             = $post_id ? (int) $post_id : (int) get_queried_object_id();
		$normalized_sections = array();
		$acf_sections        = array();

		if ( function_exists( 'get_field' ) ) {
			$sections = get_field( 'about_page_sections', $post_id );

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

		foreach ( wipe_clean_get_about_page_layout_order() as $layout ) {
			$defaults              = wipe_clean_get_about_page_section_defaults( $layout );
			$normalized_sections[] = function_exists( 'wipe_clean_merge_section_with_fallback' )
				? wipe_clean_merge_section_with_fallback( $defaults, $acf_sections[ $layout ] ?? array() )
				: array_replace_recursive( $defaults, (array) ( $acf_sections[ $layout ] ?? array() ) );
		}

		foreach ( $normalized_sections as &$section ) {
			$layout = (string) ( $section['acf_fc_layout'] ?? '' );

			if ( 'about_hero' === $layout ) {
				$section['primary_action'] = wipe_clean_force_link_url( $section['primary_action'] ?? array(), '#popup-question' );
			}

			if ( in_array( $layout, array( 'company_story', 'work_approach', 'why_us', 'team_preview', 'about_order_cta' ), true ) ) {
				$section['primary_action'] = wipe_clean_force_link_url( $section['primary_action'] ?? array(), '#popup-order-service' );
			}

			if ( 'about_services_preview' === $layout ) {
				$section['secondary_action'] = wipe_clean_force_link_url( $section['secondary_action'] ?? array(), '#popup-calc' );
			}

			if ( 'about_reviews_preview' === $layout ) {
				$section['secondary_action'] = wipe_clean_force_link_url( $section['secondary_action'] ?? array(), '#popup-review' );
			}
		}
		unset( $section );

		return $normalized_sections;
	}
}

if ( ! function_exists( 'wipe_clean_render_about_page_sections' ) ) {
	function wipe_clean_render_about_page_sections( $post_id = 0 ) {
		$wave_group_open = false;

		foreach ( wipe_clean_get_about_page_sections( $post_id ) as $section ) {
			$layout = (string) ( $section['acf_fc_layout'] ?? '' );

			if ( '' === $layout ) {
				continue;
			}

			if ( 'company_story' === $layout && ! $wave_group_open ) {
				$wave_group_open = true;
				echo '<div class="ui-wave-group">';
			}

			switch ( $layout ) {
				case 'about_hero':
					get_template_part( 'template-parts/section/about-page/about-hero', null, array( 'section' => $section ) );
					break;

				case 'company_story':
					get_template_part( 'template-parts/section/about-page/company-story', null, array( 'section' => $section ) );
					break;

				case 'work_approach':
					get_template_part( 'template-parts/section/about-page/work-approach', null, array( 'section' => $section ) );
					break;

				case 'team_preview':
					get_template_part( 'template-parts/section/about-page/team-preview', null, array( 'section' => $section ) );
					break;

				case 'why_us':
					get_template_part( 'template-parts/section/about-page/why-us', null, array( 'section' => $section ) );
					break;

				case 'about_services_preview':
					get_template_part( 'template-parts/section/front-page/services-preview', null, array( 'section' => $section ) );
					break;

				case 'about_reviews_preview':
					get_template_part( 'template-parts/section/front-page/reviews-preview', null, array( 'section' => $section ) );
					break;

				case 'about_order_cta':
					get_template_part( 'template-parts/section/service-single/order-cta', null, array( 'section' => $section ) );
					break;

				case 'contacts':
					get_template_part( 'template-parts/section/front-page/contacts', null, array( 'section' => $section ) );
					break;
			}

			if ( 'work_approach' === $layout && $wave_group_open ) {
				$wave_group_open = false;
				echo '</div>';
			}
		}

		if ( $wave_group_open ) {
			echo '</div>';
		}
	}
}

if ( ! function_exists( 'wipe_clean_render_about_page_template' ) ) {
	function wipe_clean_render_about_page_template() {
		get_header();
		?>
		<main id="primary" class="main site-main">
			<div class="about-page">
				<?php wipe_clean_render_about_page_sections(); ?>
			</div>
		</main>
		<?php
		get_footer();
	}
}
