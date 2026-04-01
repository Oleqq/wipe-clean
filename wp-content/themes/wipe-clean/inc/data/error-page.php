<?php
/**
 * Default content for the 404 page and document pages.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_get_shared_contact_panel_defaults' ) ) {
	function wipe_clean_get_shared_contact_panel_defaults( $overrides = array() ) {
		$contacts_defaults = function_exists( 'wipe_clean_get_front_page_section_defaults' )
			? wipe_clean_get_front_page_section_defaults( 'contacts' )
			: array();

		$defaults = array(
			'title'                  => 'Контакты клининговой компании ВАЙП–Клин',
			'form_title'             => (string) ( $contacts_defaults['form_title'] ?? 'Форма заявки' ),
			'id_prefix'              => 'contact-panel',
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
		);

		$merged = array_replace_recursive( $defaults, is_array( $overrides ) ? $overrides : array() );

		if ( isset( $overrides['social_links'] ) && is_array( $overrides['social_links'] ) ) {
			$merged['social_links'] = array_values( $overrides['social_links'] );
		}

		return $merged;
	}
}

if ( ! function_exists( 'wipe_clean_get_error_page_default_data' ) ) {
	function wipe_clean_get_error_page_default_data() {
		return array(
			'kicker'           => 'Ой ...',
			'title'            => 'Страница не найдена',
			'text'             => 'Воспользуйтесь навигационным меню для перехода на другие страницы',
			'primary_action'   => wipe_clean_theme_link( home_url( '/' ), 'На главную' ),
			'secondary_action' => wipe_clean_theme_link( home_url( '/services/' ), 'Наши услуги' ),
			'visual_image'     => wipe_clean_theme_image( 'static/images/section/error-404/png.png' ),
			'contact_panel'    => wipe_clean_get_shared_contact_panel_defaults(
				array(
					'id_prefix'  => 'error-404',
					'title'      => 'Контакты клининговой компании ВАЙП–Клин',
					'form_title' => 'Форма заявки',
				)
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_document_page_default_title' ) ) {
	function wipe_clean_get_document_page_default_title( $post_id = 0 ) {
		$post_slug = function_exists( 'wipe_clean_get_document_page_slug' )
			? wipe_clean_get_document_page_slug( $post_id )
			: '';

		if ( 'policy' === $post_slug ) {
			return 'Политика в отношении обработки персональных данных';
		}

		return 'Название документа';
	}
}

if ( ! function_exists( 'wipe_clean_get_document_page_default_content_html' ) ) {
	function wipe_clean_get_document_page_default_content_html() {
		$paragraphs = array(
			'Lorem Ipsum - это текст-"рыба", часто используемый в печати и веб-дизайне. Lorem Ipsum является стандартной "рыбой" для текстов на латинице с начала XVI века. В то время некий безымянный печатник создал большую коллекцию размеров и форм шрифтов, используя Lorem Ipsum для распечатки образцов.',
			'Lorem Ipsum не только успешно пережил без заметных изменений пять веков, но и перешагнул в электронный дизайн. Его популяризации в новое время послужили публикация листов Letraset с образцами Lorem Ipsum и программы вёрстки.',
			'Здесь будет располагаться основной текст документа: политика обработки персональных данных, пользовательское соглашение, реквизиты, правила акций или другие юридические материалы компании.',
			'При необходимости контент-менеджер может полностью заменить этот текст на реальный документ через обычный редактор WordPress, не заполняя технические поля и не работая с ACF-блоками.',
		);

		return implode(
			'',
			array_map(
				static function ( $paragraph ) {
					return '<p>' . esc_html( $paragraph ) . '</p>';
				},
				$paragraphs
			)
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_document_page_contact_panel_defaults' ) ) {
	function wipe_clean_get_document_page_contact_panel_defaults( $post_id = 0 ) {
		$post_slug = function_exists( 'wipe_clean_get_document_page_slug' )
			? wipe_clean_get_document_page_slug( $post_id )
			: '';

		return wipe_clean_get_shared_contact_panel_defaults(
			array(
				'id_prefix'  => $post_slug ? $post_slug : 'document',
				'title'      => 'Контакты клининговой компании ВАЙП–Клин',
				'form_title' => 'Форма заявки',
			)
		);
	}
}
