<?php
/**
 * Карточка услуги.
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

$href   = ! empty( $card['href'] ) ? wipe_clean_resolve_static_url( (string) $card['href'] ) : ( ! empty( $card['url'] ) ? wipe_clean_resolve_static_url( (string) $card['url'] ) : '#' );
$image  = $card['image'] ?? array();
$layers = isset( $card['layers'] ) && is_array( $card['layers'] ) ? array_values( $card['layers'] ) : array();

if ( empty( $layers ) && ! empty( $image ) ) {
	$layers = array(
		array(
			'media'    => $image,
			'modifier' => 'fill',
		),
	);
}
?>
<a class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" href="<?php echo esc_url( $href ); ?>" aria-label="<?php echo esc_attr( $card['title'] ?? '' ); ?>">
	<span class="service-card__media" aria-hidden="true">
		<?php foreach ( $layers as $index => $layer ) : ?>
			<?php
			$modifier = ! empty( $layer['modifier'] ) ? ' service-card__layer--' . sanitize_html_class( (string) $layer['modifier'] ) : '';
			$media    = $layer['media'] ?? $layer['image'] ?? $layer['src'] ?? array();
			echo wipe_clean_render_media( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$media,
				array(
					'class' => 'service-card__layer' . $modifier,
					'alt'   => '',
					'style' => '--service-layer-order:' . ( $index + 1 ),
				)
			);
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
