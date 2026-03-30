<?php
/**
 * Visual admin previews for flexible content blocks.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get visual block registry for admin previews.
 *
 * @return array<string, array<string, mixed>>
 */
function wipe_clean_get_section_preview_registry() {
	return array(
		'home_hero'       => array(
			'label'       => 'Первый экран',
			'description' => 'Верхний блок страницы с заголовком, изображением и формой расчёта.',
			'preview'     => 'static/images/admin-section-preview/front-page/home-hero.png',
			'core_pages'  => array( 'front_page' ),
			'used_on'     => array(),
		),
		'services_preview' => array(
			'label'       => 'Услуги',
			'description' => 'Карточки основных услуг с ценами и кнопками перехода.',
			'preview'     => 'static/images/admin-section-preview/front-page/services-preview.png',
			'core_pages'  => array( 'front_page' ),
			'used_on'     => array( 'О компании' ),
		),
		'home_wave_group' => array(
			'label'       => 'Блок с ценами и этапами',
			'description' => 'Связка из цен, этапов работы и формы расчёта стоимости.',
			'preview'     => 'static/images/admin-section-preview/front-page/home-wave-group.png',
			'core_pages'  => array( 'front_page' ),
			'used_on'     => array(),
		),
		'company_preview' => array(
			'label'       => 'О компании',
			'description' => 'Текстовый блок о компании с изображением и преимуществами.',
			'preview'     => 'static/images/admin-section-preview/front-page/company-preview.png',
			'core_pages'  => array( 'front_page' ),
			'used_on'     => array( 'Акции' ),
		),
		'reviews_preview' => array(
			'label'       => 'Отзывы',
			'description' => 'Слайдер с отзывами клиентов и кнопками перехода.',
			'preview'     => 'static/images/admin-section-preview/front-page/reviews-preview.png',
			'core_pages'  => array( 'front_page' ),
			'used_on'     => array( 'О компании', 'Контакты' ),
		),
		'gallery_preview' => array(
			'label'       => 'Галерея',
			'description' => 'Фотографии и видео выполненных работ.',
			'preview'     => 'static/images/admin-section-preview/front-page/gallery-preview.png',
			'core_pages'  => array( 'front_page' ),
			'used_on'     => array( 'Блог', 'Отзывы' ),
		),
		'faq'             => array(
			'label'       => 'Вопросы и ответы',
			'description' => 'Список частых вопросов клиентов и ответов на них.',
			'preview'     => 'static/images/admin-section-preview/front-page/faq.png',
			'core_pages'  => array( 'front_page' ),
			'used_on'     => array( 'Услуги', 'FAQ', 'Отзывы', 'Акции', 'Контакты', 'Страница услуги' ),
		),
		'contacts'        => array(
			'label'       => 'Контакты',
			'description' => 'Контакты компании, способы связи и форма обращения.',
			'preview'     => 'static/images/admin-section-preview/front-page/contacts.png',
			'core_pages'  => array( 'front_page' ),
			'used_on'     => array( 'Блог', 'Отзывы', 'FAQ', 'Акции', 'Страница услуги' ),
		),
	);
}

/**
 * Get page block sets.
 *
 * @return array<string, array<string, mixed>>
 */
function wipe_clean_get_page_section_sets() {
	return array(
		'front_page' => array(
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
			'optional'   => array(),
		),
	);
}

/**
 * Get admin badges for a block.
 *
 * @param string $layout_name Layout key.
 * @param string $page_key    Page key.
 * @return array<int, array<string, string>>
 */
function wipe_clean_get_section_preview_badges( $layout_name, $page_key ) {
	$page_sets = wipe_clean_get_page_section_sets();
	$registry  = wipe_clean_get_section_preview_registry();
	$badges    = array();

	if ( empty( $page_sets[ $page_key ] ) || empty( $registry[ $layout_name ] ) ) {
		return $badges;
	}

	$page_set = $page_sets[ $page_key ];
	$meta     = $registry[ $layout_name ];

	if ( ! empty( $page_set['core'] ) && in_array( $layout_name, $page_set['core'], true ) ) {
		$badges[] = array(
			'label' => 'Основная',
			'type'  => 'core',
		);
	}

	if ( ! empty( $meta['used_on'] ) ) {
		$badges[] = array(
			'label' => 'Есть и на других страницах',
			'type'  => 'shared',
		);
	}

	$badges[] = array(
		'label' => $page_set['label'],
		'type'  => 'page',
	);

	return $badges;
}

/**
 * Get short summary for current flexible row.
 *
 * @param string $layout_name Layout key.
 * @return string
 */
function wipe_clean_get_front_page_layout_preview_summary( $layout_name ) {
	$summary = '';

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

	$summary = trim( wp_strip_all_tags( $summary ) );

	if ( '' === $summary ) {
		return '';
	}

	return wp_html_excerpt( $summary, 88, '…' );
}

/**
 * Build preview payload for admin JS.
 *
 * @param string $page_key Page key.
 * @return array<string, array<string, mixed>>
 */
