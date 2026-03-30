<?php
/**
 * Service card component.
 *
 * @package wipe-clean
 */

$card = isset( $args['card'] ) && is_array( $args['card'] ) ? $args['card'] : array();
$size = ! empty( $args['size'] ) ? (string) $args['size'] : 'lg';

$classes = array(
	'service-card',
	'service-card--' . $size,
);

if ( ! empty( $card['className'] ) ) {
	$classes[] = (string) $card['className'];
}

$href = ! empty( $card['href'] ) ? wipe_clean_resolve_static_url( (string) $card['href'] ) : '#';
?>
<a class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" href="<?php echo esc_url( $href ); ?>" aria-label="<?php echo esc_attr( $card['title'] ?? '' ); ?>">
	<span class="service-card__media" aria-hidden="true">
		<?php foreach ( (array) ( $card['layers'] ?? array() ) as $index => $layer ) : ?>
			<?php
			$layer_classes = array( 'service-card__layer' );

			if ( ! empty( $layer['modifier'] ) ) {
				$layer_classes[] = 'service-card__layer--' . sanitize_html_class( (string) $layer['modifier'] );
			}

			$image_html = wipe_clean_render_media(
				$layer['image'] ?? array(),
				array(
					'class' => implode( ' ', $layer_classes ),
					'style' => '--service-layer-order:' . ( (int) $index + 1 ),
					'alt'   => '',
				)
			);

			echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		<?php endforeach; ?>
	</span>
	<span class="service-card__body">
		<span class="service-card__title"><?php echo esc_html( $card['title'] ?? '' ); ?></span>
		<span class="service-card__meta">
			<span class="service-card__label"><?php esc_html_e( 'Цена:', 'wipe-clean' ); ?></span>
			<span class="service-card__price"><?php echo esc_html( $card['price'] ?? '' ); ?></span>
		</span>
	</span>
</a>
