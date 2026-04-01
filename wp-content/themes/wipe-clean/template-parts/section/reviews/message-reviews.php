<?php
/**
 * Reviews archive message/photo section.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section         = isset( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : array();
$title           = trim( (string) ( $section['title'] ?? '' ) );
$button_label    = trim( (string) ( $section['button_label'] ?? 'Больше отзывов' ) );
$initial_desktop = max( 1, (int) ( $section['initial_desktop'] ?? 10 ) );
$initial_mobile  = max( 1, (int) ( $section['initial_mobile'] ?? 6 ) );
$step_desktop    = max( 1, (int) ( $section['step_desktop'] ?? 4 ) );
$step_mobile     = max( 1, (int) ( $section['step_mobile'] ?? 2 ) );
$items           = isset( $section['items'] ) && is_array( $section['items'] ) ? array_values( $section['items'] ) : array();
$loader_icon     = wipe_clean_asset_uri( 'static/images/section/blog-archive/blog-archive-loader.svg' );
?>
<section class="message-reviews" data-message-reviews data-initial-desktop="<?php echo esc_attr( (string) $initial_desktop ); ?>" data-initial-mobile="<?php echo esc_attr( (string) $initial_mobile ); ?>" data-step-desktop="<?php echo esc_attr( (string) $step_desktop ); ?>" data-step-mobile="<?php echo esc_attr( (string) $step_mobile ); ?>" data-columns-desktop="4" data-columns-mobile="2">
	<div class="_container">
		<div class="message-reviews__wrapper">
			<div class="message-reviews__head">
				<h2 class="message-reviews__title ui-title"><?php echo esc_html( $title ); ?></h2>
			</div>

			<div class="message-reviews__grid" data-message-reviews-grid>
				<?php for ( $column = 1; $column <= 4; $column++ ) : ?>
					<div class="message-reviews__column" data-message-reviews-column></div>
				<?php endfor; ?>

				<div class="message-reviews__source" hidden aria-hidden="true">
					<?php foreach ( $items as $index => $item ) : ?>
						<?php
						$item_classes = 'message-reviews__item message-reviews__item--' . esc_attr( (string) ( $item['size'] ?? 'tall' ) );
						$item         = array_merge(
							$item,
							array(
								'lightboxUrl' => wipe_clean_resolve_reviews_media_url( $item['lightboxImage'] ?? array() ),
							)
						);
						?>
						<div class="<?php echo esc_attr( $item_classes ); ?>" data-message-review-item data-item-index="<?php echo esc_attr( (string) $index ); ?>" data-desktop-column="<?php echo esc_attr( (string) ( $item['desktopColumn'] ?? 1 ) ); ?>" data-desktop-order="<?php echo esc_attr( (string) ( $item['desktopOrder'] ?? ( $index + 1 ) ) ); ?>" data-mobile-column="<?php echo esc_attr( (string) ( $item['mobileColumn'] ?? 1 ) ); ?>" data-mobile-order="<?php echo esc_attr( (string) ( $item['mobileOrder'] ?? ( $index + 1 ) ) ); ?>">
							<?php get_template_part( 'template-parts/components/message-review-card', null, array( 'item' => $item ) ); ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="message-reviews__footer" data-message-reviews-footer>
				<div class="message-reviews__loader" data-message-reviews-loader aria-hidden="true">
					<img src="<?php echo esc_url( $loader_icon ); ?>" alt="" loading="lazy">
				</div>
				<button class="message-reviews__more ui-btn ui-btn--primary" type="button" data-message-reviews-more>
					<span class="ui-btn__content"><?php echo esc_html( $button_label ); ?></span>
				</button>
			</div>
		</div>
	</div>
</section>
