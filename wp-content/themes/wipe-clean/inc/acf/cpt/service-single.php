<?php
/**
 * ACF flexible layouts for service single pages.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_get_service_single_acf_layout_service_hero() {
	return array(
		'key'        => 'layout_service_single_hero',
		'name'       => 'service_hero',
		'label'      => 'Первый экран услуги',
		'display'    => 'block',
		'sub_fields' => array(
			wipe_clean_acf_field( 'text', 'kicker', 'Кикер' ),
			wipe_clean_acf_field( 'textarea', 'title', 'Заголовок', array( 'rows' => 2 ) ),
			wipe_clean_acf_field( 'textarea', 'text', 'Текст', array( 'rows' => 5 ) ),
			wipe_clean_acf_field( 'image', 'image', 'Изображение', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
			wipe_clean_acf_field( 'link', 'primary_action', 'Основная кнопка' ),
			wipe_clean_acf_field( 'link', 'secondary_action', 'Вторая кнопка' ),
		),
	);
}

function wipe_clean_get_service_single_acf_layout_service_purpose() {
	return array(
		'key'        => 'layout_service_single_purpose',
		'name'       => 'service_purpose',
		'label'      => 'Зачем нужна услуга',
		'display'    => 'block',
		'sub_fields' => array(
			wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
			wipe_clean_acf_field( 'textarea', 'summary_text', 'Короткое описание', array( 'rows' => 2 ) ),
			wipe_clean_acf_field( 'textarea', 'intro_text', 'Основной текст', array( 'rows' => 5 ) ),
			wipe_clean_acf_field( 'textarea', 'note_text', 'Выделенный текст', array( 'rows' => 4 ) ),
			wipe_clean_acf_field( 'text', 'lead_text', 'Подзаголовок списка' ),
			wipe_clean_acf_repeater(
				'reasons',
				'Причины',
				array(
					wipe_clean_acf_field( 'text', 'text', 'Текст' ),
					wipe_clean_acf_field( 'text', 'mobile_text', 'Текст для mobile' ),
					wipe_clean_acf_field( 'image', 'icon', 'Иконка', array( 'return_format' => 'array', 'preview_size' => 'thumbnail' ) ),
				)
			),
			wipe_clean_acf_field( 'textarea', 'ending_text', 'Финальный текст', array( 'rows' => 3 ) ),
			wipe_clean_acf_field( 'image', 'image', 'Изображение', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
		),
	);
}

function wipe_clean_get_service_single_acf_layout_service_wave_group() {
	return array(
		'key'        => 'layout_service_single_wave_group',
		'name'       => 'service_wave_group',
		'label'      => 'Что входит + преимущества',
		'display'    => 'block',
		'sub_fields' => array(
			wipe_clean_acf_field(
				'message',
				'service_wave_group_note',
				'Как заполнять блок',
				array(
					'message'   => 'Этот layout объединяет две соседние секции single-страницы: <strong>Что входит в услугу</strong> и <strong>Преимущества</strong>. Позиции точек, активные состояния и служебные ID зафиксированы в шаблоне и скрыты от редактора.',
					'esc_html'  => 0,
					'new_lines' => 'wpautop',
				)
			),
			wipe_clean_acf_field( 'text', 'includes_title', 'Заголовок блока "Что входит"' ),
			wipe_clean_acf_field( 'textarea', 'includes_text', 'Текст блока "Что входит"', array( 'rows' => 4 ) ),
			wipe_clean_acf_repeater(
				'includes_tabs',
				'Табы состава услуги',
				array(
					wipe_clean_acf_field( 'text', 'label', 'Название таба' ),
					wipe_clean_acf_field( 'image', 'image', 'Изображение', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
					wipe_clean_acf_repeater(
						'hotspots',
						'Тексты подсказок',
						array(
							wipe_clean_acf_field( 'textarea', 'tooltip', 'Текст подсказки', array( 'rows' => 3 ) ),
						),
						array(
							'max'          => 4,
							'instructions' => 'В этой группе редактируется только текст подсказки. Позиция точки и размеры tooltip берутся из шаблона.',
						)
					),
				),
				array(
					'max'          => 4,
					'instructions' => 'Вкладки состава услуги собираются по шаблону. Здесь доступны только понятные поля: название, изображение и тексты подсказок.',
				)
			),
			wipe_clean_acf_field( 'text', 'benefits_title', 'Заголовок блока "Преимущества"' ),
			wipe_clean_acf_field( 'textarea', 'benefits_text', 'Текст блока "Преимущества"', array( 'rows' => 4 ) ),
			wipe_clean_acf_field( 'textarea', 'benefits_summary', 'Сводный текст', array( 'rows' => 3 ) ),
			wipe_clean_acf_repeater(
				'benefits_cards',
				'Карточки преимуществ',
				array(
					wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
					wipe_clean_acf_field( 'textarea', 'text', 'Текст', array( 'rows' => 3 ) ),
					wipe_clean_acf_field( 'image', 'icon', 'Иконка', array( 'return_format' => 'array', 'preview_size' => 'thumbnail' ) ),
				)
			),
		),
	);
}

function wipe_clean_get_service_single_acf_layout_order_cta() {
	return array(
		'key'        => 'layout_service_single_order_cta',
		'name'       => 'order_cta',
		'label'      => 'CTA секция',
		'display'    => 'block',
		'sub_fields' => array(
			wipe_clean_acf_field( 'textarea', 'title', 'Заголовок', array( 'rows' => 2, 'instructions' => 'Каждая новая строка станет новой строкой в заголовке.' ) ),
			wipe_clean_acf_field( 'textarea', 'text', 'Текст', array( 'rows' => 5 ) ),
			wipe_clean_acf_field( 'link', 'primary_action', 'Основная кнопка' ),
			wipe_clean_acf_field( 'link', 'secondary_action', 'Вторая кнопка' ),
			wipe_clean_acf_field( 'image', 'image', 'Изображение', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
		),
	);
}

function wipe_clean_get_service_single_acf_layout_service_price() {
	return array(
		'key'        => 'layout_service_single_price',
		'name'       => 'service_price',
		'label'      => 'Стоимость услуги',
		'display'    => 'block',
		'sub_fields' => array(
			wipe_clean_acf_field( 'text', 'title_accent', 'Акцент в заголовке' ),
			wipe_clean_acf_field( 'text', 'title_main', 'Основная часть заголовка' ),
			wipe_clean_acf_field( 'text', 'title_break', 'Перенос заголовка' ),
			wipe_clean_acf_field( 'textarea', 'text', 'Основной текст', array( 'rows' => 5 ) ),
			wipe_clean_acf_field( 'text', 'lead_text', 'Подзаголовок списка' ),
			wipe_clean_acf_repeater(
				'factors',
				'Факторы цены',
				array(
					wipe_clean_acf_field( 'text', 'text', 'Текст' ),
					wipe_clean_acf_field( 'image', 'icon', 'Иконка', array( 'return_format' => 'array', 'preview_size' => 'thumbnail' ) ),
				)
			),
			wipe_clean_acf_field( 'textarea', 'note_text', 'Нижний текст', array( 'rows' => 3 ) ),
			wipe_clean_acf_field( 'text', 'accent_text', 'Акцентная строка цены', array( 'instructions' => 'Если оставить пустым, будет использовано поле "Цена от" из настроек услуги.' ) ),
			wipe_clean_acf_field( 'link', 'button', 'Кнопка' ),
			wipe_clean_acf_field( 'image', 'image', 'Изображение', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
		),
	);
}

function wipe_clean_get_service_single_acf_layout_before_after_results() {
	return array(
		'key'        => 'layout_service_single_before_after',
		'name'       => 'before_after_results',
		'label'      => 'До и после',
		'display'    => 'block',
		'sub_fields' => array(
			wipe_clean_acf_field( 'textarea', 'title', 'Заголовок', array( 'rows' => 2, 'instructions' => 'Каждая новая строка станет новой строкой в заголовке.' ) ),
			wipe_clean_acf_field( 'text', 'button_label', 'Текст кнопки "Показать еще"' ),
			wipe_clean_acf_field( 'number', 'initial_desktop', 'Сколько карточек показывать на desktop' ),
			wipe_clean_acf_field( 'number', 'initial_mobile', 'Сколько карточек показывать на mobile' ),
			wipe_clean_acf_field( 'number', 'step_desktop', 'Шаг загрузки на desktop' ),
			wipe_clean_acf_field( 'number', 'step_mobile', 'Шаг загрузки на mobile' ),
			wipe_clean_acf_repeater(
				'items',
				'Карточки сравнения',
				array(
					wipe_clean_acf_field( 'text', 'id', 'ID карточки' ),
					wipe_clean_acf_field( 'image', 'before_image', 'Изображение "До"', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
					wipe_clean_acf_field( 'image', 'after_image', 'Изображение "После"', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
					wipe_clean_acf_field( 'text', 'alt', 'Alt' ),
					wipe_clean_acf_field( 'text', 'control_label', 'Подпись range-control' ),
					wipe_clean_acf_field( 'number', 'start', 'Позиция стартового слайдера desktop', array( 'step' => 0.1 ) ),
					wipe_clean_acf_field( 'number', 'mobile_start', 'Позиция стартового слайдера mobile', array( 'step' => 0.1 ) ),
				)
			),
		),
	);
}

function wipe_clean_get_service_single_acf_layout_other_services() {
	return array(
		'key'        => 'layout_service_single_other_services',
		'name'       => 'other_services',
		'label'      => 'Другие услуги',
		'display'    => 'block',
		'sub_fields' => array(
			wipe_clean_acf_field(
				'message',
				'other_services_note',
				'Как устроен блок',
				array(
					'message'   => 'Карточки других услуг подтягиваются автоматически из остальных записей CPT <strong>Услуги</strong>. Здесь редактируются только заголовок, текст и кнопка.',
					'esc_html'  => 0,
					'new_lines' => 'wpautop',
				)
			),
			wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
			wipe_clean_acf_field( 'textarea', 'text', 'Текст', array( 'rows' => 4 ) ),
			wipe_clean_acf_field( 'link', 'button', 'Кнопка' ),
		),
	);
}

function wipe_clean_get_service_single_acf_layout_faq() {
	return array(
		'key'        => 'layout_service_single_faq',
		'name'       => 'faq',
		'label'      => 'FAQ',
		'display'    => 'block',
		'sub_fields' => array(
			wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
			wipe_clean_acf_repeater(
				'items',
				'Вопросы',
				array(
					wipe_clean_acf_field( 'text', 'question', 'Вопрос' ),
					wipe_clean_acf_field( 'textarea', 'answer', 'Ответ', array( 'rows' => 4 ) ),
				)
			),
		),
	);
}

function wipe_clean_get_service_single_acf_layout_contacts() {
	return array(
		'key'        => 'layout_service_single_contacts',
		'name'       => 'contacts',
		'label'      => 'Контакты',
		'display'    => 'block',
		'sub_fields' => array(
			wipe_clean_acf_field( 'text', 'title', 'Заголовок' ),
			wipe_clean_acf_field( 'text', 'phone_label', 'Подпись телефона' ),
			wipe_clean_acf_field( 'text', 'phone_value', 'Телефон' ),
			wipe_clean_acf_field( 'text', 'socials_label', 'Подпись соцсетей' ),
			wipe_clean_acf_repeater(
				'social_links',
				'Соцсети',
				array(
					wipe_clean_acf_field( 'text', 'label', 'Название' ),
					wipe_clean_acf_field( 'url', 'url', 'Ссылка' ),
					wipe_clean_acf_field( 'image', 'icon', 'Иконка', array( 'return_format' => 'array', 'preview_size' => 'thumbnail' ) ),
				)
			),
			wipe_clean_acf_field( 'text', 'email_label', 'Подпись email' ),
			wipe_clean_acf_field( 'text', 'email_value', 'Email' ),
			wipe_clean_acf_field( 'text', 'form_title', 'Заголовок формы' ),
			wipe_clean_acf_field( 'text', 'form_name_label', 'Подпись имени' ),
			wipe_clean_acf_field( 'text', 'form_name_placeholder', 'Плейсхолдер имени' ),
			wipe_clean_acf_field( 'text', 'form_phone_label', 'Подпись телефона' ),
			wipe_clean_acf_field( 'text', 'form_phone_placeholder', 'Плейсхолдер телефона' ),
			wipe_clean_acf_field( 'textarea', 'agreement_text', 'Текст согласия', array( 'rows' => 2 ) ),
			wipe_clean_acf_field( 'text', 'submit_text', 'Кнопка desktop' ),
			wipe_clean_acf_field( 'text', 'submit_text_mobile', 'Кнопка mobile' ),
		),
	);
}

function wipe_clean_get_service_single_acf_layouts() {
	$layouts = array(
		wipe_clean_get_service_single_acf_layout_service_hero(),
		wipe_clean_get_service_single_acf_layout_service_purpose(),
		wipe_clean_get_service_single_acf_layout_service_wave_group(),
		wipe_clean_get_service_single_acf_layout_order_cta(),
		wipe_clean_get_service_single_acf_layout_service_price(),
		wipe_clean_get_service_single_acf_layout_before_after_results(),
		wipe_clean_get_service_single_acf_layout_other_services(),
		wipe_clean_get_service_single_acf_layout_faq(),
		wipe_clean_get_service_single_acf_layout_contacts(),
	);

	return array_values(
		array_filter(
			$layouts,
			static function ( $layout ) {
				return is_array( $layout ) && ! empty( $layout );
			}
		)
	);
}

function wipe_clean_register_service_single_acf_fields() {
	if ( ! function_exists( 'wipe_clean_sync_acf_field_group' ) ) {
		return;
	}

	wipe_clean_sync_acf_field_group(
		array(
			'key'      => 'group_wipe_clean_service_single',
			'title'    => 'Страница услуги',
			'fields'   => array(
				wipe_clean_acf_field(
					'message',
					'service_sections_note',
					'Как устроена страница услуги',
					array(
						'message'   => 'Страница услуги собирается из блоков ниже. Карточки на главной, в архиве и в других блоках берут данные автоматически из самой записи услуги: <strong>название</strong>, <strong>краткое описание</strong>, <strong>изображение записи</strong> и поле <strong>Цена от</strong>.',
						'esc_html'  => 0,
						'new_lines' => 'wpautop',
					)
				),
				wipe_clean_acf_field(
					'flexible_content',
					'service_sections',
					'Блоки страницы услуги',
					array(
						'button_label' => 'Добавить блок',
						'layouts'      => wipe_clean_get_service_single_acf_layouts(),
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
add_action( 'acf/init', 'wipe_clean_register_service_single_acf_fields' );
