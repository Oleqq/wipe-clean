<?php
/**
 * Custom routing for the promotions archive page.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter(
	'query_vars',
	static function ( $vars ) {
		$vars[] = 'wipe_clean_promotions_archive';

		return $vars;
	}
);

add_action(
	'init',
	static function () {
		add_rewrite_rule( '^promotions/?$', 'index.php?wipe_clean_promotions_archive=1', 'top' );

		$version = 'wipe-clean-promotions-routes-v1';

		if ( get_option( '_wipe_clean_promotions_routes_version' ) !== $version ) {
			flush_rewrite_rules( false );
			update_option( '_wipe_clean_promotions_routes_version', $version );
		}
	},
	20
);

add_action(
	'pre_get_posts',
	static function ( $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! $query->get( 'wipe_clean_promotions_archive' ) ) {
			return;
		}

		$query->set( 'post_type', 'wipe_promotion' );
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
	},
	20
);

add_filter(
	'pre_handle_404',
	static function ( $preempt ) {
		if ( function_exists( 'wipe_clean_is_promotions_archive_request' ) && wipe_clean_is_promotions_archive_request() ) {
			return false;
		}

		return $preempt;
	},
	20
);

add_filter(
	'redirect_canonical',
	static function ( $redirect_url ) {
		if ( function_exists( 'wipe_clean_is_promotions_archive_request' ) && wipe_clean_is_promotions_archive_request() ) {
			return false;
		}

		return $redirect_url;
	},
	20
);

add_filter(
	'post_type_link',
	static function ( $post_link, $post ) {
		if ( $post instanceof WP_Post && function_exists( 'wipe_clean_get_promotions_post_type' ) && wipe_clean_get_promotions_post_type() === $post->post_type ) {
			return function_exists( 'wipe_clean_get_promotions_archive_page_url' ) ? wipe_clean_get_promotions_archive_page_url() : home_url( '/promotions/' );
		}

		return $post_link;
	},
	10,
	2
);

add_filter(
	'preview_post_link',
	static function ( $preview_link, $post ) {
		if ( $post instanceof WP_Post && function_exists( 'wipe_clean_get_promotions_post_type' ) && wipe_clean_get_promotions_post_type() === $post->post_type ) {
			return function_exists( 'wipe_clean_get_promotions_archive_page_url' ) ? wipe_clean_get_promotions_archive_page_url() : home_url( '/promotions/' );
		}

		return $preview_link;
	},
	10,
	2
);

add_filter(
	'template_include',
	static function ( $template ) {
		if ( function_exists( 'wipe_clean_is_promotions_archive_request' ) && wipe_clean_is_promotions_archive_request() ) {
			$archive_template = locate_template( 'archive-wipe_promotion.php' );

			if ( $archive_template ) {
				return $archive_template;
			}
		}

		return $template;
	},
	50
);
