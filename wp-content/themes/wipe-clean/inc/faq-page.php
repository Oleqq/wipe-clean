<?php
/**
 * FAQ page rendering.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_is_faq_page_post' ) ) {
	function wipe_clean_is_faq_page_post( $post_id = 0 ) {
		$post_id = $post_id ? (int) $post_id : (int) get_queried_object_id();

		if ( ! $post_id || 'page' !== get_post_type( $post_id ) ) {
			return false;
		}

		$template_slug = (string) get_page_template_slug( $post_id );
		$post_slug     = (string) get_post_field( 'post_name', $post_id );

		return 'template-faq-page.php' === $template_slug || 'faq' === $post_slug;
	}
}

if ( ! function_exists( 'wipe_clean_get_faq_page_layout_order' ) ) {
	function wipe_clean_get_faq_page_layout_order() {
		return array(
			'faq_hero',
			'faq',
			'contacts',
		);
	}
}

if ( ! function_exists( 'wipe_clean_normalize_faq_page_section' ) ) {
	function wipe_clean_normalize_faq_page_section( $layout, $section, $acf_section ) {
		$acf_section = is_array( $acf_section ) ? $acf_section : array();
		$section     = is_array( $section ) ? $section : array();

		if ( 'faq' !== $layout ) {
			return $section;
		}

		$section['modifier'] = 'page';

		if ( ! empty( $acf_section['items'] ) && is_array( $acf_section['items'] ) ) {
			$section['mobile_items'] = array_values( $acf_section['items'] );
		}

		if ( ! isset( $section['initial_open_index'] ) ) {
			$section['initial_open_index'] = 0;
		}

		return $section;
	}
}

if ( ! function_exists( 'wipe_clean_get_faq_page_sections' ) ) {
	function wipe_clean_get_faq_page_sections( $post_id = 0 ) {
		$post_id             = $post_id ? (int) $post_id : (int) get_queried_object_id();
		$normalized_sections = array();
		$acf_sections        = array();

		if ( function_exists( 'get_field' ) ) {
			$sections = get_field( 'faq_page_sections', $post_id );

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

		foreach ( wipe_clean_get_faq_page_layout_order() as $layout ) {
			$defaults = wipe_clean_get_faq_page_section_defaults( $layout );
			$section  = function_exists( 'wipe_clean_merge_section_with_fallback' )
				? wipe_clean_merge_section_with_fallback( $defaults, $acf_sections[ $layout ] ?? array() )
				: array_replace_recursive( $defaults, (array) ( $acf_sections[ $layout ] ?? array() ) );

			$normalized_sections[] = wipe_clean_normalize_faq_page_section( $layout, $section, $acf_sections[ $layout ] ?? array() );
		}

		return $normalized_sections;
	}
}

if ( ! function_exists( 'wipe_clean_render_faq_page_sections' ) ) {
	function wipe_clean_render_faq_page_sections( $post_id = 0 ) {
		foreach ( wipe_clean_get_faq_page_sections( $post_id ) as $section ) {
			$layout = (string) ( $section['acf_fc_layout'] ?? '' );

			if ( '' === $layout ) {
				continue;
			}

			switch ( $layout ) {
				case 'faq_hero':
					get_template_part( 'template-parts/section/faq-page/faq-hero', null, array( 'section' => $section ) );
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
}

if ( ! function_exists( 'wipe_clean_render_faq_page_template' ) ) {
	function wipe_clean_render_faq_page_template() {
		get_header();
		?>
		<main id="primary" class="main site-main">
			<div class="faq-page">
				<?php wipe_clean_render_faq_page_sections(); ?>
			</div>
		</main>
		<?php
		get_footer();
	}
}
