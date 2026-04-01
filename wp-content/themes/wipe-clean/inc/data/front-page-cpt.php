<?php
/**
 * Default CPT-backed content for the front page.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default service cards used on the front page.
 *
 * @return array<int, array<string, mixed>>
 */
function wipe_clean_get_front_page_default_service_items() {
	return array(
		array(
			'seed_key'   => 'front-page-service-support',
			'title'      => 'Поддерживающая уборка',
			'price'      => 'от 3 500 ₽',
			'home_group' => 'featured',
			'home_order' => 10,
			'image'      => wipe_clean_theme_image( 'static/images/services/service-preview-base.png', 'Поддерживающая уборка' ),
		),
		array(
			'seed_key'   => 'front-page-service-urgent',
			'title'      => 'Срочная уборка',
			'price'      => 'от 7 000 ₽',
			'home_group' => 'featured',
			'home_order' => 20,
			'image'      => wipe_clean_theme_image( 'static/images/services/service-preview-urgent.png', 'Срочная уборка' ),
		),
		array(
			'seed_key'   => 'front-page-service-apartments',
			'title'      => 'Уборка квартир',
			'price'      => 'от 10 000 ₽',
			'home_group' => 'featured',
			'home_order' => 30,
			'image'      => wipe_clean_theme_image( 'static/images/services/service-preview-apartments.png', 'Уборка квартир' ),
		),
		array(
			'seed_key'   => 'front-page-service-houses',
			'title'      => 'Уборка домов и коттеджей',
			'price'      => 'от 17 000 ₽',
			'home_group' => 'featured',
			'home_order' => 40,
			'image'      => wipe_clean_theme_image( 'static/images/services/service-preview-houses.png', 'Уборка домов и коттеджей' ),
		),
		array(
			'seed_key'   => 'front-page-service-daily',
			'title'      => 'Уборка квартир посуточно',
			'price'      => 'от 5 000 ₽',
			'home_group' => 'secondary',
			'home_order' => 10,
			'image'      => wipe_clean_theme_image( 'static/images/services/service-preview-daily.png', 'Уборка квартир посуточно' ),
		),
		array(
			'seed_key'   => 'front-page-service-windows',
			'title'      => 'Мытьё окон',
			'price'      => 'от 2 500 ₽',
			'home_group' => 'secondary',
			'home_order' => 20,
			'image'      => wipe_clean_theme_image( 'static/images/services/service-preview-windows.png', 'Мытьё окон' ),
		),
		array(
			'seed_key'   => 'front-page-service-office',
			'title'      => 'Уборка офисов',
			'price'      => 'от 19 000 ₽',
			'home_group' => 'secondary',
			'home_order' => 30,
			'image'      => wipe_clean_theme_image( 'static/images/services/service-preview-office.png', 'Уборка офисов' ),
		),
		array(
			'seed_key'   => 'front-page-service-general',
			'title'      => 'Генеральная уборка',
			'price'      => 'от 12 000 ₽',
			'home_group' => 'secondary',
			'home_order' => 40,
			'image'      => wipe_clean_theme_image( 'static/images/services/service-preview-base.png', 'Генеральная уборка' ),
		),
		array(
			'seed_key'   => 'front-page-service-after-repair',
			'title'      => 'Уборка после ремонта',
			'price'      => 'от 12 000 ₽',
			'home_group' => 'secondary',
			'home_order' => 50,
			'image'      => wipe_clean_theme_image( 'static/images/services/service-preview-after-repair.png', 'Уборка после ремонта' ),
		),
	);
}

/**
 * Default review cards used on the front page.
 *
 * @return array<int, array<string, mixed>>
 */
function wipe_clean_get_front_page_default_review_items() {
	return array(
		array(
			'seed_key'   => 'front-page-review-1',
			'author'     => 'Имя клиента',
			'text'       => 'Очень понравился результат уборки: приехали вовремя, аккуратно всё обработали и оставили после себя идеальный порядок.',
			'home_order' => 10,
			'rating'     => 5,
		),
		array(
			'seed_key'   => 'front-page-review-2',
			'author'     => 'Имя клиента',
			'text'       => 'Хороший сервис и спокойная коммуникация. Всё сделали бережно, без спешки и с вниманием к деталям.',
			'home_order' => 20,
			'rating'     => 5,
		),
		array(
			'seed_key'   => 'front-page-review-3',
			'author'     => 'Имя клиента',
			'text'       => 'Заказывали уборку перед важным мероприятием. Команда быстро привела квартиру в отличное состояние, было очень удобно.',
			'home_order' => 30,
			'rating'     => 5,
		),
	);
}
