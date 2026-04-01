<?php
/**
 * Reviews archive text section.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section         = isset( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : array();
$kicker          = trim( (string) ( $section['kicker'] ?? '' ) );
$title           = trim( (string) ( $section['title'] ?? '' ) );
$top_action      = wipe_clean_resolve_link( $section['top_action'] ?? array() );
$load_more_label = trim( (string) ( $section['load_more_label'] ?? 'Больше отзывов' ) );
$initial_desktop = max( 1, (int) ( $section['initial_desktop'] ?? 8 ) );
$initial_mobile  = max( 1, (int) ( $section['initial_mobile'] ?? 6 ) );
$step_desktop    = max( 1, (int) ( $section['step_desktop'] ?? 2 ) );
$step_mobile     = max( 1, (int) ( $section['step_mobile'] ?? 3 ) );
$items           = isset( $section['items'] ) && is_array( $section['items'] ) ? array_values( $section['items'] ) : array();
$loader_icon     = wipe_clean_asset_uri( 'static/images/section/blog-archive/blog-archive-loader.svg' );
?>
<section class="reviews-archive" data-reviews-archive data-initial-desktop="<?php echo esc_attr( (string) $initial_desktop ); ?>" data-initial-mobile="<?php echo esc_attr( (string) $initial_mobile ); ?>" data-step-desktop="<?php echo esc_attr( (string) $step_desktop ); ?>" data-step-mobile="<?php echo esc_attr( (string) $step_mobile ); ?>">
	<div class="_container">
		<div class="reviews-archive__wrapper">
			<div class="reviews-archive__intro">
				<div class="reviews-archive__head">
					<?php if ( '' !== $kicker ) : ?>
						<div class="reviews-archive__kicker"><?php echo esc_html( $kicker ); ?></div>
					<?php endif; ?>
					<h1 class="reviews-archive__title ui-title ui-title--hero"><?php echo esc_html( $title ); ?></h1>
				</div>
				<?php if ( ! empty( $top_action['url'] ) ) : ?>
					<a class="ui-btn ui-btn--primary reviews-archive__top-action" href="<?php echo esc_url( (string) $top_action['url'] ); ?>"<?php echo ! empty( $top_action['target'] ) ? ' target="' . esc_attr( (string) $top_action['target'] ) . '"' : ''; ?>>
						<span class="ui-btn__content"><?php echo esc_html( (string) ( $top_action['title'] ?: 'Оставить отзыв' ) ); ?></span>
					</a>
				<?php endif; ?>
			</div>

			<div class="reviews-archive__grid">
				<?php foreach ( $items as $index => $item ) : ?>
					<div class="reviews-archive__item" data-reviews-archive-item data-item-index="<?php echo esc_attr( (string) $index ); ?>">
						<?php get_template_part( 'template-parts/components/review-card', null, array( 'item' => $item ) ); ?>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="reviews-archive__footer" data-reviews-archive-footer>
				<div class="reviews-archive__loader" data-reviews-archive-loader aria-hidden="true">
					<img src="<?php echo esc_url( $loader_icon ); ?>" alt="" loading="lazy">
				</div>
				<button class="reviews-archive__more ui-btn ui-btn--primary" type="button" data-reviews-archive-more>
					<span class="ui-btn__content"><?php echo esc_html( $load_more_label ); ?></span>
				</button>
			</div>
		</div>
	</div>
</section>
