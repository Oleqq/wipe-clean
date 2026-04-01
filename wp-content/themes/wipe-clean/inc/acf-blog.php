<?php
/**
 * ACF bootstrap for blog archive and single blog posts.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_register_blog_archive_options_page' ) ) {
	function wipe_clean_register_blog_archive_options_page() {
		if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
			return;
		}

		acf_add_options_sub_page(
			array(
				'page_title'  => 'Архив блога',
				'menu_title'  => 'Настройки архива',
				'menu_slug'   => wipe_clean_get_blog_archive_options_slug(),
				'parent_slug' => 'edit.php?post_type=' . wipe_clean_get_blog_post_type(),
				'capability'  => 'edit_posts',
				'redirect'    => false,
				'position'    => 99,
			)
		);
	}
}
add_action( 'acf/init', 'wipe_clean_register_blog_archive_options_page', 5 );

if ( ! function_exists( 'wipe_clean_get_blog_archive_acf_layout_blog_archive' ) ) {
	function wipe_clean_get_blog_archive_acf_layout_blog_archive() {
		return array(
			'key'        => 'layout_blog_archive',
			'name'       => 'blog_archive',
			'label'      => 'Архив блога',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field(
					'textarea',
					'title',
					'Заголовок',
					array(
						'rows'      => 2,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_field(
					'textarea',
					'text_top',
					'Текст сверху',
					array(
						'rows'      => 4,
						'new_lines' => 'wpautop',
					)
				),
				wipe_clean_acf_field(
					'textarea',
					'text_bottom',
					'Текст снизу',
					array(
						'rows'      => 4,
						'new_lines' => 'wpautop',
					)
				),
				wipe_clean_acf_field(
					'image',
					'hero_image',
					'Главное изображение',
					array(
						'return_format' => 'array',
						'preview_size'  => 'medium',
					)
				),
				wipe_clean_acf_field( 'text', 'button_label', 'Текст кнопки' ),
				wipe_clean_acf_field( 'text', 'button_loading_label', 'Текст кнопки при загрузке' ),
				wipe_clean_acf_field( 'number', 'initial_desktop', 'Карточек сразу на desktop' ),
				wipe_clean_acf_field( 'number', 'initial_mobile', 'Карточек сразу на mobile' ),
				wipe_clean_acf_field( 'number', 'step_desktop', 'Шаг подгрузки на desktop' ),
				wipe_clean_acf_field( 'number', 'step_mobile', 'Шаг подгрузки на mobile' ),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_single_acf_layout_blog_article_hero' ) ) {
	function wipe_clean_get_blog_single_acf_layout_blog_article_hero() {
		return array(
			'key'        => 'layout_blog_article_hero',
			'name'       => 'blog_article_hero',
			'label'      => 'Первый экран статьи',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field(
					'message',
					'hero_notice',
					'Как работает секция',
					array(
						'message'   => 'По умолчанию первый экран берёт заголовок, excerpt, дату и featured image из самой записи CPT <strong>Блог</strong>. Поля ниже нужны как fallback и для статичного пресета.',
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
					)
				),
				wipe_clean_acf_field(
					'textarea',
					'title',
					'Fallback заголовок',
					array(
						'rows'      => 2,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_field(
					'textarea',
					'excerpt',
					'Fallback описание',
					array(
						'rows'      => 5,
						'new_lines' => 'wpautop',
					)
				),
				wipe_clean_acf_field( 'text', 'date_label', 'Подпись даты' ),
				wipe_clean_acf_field(
					'image',
					'image',
					'Fallback изображение',
					array(
						'return_format' => 'array',
						'preview_size'  => 'medium',
					)
				),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_single_acf_layout_blog_article_content' ) ) {
	function wipe_clean_get_blog_single_acf_layout_blog_article_content() {
		return array(
			'key'        => 'layout_blog_article_content',
			'name'       => 'blog_article_content',
			'label'      => 'Контент статьи',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field(
					'message',
					'content_notice',
					'Как работает секция',
					array(
						'message'   => 'Если в самой записи CPT <strong>Блог</strong> заполнен обычный редактор WordPress, на сайте показывается именно он. Поле ниже используется как fallback-контент и для быстрого заполнения по статике.',
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
					)
				),
				wipe_clean_acf_field(
					'wysiwyg',
					'content',
					'Fallback контент',
					array(
						'tabs'         => 'all',
						'toolbar'      => 'full',
						'media_upload' => 1,
						'delay'        => 1,
					)
				),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_single_acf_layout_related_posts' ) ) {
	function wipe_clean_get_blog_single_acf_layout_related_posts() {
		return array(
			'key'        => 'layout_related_posts',
			'name'       => 'related_posts',
			'label'      => 'Рекомендуемые статьи',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field(
					'message',
					'related_notice',
					'Как работает секция',
					array(
						'message'   => 'Карточки ниже собираются автоматически из других записей CPT <strong>Блог</strong>. Здесь редактируются только заголовок секции и кнопки.',
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
					)
				),
				wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
				wipe_clean_acf_field( 'number', 'mobile_limit', 'Сколько карточек показывать на mobile' ),
				wipe_clean_acf_field( 'link', 'primary_action', 'Основная кнопка' ),
				wipe_clean_acf_field( 'link', 'secondary_action', 'Вторичная кнопка' ),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_archive_acf_layouts' ) ) {
	function wipe_clean_get_blog_archive_acf_layouts() {
		$layouts = array(
			wipe_clean_get_blog_archive_acf_layout_blog_archive(),
			function_exists( 'wipe_clean_get_front_page_acf_layout_contacts' ) ? wipe_clean_get_front_page_acf_layout_contacts() : null,
		);

		return array_values(
			array_filter(
				$layouts,
				static function ( $layout ) {
					return is_array( $layout ) && ! empty( $layout );
				}
			)
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_single_acf_layouts' ) ) {
	function wipe_clean_get_blog_single_acf_layouts() {
		$layouts = array(
			wipe_clean_get_blog_single_acf_layout_blog_article_hero(),
			wipe_clean_get_blog_single_acf_layout_blog_article_content(),
			wipe_clean_get_blog_single_acf_layout_related_posts(),
			function_exists( 'wipe_clean_get_front_page_acf_layout_contacts' ) ? wipe_clean_get_front_page_acf_layout_contacts() : null,
		);

		return array_values(
			array_filter(
				$layouts,
				static function ( $layout ) {
					return is_array( $layout ) && ! empty( $layout );
				}
			)
		);
	}
}

if ( ! function_exists( 'wipe_clean_register_blog_archive_acf_fields' ) ) {
	function wipe_clean_register_blog_archive_acf_fields() {
		if ( ! function_exists( 'wipe_clean_sync_acf_field_group' ) ) {
			return;
		}

		wipe_clean_sync_acf_field_group(
			array(
				'key'      => 'group_wipe_clean_blog_archive',
				'title'    => 'Архив блога',
				'fields'   => array(
					array(
						'key'       => 'field_wipe_clean_blog_archive_note',
						'label'     => 'Как устроен архив',
						'name'      => '',
						'type'      => 'message',
						'message'   => '<strong>Карточки статей</strong> в архиве подтягиваются из самих записей CPT <strong>Блог</strong>: заголовок, excerpt, дата и миниатюра. Здесь редактируются только секции самой страницы архива.',
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
					),
					array(
						'key'          => 'field_wipe_clean_blog_archive_sections',
						'label'        => 'Секции архива блога',
						'name'         => 'blog_archive_sections',
						'type'         => 'flexible_content',
						'button_label' => 'Добавить блок',
						'layouts'      => wipe_clean_get_blog_archive_acf_layouts(),
					),
				),
				'location' => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => wipe_clean_get_blog_archive_options_slug(),
						),
					),
				),
				'position' => 'acf_after_title',
				'style'    => 'seamless',
			)
		);
	}
}
add_action( 'acf/init', 'wipe_clean_register_blog_archive_acf_fields' );

if ( ! function_exists( 'wipe_clean_register_blog_post_acf_fields' ) ) {
	function wipe_clean_register_blog_post_acf_fields() {
		if ( ! function_exists( 'wipe_clean_sync_acf_field_group' ) ) {
			return;
		}

		wipe_clean_sync_acf_field_group(
			array(
				'key'      => 'group_wipe_clean_blog_post',
				'title'    => 'Страница статьи блога',
				'fields'   => array(
					array(
						'key'       => 'field_wipe_clean_blog_post_note',
						'label'     => 'Как устроена статья',
						'name'      => '',
						'type'      => 'message',
						'message'   => '<strong>Карточка статьи</strong> в архиве и в блоке рекомендаций собирается автоматически из title, excerpt, featured image и даты самой записи CPT <strong>Блог</strong>. Ниже редактируются секции single-страницы и fallback-контент.',
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
					),
					array(
						'key'          => 'field_wipe_clean_blog_post_sections',
						'label'        => 'Секции статьи',
						'name'         => 'blog_post_sections',
						'type'         => 'flexible_content',
						'button_label' => 'Добавить блок',
						'layouts'      => wipe_clean_get_blog_single_acf_layouts(),
					),
				),
				'location' => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => wipe_clean_get_blog_post_type(),
						),
					),
				),
				'position' => 'acf_after_title',
				'style'    => 'seamless',
			)
		);
	}
}
add_action( 'acf/init', 'wipe_clean_register_blog_post_acf_fields' );
