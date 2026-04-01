<?php
/**
 * Front-page FAQ section.
 *
 * @package wipe-clean
 */

$section           = $args['section'] ?? wipe_clean_get_front_page_section_defaults( 'faq' );
$items             = ! empty( $section['items'] ) ? array_values( $section['items'] ) : array();
$mobile_items      = ! empty( $section['mobile_items'] ) ? array_values( $section['mobile_items'] ) : $items;
$initial_open      = isset( $section['initial_open_index'] ) ? (int) $section['initial_open_index'] : -1;
$mobile_open       = isset( $section['mobile_initial_open_index'] ) ? (int) $section['mobile_initial_open_index'] : $initial_open;
$desktop_columns   = array_chunk( $items, (int) ceil( max( count( $items ), 1 ) / 2 ) );
$icon_uri          = wipe_clean_asset_uri( 'static/images/ui/corner-right-down.svg' );
$faq_heading_class = ! empty( $section['title'] ) ? 'faq__head' : 'faq__head faq__head--hidden';
$format_answer     = static function ( $answer ) {
	return function_exists( 'wipe_clean_format_rich_text' )
		? wipe_clean_format_rich_text( (string) $answer )
		: wpautop( esc_html( (string) $answer ) );
};
$primary_action_raw = $section['primary_action'] ?? array();

if ( empty( $primary_action_raw ) && ! empty( $section['action'] ) && is_array( $section['action'] ) ) {
	$primary_action_raw = array(
		'url'    => (string) ( $section['action']['href'] ?? '' ),
		'title'  => (string) ( $section['action']['label'] ?? '' ),
		'target' => (string) ( $section['action']['target'] ?? '' ),
	);
}

$primary_action    = wipe_clean_resolve_link( $primary_action_raw );
$modifier          = sanitize_html_class( (string) ( $section['modifier'] ?? '' ) );
$section_classes   = array( 'faq' );

if ( '' !== $modifier ) {
	$section_classes[] = 'faq--' . $modifier;
}

if ( ! empty( $section['class_name'] ) ) {
	foreach ( preg_split( '/\s+/', (string) $section['class_name'] ) as $class_name ) {
		$class_name = sanitize_html_class( $class_name );

		if ( '' !== $class_name ) {
			$section_classes[] = $class_name;
		}
	}
}

$render_faq_item = static function ( $item, $index, $prefix, $is_open ) use ( $icon_uri, $format_answer ) {
	$question_id = $prefix . '-question-' . $index;
	$answer_id   = $prefix . '-answer-' . $index;
	?>
	<div class="faq__item<?php echo $is_open ? ' is-open' : ''; ?>" data-faq-item>
		<button class="faq__toggle" type="button" id="<?php echo esc_attr( $question_id ); ?>" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $answer_id ); ?>" data-faq-toggle>
			<span class="faq__question"><?php echo esc_html( $item['question'] ?? '' ); ?></span>
			<span class="faq__icon" aria-hidden="true">
				<img src="<?php echo esc_url( $icon_uri ); ?>" alt="">
			</span>
		</button>
		<div class="faq__answer"<?php echo $is_open ? '' : ' hidden'; ?> id="<?php echo esc_attr( $answer_id ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $question_id ); ?>" aria-hidden="<?php echo $is_open ? 'false' : 'true'; ?>" data-faq-answer>
			<div class="faq__answer-inner">
				<div class="faq__answer-text"><?php echo $format_answer( $item['answer'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			</div>
		</div>
	</div>
	<?php
};
?>
<section class="<?php echo esc_attr( implode( ' ', array_unique( $section_classes ) ) ); ?>">
	<div class="_container">
		<div class="faq__wrapper">
			<div class="<?php echo esc_attr( $faq_heading_class ); ?>">
				<?php if ( ! empty( $section['title'] ) ) : ?>
					<h2 class="ui-title faq__title"><?php echo esc_html( $section['title'] ); ?></h2>
				<?php endif; ?>
			</div>

			<div class="faq__columns faq__columns--desktop">
				<?php foreach ( $desktop_columns as $column_index => $column_items ) : ?>
					<div class="faq__column" data-faq-group>
						<?php foreach ( $column_items as $item_index => $item ) : ?>
							<?php
							$global_index = ( 0 === $column_index )
								? $item_index
								: $item_index + count( $desktop_columns[0] ?? array() );
							$render_faq_item( $item, $global_index, 'faq-desktop', $global_index === $initial_open );
							?>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="faq__mobile" data-faq-group>
				<?php foreach ( $mobile_items as $index => $item ) : ?>
					<?php $render_faq_item( $item, $index, 'faq-mobile', $index === $mobile_open ); ?>
				<?php endforeach; ?>
			</div>

			<?php if ( ! empty( $primary_action['url'] ) ) : ?>
				<a class="ui-btn ui-btn--primary faq__action" href="<?php echo esc_url( $primary_action['url'] ); ?>"<?php echo ! empty( $primary_action['target'] ) ? ' target="' . esc_attr( $primary_action['target'] ) . '"' : ''; ?>>
					<span class="ui-btn__content"><?php echo esc_html( $primary_action['title'] ); ?></span>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
