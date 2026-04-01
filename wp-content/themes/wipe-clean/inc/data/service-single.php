<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_service_single_image( $path, $alt = '' ) {
	$path = (string) $path;
	$alt  = (string) $alt;

	if ( function_exists( 'wipe_clean_theme_image' ) ) {
		return wipe_clean_theme_image( $path, $alt );
	}

	return array(
		'path' => ltrim( $path, '/' ),
		'alt'  => $alt,
	);
}

function wipe_clean_service_single_link( $title, $url = '#', $target = '' ) {
	return array(
		'title'  => (string) $title,
		'url'    => (string) $url,
		'target' => (string) $target,
	);
}

if ( ! function_exists( 'wipe_clean_get_service_single_intro_text' ) ) {
	function wipe_clean_get_service_single_intro_text( $post_id ) {
		$post = get_post( $post_id );

		if ( ! $post instanceof WP_Post ) {
			return '';
		}

		$text = trim( (string) $post->post_excerpt );

		if ( '' === $text && ! empty( $post->post_content ) ) {
			$text = wp_strip_all_tags( strip_shortcodes( (string) $post->post_content ) );
			$text = wp_trim_words( $text, 42, '...' );
		}

		return $text;
	}
}

if ( ! function_exists( 'wipe_clean_get_service_single_price_text' ) ) {
	function wipe_clean_get_service_single_price_text( $post_id, $fallback = '' ) {
		$value = function_exists( 'wipe_clean_get_service_card_price_value' )
			? trim( (string) wipe_clean_get_service_card_price_value( $post_id ) )
			: '';

		if ( '' === $value ) {
			return (string) $fallback;
		}

		$contains_price_word = function_exists( 'mb_stripos' )
			? false !== mb_stripos( $value, 'цена' )
			: false !== stripos( $value, 'цена' );

		if ( $contains_price_word ) {
			return $value;
		}

		return 'Цена начинается ' . $value;
	}
}

function wipe_clean_get_service_single_title_lines( $value, $fallback = array() ) {
	$value = trim( (string) $value );

	if ( '' === $value ) {
		return array_values(
			array_filter(
				array_map( 'trim', (array) $fallback )
			)
		);
	}

	$lines = preg_split( "/\r\n|\n|\r/", $value );
	$lines = is_array( $lines ) ? $lines : array( $value );

	return array_values(
		array_filter(
			array_map( 'trim', $lines )
		)
	);
}

function wipe_clean_merge_service_wave_group_hotspots( $default_hotspots, $custom_hotspots ) {
	$default_hotspots = is_array( $default_hotspots ) ? array_values( $default_hotspots ) : array();
	$custom_hotspots  = is_array( $custom_hotspots ) ? array_values( $custom_hotspots ) : array();
	$merged_hotspots  = array();

	foreach ( $default_hotspots as $index => $default_hotspot ) {
		$custom_hotspot = isset( $custom_hotspots[ $index ] ) && is_array( $custom_hotspots[ $index ] )
			? $custom_hotspots[ $index ]
			: array();
		$merged_hotspot = is_array( $default_hotspot ) ? $default_hotspot : array();

		if ( function_exists( 'wipe_clean_has_meaningful_section_value' ) && wipe_clean_has_meaningful_section_value( $custom_hotspot['tooltip'] ?? '' ) ) {
			$merged_hotspot['tooltip'] = (string) $custom_hotspot['tooltip'];
		}

		$merged_hotspots[] = $merged_hotspot;
	}

	return $merged_hotspots;
}

