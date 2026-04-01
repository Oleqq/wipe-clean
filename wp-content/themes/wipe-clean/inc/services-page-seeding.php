<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_services_page_has_seeded_rows( $post_id ) {
	if ( ! function_exists( 'get_field' ) ) {
		return false;
	}

	$rows = get_field( 'services_page_sections', $post_id );

	return is_array( $rows ) && ! empty( $rows );
}

function wipe_clean_services_page_import_attachment( $media ) {
	$source = '';

	if ( is_numeric( $media ) ) {
		return (int) $media;
	}

	if ( is_array( $media ) ) {
		if ( ! empty( $media['ID'] ) ) {
			return (int) $media['ID'];
		}

		$source = (string) ( $media['src'] ?? $media['url'] ?? $media['path'] ?? '' );
	} else {
		$source = (string) $media;
	}

	$source = trim( $source );

	if ( '' === $source ) {
		return 0;
	}

	if ( 0 === strpos( $source, 'static/' ) ) {
		$source = '/' . ltrim( $source, '/' );
	}

	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_wipe_clean_theme_asset_source',
			'meta_value'     => $source,
		)
	);

	if ( ! empty( $existing ) ) {
		return (int) $existing[0];
	}

	$parsed_path = (string) wp_parse_url( $source, PHP_URL_PATH );
	$file_path   = '';

	if ( $parsed_path ) {
		$file_path = wp_normalize_path( untrailingslashit( ABSPATH ) . '/' . ltrim( $parsed_path, '/' ) );
	}

	if ( ! $file_path || ! file_exists( $file_path ) ) {
		return 0;
	}

	$contents = file_get_contents( $file_path );

	if ( false === $contents ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$uploads  = wp_get_upload_dir();
	$filename = wp_unique_filename( $uploads['path'], basename( $file_path ) );
	$upload   = wp_upload_bits( $filename, null, $contents );

	if ( ! empty( $upload['error'] ) || empty( $upload['file'] ) ) {
		return 0;
	}

	$filetype      = wp_check_filetype( $upload['file'] );
	$attachment_id = wp_insert_attachment(
		array(
			'post_mime_type' => $filetype['type'] ?? '',
			'post_title'     => sanitize_file_name( pathinfo( $upload['file'], PATHINFO_FILENAME ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		),
		$upload['file']
	);

	if ( is_wp_error( $attachment_id ) || ! $attachment_id ) {
		return 0;
	}

	$metadata = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );

	if ( $metadata ) {
		wp_update_attachment_metadata( $attachment_id, $metadata );
	}

	update_post_meta( $attachment_id, '_wipe_clean_theme_asset_source', $source );

	return (int) $attachment_id;
}

function wipe_clean_get_services_page_seed_rows() {
	$rows = array();

	foreach ( wipe_clean_get_services_page_layout_order() as $layout ) {
		$defaults = wipe_clean_get_services_page_section_defaults( $layout );

		switch ( $layout ) {
			case 'services_intro':
				$row = array(
					'acf_fc_layout'           => 'services_intro',
					'hero_kicker'             => (string) ( $defaults['hero_kicker'] ?? '' ),
					'hero_title'              => (string) ( $defaults['hero_title'] ?? '' ),
					'hero_text'               => (string) ( $defaults['hero_text'] ?? '' ),
					'hero_primary_action'     => $defaults['hero_primary_action'] ?? array(),
					'hero_secondary_action'   => $defaults['hero_secondary_action'] ?? array(),
					'hero_decor_image'        => wipe_clean_services_page_import_attachment( $defaults['hero_decor_image'] ?? array() ),
					'hero_cleaner_image'      => wipe_clean_services_page_import_attachment( $defaults['hero_cleaner_image'] ?? array() ),
					'hero_interior_image'     => wipe_clean_services_page_import_attachment( $defaults['hero_interior_image'] ?? array() ),
					'overview_title'          => (string) ( $defaults['overview_title'] ?? '' ),
					'overview_summary'        => (string) ( $defaults['overview_summary'] ?? '' ),
					'overview_body'           => array(),
					'overview_more_label'     => (string) ( $defaults['overview_more_label'] ?? 'Ещё' ),
					'overview_less_label'     => (string) ( $defaults['overview_less_label'] ?? 'Свернуть' ),
					'overview_image'          => wipe_clean_services_page_import_attachment( $defaults['overview_image'] ?? array() ),
					'footer_primary_action'   => $defaults['footer_primary_action'] ?? array(),
					'footer_secondary_action' => $defaults['footer_secondary_action'] ?? array(),
				);

				foreach ( (array) ( $defaults['overview_body'] ?? array() ) as $paragraph ) {
					$row['overview_body'][] = array(
						'text' => (string) ( $paragraph['text'] ?? '' ),
					);
				}

				$rows[] = $row;
				break;

			case 'services_benefits':
				$row = array(
					'acf_fc_layout'   => 'services_benefits',
					'title'           => (string) ( $defaults['title'] ?? '' ),
					'text'            => (string) ( $defaults['text'] ?? '' ),
					'cards'           => array(),
					'offer_title'     => (string) ( $defaults['offer_title'] ?? '' ),
					'offer_text'      => (string) ( $defaults['offer_text'] ?? '' ),
					'offer_button'    => $defaults['offer_button'] ?? array(),
					'checklist_title' => (string) ( $defaults['checklist_title'] ?? '' ),
					'checklist_text'  => (string) ( $defaults['checklist_text'] ?? '' ),
					'checklist_items' => array(),
					'visual_image'    => wipe_clean_services_page_import_attachment( $defaults['visual_image'] ?? array() ),
				);

				foreach ( (array) ( $defaults['cards'] ?? array() ) as $card ) {
					$row['cards'][] = array(
						'title' => (string) ( $card['title'] ?? '' ),
						'text'  => (string) ( $card['text'] ?? '' ),
						'icon'  => wipe_clean_services_page_import_attachment( $card['icon'] ?? array() ),
					);
				}

				foreach ( (array) ( $defaults['checklist_items'] ?? array() ) as $item ) {
					$row['checklist_items'][] = array(
						'text' => (string) ( $item['text'] ?? '' ),
						'icon' => wipe_clean_services_page_import_attachment( $item['icon'] ?? array() ),
					);
				}

				$rows[] = $row;
				break;

			case 'faq':
				$row = array(
					'acf_fc_layout' => 'faq',
					'title'         => (string) ( $defaults['title'] ?? '' ),
					'items'         => array(),
				);

				foreach ( (array) ( $defaults['items'] ?? array() ) as $item ) {
					$row['items'][] = array(
						'question' => (string) ( $item['question'] ?? '' ),
						'answer'   => (string) ( $item['answer'] ?? '' ),
					);
				}

				$rows[] = $row;
				break;

			case 'contacts':
				$rows[] = array(
					'acf_fc_layout'        => 'contacts',
					'title'                => (string) ( $defaults['title'] ?? '' ),
					'text'                 => (string) ( $defaults['text'] ?? '' ),
					'phone_label'          => (string) ( $defaults['phone_label'] ?? '' ),
					'phone_value'          => (string) ( $defaults['phone_value'] ?? '' ),
					'socials_label'        => (string) ( $defaults['socials_label'] ?? '' ),
					'social_links'         => array(),
					'email_label'          => (string) ( $defaults['email_label'] ?? '' ),
					'email_value'          => (string) ( $defaults['email_value'] ?? '' ),
					'form_title'           => (string) ( $defaults['form_title'] ?? '' ),
					'form_name_label'      => (string) ( $defaults['form_name_label'] ?? '' ),
					'form_name_placeholder'=> (string) ( $defaults['form_name_placeholder'] ?? '' ),
					'form_phone_label'     => (string) ( $defaults['form_phone_label'] ?? '' ),
					'form_phone_placeholder'=> (string) ( $defaults['form_phone_placeholder'] ?? '' ),
					'agreement_text'       => (string) ( $defaults['agreement_text'] ?? '' ),
					'submit_text'          => (string) ( $defaults['submit_text'] ?? '' ),
					'submit_text_mobile'   => (string) ( $defaults['submit_text_mobile'] ?? '' ),
				);

				foreach ( (array) ( $defaults['social_links'] ?? array() ) as $social_link ) {
					$rows[ array_key_last( $rows ) ]['social_links'][] = array(
						'label' => (string) ( $social_link['label'] ?? '' ),
						'url'   => (string) ( $social_link['url'] ?? '' ),
						'icon'  => wipe_clean_services_page_import_attachment( $social_link['icon'] ?? array() ),
					);
				}
				break;
		}
	}

	return $rows;
}

function wipe_clean_seed_services_page_service_posts() {
	if ( ! post_type_exists( 'wipe_service' ) || ! function_exists( 'update_field' ) ) {
		return;
	}

	foreach ( wipe_clean_get_services_page_default_service_items() as $index => $item ) {
		$seed_key = (string) ( $item['seed_key'] ?? 'service-' . ( $index + 1 ) );
		$title    = (string) ( $item['title'] ?? '' );

		if ( '' === $title ) {
			continue;
		}

		$post_id  = 0;
		$existing = get_posts(
			array(
				'post_type'      => 'wipe_service',
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
			$by_title = get_page_by_title( $title, OBJECT, 'wipe_service' );

			if ( $by_title instanceof WP_Post ) {
				$post_id = (int) $by_title->ID;
			}
		}

		$post_args = array(
			'post_type'    => 'wipe_service',
			'post_title'   => $title,
			'post_status'  => 'publish',
			'post_excerpt' => (string) ( $item['text'] ?? '' ),
			'menu_order'   => (int) ( $item['order'] ?? ( $index + 1 ) ),
		);

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
		update_post_meta( $post_id, '_wipe_clean_services_page_class', (string) ( $item['class_name'] ?? '' ) );

		$image_id = wipe_clean_services_page_import_attachment( $item['image'] ?? array() );

		if ( $image_id ) {
			set_post_thumbnail( $post_id, $image_id );
		}
	}
}

function wipe_clean_seed_services_page_sections( $post_id ) {
	if ( ! $post_id || ! function_exists( 'update_field' ) ) {
		return;
	}

	update_field( 'services_page_sections', wipe_clean_get_services_page_seed_rows(), $post_id );
	wipe_clean_seed_services_page_service_posts();
}

function wipe_clean_maybe_seed_services_page_on_save( $post_id ) {
	static $is_running = false;

	if ( $is_running || wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}

	if ( ! wipe_clean_is_services_page_post( $post_id ) || wipe_clean_services_page_has_seeded_rows( $post_id ) ) {
		return;
	}

	$is_running = true;
	wipe_clean_seed_services_page_sections( $post_id );
	$is_running = false;
}
add_action( 'save_post_page', 'wipe_clean_maybe_seed_services_page_on_save' );

function wipe_clean_render_services_page_seed_box( $post ) {
	if ( ! ( $post instanceof WP_Post ) || ! wipe_clean_is_services_page_post( $post->ID ) ) {
		return;
	}

	$has_rows = wipe_clean_services_page_has_seeded_rows( $post->ID );
	$url = wp_nonce_url(
		add_query_arg(
			array(
				'action'  => 'wipe_clean_seed_services_page',
				'post_id' => $post->ID,
			),
			admin_url( 'admin-post.php' )
		),
		'wipe_clean_seed_services_page_' . $post->ID
	);
	?>
	<div style="margin:16px 0 18px;padding:16px 18px;border:1px solid #d7e8ee;border-radius:16px;background:linear-gradient(180deg,rgba(255,255,255,0.98) 0%,rgba(250,252,253,0.96) 100%);box-shadow:0 10px 24px rgba(21,15,49,0.06);">
		<div style="display:flex;flex-wrap:wrap;gap:14px;align-items:center;justify-content:space-between;">
			<div style="max-width:760px;">
				<div style="margin:0 0 6px;font-size:16px;font-weight:700;color:#150F31;">Заполнить готовым содержимым</div>
				<div style="font-size:13px;line-height:1.55;color:#5D5779;">
					Подставляет готовые тексты, изображения и блоки страницы услуг. Карточки в архиве собираются из самих услуг и тоже будут созданы или обновлены.
					<?php if ( $has_rows ) : ?>
						<strong style="color:#150F31;">Текущие значения секций будут заменены.</strong>
					<?php endif; ?>
				</div>
			</div>
			<div>
				<a class="button button-primary button-large" href="<?php echo esc_url( $url ); ?>" onclick="return window.confirm('Заполнить страницу услуг готовым содержимым? Текущие значения секций будут заменены.');">
					<?php echo esc_html( $has_rows ? 'Обновить содержимое' : 'Заполнить готовым' ); ?>
				</a>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'edit_form_after_title', 'wipe_clean_render_services_page_seed_box' );

function wipe_clean_handle_services_page_seed_action() {
	if ( ! current_user_can( 'edit_pages' ) ) {
		wp_die( 'Недостаточно прав.' );
	}

	$post_id = isset( $_GET['post_id'] ) ? (int) $_GET['post_id'] : 0;

	if ( ! $post_id || ! wipe_clean_is_services_page_post( $post_id ) ) {
		wp_safe_redirect( admin_url( 'edit.php?post_type=page' ) );
		exit;
	}

	check_admin_referer( 'wipe_clean_seed_services_page_' . $post_id );

	wipe_clean_seed_services_page_sections( $post_id );

	wp_safe_redirect(
		add_query_arg(
			array(
				'post'                       => $post_id,
				'action'                     => 'edit',
				'wipe_clean_services_seeded' => 1,
			),
			admin_url( 'post.php' )
		)
	);
	exit;
}
add_action( 'admin_post_wipe_clean_seed_services_page', 'wipe_clean_handle_services_page_seed_action' );

function wipe_clean_render_services_page_seed_notice() {
	if ( empty( $_GET['wipe_clean_services_seeded'] ) ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}
	?>
	<div class="notice notice-success is-dismissible">
		<p>Архив услуг заполнен по готовой версии. Карточки услуг тоже созданы или обновлены.</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'wipe_clean_render_services_page_seed_notice' );

if ( ! function_exists( 'wipe_clean_services_archive_option_has_seeded_rows' ) ) {
	function wipe_clean_services_archive_option_has_seeded_rows() {
		if ( ! function_exists( 'get_field' ) ) {
			return false;
		}

		$rows = get_field( 'services_page_sections', 'option', false );

		return is_array( $rows ) && ! empty( $rows );
	}
}

if ( ! function_exists( 'wipe_clean_seed_services_archive_option_sections' ) ) {
	function wipe_clean_seed_services_archive_option_sections() {
		if ( ! function_exists( 'update_field' ) ) {
			return false;
		}

		update_field( 'services_page_sections', wipe_clean_get_services_page_seed_rows(), 'option' );
		wipe_clean_seed_services_page_service_posts();
		update_option( 'wipe_clean_services_archive_seeded_at', time() );

		return true;
	}
}

if ( ! function_exists( 'wipe_clean_get_services_archive_seed_action_url' ) ) {
	function wipe_clean_get_services_archive_seed_action_url() {
		return wp_nonce_url(
			admin_url( 'admin-post.php?action=wipe_clean_seed_services_archive_option' ),
			'wipe_clean_seed_services_archive_option'
		);
	}
}

if ( ! function_exists( 'wipe_clean_is_services_archive_options_admin_screen' ) ) {
	function wipe_clean_is_services_archive_options_admin_screen() {
		if ( ! is_admin() || ! function_exists( 'wipe_clean_get_services_archive_options_slug' ) ) {
			return false;
		}

		return isset( $_GET['page'] ) && wipe_clean_get_services_archive_options_slug() === (string) $_GET['page'];
	}
}

if ( ! function_exists( 'wipe_clean_render_services_archive_option_seed_box' ) ) {
	function wipe_clean_render_services_archive_option_seed_box() {
		if ( ! wipe_clean_is_services_archive_options_admin_screen() || ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		$has_rows   = wipe_clean_services_archive_option_has_seeded_rows();
		$action_url = wipe_clean_get_services_archive_seed_action_url();
		?>
		<div class="notice notice-info" style="margin-top:16px;padding:0;border:none;background:transparent;box-shadow:none;">
			<div style="padding:16px 18px;border:1px solid #d7e8ee;border-radius:16px;background:linear-gradient(180deg,rgba(255,255,255,0.98) 0%,rgba(250,252,253,0.96) 100%);box-shadow:0 10px 24px rgba(21,15,49,0.06);">
				<div style="display:flex;flex-wrap:wrap;gap:14px;align-items:center;justify-content:space-between;">
					<div style="max-width:760px;">
						<div style="margin:0 0 6px;font-size:16px;font-weight:700;color:#150F31;">Заполнить готовым содержимым</div>
						<div style="font-size:13px;line-height:1.55;color:#5D5779;">
							Подставляет готовые блоки страницы услуг и одновременно создаёт или обновляет услуги для карточек в архиве.
							<?php if ( $has_rows ) : ?>
								<strong style="color:#150F31;">Текущие значения секций будут заменены.</strong>
							<?php endif; ?>
						</div>
					</div>
					<div>
						<a class="button button-primary button-large" href="<?php echo esc_url( $action_url ); ?>" onclick="return window.confirm('Заполнить архив услуг готовым содержимым? Текущие значения секций будут заменены.');">
							<?php echo esc_html( $has_rows ? 'Обновить содержимое' : 'Заполнить готовым' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'wipe_clean_render_services_archive_option_seed_box', 5 );

if ( ! function_exists( 'wipe_clean_handle_services_archive_option_seed_action' ) ) {
	function wipe_clean_handle_services_archive_option_seed_action() {
		if ( ! current_user_can( 'edit_pages' ) ) {
			wp_die( esc_html__( 'Недостаточно прав для этого действия.', 'wipe-clean' ) );
		}

		check_admin_referer( 'wipe_clean_seed_services_archive_option' );
		wipe_clean_seed_services_archive_option_sections();

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'                                    => function_exists( 'wipe_clean_get_services_archive_options_slug' ) ? wipe_clean_get_services_archive_options_slug() : 'wipe-clean-services-archive',
					'wipe_clean_services_archive_seeded'      => 1,
				),
				admin_url( 'admin.php' )
			)
		);
		exit;
	}
}
add_action( 'admin_post_wipe_clean_seed_services_archive_option', 'wipe_clean_handle_services_archive_option_seed_action' );

if ( ! function_exists( 'wipe_clean_render_services_archive_option_seed_notice' ) ) {
	function wipe_clean_render_services_archive_option_seed_notice() {
		if ( empty( $_GET['wipe_clean_services_archive_seeded'] ) || ! wipe_clean_is_services_archive_options_admin_screen() ) {
			return;
		}
		?>
		<div class="notice notice-success is-dismissible">
			<p>Архив услуг заполнен готовым содержимым. Услуги для карточек тоже обновлены.</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'wipe_clean_render_services_archive_option_seed_notice', 20 );
