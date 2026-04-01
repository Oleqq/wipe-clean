<?php
/**
 * One-click seeding for document pages.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_document_page_has_seeded_content' ) ) {
	function wipe_clean_document_page_has_seeded_content( $post_id ) {
		$post_id = (int) $post_id;

		if ( ! $post_id ) {
			return false;
		}

		$content = (string) get_post_field( 'post_content', $post_id );

		return '' !== trim( wp_strip_all_tags( $content ) );
	}
}

if ( ! function_exists( 'wipe_clean_seed_document_page_content' ) ) {
	function wipe_clean_seed_document_page_content( $post_id ) {
		$post_id = (int) $post_id;

		if ( ! $post_id || ! wipe_clean_is_document_page_post( $post_id ) ) {
			return false;
		}

		$current_title = trim( (string) get_the_title( $post_id ) );
		$post_slug     = (string) get_post_field( 'post_name', $post_id );
		$seed_title    = wipe_clean_get_document_page_default_title( $post_id );

		wp_update_post(
			array(
				'ID'           => $post_id,
				'post_title'   => ( '' !== $current_title && 'policy' !== $post_slug ) ? $current_title : $seed_title,
				'post_content' => wipe_clean_get_document_page_default_content_html(),
			)
		);

		update_post_meta( $post_id, '_wipe_clean_document_page_seeded_at', time() );

		return true;
	}
}

if ( ! function_exists( 'wipe_clean_get_document_page_seed_action_url' ) ) {
	function wipe_clean_get_document_page_seed_action_url( $post_id ) {
		$post_id = (int) $post_id;

		if ( ! $post_id ) {
			return '';
		}

		return wp_nonce_url(
			admin_url( 'admin-post.php?action=wipe_clean_seed_document_page&post_id=' . $post_id ),
			'wipe_clean_seed_document_page_' . $post_id
		);
	}
}

if ( ! function_exists( 'wipe_clean_render_document_page_seed_box' ) ) {
	function wipe_clean_render_document_page_seed_box( $post ) {
		if ( ! $post instanceof WP_Post || ! wipe_clean_is_document_page_post( $post->ID ) || ! current_user_can( 'edit_post', $post->ID ) ) {
			return;
		}

		$has_content = wipe_clean_document_page_has_seeded_content( $post->ID );
		$action_url  = wipe_clean_get_document_page_seed_action_url( $post->ID );

		if ( ! $action_url ) {
			return;
		}
		?>
		<div style="margin:16px 0 18px;padding:16px 18px;border:1px solid #d7e8ee;border-radius:16px;background:linear-gradient(180deg,rgba(255,255,255,0.98) 0%,rgba(250,252,253,0.96) 100%);box-shadow:0 10px 24px rgba(21,15,49,0.06);">
			<div style="display:flex;flex-wrap:wrap;gap:14px;align-items:center;justify-content:space-between;">
				<div style="max-width:760px;">
					<div style="margin:0 0 6px;font-size:16px;font-weight:700;color:#150F31;">Заполнить готовым текстом</div>
					<div style="font-size:13px;line-height:1.55;color:#5D5779;">
						Подставляет готовый текст документа в обычный редактор WordPress. Дальше его можно спокойно править как обычную страницу.
						<?php if ( $has_content ) : ?>
							<strong style="color:#150F31;">Текущий текст страницы будет заменён.</strong>
						<?php endif; ?>
					</div>
				</div>
				<div>
					<a class="button button-primary button-large" href="<?php echo esc_url( $action_url ); ?>" onclick="return window.confirm('Заполнить страницу документа готовым текстом? Текущее содержимое страницы будет заменено.');">
						<?php echo esc_html( $has_content ? 'Обновить содержимое' : 'Заполнить готовым' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php
	}
}
add_action( 'edit_form_after_title', 'wipe_clean_render_document_page_seed_box', 7 );

if ( ! function_exists( 'wipe_clean_handle_document_page_seed_action' ) ) {
	function wipe_clean_handle_document_page_seed_action() {
		$post_id = isset( $_GET['post_id'] ) ? (int) $_GET['post_id'] : 0;

		if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
			wp_die( esc_html__( 'Недостаточно прав для этого действия.', 'wipe-clean' ) );
		}

		check_admin_referer( 'wipe_clean_seed_document_page_' . $post_id );
		wipe_clean_seed_document_page_content( $post_id );

		wp_safe_redirect(
			add_query_arg(
				array(
					'post'                            => $post_id,
					'action'                          => 'edit',
					'wipe_clean_document_page_seeded' => 1,
				),
				admin_url( 'post.php' )
			)
		);
		exit;
	}
}
add_action( 'admin_post_wipe_clean_seed_document_page', 'wipe_clean_handle_document_page_seed_action' );

if ( ! function_exists( 'wipe_clean_render_document_page_seed_notice' ) ) {
	function wipe_clean_render_document_page_seed_notice() {
		if ( empty( $_GET['wipe_clean_document_page_seeded'] ) || empty( $_GET['post'] ) ) {
			return;
		}

		$post_id = (int) $_GET['post'];

		if ( ! $post_id || ! wipe_clean_is_document_page_post( $post_id ) ) {
			return;
		}
		?>
		<div class="notice notice-success is-dismissible">
			<p>Страница документа заполнена готовым текстом.</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'wipe_clean_render_document_page_seed_notice', 20 );
