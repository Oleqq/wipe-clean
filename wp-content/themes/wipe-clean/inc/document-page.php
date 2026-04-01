<?php
/**
 * Document page rendering.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_get_document_page_slug' ) ) {
	function wipe_clean_get_document_page_slug( $post_id = 0 ) {
		$post_id = $post_id ? (int) $post_id : (int) get_queried_object_id();

		if ( $post_id && 'page' === get_post_type( $post_id ) ) {
			return (string) get_post_field( 'post_name', $post_id );
		}

		if ( function_exists( 'wipe_clean_current_request_path' ) ) {
			return (string) wipe_clean_current_request_path();
		}

		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
		$path        = (string) wp_parse_url( $request_uri, PHP_URL_PATH );
		$home_path   = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );

		if ( $home_path && 0 === strpos( $path, $home_path ) ) {
			$path = (string) substr( $path, strlen( $home_path ) );
		}

		return trim( $path, '/' );
	}
}

if ( ! function_exists( 'wipe_clean_is_policy_request' ) ) {
	function wipe_clean_is_policy_request() {
		return 'policy' === wipe_clean_get_document_page_slug();
	}
}

if ( ! function_exists( 'wipe_clean_bootstrap_policy_fallback_query' ) ) {
	function wipe_clean_bootstrap_policy_fallback_query() {
		global $wp_query;

		if ( $wp_query instanceof WP_Query ) {
			$wp_query->is_404      = false;
			$wp_query->is_page     = true;
			$wp_query->is_singular = true;
			$wp_query->is_single   = false;
			$wp_query->is_home     = false;
			$wp_query->is_archive  = false;
		}

		status_header( 200 );
	}
}

if ( ! function_exists( 'wipe_clean_is_document_page_post' ) ) {
	function wipe_clean_is_document_page_post( $post_id = 0 ) {
		$post_id = $post_id ? (int) $post_id : (int) get_queried_object_id();

		if ( ! $post_id || 'page' !== get_post_type( $post_id ) ) {
			return false;
		}

		$template_slug = (string) get_page_template_slug( $post_id );
		$post_slug     = wipe_clean_get_document_page_slug( $post_id );

		return 'template-document-page.php' === $template_slug || 'policy' === $post_slug;
	}
}

if ( ! function_exists( 'wipe_clean_get_document_page_title' ) ) {
	function wipe_clean_get_document_page_title( $post_id = 0 ) {
		$post_id = $post_id ? (int) $post_id : (int) get_queried_object_id();
		$title   = $post_id ? trim( (string) get_the_title( $post_id ) ) : '';

		return '' !== $title ? $title : wipe_clean_get_document_page_default_title( $post_id );
	}
}

if ( ! function_exists( 'wipe_clean_get_document_page_body_html' ) ) {
	function wipe_clean_get_document_page_body_html( $post_id = 0 ) {
		$post_id = $post_id ? (int) $post_id : (int) get_queried_object_id();
		$content = $post_id ? (string) get_post_field( 'post_content', $post_id ) : '';

		if ( '' !== trim( wp_strip_all_tags( $content ) ) ) {
			return apply_filters( 'the_content', $content );
		}

		return wp_kses_post( wipe_clean_get_document_page_default_content_html() );
	}
}

if ( ! function_exists( 'wipe_clean_get_document_page_modifier' ) ) {
	function wipe_clean_get_document_page_modifier( $post_id = 0 ) {
		$post_slug = wipe_clean_get_document_page_slug( $post_id );

		return sanitize_html_class( $post_slug ? $post_slug : 'document' );
	}
}

if ( ! function_exists( 'wipe_clean_render_document_page_template' ) ) {
	function wipe_clean_render_document_page_template( $post_id = 0 ) {
		$post_id = $post_id ? (int) $post_id : (int) get_queried_object_id();

		get_header();
		?>
		<main id="primary" class="main site-main">
			<div class="document-page document-page--<?php echo esc_attr( wipe_clean_get_document_page_modifier( $post_id ) ); ?>">
				<?php
				get_template_part(
					'template-parts/section/document-page/document-content',
					null,
					array(
						'title'     => wipe_clean_get_document_page_title( $post_id ),
						'body_html' => wipe_clean_get_document_page_body_html( $post_id ),
					)
				);
				?>
				<div class="document-page__contacts">
					<div class="_container">
						<div class="document-page__contacts-wrapper">
							<?php
							get_template_part(
								'template-parts/components/contact-panel',
								null,
								array(
									'panel'        => wipe_clean_get_document_page_contact_panel_defaults( $post_id ),
									'form_context' => array(
										'form_context_label'   => 'Контактная панель документа',
										'form_context_page'    => wipe_clean_get_document_page_title( $post_id ),
										'form_context_surface' => 'Контактный блок',
									),
								)
							);
							?>
						</div>
					</div>
				</div>
			</div>
		</main>
		<?php
		get_footer();
	}
}

add_filter(
	'pre_handle_404',
	static function ( $preempt, $query ) {
		if ( is_admin() || ! $query instanceof WP_Query || ! $query->is_main_query() ) {
			return $preempt;
		}

		if ( wipe_clean_is_policy_request() ) {
			return false;
		}

		return $preempt;
	},
	20,
	2
);

add_filter(
	'redirect_canonical',
	static function ( $redirect_url ) {
		if ( wipe_clean_is_policy_request() ) {
			return false;
		}

		return $redirect_url;
	},
	20
);

add_filter(
	'template_include',
	static function ( $template ) {
		if ( wipe_clean_is_policy_request() && ( is_404() || ! get_queried_object_id() ) ) {
			$policy_template = locate_template( 'page-policy.php' );

			if ( $policy_template ) {
				wipe_clean_bootstrap_policy_fallback_query();

				return $policy_template;
			}
		}

		return $template;
	},
	50
);
