<?php
/**
 * Promotions archive section.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section         = isset( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : array();
$kicker          = trim( (string) ( $section['kicker'] ?? '' ) );
$title           = trim( (string) ( $section['title'] ?? '' ) );
$title_lines     = preg_split( '/\r\n|\r|\n/', $title ) ?: array();
$primary_action  = wipe_clean_resolve_link( $section['primary_action'] ?? array() );
$button_label    = trim( (string) ( $section['button_label'] ?? 'Показать ещё' ) );
$loading_label   = trim( (string) ( $section['button_loading_label'] ?? 'Загрузка...' ) );
$initial_desktop = max( 1, (int) ( $section['initial_desktop'] ?? 3 ) );
$initial_mobile  = max( 1, (int) ( $section['initial_mobile'] ?? 3 ) );
$step_desktop    = max( 1, (int) ( $section['step_desktop'] ?? 3 ) );
$step_mobile     = max( 1, (int) ( $section['step_mobile'] ?? 3 ) );
$items           = isset( $section['items'] ) && is_array( $section['items'] ) ? array_values( $section['items'] ) : array();
$loader_icon     = wipe_clean_asset_uri( 'static/images/section/blog-archive/blog-archive-loader.svg' );

if ( empty( $title_lines ) ) {
	$title_lines = array( 'Скидки и акции', 'на клининг в Москве' );
}
?>
<section class="promotions-archive" data-promotions-archive data-initial-desktop="<?php echo esc_attr( (string) $initial_desktop ); ?>" data-initial-mobile="<?php echo esc_attr( (string) $initial_mobile ); ?>" data-step-desktop="<?php echo esc_attr( (string) $step_desktop ); ?>" data-step-mobile="<?php echo esc_attr( (string) $step_mobile ); ?>">
	<div class="_container">
		<div class="promotions-archive__wrapper">
			<div class="promotions-archive__hero">
				<div class="promotions-archive__head ui-section-head">
					<?php if ( '' !== $kicker ) : ?>
						<span class="ui-kicker promotions-archive__kicker"><?php echo esc_html( $kicker ); ?></span>
					<?php endif; ?>
					<h1 class="ui-title ui-title--hero promotions-archive__title">
						<?php foreach ( $title_lines as $index => $line ) : ?>
							<?php echo esc_html( trim( (string) $line ) ); ?>
							<?php if ( $index < count( $title_lines ) - 1 ) : ?>
								<br>
							<?php endif; ?>
						<?php endforeach; ?>
					</h1>
				</div>
				<?php if ( ! empty( $primary_action['url'] ) ) : ?>
					<a class="ui-btn ui-btn--primary promotions-archive__action" href="<?php echo esc_url( (string) $primary_action['url'] ); ?>"<?php echo ! empty( $primary_action['target'] ) ? ' target="' . esc_attr( (string) $primary_action['target'] ) . '"' : ''; ?>>
						<span class="ui-btn__content"><?php echo esc_html( (string) ( $primary_action['title'] ?: 'Ознакомиться с услугами' ) ); ?></span>
					</a>
				<?php endif; ?>
			</div>

			<div class="promotions-archive__grid">
				<?php foreach ( $items as $index => $item ) : ?>
					<div class="promotions-archive__item" data-promotions-item data-item-index="<?php echo esc_attr( (string) $index ); ?>">
						<?php get_template_part( 'template-parts/components/promotion-card', null, array( 'item' => $item ) ); ?>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="promotions-archive__footer" data-promotions-footer>
				<div class="promotions-archive__loader" data-promotions-loader aria-hidden="true">
					<img src="<?php echo esc_url( $loader_icon ); ?>" alt="" loading="lazy">
				</div>
				<button class="promotions-archive__more ui-btn ui-btn--primary" type="button" data-promotions-more>
					<span class="ui-btn__content">
						<span class="promotions-archive__more-label promotions-archive__more-label--default"><?php echo esc_html( $button_label ); ?></span>
						<span class="promotions-archive__more-label promotions-archive__more-label--loading"><?php echo esc_html( $loading_label ); ?></span>
					</span>
				</button>
			</div>
		</div>
	</div>
</section>
