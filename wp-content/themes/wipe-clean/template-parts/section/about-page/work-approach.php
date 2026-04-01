<?php
/**
 * Work approach section.
 *
 * @package wipe-clean
 */

$section        = $args['section'] ?? wipe_clean_get_about_page_section_defaults( 'work_approach' );
$items          = array_values( $section['items'] ?? array() );
$primary_action = wipe_clean_resolve_link( $section['primary_action'] ?? array() );
?>
<section class="work-approach">
	<div class="_container">
		<div class="work-approach__wrapper">
			<div class="work-approach__head ui-section-head ui-section-head--center">
				<h2 class="ui-title work-approach__title"><?php echo esc_html( $section['title'] ?? '' ); ?></h2>
				<?php if ( ! empty( $section['text'] ) ) : ?>
					<p class="ui-text work-approach__text"><?php echo esc_html( $section['text'] ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( $items ) : ?>
				<div class="work-approach__slider swiper" data-work-approach-swiper>
					<div class="work-approach__grid swiper-wrapper">
						<?php foreach ( $items as $item ) : ?>
							<div class="work-approach__slide swiper-slide">
								<?php get_template_part( 'template-parts/components/feature-card', null, array( 'item' => $item ) ); ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $primary_action['url'] ) ) : ?>
				<a class="ui-btn ui-btn--primary work-approach__action" href="<?php echo esc_url( $primary_action['url'] ); ?>"<?php echo ! empty( $primary_action['target'] ) ? ' target="' . esc_attr( $primary_action['target'] ) . '"' : ''; ?>>
					<span class="ui-btn__content"><?php echo esc_html( $primary_action['title'] ); ?></span>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
