<?php
/**
 * Checklist item component.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$item        = isset( $args['item'] ) && is_array( $args['item'] ) ? $args['item'] : array();
$icon        = $item['icon'] ?? wipe_clean_theme_image( 'static/images/ui/check-badge-icon.svg' );
$text        = (string) ( $item['text'] ?? '' );
$mobile_text = (string) ( $item['mobile_text'] ?? '' );
?>
<li class="ui-check-item">
	<span class="ui-check-item__icon" aria-hidden="true">
		<span class="ui-check-item__icon-inner">
			<?php echo wipe_clean_render_media( $icon ); ?>
		</span>
	</span>
	<span class="ui-check-item__text">
		<?php if ( '' !== $mobile_text ) : ?>
			<span class="ui-check-item__text-desktop"><?php echo esc_html( $text ); ?></span>
			<span class="ui-check-item__text-mobile"><?php echo esc_html( $mobile_text ); ?></span>
		<?php else : ?>
			<?php echo esc_html( $text ); ?>
		<?php endif; ?>
	</span>
</li>
