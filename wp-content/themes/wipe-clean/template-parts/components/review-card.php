<?php
/**
 * Review card component.
 *
 * @package wipe-clean
 */

$item      = isset( $args['item'] ) && is_array( $args['item'] ) ? $args['item'] : array();
$rating    = max( 1, min( 5, (int) ( $item['rating'] ?? 5 ) ) );
$stars_uri = wipe_clean_asset_uri( 'static/images/components/review-card/review-card-stars.svg' );
?>
<article class="review-card">
	<div class="review-card__rating" aria-label="<?php echo esc_attr( sprintf( 'Рейтинг %d из 5', $rating ) ); ?>">
		<img class="review-card__rating-stars" src="<?php echo esc_url( $stars_uri ); ?>" alt="" aria-hidden="true" width="222" height="50" loading="lazy" decoding="async">
	</div>
	<div class="review-card__body">
		<p class="review-card__text"><?php echo esc_html( $item['text'] ?? '' ); ?></p>
		<p class="review-card__author"><?php echo esc_html( $item['author'] ?? '' ); ?></p>
	</div>
</article>
