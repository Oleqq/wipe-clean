<?php
/**
 * Managed Contact Form 7 integration, leads storage, and notifications.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_get_leads_settings_option_name() {
	return 'wipe_clean_leads_settings';
}

function wipe_clean_get_leads_table_version() {
	return '1.0.0';
}

function wipe_clean_get_leads_table_version_option_name() {
	return 'wipe_clean_leads_table_version';
}

function wipe_clean_get_managed_forms_map_option_name() {
	return 'wipe_clean_managed_cf7_forms_map';
}

function wipe_clean_get_managed_forms_last_sync_option_name() {
	return 'wipe_clean_managed_cf7_forms_last_sync';
}

function wipe_clean_get_managed_form_meta_key() {
	return '_wipe_clean_managed_form_key';
}

function wipe_clean_get_managed_form_hash_meta_key() {
	return '_wipe_clean_managed_form_hash';
}

function wipe_clean_get_leads_table_name() {
	global $wpdb;

	return $wpdb->prefix . 'wipe_clean_leads';
}

function wipe_clean_get_leads_settings_defaults() {
	return array(
		'notification_emails' => array(),
		'telegram_bot_token'  => '',
		'telegram_chat_ids'   => array(),
	);
}

function wipe_clean_parse_multiline_tokens( $value ) {
	$value = trim( (string) $value );

	if ( '' === $value ) {
		return array();
	}

	$parts = preg_split( '/[\s,;]+/', $value ) ?: array();
	$parts = array_values(
		array_filter(
			array_map( 'trim', $parts ),
			'strlen'
		)
	);

	return array_values( array_unique( $parts ) );
}

function wipe_clean_sanitize_leads_settings( $settings ) {
	$defaults = wipe_clean_get_leads_settings_defaults();
	$settings = is_array( $settings ) ? $settings : array();

	$emails = $settings['notification_emails'] ?? array();
	$emails = is_array( $emails ) ? $emails : wipe_clean_parse_multiline_tokens( (string) $emails );
	$emails = array_values(
		array_unique(
			array_filter(
				array_map( 'sanitize_email', $emails ),
				'is_email'
			)
		)
	);

	$chat_ids = $settings['telegram_chat_ids'] ?? array();
	$chat_ids = is_array( $chat_ids ) ? $chat_ids : wipe_clean_parse_multiline_tokens( (string) $chat_ids );
	$chat_ids = array_values(
		array_unique(
			array_filter(
				array_map(
					static function ( $chat_id ) {
						$chat_id = trim( (string) $chat_id );

						return '' !== $chat_id ? $chat_id : null;
					},
					$chat_ids
				)
			)
		)
	);

	return array(
		'notification_emails' => $emails,
		'telegram_bot_token'  => trim( (string) ( $settings['telegram_bot_token'] ?? $defaults['telegram_bot_token'] ) ),
		'telegram_chat_ids'   => $chat_ids,
	);
}

function wipe_clean_get_leads_settings() {
	$settings = get_option( wipe_clean_get_leads_settings_option_name(), array() );

	return array_replace_recursive(
		wipe_clean_get_leads_settings_defaults(),
		wipe_clean_sanitize_leads_settings( $settings )
	);
}

function wipe_clean_get_notification_recipient_emails() {
	$settings = wipe_clean_get_leads_settings();
	$emails   = array_values( (array) ( $settings['notification_emails'] ?? array() ) );

	if ( ! empty( $emails ) ) {
		return $emails;
	}

	$admin_email = sanitize_email( (string) get_option( 'admin_email' ) );

	return $admin_email ? array( $admin_email ) : array();
}

function wipe_clean_get_telegram_bot_token() {
	$settings = wipe_clean_get_leads_settings();

	return trim( (string) ( $settings['telegram_bot_token'] ?? '' ) );
}

function wipe_clean_get_telegram_chat_ids() {
	$settings = wipe_clean_get_leads_settings();

	return array_values( (array) ( $settings['telegram_chat_ids'] ?? array() ) );
}

function wipe_clean_is_cf7_available() {
	return class_exists( 'WPCF7_ContactForm' ) && class_exists( 'WPCF7_ContactFormTemplate' );
}

function wipe_clean_maybe_install_leads_table() {
	$current_version = (string) get_option( wipe_clean_get_leads_table_version_option_name(), '' );

	if ( wipe_clean_get_leads_table_version() === $current_version ) {
		return;
	}

	global $wpdb;

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	$table_name      = wipe_clean_get_leads_table_name();
	$charset_collate = $wpdb->get_charset_collate();
	$sql             = "CREATE TABLE {$table_name} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		created_at datetime NOT NULL,
		form_key varchar(120) NOT NULL,
		form_title varchar(255) NOT NULL,
		source_label varchar(255) NOT NULL,
		page_label varchar(255) NOT NULL,
		lead_name varchar(255) NOT NULL,
		lead_phone varchar(100) NOT NULL,
		lead_email varchar(255) NOT NULL,
		submission_status varchar(60) NOT NULL,
		mail_status varchar(60) NOT NULL,
		telegram_status varchar(60) NOT NULL,
		contact_form_id bigint(20) unsigned NOT NULL DEFAULT 0,
		submission_hash varchar(120) NOT NULL,
		submitted_url text NULL,
		remote_ip varchar(120) NOT NULL,
		user_agent text NULL,
		payload_json longtext NULL,
		delivery_json longtext NULL,
		PRIMARY KEY  (id),
		KEY form_key (form_key),
		KEY created_at (created_at),
		KEY submission_status (submission_status)
	) {$charset_collate};";

	dbDelta( $sql );
	update_option( wipe_clean_get_leads_table_version_option_name(), wipe_clean_get_leads_table_version() );
}
add_action( 'init', 'wipe_clean_maybe_install_leads_table', 5 );
add_action( 'after_switch_theme', 'wipe_clean_maybe_install_leads_table' );

function wipe_clean_get_front_page_forms_post_id() {
	$page_id = (int) get_option( 'page_on_front' );

	if ( $page_id && function_exists( 'wipe_clean_is_front_page_sections_post' ) && wipe_clean_is_front_page_sections_post( $page_id ) ) {
		return $page_id;
	}

	$page_ids = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_wp_page_template',
			'meta_value'     => 'template-home-page.php',
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
		)
	);

	return ! empty( $page_ids[0] ) ? (int) $page_ids[0] : 0;
}

function wipe_clean_get_contacts_page_forms_post_id() {
	if ( function_exists( 'wipe_clean_get_site_shell_publish_page_id_by_template' ) ) {
		$page_id = (int) wipe_clean_get_site_shell_publish_page_id_by_template( 'template-contacts-page.php' );

		if ( $page_id ) {
			return $page_id;
		}
	}

	if ( function_exists( 'wipe_clean_get_site_shell_publish_page_id_by_slug' ) ) {
		$page_id = (int) wipe_clean_get_site_shell_publish_page_id_by_slug( 'contacts' );

		if ( $page_id ) {
			return $page_id;
		}
	}

	return 0;
}

function wipe_clean_find_flexible_layout_row( $rows, $layout ) {
	foreach ( (array) $rows as $row ) {
		if ( $layout === (string) ( $row['acf_fc_layout'] ?? '' ) ) {
			return is_array( $row ) ? $row : array();
		}
	}

	return array();
}

function wipe_clean_get_front_page_section_for_forms( $layout ) {
	$defaults = function_exists( 'wipe_clean_get_front_page_section_defaults' )
		? wipe_clean_get_front_page_section_defaults( $layout )
		: array( 'acf_fc_layout' => $layout );

	$post_id = wipe_clean_get_front_page_forms_post_id();

	if ( ! $post_id || ! function_exists( 'get_field' ) ) {
		return $defaults;
	}

	$rows    = get_field( 'front_page_sections', $post_id );
	$section = wipe_clean_find_flexible_layout_row( is_array( $rows ) ? $rows : array(), $layout );

	if ( function_exists( 'wipe_clean_merge_section_with_fallback' ) ) {
		return wipe_clean_merge_section_with_fallback( $defaults, $section );
	}

	return array_replace_recursive( $defaults, $section );
}

function wipe_clean_get_contacts_page_section_for_forms( $layout ) {
	$defaults = function_exists( 'wipe_clean_get_contacts_page_section_defaults' )
		? wipe_clean_get_contacts_page_section_defaults( $layout )
		: array( 'acf_fc_layout' => $layout );

	$post_id = wipe_clean_get_contacts_page_forms_post_id();

	if ( ! $post_id || ! function_exists( 'get_field' ) ) {
		return $defaults;
	}

	$rows    = get_field( 'contacts_page_sections', $post_id );
	$section = wipe_clean_find_flexible_layout_row( is_array( $rows ) ? $rows : array(), $layout );

	if ( function_exists( 'wipe_clean_merge_section_with_fallback' ) ) {
		return wipe_clean_merge_section_with_fallback( $defaults, $section );
	}

	return array_replace_recursive( $defaults, $section );
}

function wipe_clean_get_promotions_contacts_section_for_forms() {
	if ( function_exists( 'wipe_clean_get_promotions_archive_sections' ) ) {
		foreach ( wipe_clean_get_promotions_archive_sections() as $section ) {
			if ( 'contacts' === (string) ( $section['acf_fc_layout'] ?? '' ) ) {
				return $section;
			}
		}
	}

	if ( function_exists( 'wipe_clean_get_promotions_archive_section_defaults' ) ) {
		return wipe_clean_get_promotions_archive_section_defaults( 'contacts' );
	}

	return function_exists( 'wipe_clean_get_front_page_section_defaults' )
		? wipe_clean_get_front_page_section_defaults( 'contacts' )
		: array( 'acf_fc_layout' => 'contacts' );
}

function wipe_clean_get_quote_request_section_for_forms() {
	$wave_group = wipe_clean_get_front_page_section_for_forms( 'home_wave_group' );

	return isset( $wave_group['quote_request'] ) && is_array( $wave_group['quote_request'] )
		? $wave_group['quote_request']
		: array();
}

function wipe_clean_get_contact_panel_for_forms() {
	return function_exists( 'wipe_clean_get_shared_contact_panel_defaults' )
		? wipe_clean_get_shared_contact_panel_defaults()
		: array();
}

function wipe_clean_get_front_page_forms_url() {
	$page_id = wipe_clean_get_front_page_forms_post_id();
	$url     = $page_id ? get_permalink( $page_id ) : '';

	return $url ? $url : home_url( '/' );
}

function wipe_clean_get_contacts_page_forms_url() {
	if ( function_exists( 'wipe_clean_get_site_contacts_page_url' ) ) {
		return wipe_clean_get_site_contacts_page_url();
	}

	$page_id = wipe_clean_get_contacts_page_forms_post_id();
	$url     = $page_id ? get_permalink( $page_id ) : '';

	return $url ? $url : home_url( '/contacts/' );
}

function wipe_clean_get_promotions_page_forms_url() {
	if ( function_exists( 'wipe_clean_get_promotions_archive_page_url' ) ) {
		return wipe_clean_get_promotions_archive_page_url();
	}

	return home_url( '/promotions/' );
}

function wipe_clean_get_policy_page_forms_url() {
	if ( function_exists( 'wipe_clean_get_site_policy_page_url' ) ) {
		return wipe_clean_get_site_policy_page_url();
	}

	return home_url( '/policy/' );
}

function wipe_clean_cf7_escape_tag_value( $value ) {
	return str_replace( '"', '\"', (string) $value );
}

function wipe_clean_build_cf7_text_tag( $type, $name, $options = array() ) {
	$tag_name = ! empty( $options['required'] ) ? $type . '*' : $type;
	$parts    = array( $tag_name, sanitize_key( $name ) );
	$classes  = preg_split( '/\s+/', trim( (string) ( $options['class'] ?? '' ) ) ) ?: array();

	foreach ( $classes as $class_name ) {
		$class_name = sanitize_html_class( $class_name );

		if ( '' !== $class_name ) {
			$parts[] = 'class:' . $class_name;
		}
	}

	if ( ! empty( $options['autocomplete'] ) ) {
		$autocomplete = preg_replace( '/[^a-z0-9_\-:]/i', '', (string) $options['autocomplete'] );

		if ( '' !== $autocomplete ) {
			$parts[] = 'autocomplete:' . $autocomplete;
		}
	}

	if ( ! empty( $options['id'] ) ) {
		$id = preg_replace( '/[^a-z0-9_\-{}]/i', '', (string) $options['id'] );

		if ( '' !== $id ) {
			$parts[] = 'id:' . $id;
		}
	}

	if ( ! empty( $options['placeholder'] ) ) {
		$parts[] = 'placeholder';
		$parts[] = '"' . wipe_clean_cf7_escape_tag_value( $options['placeholder'] ) . '"';
	}

	return '[' . implode( ' ', $parts ) . ']';
}

function wipe_clean_build_cf7_acceptance_tag( $name, $label, $options = array() ) {
	$parts   = array( 'acceptance', sanitize_key( $name ) );
	$classes = preg_split( '/\s+/', trim( (string) ( $options['class'] ?? '' ) ) ) ?: array();

	foreach ( $classes as $class_name ) {
		$class_name = sanitize_html_class( $class_name );

		if ( '' !== $class_name ) {
			$parts[] = 'class:' . $class_name;
		}
	}

	if ( ! empty( $options['invert'] ) ) {
		$parts[] = 'invert';
	}

	if ( ! empty( $options['optional'] ) ) {
		$parts[] = 'optional';
	}

	return '[' . implode( ' ', $parts ) . ']' . esc_html( $label ) . '[/acceptance]';
}

function wipe_clean_build_cf7_file_tag( $name, $options = array() ) {
	$tag_name = ! empty( $options['required'] ) ? 'file*' : 'file';
	$parts    = array( $tag_name, sanitize_key( $name ) );
	$classes  = preg_split( '/\s+/', trim( (string) ( $options['class'] ?? '' ) ) ) ?: array();

	foreach ( $classes as $class_name ) {
		$class_name = sanitize_html_class( $class_name );

		if ( '' !== $class_name ) {
			$parts[] = 'class:' . $class_name;
		}
	}

	if ( ! empty( $options['id'] ) ) {
		$id = preg_replace( '/[^a-z0-9_\-{}]/i', '', (string) $options['id'] );

		if ( '' !== $id ) {
			$parts[] = 'id:' . $id;
		}
	}

	if ( ! empty( $options['filetypes'] ) ) {
		$filetypes = array_filter(
			array_map(
				'trim',
				preg_split( '/[|,\s]+/', (string) $options['filetypes'] ) ?: array()
			),
			'strlen'
		);

		if ( ! empty( $filetypes ) ) {
			$parts[] = 'filetypes:' . implode( '|', $filetypes );
		}
	}

	if ( ! empty( $options['limit'] ) ) {
		$parts[] = 'limit:' . preg_replace( '/[^a-z0-9]/i', '', (string) $options['limit'] );
	}

	return '[' . implode( ' ', $parts ) . ']';
}

function wipe_clean_build_select_options_html( $options, $selected_value ) {
	$html = '';

	foreach ( (array) $options as $option ) {
		$value = (string) ( $option['value'] ?? '' );
		$label = (string) ( $option['label'] ?? $value );

		$html .= sprintf(
			'<option value="%1$s"%2$s>%3$s</option>',
			esc_attr( $value ),
			selected( $selected_value, $value, false ),
			esc_html( $label )
		);
	}

	return $html;
}

function wipe_clean_build_form_context_hidden_inputs( $extra = array() ) {
	$fields = array_merge(
		array(
			'form_context_key'     => '{{form_context_key}}',
			'form_context_label'   => '{{form_context_label}}',
			'form_context_page'    => '{{form_context_page}}',
			'form_context_surface' => '{{form_context_surface}}',
		),
		(array) $extra
	);

	$html = '';

	foreach ( $fields as $name => $value ) {
		$html .= sprintf(
			'<input type="hidden" name="%1$s" value="%2$s">',
			esc_attr( $name ),
			esc_attr( (string) $value )
		);
	}

	return $html;
}

function wipe_clean_build_home_hero_form_markup( $section ) {
	$area_options      = array_values( (array) ( $section['area_options'] ?? array() ) );
	$service_options   = array_values( (array) ( $section['service_options'] ?? array() ) );
	$frequency_options = array_values( (array) ( $section['frequency_options'] ?? array() ) );
	$area_default      = (string) ( $area_options[0]['value'] ?? '' );
	$service_default   = (string) ( $section['service_default'] ?? ( $service_options[0]['value'] ?? '' ) );
	$frequency_default = (string) ( $section['frequency_default'] ?? ( $frequency_options[0]['value'] ?? '' ) );
	$choices_html      = '';
	$service_field_id  = 'home-hero-service';
	$frequency_field_id = 'home-hero-frequency';
	$name_field_id     = 'home-hero-name';
	$phone_field_id    = 'home-hero-phone';

	foreach ( $area_options as $index => $option ) {
		$is_active = 0 === $index;

		$choices_html .= sprintf(
			'<button class="ui-choice%1$s home-hero__choice swiper-slide%2$s" type="button" aria-pressed="%3$s" data-form-choice data-choice-value="%4$s"><span class="ui-choice__label">%5$s</span></button>',
			$is_active ? ' ui-choice--active' : '',
			! empty( $option['is_meter'] ) ? ' home-hero__choice--meter' : '',
			$is_active ? 'true' : 'false',
			esc_attr( (string) ( $option['value'] ?? '' ) ),
			wp_kses( (string) ( $option['label'] ?? '' ), wipe_clean_allowed_inline_html() )
		);
	}

	$name_tag  = wipe_clean_build_cf7_text_tag(
		'text',
		'name',
		array(
			'required'     => true,
			'class'        => 'ui-field__control',
			'id'           => $name_field_id,
			'autocomplete' => 'name',
			'placeholder'  => (string) ( $section['name_placeholder'] ?? '' ),
		)
	);
	$phone_tag = wipe_clean_build_cf7_text_tag(
		'tel',
		'phone',
		array(
			'required'     => true,
			'class'        => 'ui-field__control',
			'id'           => $phone_field_id,
			'autocomplete' => 'tel-national',
			'placeholder'  => (string) ( $section['phone_placeholder'] ?? '' ),
		)
	);

	return
		wipe_clean_build_form_context_hidden_inputs() .
		sprintf(
			'
<input type="hidden" name="area" value="%1$s" data-form-choice-input>
<fieldset class="home-hero__area">
	<legend class="home-hero__area-title">%2$s</legend>
	<div class="home-hero__area-slider swiper" data-hero-area-swiper>
		<div class="ui-choice-group home-hero__area-options swiper-wrapper" data-form-choice-group>
			%3$s
		</div>
	</div>
</fieldset>
<div class="home-hero__form-grid">
	<div class="ui-field home-hero__field">
		<label class="ui-field__label" for="%4$s">%5$s</label>
		<div class="ui-select home-hero__select">
			<select class="ui-field__control" id="%4$s" name="service" required>
				%6$s
			</select>
			<span class="ui-select__icon" aria-hidden="true">
				<svg class="ui-select__icon-svg" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M1 1.25L6 6.25L11 1.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
				</svg>
			</span>
		</div>
	</div>
	<div class="ui-field home-hero__field">
		<label class="ui-field__label" for="%7$s">%8$s</label>
		<div class="ui-select home-hero__select">
			<select class="ui-field__control" id="%7$s" name="frequency" required>
				%9$s
			</select>
			<span class="ui-select__icon" aria-hidden="true">
				<svg class="ui-select__icon-svg" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M1 1.25L6 6.25L11 1.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
				</svg>
			</span>
		</div>
	</div>
	<div class="ui-field home-hero__field">
		<label class="ui-field__label" for="%10$s">%11$s</label>
		%12$s
	</div>
	<div class="ui-field home-hero__field">
		<label class="ui-field__label" for="%13$s">%14$s</label>
		%15$s
	</div>
</div>
<div class="home-hero__actions" data-form-actions>
	<div class="ui-checkbox home-hero__checkbox">%16$s</div>
	<button class="ui-btn ui-btn--primary ui-btn--full home-hero__submit" type="submit">
		<span class="ui-btn__content">%17$s</span>
	</button>
</div>',
			esc_attr( $area_default ),
			esc_html( (string) ( $section['area_title'] ?? '' ) ),
			$choices_html,
			esc_attr( $service_field_id ),
			esc_html( (string) ( $section['service_label'] ?? '' ) ),
			wipe_clean_build_select_options_html( $service_options, $service_default ),
			esc_attr( $frequency_field_id ),
			esc_html( (string) ( $section['frequency_label'] ?? '' ) ),
			wipe_clean_build_select_options_html( $frequency_options, $frequency_default ),
			esc_attr( $name_field_id ),
			esc_html( (string) ( $section['name_label'] ?? '' ) ),
			$name_tag,
			esc_attr( $phone_field_id ),
			esc_html( (string) ( $section['phone_label'] ?? '' ) ),
			$phone_tag,
			wipe_clean_build_cf7_acceptance_tag( 'agreement', (string) ( $section['agreement_text'] ?? '' ), array( 'class' => 'ui-checkbox__input' ) ),
			esc_html( (string) ( $section['submit_text'] ?? '' ) )
		);
}

function wipe_clean_build_contacts_form_markup( $section, $prefix, $actions_class = '' ) {
	$field_prefix = sanitize_html_class( $prefix );
	$name_field_id = $field_prefix . '-name';
	$phone_field_id = $field_prefix . '-phone';

	$name_tag  = wipe_clean_build_cf7_text_tag(
		'text',
		'name',
		array(
			'required'     => true,
			'class'        => 'ui-field__control',
			'id'           => $name_field_id,
			'autocomplete' => 'name',
			'placeholder'  => (string) ( $section['form_name_placeholder'] ?? '' ),
		)
	);
	$phone_tag = wipe_clean_build_cf7_text_tag(
		'tel',
		'phone',
		array(
			'required'     => true,
			'class'        => 'ui-field__control',
			'id'           => $phone_field_id,
			'autocomplete' => 'tel-national',
			'placeholder'  => (string) ( $section['form_phone_placeholder'] ?? '' ),
		)
	);

	if ( '' === $actions_class ) {
		$actions_class = sanitize_html_class( $prefix ) . '__form-actions';
	}

	return
		wipe_clean_build_form_context_hidden_inputs() .
		sprintf(
			'
<div class="%1$s__fields">
	<div class="ui-field %1$s__field">
		<label class="ui-field__label" for="%2$s">%3$s</label>
		%4$s
	</div>
	<div class="ui-field %1$s__field">
		<label class="ui-field__label" for="%5$s">%6$s</label>
		%7$s
	</div>
</div>
<div class="%11$s" data-form-actions>
	<div class="ui-checkbox %1$s__checkbox">%8$s</div>
	<button class="ui-btn ui-btn--primary %1$s__submit" type="submit">
		<span class="ui-btn__content">
			<span class="%1$s__submit-text %1$s__submit-text--desktop">%9$s</span>
			<span class="%1$s__submit-text %1$s__submit-text--mobile">%10$s</span>
		</span>
	</button>
</div>',
			sanitize_html_class( $prefix ),
			esc_attr( $name_field_id ),
			esc_html( (string) ( $section['form_name_label'] ?? '' ) ),
			$name_tag,
			esc_attr( $phone_field_id ),
			esc_html( (string) ( $section['form_phone_label'] ?? '' ) ),
			$phone_tag,
			wipe_clean_build_cf7_acceptance_tag( 'agreement', (string) ( $section['agreement_text'] ?? '' ), array( 'class' => 'ui-checkbox__input' ) ),
			esc_html( (string) ( $section['submit_text'] ?? '' ) ),
			esc_html( (string) ( $section['submit_text_mobile'] ?? ( $section['submit_text'] ?? '' ) ) ),
			esc_attr( $actions_class )
		);
}

function wipe_clean_build_quote_request_form_markup( $section ) {
	$name_field_id  = 'quote-request-name';
	$phone_field_id = 'quote-request-phone';

	$name_tag  = wipe_clean_build_cf7_text_tag(
		'text',
		'name',
		array(
			'required'     => true,
			'class'        => 'ui-field__control',
			'id'           => $name_field_id,
			'autocomplete' => 'name',
			'placeholder'  => (string) ( $section['name_placeholder'] ?? '' ),
		)
	);
	$phone_tag = wipe_clean_build_cf7_text_tag(
		'tel',
		'phone',
		array(
			'required'     => true,
			'class'        => 'ui-field__control',
			'id'           => $phone_field_id,
			'autocomplete' => 'tel-national',
			'placeholder'  => (string) ( $section['phone_placeholder'] ?? '' ),
		)
	);

	return
		wipe_clean_build_form_context_hidden_inputs() .
		sprintf(
			'
<div class="quote-request__fields">
	<div class="ui-field quote-request__field">
		<label class="ui-field__label" for="%1$s">%2$s</label>
		%3$s
	</div>
	<div class="ui-field quote-request__field">
		<label class="ui-field__label" for="%4$s">%5$s</label>
		%6$s
	</div>
</div>
<div class="ui-checkbox quote-request__checkbox" data-form-actions>%7$s</div>
<button class="ui-btn ui-btn--primary ui-btn--full quote-request__submit" type="submit">
	<span class="ui-btn__content">%8$s</span>
</button>',
			esc_attr( $name_field_id ),
			esc_html( (string) ( $section['name_label'] ?? '' ) ),
			$name_tag,
			esc_attr( $phone_field_id ),
			esc_html( (string) ( $section['phone_label'] ?? '' ) ),
			$phone_tag,
			wipe_clean_build_cf7_acceptance_tag( 'agreement', (string) ( $section['agreement_text'] ?? '' ), array( 'class' => 'ui-checkbox__input' ) ),
			esc_html( (string) ( $section['submit_text'] ?? '' ) )
		);
}

function wipe_clean_build_popup_form_markup() {
	$name_field_id  = '{{popup_id}}-name';
	$phone_field_id = '{{popup_id}}-phone';

	$name_tag  = wipe_clean_build_cf7_text_tag(
		'text',
		'name',
		array(
			'required'     => true,
			'class'        => 'ui-field__control',
			'id'           => $name_field_id,
			'autocomplete' => 'name',
			'placeholder'  => 'Введите имя и фамилию',
		)
	);
	$phone_tag = wipe_clean_build_cf7_text_tag(
		'tel',
		'phone',
		array(
			'required'     => true,
			'class'        => 'ui-field__control',
			'id'           => $phone_field_id,
			'autocomplete' => 'tel-national',
			'placeholder'  => '+7 _ _ _ _ _ _ _ _ _ _',
		)
	);

	return
		wipe_clean_build_form_context_hidden_inputs(
			array(
				'promotion_title' => '{{promotion_title}}',
				'popup_id'        => '{{popup_id}}',
			)
		) .
		sprintf(
			'
<div class="popup-form__grid popup-form__grid--two">
	<div class="ui-field popup-form__field">
		<label class="ui-field__label" for="%1$s">Ваше имя</label>
		%2$s
	</div>
	<div class="ui-field popup-form__field">
		<label class="ui-field__label" for="%3$s">Номер телефона</label>
		%4$s
	</div>
</div>
<div class="popup-form__actions" data-form-actions>
	<div class="ui-checkbox popup-form__checkbox">%5$s</div>
	<button class="ui-btn ui-btn--primary popup-form__submit" type="submit">
		<span class="ui-btn__content">Заказать клининг</span>
	</button>
</div>',
			esc_attr( $name_field_id ),
			$name_tag,
			esc_attr( $phone_field_id ),
			$phone_tag,
			wipe_clean_build_cf7_acceptance_tag( 'agreement', 'Заполняя форму вы даёте согласие на обработку персональных данных', array( 'class' => 'ui-checkbox__input' ) )
		);
}

function wipe_clean_build_popup_order_service_form_markup( $section ) {
	$name_field_id    = 'popup-order-service-name';
	$phone_field_id   = 'popup-order-service-phone';
	$email_field_id   = 'popup-order-service-email';
	$service_field_id = 'popup-order-service-select';
	$service_options  = array_values( (array) ( $section['service_options'] ?? array() ) );

	$name_tag = wipe_clean_build_cf7_text_tag(
		'text',
		'name',
		array(
			'required'     => true,
			'class'        => 'ui-field__control',
			'id'           => $name_field_id,
			'autocomplete' => 'name',
			'placeholder'  => 'Введите имя и фамилию',
		)
	);
	$phone_tag = wipe_clean_build_cf7_text_tag(
		'tel',
		'phone',
		array(
			'required'     => true,
			'class'        => 'ui-field__control',
			'id'           => $phone_field_id,
			'autocomplete' => 'tel-national',
			'placeholder'  => '+7 _ _ _ _ _ _ _ _ _ _',
		)
	);
	$email_tag = wipe_clean_build_cf7_text_tag(
		'email',
		'email',
		array(
			'required'     => true,
			'class'        => 'ui-field__control',
			'id'           => $email_field_id,
			'autocomplete' => 'email',
			'placeholder'  => 'Ваша электронная почта',
		)
	);

	$options_html = '<option value="" disabled selected>Выберите нужную вам услугу</option>' . wipe_clean_build_select_options_html( $service_options, '' );

	return
		wipe_clean_build_form_context_hidden_inputs() .
		sprintf(
			'
<div class="popup-form__grid popup-form__grid--two">
	<div class="ui-field popup-form__field">
		<label class="ui-field__label" for="%1$s">Ваше имя</label>
		%2$s
	</div>
	<div class="ui-field popup-form__field">
		<label class="ui-field__label" for="%3$s">Номер телефона</label>
		%4$s
	</div>
</div>
<div class="ui-field popup-form__field">
	<label class="ui-field__label" for="%5$s">Email</label>
	%6$s
</div>
<div class="ui-field popup-form__field">
	<label class="ui-field__label" for="%7$s">Выбор услуг</label>
	<div class="popup-form__select">
		<select id="%7$s" name="service" required>
			%8$s
		</select>
		<span class="popup-form__select-icon" aria-hidden="true">
			<svg class="popup-form__select-icon-svg" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
				<path d="M1 1.25L6 6.25L11 1.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
			</svg>
		</span>
	</div>
</div>
<div class="popup-form__actions" data-form-actions>
	<div class="ui-checkbox popup-form__checkbox">%9$s</div>
	<button class="ui-btn ui-btn--primary popup-form__submit" type="submit">
		<span class="ui-btn__content">Заказать клининг</span>
	</button>
</div>',
			esc_attr( $name_field_id ),
			$name_tag,
			esc_attr( $phone_field_id ),
			$phone_tag,
			esc_attr( $email_field_id ),
			$email_tag,
			esc_attr( $service_field_id ),
			$options_html,
			wipe_clean_build_cf7_acceptance_tag( 'agreement', 'Заполняя форму вы даёте согласие на обработку персональных данных', array( 'class' => 'ui-checkbox__input' ) )
		);
}

function wipe_clean_build_popup_calc_form_markup( $section ) {
	$area_options       = array_values( (array) ( $section['area_options'] ?? array() ) );
	$service_options    = array_values( (array) ( $section['service_options'] ?? array() ) );
	$frequency_options  = array_values( (array) ( $section['frequency_options'] ?? array() ) );
	$area_default       = (string) ( $area_options[0]['value'] ?? '' );
	$service_field_id   = 'popup-calc-service';
	$frequency_field_id = 'popup-calc-frequency';
	$name_field_id      = 'popup-calc-name';
	$phone_field_id     = 'popup-calc-phone';
	$choices_html       = '';

	foreach ( $area_options as $index => $option ) {
		$is_active = 0 === $index;

		$choices_html .= sprintf(
			'<button class="ui-choice popup-form__choice swiper-slide%1$s" type="button" aria-pressed="%2$s" data-form-choice data-choice-value="%3$s"><span class="ui-choice__label">%4$s</span></button>',
			$is_active ? ' ui-choice--active' : '',
			$is_active ? 'true' : 'false',
			esc_attr( (string) ( $option['value'] ?? '' ) ),
			wp_kses( (string) ( $option['label'] ?? '' ), wipe_clean_allowed_inline_html() )
		);
	}

	$name_tag = wipe_clean_build_cf7_text_tag(
		'text',
		'name',
		array(
			'required'     => true,
			'class'        => 'ui-field__control',
			'id'           => $name_field_id,
			'autocomplete' => 'name',
			'placeholder'  => 'Введите имя и фамилию',
		)
	);
	$phone_tag = wipe_clean_build_cf7_text_tag(
		'tel',
		'phone',
		array(
			'required'     => true,
			'class'        => 'ui-field__control',
			'id'           => $phone_field_id,
			'autocomplete' => 'tel-national',
			'placeholder'  => '+7 _ _ _ _ _ _ _ _ _ _',
		)
	);

	return
		wipe_clean_build_form_context_hidden_inputs() .
		sprintf(
			'
<input type="hidden" name="area" value="%1$s" data-form-choice-input>
<fieldset class="popup-form__fieldset">
	<legend class="popup-form__legend">Площадь</legend>
	<div class="popup-form__choice-slider swiper" data-popup-choice-swiper>
		<div class="ui-choice-group popup-form__choice-group swiper-wrapper" data-form-choice-group>
			%2$s
		</div>
	</div>
</fieldset>
<div class="popup-form__grid popup-form__grid--two">
	<div class="ui-field popup-form__field">
		<label class="ui-field__label" for="%3$s">Услуги</label>
		<div class="popup-form__select">
			<select id="%3$s" name="service" required>
				<option value="" disabled selected>Выберите услугу</option>
				%4$s
			</select>
			<span class="popup-form__select-icon" aria-hidden="true">
				<svg class="popup-form__select-icon-svg" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
					<path d="M1 1.25L6 6.25L11 1.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
				</svg>
			</span>
		</div>
	</div>
	<div class="ui-field popup-form__field">
		<label class="ui-field__label" for="%5$s">Регулярность</label>
		<div class="popup-form__select">
			<select id="%5$s" name="frequency" required>
				<option value="" disabled selected>Выберите регулярность</option>
				%6$s
			</select>
			<span class="popup-form__select-icon" aria-hidden="true">
				<svg class="popup-form__select-icon-svg" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
					<path d="M1 1.25L6 6.25L11 1.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
				</svg>
			</span>
		</div>
	</div>
</div>
<div class="popup-form__grid popup-form__grid--two">
	<div class="ui-field popup-form__field">
		<label class="ui-field__label" for="%7$s">Ваше имя</label>
		%8$s
	</div>
	<div class="ui-field popup-form__field">
		<label class="ui-field__label" for="%9$s">Номер телефона</label>
		%10$s
	</div>
</div>
<div class="popup-form__actions" data-form-actions>
	<div class="ui-checkbox popup-form__checkbox">%11$s</div>
	<button class="ui-btn ui-btn--primary popup-form__submit" type="submit">
		<span class="ui-btn__content">Рассчитать стоимость</span>
	</button>
</div>',
			esc_attr( $area_default ),
			$choices_html,
			esc_attr( $service_field_id ),
			wipe_clean_build_select_options_html( $service_options, '' ),
			esc_attr( $frequency_field_id ),
			wipe_clean_build_select_options_html( $frequency_options, '' ),
			esc_attr( $name_field_id ),
			$name_tag,
			esc_attr( $phone_field_id ),
			$phone_tag,
			wipe_clean_build_cf7_acceptance_tag( 'agreement', 'Заполняя форму вы даёте согласие на обработку персональных данных', array( 'class' => 'ui-checkbox__input' ) )
		);
}

function wipe_clean_build_popup_question_form_markup() {
	$name_field_id    = 'popup-question-name';
	$phone_field_id   = 'popup-question-phone';
	$email_field_id   = 'popup-question-email';
	$message_field_id = 'popup-question-message';

	$name_tag = wipe_clean_build_cf7_text_tag(
		'text',
		'name',
		array(
			'required'     => true,
			'class'        => 'ui-field__control',
			'id'           => $name_field_id,
			'autocomplete' => 'name',
			'placeholder'  => 'Введите имя и фамилию',
		)
	);
	$phone_tag = wipe_clean_build_cf7_text_tag(
		'tel',
		'phone',
		array(
			'required'     => true,
			'class'        => 'ui-field__control',
			'id'           => $phone_field_id,
			'autocomplete' => 'tel-national',
			'placeholder'  => '+7 _ _ _ _ _ _ _ _ _ _',
		)
	);
	$email_tag = wipe_clean_build_cf7_text_tag(
		'email',
		'email',
		array(
			'required'     => true,
			'class'        => 'ui-field__control',
			'id'           => $email_field_id,
			'autocomplete' => 'email',
			'placeholder'  => 'Ваша электронная почта',
		)
	);
	$message_tag = sprintf(
		'[textarea* message class:ui-field__control id:%1$s placeholder "Напишите ваш вопрос ..."]',
		sanitize_html_class( $message_field_id )
	);

	return
		wipe_clean_build_form_context_hidden_inputs() .
		sprintf(
			'
<div class="popup-form__grid popup-form__grid--two">
	<div class="ui-field popup-form__field">
		<label class="ui-field__label" for="%1$s">Ваше имя</label>
		%2$s
	</div>
	<div class="ui-field popup-form__field">
		<label class="ui-field__label" for="%3$s">Номер телефона</label>
		%4$s
	</div>
</div>
<div class="ui-field popup-form__field">
	<label class="ui-field__label" for="%5$s">Email</label>
	%6$s
</div>
<div class="ui-field popup-form__field">
	<label class="ui-field__label" for="%7$s">Ваш вопрос</label>
	%8$s
</div>
<div class="popup-form__actions" data-form-actions>
	<div class="ui-checkbox popup-form__checkbox">%9$s</div>
	<button class="ui-btn ui-btn--primary popup-form__submit" type="submit">
		<span class="ui-btn__content">Отправить вопрос</span>
	</button>
</div>',
			esc_attr( $name_field_id ),
			$name_tag,
			esc_attr( $phone_field_id ),
			$phone_tag,
			esc_attr( $email_field_id ),
			$email_tag,
			esc_attr( $message_field_id ),
			$message_tag,
			wipe_clean_build_cf7_acceptance_tag( 'agreement', 'Заполняя форму вы даёте согласие на обработку персональных данных', array( 'class' => 'ui-checkbox__input' ) )
		);
}

function wipe_clean_build_popup_review_form_markup() {
	$name_field_id    = 'popup-review-name';
	$phone_field_id   = 'popup-review-phone';
	$email_field_id   = 'popup-review-email';
	$message_field_id = 'popup-review-message';
	$files_field_id   = 'popup-review-files';

	$name_tag = wipe_clean_build_cf7_text_tag(
		'text',
		'name',
		array(
			'required'     => true,
			'class'        => 'ui-field__control',
			'id'           => $name_field_id,
			'autocomplete' => 'name',
			'placeholder'  => 'Введите имя и фамилию',
		)
	);
	$phone_tag = wipe_clean_build_cf7_text_tag(
		'tel',
		'phone',
		array(
			'required'     => true,
			'class'        => 'ui-field__control',
			'id'           => $phone_field_id,
			'autocomplete' => 'tel-national',
			'placeholder'  => '+7 _ _ _ _ _ _ _ _ _ _',
		)
	);
	$email_tag = wipe_clean_build_cf7_text_tag(
		'email',
		'email',
		array(
			'required'     => true,
			'class'        => 'ui-field__control',
			'id'           => $email_field_id,
			'autocomplete' => 'email',
			'placeholder'  => 'Ваша электронная почта',
		)
	);
	$message_tag = sprintf(
		'[textarea* message class:ui-field__control id:%1$s placeholder "Описание отзыва ..."]',
		sanitize_html_class( $message_field_id )
	);
	$file_tag = wipe_clean_build_cf7_file_tag(
		'review_files',
		array(
			'class'     => 'popup-upload__input',
			'id'        => $files_field_id,
			'filetypes' => 'jpg|jpeg|png|mp4',
			'limit'     => '20mb',
		)
	);

	return
		wipe_clean_build_form_context_hidden_inputs() .
		sprintf(
			'
<div class="popup-form__grid popup-form__grid--three">
	<div class="ui-field popup-form__field">
		<label class="ui-field__label" for="%1$s">Ваше имя</label>
		%2$s
	</div>
	<div class="ui-field popup-form__field">
		<label class="ui-field__label" for="%3$s">Номер телефона</label>
		%4$s
	</div>
	<div class="ui-field popup-form__field">
		<label class="ui-field__label" for="%5$s">Email</label>
		%6$s
	</div>
</div>
<div class="popup-form__grid popup-form__grid--two">
	<div class="ui-field popup-form__field">
		<label class="ui-field__label" for="%7$s">Ваш отзыв</label>
		%8$s
	</div>
	<div class="popup-upload" data-popup-upload>
		<span class="ui-field__label popup-upload__label">Фото или видео</span>
		<label class="popup-upload__zone" for="%9$s">
			%10$s
			<div class="popup-upload__empty" data-popup-upload-empty>
				<span class="popup-upload__badge">
					<svg class="popup-upload__badge-icon" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<path d="M20 35V21.6667L13.3333 28.3333" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
						<path d="M20 21.6667L26.6667 28.3333" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
						<path d="M7.32204 25.4483C5.96082 24.2576 4.89508 22.7666 4.20899 21.0932C3.5229 19.4198 3.23524 17.6099 3.36874 15.8062C3.50224 14.0026 4.05324 12.2547 4.97823 10.7005C5.90321 9.14641 7.17685 7.82861 8.69856 6.8512C10.2203 5.87379 11.9484 5.26355 13.7464 5.06867C15.5445 4.87378 17.3632 5.09959 19.059 5.72826C20.7548 6.35692 22.2812 7.37124 23.5177 8.69109C24.7542 10.0109 25.6669 11.6002 26.1837 13.3333H29.167C30.7871 13.3331 32.3638 13.8576 33.661 14.8281C34.9582 15.7987 35.9063 17.1632 36.3633 18.7175C36.8204 20.2718 36.7619 21.9323 36.1965 23.4506C35.6312 24.9688 34.5894 26.2632 33.227 27.14" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
					</svg>
				</span>
				<div class="popup-upload__copy">
					<p class="popup-upload__title">Добавьте картинку или видео</p>
					<p class="popup-upload__text">JPEG, PNG или MP4, не больше 20 МБ</p>
				</div>
			</div>
			<div class="popup-upload__preview" hidden data-popup-upload-preview></div>
		</label>
	</div>
</div>
<div class="popup-form__actions" data-form-actions>
	<div class="ui-checkbox popup-form__checkbox">%11$s</div>
	<button class="ui-btn ui-btn--primary popup-form__submit" type="submit">
		<span class="ui-btn__content">Оставить отзыв</span>
	</button>
</div>',
			esc_attr( $name_field_id ),
			$name_tag,
			esc_attr( $phone_field_id ),
			$phone_tag,
			esc_attr( $email_field_id ),
			$email_tag,
			esc_attr( $message_field_id ),
			$message_tag,
			esc_attr( $files_field_id ),
			$file_tag,
			wipe_clean_build_cf7_acceptance_tag( 'agreement', 'Заполняя форму вы даёте согласие на обработку персональных данных', array( 'class' => 'ui-checkbox__input' ) )
		);
}

function wipe_clean_get_managed_form_messages() {
	return array(
		'mail_sent_ok'     => 'Заявка отправлена. Мы свяжемся с вами в ближайшее время.',
		'mail_sent_ng'     => 'Не удалось отправить заявку. Попробуйте ещё раз или свяжитесь с нами по телефону.',
		'validation_error' => 'Проверьте заполнение полей и попробуйте снова.',
		'spam'             => 'Не удалось отправить заявку. Попробуйте ещё раз немного позже.',
		'accept_terms'     => 'Подтвердите согласие на обработку данных.',
		'invalid_required' => 'Заполните обязательное поле.',
		'invalid_too_long' => 'Проверьте значение поля.',
		'invalid_too_short'=> 'Проверьте значение поля.',
		'invalid_tel'      => 'Укажите корректный номер телефона.',
	);
}

function wipe_clean_get_managed_forms_registry() {
	static $registry = null;

	if ( null !== $registry ) {
		return $registry;
	}

	$home_hero         = wipe_clean_get_front_page_section_for_forms( 'home_hero' );
	$front_contacts    = wipe_clean_get_front_page_section_for_forms( 'contacts' );
	$contacts_hero     = wipe_clean_get_contacts_page_section_for_forms( 'contacts_hero' );
	$contact_panel     = wipe_clean_get_contact_panel_for_forms();
	$quote_request     = wipe_clean_get_quote_request_section_for_forms();
	$promotions_contact = wipe_clean_get_promotions_contacts_section_for_forms();

	$registry = array(
		'home_hero_calculator' => array(
			'title'           => 'Wipe Clean • Главная • Калькулятор',
			'location_label'  => 'Главная, первый экран',
			'page_label'      => 'Главная',
			'page_url'        => wipe_clean_get_front_page_forms_url(),
			'surface_label'   => 'Встроенная форма',
			'description'     => 'Быстрый расчёт на первом экране с выбором площади, услуги и периодичности.',
			'fields'          => array( 'Площадь', 'Услуга', 'Периодичность', 'Имя', 'Телефон', 'Согласие' ),
			'success_popup'   => 'popup-status-success',
			'error_popup'     => 'popup-status-error',
			'html_class'      => 'home-hero__form ui-form',
			'html_title'      => 'Калькулятор стоимости на главной',
			'form'            => wipe_clean_build_home_hero_form_markup( $home_hero ),
			'default_context' => array(
				'form_context_key'     => 'home_hero_calculator',
				'form_context_label'   => 'Калькулятор на первом экране',
				'form_context_page'    => 'Главная',
				'form_context_surface' => 'Первый экран',
			),
		),
		'front_contacts' => array(
			'title'           => 'Wipe Clean • Главная • Контакты',
			'location_label'  => 'Главная, секция контактов',
			'page_label'      => 'Главная',
			'page_url'        => wipe_clean_get_front_page_forms_url(),
			'surface_label'   => 'Встроенная форма',
			'description'     => 'Контактная форма в нижней секции главной страницы.',
			'fields'          => array( 'Имя', 'Телефон', 'Согласие' ),
			'success_popup'   => 'popup-status-success',
			'error_popup'     => 'popup-status-error',
			'html_class'      => 'contacts__form ui-form',
			'html_title'      => 'Контактная форма на главной',
			'form'            => wipe_clean_build_contacts_form_markup( $front_contacts, 'contacts' ),
			'default_context' => array(
				'form_context_key'     => 'front_contacts',
				'form_context_label'   => 'Контакты на главной',
				'form_context_page'    => 'Главная',
				'form_context_surface' => 'Секция контактов',
			),
		),
		'contacts_page_hero' => array(
			'title'           => 'Wipe Clean • Контакты • Первый экран',
			'location_label'  => 'Страница контактов, первый экран',
			'page_label'      => 'Контакты',
			'page_url'        => wipe_clean_get_contacts_page_forms_url(),
			'surface_label'   => 'Встроенная форма',
			'description'     => 'Основная форма связи в первом экране страницы контактов.',
			'fields'          => array( 'Имя', 'Телефон', 'Согласие' ),
			'success_popup'   => 'popup-status-success',
			'error_popup'     => 'popup-status-error',
			'html_class'      => 'contacts-hero__form ui-form',
			'html_title'      => 'Форма связи на странице контактов',
			'form'            => wipe_clean_build_contacts_form_markup( $contacts_hero, 'contacts-hero', 'contacts-hero__actions' ),
			'default_context' => array(
				'form_context_key'     => 'contacts_page_hero',
				'form_context_label'   => 'Форма на странице контактов',
				'form_context_page'    => 'Контакты',
				'form_context_surface' => 'Первый экран',
			),
		),
		'contact_panel' => array(
			'title'           => 'Wipe Clean • Контактная панель',
			'location_label'  => 'Документы и 404',
			'page_label'      => 'Документы / 404',
			'page_url'        => wipe_clean_get_policy_page_forms_url(),
			'surface_label'   => 'Встроенная форма',
			'description'     => 'Единая форма в общей контактной панели для документов и страницы 404.',
			'fields'          => array( 'Имя', 'Телефон', 'Согласие' ),
			'success_popup'   => 'popup-status-success',
			'error_popup'     => 'popup-status-error',
			'html_class'      => 'contact-panel__form ui-form',
			'html_title'      => 'Контактная панель',
			'form'            => wipe_clean_build_contacts_form_markup( $contact_panel, 'contact-panel' ),
			'default_context' => array(
				'form_context_key'     => 'contact_panel',
				'form_context_label'   => 'Контактная панель',
				'form_context_page'    => 'Документы / 404',
				'form_context_surface' => 'Контактный блок',
			),
		),
		'quote_request' => array(
			'title'           => 'Wipe Clean • Главная • Запрос расчёта',
			'location_label'  => 'Главная, блок с запросом расчёта',
			'page_label'      => 'Главная',
			'page_url'        => wipe_clean_get_front_page_forms_url(),
			'surface_label'   => 'Встроенная форма',
			'description'     => 'Форма заявки в блоке под секцией этапов работы.',
			'fields'          => array( 'Имя', 'Телефон', 'Согласие' ),
			'success_popup'   => 'popup-status-success',
			'error_popup'     => 'popup-status-error',
			'html_class'      => 'quote-request__form ui-form',
			'html_title'      => 'Запрос расчёта на главной',
			'form'            => wipe_clean_build_quote_request_form_markup( $quote_request ),
			'default_context' => array(
				'form_context_key'     => 'quote_request',
				'form_context_label'   => 'Запрос расчёта на главной',
				'form_context_page'    => 'Главная',
				'form_context_surface' => 'Блок заявки',
			),
		),
		'promotions_contacts' => array(
			'title'           => 'Wipe Clean • Акции • Контакты',
			'location_label'  => 'Страница акций, секция контактов',
			'page_label'      => 'Акции',
			'page_url'        => wipe_clean_get_promotions_page_forms_url(),
			'surface_label'   => 'Встроенная форма',
			'description'     => 'Форма связи внизу страницы акций.',
			'fields'          => array( 'Имя', 'Телефон', 'Согласие' ),
			'success_popup'   => 'popup-status-success',
			'error_popup'     => 'popup-status-error',
			'html_class'      => 'contacts__form ui-form',
			'html_title'      => 'Форма связи на странице акций',
			'form'            => wipe_clean_build_contacts_form_markup( $promotions_contact, 'contacts' ),
			'default_context' => array(
				'form_context_key'     => 'promotions_contacts',
				'form_context_label'   => 'Контакты на странице акций',
				'form_context_page'    => 'Акции',
				'form_context_surface' => 'Секция контактов',
			),
		),
		'promotion_popup' => array(
			'title'           => 'Wipe Clean • Акции • Попап',
			'location_label'  => 'Страница акций, попап акции',
			'page_label'      => 'Акции',
			'page_url'        => wipe_clean_get_promotions_page_forms_url(),
			'surface_label'   => 'Попап',
			'description'     => 'Форма внутри попапов карточек акций.',
			'fields'          => array( 'Имя', 'Телефон', 'Согласие', 'Название акции' ),
			'success_popup'   => 'popup-status-success',
			'error_popup'     => 'popup-status-error',
			'html_class'      => 'popup-form__form ui-form',
			'html_title'      => 'Форма в попапе акции',
			'form'            => wipe_clean_build_popup_form_markup(),
			'default_context' => array(
				'form_context_key'     => 'promotion_popup',
				'form_context_label'   => 'Попап акции',
				'form_context_page'    => 'Акции',
				'form_context_surface' => 'Попап',
				'promotion_title'      => '',
				'popup_id'             => '',
			),
		),
		'popup_order_service' => array(
			'title'           => 'Wipe Clean • Popup • Заказать услугу',
			'location_label'  => 'Попап заказа услуги',
			'page_label'      => 'Весь сайт',
			'page_url'        => home_url( '/' ),
			'surface_label'   => 'Попап',
			'description'     => 'Глобальный попап для заказа услуги с любой страницы сайта.',
			'fields'          => array( 'Имя', 'Телефон', 'Email', 'Услуга', 'Согласие' ),
			'success_popup'   => 'popup-status-success',
			'error_popup'     => 'popup-status-error',
			'html_class'      => 'popup-form__form ui-form',
			'html_title'      => 'Попап заказа услуги',
			'form'            => wipe_clean_build_popup_order_service_form_markup( $home_hero ),
			'default_context' => array(
				'form_context_key'     => 'popup_order_service',
				'form_context_label'   => 'Попап заказа услуги',
				'form_context_page'    => 'Сайт',
				'form_context_surface' => 'Попап',
			),
		),
		'popup_calc' => array(
			'title'           => 'Wipe Clean • Popup • Рассчитать стоимость',
			'location_label'  => 'Попап расчёта стоимости',
			'page_label'      => 'Весь сайт',
			'page_url'        => home_url( '/' ),
			'surface_label'   => 'Попап',
			'description'     => 'Глобальный попап быстрого расчёта стоимости уборки.',
			'fields'          => array( 'Площадь', 'Услуга', 'Регулярность', 'Имя', 'Телефон', 'Согласие' ),
			'success_popup'   => 'popup-status-success',
			'error_popup'     => 'popup-status-error',
			'html_class'      => 'popup-form__form ui-form',
			'html_title'      => 'Попап расчёта стоимости',
			'form'            => wipe_clean_build_popup_calc_form_markup( $home_hero ),
			'default_context' => array(
				'form_context_key'     => 'popup_calc',
				'form_context_label'   => 'Попап расчёта стоимости',
				'form_context_page'    => 'Сайт',
				'form_context_surface' => 'Попап',
			),
		),
		'popup_question' => array(
			'title'           => 'Wipe Clean • Popup • Задать вопрос',
			'location_label'  => 'Попап вопроса',
			'page_label'      => 'Весь сайт',
			'page_url'        => home_url( '/' ),
			'surface_label'   => 'Попап',
			'description'     => 'Глобальный попап для вопросов по услугам, условиям и стоимости.',
			'fields'          => array( 'Имя', 'Телефон', 'Email', 'Вопрос', 'Согласие' ),
			'success_popup'   => 'popup-status-success',
			'error_popup'     => 'popup-status-error',
			'html_class'      => 'popup-form__form ui-form',
			'html_title'      => 'Попап вопроса',
			'form'            => wipe_clean_build_popup_question_form_markup(),
			'message_label'   => 'Вопрос',
			'default_context' => array(
				'form_context_key'     => 'popup_question',
				'form_context_label'   => 'Попап вопроса',
				'form_context_page'    => 'Сайт',
				'form_context_surface' => 'Попап',
			),
		),
		'popup_review' => array(
			'title'                => 'Wipe Clean • Popup • Отзыв',
			'location_label'       => 'Попап отзыва',
			'page_label'           => 'Весь сайт',
			'page_url'             => home_url( '/' ),
			'surface_label'        => 'Попап',
			'description'          => 'Глобальный попап для нового отзыва. После отправки создаётся черновик в разделе «Отзывы».',
			'fields'               => array( 'Имя', 'Телефон', 'Email', 'Отзыв', 'Фото / видео', 'Согласие' ),
			'success_popup'        => 'popup-status-success',
			'error_popup'          => 'popup-status-error',
			'html_class'           => 'popup-form__form ui-form',
			'html_title'           => 'Попап отзыва',
			'form'                 => wipe_clean_build_popup_review_form_markup(),
			'message_label'        => 'Отзыв',
			'files_field_name'     => 'review_files',
			'files_label'          => 'Файлы',
			'mail_attachments'     => '[review_files]',
			'notification_badge'   => 'Новый отзыв',
			'notification_subject' => 'Новый отзыв',
			'default_context'      => array(
				'form_context_key'     => 'popup_review',
				'form_context_label'   => 'Попап отзыва',
				'form_context_page'    => 'Сайт',
				'form_context_surface' => 'Попап',
			),
		),
	);

	return $registry;
}

function wipe_clean_get_managed_form_map() {
	$map = get_option( wipe_clean_get_managed_forms_map_option_name(), array() );

	return is_array( $map ) ? $map : array();
}

function wipe_clean_get_managed_form_id( $form_key ) {
	$map = wipe_clean_get_managed_form_map();

	return ! empty( $map[ $form_key ] ) ? (int) $map[ $form_key ] : 0;
}

function wipe_clean_get_managed_form_config_by_contact_form( $contact_form ) {
	$contact_form = $contact_form instanceof WPCF7_ContactForm ? $contact_form : WPCF7_ContactForm::get_instance( $contact_form );

	if ( ! $contact_form instanceof WPCF7_ContactForm ) {
		return array();
	}

	$form_key = (string) get_post_meta( $contact_form->id(), wipe_clean_get_managed_form_meta_key(), true );
	$registry = wipe_clean_get_managed_forms_registry();

	return $registry[ $form_key ] ?? array();
}

function wipe_clean_get_managed_form_key_by_contact_form( $contact_form ) {
	$contact_form = $contact_form instanceof WPCF7_ContactForm ? $contact_form : WPCF7_ContactForm::get_instance( $contact_form );

	if ( ! $contact_form instanceof WPCF7_ContactForm ) {
		return '';
	}

	return (string) get_post_meta( $contact_form->id(), wipe_clean_get_managed_form_meta_key(), true );
}

function wipe_clean_get_managed_mail_template( $config ) {
	$template                 = WPCF7_ContactFormTemplate::get_default( 'mail' );
	$template['subject']      = 'Новая заявка: ' . (string) ( $config['location_label'] ?? $config['title'] ?? 'Wipe Clean' );
	$template['recipient']    = '[_site_admin_email]';
	$template['body']         = wipe_clean_build_managed_email_preview_html( $config );
	$template['attachments']  = '';
	$template['exclude_blank'] = 0;
	$template['use_html']     = 1;
	$template['additional_headers'] = '';
	$subject_prefix           = trim( (string) ( $config['notification_subject'] ?? 'Новая заявка' ) );
	$location_label           = (string) ( $config['location_label'] ?? $config['title'] ?? 'Wipe Clean' );
	$template['subject']      = $subject_prefix . ': ' . $location_label;
	$template['attachments']  = trim( (string) ( $config['mail_attachments'] ?? '' ) );

	return $template;
}

function wipe_clean_get_managed_form_sync_hash( $config ) {
	$hash_source = array(
		'title'               => (string) ( $config['title'] ?? '' ),
		'form'                => (string) ( $config['form'] ?? '' ),
		'html_class'          => (string) ( $config['html_class'] ?? '' ),
		'html_title'          => (string) ( $config['html_title'] ?? '' ),
		'success_popup'       => (string) ( $config['success_popup'] ?? '' ),
		'error_popup'         => (string) ( $config['error_popup'] ?? '' ),
		'default_context'     => (array) ( $config['default_context'] ?? array() ),
		'mail'                => wipe_clean_get_managed_mail_template( $config ),
		'messages'            => wipe_clean_get_managed_form_messages(),
		'additional_settings' => "acceptance_as_validation: on\n",
	);

	return md5( wp_json_encode( $hash_source ) ?: '' );
}

function wipe_clean_select_managed_contact_form( $forms, $preferred_id = 0 ) {
	$forms = array_values(
		array_filter(
			(array) $forms,
			static function ( $form ) {
				return $form instanceof WPCF7_ContactForm;
			}
		)
	);

	if ( empty( $forms ) ) {
		return null;
	}

	if ( $preferred_id > 0 ) {
		foreach ( $forms as $form ) {
			if ( $preferred_id === (int) $form->id() ) {
				return $form;
			}
		}
	}

	foreach ( $forms as $form ) {
		if ( 'trash' !== get_post_status( $form->id() ) ) {
			return $form;
		}
	}

	return $forms[0];
}

function wipe_clean_cleanup_duplicate_managed_forms( $forms, $keep_form_id ) {
	$keep_form_id = (int) $keep_form_id;

	if ( $keep_form_id <= 0 ) {
		return;
	}

	foreach ( (array) $forms as $form ) {
		if ( ! $form instanceof WPCF7_ContactForm ) {
			continue;
		}

		$form_id = (int) $form->id();

		if ( $form_id <= 0 || $keep_form_id === $form_id ) {
			continue;
		}

		delete_post_meta( $form_id, wipe_clean_get_managed_form_meta_key() );
		delete_post_meta( $form_id, wipe_clean_get_managed_form_hash_meta_key() );

		if ( 'trash' !== get_post_status( $form_id ) ) {
			wp_trash_post( $form_id );
		}
	}
}

function wipe_clean_sync_managed_cf7_forms( $force = false ) {
	static $is_running = false;
	static $has_synced = false;

	if ( $is_running || ! wipe_clean_is_cf7_available() ) {
		return;
	}

	if ( $has_synced && ! $force ) {
		return;
	}

	$is_running = true;
	try {
		$registry      = wipe_clean_get_managed_forms_registry();
		$existing_map  = wipe_clean_get_managed_form_map();
		$map           = array();

		foreach ( $registry as $form_key => $config ) {
			$existing = WPCF7_ContactForm::find(
				array(
					'post_status'    => 'any',
					'posts_per_page' => -1,
					'meta_key'       => wipe_clean_get_managed_form_meta_key(),
					'meta_value'     => $form_key,
				)
			);
			$form     = wipe_clean_select_managed_contact_form(
				$existing,
				(int) ( $existing_map[ $form_key ] ?? 0 )
			);
			$form     = $form instanceof WPCF7_ContactForm
				? $form
				: WPCF7_ContactForm::get_template(
					array(
						'title'  => (string) ( $config['title'] ?? $form_key ),
						'locale' => determine_locale(),
					)
				);

			$hash          = wipe_clean_get_managed_form_sync_hash( $config );
			$current_hash  = ! empty( $form->id() ) ? (string) get_post_meta( $form->id(), wipe_clean_get_managed_form_hash_meta_key(), true ) : '';
			$should_update = $force || $form->initial() || $hash !== $current_hash || (string) $form->title() !== (string) ( $config['title'] ?? '' );

			if ( $should_update ) {
				$form->set_title( (string) ( $config['title'] ?? $form_key ) );
				$form->set_locale( determine_locale() );
				$form->set_properties(
					array(
						'form'                => (string) ( $config['form'] ?? '' ),
						'mail'                => wipe_clean_get_managed_mail_template( $config ),
						'mail_2'              => WPCF7_ContactFormTemplate::get_default( 'mail_2' ),
						'messages'            => wipe_clean_get_managed_form_messages(),
						'additional_settings' => "acceptance_as_validation: on\n",
					)
				);

				$form_id = (int) $form->save();

				if ( $form_id ) {
					update_post_meta( $form_id, wipe_clean_get_managed_form_meta_key(), $form_key );
					update_post_meta( $form_id, wipe_clean_get_managed_form_hash_meta_key(), $hash );
				}
			}

			if ( $form->id() ) {
				wipe_clean_cleanup_duplicate_managed_forms( $existing, (int) $form->id() );
				$map[ $form_key ] = (int) $form->id();
			}
		}

		update_option( wipe_clean_get_managed_forms_map_option_name(), $map, false );
		update_option( wipe_clean_get_managed_forms_last_sync_option_name(), current_time( 'mysql' ), false );
		$has_synced = true;
	} finally {
		$is_running = false;
	}
}
add_action( 'init', 'wipe_clean_sync_managed_cf7_forms', 20 );

function wipe_clean_get_current_managed_form_render_context() {
	return $GLOBALS['wipe_clean_managed_form_render_context'] ?? null;
}

function wipe_clean_set_current_managed_form_render_context( $context = null ) {
	if ( null === $context ) {
		unset( $GLOBALS['wipe_clean_managed_form_render_context'] );

		return null;
	}

	$GLOBALS['wipe_clean_managed_form_render_context'] = is_array( $context ) ? $context : array();

	return $GLOBALS['wipe_clean_managed_form_render_context'];
}

function wipe_clean_render_managed_cf7_form( $form_key, $args = array() ) {
	if ( ! wipe_clean_is_cf7_available() ) {
		return current_user_can( 'manage_options' )
			? '<div class="ui-form-status ui-form-status--error">Contact Form 7 не найден. Проверьте активность плагина.</div>'
			: '';
	}

	wipe_clean_sync_managed_cf7_forms();

	$registry = wipe_clean_get_managed_forms_registry();
	$config   = $registry[ $form_key ] ?? array();
	$form_id  = wipe_clean_get_managed_form_id( $form_key );

	if ( ! $form_id || empty( $config ) ) {
		return '';
	}

	$form = WPCF7_ContactForm::get_instance( $form_id );

	if ( ! $form instanceof WPCF7_ContactForm ) {
		return '';
	}

	$html_class = trim(
		implode(
			' ',
			array_filter(
				array(
					'wipe-clean-managed-form',
					(string) ( $config['html_class'] ?? '' ),
					(string) ( $args['html_class'] ?? '' ),
				)
			)
		)
	);

	$render_context = array_merge(
		(array) ( $config['default_context'] ?? array() ),
		(array) ( $args['render_context'] ?? array() ),
		array(
			'form_key'      => $form_key,
			'success_popup' => (string) ( $args['success_popup'] ?? ( $config['success_popup'] ?? 'popup-status-success' ) ),
			'error_popup'   => (string) ( $args['error_popup'] ?? ( $config['error_popup'] ?? 'popup-status-error' ) ),
		)
	);

	wipe_clean_set_current_managed_form_render_context( $render_context );

	try {
		return $form->form_html(
			array(
				'html_class' => $html_class,
				'html_title' => (string) ( $args['html_title'] ?? ( $config['html_title'] ?? '' ) ),
				'html_id'    => (string) ( $args['html_id'] ?? '' ),
				'html_name'  => (string) ( $args['html_name'] ?? '' ),
			)
		);
	} finally {
		wipe_clean_set_current_managed_form_render_context();
	}
}

function wipe_clean_filter_managed_form_additional_atts( $atts ) {
	$context = wipe_clean_get_current_managed_form_render_context();

	if ( empty( $context['form_key'] ) ) {
		return $atts;
	}

	$atts['data-managed-form']      = 'true';
	$atts['data-form-key']          = (string) $context['form_key'];
	$atts['data-form-success-popup'] = (string) ( $context['success_popup'] ?? 'popup-status-success' );
	$atts['data-form-error-popup']  = (string) ( $context['error_popup'] ?? 'popup-status-error' );

	return $atts;
}
add_filter( 'wpcf7_form_additional_atts', 'wipe_clean_filter_managed_form_additional_atts' );

function wipe_clean_prepare_popup_review_file_input_markup( $elements ) {
	if ( false === strpos( $elements, 'id="popup-review-files"' ) ) {
		return $elements;
	}

	$prepared = preg_replace_callback(
		'/<input\b[^>]*\bid=(["\'])popup-review-files\1[^>]*>/i',
		static function ( $matches ) {
			$input_html = $matches[0];

			if ( false === stripos( $input_html, ' hidden' ) ) {
				$input_html = preg_replace( '/\s*\/?>$/', ' hidden$0', $input_html, 1 );
			}

			if ( false === stripos( $input_html, ' data-popup-upload-input' ) ) {
				$input_html = preg_replace( '/\s*\/?>$/', ' data-popup-upload-input$0', $input_html, 1 );
			}

			if ( false === stripos( $input_html, ' tabindex=' ) ) {
				$input_html = preg_replace( '/\s*\/?>$/', ' tabindex="-1"$0', $input_html, 1 );
			}

			if ( false === stripos( $input_html, ' accept=' ) ) {
				$input_html = preg_replace( '/\s*\/?>$/', ' accept=".jpg,.jpeg,.png,.mp4"$0', $input_html, 1 );
			}

			return $input_html;
		},
		$elements,
		1
	);

	return is_string( $prepared ) ? $prepared : $elements;
}

function wipe_clean_filter_managed_form_elements( $elements ) {
	$context = wipe_clean_get_current_managed_form_render_context();

	if ( empty( $context['form_key'] ) ) {
		return $elements;
	}

	$replacements = array();

	foreach ( $context as $key => $value ) {
		$replacements[ '{{' . $key . '}}' ] = esc_attr( (string) $value );
	}

	$elements = strtr( $elements, $replacements );

	if ( 'popup_review' === (string) $context['form_key'] ) {
		$elements = wipe_clean_prepare_popup_review_file_input_markup( $elements );
	}

	return $elements;
}
add_filter( 'wpcf7_form_elements', 'wipe_clean_filter_managed_form_elements' );

function wipe_clean_disable_managed_cf7_autop( $autop, $options = array() ) {
	$options = wp_parse_args(
		(array) $options,
		array(
			'for' => 'form',
		)
	);

	if ( 'form' !== $options['for'] ) {
		return $autop;
	}

	$context = wipe_clean_get_current_managed_form_render_context();

	if ( ! empty( $context['form_key'] ) ) {
		return false;
	}

	$current_form = class_exists( 'WPCF7_ContactForm' ) ? WPCF7_ContactForm::get_current() : null;

	if ( $current_form instanceof WPCF7_ContactForm && ! empty( wipe_clean_get_managed_form_config_by_contact_form( $current_form ) ) ) {
		return false;
	}

	return $autop;
}
add_filter( 'wpcf7_autop_or_not', 'wipe_clean_disable_managed_cf7_autop', 20, 2 );

function wipe_clean_is_captureable_submission_status( $status ) {
	return in_array( (string) $status, array( 'mail_sent', 'mail_failed', 'aborted' ), true );
}

function wipe_clean_clean_lead_value( $value ) {
	if ( is_array( $value ) ) {
		$value = implode( ', ', array_map( 'wipe_clean_clean_lead_value', $value ) );
	}

	return trim( wp_strip_all_tags( (string) $value ) );
}

function wipe_clean_normalize_submission_payload_value( $value ) {
	if ( is_array( $value ) ) {
		$items = array_values(
			array_filter(
				array_map( 'wipe_clean_clean_lead_value', $value ),
				'strlen'
			)
		);

		return ! empty( $items ) ? $items : '';
	}

	return wipe_clean_clean_lead_value( $value );
}

function wipe_clean_get_uploaded_file_names( $submission, $field_name = '' ) {
	if ( ! $submission instanceof WPCF7_Submission ) {
		return array();
	}

	$uploaded_files = (array) $submission->uploaded_files();
	$field_name     = sanitize_key( (string) $field_name );

	if ( '' !== $field_name ) {
		$uploaded_files = array_intersect_key(
			$uploaded_files,
			array(
				$field_name => true,
			)
		);
	}

	$names = array();

	foreach ( $uploaded_files as $paths ) {
		foreach ( (array) $paths as $path ) {
			$filename = wp_basename( (string) $path );

			if ( '' !== $filename ) {
				$names[] = $filename;
			}
		}
	}

	return array_values( array_unique( $names ) );
}

function wipe_clean_get_managed_submission_payload( $submission ) {
	if ( ! $submission instanceof WPCF7_Submission ) {
		return array();
	}

	$posted_data = (array) $submission->get_posted_data();
	$payload     = array();

	foreach ( $posted_data as $key => $value ) {
		$payload[ $key ] = wipe_clean_normalize_submission_payload_value( $value );
	}

	foreach ( (array) $submission->uploaded_files() as $key => $paths ) {
		$names = array_values(
			array_filter(
				array_map( 'wp_basename', (array) $paths ),
				'strlen'
			)
		);

		if ( ! empty( $names ) ) {
			$payload[ $key ] = $names;
		}
	}

	return $payload;
}

function wipe_clean_get_managed_submission_core_fields( $payload ) {
	return array(
		'name'             => wipe_clean_clean_lead_value( $payload['name'] ?? '' ),
		'phone'            => wipe_clean_clean_lead_value( $payload['phone'] ?? '' ),
		'email'            => wipe_clean_clean_lead_value( $payload['email'] ?? '' ),
		'message'          => wipe_clean_clean_lead_value( $payload['message'] ?? '' ),
		'review_files'     => wipe_clean_normalize_submission_payload_value( $payload['review_files'] ?? '' ),
		'form_context_key' => wipe_clean_clean_lead_value( $payload['form_context_key'] ?? '' ),
		'context_label'    => wipe_clean_clean_lead_value( $payload['form_context_label'] ?? '' ),
		'page_label'       => wipe_clean_clean_lead_value( $payload['form_context_page'] ?? '' ),
		'surface_label'    => wipe_clean_clean_lead_value( $payload['form_context_surface'] ?? '' ),
		'promotion_title'  => wipe_clean_clean_lead_value( $payload['promotion_title'] ?? '' ),
		'area'             => wipe_clean_clean_lead_value( $payload['area'] ?? '' ),
		'service'          => wipe_clean_clean_lead_value( $payload['service'] ?? '' ),
		'frequency'        => wipe_clean_clean_lead_value( $payload['frequency'] ?? '' ),
	);
}

function wipe_clean_build_email_detail_rows( $config, $core_fields, $submission ) {
	$rows = array(
		'Форма'    => (string) ( $core_fields['context_label'] ?: ( $config['location_label'] ?? '' ) ),
		'Страница' => (string) ( $core_fields['page_label'] ?: ( $config['page_label'] ?? '' ) ),
		'Раздел'   => (string) ( $core_fields['surface_label'] ?: ( $config['surface_label'] ?? '' ) ),
		'Имя'      => (string) $core_fields['name'],
		'Телефон'  => (string) $core_fields['phone'],
	);

	if ( '' !== $core_fields['email'] ) {
		$rows['Email'] = (string) $core_fields['email'];
	}

	if ( '' !== $core_fields['promotion_title'] ) {
		$rows['Акция'] = (string) $core_fields['promotion_title'];
	}

	if ( '' !== $core_fields['area'] ) {
		$rows['Площадь'] = (string) $core_fields['area'];
	}

	if ( '' !== $core_fields['service'] ) {
		$rows['Услуга'] = (string) $core_fields['service'];
	}

	if ( '' !== $core_fields['frequency'] ) {
		$rows['Периодичность'] = (string) $core_fields['frequency'];
	}

	if ( '' !== $core_fields['message'] ) {
		$rows[ (string) ( $config['message_label'] ?? 'Сообщение' ) ] = (string) $core_fields['message'];
	}

	if ( ! empty( $core_fields['review_files'] ) ) {
		$rows[ (string) ( $config['files_label'] ?? 'Файлы' ) ] = wipe_clean_clean_lead_value( $core_fields['review_files'] );
	}

	if ( $submission instanceof WPCF7_Submission ) {
		$submitted_url = wipe_clean_clean_lead_value( $submission->get_meta( 'url' ) );
		$remote_ip     = wipe_clean_clean_lead_value( $submission->get_meta( 'remote_ip' ) );

		if ( '' !== $submitted_url ) {
			$rows['URL'] = $submitted_url;
		}

		if ( '' !== $remote_ip ) {
			$rows['IP'] = $remote_ip;
		}
	}

	return array_filter(
		$rows,
		static function ( $value ) {
			return '' !== trim( (string) $value );
		}
	);
}

function wipe_clean_format_email_detail_value( $label, $value ) {
	$label = (string) $label;
	$value = trim( (string) $value );

	if ( '' === $value ) {
		return '';
	}

	$escaped_value = esc_html( $value );
	$formatted     = nl2br( $escaped_value );

	if ( 'Email' === $label && is_email( $value ) ) {
		return sprintf(
			'<a href="mailto:%1$s" style="color:#017096;text-decoration:none;">%2$s</a>',
			esc_attr( $value ),
			$formatted
		);
	}

	if ( 'Телефон' === $label ) {
		$phone_href = preg_replace( '/[^\d\+]+/', '', $value );

		if ( '' !== $phone_href ) {
			return sprintf(
				'<a href="tel:%1$s" style="color:#017096;text-decoration:none;">%2$s</a>',
				esc_attr( $phone_href ),
				$formatted
			);
		}
	}

	if ( 'URL' === $label && wp_http_validate_url( $value ) ) {
		return sprintf(
			'<a href="%1$s" style="color:#017096;text-decoration:none;word-break:break-all;">%2$s</a>',
			esc_url( $value ),
			$formatted
		);
	}

	return sprintf(
		'<span style="display:block;white-space:normal;word-break:break-word;">%s</span>',
		$formatted
	);
}

function wipe_clean_render_email_rows_html( $rows ) {
	$html = '';
	$index = 0;

	foreach ( (array) $rows as $label => $value ) {
		if ( '' === trim( (string) $value ) ) {
			continue;
		}

		$background = 0 === $index % 2 ? '#FFFFFF' : '#F7FBFA';

		$html .= sprintf(
			'<tr><td valign="top" bgcolor="%3$s" style="padding:15px 18px;border-bottom:1px solid #DCE9E4;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:1.4;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#6A6880;width:190px;">%1$s</td><td valign="top" bgcolor="%3$s" style="padding:15px 18px;border-bottom:1px solid #DCE9E4;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.6;color:#1F1A36;">%2$s</td></tr>',
			esc_html( $label ),
			wipe_clean_format_email_detail_value( $label, $value ),
			esc_attr( $background )
		);

		++$index;
	}

	return $html;
}

function wipe_clean_build_managed_email_markup( $args = array() ) {
	$args = wp_parse_args(
		(array) $args,
		array(
			'preheader'  => 'Новая заявка с сайта Wipe Clean.',
			'eyebrow'    => 'Wipe Clean',
			'badge'      => 'Новая заявка',
			'headline'   => 'Новая заявка',
			'intro'      => 'Мы получили новое обращение с сайта.',
			'rows_html'  => '',
			'meta_label' => 'Время получения',
			'meta_value' => current_time( 'd.m.Y H:i' ),
			'footer'     => 'Письмо сформировано автоматически темой сайта Wipe Clean.',
		)
	);

	return sprintf(
		'<!doctype html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="x-apple-disable-message-reformatting">
	<title>%1$s</title>
</head>
<body style="margin:0;padding:0;background-color:#F6F1E8;">
	<div style="display:none;max-height:0;overflow:hidden;opacity:0;mso-hide:all;font-size:1px;line-height:1px;color:#F6F1E8;">%2$s</div>
	<table role="presentation" width="100%%" cellspacing="0" cellpadding="0" border="0" style="width:100%%;margin:0;padding:0;border-collapse:collapse;background-color:#F6F1E8;mso-table-lspace:0pt;mso-table-rspace:0pt;">
		<tr>
			<td align="center" style="padding:28px 14px;">
				<table role="presentation" width="100%%" cellspacing="0" cellpadding="0" border="0" style="width:100%%;max-width:680px;border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;">
					<tr>
						<td style="background-color:#150F31;border-radius:28px 28px 0 0;padding:22px 28px 18px;">
							<table role="presentation" width="100%%" cellspacing="0" cellpadding="0" border="0" style="width:100%%;border-collapse:collapse;">
								<tr>
									<td align="left" valign="middle">
										<div style="font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:1.2;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#8AD7C4;">%3$s</div>
									</td>
									<td align="right" valign="middle">
										<span style="display:inline-block;padding:8px 14px;border-radius:999px;background-color:#28B789;font-family:Arial,Helvetica,sans-serif;font-size:11px;line-height:1.1;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#FFFFFF;">%4$s</span>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td style="border:1px solid #DCE9E4;border-top:0;border-radius:0 0 28px 28px;background-color:#FFFFFF;padding:0;">
							<table role="presentation" width="100%%" cellspacing="0" cellpadding="0" border="0" style="width:100%%;border-collapse:collapse;">
								<tr>
									<td style="padding:28px 28px 10px;">
										<h1 style="margin:0 0 12px;font-family:Arial,Helvetica,sans-serif;font-size:28px;line-height:1.15;font-weight:700;color:#150F31;">%5$s</h1>
										<p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.7;color:#4A4760;">%6$s</p>
									</td>
								</tr>
								<tr>
									<td style="padding:0 28px 24px;">
										<table role="presentation" width="100%%" cellspacing="0" cellpadding="0" border="0" style="width:100%%;border-collapse:separate;border-spacing:0;border:1px solid #DCE9E4;border-radius:22px;overflow:hidden;background-color:#F7FBFA;">
											%7$s
										</table>
									</td>
								</tr>
								<tr>
									<td style="padding:0 28px 18px;">
										<table role="presentation" width="100%%" cellspacing="0" cellpadding="0" border="0" style="width:100%%;border-collapse:collapse;background-color:#F4EFE6;border-radius:20px;">
											<tr>
												<td style="padding:16px 18px;">
													<div style="font-family:Arial,Helvetica,sans-serif;font-size:11px;line-height:1.2;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#7A748F;">%8$s</div>
													<div style="padding-top:8px;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.6;color:#1F1A36;">%9$s</div>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td style="padding:0 28px 28px;">
										<p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:1.7;color:#6A6880;">%10$s</p>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>',
		esc_html( (string) $args['headline'] ),
		esc_html( (string) $args['preheader'] ),
		esc_html( (string) $args['eyebrow'] ),
		esc_html( (string) $args['badge'] ),
		esc_html( (string) $args['headline'] ),
		esc_html( (string) $args['intro'] ),
		(string) $args['rows_html'],
		esc_html( (string) $args['meta_label'] ),
		esc_html( (string) $args['meta_value'] ),
		esc_html( (string) $args['footer'] )
	);
}

function wipe_clean_build_managed_email_preview_html( $config ) {
	$headline = (string) ( $config['location_label'] ?? $config['title'] ?? 'Новая заявка' );
	$intro    = 'Так будет выглядеть письмо по этой форме. Финальная версия собирается темой автоматически и подставляет реальные значения заявки.';
	$rows     = array(
		'Форма'         => '[form_context_label]',
		'Страница'      => '[form_context_page]',
		'Раздел'        => '[form_context_surface]',
		'Имя'           => '[name]',
		'Телефон'       => '[phone]',
		'Акция'         => '[promotion_title]',
		'Площадь'       => '[area]',
		'Услуга'        => '[service]',
		'Периодичность' => '[frequency]',
		'URL'           => '[_url]',
		'IP'            => '[_remote_ip]',
	);

	$rows['Email'] = '[email]';

	if ( ! empty( $config['message_label'] ) ) {
		$rows[ (string) $config['message_label'] ] = '[message]';
	}

	if ( ! empty( $config['files_field_name'] ) ) {
		$rows[ (string) ( $config['files_label'] ?? 'Файлы' ) ] = '[' . sanitize_key( (string) $config['files_field_name'] ) . ']';
	}

	return wipe_clean_build_managed_email_markup(
		array(
			'preheader'  => 'Новая заявка по форме ' . $headline . '.',
			'badge'      => (string) ( $config['notification_badge'] ?? 'Новая заявка' ),
			'headline'   => $headline,
			'intro'      => $intro,
			'rows_html'  => wipe_clean_render_email_rows_html( $rows ),
			'meta_label' => 'Пример времени',
			'meta_value' => '[_date] [_time]',
			'footer'     => 'SMTP и адреса получателей настраиваются отдельно. Внешний вид письма управляется темой Wipe Clean.',
		)
	);
}

function wipe_clean_build_managed_email_subject( $config, $core_fields ) {
	$subject_prefix = trim( (string) ( $config['notification_subject'] ?? $config['notification_badge'] ?? 'Новая заявка' ) );
	$subject        = trim( (string) ( $core_fields['context_label'] ?: ( $config['location_label'] ?? $config['title'] ?? 'Wipe Clean' ) ) );

	if ( '' !== $core_fields['promotion_title'] ) {
		$subject .= ' • ' . $core_fields['promotion_title'];
	}

	return $subject_prefix . ': ' . $subject;
}

function wipe_clean_build_managed_email_html( $config, $core_fields, $submission ) {
	$rows         = wipe_clean_build_email_detail_rows( $config, $core_fields, $submission );
	$section_name = (string) ( $core_fields['context_label'] ?: ( $config['location_label'] ?? 'Заявка с сайта' ) );
	$page_name    = (string) ( $core_fields['page_label'] ?: ( $config['page_label'] ?? '' ) );
	$badge        = trim( (string) ( $config['notification_badge'] ?? $config['notification_subject'] ?? 'Новая заявка' ) );
	$submitted_at = current_time( 'd.m.Y H:i' );

	return wipe_clean_build_managed_email_markup(
		array(
			'preheader'  => 'Новая заявка по форме ' . $section_name . '.',
			'badge'      => $badge,
			'headline'   => $section_name,
			'intro'      => '' !== $page_name ? 'Страница: ' . $page_name : 'Новое обращение из формы сайта Wipe Clean.',
			'rows_html'  => wipe_clean_render_email_rows_html( $rows ),
			'meta_label' => 'Время получения',
			'meta_value' => $submitted_at,
			'footer'     => 'Письмо сформировано автоматически темой сайта Wipe Clean. Заявка также сохранена в журнале обращений WordPress.',
		)
	);
}

function wipe_clean_filter_managed_mail_components( $components, $contact_form, $mail ) {
	if ( ! $contact_form instanceof WPCF7_ContactForm || ! $mail instanceof WPCF7_Mail ) {
		return $components;
	}

	if ( 'mail' !== $mail->get_template_name() ) {
		return $components;
	}

	$config = wipe_clean_get_managed_form_config_by_contact_form( $contact_form );

	if ( empty( $config ) ) {
		return $components;
	}

	$submission = WPCF7_Submission::get_instance();
	$payload    = wipe_clean_get_managed_submission_payload( $submission );
	$core       = wipe_clean_get_managed_submission_core_fields( $payload );
	$recipients = wipe_clean_get_notification_recipient_emails();

	if ( ! empty( $recipients ) ) {
		$components['recipient'] = implode( ', ', $recipients );
	}

	$components['subject']            = wipe_clean_build_managed_email_subject( $config, $core );
	$components['body']               = wipe_clean_build_managed_email_html( $config, $core, $submission );
	$components['additional_headers'] = trim( (string) ( $components['additional_headers'] ?? '' ) );

	return $components;
}
add_filter( 'wpcf7_mail_components', 'wipe_clean_filter_managed_mail_components', 20, 3 );

function wipe_clean_escape_telegram_html( $value ) {
	return htmlspecialchars( (string) $value, ENT_QUOTES, 'UTF-8' );
}

function wipe_clean_build_telegram_message( $config, $core_fields, $submission ) {
	$lines = array(
		'<b>' . wipe_clean_escape_telegram_html( (string) ( $config['notification_badge'] ?? 'Новая заявка' ) ) . '</b>',
		'Форма: ' . wipe_clean_escape_telegram_html( (string) ( $core_fields['context_label'] ?: ( $config['location_label'] ?? '' ) ) ),
		'Страница: ' . wipe_clean_escape_telegram_html( (string) ( $core_fields['page_label'] ?: ( $config['page_label'] ?? '' ) ) ),
		'Раздел: ' . wipe_clean_escape_telegram_html( (string) ( $core_fields['surface_label'] ?: ( $config['surface_label'] ?? '' ) ) ),
		'Имя: ' . wipe_clean_escape_telegram_html( (string) $core_fields['name'] ),
		'Телефон: ' . wipe_clean_escape_telegram_html( (string) $core_fields['phone'] ),
	);

	if ( '' !== $core_fields['email'] ) {
		$lines[] = 'Email: ' . wipe_clean_escape_telegram_html( (string) $core_fields['email'] );
	}

	if ( '' !== $core_fields['promotion_title'] ) {
		$lines[] = 'Акция: ' . wipe_clean_escape_telegram_html( (string) $core_fields['promotion_title'] );
	}

	if ( '' !== $core_fields['area'] ) {
		$lines[] = 'Площадь: ' . wipe_clean_escape_telegram_html( (string) $core_fields['area'] );
	}

	if ( '' !== $core_fields['service'] ) {
		$lines[] = 'Услуга: ' . wipe_clean_escape_telegram_html( (string) $core_fields['service'] );
	}

	if ( '' !== $core_fields['frequency'] ) {
		$lines[] = 'Периодичность: ' . wipe_clean_escape_telegram_html( (string) $core_fields['frequency'] );
	}

	if ( '' !== $core_fields['message'] ) {
		$lines[] = wipe_clean_escape_telegram_html( (string) ( $config['message_label'] ?? 'Сообщение' ) ) . ': ' . wipe_clean_escape_telegram_html( (string) $core_fields['message'] );
	}

	if ( ! empty( $core_fields['review_files'] ) ) {
		$lines[] = wipe_clean_escape_telegram_html( (string) ( $config['files_label'] ?? 'Файлы' ) ) . ': ' . wipe_clean_escape_telegram_html( wipe_clean_clean_lead_value( $core_fields['review_files'] ) );
	}

	if ( $submission instanceof WPCF7_Submission ) {
		$url = wipe_clean_clean_lead_value( $submission->get_meta( 'url' ) );

		if ( '' !== $url ) {
			$lines[] = 'URL: ' . wipe_clean_escape_telegram_html( $url );
		}
	}

	return implode( "\n", $lines );
}

function wipe_clean_send_telegram_notification( $config, $core_fields, $submission ) {
	$token    = wipe_clean_get_telegram_bot_token();
	$chat_ids = wipe_clean_get_telegram_chat_ids();

	if ( '' === $token || empty( $chat_ids ) ) {
		return array(
			'status'   => 'disabled',
			'requests' => array(),
		);
	}

	$endpoint = sprintf( 'https://api.telegram.org/bot%s/sendMessage', $token );
	$message  = wipe_clean_build_telegram_message( $config, $core_fields, $submission );
	$requests = array();
	$success  = true;

	foreach ( $chat_ids as $chat_id ) {
		$response = wp_remote_post(
			$endpoint,
			array(
				'timeout' => 12,
				'body'    => array(
					'chat_id'                  => (string) $chat_id,
					'text'                     => $message,
					'parse_mode'               => 'HTML',
					'disable_web_page_preview' => 'true',
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			$success = false;
			$requests[] = array(
				'chat_id' => (string) $chat_id,
				'ok'      => false,
				'error'   => $response->get_error_message(),
			);
			continue;
		}

		$code = (int) wp_remote_retrieve_response_code( $response );
		$body = json_decode( (string) wp_remote_retrieve_body( $response ), true );
		$ok   = 200 === $code && ! empty( $body['ok'] );

		if ( ! $ok ) {
			$success = false;
		}

		$requests[] = array(
			'chat_id' => (string) $chat_id,
			'ok'      => $ok,
			'code'    => $code,
			'error'   => ! empty( $body['description'] ) ? (string) $body['description'] : '',
		);
	}

	return array(
		'status'   => $success ? 'sent' : 'failed',
		'requests' => $requests,
	);
}

function wipe_clean_insert_lead_row( $contact_form, $config, $submission, $result, $payload, $core_fields, $telegram_result ) {
	global $wpdb;

	$submission_status = (string) ( $result['status'] ?? '' );
	$mail_status       = 'mail_sent' === $submission_status ? 'sent' : ( 'mail_failed' === $submission_status ? 'failed' : 'aborted' );
	$payload_json      = wp_json_encode( $payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
	$delivery_json     = wp_json_encode(
		array(
			'mail_result' => $result,
			'telegram'    => $telegram_result,
		),
		JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
	);

	$wpdb->insert(
		wipe_clean_get_leads_table_name(),
		array(
			'created_at'         => current_time( 'mysql' ),
			'form_key'           => (string) wipe_clean_get_managed_form_key_by_contact_form( $contact_form ),
			'form_title'         => (string) ( $config['title'] ?? '' ),
			'source_label'       => (string) ( $core_fields['context_label'] ?: ( $config['location_label'] ?? '' ) ),
			'page_label'         => (string) ( $core_fields['page_label'] ?: ( $config['page_label'] ?? '' ) ),
			'lead_name'          => (string) $core_fields['name'],
			'lead_phone'         => (string) $core_fields['phone'],
			'lead_email'         => (string) $core_fields['email'],
			'submission_status'  => $submission_status,
			'mail_status'        => $mail_status,
			'telegram_status'    => (string) ( $telegram_result['status'] ?? 'disabled' ),
			'contact_form_id'    => (int) $contact_form->id(),
			'submission_hash'    => wipe_clean_clean_lead_value( $result['posted_data_hash'] ?? '' ),
			'submitted_url'      => wipe_clean_clean_lead_value( $submission->get_meta( 'url' ) ),
			'remote_ip'          => wipe_clean_clean_lead_value( $submission->get_meta( 'remote_ip' ) ),
			'user_agent'         => wipe_clean_clean_lead_value( $submission->get_meta( 'user_agent' ) ),
			'payload_json'       => false !== $payload_json ? $payload_json : '',
			'delivery_json'      => false !== $delivery_json ? $delivery_json : '',
		),
		array(
			'%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s',
		)
	);
}

function wipe_clean_get_uploaded_file_paths( $submission, $field_name = '' ) {
	if ( ! $submission instanceof WPCF7_Submission ) {
		return array();
	}

	$uploaded_files = (array) $submission->uploaded_files();
	$field_name     = sanitize_key( (string) $field_name );

	if ( '' !== $field_name ) {
		$uploaded_files = array_intersect_key(
			$uploaded_files,
			array(
				$field_name => true,
			)
		);
	}

	$paths = array();

	foreach ( $uploaded_files as $field_paths ) {
		foreach ( (array) $field_paths as $path ) {
			$path = (string) $path;

			if ( '' !== $path && file_exists( $path ) ) {
				$paths[] = $path;
			}
		}
	}

	return array_values( array_unique( $paths ) );
}

function wipe_clean_update_review_submission_field( $post_id, $field_name, $value ) {
	$post_id    = (int) $post_id;
	$field_name = sanitize_key( (string) $field_name );

	if ( $post_id <= 0 || '' === $field_name ) {
		return;
	}

	if ( function_exists( 'update_field' ) ) {
		update_field( $field_name, $value, $post_id );
		return;
	}

	update_post_meta( $post_id, $field_name, $value );
}

function wipe_clean_import_review_submission_attachment( $file_path, $post_id ) {
	$file_path = (string) $file_path;
	$post_id   = (int) $post_id;

	if ( '' === $file_path || $post_id <= 0 || ! file_exists( $file_path ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$temp_file = wp_tempnam( wp_basename( $file_path ) );

	if ( ! $temp_file || ! copy( $file_path, $temp_file ) ) {
		return 0;
	}

	$file_array = array(
		'name'     => wp_basename( $file_path ),
		'tmp_name' => $temp_file,
	);

	$attachment_id = media_handle_sideload( $file_array, $post_id );

	if ( is_wp_error( $attachment_id ) ) {
		if ( file_exists( $temp_file ) ) {
			@unlink( $temp_file );
		}

		return 0;
	}

	return (int) $attachment_id;
}

function wipe_clean_create_review_submission_draft( $contact_form, $config, $submission, $result, $payload, $core_fields ) {
	if ( ! $contact_form instanceof WPCF7_ContactForm || ! $submission instanceof WPCF7_Submission ) {
		return 0;
	}

	$form_key = wipe_clean_get_managed_form_key_by_contact_form( $contact_form );

	if ( 'popup_review' !== $form_key ) {
		return 0;
	}

	$submission_hash = wipe_clean_clean_lead_value( $result['posted_data_hash'] ?? '' );

	if ( '' !== $submission_hash ) {
		$existing_posts = get_posts(
			array(
				'post_type'      => 'wipe_review',
				'post_status'    => array( 'draft', 'pending', 'publish', 'private' ),
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_key'       => '_wipe_clean_review_submission_hash',
				'meta_value'     => $submission_hash,
			)
		);

		if ( ! empty( $existing_posts[0] ) ) {
			return (int) $existing_posts[0];
		}
	}

	$author_name  = trim( (string) ( $core_fields['name'] ?? '' ) );
	$page_label   = trim( (string) ( $core_fields['page_label'] ?? '' ) );
	$submitted_at = current_time( 'd.m.Y H:i' );
	$title_parts  = array_filter(
		array(
			'' !== $author_name ? 'Отзыв от ' . $author_name : 'Новый отзыв с сайта',
			$submitted_at,
		)
	);
	$post_title   = implode( ' — ', $title_parts );
	$post_content = trim( (string) ( $core_fields['message'] ?? '' ) );

	$post_id = wp_insert_post(
		array(
			'post_type'      => 'wipe_review',
			'post_status'    => 'draft',
			'post_title'     => $post_title,
			'post_content'   => $post_content,
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		),
		true
	);

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return 0;
	}

	$post_id = (int) $post_id;

	wipe_clean_update_review_submission_field( $post_id, 'review_type', 'text' );
	wipe_clean_update_review_submission_field( $post_id, 'author_name', $author_name );
	wipe_clean_update_review_submission_field( $post_id, 'review_text', $post_content );
	wipe_clean_update_review_submission_field( $post_id, 'rating', 5 );
	wipe_clean_update_review_submission_field( $post_id, 'show_on_home', 0 );
	wipe_clean_update_review_submission_field( $post_id, 'home_order', 10 );

	update_post_meta( $post_id, '_wipe_clean_review_submission_pending', 1 );
	update_post_meta( $post_id, '_wipe_clean_review_submission_hash', $submission_hash );
	update_post_meta( $post_id, '_wipe_clean_review_submission_form_key', $form_key );
	update_post_meta( $post_id, '_wipe_clean_review_submission_source_label', (string) ( $core_fields['context_label'] ?? '' ) );
	update_post_meta( $post_id, '_wipe_clean_review_submission_page_label', $page_label );
	update_post_meta( $post_id, '_wipe_clean_review_submission_surface_label', (string) ( $core_fields['surface_label'] ?? '' ) );
	update_post_meta( $post_id, '_wipe_clean_review_submission_phone', (string) ( $core_fields['phone'] ?? '' ) );
	update_post_meta( $post_id, '_wipe_clean_review_submission_email', (string) ( $core_fields['email'] ?? '' ) );
	update_post_meta( $post_id, '_wipe_clean_review_submission_url', wipe_clean_clean_lead_value( $submission->get_meta( 'url' ) ) );
	update_post_meta( $post_id, '_wipe_clean_review_submission_payload', wp_json_encode( $payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) ?: '' );

	$attachment_ids = array();
	$file_field     = sanitize_key( (string) ( $config['files_field_name'] ?? 'review_files' ) );

	foreach ( wipe_clean_get_uploaded_file_paths( $submission, $file_field ) as $file_path ) {
		$attachment_id = wipe_clean_import_review_submission_attachment( $file_path, $post_id );

		if ( $attachment_id > 0 ) {
			$attachment_ids[] = $attachment_id;
		}
	}

	if ( ! empty( $attachment_ids ) ) {
		update_post_meta( $post_id, '_wipe_clean_review_submission_attachment_ids', $attachment_ids );
		update_post_meta( $post_id, '_wipe_clean_review_submission_primary_attachment', (int) $attachment_ids[0] );
	}

	return $post_id;
}

function wipe_clean_capture_managed_cf7_submission( $contact_form, $result ) {
	if ( ! $contact_form instanceof WPCF7_ContactForm || ! wipe_clean_is_captureable_submission_status( $result['status'] ?? '' ) ) {
		return;
	}

	$config = wipe_clean_get_managed_form_config_by_contact_form( $contact_form );

	if ( empty( $config ) ) {
		return;
	}

	$submission = WPCF7_Submission::get_instance();

	if ( ! $submission instanceof WPCF7_Submission ) {
		return;
	}

	$payload         = wipe_clean_get_managed_submission_payload( $submission );
	$core_fields     = wipe_clean_get_managed_submission_core_fields( $payload );
	$telegram_result = wipe_clean_send_telegram_notification( $config, $core_fields, $submission );

	wipe_clean_insert_lead_row( $contact_form, $config, $submission, $result, $payload, $core_fields, $telegram_result );
	wipe_clean_create_review_submission_draft( $contact_form, $config, $submission, $result, $payload, $core_fields );
}
add_action( 'wpcf7_submit', 'wipe_clean_capture_managed_cf7_submission', 20, 2 );

function wipe_clean_validate_managed_name_field( $result, $tag ) {
	$contact_form = wpcf7_get_current_contact_form();

	if ( ! $contact_form instanceof WPCF7_ContactForm || empty( wipe_clean_get_managed_form_config_by_contact_form( $contact_form ) ) || 'name' !== $tag->name ) {
		return $result;
	}

	$value = wipe_clean_clean_lead_value( wp_unslash( $_POST['name'] ?? '' ) );

	$length = function_exists( 'mb_strlen' ) ? mb_strlen( $value ) : strlen( $value );

	if ( $length < 2 ) {
		$result->invalidate( $tag, 'Укажите имя полностью.' );
	}

	return $result;
}
add_filter( 'wpcf7_validate_text*', 'wipe_clean_validate_managed_name_field', 20, 2 );

function wipe_clean_validate_managed_phone_field( $result, $tag ) {
	$contact_form = wpcf7_get_current_contact_form();

	if ( ! $contact_form instanceof WPCF7_ContactForm || empty( wipe_clean_get_managed_form_config_by_contact_form( $contact_form ) ) || 'phone' !== $tag->name ) {
		return $result;
	}

	$value  = wipe_clean_clean_lead_value( wp_unslash( $_POST['phone'] ?? '' ) );
	$digits = preg_replace( '/\D+/', '', $value );

	if ( strlen( (string) $digits ) < 10 ) {
		$result->invalidate( $tag, 'Укажите телефон полностью.' );
	}

	return $result;
}
add_filter( 'wpcf7_validate_tel*', 'wipe_clean_validate_managed_phone_field', 20, 2 );
