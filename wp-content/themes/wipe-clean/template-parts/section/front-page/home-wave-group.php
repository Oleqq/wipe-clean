<?php
/**
 * Front-page wave group section.
 *
 * @package wipe-clean
 */

$section = $args['section'] ?? wipe_clean_get_front_page_section_defaults( 'home_wave_group' );
$price   = $section['price_preview'] ?? array();
$steps   = $section['work_steps'] ?? array();
$quote   = $section['quote_request'] ?? array();

$price_rows   = ! empty( $price['rows'] ) ? $price['rows'] : array();
$step_items   = ! empty( $steps['items'] ) ? $steps['items'] : array();
$primary_link = wipe_clean_resolve_link( $price['primary_button'] ?? array() );
$second_link  = wipe_clean_resolve_link( $price['secondary_button'] ?? array() );
?>
<div class="ui-wave-group">
	<section class="price-preview">
		<div class="_container">
			<div class="price-preview__wrapper">
				<div class="price-preview__visual" aria-hidden="true">
					<?php echo wipe_clean_render_media( $price['image'] ?? array() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<div class="price-preview__content">
					<div class="price-preview__head ui-section-head ui-section-head--compact">
						<h2 class="price-preview__title ui-title">
							<?php if ( ! empty( $price['title_accent'] ) ) : ?>
								<span class="price-preview__title-accent"><?php echo esc_html( $price['title_accent'] ); ?></span>
							<?php endif; ?>
							<?php echo esc_html( $price['title'] ?? '' ); ?>
						</h2>
						<?php if ( ! empty( $price['text'] ) ) : ?>
							<p class="price-preview__text ui-text"><?php echo esc_html( $price['text'] ); ?></p>
						<?php endif; ?>
					</div>

					<?php if ( $price_rows ) : ?>
						<ul class="price-preview__list ui-price-list">
							<?php foreach ( $price_rows as $row ) : ?>
								<li class="price-preview__row ui-price-list__row">
									<span class="price-preview__label ui-price-list__label"><?php echo esc_html( $row['label'] ?? '' ); ?></span>
									<span class="price-preview__value ui-price-list__value"><?php echo esc_html( $row['value'] ?? '' ); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<div class="price-preview__actions">
						<?php if ( ! empty( $primary_link['url'] ) ) : ?>
							<a class="price-preview__action ui-btn ui-btn--primary" href="<?php echo esc_url( $primary_link['url'] ); ?>"<?php echo ! empty( $primary_link['target'] ) ? ' target="' . esc_attr( $primary_link['target'] ) . '"' : ''; ?>>
								<span class="ui-btn__content"><?php echo esc_html( $primary_link['title'] ); ?></span>
							</a>
						<?php endif; ?>

						<?php if ( ! empty( $second_link['url'] ) ) : ?>
							<a class="price-preview__action ui-btn ui-btn--secondary" href="<?php echo esc_url( $second_link['url'] ); ?>"<?php echo ! empty( $second_link['target'] ) ? ' target="' . esc_attr( $second_link['target'] ) . '"' : ''; ?>>
								<span class="ui-btn__content"><?php echo esc_html( $second_link['title'] ); ?></span>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="work-steps">
		<div class="_container">
			<div class="work-steps__wrapper">
				<div class="work-steps__head ui-section-head ui-section-head--compact ui-section-head--narrow">
					<h2 class="ui-title"><?php echo esc_html( $steps['title'] ?? '' ); ?></h2>
					<?php if ( ! empty( $steps['text'] ) ) : ?>
						<p class="ui-text"><?php echo esc_html( $steps['text'] ); ?></p>
					<?php endif; ?>
				</div>
				<div class="work-steps__slider swiper" data-work-steps-swiper>
					<div class="work-steps__grid swiper-wrapper">
						<?php foreach ( $step_items as $item ) : ?>
							<article class="ui-card work-steps__item swiper-slide">
								<span class="work-steps__number ui-title--number"><?php echo esc_html( $item['number'] ?? '' ); ?></span>
								<div class="work-steps__item-body">
									<h3 class="work-steps__item-title"><?php echo esc_html( $item['title'] ?? '' ); ?></h3>
									<p class="work-steps__item-text ui-text"><?php echo esc_html( $item['text'] ?? '' ); ?></p>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="quote-request">
		<div class="_container">
			<div class="quote-request__wrapper">
				<div class="quote-request__visual" aria-hidden="true">
					<div class="quote-request__visual-media">
						<?php echo wipe_clean_render_media( $quote['image'] ?? array() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
				<div class="quote-request__content">
					<div class="quote-request__head ui-section-head ui-section-head--compact">
						<h2 class="quote-request__title ui-title"><?php echo esc_html( $quote['title'] ?? '' ); ?></h2>
						<?php if ( ! empty( $quote['text'] ) ) : ?>
							<p class="quote-request__text ui-text"><?php echo esc_html( $quote['text'] ); ?></p>
						<?php endif; ?>
					</div>
					<?php echo wipe_clean_render_managed_cf7_form( 'quote_request' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</div>
	</section>
</div>
