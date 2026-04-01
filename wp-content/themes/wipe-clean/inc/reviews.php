<?php
/**
 * Reviews archive rendering and helpers.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/reviews-routing.php';
require_once __DIR__ . '/data/reviews.php';

if ( ! function_exists( 'wipe_clean_get_reviews_post_type' ) ) {
	function wipe_clean_get_reviews_post_type() {
		return 'wipe_review';
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_archive_options_slug' ) ) {
	function wipe_clean_get_reviews_archive_options_slug() {
		return 'wipe-clean-reviews-archive';
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_archive_settings_url' ) ) {
	function wipe_clean_get_reviews_archive_settings_url() {
		return admin_url( 'admin.php?page=' . wipe_clean_get_reviews_archive_options_slug() );
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_archive_page_url' ) ) {
	function wipe_clean_get_reviews_archive_page_url() {
		return home_url( '/reviews/' );
	}
}

if ( ! function_exists( 'wipe_clean_is_reviews_archive_request' ) ) {
	function wipe_clean_is_reviews_archive_request() {
		return (bool) get_query_var( 'wipe_clean_reviews_archive' ) || 'reviews' === wipe_clean_current_request_path();
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_archive_raw_rows' ) ) {
	function wipe_clean_get_reviews_archive_raw_rows() {
		if ( ! function_exists( 'get_field' ) ) {
			return array();
		}

		$rows = get_field( 'reviews_archive_sections', 'option' );

		return is_array( $rows ) ? $rows : array();
	}
}

if ( ! function_exists( 'wipe_clean_normalize_review_type' ) ) {
	function wipe_clean_normalize_review_type( $value ) {
		$value = sanitize_key( (string) $value );

		if ( ! in_array( $value, array( 'text', 'video', 'photo' ), true ) ) {
			return 'text';
		}

		return $value;
	}
}

if ( ! function_exists( 'wipe_clean_resolve_reviews_media_url' ) ) {
	function wipe_clean_resolve_reviews_media_url( $media ) {
		if ( empty( $media ) ) {
			return '';
		}

		if ( is_numeric( $media ) ) {
			return (string) wp_get_attachment_url( (int) $media );
		}

		if ( is_array( $media ) ) {
			if ( ! empty( $media['ID'] ) ) {
				return (string) wp_get_attachment_url( (int) $media['ID'] );
			}

			if ( ! empty( $media['url'] ) ) {
				return wipe_clean_resolve_static_url( (string) $media['url'] );
			}

			if ( ! empty( $media['src'] ) ) {
				return wipe_clean_resolve_static_url( (string) $media['src'] );
			}

			if ( ! empty( $media['path'] ) ) {
				return wipe_clean_asset_uri( (string) $media['path'] );
			}
		}

		if ( is_string( $media ) ) {
			return wipe_clean_resolve_static_url( $media );
		}

		return '';
	}
}

if ( ! function_exists( 'wipe_clean_get_review_posts' ) ) {
	function wipe_clean_get_review_posts() {
		if ( ! post_type_exists( wipe_clean_get_reviews_post_type() ) ) {
			return array();
		}

		return get_posts(
			array(
				'post_type'      => wipe_clean_get_reviews_post_type(),
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => array(
					'menu_order' => 'ASC',
					'date'       => 'DESC',
					'title'      => 'ASC',
				),
				'order'          => 'ASC',
			)
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_review_posts_by_type' ) ) {
	function wipe_clean_get_review_posts_by_type( $review_type ) {
		$review_type = wipe_clean_normalize_review_type( $review_type );
		$matches     = array();

		foreach ( wipe_clean_get_review_posts() as $post ) {
			$post_id = (int) $post->ID;
			$type    = function_exists( 'get_field' ) ? wipe_clean_normalize_review_type( get_field( 'review_type', $post_id ) ) : 'text';

			if ( $type !== $review_type ) {
				continue;
			}

			$matches[] = $post;
		}

		return $matches;
	}
}

if ( ! function_exists( 'wipe_clean_get_review_seed_key' ) ) {
	function wipe_clean_get_review_seed_key( $post_id ) {
		return trim( (string) get_post_meta( (int) $post_id, '_wipe_clean_seed_key', true ) );
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_media_dimensions' ) ) {
	function wipe_clean_get_reviews_media_dimensions( $media ) {
		$attachment_id = 0;

		if ( is_numeric( $media ) ) {
			$attachment_id = (int) $media;
		} elseif ( is_array( $media ) && ! empty( $media['ID'] ) ) {
			$attachment_id = (int) $media['ID'];
		}

		if ( $attachment_id ) {
			$metadata = wp_get_attachment_metadata( $attachment_id );

			if ( ! empty( $metadata['width'] ) && ! empty( $metadata['height'] ) ) {
				return array(
					'width'  => (int) $metadata['width'],
					'height' => (int) $metadata['height'],
				);
			}
		}

		if ( is_array( $media ) && ! empty( $media['width'] ) && ! empty( $media['height'] ) ) {
			return array(
				'width'  => (int) $media['width'],
				'height' => (int) $media['height'],
			);
		}

		return array(
			'width'  => 0,
			'height' => 0,
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_message_review_layout_defaults' ) ) {
	function wipe_clean_get_message_review_layout_defaults( $post, $fallback = array() ) {
		$post     = get_post( $post );
		$fallback = is_array( $fallback ) ? $fallback : array();
		$post_id  = $post instanceof WP_Post ? (int) $post->ID : 0;
		$sequence = $post instanceof WP_Post ? max( 1, (int) ( $post->menu_order ?: $post_id ) ) : 1;
		$defaults = array(
			'size'          => (string) ( $fallback['size'] ?? ( 0 === $sequence % 3 ? 'short' : 'tall' ) ),
			'desktopColumn' => (int) ( $fallback['desktopColumn'] ?? ( 1 + ( ( $sequence - 1 ) % 4 ) ) ),
			'desktopOrder'  => (int) ( $fallback['desktopOrder'] ?? $sequence ),
			'mobileColumn'  => (int) ( $fallback['mobileColumn'] ?? ( 1 + ( ( $sequence - 1 ) % 2 ) ) ),
			'mobileOrder'   => (int) ( $fallback['mobileOrder'] ?? $sequence ),
		);

		if ( ! $post_id || ! function_exists( 'wipe_clean_get_reviews_default_message_items' ) ) {
			return $defaults;
		}

		$seed_key = wipe_clean_get_review_seed_key( $post_id );

		if ( '' === $seed_key ) {
			return $defaults;
		}

		foreach ( wipe_clean_get_reviews_default_message_items() as $item ) {
			if ( $seed_key !== (string) ( $item['seed_key'] ?? '' ) ) {
				continue;
			}

			return array(
				'size'          => (string) ( $item['size'] ?? $defaults['size'] ),
				'desktopColumn' => (int) ( $item['desktopColumn'] ?? $defaults['desktopColumn'] ),
				'desktopOrder'  => (int) ( $item['desktopOrder'] ?? $defaults['desktopOrder'] ),
				'mobileColumn'  => (int) ( $item['mobileColumn'] ?? $defaults['mobileColumn'] ),
				'mobileOrder'   => (int) ( $item['mobileOrder'] ?? $defaults['mobileOrder'] ),
			);
		}

		return $defaults;
	}
}

if ( ! function_exists( 'wipe_clean_build_text_review_item_from_post' ) ) {
	function wipe_clean_build_text_review_item_from_post( $post, $fallback = array() ) {
		$post = get_post( $post );

		if ( ! $post instanceof WP_Post ) {
			return (array) $fallback;
		}

		$post_id = (int) $post->ID;
		$author  = function_exists( 'get_field' ) ? trim( (string) get_field( 'author_name', $post_id ) ) : '';
		$text    = function_exists( 'get_field' ) ? trim( (string) get_field( 'review_text', $post_id ) ) : '';
		$rating  = function_exists( 'get_field' ) ? (int) get_field( 'rating', $post_id ) : 5;

		return array_merge(
			(array) $fallback,
			array(
				'author' => '' !== $author ? $author : get_the_title( $post ),
				'text'   => '' !== $text ? $text : (string) ( $fallback['text'] ?? '' ),
				'rating' => max( 1, min( 5, $rating ?: (int) ( $fallback['rating'] ?? 5 ) ) ),
			)
		);
	}
}

if ( ! function_exists( 'wipe_clean_build_video_review_item_from_post' ) ) {
	function wipe_clean_build_video_review_item_from_post( $post, $fallback = array() ) {
		$post = get_post( $post );

		if ( ! $post instanceof WP_Post ) {
			return (array) $fallback;
		}

		$post_id      = (int) $post->ID;
		$poster       = function_exists( 'get_field' ) ? get_field( 'video_poster', $post_id ) : array();
		$video_file   = function_exists( 'get_field' ) ? get_field( 'video_file', $post_id ) : array();
		$video_url    = function_exists( 'get_field' ) ? trim( (string) get_field( 'video_url', $post_id ) ) : '';
		$caption      = function_exists( 'get_field' ) ? trim( (string) get_field( 'video_caption', $post_id ) ) : '';
		$alt          = function_exists( 'get_field' ) ? trim( (string) get_field( 'video_alt', $post_id ) ) : '';
		$resolved_url = wipe_clean_resolve_reviews_media_url( $video_file );
		$dimensions   = wipe_clean_get_reviews_media_dimensions( $video_file );

		if ( '' === $resolved_url && '' !== $video_url ) {
			$resolved_url = wipe_clean_resolve_static_url( $video_url );
		}

		return array_merge(
			(array) $fallback,
			array(
				'poster'      => ! empty( $poster ) ? $poster : ( $fallback['poster'] ?? array() ),
				'videoSrc'    => '' !== $resolved_url ? $resolved_url : (string) ( $fallback['videoSrc'] ?? '' ),
				'alt'         => '' !== $alt ? $alt : (string) ( $fallback['alt'] ?? get_the_title( $post ) ),
				'caption'     => '' !== $caption ? $caption : (string) ( $fallback['caption'] ?? get_the_title( $post ) ),
				'videoWidth'  => ! empty( $dimensions['width'] ) ? (int) $dimensions['width'] : (int) ( $fallback['videoWidth'] ?? 720 ),
				'videoHeight' => ! empty( $dimensions['height'] ) ? (int) $dimensions['height'] : (int) ( $fallback['videoHeight'] ?? 1280 ),
			)
		);
	}
}

if ( ! function_exists( 'wipe_clean_build_message_review_item_from_post' ) ) {
	function wipe_clean_build_message_review_item_from_post( $post, $fallback = array() ) {
		$post = get_post( $post );

		if ( ! $post instanceof WP_Post ) {
			return (array) $fallback;
		}

		$post_id       = (int) $post->ID;
		$image         = function_exists( 'get_field' ) ? get_field( 'photo_image', $post_id ) : array();
		$lightbox      = function_exists( 'get_field' ) ? get_field( 'photo_lightbox_image', $post_id ) : array();
		$caption       = function_exists( 'get_field' ) ? trim( (string) get_field( 'photo_caption', $post_id ) ) : '';
		$alt           = function_exists( 'get_field' ) ? trim( (string) get_field( 'photo_alt', $post_id ) ) : '';
		$layout        = wipe_clean_get_message_review_layout_defaults( $post, $fallback );

		return array_merge(
			(array) $fallback,
			array(
				'id'            => 'message-review-' . $post_id,
				'size'          => (string) ( $layout['size'] ?? 'tall' ),
				'desktopColumn' => (int) ( $layout['desktopColumn'] ?? 1 ),
				'desktopOrder'  => (int) ( $layout['desktopOrder'] ?? ( $post->menu_order ?: $post_id ) ),
				'mobileColumn'  => (int) ( $layout['mobileColumn'] ?? 1 ),
				'mobileOrder'   => (int) ( $layout['mobileOrder'] ?? ( $post->menu_order ?: $post_id ) ),
				'image'         => ! empty( $image ) ? $image : ( $fallback['image'] ?? array() ),
				'lightboxImage' => ! empty( $lightbox ) ? $lightbox : ( ! empty( $image ) ? $image : ( $fallback['lightboxImage'] ?? array() ) ),
				'alt'           => '' !== $alt ? $alt : (string) ( $fallback['alt'] ?? get_the_title( $post ) ),
				'caption'       => '' !== $caption ? $caption : (string) ( $fallback['caption'] ?? get_the_title( $post ) ),
			)
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_text_items' ) ) {
	function wipe_clean_get_reviews_text_items() {
		$fallback_items = wipe_clean_get_reviews_default_text_items();
		$posts          = wipe_clean_get_review_posts_by_type( 'text' );

		if ( empty( $posts ) ) {
			return $fallback_items;
		}

		$items = array();

		foreach ( array_values( $posts ) as $index => $post ) {
			$items[] = wipe_clean_build_text_review_item_from_post( $post, $fallback_items[ $index ] ?? array() );
		}

		return $items;
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_video_items' ) ) {
	function wipe_clean_get_reviews_video_items() {
		$fallback_items = wipe_clean_get_reviews_default_video_items();
		$posts          = wipe_clean_get_review_posts_by_type( 'video' );

		if ( empty( $posts ) ) {
			return $fallback_items;
		}

		$items = array();

		foreach ( array_values( $posts ) as $index => $post ) {
			$items[] = wipe_clean_build_video_review_item_from_post( $post, $fallback_items[ $index ] ?? array() );
		}

		return $items;
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_message_items' ) ) {
	function wipe_clean_get_reviews_message_items() {
		$fallback_items = wipe_clean_get_reviews_default_message_items();
		$posts          = wipe_clean_get_review_posts_by_type( 'photo' );

		if ( empty( $posts ) ) {
			return $fallback_items;
		}

		$items = array();

		foreach ( array_values( $posts ) as $index => $post ) {
			$items[] = wipe_clean_build_message_review_item_from_post( $post, $fallback_items[ $index ] ?? array() );
		}

		return $items;
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_archive_sections' ) ) {
	function wipe_clean_get_reviews_archive_sections() {
		$sections = wipe_clean_normalize_blog_sections(
			wipe_clean_get_reviews_archive_raw_rows(),
			wipe_clean_get_reviews_archive_layout_order(),
			'wipe_clean_get_reviews_archive_section_defaults'
		);

		foreach ( $sections as &$section ) {
			$layout = isset( $section['acf_fc_layout'] ) ? (string) $section['acf_fc_layout'] : '';

			switch ( $layout ) {
				case 'reviews_archive':
					$section['items']      = wipe_clean_get_reviews_text_items();
					$section['top_action'] = wipe_clean_force_link_url( $section['top_action'] ?? array(), '#popup-review' );
					break;

				case 'video_reviews':
					$section['items']      = wipe_clean_get_reviews_video_items();
					$section['top_action'] = wipe_clean_force_link_url( $section['top_action'] ?? array(), '#popup-review' );
					break;

				case 'message_reviews':
					$section['items'] = wipe_clean_get_reviews_message_items();
					break;
			}
		}
		unset( $section );

		return $sections;
	}
}

if ( ! function_exists( 'wipe_clean_render_reviews_archive_sections' ) ) {
	function wipe_clean_render_reviews_archive_sections() {
		foreach ( wipe_clean_get_reviews_archive_sections() as $section ) {
			$layout = isset( $section['acf_fc_layout'] ) ? (string) $section['acf_fc_layout'] : '';

			switch ( $layout ) {
				case 'reviews_archive':
					get_template_part( 'template-parts/section/reviews/reviews-archive', null, array( 'section' => $section ) );
					break;

				case 'video_reviews':
					get_template_part( 'template-parts/section/reviews/video-reviews', null, array( 'section' => $section ) );
					break;

				case 'message_reviews':
					get_template_part( 'template-parts/section/reviews/message-reviews', null, array( 'section' => $section ) );
					break;

				case 'before_after_results':
					get_template_part( 'template-parts/section/service-single/before-after-results', null, array( 'section' => $section ) );
					break;

				case 'faq':
					get_template_part( 'template-parts/section/front-page/faq', null, array( 'section' => $section ) );
					break;

				case 'gallery_preview':
					get_template_part( 'template-parts/section/front-page/gallery-preview', null, array( 'section' => $section ) );
					break;

				case 'contacts':
					get_template_part( 'template-parts/section/front-page/contacts', null, array( 'section' => $section ) );
					break;
			}
		}
	}
}

if ( ! function_exists( 'wipe_clean_render_reviews_archive_content' ) ) {
	function wipe_clean_render_reviews_archive_content() {
		echo '<div class="reviews-page">';
		wipe_clean_render_reviews_archive_sections();
		echo '</div>';
	}
}

if ( ! function_exists( 'wipe_clean_filter_review_row_actions' ) ) {
	function wipe_clean_filter_review_row_actions( $actions, $post ) {
		if ( ! $post instanceof WP_Post || wipe_clean_get_reviews_post_type() !== $post->post_type ) {
			return $actions;
		}

		unset( $actions['view'], $actions['inline hide-if-no-js'] );

		return $actions;
	}
}
add_filter( 'post_row_actions', 'wipe_clean_filter_review_row_actions', 10, 2 );

if ( ! function_exists( 'wipe_clean_add_review_type_admin_column' ) ) {
	function wipe_clean_add_review_type_admin_column( $columns ) {
		$columns_with_type = array();

		foreach ( $columns as $key => $label ) {
			$columns_with_type[ $key ] = $label;

			if ( 'title' === $key ) {
				$columns_with_type['review_type'] = 'Тип';
			}
		}

		return $columns_with_type;
	}
}
add_filter( 'manage_wipe_review_posts_columns', 'wipe_clean_add_review_type_admin_column' );

if ( ! function_exists( 'wipe_clean_render_review_type_admin_column' ) ) {
	function wipe_clean_render_review_type_admin_column( $column, $post_id ) {
		if ( 'review_type' !== $column ) {
			return;
		}

		$type_map = array(
			'text'  => 'Текстовый',
			'video' => 'Видео',
			'photo' => 'Фото / переписка',
		);
		$type     = function_exists( 'get_field' ) ? wipe_clean_normalize_review_type( get_field( 'review_type', $post_id ) ) : 'text';

		echo esc_html( $type_map[ $type ] ?? 'Текстовый' );
	}
}
add_action( 'manage_wipe_review_posts_custom_column', 'wipe_clean_render_review_type_admin_column', 10, 2 );

if ( ! function_exists( 'wipe_clean_is_pending_review_submission' ) ) {
	function wipe_clean_is_pending_review_submission( $post_id ) {
		return (bool) get_post_meta( (int) $post_id, '_wipe_clean_review_submission_pending', true );
	}
}

if ( ! function_exists( 'wipe_clean_add_review_submission_metabox' ) ) {
	function wipe_clean_add_review_submission_metabox() {
		add_meta_box(
			'wipe-clean-review-submission',
			'Заявка с сайта',
			'wipe_clean_render_review_submission_metabox',
			wipe_clean_get_reviews_post_type(),
			'side',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'wipe_clean_add_review_submission_metabox' );

if ( ! function_exists( 'wipe_clean_render_review_submission_metabox' ) ) {
	function wipe_clean_render_review_submission_metabox( $post ) {
		$post = get_post( $post );

		if ( ! $post instanceof WP_Post ) {
			return;
		}

		$post_id        = (int) $post->ID;
		$is_submission  = wipe_clean_is_pending_review_submission( $post_id );
		$source_label   = trim( (string) get_post_meta( $post_id, '_wipe_clean_review_submission_source_label', true ) );
		$page_label     = trim( (string) get_post_meta( $post_id, '_wipe_clean_review_submission_page_label', true ) );
		$surface_label  = trim( (string) get_post_meta( $post_id, '_wipe_clean_review_submission_surface_label', true ) );
		$phone          = trim( (string) get_post_meta( $post_id, '_wipe_clean_review_submission_phone', true ) );
		$email          = trim( (string) get_post_meta( $post_id, '_wipe_clean_review_submission_email', true ) );
		$url            = trim( (string) get_post_meta( $post_id, '_wipe_clean_review_submission_url', true ) );
		$attachment_ids = array_map(
			'intval',
			(array) get_post_meta( $post_id, '_wipe_clean_review_submission_attachment_ids', true )
		);

		if ( ! $is_submission ) {
			echo '<p>Этот отзыв создан или заполнен вручную.</p>';
			return;
		}
		?>
		<div class="wipe-clean-review-submission">
			<p><strong>Статус:</strong> ждёт проверки перед публикацией.</p>
			<?php if ( '' !== $source_label ) : ?>
				<p><strong>Форма:</strong> <?php echo esc_html( $source_label ); ?></p>
			<?php endif; ?>
			<?php if ( '' !== $page_label ) : ?>
				<p><strong>Страница:</strong> <?php echo esc_html( $page_label ); ?></p>
			<?php endif; ?>
			<?php if ( '' !== $surface_label ) : ?>
				<p><strong>Раздел:</strong> <?php echo esc_html( $surface_label ); ?></p>
			<?php endif; ?>
			<?php if ( '' !== $phone ) : ?>
				<p><strong>Телефон:</strong> <a href="tel:<?php echo esc_attr( preg_replace( '/\D+/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p>
			<?php endif; ?>
			<?php if ( '' !== $email ) : ?>
				<p><strong>Email:</strong> <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
			<?php endif; ?>
			<?php if ( '' !== $url ) : ?>
				<p><strong>Адрес:</strong> <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noreferrer noopener">Открыть страницу</a></p>
			<?php endif; ?>
			<?php if ( ! empty( $attachment_ids ) ) : ?>
				<div class="wipe-clean-review-submission__files">
					<p><strong>Файлы:</strong></p>
					<ul>
						<?php foreach ( $attachment_ids as $attachment_id ) : ?>
							<?php
							$file_url  = wp_get_attachment_url( $attachment_id );
							$file_name = wp_basename( (string) get_attached_file( $attachment_id ) );
							?>
							<?php if ( $file_url ) : ?>
								<li><a href="<?php echo esc_url( $file_url ); ?>" target="_blank" rel="noreferrer noopener"><?php echo esc_html( $file_name ?: 'Файл' ); ?></a></li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
			<p>Перед публикацией проверьте текст, рейтинг и при необходимости смените тип отзыва.</p>
		</div>
		<?php
	}
}

if ( ! function_exists( 'wipe_clean_add_review_submission_post_state' ) ) {
	function wipe_clean_add_review_submission_post_state( $states, $post ) {
		if ( ! $post instanceof WP_Post || wipe_clean_get_reviews_post_type() !== $post->post_type ) {
			return $states;
		}

		if ( wipe_clean_is_pending_review_submission( $post->ID ) && 'publish' !== $post->post_status ) {
			$states['wipe_clean_review_submission'] = 'Новый отзыв с сайта';
		}

		return $states;
	}
}
add_filter( 'display_post_states', 'wipe_clean_add_review_submission_post_state', 10, 2 );

if ( ! function_exists( 'wipe_clean_get_pending_review_submissions_count' ) ) {
	function wipe_clean_get_pending_review_submissions_count() {
		if ( ! post_type_exists( wipe_clean_get_reviews_post_type() ) ) {
			return 0;
		}

		$pending_ids = get_posts(
			array(
				'post_type'      => wipe_clean_get_reviews_post_type(),
				'post_status'    => array( 'draft', 'pending', 'private' ),
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_key'       => '_wipe_clean_review_submission_pending',
				'meta_value'     => 1,
				'no_found_rows'  => true,
			)
		);

		return count( (array) $pending_ids );
	}
}

if ( ! function_exists( 'wipe_clean_get_pending_review_submissions_url' ) ) {
	function wipe_clean_get_pending_review_submissions_url() {
		return add_query_arg(
			array(
				'post_type'                    => wipe_clean_get_reviews_post_type(),
				'wipe_clean_review_submission' => 'pending',
			),
			admin_url( 'edit.php' )
		);
	}
}

if ( ! function_exists( 'wipe_clean_filter_pending_review_submissions_admin_query' ) ) {
	function wipe_clean_filter_pending_review_submissions_admin_query( $query ) {
		if ( ! is_admin() || ! $query instanceof WP_Query || ! $query->is_main_query() ) {
			return;
		}

		if ( wipe_clean_get_reviews_post_type() !== sanitize_key( (string) $query->get( 'post_type' ) ) ) {
			return;
		}

		if ( 'pending' !== sanitize_key( (string) ( $_GET['wipe_clean_review_submission'] ?? '' ) ) ) {
			return;
		}

		$meta_query   = (array) $query->get( 'meta_query' );
		$meta_query[] = array(
			'key'   => '_wipe_clean_review_submission_pending',
			'value' => 1,
		);

		$query->set( 'meta_query', $meta_query );

		if ( ! $query->get( 'post_status' ) ) {
			$query->set( 'post_status', array( 'draft', 'pending', 'private' ) );
		}
	}
}
add_action( 'pre_get_posts', 'wipe_clean_filter_pending_review_submissions_admin_query' );

if ( ! function_exists( 'wipe_clean_add_pending_review_submissions_view' ) ) {
	function wipe_clean_add_pending_review_submissions_view( $views ) {
		global $typenow;

		if ( wipe_clean_get_reviews_post_type() !== $typenow ) {
			return $views;
		}

		$count    = wipe_clean_get_pending_review_submissions_count();
		$url      = wipe_clean_get_pending_review_submissions_url();
		$is_active = 'pending' === sanitize_key( (string) ( $_GET['wipe_clean_review_submission'] ?? '' ) );

		$views['wipe_clean_review_submission'] = sprintf(
			'<a href="%1$s"%2$s>Новые отзывы с сайта <span class="count">(%3$d)</span></a>',
			esc_url( $url ),
			$is_active ? ' class="current" aria-current="page"' : '',
			(int) $count
		);

		return $views;
	}
}
add_filter( 'views_edit-' . wipe_clean_get_reviews_post_type(), 'wipe_clean_add_pending_review_submissions_view' );

if ( ! function_exists( 'wipe_clean_finalize_review_submission_after_publish' ) ) {
	function wipe_clean_finalize_review_submission_after_publish( $new_status, $old_status, $post ) {
		if ( ! $post instanceof WP_Post || wipe_clean_get_reviews_post_type() !== $post->post_type ) {
			return;
		}

		if ( 'publish' !== $new_status || 'publish' === $old_status ) {
			return;
		}

		delete_post_meta( $post->ID, '_wipe_clean_review_submission_pending' );
	}
}
add_action( 'transition_post_status', 'wipe_clean_finalize_review_submission_after_publish', 10, 3 );
