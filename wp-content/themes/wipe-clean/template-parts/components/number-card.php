<?php
/**
 * Number card component.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$item        = isset( $args['item'] ) && is_array( $args['item'] ) ? $args['item'] : array();
$class_names = ! empty( $item['class_name'] ) ? preg_split( '/\s+/', (string) $item['class_name'] ) : array();
$class_names = array_filter( array_map( 'sanitize_html_class', (array) $class_names ) );
$class       = ! empty( $class_names ) ? ' ' . implode( ' ', $class_names ) : '';
?>
<article class="number-card ui-card<?php echo esc_attr( $class ); ?>">
	<span class="number-card__number ui-title--number"><?php echo esc_html( $item['number'] ?? '' ); ?></span>
	<div class="number-card__body">
		<h3 class="number-card__title"><?php echo esc_html( $item['title'] ?? '' ); ?></h3>
		<p class="number-card__text ui-text"><?php echo esc_html( $item['text'] ?? '' ); ?></p>
	</div>
</article>
