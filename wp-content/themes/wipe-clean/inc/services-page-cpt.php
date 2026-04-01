<?php
/**
 * CPT-карточки для архива услуг.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_normalize_theme_image' ) ) {
	function wipe_clean_normalize_theme_image( $image, $fallback_alt = '' ) {
		if ( empty( $image ) ) {
			return array();
		}

		if ( is_array( $image ) ) {
			$url = (string) ( $image['url'] ?? $image['src'] ?? '' );
			$alt = (string) ( $image['alt'] ?? $fallback_alt );
		} elseif ( is_numeric( $image ) ) {
			$url = (string) wp_get_attachment_image_url( (int) $image, 'full' );
			$alt = (string) get_post_meta( (int) $image, '_wp_attachment_image_alt', true );
		} else {
			$url = (string) $image;
			$alt = (string) $fallback_alt;
		}

		if ( '' === $url ) {
			return array();
		}

		return array(
			'url' => $url,
			'alt' => '' !== $alt ? $alt : $fallback_alt,
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_service_card_excerpt' ) ) {
	function wipe_clean_get_service_card_excerpt( $post, $fallback = '' ) {
		$post = get_post( $post );

		if ( ! $post instanceof WP_Post ) {
			return (string) $fallback;
		}

		$text = trim( (string) $post->post_excerpt );

		if ( '' === $text ) {
			$hero_row  = function_exists( 'wipe_clean_get_service_single_section_row' )
				? wipe_clean_get_service_single_section_row( $post->ID, 'service_hero' )
				: array();
			$hero_text = trim( (string) ( $hero_row['text'] ?? '' ) );

			if ( '' !== $hero_text ) {
				$text = wp_trim_words( wp_strip_all_tags( $hero_text ), 24, '...' );
			}
		}

		return '' !== $text ? $text : (string) $fallback;
	}
}

if ( ! function_exists( 'wipe_clean_get_service_card_price_value' ) ) {
	function wipe_clean_get_service_card_price_value( $post, $fallback = '' ) {
		$post = get_post( $post );

		if ( ! $post instanceof WP_Post ) {
			return (string) $fallback;
		}

		$price = '';

		if ( function_exists( 'get_field' ) ) {
			$price = trim( (string) get_field( 'service_price_value', $post->ID ) );

			if ( '' === $price && function_exists( 'wipe_clean_get_service_single_section_row' ) ) {
				$price_row = wipe_clean_get_service_single_section_row( $post->ID, 'service_price' );
				$price     = trim( (string) ( $price_row['accent_text'] ?? '' ) );
			}
		}

		return '' !== $price ? $price : (string) $fallback;
	}
}

if ( ! function_exists( 'wipe_clean_get_service_single_section_row' ) ) {
	function wipe_clean_get_service_single_section_row( $post_id, $layout_name ) {
		$post_id     = (int) $post_id;
		$layout_name = (string) $layout_name;

		if ( ! $post_id || '' === $layout_name || ! function_exists( 'get_field' ) ) {
			return array();
		}

		$rows = get_field( 'service_sections', $post_id );

		if ( ! is_array( $rows ) ) {
			return array();
		}

		foreach ( $rows as $row ) {
			if ( ( $row['acf_fc_layout'] ?? '' ) === $layout_name ) {
				return is_array( $row ) ? $row : array();
			}
		}

		return array();
	}
}

if ( ! function_exists( 'wipe_clean_get_service_primary_image' ) ) {
	function wipe_clean_get_service_primary_image( $post, $fallback = array() ) {
		$post = get_post( $post );

		if ( ! $post instanceof WP_Post ) {
			return $fallback;
		}

		if ( has_post_thumbnail( $post ) ) {
			return get_post_thumbnail_id( $post );
		}

		if ( function_exists( 'wipe_clean_get_service_single_section_row' ) ) {
			$hero_row = wipe_clean_get_service_single_section_row( $post->ID, 'service_hero' );
			$image    = $hero_row['image'] ?? array();

			if ( ! empty( $image ) ) {
				return $image;
			}
		}

		return $fallback;
	}
}

if ( ! function_exists( 'wipe_clean_get_service_card_title' ) ) {
	function wipe_clean_get_service_card_title( $post, $fallback = '' ) {
		$post = get_post( $post );

		if ( ! $post instanceof WP_Post ) {
			return (string) $fallback;
		}

		$title = trim( (string) get_the_title( $post ) );

		return '' !== $title ? $title : (string) $fallback;
	}
}

if ( ! function_exists( 'wipe_clean_get_service_card_class_name' ) ) {
	function wipe_clean_get_service_card_class_name( $post, $fallback = '' ) {
		$post = get_post( $post );

		if ( ! $post instanceof WP_Post ) {
			return (string) $fallback;
		}

		$class_name = trim( (string) get_post_meta( $post->ID, '_wipe_clean_services_page_class', true ) );

		return '' !== $class_name ? $class_name : (string) $fallback;
	}
}

if ( ! function_exists( 'wipe_clean_get_services_page_cpt_service_cards' ) ) {
	function wipe_clean_get_services_page_cpt_service_cards( $fallback_cards = array() ) {
		$posts = get_posts(
			array(
				'post_type'      => 'wipe_service',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => array(
					'menu_order' => 'ASC',
					'title'      => 'ASC',
				),
				'order'          => 'ASC',
			)
		);

		if ( empty( $posts ) ) {
			return $fallback_cards;
		}

		$cards = array();

		foreach ( array_values( $posts ) as $index => $post ) {
			$fallback = $fallback_cards[ $index ] ?? array();
			$title    = wipe_clean_get_service_card_title( $post, (string) ( $fallback['title'] ?? '' ) );
			$url      = get_permalink( $post );
			$text     = wipe_clean_get_service_card_excerpt( $post, (string) ( $fallback['text'] ?? '' ) );
			$image    = wipe_clean_get_service_primary_image( $post, $fallback['image'] ?? array() );
			$class    = wipe_clean_get_service_card_class_name( $post, (string) ( $fallback['class_name'] ?? '' ) );

			$cards[] = array_merge(
				$fallback,
				array(
					'id'         => 'service-card-' . $post->ID,
					'title'      => $title,
					'text'       => $text,
					'url'        => $url,
					'link'       => array(
						'url'   => $url,
						'title' => (string) ( $fallback['link']['title'] ?? $fallback['link_label'] ?? 'Подробнее' ),
					),
					'link_label' => (string) ( $fallback['link_label'] ?? 'Подробнее' ),
					'class_name' => $class,
					'image'      => $image,
				)
			);
		}

		return $cards;
	}
}
