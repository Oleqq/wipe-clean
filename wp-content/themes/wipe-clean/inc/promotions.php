<?php
/**
 * Promotions archive rendering and helpers.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/promotions-routing.php';
require_once __DIR__ . '/data/promotions.php';

if ( ! function_exists( 'wipe_clean_get_promotions_post_type' ) ) {
	function wipe_clean_get_promotions_post_type() {
		return 'wipe_promotion';
	}
}

if ( ! function_exists( 'wipe_clean_get_promotions_archive_options_slug' ) ) {
	function wipe_clean_get_promotions_archive_options_slug() {
		return 'wipe-clean-promotions-archive';
	}
}

if ( ! function_exists( 'wipe_clean_get_promotions_archive_settings_url' ) ) {
	function wipe_clean_get_promotions_archive_settings_url() {
		return admin_url( 'admin.php?page=' . wipe_clean_get_promotions_archive_options_slug() );
	}
}

if ( ! function_exists( 'wipe_clean_get_promotions_archive_page_url' ) ) {
	function wipe_clean_get_promotions_archive_page_url() {
		return home_url( '/promotions/' );
	}
}

if ( ! function_exists( 'wipe_clean_is_promotions_archive_request' ) ) {
	function wipe_clean_is_promotions_archive_request() {
		return (bool) get_query_var( 'wipe_clean_promotions_archive' ) || 'promotions' === wipe_clean_current_request_path();
	}
}

if ( ! function_exists( 'wipe_clean_get_promotions_archive_raw_rows' ) ) {
	function wipe_clean_get_promotions_archive_raw_rows() {
		if ( ! function_exists( 'get_field' ) ) {
			return array();
		}

		$rows = get_field( 'promotions_archive_sections', 'option' );

		return is_array( $rows ) ? $rows : array();
	}
}

if ( ! function_exists( 'wipe_clean_get_promotion_posts' ) ) {
	function wipe_clean_get_promotion_posts() {
		if ( ! post_type_exists( wipe_clean_get_promotions_post_type() ) ) {
			return array();
		}

		return get_posts(
			array(
				'post_type'      => wipe_clean_get_promotions_post_type(),
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => array(
					'menu_order' => 'ASC',
					'date'       => 'DESC',
					'title'      => 'ASC',
				),
				'order'          => 'ASC',
			)
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_promotion_thumbnail_alt' ) ) {
	function wipe_clean_get_promotion_thumbnail_alt( $post_id, $fallback = '' ) {
		$thumbnail_id = (int) get_post_thumbnail_id( (int) $post_id );

		if ( ! $thumbnail_id ) {
			return (string) $fallback;
		}

		$alt = trim( (string) get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ) );

		if ( '' !== $alt ) {
			return $alt;
		}

		$title = trim( (string) get_the_title( $thumbnail_id ) );

		return '' !== $title ? $title : (string) $fallback;
	}
}

if ( ! function_exists( 'wipe_clean_split_promotion_popup_text' ) ) {
	function wipe_clean_split_promotion_popup_text( $text ) {
		$text = trim( (string) $text );

		if ( '' === $text ) {
			return array();
		}

		$parts = preg_split( '/(?:\r\n|\r|\n){2,}/', $text ) ?: array();
		$parts = array_values(
			array_filter(
				array_map( 'trim', $parts ),
				'strlen'
			)
		);

		if ( empty( $parts ) ) {
			$parts[] = $text;
		}

		return $parts;
	}
}

if ( ! function_exists( 'wipe_clean_get_promotion_popup_conditions' ) ) {
	function wipe_clean_get_promotion_popup_conditions( $post_id ) {
		if ( ! function_exists( 'get_field' ) ) {
			return array();
		}

		$rows  = get_field( 'popup_conditions', (int) $post_id );
		$items = array();

		foreach ( (array) $rows as $row ) {
			$text = trim( (string) ( $row['text'] ?? '' ) );

			if ( '' === $text ) {
				continue;
			}

			$items[] = $text;
		}

		return $items;
	}
}

if ( ! function_exists( 'wipe_clean_build_promotion_item_from_post' ) ) {
	function wipe_clean_build_promotion_item_from_post( $post, $fallback = array() ) {
		$post = get_post( $post );

		if ( ! $post instanceof WP_Post ) {
			return (array) $fallback;
		}

		$post_id          = (int) $post->ID;
		$title            = trim( (string) get_the_title( $post ) );
		$popup_title      = function_exists( 'get_field' ) ? trim( (string) get_field( 'popup_title', $post_id ) ) : '';
		$popup_text       = function_exists( 'get_field' ) ? wipe_clean_split_promotion_popup_text( (string) get_field( 'popup_text', $post_id ) ) : array();
		$popup_conditions = wipe_clean_get_promotion_popup_conditions( $post_id );
		$popup_image      = function_exists( 'get_field' ) ? get_field( 'popup_image', $post_id ) : array();
		$image            = has_post_thumbnail( $post_id ) ? get_post_thumbnail_id( $post_id ) : ( $fallback['image'] ?? array() );
		$image_alt        = wipe_clean_get_promotion_thumbnail_alt( $post_id, (string) ( $fallback['imageAlt'] ?? $title ) );

		if ( empty( $popup_text ) ) {
			$popup_text = array_values( (array) ( $fallback['popupText'] ?? array() ) );
		}

		if ( empty( $popup_conditions ) ) {
			$popup_conditions = array_values( (array) ( $fallback['popupConditions'] ?? array() ) );
		}

		return array_merge(
			(array) $fallback,
			array(
				'title'           => '' !== $title ? $title : (string) ( $fallback['title'] ?? '' ),
				'href'            => '#',
				'popupId'         => 'promotion-popup-' . sanitize_title( $post->post_name ?: $post_id ),
				'image'           => $image,
				'imageAlt'        => $image_alt,
				'popupTitle'      => '' !== $popup_title ? $popup_title : ( '' !== $title ? $title : (string) ( $fallback['popupTitle'] ?? '' ) ),
				'popupText'       => $popup_text,
				'popupConditions' => $popup_conditions,
				'popupImage'      => ! empty( $popup_image ) ? $popup_image : $image,
				'popupImageAlt'   => ! empty( $popup_image ) ? (string) ( $popup_image['alt'] ?? $image_alt ) : $image_alt,
			)
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_promotions_archive_items' ) ) {
	function wipe_clean_get_promotions_archive_items() {
		$fallback_items = wipe_clean_get_promotions_archive_default_items();
		$posts          = wipe_clean_get_promotion_posts();

		if ( empty( $posts ) ) {
			return $fallback_items;
		}

		$items = array();

		foreach ( array_values( $posts ) as $index => $post ) {
			$items[] = wipe_clean_build_promotion_item_from_post( $post, $fallback_items[ $index ] ?? array() );
		}

		return $items;
	}
}

if ( ! function_exists( 'wipe_clean_get_promotions_archive_sections' ) ) {
	function wipe_clean_get_promotions_archive_sections() {
		$sections = wipe_clean_normalize_blog_sections(
			wipe_clean_get_promotions_archive_raw_rows(),
			wipe_clean_get_promotions_archive_layout_order(),
			'wipe_clean_get_promotions_archive_section_defaults'
		);

		foreach ( $sections as &$section ) {
			$layout = isset( $section['acf_fc_layout'] ) ? (string) $section['acf_fc_layout'] : '';

			if ( 'promotions_archive' === $layout ) {
				$section['items']          = wipe_clean_get_promotions_archive_items();
				$section['primary_action'] = wipe_clean_resolve_link( $section['primary_action'] ?? array() );
			}

			if ( 'contacts' === $layout && empty( $section['id_prefix'] ) ) {
				$section['id_prefix'] = 'promotions-contacts';
			}
		}
		unset( $section );

		return $sections;
	}
}

if ( ! function_exists( 'wipe_clean_get_promotions_popup_items' ) ) {
	function wipe_clean_get_promotions_popup_items() {
		foreach ( wipe_clean_get_promotions_archive_sections() as $section ) {
			if ( 'promotions_archive' === (string) ( $section['acf_fc_layout'] ?? '' ) ) {
				return array_values( (array) ( $section['items'] ?? array() ) );
			}
		}

		return array();
	}
}

if ( ! function_exists( 'wipe_clean_render_promotions_archive_sections' ) ) {
	function wipe_clean_render_promotions_archive_sections() {
		foreach ( wipe_clean_get_promotions_archive_sections() as $section ) {
			$layout = isset( $section['acf_fc_layout'] ) ? (string) $section['acf_fc_layout'] : '';

			switch ( $layout ) {
				case 'promotions_archive':
					get_template_part( 'template-parts/section/promotions/promotions-archive', null, array( 'section' => $section ) );
					break;

				case 'company_preview':
					get_template_part( 'template-parts/section/front-page/company-preview', null, array( 'section' => $section ) );
					break;

				case 'contacts':
					get_template_part( 'template-parts/section/front-page/contacts', null, array( 'section' => $section ) );
					break;

				case 'faq':
					get_template_part( 'template-parts/section/front-page/faq', null, array( 'section' => $section ) );
					break;
			}
		}
	}
}

if ( ! function_exists( 'wipe_clean_render_promotions_archive_content' ) ) {
	function wipe_clean_render_promotions_archive_content() {
		echo '<div class="promotions-page">';
		wipe_clean_render_promotions_archive_sections();
		echo '</div>';
	}
}

if ( ! function_exists( 'wipe_clean_render_promotions_archive_popups' ) ) {
	function wipe_clean_render_promotions_archive_popups() {
		$items = wipe_clean_get_promotions_popup_items();

		if ( empty( $items ) ) {
			return;
		}

		get_template_part( 'template-parts/section/promotions/promotions-popups', null, array( 'items' => $items ) );
	}
}
