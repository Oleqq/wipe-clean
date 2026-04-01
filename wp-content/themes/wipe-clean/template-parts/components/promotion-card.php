<?php
/**
 * Promotion card component.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$item       = isset( $args['item'] ) && is_array( $args['item'] ) ? $args['item'] : array();
$title      = trim( (string) ( $item['title'] ?? '' ) );
$popup_id   = trim( (string) ( $item['popupId'] ?? '' ) );
$href       = ! empty( $popup_id ) ? '#' : wipe_clean_resolve_static_url( (string) ( $item['href'] ?? '#' ) );
$image      = $item['image'] ?? array();
$image_alt  = trim( (string) ( $item['imageAlt'] ?? $title ) );
$link_label = trim( (string) ( $item['linkLabel'] ?? 'Подробнее' ) );
$arrow_icon = wipe_clean_asset_uri( 'static/images/section/blog-archive/blog-card-arrow.svg' );
?>
<article class="promotion-card">
	<a class="promotion-card__link" href="<?php echo esc_url( $href ); ?>" aria-label="<?php echo esc_attr( $title ); ?>"<?php echo '' !== $popup_id ? ' data-popup-open="' . esc_attr( $popup_id ) . '"' : ''; ?>>
		<div class="promotion-card__media">
			<?php
			echo wipe_clean_render_media( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$image,
				array(
					'alt' => $image_alt,
				)
			);
			?>
		</div>
		<div class="promotion-card__body">
			<h3 class="promotion-card__title"><?php echo esc_html( $title ); ?></h3>
			<div class="promotion-card__meta">
				<span class="promotion-card__label"><?php echo esc_html( $link_label ); ?></span>
				<span class="promotion-card__icon">
					<span class="promotion-card__icon-inner">
						<img src="<?php echo esc_url( $arrow_icon ); ?>" alt="" aria-hidden="true" loading="lazy">
					</span>
				</span>
			</div>
		</div>
	</a>
</article>
