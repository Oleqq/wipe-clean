<?php
/**
 * Карточка услуги для архива услуг и слайдеров.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$card      = isset( $args['card'] ) && is_array( $args['card'] ) ? $args['card'] : array();
$href      = ! empty( $card['url'] ) ? (string) $card['url'] : ( ! empty( $card['href'] ) ? (string) $card['href'] : '#' );
$class     = ! empty( $card['class_name'] ) ? ' ' . sanitize_html_class( $card['class_name'] ) : '';
$title     = (string) ( $card['title'] ?? '' );
$text      = (string) ( $card['text'] ?? '' );
$cta_label = (string) ( $card['link_label'] ?? 'Подробнее' );
$image     = $card['image'] ?? array();
$images    = isset( $card['images'] ) && is_array( $card['images'] ) ? array_values( $card['images'] ) : array();

if ( empty( $images ) && ! empty( $image ) ) {
	$images = array(
		array(
			'media'     => $image,
			'className' => 'service-teaser-card__image--main',
		),
	);
}
?>
<a class="service-teaser-card<?php echo esc_attr( $class ); ?>" href="<?php echo esc_url( $href ); ?>" aria-label="<?php echo esc_attr( $title ); ?>">
	<span class="service-teaser-card__media" aria-hidden="true">
		<?php foreach ( $images as $image_item ) : ?>
			<?php
			$image_class = 'service-teaser-card__image';
			if ( ! empty( $image_item['className'] ) ) {
				$image_class .= ' ' . sanitize_html_class( (string) $image_item['className'] );
			}

			echo wipe_clean_render_media( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$image_item['media'] ?? $image_item['image'] ?? $image_item['src'] ?? array(),
				array(
					'class' => $image_class,
					'alt'   => '',
				)
			);
			?>
		<?php endforeach; ?>
	</span>
	<span class="service-teaser-card__body">
		<span class="service-teaser-card__title"><?php echo esc_html( $title ); ?></span>
		<?php if ( '' !== $text ) : ?>
			<span class="service-teaser-card__text"><?php echo esc_html( $text ); ?></span>
		<?php endif; ?>
	</span>
	<span class="service-teaser-card__footer">
		<span class="service-teaser-card__link"><?php echo esc_html( $cta_label ); ?></span>
		<span class="service-teaser-card__arrow ui-btn ui-btn--primary ui-btn--icon" aria-hidden="true">
			<span class="ui-btn__content">
				<svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M21 9.33398L25.6667 14.0007L21 18.6673" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					<path d="M2.33398 14H25.6673" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</span>
		</span>
	</span>
</a>
