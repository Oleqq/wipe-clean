<?php
/**
 * Before/after comparison card.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$item         = isset( $args['item'] ) && is_array( $args['item'] ) ? $args['item'] : array();
$id           = ! empty( $item['id'] ) ? (string) $item['id'] : 'before-after-card-' . wp_unique_id();
$start        = isset( $item['start'] ) ? (float) $item['start'] : 50;
$mobile_start = isset( $item['mobile_start'] ) ? (float) $item['mobile_start'] : $start;
$label        = ! empty( $item['control_label'] ) ? (string) $item['control_label'] : 'Сравнить состояние до и после уборки';
?>
<div class="before-after-card" data-before-after data-start="<?php echo esc_attr( $start ); ?>" data-start-mobile="<?php echo esc_attr( $mobile_start ); ?>" style="<?php echo esc_attr( '--before-after-position: ' . $start . '%;' ); ?>">
	<div class="before-after-card__media">
		<?php
		echo wipe_clean_render_media(
			$item['before_image'] ?? array(),
			array(
				'class' => 'before-after-card__image before-after-card__image--before',
				'alt'   => (string) ( $item['alt'] ?? '' ),
			)
		);
		echo wipe_clean_render_media(
			$item['after_image'] ?? array(),
			array(
				'class'       => 'before-after-card__image before-after-card__image--after',
				'alt'         => '',
				'aria-hidden' => 'true',
			)
		);
		?>
		<div class="before-after-card__divider" aria-hidden="true">
			<span class="before-after-card__handle" data-before-after-handle>
				<span class="before-after-card__handle-inner">
					<img src="<?php echo esc_url( wipe_clean_asset_uri( 'static/images/ui/before-after-handle-icon.svg' ) ); ?>" alt="" loading="lazy">
				</span>
			</span>
		</div>
		<label class="_visually-hidden" for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label>
		<input id="<?php echo esc_attr( $id ); ?>" class="before-after-card__range" type="range" min="0" max="100" step="0.1" value="<?php echo esc_attr( $start ); ?>" aria-label="<?php echo esc_attr( $label ); ?>" data-before-after-range>
	</div>
</div>
