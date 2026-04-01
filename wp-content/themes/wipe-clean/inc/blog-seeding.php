<?php
/**
 * One-click seeding for blog archive and single blog posts.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_blog_archive_has_seeded_rows' ) ) {
	function wipe_clean_blog_archive_has_seeded_rows() {
		if ( ! function_exists( 'get_field' ) ) {
			return false;
		}

		$rows = get_field( 'blog_archive_sections', 'option', false );

		return is_array( $rows ) && ! empty( $rows );
	}
}

if ( ! function_exists( 'wipe_clean_blog_post_has_seeded_rows' ) ) {
	function wipe_clean_blog_post_has_seeded_rows( $post_id ) {
		if ( ! function_exists( 'get_field' ) ) {
			return false;
		}

		$rows = get_field( 'blog_post_sections', (int) $post_id, false );

		return is_array( $rows ) && ! empty( $rows );
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_archive_seed_rows' ) ) {
	function wipe_clean_get_blog_archive_seed_rows() {
		$rows = array();

		foreach ( wipe_clean_get_blog_archive_layout_order() as $layout ) {
			$row = wipe_clean_get_blog_archive_section_defaults( $layout );

			if ( function_exists( 'wipe_clean_prepare_front_page_seed_value' ) ) {
				$row = wipe_clean_prepare_front_page_seed_value( $row );
			}

			$rows[] = $row;
		}

		return $rows;
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_post_seed_rows' ) ) {
	function wipe_clean_get_blog_post_seed_rows() {
		$rows = array();

		foreach ( wipe_clean_get_blog_single_layout_order() as $layout ) {
			$row = wipe_clean_get_blog_single_section_defaults( $layout );

			if ( function_exists( 'wipe_clean_prepare_front_page_seed_value' ) ) {
				$row = wipe_clean_prepare_front_page_seed_value( $row );
			}

			$rows[] = $row;
		}

		return $rows;
	}
}

if ( ! function_exists( 'wipe_clean_seed_blog_post_sections' ) ) {
	function wipe_clean_seed_blog_post_sections( $post_id ) {
		$post_id = (int) $post_id;

		if ( ! $post_id || wipe_clean_get_blog_post_type() !== get_post_type( $post_id ) || ! function_exists( 'update_field' ) ) {
			return false;
		}

		update_field( 'blog_post_sections', wipe_clean_get_blog_post_seed_rows(), $post_id );
		update_post_meta( $post_id, '_wipe_clean_blog_post_seeded_at', time() );

		return true;
	}
}

if ( ! function_exists( 'wipe_clean_import_blog_seed_attachment' ) ) {
	function wipe_clean_import_blog_seed_attachment( $media ) {
		if ( function_exists( 'wipe_clean_services_page_import_attachment' ) ) {
			return (int) wipe_clean_services_page_import_attachment( $media );
		}

		return 0;
	}
}

if ( ! function_exists( 'wipe_clean_seed_blog_posts' ) ) {
	function wipe_clean_seed_blog_posts() {
		if ( ! post_type_exists( wipe_clean_get_blog_post_type() ) ) {
			return;
		}

		$default_items = wipe_clean_get_blog_archive_default_items();

		foreach ( $default_items as $index => $item ) {
			$title    = trim( (string) ( $item['title'] ?? '' ) );
			$seed_key = 'blog-article-' . ( $index + 1 );

			if ( '' === $title ) {
				continue;
			}

			$post_id  = 0;
			$existing = get_posts(
				array(
					'post_type'      => wipe_clean_get_blog_post_type(),
					'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
					'posts_per_page' => 1,
					'fields'         => 'ids',
					'meta_key'       => '_wipe_clean_seed_key',
					'meta_value'     => $seed_key,
				)
			);

			if ( ! empty( $existing ) ) {
				$post_id = (int) $existing[0];
			}

			if ( ! $post_id ) {
				$by_title = get_page_by_title( $title, OBJECT, wipe_clean_get_blog_post_type() );

				if ( $by_title instanceof WP_Post ) {
					$post_id = (int) $by_title->ID;
				}
			}

			$post_date = trim( (string) ( $item['dateTime'] ?? '' ) );
			$post_args = array(
				'post_type'    => wipe_clean_get_blog_post_type(),
				'post_title'   => $title,
				'post_status'  => 'publish',
				'post_excerpt' => (string) ( $item['excerpt'] ?? '' ),
				'post_content' => wipe_clean_get_blog_single_default_content_html(),
				'menu_order'   => $index + 1,
			);

			if ( '' !== $post_date ) {
				$post_args['post_date']     = $post_date . ' 09:00:00';
				$post_args['post_date_gmt'] = get_gmt_from_date( $post_args['post_date'] );
			}

			if ( $post_id ) {
				$post_args['ID'] = $post_id;
				$post_id         = wp_update_post( $post_args, true );
			} else {
				$post_id = wp_insert_post( $post_args, true );
			}

			if ( is_wp_error( $post_id ) || ! $post_id ) {
				continue;
			}

			update_post_meta( $post_id, '_wipe_clean_seed_key', $seed_key );

			$image_id = wipe_clean_import_blog_seed_attachment( $item['image'] ?? array() );

			if ( $image_id ) {
				set_post_thumbnail( $post_id, $image_id );
			}

			wipe_clean_seed_blog_post_sections( $post_id );
		}
	}
}

if ( ! function_exists( 'wipe_clean_seed_blog_archive_sections' ) ) {
	function wipe_clean_seed_blog_archive_sections() {
		if ( ! function_exists( 'update_field' ) ) {
			return false;
		}

		update_field( 'blog_archive_sections', wipe_clean_get_blog_archive_seed_rows(), 'option' );
		wipe_clean_seed_blog_posts();
		update_option( 'wipe_clean_blog_archive_seeded_at', time() );

		return true;
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_archive_seed_action_url' ) ) {
	function wipe_clean_get_blog_archive_seed_action_url() {
		return wp_nonce_url(
			admin_url( 'admin-post.php?action=wipe_clean_seed_blog_archive' ),
			'wipe_clean_seed_blog_archive'
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_post_seed_action_url' ) ) {
	function wipe_clean_get_blog_post_seed_action_url( $post_id ) {
		$post_id = (int) $post_id;

		if ( ! $post_id || wipe_clean_get_blog_post_type() !== get_post_type( $post_id ) ) {
			return '';
		}

		return wp_nonce_url(
			admin_url( 'admin-post.php?action=wipe_clean_seed_blog_post&post_id=' . $post_id ),
			'wipe_clean_seed_blog_post_' . $post_id
		);
	}
}

if ( ! function_exists( 'wipe_clean_is_blog_archive_options_admin_screen' ) ) {
	function wipe_clean_is_blog_archive_options_admin_screen() {
		return is_admin() && isset( $_GET['page'] ) && wipe_clean_get_blog_archive_options_slug() === (string) $_GET['page'];
	}
}

if ( ! function_exists( 'wipe_clean_render_blog_archive_seed_box' ) ) {
	function wipe_clean_render_blog_archive_seed_box() {
		if ( ! wipe_clean_is_blog_archive_options_admin_screen() || ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$has_rows   = wipe_clean_blog_archive_has_seeded_rows();
		$action_url = wipe_clean_get_blog_archive_seed_action_url();
		?>
		<div class="notice notice-info" style="margin-top:16px;padding:0;border:none;background:transparent;box-shadow:none;">
			<div style="padding:16px 18px;border:1px solid #d7e8ee;border-radius:16px;background:linear-gradient(180deg,rgba(255,255,255,0.98) 0%,rgba(250,252,253,0.96) 100%);box-shadow:0 10px 24px rgba(21,15,49,0.06);">
				<div style="display:flex;flex-wrap:wrap;gap:14px;align-items:center;justify-content:space-between;">
					<div style="max-width:760px;">
						<div style="margin:0 0 6px;font-size:16px;font-weight:700;color:#150F31;">Заполнить готовым содержимым</div>
						<div style="font-size:13px;line-height:1.55;color:#5D5779;">
							Подставляет готовые блоки страницы блога и одновременно создаёт или обновляет статьи для карточек и внутренних страниц.
							<?php if ( $has_rows ) : ?>
								<strong style="color:#150F31;">Текущие значения секций будут заменены.</strong>
							<?php endif; ?>
						</div>
					</div>
					<div>
						<a class="button button-primary button-large" href="<?php echo esc_url( $action_url ); ?>" onclick="return window.confirm('Заполнить страницу блога готовым содержимым? Текущие значения блоков будут заменены, а готовые статьи обновлены.');">
							<?php echo esc_html( $has_rows ? 'Обновить содержимое' : 'Заполнить готовым' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'wipe_clean_render_blog_archive_seed_box', 5 );

if ( ! function_exists( 'wipe_clean_handle_blog_archive_seed_action' ) ) {
	function wipe_clean_handle_blog_archive_seed_action() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( esc_html__( 'Недостаточно прав для этого действия.', 'wipe-clean' ) );
		}

		check_admin_referer( 'wipe_clean_seed_blog_archive' );
		wipe_clean_seed_blog_archive_sections();

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'                           => wipe_clean_get_blog_archive_options_slug(),
					'wipe_clean_blog_archive_seeded' => 1,
				),
				admin_url( 'admin.php' )
			)
		);
		exit;
	}
}
add_action( 'admin_post_wipe_clean_seed_blog_archive', 'wipe_clean_handle_blog_archive_seed_action' );

if ( ! function_exists( 'wipe_clean_render_blog_archive_seed_notice' ) ) {
	function wipe_clean_render_blog_archive_seed_notice() {
		if ( empty( $_GET['wipe_clean_blog_archive_seeded'] ) || ! wipe_clean_is_blog_archive_options_admin_screen() ) {
			return;
		}
		?>
		<div class="notice notice-success is-dismissible">
			<p>Страница блога заполнена готовым содержимым. Готовые статьи тоже созданы или обновлены.</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'wipe_clean_render_blog_archive_seed_notice', 20 );

if ( ! function_exists( 'wipe_clean_render_blog_post_seed_box' ) ) {
	function wipe_clean_render_blog_post_seed_box( $post ) {
		if ( ! $post instanceof WP_Post || wipe_clean_get_blog_post_type() !== $post->post_type || ! current_user_can( 'edit_post', $post->ID ) ) {
			return;
		}

		$has_rows   = wipe_clean_blog_post_has_seeded_rows( $post->ID );
		$action_url = wipe_clean_get_blog_post_seed_action_url( $post->ID );

		if ( ! $action_url ) {
			return;
		}
		?>
		<div style="margin:16px 0 18px;padding:16px 18px;border:1px solid #d7e8ee;border-radius:16px;background:linear-gradient(180deg,rgba(255,255,255,0.98) 0%,rgba(250,252,253,0.96) 100%);box-shadow:0 10px 24px rgba(21,15,49,0.06);">
			<div style="display:flex;flex-wrap:wrap;gap:14px;align-items:center;justify-content:space-between;">
				<div style="max-width:760px;">
					<div style="margin:0 0 6px;font-size:16px;font-weight:700;color:#150F31;">Заполнить готовым содержимым</div>
					<div style="font-size:13px;line-height:1.55;color:#5D5779;">
						Подставляет готовые блоки страницы статьи. Карточка статьи и первый экран всё равно берут данные из самой записи: название, краткое описание, изображение записи и дату публикации.
						<?php if ( $has_rows ) : ?>
							<strong style="color:#150F31;">Текущие блоки страницы будут заменены.</strong>
						<?php endif; ?>
					</div>
				</div>
				<div>
					<a class="button button-primary button-large" href="<?php echo esc_url( $action_url ); ?>" onclick="return window.confirm('Заполнить страницу статьи готовым содержимым? Текущие блоки страницы будут заменены.');">
						<?php echo esc_html( $has_rows ? 'Обновить содержимое' : 'Заполнить готовым' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php
	}
}
add_action( 'edit_form_after_title', 'wipe_clean_render_blog_post_seed_box', 7 );

if ( ! function_exists( 'wipe_clean_handle_blog_post_seed_action' ) ) {
	function wipe_clean_handle_blog_post_seed_action() {
		$post_id = isset( $_GET['post_id'] ) ? (int) $_GET['post_id'] : 0;

		if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
			wp_die( esc_html__( 'Недостаточно прав для этого действия.', 'wipe-clean' ) );
		}

		check_admin_referer( 'wipe_clean_seed_blog_post_' . $post_id );
		wipe_clean_seed_blog_post_sections( $post_id );

		wp_safe_redirect(
			add_query_arg(
				array(
					'post'                        => $post_id,
					'action'                      => 'edit',
					'wipe_clean_blog_post_seeded' => 1,
				),
				admin_url( 'post.php' )
			)
		);
		exit;
	}
}
add_action( 'admin_post_wipe_clean_seed_blog_post', 'wipe_clean_handle_blog_post_seed_action' );

if ( ! function_exists( 'wipe_clean_render_blog_post_seed_notice' ) ) {
	function wipe_clean_render_blog_post_seed_notice() {
		if ( empty( $_GET['wipe_clean_blog_post_seeded'] ) || empty( $_GET['post'] ) ) {
			return;
		}

		$post_id = (int) $_GET['post'];

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		if ( ! $screen || wipe_clean_get_blog_post_type() !== $screen->post_type ) {
			return;
		}
		?>
		<div class="notice notice-success is-dismissible">
			<p>Статья блога заполнена готовым содержимым.</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'wipe_clean_render_blog_post_seed_notice' );
