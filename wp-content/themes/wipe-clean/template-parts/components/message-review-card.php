<?php
/**
 * Message review card component.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$item     = isset( $args['item'] ) && is_array( $args['item'] ) ? $args['item'] : array();
$image    = $item['image'] ?? array();
$lightbox = trim( (string) ( $item['lightboxUrl'] ?? '' ) );
$alt      = trim( (string) ( $item['alt'] ?? 'Открыть рекомендацию клиента' ) );
$caption  = trim( (string) ( $item['caption'] ?? '' ) );
?>
<a class="message-review-card" href="<?php echo esc_url( $lightbox ? $lightbox : '#' ); ?>" data-fancybox="message-reviews" aria-label="<?php echo esc_attr( $alt ); ?>"<?php echo '' !== $caption ? ' data-caption="' . esc_attr( $caption ) . '"' : ''; ?>>
	<div class="message-review-card__media">
		<?php
		echo wipe_clean_render_media( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$image,
			array(
				'alt'      => $alt,
				'loading'  => 'lazy',
				'decoding' => 'async',
			)
		);
		?>
	</div>
</a>
