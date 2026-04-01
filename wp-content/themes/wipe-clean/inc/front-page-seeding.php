<?php
/**
 * One-click seeding for front-page ACF blocks.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check whether the post uses the front-page section set.
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function wipe_clean_is_front_page_sections_post( $post_id ) {
	$post_id = (int) $post_id;

	if ( ! $post_id || 'page' !== get_post_type( $post_id ) ) {
		return false;
	}

	if ( (int) get_option( 'page_on_front' ) === $post_id ) {
		return true;
	}

	return 'template-home-page.php' === get_page_template_slug( $post_id );
}

/**
 * Check whether the page already has flexible rows filled.
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function wipe_clean_front_page_has_seeded_rows( $post_id ) {
	if ( ! function_exists( 'get_field' ) ) {
		return false;
	}

	$sections = get_field( 'front_page_sections', (int) $post_id, false );

	return is_array( $sections ) && ! empty( $sections );
}

/**
 * Get mime type for local asset import.
 *
 * @param string $file_path Absolute file path.
 * @return string
 */
function wipe_clean_get_local_asset_mime_type( $file_path ) {
	$extension = strtolower( pathinfo( (string) $file_path, PATHINFO_EXTENSION ) );
	$map       = array(
		'png'  => 'image/png',
		'jpg'  => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'webp' => 'image/webp',
		'svg'  => 'image/svg+xml',
		'gif'  => 'image/gif',
	);

	return $map[ $extension ] ?? 'application/octet-stream';
}

/**
 * Import local theme asset into the media library if needed.
 *
 * @param string $relative_path Relative theme asset path.
 * @param string $alt           Alt text.
 * @return int
 */
function wipe_clean_import_theme_asset_attachment( $relative_path, $alt = '' ) {
	$relative_path = ltrim( (string) $relative_path, '/' );

	if ( '' === $relative_path ) {
		return 0;
	}

	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_wipe_clean_theme_asset_source',
			'meta_value'     => $relative_path,
		)
	);

	if ( ! empty( $existing[0] ) ) {
		return (int) $existing[0];
	}

	$source_path = wipe_clean_asset_path( $relative_path );

	if ( ! file_exists( $source_path ) ) {
		return 0;
	}

	$upload_dir = wp_upload_dir();

	if ( ! empty( $upload_dir['error'] ) ) {
		return 0;
	}

	$target_dir = trailingslashit( $upload_dir['basedir'] ) . 'wipe-clean-seeded';

	if ( ! file_exists( $target_dir ) ) {
		wp_mkdir_p( $target_dir );
	}

	$filename    = wp_unique_filename( $target_dir, wp_basename( $source_path ) );
	$target_path = trailingslashit( $target_dir ) . $filename;
	$target_url  = trailingslashit( $upload_dir['baseurl'] ) . 'wipe-clean-seeded/' . $filename;

	if ( ! copy( $source_path, $target_path ) ) {
		return 0;
	}

	$attachment_id = wp_insert_attachment(
		array(
			'post_mime_type' => wipe_clean_get_local_asset_mime_type( $target_path ),
			'post_title'     => preg_replace( '/\.[^.]+$/', '', $filename ),
			'post_content'   => '',
			'post_status'    => 'inherit',
			'guid'           => $target_url,
		),
		$target_path
	);

	if ( is_wp_error( $attachment_id ) || ! $attachment_id ) {
		return 0;
	}

	add_post_meta( $attachment_id, '_wipe_clean_theme_asset_source', $relative_path, true );

	if ( '' !== trim( $alt ) ) {
		update_post_meta( $attachment_id, '_wp_attachment_image_alt', wp_strip_all_tags( $alt ) );
	}

	$extension = strtolower( pathinfo( $target_path, PATHINFO_EXTENSION ) );

	if ( ! in_array( $extension, array( 'svg' ), true ) ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$metadata = wp_generate_attachment_metadata( $attachment_id, $target_path );

		if ( ! is_wp_error( $metadata ) && ! empty( $metadata ) ) {
			wp_update_attachment_metadata( $attachment_id, $metadata );
		}
	}

	return (int) $attachment_id;
}

/**
 * Convert fallback values into ACF-ready values.
 *
 * @param mixed $value Raw fallback value.
 * @return mixed
 */
function wipe_clean_prepare_front_page_seed_value( $value ) {
	if ( is_array( $value ) ) {
		if ( isset( $value['path'] ) ) {
			return wipe_clean_import_theme_asset_attachment( (string) $value['path'], (string) ( $value['alt'] ?? '' ) );
		}

		$prepared = array();

		foreach ( $value as $key => $item ) {
			$prepared[ $key ] = wipe_clean_prepare_front_page_seed_value( $item );
		}

		return $prepared;
	}

	return $value;
}

