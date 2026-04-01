<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = isset( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : array();
$actions = array_filter(
	array(
		$section['primary_action'] ?? array(),
		$section['secondary_action'] ?? array(),
	),
	static function ( $action ) {
		return is_array( $action ) && ! empty( $action['title'] );
	}
);
?>
<section class="service-hero">
	<div class="_container">
		<div class="service-hero__wrapper">
			<div class="service-hero__media">
				<?php
				echo wipe_clean_render_media(
					$section['image'] ?? array(),
					array(
						'alt'           => (string) ( $section['title'] ?? '' ),
						'loading'       => 'eager',
						'decoding'      => 'async',
						'fetchpriority' => 'high',
					)
				);
				?>
			</div>
			<div class="service-hero__head ui-section-head">
				<?php if ( ! empty( $section['kicker'] ) ) : ?>
					<span class="ui-kicker service-hero__kicker"><?php echo esc_html( $section['kicker'] ); ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $section['title'] ) ) : ?>
					<h1 class="ui-title ui-title--hero service-hero__title"><?php echo esc_html( $section['title'] ); ?></h1>
				<?php endif; ?>
			</div>
			<div class="service-hero__content">
				<?php if ( ! empty( $section['text'] ) ) : ?>
					<p class="ui-text service-hero__text"><?php echo esc_html( $section['text'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $actions ) ) : ?>
					<div class="service-hero__actions">
						<?php foreach ( $actions as $index => $action ) : ?>
							<?php $link = wipe_clean_resolve_link( $action ); ?>
							<a class="ui-btn service-hero__action <?php echo 0 === $index ? 'ui-btn--primary' : 'ui-btn--secondary'; ?>" href="<?php echo esc_url( $link['url'] ?: '#' ); ?>"<?php echo $link['target'] ? ' target="' . esc_attr( $link['target'] ) . '"' : ''; ?>>
								<span class="ui-btn__content"><?php echo esc_html( $link['title'] ); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
