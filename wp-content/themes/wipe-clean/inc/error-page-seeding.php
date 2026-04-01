<?php
/**
 * One-click seeding for the 404 page settings.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_is_error_page_options_admin_screen' ) ) {
	function wipe_clean_is_error_page_options_admin_screen() {
		return is_admin() && isset( $_GET['page'] ) && wipe_clean_get_error_page_options_slug() === (string) $_GET['page'];
	}
}

if ( ! function_exists( 'wipe_clean_error_page_has_seeded_values' ) ) {
	function wipe_clean_error_page_has_seeded_values() {
		if ( ! function_exists( 'get_field' ) ) {
			return false;
		}

		$title = get_field( 'error_404_title', 'option', false );

		return is_string( $title ) && '' !== trim( $title );
	}
}

if ( ! function_exists( 'wipe_clean_prepare_error_page_seed_social_links' ) ) {
	function wipe_clean_prepare_error_page_seed_social_links( $items ) {
		$prepared = array();

		foreach ( (array) $items as $item ) {
			$prepared[] = array(
				'label' => (string) ( $item['label'] ?? '' ),
				'url'   => (string) ( $item['url'] ?? '' ),
				'icon'  => function_exists( 'wipe_clean_services_page_import_attachment' ) ? (int) wipe_clean_services_page_import_attachment( $item['icon'] ?? array() ) : 0,
			);
		}

		return $prepared;
	}
}

if ( ! function_exists( 'wipe_clean_seed_error_page_settings' ) ) {
	function wipe_clean_seed_error_page_settings() {
		if ( ! function_exists( 'update_field' ) ) {
			return false;
		}

		$defaults      = wipe_clean_get_error_page_default_data();
		$contact_panel = (array) ( $defaults['contact_panel'] ?? array() );
		$image_id      = function_exists( 'wipe_clean_services_page_import_attachment' ) ? (int) wipe_clean_services_page_import_attachment( $defaults['visual_image'] ?? array() ) : 0;

		update_field( 'error_404_kicker', (string) ( $defaults['kicker'] ?? '' ), 'option' );
		update_field( 'error_404_title', (string) ( $defaults['title'] ?? '' ), 'option' );
		update_field( 'error_404_text', (string) ( $defaults['text'] ?? '' ), 'option' );
		update_field( 'error_404_primary_action', $defaults['primary_action'] ?? array(), 'option' );
		update_field( 'error_404_secondary_action', $defaults['secondary_action'] ?? array(), 'option' );
		update_field( 'error_404_visual_image', $image_id, 'option' );
		update_field( 'contact_panel_title', (string) ( $contact_panel['title'] ?? '' ), 'option' );
		update_field( 'contact_panel_form_title', (string) ( $contact_panel['form_title'] ?? '' ), 'option' );
		update_field( 'contact_panel_phone_label', (string) ( $contact_panel['phone_label'] ?? '' ), 'option' );
		update_field( 'contact_panel_phone_value', (string) ( $contact_panel['phone_value'] ?? '' ), 'option' );
		update_field( 'contact_panel_socials_label', (string) ( $contact_panel['socials_label'] ?? '' ), 'option' );
		update_field( 'contact_panel_social_links', wipe_clean_prepare_error_page_seed_social_links( $contact_panel['social_links'] ?? array() ), 'option' );
		update_field( 'contact_panel_email_label', (string) ( $contact_panel['email_label'] ?? '' ), 'option' );
		update_field( 'contact_panel_email_value', (string) ( $contact_panel['email_value'] ?? '' ), 'option' );
		update_field( 'contact_panel_name_label', (string) ( $contact_panel['form_name_label'] ?? '' ), 'option' );
		update_field( 'contact_panel_name_placeholder', (string) ( $contact_panel['form_name_placeholder'] ?? '' ), 'option' );
		update_field( 'contact_panel_phone_field_label', (string) ( $contact_panel['form_phone_label'] ?? '' ), 'option' );
		update_field( 'contact_panel_phone_placeholder', (string) ( $contact_panel['form_phone_placeholder'] ?? '' ), 'option' );
		update_field( 'contact_panel_agreement_text', (string) ( $contact_panel['agreement_text'] ?? '' ), 'option' );
		update_field( 'contact_panel_submit_text', (string) ( $contact_panel['submit_text'] ?? '' ), 'option' );
		update_field( 'contact_panel_submit_text_mobile', (string) ( $contact_panel['submit_text_mobile'] ?? '' ), 'option' );
		update_option( 'wipe_clean_error_page_seeded_at', time() );

		return true;
	}
}

if ( ! function_exists( 'wipe_clean_get_error_page_seed_action_url' ) ) {
	function wipe_clean_get_error_page_seed_action_url() {
		return wp_nonce_url(
			admin_url( 'admin-post.php?action=wipe_clean_seed_error_page' ),
			'wipe_clean_seed_error_page'
		);
	}
}

if ( ! function_exists( 'wipe_clean_render_error_page_seed_box' ) ) {
	function wipe_clean_render_error_page_seed_box() {
		if ( ! wipe_clean_is_error_page_options_admin_screen() || ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		$has_values = wipe_clean_error_page_has_seeded_values();
		?>
		<div class="notice notice-info" style="margin-top:16px;padding:0;border:none;background:transparent;box-shadow:none;">
			<div style="padding:16px 18px;border:1px solid #d7e8ee;border-radius:16px;background:linear-gradient(180deg,rgba(255,255,255,0.98) 0%,rgba(250,252,253,0.96) 100%);box-shadow:0 10px 24px rgba(21,15,49,0.06);">
				<div style="display:flex;flex-wrap:wrap;gap:14px;align-items:center;justify-content:space-between;">
					<div style="max-width:760px;">
						<div style="margin:0 0 6px;font-size:16px;font-weight:700;color:#150F31;">Заполнить готовым содержимым</div>
						<div style="font-size:13px;line-height:1.55;color:#5D5779;">
							Подставляет готовое содержимое страницы 404: первый экран, кнопки, изображение и блок связи.
							<?php if ( $has_values ) : ?>
								<strong style="color:#150F31;">Текущие значения будут заменены.</strong>
							<?php endif; ?>
						</div>
					</div>
					<div>
						<a class="button button-primary button-large" href="<?php echo esc_url( wipe_clean_get_error_page_seed_action_url() ); ?>" onclick="return window.confirm('Заполнить страницу 404 готовым содержимым? Текущие значения будут заменены.');">
							<?php echo esc_html( $has_values ? 'Обновить содержимое' : 'Заполнить готовым' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'wipe_clean_render_error_page_seed_box', 5 );

if ( ! function_exists( 'wipe_clean_handle_error_page_seed_action' ) ) {
	function wipe_clean_handle_error_page_seed_action() {
		if ( ! current_user_can( 'edit_pages' ) ) {
			wp_die( esc_html__( 'Недостаточно прав для этого действия.', 'wipe-clean' ) );
		}

		check_admin_referer( 'wipe_clean_seed_error_page' );
		wipe_clean_seed_error_page_settings();

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'                         => wipe_clean_get_error_page_options_slug(),
					'wipe_clean_error_page_seeded' => 1,
				),
				admin_url( 'admin.php' )
			)
		);
		exit;
	}
}
add_action( 'admin_post_wipe_clean_seed_error_page', 'wipe_clean_handle_error_page_seed_action' );

if ( ! function_exists( 'wipe_clean_render_error_page_seed_notice' ) ) {
	function wipe_clean_render_error_page_seed_notice() {
		if ( empty( $_GET['wipe_clean_error_page_seeded'] ) || ! wipe_clean_is_error_page_options_admin_screen() ) {
			return;
		}
		?>
		<div class="notice notice-success is-dismissible">
			<p>Страница 404 заполнена готовым содержимым.</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'wipe_clean_render_error_page_seed_notice', 20 );
