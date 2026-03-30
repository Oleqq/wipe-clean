<?php
/**
 * Custom post types used by section-driven pages.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register non-public content post types for section data.
 *
 * @return void
 */
function wipe_clean_register_content_post_types() {
	register_post_type(
		'wipe_service',
		array(
			'labels'              => array(
				'name'               => 'Услуги для блоков',
				'singular_name'      => 'Услуга для блока',
				'add_new'            => 'Добавить услугу',
				'add_new_item'       => 'Добавить услугу',
				'edit_item'          => 'Редактировать услугу',
				'new_item'           => 'Новая услуга',
				'view_item'          => 'Просмотреть услугу',
				'search_items'       => 'Найти услугу',
				'not_found'          => 'Услуги не найдены',
				'not_found_in_trash' => 'В корзине услуг нет',
				'all_items'          => 'Все услуги',
				'menu_name'          => 'Услуги',
			),
			'public'              => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'rewrite'             => false,
			'menu_icon'           => 'dashicons-admin-tools',
			'supports'            => array( 'title' ),
		)
	);

	register_post_type(
		'wipe_review',
		array(
			'labels'              => array(
				'name'               => 'Отзывы для блоков',
				'singular_name'      => 'Отзыв для блока',
				'add_new'            => 'Добавить отзыв',
				'add_new_item'       => 'Добавить отзыв',
				'edit_item'          => 'Редактировать отзыв',
				'new_item'           => 'Новый отзыв',
				'view_item'          => 'Просмотреть отзыв',
				'search_items'       => 'Найти отзыв',
				'not_found'          => 'Отзывы не найдены',
				'not_found_in_trash' => 'В корзине отзывов нет',
				'all_items'          => 'Все отзывы',
				'menu_name'          => 'Отзывы',
			),
			'public'              => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'rewrite'             => false,
			'menu_icon'           => 'dashicons-star-filled',
			'supports'            => array( 'title' ),
		)
	);
}
add_action( 'init', 'wipe_clean_register_content_post_types' );
