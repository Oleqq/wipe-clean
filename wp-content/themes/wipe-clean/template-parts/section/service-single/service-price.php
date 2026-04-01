<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = isset( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : array();
$factors = ! empty( $section['factors'] ) && is_array( $section['factors'] ) ? array_values( $section['factors'] ) : array();
$button  = wipe_clean_resolve_link( $section['button'] ?? array() );
?>
<div class="ui-wave-group service-page__price-wave">
	<section class="service-price">
		<div class="_container">
			<div class="service-price__wrapper">
				<div class="service-price__visual" aria-hidden="true">
					<div class="service-price__visual-media">
						<?php echo wipe_clean_render_media( $section['image'] ?? array(), array( 'alt' => '' ) ); ?>
					</div>
				</div>

				<div class="service-price__content">
					<div class="service-price__body">
						<h2 class="service-price__title ui-title">
							<span class="service-price__title-inline">
								<?php if ( ! empty( $section['title_accent'] ) ) : ?>
									<span class="service-price__title-accent"><?php echo esc_html( $section['title_accent'] ); ?></span>
								<?php endif; ?>
								<?php if ( ! empty( $section['title_main'] ) ) : ?>
									<?php echo esc_html( ! empty( $section['title_accent'] ) ? ' ' . $section['title_main'] : $section['title_main'] ); ?>
								<?php endif; ?>
							</span>
							<?php if ( ! empty( $section['title_break'] ) ) : ?>
								<span class="service-price__title-break"><?php echo esc_html( $section['title_break'] ); ?></span>
							<?php endif; ?>
						</h2>

						<?php if ( ! empty( $section['text'] ) ) : ?>
							<p class="service-price__text ui-text"><?php echo esc_html( $section['text'] ); ?></p>
						<?php endif; ?>

						<?php if ( ! empty( $section['lead_text'] ) ) : ?>
							<p class="service-price__lead ui-text"><?php echo esc_html( $section['lead_text'] ); ?></p>
						<?php endif; ?>

						<?php if ( ! empty( $factors ) ) : ?>
							<ul class="service-price__list">
								<?php foreach ( $factors as $item ) : ?>
									<?php get_template_part( 'template-parts/components/check-list-item', null, array( 'item' => $item ) ); ?>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<?php if ( ! empty( $section['note_text'] ) ) : ?>
							<p class="service-price__note ui-text"><?php echo esc_html( $section['note_text'] ); ?></p>
						<?php endif; ?>

						<?php if ( ! empty( $section['accent_text'] ) ) : ?>
							<p class="service-price__accent ui-accent-value"><?php echo esc_html( $section['accent_text'] ); ?></p>
						<?php endif; ?>
					</div>

					<?php if ( ! empty( $button['title'] ) ) : ?>
						<a class="service-price__action ui-btn ui-btn--primary" href="<?php echo esc_url( $button['url'] ?: '#' ); ?>"<?php echo $button['target'] ? ' target="' . esc_attr( $button['target'] ) . '"' : ''; ?>>
							<span class="ui-btn__content"><?php echo esc_html( $button['title'] ); ?></span>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
</div>
