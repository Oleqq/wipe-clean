<?php
/**
 * ACF layout: front page hero.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_get_front_page_acf_layout_home_hero() {
	return array(
		'key'        => 'layout_home_hero',
		'name'       => 'home_hero',
		'label'      => 'Главный экран',
		'display'    => 'block',
		'sub_fields' => array(
			wipe_clean_acf_tab( 'Контент', 'home_hero_content_tab' ),
			wipe_clean_acf_field( 'text', 'kicker', 'Кикер' ),
			wipe_clean_acf_field( 'textarea', 'title', 'Заголовок', array( 'rows' => 3 ) ),
			wipe_clean_acf_field( 'textarea', 'text', 'Текст', array( 'rows' => 3 ) ),
			wipe_clean_acf_tab( 'Форма', 'home_hero_form_tab' ),
			wipe_clean_acf_field( 'text', 'area_title', 'Подпись площади' ),
			wipe_clean_acf_repeater(
				'area_options',
				'Варианты площади',
				array(
					wipe_clean_acf_field( 'text', 'label', 'Текст' ),
					wipe_clean_acf_field( 'text', 'value', 'Значение' ),
				)
			),
			wipe_clean_acf_field( 'text', 'service_label', 'Подпись услуги' ),
			wipe_clean_acf_repeater(
				'service_options',
				'Варианты услуги',
				array(
					wipe_clean_acf_field( 'text', 'label', 'Текст' ),
					wipe_clean_acf_field( 'text', 'value', 'Значение' ),
				)
			),
			wipe_clean_acf_field( 'text', 'service_default', 'Услуга по умолчанию' ),
			wipe_clean_acf_field( 'text', 'frequency_label', 'Подпись регулярности' ),
			wipe_clean_acf_repeater(
				'frequency_options',
				'Варианты регулярности',
				array(
					wipe_clean_acf_field( 'text', 'label', 'Текст' ),
					wipe_clean_acf_field( 'text', 'value', 'Значение' ),
				)
			),
			wipe_clean_acf_field( 'text', 'frequency_default', 'Регулярность по умолчанию' ),
			wipe_clean_acf_field( 'text', 'name_label', 'Подпись поля имени' ),
			wipe_clean_acf_field( 'text', 'name_placeholder', 'Плейсхолдер имени' ),
			wipe_clean_acf_field( 'text', 'phone_label', 'Подпись поля телефона' ),
			wipe_clean_acf_field( 'text', 'phone_placeholder', 'Плейсхолдер телефона' ),
			wipe_clean_acf_field( 'textarea', 'agreement_text', 'Текст согласия', array( 'rows' => 2 ) ),
			wipe_clean_acf_field( 'text', 'submit_text', 'Текст кнопки' ),
			wipe_clean_acf_tab( 'Медиа и карточки', 'home_hero_media_tab' ),
			wipe_clean_acf_field( 'image', 'tools_image', 'Изображение инструментов', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
			wipe_clean_acf_field( 'image', 'room_image', 'Изображение комнаты', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
			wipe_clean_acf_field( 'image', 'cleaner_image', 'Изображение клинера', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
			wipe_clean_acf_repeater(
				'benefits',
				'Карточки преимуществ',
				array(
					wipe_clean_acf_field( 'image', 'icon', 'Иконка', array( 'return_format' => 'array', 'preview_size' => 'thumbnail' ) ),
					wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
				)
			),
		),
	);
}