function wipe_clean_merge_service_wave_group_section( $defaults, $section ) {
	$defaults = is_array( $defaults ) ? $defaults : array();
	$section  = is_array( $section ) ? $section : array();
	$merged   = function_exists( 'wipe_clean_merge_section_with_fallback' )
		? wipe_clean_merge_section_with_fallback( $defaults, $section )
		: array_replace_recursive( $defaults, $section );

	$default_tabs = ! empty( $defaults['includes_tabs'] ) && is_array( $defaults['includes_tabs'] )
		? array_values( $defaults['includes_tabs'] )
		: array();
	$custom_tabs  = ! empty( $section['includes_tabs'] ) && is_array( $section['includes_tabs'] )
		? array_values( $section['includes_tabs'] )
		: array();
	$merged_tabs  = array();

	foreach ( $default_tabs as $index => $default_tab ) {
		$custom_tab = isset( $custom_tabs[ $index ] ) && is_array( $custom_tabs[ $index ] )
			? $custom_tabs[ $index ]
			: array();
		$merged_tab = is_array( $default_tab ) ? $default_tab : array();

		if ( function_exists( 'wipe_clean_has_meaningful_section_value' ) && wipe_clean_has_meaningful_section_value( $custom_tab['label'] ?? '' ) ) {
			$merged_tab['label'] = (string) $custom_tab['label'];
		}

		if ( ! empty( $custom_tab['image'] ) ) {
			$merged_tab['image'] = $custom_tab['image'];
		}

		$merged_tab['hotspots'] = wipe_clean_merge_service_wave_group_hotspots(
			$default_tab['hotspots'] ?? array(),
			$custom_tab['hotspots'] ?? array()
		);

		if ( ! empty( $default_tab['active_hotspot_id'] ) ) {
			$merged_tab['active_hotspot_id'] = (string) $default_tab['active_hotspot_id'];
		}

		$merged_tabs[] = $merged_tab;
	}

	$merged['includes_tabs'] = $merged_tabs;

	return $merged;
}

