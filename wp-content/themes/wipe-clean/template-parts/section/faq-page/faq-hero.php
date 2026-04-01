<?php
/**
 * FAQ hero section.
 *
 * @package wipe-clean
 */

$section        = $args['section'] ?? wipe_clean_get_faq_page_section_defaults( 'faq_hero' );
$primary_action = wipe_clean_resolve_link( $section['primary_action'] ?? array() );
?>
<section class="faq-hero">
	<div class="_container">
		<div class="faq-hero__wrapper">
			<div class="faq-hero__content">
				<div class="faq-hero__head ui-section-head">
					<?php if ( ! empty( $section['kicker'] ) ) : ?>
						<span class="ui-kicker faq-hero__kicker"><?php echo esc_html( $section['kicker'] ); ?></span>
					<?php endif; ?>
					<h1 class="ui-title ui-title--hero faq-hero__title"><?php echo esc_html( $section['title'] ?? '' ); ?></h1>
				</div>

				<?php if ( ! empty( $section['text'] ) ) : ?>
					<p class="ui-text faq-hero__text"><?php echo esc_html( $section['text'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $primary_action['url'] ) ) : ?>
					<a class="ui-btn faq-hero__action ui-btn--primary" href="<?php echo esc_url( $primary_action['url'] ); ?>"<?php echo ! empty( $primary_action['target'] ) ? ' target="' . esc_attr( $primary_action['target'] ) . '"' : ''; ?>>
						<span class="ui-btn__content"><?php echo esc_html( $primary_action['title'] ); ?></span>
					</a>
				<?php endif; ?>
			</div>

			<div class="faq-hero__media" aria-hidden="true">
				<?php echo wipe_clean_render_media( $section['image'] ?? array(), array( 'alt' => '', 'loading' => 'eager', 'decoding' => 'async', 'fetchpriority' => 'high' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
	</div>
</section>
