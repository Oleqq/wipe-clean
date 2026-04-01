<?php
/**
 * ACF bootstrap for the prices page.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_register_page_slug_location_rule_type' ) ) {
	function wipe_clean_register_page_slug_location_rule_type( $choices ) {
		$choices['Page']['wipe_clean_page_slug'] = 'Слаг страницы';

		return $choices;
	}
}
add_filter( 'acf/location/rule_types', 'wipe_clean_register_page_slug_location_rule_type' );

if ( ! function_exists( 'wipe_clean_register_page_slug_location_rule_values' ) ) {
	function wipe_clean_register_page_slug_location_rule_values( $choices ) {
		$choices['prices'] = 'prices';

		return $choices;
	}
}
add_filter( 'acf/location/rule_values/wipe_clean_page_slug', 'wipe_clean_register_page_slug_location_rule_values' );

if ( ! function_exists( 'wipe_clean_match_page_slug_location_rule' ) ) {
	function wipe_clean_match_page_slug_location_rule( $match, $rule, $options ) {
		$post_id = 0;

		if ( ! empty( $options['post_id'] ) ) {
			$post_id = (int) $options['post_id'];
		}

		if ( ! $post_id || 'page' !== get_post_type( $post_id ) ) {
			return false;
		}

		$page_slug = (string) get_post_field( 'post_name', $post_id );
		$expected  = (string) ( $rule['value'] ?? '' );
		$result    = $page_slug === $expected;

		if ( '!=' === ( $rule['operator'] ?? '==' ) ) {
			return ! $result;
		}

		return $result;
	}
}
add_filter( 'acf/location/rule_match/wipe_clean_page_slug', 'wipe_clean_match_page_slug_location_rule', 10, 3 );

if ( ! function_exists( 'wipe_clean_get_prices_page_acf_layout_prices_hero' ) ) {
	function wipe_clean_get_prices_page_acf_layout_prices_hero() {
		return array(
			'key'        => 'layout_prices_hero',
			'name'       => 'prices_hero',
			'label'      => 'Первый экран цен',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field( 'text', 'kicker', 'Кикер' ),
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
						'rows'      => 4,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_field( 'link', 'primary_action', 'Кнопка' ),
				wipe_clean_acf_field(
					'image',
					'left_image',
					'Левая декоративная картинка',
					array(
						'return_format' => 'array',
						'preview_size'  => 'medium',
					)
				),
				wipe_clean_acf_field(
					'image',
					'right_image',
					'Правая декоративная картинка',
					array(
						'return_format' => 'array',
						'preview_size'  => 'medium',
					)
				),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_prices_page_acf_layout_services_preview' ) ) {
	function wipe_clean_get_prices_page_acf_layout_services_preview() {
		return array(
			'key'        => 'layout_prices_services_preview',
			'name'       => 'prices_services_preview',
			'label'      => 'Услуги на странице цен',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field(
					'message',
					'services_preview_notice',
					'Как работает секция',
					array(
						'message'   => 'Карточки услуг здесь собираются автоматически из записей CPT <strong>Услуги</strong>. Контент-менеджер редактирует только заголовок, тексты и кнопки секции.',
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
					'Основной текст',
					array(
						'rows'      => 4,
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

if ( ! function_exists( 'wipe_clean_get_prices_page_acf_layout_area_pricing' ) ) {
	function wipe_clean_get_prices_page_acf_layout_area_pricing() {
		return array(
			'key'        => 'layout_area_pricing',
			'name'       => 'area_pricing',
			'label'      => 'Прайс по метражу',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
				wipe_clean_acf_field(
					'textarea',
					'text',
					'Текст',
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
				wipe_clean_acf_repeater(
					'items',
					'Строки прайса',
					array(
						wipe_clean_acf_field( 'text', 'label', 'Название' ),
						wipe_clean_acf_field( 'text', 'value', 'Цена' ),
					)
				),
				wipe_clean_acf_repeater(
					'cards',
					'Дополнительные карточки',
					array(
						wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
						wipe_clean_acf_field(
							'textarea',
							'text',
							'Текст',
							array(
								'rows'      => 4,
								'new_lines' => '',
							)
						),
						wipe_clean_acf_field( 'text', 'accent_text', 'Акцентная строка' ),
						wipe_clean_acf_field(
							'textarea',
							'accent_lines',
							'Список акцентов',
							array(
								'rows'         => 3,
								'new_lines'    => '',
								'instructions' => 'Если нужно несколько строк, укажите каждую с новой строки.',
							)
						),
					)
				),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_prices_page_acf_layout_price_factors' ) ) {
	function wipe_clean_get_prices_page_acf_layout_price_factors() {
		return array(
			'key'        => 'layout_price_factors',
			'name'       => 'price_factors',
			'label'      => 'Что влияет на цену',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
				wipe_clean_acf_field(
					'textarea',
					'text',
					'Текст',
					array(
						'rows'      => 4,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_field( 'link', 'primary_action', 'Кнопка' ),
				wipe_clean_acf_repeater(
					'items',
					'Карточки факторов',
					array(
						wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
						wipe_clean_acf_field(
							'textarea',
							'text',
							'Текст',
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

if ( ! function_exists( 'wipe_clean_get_prices_page_acf_layout_price_advantages' ) ) {
	function wipe_clean_get_prices_page_acf_layout_price_advantages() {
		return array(
			'key'        => 'layout_price_advantages',
			'name'       => 'price_advantages',
			'label'      => 'Преимущества',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
				wipe_clean_acf_field(
					'textarea',
					'text',
					'Текст',
					array(
						'rows'      => 4,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_field(
					'textarea',
					'note_text',
					'Подзаголовок над карточками',
					array(
						'rows'      => 3,
						'new_lines' => '',
					)
				),
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
								'rows'      => 4,
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

if ( ! function_exists( 'wipe_clean_get_prices_page_acf_layout_company_highlight' ) ) {
	function wipe_clean_get_prices_page_acf_layout_company_highlight() {
		return array(
			'key'        => 'layout_company_highlight',
			'name'       => 'company_highlight',
			'label'      => 'О компании',
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
						'rows'      => 3,
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
					'textarea',
					'note_text',
					'Акцентный текст',
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

if ( ! function_exists( 'wipe_clean_get_prices_page_acf_layouts' ) ) {
	function wipe_clean_get_prices_page_acf_layouts() {
		$layouts = array(
			wipe_clean_get_prices_page_acf_layout_prices_hero(),
			wipe_clean_get_prices_page_acf_layout_services_preview(),
			wipe_clean_get_prices_page_acf_layout_area_pricing(),
			wipe_clean_get_prices_page_acf_layout_price_factors(),
			wipe_clean_get_prices_page_acf_layout_price_advantages(),
			wipe_clean_get_prices_page_acf_layout_company_highlight(),
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

if ( ! function_exists( 'wipe_clean_register_prices_page_acf_fields' ) ) {
	function wipe_clean_register_prices_page_acf_fields() {
		if ( ! function_exists( 'wipe_clean_sync_acf_field_group' ) ) {
			return;
		}

		wipe_clean_sync_acf_field_group(
			array(
				'key'      => 'group_wipe_clean_prices_page',
				'title'    => 'Страница цен',
				'fields'   => array(
					array(
						'key'       => 'field_wipe_clean_prices_page_note',
						'label'     => 'Как устроена страница',
						'name'      => '',
						'type'      => 'message',
						'message'   => '<strong>Карточки услуг</strong> в секции цен подтягиваются автоматически из CPT <strong>Услуги</strong>. Здесь редактируются только обычные секции страницы.',
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
					),
					array(
						'key'          => 'field_wipe_clean_prices_page_sections',
						'label'        => 'Секции страницы цен',
						'name'         => 'prices_page_sections',
						'type'         => 'flexible_content',
						'button_label' => 'Добавить блок',
						'layouts'      => wipe_clean_get_prices_page_acf_layouts(),
					),
				),
				'location' => array(
					array(
						array(
							'param'    => 'page_template',
							'operator' => '==',
							'value'    => 'template-prices-page.php',
						),
					),
					array(
						array(
							'param'    => 'wipe_clean_page_slug',
							'operator' => '==',
							'value'    => 'prices',
						),
					),
				),
				'position' => 'acf_after_title',
				'style'    => 'seamless',
			)
		);
	}
}
add_action( 'acf/init', 'wipe_clean_register_prices_page_acf_fields' );
