<?php
/**
 * Global site header and footer settings.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_get_site_shell_options_slug' ) ) {
	function wipe_clean_get_site_shell_options_slug() {
		return 'wipe-clean-site-shell';
	}
}

if ( ! function_exists( 'wipe_clean_get_site_header_options_slug' ) ) {
	function wipe_clean_get_site_header_options_slug() {
		return 'wipe-clean-site-header';
	}
}

if ( ! function_exists( 'wipe_clean_get_site_footer_options_slug' ) ) {
	function wipe_clean_get_site_footer_options_slug() {
		return 'wipe-clean-site-footer';
	}
}

if ( ! function_exists( 'wipe_clean_get_site_header_settings_url' ) ) {
	function wipe_clean_get_site_header_settings_url() {
		$slug = wipe_clean_get_site_header_options_slug();
		$url  = function_exists( 'menu_page_url' ) ? menu_page_url( $slug, false ) : '';

		if ( ! $url ) {
			$url = admin_url( 'admin.php?page=' . $slug );
		}

		return $url;
	}
}

if ( ! function_exists( 'wipe_clean_get_site_footer_settings_url' ) ) {
	function wipe_clean_get_site_footer_settings_url() {
		$slug = wipe_clean_get_site_footer_options_slug();
		$url  = function_exists( 'menu_page_url' ) ? menu_page_url( $slug, false ) : '';

		if ( ! $url ) {
			$url = admin_url( 'admin.php?page=' . $slug );
		}

		return $url;
	}
}

if ( ! function_exists( 'wipe_clean_get_site_shell_settings_url' ) ) {
	function wipe_clean_get_site_shell_settings_url() {
		return wipe_clean_get_site_header_settings_url();
	}
}

if ( ! function_exists( 'wipe_clean_get_site_shell_publish_page_id_by_slug' ) ) {
	function wipe_clean_get_site_shell_publish_page_id_by_slug( $slug ) {
		$slug = sanitize_title( (string) $slug );

		if ( '' === $slug ) {
			return 0;
		}

		$page_ids = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'name'           => $slug,
				'orderby'        => 'menu_order title',
				'order'          => 'ASC',
			)
		);

		return ! empty( $page_ids[0] ) ? (int) $page_ids[0] : 0;
	}
}

if ( ! function_exists( 'wipe_clean_get_site_shell_publish_page_id_by_template' ) ) {
	function wipe_clean_get_site_shell_publish_page_id_by_template( $template_slug ) {
		$template_slug = trim( (string) $template_slug );

		if ( '' === $template_slug ) {
			return 0;
		}

		$page_ids = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_key'       => '_wp_page_template',
				'meta_value'     => $template_slug,
				'orderby'        => 'menu_order title',
				'order'          => 'ASC',
			)
		);

		return ! empty( $page_ids[0] ) ? (int) $page_ids[0] : 0;
	}
}

if ( ! function_exists( 'wipe_clean_resolve_site_shell_page_url' ) ) {
	function wipe_clean_resolve_site_shell_page_url( $slugs, $templates, $fallback_path ) {
		foreach ( (array) $slugs as $slug ) {
			$page_id = wipe_clean_get_site_shell_publish_page_id_by_slug( $slug );

			if ( $page_id ) {
				$url = get_permalink( $page_id );

				if ( $url ) {
					return (string) $url;
				}
			}
		}

		foreach ( (array) $templates as $template_slug ) {
			$page_id = wipe_clean_get_site_shell_publish_page_id_by_template( $template_slug );

			if ( $page_id ) {
				$url = get_permalink( $page_id );

				if ( $url ) {
					return (string) $url;
				}
			}
		}

		$fallback_path = trim( (string) $fallback_path );

		if ( '' === $fallback_path ) {
			return home_url( '/' );
		}

		return home_url( '/' . trim( $fallback_path, '/' ) . '/' );
	}
}

if ( ! function_exists( 'wipe_clean_get_site_about_page_url' ) ) {
	function wipe_clean_get_site_about_page_url() {
		return wipe_clean_resolve_site_shell_page_url(
			array( 'about-us', 'about' ),
			array( 'template-about-page.php' ),
			'about-us'
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_site_prices_page_url' ) ) {
	function wipe_clean_get_site_prices_page_url() {
		return wipe_clean_resolve_site_shell_page_url(
			array( 'prices' ),
			array( 'template-prices-page.php' ),
			'prices'
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_site_faq_page_url' ) ) {
	function wipe_clean_get_site_faq_page_url() {
		return wipe_clean_resolve_site_shell_page_url(
			array( 'faq' ),
			array( 'template-faq-page.php' ),
			'faq'
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_site_contacts_page_url' ) ) {
	function wipe_clean_get_site_contacts_page_url() {
		return wipe_clean_resolve_site_shell_page_url(
			array( 'contacts' ),
			array( 'template-contacts-page.php' ),
			'contacts'
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_site_policy_page_url' ) ) {
	function wipe_clean_get_site_policy_page_url() {
		$page_id = wipe_clean_get_site_shell_publish_page_id_by_slug( 'policy' );

		if ( $page_id ) {
			$url = get_permalink( $page_id );

			if ( $url ) {
				return (string) $url;
			}
		}

		return home_url( '/policy/' );
	}
}

if ( ! function_exists( 'wipe_clean_get_site_services_archive_url' ) ) {
	function wipe_clean_get_site_services_archive_url() {
		$archive_url = post_type_exists( 'wipe_service' ) ? get_post_type_archive_link( 'wipe_service' ) : '';

		return $archive_url ? (string) $archive_url : home_url( '/services/' );
	}
}

if ( ! function_exists( 'wipe_clean_get_site_blog_archive_url' ) ) {
	function wipe_clean_get_site_blog_archive_url() {
		if ( function_exists( 'wipe_clean_get_blog_archive_page_url' ) ) {
			return (string) wipe_clean_get_blog_archive_page_url();
		}

		return home_url( '/blog/' );
	}
}

if ( ! function_exists( 'wipe_clean_get_site_reviews_archive_url' ) ) {
	function wipe_clean_get_site_reviews_archive_url() {
		if ( function_exists( 'wipe_clean_get_reviews_archive_page_url' ) ) {
			return (string) wipe_clean_get_reviews_archive_page_url();
		}

		return home_url( '/reviews/' );
	}
}

if ( ! function_exists( 'wipe_clean_get_site_promotions_archive_url' ) ) {
	function wipe_clean_get_site_promotions_archive_url() {
		if ( function_exists( 'wipe_clean_get_promotions_archive_page_url' ) ) {
			return (string) wipe_clean_get_promotions_archive_page_url();
		}

		return home_url( '/promotions/' );
	}
}

if ( ! function_exists( 'wipe_clean_get_site_shell_contact_defaults' ) ) {
	function wipe_clean_get_site_shell_contact_defaults() {
		$defaults = function_exists( 'wipe_clean_get_front_page_default_sections_map' )
			? wipe_clean_get_front_page_default_sections_map()
			: array();

		$contacts = isset( $defaults['contacts'] ) && is_array( $defaults['contacts'] )
			? $defaults['contacts']
			: array();

		return $contacts;
	}
}

if ( ! function_exists( 'wipe_clean_get_site_shell_default_phone' ) ) {
	function wipe_clean_get_site_shell_default_phone() {
		$contacts = wipe_clean_get_site_shell_contact_defaults();
		$phone    = trim( (string) ( $contacts['phone_value'] ?? '' ) );

		return '' !== $phone ? $phone : '+7 980 163 6101';
	}
}

if ( ! function_exists( 'wipe_clean_get_site_shell_default_email' ) ) {
	function wipe_clean_get_site_shell_default_email() {
		$contacts = wipe_clean_get_site_shell_contact_defaults();
		$email    = trim( (string) ( $contacts['email_value'] ?? '' ) );

		return '' !== $email ? $email : 'MAILBOX@WIPECLEAN.RU';
	}
}

if ( ! function_exists( 'wipe_clean_get_site_shell_default_social_label' ) ) {
	function wipe_clean_get_site_shell_default_social_label() {
		$contacts = wipe_clean_get_site_shell_contact_defaults();
		$label    = trim( (string) ( $contacts['socials_label'] ?? '' ) );

		return '' !== $label ? $label : 'Мессенджеры и соцсети';
	}
}

if ( ! function_exists( 'wipe_clean_get_site_shell_default_social_links' ) ) {
	function wipe_clean_get_site_shell_default_social_links() {
		$contacts = wipe_clean_get_site_shell_contact_defaults();
		$items    = array();

		foreach ( (array) ( $contacts['social_links'] ?? array() ) as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}

			$link = wipe_clean_resolve_link(
				array(
					'url'   => (string) ( $item['url'] ?? '#' ),
					'title' => (string) ( $item['label'] ?? '' ),
				)
			);

			if ( '' === $link['url'] ) {
				$link['url'] = '#';
			}

			$items[] = array(
				'label' => '' !== trim( (string) $link['title'] ) ? (string) $link['title'] : (string) ( $item['label'] ?? 'Соцсеть' ),
				'link'  => $link,
				'icon'  => $item['icon'] ?? array(),
			);
		}

		return $items;
	}
}

if ( ! function_exists( 'wipe_clean_get_site_shell_logo_mark_default' ) ) {
	function wipe_clean_get_site_shell_logo_mark_default() {
		return wipe_clean_theme_image( 'static/images/section/company-preview/company-preview-logo-mark.svg' );
	}
}

if ( ! function_exists( 'wipe_clean_get_site_shell_logo_type_default' ) ) {
	function wipe_clean_get_site_shell_logo_type_default() {
		return wipe_clean_theme_image( 'static/images/section/company-preview/company-preview-logo-type.svg' );
	}
}

if ( ! function_exists( 'wipe_clean_get_site_header_service_link_fallbacks' ) ) {
	function wipe_clean_get_site_header_service_link_fallbacks() {
		$archive_url = wipe_clean_get_site_services_archive_url();

		return array(
			wipe_clean_theme_link( $archive_url, 'Поддерживающая уборка' ),
			wipe_clean_theme_link( $archive_url, 'Срочная уборка' ),
			wipe_clean_theme_link( $archive_url, 'Уборка квартир' ),
			wipe_clean_theme_link( $archive_url, 'Уборка домов и коттеджей' ),
			wipe_clean_theme_link( $archive_url, 'Уборка квартир по суточно' ),
			wipe_clean_theme_link( $archive_url, 'Мытье окон' ),
			wipe_clean_theme_link( $archive_url, 'Уборка офисов' ),
			wipe_clean_theme_link( $archive_url, 'Генеральная уборка' ),
			wipe_clean_theme_link( $archive_url, 'Уборка после ремонта' ),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_site_header_service_links' ) ) {
	function wipe_clean_get_site_header_service_links() {
		if ( ! post_type_exists( 'wipe_service' ) ) {
			return wipe_clean_get_site_header_service_link_fallbacks();
		}

		$posts = get_posts(
			array(
				'post_type'      => 'wipe_service',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => array(
					'menu_order' => 'ASC',
					'title'      => 'ASC',
				),
				'order'          => 'ASC',
			)
		);

		$links = array();

		foreach ( $posts as $post ) {
			if ( ! $post instanceof WP_Post ) {
				continue;
			}

			$url = get_permalink( $post );

			if ( ! $url ) {
				continue;
			}

			$links[] = wipe_clean_theme_link( $url, get_the_title( $post ) );
		}

		return ! empty( $links ) ? $links : wipe_clean_get_site_header_service_link_fallbacks();
	}
}

if ( ! function_exists( 'wipe_clean_split_site_shell_links_into_columns' ) ) {
	function wipe_clean_split_site_shell_links_into_columns( $links, $columns_count = 2 ) {
		$links = array_values(
			array_filter(
				(array) $links,
				static function ( $link ) {
					return is_array( $link ) && ! empty( $link['url'] ) && ! empty( $link['title'] );
				}
			)
		);

		if ( empty( $links ) ) {
			return array();
		}

		$columns_count = max( 1, (int) $columns_count );
		$per_column    = (int) ceil( count( $links ) / $columns_count );
		$chunks        = array_chunk( $links, $per_column );
		$columns       = array();

		foreach ( $chunks as $chunk ) {
			$columns[] = array_values( $chunk );
		}

		return $columns;
	}
}

if ( ! function_exists( 'wipe_clean_normalize_site_shell_link_rows' ) ) {
	function wipe_clean_normalize_site_shell_link_rows( $rows, $fallback = array() ) {
		$items = array();

		foreach ( (array) $rows as $index => $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}

			$fallback_link = isset( $fallback[ $index ] ) && is_array( $fallback[ $index ] ) ? $fallback[ $index ] : array();
			$link          = wipe_clean_merge_site_shell_link( $fallback_link, $row['link'] ?? $row );
			$label         = trim( (string) ( $row['label'] ?? '' ) );

			if ( '' !== $label && empty( $link['title'] ) ) {
				$link['title'] = $label;
			}

			if ( empty( $link['url'] ) || empty( $link['title'] ) ) {
				continue;
			}

			$items[] = $link;
		}

		return ! empty( $items ) ? $items : $fallback;
	}
}

if ( ! function_exists( 'wipe_clean_get_site_shell_primary_nav_links' ) ) {
	function wipe_clean_get_site_shell_primary_nav_links() {
		return array(
			wipe_clean_theme_link( wipe_clean_get_site_prices_page_url(), 'Цены' ),
			wipe_clean_theme_link( wipe_clean_get_site_promotions_archive_url(), 'Акции' ),
			wipe_clean_theme_link( wipe_clean_get_site_reviews_archive_url(), 'Отзывы' ),
			wipe_clean_theme_link( wipe_clean_get_site_contacts_page_url(), 'Контакты' ),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_site_shell_mobile_only_nav_links' ) ) {
	function wipe_clean_get_site_shell_mobile_only_nav_links() {
		return array(
			wipe_clean_theme_link( wipe_clean_get_site_blog_archive_url(), 'Блог' ),
			wipe_clean_theme_link( wipe_clean_get_site_faq_page_url(), 'FAQ' ),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_site_header_default_menu_items' ) ) {
	function wipe_clean_get_site_header_default_menu_items() {
		$items         = array();
		$about_submenu = array(
			wipe_clean_theme_link( wipe_clean_get_site_blog_archive_url(), 'Блог' ),
			wipe_clean_theme_link( wipe_clean_get_site_faq_page_url(), 'FAQ' ),
		);
		$services_item = array(
			'label'         => 'Услуги',
			'link'          => wipe_clean_theme_link( wipe_clean_get_site_services_archive_url(), 'Услуги' ),
			'has_submenu'   => true,
			'submenu_links' => wipe_clean_get_site_header_service_links(),
			'mobile_only'   => false,
		);

		$items[] = array(
			'label'         => 'О компании',
			'link'          => wipe_clean_theme_link( wipe_clean_get_site_about_page_url(), 'О компании' ),
			'has_submenu'   => true,
			'submenu_links' => $about_submenu,
			'mobile_only'   => false,
		);

		$items[] = $services_item;

		foreach ( wipe_clean_get_site_shell_primary_nav_links() as $link ) {
			$items[] = array(
				'label'         => (string) ( $link['title'] ?? '' ),
				'link'          => $link,
				'has_submenu'   => false,
				'submenu_links' => array(),
				'mobile_only'   => false,
			);
		}

		foreach ( wipe_clean_get_site_shell_mobile_only_nav_links() as $link ) {
			$items[] = array(
				'label'         => (string) ( $link['title'] ?? '' ),
				'link'          => $link,
				'has_submenu'   => false,
				'submenu_links' => array(),
				'mobile_only'   => true,
			);
		}

		return $items;
	}
}

if ( ! function_exists( 'wipe_clean_get_site_header_menu_columns' ) ) {
	function wipe_clean_get_site_header_menu_columns( $submenu_links ) {
		$submenu_links = array_values(
			array_filter(
				(array) $submenu_links,
				static function ( $link ) {
					return is_array( $link ) && ! empty( $link['url'] ) && ! empty( $link['title'] );
				}
			)
		);

		if ( empty( $submenu_links ) ) {
			return array();
		}

		if ( count( $submenu_links ) <= 4 ) {
			return array( $submenu_links );
		}

		return wipe_clean_split_site_shell_links_into_columns( $submenu_links, 2 );
	}
}

if ( ! function_exists( 'wipe_clean_normalize_site_header_menu_items' ) ) {
	function wipe_clean_normalize_site_header_menu_items( $rows, $fallback ) {
		$items = array();

		foreach ( (array) $rows as $index => $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}

			$fallback_item = isset( $fallback[ $index ] ) && is_array( $fallback[ $index ] )
				? $fallback[ $index ]
				: array(
					'label'         => '',
					'link'          => array(),
					'has_submenu'   => false,
					'submenu_links' => array(),
					'mobile_only'   => false,
				);

			$link        = wipe_clean_merge_site_shell_link( $fallback_item['link'] ?? array(), $row['link'] ?? array() );
			$label       = trim( (string) ( $row['label'] ?? '' ) );
			$has_submenu = ! empty( $row['has_submenu'] );
			$mobile_only = ! empty( $row['mobile_only'] );

			if ( '' === $label ) {
				$label = trim( (string) ( $link['title'] ?? ( $fallback_item['label'] ?? '' ) ) );
			}

			if ( '' !== $label ) {
				$link['title'] = $label;
			}

			$submenu_links = $has_submenu
				? wipe_clean_normalize_site_shell_link_rows( $row['submenu_links'] ?? array(), $fallback_item['submenu_links'] ?? array() )
				: array();

			if ( empty( $link['url'] ) && $has_submenu ) {
				$link['url'] = '#';
			}

			if ( empty( $link['title'] ) && ! empty( $label ) ) {
				$link['title'] = $label;
			}

			if ( empty( $link['title'] ) ) {
				continue;
			}

			if ( empty( $link['url'] ) && empty( $submenu_links ) ) {
				continue;
			}

			$items[] = array(
				'label'         => $label,
				'link'          => $link,
				'has_submenu'   => $has_submenu && ! empty( $submenu_links ),
				'submenu_links' => $has_submenu ? $submenu_links : array(),
				'mobile_only'   => $mobile_only,
			);
		}

		return ! empty( $items ) ? $items : $fallback;
	}
}

if ( ! function_exists( 'wipe_clean_get_site_header_default_settings' ) ) {
	function wipe_clean_get_site_header_default_settings() {
		return array(
			'brand_url'   => home_url( '/' ),
			'brand_mark'  => wipe_clean_get_site_shell_logo_mark_default(),
			'brand_type'  => wipe_clean_get_site_shell_logo_type_default(),
			'phone'       => wipe_clean_get_site_shell_default_phone(),
			'cta'         => wipe_clean_theme_link( wipe_clean_get_site_contacts_page_url(), 'Связаться с нами' ),
			'menu_items'  => wipe_clean_get_site_header_default_menu_items(),
		);
	}
}

if ( ! function_exists( 'wipe_clean_merge_site_shell_link' ) ) {
	function wipe_clean_merge_site_shell_link( $fallback, $value ) {
		$fallback   = is_array( $fallback ) ? $fallback : array();
		$normalized = wipe_clean_resolve_link( $value );

		if ( ! empty( $normalized['url'] ) ) {
			$fallback['url'] = (string) $normalized['url'];
		}

		if ( ! empty( $normalized['title'] ) ) {
			$fallback['title'] = (string) $normalized['title'];
		}

		if ( ! empty( $normalized['target'] ) ) {
			$fallback['target'] = (string) $normalized['target'];
		}

		return $fallback;
	}
}

if ( ! function_exists( 'wipe_clean_get_site_header_settings' ) ) {
	function wipe_clean_get_site_header_settings() {
		$settings = wipe_clean_get_site_header_default_settings();

		if ( ! function_exists( 'get_field' ) ) {
			return $settings;
		}

		$brand_mark = get_field( 'site_header_brand_mark', 'option' );
		$brand_type = get_field( 'site_header_brand_type', 'option' );
		$phone      = trim( (string) get_field( 'site_header_phone', 'option' ) );
		$cta        = get_field( 'site_header_cta', 'option' );
		$menu_items = get_field( 'site_header_menu_items', 'option' );

		if ( ! empty( $brand_mark ) ) {
			$settings['brand_mark'] = $brand_mark;
		}

		if ( ! empty( $brand_type ) ) {
			$settings['brand_type'] = $brand_type;
		}

		if ( '' !== $phone ) {
			$settings['phone'] = $phone;
		}

		if ( ! empty( $cta ) ) {
			$settings['cta'] = wipe_clean_merge_site_shell_link( $settings['cta'], $cta );
		}

		$settings['menu_items'] = wipe_clean_normalize_site_header_menu_items(
			is_array( $menu_items ) ? $menu_items : array(),
			$settings['menu_items'] ?? array()
		);

		return $settings;
	}
}

if ( ! function_exists( 'wipe_clean_get_site_footer_default_settings' ) ) {
	function wipe_clean_get_site_footer_default_settings() {
		$current_year = gmdate( 'Y' );
		$nav_links    = array_merge(
			array(
				wipe_clean_theme_link( wipe_clean_get_site_about_page_url(), 'О компании' ),
				wipe_clean_theme_link( wipe_clean_get_site_services_archive_url(), 'Услуги' ),
			),
			wipe_clean_get_site_shell_primary_nav_links(),
			wipe_clean_get_site_shell_mobile_only_nav_links()
		);

		return array(
			'brand_url'      => home_url( '/' ),
			'brand_mark'     => wipe_clean_get_site_shell_logo_mark_default(),
			'brand_type'     => wipe_clean_get_site_shell_logo_type_default(),
			'wave_image'     => wipe_clean_theme_image( 'static/images/ui/footer-wave.svg' ),
			'requisites'     => array(
				array(
					'label' => 'ООО',
					'value' => '«ВАЙП–Клин»',
				),
				array(
					'label' => 'ОГРН',
					'value' => '0000000000000',
				),
				array(
					'label' => 'ИНН',
					'value' => '0000000000',
				),
				array(
					'label' => 'КПП',
					'value' => '000000000',
				),
			),
			'nav_title'      => 'Меню сайта',
			'nav_links'      => $nav_links,
			'nav_columns'    => wipe_clean_split_site_shell_links_into_columns( $nav_links, 2 ),
			'phone_label'    => 'Номер телефона',
			'phone'          => wipe_clean_get_site_shell_default_phone(),
			'socials_label'  => wipe_clean_get_site_shell_default_social_label(),
			'social_links'   => wipe_clean_get_site_shell_default_social_links(),
			'email_label'    => 'Электронная почта',
			'email'          => wipe_clean_get_site_shell_default_email(),
			'copyright'      => '© ' . $current_year . ' - Официальный сайт "ВАЙП–Клин"',
			'legal_links'    => array(
				wipe_clean_theme_link( wipe_clean_get_site_policy_page_url(), 'Политика конфиденциальности' ),
				wipe_clean_theme_link( wipe_clean_get_site_policy_page_url(), 'Согласие ОПД' ),
				wipe_clean_theme_link( wipe_clean_get_site_policy_page_url(), 'Согласие Cookies' ),
			),
			'made_by_badge'  => 'DS',
			'made_by_link'   => wipe_clean_theme_link( 'https://ds-art.ru/', 'Сайт разработан компанией DS-ART', '_blank' ),
		);
	}
}

if ( ! function_exists( 'wipe_clean_normalize_site_footer_requisites' ) ) {
	function wipe_clean_normalize_site_footer_requisites( $rows, $fallback ) {
		$items = array();

		foreach ( (array) $rows as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}

			$label = trim( (string) ( $row['label'] ?? '' ) );
			$value = trim( (string) ( $row['value'] ?? '' ) );

			if ( '' === $label || '' === $value ) {
				continue;
			}

			$items[] = array(
				'label' => $label,
				'value' => $value,
			);
		}

		return ! empty( $items ) ? $items : $fallback;
	}
}

if ( ! function_exists( 'wipe_clean_normalize_site_footer_social_links' ) ) {
	function wipe_clean_normalize_site_footer_social_links( $rows, $fallback ) {
		$items = array();

		foreach ( (array) $rows as $index => $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}

			$fallback_item = isset( $fallback[ $index ] ) && is_array( $fallback[ $index ] )
				? $fallback[ $index ]
				: array(
					'label' => 'Соцсеть',
					'link'  => array(
						'url'    => '#',
						'title'  => 'Соцсеть',
						'target' => '',
					),
					'icon'  => array(),
				);

			$item_link  = wipe_clean_merge_site_shell_link( $fallback_item['link'] ?? array(), $row['link'] ?? array() );
			$item_label = trim( (string) ( $row['label'] ?? '' ) );
			$item_icon  = $row['icon'] ?? null;

			if ( empty( $item_icon ) && ! empty( $fallback_item['icon'] ) ) {
				$item_icon = $fallback_item['icon'];
			}

			if ( '' === trim( (string) ( $item_link['url'] ?? '' ) ) ) {
				$item_link['url'] = '#';
			}

			if ( '' === trim( (string) ( $item_link['title'] ?? '' ) ) && '' !== $item_label ) {
				$item_link['title'] = $item_label;
			}

			if ( '' === $item_label ) {
				$item_label = trim( (string) ( $item_link['title'] ?? ( $fallback_item['label'] ?? 'Соцсеть' ) ) );
			}

			$items[] = array(
				'label' => $item_label,
				'link'  => $item_link,
				'icon'  => $item_icon,
			);
		}

		return ! empty( $items ) ? $items : $fallback;
	}
}

if ( ! function_exists( 'wipe_clean_normalize_site_footer_links' ) ) {
	function wipe_clean_normalize_site_footer_links( $rows, $fallback ) {
		$items = array();

		foreach ( (array) $rows as $row ) {
			if ( ! is_array( $row ) || empty( $row['link'] ) ) {
				continue;
			}

			$link = wipe_clean_merge_site_shell_link( array(), $row['link'] );

			if ( empty( $link['url'] ) || empty( $link['title'] ) ) {
				continue;
			}

			$items[] = $link;
		}

		return ! empty( $items ) ? $items : $fallback;
	}
}

if ( ! function_exists( 'wipe_clean_get_site_footer_settings' ) ) {
	function wipe_clean_get_site_footer_settings() {
		$settings        = wipe_clean_get_site_footer_default_settings();
		$header_settings = wipe_clean_get_site_header_settings();

		$settings['brand_mark'] = $header_settings['brand_mark'] ?? $settings['brand_mark'];
		$settings['brand_type'] = $header_settings['brand_type'] ?? $settings['brand_type'];
		$settings['phone']      = $header_settings['phone'] ?? $settings['phone'];

		if ( ! function_exists( 'get_field' ) ) {
			return $settings;
		}

		$brand_mark = get_field( 'site_footer_brand_mark', 'option' );
		$brand_type = get_field( 'site_footer_brand_type', 'option' );
		$phone      = trim( (string) get_field( 'site_footer_phone', 'option' ) );
		$email      = trim( (string) get_field( 'site_footer_email', 'option' ) );
		$copyright  = trim( (string) get_field( 'site_footer_copyright', 'option' ) );
		$badge      = trim( (string) get_field( 'site_footer_made_by_badge', 'option' ) );
		$made_by    = get_field( 'site_footer_made_by_link', 'option' );

		if ( ! empty( $brand_mark ) ) {
			$settings['brand_mark'] = $brand_mark;
		}

		if ( ! empty( $brand_type ) ) {
			$settings['brand_type'] = $brand_type;
		}

		$settings['requisites']   = wipe_clean_normalize_site_footer_requisites( get_field( 'site_footer_requisites', 'option' ), $settings['requisites'] );
		$settings['social_links'] = wipe_clean_normalize_site_footer_social_links( get_field( 'site_footer_social_links', 'option' ), $settings['social_links'] );
		$settings['legal_links']  = wipe_clean_normalize_site_footer_links( get_field( 'site_footer_legal_links', 'option' ), $settings['legal_links'] );
		$settings['nav_links']    = wipe_clean_normalize_site_shell_link_rows( get_field( 'site_footer_nav_links', 'option' ), $settings['nav_links'] ?? array() );

		$phone_label = trim( (string) get_field( 'site_footer_phone_label', 'option' ) );

		if ( '' !== $phone_label ) {
			$settings['phone_label'] = $phone_label;
		}

		if ( '' !== $phone ) {
			$settings['phone'] = $phone;
		}

		$socials_label = trim( (string) get_field( 'site_footer_socials_label', 'option' ) );

		if ( '' !== $socials_label ) {
			$settings['socials_label'] = $socials_label;
		}

		$email_label = trim( (string) get_field( 'site_footer_email_label', 'option' ) );

		if ( '' !== $email_label ) {
			$settings['email_label'] = $email_label;
		}

		if ( '' !== $email ) {
			$settings['email'] = $email;
		}

		if ( '' !== $copyright ) {
			$settings['copyright'] = $copyright;
		}

		$nav_title = trim( (string) get_field( 'site_footer_nav_title', 'option' ) );

		if ( '' !== $nav_title ) {
			$settings['nav_title'] = $nav_title;
		}

		if ( '' !== $badge ) {
			$settings['made_by_badge'] = $badge;
		}

		if ( ! empty( $made_by ) ) {
			$settings['made_by_link'] = wipe_clean_merge_site_shell_link( $settings['made_by_link'], $made_by );
		}

		$settings['nav_columns'] = wipe_clean_split_site_shell_links_into_columns( $settings['nav_links'] ?? array(), 2 );

		return $settings;
	}
}

if ( ! function_exists( 'wipe_clean_get_site_shell_phone_href' ) ) {
	function wipe_clean_get_site_shell_phone_href( $phone ) {
		$phone      = trim( (string) $phone );
		$has_plus   = str_starts_with( $phone, '+' );
		$normalized = preg_replace( '/\D+/', '', $phone );

		if ( '' === $normalized ) {
			return '';
		}

		return 'tel:' . ( $has_plus ? '+' : '' ) . $normalized;
	}
}

if ( ! function_exists( 'wipe_clean_get_site_shell_email_href' ) ) {
	function wipe_clean_get_site_shell_email_href( $email ) {
		$email = sanitize_email( (string) $email );

		return '' !== $email ? 'mailto:' . $email : '';
	}
}

if ( ! function_exists( 'wipe_clean_render_site_header' ) ) {
	function wipe_clean_render_site_header() {
		get_template_part(
			'template-parts/layout/site-header',
			null,
			array(
				'settings' => wipe_clean_get_site_header_settings(),
			)
		);
	}
}

if ( ! function_exists( 'wipe_clean_render_site_footer' ) ) {
	function wipe_clean_render_site_footer() {
		get_template_part(
			'template-parts/layout/site-footer',
			null,
			array(
				'settings' => wipe_clean_get_site_footer_settings(),
			)
		);
	}
}
