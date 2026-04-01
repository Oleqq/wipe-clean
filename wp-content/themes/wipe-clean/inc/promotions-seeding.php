<?php
/**
 * One-click seeding for the promotions archive and promotion records.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_promotions_archive_has_seeded_rows' ) ) {
	function wipe_clean_promotions_archive_has_seeded_rows() {
		if ( ! function_exists( 'get_field' ) ) {
			return false;
		}

		$rows = get_field( 'promotions_archive_sections', 'option', false );

		return is_array( $rows ) && ! empty( $rows );
	}
}

if ( ! function_exists( 'wipe_clean_get_promotions_archive_seed_rows' ) ) {
	function wipe_clean_get_promotions_archive_seed_rows() {
		$rows = array();

		foreach ( wipe_clean_get_promotions_archive_layout_order() as $layout ) {
			$row = wipe_clean_get_promotions_archive_section_defaults( $layout );

			if ( function_exists( 'wipe_clean_prepare_front_page_seed_value' ) ) {
				$row = wipe_clean_prepare_front_page_seed_value( $row );
			}

			$rows[] = $row;
		}

		return $rows;
	}
}

if ( ! function_exists( 'wipe_clean_import_promotion_seed_attachment' ) ) {
	function wipe_clean_import_promotion_seed_attachment( $media ) {
		if ( function_exists( 'wipe_clean_services_page_import_attachment' ) ) {
			return (int) wipe_clean_services_page_import_attachment( $media );
		}

		return 0;
	}
}

if ( ! function_exists( 'wipe_clean_upsert_seeded_promotion_post' ) ) {
	function wipe_clean_upsert_seeded_promotion_post( $seed_key, $title, $menu_order ) {
		$post_id = function_exists( 'wipe_clean_upsert_seeded_post' )
			? wipe_clean_upsert_seeded_post(
				wipe_clean_get_promotions_post_type(),
				$seed_key,
				array(
					'post_title' => $title,
					'menu_order' => (int) $menu_order,
				)
			)
			: 0;

		return (int) $post_id;
	}
}

if ( ! function_exists( 'wipe_clean_seed_promotion_posts' ) ) {
	function wipe_clean_seed_promotion_posts() {
		if ( ! post_type_exists( wipe_clean_get_promotions_post_type() ) || ! function_exists( 'update_field' ) ) {
			return;
		}

		foreach ( wipe_clean_get_promotions_default_items() as $index => $item ) {
			$post_id = wipe_clean_upsert_seeded_promotion_post(
				(string) ( $item['seed_key'] ?? 'promotion-' . ( $index + 1 ) ),
				(string) ( $item['title'] ?? 'Акция ' . ( $index + 1 ) ),
				$index + 1
			);

			if ( ! $post_id ) {
				continue;
			}

			$image_id = wipe_clean_import_promotion_seed_attachment( $item['image'] ?? array() );
			$popup_id = wipe_clean_import_promotion_seed_attachment( $item['popupImage'] ?? ( $item['image'] ?? array() ) );

			if ( $image_id ) {
				set_post_thumbnail( $post_id, $image_id );
			}

			update_field( 'popup_title', (string) ( $item['popupTitle'] ?? $item['title'] ?? '' ), $post_id );
			update_field( 'popup_text', implode( "\n\n", array_values( (array) ( $item['popupText'] ?? array() ) ) ), $post_id );
			update_field(
				'popup_conditions',
				array_map(
					static function ( $condition ) {
						return array(
							'text' => (string) $condition,
						);
					},
					array_values( (array) ( $item['popupConditions'] ?? array() ) )
				),
				$post_id
			);

			if ( $popup_id ) {
				update_field( 'popup_image', $popup_id, $post_id );
			}
		}
	}
}

if ( ! function_exists( 'wipe_clean_seed_promotions_archive_sections' ) ) {
	function wipe_clean_seed_promotions_archive_sections() {
		if ( ! function_exists( 'update_field' ) ) {
			return false;
		}

		update_field( 'promotions_archive_sections', wipe_clean_get_promotions_archive_seed_rows(), 'option' );
		wipe_clean_seed_promotion_posts();
		update_option( 'wipe_clean_promotions_archive_seeded_at', time() );

		return true;
	}
}

if ( ! function_exists( 'wipe_clean_get_promotions_archive_seed_action_url' ) ) {
	function wipe_clean_get_promotions_archive_seed_action_url() {
		return wp_nonce_url(
			admin_url( 'admin-post.php?action=wipe_clean_seed_promotions_archive' ),
			'wipe_clean_seed_promotions_archive'
		);
	}
}

if ( ! function_exists( 'wipe_clean_is_promotions_archive_options_admin_screen' ) ) {
	function wipe_clean_is_promotions_archive_options_admin_screen() {
		return is_admin() && isset( $_GET['page'] ) && wipe_clean_get_promotions_archive_options_slug() === (string) $_GET['page'];
	}
}

if ( ! function_exists( 'wipe_clean_render_promotions_archive_seed_box' ) ) {
	function wipe_clean_render_promotions_archive_seed_box() {
		if ( ! wipe_clean_is_promotions_archive_options_admin_screen() || ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$has_rows   = wipe_clean_promotions_archive_has_seeded_rows();
		$action_url = wipe_clean_get_promotions_archive_seed_action_url();
		?>
		<div class="notice notice-info" style="margin-top:16px;padding:0;border:none;background:transparent;box-shadow:none;">
			<div style="padding:16px 18px;border:1px solid #d7e8ee;border-radius:16px;background:linear-gradient(180deg,rgba(255,255,255,0.98) 0%,rgba(250,252,253,0.96) 100%);box-shadow:0 10px 24px rgba(21,15,49,0.06);">
				<div style="display:flex;flex-wrap:wrap;gap:14px;align-items:center;justify-content:space-between;">
					<div style="max-width:760px;">
						<div style="margin:0 0 6px;font-size:16px;font-weight:700;color:#150F31;">Заполнить готовым содержимым</div>
						<div style="font-size:13px;line-height:1.55;color:#5D5779;">
							Подставляет готовые блоки страницы акций и одновременно создаёт или обновляет готовые акции. На сайте они открываются только во всплывающем окне, без отдельной страницы.
							<?php if ( $has_rows ) : ?>
								<strong style="color:#150F31;">Текущие значения секций будут заменены.</strong>
							<?php endif; ?>
						</div>
					</div>
					<div>
						<a class="button button-primary button-large" href="<?php echo esc_url( $action_url ); ?>" onclick="return window.confirm('Заполнить страницу акций готовым содержимым? Текущие блоки будут заменены, а готовые акции обновлены.');">
							<?php echo esc_html( $has_rows ? 'Обновить содержимое' : 'Заполнить готовым' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'wipe_clean_render_promotions_archive_seed_box', 5 );

if ( ! function_exists( 'wipe_clean_handle_promotions_archive_seed_action' ) ) {
	function wipe_clean_handle_promotions_archive_seed_action() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( esc_html__( 'Недостаточно прав для этого действия.', 'wipe-clean' ) );
		}

		check_admin_referer( 'wipe_clean_seed_promotions_archive' );
		wipe_clean_seed_promotions_archive_sections();

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'                                 => wipe_clean_get_promotions_archive_options_slug(),
					'wipe_clean_promotions_archive_seeded' => 1,
				),
				admin_url( 'admin.php' )
			)
		);
		exit;
	}
}
add_action( 'admin_post_wipe_clean_seed_promotions_archive', 'wipe_clean_handle_promotions_archive_seed_action' );

if ( ! function_exists( 'wipe_clean_render_promotions_archive_seed_notice' ) ) {
	function wipe_clean_render_promotions_archive_seed_notice() {
		if ( empty( $_GET['wipe_clean_promotions_archive_seeded'] ) || ! wipe_clean_is_promotions_archive_options_admin_screen() ) {
			return;
		}
		?>
		<div class="notice notice-success is-dismissible">
			<p>Страница акций заполнена готовым содержимым. Готовые акции тоже созданы или обновлены.</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'wipe_clean_render_promotions_archive_seed_notice', 20 );
