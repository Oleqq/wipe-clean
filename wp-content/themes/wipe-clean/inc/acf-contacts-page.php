<?php
/**
 * ACF bootstrap for the contacts page.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_register_contacts_page_slug_location_rule_values' ) ) {
	function wipe_clean_register_contacts_page_slug_location_rule_values( $choices ) {
		$choices['contacts'] = 'contacts';

		return $choices;
	}
}
add_filter( 'acf/location/rule_values/wipe_clean_page_slug', 'wipe_clean_register_contacts_page_slug_location_rule_values' );

if ( ! function_exists( 'wipe_clean_get_contacts_page_acf_layout_contacts_hero' ) ) {
	function wipe_clean_get_contacts_page_acf_layout_contacts_hero() {
		return array(
			'key'        => 'layout_contacts_page_contacts_hero',
			'name'       => 'contacts_hero',
			'label'      => 'Первый экран контактов',
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
						'rows'      => 4,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_field( 'text', 'phone_label', 'Подпись телефона' ),
				wipe_clean_acf_field( 'text', 'phone_value', 'Телефон' ),
				wipe_clean_acf_field( 'text', 'socials_label', 'Подпись соцсетей' ),
				wipe_clean_acf_repeater(
					'social_links',
					'Соцсети',
					array(
						wipe_clean_acf_field( 'text', 'label', 'Название' ),
						wipe_clean_acf_field( 'url', 'url', 'Ссылка' ),
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
				wipe_clean_acf_field( 'text', 'email_label', 'Подпись email' ),
				wipe_clean_acf_field( 'text', 'email_value', 'Email' ),
				wipe_clean_acf_field( 'text', 'form_name_label', 'Подпись поля имени' ),
				wipe_clean_acf_field( 'text', 'form_name_placeholder', 'Плейсхолдер имени' ),
				wipe_clean_acf_field( 'text', 'form_phone_label', 'Подпись поля телефона' ),
				wipe_clean_acf_field( 'text', 'form_phone_placeholder', 'Плейсхолдер телефона' ),
				wipe_clean_acf_field(
					'textarea',
					'agreement_text',
					'Текст согласия',
					array(
						'rows'      => 2,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_field( 'text', 'submit_text', 'Кнопка desktop' ),
				wipe_clean_acf_field( 'text', 'submit_text_mobile', 'Кнопка mobile' ),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_contacts_page_acf_layout_company_requisites_band' ) ) {
	function wipe_clean_get_contacts_page_acf_layout_company_requisites_band() {
		return array(
			'key'        => 'layout_contacts_page_company_requisites_band',
			'name'       => 'company_requisites_band',
			'label'      => 'Реквизиты компании',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field(
					'message',
					'company_requisites_band_note',
					'Как работает блок',
					array(
						'message'   => 'Блок реквизитов уже зафиксирован по структуре из статичной страницы. Здесь редактируются только подписи и значения, без лишних технических настроек.',
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
					)
				),
				wipe_clean_acf_field( 'text', 'company_label', 'Подпись 1' ),
				wipe_clean_acf_field( 'text', 'company_value', 'Значение 1' ),
				wipe_clean_acf_field( 'text', 'ogrn_label', 'Подпись 2' ),
				wipe_clean_acf_field( 'text', 'ogrn_value', 'Значение 2' ),
				wipe_clean_acf_field( 'text', 'inn_label', 'Подпись 3' ),
				wipe_clean_acf_field( 'text', 'inn_value', 'Значение 3' ),
				wipe_clean_acf_field( 'text', 'kpp_label', 'Подпись 4' ),
				wipe_clean_acf_field( 'text', 'kpp_value', 'Значение 4' ),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_contacts_page_acf_layout_reviews_preview' ) ) {
	function wipe_clean_get_contacts_page_acf_layout_reviews_preview() {
		return array(
			'key'        => 'layout_contacts_page_reviews_preview',
			'name'       => 'reviews_preview',
			'label'      => 'Превью отзывов',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field(
					'message',
					'contacts_reviews_preview_note',
					'Как работает блок',
					array(
						'message'   => 'Карточки в превью уже подготовлены из статичной страницы Контактов. Контент-менеджер меняет здесь только заголовок, текст и кнопки секции.',
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
					)
				),
				wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
				wipe_clean_acf_field( 'textarea', 'text', 'Текст', array( 'rows' => 4 ) ),
				wipe_clean_acf_field( 'link', 'primary_action', 'Первая кнопка' ),
				wipe_clean_acf_field( 'link', 'secondary_action', 'Вторая кнопка' ),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_contacts_page_acf_layout_faq' ) ) {
	function wipe_clean_get_contacts_page_acf_layout_faq() {
		return array(
			'key'        => 'layout_contacts_page_faq',
			'name'       => 'faq',
			'label'      => 'FAQ',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field(
					'message',
					'contacts_faq_note',
					'Как работает блок',
					array(
						'message'   => 'На странице Контакты редактор меняет только список вопросов и заголовок секции. Раскладка FAQ и закрытое состояние по умолчанию уже зашиты в шаблон.',
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
					)
				),
				wipe_clean_acf_field( 'text', 'title', 'Заголовок секции' ),
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

if ( ! function_exists( 'wipe_clean_register_contacts_page_acf_fields' ) ) {
	function wipe_clean_register_contacts_page_acf_fields() {
		if ( ! function_exists( 'wipe_clean_sync_acf_field_group' ) ) {
			return;
		}

		$layouts = array(
			wipe_clean_get_contacts_page_acf_layout_contacts_hero(),
			wipe_clean_get_contacts_page_acf_layout_company_requisites_band(),
			wipe_clean_get_contacts_page_acf_layout_reviews_preview(),
			wipe_clean_get_contacts_page_acf_layout_faq(),
		);

		wipe_clean_sync_acf_field_group(
			array(
				'key'      => 'group_wipe_clean_contacts_page',
				'title'    => 'Контакты: блоки',
				'fields'   => array(
					wipe_clean_acf_field(
						'message',
						'contacts_page_sections_note',
						'Как работать с блоками',
						array(
							'message'   => '<strong>Страница Контакты</strong> собирается из готовых секций по статичной верстке: первый экран с формой, реквизиты, превью отзывов и FAQ. Здесь нет технических полей для колонок, анимаций и разметки, чтобы редактору было проще работать с контентом.',
							'esc_html'  => 0,
							'new_lines' => 'wpautop',
						)
					),
					wipe_clean_acf_field(
						'flexible_content',
						'contacts_page_sections',
						'Блоки страницы Контакты',
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
							'value'    => 'template-contacts-page.php',
						),
					),
					array(
						array(
							'param'    => 'wipe_clean_page_slug',
							'operator' => '==',
							'value'    => 'contacts',
						),
					),
				),
				'position' => 'acf_after_title',
				'style'    => 'seamless',
			)
		);
	}
}
add_action( 'acf/init', 'wipe_clean_register_contacts_page_acf_fields' );
