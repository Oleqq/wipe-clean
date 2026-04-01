<?php
/**
 * One-click seeding for the global site header and footer.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_is_site_shell_options_admin_screen' ) ) {
	function wipe_clean_is_site_shell_options_admin_screen( $page_slug ) {
		return is_admin() && isset( $_GET['page'] ) && (string) $page_slug === (string) $_GET['page'];
	}
}

if ( ! function_exists( 'wipe_clean_site_header_has_seeded_values' ) ) {
	function wipe_clean_site_header_has_seeded_values() {
		if ( ! function_exists( 'get_field' ) ) {
			return false;
		}

		$phone = get_field( 'site_header_phone', 'option', false );

		return is_string( $phone ) && '' !== trim( $phone );
	}
}

if ( ! function_exists( 'wipe_clean_site_footer_has_seeded_values' ) ) {
	function wipe_clean_site_footer_has_seeded_values() {
		if ( ! function_exists( 'get_field' ) ) {
			return false;
		}

		$email = get_field( 'site_footer_email', 'option', false );

		return is_string( $email ) && '' !== trim( $email );
	}
}

if ( ! function_exists( 'wipe_clean_prepare_site_shell_seed_image' ) ) {
	function wipe_clean_prepare_site_shell_seed_image( $image ) {
		if ( function_exists( 'wipe_clean_import_theme_asset_attachment' ) ) {
			return (int) wipe_clean_import_theme_asset_attachment(
				(string) ( $image['path'] ?? '' ),
				(string) ( $image['alt'] ?? '' )
			);
		}

		return 0;
	}
}

if ( ! function_exists( 'wipe_clean_prepare_site_shell_seed_social_links' ) ) {
	function wipe_clean_prepare_site_shell_seed_social_links( $items ) {
		$prepared = array();

		foreach ( (array) $items as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}

			$prepared[] = array(
				'label' => (string) ( $item['label'] ?? '' ),
				'link'  => $item['link'] ?? array(),
				'icon'  => wipe_clean_prepare_site_shell_seed_image( $item['icon'] ?? array() ),
			);
		}

		return $prepared;
	}
}

if ( ! function_exists( 'wipe_clean_prepare_site_shell_seed_header_menu_items' ) ) {
	function wipe_clean_prepare_site_shell_seed_header_menu_items( $items ) {
		$prepared = array();

		foreach ( (array) $items as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}

			$row = array(
				'label'         => (string) ( $item['label'] ?? ( $item['link']['title'] ?? '' ) ),
				'link'          => $item['link'] ?? array(),
				'has_submenu'   => ! empty( $item['has_submenu'] ),
				'mobile_only'   => ! empty( $item['mobile_only'] ),
				'submenu_links' => array(),
			);

			foreach ( (array) ( $item['submenu_links'] ?? array() ) as $submenu_link ) {
				if ( ! is_array( $submenu_link ) || empty( $submenu_link['url'] ) || empty( $submenu_link['title'] ) ) {
					continue;
				}

				$row['submenu_links'][] = array(
					'link' => $submenu_link,
				);
			}

			$prepared[] = $row;
		}

		return $prepared;
	}
}

if ( ! function_exists( 'wipe_clean_prepare_site_shell_seed_links' ) ) {
	function wipe_clean_prepare_site_shell_seed_links( $items ) {
		$prepared = array();

		foreach ( (array) $items as $item ) {
			if ( ! is_array( $item ) || empty( $item['url'] ) || empty( $item['title'] ) ) {
				continue;
			}

			$prepared[] = array(
				'link' => $item,
			);
		}

		return $prepared;
	}
}

if ( ! function_exists( 'wipe_clean_seed_site_header_settings' ) ) {
	function wipe_clean_seed_site_header_settings() {
		if ( ! function_exists( 'update_field' ) ) {
			return false;
		}

		$defaults = wipe_clean_get_site_header_default_settings();

		update_field( 'site_header_brand_mark', wipe_clean_prepare_site_shell_seed_image( $defaults['brand_mark'] ?? array() ), 'option' );
		update_field( 'site_header_brand_type', wipe_clean_prepare_site_shell_seed_image( $defaults['brand_type'] ?? array() ), 'option' );
		update_field( 'site_header_phone', (string) ( $defaults['phone'] ?? '' ), 'option' );
		update_field( 'site_header_cta', $defaults['cta'] ?? array(), 'option' );
		update_field( 'site_header_menu_items', wipe_clean_prepare_site_shell_seed_header_menu_items( $defaults['menu_items'] ?? array() ), 'option' );
		update_option( 'wipe_clean_site_header_seeded_at', time() );

		return true;
	}
}

if ( ! function_exists( 'wipe_clean_seed_site_footer_settings' ) ) {
	function wipe_clean_seed_site_footer_settings() {
		if ( ! function_exists( 'update_field' ) ) {
			return false;
		}

		$defaults = wipe_clean_get_site_footer_default_settings();

		update_field( 'site_footer_brand_mark', wipe_clean_prepare_site_shell_seed_image( $defaults['brand_mark'] ?? array() ), 'option' );
		update_field( 'site_footer_brand_type', wipe_clean_prepare_site_shell_seed_image( $defaults['brand_type'] ?? array() ), 'option' );
		update_field( 'site_footer_requisites', $defaults['requisites'] ?? array(), 'option' );
		update_field( 'site_footer_nav_title', (string) ( $defaults['nav_title'] ?? '' ), 'option' );
		update_field( 'site_footer_nav_links', wipe_clean_prepare_site_shell_seed_links( $defaults['nav_links'] ?? array() ), 'option' );
		update_field( 'site_footer_phone_label', (string) ( $defaults['phone_label'] ?? '' ), 'option' );
		update_field( 'site_footer_phone', (string) ( $defaults['phone'] ?? '' ), 'option' );
		update_field( 'site_footer_socials_label', (string) ( $defaults['socials_label'] ?? '' ), 'option' );
		update_field( 'site_footer_social_links', wipe_clean_prepare_site_shell_seed_social_links( $defaults['social_links'] ?? array() ), 'option' );
		update_field( 'site_footer_email_label', (string) ( $defaults['email_label'] ?? '' ), 'option' );
		update_field( 'site_footer_email', (string) ( $defaults['email'] ?? '' ), 'option' );
		update_field( 'site_footer_legal_links', wipe_clean_prepare_site_shell_seed_links( $defaults['legal_links'] ?? array() ), 'option' );
		update_field( 'site_footer_copyright', (string) ( $defaults['copyright'] ?? '' ), 'option' );
		update_field( 'site_footer_made_by_badge', (string) ( $defaults['made_by_badge'] ?? '' ), 'option' );
		update_field( 'site_footer_made_by_link', $defaults['made_by_link'] ?? array(), 'option' );
		update_option( 'wipe_clean_site_footer_seeded_at', time() );

		return true;
	}
}

if ( ! function_exists( 'wipe_clean_get_site_header_seed_action_url' ) ) {
	function wipe_clean_get_site_header_seed_action_url() {
		return wp_nonce_url(
			admin_url( 'admin-post.php?action=wipe_clean_seed_site_header' ),
			'wipe_clean_seed_site_header'
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_site_footer_seed_action_url' ) ) {
	function wipe_clean_get_site_footer_seed_action_url() {
		return wp_nonce_url(
			admin_url( 'admin-post.php?action=wipe_clean_seed_site_footer' ),
			'wipe_clean_seed_site_footer'
		);
	}
}

if ( ! function_exists( 'wipe_clean_render_site_shell_seed_box' ) ) {
	function wipe_clean_render_site_shell_seed_box( $args ) {
		$args = wp_parse_args(
			(array) $args,
			array(
				'page_slug'    => '',
				'has_values'   => false,
				'action_url'   => '',
				'title'        => '',
				'description'  => '',
				'confirm_text' => '',
			)
		);

		if ( ! $args['page_slug'] || ! wipe_clean_is_site_shell_options_admin_screen( $args['page_slug'] ) || ! current_user_can( 'edit_pages' ) ) {
			return;
		}
		?>
		<div class="notice notice-info" style="margin-top:16px;padding:0;border:none;background:transparent;box-shadow:none;">
			<div style="padding:16px 18px;border:1px solid #d7e8ee;border-radius:16px;background:linear-gradient(180deg,rgba(255,255,255,0.98) 0%,rgba(250,252,253,0.96) 100%);box-shadow:0 10px 24px rgba(21,15,49,0.06);">
				<div style="display:flex;flex-wrap:wrap;gap:14px;align-items:center;justify-content:space-between;">
					<div style="max-width:760px;">
						<div style="margin:0 0 6px;font-size:16px;font-weight:700;color:#150F31;"><?php echo esc_html( (string) $args['title'] ); ?></div>
						<div style="font-size:13px;line-height:1.55;color:#5D5779;">
							<?php echo wp_kses_post( (string) $args['description'] ); ?>
							<?php if ( ! empty( $args['has_values'] ) ) : ?>
								<strong style="color:#150F31;">Текущие значения будут заменены.</strong>
							<?php endif; ?>
						</div>
					</div>
					<div>
						<a class="button button-primary button-large" href="<?php echo esc_url( (string) $args['action_url'] ); ?>" onclick="return window.confirm('<?php echo esc_js( (string) $args['confirm_text'] ); ?>');">
							<?php echo esc_html( ! empty( $args['has_values'] ) ? 'Обновить содержимое' : 'Заполнить готовым' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'wipe_clean_render_site_header_seed_box' ) ) {
	function wipe_clean_render_site_header_seed_box() {
		wipe_clean_render_site_shell_seed_box(
			array(
				'page_slug'    => wipe_clean_get_site_header_options_slug(),
				'has_values'   => wipe_clean_site_header_has_seeded_values(),
				'action_url'   => wipe_clean_get_site_header_seed_action_url(),
				'title'        => 'Заполнить готовым содержимым',
				'description'  => 'Подставляет готовые логотип, телефон, кнопку и меню для шапки сайта. Ссылки будут выставлены сразу правильно.',
				'confirm_text' => 'Заполнить шапку сайта готовым содержимым? Текущие значения будут заменены.',
			)
		);
	}
}
add_action( 'admin_notices', 'wipe_clean_render_site_header_seed_box', 5 );

if ( ! function_exists( 'wipe_clean_render_site_footer_seed_box' ) ) {
	function wipe_clean_render_site_footer_seed_box() {
		wipe_clean_render_site_shell_seed_box(
			array(
				'page_slug'    => wipe_clean_get_site_footer_options_slug(),
				'has_values'   => wipe_clean_site_footer_has_seeded_values(),
				'action_url'   => wipe_clean_get_site_footer_seed_action_url(),
				'title'        => 'Заполнить готовым содержимым',
				'description'  => 'Подставляет готовые логотип, меню, реквизиты, контакты, документы и нижнюю строку для подвала сайта. Ссылки будут выставлены сразу правильно.',
				'confirm_text' => 'Заполнить подвал сайта готовым содержимым? Текущие значения будут заменены.',
			)
		);
	}
}
add_action( 'admin_notices', 'wipe_clean_render_site_footer_seed_box', 5 );

if ( ! function_exists( 'wipe_clean_handle_site_header_seed_action' ) ) {
	function wipe_clean_handle_site_header_seed_action() {
		if ( ! current_user_can( 'edit_pages' ) ) {
			wp_die( esc_html__( 'Недостаточно прав для этого действия.', 'wipe-clean' ) );
		}

		check_admin_referer( 'wipe_clean_seed_site_header' );
		wipe_clean_seed_site_header_settings();

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'                          => wipe_clean_get_site_header_options_slug(),
					'wipe_clean_site_header_seeded' => 1,
				),
				admin_url( 'admin.php' )
			)
		);
		exit;
	}
}
add_action( 'admin_post_wipe_clean_seed_site_header', 'wipe_clean_handle_site_header_seed_action' );

if ( ! function_exists( 'wipe_clean_handle_site_footer_seed_action' ) ) {
	function wipe_clean_handle_site_footer_seed_action() {
		if ( ! current_user_can( 'edit_pages' ) ) {
			wp_die( esc_html__( 'Недостаточно прав для этого действия.', 'wipe-clean' ) );
		}

		check_admin_referer( 'wipe_clean_seed_site_footer' );
		wipe_clean_seed_site_footer_settings();

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'                          => wipe_clean_get_site_footer_options_slug(),
					'wipe_clean_site_footer_seeded' => 1,
				),
				admin_url( 'admin.php' )
			)
		);
		exit;
	}
}
add_action( 'admin_post_wipe_clean_seed_site_footer', 'wipe_clean_handle_site_footer_seed_action' );

if ( ! function_exists( 'wipe_clean_render_site_header_seed_notice' ) ) {
	function wipe_clean_render_site_header_seed_notice() {
		if ( empty( $_GET['wipe_clean_site_header_seeded'] ) || ! wipe_clean_is_site_shell_options_admin_screen( wipe_clean_get_site_header_options_slug() ) ) {
			return;
		}
		?>
		<div class="notice notice-success is-dismissible">
			<p>Шапка сайта заполнена готовым содержимым.</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'wipe_clean_render_site_header_seed_notice', 20 );

if ( ! function_exists( 'wipe_clean_render_site_footer_seed_notice' ) ) {
	function wipe_clean_render_site_footer_seed_notice() {
		if ( empty( $_GET['wipe_clean_site_footer_seeded'] ) || ! wipe_clean_is_site_shell_options_admin_screen( wipe_clean_get_site_footer_options_slug() ) ) {
			return;
		}
		?>
		<div class="notice notice-success is-dismissible">
			<p>Подвал сайта заполнен готовым содержимым.</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'wipe_clean_render_site_footer_seed_notice', 20 );
