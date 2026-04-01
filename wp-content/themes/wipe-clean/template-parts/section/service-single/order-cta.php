<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = isset( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : array();
$actions = array_filter(
	array(
		$section['primary_action'] ?? array(),
		$section['secondary_action'] ?? array(),
	),
	static function ( $action ) {
		return is_array( $action ) && ! empty( $action['title'] );
	}
);
$section_class = trim( (string) ( $section['section_class'] ?? 'order-cta--service' ) );
$title_lines   = wipe_clean_get_service_single_title_lines(
	$section['title'] ?? '',
	array( 'Закажите уборку', 'жилья прямо сейчас' )
);
?>
<section class="<?php echo esc_attr( trim( 'order-cta ' . $section_class ) ); ?>">
	<div class="_container">
		<div class="order-cta__wrapper">
			<div class="order-cta__content">
				<div class="order-cta__head ui-section-head ui-section-head--compact">
					<h2 class="order-cta__title ui-title">
						<span class="order-cta__title-desktop">
							<?php foreach ( $title_lines as $index => $line ) : ?>
								<?php echo esc_html( $line ); ?>
								<?php if ( $index < count( $title_lines ) - 1 ) : ?>
									<br>
								<?php endif; ?>
							<?php endforeach; ?>
						</span>
						<span class="order-cta__title-mobile"><?php echo esc_html( implode( ' ', $title_lines ) ); ?></span>
					</h2>
					<?php if ( ! empty( $section['text'] ) ) : ?>
						<p class="order-cta__text ui-text"><?php echo esc_html( $section['text'] ); ?></p>
					<?php endif; ?>
				</div>
				<?php if ( ! empty( $actions ) ) : ?>
					<div class="order-cta__actions">
						<?php foreach ( $actions as $index => $action ) : ?>
							<?php $link = wipe_clean_resolve_link( $action ); ?>
							<a class="ui-btn order-cta__action <?php echo 0 === $index ? 'ui-btn--primary' : 'ui-btn--secondary'; ?>" href="<?php echo esc_url( $link['url'] ?: '#' ); ?>"<?php echo $link['target'] ? ' target="' . esc_attr( $link['target'] ) . '"' : ''; ?>>
								<span class="ui-btn__content"><?php echo esc_html( $link['title'] ); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="order-cta__visual" aria-hidden="true">
				<div class="order-cta__visual-media">
					<?php echo wipe_clean_render_media( $section['image'] ?? array(), array( 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</div>
	</div>
</section>
