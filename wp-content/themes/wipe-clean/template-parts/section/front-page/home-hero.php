<?php
/**
 * Front-page home hero section.
 *
 * @package wipe-clean
 */

$section = $args['section'] ?? wipe_clean_get_front_page_section_defaults( 'home_hero' );

$area_options      = ! empty( $section['area_options'] ) ? $section['area_options'] : array();
$service_options   = ! empty( $section['service_options'] ) ? $section['service_options'] : array();
$frequency_options = ! empty( $section['frequency_options'] ) ? $section['frequency_options'] : array();
$benefits          = ! empty( $section['benefits'] ) ? $section['benefits'] : array();
?>
<section class="home-hero">
	<div class="_container">
		<div class="home-hero__wrapper">
			<div class="home-hero__top">
				<div class="home-hero__intro ui-section-head">
					<?php if ( ! empty( $section['kicker'] ) ) : ?>
						<span class="ui-kicker"><?php echo esc_html( $section['kicker'] ); ?></span>
					<?php endif; ?>

					<?php if ( ! empty( $section['title'] ) ) : ?>
						<h1 class="ui-title ui-title--hero"><?php echo nl2br( esc_html( $section['title'] ) ); ?></h1>
					<?php endif; ?>

					<?php if ( ! empty( $section['text'] ) ) : ?>
						<p class="ui-text home-hero__text"><?php echo nl2br( esc_html( $section['text'] ) ); ?></p>
					<?php endif; ?>

					<div class="home-hero__calculator">
						<?php echo wipe_clean_render_managed_cf7_form( 'home_hero_calculator' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>

				<div class="home-hero__visual" aria-hidden="true">
					<div class="home-hero__visual-tools">
						<?php echo wipe_clean_render_media( $section['tools_image'] ?? array(), array( 'loading' => 'eager' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div class="home-hero__visual-shadow"></div>
					<div class="home-hero__visual-room">
						<?php echo wipe_clean_render_media( $section['room_image'] ?? array(), array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div class="home-hero__visual-cleaner">
						<?php echo wipe_clean_render_media( $section['cleaner_image'] ?? array(), array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
			</div>

			<?php if ( $benefits ) : ?>
				<div class="home-hero__benefits">
					<?php foreach ( $benefits as $benefit ) : ?>
						<article class="ui-card ui-card--compact home-hero__benefit">
							<div class="ui-icon-badge home-hero__benefit-icon">
								<div class="ui-icon-badge__inner">
									<?php echo wipe_clean_render_media( $benefit['icon'] ?? array(), array( 'loading' => 'lazy', 'aria-hidden' => 'true' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
							</div>
							<p class="home-hero__benefit-title"><?php echo esc_html( $benefit['title'] ?? '' ); ?></p>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