/**
 * Get ACF-ready rows for the front-page flexible field.
 *
 * @return array<int, array<string, mixed>>
 */
function wipe_clean_get_front_page_seed_rows() {
	$rows = array();

	foreach ( wipe_clean_get_front_page_layout_order() as $layout ) {
		$rows[] = wipe_clean_prepare_front_page_seed_value( wipe_clean_get_front_page_section_defaults( $layout ) );
	}

	return $rows;
}

/**
 * Find seeded CPT post by stable key.
 *
 * @param string $post_type Post type.
 * @param string $seed_key  Stable seed key.
 * @return int
 */
function wipe_clean_get_seeded_cpt_post_id( $post_type, $seed_key ) {
	$posts = get_posts(
		array(
			'post_type'      => $post_type,
			'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_wipe_clean_seed_key',
			'meta_value'     => (string) $seed_key,
		)
	);

	return ! empty( $posts[0] ) ? (int) $posts[0] : 0;
}

/**
 * Create or update a seeded post.
 *
 * @param string $post_type Post type.
 * @param string $seed_key  Stable seed key.
 * @param array  $postarr   Post arguments.
 * @return int
 */
function wipe_clean_upsert_seeded_post( $post_type, $seed_key, $postarr ) {
	$post_id = wipe_clean_get_seeded_cpt_post_id( $post_type, $seed_key );
	$postarr = array_merge(
		array(
			'post_type'   => $post_type,
			'post_status' => 'publish',
		),
		(array) $postarr
	);

	if ( $post_id ) {
		$postarr['ID'] = $post_id;
		$post_id       = wp_update_post( wp_slash( $postarr ), true );
	} else {
		$post_id = wp_insert_post( wp_slash( $postarr ), true );
	}

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return 0;
	}

	update_post_meta( $post_id, '_wipe_clean_seed_key', (string) $seed_key );

	return (int) $post_id;
}

/**
 * Seed homepage service records.
 *
 * @return array<int>
 */
function wipe_clean_seed_front_page_service_posts() {
	$post_ids = array();

	if ( ! post_type_exists( 'wipe_service' ) || ! function_exists( 'update_field' ) ) {
		return $post_ids;
	}

	foreach ( wipe_clean_get_front_page_default_service_items() as $item ) {
		$seed_key = (string) ( $item['seed_key'] ?? '' );

		if ( '' === $seed_key ) {
			continue;
		}

		$post_id = wipe_clean_upsert_seeded_post(
			'wipe_service',
			$seed_key,
			array(
				'post_title'   => (string) ( $item['title'] ?? '' ),
				'post_content' => '',
				'menu_order'   => (int) ( $item['home_order'] ?? 0 ),
			)
		);

		if ( ! $post_id ) {
			continue;
		}

		$image    = $item['image'] ?? array();
		$image_id = 0;

		if ( is_array( $image ) && ! empty( $image['path'] ) ) {
			$image_id = wipe_clean_import_theme_asset_attachment( (string) $image['path'], (string) ( $item['title'] ?? '' ) );
		}

		update_field( 'service_price_value', (string) ( $item['price'] ?? '' ), $post_id );

		if ( $image_id ) {
			set_post_thumbnail( $post_id, $image_id );
		}

		$post_ids[] = $post_id;
	}

	return $post_ids;
}

/**
 * Seed homepage review records.
 *
 * @return array<int>
 */
function wipe_clean_seed_front_page_review_posts() {
	$post_ids = array();

	if ( ! post_type_exists( 'wipe_review' ) || ! function_exists( 'update_field' ) ) {
		return $post_ids;
	}

	foreach ( wipe_clean_get_front_page_default_review_items() as $index => $item ) {
		$seed_key = (string) ( $item['seed_key'] ?? '' );

		if ( '' === $seed_key ) {
			continue;
		}

		$author  = (string) ( $item['author'] ?? '' );
		$post_id = wipe_clean_upsert_seeded_post(
			'wipe_review',
			$seed_key,
			array(
				'post_title'   => '' !== $author ? $author : sprintf( 'Отзыв %d', $index + 1 ),
				'post_content' => '',
			)
		);

		if ( ! $post_id ) {
			continue;
		}

		update_field( 'author_name', $author, $post_id );
		update_field( 'review_text', (string) ( $item['text'] ?? '' ), $post_id );
		update_field( 'rating', (int) ( $item['rating'] ?? 5 ), $post_id );
		update_field( 'show_on_home', 1, $post_id );
		update_field( 'home_order', (int) ( $item['home_order'] ?? 10 ), $post_id );

		$post_ids[] = $post_id;
	}

	return $post_ids;
}

