<?php
/**
 * ACF layout: front page wave group.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_get_home_wave_group_valid_acf_field( $field ) {
	if ( function_exists( 'acf_get_valid_field' ) ) {
		$field = acf_get_valid_field( $field );
	}

	$field            = is_array( $field ) ? $field : array();
	$field['wrapper'] = wp_parse_args(
		isset( $field['wrapper'] ) && is_array( $field['wrapper'] ) ? $field['wrapper'] : array(),
		array(
			'width' => '',
			'class' => '',
			'id'    => '',
		)
	);
	$field['class']   = isset( $field['class'] ) ? (string) $field['class'] : '';

	return $field;
}

function wipe_clean_get_home_wave_group_work_steps_item_fields() {
	return array(
		wipe_clean_get_home_wave_group_valid_acf_field( wipe_clean_acf_field( 'text', 'number', 'Номер' ) ),
		wipe_clean_get_home_wave_group_valid_acf_field( wipe_clean_acf_field( 'text', 'title', 'Заголовок этапа' ) ),
		wipe_clean_get_home_wave_group_valid_acf_field( wipe_clean_acf_field( 'textarea', 'text', 'Короткое описание', array( 'rows' => 3 ) ) ),
	);
}

function wipe_clean_normalize_home_wave_group_work_steps_items_field( $field ) {
	$work_steps_item_fields = wipe_clean_get_home_wave_group_work_steps_item_fields();

	$field                  = wipe_clean_get_home_wave_group_valid_acf_field( $field );
	$field['sub_fields']    = $work_steps_item_fields;
	$field['instructions']  = 'Для каждого этапа заполните номер, заголовок и короткое описание карточки.';
	$field['layout']        = 'block';
	$field['button_label']  = 'Добавить этап';
	$field['collapsed']     = $work_steps_item_fields[1]['key'];

	return $field;
}

function wipe_clean_filter_home_wave_group_work_steps_items_field( $field ) {
	$is_target_field = isset( $field['type'], $field['name'] ) && 'repeater' === $field['type'] && 'items' === $field['name'];

	if ( ! $is_target_field ) {
		return $field;
	}

	$matches_layout = isset( $field['parent_layout'] ) && 'layout_home_wave_group' === $field['parent_layout'];
	$matches_label  = isset( $field['label'] ) && 'Этапы' === $field['label'];

	if ( ! $matches_layout && ! $matches_label ) {
		return $field;
	}

	return wipe_clean_normalize_home_wave_group_work_steps_items_field( $field );
}
add_filter( 'acf/load_field/name=items', 'wipe_clean_filter_home_wave_group_work_steps_items_field', 20 );

function wipe_clean_get_home_wave_group_work_steps_items_repeater() {
	return wipe_clean_normalize_home_wave_group_work_steps_items_field(
		wipe_clean_acf_repeater(
			'items',
			'Этапы',
			wipe_clean_get_home_wave_group_work_steps_item_fields()
		)
	);
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
						wipe_clean_get_home_wave_group_work_steps_items_repeater(),
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
