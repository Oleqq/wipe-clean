<?php
/**
 * Price advantages section.
 *
 * @package wipe-clean
 */

$section = $args['section'] ?? wipe_clean_get_prices_page_section_defaults( 'price_advantages' );
$items   = array_values( $section['items'] ?? array() );
?>
<section class="price-advantages">
	<div class="_container">
		<div class="price-advantages__wrapper">
			<div class="price-advantages__head ui-section-head ui-section-head--center">
				<h2 class="price-advantages__title ui-title"><?php echo esc_html( $section['title'] ?? '' ); ?></h2>
				<?php if ( ! empty( $section['text'] ) ) : ?>
					<p class="price-advantages__text ui-text"><?php echo esc_html( $section['text'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $section['note_text'] ) ) : ?>
					<p class="price-advantages__note ui-text"><?php echo esc_html( $section['note_text'] ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( $items ) : ?>
				<div class="price-advantages__slider swiper" data-price-advantages-swiper>
					<div class="price-advantages__grid swiper-wrapper">
						<?php foreach ( $items as $item ) : ?>
							<div class="price-advantages__slide swiper-slide">
								<?php
								$item['class_name'] = trim( (string) ( $item['class_name'] ?? 'feature-card--stacked' ) );
								get_template_part( 'template-parts/components/feature-card', null, array( 'item' => $item ) );
								?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
