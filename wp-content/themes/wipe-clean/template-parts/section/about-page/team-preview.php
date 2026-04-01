<?php
/**
 * Team preview section.
 *
 * @package wipe-clean
 */

$section = $args['section'] ?? wipe_clean_get_about_page_section_defaults( 'team_preview' );
$items   = array_values( $section['items'] ?? array() );
?>
<section class="team-preview">
	<div class="_container">
		<div class="team-preview__wrapper">
			<div class="team-preview__top">
				<div class="team-preview__head ui-section-head">
					<h2 class="ui-title team-preview__title"><?php echo esc_html( $section['title'] ?? '' ); ?></h2>
					<?php if ( ! empty( $section['text'] ) ) : ?>
						<p class="ui-text team-preview__text"><?php echo esc_html( $section['text'] ); ?></p>
					<?php endif; ?>
				</div>

				<div class="team-preview__nav ui-slider-nav ui-slider-nav--desktop-only">
					<button class="team-preview__nav-btn ui-btn ui-btn--primary ui-slider-nav__btn" type="button" aria-label="Предыдущий сотрудник" data-team-preview-prev>
						<span class="ui-btn__content">
							<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M28 18H8" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path>
								<path d="M16 10L8 18L16 26" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path>
							</svg>
						</span>
					</button>
					<button class="team-preview__nav-btn ui-btn ui-btn--primary ui-slider-nav__btn" type="button" aria-label="Следующий сотрудник" data-team-preview-next>
						<span class="ui-btn__content">
							<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M8 18H28" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path>
								<path d="M20 10L28 18L20 26" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path>
							</svg>
						</span>
					</button>
				</div>
			</div>

			<?php if ( $items ) : ?>
				<div class="team-preview__slider swiper" data-team-preview-swiper>
					<div class="team-preview__track swiper-wrapper">
						<?php foreach ( $items as $item ) : ?>
							<div class="team-preview__slide swiper-slide">
								<?php get_template_part( 'template-parts/components/team-member-card', null, array( 'item' => $item ) ); ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
