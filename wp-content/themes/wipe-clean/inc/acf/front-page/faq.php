<?php
/**
 * ACF layout: front page FAQ.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_get_front_page_acf_layout_faq() {
	return array(
		'key'        => 'layout_faq',
		'name'       => 'faq',
		'label'      => 'FAQ',
		'display'    => 'block',
		'sub_fields' => array(
			wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
			wipe_clean_acf_repeater(
				'items',
				'Вопросы',
				array(
					wipe_clean_acf_field( 'text', 'question', 'Вопрос' ),
					wipe_clean_acf_field( 'textarea', 'answer', 'Ответ', array( 'rows' => 4 ) ),
				)
			),
		),
	);
}
