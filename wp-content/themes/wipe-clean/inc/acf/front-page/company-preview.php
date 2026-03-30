<?php
/**
 * ACF layout: front page company preview.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_get_front_page_acf_layout_company_preview() {
	return array(
		'key'        => 'layout_company_preview',
		'name'       => 'company_preview',
		'label'      => 'О компании',
		'display'    => 'block',
		'sub_fields' => array(
			wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
			wipe_clean_acf_field( 'textarea', 'text_primary', 'Первый абзац', array( 'rows' => 4 ) ),
			wipe_clean_acf_field( 'textarea', 'text_secondary', 'Второй абзац', array( 'rows' => 4 ) ),
			wipe_clean_acf_field( 'image', 'media_image', 'Основное изображение', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
			wipe_clean_acf_field( 'image', 'logo_image', 'Логотип-карточка', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
			wipe_clean_acf_repeater(
				'benefits',
				'Преимущества',
				array(
					wipe_clean_acf_field( 'image', 'icon', 'Иконка', array( 'return_format' => 'array', 'preview_size' => 'thumbnail' ) ),
					wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
					wipe_clean_acf_field( 'textarea', 'text', 'Описание', array( 'rows' => 2 ) ),
				)
			),
		),
	);
}
