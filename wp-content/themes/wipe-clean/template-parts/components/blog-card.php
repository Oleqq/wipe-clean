<?php
/**
 * Blog card component.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$card       = isset( $args['card'] ) && is_array( $args['card'] ) ? $args['card'] : array();
$title      = trim( (string) ( $card['title'] ?? '' ) );
$excerpt    = trim( (string) ( $card['excerpt'] ?? '' ) );
$href       = ! empty( $card['href'] ) ? wipe_clean_resolve_static_url( (string) $card['href'] ) : '#';
$date_label = trim( (string) ( $card['dateLabel'] ?? '' ) );
$date_time  = trim( (string) ( $card['dateTime'] ?? '' ) );
$image      = $card['image'] ?? array();
$image_alt  = trim( (string) ( $card['imageAlt'] ?? $title ) );
$arrow_icon = wipe_clean_asset_uri( 'static/images/section/blog-archive/blog-card-arrow.svg' );
?>
<article class="blog-card">
	<a class="blog-card__inner" href="<?php echo esc_url( $href ); ?>" aria-label="<?php echo esc_attr( $title ); ?>">
		<div class="blog-card__media">
			<?php
			echo wipe_clean_render_media( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$image,
				array(
					'alt' => $image_alt,
				)
			);
			?>
		</div>
		<div class="blog-card__body">
			<div class="blog-card__content">
				<h3 class="blog-card__title"><?php echo esc_html( $title ); ?></h3>
				<?php if ( '' !== $excerpt ) : ?>
					<p class="blog-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
				<?php endif; ?>
			</div>
			<div class="blog-card__meta">
				<?php if ( '' !== $date_label ) : ?>
					<time class="blog-card__date" datetime="<?php echo esc_attr( $date_time ); ?>"><?php echo esc_html( $date_label ); ?></time>
				<?php endif; ?>
				<span class="blog-card__link">
					<span class="blog-card__link-label"><?php esc_html_e( 'Читать', 'wipe-clean' ); ?></span>
					<span class="blog-card__link-icon">
						<span class="blog-card__link-icon-inner">
							<img src="<?php echo esc_url( $arrow_icon ); ?>" alt="" aria-hidden="true" loading="lazy">
						</span>
					</span>
				</span>
			</div>
		</div>
	</a>
</article>
