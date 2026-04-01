<?php
/**
 * Blog archive section.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section           = isset( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : array();
$title             = trim( (string) ( $section['title'] ?? '' ) );
$title_lines       = preg_split( '/\r\n|\r|\n/', $title ) ?: array();
$text_top          = trim( (string) ( $section['text_top'] ?? '' ) );
$text_bottom       = trim( (string) ( $section['text_bottom'] ?? '' ) );
$text_top_html     = function_exists( 'wipe_clean_format_rich_text' ) ? wipe_clean_format_rich_text( $text_top ) : wpautop( esc_html( $text_top ) );
$text_bottom_html  = function_exists( 'wipe_clean_format_rich_text' ) ? wipe_clean_format_rich_text( $text_bottom ) : wpautop( esc_html( $text_bottom ) );
$hero_image        = $section['hero_image'] ?? array();
$button_label      = trim( (string) ( $section['button_label'] ?? 'Показать ещё' ) );
$loading_label     = trim( (string) ( $section['button_loading_label'] ?? 'Загрузка...' ) );
$initial_desktop   = max( 1, (int) ( $section['initial_desktop'] ?? 10 ) );
$initial_mobile    = max( 1, (int) ( $section['initial_mobile'] ?? 6 ) );
$step_desktop      = max( 1, (int) ( $section['step_desktop'] ?? 4 ) );
$step_mobile       = max( 1, (int) ( $section['step_mobile'] ?? 3 ) );
$items             = isset( $section['items'] ) && is_array( $section['items'] ) ? array_values( $section['items'] ) : array();
$loader_icon       = wipe_clean_asset_uri( 'static/images/section/blog-archive/blog-archive-loader.svg' );

if ( empty( $title_lines ) ) {
	$title_lines = array( 'Статьи и новости в сфере уборки' );
}
?>
<section class="blog-archive" data-blog-archive data-initial-desktop="<?php echo esc_attr( (string) $initial_desktop ); ?>" data-initial-mobile="<?php echo esc_attr( (string) $initial_mobile ); ?>" data-step-desktop="<?php echo esc_attr( (string) $step_desktop ); ?>" data-step-mobile="<?php echo esc_attr( (string) $step_mobile ); ?>">
	<div class="_container">
		<div class="blog-archive__wrapper">
			<div class="blog-archive__hero">
				<div class="blog-archive__content">
					<div class="blog-archive__head">
						<h1 class="blog-archive__title ui-title">
							<?php foreach ( $title_lines as $index => $line ) : ?>
								<?php echo esc_html( trim( (string) $line ) ); ?>
								<?php if ( $index < count( $title_lines ) - 1 ) : ?>
									<br>
								<?php endif; ?>
							<?php endforeach; ?>
						</h1>
					</div>
					<div class="blog-archive__copy">
						<?php if ( '' !== $text_top_html ) : ?>
							<div class="blog-archive__text ui-text"><?php echo wp_kses_post( $text_top_html ); ?></div>
						<?php endif; ?>
						<?php if ( '' !== $text_bottom_html ) : ?>
							<div class="blog-archive__text ui-text"><?php echo wp_kses_post( $text_bottom_html ); ?></div>
						<?php endif; ?>
					</div>
				</div>
				<div class="blog-archive__hero-media">
					<?php
					echo wipe_clean_render_media( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						$hero_image,
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

			<div class="blog-archive__grid">
				<?php foreach ( $items as $index => $item ) : ?>
					<div class="blog-archive__item" data-blog-item data-item-index="<?php echo esc_attr( (string) $index ); ?>">
						<?php get_template_part( 'template-parts/components/blog-card', null, array( 'card' => $item ) ); ?>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="blog-archive__footer" data-blog-footer>
				<div class="blog-archive__loader" data-blog-loader aria-hidden="true">
					<img src="<?php echo esc_url( $loader_icon ); ?>" alt="" loading="lazy">
				</div>
				<button class="blog-archive__more ui-btn ui-btn--primary" type="button" data-blog-more>
					<span class="ui-btn__content">
						<span class="blog-archive__more-label blog-archive__more-label--default"><?php echo esc_html( $button_label ); ?></span>
						<span class="blog-archive__more-label blog-archive__more-label--loading"><?php echo esc_html( $loading_label ); ?></span>
					</span>
				</button>
			</div>
		</div>
	</div>
</section>
