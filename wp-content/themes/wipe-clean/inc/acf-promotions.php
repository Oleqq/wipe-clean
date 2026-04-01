<?php
/**
 * ACF bootstrap for the promotions archive.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_register_promotions_archive_options_page' ) ) {
	function wipe_clean_register_promotions_archive_options_page() {
		if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
			return;
		}

		acf_add_options_sub_page(
			array(
				'page_title'  => 'Архив акций',
				'menu_title'  => 'Настройки архива',
				'menu_slug'   => wipe_clean_get_promotions_archive_options_slug(),
				'parent_slug' => 'edit.php?post_type=' . wipe_clean_get_promotions_post_type(),
				'capability'  => 'edit_posts',
				'redirect'    => false,
				'position'    => 99,
			)
		);
	}
}
add_action( 'acf/init', 'wipe_clean_register_promotions_archive_options_page', 5 );

if ( ! function_exists( 'wipe_clean_get_promotions_archive_acf_layout_promotions_archive' ) ) {
	function wipe_clean_get_promotions_archive_acf_layout_promotions_archive() {
		return array(
			'key'        => 'layout_promotions_archive',
			'name'       => 'promotions_archive',
			'label'      => 'Архив акций',
			'display'    => 'block',
			'sub_fields' => array(
				wipe_clean_acf_field( 'text', 'kicker', 'Кикер' ),
				wipe_clean_acf_field(
					'textarea',
					'title',
					'Заголовок',
					array(
						'rows'      => 2,
						'new_lines' => '',
					)
				),
				wipe_clean_acf_field( 'link', 'primary_action', 'Основная кнопка' ),
				wipe_clean_acf_field( 'text', 'button_label', 'Текст кнопки "Показать ещё"' ),
				wipe_clean_acf_field( 'text', 'button_loading_label', 'Текст кнопки при загрузке' ),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_promotions_archive_acf_layouts' ) ) {
	function wipe_clean_get_promotions_archive_acf_layouts() {
		$layouts = array(
			wipe_clean_get_promotions_archive_acf_layout_promotions_archive(),
			function_exists( 'wipe_clean_get_front_page_acf_layout_company_preview' ) ? wipe_clean_get_front_page_acf_layout_company_preview() : null,
			function_exists( 'wipe_clean_get_front_page_acf_layout_contacts' ) ? wipe_clean_get_front_page_acf_layout_contacts() : null,
			function_exists( 'wipe_clean_get_front_page_acf_layout_faq' ) ? wipe_clean_get_front_page_acf_layout_faq() : null,
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
}

if ( ! function_exists( 'wipe_clean_register_promotions_archive_acf_fields' ) ) {
	function wipe_clean_register_promotions_archive_acf_fields() {
		if ( ! function_exists( 'wipe_clean_sync_acf_field_group' ) ) {
			return;
		}

		wipe_clean_sync_acf_field_group(
			array(
				'key'      => 'group_wipe_clean_promotions_archive',
				'title'    => 'Архив акций',
				'fields'   => array(
					array(
						'key'       => 'field_wipe_clean_promotions_archive_note',
						'label'     => 'Как устроен архив',
						'name'      => '',
						'type'      => 'message',
						'message'   => '<strong>Карточки акций</strong> и их pop-up содержимое собираются автоматически из записей CPT <strong>Акции</strong>. Публичных single-страниц у этих записей нет: на сайте акция открывается только во всплывающем окне. Здесь редактируются только обычные секции самой страницы архива.',
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
					),
					array(
						'key'          => 'field_wipe_clean_promotions_archive_sections',
						'label'        => 'Секции архива акций',
						'name'         => 'promotions_archive_sections',
						'type'         => 'flexible_content',
						'button_label' => 'Добавить блок',
						'layouts'      => wipe_clean_get_promotions_archive_acf_layouts(),
					),
				),
				'location' => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => wipe_clean_get_promotions_archive_options_slug(),
						),
					),
				),
				'position' => 'acf_after_title',
				'style'    => 'seamless',
			)
		);
	}
}
add_action( 'acf/init', 'wipe_clean_register_promotions_archive_acf_fields' );
