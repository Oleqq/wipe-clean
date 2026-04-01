<?php
/**
 * Reviews archive video section.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section     = isset( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : array();
$title       = trim( (string) ( $section['title'] ?? '' ) );
$title_lines = preg_split( '/\r\n|\r|\n/', $title ) ?: array();
$top_action  = wipe_clean_resolve_link( $section['top_action'] ?? array() );
$items       = isset( $section['items'] ) && is_array( $section['items'] ) ? array_values( $section['items'] ) : array();

if ( empty( $title_lines ) ) {
	$title_lines = array( 'Видео Отзывы', 'наших клиентов' );
}
?>
<section class="video-reviews">
	<div class="_container">
		<div class="video-reviews__wrapper">
			<div class="video-reviews__head">
				<h2 class="video-reviews__title">
					<?php foreach ( $title_lines as $index => $line ) : ?>
						<?php echo esc_html( trim( (string) $line ) ); ?>
						<?php if ( $index < count( $title_lines ) - 1 ) : ?>
							<br>
						<?php endif; ?>
					<?php endforeach; ?>
				</h2>
			</div>

			<div class="video-reviews__slider swiper" data-video-reviews-swiper>
				<div class="video-reviews__track swiper-wrapper">
					<?php foreach ( $items as $item ) : ?>
						<div class="video-reviews__slide swiper-slide">
							<?php get_template_part( 'template-parts/components/video-review-card', null, array( 'item' => $item ) ); ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="video-reviews__actions">
				<?php if ( ! empty( $top_action['url'] ) ) : ?>
					<a class="ui-btn ui-btn--primary video-reviews__top-action" href="<?php echo esc_url( (string) $top_action['url'] ); ?>"<?php echo ! empty( $top_action['target'] ) ? ' target="' . esc_attr( (string) $top_action['target'] ) . '"' : ''; ?>>
						<span class="ui-btn__content"><?php echo esc_html( (string) ( $top_action['title'] ?: 'Оставить отзыв' ) ); ?></span>
					</a>
				<?php endif; ?>

				<div class="video-reviews__nav ui-slider-nav ui-slider-nav--desktop-only">
					<button class="video-reviews__nav-btn ui-btn ui-btn--primary ui-slider-nav__btn" type="button" aria-label="Предыдущее видео" data-video-reviews-prev>
						<span class="ui-btn__content">
							<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M28 18H8" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path>
								<path d="M16 10L8 18L16 26" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path>
							</svg>
						</span>
					</button>
					<button class="video-reviews__nav-btn ui-btn ui-btn--primary ui-slider-nav__btn" type="button" aria-label="Следующее видео" data-video-reviews-next>
						<span class="ui-btn__content">
							<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M8 18H28" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path>
								<path d="M20 10L28 18L20 26" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path>
							</svg>
						</span>
					</button>
				</div>
			</div>
		</div>
	</div>
</section>
