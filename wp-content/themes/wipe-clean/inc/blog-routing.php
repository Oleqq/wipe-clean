<?php
/**
 * Routing helpers for blog CPT archive and single.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter(
	'register_post_type_args',
	static function ( $args, $post_type ) {
		if ( 'wipe_blog' !== $post_type ) {
			return $args;
		}

		$args['public']             = true;
		$args['publicly_queryable'] = true;
		$args['query_var']          = true;
		$args['show_in_rest']       = true;
		$args['has_archive']        = 'blog';
		$args['rewrite']            = array(
			'slug'       => 'blog',
			'with_front' => false,
		);

		return $args;
	},
	20,
	2
);

if ( ! function_exists( 'wipe_clean_current_request_path' ) ) {
	function wipe_clean_current_request_path() {
		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
		$path        = (string) wp_parse_url( $request_uri, PHP_URL_PATH );
		$home_path   = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );

		if ( $home_path && 0 === strpos( $path, $home_path ) ) {
			$path = (string) substr( $path, strlen( $home_path ) );
		}

		return trim( $path, '/' );
	}
}

add_filter(
	'query_vars',
	static function ( $vars ) {
		$vars[] = 'wipe_clean_blog_archive';
		$vars[] = 'wipe_clean_blog_slug';

		return $vars;
	}
);

add_action(
	'init',
	static function () {
		add_rewrite_rule( '^blog/?$', 'index.php?wipe_clean_blog_archive=1', 'top' );
		add_rewrite_rule( '^blog/([^/]+)/?$', 'index.php?wipe_clean_blog_slug=$matches[1]', 'top' );

		$version = 'wipe-clean-blog-routes-v1';

		if ( get_option( '_wipe_clean_blog_routes_version' ) !== $version ) {
			flush_rewrite_rules( false );
			update_option( '_wipe_clean_blog_routes_version', $version );
		}
	},
	20
);

add_action(
	'pre_get_posts',
	static function ( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( $query->get( 'wipe_clean_blog_archive' ) ) {
			$query->set( 'post_type', 'wipe_blog' );
			$query->set( 'post_status', 'publish' );
			$query->set( 'posts_per_page', -1 );
			$query->set( 'name', '' );
			$query->set( 'pagename', '' );
			$query->set( 'page_id', 0 );
			$query->is_home              = false;
			$query->is_page              = false;
			$query->is_singular          = false;
			$query->is_single            = false;
			$query->is_archive           = true;
			$query->is_post_type_archive = true;
			$query->is_404               = false;
		}

		if ( $query->get( 'wipe_clean_blog_slug' ) ) {
			$query->set( 'post_type', 'wipe_blog' );
			$query->set( 'post_status', 'publish' );
			$query->set( 'posts_per_page', 1 );
			$query->set( 'name', sanitize_title( (string) $query->get( 'wipe_clean_blog_slug' ) ) );
			$query->set( 'pagename', '' );
			$query->set( 'page_id', 0 );
			$query->is_home              = false;
			$query->is_page              = false;
			$query->is_archive           = false;
			$query->is_post_type_archive = false;
			$query->is_singular          = true;
			$query->is_single            = true;
			$query->is_404               = false;
		}
	},
	20
);

add_filter(
	'pre_handle_404',
	static function ( $preempt ) {
		if ( function_exists( 'wipe_clean_is_blog_archive_request' ) && wipe_clean_is_blog_archive_request() ) {
			return false;
		}

		if ( function_exists( 'wipe_clean_is_blog_single_request' ) && wipe_clean_is_blog_single_request() ) {
			return false;
		}

		return $preempt;
	},
	20
);

add_filter(
	'redirect_canonical',
	static function ( $redirect_url ) {
		if ( function_exists( 'wipe_clean_is_blog_archive_request' ) && wipe_clean_is_blog_archive_request() ) {
			return false;
		}

		if ( function_exists( 'wipe_clean_is_blog_single_request' ) && wipe_clean_is_blog_single_request() ) {
			return false;
		}

		return $redirect_url;
	},
	20
);

add_filter(
	'post_type_link',
	static function ( $post_link, $post ) {
		if ( $post instanceof WP_Post && 'wipe_blog' === $post->post_type ) {
			return home_url( '/blog/' . $post->post_name . '/' );
		}

		return $post_link;
	},
	10,
	2
);

add_filter(
	'template_include',
	static function ( $template ) {
		if ( function_exists( 'wipe_clean_is_blog_archive_request' ) && wipe_clean_is_blog_archive_request() ) {
			$archive_template = locate_template( 'archive-wipe_blog.php' );

			if ( $archive_template ) {
				return $archive_template;
			}
		}

		if ( function_exists( 'wipe_clean_is_blog_single_request' ) && wipe_clean_is_blog_single_request() ) {
			$single_template = locate_template( 'single-wipe_blog.php' );

			if ( $single_template ) {
				return $single_template;
			}
		}

		return $template;
	},
	50
);
