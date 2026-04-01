<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_get_services_page_acf_layout_contacts() {
	return array(
		'key'        => 'layout_wipe_clean_services_contacts',
		'name'       => 'contacts',
		'label'      => 'Контакты',
		'display'    => 'block',
		'sub_fields' => array(
			array(
				'key'   => 'field_wipe_clean_services_contacts_title',
				'label' => 'Заголовок',
				'name'  => 'title',
				'type'  => 'text',
			),
			array(
				'key'       => 'field_wipe_clean_services_contacts_text',
				'label'     => 'Текст',
				'name'      => 'text',
				'type'      => 'textarea',
				'rows'      => 5,
				'new_lines' => 'br',
			),
			array(
				'key'   => 'field_wipe_clean_services_contacts_form_title',
				'label' => 'Заголовок формы',
				'name'  => 'form_title',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_wipe_clean_services_contacts_submit_text',
				'label' => 'Текст кнопки',
				'name'  => 'submit_text',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_wipe_clean_services_contacts_submit_text_mobile',
				'label' => 'Текст кнопки на мобильном',
				'name'  => 'submit_text_mobile',
				'type'  => 'text',
			),
		),
	);
}
