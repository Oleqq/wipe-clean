<?php
/**
 * Blog single hero section.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section      = isset( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : array();
$title        = trim( (string) ( $section['title'] ?? '' ) );
$title_lines  = preg_split( '/\r\n|\r|\n/', $title ) ?: array();
$excerpt      = trim( (string) ( $section['excerpt'] ?? '' ) );
$excerpt_html = function_exists( 'wipe_clean_format_rich_text' ) ? wipe_clean_format_rich_text( $excerpt ) : wpautop( esc_html( $excerpt ) );
$date_label   = trim( (string) ( $section['date_label'] ?? 'Дата публикации:' ) );
$date_value   = trim( (string) ( $section['date_value'] ?? '' ) );
$image        = $section['image'] ?? array();

if ( empty( $title_lines ) ) {
	$title_lines = array( get_the_title() );
}
?>
<section class="blog-article-hero">
	<div class="_container">
		<div class="blog-article-hero__wrapper">
			<div class="blog-article-hero__content">
				<div class="blog-article-hero__head">
					<h1 class="blog-article-hero__title">
						<?php foreach ( $title_lines as $index => $line ) : ?>
							<?php echo esc_html( trim( (string) $line ) ); ?>
							<?php if ( $index < count( $title_lines ) - 1 ) : ?>
								<br>
							<?php endif; ?>
						<?php endforeach; ?>
					</h1>
					<?php if ( '' !== $excerpt_html ) : ?>
						<div class="blog-article-hero__excerpt ui-text"><?php echo wp_kses_post( $excerpt_html ); ?></div>
					<?php endif; ?>
				</div>
				<?php if ( '' !== $date_value ) : ?>
					<div class="blog-article-hero__meta">
						<span class="blog-article-hero__meta-label"><?php echo esc_html( $date_label ); ?></span>
						<span class="blog-article-hero__meta-value"><?php echo esc_html( $date_value ); ?></span>
					</div>
				<?php endif; ?>
			</div>
			<div class="blog-article-hero__media">
				<?php
				echo wipe_clean_render_media( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					$image,
					array(
						'loading'       => 'eager',
						'decoding'      => 'async',
						'fetchpriority' => 'high',
						'sizes'         => '(max-width: 650px) 100vw, 46vw',
					)
				);
				?>
			</div>
		</div>
	</div>
</section>
