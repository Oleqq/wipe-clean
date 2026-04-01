<?php
/**
 * 404 page rendering.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_get_error_page_options_slug' ) ) {
	function wipe_clean_get_error_page_options_slug() {
		return 'wipe-clean-error-page';
	}
}

if ( ! function_exists( 'wipe_clean_get_error_page_settings_url' ) ) {
	function wipe_clean_get_error_page_settings_url() {
		$slug = wipe_clean_get_error_page_options_slug();
		$url  = function_exists( 'menu_page_url' ) ? menu_page_url( $slug, false ) : '';

		if ( ! $url ) {
			$url = admin_url( 'admin.php?page=' . $slug );
		}

		return $url;
	}
}

if ( ! function_exists( 'wipe_clean_is_error_page_request' ) ) {
	function wipe_clean_is_error_page_request() {
		return is_404();
	}
}

if ( ! function_exists( 'wipe_clean_get_error_page_data' ) ) {
	function wipe_clean_get_error_page_data() {
		$defaults = wipe_clean_get_error_page_default_data();

		if ( ! function_exists( 'get_field' ) ) {
			return $defaults;
		}

		$data = $defaults;

		$string_fields = array(
			'kicker'                               => 'error_404_kicker',
			'title'                                => 'error_404_title',
			'text'                                 => 'error_404_text',
			'contact_panel.title'                  => 'contact_panel_title',
			'contact_panel.form_title'             => 'contact_panel_form_title',
			'contact_panel.phone_label'            => 'contact_panel_phone_label',
			'contact_panel.phone_value'            => 'contact_panel_phone_value',
			'contact_panel.socials_label'          => 'contact_panel_socials_label',
			'contact_panel.email_label'            => 'contact_panel_email_label',
			'contact_panel.email_value'            => 'contact_panel_email_value',
			'contact_panel.form_name_label'        => 'contact_panel_name_label',
			'contact_panel.form_name_placeholder'  => 'contact_panel_name_placeholder',
			'contact_panel.form_phone_label'       => 'contact_panel_phone_field_label',
			'contact_panel.form_phone_placeholder' => 'contact_panel_phone_placeholder',
			'contact_panel.agreement_text'         => 'contact_panel_agreement_text',
			'contact_panel.submit_text'            => 'contact_panel_submit_text',
			'contact_panel.submit_text_mobile'     => 'contact_panel_submit_text_mobile',
		);

		foreach ( $string_fields as $path => $field_name ) {
			$value = get_field( $field_name, 'option' );

			if ( null === $value || '' === $value ) {
				continue;
			}

			if ( false === strpos( $path, '.' ) ) {
				$data[ $path ] = (string) $value;
				continue;
			}

			list( $group, $key ) = explode( '.', $path, 2 );
			$data[ $group ][ $key ] = (string) $value;
		}

		$link_fields = array(
			'primary_action'   => 'error_404_primary_action',
			'secondary_action' => 'error_404_secondary_action',
		);

		foreach ( $link_fields as $key => $field_name ) {
			$value = get_field( $field_name, 'option' );

			if ( is_array( $value ) && ! empty( $value ) ) {
				$data[ $key ] = $value;
			}
		}

		$visual_image = get_field( 'error_404_visual_image', 'option' );

		if ( ! empty( $visual_image ) ) {
			$data['visual_image'] = $visual_image;
		}

		$social_links = get_field( 'contact_panel_social_links', 'option' );

		if ( is_array( $social_links ) && ! empty( $social_links ) ) {
			$data['contact_panel']['social_links'] = array_values( $social_links );
		}

		return $data;
	}
}

if ( ! function_exists( 'wipe_clean_render_error_page_template' ) ) {
	function wipe_clean_render_error_page_template() {
		get_header();
		?>
		<main id="primary" class="main site-main">
			<?php get_template_part( 'template-parts/section/error-page/error-404', null, array( 'section' => wipe_clean_get_error_page_data() ) ); ?>
		</main>
		<?php
		get_footer();
	}
}
