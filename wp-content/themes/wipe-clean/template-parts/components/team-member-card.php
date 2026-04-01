<?php
/**
 * Team member card component.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$item  = isset( $args['item'] ) && is_array( $args['item'] ) ? $args['item'] : array();
$image = $item['image'] ?? array();
?>
<article class="team-member-card">
	<div class="team-member-card__media">
		<?php if ( ! empty( $image ) ) : ?>
			<?php echo wipe_clean_render_media( $image, array( 'alt' => (string) ( $item['name'] ?? '' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php else : ?>
			<div class="team-member-card__placeholder" aria-hidden="true"></div>
		<?php endif; ?>
	</div>
	<div class="team-member-card__body">
		<h3 class="team-member-card__name"><?php echo esc_html( $item['name'] ?? '' ); ?></h3>
	</div>
</article>
