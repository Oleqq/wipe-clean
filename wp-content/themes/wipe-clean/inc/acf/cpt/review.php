<?php
/**
 * ACF fields for homepage review cards.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register ACF for review items.
 *
 * @return void
 */
function wipe_clean_register_review_cpt_acf_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'      => 'group_wipe_clean_review_cpt',
			'title'    => 'Настройки отзыва',
			'fields'   => array(
				wipe_clean_acf_field(
					'message',
					'review_note',
					'Как использовать',
					array(
						'message'   => 'Эти записи используются в слайдере отзывов на главной странице. Если отзыв не должен показываться на главной, снимите галочку <strong>Показывать на главной</strong>.',
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
					)
				),
				wipe_clean_acf_field( 'text', 'author_name', 'Имя клиента' ),
				wipe_clean_acf_field( 'textarea', 'review_text', 'Текст отзыва', array( 'rows' => 5 ) ),
				wipe_clean_acf_field(
					'number',
					'rating',
					'Оценка',
					array(
						'default_value' => 5,
						'min'           => 1,
						'max'           => 5,
					)
				),
				wipe_clean_acf_field(
					'true_false',
					'show_on_home',
					'Показывать на главной',
					array(
						'ui'            => 1,
						'default_value' => 1,
					)
				),
				wipe_clean_acf_field( 'number', 'home_order', 'Порядок на главной', array( 'default_value' => 10 ) ),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'wipe_review',
					),
				),
			),
			'position' => 'acf_after_title',
			'style'    => 'seamless',
		)
	);
}
add_action( 'acf/init', 'wipe_clean_register_review_cpt_acf_fields' );
