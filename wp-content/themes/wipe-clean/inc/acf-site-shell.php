<?php
/**
 * ACF bootstrap for the global site header and footer.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_register_site_shell_options_pages' ) ) {
	function wipe_clean_register_site_shell_options_pages() {
		if ( ! function_exists( 'acf_add_options_page' ) || ! function_exists( 'acf_add_options_sub_page' ) ) {
			return;
		}

		acf_add_options_page(
			array(
				'page_title' => 'Шапка и подвал',
				'menu_title' => 'Шапка и подвал',
				'menu_slug'  => wipe_clean_get_site_shell_options_slug(),
				'capability' => 'edit_pages',
				'redirect'   => true,
				'position'   => 58,
				'icon_url'   => 'dashicons-editor-kitchensink',
			)
		);

		acf_add_options_sub_page(
			array(
				'page_title'  => 'Шапка сайта',
				'menu_title'  => 'Шапка сайта',
				'menu_slug'   => wipe_clean_get_site_header_options_slug(),
				'parent_slug' => wipe_clean_get_site_shell_options_slug(),
				'capability'  => 'edit_pages',
				'redirect'    => false,
			)
		);

		acf_add_options_sub_page(
			array(
				'page_title'  => 'Подвал сайта',
				'menu_title'  => 'Подвал сайта',
				'menu_slug'   => wipe_clean_get_site_footer_options_slug(),
				'parent_slug' => wipe_clean_get_site_shell_options_slug(),
				'capability'  => 'edit_pages',
				'redirect'    => false,
			)
		);
	}
}
add_action( 'acf/init', 'wipe_clean_register_site_shell_options_pages', 5 );

if ( ! function_exists( 'wipe_clean_register_site_header_acf_fields' ) ) {
	function wipe_clean_register_site_header_acf_fields() {
		if ( ! function_exists( 'wipe_clean_sync_acf_field_group' ) ) {
			return;
		}

		$header_menu_item_context     = 'site_header_menu_item';
		$header_menu_item_label_key   = wipe_clean_acf_key( 'label_text', $header_menu_item_context );
		$header_menu_has_submenu_key  = wipe_clean_acf_key( 'has_submenu_true_false', $header_menu_item_context );
		$header_menu_link_context     = 'site_header_menu_link';
		$header_submenu_link_context  = 'site_header_submenu_link';
		$header_submenu_link_key      = wipe_clean_acf_key( 'link_link', $header_submenu_link_context );

		wipe_clean_sync_acf_field_group(
			array(
				'key'      => 'group_wipe_clean_site_header',
				'title'    => 'Шапка сайта',
				'fields'   => array(
					wipe_clean_acf_field(
						'message',
						'site_header_note',
						'Как устроена шапка',
						array(
							'message'   => '<strong>Здесь настраивается шапка сайта:</strong> логотип, телефон, кнопка и меню.',
							'esc_html'  => 0,
							'new_lines' => 'wpautop',
						)
					),
					wipe_clean_acf_tab( 'Логотип', 'site_header_branding_tab' ),
					wipe_clean_acf_field(
						'image',
						'site_header_brand_mark',
						'Значок логотипа',
						array(
							'return_format' => 'array',
							'preview_size'  => 'medium',
							'instructions'  => 'Картинка слева в логотипе. Если не заполнять, останется готовый вариант сайта.',
						)
					),
					wipe_clean_acf_field(
						'image',
						'site_header_brand_type',
						'Текст рядом с логотипом',
						array(
							'return_format' => 'array',
							'preview_size'  => 'medium',
							'instructions'  => 'Текстовая часть логотипа. Если не заполнять, останется готовый вариант сайта.',
						)
					),
					wipe_clean_acf_tab( 'Телефон и кнопка', 'site_header_contacts_tab' ),
					wipe_clean_acf_field(
						'text',
						'site_header_phone',
						'Телефон',
						array(
							'instructions' => 'Номер телефона в верхней части сайта.',
						)
					),
					wipe_clean_acf_field(
						'link',
						'site_header_cta',
						'Кнопка справа',
						array(
							'instructions' => 'Задайте текст кнопки и страницу, куда она ведёт. Если не заполнять, откроются контакты.',
						)
					),
					wipe_clean_acf_tab( 'Меню', 'site_header_navigation_tab' ),
					wipe_clean_acf_field(
						'message',
						'site_header_navigation_note',
						'Как редактировать меню',
						array(
							'message'   => 'Каждая строка ниже — это один пункт меню. Если включить <strong>Подменю</strong>, ниже можно будет добавить ссылки, которые откроются у этого пункта.',
							'esc_html'  => 0,
							'new_lines' => 'wpautop',
						)
					),
					wipe_clean_acf_repeater(
						'site_header_menu_items',
						'Пункты меню',
						array(
							wipe_clean_acf_field(
								'text',
								'label',
								'Название пункта',
								array(
									'wipe_clean_key_context' => $header_menu_item_context,
									'wrapper'                => array( 'width' => 35 ),
								)
							),
							wipe_clean_acf_field(
								'link',
								'link',
								'Куда ведёт пункт',
								array(
									'wipe_clean_key_context' => $header_menu_link_context,
									'wrapper'                => array( 'width' => 40 ),
									'instructions'           => 'Можно не заполнять, если этот пункт только открывает список ниже.',
								)
							),
							wipe_clean_acf_field(
								'true_false',
								'has_submenu',
								'Подменю',
								array(
									'wipe_clean_key_context' => $header_menu_item_context,
									'ui'                     => 1,
									'wrapper'                => array( 'width' => 12 ),
									'message'                => 'Показывать',
								)
							),
							wipe_clean_acf_field(
								'true_false',
								'mobile_only',
								'Только на телефоне',
								array(
									'wipe_clean_key_context' => $header_menu_item_context,
									'ui'                     => 1,
									'wrapper'                => array( 'width' => 13 ),
									'message'                => 'Показывать только в меню на телефоне',
								)
							),
							wipe_clean_acf_repeater(
								'submenu_links',
								'Пункты подменю',
								array(
									wipe_clean_acf_field(
										'link',
										'link',
										'Куда ведёт пункт',
										array(
											'wipe_clean_key_context' => $header_submenu_link_context,
										)
									),
								),
								array(
									'layout'            => 'row',
									'button_label'      => 'Добавить пункт подменю',
									'collapsed'         => $header_submenu_link_key,
									'conditional_logic' => array(
										array(
											array(
												'field'    => $header_menu_has_submenu_key,
												'operator' => '==',
												'value'    => 1,
											),
										),
									),
									'instructions'      => 'Если ссылок будет много, сайт сам аккуратно разложит их по колонкам.',
								)
							),
						),
						array(
							'layout'       => 'block',
							'button_label' => 'Добавить пункт меню',
							'collapsed'    => $header_menu_item_label_key,
						)
					),
				),
				'location' => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => wipe_clean_get_site_header_options_slug(),
						),
					),
				),
				'position' => 'acf_after_title',
				'style'    => 'seamless',
			)
		);
	}
}
add_action( 'acf/init', 'wipe_clean_register_site_header_acf_fields' );

if ( ! function_exists( 'wipe_clean_register_site_footer_acf_fields' ) ) {
	function wipe_clean_register_site_footer_acf_fields() {
		if ( ! function_exists( 'wipe_clean_sync_acf_field_group' ) ) {
			return;
		}

		$footer_nav_link_context = 'site_footer_nav_link';
		$footer_nav_link_key     = wipe_clean_acf_key( 'link_link', $footer_nav_link_context );

		wipe_clean_sync_acf_field_group(
			array(
				'key'      => 'group_wipe_clean_site_footer',
				'title'    => 'Подвал сайта',
				'fields'   => array(
					wipe_clean_acf_field(
						'message',
						'site_footer_note',
						'Как устроен подвал',
						array(
							'message'   => '<strong>Здесь настраивается подвал сайта:</strong> логотип, реквизиты, меню, контакты, документы и нижняя строка.',
							'esc_html'  => 0,
							'new_lines' => 'wpautop',
						)
					),
					wipe_clean_acf_tab( 'Логотип', 'site_footer_branding_tab' ),
					wipe_clean_acf_field(
						'image',
						'site_footer_brand_mark',
						'Значок логотипа',
						array(
							'return_format' => 'array',
							'preview_size'  => 'medium',
							'instructions'  => 'Необязательно. Если не заполнять, будет использован значок из шапки.',
						)
					),
					wipe_clean_acf_field(
						'image',
						'site_footer_brand_type',
						'Текст рядом с логотипом',
						array(
							'return_format' => 'array',
							'preview_size'  => 'medium',
							'instructions'  => 'Необязательно. Если не заполнять, будет использован текст из шапки.',
						)
					),
					wipe_clean_acf_tab( 'Реквизиты', 'site_footer_requisites_tab' ),
					wipe_clean_acf_repeater(
						'site_footer_requisites',
						'Реквизиты',
						array(
							wipe_clean_acf_field( 'text', 'label', 'Подпись' ),
							wipe_clean_acf_field( 'text', 'value', 'Значение' ),
						),
						array(
							'layout'       => 'row',
							'button_label' => 'Добавить реквизит',
						)
					),
					wipe_clean_acf_tab( 'Меню', 'site_footer_navigation_tab' ),
					wipe_clean_acf_field( 'text', 'site_footer_nav_title', 'Заголовок над ссылками' ),
					wipe_clean_acf_repeater(
						'site_footer_nav_links',
						'Пункты меню',
						array(
							wipe_clean_acf_field(
								'link',
								'link',
								'Куда ведёт пункт',
								array(
									'wipe_clean_key_context' => $footer_nav_link_context,
								)
							),
						),
						array(
							'layout'       => 'row',
							'button_label' => 'Добавить пункт',
							'collapsed'    => $footer_nav_link_key,
							'instructions' => 'Сайт сам аккуратно разложит ссылки по колонкам.',
						)
					),
					wipe_clean_acf_tab( 'Контакты', 'site_footer_contacts_tab' ),
					wipe_clean_acf_field( 'text', 'site_footer_phone_label', 'Подпись над телефоном' ),
					wipe_clean_acf_field(
						'text',
						'site_footer_phone',
						'Телефон',
						array(
							'instructions' => 'Если не заполнять, будет использован телефон из шапки.',
						)
					),
					wipe_clean_acf_field( 'text', 'site_footer_socials_label', 'Подпись над соцсетями' ),
					wipe_clean_acf_repeater(
						'site_footer_social_links',
						'Ссылки на соцсети',
						array(
							wipe_clean_acf_field( 'text', 'label', 'Название' ),
							wipe_clean_acf_field( 'link', 'link', 'Куда ведёт ссылка' ),
							wipe_clean_acf_field(
								'image',
								'icon',
								'Иконка',
								array(
									'return_format' => 'array',
									'preview_size'  => 'thumbnail',
								)
							),
						),
						array(
							'layout'       => 'block',
							'button_label' => 'Добавить соцсеть',
						)
					),
					wipe_clean_acf_field( 'text', 'site_footer_email_label', 'Подпись над почтой' ),
					wipe_clean_acf_field( 'text', 'site_footer_email', 'Email' ),
					wipe_clean_acf_tab( 'Документы', 'site_footer_legal_tab' ),
					wipe_clean_acf_repeater(
						'site_footer_legal_links',
						'Ссылки на документы',
						array(
							wipe_clean_acf_field( 'link', 'link', 'Куда ведёт ссылка' ),
						),
						array(
							'layout'       => 'row',
							'button_label' => 'Добавить ссылку',
						)
					),
					wipe_clean_acf_tab( 'Низ сайта', 'site_footer_bottom_tab' ),
					wipe_clean_acf_field( 'text', 'site_footer_copyright', 'Текст слева' ),
					wipe_clean_acf_field( 'text', 'site_footer_made_by_badge', 'Короткая подпись' ),
					wipe_clean_acf_field(
						'link',
						'site_footer_made_by_link',
						'Ссылка справа',
						array(
							'instructions' => 'Текст ссылки задаётся в поле названия.',
						)
					),
				),
				'location' => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => wipe_clean_get_site_footer_options_slug(),
						),
					),
				),
				'position' => 'acf_after_title',
				'style'    => 'seamless',
			)
		);
	}
}
add_action( 'acf/init', 'wipe_clean_register_site_footer_acf_fields' );
