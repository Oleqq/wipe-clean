<?php
/**
 * ACF layout: front page wave group.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_get_front_page_acf_layout_home_wave_group() {
	return array(
		'key'        => 'layout_home_wave_group',
		'name'       => 'home_wave_group',
		'label'      => 'Wave-группа главной',
		'display'    => 'block',
		'sub_fields' => array(
			wipe_clean_acf_field(
				'group',
				'price_preview',
				'Блок цен',
				array(
					'layout'     => 'block',
					'sub_fields' => array(
						wipe_clean_acf_field( 'image', 'image', 'Изображение', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
						wipe_clean_acf_field( 'text', 'title_accent', 'Акцент заголовка' ),
						wipe_clean_acf_field( 'text', 'title', 'Основной заголовок' ),
						wipe_clean_acf_field( 'textarea', 'text', 'Описание', array( 'rows' => 4 ) ),
						wipe_clean_acf_repeater(
							'rows',
							'Строки прайса',
							array(
								wipe_clean_acf_field( 'text', 'label', 'Название' ),
								wipe_clean_acf_field( 'text', 'value', 'Стоимость' ),
							)
						),
						wipe_clean_acf_field( 'link', 'primary_button', 'Главная кнопка' ),
						wipe_clean_acf_field( 'link', 'secondary_button', 'Вторичная кнопка' ),
					),
				)
			),
			wipe_clean_acf_field(
				'group',
				'work_steps',
				'Этапы работы',
				array(
					'layout'     => 'block',
					'sub_fields' => array(
						wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
						wipe_clean_acf_field( 'textarea', 'text', 'Описание', array( 'rows' => 3 ) ),
						wipe_clean_acf_repeater(
							'items',
							'Этапы',
							array(
								wipe_clean_acf_field( 'text', 'number', 'Номер' ),
								wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
								wipe_clean_acf_field( 'textarea', 'text', 'Описание', array( 'rows' => 3 ) ),
							)
						),
					),
				)
			),
			wipe_clean_acf_field(
				'group',
				'quote_request',
				'Форма расчёта',
				array(
					'layout'     => 'block',
					'sub_fields' => array(
						wipe_clean_acf_field( 'image', 'image', 'Изображение', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
						wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
						wipe_clean_acf_field( 'textarea', 'text', 'Описание', array( 'rows' => 4 ) ),
						wipe_clean_acf_field( 'text', 'name_label', 'Подпись имени' ),
						wipe_clean_acf_field( 'text', 'name_placeholder', 'Плейсхолдер имени' ),
						wipe_clean_acf_field( 'text', 'phone_label', 'Подпись телефона' ),
						wipe_clean_acf_field( 'text', 'phone_placeholder', 'Плейсхолдер телефона' ),
						wipe_clean_acf_field( 'textarea', 'agreement_text', 'Текст согласия', array( 'rows' => 2 ) ),
						wipe_clean_acf_field( 'text', 'submit_text', 'Текст кнопки' ),
					),
				)
			),
		),
	);
}
