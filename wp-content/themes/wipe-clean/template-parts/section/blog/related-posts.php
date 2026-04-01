<?php
/**
 * Related posts section.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section          = isset( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : array();
$title            = trim( (string) ( $section['title'] ?? '' ) );
$items            = isset( $section['items'] ) && is_array( $section['items'] ) ? array_values( $section['items'] ) : array();
$mobile_limit     = max( 1, (int) ( $section['mobile_limit'] ?? 3 ) );
$primary_action   = wipe_clean_resolve_link( $section['primary_action'] ?? array() );
$secondary_action = wipe_clean_resolve_link( $section['secondary_action'] ?? array() );
?>
<section class="related-posts">
	<div class="_container">
		<div class="related-posts__wrapper">
			<?php if ( '' !== $title ) : ?>
				<div class="related-posts__head">
					<h2 class="related-posts__title"><?php echo esc_html( $title ); ?></h2>
				</div>
			<?php endif; ?>

			<div class="related-posts__grid">
				<?php foreach ( $items as $index => $item ) : ?>
					<?php $item_class = $index + 1 > $mobile_limit ? ' related-posts__item--mobile-hidden' : ''; ?>
					<div class="related-posts__item<?php echo esc_attr( $item_class ); ?>">
						<?php get_template_part( 'template-parts/components/blog-card', null, array( 'card' => $item ) ); ?>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ( ! empty( $primary_action['url'] ) || ! empty( $secondary_action['url'] ) ) : ?>
				<div class="related-posts__actions">
					<?php if ( ! empty( $primary_action['url'] ) ) : ?>
						<a class="ui-btn ui-btn--primary" href="<?php echo esc_url( $primary_action['url'] ); ?>"<?php echo ! empty( $primary_action['target'] ) ? ' target="' . esc_attr( $primary_action['target'] ) . '"' : ''; ?>>
							<span class="ui-btn__content"><?php echo esc_html( $primary_action['title'] ?: 'Наши статьи' ); ?></span>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $secondary_action['url'] ) ) : ?>
						<a class="ui-btn ui-btn--secondary" href="<?php echo esc_url( $secondary_action['url'] ); ?>"<?php echo ! empty( $secondary_action['target'] ) ? ' target="' . esc_attr( $secondary_action['target'] ) . '"' : ''; ?>>
							<span class="ui-btn__content"><?php echo esc_html( $secondary_action['title'] ?: 'Наши услуги' ); ?></span>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