function wipe_clean_build_admin_section_preview_payload( $page_key ) {
	$registry  = wipe_clean_get_section_preview_registry();
	$payload   = array();
	$page_sets = wipe_clean_get_page_section_sets();

	if ( empty( $page_sets[ $page_key ] ) ) {
		return $payload;
	}

	foreach ( $page_sets[ $page_key ]['core'] as $layout_name ) {
		if ( empty( $registry[ $layout_name ] ) ) {
			continue;
		}

		$meta                   = $registry[ $layout_name ];
		$payload[ $layout_name ] = array(
			'label'       => (string) $meta['label'],
			'description' => (string) $meta['description'],
			'preview'     => wipe_clean_asset_uri( (string) $meta['preview'] ),
			'badges'      => wipe_clean_get_section_preview_badges( $layout_name, $page_key ),
			'usedOn'      => array_values( $meta['used_on'] ?? array() ),
		);
	}

	return $payload;
}

/**
 * Render badges HTML for flexible row handle.
 *
 * @param array<int, array<string, string>> $badges Badges.
 * @return string
 */
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

/**
 * Replace flexible row title with visual preview row.
 *
 * @param string $title  Default title.
 * @param array  $field  Field settings.
 * @param array  $layout Layout settings.
 * @param int    $i      Row index.
 * @return string
 */
function wipe_clean_filter_front_page_layout_title( $title, $field, $layout, $i ) {
	$registry = wipe_clean_build_admin_section_preview_payload( 'front_page' );
	$name     = (string) ( $layout['name'] ?? '' );

	if ( empty( $registry[ $name ] ) ) {
		return $title;
	}

	$meta        = $registry[ $name ];
	$summary     = wipe_clean_get_front_page_layout_preview_summary( $name );
	$summary     = '' !== $summary ? $summary : (string) $meta['description'];
	$usage_line  = ! empty( $meta['usedOn'] ) ? 'Также используется: ' . implode( ', ', array_map( 'sanitize_text_field', $meta['usedOn'] ) ) : '';
	$badges_html = wipe_clean_render_admin_section_badges_html( $meta['badges'] );

	return sprintf(
		'<span class="wipe-clean-admin-layout"><span class="wipe-clean-admin-layout__thumb"><img src="%1$s" alt="%2$s"></span><span class="wipe-clean-admin-layout__body"><span class="wipe-clean-admin-layout__top"><span class="wipe-clean-admin-layout__title">%2$s</span><span class="wipe-clean-admin-layout__badges">%3$s</span></span><span class="wipe-clean-admin-layout__description">%4$s</span>%5$s</span></span>',
		esc_url( (string) $meta['preview'] ),
		esc_html( (string) $meta['label'] ),
		$badges_html,
		esc_html( $summary ),
		'' !== $usage_line ? sprintf( '<span class="wipe-clean-admin-layout__usage">%s</span>', esc_html( $usage_line ) ) : ''
	);
}
add_filter( 'acf/fields/flexible_content/layout_title/name=front_page_sections', 'wipe_clean_filter_front_page_layout_title', 10, 4 );

/**
 * Enqueue admin styles and scripts for flexible previews.
 *
 * @param string $hook Current admin hook.
 * @return void
 */
function wipe_clean_enqueue_admin_section_preview_assets( $hook ) {
	$screen = get_current_screen();

	if ( ! $screen || 'page' !== $screen->post_type || ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	$payload = wipe_clean_build_admin_section_preview_payload( 'front_page' );

	if ( empty( $payload ) ) {
		return;
	}

	$css = <<<'CSS'
.acf-field-flexible-content[data-name="front_page_sections"] .acf-flexible-content .values > .layout > .acf-fc-layout-handle {
	padding-top: 10px;
	padding-bottom: 10px;
	cursor: pointer;
}

.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout {
	display: flex;
	align-items: center;
	gap: 12px;
	max-width: calc(100% - 92px);
	user-select: none;
	pointer-events: none;
}

.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout * {
	pointer-events: none;
}

.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout__thumb {
	width: 120px;
	flex: 0 0 120px;
	aspect-ratio: 16 / 9;
	overflow: hidden;
	border: 1px solid #D7E8EE;
	border-radius: 10px;
	background: #fff;
	box-shadow: 0 8px 18px rgba(21, 15, 49, 0.07);
}

.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout__thumb img {
	display: block;
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout__body {
	display: grid;
	gap: 5px;
	min-width: 0;
}

.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout__top {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	gap: 6px 8px;
}

.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout__title {
	font-size: 15px;
	font-weight: 800;
	line-height: 1.2;
	color: #150F31;
}

.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout__badges {
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
.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout__usage {
	display: block;
	font-size: 12px;
	line-height: 1.45;
	color: #5D5779;
}

.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout__usage {
	color: #0086B3;
}

@media (max-width: 960px) {
	.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout {
		max-width: calc(100% - 88px);
	}

	.acf-field-flexible-content[data-name="front_page_sections"] .wipe-clean-admin-layout__thumb {
		width: 96px;
		flex-basis: 96px;
	}
}
CSS;

	wp_add_inline_style( 'acf-input', $css );

}
add_action( 'admin_enqueue_scripts', 'wipe_clean_enqueue_admin_section_preview_assets' );
