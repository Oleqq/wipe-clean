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
			'seed_key'     => 'front-page-service-support',
			'title'        => 'Поддерживающая уборка',
			'price'        => 'от 3 500 ₽',
			'home_group'   => 'featured',
			'home_order'   => 10,
			'card_variant' => 'standard',
			'layers'       => array(
				array(
					'image_path' => 'static/images/services/service-preview-base.png',
					'modifier'   => 'fill',
				),
			),
		),
		array(
			'seed_key'     => 'front-page-service-urgent',
			'title'        => 'Срочная уборка',
			'price'        => 'от 7 000 ₽',
			'home_group'   => 'featured',
			'home_order'   => 20,
			'card_variant' => 'standard',
			'layers'       => array(
				array(
					'image_path' => 'static/images/services/service-preview-urgent.png',
					'modifier'   => 'fill',
				),
			),
		),
		array(
			'seed_key'     => 'front-page-service-apartments',
			'title'        => 'Уборка квартир',
			'price'        => 'от 10 000 ₽',
			'home_group'   => 'featured',
			'home_order'   => 30,
			'card_variant' => 'standard',
			'layers'       => array(
				array(
					'image_path' => 'static/images/services/service-preview-base.png',
					'modifier'   => 'shift-top',
				),
				array(
					'image_path' => 'static/images/services/service-preview-apartments.png',
					'modifier'   => 'fill',
				),
			),
		),
		array(
			'seed_key'     => 'front-page-service-houses',
			'title'        => 'Уборка домов и коттеджей',
			'price'        => 'от 17 000 ₽',
			'home_group'   => 'featured',
			'home_order'   => 40,
			'card_variant' => 'standard',
			'layers'       => array(
				array(
					'image_path' => 'static/images/services/service-preview-urgent.png',
					'modifier'   => 'fill',
				),
				array(
					'image_path' => 'static/images/services/service-preview-houses.png',
					'modifier'   => 'shift-right',
				),
			),
		),
		array(
			'seed_key'     => 'front-page-service-daily',
			'title'        => 'Уборка квартир по суточно',
			'price'        => 'от 5 000 ₽',
			'home_group'   => 'secondary',
			'home_order'   => 10,
			'card_variant' => 'standard',
			'layers'       => array(
				array(
					'image_path' => 'static/images/services/service-preview-base.png',
					'modifier'   => 'shift-top',
				),
				array(
					'image_path' => 'static/images/services/service-preview-apartments.png',
					'modifier'   => 'fill',
				),
				array(
					'image_path' => 'static/images/services/service-preview-daily.png',
					'modifier'   => 'fill',
				),
			),
		),
		array(
			'seed_key'     => 'front-page-service-windows',
			'title'        => 'Мытьё окон',
			'price'        => 'от 2 500 ₽',
			'home_group'   => 'secondary',
			'home_order'   => 20,
			'card_variant' => 'standard',
			'layers'       => array(
				array(
					'image_path' => 'static/images/services/service-preview-urgent.png',
					'modifier'   => 'fill',
				),
				array(
					'image_path' => 'static/images/services/service-preview-houses.png',
					'modifier'   => 'shift-right',
				),
				array(
					'image_path' => 'static/images/services/service-preview-windows.png',
					'modifier'   => 'flip',
				),
			),
		),
		array(
			'seed_key'     => 'front-page-service-office',
			'title'        => 'Уборка офисов',
			'price'        => 'от 19 000 ₽',
			'home_group'   => 'secondary',
			'home_order'   => 30,
			'card_variant' => 'standard',
			'layers'       => array(
				array(
					'image_path' => 'static/images/services/service-preview-base.png',
					'modifier'   => 'shift-top',
				),
				array(
					'image_path' => 'static/images/services/service-preview-apartments.png',
					'modifier'   => 'fill',
				),
				array(
					'image_path' => 'static/images/services/service-preview-daily.png',
					'modifier'   => 'fill',
				),
				array(
					'image_path' => 'static/images/services/service-preview-office.png',
					'modifier'   => 'shift-right',
				),
			),
		),
		array(
			'seed_key'     => 'front-page-service-general',
			'title'        => 'Генеральная уборка',
			'price'        => 'от 12 000 ₽',
			'home_group'   => 'secondary',
			'home_order'   => 40,
			'card_variant' => 'standard',
			'layers'       => array(
				array(
					'image_path' => 'static/images/services/service-preview-base.png',
					'modifier'   => 'shift-top',
				),
				array(
					'image_path' => 'static/images/services/service-preview-urgent.png',
					'modifier'   => 'fill',
				),
				array(
					'image_path' => 'static/images/services/service-preview-houses.png',
					'modifier'   => 'shift-right',
				),
				array(
					'image_path' => 'static/images/services/service-preview-windows.png',
					'modifier'   => 'flip',
				),
				array(
					'image_path' => 'static/images/services/service-preview-after-repair.png',
					'modifier'   => 'overlay',
				),
			),
		),
		array(
			'seed_key'     => 'front-page-service-after-repair',
			'title'        => 'Уборка после ремонта',
			'price'        => 'от 12 000 ₽',
			'home_group'   => 'secondary',
			'home_order'   => 50,
			'card_variant' => 'after_repair',
			'layers'       => array(
				array(
					'image_path' => 'static/images/services/service-preview-base.png',
					'modifier'   => 'shift-top',
				),
				array(
					'image_path' => 'static/images/services/service-preview-urgent.png',
					'modifier'   => 'fill',
				),
				array(
					'image_path' => 'static/images/services/service-preview-houses.png',
					'modifier'   => 'shift-right',
				),
				array(
					'image_path' => 'static/images/services/service-preview-windows.png',
					'modifier'   => 'flip',
				),
				array(
					'image_path' => 'static/images/services/service-preview-after-repair.png',
					'modifier'   => 'overlay',
				),
				array(
					'image_path' => 'static/images/services/service-preview-after-repair.png',
					'modifier'   => 'fill',
				),
			),
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
			'text'       => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
			'home_order' => 10,
			'rating'     => 5,
		),
		array(
			'seed_key'   => 'front-page-review-2',
			'author'     => 'Имя клиента',
			'text'       => 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
			'home_order' => 20,
			'rating'     => 5,
		),
		array(
			'seed_key'   => 'front-page-review-3',
			'author'     => 'Имя клиента',
			'text'       => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.',
			'home_order' => 30,
			'rating'     => 5,
		),
	);
}
