<?php
/**
 * Manual seed helpers for service single pages.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_service_single_has_seeded_rows' ) ) {
	function wipe_clean_service_single_has_seeded_rows( $post_id ) {
		if ( ! function_exists( 'get_field' ) ) {
			return false;
		}

		$rows = get_field( 'service_sections', (int) $post_id, false );

		return is_array( $rows ) && ! empty( $rows );
	}
}

if ( ! function_exists( 'wipe_clean_get_service_single_seed_rows' ) ) {
	function wipe_clean_get_service_single_seed_rows() {
		$rows         = array();
		$defaults_map = function_exists( 'wipe_clean_get_service_single_default_sections_map' )
			? wipe_clean_get_service_single_default_sections_map()
			: array();
		$layouts      = function_exists( 'wipe_clean_get_service_single_layout_order' )
			? wipe_clean_get_service_single_layout_order()
			: array();

		foreach ( $layouts as $layout ) {
			$row = $defaults_map[ $layout ] ?? array(
				'acf_fc_layout' => $layout,
			);

			if ( function_exists( 'wipe_clean_prepare_front_page_seed_value' ) ) {
				$row = wipe_clean_prepare_front_page_seed_value( $row );
			}

			$rows[] = $row;
		}

		return $rows;
	}
}

if ( ! function_exists( 'wipe_clean_seed_service_single_sections' ) ) {
	function wipe_clean_seed_service_single_sections( $post_id ) {
		$post_id = (int) $post_id;

		if ( ! $post_id || 'wipe_service' !== get_post_type( $post_id ) || ! function_exists( 'update_field' ) ) {
			return false;
		}

		update_field( 'service_sections', wipe_clean_get_service_single_seed_rows(), $post_id );
		update_post_meta( $post_id, '_wipe_clean_service_single_seeded_at', time() );

		return true;
	}
}

if ( ! function_exists( 'wipe_clean_get_service_single_seed_action_url' ) ) {
	function wipe_clean_get_service_single_seed_action_url( $post_id ) {
		$post_id = (int) $post_id;

		if ( ! $post_id || 'wipe_service' !== get_post_type( $post_id ) ) {
			return '';
		}

		return wp_nonce_url(
			admin_url( 'admin-post.php?action=wipe_clean_seed_service_single&post_id=' . $post_id ),
			'wipe_clean_seed_service_single_' . $post_id
		);
	}
}

if ( ! function_exists( 'wipe_clean_render_service_single_seed_box' ) ) {
	function wipe_clean_render_service_single_seed_box( $post ) {
		if ( ! $post instanceof WP_Post || 'wipe_service' !== $post->post_type || ! current_user_can( 'edit_post', $post->ID ) ) {
			return;
		}

		$has_rows   = wipe_clean_service_single_has_seeded_rows( $post->ID );
		$action_url = wipe_clean_get_service_single_seed_action_url( $post->ID );

		if ( ! $action_url ) {
			return;
		}
		?>
		<div style="margin:16px 0 18px;padding:16px 18px;border:1px solid #d7e8ee;border-radius:16px;background:linear-gradient(180deg,rgba(255,255,255,0.98) 0%,rgba(250,252,253,0.96) 100%);box-shadow:0 10px 24px rgba(21,15,49,0.06);">
			<div style="display:flex;flex-wrap:wrap;gap:14px;align-items:center;justify-content:space-between;">
				<div style="max-width:760px;">
					<div style="margin:0 0 6px;font-size:16px;font-weight:700;color:#150F31;">Заполнить готовым содержимым</div>
					<div style="font-size:13px;line-height:1.55;color:#5D5779;">
						Подставляет в блоки страницы услуги готовое содержимое. Карточка услуги в архиве и в других блоках всё равно берёт данные из самой записи: название, краткое описание, изображение записи и цену.
						<?php if ( $has_rows ) : ?>
							<strong style="color:#150F31;">Текущие блоки страницы будут заменены.</strong>
						<?php endif; ?>
					</div>
				</div>
				<div>
					<a class="button button-primary button-large" href="<?php echo esc_url( $action_url ); ?>" onclick="return window.confirm('Заполнить страницу услуги готовым содержимым? Текущие блоки страницы будут заменены.');">
						<?php echo esc_html( $has_rows ? 'Обновить содержимое' : 'Заполнить готовым' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php
	}
}
add_action( 'edit_form_after_title', 'wipe_clean_render_service_single_seed_box', 7 );

if ( ! function_exists( 'wipe_clean_handle_service_single_seed_action' ) ) {
	function wipe_clean_handle_service_single_seed_action() {
		$post_id = isset( $_GET['post_id'] ) ? (int) $_GET['post_id'] : 0;

		if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
			wp_die( esc_html__( 'Недостаточно прав для этого действия.', 'wipe-clean' ) );
		}

		check_admin_referer( 'wipe_clean_seed_service_single_' . $post_id );
		wipe_clean_seed_service_single_sections( $post_id );

		wp_safe_redirect(
			add_query_arg(
				array(
					'post'                            => $post_id,
					'action'                          => 'edit',
					'wipe_clean_service_single_seeded' => 1,
				),
				admin_url( 'post.php' )
			)
		);
		exit;
	}
}
add_action( 'admin_post_wipe_clean_seed_service_single', 'wipe_clean_handle_service_single_seed_action' );

if ( ! function_exists( 'wipe_clean_render_service_single_seed_notice' ) ) {
	function wipe_clean_render_service_single_seed_notice() {
		if ( empty( $_GET['wipe_clean_service_single_seeded'] ) || empty( $_GET['post'] ) ) {
			return;
		}

		$post_id = (int) $_GET['post'];

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		if ( ! $screen || 'wipe_service' !== $screen->post_type ) {
			return;
		}
		?>
		<div class="notice notice-success is-dismissible">
			<p>Страница услуги заполнена готовым содержимым.</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'wipe_clean_render_service_single_seed_notice' );
