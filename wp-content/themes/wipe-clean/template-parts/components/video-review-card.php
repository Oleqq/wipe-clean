<?php
/**
 * Video review card component.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$item         = isset( $args['item'] ) && is_array( $args['item'] ) ? $args['item'] : array();
$video_src    = trim( (string) ( $item['videoSrc'] ?? '' ) );
$poster       = $item['poster'] ?? array();
$alt          = trim( (string) ( $item['alt'] ?? 'Открыть видео отзыв' ) );
$caption      = trim( (string) ( $item['caption'] ?? '' ) );
$video_width  = max( 1, (int) ( $item['videoWidth'] ?? 720 ) );
$video_height = max( 1, (int) ( $item['videoHeight'] ?? 1280 ) );
?>
<a class="video-review-card" href="<?php echo esc_url( $video_src ? $video_src : '#' ); ?>" data-fancybox="video-reviews" data-type="html5video" data-html5video-format="video/mp4" data-width="<?php echo esc_attr( (string) $video_width ); ?>" data-height="<?php echo esc_attr( (string) $video_height ); ?>" aria-label="<?php echo esc_attr( $alt ); ?>"<?php echo '' !== $caption ? ' data-caption="' . esc_attr( $caption ) . '"' : ''; ?>>
	<div class="video-review-card__media">
		<?php
		echo wipe_clean_render_media( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$poster,
			array(
				'alt'      => $alt,
				'loading'  => 'lazy',
				'decoding' => 'async',
				'sizes'    => '(max-width: 650px) 70vw, 23vw',
			)
		);
		?>
		<span class="video-review-card__play" aria-hidden="true">
			<svg viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
				<rect x="1" y="1" width="58" height="58" rx="29" fill="white" fill-opacity="0.16"></rect>
				<path d="M24 18.5C24 17.3127 25.3049 16.595 26.303 17.23L43.3342 28.0708C44.257 28.6581 44.257 30.0019 43.3342 30.5892L26.303 41.43C25.3049 42.065 24 41.3473 24 40.16V18.5Z" fill="#E8BF80"></path>
			</svg>
		</span>
	</div>
</a>
