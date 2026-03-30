<?php
/**
 * ACF layout: front page services preview.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_get_front_page_acf_layout_services_preview() {
	return array(
		'key'        => 'layout_services_preview',
		'name'       => 'services_preview',
		'label'      => 'Услуги',
		'display'    => 'block',
		'sub_fields' => array(
			wipe_clean_acf_field(
				'message',
				'services_preview_note',
				'Как работает блок',
				array(
					'message'   => 'Карточки услуг берутся из записей раздела <strong>Услуги главной страницы</strong>. Здесь редактируются только заголовок, текст и кнопки блока.',
					'esc_html'  => 0,
					'new_lines' => 'wpautop',
				)
			),
			wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
			wipe_clean_acf_field( 'textarea', 'text', 'Текст', array( 'rows' => 4 ) ),
			wipe_clean_acf_field( 'textarea', 'note_text', 'Подсказка под карточками', array( 'rows' => 3 ) ),
			wipe_clean_acf_field( 'link', 'primary_action', 'Первая кнопка' ),
			wipe_clean_acf_field( 'link', 'secondary_action', 'Вторая кнопка' ),
		),
	);
}
