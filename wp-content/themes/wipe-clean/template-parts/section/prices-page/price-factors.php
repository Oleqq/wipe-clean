<?php
/**
 * Price factors section.
 *
 * @package wipe-clean
 */

$section        = $args['section'] ?? wipe_clean_get_prices_page_section_defaults( 'price_factors' );
$items          = array_values( $section['items'] ?? array() );
$primary_action = wipe_clean_resolve_link( $section['primary_action'] ?? array() );
?>
<section class="price-factors">
	<div class="_container">
		<div class="price-factors__wrapper">
			<div class="price-factors__head ui-section-head ui-section-head--compact">
				<h2 class="price-factors__title ui-title"><?php echo esc_html( $section['title'] ?? '' ); ?></h2>
				<?php if ( ! empty( $section['text'] ) ) : ?>
					<p class="price-factors__text ui-text"><?php echo esc_html( $section['text'] ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( $items ) : ?>
				<div class="price-factors__slider swiper" data-price-factors-swiper>
					<div class="price-factors__grid swiper-wrapper">
						<?php foreach ( $items as $index => $item ) : ?>
							<div class="price-factors__slide swiper-slide">
								<?php
								get_template_part(
									'template-parts/components/number-card',
									null,
									array(
										'item' => array(
											'number' => sprintf( '%02d', $index + 1 ),
											'title'  => $item['title'] ?? '',
											'text'   => $item['text'] ?? '',
										),
									)
								);
								?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $primary_action['url'] ) ) : ?>
				<a class="price-factors__action ui-btn ui-btn--primary" href="<?php echo esc_url( $primary_action['url'] ); ?>"<?php echo ! empty( $primary_action['target'] ) ? ' target="' . esc_attr( $primary_action['target'] ) . '"' : ''; ?>>
					<span class="ui-btn__content"><?php echo esc_html( $primary_action['title'] ); ?></span>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