function wipe_clean_get_service_single_default_sections_map() {
	$faq = function_exists( 'wipe_clean_get_services_page_section_defaults' )
		? wipe_clean_get_services_page_section_defaults( 'faq' )
		: array();

	$contacts = function_exists( 'wipe_clean_get_services_page_section_defaults' )
		? wipe_clean_get_services_page_section_defaults( 'contacts' )
		: array();

	return array(
		'service_hero'          => array(
			'acf_fc_layout'     => 'service_hero',
			'kicker'            => 'Уборка квартир',
			'title'             => 'Уборка квартир под ключ в Москве',
			'text'              => 'Поддерживающая уборка квартир под ключ в Москве гарантирует чистоту и освобождает от бытовой рутины. Команда приезжает вовремя, работает аккуратно и бережно относится к деталям интерьера.',
			'image'             => wipe_clean_service_single_image( 'static/images/section/service-hero/service-hero-apartment-cleaning.png', 'Уборка квартиры под ключ в Москве' ),
			'primary_action'    => wipe_clean_service_single_link( 'Заказать уборку квартиры' ),
			'secondary_action'  => wipe_clean_service_single_link( 'Проконсультироваться' ),
		),
		'service_purpose'      => array(
			'acf_fc_layout' => 'service_purpose',
			'title'         => 'Зачем заказывать уборку квартиры?',
			'summary_text'  => 'Профессиональной уборкой квартир под ключ чаще пользуются те, кому важно не тратить выходные на тяжелую бытовую работу.',
			'intro_text'    => 'Профессиональная уборка квартиры превращает наведение порядка в понятный и контролируемый процесс. Это освобождает от бытовой рутины и помогает поддерживать дом в аккуратном состоянии без лишних усилий.',
			'note_text'     => 'Такой формат особенно удобен для семей с плотным графиком, владельцев домашних животных и тех, кто хочет сохранить чистоту без постоянной вовлеченности.',
			'lead_text'     => 'Главные причины выбрать наш сервис:',
			'reasons'       => array(
				array(
					'text' => 'Экономия времени и сил владельца квартиры',
					'icon' => wipe_clean_service_single_image( 'static/images/ui/check-badge-icon.svg', 'Пункт списка' ),
				),
				array(
					'text' => 'Профессиональный результат без бытовых компромиссов',
					'icon' => wipe_clean_service_single_image( 'static/images/ui/check-badge-icon.svg', 'Пункт списка' ),
				),
				array(
					'text' => 'Индивидуальный подход под состояние помещения',
					'icon' => wipe_clean_service_single_image( 'static/images/ui/check-badge-icon.svg', 'Пункт списка' ),
				),
				array(
					'text' => 'Бережное отношение к отделке, мебели и вещам',
					'mobile_text' => 'Бережно работаем с отделкой, мебелью и вещами.',
					'icon' => wipe_clean_service_single_image( 'static/images/ui/check-badge-icon.svg', 'Пункт списка' ),
				),
			),
			'ending_text'   => 'Грамотно организованный клининг помогает дольше сохранять интерьер в хорошем состоянии и упрощает регулярный уход за жильем.',
			'image'         => wipe_clean_service_single_image( 'static/images/section/service-purpose/service-purpose-image.png', 'Причины заказать уборку квартиры' ),
		),
		'service_wave_group'   => array(
			'acf_fc_layout'    => 'service_wave_group',
			'includes_title'   => 'Что входит в уборку квартиры под ключ?',
			'includes_text'    => 'Комплексная уборка включает последовательную проработку всех основных зон квартиры с учетом типа загрязнений и материала поверхностей.',
			'includes_tabs'    => array(
				array(
					'id'                => 'living-space',
					'label'             => 'Жилое пространство',
					'image'             => wipe_clean_service_single_image( 'static/images/section/service-includes/service-includes-room.png', 'Жилое пространство после уборки' ),
					'active_hotspot_id' => 'floors',
					'hotspots'          => array(
						array(
							'id'                    => 'windows',
							'x_desktop'             => 27,
							'y_desktop'             => 22,
							'x_mobile'              => 34,
							'y_mobile'              => 16,
							'tooltip_x_desktop'     => 17,
							'tooltip_y_desktop'     => 12,
							'tooltip_x_mobile'      => 11,
							'tooltip_y_mobile'      => 10,
							'tooltip_width_desktop' => 236,
							'tooltip_width_mobile'  => 149,
							'tooltip'               => 'Аккуратная протирка рам, подоконников и доступных стеклянных поверхностей.',
						),
						array(
							'id'                    => 'soft-furniture',
							'x_desktop'             => 10,
							'y_desktop'             => 45,
							'x_mobile'              => 10,
							'y_mobile'              => 50,
							'tooltip_x_desktop'     => 3,
							'tooltip_y_desktop'     => 31,
							'tooltip_x_mobile'      => 4,
							'tooltip_y_mobile'      => 34,
							'tooltip_width_desktop' => 244,
							'tooltip_width_mobile'  => 149,
							'tooltip'               => 'Удаление пыли с мягкой мебели, открытых полок и декоративных элементов.',
						),
						array(
							'id'                    => 'surfaces',
							'x_desktop'             => 50,
							'y_desktop'             => 41,
							'x_mobile'              => 50,
							'y_mobile'              => 43,
							'tooltip_x_desktop'     => 43,
							'tooltip_y_desktop'     => 25,
							'tooltip_x_mobile'      => 38,
							'tooltip_y_mobile'      => 22,
							'tooltip_width_desktop' => 230,
							'tooltip_width_mobile'  => 149,
							'tooltip'               => 'Чистка столиков, тумб и доступных бытовых поверхностей в жилой зоне.',
						),
						array(
							'id'                    => 'floors',
							'x_desktop'             => 81,
							'y_desktop'             => 86,
							'x_mobile'              => 81,
							'y_mobile'              => 85,
							'tooltip_x_desktop'     => 63,
							'tooltip_y_desktop'     => 64,
							'tooltip_x_mobile'      => 55,
							'tooltip_y_mobile'      => 57,
							'tooltip_width_desktop' => 240,
							'tooltip_width_mobile'  => 149,
							'tooltip'               => 'Бережное мытье напольных покрытий и прилегающих плинтусов.',
						),
					),
				),
				array(
					'id'                => 'kitchen',
					'label'             => 'Кухня',
					'image'             => wipe_clean_service_single_image( 'static/images/section/service-includes/service-includes-room.png', 'Кухня после уборки' ),
					'active_hotspot_id' => 'facades',
					'hotspots'          => array(
						array(
							'id'                    => 'facades',
							'x_desktop'             => 18,
							'y_desktop'             => 32,
							'x_mobile'              => 16,
							'y_mobile'              => 31,
							'tooltip_x_desktop'     => 4,
							'tooltip_y_desktop'     => 17,
							'tooltip_x_mobile'      => 4,
							'tooltip_y_mobile'      => 17,
							'tooltip_width_desktop' => 244,
							'tooltip_width_mobile'  => 149,
							'tooltip'               => 'Удаление жирового налета с фасадов, ручек и кухонного фартука.',
						),
						array(
							'id'                    => 'countertop',
							'x_desktop'             => 45,
							'y_desktop'             => 24,
							'x_mobile'              => 42,
							'y_mobile'              => 22,
							'tooltip_x_desktop'     => 36,
							'tooltip_y_desktop'     => 10,
							'tooltip_x_mobile'      => 31,
							'tooltip_y_mobile'      => 8,
							'tooltip_width_desktop' => 232,
							'tooltip_width_mobile'  => 149,
							'tooltip'               => 'Тщательная обработка рабочих зон, столешниц и обеденных поверхностей.',
						),
						array(
							'id'                    => 'sink',
							'x_desktop'             => 67,
							'y_desktop'             => 46,
							'x_mobile'              => 66,
							'y_mobile'              => 47,
							'tooltip_x_desktop'     => 58,
							'tooltip_y_desktop'     => 31,
							'tooltip_x_mobile'      => 48,
							'tooltip_y_mobile'      => 31,
							'tooltip_width_desktop' => 236,
							'tooltip_width_mobile'  => 149,
							'tooltip'               => 'Очистка мойки, смесителя и зон, где быстрее всего накапливается налет.',
						),
						array(
							'id'                    => 'floor-kitchen',
							'x_desktop'             => 80,
							'y_desktop'             => 83,
							'x_mobile'              => 80,
							'y_mobile'              => 83,
							'tooltip_x_desktop'     => 71,
							'tooltip_y_desktop'     => 62,
							'tooltip_x_mobile'      => 53,
							'tooltip_y_mobile'      => 55,
							'tooltip_width_desktop' => 242,
							'tooltip_width_mobile'  => 149,
							'tooltip'               => 'Финишная влажная уборка пола с учетом материала покрытия.',
						),
					),
				),
				array(
					'id'                => 'bathroom',
					'label'             => 'Санузел',
					'image'             => wipe_clean_service_single_image( 'static/images/section/service-includes/service-includes-room.png', 'Санузел после уборки' ),
					'active_hotspot_id' => 'sanitaryware',
					'hotspots'          => array(
						array(
							'id'                    => 'sanitaryware',
							'x_desktop'             => 19,
							'y_desktop'             => 35,
							'x_mobile'              => 17,
							'y_mobile'              => 32,
							'tooltip_x_desktop'     => 4,
							'tooltip_y_desktop'     => 19,
							'tooltip_x_mobile'      => 4,
							'tooltip_y_mobile'      => 17,
							'tooltip_width_desktop' => 242,
							'tooltip_width_mobile'  => 149,
							'tooltip'               => 'Деликатная очистка сантехники, смесителей и зон с известковым налетом.',
						),
						array(
							'id'                    => 'mirrors',
							'x_desktop'             => 47,
							'y_desktop'             => 20,
							'x_mobile'              => 44,
							'y_mobile'              => 18,
							'tooltip_x_desktop'     => 38,
							'tooltip_y_desktop'     => 7,
							'tooltip_x_mobile'      => 31,
							'tooltip_y_mobile'      => 7,
							'tooltip_width_desktop' => 220,
							'tooltip_width_mobile'  => 149,
							'tooltip'               => 'Полировка зеркал и глянцевых поверхностей без разводов.',
						),
						array(
							'id'                    => 'tile-joints',
							'x_desktop'             => 65,
							'y_desktop'             => 49,
							'x_mobile'              => 66,
							'y_mobile'              => 49,
							'tooltip_x_desktop'     => 57,
							'tooltip_y_desktop'     => 33,
							'tooltip_x_mobile'      => 46,
							'tooltip_y_mobile'      => 33,
							'tooltip_width_desktop' => 228,
							'tooltip_width_mobile'  => 149,
							'tooltip'               => 'Проработка плитки, швов и проблемных участков с повышенной влажностью.',
						),
						array(
							'id'                    => 'bathroom-floor',
							'x_desktop'             => 79,
							'y_desktop'             => 84,
							'x_mobile'              => 79,
							'y_mobile'              => 84,
							'tooltip_x_desktop'     => 70,
							'tooltip_y_desktop'     => 63,
							'tooltip_x_mobile'      => 53,
							'tooltip_y_mobile'      => 56,
							'tooltip_width_desktop' => 238,
							'tooltip_width_mobile'  => 149,
							'tooltip'               => 'Завершающее мытье пола и нижних зон, где скапливается влага.',
						),
					),
				),
				array(
					'id'                => 'additional-zones',
					'label'             => 'Дополнительные зоны',
					'image'             => wipe_clean_service_single_image( 'static/images/section/service-includes/service-includes-room.png', 'Дополнительные зоны квартиры после уборки' ),
					'active_hotspot_id' => 'hallway',
					'hotspots'          => array(
						array(
							'id'                    => 'hallway',
							'x_desktop'             => 14,
							'y_desktop'             => 47,
							'x_mobile'              => 11,
							'y_mobile'              => 48,
							'tooltip_x_desktop'     => 2,
							'tooltip_y_desktop'     => 33,
							'tooltip_x_mobile'      => 4,
							'tooltip_y_mobile'      => 34,
							'tooltip_width_desktop' => 228,
							'tooltip_width_mobile'  => 149,
							'tooltip'               => 'Прихожая и зоны интенсивного прохода прорабатываются отдельно.',
						),
						array(
							'id'                    => 'balcony',
							'x_desktop'             => 28,
							'y_desktop'             => 22,
							'x_mobile'              => 34,
							'y_mobile'              => 17,
							'tooltip_x_desktop'     => 18,
							'tooltip_y_desktop'     => 10,
							'tooltip_x_mobile'      => 11,
							'tooltip_y_mobile'      => 10,
							'tooltip_width_desktop' => 220,
							'tooltip_width_mobile'  => 149,
							'tooltip'               => 'Балконные блоки и прилегающие поверхности очищаются по запросу.',
						),
						array(
							'id'                    => 'storage',
							'x_desktop'             => 51,
							'y_desktop'             => 42,
							'x_mobile'              => 50,
							'y_mobile'              => 43,
							'tooltip_x_desktop'     => 43,
							'tooltip_y_desktop'     => 26,
							'tooltip_x_mobile'      => 37,
							'tooltip_y_mobile'      => 24,
							'tooltip_width_desktop' => 234,
							'tooltip_width_mobile'  => 149,
							'tooltip'               => 'Ниши, шкафы и стеллажи включаются в маршрут уборки при согласовании.',
						),
						array(
							'id'                    => 'switches',
							'x_desktop'             => 81,
							'y_desktop'             => 85,
							'x_mobile'              => 81,
							'y_mobile'              => 85,
							'tooltip_x_desktop'     => 71,
							'tooltip_y_desktop'     => 63,
							'tooltip_x_mobile'      => 54,
							'tooltip_y_mobile'      => 56,
							'tooltip_width_desktop' => 234,
							'tooltip_width_mobile'  => 149,
							'tooltip'               => 'Аккуратная очистка выключателей, плинтусов и зон локального контакта.',
						),
					),
				),
			),
			'benefits_title'   => 'Преимущества уборки под ключ от нашей компании',
			'benefits_text'    => 'Взаимодействие с профессиональной клининговой командой строится на прозрачности, контроле качества и понятном результате.',
			'benefits_summary' => 'Продуманный сервис избавляет клиента от лишней бытовой рутины. Все задачи выполняются по четкому регламенту и с учетом состояния конкретного объекта.',
			'benefits_cards'   => array(
				array(
					'title' => 'Штатные клинеры с опытом',
					'text'  => 'На объект выезжают сотрудники с внутренней подготовкой и понятным стандартом работы.',
					'icon'  => wipe_clean_service_single_image( 'static/images/ui/feature-card-star-green.png', 'Штатные клинеры с опытом' ),
				),
				array(
					'title' => 'Поэтапный контроль качества',
					'text'  => 'Каждое техническое задание курируется ответственным менеджером.',
					'icon'  => wipe_clean_service_single_image( 'static/images/ui/feature-card-star-green.png', 'Поэтапный контроль качества' ),
				),
				array(
					'title' => 'Аккуратная уборка по чек-листу',
					'text'  => 'Процесс строится по внутреннему регламенту без пропусков важных зон.',
					'icon'  => wipe_clean_service_single_image( 'static/images/ui/feature-card-star-green.png', 'Аккуратная уборка по чек-листу' ),
				),
				array(
					'title' => 'Безопасные моющие средства',
					'text'  => 'Подбираем эффективные составы с учетом материалов и деликатных поверхностей.',
					'icon'  => wipe_clean_service_single_image( 'static/images/ui/feature-card-star-green.png', 'Безопасные моющие средства' ),
				),
				array(
					'title' => 'Ответственность за результат',
					'text'  => 'Фиксируем объем работ заранее и не размываем состав услуги по ходу выполнения.',
					'icon'  => wipe_clean_service_single_image( 'static/images/ui/feature-card-star-green.png', 'Ответственность за результат' ),
				),
			),
		),
		'order_cta'             => array(
			'acf_fc_layout'    => 'order_cta',
			'title'            => "Закажите уборку\nжилья прямо сейчас",
			'text'             => 'Для заказа достаточно короткой заявки или звонка. Мы быстро уточним объем работ, сориентируем по стоимости и предложим удобное время выезда.',
			'primary_action'   => wipe_clean_service_single_link( 'Заказать уборку под ключ' ),
			'secondary_action' => wipe_clean_service_single_link( 'Проконсультироваться' ),
			'image'            => wipe_clean_service_single_image( 'static/images/section/order-cta/order-cta-service-housing.png', 'Заказ уборки жилья в Москве' ),
		),
		'service_price'         => array(
			'acf_fc_layout' => 'service_price',
			'title_accent'  => 'Стоимость',
			'title_main'    => 'уборки',
			'title_break'   => 'под ключ',
			'text'          => 'Стоимость клининга зависит от площади, состояния помещения и состава работ. Мы считаем цену прозрачно и заранее озвучиваем итоговую рамку без скрытых доплат.',
			'lead_text'     => 'Факторы, влияющие на ценообразование:',
			'factors'       => array(
				array(
					'text' => 'Площадь объекта и фактическое количество комнат',
					'icon' => wipe_clean_service_single_image( 'static/images/ui/check-badge-icon.svg', 'Пункт списка' ),
				),
				array(
					'text' => 'Тип услуги: разовая, генеральная или регулярная уборка',
					'icon' => wipe_clean_service_single_image( 'static/images/ui/check-badge-icon.svg', 'Пункт списка' ),
				),
				array(
					'text' => 'Степень загрязнения и текущее состояние помещений',
					'icon' => wipe_clean_service_single_image( 'static/images/ui/check-badge-icon.svg', 'Пункт списка' ),
				),
				array(
					'text' => 'Дополнительные задачи: окна, техника, сложные покрытия',
					'icon' => wipe_clean_service_single_image( 'static/images/ui/check-badge-icon.svg', 'Пункт списка' ),
				),
			),
			'note_text'     => 'Окончательный расчет делается персонально под конкретную услугу и объем работ. Такой подход исключает переплату за ненужные опции.',
			'accent_text'   => 'Цена начинается от 10 000 ₽',
			'button'        => wipe_clean_service_single_link( 'Ознакомиться с ценами на уборку' ),
			'image'         => wipe_clean_service_single_image( 'static/images/section/service-price/service-price-cleaning-kit.png', 'Стоимость уборки квартиры под ключ' ),
		),
		'before_after_results' => array(
			'acf_fc_layout'   => 'before_after_results',
			'title'           => "Результаты нашего\nклининга до и после",
			'button_label'    => 'Больше',
			'loading_label'   => 'Загрузка...',
			'initial_desktop' => 6,
			'initial_mobile'  => 3,
			'step_desktop'    => 3,
			'step_mobile'     => 3,
			'items'           => array(
				array(
					'id'            => 'before-after-room-1',
					'before_image'  => wipe_clean_service_single_image( 'static/images/section/before-after-results/comparison-room-before.png', 'Комната до уборки' ),
					'after_image'   => wipe_clean_service_single_image( 'static/images/section/before-after-results/comparison-room-after.png', 'Комната после уборки' ),
					'alt'           => 'Сравнение комнаты до и после уборки',
					'control_label' => 'Сравнить комнату до и после уборки',
					'start'         => 53.16,
					'mobile_start'  => 52.78,
				),
				array(
					'id'            => 'before-after-kitchen-1',
					'before_image'  => wipe_clean_service_single_image( 'static/images/section/before-after-results/comparison-kitchen-before.png', 'Кухня до уборки' ),
					'after_image'   => wipe_clean_service_single_image( 'static/images/section/before-after-results/comparison-kitchen-after.png', 'Кухня после уборки' ),
					'alt'           => 'Сравнение кухни до и после уборки',
					'control_label' => 'Сравнить кухню до и после уборки',
					'start'         => 43.79,
					'mobile_start'  => 52.78,
				),
				array(
					'id'            => 'before-after-living-1',
					'before_image'  => wipe_clean_service_single_image( 'static/images/section/before-after-results/comparison-living-before.png', 'Гостиная до уборки' ),
					'after_image'   => wipe_clean_service_single_image( 'static/images/section/before-after-results/comparison-living-after.png', 'Гостиная после уборки' ),
					'alt'           => 'Сравнение гостиной до и после уборки',
					'control_label' => 'Сравнить гостиную до и после уборки',
					'start'         => 76.81,
					'mobile_start'  => 52.78,
				),
				array(
					'id'            => 'before-after-room-2',
					'before_image'  => wipe_clean_service_single_image( 'static/images/section/before-after-results/comparison-room-before.png', 'Комната до уборки' ),
					'after_image'   => wipe_clean_service_single_image( 'static/images/section/before-after-results/comparison-room-after.png', 'Комната после уборки' ),
					'alt'           => 'Сравнение комнаты до и после уборки',
					'control_label' => 'Сравнить комнату до и после уборки',
					'start'         => 53.16,
					'mobile_start'  => 52.78,
				),
				array(
					'id'            => 'before-after-kitchen-2',
					'before_image'  => wipe_clean_service_single_image( 'static/images/section/before-after-results/comparison-kitchen-before.png', 'Кухня до уборки' ),
					'after_image'   => wipe_clean_service_single_image( 'static/images/section/before-after-results/comparison-kitchen-after.png', 'Кухня после уборки' ),
					'alt'           => 'Сравнение кухни до и после уборки',
					'control_label' => 'Сравнить кухню до и после уборки',
					'start'         => 43.79,
					'mobile_start'  => 52.78,
				),
				array(
					'id'            => 'before-after-living-2',
					'before_image'  => wipe_clean_service_single_image( 'static/images/section/before-after-results/comparison-living-before.png', 'Гостиная до уборки' ),
					'after_image'   => wipe_clean_service_single_image( 'static/images/section/before-after-results/comparison-living-after.png', 'Гостиная после уборки' ),
					'alt'           => 'Сравнение гостиной до и после уборки',
					'control_label' => 'Сравнить гостиную до и после уборки',
					'start'         => 76.81,
					'mobile_start'  => 52.78,
				),
			),
		),
		'other_services'        => array(
			'acf_fc_layout' => 'other_services',
			'title'         => 'Другие услуги нашей компании',
			'text'          => 'Карточки в этом блоке подтягиваются автоматически из других записей услуг. Так архив и single-страницы всегда показывают актуальный список без ручного дублирования.',
			'button'        => wipe_clean_service_single_link( 'Все услуги', get_post_type_archive_link( 'wipe_service' ) ?: '#' ),
		),
		'faq'                   => array_merge(
			$faq,
			array(
				'acf_fc_layout' => 'faq',
				'title'         => 'Ответы на вопросы о клининге жилья',
			)
		),
		'contacts'              => array_merge(
			$contacts,
			array(
				'acf_fc_layout' => 'contacts',
				'title'         => 'Контакты',
			)
		),
	);
}

