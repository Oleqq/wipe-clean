<?php
/**
 * Front-page company preview section.
 *
 * @package wipe-clean
 */

$section  = $args['section'] ?? wipe_clean_get_front_page_section_defaults( 'company_preview' );
$benefits = ! empty( $section['benefits'] ) ? $section['benefits'] : array();
?>
<section class="company-preview">
	<div class="_container">
		<div class="company-preview__wrapper">
			<div class="company-preview__top">
				<div class="company-preview__content">
					<div class="company-preview__head">
						<h2 class="ui-title company-preview__title"><?php echo esc_html( $section['title'] ?? '' ); ?></h2>
						<?php if ( ! empty( $section['text_primary'] ) ) : ?>
							<p class="ui-text company-preview__text"><?php echo esc_html( $section['text_primary'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $section['text_secondary'] ) ) : ?>
							<p class="ui-text company-preview__text"><?php echo esc_html( $section['text_secondary'] ); ?></p>
						<?php endif; ?>
					</div>
				</div>

				<div class="company-preview__visual">
					<div class="company-preview__media">
						<?php echo wipe_clean_render_media( $section['media_image'] ?? array() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div class="company-preview__brand-card">
						<?php echo wipe_clean_render_media( $section['logo_image'] ?? array() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
			</div>

			<?php if ( $benefits ) : ?>
				<div class="company-preview__benefits swiper" data-company-preview-benefits-swiper>
					<div class="company-preview__benefits-track swiper-wrapper">
						<?php foreach ( $benefits as $benefit ) : ?>
							<div class="company-preview__benefit-slide swiper-slide">
								<article class="company-benefit-card">
									<div class="company-benefit-card__icon">
										<span class="company-benefit-card__icon-box">
											<?php echo wipe_clean_render_media( $benefit['icon'] ?? array(), array( 'aria-hidden' => 'true' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										</span>
									</div>
									<div class="company-benefit-card__body">
										<h3 class="company-benefit-card__title"><?php echo esc_html( $benefit['title'] ?? '' ); ?></h3>
										<p class="company-benefit-card__text"><?php echo esc_html( $benefit['text'] ?? '' ); ?></p>
									</div>
								</article>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
