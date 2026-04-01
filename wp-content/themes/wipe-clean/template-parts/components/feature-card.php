<?php
/**
 * Feature card component.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$item        = isset( $args['item'] ) && is_array( $args['item'] ) ? $args['item'] : array();
$class_names = ! empty( $item['class_name'] ) ? preg_split( '/\s+/', (string) $item['class_name'] ) : array();
$class_names = array_filter( array_map( 'sanitize_html_class', is_array( $class_names ) ? $class_names : array() ) );
$class       = ! empty( $class_names ) ? ' ' . implode( ' ', $class_names ) : '';
$title = (string) ( $item['title'] ?? '' );
$text  = (string) ( $item['text'] ?? '' );
$icon  = $item['icon'] ?? array();
?>
<article class="feature-card<?php echo esc_attr( $class ); ?>">
	<div class="feature-card__head">
		<div class="feature-card__icon">
			<span class="feature-card__icon-box">
				<?php echo wipe_clean_render_media( $icon ); ?>
			</span>
		</div>
		<h3 class="feature-card__title"><?php echo esc_html( $title ); ?></h3>
	</div>
	<p class="feature-card__text"><?php echo esc_html( $text ); ?></p>
</article>