function wipe_clean_get_service_single_layout_order() {
	return array(
		'service_hero',
		'service_purpose',
		'service_wave_group',
		'order_cta',
		'service_price',
		'before_after_results',
		'other_services',
		'faq',
		'contacts',
	);
}

function wipe_clean_get_service_single_other_service_cards( $current_post_id ) {
	$current_post_id = (int) $current_post_id;
	$cards           = array();
	$posts           = get_posts(
		array(
			'post_type'      => 'wipe_service',
			'post_status'    => 'publish',
			'posts_per_page' => 8,
			'post__not_in'   => $current_post_id ? array( $current_post_id ) : array(),
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
			'order'          => 'ASC',
		)
	);

	foreach ( $posts as $post ) {
		$cards[] = array(
			'title'     => get_the_title( $post ),
			'price'     => function_exists( 'wipe_clean_get_service_card_price_value' ) ? wipe_clean_get_service_card_price_value( $post ) : '',
			'url'       => get_permalink( $post ),
			'image'     => function_exists( 'wipe_clean_get_service_primary_image' ) ? wipe_clean_get_service_primary_image( $post ) : array(),
			'className' => 'other-services__card',
		);
	}

	if ( ! empty( $cards ) ) {
		return $cards;
	}

	if ( ! function_exists( 'wipe_clean_get_front_page_default_service_items' ) ) {
		return array();
	}

	foreach ( wipe_clean_get_front_page_default_service_items() as $item ) {
		$cards[] = array(
			'title'     => (string) ( $item['title'] ?? '' ),
			'price'     => (string) ( $item['price'] ?? '' ),
			'url'       => get_post_type_archive_link( 'wipe_service' ) ?: '#',
			'image'     => $item['image'] ?? array(),
			'className' => 'other-services__card',
		);
	}

	return array_slice( $cards, 0, 8 );
}

