<?php
/**
 * ACF bootstrap for the reviews archive.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_register_reviews_archive_options_page' ) ) {
	function wipe_clean_register_reviews_archive_options_page() {
		if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
			return;
		}

		acf_add_options_sub_page(
			array(
				'page_title'  => 'Архив отзывов',
				'menu_title'  => 'Настройки архива',
				'menu_slug'   => wipe_clean_get_reviews_archive_options_slug(),
				'parent_slug' => 'edit.php?post_type=' . wipe_clean_get_reviews_post_type(),
				'capability'  => 'edit_posts',
				'redirect'    => false,
				'position'    => 99,
			)
		);
	}
}
add_action( 'acf/init', 'wipe_clean_register_reviews_archive_options_page', 5 );

if ( ! function_exists( 'wipe_clean_get_reviews_archive_acf_layout_reviews_archive' ) ) {
	function wipe_clean_get_reviews_archive_acf_layout_reviews_archive() {
		return array(
			'key'        => 'layout_reviews_archive_page_intro',
			'name'       => 'reviews_archive',
			'label'      => 'Текстовые отзывы',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field( 'text', 'kicker', 'Кикер' ),
				wipe_clean_acf_field( 'textarea', 'title', 'Заголовок', array( 'rows' => 2, 'new_lines' => '' ) ),
				wipe_clean_acf_field( 'link', 'top_action', 'Кнопка сверху' ),
				wipe_clean_acf_field( 'text', 'load_more_label', 'Текст кнопки "Показать ещё"' ),
				wipe_clean_acf_field( 'number', 'initial_desktop', 'Сколько карточек сразу на desktop' ),
				wipe_clean_acf_field( 'number', 'initial_mobile', 'Сколько карточек сразу на mobile' ),
				wipe_clean_acf_field( 'number', 'step_desktop', 'Шаг подгрузки на desktop' ),
				wipe_clean_acf_field( 'number', 'step_mobile', 'Шаг подгрузки на mobile' ),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_archive_acf_layout_video_reviews' ) ) {
	function wipe_clean_get_reviews_archive_acf_layout_video_reviews() {
		return array(
			'key'        => 'layout_reviews_archive_video',
			'name'       => 'video_reviews',
			'label'      => 'Видео отзывы',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field(
					'textarea',
					'title',
					'Заголовок',
					array(
						'rows'         => 2,
						'new_lines'    => '',
						'instructions' => 'Каждая новая строка станет новой строкой в заголовке.',
					)
				),
				wipe_clean_acf_field( 'link', 'top_action', 'Кнопка под слайдером' ),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_archive_acf_layout_message_reviews' ) ) {
	function wipe_clean_get_reviews_archive_acf_layout_message_reviews() {
		return array(
			'key'        => 'layout_reviews_archive_message',
			'name'       => 'message_reviews',
			'label'      => 'Фото отзывы / переписки',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
				wipe_clean_acf_field( 'text', 'button_label', 'Текст кнопки "Показать ещё"' ),
				wipe_clean_acf_field( 'number', 'initial_desktop', 'Сколько карточек сразу на desktop' ),
				wipe_clean_acf_field( 'number', 'initial_mobile', 'Сколько карточек сразу на mobile' ),
				wipe_clean_acf_field( 'number', 'step_desktop', 'Шаг подгрузки на desktop' ),
				wipe_clean_acf_field( 'number', 'step_mobile', 'Шаг подгрузки на mobile' ),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_archive_acf_layout_before_after_results' ) ) {
	function wipe_clean_get_reviews_archive_acf_layout_before_after_results() {
		return array(
			'key'        => 'layout_reviews_archive_before_after',
			'name'       => 'before_after_results',
			'label'      => 'До и после',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field( 'textarea', 'title', 'Заголовок', array( 'rows' => 2, 'instructions' => 'Каждая новая строка станет новой строкой в заголовке.' ) ),
				wipe_clean_acf_field(
					'message',
					'before_after_results_note',
					'Как устроен блок',
					array(
						'message'   => 'Это обычная секция страницы. Здесь редактируются только заголовок, текст кнопки и изображения карточек. Позиции ползунка, стартовая загрузка карточек и прочие технические параметры тема выставляет автоматически по fallback-верстке.',
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
					)
				),
				wipe_clean_acf_field( 'text', 'button_label', 'Текст кнопки "Показать ещё"' ),
				wipe_clean_acf_repeater(
					'items',
					'Карточки сравнения',
					array(
						wipe_clean_acf_field( 'image', 'before_image', 'Изображение "До"', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
						wipe_clean_acf_field( 'image', 'after_image', 'Изображение "После"', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
					)
				),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_archive_acf_layouts' ) ) {
	function wipe_clean_get_reviews_archive_acf_layouts() {
		$layouts = array(
			wipe_clean_get_reviews_archive_acf_layout_reviews_archive(),
			wipe_clean_get_reviews_archive_acf_layout_video_reviews(),
			wipe_clean_get_reviews_archive_acf_layout_message_reviews(),
			wipe_clean_get_reviews_archive_acf_layout_before_after_results(),
			function_exists( 'wipe_clean_get_front_page_acf_layout_faq' ) ? wipe_clean_get_front_page_acf_layout_faq() : null,
			function_exists( 'wipe_clean_get_front_page_acf_layout_gallery_preview' ) ? wipe_clean_get_front_page_acf_layout_gallery_preview() : null,
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

if ( ! function_exists( 'wipe_clean_register_reviews_archive_acf_fields' ) ) {
	function wipe_clean_register_reviews_archive_acf_fields() {
		if ( ! function_exists( 'wipe_clean_sync_acf_field_group' ) ) {
			return;
		}

		wipe_clean_sync_acf_field_group(
			array(
				'key'      => 'group_wipe_clean_reviews_archive',
				'title'    => 'Архив отзывов',
				'fields'   => array(
					array(
						'key'       => 'field_wipe_clean_reviews_archive_note',
						'label'     => 'Как устроена страница',
						'name'      => '',
						'type'      => 'message',
						'message'   => '<strong>Карточки отзывов</strong> на странице собираются автоматически из записей CPT <strong>Отзывы</strong>. Тип записи определяет секцию: <strong>Текстовый</strong> попадает в блок текстовых отзывов, <strong>Видео</strong> в видео-секцию, <strong>Фото / переписка</strong> в секцию рекомендаций из переписок. Здесь редактируются только обычные ACF-секции самой страницы архива.',
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
					),
					array(
						'key'          => 'field_wipe_clean_reviews_archive_sections',
						'label'        => 'Секции архива отзывов',
						'name'         => 'reviews_archive_sections',
						'type'         => 'flexible_content',
						'button_label' => 'Добавить блок',
						'layouts'      => wipe_clean_get_reviews_archive_acf_layouts(),
					),
				),
				'location' => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => wipe_clean_get_reviews_archive_options_slug(),
						),
					),
				),
				'position' => 'acf_after_title',
				'style'    => 'seamless',
			)
		);
	}
}
add_action( 'acf/init', 'wipe_clean_register_reviews_archive_acf_fields' );