/**
 * Seed front-page ACF blocks for a page.
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function wipe_clean_seed_front_page_sections( $post_id ) {
	$post_id = (int) $post_id;

	if ( ! $post_id || ! function_exists( 'update_field' ) || ! wipe_clean_is_front_page_sections_post( $post_id ) ) {
		return false;
	}

	update_field( 'front_page_sections', wipe_clean_get_front_page_seed_rows(), $post_id );
	wipe_clean_seed_front_page_service_posts();
	wipe_clean_seed_front_page_review_posts();
	update_post_meta( $post_id, '_wipe_clean_front_page_seeded_at', time() );

	return true;
}

/**
 * Auto-seed empty front-page templates on first save.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Current post.
 * @return void
 */
function wipe_clean_maybe_seed_front_page_on_save( $post_id, $post ) {
	if ( wp_is_post_revision( $post_id ) || 'page' !== $post->post_type ) {
		return;
	}

	if ( ! wipe_clean_is_front_page_sections_post( $post_id ) || wipe_clean_front_page_has_seeded_rows( $post_id ) ) {
		return;
	}

	wipe_clean_seed_front_page_sections( $post_id );
}
add_action( 'save_post_page', 'wipe_clean_maybe_seed_front_page_on_save', 30, 2 );

/**
 * Render quick seed box above the editor.
 *
 * @param WP_Post $post Current post.
 * @return void
 */
function wipe_clean_render_front_page_seed_box( $post ) {
	if ( ! $post instanceof WP_Post || ! current_user_can( 'edit_post', $post->ID ) || ! wipe_clean_is_front_page_sections_post( $post->ID ) ) {
		return;
	}

	$has_rows   = wipe_clean_front_page_has_seeded_rows( $post->ID );
	$action_url = wp_nonce_url(
		admin_url( 'admin-post.php?action=wipe_clean_seed_front_page&post_id=' . (int) $post->ID ),
		'wipe_clean_seed_front_page_' . (int) $post->ID
	);
	?>
	<div style="margin:16px 0 18px;padding:16px 18px;border:1px solid #d7e8ee;border-radius:16px;background:linear-gradient(180deg,rgba(255,255,255,0.98) 0%,rgba(250,252,253,0.96) 100%);box-shadow:0 10px 24px rgba(21,15,49,0.06);">
		<div style="display:flex;flex-wrap:wrap;gap:14px;align-items:center;justify-content:space-between;">
			<div style="max-width:760px;">
				<div style="margin:0 0 6px;font-size:16px;font-weight:700;color:#150F31;">Быстрое заполнение блоков</div>
				<div style="font-size:13px;line-height:1.55;color:#5D5779;">
					Можно автоматически заполнить блоки главной страницы значениями из готовой версии сайта. Это поможет сразу проверить, что тексты, кнопки, списки и изображения правильно привязаны к полям.
					<?php if ( $has_rows ) : ?>
						<strong style="color:#150F31;">Текущие значения будут заменены.</strong>
					<?php endif; ?>
				</div>
			</div>
			<div>
				<a class="button button-primary button-large" href="<?php echo esc_url( $action_url ); ?>" onclick="return window.confirm('Заполнить блоки значениями из готовой версии сайта? Текущие данные в этих блоках будут заменены.');">
					<?php echo esc_html( $has_rows ? 'Обновить по готовой версии' : 'Заполнить по готовой версии' ); ?>
				</a>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'edit_form_after_title', 'wipe_clean_render_front_page_seed_box', 6 );

/**
 * Handle manual seeding request.
 *
 * @return void
 */
function wipe_clean_handle_front_page_seed_action() {
	$post_id = isset( $_GET['post_id'] ) ? (int) $_GET['post_id'] : 0;

	if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
		wp_die( esc_html__( 'Недостаточно прав для этого действия.', 'wipe-clean' ) );
	}

	check_admin_referer( 'wipe_clean_seed_front_page_' . $post_id );

	wipe_clean_seed_front_page_sections( $post_id );

	wp_safe_redirect(
		add_query_arg(
			array(
				'post'                  => $post_id,
				'action'                => 'edit',
				'wipe_clean_seeded'     => 1,
			),
			admin_url( 'post.php' )
		)
	);
	exit;
}
add_action( 'admin_post_wipe_clean_seed_front_page', 'wipe_clean_handle_front_page_seed_action' );

/**
 * Show success notice after manual seed.
 *
 * @return void
 */
function wipe_clean_render_front_page_seed_notice() {
	if ( empty( $_GET['wipe_clean_seeded'] ) || empty( $_GET['post'] ) ) {
		return;
	}

	$post_id = (int) $_GET['post'];

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$screen = get_current_screen();

	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	?>
	<div class="notice notice-success is-dismissible">
		<p>Блоки страницы заполнены значениями из готовой версии сайта.</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'wipe_clean_render_front_page_seed_notice' );