function wipe_clean_get_service_single_sections( $post_id = 0 ) {
	$post_id         = $post_id ? (int) $post_id : ( function_exists( 'wipe_clean_get_current_service_post_id' ) ? wipe_clean_get_current_service_post_id() : (int) get_the_ID() );
	$defaults_map    = wipe_clean_get_service_single_default_sections_map();
	$rows            = function_exists( 'get_field' ) ? get_field( 'service_sections', $post_id ) : array();
	$rows_by_layout  = array();
	$archive_link    = get_post_type_archive_link( 'wipe_service' ) ?: '#';
	$service_title   = $post_id ? get_the_title( $post_id ) : '';
	$service_text    = $post_id ? wipe_clean_get_service_single_intro_text( $post_id ) : '';
	$service_image   = $post_id && function_exists( 'wipe_clean_get_service_primary_image' ) ? wipe_clean_get_service_primary_image( $post_id ) : array();
	$service_price   = $post_id ? wipe_clean_get_service_single_price_text( $post_id, (string) ( $defaults_map['service_price']['accent_text'] ?? '' ) ) : '';

	if ( is_array( $rows ) ) {
		foreach ( $rows as $row ) {
			$layout = $row['acf_fc_layout'] ?? '';

			if ( $layout ) {
				$rows_by_layout[ $layout ] = $row;
			}
		}
	}

	$sections = array();

	foreach ( wipe_clean_get_service_single_layout_order() as $layout ) {
		$section_defaults = $defaults_map[ $layout ] ?? array( 'acf_fc_layout' => $layout );
		$section_data     = $rows_by_layout[ $layout ] ?? array();
		$section          = 'service_wave_group' === $layout
			? wipe_clean_merge_service_wave_group_section( $section_defaults, $section_data )
			: wipe_clean_merge_section_with_fallback( $section_defaults, $section_data );

		if ( 'service_hero' === $layout ) {
			if ( '' !== $service_title && empty( $rows_by_layout[ $layout ]['kicker'] ) ) {
				$section['kicker'] = $service_title;
			}

			if ( '' !== $service_title && empty( $rows_by_layout[ $layout ]['title'] ) ) {
				$section['title'] = $service_title;
			}

			if ( '' !== $service_text && empty( $rows_by_layout[ $layout ]['text'] ) ) {
				$section['text'] = $service_text;
			}

			if ( ! empty( $service_image ) && empty( $rows_by_layout[ $layout ]['image'] ) ) {
				$section['image'] = $service_image;
			}

			$section['primary_action']   = wipe_clean_force_link_url( $section['primary_action'] ?? array(), '#popup-order-service' );
			$section['secondary_action'] = wipe_clean_force_link_url( $section['secondary_action'] ?? array(), '#popup-question' );
		}

		if ( 'order_cta' === $layout ) {
			$section['primary_action']   = wipe_clean_force_link_url( $section['primary_action'] ?? array(), '#popup-order-service' );
			$section['secondary_action'] = wipe_clean_force_link_url( $section['secondary_action'] ?? array(), '#popup-question' );
		}

		if ( 'service_price' === $layout ) {
			if ( '' !== $service_price && empty( $rows_by_layout[ $layout ]['accent_text'] ) ) {
				$section['accent_text'] = $service_price;
			}

			$section['button'] = wipe_clean_force_link_url( $section['button'] ?? array(), home_url( '/prices/' ) );
		}

		if ( 'other_services' === $layout ) {
			$section['cards'] = wipe_clean_get_service_single_other_service_cards( $post_id );

			if ( empty( $section['button']['url'] ) ) {
				$section['button']['url'] = $archive_link;
			}
		}

		$sections[] = $section;
	}

	return $sections;
}
