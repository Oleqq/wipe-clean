<?php
/**
 * Area pricing section.
 *
 * @package wipe-clean
 */

$section = $args['section'] ?? wipe_clean_get_prices_page_section_defaults( 'area_pricing' );
$items   = array_values( $section['items'] ?? array() );
$cards   = array_values( $section['cards'] ?? array() );
?>
<section class="area-pricing">
	<div class="_container">
		<div class="area-pricing__wrapper">
			<div class="area-pricing__main">
				<div class="area-pricing__head ui-section-head ui-section-head--compact">
					<h2 class="area-pricing__title ui-title"><?php echo esc_html( $section['title'] ?? '' ); ?></h2>
					<?php if ( ! empty( $section['text'] ) ) : ?>
						<p class="area-pricing__text ui-text"><?php echo esc_html( $section['text'] ); ?></p>
					<?php endif; ?>
				</div>
				<div class="area-pricing__visual" aria-hidden="true">
					<div class="area-pricing__visual-media">
						<?php echo wipe_clean_render_media( $section['image'] ?? array() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
				<?php if ( $items ) : ?>
					<ul class="area-pricing__list ui-price-list">
						<?php foreach ( $items as $item ) : ?>
							<li class="area-pricing__row ui-price-list__row">
								<span class="area-pricing__label ui-price-list__label"><?php echo esc_html( $item['label'] ?? '' ); ?></span>
								<span class="area-pricing__value ui-price-list__value"><?php echo esc_html( $item['value'] ?? '' ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<?php if ( $cards ) : ?>
				<div class="area-pricing__cards">
					<?php foreach ( $cards as $card ) : ?>
						<?php
						$accent_lines = preg_split( '/\r\n|\r|\n/', (string) ( $card['accent_lines'] ?? '' ) );
						$accent_lines = array_values( array_filter( array_map( 'trim', (array) $accent_lines ) ) );
						?>
						<div class="area-pricing__card ui-card ui-card--compact">
							<h3 class="area-pricing__card-title"><?php echo esc_html( $card['title'] ?? '' ); ?></h3>
							<?php if ( ! empty( $card['text'] ) ) : ?>
								<p class="area-pricing__card-text ui-text"><?php echo esc_html( $card['text'] ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $card['accent_text'] ) ) : ?>
								<p class="area-pricing__card-accent"><?php echo esc_html( $card['accent_text'] ); ?></p>
							<?php endif; ?>
							<?php if ( $accent_lines ) : ?>
								<div class="area-pricing__card-accents">
									<?php foreach ( $accent_lines as $accent_line ) : ?>
										<p class="area-pricing__card-accent"><?php echo esc_html( $accent_line ); ?></p>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
