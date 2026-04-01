<?php
/**
 * ACF fields for promotion records.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_register_promotion_cpt_acf_fields' ) ) {
	function wipe_clean_register_promotion_cpt_acf_fields() {
		if ( ! function_exists( 'wipe_clean_sync_acf_field_group' ) ) {
			return;
		}

		wipe_clean_sync_acf_field_group(
			array(
				'key'      => 'group_wipe_clean_promotion_cpt',
				'title'    => 'Настройки акции',
				'fields'   => array(
					wipe_clean_acf_field(
						'message',
						'promotion_note',
						'Как устроена акция',
						array(
							'message'   => 'Запись CPT <strong>Акции</strong> не создаёт отдельную публичную страницу. На сайте карточка акции открывает pop-up прямо на архиве <strong>/promotions/</strong>. Для карточки используются <strong>заголовок записи</strong> и <strong>изображение записи</strong>. Если для pop-up не задано отдельное изображение, берётся то же изображение карточки.',
							'esc_html'  => 0,
							'new_lines' => 'wpautop',
						)
					),
					wipe_clean_acf_field( 'text', 'popup_title', 'Заголовок внутри pop-up' ),
					wipe_clean_acf_field(
						'textarea',
						'popup_text',
						'Текст акции',
						array(
							'rows'         => 8,
							'new_lines'    => '',
							'instructions' => 'Если нужен новый абзац, оставьте пустую строку между абзацами.',
						)
					),
					wipe_clean_acf_repeater(
						'popup_conditions',
						'Условия акции',
						array(
							wipe_clean_acf_field( 'text', 'text', 'Текст условия' ),
						),
						array(
							'button_label' => 'Добавить условие',
						)
					),
					wipe_clean_acf_field(
						'image',
						'popup_image',
						'Изображение для pop-up',
						array(
							'return_format' => 'array',
							'preview_size'  => 'medium',
							'instructions'  => 'Необязательно. Если оставить пустым, pop-up возьмёт то же изображение, что и карточка акции.',
						)
					),
				),
				'location' => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'wipe_promotion',
						),
					),
				),
				'position' => 'acf_after_title',
				'style'    => 'seamless',
			)
		);
	}
}
add_action( 'acf/init', 'wipe_clean_register_promotion_cpt_acf_fields' );
