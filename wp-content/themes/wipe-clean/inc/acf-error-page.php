<?php
/**
 * ACF bootstrap for the 404 page settings.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_register_error_page_options_page' ) ) {
	function wipe_clean_register_error_page_options_page() {
		if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
			return;
		}

		acf_add_options_sub_page(
			array(
				'page_title'  => 'Страница 404',
				'menu_title'  => 'Страница 404',
				'menu_slug'   => wipe_clean_get_error_page_options_slug(),
				'parent_slug' => 'options-general.php',
				'capability'  => 'edit_pages',
				'redirect'    => false,
			)
		);
	}
}
add_action( 'acf/init', 'wipe_clean_register_error_page_options_page', 5 );

if ( ! function_exists( 'wipe_clean_register_error_page_acf_fields' ) ) {
	function wipe_clean_register_error_page_acf_fields() {
		if ( ! function_exists( 'wipe_clean_sync_acf_field_group' ) ) {
			return;
		}

		wipe_clean_sync_acf_field_group(
			array(
				'key'      => 'group_wipe_clean_error_page',
				'title'    => 'Страница 404',
				'fields'   => array(
					wipe_clean_acf_field(
						'message',
						'error_404_note',
						'Как устроена страница',
						array(
							'message'   => '<strong>404-страница</strong> управляется из нативного раздела <strong>Настройки</strong>. Здесь редактируются только понятные контентные поля: тексты, кнопки, изображение и контактный блок. Технической разметки и служебных настроек для контент-менеджера здесь нет.',
							'esc_html'  => 0,
							'new_lines' => 'wpautop',
						)
					),
					wipe_clean_acf_tab( 'Первый экран', 'error_404_hero_tab' ),
					wipe_clean_acf_field( 'text', 'error_404_kicker', 'Кикер' ),
					wipe_clean_acf_field(
						'textarea',
						'error_404_title',
						'Заголовок',
						array(
							'rows'      => 3,
							'new_lines' => '',
						)
					),
					wipe_clean_acf_field(
						'textarea',
						'error_404_text',
						'Текст',
						array(
							'rows'      => 3,
							'new_lines' => '',
						)
					),
					wipe_clean_acf_field( 'link', 'error_404_primary_action', 'Кнопка 1' ),
					wipe_clean_acf_field( 'link', 'error_404_secondary_action', 'Кнопка 2' ),
					wipe_clean_acf_field(
						'image',
						'error_404_visual_image',
						'Изображение',
						array(
							'return_format' => 'array',
							'preview_size'  => 'medium',
						)
					),
					wipe_clean_acf_tab( 'Контактный блок', 'error_404_contact_panel_tab' ),
					wipe_clean_acf_field( 'text', 'contact_panel_title', 'Заголовок блока' ),
					wipe_clean_acf_field( 'text', 'contact_panel_form_title', 'Заголовок формы' ),
					wipe_clean_acf_field( 'text', 'contact_panel_phone_label', 'Подпись телефона' ),
					wipe_clean_acf_field( 'text', 'contact_panel_phone_value', 'Телефон' ),
					wipe_clean_acf_field( 'text', 'contact_panel_socials_label', 'Подпись соцсетей' ),
					wipe_clean_acf_repeater(
						'contact_panel_social_links',
						'Соцсети',
						array(
							wipe_clean_acf_field( 'text', 'label', 'Название' ),
							wipe_clean_acf_field( 'url', 'url', 'Ссылка' ),
							wipe_clean_acf_field(
								'image',
								'icon',
								'Иконка',
								array(
									'return_format' => 'array',
									'preview_size'  => 'thumbnail',
								)
							),
						)
					),
					wipe_clean_acf_field( 'text', 'contact_panel_email_label', 'Подпись email' ),
					wipe_clean_acf_field( 'text', 'contact_panel_email_value', 'Email' ),
					wipe_clean_acf_field( 'text', 'contact_panel_name_label', 'Подпись поля имени' ),
					wipe_clean_acf_field( 'text', 'contact_panel_name_placeholder', 'Плейсхолдер имени' ),
					wipe_clean_acf_field( 'text', 'contact_panel_phone_field_label', 'Подпись поля телефона' ),
					wipe_clean_acf_field( 'text', 'contact_panel_phone_placeholder', 'Плейсхолдер телефона' ),
					wipe_clean_acf_field(
						'textarea',
						'contact_panel_agreement_text',
						'Текст согласия',
						array(
							'rows'      => 2,
							'new_lines' => '',
						)
					),
					wipe_clean_acf_field( 'text', 'contact_panel_submit_text', 'Кнопка desktop' ),
					wipe_clean_acf_field( 'text', 'contact_panel_submit_text_mobile', 'Кнопка mobile' ),
				),
				'location' => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => wipe_clean_get_error_page_options_slug(),
						),
					),
				),
				'position' => 'acf_after_title',
				'style'    => 'seamless',
			)
		);
	}
}
add_action( 'acf/init', 'wipe_clean_register_error_page_acf_fields' );
