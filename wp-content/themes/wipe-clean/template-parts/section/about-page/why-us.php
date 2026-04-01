<?php
/**
 * Why us section.
 *
 * @package wipe-clean
 */

$section        = $args['section'] ?? wipe_clean_get_about_page_section_defaults( 'why_us' );
$items          = array_values( $section['items'] ?? array() );
$primary_action = wipe_clean_resolve_link( $section['primary_action'] ?? array() );
?>
<section class="why-us">
	<div class="_container">
		<div class="why-us__wrapper">
			<div class="why-us__head ui-section-head">
				<h2 class="ui-title why-us__title"><?php echo esc_html( $section['title'] ?? '' ); ?></h2>
				<?php if ( ! empty( $section['text_primary'] ) ) : ?>
					<p class="ui-text why-us__text"><?php echo esc_html( $section['text_primary'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $section['text_secondary'] ) ) : ?>
					<p class="ui-text why-us__text"><?php echo esc_html( $section['text_secondary'] ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( $items ) : ?>
				<div class="why-us__slider swiper" data-why-us-swiper>
					<div class="why-us__grid swiper-wrapper">
						<?php foreach ( $items as $index => $item ) : ?>
							<?php
							$slide_classes = array( 'why-us__slide', 'swiper-slide' );

							if ( 3 === $index ) {
								$slide_classes[] = 'why-us__slide--offset-2';
							}

							if ( 4 === $index ) {
								$slide_classes[] = 'why-us__slide--offset-3';
							}

							$item['class_name'] = trim( (string) ( $item['class_name'] ?? 'feature-card--compact' ) );
							?>
							<div class="<?php echo esc_attr( implode( ' ', $slide_classes ) ); ?>">
								<?php get_template_part( 'template-parts/components/feature-card', null, array( 'item' => $item ) ); ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $primary_action['url'] ) ) : ?>
				<a class="ui-btn ui-btn--primary why-us__action" href="<?php echo esc_url( $primary_action['url'] ); ?>"<?php echo ! empty( $primary_action['target'] ) ? ' target="' . esc_attr( $primary_action['target'] ) . '"' : ''; ?>>
					<span class="ui-btn__content"><?php echo esc_html( $primary_action['title'] ); ?></span>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
