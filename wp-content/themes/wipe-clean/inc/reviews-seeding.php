<?php
/**
 * One-click seeding for the reviews archive and demo review records.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_reviews_archive_has_seeded_rows' ) ) {
	function wipe_clean_reviews_archive_has_seeded_rows() {
		if ( ! function_exists( 'get_field' ) ) {
			return false;
		}

		$rows = get_field( 'reviews_archive_sections', 'option', false );

		return is_array( $rows ) && ! empty( $rows );
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_archive_seed_rows' ) ) {
	function wipe_clean_get_reviews_archive_seed_rows() {
		$rows = array();

		foreach ( wipe_clean_get_reviews_archive_layout_order() as $layout ) {
			$row = wipe_clean_get_reviews_archive_section_defaults( $layout );

			if ( function_exists( 'wipe_clean_prepare_front_page_seed_value' ) ) {
				$row = wipe_clean_prepare_front_page_seed_value( $row );
			}

			$rows[] = $row;
		}

		return $rows;
	}
}

if ( ! function_exists( 'wipe_clean_import_review_seed_attachment' ) ) {
	function wipe_clean_import_review_seed_attachment( $media ) {
		if ( function_exists( 'wipe_clean_services_page_import_attachment' ) ) {
			return (int) wipe_clean_services_page_import_attachment( $media );
		}

		return 0;
	}
}

if ( ! function_exists( 'wipe_clean_upsert_seeded_review_post' ) ) {
	function wipe_clean_upsert_seeded_review_post( $seed_key, $title, $menu_order ) {
		$post_id = function_exists( 'wipe_clean_upsert_seeded_post' )
			? wipe_clean_upsert_seeded_post(
				wipe_clean_get_reviews_post_type(),
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

if ( ! function_exists( 'wipe_clean_seed_text_review_posts' ) ) {
	function wipe_clean_seed_text_review_posts() {
		foreach ( wipe_clean_get_reviews_default_text_items() as $index => $item ) {
			$post_id = wipe_clean_upsert_seeded_review_post(
				(string) ( $item['seed_key'] ?? 'archive-review-text-' . ( $index + 1 ) ),
				(string) ( $item['title'] ?? 'Текстовый отзыв ' . ( $index + 1 ) ),
				$index + 1
			);

			if ( ! $post_id || ! function_exists( 'update_field' ) ) {
				continue;
			}

			update_field( 'review_type', 'text', $post_id );
			update_field( 'author_name', (string) ( $item['author'] ?? '' ), $post_id );
			update_field( 'review_text', (string) ( $item['text'] ?? '' ), $post_id );
			update_field( 'rating', (int) ( $item['rating'] ?? 5 ), $post_id );
			update_field( 'show_on_home', 0, $post_id );
			update_field( 'home_order', ( $index + 1 ) * 10, $post_id );
		}
	}
}

if ( ! function_exists( 'wipe_clean_seed_video_review_posts' ) ) {
	function wipe_clean_seed_video_review_posts() {
		foreach ( wipe_clean_get_reviews_default_video_items() as $index => $item ) {
			$post_id = wipe_clean_upsert_seeded_review_post(
				(string) ( $item['seed_key'] ?? 'archive-review-video-' . ( $index + 1 ) ),
				(string) ( $item['title'] ?? 'Видео отзыв ' . ( $index + 1 ) ),
				$index + 1
			);

			if ( ! $post_id || ! function_exists( 'update_field' ) ) {
				continue;
			}

			update_field( 'review_type', 'video', $post_id );
			update_field( 'video_poster', wipe_clean_import_review_seed_attachment( $item['poster'] ?? array() ), $post_id );
			update_field( 'video_file', wipe_clean_import_review_seed_attachment( $item['videoSrc'] ?? '' ), $post_id );
			update_field( 'video_url', '', $post_id );
			update_field( 'video_caption', (string) ( $item['caption'] ?? '' ), $post_id );
			update_field( 'video_alt', (string) ( $item['alt'] ?? '' ), $post_id );
		}
	}
}

if ( ! function_exists( 'wipe_clean_seed_message_review_posts' ) ) {
	function wipe_clean_seed_message_review_posts() {
		foreach ( wipe_clean_get_reviews_default_message_items() as $index => $item ) {
			$post_id = wipe_clean_upsert_seeded_review_post(
				(string) ( $item['seed_key'] ?? 'archive-review-photo-' . ( $index + 1 ) ),
				(string) ( $item['title'] ?? 'Фото отзыв ' . ( $index + 1 ) ),
				$index + 1
			);

			if ( ! $post_id || ! function_exists( 'update_field' ) ) {
				continue;
			}

			update_field( 'review_type', 'photo', $post_id );
			update_field( 'photo_image', wipe_clean_import_review_seed_attachment( $item['image'] ?? array() ), $post_id );
			update_field( 'photo_lightbox_image', wipe_clean_import_review_seed_attachment( $item['lightboxImage'] ?? array() ), $post_id );
			update_field( 'photo_caption', (string) ( $item['caption'] ?? '' ), $post_id );
			update_field( 'photo_alt', (string) ( $item['alt'] ?? '' ), $post_id );
		}
	}
}

if ( ! function_exists( 'wipe_clean_seed_review_posts' ) ) {
	function wipe_clean_seed_review_posts() {
		if ( ! post_type_exists( wipe_clean_get_reviews_post_type() ) ) {
			return;
		}

		wipe_clean_seed_text_review_posts();
		wipe_clean_seed_video_review_posts();
		wipe_clean_seed_message_review_posts();
	}
}

if ( ! function_exists( 'wipe_clean_seed_reviews_archive_sections' ) ) {
	function wipe_clean_seed_reviews_archive_sections() {
		if ( ! function_exists( 'update_field' ) ) {
			return false;
		}

		update_field( 'reviews_archive_sections', wipe_clean_get_reviews_archive_seed_rows(), 'option' );
		wipe_clean_seed_review_posts();
		update_option( 'wipe_clean_reviews_archive_seeded_at', time() );

		return true;
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_archive_seed_action_url' ) ) {
	function wipe_clean_get_reviews_archive_seed_action_url() {
		return wp_nonce_url(
			admin_url( 'admin-post.php?action=wipe_clean_seed_reviews_archive' ),
			'wipe_clean_seed_reviews_archive'
		);
	}
}

if ( ! function_exists( 'wipe_clean_is_reviews_archive_options_admin_screen' ) ) {
	function wipe_clean_is_reviews_archive_options_admin_screen() {
		return is_admin() && isset( $_GET['page'] ) && wipe_clean_get_reviews_archive_options_slug() === (string) $_GET['page'];
	}
}

if ( ! function_exists( 'wipe_clean_render_reviews_archive_seed_box' ) ) {
	function wipe_clean_render_reviews_archive_seed_box() {
		if ( ! wipe_clean_is_reviews_archive_options_admin_screen() || ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$has_rows   = wipe_clean_reviews_archive_has_seeded_rows();
		$action_url = wipe_clean_get_reviews_archive_seed_action_url();
		?>
		<div class="notice notice-info" style="margin-top:16px;padding:0;border:none;background:transparent;box-shadow:none;">
			<div style="padding:16px 18px;border:1px solid #d7e8ee;border-radius:16px;background:linear-gradient(180deg,rgba(255,255,255,0.98) 0%,rgba(250,252,253,0.96) 100%);box-shadow:0 10px 24px rgba(21,15,49,0.06);">
				<div style="display:flex;flex-wrap:wrap;gap:14px;align-items:center;justify-content:space-between;">
					<div style="max-width:760px;">
						<div style="margin:0 0 6px;font-size:16px;font-weight:700;color:#150F31;">Заполнить готовым содержимым</div>
						<div style="font-size:13px;line-height:1.55;color:#5D5779;">
							Подставляет готовые блоки страницы отзывов и одновременно создаёт или обновляет готовые отзывы трёх типов: текстовые, видео и фото.
							<?php if ( $has_rows ) : ?>
								<strong style="color:#150F31;">Текущие значения секций будут заменены.</strong>
							<?php endif; ?>
						</div>
					</div>
					<div>
						<a class="button button-primary button-large" href="<?php echo esc_url( $action_url ); ?>" onclick="return window.confirm('Заполнить страницу отзывов готовым содержимым? Текущие блоки будут заменены, а готовые отзывы обновлены.');">
							<?php echo esc_html( $has_rows ? 'Обновить содержимое' : 'Заполнить готовым' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'wipe_clean_render_reviews_archive_seed_box', 5 );

if ( ! function_exists( 'wipe_clean_handle_reviews_archive_seed_action' ) ) {
	function wipe_clean_handle_reviews_archive_seed_action() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( esc_html__( 'Недостаточно прав для этого действия.', 'wipe-clean' ) );
		}

		check_admin_referer( 'wipe_clean_seed_reviews_archive' );
		wipe_clean_seed_reviews_archive_sections();

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'                              => wipe_clean_get_reviews_archive_options_slug(),
					'wipe_clean_reviews_archive_seeded' => 1,
				),
				admin_url( 'admin.php' )
			)
		);
		exit;
	}
}
add_action( 'admin_post_wipe_clean_seed_reviews_archive', 'wipe_clean_handle_reviews_archive_seed_action' );

if ( ! function_exists( 'wipe_clean_render_reviews_archive_seed_notice' ) ) {
	function wipe_clean_render_reviews_archive_seed_notice() {
		if ( empty( $_GET['wipe_clean_reviews_archive_seeded'] ) || ! wipe_clean_is_reviews_archive_options_admin_screen() ) {
			return;
		}
		?>
		<div class="notice notice-success is-dismissible">
			<p>Страница отзывов заполнена готовым содержимым. Готовые отзывы всех трёх типов тоже созданы или обновлены.</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'wipe_clean_render_reviews_archive_seed_notice', 20 );
