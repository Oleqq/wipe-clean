<?php
/**
 * Регистрация типов записей темы.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme post types.
 *
 * @return void
 */
function wipe_clean_register_content_post_types() {
	register_post_type(
		'wipe_service',
		array(
			'labels'              => array(
				'name'                  => 'Услуги',
				'singular_name'         => 'Услуга',
				'menu_name'             => 'Услуги',
				'name_admin_bar'        => 'Услуга',
				'add_new'               => 'Добавить услугу',
				'add_new_item'          => 'Добавить услугу',
				'new_item'              => 'Новая услуга',
				'edit_item'             => 'Редактировать услугу',
				'view_item'             => 'Посмотреть услугу',
				'view_items'            => 'Посмотреть услуги',
				'all_items'             => 'Все услуги',
				'search_items'          => 'Найти услугу',
				'not_found'             => 'Услуги не найдены',
				'not_found_in_trash'    => 'В корзине услуг нет',
				'archives'              => 'Архив услуг',
				'attributes'            => 'Параметры услуги',
				'insert_into_item'      => 'Вставить в услугу',
				'uploaded_to_this_item' => 'Загружено для этой услуги',
				'filter_items_list'     => 'Фильтр списка услуг',
				'items_list_navigation' => 'Навигация по услугам',
				'items_list'            => 'Список услуг',
			),
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'show_in_admin_bar'   => true,
			'exclude_from_search' => false,
			'has_archive'         => 'services',
			'rewrite'             => array(
				'slug'       => 'services',
				'with_front' => false,
			),
			'query_var'           => true,
			'menu_icon'           => 'dashicons-admin-tools',
			'supports'            => array( 'title', 'excerpt', 'thumbnail', 'page-attributes' ),
		)
	);

	register_post_type(
		'wipe_blog',
		array(
			'labels'              => array(
				'name'                  => 'Блог',
				'singular_name'         => 'Статья блога',
				'menu_name'             => 'Блог',
				'name_admin_bar'        => 'Статья блога',
				'add_new'               => 'Добавить статью',
				'add_new_item'          => 'Добавить статью блога',
				'new_item'              => 'Новая статья',
				'edit_item'             => 'Редактировать статью',
				'view_item'             => 'Посмотреть статью',
				'view_items'            => 'Посмотреть статьи',
				'all_items'             => 'Все статьи',
				'search_items'          => 'Найти статью',
				'not_found'             => 'Статьи не найдены',
				'not_found_in_trash'    => 'В корзине статей нет',
				'archives'              => 'Архив блога',
				'attributes'            => 'Параметры статьи',
				'insert_into_item'      => 'Вставить в статью',
				'uploaded_to_this_item' => 'Загружено для этой статьи',
				'filter_items_list'     => 'Фильтр списка статей',
				'items_list_navigation' => 'Навигация по статьям',
				'items_list'            => 'Список статей',
			),
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'show_in_admin_bar'   => true,
			'exclude_from_search' => false,
			'has_archive'         => 'blog',
			'rewrite'             => array(
				'slug'       => 'blog',
				'with_front' => false,
			),
			'query_var'           => true,
			'menu_icon'           => 'dashicons-welcome-write-blog',
			'taxonomies'          => array( 'category', 'post_tag' ),
			'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
		)
	);

	register_post_type(
		'wipe_review',
		array(
			'labels'              => array(
				'name'                  => 'Отзывы',
				'singular_name'         => 'Отзыв',
				'menu_name'             => 'Отзывы',
				'name_admin_bar'        => 'Отзыв',
				'add_new'               => 'Добавить отзыв',
				'add_new_item'          => 'Добавить отзыв',
				'new_item'              => 'Новый отзыв',
				'edit_item'             => 'Редактировать отзыв',
				'view_item'             => 'Посмотреть отзыв',
				'all_items'             => 'Все отзывы',
				'search_items'          => 'Найти отзыв',
				'not_found'             => 'Отзывы не найдены',
				'not_found_in_trash'    => 'В корзине отзывов нет',
				'items_list'            => 'Список отзывов',
				'items_list_navigation' => 'Навигация по отзывам',
			),
			'public'              => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'show_in_admin_bar'   => true,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'rewrite'             => false,
			'menu_icon'           => 'dashicons-star-filled',
			'supports'            => array( 'title', 'page-attributes' ),
		)
	);

	register_post_type(
		'wipe_promotion',
		array(
			'labels'              => array(
				'name'                  => 'Акции',
				'singular_name'         => 'Акция',
				'menu_name'             => 'Акции',
				'name_admin_bar'        => 'Акция',
				'add_new'               => 'Добавить акцию',
				'add_new_item'          => 'Добавить акцию',
				'new_item'              => 'Новая акция',
				'edit_item'             => 'Редактировать акцию',
				'view_item'             => 'Посмотреть акцию',
				'all_items'             => 'Все акции',
				'search_items'          => 'Найти акцию',
				'not_found'             => 'Акции не найдены',
				'not_found_in_trash'    => 'В корзине акций нет',
				'items_list'            => 'Список акций',
				'items_list_navigation' => 'Навигация по акциям',
			),
			'public'              => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'show_in_admin_bar'   => true,
			'exclude_from_search' => true,
			'has_archive'         => false,
			'rewrite'             => false,
			'query_var'           => false,
			'menu_icon'           => 'dashicons-megaphone',
			'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
		)
	);
}
add_action( 'init', 'wipe_clean_register_content_post_types' );
