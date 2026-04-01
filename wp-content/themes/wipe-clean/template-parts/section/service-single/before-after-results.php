<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section     = isset( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : array();
$items       = ! empty( $section['items'] ) && is_array( $section['items'] ) ? array_values( $section['items'] ) : array();
$title_lines = wipe_clean_get_service_single_title_lines(
	$section['title'] ?? '',
	array( 'Результаты нашего', 'клининга до и после' )
);
?>
<section class="before-after-results">
	<div class="_container" data-before-after-results data-initial-desktop="<?php echo esc_attr( (int) ( $section['initial_desktop'] ?? 6 ) ); ?>" data-initial-mobile="<?php echo esc_attr( (int) ( $section['initial_mobile'] ?? 3 ) ); ?>" data-step-desktop="<?php echo esc_attr( (int) ( $section['step_desktop'] ?? 3 ) ); ?>" data-step-mobile="<?php echo esc_attr( (int) ( $section['step_mobile'] ?? 3 ) ); ?>">
		<div class="before-after-results__wrapper">
			<div class="before-after-results__head ui-section-head ui-section-head--center">
				<h2 class="before-after-results__title ui-title">
					<?php foreach ( $title_lines as $index => $line ) : ?>
						<?php echo esc_html( $line ); ?>
						<?php if ( $index < count( $title_lines ) - 1 ) : ?>
							<br>
						<?php endif; ?>
					<?php endforeach; ?>
				</h2>
			</div>

			<div class="before-after-results__grid">
				<?php foreach ( $items as $index => $item ) : ?>
					<div class="before-after-results__item" data-before-after-item data-item-index="<?php echo esc_attr( $index ); ?>">
						<?php get_template_part( 'template-parts/components/before-after-card', null, array( 'item' => $item ) ); ?>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="before-after-results__footer" data-before-after-footer>
				<div class="before-after-results__loader" data-before-after-loader aria-hidden="true">
					<img src="<?php echo esc_url( wipe_clean_asset_uri( 'static/images/section/blog-archive/blog-archive-loader.svg' ) ); ?>" alt="" loading="lazy">
				</div>
				<button class="before-after-results__more ui-btn ui-btn--primary" type="button" data-before-after-more>
					<span class="ui-btn__content">
						<span class="before-after-results__more-label before-after-results__more-label--default" data-reveal-ignore><?php echo esc_html( $section['button_label'] ?? 'Больше' ); ?></span>
					</span>
				</button>
			</div>
		</div>
	</div>
</section>
