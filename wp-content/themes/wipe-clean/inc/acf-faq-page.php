<?php
/**
 * ACF bootstrap for the FAQ page.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_register_faq_page_slug_location_rule_values' ) ) {
	function wipe_clean_register_faq_page_slug_location_rule_values( $choices ) {
		$choices['faq'] = 'faq';

		return $choices;
	}
}
add_filter( 'acf/location/rule_values/wipe_clean_page_slug', 'wipe_clean_register_faq_page_slug_location_rule_values' );

if ( ! function_exists( 'wipe_clean_get_faq_page_acf_layout_faq_hero' ) ) {
	function wipe_clean_get_faq_page_acf_layout_faq_hero() {
		return array(
			'key'        => 'layout_faq_page_faq_hero',
			'name'       => 'faq_hero',
			'label'      => 'Первый экран FAQ',
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
				wipe_clean_acf_field( 'link', 'primary_action', 'Кнопка' ),
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

if ( ! function_exists( 'wipe_clean_get_faq_page_acf_layout_faq' ) ) {
	function wipe_clean_get_faq_page_acf_layout_faq() {
		return array(
			'key'        => 'layout_faq_page_faq',
			'name'       => 'faq',
			'label'      => 'FAQ',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field(
					'message',
					'faq_page_notice',
					'Как работает секция',
					array(
						'message'   => 'На странице FAQ контент-менеджер меняет только вопросы, ответы и кнопку. Техническая раскладка аккордеона и открытие первого вопроса уже зашиты в шаблон, чтобы не перегружать редактор.',
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
					)
				),
				wipe_clean_acf_field(
					'text',
					'title',
					'Заголовок секции',
					array(
						'instructions' => 'Можно оставить пустым, чтобы секция выглядела как в статике, без дополнительного заголовка.',
					)
				),
				wipe_clean_acf_field( 'link', 'primary_action', 'Кнопка под FAQ' ),
				wipe_clean_acf_repeater(
					'items',
					'Вопросы',
					array(
						wipe_clean_acf_field( 'text', 'question', 'Вопрос' ),
						wipe_clean_acf_field(
							'textarea',
							'answer',
							'Ответ',
							array(
								'rows'      => 4,
								'new_lines' => '',
							)
						),
					)
				),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_register_faq_page_acf_fields' ) ) {
	function wipe_clean_register_faq_page_acf_fields() {
		if ( ! function_exists( 'wipe_clean_sync_acf_field_group' ) ) {
			return;
		}

		$layouts = array(
			wipe_clean_get_faq_page_acf_layout_faq_hero(),
			wipe_clean_get_faq_page_acf_layout_faq(),
			function_exists( 'wipe_clean_get_front_page_acf_layout_contacts' ) ? wipe_clean_get_front_page_acf_layout_contacts() : null,
		);

		$layouts = array_values(
			array_filter(
				$layouts,
				static function ( $layout ) {
					return is_array( $layout ) && ! empty( $layout );
				}
			)
		);

		wipe_clean_sync_acf_field_group(
			array(
				'key'      => 'group_wipe_clean_faq_page',
				'title'    => 'FAQ: блоки',
				'fields'   => array(
					wipe_clean_acf_field(
						'message',
						'faq_page_sections_note',
						'Как работать с блоками',
						array(
							'message'   => '<strong>Страница FAQ</strong> собирается из готовых блоков. Первый экран, список вопросов и контакты уже подготовлены под верстку из статики. Лишние технические настройки здесь не выводятся: редактор меняет только тексты, вопросы, ответы и кнопки.',
							'esc_html'  => 0,
							'new_lines' => 'wpautop',
						)
					),
					wipe_clean_acf_field(
						'flexible_content',
						'faq_page_sections',
						'Блоки страницы FAQ',
						array(
							'button_label' => 'Добавить блок',
							'layouts'      => $layouts,
						)
					),
				),
				'location' => array(
					array(
						array(
							'param'    => 'page_template',
							'operator' => '==',
							'value'    => 'template-faq-page.php',
						),
					),
					array(
						array(
							'param'    => 'wipe_clean_page_slug',
							'operator' => '==',
							'value'    => 'faq',
						),
					),
				),
				'position' => 'acf_after_title',
				'style'    => 'seamless',
			)
		);
	}
}
add_action( 'acf/init', 'wipe_clean_register_faq_page_acf_fields' );
