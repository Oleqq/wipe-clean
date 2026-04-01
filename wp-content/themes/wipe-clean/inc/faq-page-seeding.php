<?php
/**
 * One-click seeding for the FAQ page.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_faq_page_has_seeded_rows' ) ) {
	function wipe_clean_faq_page_has_seeded_rows( $post_id ) {
		if ( ! function_exists( 'get_field' ) ) {
			return false;
		}

		$rows = get_field( 'faq_page_sections', (int) $post_id, false );

		return is_array( $rows ) && ! empty( $rows );
	}
}

if ( ! function_exists( 'wipe_clean_get_faq_page_seed_rows' ) ) {
	function wipe_clean_get_faq_page_seed_rows() {
		$rows = array();

		foreach ( wipe_clean_get_faq_page_layout_order() as $layout ) {
			$row = wipe_clean_get_faq_page_section_defaults( $layout );

			if ( function_exists( 'wipe_clean_prepare_front_page_seed_value' ) ) {
				$row = wipe_clean_prepare_front_page_seed_value( $row );
			}

			$rows[] = $row;
		}

		return $rows;
	}
}

if ( ! function_exists( 'wipe_clean_seed_faq_page_sections' ) ) {
	function wipe_clean_seed_faq_page_sections( $post_id ) {
		$post_id = (int) $post_id;

		if ( ! $post_id || ! function_exists( 'update_field' ) || ! wipe_clean_is_faq_page_post( $post_id ) ) {
			return false;
		}

		update_field( 'faq_page_sections', wipe_clean_get_faq_page_seed_rows(), $post_id );
		update_post_meta( $post_id, '_wipe_clean_faq_page_seeded_at', time() );

		return true;
	}
}

if ( ! function_exists( 'wipe_clean_maybe_seed_faq_page_on_save' ) ) {
	function wipe_clean_maybe_seed_faq_page_on_save( $post_id ) {
		static $is_running = false;

		if ( $is_running || wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return;
		}

		if ( ! wipe_clean_is_faq_page_post( $post_id ) || wipe_clean_faq_page_has_seeded_rows( $post_id ) ) {
			return;
		}

		$is_running = true;
		wipe_clean_seed_faq_page_sections( $post_id );
		$is_running = false;
	}
}
add_action( 'save_post_page', 'wipe_clean_maybe_seed_faq_page_on_save' );

if ( ! function_exists( 'wipe_clean_get_faq_page_seed_action_url' ) ) {
	function wipe_clean_get_faq_page_seed_action_url( $post_id ) {
		$post_id = (int) $post_id;

		if ( ! $post_id ) {
			return '';
		}

		return wp_nonce_url(
			admin_url( 'admin-post.php?action=wipe_clean_seed_faq_page&post_id=' . $post_id ),
			'wipe_clean_seed_faq_page_' . $post_id
		);
	}
}

if ( ! function_exists( 'wipe_clean_render_faq_page_seed_box' ) ) {
	function wipe_clean_render_faq_page_seed_box( $post ) {
		if ( ! $post instanceof WP_Post || ! wipe_clean_is_faq_page_post( $post->ID ) || ! current_user_can( 'edit_post', $post->ID ) ) {
			return;
		}

		$has_rows   = wipe_clean_faq_page_has_seeded_rows( $post->ID );
		$action_url = wipe_clean_get_faq_page_seed_action_url( $post->ID );

		if ( ! $action_url ) {
			return;
		}
		?>
		<div style="margin:16px 0 18px;padding:16px 18px;border:1px solid #d7e8ee;border-radius:16px;background:linear-gradient(180deg,rgba(255,255,255,0.98) 0%,rgba(250,252,253,0.96) 100%);box-shadow:0 10px 24px rgba(21,15,49,0.06);">
			<div style="display:flex;flex-wrap:wrap;gap:14px;align-items:center;justify-content:space-between;">
				<div style="max-width:760px;">
					<div style="margin:0 0 6px;font-size:16px;font-weight:700;color:#150F31;">Заполнить готовым содержимым</div>
					<div style="font-size:13px;line-height:1.55;color:#5D5779;">
						Подставляет готовые блоки страницы FAQ. Вопросы и ответы загрузятся в нужном порядке, а внешний вид секции сайт соберёт сам.
						<?php if ( $has_rows ) : ?>
							<strong style="color:#150F31;">Текущие значения секций будут заменены.</strong>
						<?php endif; ?>
					</div>
				</div>
				<div>
					<a class="button button-primary button-large" href="<?php echo esc_url( $action_url ); ?>" onclick="return window.confirm('Заполнить страницу FAQ готовым содержимым? Текущие значения секций будут заменены.');">
						<?php echo esc_html( $has_rows ? 'Обновить содержимое' : 'Заполнить готовым' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php
	}
}
add_action( 'edit_form_after_title', 'wipe_clean_render_faq_page_seed_box', 7 );

if ( ! function_exists( 'wipe_clean_handle_faq_page_seed_action' ) ) {
	function wipe_clean_handle_faq_page_seed_action() {
		$post_id = isset( $_GET['post_id'] ) ? (int) $_GET['post_id'] : 0;

		if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
			wp_die( esc_html__( 'Недостаточно прав для этого действия.', 'wipe-clean' ) );
		}

		check_admin_referer( 'wipe_clean_seed_faq_page_' . $post_id );
		wipe_clean_seed_faq_page_sections( $post_id );

		wp_safe_redirect(
			add_query_arg(
				array(
					'post'                       => $post_id,
					'action'                     => 'edit',
					'wipe_clean_faq_page_seeded' => 1,
				),
				admin_url( 'post.php' )
			)
		);
		exit;
	}
}
add_action( 'admin_post_wipe_clean_seed_faq_page', 'wipe_clean_handle_faq_page_seed_action' );

if ( ! function_exists( 'wipe_clean_render_faq_page_seed_notice' ) ) {
	function wipe_clean_render_faq_page_seed_notice() {
		if ( empty( $_GET['wipe_clean_faq_page_seeded'] ) || empty( $_GET['post'] ) ) {
			return;
		}

		$post_id = (int) $_GET['post'];

		if ( ! $post_id || ! wipe_clean_is_faq_page_post( $post_id ) ) {
			return;
		}
		?>
		<div class="notice notice-success is-dismissible">
			<p>Страница FAQ заполнена готовым содержимым.</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'wipe_clean_render_faq_page_seed_notice', 20 );
