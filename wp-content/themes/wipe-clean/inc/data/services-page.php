<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_services_page_image( $path, $alt = '' ) {
	$path = (string) $path;
	$alt  = (string) $alt;

	if ( function_exists( 'wipe_clean_theme_image' ) ) {
		return wipe_clean_theme_image( $path, $alt );
	}

	return array(
		'path'   => ltrim( $path, '/' ),
		'alt'    => $alt,
		'width'  => 0,
		'height' => 0,
	);
}

function wipe_clean_services_page_link( $title, $url = '#', $target = '' ) {
	return array(
		'title'  => (string) $title,
		'url'    => (string) $url,
		'target' => (string) $target,
	);
}

function wipe_clean_get_services_page_default_service_items() {
	return array(
		array(
			'seed_key'   => 'service-apartment-cleaning',
			'order'      => 10,
			'class_name' => 'service-teaser-card--apartment',
			'title'      => 'Уборка квартир',
			'text'       => 'Качественная уборка квартир в Москве с бережной проработкой жилых комнат, кухни, санузлов и всех бытовых зон.',
			'url'        => '#',
			'link_label' => 'Подробнее',
			'image'      => wipe_clean_services_page_image( 'static/images/section/services-intro/service-teaser-apartment.png', 'Уборка квартир' ),
		),
		array(
			'seed_key'   => 'service-house-cleaning',
			'order'      => 20,
			'class_name' => 'service-teaser-card--cottage',
			'title'      => 'Уборка домов и коттеджей',
			'text'       => 'Комплексный клининг частных домов и коттеджей с учетом больших площадей, лестниц, террас и сложных покрытий.',
			'url'        => '#',
			'link_label' => 'Подробнее',
			'image'      => wipe_clean_services_page_image( 'static/images/section/services-intro/service-teaser-cottage-base.png', 'Уборка домов и коттеджей' ),
		),
		array(
			'seed_key'   => 'service-office-cleaning',
			'order'      => 30,
			'class_name' => 'service-teaser-card--office',
			'title'      => 'Уборка офисов',
			'text'       => 'Уборка рабочих пространств, переговорных, кабинетов и общих зон с удобным графиком обслуживания.',
			'url'        => '#',
			'link_label' => 'Подробнее',
			'image'      => wipe_clean_services_page_image( 'static/images/section/services-intro/service-teaser-office.png', 'Уборка офисов' ),
		),
		array(
			'seed_key'   => 'service-general-cleaning',
			'order'      => 40,
			'class_name' => 'service-teaser-card--general',
			'title'      => 'Генеральная уборка',
			'text'       => 'Глубокая уборка всего объекта с вниманием к сложным зонам, бытовым загрязнениям и деталям интерьера.',
			'url'        => '#',
			'link_label' => 'Подробнее',
			'image'      => wipe_clean_services_page_image( 'static/images/section/services-intro/service-teaser-general-base.png', 'Генеральная уборка' ),
		),
		array(
			'seed_key'   => 'service-maintenance-cleaning',
			'order'      => 50,
			'class_name' => 'service-teaser-card--maintenance',
			'title'      => 'Поддерживающая уборка',
			'text'       => 'Регулярное наведение порядка для квартир и домов, чтобы пространство всегда оставалось чистым и свежим.',
			'url'        => '#',
			'link_label' => 'Подробнее',
			'image'      => wipe_clean_services_page_image( 'static/images/section/services-intro/service-teaser-maintenance.png', 'Поддерживающая уборка' ),
		),
		array(
			'seed_key'   => 'service-urgent-cleaning',
			'order'      => 60,
			'class_name' => 'service-teaser-card--general',
			'title'      => 'Срочная уборка',
			'text'       => 'Оперативный выезд для случаев, когда нужно быстро привести помещение в аккуратное состояние.',
			'url'        => '#',
			'link_label' => 'Подробнее',
			'image'      => wipe_clean_services_page_image( 'static/images/section/services-intro/service-teaser-general-base.png', 'Срочная уборка' ),
		),
		array(
			'seed_key'   => 'service-daily-rental-cleaning',
			'order'      => 70,
			'class_name' => 'service-teaser-card--apartment',
			'title'      => 'Уборка квартир посуточно',
			'text'       => 'Подготовка квартир к следующему заезду гостей: чистота, свежесть и аккуратный внешний вид без лишней суеты.',
			'url'        => '#',
			'link_label' => 'Подробнее',
			'image'      => wipe_clean_services_page_image( 'static/images/section/services-intro/service-teaser-apartment.png', 'Уборка квартир посуточно' ),
		),
		array(
			'seed_key'   => 'service-window-cleaning',
			'order'      => 80,
			'class_name' => 'service-teaser-card--office',
			'title'      => 'Мытьё окон',
			'text'       => 'Чистые стекла, рамы и подоконники без разводов, с аккуратной работой внутри помещения.',
			'url'        => '#',
			'link_label' => 'Подробнее',
			'image'      => wipe_clean_services_page_image( 'static/images/section/services-intro/service-teaser-office.png', 'Мытьё окон' ),
		),
		array(
			'seed_key'   => 'service-post-renovation-cleaning',
			'order'      => 90,
			'class_name' => 'service-teaser-card--cottage',
			'title'      => 'Уборка после ремонта',
			'text'       => 'Удаление строительной пыли и следов ремонта, чтобы быстро перевести объект в готовое жилое состояние.',
			'url'        => '#',
			'link_label' => 'Подробнее',
			'image'      => wipe_clean_services_page_image( 'static/images/section/services-intro/service-teaser-cottage-base.png', 'Уборка после ремонта' ),
		),
	);
}

