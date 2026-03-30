<?php
/**
 * ACF layout: front page contacts.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_get_front_page_acf_layout_contacts() {
	return array(
		'key'        => 'layout_contacts',
		'name'       => 'contacts',
		'label'      => 'Контакты',
		'display'    => 'block',
		'sub_fields' => array(
			wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
			wipe_clean_acf_field( 'text', 'phone_label', 'Подпись телефона' ),
			wipe_clean_acf_field( 'text', 'phone_value', 'Телефон' ),
			wipe_clean_acf_field( 'text', 'socials_label', 'Подпись соцсетей' ),
			wipe_clean_acf_repeater(
				'social_links',
				'Соцсети',
				array(
					wipe_clean_acf_field( 'text', 'label', 'Название' ),
					wipe_clean_acf_field( 'url', 'url', 'Ссылка' ),
					wipe_clean_acf_field( 'image', 'icon', 'Иконка', array( 'return_format' => 'array', 'preview_size' => 'thumbnail' ) ),
				)
			),
			wipe_clean_acf_field( 'text', 'email_label', 'Подпись email' ),
			wipe_clean_acf_field( 'text', 'email_value', 'Email' ),
			wipe_clean_acf_field( 'text', 'form_title', 'Заголовок формы' ),
			wipe_clean_acf_field( 'text', 'form_name_label', 'Подпись имени' ),
			wipe_clean_acf_field( 'text', 'form_name_placeholder', 'Плейсхолдер имени' ),
			wipe_clean_acf_field( 'text', 'form_phone_label', 'Подпись телефона' ),
			wipe_clean_acf_field( 'text', 'form_phone_placeholder', 'Плейсхолдер телефона' ),
			wipe_clean_acf_field( 'textarea', 'agreement_text', 'Текст согласия', array( 'rows' => 2 ) ),
			wipe_clean_acf_field( 'text', 'submit_text', 'Кнопка desktop' ),
			wipe_clean_acf_field( 'text', 'submit_text_mobile', 'Кнопка mobile' ),
		),
	);
}
