<?php
/**
 * ACF fields for homepage service cards.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register ACF for service items.
 *
 * @return void
 */
function wipe_clean_register_service_cpt_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'      => 'group_wipe_clean_service_cpt',
			'title'    => 'Настройки карточки услуги',
			'fields'   => array(
				wipe_clean_acf_field(
					'message',
					'service_note',
					'Как использовать',
					array(
						'message'   => 'Эти записи используются в блоке услуг на главной странице. Если услуга не должна показываться на главной, снимите галочку <strong>Показывать на главной</strong>.',
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
					)
				),
				wipe_clean_acf_field( 'text', 'card_price', 'Цена' ),
				wipe_clean_acf_field(
					'true_false',
					'show_on_home',
					'Показывать на главной',
					array(
						'ui'            => 1,
						'default_value' => 1,
					)
				),
				wipe_clean_acf_field(
					'select',
					'home_group',
					'Где показывать на главной',
					array(
						'choices'       => array(
							'featured'  => 'Верхние карточки',
							'secondary' => 'Нижние карточки',
						),
						'default_value' => 'featured',
						'ui'            => 1,
					)
				),
				wipe_clean_acf_field( 'number', 'home_order', 'Порядок на главной', array( 'default_value' => 10 ) ),
				wipe_clean_acf_field(
					'select',
					'card_variant',
					'Оформление карточки',
					array(
						'choices'       => array(
							'standard'     => 'Обычное',
							'after_repair' => 'После ремонта',
						),
						'default_value' => 'standard',
						'ui'            => 1,
					)
				),
				wipe_clean_acf_repeater(
					'card_layers',
					'Слои изображения',
					array(
						wipe_clean_acf_field(
							'image',
							'image',
							'Изображение',
							array(
								'return_format' => 'id',
								'preview_size'  => 'medium',
							)
						),
						wipe_clean_acf_field(
							'select',
							'modifier',
							'Расположение слоя',
							array(
								'choices'       => array(
									'fill'        => 'На весь кадр',
									'shift-top'   => 'Сдвиг вверх',
									'shift-right' => 'Сдвиг вправо',
									'flip'        => 'Отразить',
									'overlay'     => 'Поверх других',
								),
								'default_value' => 'fill',
								'ui'            => 1,
							)
						),
					),
					array(
						'button_label' => 'Добавить слой',
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
