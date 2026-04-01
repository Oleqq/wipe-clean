<?php
/**
 * Default content for the contacts page.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_contacts_page_link' ) ) {
	function wipe_clean_contacts_page_link( $title, $url = '#', $target = '' ) {
		return wipe_clean_theme_link( $url, $title, $target );
	}
}

if ( ! function_exists( 'wipe_clean_get_contacts_page_default_review_items' ) ) {
	function wipe_clean_get_contacts_page_default_review_items() {
		$text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';

		return array(
			array(
				'seed_key' => 'contacts-page-review-1',
				'author'   => 'Имя клиента',
				'text'     => $text,
				'rating'   => 5,
			),
			array(
				'seed_key' => 'contacts-page-review-2',
				'author'   => 'Имя клиента',
				'text'     => $text,
				'rating'   => 5,
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_contacts_page_default_sections_map' ) ) {
	function wipe_clean_get_contacts_page_default_sections_map() {
		$contacts_defaults = function_exists( 'wipe_clean_get_front_page_section_defaults' )
			? wipe_clean_get_front_page_section_defaults( 'contacts' )
			: array( 'acf_fc_layout' => 'contacts' );
		$reviews_defaults  = function_exists( 'wipe_clean_get_front_page_section_defaults' )
			? wipe_clean_get_front_page_section_defaults( 'reviews_preview' )
			: array( 'acf_fc_layout' => 'reviews_preview' );
		$faq_defaults      = function_exists( 'wipe_clean_get_front_page_section_defaults' )
			? wipe_clean_get_front_page_section_defaults( 'faq' )
			: array( 'acf_fc_layout' => 'faq' );

		return array(
			'contacts_hero'          => array(
				'acf_fc_layout'          => 'contacts_hero',
				'id_prefix'              => 'contacts-page',
				'kicker'                 => 'Наши контакты',
				'title'                  => 'Наши контакты',
				'text'                   => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
				'phone_label'            => (string) ( $contacts_defaults['phone_label'] ?? 'Номер телефона' ),
				'phone_value'            => (string) ( $contacts_defaults['phone_value'] ?? '+7 980 163 6101' ),
				'socials_label'          => (string) ( $contacts_defaults['socials_label'] ?? 'Мессенджеры и соцсети' ),
				'social_links'           => array_values( $contacts_defaults['social_links'] ?? array() ),
				'email_label'            => (string) ( $contacts_defaults['email_label'] ?? 'Электронная почта' ),
				'email_value'            => (string) ( $contacts_defaults['email_value'] ?? 'MAILBOX@WIPECLEAN.RU' ),
				'form_name_label'        => (string) ( $contacts_defaults['form_name_label'] ?? 'Ваше имя' ),
				'form_name_placeholder'  => (string) ( $contacts_defaults['form_name_placeholder'] ?? 'Введите имя и фамилию' ),
				'form_phone_label'       => (string) ( $contacts_defaults['form_phone_label'] ?? 'Номер телефона' ),
				'form_phone_placeholder' => (string) ( $contacts_defaults['form_phone_placeholder'] ?? '+7 _ _ _ _ _ _ _ _ _ _' ),
				'agreement_text'         => (string) ( $contacts_defaults['agreement_text'] ?? 'Заполняя форму вы даете согласие на обработку персональных данных' ),
				'submit_text'            => (string) ( $contacts_defaults['submit_text'] ?? 'Узнать стоимость клининга' ),
				'submit_text_mobile'     => (string) ( $contacts_defaults['submit_text_mobile'] ?? 'Рассчитать стоимость' ),
			),
			'company_requisites_band' => array(
				'acf_fc_layout' => 'company_requisites_band',
				'company_label' => 'ООО',
				'company_value' => '«ВАЙП–Клин»',
				'ogrn_label'    => 'ОГРН',
				'ogrn_value'    => '0000000000000',
				'inn_label'     => 'ИНН',
				'inn_value'     => '0000000000',
				'kpp_label'     => 'КПП',
				'kpp_value'     => '000000000',
			),
			'reviews_preview'        => array_merge(
				(array) $reviews_defaults,
				array(
					'acf_fc_layout'    => 'reviews_preview',
					'title'            => 'Отзывы наших клиентов',
					'text'             => 'Нам доверяют уборку квартир, домов и офисов в Москве, а многие клиенты обращаются к нам регулярно и рекомендуют ВАЙП–Клин своим близким.',
					'items'            => wipe_clean_get_contacts_page_default_review_items(),
					'primary_action'   => wipe_clean_contacts_page_link( 'Ознакомиться с другими отзывами', home_url( '/reviews/' ) ),
					'secondary_action' => wipe_clean_contacts_page_link( 'Оставить отзыв о компании', '#popup-review' ),
				)
			),
			'faq'                    => array_merge(
				(array) $faq_defaults,
				array(
					'acf_fc_layout'          => 'faq',
					'title'                  => 'Ответы на вопросы о клининге в Москве',
					'initial_open_index'     => -1,
					'mobile_initial_open_index' => -1,
				)
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_contacts_page_section_defaults' ) ) {
	function wipe_clean_get_contacts_page_section_defaults( $layout ) {
		$defaults_map = wipe_clean_get_contacts_page_default_sections_map();

		return $defaults_map[ $layout ] ?? array(
			'acf_fc_layout' => $layout,
		);
	}
}