function wipe_clean_get_services_page_default_service_cards() {
	return wipe_clean_get_services_page_default_service_items();
}

function wipe_clean_get_services_page_default_sections_map() {
	return array(
		'services_intro'    => array(
			'acf_fc_layout'           => 'services_intro',
			'hero_kicker'             => 'Наши клининговые услуги',
			'hero_title'              => 'Услуги клининговой компании в Москве',
			'hero_text'               => 'ВАЙП-Клин освобождает от бытовых забот и помогает поддерживать в помещении аккуратный порядок без лишней рутины.',
			'hero_primary_action'     => wipe_clean_services_page_link( 'Заказать клининг' ),
			'hero_secondary_action'   => wipe_clean_services_page_link( 'Хочу задать вопрос услуги' ),
			'hero_decor_image'        => wipe_clean_services_page_image( 'static/images/section/services-hero/services-hero-decor.png', 'Инвентарь для уборки' ),
			'hero_cleaner_image'      => wipe_clean_services_page_image( 'static/images/section/services-hero/services-hero-cleaner.png', 'Сотрудница клининговой компании' ),
			'hero_interior_image'     => wipe_clean_services_page_image( 'static/images/section/services-hero/services-hero-interior.png', 'Интерьер квартиры' ),
			'overview_title'          => 'Наши услуги по уборке в квартирах и не только',
			'overview_summary'        => 'Мы работаем с квартирами, домами, офисами и частным сектором, подбирая удобный формат под ваш запрос.',
			'overview_body'           => array(
				array(
					'text' => 'Все представленные услуги оказываются комплексно и подразумевают внимательную проработку каждой зоны помещения.',
				),
				array(
					'text' => 'Можно заказать как разовое наведение чистоты, так и постоянное сопровождение по удобному графику.',
				),
				array(
					'text' => 'Мы заранее обсуждаем объем работ, чтобы вы точно понимали, что входит в уборку и какой результат получите.',
				),
			),
			'overview_more_label'     => 'Ещё',
			'overview_less_label'     => 'Свернуть',
			'overview_image'          => wipe_clean_services_page_image( 'static/images/section/services-intro/services-intro-tools2.png', 'Инвентарь для уборки' ),
			'footer_primary_action'   => wipe_clean_services_page_link( 'Рассчитать стоимость уборки' ),
			'footer_secondary_action' => wipe_clean_services_page_link( 'Задать вопрос' ),
		),
		'services_benefits' => array(
			'acf_fc_layout'   => 'services_benefits',
			'title'           => 'Почему стоит заказать уборку в нашей компании',
			'text'            => 'Для нас важен не только результат, но и ощущение спокойствия клиента на всех этапах. Мы работаем аккуратно, прозрачно и без лишней суеты.',
			'cards'           => array(
				array(
					'title' => 'Прозрачная стоимость',
					'text'  => 'Сразу согласовываем объем работ и честно объясняем, из чего складывается цена.',
					'icon'  => wipe_clean_services_page_image( 'static/images/section/company-preview/icon-pricing.svg', 'Прозрачная стоимость' ),
				),
				array(
					'title' => 'Контроль качества',
					'text'  => 'Проверяем результат после уборки и следим, чтобы работа соответствовала стандартам сервиса.',
					'icon'  => wipe_clean_services_page_image( 'static/images/section/company-preview/icon-quality.svg', 'Контроль качества' ),
				),
				array(
					'title' => 'Безопасная химия',
					'text'  => 'Используем профессиональные средства, которые подходят для повседневного ухода за помещениями.',
					'icon'  => wipe_clean_services_page_image( 'static/images/section/company-preview/icon-chemistry.svg', 'Безопасная химия' ),
				),
				array(
					'title' => 'Опытная команда',
					'text'  => 'На объект приезжают специалисты, которые умеют аккуратно работать в жилых и коммерческих пространствах.',
					'icon'  => wipe_clean_services_page_image( 'static/images/section/company-preview/icon-experience.svg', 'Опытная команда' ),
				),
			),
			'offer_title'     => 'Заказывайте клининг в Москве сейчас и получите специальные предложения',
			'offer_text'      => 'При расчете стоимости мы обязательно учитываем размер помещений и ваши пожелания по результату. Достаточно оставить заявку, и мы поможем выбрать удобный формат.',
			'offer_button'    => wipe_clean_services_page_link( 'Заказать клининг' ),
			'checklist_title' => 'Что важно знать перед вызовом клининга',
			'checklist_text'  => 'Перед оформлением заказа полезно заранее определить объем работ и рассказать о нюансах помещения. Мы со своей стороны всегда держим процесс прозрачным:',
			'checklist_items' => array(
				array(
					'text' => 'Специалисты приезжают со своим инвентарем и нужной химией.',
					'icon' => wipe_clean_services_page_image( 'static/images/ui/check-badge-icon.svg', 'Пункт списка' ),
				),
				array(
					'text' => 'Вы можете находиться на объекте во время уборки или уехать по делам.',
					'icon' => wipe_clean_services_page_image( 'static/images/ui/check-badge-icon.svg', 'Пункт списка' ),
				),
				array(
					'text' => 'Оплата производится после завершения и вашей проверки результата.',
					'icon' => wipe_clean_services_page_image( 'static/images/ui/check-badge-icon.svg', 'Пункт списка' ),
				),
				array(
					'text' => 'Мы внимательно относимся к вещам, мебели и деликатным поверхностям.',
					'icon' => wipe_clean_services_page_image( 'static/images/ui/check-badge-icon.svg', 'Пункт списка' ),
				),
			),
			'visual_image'    => wipe_clean_services_page_image( 'static/images/section/services-benefits/services-benefits-offer-person.png', 'Сотрудница клининговой компании' ),
		),
		'faq'              => array(
			'acf_fc_layout' => 'faq',
			'title'         => 'Ответы на интересующие вас вопросы',
			'items'         => array(
				array(
					'question' => 'Сколько времени занимает стандартная уборка квартиры?',
					'answer'   => 'Все зависит от площади и объема задач. После короткого уточнения мы обычно сразу можем назвать примерный диапазон по времени и подобрать удобное окно для выезда.',
				),
				array(
					'question' => 'Можно ли заказать уборку на выходной или вечер?',
					'answer'   => 'Да, мы стараемся подстроиться под ваш график. Лучше согласовать удобное время заранее, чтобы закрепить свободное окно в расписании.',
				),
				array(
					'question' => 'Что нужно подготовить перед приездом клинеров?',
					'answer'   => 'Обычно достаточно обеспечить доступ в помещение и заранее сообщить о важных нюансах: хрупких вещах, сложных загрязнениях или пожеланиях по отдельным зонам.',
				),
				array(
					'question' => 'Вы привозите свою химию и оборудование?',
					'answer'   => 'Да, специалисты приезжают со своим инвентарем и необходимыми средствами. Если у вас есть особые требования к химии, это тоже можно обсудить заранее.',
				),
			),
		),
		'contacts'         => array(
			'acf_fc_layout'           => 'contacts',
			'id_prefix'               => 'services-contacts',
			'idPrefix'                => 'services-contacts',
			'title'                   => 'Контакты',
			'text'                    => 'Нужна надежная клининговая компания для уборки квартир и других помещений? Мы всегда на связи и готовы помочь с выбором услуги и удобного времени выезда.',
			'phone_label'             => 'Телефон',
			'phone_value'             => '+7 (999) 123-45-67',
			'socials_label'           => 'Мы в мессенджерах',
			'social_links'            => array(),
			'email_label'             => 'Электронная почта',
			'email_value'             => 'info@wipe-clean.ru',
			'form_title'              => 'Оставить заявку',
			'form_name_label'         => 'Ваше имя',
			'form_name_placeholder'   => 'Введите имя и фамилию',
			'form_phone_label'        => 'Номер телефона',
			'form_phone_placeholder'  => '+7 ___ ___ __ __',
			'agreement_text'          => 'Заполняя форму вы даете согласие на обработку персональных данных',
			'submit_text'             => 'Рассчитать стоимость уборки',
			'submit_text_mobile'      => 'Рассчитать стоимость',
		),
	);
}

function wipe_clean_get_services_page_section_defaults( $layout ) {
	$defaults_map = wipe_clean_get_services_page_default_sections_map();

	return $defaults_map[ $layout ] ?? array(
		'acf_fc_layout' => $layout,
	);
}
