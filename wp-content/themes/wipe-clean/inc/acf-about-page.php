<?php
/**
 * ACF bootstrap for the about page.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_register_about_page_slug_location_rule_values' ) ) {
	function wipe_clean_register_about_page_slug_location_rule_values( $choices ) {
		$choices['about-us'] = 'about-us';
		$choices['about']    = 'about';

		return $choices;
	}
}
add_filter( 'acf/location/rule_values/wipe_clean_page_slug', 'wipe_clean_register_about_page_slug_location_rule_values' );

if ( ! function_exists( 'wipe_clean_get_about_page_acf_layout_about_hero' ) ) {
	function wipe_clean_get_about_page_acf_layout_about_hero() {
		return array(
			'key'        => 'layout_about_page_about_hero',
			'name'       => 'about_hero',
			'label'      => 'Первый экран',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field( 'text', 'kicker', 'Кикер' ),
				wipe_clean_acf_field(
					'textarea',
					'title',
					'Заголовок',
					array(
						'rows'      => 3,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_field(
					'textarea',
					'text',
					'Текст',
					array(
						'rows'      => 5,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_field( 'link', 'primary_action', 'Основная кнопка' ),
				wipe_clean_acf_field( 'link', 'secondary_action', 'Вторичная кнопка' ),
				wipe_clean_acf_field(
					'image',
					'decor_image',
					'Декоративное изображение',
					array(
						'return_format' => 'array',
						'preview_size'  => 'medium',
					)
				),
				wipe_clean_acf_field(
					'image',
					'main_image',
					'Главное изображение',
					array(
						'return_format' => 'array',
						'preview_size'  => 'medium',
					)
				),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_about_page_acf_layout_company_story' ) ) {
	function wipe_clean_get_about_page_acf_layout_company_story() {
		return array(
			'key'        => 'layout_about_page_company_story',
			'name'       => 'company_story',
			'label'      => 'История компании',
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
					'summary_text',
					'Короткий текст',
					array(
						'rows'      => 4,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_field(
					'wysiwyg',
					'body_content',
					'Основной текст',
					array(
						'tabs'         => 'all',
						'toolbar'      => 'basic',
						'media_upload' => 0,
						'delay'        => 1,
					)
				),
				wipe_clean_acf_field(
					'wysiwyg',
					'featured_content',
					'Выделенный текст',
					array(
						'tabs'         => 'all',
						'toolbar'      => 'basic',
						'media_upload' => 0,
						'delay'        => 1,
					)
				),
				wipe_clean_acf_field(
					'textarea',
					'note_text',
					'Нижний акцентный текст',
					array(
						'rows'      => 4,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_field(
					'image',
					'image',
					'Изображение',
					array(
						'return_format' => 'array',
						'preview_size'  => 'medium',
					)
				),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_about_page_acf_layout_work_approach' ) ) {
	function wipe_clean_get_about_page_acf_layout_work_approach() {
		return array(
			'key'        => 'layout_about_page_work_approach',
			'name'       => 'work_approach',
			'label'      => 'Подход к работе',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
				wipe_clean_acf_field(
					'textarea',
					'text',
					'Текст',
					array(
						'rows'      => 5,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_field( 'link', 'primary_action', 'Кнопка' ),
				wipe_clean_acf_repeater(
					'items',
					'Карточки подхода',
					array(
						wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
						wipe_clean_acf_field(
							'textarea',
							'text',
							'Текст',
							array(
								'rows'      => 5,
								'new_lines' => '',
							)
						),
						wipe_clean_acf_field(
							'image',
							'icon',
							'Иконка',
							array(
								'return_format' => 'array',
								'preview_size'  => 'thumbnail',
							)
						),
					)
				),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_about_page_acf_layout_team_preview' ) ) {
	function wipe_clean_get_about_page_acf_layout_team_preview() {
		return array(
			'key'        => 'layout_about_page_team_preview',
			'name'       => 'team_preview',
			'label'      => 'Команда',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
				wipe_clean_acf_field(
					'textarea',
					'text',
					'Текст',
					array(
						'rows'      => 5,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_repeater(
					'items',
					'Сотрудники',
					array(
						wipe_clean_acf_field( 'text', 'name', 'Имя' ),
						wipe_clean_acf_field(
							'image',
							'image',
							'Фото',
							array(
								'return_format' => 'array',
								'preview_size'  => 'medium',
							)
						),
					)
				),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_about_page_acf_layout_why_us' ) ) {
	function wipe_clean_get_about_page_acf_layout_why_us() {
		return array(
			'key'        => 'layout_about_page_why_us',
			'name'       => 'why_us',
			'label'      => 'Почему выбирают нас',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
				wipe_clean_acf_field(
					'textarea',
					'text_primary',
					'Основной текст',
					array(
						'rows'      => 5,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_field(
					'textarea',
					'text_secondary',
					'Подзаголовок перед карточками',
					array(
						'rows'      => 3,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_field( 'link', 'primary_action', 'Кнопка' ),
				wipe_clean_acf_repeater(
					'items',
					'Карточки преимуществ',
					array(
						wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
						wipe_clean_acf_field(
							'textarea',
							'text',
							'Текст',
							array(
								'rows'      => 5,
								'new_lines' => '',
							)
						),
						wipe_clean_acf_field(
							'image',
							'icon',
							'Иконка',
							array(
								'return_format' => 'array',
								'preview_size'  => 'thumbnail',
							)
						),
					)
				),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_about_page_acf_layout_services_preview' ) ) {
	function wipe_clean_get_about_page_acf_layout_services_preview() {
		return array(
			'key'        => 'layout_about_page_services_preview',
			'name'       => 'about_services_preview',
			'label'      => 'Превью услуг',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field(
					'message',
					'services_preview_notice',
					'Как работает секция',
					array(
						'message'   => 'Карточки услуг здесь не заполняются вручную. Они подтягиваются автоматически из раздела <strong>Услуги</strong>. В этом блоке редактируются только тексты и кнопки.',
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
					)
				),
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
					'summary_text',
					'Короткий текст',
					array(
						'rows'      => 4,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_field(
					'textarea',
					'text',
					'Полный текст',
					array(
						'rows'      => 6,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_field(
					'textarea',
					'note_text',
					'Текст под карточками',
					array(
						'rows'      => 4,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_field( 'link', 'primary_action', 'Основная кнопка' ),
				wipe_clean_acf_field( 'link', 'secondary_action', 'Вторичная кнопка' ),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_about_page_acf_layout_reviews_preview' ) ) {
	function wipe_clean_get_about_page_acf_layout_reviews_preview() {
		return array(
			'key'        => 'layout_about_page_reviews_preview',
			'name'       => 'about_reviews_preview',
			'label'      => 'Превью отзывов',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field(
					'message',
					'reviews_preview_notice',
					'Как работает секция',
					array(
						'message'   => 'Текстовые карточки отзывов подтягиваются автоматически из раздела <strong>Отзывы</strong>. Вручную здесь редактируются только тексты и кнопки секции.',
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
					)
				),
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
					'text',
					'Текст',
					array(
						'rows'      => 5,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_field( 'link', 'primary_action', 'Основная кнопка' ),
				wipe_clean_acf_field( 'link', 'secondary_action', 'Вторичная кнопка' ),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_about_page_acf_layout_order_cta' ) ) {
	function wipe_clean_get_about_page_acf_layout_order_cta() {
		return array(
			'key'        => 'layout_about_page_order_cta',
			'name'       => 'about_order_cta',
			'label'      => 'CTA-блок',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field(
					'textarea',
					'title',
					'Заголовок',
					array(
						'rows'      => 3,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_field(
					'textarea',
					'text',
					'Текст',
					array(
						'rows'      => 6,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_field( 'link', 'primary_action', 'Основная кнопка' ),
				wipe_clean_acf_field( 'link', 'secondary_action', 'Вторичная кнопка' ),
				wipe_clean_acf_field(
					'image',
					'image',
					'Изображение',
					array(
						'return_format' => 'array',
						'preview_size'  => 'medium',
					)
				),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_about_page_acf_layouts' ) ) {
	function wipe_clean_get_about_page_acf_layouts() {
		$layouts = array(
			wipe_clean_get_about_page_acf_layout_about_hero(),
			wipe_clean_get_about_page_acf_layout_company_story(),
			wipe_clean_get_about_page_acf_layout_work_approach(),
			wipe_clean_get_about_page_acf_layout_team_preview(),
			wipe_clean_get_about_page_acf_layout_why_us(),
			wipe_clean_get_about_page_acf_layout_services_preview(),
			wipe_clean_get_about_page_acf_layout_reviews_preview(),
			wipe_clean_get_about_page_acf_layout_order_cta(),
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

if ( ! function_exists( 'wipe_clean_register_about_page_acf_fields' ) ) {
	function wipe_clean_register_about_page_acf_fields() {
		if ( ! function_exists( 'wipe_clean_sync_acf_field_group' ) ) {
			return;
		}

		wipe_clean_sync_acf_field_group(
			array(
				'key'      => 'group_wipe_clean_about_page',
				'title'    => 'О компании',
				'fields'   => array(
					array(
						'key'       => 'field_wipe_clean_about_page_note',
						'label'     => 'Как устроена страница',
						'name'      => '',
						'type'      => 'message',
						'message'   => '<strong>Карточки услуг и отзывов</strong> в этой странице подтягиваются автоматически из уже существующих разделов сайта. Здесь редактируются только обычные секции самой страницы <strong>О компании</strong>.',
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
					),
					array(
						'key'          => 'field_wipe_clean_about_page_sections',
						'label'        => 'Секции страницы О компании',
						'name'         => 'about_page_sections',
						'type'         => 'flexible_content',
						'button_label' => 'Добавить блок',
						'layouts'      => wipe_clean_get_about_page_acf_layouts(),
					),
				),
				'location' => array(
					array(
						array(
							'param'    => 'page_template',
							'operator' => '==',
							'value'    => 'template-about-page.php',
						),
					),
					array(
						array(
							'param'    => 'wipe_clean_page_slug',
							'operator' => '==',
							'value'    => 'about-us',
						),
					),
					array(
						array(
							'param'    => 'wipe_clean_page_slug',
							'operator' => '==',
							'value'    => 'about',
						),
					),
				),
				'position' => 'acf_after_title',
				'style'    => 'seamless',
			)
		);
	}
}
add_action( 'acf/init', 'wipe_clean_register_about_page_acf_fields' );
