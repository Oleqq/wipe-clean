<?php
/**
 * Default content for the front page non-CPT sections.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_get_front_page_default_sections_map() {
	return array(
		'home_hero'       => array(
			'acf_fc_layout'     => 'home_hero',
			'kicker'            => 'Уборка любых помещений в любом районе Москвы по доступным ценам',
			'title'             => 'Клининговая компания по уборке квартир и домов в Москве и МО',
			'text'              => "Мечтаете о безупречном порядке?\nНачните с расчёта стоимости — форма ниже ждёт вас!",
			'area_title'        => 'Площадь',
			'area_options'      => array(
				array(
					'label' => 'однокомнатная',
					'value' => 'one-room',
				),
				array(
					'label' => '2-х комнатная',
					'value' => 'two-room',
				),
				array(
					'label' => '3/4-х комнатная',
					'value' => 'three-four-room',
				),
				array(
					'label' => '_ _ _ _ M<sup>2</sup>',
					'value' => 'custom-area',
				),
			),
			'service_label'     => 'Услуги',
			'service_options'   => array(
				array(
					'label' => 'Поддерживающая уборка',
					'value' => 'support-cleaning',
				),
				array(
					'label' => 'Генеральная уборка',
					'value' => 'general-cleaning',
				),
				array(
					'label' => 'Уборка после ремонта',
					'value' => 'after-renovation',
				),
				array(
					'label' => 'Мытьё окон',
					'value' => 'window-cleaning',
				),
			),
			'service_default'   => 'support-cleaning',
			'frequency_label'   => 'Регулярность',
			'frequency_options' => array(
				array(
					'label' => 'Разовая',
					'value' => 'single',
				),
				array(
					'label' => 'Еженедельно',
					'value' => 'weekly',
				),
				array(
					'label' => '2 раза в месяц',
					'value' => 'biweekly',
				),
				array(
					'label' => 'Ежемесячно',
					'value' => 'monthly',
				),
			),
			'frequency_default' => 'single',
			'name_label'        => 'Ваше имя',
			'name_placeholder'  => 'Введите имя и фамилию',
			'phone_label'       => 'Номер телефона',
			'phone_placeholder' => '+7 _ _ _ _ _ _ _ _ _ _',
			'agreement_text'    => 'Заполняя форму вы даете согласие на обработку персональных данных',
			'submit_text'       => 'Рассчитать стоимость',
			'tools_image'       => wipe_clean_theme_image( 'static/images/section/home-hero/hero-tools.png', '', 2048, 2048 ),
			'room_image'        => wipe_clean_theme_image( 'static/images/section/home-hero/hero-room.png', '', 1456, 816 ),
			'cleaner_image'     => wipe_clean_theme_image( 'static/images/section/home-hero/hero-cleaner.png', '', 928, 1232 ),
			'benefits'          => array(
				array(
					'icon'  => wipe_clean_theme_image( 'static/images/ui/home-hero-benefit-offers.svg', 'Выгодные предложения', 40, 40 ),
					'title' => 'Выгодные предложения',
				),
				array(
					'icon'  => wipe_clean_theme_image( 'static/images/ui/home-hero-benefit-team.svg', 'Квалифицированная команда', 40, 40 ),
					'title' => 'Квалифицированная команда',
				),
				array(
					'icon'  => wipe_clean_theme_image( 'static/images/ui/home-hero-benefit-discounts.svg', 'Скидки постоянным клиентам', 40, 40 ),
					'title' => 'Скидки постоянным клиентам',
				),
			),
		),
		'services_preview' => array(
			'acf_fc_layout'    => 'services_preview',
			'title'            => 'Наши услуги по уборке',
			'text'             => 'Мы выполняем клининг под разные задачи — от регулярной уборки квартиры до комплексной генеральной уборки после переезда. Все работы выполняются штатными клинерами по чек-листу, с контролем старшего специалиста.',
			'summary_text'     => '',
			'note_text'        => 'Итоговая стоимость зависит от площади помещения, необходимых услуг и индивидуальных предпочтений. Заполните заявку на сайте или свяжитесь с нами по телефону или в мессенджер, чтобы мы рассчитали стоимость клиринговых услуг.',
			'primary_action'   => wipe_clean_theme_link( 'services.html', 'Ознакомиться со всеми услугами' ),
			'secondary_action' => wipe_clean_theme_link( 'prices.html', 'Рассчитать стоимость уборки' ),
		),
		'home_wave_group' => array(
			'acf_fc_layout' => 'home_wave_group',
			'price_preview' => array(
				'image'            => wipe_clean_theme_image( 'static/images/section/price-preview/price-preview-bucket.png' ),
				'title_accent'     => 'Цены на',
				'title'            => 'клининг в Москве',
				'text'             => 'Мы придерживаемся прозрачного ценообразования: стоимость клининга зависит от площади, состояния помещения и выбранного формата уборки. Никаких скрытых доплат — итоговая цена согласовывается заранее.',
				'rows'             => array(
					array(
						'label' => 'Уборка квартир',
						'value' => 'от 80 руб./м²',
					),
					array(
						'label' => 'Уборка домов и коттеджей',
						'value' => 'от 80 руб./м²',
					),
					array(
						'label' => 'Уборка офисов',
						'value' => 'от 30 руб./м²',
					),
					array(
						'label' => 'Генеральная уборка',
						'value' => 'от 140 руб./м²',
					),
					array(
						'label' => 'Уборка после ремонта',
						'value' => 'от 160 руб./м²',
					),
					array(
						'label' => 'Поддерживающая уборка',
						'value' => 'от 80 руб./м²',
					),
					array(
						'label' => 'Срочная уборка',
						'value' => 'от 140 руб./м²',
					),
					array(
						'label' => 'Уборка перед въездом / после переезда',
						'value' => 'от 80 руб./м²',
					),
					array(
						'label' => 'Мытьё окон',
						'value' => 'от 300 руб./м²',
					),
				),
				'primary_button'   => wipe_clean_theme_link( 'prices.html', 'Ознакомиться со всеми ценами' ),
				'secondary_button' => wipe_clean_theme_link( 'services.html', 'Заказать клининг' ),
			),
			'work_steps'    => array(
				'title' => 'Этапы нашей работы',
				'text'  => 'Мы выстроили процесс клининга так, чтобы для клиента он был максимально простым и понятным — от первого обращения до приёмки результата.',
				'items' => array(
					array(
						'number' => '01',
						'title'  => 'Заявка и консультация',
						'text'   => 'Вы оставляете заявку, а наш менеджер уточняет детали уборки и отвечает на ваши вопросы.',
					),
					array(
						'number' => '02',
						'title'  => 'Расчёт стоимости и согласование',
						'text'   => 'Мы рассчитываем стоимость клининга и согласовываем её с вами до начала работ.',
					),
					array(
						'number' => '03',
						'title'  => 'Согласование даты выезда',
						'text'   => 'Подбираем удобную для вас дату и время проведения уборки.',
					),
					array(
						'number' => '04',
						'title'  => 'Прибытие команды в день уборки со всеми средствами',
						'text'   => 'В назначенный день команда приезжает вовремя со всем необходимым инвентарём и безопасной химией.',
					),
					array(
						'number' => '05',
						'title'  => 'Уборка по чек-листу',
						'text'   => 'Клинеры выполняют уборку по чек-листу под контролем старшего специалиста.',
					),
					array(
						'number' => '06',
						'title'  => 'Приёмка работ и оплата',
						'text'   => 'Вы принимаете результат, и после этого производится оплата удобным для вас способом.',
					),
				),
			),
			'quote_request' => array(
				'image'             => wipe_clean_theme_image( 'static/images/section/quote-request/Mask group.png' ),
				'title'             => 'Рассчитайте стоимость клининга в Москве уже сейчас',
				'text'              => 'Оставьте заявку, и наш менеджер свяжется с вами в течение 5–15 минут, уточнит детали и рассчитает точную стоимость клининговых услуг под вашу задачу.',
				'name_label'        => 'Ваше имя',
				'name_placeholder'  => 'Введите имя и фамилию',
				'phone_label'       => 'Номер телефона',
				'phone_placeholder' => '+7 _ _ _ _ _ _ _ _ _ _',
				'agreement_text'    => 'Заполняя форму вы даете согласие на обработку персональных данных',
				'submit_text'       => 'Рассчитать стоимость',
			),
		),
		'company_preview' => array(
			'acf_fc_layout'  => 'company_preview',
			'title'          => 'Клининговая компания ВАЙП–Клин, ваш надёжный помощник',
			'text_primary'   => 'Мы подходим к уборке как к заботе о доме. Для нас важно не просто навести чистоту, а сделать это аккуратно, безопасно и с уважением к вашему пространству. Именно поэтому все работы выполняются штатной командой, прошедшей обучение и внутреннюю проверку качества.',
			'text_secondary' => 'Каждая уборка контролируется старшим клинером, а если результат вас не устроит — мы переделаем работу за свой счёт. Такой подход позволяет нам выстраивать долгосрочные и доверительные отношения с клиентами.',
			'media_image'    => wipe_clean_theme_image( 'static/images/section/company-preview/company-preview-media.png', 'Команда ВАЙП–Клин за работой' ),
			'logo_image'     => wipe_clean_theme_image( 'static/images/section/company-preview/Group 19.png', 'ВАЙП–Клин' ),
			'benefits'       => array(
				array(
					'icon'  => wipe_clean_theme_image( 'static/images/section/company-preview/icon-experience.svg' ),
					'title' => 'Опытные клинеры',
					'text'  => 'Штатные специалисты с регулярным обучением',
				),
				array(
					'icon'  => wipe_clean_theme_image( 'static/images/section/company-preview/icon-guarantee.svg' ),
					'title' => 'Гарантия на клининг',
					'text'  => 'Переделка за наш счёт',
				),
				array(
					'icon'  => wipe_clean_theme_image( 'static/images/section/company-preview/icon-quality.svg' ),
					'title' => 'Высокое качество услуг',
					'text'  => 'Уборка по чек-листу',
				),
				array(
					'icon'  => wipe_clean_theme_image( 'static/images/section/company-preview/icon-discounts.svg' ),
					'title' => 'Акции и скидки',
					'text'  => 'Для новых и постоянных клиентов',
				),
				array(
					'icon'  => wipe_clean_theme_image( 'static/images/section/company-preview/icon-pricing.svg' ),
					'title' => 'Прозрачные цены',
					'text'  => 'Честные цены без сюрпризов',
				),
				array(
					'icon'  => wipe_clean_theme_image( 'static/images/section/company-preview/icon-chemistry.svg' ),
					'title' => 'Безопасная химия',
					'text'  => 'Гипоаллергенные средства для детей и животных',
				),
			),
		),
		'reviews_preview' => array(
			'acf_fc_layout'    => 'reviews_preview',
			'title'            => 'Отзывы наших клиентов',
			'text'             => 'Нам доверяют уборку квартир, домов и офисов в Москве, а многие клиенты обращаются к нам регулярно и рекомендуют ВАЙП–Клин своим близким.',
			'primary_action'   => wipe_clean_theme_link( 'reviews.html', 'Ознакомиться с другими отзывами' ),
			'secondary_action' => wipe_clean_theme_link( 'reviews.html', 'Оставить отзыв о компании' ),
		),
		'gallery_preview' => array(
			'acf_fc_layout' => 'gallery_preview',
			'title'         => 'Галерея наших работ по уборке квартир, домов и офисов',
			'top_items'     => array(
				array(
					'id'      => 'gallery-top-1',
					'type'    => 'image',
					'image'   => wipe_clean_theme_image( 'static/images/section/gallery-preview/gallery-row-1-photo-1.png', 'Уборка кухни крупным планом' ),
					'caption' => 'Работа команды ВАЙП–Клин',
				),
				array(
					'id'      => 'gallery-top-2',
					'type'    => 'image',
					'image'   => wipe_clean_theme_image( 'static/images/section/gallery-preview/gallery-row-1-photo-2.png', 'Влажная уборка дома' ),
					'caption' => 'Уборка квартир и домов',
				),
				array(
					'id'      => 'gallery-top-3',
					'type'    => 'image',
					'image'   => wipe_clean_theme_image( 'static/images/section/gallery-preview/gallery-row-1-photo-3.png', 'Светлая гостиная после клининга' ),
					'caption' => 'Результат уборки квартиры',
				),
				array(
					'id'        => 'gallery-top-4',
					'type'      => 'video',
					'image'     => wipe_clean_theme_image( 'static/images/section/gallery-preview/gallery-row-1-video-poster.png', 'Видео процесса уборки' ),
					'video_url' => wipe_clean_asset_uri( 'static/images/section/gallery-preview/test-video.mp4' ),
					'caption'   => 'Видео процесса уборки',
				),
			),
			'bottom_items'  => array(
				array(
					'id'      => 'gallery-bottom-1',
					'type'    => 'image',
					'image'   => wipe_clean_theme_image( 'static/images/section/gallery-preview/gallery-row-2-photo-1.png', 'Гостиная после уборки' ),
					'caption' => 'Чистый интерьер после клининга',
				),
				array(
					'id'        => 'gallery-bottom-2',
					'type'      => 'video',
					'image'     => wipe_clean_theme_image( 'static/images/section/gallery-preview/gallery-row-2-video-poster.png', 'Видео работы клинеров' ),
					'video_url' => wipe_clean_asset_uri( 'static/images/section/gallery-preview/test-video.mp4' ),
					'caption'   => 'Видео работы команды ВАЙП–Клин',
				),
				array(
					'id'      => 'gallery-bottom-3',
					'type'    => 'image',
					'image'   => wipe_clean_theme_image( 'static/images/section/gallery-preview/gallery-row-2-photo-2.png', 'Химчистка мягкой мебели' ),
					'caption' => 'Химчистка и глубокая уборка',
				),
				array(
					'id'      => 'gallery-bottom-4',
					'type'    => 'image',
					'image'   => wipe_clean_theme_image( 'static/images/section/gallery-preview/gallery-row-2-photo-3.png', 'Инвентарь для клининга' ),
					'caption' => 'Профессиональный инвентарь и безопасная химия',
				),
			),
		),
		'faq'             => array(
			'acf_fc_layout' => 'faq',
			'title'         => 'Ответы на вопросы о клининге в Москве',
			'items'         => array(
				array(
					'question' => 'Как рассчитывается стоимость клининга квартиры в Москве?',
					'answer'   => 'Итоговая стоимость зависит от площади, степени загрязнения и выбранного набора услуг. Финальную цену мы рассчитываем заранее и согласовываем с вами до выезда команды.',
				),
				array(
					'question' => 'Можно ли находиться дома во время уборки квартиры?',
					'answer'   => 'Да, конечно. Вы можете оставаться дома во время клининга или передать доступ удобным для вас способом. Все детали согласовываем заранее.',
				),
				array(
					'question' => 'Сколько времени занимает уборка квартиры или дома?',
					'answer'   => 'Срок зависит от площади помещения, формата уборки и количества дополнительных задач. После уточнения деталей мы заранее ориентируем вас по времени до подтверждения заказа.',
				),
				array(
					'question' => 'Какие средства используются при уборке?',
					'answer'   => 'Мы используем профессиональные и безопасные средства, подходящие для жилых помещений. При необходимости подбираем деликатную химию для детей, животных и чувствительных поверхностей.',
				),
				array(
					'question' => 'Что делать, если результат уборки не устроил?',
					'answer'   => 'Если у вас останутся замечания, сообщите нам сразу после приемки. Мы оперативно вернемся и исправим недочеты за свой счет.',
				),
				array(
					'question' => 'Работаете ли вы в выходные и праздники?',
					'answer'   => 'Да, мы принимаем заказы на выходные и праздничные дни. Лучше бронировать дату заранее, особенно в периоды высокой загрузки.',
				),
				array(
					'question' => 'Возможна ли срочная уборка в Москве?',
					'answer'   => 'Да, при наличии свободной команды можем организовать срочный выезд в ближайшее доступное время. Уточнить возможность можно по телефону или через форму заявки.',
				),
				array(
					'question' => 'Как производится оплата клининга?',
					'answer'   => 'Оплатить можно удобным для вас способом после согласования стоимости и выполнения работ. Все условия оплаты озвучиваем заранее, без скрытых доплат.',
				),
			),
		),
		'contacts'        => array(
			'acf_fc_layout'          => 'contacts',
			'title'                  => 'Контакты клининговой компании ВАЙП–Клин',
			'phone_label'            => 'Номер телефона',
			'phone_value'            => '+7 980 163 6101',
			'socials_label'          => 'Мессенджеры и соцсети',
			'social_links'           => array(
				array(
					'label' => 'Telegram',
					'url'   => '#',
					'icon'  => wipe_clean_theme_image( 'static/images/section/contacts/akar-icons_telegram-fill.png' ),
				),
				array(
					'label' => 'WhatsApp',
					'url'   => '#',
					'icon'  => wipe_clean_theme_image( 'static/images/section/contacts/Vector.png' ),
				),
				array(
					'label' => 'VK',
					'url'   => '#',
					'icon'  => wipe_clean_theme_image( 'static/images/section/contacts/Vector (1).png' ),
				),
			),
			'email_label'            => 'Электронная почта',
			'email_value'            => 'MAILBOX@WIPECLEAN.RU',
			'form_title'             => 'Форма заявки',
			'form_name_label'        => 'Ваше имя',
			'form_name_placeholder'  => 'Введите имя и фамилию',
			'form_phone_label'       => 'Номер телефона',
			'form_phone_placeholder' => '+7 _ _ _ _ _ _ _ _ _ _',
			'agreement_text'         => 'Заполняя форму вы даете согласие на обработку персональных данных',
			'submit_text'            => 'Узнать стоимость клининга',
			'submit_text_mobile'     => 'Рассчитать стоимость',
		),
	);
}

function wipe_clean_get_front_page_section_defaults( $layout ) {
	$defaults_map = wipe_clean_get_front_page_default_sections_map();

	if ( isset( $defaults_map[ $layout ] ) ) {
		return $defaults_map[ $layout ];
	}

	return array(
		'acf_fc_layout' => $layout,
	);
}
