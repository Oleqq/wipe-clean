<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_get_services_page_acf_layout_faq() {
	return array(
		'key'        => 'layout_wipe_clean_services_faq',
		'name'       => 'faq',
		'label'      => 'Вопросы и ответы',
		'display'    => 'block',
		'sub_fields' => array(
			array(
				'key'   => 'field_wipe_clean_services_faq_title',
				'label' => 'Заголовок',
				'name'  => 'title',
				'type'  => 'text',
			),
			array(
				'key'          => 'field_wipe_clean_services_faq_items',
				'label'        => 'Вопросы',
				'name'         => 'items',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Добавить вопрос',
				'sub_fields'   => array(
					array(
						'key'   => 'field_wipe_clean_services_faq_items_question',
						'label' => 'Вопрос',
						'name'  => 'question',
						'type'  => 'text',
					),
					array(
						'key'       => 'field_wipe_clean_services_faq_items_answer',
						'label'     => 'Ответ',
						'name'      => 'answer',
						'type'      => 'textarea',
						'rows'      => 4,
						'new_lines' => 'br',
					),
				),
			),
		),
	);
}
