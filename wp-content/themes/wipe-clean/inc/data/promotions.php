<?php
/**
 * Default content for the promotions archive.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_get_promotions_default_items' ) ) {
	function wipe_clean_get_promotions_default_items() {
		return array(
			array(
				'seed_key'        => 'promotion-first-order',
				'title'           => 'Скидка на первый заказ 10%',
				'image'           => wipe_clean_theme_image( 'static/images/section/promotions-archive/promotion-first-order.png', 'Скидка на первый заказ 10%' ),
				'popupText'       => array(
					'Если вы обращаетесь в ВАЙП-Клин впервые, мы дарим скидку 10% на первый заказ. Это удобный способ познакомиться с нашим сервисом и оценить качество уборки без лишних затрат.',
					'Скидка применяется при подтверждении заявки менеджером и действует на выбранный формат клининга, согласованный заранее.',
				),
				'popupConditions' => array(
					'Скидка действует только на первый заказ',
					'Применяется после подтверждения заявки менеджером',
					'Не суммируется с другими акциями',
				),
			),
			array(
				'seed_key'        => 'promotion-pensioners',
				'title'           => 'Скидка пенсионерам',
				'image'           => wipe_clean_theme_image( 'static/images/section/promotions-archive/promotion-pensioners.png', 'Скидка пенсионерам' ),
				'popupText'       => array(
					'Для пенсионеров действует специальная скидка на услуги клининга. Мы стараемся делать уборку доступнее и удобнее, сохраняя то же качество и внимание к деталям.',
					'Чтобы воспользоваться предложением, сообщите менеджеру о скидке при оформлении заявки.',
				),
				'popupConditions' => array(
					'Скидка предоставляется при подтверждении статуса пенсионера',
					'Распространяется на основные услуги клининга',
					'Не суммируется с другими акциями',
				),
			),
			array(
				'seed_key'        => 'promotion-review',
				'title'           => 'Скидка за отзыв',
				'image'           => wipe_clean_theme_image( 'static/images/section/promotions-archive/promotion-review.png', 'Скидка за отзыв' ),
				'popupText'       => array(
					'После уборки вы можете оставить отзыв о нашей работе и получить скидку на следующий заказ. Нам важно видеть обратную связь и улучшать сервис на основе реальных впечатлений клиентов.',
					'Отзыв можно оставить в любом удобном формате: текстом, фото или видео.',
				),
				'popupConditions' => array(
					'Скидка действует на следующий заказ',
					'Нужно оставить реальный отзыв о выполненной уборке',
					'Размер скидки подтверждается менеджером',
				),
			),
			array(
				'seed_key'        => 'promotion-general-cleaning',
				'title'           => 'Скидка на генеральную уборку',
				'image'           => wipe_clean_theme_image( 'static/images/section/promotions-archive/promotion-first-order.png', 'Скидка на генеральную уборку' ),
				'popupText'       => array(
					'При заказе генеральной уборки вы можете получить специальную цену на расширенный комплекс работ. Это удобно, если нужно быстро привести квартиру или дом в идеальное состояние.',
					'Менеджер подскажет актуальный состав работ и рассчитает точную стоимость со скидкой.',
				),
				'popupConditions' => array(
					'Скидка действует на формат генеральной уборки',
					'Финальная стоимость зависит от площади и состояния объекта',
					'Предложение актуально при подтверждении заявки',
				),
			),
			array(
				'seed_key'        => 'promotion-regulars',
				'title'           => 'Скидка постоянным клиентам',
				'image'           => wipe_clean_theme_image( 'static/images/section/promotions-archive/promotion-pensioners.png', 'Скидка постоянным клиентам' ),
				'popupText'       => array(
					'Для клиентов, которые регулярно заказывают уборку, мы предлагаем персональные условия и выгодные цены. Это позволяет сохранить привычный уровень сервиса и при этом оптимизировать бюджет.',
					'Условия зависят от частоты уборок и выбранного пакета услуг.',
				),
				'popupConditions' => array(
					'Скидка зависит от регулярности обращений',
					'Персональные условия согласовываются с менеджером',
					'Доступна для поддерживающей и генеральной уборки',
				),
			),
			array(
				'seed_key'        => 'promotion-two-services',
				'title'           => 'Скидка при заказе двух услуг',
				'image'           => wipe_clean_theme_image( 'static/images/section/promotions-archive/promotion-review.png', 'Скидка при заказе двух услуг' ),
				'popupText'       => array(
					'Если вам нужно сразу несколько услуг, мы предложим более выгодную стоимость на комплексный заказ. Это удобно, когда вместе с уборкой нужны мойка окон, химчистка или дополнительные работы.',
					'Комбинацию услуг и точную скидку менеджер подтвердит при оформлении заявки.',
				),
				'popupConditions' => array(
					'Скидка действует при заказе двух и более услуг',
					'Финальная стоимость рассчитывается индивидуально',
					'Предложение не суммируется с другими акциями',
				),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_promotions_archive_default_items' ) ) {
	function wipe_clean_get_promotions_archive_default_items() {
		return wipe_clean_get_promotions_default_items();
	}
}

if ( ! function_exists( 'wipe_clean_get_promotions_default_faq_items' ) ) {
	function wipe_clean_get_promotions_default_faq_items() {
		return array(
			array(
				'question' => 'Как рассчитывается стоимость клининга квартиры в Москве?',
				'answer'   => 'Стоимость зависит от площади, степени загрязнения и выбранного набора услуг. Мы заранее называем ориентир по цене и фиксируем состав работ до выезда команды.',
			),
			array(
				'question' => 'Сколько времени занимает уборка квартиры или дома?',
				'answer'   => 'Точное время зависит от метража и формата уборки. После короткого уточнения деталей менеджер подскажет реалистичный интервал и удобное окно для визита.',
			),
			array(
				'question' => 'Что делать, если результат уборки не устроил?',
				'answer'   => 'Если после приёмки у вас останутся замечания, сообщите нам сразу. Мы оперативно вернёмся и исправим недочёты за свой счёт.',
			),
			array(
				'question' => 'Возможна ли срочная уборка в Москве?',
				'answer'   => 'Да, при наличии свободной бригады мы можем организовать срочный выезд. Лучше уточнить доступность по телефону или через форму на сайте.',
			),
			array(
				'question' => 'Можно ли находиться дома во время уборки квартиры?',
				'answer'   => 'Да, вы можете оставаться дома или передать доступ удобным способом. Все детали по присутствию и доступу согласовываются заранее.',
			),
			array(
				'question' => 'Какие средства используются при уборке?',
				'answer'   => 'Мы используем профессиональные и безопасные средства, подходящие под бытовые и деликатные поверхности. При необходимости подбираем более щадящий набор химии.',
			),
			array(
				'question' => 'Работаете ли вы в выходные и праздники?',
				'answer'   => 'Да, принимаем заявки на выходные и праздничные дни. В периоды высокой загрузки лучше бронировать удобную дату заранее.',
			),
			array(
				'question' => 'Как производится оплата клининга?',
				'answer'   => 'Оплата производится удобным для вас способом после согласования стоимости. Все условия обсуждаются заранее, без скрытых доплат.',
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_promotions_default_sections_map' ) ) {
	function wipe_clean_get_promotions_default_sections_map() {
		$company_preview = function_exists( 'wipe_clean_get_front_page_section_defaults' )
			? wipe_clean_get_front_page_section_defaults( 'company_preview' )
			: array( 'acf_fc_layout' => 'company_preview' );

		$contacts_defaults = function_exists( 'wipe_clean_get_front_page_section_defaults' )
			? wipe_clean_get_front_page_section_defaults( 'contacts' )
			: array( 'acf_fc_layout' => 'contacts' );

		$faq_defaults = function_exists( 'wipe_clean_get_front_page_section_defaults' )
			? wipe_clean_get_front_page_section_defaults( 'faq' )
			: array( 'acf_fc_layout' => 'faq' );

		return array(
			'promotions_archive' => array(
				'acf_fc_layout'        => 'promotions_archive',
				'kicker'               => 'Акции',
				'title'                => "Скидки и акции\nна клининг в Москве",
				'primary_action'       => wipe_clean_theme_link( 'services.html', 'Ознакомиться с услугами' ),
				'button_label'         => 'Показать ещё',
				'button_loading_label' => 'Загрузка...',
				'initial_desktop'      => 3,
				'initial_mobile'       => 3,
				'step_desktop'         => 3,
				'step_mobile'          => 3,
				'items'                => wipe_clean_get_promotions_archive_default_items(),
			),
			'company_preview'    => $company_preview,
			'contacts'           => array_merge(
				$contacts_defaults,
				array(
					'acf_fc_layout'      => 'contacts',
					'id_prefix'          => 'promotions-contacts',
					'title'              => 'Закажите клининг прямо сейчас и получите персональное предложение',
					'submit_text'        => 'Задать вопрос',
					'submit_text_mobile' => 'Задать вопрос',
				)
			),
			'faq'                => array_merge(
				$faq_defaults,
				array(
					'acf_fc_layout'             => 'faq',
					'title'                     => 'Ответы на вопросы о клининге в Москве',
					'items'                     => wipe_clean_get_promotions_default_faq_items(),
					'initial_open_index'        => -1,
					'mobile_initial_open_index' => -1,
				)
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_promotions_archive_layout_order' ) ) {
	function wipe_clean_get_promotions_archive_layout_order() {
		return array(
			'promotions_archive',
			'company_preview',
			'contacts',
			'faq',
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_promotions_archive_section_defaults' ) ) {
	function wipe_clean_get_promotions_archive_section_defaults( $layout ) {
		$defaults_map = wipe_clean_get_promotions_default_sections_map();

		if ( isset( $defaults_map[ $layout ] ) ) {
			return $defaults_map[ $layout ];
		}

		return array(
			'acf_fc_layout' => $layout,
		);
	}
}
