<?php
/**
 * Поля ACF для услуги.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register service ACF fields.
 *
 * @return void
 */
function wipe_clean_register_service_cpt_acf_fields() {
	if ( ! function_exists( 'wipe_clean_sync_acf_field_group' ) ) {
		return;
	}

	wipe_clean_sync_acf_field_group(
		array(
			'key'      => 'group_wipe_clean_service_cpt',
			'title'    => 'Карточка услуги',
			'fields'   => array(
				wipe_clean_acf_field(
					'message',
					'service_note',
					'Как заполнять услугу',
					array(
						'message'   => 'Услуга здесь работает как обычная запись. Карточки на главной, в архиве и в других блоках собираются сами из <strong>названия</strong>, <strong>краткого описания</strong>, <strong>изображения записи</strong> и поля <strong>Цена от</strong>. Отдельные поля для главной не нужны.',
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
					)
				),
				wipe_clean_acf_field(
					'text',
					'service_price_value',
					'Цена от',
					array(
						'instructions' => 'Короткая цена для карточки услуги и блока стоимости на странице услуги. Например: от 10 000 ₽.',
					)
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'wipe_service',
					),
				),
			),
			'position' => 'acf_after_title',
			'style'    => 'seamless',
		)
	);
}
add_action( 'acf/init', 'wipe_clean_register_service_cpt_acf_fields' );
