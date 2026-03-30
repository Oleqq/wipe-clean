<?php
/**
 * Front-page helpers for CPT-backed sections.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sort homepage CPT items by configured order.
 *
 * @param array<int, array<string, mixed>> $items Raw items.
 * @return array<int, array<string, mixed>>
 */
function wipe_clean_sort_front_page_cpt_items( $items ) {
	usort(
		$items,
		static function ( $left, $right ) {
			$left_order  = (int) ( $left['home_order'] ?? 999 );
			$right_order = (int) ( $right['home_order'] ?? 999 );

			if ( $left_order === $right_order ) {
				return strcmp( (string) ( $left['title'] ?? $left['author'] ?? '' ), (string) ( $right['title'] ?? $right['author'] ?? '' ) );
			}

			return $left_order <=> $right_order;
		}
	);

	return array_values( $items );
}

/**
 * Build service card data from a seeded fallback item.
 *
 * @param array<string, mixed> $item Raw item.
 * @return array<string, mixed>
 */
function wipe_clean_map_fallback_service_item_to_card( $item ) {
	$layers = array();

	foreach ( (array) ( $item['layers'] ?? array() ) as $layer ) {
		$image_path = (string) ( $layer['image_path'] ?? '' );

		if ( '' === $image_path ) {
			continue;
		}

		$layers[] = array(
			'image'    => wipe_clean_theme_image( $image_path ),
			'modifier' => (string) ( $layer['modifier'] ?? '' ),
		);
	}

	return array(
		'title'     => (string) ( $item['title'] ?? '' ),
		'price'     => (string) ( $item['price'] ?? '' ),
		'href'      => 'services.html',
		'className' => 'after_repair' === (string) ( $item['card_variant'] ?? '' ) ? 'service-card--after-repair' : '',
		'layers'    => $layers,
	);
}

/**
 * Build review card data from a seeded fallback item.
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
			$post_id  = (int) $post->ID;
			$home_key = (string) get_field( 'home_group', $post_id );
			$card     = array(
				'title'     => get_the_title( $post_id ),
				'price'     => (string) get_field( 'card_price', $post_id ),
				'href'      => 'services.html',
				'className' => 'after_repair' === (string) get_field( 'card_variant', $post_id ) ? 'service-card--after-repair' : '',
				'layers'    => array(),
				'home_order' => (int) get_field( 'home_order', $post_id ),
			);

			foreach ( (array) get_field( 'card_layers', $post_id ) as $layer ) {
				if ( empty( $layer['image'] ) ) {
					continue;
				}

				$card['layers'][] = array(
					'image'    => $layer['image'],
					'modifier' => (string) ( $layer['modifier'] ?? '' ),
				);
			}

			if ( 'secondary' === $home_key ) {
				$secondary[] = $card;
			} else {
				$featured[] = $card;
			}
		}
	}

	if ( empty( $featured ) || empty( $secondary ) ) {
		foreach ( wipe_clean_get_front_page_default_service_items() as $item ) {
			$card = wipe_clean_map_fallback_service_item_to_card( $item );

			if ( empty( $featured ) && 'featured' === ( $item['home_group'] ?? '' ) ) {
				$featured[] = $card;
			}

			if ( empty( $secondary ) && 'secondary' === ( $item['home_group'] ?? '' ) ) {
				$secondary[] = $card;
			}
		}
	}

	$featured  = wipe_clean_sort_front_page_cpt_items( $featured );
	$secondary = wipe_clean_sort_front_page_cpt_items( $secondary );

	return array(
		'featured'  => array_values( $featured ),
		'secondary' => array_values( $secondary ),
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
