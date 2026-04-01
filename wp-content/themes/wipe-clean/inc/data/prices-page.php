<?php
/**
 * Default content for the prices page.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_prices_page_image' ) ) {
	function wipe_clean_prices_page_image( $path, $alt = '', $width = 0, $height = 0 ) {
		if ( function_exists( 'wipe_clean_theme_image' ) ) {
			return wipe_clean_theme_image( $path, $alt, $width, $height );
		}

		return array(
			'path'   => ltrim( (string) $path, '/' ),
			'alt'    => (string) $alt,
			'width'  => (int) $width,
			'height' => (int) $height,
		);
	}
}

if ( ! function_exists( 'wipe_clean_prices_page_link' ) ) {
	function wipe_clean_prices_page_link( $title, $url = '#', $target = '' ) {
		return array(
			'title'  => (string) $title,
			'url'    => (string) $url,
			'target' => (string) $target,
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_prices_page_default_service_cards' ) ) {
	function wipe_clean_get_prices_page_default_service_cards() {
		return array(
			array(
				'service_seed_key' => 'service-maintenance-cleaning',
				'group'            => 'featured',
				'title'            => 'Поддерживающая уборка',
				'price'            => 'от 3 500 ₽',
				'href'             => home_url( '/services/' ),
				'layers'           => array(
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-base.png' ),
						'modifier' => 'fill',
					),
				),
			),
			array(
				'service_seed_key' => 'service-urgent-cleaning',
				'group'            => 'featured',
				'title'            => 'Срочная уборка',
				'price'            => 'от 7 000 ₽',
				'href'             => home_url( '/services/' ),
				'layers'           => array(
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-urgent.png' ),
						'modifier' => 'fill',
					),
				),
			),
			array(
				'service_seed_key' => 'service-apartment-cleaning',
				'group'            => 'featured',
				'title'            => 'Уборка квартир',
				'price'            => 'от 10 000 ₽',
				'href'             => home_url( '/services/' ),
				'layers'           => array(
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-base.png' ),
						'modifier' => 'shift-top',
					),
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-apartments.png' ),
						'modifier' => 'fill',
					),
				),
			),
			array(
				'service_seed_key' => 'service-house-cleaning',
				'group'            => 'featured',
				'title'            => 'Уборка домов и коттеджей',
				'price'            => 'от 17 000 ₽',
				'href'             => home_url( '/services/' ),
				'layers'           => array(
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-urgent.png' ),
						'modifier' => 'fill',
					),
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-houses.png' ),
						'modifier' => 'shift-right',
					),
				),
			),
			array(
				'service_seed_key' => 'service-daily-rental-cleaning',
				'group'            => 'secondary',
				'title'            => 'Уборка квартир по суточно',
				'price'            => 'от 5 000 ₽',
				'href'             => home_url( '/services/' ),
				'layers'           => array(
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-base.png' ),
						'modifier' => 'shift-top',
					),
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-apartments.png' ),
						'modifier' => 'fill',
					),
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-daily.png' ),
						'modifier' => 'fill',
					),
				),
			),
			array(
				'service_seed_key' => 'service-window-cleaning',
				'group'            => 'secondary',
				'title'            => 'Мытьё окон',
				'price'            => 'от 2 500 ₽',
				'href'             => home_url( '/services/' ),
				'layers'           => array(
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-urgent.png' ),
						'modifier' => 'fill',
					),
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-houses.png' ),
						'modifier' => 'shift-right',
					),
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-windows.png' ),
						'modifier' => 'flip',
					),
				),
			),
			array(
				'service_seed_key' => 'service-office-cleaning',
				'group'            => 'secondary',
				'title'            => 'Уборка офисов',
				'price'            => 'от 19 000 ₽',
				'href'             => home_url( '/services/' ),
				'layers'           => array(
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-base.png' ),
						'modifier' => 'shift-top',
					),
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-apartments.png' ),
						'modifier' => 'fill',
					),
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-daily.png' ),
						'modifier' => 'fill',
					),
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-office.png' ),
						'modifier' => 'shift-right',
					),
				),
			),
			array(
				'service_seed_key' => 'service-general-cleaning',
				'group'            => 'secondary',
				'title'            => 'Генеральная уборка',
				'price'            => 'от 12 000 ₽',
				'href'             => home_url( '/services/' ),
				'layers'           => array(
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-base.png' ),
						'modifier' => 'shift-top',
					),
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-urgent.png' ),
						'modifier' => 'fill',
					),
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-houses.png' ),
						'modifier' => 'shift-right',
					),
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-windows.png' ),
						'modifier' => 'flip',
					),
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-after-repair.png' ),
						'modifier' => 'overlay',
					),
				),
			),
			array(
				'service_seed_key' => 'service-post-renovation-cleaning',
				'group'            => 'secondary',
				'title'            => 'Уборка после ремонта',
				'price'            => 'от 12 000 ₽',
				'href'             => home_url( '/services/' ),
				'className'        => 'service-card--after-repair',
				'layers'           => array(
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-base.png' ),
						'modifier' => 'shift-top',
					),
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-urgent.png' ),
						'modifier' => 'fill',
					),
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-houses.png' ),
						'modifier' => 'shift-right',
					),
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-windows.png' ),
						'modifier' => 'flip',
					),
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-after-repair.png' ),
						'modifier' => 'overlay',
					),
					array(
						'image'    => wipe_clean_prices_page_image( 'static/images/services/service-preview-after-repair.png' ),
						'modifier' => 'fill',
					),
				),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_prices_page_service_cards' ) ) {
	function wipe_clean_get_prices_page_service_cards() {
		$fallback_cards = wipe_clean_get_prices_page_default_service_cards();
		$resolved_cards = array();
		$posts_by_seed  = array();
		$posts_by_title = array();

		if ( post_type_exists( 'wipe_service' ) ) {
			$posts = get_posts(
				array(
					'post_type'      => 'wipe_service',
					'post_status'    => 'publish',
					'posts_per_page' => -1,
					'orderby'        => array(
						'menu_order' => 'ASC',
						'title'      => 'ASC',
					),
					'order'          => 'ASC',
				)
			);

			foreach ( $posts as $post ) {
				if ( ! $post instanceof WP_Post ) {
					continue;
				}

				$seed_key = trim( (string) get_post_meta( $post->ID, '_wipe_clean_seed_key', true ) );
				$title    = sanitize_title( get_the_title( $post ) );

				if ( '' !== $seed_key ) {
					$posts_by_seed[ $seed_key ] = $post;
				}

				if ( '' !== $title ) {
					$posts_by_title[ $title ] = $post;
				}
			}
		}

		foreach ( $fallback_cards as $fallback_card ) {
			$post     = null;
			$seed_key = (string) ( $fallback_card['service_seed_key'] ?? '' );
			$title    = (string) ( $fallback_card['title'] ?? '' );

			if ( '' !== $seed_key && isset( $posts_by_seed[ $seed_key ] ) ) {
				$post = $posts_by_seed[ $seed_key ];
			} elseif ( '' !== $title ) {
				$title_key = sanitize_title( $title );

				if ( isset( $posts_by_title[ $title_key ] ) ) {
					$post = $posts_by_title[ $title_key ];
				}
			}

			if ( $post instanceof WP_Post ) {
				$fallback_card['title'] = function_exists( 'wipe_clean_get_service_card_title' )
					? wipe_clean_get_service_card_title( $post, $title )
					: get_the_title( $post );
				$fallback_card['price'] = function_exists( 'wipe_clean_get_service_card_price_value' )
					? wipe_clean_get_service_card_price_value( $post, (string) ( $fallback_card['price'] ?? '' ) )
					: (string) ( $fallback_card['price'] ?? '' );
				$fallback_card['href']  = get_permalink( $post );
			}

			$resolved_cards[] = $fallback_card;
		}

		return array(
			'featured'  => array_values(
				array_filter(
					$resolved_cards,
					static function ( $card ) {
						return 'featured' === ( $card['group'] ?? '' );
					}
				)
			),
			'secondary' => array_values(
				array_filter(
					$resolved_cards,
					static function ( $card ) {
						return 'secondary' === ( $card['group'] ?? '' );
					}
				)
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_prices_page_default_sections_map' ) ) {
	function wipe_clean_get_prices_page_default_sections_map() {
		$contacts_defaults = function_exists( 'wipe_clean_get_front_page_section_defaults' )
			? wipe_clean_get_front_page_section_defaults( 'contacts' )
			: array(
				'acf_fc_layout' => 'contacts',
			);

		$contacts_defaults['id_prefix']  = 'prices-contacts';
		$contacts_defaults['idPrefix']   = 'prices-contacts';
		$contacts_defaults['title']      = 'Рассчитать стоимость клининга';
		$contacts_defaults['form_title'] = 'Форма заявки';
		$contacts_defaults['text']       = 'Мы рассчитываем стоимость на клининг под ваш объект — от маленькой студии до огромного дома. Команда быстро примет заявку, чтобы вы скорее насладились чистотой без долгих ожиданий. С нами вы получаете не только личный подход, но и полную уверенность в качестве результата. Мы стремимся к лучшим ценам клининговых услуг и уборки в Москве, сохраняя честность с каждым клиентом.';

		return array(
			'prices_hero'             => array(
				'acf_fc_layout' => 'prices_hero',
				'kicker'        => 'Цены наших услуг',
				'title'         => 'Стоимость клининговых услуг для квартир и домов',
				'text'          => 'Обеспечить комфорт в жилых помещениях Москвы станет намного проще, если изучить прозрачные цены на клининговые услуг без лишних наценок. Наши эксперты возьмут на себя рутину, гарантируя по-настоящему качественный клининг в каждой квартире.',
				'primary_action'=> wipe_clean_prices_page_link( 'Рассчитать стоимость уборки', '#prices-contacts' ),
				'left_image'    => wipe_clean_prices_page_image( 'static/images/section/prices-hero/prices-hero-left.png' ),
				'right_image'   => wipe_clean_prices_page_image( 'static/images/section/prices-hero/prices-hero-right.png' ),
			),
			'prices_services_preview' => array(
				'acf_fc_layout'    => 'prices_services_preview',
				'title'            => 'Стоимость наведения порядка в Москве',
				'text'             => 'Оформить заявку на чистоту в столице станет легче, если ориентироваться на открытый прайс без лишних комиссий. Наши доступные цены на уборку в Москве гарантируют вам профессиональный клининг, а продуманный подход строится на индивидуальном расчете стоимости.',
				'note_text'        => 'Итоговая стоимость зависит от площади помещения, необходимых услуг и индивидуальных предпочтений. Заполните заявку на сайте или свяжитесь с нами по телефону или в мессенджер, чтобы мы рассчитали стоимость клиринговых услуг.',
				'primary_action'   => wipe_clean_prices_page_link( 'Рассчитать стоимость уборки', '#prices-contacts' ),
				'secondary_action' => wipe_clean_prices_page_link( 'Все услуги', home_url( '/services/' ) ),
			),
			'area_pricing'            => array(
				'acf_fc_layout' => 'area_pricing',
				'title'         => 'Цены уборки в зависимости от метража жилья',
				'text'          => 'Цена зависит от указанных клиентом параметров: чем точнее размер, тем проще рассчитать стоимость уборки без лишних наценок. Мы учитываем планировку, предлагая честные цены клининговых услуг, а для больших объектов и клининга домовладений стоимость за квадрат выходит еще выгоднее.',
				'image'         => wipe_clean_prices_page_image( 'static/images/section/area-pricing/area-pricing-visual-placeholder.png', 'Цены уборки в зависимости от метража жилья' ),
				'items'         => array(
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
				'cards'         => array(
					array(
						'title'       => 'Выезд в Московскую область',
						'text'        => 'Мы работаем не только в Москве, но и в Московской области. К стоимости заказа добавляется 500 рублей — эта сумма компенсирует транспортные расходы и логистику при выезде за МКАД. Так мы сохраняем высокое качество уборки вне зависимости от расстояния.',
						'accent_text' => 'Доплата 500 рублей к услуге',
						'accent_lines'=> '',
					),
					array(
						'title'       => 'Крупный инвентарь: как организуем',
						'text'        => 'Бытовая химия входит в стоимость услуги. Для масштабных задач, где нужен пылесос, стремянка и другой инвентарь, предлагаем два варианта: использовать ваш комплект или наш. Условия и логистику заранее согласовываем при бронировании.',
						'accent_text' => '',
						'accent_lines'=> "По Москве - 3000 рублей\nПо МО - 4000 рублей",
					),
				),
			),
			'price_factors'          => array(
				'acf_fc_layout' => 'price_factors',
				'title'         => 'Что влияет на цену за уборку',
				'text'          => 'Многие клиенты задаются вопросом, из чего складывается итоговый чек уборки? Им интересно почему на одинаковые по площади объекты цена клининга часто различается. Мы всегда объясняем заказчикам, что все зависит от параметров жилплощади и специфики клининга, которые стоят перед нашими мастерами.',
				'primary_action'=> wipe_clean_prices_page_link( 'Хочу индивидуальный рассчет', '#prices-contacts' ),
				'items'         => array(
					array(
						'title' => 'Площадь помещения',
						'text'  => 'Это фундаментальный критерий, от которого напрямую зависит время работы бригады и общее количество используемого инвентаря на выезде. Логично, что актуальные расценки на клининговые услуги для просторной студии и загородного особняка будут отличаться.',
					),
					array(
						'title' => 'Тип уборки',
						'text'  => 'Поддерживающий клининг обходится дешевле, тогда как генеральные работы или наведение порядка после стройки требуют гораздо больших ресурсов. Выбирая формат, помните, что итоговая стоимость клининга всегда коррелирует с глубиной и тщательностью очистки.',
					),
					array(
						'title' => 'Уровень загрязнений',
						'text'  => 'Если жилплощадь долго не убирали или требуется удаления сложных пятен, это значительно увеличивает общую трудоемкость процесса. Стоимость рассчитывается индивидуально и учитывает реальное состояние объекта, чтобы обеспечить честный клининг.',
					),
					array(
						'title' => 'Количество санузлов',
						'text'  => 'Данные зоны являются самыми сложными. Им требуется глубокая дезинфекция и борьба с известковым налетом на кафеле. Чем больше таких узлов в помещении, тем выше будет итоговая стоимость клининга в вашем конкретном случае при оформлении заказа.',
					),
					array(
						'title' => 'Дополнительные работы',
						'text'  => 'Химическая чистка мягкой обивки, мытье бытовой техники внутри или чистка жалюзи оплачиваются отдельно от основного выбранного пакета. Такие клининговые услуги цены, на которые зафиксированы в прайсе, позволяют гибко настроить индивидуальный расчёт.',
					),
					array(
						'title' => 'Срочность',
						'text'  => 'Если вам нужно навести идеальную чистоту прямо сейчас, мы быстро отправим к вам мобильную бригаду. Учтите, что расценки клининга при срочном выезде могут немного вырасти. Это обусловлено нагрузкой на персонал и темпом уборки.',
					),
				),
			),
			'price_advantages'       => array(
				'acf_fc_layout' => 'price_advantages',
				'title'         => 'Наши преимущества',
				'text'          => 'Мы работаем честно и не добавляем лишних сумм в чек, когда уборка уже идет. Вам всегда понятно, за что вы платите, поэтому никаких «сюрпризов» в квитанции точно не будет. Такой подход помогает нам держать стабильные цены на уборку в Москве даже при большой конкуренции.',
				'note_text'     => 'Наш принцип сотрудничества строится на простых правилах:',
				'items'         => array(
					array(
						'title' => 'Без скрытых доплат',
						'text'  => 'Вы платите ровно ту сумму, которая была озвучена на этапе оформления заявки. Она оглашается нашим менеджером. Мы никогда не навязываем ненужные опции. Мы сохраняем цены на уборку в Москве понятными, прозрачными и доступными для каждого.',
						'icon'  => wipe_clean_prices_page_image( 'static/images/section/company-preview/icon-pricing.svg', 'Без скрытых доплат' ),
					),
					array(
						'title' => 'Согласование стоимости заранее',
						'text'  => 'Мы заранее согласовываем расценки с клиентом. И только потом начинаем запланированный клининг на конкретном жилом объекте. Это позволяет вам планировать бюджет. Вы точно знаете, что общая сумма на услуги не изменится в процессе.',
						'icon'  => wipe_clean_prices_page_image( 'static/images/section/company-preview/icon-guarantee.svg', 'Согласование стоимости заранее' ),
					),
					array(
						'title' => 'Контроль качества',
						'text'  => 'После завершения клининга ответственный менеджер проверяет соответствие результата. Он использует внутренние стандарты качества нашей компании. Мы фиксируем цену уборки квартир за м² перед началом работ. Мы гарантируем, что вы останетесь довольны сервисом.',
						'icon'  => wipe_clean_prices_page_image( 'static/images/section/company-preview/icon-quality.svg', 'Контроль качества' ),
					),
					array(
						'title' => 'Индивидуальный подход',
						'text'  => 'Мы учитываем особенности объекта и личные пожелания заказчика, создавая по-настоящему эффективный и выгодный план чистоты. Подбираем оптимальный формат взаимодействия, чтобы стоимость уборки квартиры была для вас комфортной и выгодной всегда.',
						'icon'  => wipe_clean_prices_page_image( 'static/images/section/company-preview/icon-experience.svg', 'Индивидуальный подход' ),
					),
				),
			),
			'company_highlight'      => array(
				'acf_fc_layout' => 'company_highlight',
				'title'         => 'ВАЙП–Клин, ВАШ НАДЕЖНЫЙ ПОМОЩНИК',
				'summary_text'  => 'В ВАЙП–Клин мы ценим ваше время и берем на себя все заботы о чистоте. Мы создаем сервис, в котором легко заказать уборку, заранее понимать стоимость и быть уверенным в результате...',
				'body_content'  => "<p>В ВАЙП–Клин мы ценим ваше время и берем на себя все заботы о чистоте. Мы создаем сервис, в котором легко заказать уборку, заранее понимать стоимость и быть уверенным в результате. Наша задача — не просто навести порядок, а сделать ваш дом по-настоящему комфортным, свежим и приятным для жизни.</p>\n<p>Мы выстраиваем работу так, чтобы вам было удобно на каждом этапе: от оформления заявки до финальной проверки результата. Команда приезжает вовремя, учитывает особенности жилья и работает аккуратно, чтобы качество уборки было стабильным, а сервис — понятным и предсказуемым.</p>",
				'note_text'     => 'Для нас клининг — это не разовая услуга, а надежная поддержка вашего ежедневного комфорта. Регулярная уборка помогает поддерживать дом в идеальном состоянии без лишних усилий, а честный подход и внимание к деталям становятся основой доверия между нами и клиентом.',
				'image'         => wipe_clean_prices_page_image( 'static/images/section/company-highlight/company-highlight-full.png', 'Инвентарь ВАЙП–Клин для уборки' ),
			),
			'contacts'                => $contacts_defaults,
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_prices_page_section_defaults' ) ) {
	function wipe_clean_get_prices_page_section_defaults( $layout ) {
		$defaults_map = wipe_clean_get_prices_page_default_sections_map();

		return $defaults_map[ $layout ] ?? array(
			'acf_fc_layout' => $layout,
		);
	}
}
