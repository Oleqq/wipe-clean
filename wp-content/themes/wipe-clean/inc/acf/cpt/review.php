<?php
/**
 * ACF fields for review records.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_register_review_cpt_acf_fields' ) ) {
	function wipe_clean_register_review_cpt_acf_fields() {
		if ( ! function_exists( 'wipe_clean_sync_acf_field_group' ) ) {
			return;
		}

		$type_field = wipe_clean_acf_field(
			'button_group',
			'review_type',
			'Тип отзыва',
			array(
				'choices'       => array(
					'text'  => 'Текстовый',
					'video' => 'Видео',
					'photo' => 'Фото / переписка',
				),
				'default_value' => 'text',
				'layout'        => 'horizontal',
				'return_format' => 'value',
			)
		);

		$text_logic  = array(
			array(
				array(
					'field'    => $type_field['key'],
					'operator' => '==',
					'value'    => 'text',
				),
			),
		);
		$video_logic = array(
			array(
				array(
					'field'    => $type_field['key'],
					'operator' => '==',
					'value'    => 'video',
				),
			),
		);
		$photo_logic = array(
			array(
				array(
					'field'    => $type_field['key'],
					'operator' => '==',
					'value'    => 'photo',
				),
			),
		);

		wipe_clean_sync_acf_field_group(
			array(
				'key'      => 'group_wipe_clean_review_cpt',
				'title'    => 'Настройки отзыва',
				'fields'   => array(
					wipe_clean_acf_field(
						'message',
						'review_note',
						'Как устроен отзыв',
						array(
							'message'   => 'Запись CPT <strong>Отзывы</strong> не создаёт отдельную публичную страницу. Тип записи определяет, в какую секцию страницы <strong>/reviews/</strong> попадёт отзыв: <strong>Текстовый</strong>, <strong>Видео</strong> или <strong>Фото / переписка</strong>. На главной странице используются только текстовые отзывы с включённым флагом показа. Размеры видео и раскладка фото-отзывов рассчитываются автоматически, без технических полей в редакторе.',
							'esc_html'  => 0,
							'new_lines' => 'wpautop',
						)
					),
					$type_field,
					wipe_clean_acf_field(
						'tab',
						'text_review_tab',
						'Текстовый отзыв',
						array(
							'placement'         => 'top',
							'endpoint'          => 0,
							'conditional_logic' => $text_logic,
						)
					),
					wipe_clean_acf_field(
						'text',
						'author_name',
						'Имя автора',
						array(
							'conditional_logic' => $text_logic,
						)
					),
					wipe_clean_acf_field(
						'textarea',
						'review_text',
						'Текст отзыва',
						array(
							'rows'              => 6,
							'new_lines'         => 'wpautop',
							'conditional_logic' => $text_logic,
						)
					),
					wipe_clean_acf_field(
						'number',
						'rating',
						'Рейтинг',
						array(
							'default_value'     => 5,
							'min'               => 1,
							'max'               => 5,
							'step'              => 1,
							'conditional_logic' => $text_logic,
						)
					),
					wipe_clean_acf_field(
						'true_false',
						'show_on_home',
						'Показывать на главной',
						array(
							'ui'                => 1,
							'default_value'     => 1,
							'instructions'      => 'Используется только для текстовых отзывов.',
							'conditional_logic' => $text_logic,
						)
					),
					wipe_clean_acf_field(
						'number',
						'home_order',
						'Порядок на главной',
						array(
							'default_value'     => 10,
							'min'               => 0,
							'step'              => 1,
							'conditional_logic' => $text_logic,
						)
					),
					wipe_clean_acf_field(
						'tab',
						'video_review_tab',
						'Видео отзыв',
						array(
							'placement'         => 'top',
							'endpoint'          => 0,
							'conditional_logic' => $video_logic,
						)
					),
					wipe_clean_acf_field(
						'image',
						'video_poster',
						'Постер видео',
						array(
							'return_format'     => 'array',
							'preview_size'      => 'medium',
							'conditional_logic' => $video_logic,
						)
					),
					wipe_clean_acf_field(
						'file',
						'video_file',
						'Видео файл',
						array(
							'return_format'     => 'array',
							'mime_types'        => 'mp4,mov,webm',
							'conditional_logic' => $video_logic,
						)
					),
					wipe_clean_acf_field(
						'url',
						'video_url',
						'Внешний URL видео',
						array(
							'instructions'      => 'Используется как fallback, если файл не загружен.',
							'conditional_logic' => $video_logic,
						)
					),
					wipe_clean_acf_field(
						'text',
						'video_caption',
						'Подпись',
						array(
							'conditional_logic' => $video_logic,
						)
					),
					wipe_clean_acf_field(
						'text',
						'video_alt',
						'Alt / aria label',
						array(
							'conditional_logic' => $video_logic,
						)
					),
					wipe_clean_acf_field(
						'tab',
						'photo_review_tab',
						'Фото / переписка',
						array(
							'placement'         => 'top',
							'endpoint'          => 0,
							'conditional_logic' => $photo_logic,
						)
					),
					wipe_clean_acf_field(
						'image',
						'photo_image',
						'Изображение карточки',
						array(
							'return_format'     => 'array',
							'preview_size'      => 'medium',
							'conditional_logic' => $photo_logic,
						)
					),
					wipe_clean_acf_field(
						'image',
						'photo_lightbox_image',
						'Изображение для lightbox',
						array(
							'return_format'     => 'array',
							'preview_size'      => 'medium',
							'instructions'      => 'Если оставить пустым, откроется основное изображение карточки.',
							'conditional_logic' => $photo_logic,
						)
					),
					wipe_clean_acf_field(
						'text',
						'photo_caption',
						'Подпись',
						array(
							'conditional_logic' => $photo_logic,
						)
					),
					wipe_clean_acf_field(
						'text',
						'photo_alt',
						'Alt',
						array(
							'conditional_logic' => $photo_logic,
						)
					),
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
}
add_action( 'acf/init', 'wipe_clean_register_review_cpt_acf_fields' );
