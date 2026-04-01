<?php
/**
 * Visual admin previews for flexible content blocks.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_get_admin_preview_url' ) ) {
	function wipe_clean_get_admin_preview_url( $path ) {
		$path = trim( (string) $path );

		if ( '' === $path ) {
			return '';
		}

		if ( 0 === strpos( $path, 'static/' ) ) {
			return wipe_clean_asset_uri( $path );
		}

		$segments = array_map(
			'rawurlencode',
			array_filter(
				explode( '/', str_replace( '\\', '/', ltrim( $path, '/' ) ) ),
				'strlen'
			)
		);

		return home_url( '/' . implode( '/', $segments ) );
	}
}

if ( ! function_exists( 'wipe_clean_get_section_preview_registry' ) ) {
	function wipe_clean_get_section_preview_registry() {
		return array(
			'home_hero'            => array(
				'label'       => 'Первый экран',
				'description' => 'Верхний блок страницы с заголовком, изображением и формой расчёта.',
				'preview'          => 'admin-section-preview/Главная.png',
				'preview_position' => '50% 10%',
				'used_on'     => array(),
			),
			'services_preview'     => array(
				'label'       => 'Услуги',
				'description' => 'Карточки основных услуг с ценами и кнопками перехода.',
				'preview'          => 'admin-section-preview/Главная.png',
				'preview_position' => '50% 24%',
				'used_on'     => array( 'О компании' ),
			),
			'home_wave_group'      => array(
				'label'       => 'Цены и этапы',
				'description' => 'Связка из цен, этапов работы и формы расчёта стоимости.',
				'preview'          => 'admin-section-preview/Главная.png',
				'preview_position' => '50% 41%',
				'used_on'     => array(),
			),
			'company_preview'      => array(
				'label'       => 'О компании',
				'description' => 'Текстовый блок о компании с изображением и преимуществами.',
				'preview'          => 'admin-section-preview/Главная.png',
				'preview_position' => '50% 57%',
				'used_on'     => array( 'Акции' ),
			),
			'reviews_preview'      => array(
				'label'       => 'Отзывы',
				'description' => 'Слайдер с отзывами клиентов и кнопками перехода.',
				'preview'          => 'admin-section-preview/Главная.png',
				'preview_position' => '50% 72%',
				'used_on'     => array( 'О компании', 'Контакты' ),
			),
			'gallery_preview'      => array(
				'label'       => 'Галерея',
				'description' => 'Фото и видео выполненных работ.',
				'preview'          => 'admin-section-preview/Главная.png',
				'preview_position' => '50% 84%',
				'used_on'     => array( 'Блог', 'Отзывы' ),
			),
			'services_intro'       => array(
				'label'            => 'Верх архива услуг',
				'description'      => 'Первый экран архива услуг, обзорный текст и слайдер карточек услуг из CPT.',
				'preview'          => 'admin-section-preview/Услуги.png',
				'preview_position' => '50% 10%',
				'used_on'          => array(),
			),
			'services_benefits'    => array(
				'label'            => 'Преимущества архива',
				'description'      => 'Карточки преимуществ, оффер и checklist на архиве услуг.',
				'preview'          => 'admin-section-preview/Услуги.png',
				'preview_position' => '50% 60%',
				'used_on'          => array(),
			),
			'prices_hero'          => array(
				'label'            => 'Первый экран цен',
				'description'      => 'Главный экран страницы цен с заголовком, декоративными изображениями и кнопкой расчёта.',
				'preview'          => 'admin-section-preview/Цены.png',
				'preview_position' => '50% 9%',
				'used_on'          => array(),
			),
			'prices_services_preview' => array(
				'label'            => 'Услуги на странице цен',
				'description'      => 'Карточки услуг с ценами, которые автоматически подтягиваются из CPT услуг.',
				'preview'          => 'admin-section-preview/Цены.png',
				'preview_position' => '50% 36%',
				'used_on'          => array(),
			),
			'area_pricing'         => array(
				'label'            => 'Прайс по метражу',
				'description'      => 'Таблица цен по типам уборки и дополнительные карточки с условиями расчёта.',
				'preview'          => 'admin-section-preview/Цены.png',
				'preview_position' => '50% 51%',
				'used_on'          => array(),
			),
			'price_factors'        => array(
				'label'            => 'Факторы стоимости',
				'description'      => 'Слайдер карточек с объяснением, из чего складывается цена уборки.',
				'preview'          => 'admin-section-preview/Цены.png',
				'preview_position' => '50% 66%',
				'used_on'          => array(),
			),
			'price_advantages'     => array(
				'label'            => 'Преимущества',
				'description'      => 'Карточки с аргументами, почему у компании прозрачное ценообразование.',
				'preview'          => 'admin-section-preview/Цены.png',
				'preview_position' => '50% 79%',
				'used_on'          => array(),
			),
			'company_highlight'    => array(
				'label'            => 'О компании',
				'description'      => 'Текстовый блок о компании с read more и крупным изображением.',
				'preview'          => 'admin-section-preview/Цены.png',
				'preview_position' => '50% 90%',
				'used_on'          => array( 'О компании' ),
			),
			'about_hero'           => array(
				'label'            => 'Первый экран О компании',
				'description'      => 'Главный экран страницы О компании с кикером, заголовком, действиями и двумя изображениями.',
				'preview'          => 'admin-section-preview/О компании.png',
				'preview_position' => '50% 10%',
				'used_on'          => array(),
			),
			'company_story'        => array(
				'label'            => 'История компании',
				'description'      => 'Текстовый блок об истории компании с read more, акцентным текстом и крупным изображением.',
				'preview'          => 'admin-section-preview/О компании.png',
				'preview_position' => '50% 28%',
				'used_on'          => array(),
			),
			'work_approach'        => array(
				'label'            => 'Подход к работе',
				'description'      => 'Секция с карточками подхода к работе, описанием и кнопкой действия.',
				'preview'          => 'admin-section-preview/О компании.png',
				'preview_position' => '50% 43%',
				'used_on'          => array(),
			),
			'team_preview'         => array(
				'label'            => 'Команда',
				'description'      => 'Слайдер сотрудников компании с именами и фотографиями.',
				'preview'          => 'admin-section-preview/О компании.png',
				'preview_position' => '50% 58%',
				'used_on'          => array(),
			),
			'why_us'               => array(
				'label'            => 'Почему выбирают нас',
				'description'      => 'Преимущества компании в формате карточек с иконками и кнопкой.',
				'preview'          => 'admin-section-preview/О компании.png',
				'preview_position' => '50% 74%',
				'used_on'          => array(),
			),
			'about_services_preview' => array(
				'label'            => 'Превью услуг',
				'description'      => 'Автоматическая подборка карточек услуг из раздела Услуги с текстами секции.',
				'preview'          => 'admin-section-preview/О компании.png',
				'preview_position' => '50% 86%',
				'used_on'          => array(),
			),
			'about_reviews_preview' => array(
				'label'            => 'Превью отзывов',
				'description'      => 'Автоматическая подборка текстовых отзывов из раздела Отзывы с CTA секции.',
				'preview'          => 'admin-section-preview/О компании.png',
				'preview_position' => '50% 93%',
				'used_on'          => array(),
			),
			'about_order_cta'      => array(
				'label'            => 'Финальный CTA',
				'description'      => 'Завершающий призыв к действию с текстом, кнопками и крупной иллюстрацией.',
				'preview'          => 'admin-section-preview/О компании.png',
				'preview_position' => '50% 98%',
				'used_on'          => array(),
			),
			'faq_hero'             => array(
				'label'            => 'Первый экран FAQ',
				'description'      => 'Главный экран страницы FAQ с кикером, заголовком, поясняющим текстом и декоративной иллюстрацией.',
				'preview'          => 'admin-section-preview/FAQ.png',
				'preview_position' => '50% 12%',
				'used_on'          => array(),
			),
			'contacts_hero'        => array(
				'label'            => 'Первый экран контактов',
				'description'      => 'Главный экран страницы контактов с формой, телефоном, соцсетями и email.',
				'preview'          => 'admin-section-preview/Контакты.png',
				'preview_position' => '50% 15%',
				'used_on'          => array(),
			),
			'company_requisites_band' => array(
				'label'            => 'Реквизиты компании',
				'description'      => 'Волновой блок с юридическими реквизитами компании под первым экраном.',
				'preview'          => 'admin-section-preview/Контакты.png',
				'preview_position' => '50% 38%',
				'used_on'          => array(),
			),
			'service_hero'         => array(
				'label'            => 'Первый экран услуги',
				'description'      => 'Главный экран single-страницы услуги с заголовком, текстом и изображением.',
				'preview'          => 'admin-section-preview/Уборка квартир.png',
				'preview_position' => '50% 8%',
				'used_on'          => array(),
			),
			'service_purpose'      => array(
				'label'            => 'Зачем нужна услуга',
				'description'      => 'Текстовый блок с причинами, списком и акцентным изображением.',
				'preview'          => 'admin-section-preview/Уборка квартир.png',
				'preview_position' => '50% 26%',
				'used_on'          => array(),
			),
			'service_wave_group'   => array(
				'label'            => 'Состав и преимущества',
				'description'      => 'Связка из табов состава услуги и карточек преимуществ.',
				'preview'          => 'admin-section-preview/Уборка квартир.png',
				'preview_position' => '50% 43%',
				'used_on'          => array(),
			),
			'order_cta'            => array(
				'label'            => 'CTA-блок',
				'description'      => 'Промежуточный призыв к действию с кнопками и крупной иллюстрацией.',
				'preview'          => 'admin-section-preview/Уборка квартир.png',
				'preview_position' => '50% 58%',
				'used_on'          => array(),
			),
			'service_price'        => array(
				'label'            => 'Стоимость услуги',
				'description'      => 'Блок стоимости с факторами цены, акцентной строкой и кнопкой.',
				'preview'          => 'admin-section-preview/Уборка квартир.png',
				'preview_position' => '50% 74%',
				'used_on'          => array(),
			),
			'before_after_results' => array(
				'label'            => 'До и после',
				'description'      => 'Сетка сравнений результата уборки с карточками before/after.',
				'preview'          => 'admin-section-preview/Уборка квартир.png',
				'preview_position' => '50% 88%',
				'used_on'          => array(),
			),
			'other_services'       => array(
				'label'            => 'Другие услуги',
				'description'      => 'Автоматическая подборка других услуг из CPT без ручного дублирования карточек.',
				'preview'          => 'admin-section-preview/Уборка квартир.png',
				'preview_position' => '50% 97%',
				'used_on'          => array(),
			),
			'reviews_archive'     => array(
				'label'            => 'Текстовые отзывы',
				'description'      => 'Верхняя секция архива отзывов с текстовыми карточками, кнопкой и подгрузкой.',
				'preview'          => 'admin-section-preview/Отзывы.png',
				'preview_position' => '50% 12%',
				'used_on'          => array(),
			),
			'video_reviews'       => array(
				'label'            => 'Видео отзывы',
				'description'      => 'Секция видео отзывов со слайдером карточек и CTA-кнопкой.',
				'preview'          => 'admin-section-preview/Отзывы.png',
				'preview_position' => '50% 46%',
				'used_on'          => array(),
			),
			'message_reviews'     => array(
				'label'            => 'Фото отзывы / переписки',
				'description'      => 'Секция скриншотов переписок и фото отзывов с masonry-сеткой.',
				'preview'          => 'admin-section-preview/Отзывы.png',
				'preview_position' => '50% 76%',
				'used_on'          => array(),
			),
			'promotions_archive' => array(
				'label'            => 'Архив акций',
				'description'      => 'Первый экран страницы акций с карточками предложений из CPT и открытием содержимого в pop-up без отдельного single.',
				'preview'          => 'admin-section-preview/Акции.png',
				'preview_position' => '50% 18%',
				'used_on'          => array(),
			),
			'blog_archive'         => array(
				'label'            => 'Архив блога',
				'description'      => 'Первый экран архива блога с описанием раздела, иллюстрацией и лентой карточек статей.',
				'preview'          => 'admin-section-preview/Блог.png',
				'preview_position' => '50% 18%',
				'used_on'          => array(),
			),
			'blog_article_hero'    => array(
				'label'            => 'Первый экран статьи',
				'description'      => 'Заголовок статьи, описание, дата публикации и основное изображение single-страницы блога.',
				'preview'          => 'admin-section-preview/Статья.png',
				'preview_position' => '50% 11%',
				'used_on'          => array(),
			),
			'blog_article_content' => array(
				'label'            => 'Контент статьи',
				'description'      => 'Основное содержимое статьи с заголовками, списками и иллюстрациями.',
				'preview'          => 'admin-section-preview/Статья.png',
				'preview_position' => '50% 52%',
				'used_on'          => array(),
			),
			'related_posts'        => array(
				'label'            => 'Рекомендованные статьи',
				'description'      => 'Подборка других записей блога под статьёй с карточками и CTA-кнопками.',
				'preview'          => 'admin-section-preview/Статья.png',
				'preview_position' => '50% 86%',
				'used_on'          => array(),
			),
			'faq'                  => array(
				'label'       => 'FAQ',
				'description' => 'Список частых вопросов и ответов.',
				'preview'          => 'admin-section-preview/Главная.png',
				'preview_position' => '50% 92%',
				'used_on'     => array( 'Услуги', 'FAQ', 'Отзывы', 'Акции', 'Контакты', 'Страница услуги' ),
			),
			'contacts'             => array(
				'label'       => 'Контакты',
				'description' => 'Контакты компании и форма обращения.',
				'preview'          => 'admin-section-preview/Главная.png',
				'preview_position' => '50% 98%',
				'used_on'     => array( 'Блог', 'Отзывы', 'FAQ', 'Акции', 'Страница услуги' ),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_page_section_sets' ) ) {
	function wipe_clean_get_page_section_sets() {
		return array(
			'front_page'       => array(
				'field_name' => 'front_page_sections',
				'label'      => 'Главная',
				'core'       => array(
					'home_hero',
					'services_preview',
					'home_wave_group',
					'company_preview',
					'reviews_preview',
					'gallery_preview',
					'faq',
					'contacts',
				),
			),
			'services_archive' => array(
				'field_name' => 'services_page_sections',
				'label'      => 'Архив услуг',
				'core'       => array(
					'services_intro',
					'services_benefits',
					'faq',
					'contacts',
				),
			),
			'service_single'   => array(
				'field_name' => 'service_sections',
				'label'      => 'Страница услуги',
				'core'       => array(
					'service_hero',
					'service_purpose',
					'service_wave_group',
					'order_cta',
					'service_price',
					'before_after_results',
					'other_services',
					'faq',
					'contacts',
				),
			),
			'reviews_archive'  => array(
				'field_name' => 'reviews_archive_sections',
				'label'      => 'Отзывы',
				'core'       => array(
					'reviews_archive',
					'video_reviews',
					'message_reviews',
					'before_after_results',
					'faq',
					'gallery_preview',
					'contacts',
				),
			),
			'promotions_archive' => array(
				'field_name' => 'promotions_archive_sections',
				'label'      => 'Акции',
				'core'       => array(
					'promotions_archive',
					'company_preview',
					'contacts',
					'faq',
				),
			),
			'blog_archive'     => array(
				'field_name' => 'blog_archive_sections',
				'label'      => 'Архив блога',
				'core'       => array(
					'blog_archive',
					'contacts',
				),
			),
			'blog_single'      => array(
				'field_name' => 'blog_post_sections',
				'label'      => 'Статья блога',
				'core'       => array(
					'blog_article_hero',
					'blog_article_content',
					'related_posts',
					'contacts',
				),
			),
			'prices_page'      => array(
				'field_name' => 'prices_page_sections',
				'label'      => 'Цены',
				'core'       => array(
					'prices_hero',
					'prices_services_preview',
					'area_pricing',
					'price_factors',
					'price_advantages',
					'company_highlight',
					'contacts',
				),
			),
			'about_page'       => array(
				'field_name' => 'about_page_sections',
				'label'      => 'О компании',
				'core'       => array(
					'about_hero',
					'company_story',
					'work_approach',
					'team_preview',
					'why_us',
					'about_services_preview',
					'about_reviews_preview',
					'about_order_cta',
					'contacts',
				),
			),
			'faq_page'         => array(
				'field_name' => 'faq_page_sections',
				'label'      => 'FAQ',
				'core'       => array(
					'faq_hero',
					'faq',
					'contacts',
				),
			),
			'contacts_page'    => array(
				'field_name' => 'contacts_page_sections',
				'label'      => 'Контакты',
				'core'       => array(
					'contacts_hero',
					'company_requisites_band',
					'reviews_preview',
					'faq',
				),
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_page_section_key_by_field_name' ) ) {
	function wipe_clean_get_page_section_key_by_field_name( $field_name ) {
		foreach ( wipe_clean_get_page_section_sets() as $page_key => $page_set ) {
			if ( ( $page_set['field_name'] ?? '' ) === $field_name ) {
				return $page_key;
			}
		}

		return '';
	}
}

if ( ! function_exists( 'wipe_clean_get_section_preview_badges' ) ) {
	function wipe_clean_get_section_preview_badges( $layout_name, $page_key ) {
		$page_sets = wipe_clean_get_page_section_sets();
		$registry  = wipe_clean_get_section_preview_registry();
		$badges    = array();

		if ( empty( $page_sets[ $page_key ] ) || empty( $registry[ $layout_name ] ) ) {
			return $badges;
		}

		if ( in_array( $layout_name, $page_sets[ $page_key ]['core'], true ) ) {
			$badges[] = array(
				'label' => 'Основная',
				'type'  => 'core',
			);
		}

		if ( ! empty( $registry[ $layout_name ]['used_on'] ) ) {
			$badges[] = array(
				'label' => 'Есть и на других страницах',
				'type'  => 'shared',
			);
		}

		$badges[] = array(
			'label' => $page_sets[ $page_key ]['label'],
			'type'  => 'page',
		);

		return $badges;
	}
}

if ( ! function_exists( 'wipe_clean_get_layout_preview_summary' ) ) {
	function wipe_clean_get_layout_preview_summary( $layout_name, $page_key ) {
		$summary = '';

		switch ( $page_key ) {
			case 'front_page':
				switch ( $layout_name ) {
					case 'home_hero':
					case 'services_preview':
					case 'company_preview':
					case 'reviews_preview':
					case 'gallery_preview':
					case 'faq':
					case 'contacts':
						$summary = (string) get_sub_field( 'title' );
						break;
					case 'home_wave_group':
						$price_preview = get_sub_field( 'price_preview' );
						if ( is_array( $price_preview ) ) {
							$summary = (string) ( $price_preview['title'] ?? '' );
						}
						break;
				}
				break;

			case 'services_archive':
				switch ( $layout_name ) {
					case 'services_intro':
						$summary = (string) get_sub_field( 'hero_title' );
						if ( '' === trim( $summary ) ) {
							$summary = (string) get_sub_field( 'overview_title' );
						}
						break;
					case 'services_benefits':
					case 'faq':
					case 'contacts':
						$summary = (string) get_sub_field( 'title' );
						break;
				}
				break;

			case 'service_single':
				switch ( $layout_name ) {
					case 'service_hero':
						$summary = (string) get_sub_field( 'title' );
						if ( '' === trim( $summary ) ) {
							$summary = (string) get_sub_field( 'kicker' );
						}
						break;
					case 'service_purpose':
					case 'other_services':
					case 'faq':
					case 'contacts':
						$summary = (string) get_sub_field( 'title' );
						break;
					case 'service_wave_group':
						$summary = (string) get_sub_field( 'includes_title' );
						if ( '' === trim( $summary ) ) {
							$summary = (string) get_sub_field( 'benefits_title' );
						}
						break;
					case 'order_cta':
					case 'before_after_results':
						$summary = (string) get_sub_field( 'title' );
						break;
					case 'service_price':
						$summary = trim( (string) get_sub_field( 'title_accent' ) . ' ' . (string) get_sub_field( 'title_main' ) );
						break;
				}
				break;

			case 'reviews_archive':
				switch ( $layout_name ) {
					case 'reviews_archive':
					case 'video_reviews':
					case 'message_reviews':
					case 'before_after_results':
					case 'faq':
					case 'gallery_preview':
					case 'contacts':
						$summary = (string) get_sub_field( 'title' );
						if ( '' === trim( $summary ) ) {
							$summary = (string) get_sub_field( 'kicker' );
						}
						break;
				}
				break;

			case 'promotions_archive':
				switch ( $layout_name ) {
					case 'promotions_archive':
					case 'company_preview':
					case 'contacts':
					case 'faq':
						$summary = (string) get_sub_field( 'title' );
						if ( '' === trim( $summary ) ) {
							$summary = (string) get_sub_field( 'kicker' );
						}
						break;
				}
				break;

			case 'blog_archive':
				switch ( $layout_name ) {
					case 'blog_archive':
					case 'contacts':
						$summary = (string) get_sub_field( 'title' );
						break;
				}
				break;

			case 'blog_single':
				switch ( $layout_name ) {
					case 'blog_article_hero':
					case 'related_posts':
					case 'contacts':
						$summary = (string) get_sub_field( 'title' );
						if ( '' === trim( $summary ) ) {
							$summary = (string) get_sub_field( 'excerpt' );
						}
						break;
					case 'blog_article_content':
						$summary = trim( wp_strip_all_tags( (string) get_sub_field( 'content' ) ) );
						break;
				}
				break;

			case 'prices_page':
				switch ( $layout_name ) {
					case 'prices_hero':
					case 'prices_services_preview':
					case 'area_pricing':
					case 'price_factors':
					case 'price_advantages':
					case 'company_highlight':
					case 'contacts':
						$summary = (string) get_sub_field( 'title' );
						if ( '' === trim( $summary ) ) {
							$summary = (string) get_sub_field( 'kicker' );
						}
						break;
				}
				break;

			case 'about_page':
				switch ( $layout_name ) {
					case 'about_hero':
						$summary = (string) get_sub_field( 'title' );
						if ( '' === trim( $summary ) ) {
							$summary = (string) get_sub_field( 'kicker' );
						}
						break;
					case 'company_story':
					case 'work_approach':
					case 'team_preview':
					case 'why_us':
					case 'about_services_preview':
					case 'about_reviews_preview':
					case 'about_order_cta':
					case 'contacts':
						$summary = (string) get_sub_field( 'title' );
						break;
				}
				break;

			case 'faq_page':
				switch ( $layout_name ) {
					case 'faq_hero':
					case 'faq':
					case 'contacts':
						$summary = (string) get_sub_field( 'title' );
						if ( '' === trim( $summary ) ) {
							$summary = (string) get_sub_field( 'kicker' );
						}
						break;
				}
				break;

			case 'contacts_page':
				switch ( $layout_name ) {
					case 'contacts_hero':
					case 'reviews_preview':
					case 'faq':
						$summary = (string) get_sub_field( 'title' );
						if ( '' === trim( $summary ) ) {
							$summary = (string) get_sub_field( 'kicker' );
						}
						break;
					case 'company_requisites_band':
						$summary = (string) get_sub_field( 'company_value' );
						if ( '' === trim( $summary ) ) {
							$summary = (string) get_sub_field( 'ogrn_value' );
						}
						break;
				}
				break;
		}

		$summary = trim( wp_strip_all_tags( $summary ) );

		if ( '' === $summary ) {
			return '';
		}

		return wp_html_excerpt( $summary, 88, '…' );
	}
}

if ( ! function_exists( 'wipe_clean_build_admin_section_preview_payload' ) ) {
	function wipe_clean_build_admin_section_preview_payload( $page_key ) {
		$registry  = wipe_clean_get_section_preview_registry();
		$page_sets = wipe_clean_get_page_section_sets();
		$payload   = array();

		if ( empty( $page_sets[ $page_key ] ) ) {
			return $payload;
		}

		foreach ( $page_sets[ $page_key ]['core'] as $layout_name ) {
			if ( empty( $registry[ $layout_name ] ) ) {
				continue;
			}

			$payload[ $layout_name ] = array(
				'label'           => function_exists( 'wipe_clean_make_admin_text_friendly' ) ? wipe_clean_make_admin_text_friendly( (string) $registry[ $layout_name ]['label'] ) : (string) $registry[ $layout_name ]['label'],
				'description'     => function_exists( 'wipe_clean_make_admin_text_friendly' ) ? wipe_clean_make_admin_text_friendly( (string) $registry[ $layout_name ]['description'] ) : (string) $registry[ $layout_name ]['description'],
				'preview'         => wipe_clean_get_admin_preview_url( (string) $registry[ $layout_name ]['preview'] ),
				'previewPosition' => (string) ( $registry[ $layout_name ]['preview_position'] ?? '50% 50%' ),
				'badges'          => wipe_clean_get_section_preview_badges( $layout_name, $page_key ),
				'usedOn'          => array_values(
					array_map(
						static function ( $item ) {
							$item = (string) $item;

							return function_exists( 'wipe_clean_make_admin_text_friendly' )
								? wipe_clean_make_admin_text_friendly( $item )
								: $item;
						},
						(array) ( $registry[ $layout_name ]['used_on'] ?? array() )
					)
				),
			);
		}

		return $payload;
	}
}

if ( ! function_exists( 'wipe_clean_render_admin_section_badges_html' ) ) {
	function wipe_clean_render_admin_section_badges_html( $badges ) {
		$html = '';

		foreach ( $badges as $badge ) {
			$html .= sprintf(
				'<span class="wipe-clean-admin-layout__badge wipe-clean-admin-layout__badge--%1$s">%2$s</span>',
				esc_attr( (string) ( $badge['type'] ?? 'page' ) ),
				esc_html( (string) ( $badge['label'] ?? '' ) )
			);
		}

		return $html;
	}
}

if ( ! function_exists( 'wipe_clean_filter_section_layout_title' ) ) {
	function wipe_clean_filter_section_layout_title( $title, $field, $layout, $i ) {
		$field_name = (string) ( $field['name'] ?? '' );
		$page_key   = wipe_clean_get_page_section_key_by_field_name( $field_name );
		$registry   = wipe_clean_build_admin_section_preview_payload( $page_key );
		$name       = (string) ( $layout['name'] ?? '' );

		if ( '' === $page_key || empty( $registry[ $name ] ) ) {
			return $title;
		}

		$meta           = $registry[ $name ];
		$summary        = wipe_clean_get_layout_preview_summary( $name, $page_key );
		$summary        = '' !== $summary ? $summary : (string) $meta['description'];
		$summary        = function_exists( 'wipe_clean_make_admin_text_friendly' ) ? wipe_clean_make_admin_text_friendly( $summary ) : $summary;
		$usage_line     = ! empty( $meta['usedOn'] ) ? 'Также используется: ' . implode( ', ', array_map( 'sanitize_text_field', $meta['usedOn'] ) ) : '';
		$badges_html    = wipe_clean_render_admin_section_badges_html( $meta['badges'] );
		$preview_style  = sprintf( 'object-position:%s;', esc_attr( (string) $meta['previewPosition'] ) );

		return sprintf(
			'<span class="wipe-clean-admin-layout"><span class="wipe-clean-admin-layout__thumb"><img src="%1$s" alt="%2$s" style="%6$s"></span><span class="wipe-clean-admin-layout__body"><span class="wipe-clean-admin-layout__top"><span class="wipe-clean-admin-layout__title">%2$s</span><span class="wipe-clean-admin-layout__badges">%3$s</span></span><span class="wipe-clean-admin-layout__description">%4$s</span>%5$s</span></span>',
			esc_url( (string) $meta['preview'] ),
			esc_html( (string) $meta['label'] ),
			$badges_html,
			esc_html( $summary ),
			'' !== $usage_line ? sprintf( '<span class="wipe-clean-admin-layout__usage">%s</span>', esc_html( $usage_line ) ) : '',
			$preview_style
		);
	}
}
add_filter( 'acf/fields/flexible_content/layout_title/name=front_page_sections', 'wipe_clean_filter_section_layout_title', 10, 4 );
add_filter( 'acf/fields/flexible_content/layout_title/name=services_page_sections', 'wipe_clean_filter_section_layout_title', 10, 4 );
add_filter( 'acf/fields/flexible_content/layout_title/name=service_sections', 'wipe_clean_filter_section_layout_title', 10, 4 );
add_filter( 'acf/fields/flexible_content/layout_title/name=reviews_archive_sections', 'wipe_clean_filter_section_layout_title', 10, 4 );
add_filter( 'acf/fields/flexible_content/layout_title/name=promotions_archive_sections', 'wipe_clean_filter_section_layout_title', 10, 4 );
add_filter( 'acf/fields/flexible_content/layout_title/name=blog_archive_sections', 'wipe_clean_filter_section_layout_title', 10, 4 );
add_filter( 'acf/fields/flexible_content/layout_title/name=blog_post_sections', 'wipe_clean_filter_section_layout_title', 10, 4 );
add_filter( 'acf/fields/flexible_content/layout_title/name=prices_page_sections', 'wipe_clean_filter_section_layout_title', 10, 4 );
add_filter( 'acf/fields/flexible_content/layout_title/name=about_page_sections', 'wipe_clean_filter_section_layout_title', 10, 4 );
add_filter( 'acf/fields/flexible_content/layout_title/name=faq_page_sections', 'wipe_clean_filter_section_layout_title', 10, 4 );
add_filter( 'acf/fields/flexible_content/layout_title/name=contacts_page_sections', 'wipe_clean_filter_section_layout_title', 10, 4 );

if ( ! function_exists( 'wipe_clean_enqueue_admin_section_preview_assets' ) ) {
	function wipe_clean_enqueue_admin_section_preview_assets() {
		if ( ! function_exists( 'acf_get_field_groups' ) ) {
			return;
		}

		$css = <<<'CSS'
.acf-field-flexible-content[data-name="front_page_sections"] .acf-flexible-content .values > .layout > .acf-fc-layout-handle,
.acf-field-flexible-content[data-name="services_page_sections"] .acf-flexible-content .values > .layout > .acf-fc-layout-handle,
.acf-field-flexible-content[data-name="service_sections"] .acf-flexible-content .values > .layout > .acf-fc-layout-handle,
.acf-field-flexible-content[data-name="prices_page_sections"] .acf-flexible-content .values > .layout > .acf-fc-layout-handle,
.acf-field-flexible-content[data-name="about_page_sections"] .acf-flexible-content .values > .layout > .acf-fc-layout-handle,
.acf-field-flexible-content[data-name="faq_page_sections"] .acf-flexible-content .values > .layout > .acf-fc-layout-handle {
	padding-top: 10px;
	padding-bottom: 10px;
	cursor: pointer;
}

.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout,
.acf-field-flexible-content[data-name="services_page_sections"] .wipe-clean-admin-layout,
.acf-field-flexible-content[data-name="service_sections"] .wipe-clean-admin-layout,
.acf-field-flexible-content[data-name="prices_page_sections"] .wipe-clean-admin-layout,
.acf-field-flexible-content[data-name="about_page_sections"] .wipe-clean-admin-layout,
.acf-field-flexible-content[data-name="faq_page_sections"] .wipe-clean-admin-layout {
	display: flex;
	align-items: center;
	gap: 12px;
	max-width: calc(100% - 92px);
	user-select: none;
	pointer-events: none;
}

.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout *,
.acf-field-flexible-content[data-name="services_page_sections"] .wipe-clean-admin-layout *,
.acf-field-flexible-content[data-name="service_sections"] .wipe-clean-admin-layout *,
.acf-field-flexible-content[data-name="prices_page_sections"] .wipe-clean-admin-layout *,
.acf-field-flexible-content[data-name="about_page_sections"] .wipe-clean-admin-layout *,
.acf-field-flexible-content[data-name="faq_page_sections"] .wipe-clean-admin-layout * {
	pointer-events: none;
}

.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout__thumb,
.acf-field-flexible-content[data-name="services_page_sections"] .wipe-clean-admin-layout__thumb,
.acf-field-flexible-content[data-name="service_sections"] .wipe-clean-admin-layout__thumb,
.acf-field-flexible-content[data-name="prices_page_sections"] .wipe-clean-admin-layout__thumb,
.acf-field-flexible-content[data-name="about_page_sections"] .wipe-clean-admin-layout__thumb,
.acf-field-flexible-content[data-name="faq_page_sections"] .wipe-clean-admin-layout__thumb {
	width: 120px;
	flex: 0 0 120px;
	aspect-ratio: 16 / 9;
	overflow: hidden;
	border: 1px solid #D7E8EE;
	border-radius: 10px;
	background: #fff;
	box-shadow: 0 8px 18px rgba(21, 15, 49, 0.07);
}

.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout__thumb img,
.acf-field-flexible-content[data-name="services_page_sections"] .wipe-clean-admin-layout__thumb img,
.acf-field-flexible-content[data-name="service_sections"] .wipe-clean-admin-layout__thumb img,
.acf-field-flexible-content[data-name="prices_page_sections"] .wipe-clean-admin-layout__thumb img,
.acf-field-flexible-content[data-name="about_page_sections"] .wipe-clean-admin-layout__thumb img,
.acf-field-flexible-content[data-name="faq_page_sections"] .wipe-clean-admin-layout__thumb img {
	display: block;
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout__body,
.acf-field-flexible-content[data-name="services_page_sections"] .wipe-clean-admin-layout__body,
.acf-field-flexible-content[data-name="service_sections"] .wipe-clean-admin-layout__body,
.acf-field-flexible-content[data-name="prices_page_sections"] .wipe-clean-admin-layout__body,
.acf-field-flexible-content[data-name="about_page_sections"] .wipe-clean-admin-layout__body,
.acf-field-flexible-content[data-name="faq_page_sections"] .wipe-clean-admin-layout__body {
	display: grid;
	gap: 5px;
	min-width: 0;
}

.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout__top,
.acf-field-flexible-content[data-name="services_page_sections"] .wipe-clean-admin-layout__top,
.acf-field-flexible-content[data-name="service_sections"] .wipe-clean-admin-layout__top,
.acf-field-flexible-content[data-name="prices_page_sections"] .wipe-clean-admin-layout__top,
.acf-field-flexible-content[data-name="about_page_sections"] .wipe-clean-admin-layout__top,
.acf-field-flexible-content[data-name="faq_page_sections"] .wipe-clean-admin-layout__top {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	gap: 6px 8px;
}

.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout__title,
.acf-field-flexible-content[data-name="services_page_sections"] .wipe-clean-admin-layout__title,
.acf-field-flexible-content[data-name="service_sections"] .wipe-clean-admin-layout__title,
.acf-field-flexible-content[data-name="prices_page_sections"] .wipe-clean-admin-layout__title,
.acf-field-flexible-content[data-name="about_page_sections"] .wipe-clean-admin-layout__title,
.acf-field-flexible-content[data-name="faq_page_sections"] .wipe-clean-admin-layout__title {
	font-size: 15px;
	font-weight: 800;
	line-height: 1.2;
	color: #150F31;
}

.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout__badges,
.acf-field-flexible-content[data-name="services_page_sections"] .wipe-clean-admin-layout__badges,
.acf-field-flexible-content[data-name="service_sections"] .wipe-clean-admin-layout__badges,
.acf-field-flexible-content[data-name="prices_page_sections"] .wipe-clean-admin-layout__badges,
.acf-field-flexible-content[data-name="about_page_sections"] .wipe-clean-admin-layout__badges,
.acf-field-flexible-content[data-name="faq_page_sections"] .wipe-clean-admin-layout__badges {
	display: flex;
	flex-wrap: wrap;
	gap: 6px;
}

.wipe-clean-admin-layout__badge {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	padding: 4px 8px;
	border-radius: 999px;
	font-size: 10px;
	font-weight: 700;
	line-height: 1;
}

.wipe-clean-admin-layout__badge--core {
	background: rgba(27, 158, 116, 0.12);
	color: #1B9E74;
}

.wipe-clean-admin-layout__badge--shared {
	background: rgba(0, 134, 179, 0.12);
	color: #0086B3;
}

.wipe-clean-admin-layout__badge--page {
	background: rgba(21, 15, 49, 0.08);
	color: #150F31;
}

.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout__description,
.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout__usage,
.acf-field-flexible-content[data-name="services_page_sections"] .wipe-clean-admin-layout__description,
.acf-field-flexible-content[data-name="services_page_sections"] .wipe-clean-admin-layout__usage,
.acf-field-flexible-content[data-name="service_sections"] .wipe-clean-admin-layout__description,
.acf-field-flexible-content[data-name="service_sections"] .wipe-clean-admin-layout__usage,
.acf-field-flexible-content[data-name="prices_page_sections"] .wipe-clean-admin-layout__description,
.acf-field-flexible-content[data-name="prices_page_sections"] .wipe-clean-admin-layout__usage,
.acf-field-flexible-content[data-name="about_page_sections"] .wipe-clean-admin-layout__description,
.acf-field-flexible-content[data-name="about_page_sections"] .wipe-clean-admin-layout__usage,
.acf-field-flexible-content[data-name="faq_page_sections"] .wipe-clean-admin-layout__description,
.acf-field-flexible-content[data-name="faq_page_sections"] .wipe-clean-admin-layout__usage {
	display: block;
	font-size: 12px;
	line-height: 1.45;
	color: #5D5779;
}

.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout__usage,
.acf-field-flexible-content[data-name="services_page_sections"] .wipe-clean-admin-layout__usage,
.acf-field-flexible-content[data-name="service_sections"] .wipe-clean-admin-layout__usage,
.acf-field-flexible-content[data-name="prices_page_sections"] .wipe-clean-admin-layout__usage,
.acf-field-flexible-content[data-name="about_page_sections"] .wipe-clean-admin-layout__usage,
.acf-field-flexible-content[data-name="faq_page_sections"] .wipe-clean-admin-layout__usage {
	color: #0086B3;
}

@media (max-width: 960px) {
	.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout,
	.acf-field-flexible-content[data-name="services_page_sections"] .wipe-clean-admin-layout,
	.acf-field-flexible-content[data-name="service_sections"] .wipe-clean-admin-layout,
	.acf-field-flexible-content[data-name="prices_page_sections"] .wipe-clean-admin-layout,
	.acf-field-flexible-content[data-name="about_page_sections"] .wipe-clean-admin-layout,
	.acf-field-flexible-content[data-name="faq_page_sections"] .wipe-clean-admin-layout {
		max-width: calc(100% - 88px);
	}

	.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout__thumb,
	.acf-field-flexible-content[data-name="services_page_sections"] .wipe-clean-admin-layout__thumb,
	.acf-field-flexible-content[data-name="service_sections"] .wipe-clean-admin-layout__thumb,
	.acf-field-flexible-content[data-name="prices_page_sections"] .wipe-clean-admin-layout__thumb,
	.acf-field-flexible-content[data-name="about_page_sections"] .wipe-clean-admin-layout__thumb,
	.acf-field-flexible-content[data-name="faq_page_sections"] .wipe-clean-admin-layout__thumb {
		width: 96px;
		flex-basis: 96px;
	}
}

.acf-field-flexible-content[data-name="contacts_page_sections"] .acf-flexible-content .values > .layout > .acf-fc-layout-handle {
	padding-top: 10px;
	padding-bottom: 10px;
	cursor: pointer;
}

.acf-field-flexible-content[data-name="contacts_page_sections"] .wipe-clean-admin-layout {
	display: flex;
	align-items: center;
	gap: 12px;
	max-width: calc(100% - 92px);
	user-select: none;
	pointer-events: none;
}

.acf-field-flexible-content[data-name="contacts_page_sections"] .wipe-clean-admin-layout * {
	pointer-events: none;
}

.acf-field-flexible-content[data-name="contacts_page_sections"] .wipe-clean-admin-layout__thumb {
	width: 120px;
	flex: 0 0 120px;
	aspect-ratio: 16 / 9;
	overflow: hidden;
	border: 1px solid #D7E8EE;
	border-radius: 10px;
	background: #fff;
	box-shadow: 0 8px 18px rgba(21, 15, 49, 0.07);
}

.acf-field-flexible-content[data-name="contacts_page_sections"] .wipe-clean-admin-layout__thumb img {
	display: block;
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.acf-field-flexible-content[data-name="contacts_page_sections"] .wipe-clean-admin-layout__body {
	display: grid;
	gap: 5px;
	min-width: 0;
}

.acf-field-flexible-content[data-name="contacts_page_sections"] .wipe-clean-admin-layout__top {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	gap: 6px 8px;
}

.acf-field-flexible-content[data-name="contacts_page_sections"] .wipe-clean-admin-layout__title {
	font-size: 15px;
	font-weight: 800;
	line-height: 1.2;
	color: #150F31;
}

.acf-field-flexible-content[data-name="contacts_page_sections"] .wipe-clean-admin-layout__badges {
	display: flex;
	flex-wrap: wrap;
	gap: 6px;
}

.acf-field-flexible-content[data-name="contacts_page_sections"] .wipe-clean-admin-layout__description,
.acf-field-flexible-content[data-name="contacts_page_sections"] .wipe-clean-admin-layout__usage {
	display: block;
	font-size: 12px;
	line-height: 1.45;
	color: #5D5779;
}

.acf-field-flexible-content[data-name="contacts_page_sections"] .wipe-clean-admin-layout__usage {
	color: #0086B3;
}

@media (max-width: 960px) {
	.acf-field-flexible-content[data-name="contacts_page_sections"] .wipe-clean-admin-layout {
		max-width: calc(100% - 88px);
	}

	.acf-field-flexible-content[data-name="contacts_page_sections"] .wipe-clean-admin-layout__thumb {
		width: 96px;
		flex-basis: 96px;
	}
}

.acf-field-flexible-content[data-name="blog_archive_sections"] .acf-flexible-content .values > .layout > .acf-fc-layout-handle,
.acf-field-flexible-content[data-name="blog_post_sections"] .acf-flexible-content .values > .layout > .acf-fc-layout-handle {
	padding-top: 10px;
	padding-bottom: 10px;
	cursor: pointer;
}

.acf-field-flexible-content[data-name="blog_archive_sections"] .wipe-clean-admin-layout,
.acf-field-flexible-content[data-name="blog_post_sections"] .wipe-clean-admin-layout {
	display: flex;
	align-items: center;
	gap: 12px;
	max-width: calc(100% - 92px);
	user-select: none;
	pointer-events: none;
}

.acf-field-flexible-content[data-name="blog_archive_sections"] .wipe-clean-admin-layout *,
.acf-field-flexible-content[data-name="blog_post_sections"] .wipe-clean-admin-layout * {
	pointer-events: none;
}

.acf-field-flexible-content[data-name="blog_archive_sections"] .wipe-clean-admin-layout__thumb,
.acf-field-flexible-content[data-name="blog_post_sections"] .wipe-clean-admin-layout__thumb {
	width: 120px;
	flex: 0 0 120px;
	aspect-ratio: 16 / 9;
	overflow: hidden;
	border: 1px solid #D7E8EE;
	border-radius: 10px;
	background: #fff;
	box-shadow: 0 8px 18px rgba(21, 15, 49, 0.07);
}

.acf-field-flexible-content[data-name="blog_archive_sections"] .wipe-clean-admin-layout__thumb img,
.acf-field-flexible-content[data-name="blog_post_sections"] .wipe-clean-admin-layout__thumb img {
	display: block;
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.acf-field-flexible-content[data-name="blog_archive_sections"] .wipe-clean-admin-layout__body,
.acf-field-flexible-content[data-name="blog_post_sections"] .wipe-clean-admin-layout__body {
	display: grid;
	gap: 5px;
	min-width: 0;
}

.acf-field-flexible-content[data-name="blog_archive_sections"] .wipe-clean-admin-layout__top,
.acf-field-flexible-content[data-name="blog_post_sections"] .wipe-clean-admin-layout__top {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	gap: 6px 8px;
}

.acf-field-flexible-content[data-name="blog_archive_sections"] .wipe-clean-admin-layout__title,
.acf-field-flexible-content[data-name="blog_post_sections"] .wipe-clean-admin-layout__title {
	font-size: 15px;
	font-weight: 800;
	line-height: 1.2;
	color: #150F31;
}

.acf-field-flexible-content[data-name="blog_archive_sections"] .wipe-clean-admin-layout__badges,
.acf-field-flexible-content[data-name="blog_post_sections"] .wipe-clean-admin-layout__badges {
	display: flex;
	flex-wrap: wrap;
	gap: 6px;
}

.acf-field-flexible-content[data-name="blog_archive_sections"] .wipe-clean-admin-layout__description,
.acf-field-flexible-content[data-name="blog_archive_sections"] .wipe-clean-admin-layout__usage,
.acf-field-flexible-content[data-name="blog_post_sections"] .wipe-clean-admin-layout__description,
.acf-field-flexible-content[data-name="blog_post_sections"] .wipe-clean-admin-layout__usage {
	display: block;
	font-size: 12px;
	line-height: 1.45;
	color: #5D5779;
}

.acf-field-flexible-content[data-name="blog_archive_sections"] .wipe-clean-admin-layout__usage,
.acf-field-flexible-content[data-name="blog_post_sections"] .wipe-clean-admin-layout__usage {
	color: #0086B3;
}

@media (max-width: 960px) {
	.acf-field-flexible-content[data-name="blog_archive_sections"] .wipe-clean-admin-layout,
	.acf-field-flexible-content[data-name="blog_post_sections"] .wipe-clean-admin-layout {
		max-width: calc(100% - 88px);
	}

	.acf-field-flexible-content[data-name="blog_archive_sections"] .wipe-clean-admin-layout__thumb,
	.acf-field-flexible-content[data-name="blog_post_sections"] .wipe-clean-admin-layout__thumb {
		width: 96px;
		flex-basis: 96px;
	}
}

.acf-field-flexible-content[data-name="reviews_archive_sections"] .acf-flexible-content .values > .layout > .acf-fc-layout-handle {
	padding-top: 10px;
	padding-bottom: 10px;
	cursor: pointer;
}

.acf-field-flexible-content[data-name="reviews_archive_sections"] .wipe-clean-admin-layout {
	display: flex;
	align-items: center;
	gap: 12px;
	max-width: calc(100% - 92px);
	user-select: none;
	pointer-events: none;
}

.acf-field-flexible-content[data-name="reviews_archive_sections"] .wipe-clean-admin-layout * {
	pointer-events: none;
}

.acf-field-flexible-content[data-name="reviews_archive_sections"] .wipe-clean-admin-layout__thumb {
	width: 120px;
	flex: 0 0 120px;
	aspect-ratio: 16 / 9;
	overflow: hidden;
	border: 1px solid #D7E8EE;
	border-radius: 10px;
	background: #fff;
	box-shadow: 0 8px 18px rgba(21, 15, 49, 0.07);
}

.acf-field-flexible-content[data-name="reviews_archive_sections"] .wipe-clean-admin-layout__thumb img {
	display: block;
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.acf-field-flexible-content[data-name="reviews_archive_sections"] .wipe-clean-admin-layout__body {
	display: grid;
	gap: 5px;
	min-width: 0;
}

.acf-field-flexible-content[data-name="reviews_archive_sections"] .wipe-clean-admin-layout__top {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	gap: 6px 8px;
}

.acf-field-flexible-content[data-name="reviews_archive_sections"] .wipe-clean-admin-layout__title {
	font-size: 15px;
	font-weight: 800;
	line-height: 1.2;
	color: #150F31;
}

.acf-field-flexible-content[data-name="reviews_archive_sections"] .wipe-clean-admin-layout__badges {
	display: flex;
	flex-wrap: wrap;
	gap: 6px;
}

.acf-field-flexible-content[data-name="reviews_archive_sections"] .wipe-clean-admin-layout__description,
.acf-field-flexible-content[data-name="reviews_archive_sections"] .wipe-clean-admin-layout__usage {
	display: block;
	font-size: 12px;
	line-height: 1.45;
	color: #5D5779;
}

.acf-field-flexible-content[data-name="reviews_archive_sections"] .wipe-clean-admin-layout__usage {
	color: #0086B3;
}

@media (max-width: 960px) {
	.acf-field-flexible-content[data-name="reviews_archive_sections"] .wipe-clean-admin-layout {
		max-width: calc(100% - 88px);
	}

	.acf-field-flexible-content[data-name="reviews_archive_sections"] .wipe-clean-admin-layout__thumb {
		width: 96px;
		flex-basis: 96px;
	}
}

.acf-field-flexible-content[data-name="promotions_archive_sections"] .acf-flexible-content .values > .layout > .acf-fc-layout-handle {
	padding-top: 10px;
	padding-bottom: 10px;
	cursor: pointer;
}

.acf-field-flexible-content[data-name="promotions_archive_sections"] .wipe-clean-admin-layout {
	display: flex;
	align-items: center;
	gap: 12px;
	max-width: calc(100% - 92px);
	user-select: none;
	pointer-events: none;
}

.acf-field-flexible-content[data-name="promotions_archive_sections"] .wipe-clean-admin-layout * {
	pointer-events: none;
}

.acf-field-flexible-content[data-name="promotions_archive_sections"] .wipe-clean-admin-layout__thumb {
	width: 120px;
	flex: 0 0 120px;
	aspect-ratio: 16 / 9;
	overflow: hidden;
	border: 1px solid #D7E8EE;
	border-radius: 10px;
	background: #fff;
	box-shadow: 0 8px 18px rgba(21, 15, 49, 0.07);
}

.acf-field-flexible-content[data-name="promotions_archive_sections"] .wipe-clean-admin-layout__thumb img {
	display: block;
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.acf-field-flexible-content[data-name="promotions_archive_sections"] .wipe-clean-admin-layout__body {
	display: grid;
	gap: 5px;
	min-width: 0;
}

.acf-field-flexible-content[data-name="promotions_archive_sections"] .wipe-clean-admin-layout__top {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	gap: 6px 8px;
}

.acf-field-flexible-content[data-name="promotions_archive_sections"] .wipe-clean-admin-layout__title {
	font-size: 15px;
	font-weight: 800;
	line-height: 1.2;
	color: #150F31;
}

.acf-field-flexible-content[data-name="promotions_archive_sections"] .wipe-clean-admin-layout__badges {
	display: flex;
	flex-wrap: wrap;
	gap: 6px;
}

.acf-field-flexible-content[data-name="promotions_archive_sections"] .wipe-clean-admin-layout__description,
.acf-field-flexible-content[data-name="promotions_archive_sections"] .wipe-clean-admin-layout__usage {
	display: block;
	font-size: 12px;
	line-height: 1.45;
	color: #5D5779;
}

.acf-field-flexible-content[data-name="promotions_archive_sections"] .wipe-clean-admin-layout__usage {
	color: #0086B3;
}

@media (max-width: 960px) {
	.acf-field-flexible-content[data-name="promotions_archive_sections"] .wipe-clean-admin-layout {
		max-width: calc(100% - 88px);
	}

	.acf-field-flexible-content[data-name="promotions_archive_sections"] .wipe-clean-admin-layout__thumb {
		width: 96px;
		flex-basis: 96px;
	}
}
CSS;

		wp_add_inline_style( 'acf-input', $css );
	}
}
add_action( 'admin_enqueue_scripts', 'wipe_clean_enqueue_admin_section_preview_assets' );
