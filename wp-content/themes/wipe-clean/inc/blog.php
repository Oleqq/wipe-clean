<?php
/**
 * Blog archive and single-post rendering.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/blog-routing.php';
require_once __DIR__ . '/data/blog.php';

if ( ! function_exists( 'wipe_clean_get_blog_post_type' ) ) {
	function wipe_clean_get_blog_post_type() {
		return 'wipe_blog';
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_archive_options_slug' ) ) {
	function wipe_clean_get_blog_archive_options_slug() {
		return 'wipe-clean-blog-archive';
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_archive_settings_url' ) ) {
	function wipe_clean_get_blog_archive_settings_url() {
		return admin_url( 'admin.php?page=' . wipe_clean_get_blog_archive_options_slug() );
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_archive_page_url' ) ) {
	function wipe_clean_get_blog_archive_page_url() {
		$archive_link = get_post_type_archive_link( wipe_clean_get_blog_post_type() );

		if ( $archive_link ) {
			return $archive_link;
		}

		$mapped_url = function_exists( 'wipe_clean_resolve_static_url' )
			? wipe_clean_resolve_static_url( 'blog.html' )
			: '';

		return $mapped_url ? $mapped_url : home_url( '/blog/' );
	}
}

if ( ! function_exists( 'wipe_clean_find_blog_post_by_slug' ) ) {
	function wipe_clean_find_blog_post_by_slug( $slug ) {
		$slug = sanitize_title( (string) $slug );

		if ( '' === $slug ) {
			return null;
		}

		$posts = get_posts(
			array(
				'post_type'      => wipe_clean_get_blog_post_type(),
				'post_status'    => array( 'publish', 'private', 'draft', 'pending' ),
				'name'           => $slug,
				'posts_per_page' => 1,
			)
		);

		if ( empty( $posts ) ) {
			return null;
		}

		return $posts[0] instanceof WP_Post ? $posts[0] : null;
	}
}

if ( ! function_exists( 'wipe_clean_get_current_blog_post' ) ) {
	function wipe_clean_get_current_blog_post() {
		$queried = get_queried_object();

		if ( $queried instanceof WP_Post && wipe_clean_get_blog_post_type() === $queried->post_type ) {
			return $queried;
		}

		$post_id = (int) get_queried_object_id();

		if ( $post_id ) {
			$post = get_post( $post_id );

			if ( $post instanceof WP_Post && wipe_clean_get_blog_post_type() === $post->post_type ) {
				return $post;
			}
		}

		$slug = (string) get_query_var( 'wipe_clean_blog_slug' );

		if ( '' === $slug ) {
			$path = wipe_clean_current_request_path();

			if ( preg_match( '~^blog/([^/]+)/?$~', $path, $matches ) ) {
				$slug = (string) $matches[1];
			}
		}

		return wipe_clean_find_blog_post_by_slug( $slug );
	}
}

if ( ! function_exists( 'wipe_clean_get_current_blog_post_id' ) ) {
	function wipe_clean_get_current_blog_post_id() {
		$post = wipe_clean_get_current_blog_post();

		return $post instanceof WP_Post ? (int) $post->ID : 0;
	}
}

if ( ! function_exists( 'wipe_clean_is_blog_archive_request' ) ) {
	function wipe_clean_is_blog_archive_request() {
		return is_post_type_archive( wipe_clean_get_blog_post_type() ) || (bool) get_query_var( 'wipe_clean_blog_archive' ) || 'blog' === wipe_clean_current_request_path();
	}
}

if ( ! function_exists( 'wipe_clean_is_blog_single_request' ) ) {
	function wipe_clean_is_blog_single_request() {
		$path = wipe_clean_current_request_path();

		return is_singular( wipe_clean_get_blog_post_type() ) || (bool) get_query_var( 'wipe_clean_blog_slug' ) || (bool) preg_match( '~^blog/[^/]+/?$~', $path );
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_archive_raw_rows' ) ) {
	function wipe_clean_get_blog_archive_raw_rows() {
		if ( ! function_exists( 'get_field' ) ) {
			return array();
		}

		$rows = get_field( 'blog_archive_sections', 'option' );

		return is_array( $rows ) ? $rows : array();
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_post_raw_rows' ) ) {
	function wipe_clean_get_blog_post_raw_rows( $post_id = 0 ) {
		$post_id = $post_id ? (int) $post_id : wipe_clean_get_current_blog_post_id();

		if ( ! $post_id || ! function_exists( 'get_field' ) ) {
			return array();
		}

		$rows = get_field( 'blog_post_sections', $post_id );

		return is_array( $rows ) ? $rows : array();
	}
}

if ( ! function_exists( 'wipe_clean_index_blog_sections_by_layout' ) ) {
	function wipe_clean_index_blog_sections_by_layout( $rows ) {
		$indexed_rows = array();

		foreach ( (array) $rows as $row ) {
			$layout = isset( $row['acf_fc_layout'] ) ? (string) $row['acf_fc_layout'] : '';

			if ( '' === $layout ) {
				continue;
			}

			$indexed_rows[ $layout ] = $row;
		}

		return $indexed_rows;
	}
}

if ( ! function_exists( 'wipe_clean_normalize_blog_sections' ) ) {
	function wipe_clean_normalize_blog_sections( $rows, $layout_order, $defaults_callback ) {
		$normalized_rows = array();
		$indexed_rows    = wipe_clean_index_blog_sections_by_layout( $rows );

		foreach ( (array) $layout_order as $layout ) {
			$defaults = is_callable( $defaults_callback )
				? (array) call_user_func( $defaults_callback, $layout )
				: array( 'acf_fc_layout' => $layout );

			$normalized_rows[] = function_exists( 'wipe_clean_merge_section_with_fallback' )
				? wipe_clean_merge_section_with_fallback( $defaults, $indexed_rows[ $layout ] ?? array() )
				: array_replace_recursive( $defaults, (array) ( $indexed_rows[ $layout ] ?? array() ) );
		}

		return $normalized_rows;
	}
}

if ( ! function_exists( 'wipe_clean_blog_has_meaningful_markup' ) ) {
	function wipe_clean_blog_has_meaningful_markup( $content ) {
		$content = trim( wp_strip_all_tags( (string) $content ) );

		return '' !== $content;
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_post_excerpt_text' ) ) {
	function wipe_clean_get_blog_post_excerpt_text( $post, $fallback = '' ) {
		$post = get_post( $post );

		if ( ! $post instanceof WP_Post ) {
			return (string) $fallback;
		}

		$excerpt = has_excerpt( $post )
			? (string) $post->post_excerpt
			: trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( strip_shortcodes( (string) $post->post_content ) ) ) );

		if ( '' === $excerpt ) {
			return (string) $fallback;
		}

		return wp_trim_words( $excerpt, 28, '...' );
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_post_thumbnail_alt' ) ) {
	function wipe_clean_get_blog_post_thumbnail_alt( $post_id, $fallback = '' ) {
		$thumbnail_id = (int) get_post_thumbnail_id( $post_id );

		if ( ! $thumbnail_id ) {
			return (string) $fallback;
		}

		$alt = trim( (string) get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ) );

		if ( '' !== $alt ) {
			return $alt;
		}

		$title = trim( (string) get_the_title( $thumbnail_id ) );

		return '' !== $title ? $title : (string) $fallback;
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_post_card_date' ) ) {
	function wipe_clean_get_blog_post_card_date( $post ) {
		$post = get_post( $post );

		if ( ! $post instanceof WP_Post ) {
			return array(
				'label'    => '',
				'datetime' => '',
			);
		}

		return array(
			'label'    => get_the_date( 'd / m / Y', $post ),
			'datetime' => get_the_date( 'Y-m-d', $post ),
		);
	}
}

if ( ! function_exists( 'wipe_clean_build_blog_card_from_post' ) ) {
	function wipe_clean_build_blog_card_from_post( $post, $fallback = array() ) {
		$post = get_post( $post );

		if ( ! $post instanceof WP_Post ) {
			return (array) $fallback;
		}

		$default_items = wipe_clean_get_blog_archive_default_items();
		$default_item  = isset( $default_items[0] ) && is_array( $default_items[0] ) ? $default_items[0] : array();
		$date          = wipe_clean_get_blog_post_card_date( $post );
		$item          = (array) $fallback;

		$item['title']     = get_the_title( $post );
		$item['excerpt']   = wipe_clean_get_blog_post_excerpt_text( $post, (string) ( $fallback['excerpt'] ?? '' ) );
		$item['href']      = get_permalink( $post );
		$item['dateLabel'] = $date['label'];
		$item['dateTime']  = $date['datetime'];
		$item['image']     = has_post_thumbnail( $post->ID ) ? get_post_thumbnail_id( $post->ID ) : ( $fallback['image'] ?? ( $default_item['image'] ?? array() ) );
		$item['imageAlt']  = wipe_clean_get_blog_post_thumbnail_alt(
			$post->ID,
			(string) ( $fallback['imageAlt'] ?? ( $default_item['imageAlt'] ?? get_the_title( $post ) ) )
		);

		return $item;
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_archive_items' ) ) {
	function wipe_clean_get_blog_archive_items() {
		$fallback_items = wipe_clean_get_blog_archive_default_items();
		$posts          = get_posts(
			array(
				'post_type'      => wipe_clean_get_blog_post_type(),
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);

		if ( empty( $posts ) ) {
			return $fallback_items;
		}

		$items = array();

		foreach ( $posts as $index => $post ) {
			$items[] = wipe_clean_build_blog_card_from_post( $post, $fallback_items[ $index ] ?? array() );
		}

		return $items;
	}
}

if ( ! function_exists( 'wipe_clean_get_related_blog_items' ) ) {
	function wipe_clean_get_related_blog_items( $post_id = 0, $limit = 4 ) {
		$post_id        = (int) $post_id;
		$limit          = max( 1, (int) $limit );
		$fallback_items = array_slice( wipe_clean_get_blog_related_default_items(), 0, $limit );
		$posts          = get_posts(
			array(
				'post_type'      => wipe_clean_get_blog_post_type(),
				'post_status'    => 'publish',
				'posts_per_page' => $limit,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'post__not_in'   => $post_id ? array( $post_id ) : array(),
			)
		);

		if ( empty( $posts ) ) {
			return $fallback_items;
		}

		$items = array();

		foreach ( $posts as $index => $post ) {
			$items[] = wipe_clean_build_blog_card_from_post( $post, $fallback_items[ $index ] ?? array() );
		}

		return $items;
	}
}

if ( ! function_exists( 'wipe_clean_prepare_blog_content_markup' ) ) {
	function wipe_clean_prepare_blog_content_markup( $content ) {
		$content = (string) $content;

		if ( '' === trim( $content ) ) {
			return '';
		}

		if (
			false !== strpos( $content, 'entry-content__section' ) ||
			false !== strpos( $content, 'entry-content__figure' ) ||
			false !== strpos( $content, '<section' ) ||
			false !== strpos( $content, '<figure' )
		) {
			return $content;
		}

		return apply_filters( 'the_content', $content );
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_archive_sections' ) ) {
	function wipe_clean_get_blog_archive_sections() {
		$sections = wipe_clean_normalize_blog_sections(
			wipe_clean_get_blog_archive_raw_rows(),
			wipe_clean_get_blog_archive_layout_order(),
			'wipe_clean_get_blog_archive_section_defaults'
		);

		foreach ( $sections as &$section ) {
			$layout = isset( $section['acf_fc_layout'] ) ? (string) $section['acf_fc_layout'] : '';

			if ( 'blog_archive' !== $layout ) {
				continue;
			}

			$section['items'] = wipe_clean_get_blog_archive_items();
		}
		unset( $section );

		return $sections;
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_single_sections' ) ) {
	function wipe_clean_get_blog_single_sections( $post_id = 0 ) {
		$post_id          = $post_id ? (int) $post_id : wipe_clean_get_current_blog_post_id();
		$post             = $post_id ? get_post( $post_id ) : wipe_clean_get_current_blog_post();
		$sections         = wipe_clean_normalize_blog_sections(
			wipe_clean_get_blog_post_raw_rows( $post_id ),
			wipe_clean_get_blog_single_layout_order(),
			'wipe_clean_get_blog_single_section_defaults'
		);
		$fallback_date    = wipe_clean_get_blog_archive_default_items()[0]['dateLabel'] ?? '';
		$fallback_content = (string) ( wipe_clean_get_blog_single_section_defaults( 'blog_article_content' )['content'] ?? '' );

		foreach ( $sections as &$section ) {
			$layout = isset( $section['acf_fc_layout'] ) ? (string) $section['acf_fc_layout'] : '';

			switch ( $layout ) {
				case 'blog_article_hero':
					if ( $post instanceof WP_Post ) {
						$title = trim( (string) get_the_title( $post ) );

						if ( '' !== $title ) {
							$section['title'] = $title;
						}

						$excerpt = wipe_clean_get_blog_post_excerpt_text( $post, (string) ( $section['excerpt'] ?? '' ) );

						if ( '' !== trim( $excerpt ) ) {
							$section['excerpt'] = $excerpt;
						}

						$section['date_value'] = get_the_date( 'd / m / Y', $post );

						if ( has_post_thumbnail( $post->ID ) ) {
							$section['image'] = get_post_thumbnail_id( $post->ID );
						}
					} else {
						$section['date_value'] = (string) ( $section['date_value'] ?? $fallback_date );
					}
					break;

				case 'blog_article_content':
					$post_content = $post instanceof WP_Post ? (string) $post->post_content : '';
					$acf_content  = (string) ( $section['content'] ?? '' );
					$content      = '';

					if ( wipe_clean_blog_has_meaningful_markup( $post_content ) ) {
						$content = $post_content;
					} elseif ( wipe_clean_blog_has_meaningful_markup( $acf_content ) ) {
						$content = $acf_content;
					} else {
						$content = $fallback_content;
					}

					$section['content_markup'] = wipe_clean_prepare_blog_content_markup( $content );
					break;

				case 'related_posts':
					$section['items']            = wipe_clean_get_related_blog_items( $post_id, 4 );
					$section['mobile_limit']     = max( 1, (int) ( $section['mobile_limit'] ?? 3 ) );
					$section['primary_action']   = wipe_clean_resolve_link( $section['primary_action'] ?? array() );
					$section['secondary_action'] = wipe_clean_resolve_link( $section['secondary_action'] ?? array() );

					if ( '' === trim( (string) $section['primary_action']['url'] ) ) {
						$section['primary_action'] = array(
							'title'  => (string) ( $section['primary_action']['title'] ?? 'Наши статьи' ),
							'url'    => wipe_clean_get_blog_archive_page_url(),
							'target' => '',
						);
					}

					if ( '' === trim( (string) $section['secondary_action']['url'] ) ) {
						$section['secondary_action'] = array(
							'title'  => (string) ( $section['secondary_action']['title'] ?? 'Наши услуги' ),
							'url'    => function_exists( 'wipe_clean_resolve_static_url' ) ? wipe_clean_resolve_static_url( 'services.html' ) : home_url( '/services/' ),
							'target' => '',
						);
					}
					break;
			}
		}
		unset( $section );

		return $sections;
	}
}

if ( ! function_exists( 'wipe_clean_render_blog_archive_sections' ) ) {
	function wipe_clean_render_blog_archive_sections() {
		foreach ( wipe_clean_get_blog_archive_sections() as $section ) {
			$layout = isset( $section['acf_fc_layout'] ) ? (string) $section['acf_fc_layout'] : '';

			switch ( $layout ) {
				case 'blog_archive':
					get_template_part( 'template-parts/section/blog/blog-archive', null, array( 'section' => $section ) );
					break;

				case 'contacts':
					get_template_part( 'template-parts/section/front-page/contacts', null, array( 'section' => $section ) );
					break;
			}
		}
	}
}

if ( ! function_exists( 'wipe_clean_render_blog_single_sections' ) ) {
	function wipe_clean_render_blog_single_sections( $post_id = 0 ) {
		foreach ( wipe_clean_get_blog_single_sections( $post_id ) as $section ) {
			$layout = isset( $section['acf_fc_layout'] ) ? (string) $section['acf_fc_layout'] : '';

			switch ( $layout ) {
				case 'blog_article_hero':
					get_template_part( 'template-parts/section/blog/blog-article-hero', null, array( 'section' => $section ) );
					break;

				case 'blog_article_content':
					get_template_part( 'template-parts/section/blog/blog-article-content', null, array( 'section' => $section ) );
					break;

				case 'related_posts':
					get_template_part( 'template-parts/section/blog/related-posts', null, array( 'section' => $section ) );
					break;

				case 'contacts':
					get_template_part( 'template-parts/section/front-page/contacts', null, array( 'section' => $section ) );
					break;
			}
		}
	}
}

if ( ! function_exists( 'wipe_clean_render_blog_archive_content' ) ) {
	function wipe_clean_render_blog_archive_content() {
		echo '<div class="blog-page">';
		wipe_clean_render_blog_archive_sections();
		echo '</div>';
	}
}

if ( ! function_exists( 'wipe_clean_render_blog_single_content' ) ) {
	function wipe_clean_render_blog_single_content( $post_id = 0 ) {
		wipe_clean_render_blog_single_sections( $post_id ? (int) $post_id : (int) get_queried_object_id() );
	}
}
