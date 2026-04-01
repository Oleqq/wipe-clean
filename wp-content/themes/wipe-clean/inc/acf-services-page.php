<?php
/**
 * ACF bootstrap for the services archive settings.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wipe_clean_services_page_acf_files = array(
	__DIR__ . '/acf/services-page/services-intro.php',
	__DIR__ . '/acf/services-page/services-benefits.php',
	__DIR__ . '/acf/services-page/faq.php',
	__DIR__ . '/acf/services-page/contacts.php',
);

foreach ( $wipe_clean_services_page_acf_files as $wipe_clean_services_page_acf_file ) {
	if ( file_exists( $wipe_clean_services_page_acf_file ) ) {
		require_once $wipe_clean_services_page_acf_file;
	}
}

if ( ! function_exists( 'wipe_clean_register_services_archive_options_page' ) ) {
	function wipe_clean_register_services_archive_options_page() {
		if ( ! function_exists( 'acf_add_options_sub_page' ) || ! function_exists( 'wipe_clean_get_services_archive_options_slug' ) ) {
			return;
		}

		acf_add_options_sub_page(
			array(
				'page_title'  => 'Архив услуг',
				'menu_title'  => 'Настройки архива',
				'menu_slug'   => wipe_clean_get_services_archive_options_slug(),
				'parent_slug' => 'edit.php?post_type=wipe_service',
				'capability'  => 'edit_posts',
				'redirect'    => false,
				'position'    => 99,
			)
		);
	}
}
add_action( 'acf/init', 'wipe_clean_register_services_archive_options_page', 5 );

function wipe_clean_get_services_page_acf_layouts() {
	$layouts = array(
		function_exists( 'wipe_clean_get_services_page_acf_layout_services_intro' ) ? wipe_clean_get_services_page_acf_layout_services_intro() : null,
		function_exists( 'wipe_clean_get_services_page_acf_layout_services_benefits' ) ? wipe_clean_get_services_page_acf_layout_services_benefits() : null,
		function_exists( 'wipe_clean_get_services_page_acf_layout_faq' ) ? wipe_clean_get_services_page_acf_layout_faq() : null,
		function_exists( 'wipe_clean_get_services_page_acf_layout_contacts' ) ? wipe_clean_get_services_page_acf_layout_contacts() : null,
	);

	return array_values(
		array_filter(
			$layouts,
			static function ( $layout ) {
				return is_array( $layout ) && ! empty( $layout );
			}
		)
	);
}

function wipe_clean_register_services_page_acf_fields() {
	if ( ! function_exists( 'wipe_clean_sync_acf_field_group' ) || ! function_exists( 'wipe_clean_get_services_archive_options_slug' ) ) {
		return;
	}

	wipe_clean_sync_acf_field_group(
		array(
			'key'      => 'group_wipe_clean_services_page',
			'title'    => 'Архив услуг',
			'fields'   => array(
				array(
					'key'       => 'field_wipe_clean_services_page_note',
					'label'     => 'Как устроен архив',
					'name'      => '',
					'type'      => 'message',
					'message'   => '<strong>Карточки услуг</strong> в верхнем блоке собираются автоматически из самих услуг: название, краткое описание, изображение записи и цена. Здесь редактируются только блоки самой страницы архива.',
					'esc_html'  => 0,
					'new_lines' => 'wpautop',
				),
				array(
					'key'          => 'field_wipe_clean_services_page_sections',
					'label'        => 'Блоки архива услуг',
					'name'         => 'services_page_sections',
					'type'         => 'flexible_content',
					'button_label' => 'Добавить блок',
					'layouts'      => wipe_clean_get_services_page_acf_layouts(),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => wipe_clean_get_services_archive_options_slug(),
					),
				),
			),
			'position' => 'acf_after_title',
			'style'    => 'seamless',
		)
	);
}
add_action( 'acf/init', 'wipe_clean_register_services_page_acf_fields' );
