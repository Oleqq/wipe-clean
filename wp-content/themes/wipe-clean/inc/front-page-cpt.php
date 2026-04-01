<?php
/**
 * Помощники для CPT-блоков главной страницы.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sort CPT-backed homepage items.
 *
 * @param array<int, array<string, mixed>> $items Items.
 * @return array<int, array<string, mixed>>
 */
function wipe_clean_sort_front_page_cpt_items( $items ) {
	usort(
		$items,
		static function ( $left, $right ) {
			$left_order  = (int) ( $left['sort_order'] ?? $left['home_order'] ?? $left['menu_order'] ?? 999 );
			$right_order = (int) ( $right['sort_order'] ?? $right['home_order'] ?? $right['menu_order'] ?? 999 );

			if ( $left_order === $right_order ) {
				return strcmp( (string) ( $left['title'] ?? $left['author'] ?? '' ), (string) ( $right['title'] ?? $right['author'] ?? '' ) );
			}

			return $left_order <=> $right_order;
		}
	);

	return array_values( $items );
}

if ( ! function_exists( 'wipe_clean_get_front_page_service_row_limits' ) ) {
	function wipe_clean_get_front_page_service_row_limits() {
		$limits = array(
			'featured'  => 0,
			'secondary' => 0,
		);

		foreach ( wipe_clean_get_front_page_default_service_items() as $item ) {
			$group = 'secondary' === ( $item['home_group'] ?? '' ) ? 'secondary' : 'featured';
			$limits[ $group ]++;
		}

		return $limits;
	}
}

/**
 * Get a fallback service image.
 *
 * @param array<string, mixed> $item Raw item.
 * @return array<string, mixed>
 */
function wipe_clean_get_fallback_service_card_image( $item ) {
	if ( empty( $item['image'] ) ) {
		return array();
	}

	return wipe_clean_normalize_theme_image( $item['image'], (string) ( $item['title'] ?? '' ) );
}

/**
 * Map fallback service item to card.
 *
 * @param array<string, mixed> $item Raw item.
 * @return array<string, mixed>
 */
function wipe_clean_map_fallback_service_item_to_card( $item ) {
	return array(
		'title' => (string) ( $item['title'] ?? '' ),
		'price' => (string) ( $item['price'] ?? '' ),
		'href'  => home_url( '/services/' ),
		'image' => wipe_clean_get_fallback_service_card_image( $item ),
	);
}

/**
 * Map fallback review item to card.
 *
 * @param array<string, mixed> $item Raw item.
 * @return array<string, mixed>
 */
function wipe_clean_map_fallback_review_item_to_card( $item ) {
	return array(
		'author' => (string) ( $item['author'] ?? '' ),
		'text'   => (string) ( $item['text'] ?? '' ),
		'rating' => (int) ( $item['rating'] ?? 5 ),
	);
}

/**
 * Get service cards for the homepage.
 *
 * @return array<string, array<int, array<string, mixed>>>
 */
function wipe_clean_get_front_page_service_cards() {
	$featured  = array();
	$secondary = array();

	if ( post_type_exists( 'wipe_service' ) ) {
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

		$cards = array();

		foreach ( $posts as $post ) {
			$post_id  = (int) $post->ID;
			$fallback = array();

			foreach ( wipe_clean_get_front_page_default_service_items() as $default_item ) {
				if ( get_the_title( $post_id ) === (string) ( $default_item['title'] ?? '' ) ) {
					$fallback = $default_item;
					break;
				}
			}

			$image = function_exists( 'wipe_clean_get_service_primary_image' )
				? wipe_clean_get_service_primary_image( $post_id, $fallback['image'] ?? array() )
				: wipe_clean_get_fallback_service_card_image( $fallback );

			$price = function_exists( 'wipe_clean_get_service_card_price_value' )
				? wipe_clean_get_service_card_price_value( $post_id, (string) ( $fallback['price'] ?? '' ) )
				: (string) ( $fallback['price'] ?? '' );

			$cards[] = array(
				'title'      => get_the_title( $post_id ),
				'price'      => $price,
				'href'       => get_permalink( $post_id ),
				'image'      => $image,
				'sort_order' => (int) $post->menu_order,
			);
		}

		if ( ! empty( $cards ) ) {
			$limits    = wipe_clean_get_front_page_service_row_limits();
			$cards     = wipe_clean_sort_front_page_cpt_items( $cards );
			$featured  = array_slice( $cards, 0, (int) $limits['featured'] );
			$secondary = array_slice( $cards, (int) $limits['featured'], (int) $limits['secondary'] );
		}
	}

	if ( empty( $featured ) || empty( $secondary ) ) {
		$limits = wipe_clean_get_front_page_service_row_limits();

		foreach ( wipe_clean_get_front_page_default_service_items() as $item ) {
			$card = wipe_clean_map_fallback_service_item_to_card( $item );

			if ( 'featured' === ( $item['home_group'] ?? '' ) && count( $featured ) < (int) $limits['featured'] ) {
				$featured[] = $card;
			}

			if ( 'secondary' === ( $item['home_group'] ?? '' ) && count( $secondary ) < (int) $limits['secondary'] ) {
				$secondary[] = $card;
			}
		}
	}

	return array(
		'featured'  => wipe_clean_sort_front_page_cpt_items( $featured ),
		'secondary' => wipe_clean_sort_front_page_cpt_items( $secondary ),
	);
}

/**
 * Get review cards for the homepage.
 *
 * @return array<int, array<string, mixed>>
 */
function wipe_clean_get_front_page_review_items() {
	$items = array();

	if ( post_type_exists( 'wipe_review' ) ) {
		$posts = get_posts(
			array(
				'post_type'      => 'wipe_review',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'meta_query'     => array(
					array(
						'key'     => 'show_on_home',
						'value'   => '1',
						'compare' => '=',
					),
				),
			)
		);

		foreach ( $posts as $post ) {
			$post_id = (int) $post->ID;
			$type    = function_exists( 'wipe_clean_normalize_review_type' )
				? wipe_clean_normalize_review_type( get_field( 'review_type', $post_id ) )
				: 'text';

			if ( 'text' !== $type ) {
				continue;
			}

			$items[] = array(
				'author'     => (string) ( get_field( 'author_name', $post_id ) ?: get_the_title( $post_id ) ),
				'text'       => (string) get_field( 'review_text', $post_id ),
				'rating'     => (int) get_field( 'rating', $post_id ),
				'home_order' => (int) get_field( 'home_order', $post_id ),
			);
		}
	}

	if ( empty( $items ) ) {
		foreach ( wipe_clean_get_front_page_default_review_items() as $item ) {
			$items[] = wipe_clean_map_fallback_review_item_to_card( $item );
		}
	}

	return wipe_clean_sort_front_page_cpt_items( $items );
}
