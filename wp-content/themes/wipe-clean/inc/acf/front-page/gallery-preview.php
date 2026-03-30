<?php
/**
 * ACF layout: front page gallery preview.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_get_front_page_acf_layout_gallery_preview() {
	return array(
		'key'        => 'layout_gallery_preview',
		'name'       => 'gallery_preview',
		'label'      => 'Галерея',
		'display'    => 'block',
		'sub_fields' => array(
			wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
			wipe_clean_acf_repeater(
				'top_items',
				'Верхняя строка',
				array(
					wipe_clean_acf_field( 'select', 'type', 'Тип', array( 'choices' => array( 'image' => 'Изображение', 'video' => 'Видео' ), 'default_value' => 'image' ) ),
					wipe_clean_acf_field( 'image', 'image', 'Изображение / постер', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
					wipe_clean_acf_field( 'url', 'video_url', 'URL видео' ),
					wipe_clean_acf_field( 'text', 'caption', 'Подпись' ),
				)
			),
			wipe_clean_acf_repeater(
				'bottom_items',
				'Нижняя строка',
				array(
					wipe_clean_acf_field( 'select', 'type', 'Тип', array( 'choices' => array( 'image' => 'Изображение', 'video' => 'Видео' ), 'default_value' => 'image' ) ),
					wipe_clean_acf_field( 'image', 'image', 'Изображение / постер', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
					wipe_clean_acf_field( 'url', 'video_url', 'URL видео' ),
					wipe_clean_acf_field( 'text', 'caption', 'Подпись' ),
				)
			),
		),
	);
}
