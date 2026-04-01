<?php
/**
 * About hero section.
 *
 * @package wipe-clean
 */

$section          = $args['section'] ?? wipe_clean_get_about_page_section_defaults( 'about_hero' );
$primary_action   = wipe_clean_resolve_link( $section['primary_action'] ?? array() );
$secondary_action = wipe_clean_resolve_link( $section['secondary_action'] ?? array() );
$title_lines      = preg_split( '/\r\n|\r|\n/', (string) ( $section['title'] ?? '' ) );
$title_lines      = array_values( array_filter( array_map( 'trim', (array) $title_lines ) ) );

if ( empty( $title_lines ) ) {
	$title_lines = array( 'КЛИНИНГОВАЯ', 'КОМПАНИЯ ВАЙП–Клин' );
}
?>
<section class="about-hero">
	<div class="_container">
		<div class="about-hero__wrapper">
			<div class="about-hero__media" aria-hidden="true">
				<div class="about-hero__decor">
					<?php echo wipe_clean_render_media( $section['decor_image'] ?? array(), array( 'alt' => '', 'loading' => 'eager', 'decoding' => 'async', 'fetchpriority' => 'high' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<div class="about-hero__image">
					<?php echo wipe_clean_render_media( $section['main_image'] ?? array(), array( 'alt' => '', 'loading' => 'eager', 'decoding' => 'async', 'fetchpriority' => 'high' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
			<div class="about-hero__head ui-section-head">
				<?php if ( ! empty( $section['kicker'] ) ) : ?>
					<span class="ui-kicker about-hero__kicker"><?php echo esc_html( $section['kicker'] ); ?></span>
				<?php endif; ?>
				<h1 class="ui-title ui-title--hero about-hero__title">
					<?php foreach ( $title_lines as $index => $line ) : ?>
						<?php echo esc_html( $line ); ?>
						<?php if ( $index < count( $title_lines ) - 1 ) : ?>
							<br>
						<?php endif; ?>
					<?php endforeach; ?>
				</h1>
			</div>
			<div class="about-hero__content">
				<?php if ( ! empty( $section['text'] ) ) : ?>
					<p class="ui-text about-hero__text"><?php echo esc_html( $section['text'] ); ?></p>
				<?php endif; ?>
				<div class="about-hero__actions">
					<?php if ( ! empty( $primary_action['url'] ) ) : ?>
						<a class="ui-btn ui-btn--primary about-hero__action" href="<?php echo esc_url( $primary_action['url'] ); ?>"<?php echo ! empty( $primary_action['target'] ) ? ' target="' . esc_attr( $primary_action['target'] ) . '"' : ''; ?>>
							<span class="ui-btn__content"><?php echo esc_html( $primary_action['title'] ); ?></span>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $secondary_action['url'] ) ) : ?>
						<a class="ui-btn ui-btn--secondary about-hero__action" href="<?php echo esc_url( $secondary_action['url'] ); ?>"<?php echo ! empty( $secondary_action['target'] ) ? ' target="' . esc_attr( $secondary_action['target'] ) . '"' : ''; ?>>
							<span class="ui-btn__content"><?php echo esc_html( $secondary_action['title'] ); ?></span>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
