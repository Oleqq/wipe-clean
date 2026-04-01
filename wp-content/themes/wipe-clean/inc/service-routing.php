<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter(
	'register_post_type_args',
	static function ( $args, $post_type ) {
		if ( 'wipe_service' !== $post_type ) {
			return $args;
		}

		$args['public']             = true;
		$args['publicly_queryable'] = true;
		$args['query_var']          = true;
		$args['show_in_rest']       = true;
		$args['has_archive']        = 'services';
		$args['rewrite']            = array(
			'slug'       => 'services',
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
		$vars[] = 'wipe_clean_services_archive';
		$vars[] = 'wipe_clean_service_slug';

		return $vars;
	}
);

if ( ! function_exists( 'wipe_clean_is_services_archive_request' ) ) {
	function wipe_clean_is_services_archive_request() {
		return is_post_type_archive( 'wipe_service' ) || (bool) get_query_var( 'wipe_clean_services_archive' ) || 'services' === wipe_clean_current_request_path();
	}
}

if ( ! function_exists( 'wipe_clean_is_services_single_request' ) ) {
	function wipe_clean_is_services_single_request() {
		$path = wipe_clean_current_request_path();

		return is_singular( 'wipe_service' ) || (bool) get_query_var( 'wipe_clean_service_slug' ) || (bool) preg_match( '~^services/[^/]+/?$~', $path );
	}
}

if ( ! function_exists( 'wipe_clean_find_service_post_by_slug' ) ) {
	function wipe_clean_find_service_post_by_slug( $slug ) {
		$slug = sanitize_title( (string) $slug );

		if ( '' === $slug ) {
			return null;
		}

		$posts = get_posts(
			array(
				'post_type'      => 'wipe_service',
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

if ( ! function_exists( 'wipe_clean_get_current_service_post' ) ) {
	function wipe_clean_get_current_service_post() {
		$queried = get_queried_object();

		if ( $queried instanceof WP_Post && 'wipe_service' === $queried->post_type ) {
			return $queried;
		}

		$post_id = (int) get_queried_object_id();

		if ( $post_id ) {
			$post = get_post( $post_id );

			if ( $post instanceof WP_Post && 'wipe_service' === $post->post_type ) {
				return $post;
			}
		}

		$slug = (string) get_query_var( 'wipe_clean_service_slug' );

		if ( '' === $slug ) {
			$path = wipe_clean_current_request_path();

			if ( preg_match( '~^services/([^/]+)/?$~', $path, $matches ) ) {
				$slug = (string) $matches[1];
			}
		}

		return wipe_clean_find_service_post_by_slug( $slug );
	}
}

if ( ! function_exists( 'wipe_clean_get_current_service_post_id' ) ) {
	function wipe_clean_get_current_service_post_id() {
		$post = wipe_clean_get_current_service_post();

		return $post instanceof WP_Post ? (int) $post->ID : 0;
	}
}

add_action(
	'init',
	static function () {
		add_rewrite_rule( '^services/?$', 'index.php?wipe_clean_services_archive=1', 'top' );
		add_rewrite_rule( '^services/([^/]+)/?$', 'index.php?wipe_clean_service_slug=$matches[1]', 'top' );

		$version = 'wipe-clean-services-routes-v8';

		if ( get_option( '_wipe_clean_service_routes_version' ) !== $version ) {
			flush_rewrite_rules( false );
			update_option( '_wipe_clean_service_routes_version', $version );
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

		if ( $query->get( 'wipe_clean_services_archive' ) ) {
			$query->set( 'post_type', 'wipe_service' );
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

		if ( $query->get( 'wipe_clean_service_slug' ) ) {
			$query->set( 'post_type', 'wipe_service' );
			$query->set( 'post_status', 'publish' );
			$query->set( 'posts_per_page', 1 );
			$query->set( 'name', sanitize_title( (string) $query->get( 'wipe_clean_service_slug' ) ) );
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
		if ( wipe_clean_is_services_archive_request() || wipe_clean_is_services_single_request() ) {
			return false;
		}

		return $preempt;
	},
	20
);

add_filter(
	'redirect_canonical',
	static function ( $redirect_url ) {
		if ( wipe_clean_is_services_archive_request() || wipe_clean_is_services_single_request() ) {
			return false;
		}

		return $redirect_url;
	},
	20
);

add_filter(
	'post_type_link',
	static function ( $post_link, $post ) {
		if ( $post instanceof WP_Post && 'wipe_service' === $post->post_type ) {
			return home_url( '/services/' . $post->post_name . '/' );
		}

		return $post_link;
	},
	10,
	2
);

add_filter(
	'template_include',
	static function ( $template ) {
		if ( wipe_clean_is_services_archive_request() ) {
			$archive_template = locate_template( 'archive-services.php' );

			if ( $archive_template ) {
				return $archive_template;
			}
		}

		if ( wipe_clean_is_services_single_request() ) {
			$single_template = locate_template( 'single-service.php' );

			if ( $single_template ) {
				return $single_template;
			}
		}

		return $template;
	},
	50
);
