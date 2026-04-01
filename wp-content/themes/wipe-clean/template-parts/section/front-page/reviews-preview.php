<?php
/**
 * Front-page reviews preview section.
 *
 * @package wipe-clean
 */

$section           = $args['section'] ?? wipe_clean_get_front_page_section_defaults( 'reviews_preview' );
$items_source      = sanitize_key( (string) ( $section['items_source'] ?? '' ) );
$custom_items      = ! empty( $section['items'] ) && is_array( $section['items'] ) ? array_values( $section['items'] ) : array();
$items             = ! empty( $custom_items )
	? $custom_items
	: (
		'reviews_archive_text' === $items_source && function_exists( 'wipe_clean_get_reviews_text_items' )
			? array_values( wipe_clean_get_reviews_text_items() )
			: array_values( wipe_clean_get_front_page_review_items() )
	);
$primary_action    = wipe_clean_resolve_link( $section['primary_action'] ?? array() );
$secondary_action  = wipe_clean_resolve_link( $section['secondary_action'] ?? array() );
$render_items      = $items;
$extra_classes     = trim( (string) ( $section['class_name'] ?? $section['className'] ?? '' ) );
$section_class     = trim( 'reviews-preview ' . $extra_classes );

if ( ! empty( $render_items ) && count( $render_items ) < 6 ) {
	$index = 0;
	while ( count( $render_items ) < 6 ) {
		$render_items[] = $items[ $index % count( $items ) ];
		$index++;
	}
}
?>
<section class="<?php echo esc_attr( $section_class ); ?>">
	<div class="_container">
		<div class="reviews-preview__wrapper">
			<div class="reviews-preview__head ui-section-head ui-section-head--center">
				<h2 class="ui-title"><?php echo esc_html( $section['title'] ?? '' ); ?></h2>
				<?php if ( ! empty( $section['text'] ) ) : ?>
					<p class="ui-text"><?php echo esc_html( $section['text'] ); ?></p>
				<?php endif; ?>
			</div>

			<div class="reviews-preview__slider swiper" data-reviews-preview-swiper>
				<div class="reviews-preview__track swiper-wrapper">
					<?php foreach ( $render_items as $item ) : ?>
						<div class="reviews-preview__slide swiper-slide">
							<?php get_template_part( 'template-parts/components/review-card', null, array( 'item' => $item ) ); ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="reviews-preview__footer">
				<div class="reviews-preview__actions">
					<?php if ( ! empty( $primary_action['url'] ) ) : ?>
						<a class="ui-btn ui-btn--primary" href="<?php echo esc_url( $primary_action['url'] ); ?>"<?php echo ! empty( $primary_action['target'] ) ? ' target="' . esc_attr( $primary_action['target'] ) . '"' : ''; ?>>
							<span class="ui-btn__content"><?php echo esc_html( $primary_action['title'] ); ?></span>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $secondary_action['url'] ) ) : ?>
						<a class="ui-btn ui-btn--secondary" href="<?php echo esc_url( $secondary_action['url'] ); ?>"<?php echo ! empty( $secondary_action['target'] ) ? ' target="' . esc_attr( $secondary_action['target'] ) . '"' : ''; ?>>
							<span class="ui-btn__content"><?php echo esc_html( $secondary_action['title'] ); ?></span>
						</a>
					<?php endif; ?>
				</div>

				<div class="reviews-preview__nav ui-slider-nav ui-slider-nav--desktop-only">
					<button class="reviews-preview__nav-btn ui-btn ui-btn--primary ui-slider-nav__btn" type="button" aria-label="Предыдущий отзыв" data-reviews-preview-prev>
						<span class="ui-btn__content">
							<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M28 18H8" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path>
								<path d="M16 10L8 18L16 26" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path>
							</svg>
						</span>
					</button>
					<button class="reviews-preview__nav-btn ui-btn ui-btn--primary ui-slider-nav__btn" type="button" aria-label="Следующий отзыв" data-reviews-preview-next>
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
