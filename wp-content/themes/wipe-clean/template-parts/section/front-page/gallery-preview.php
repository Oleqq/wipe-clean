<?php
/**
 * Front-page gallery preview section.
 *
 * @package wipe-clean
 */

$section      = $args['section'] ?? wipe_clean_get_front_page_section_defaults( 'gallery_preview' );
$top_items    = ! empty( $section['top_items'] ) ? $section['top_items'] : array();
$bottom_items = ! empty( $section['bottom_items'] ) ? $section['bottom_items'] : array();
$play_icon    = wipe_clean_asset_uri( 'static/images/section/gallery-preview/gallery-play.svg' );

$render_gallery_item = static function ( $item, $clone = false, $row_key = 'row', $index = 0 ) use ( $play_icon ) {
	$item_id  = ! empty( $item['id'] ) ? (string) $item['id'] : sprintf( 'gallery-%1$s-%2$d', sanitize_key( (string) $row_key ), (int) $index + 1 );
	$item_url = '';
	$caption  = $item['caption'] ?? '';
	$type     = $item['type'] ?? 'image';

	if ( 'video' === $type ) {
		$item_url = $item['video_url'] ?? '';
	} elseif ( ! empty( $item['image']['url'] ) ) {
		$item_url = wipe_clean_resolve_static_url( (string) $item['image']['url'] );
	} elseif ( ! empty( $item['image']['path'] ) ) {
		$item_url = wipe_clean_asset_uri( (string) $item['image']['path'] );
	}

	$classes = array( 'gallery-preview__item' );

	if ( 'video' === $type ) {
		$classes[] = 'gallery-preview__item--video';
	}

	if ( $clone ) {
		$classes[] = 'gallery-preview__item--clone';
	}

	$attributes = array(
		'href="' . esc_url( $item_url ) . '"',
		'class="' . esc_attr( implode( ' ', $classes ) ) . '"',
		'data-gallery-item-id="' . esc_attr( $item_id ) . '"',
		'aria-label="' . esc_attr( $item['image']['alt'] ?? '' ) . '"',
	);

	if ( $clone ) {
		$attributes[] = 'data-gallery-clone';
		$attributes[] = 'data-gallery-target="' . esc_attr( $item_id ) . '"';
		$attributes[] = 'aria-hidden="true"';
		$attributes[] = 'tabindex="-1"';
	} else {
		$attributes[] = 'data-fancybox="gallery-preview"';
		$attributes[] = 'data-caption="' . esc_attr( $caption ) . '"';
		if ( 'video' === $type ) {
			$attributes[] = 'data-type="html5video"';
			$attributes[] = 'data-html5video-format="video/mp4"';
			$attributes[] = 'data-width="1280"';
			$attributes[] = 'data-height="720"';
		}
	}

	ob_start();
	?>
	<a <?php echo implode( ' ', $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<span class="gallery-preview__item-media">
			<?php echo wipe_clean_render_media( $item['image'] ?? array() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</span>
		<?php if ( 'video' === $type ) : ?>
			<span class="gallery-preview__item-badge" aria-hidden="true"><img src="<?php echo esc_url( $play_icon ); ?>" alt=""></span>
		<?php endif; ?>
	</a>
	<?php
	return ob_get_clean();
};
?>
<section class="gallery-preview">
	<div class="_container">
		<div class="gallery-preview__wrapper">
			<div class="gallery-preview__head">
				<h2 class="ui-title gallery-preview__title"><?php echo esc_html( $section['title'] ?? '' ); ?></h2>
			</div>

			<div class="gallery-preview__marquees">
				<div class="gallery-preview__row" data-gallery-marquee-row data-direction="-1" data-speed="36">
					<div class="gallery-preview__track" data-gallery-marquee-track>
						<?php
						foreach ( $top_items as $index => $item ) {
							echo $render_gallery_item( $item, false, 'top', $index ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						foreach ( $top_items as $index => $item ) {
							echo $render_gallery_item( $item, true, 'top', $index ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
					</div>
				</div>

				<div class="gallery-preview__row gallery-preview__row--reverse" data-gallery-marquee-row data-direction="1" data-speed="38">
					<div class="gallery-preview__track" data-gallery-marquee-track>
						<?php
						foreach ( $bottom_items as $index => $item ) {
							echo $render_gallery_item( $item, false, 'bottom', $index ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						foreach ( $bottom_items as $index => $item ) {
							echo $render_gallery_item( $item, true, 'bottom', $index ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
