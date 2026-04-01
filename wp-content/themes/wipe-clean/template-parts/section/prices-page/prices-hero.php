<?php
/**
 * Prices hero section.
 *
 * @package wipe-clean
 */

$section        = $args['section'] ?? wipe_clean_get_prices_page_section_defaults( 'prices_hero' );
$primary_action = wipe_clean_resolve_link( $section['primary_action'] ?? array() );
?>
<section class="prices-hero">
	<div class="_container">
		<div class="prices-hero__wrapper">
			<div class="prices-hero__head ui-section-head">
				<?php if ( ! empty( $section['kicker'] ) ) : ?>
					<span class="ui-kicker prices-hero__kicker"><?php echo esc_html( $section['kicker'] ); ?></span>
				<?php endif; ?>
				<h1 class="ui-title ui-title--hero prices-hero__title"><?php echo esc_html( $section['title'] ?? '' ); ?></h1>
				<?php if ( ! empty( $section['text'] ) ) : ?>
					<p class="ui-text prices-hero__text"><?php echo esc_html( $section['text'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $primary_action['url'] ) ) : ?>
					<a class="ui-btn ui-btn--primary prices-hero__action" href="<?php echo esc_url( $primary_action['url'] ); ?>"<?php echo ! empty( $primary_action['target'] ) ? ' target="' . esc_attr( $primary_action['target'] ) . '"' : ''; ?>>
						<span class="ui-btn__content"><?php echo esc_html( $primary_action['title'] ); ?></span>
					</a>
				<?php endif; ?>
			</div>
			<div class="prices-hero__media" aria-hidden="true">
				<div class="prices-hero__image prices-hero__image--left">
					<?php echo wipe_clean_render_media( $section['left_image'] ?? array(), array( 'loading' => 'eager', 'decoding' => 'async', 'fetchpriority' => 'high', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<div class="prices-hero__image prices-hero__image--right">
					<?php echo wipe_clean_render_media( $section['right_image'] ?? array(), array( 'loading' => 'eager', 'decoding' => 'async', 'fetchpriority' => 'high', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</div>
	</div>
</section>
