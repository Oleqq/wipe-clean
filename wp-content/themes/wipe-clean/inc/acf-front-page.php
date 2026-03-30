<?php
/**
 * ACF bootstrap for the front page.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wipe_clean_front_page_acf_files = array(
	__DIR__ . '/acf/front-page/home-hero.php',
	__DIR__ . '/acf/front-page/services-preview.php',
	__DIR__ . '/acf/front-page/home-wave-group.php',
	__DIR__ . '/acf/front-page/company-preview.php',
	__DIR__ . '/acf/front-page/reviews-preview.php',
	__DIR__ . '/acf/front-page/gallery-preview.php',
	__DIR__ . '/acf/front-page/faq.php',
	__DIR__ . '/acf/front-page/contacts.php',
);

foreach ( $wipe_clean_front_page_acf_files as $wipe_clean_front_page_acf_file ) {
	if ( file_exists( $wipe_clean_front_page_acf_file ) ) {
		require_once $wipe_clean_front_page_acf_file;
	}
}

/**
 * Get available front-page flexible layouts.
 *
 * @return array<int, array<string, mixed>>
 */
function wipe_clean_get_front_page_acf_layouts() {
	$layouts = array(
		function_exists( 'wipe_clean_get_front_page_acf_layout_home_hero' ) ? wipe_clean_get_front_page_acf_layout_home_hero() : null,
		function_exists( 'wipe_clean_get_front_page_acf_layout_services_preview' ) ? wipe_clean_get_front_page_acf_layout_services_preview() : null,
		function_exists( 'wipe_clean_get_front_page_acf_layout_home_wave_group' ) ? wipe_clean_get_front_page_acf_layout_home_wave_group() : null,
		function_exists( 'wipe_clean_get_front_page_acf_layout_company_preview' ) ? wipe_clean_get_front_page_acf_layout_company_preview() : null,
		function_exists( 'wipe_clean_get_front_page_acf_layout_reviews_preview' ) ? wipe_clean_get_front_page_acf_layout_reviews_preview() : null,
		function_exists( 'wipe_clean_get_front_page_acf_layout_gallery_preview' ) ? wipe_clean_get_front_page_acf_layout_gallery_preview() : null,
		function_exists( 'wipe_clean_get_front_page_acf_layout_faq' ) ? wipe_clean_get_front_page_acf_layout_faq() : null,
		function_exists( 'wipe_clean_get_front_page_acf_layout_contacts' ) ? wipe_clean_get_front_page_acf_layout_contacts() : null,
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

/**
 * Register ACF fields for the front-page flexible content.
 *
 * @return void
 */
function wipe_clean_register_front_page_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'      => 'group_wipe_clean_front_page',
			'title'    => 'Главная страница: блоки',
			'fields'   => array(
				wipe_clean_acf_field(
					'message',
					'front_page_sections_note',
					'Как работать с блоками',
					array(
						'message'   => '<strong>Главная страница</strong> собирается из готовых блоков. Пометка <strong>Основная</strong> показывает блоки, которые обычно нужны на главной. Пометка <strong>Есть и на других страницах</strong> показывает блоки, которые используются и в других разделах сайта. У каждого блока есть миниатюра, чтобы было проще выбрать нужный.',
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
					)
				),
				wipe_clean_acf_field(
					'flexible_content',
					'front_page_sections',
					'Блоки главной страницы',
					array(
						'button_label' => 'Добавить блок',
						'layouts'      => wipe_clean_get_front_page_acf_layouts(),
					)
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'page_type',
						'operator' => '==',
						'value'    => 'front_page',
					),
				),
				array(
					array(
						'param'    => 'page_template',
						'operator' => '==',
						'value'    => 'template-home-page.php',
					),
				),
			),
			'position' => 'acf_after_title',
			'style'    => 'seamless',
		)
	);
}
add_action( 'acf/init', 'wipe_clean_register_front_page_acf_fields' );
