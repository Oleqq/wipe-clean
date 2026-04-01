<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = isset( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : array();
$cards   = ! empty( $section['cards'] ) && is_array( $section['cards'] ) ? array_values( $section['cards'] ) : array();
$button  = wipe_clean_resolve_link( $section['button'] ?? array() );
?>
<section class="other-services">
	<div class="_container">
		<div class="other-services__wrapper">
			<div class="other-services__head ui-section-head ui-section-head--center">
				<?php if ( ! empty( $section['title'] ) ) : ?>
					<h2 class="other-services__title ui-title"><?php echo esc_html( $section['title'] ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $section['text'] ) ) : ?>
					<p class="other-services__text ui-text"><?php echo esc_html( $section['text'] ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $cards ) ) : ?>
				<div class="other-services__grid">
					<?php foreach ( $cards as $card ) : ?>
						<?php get_template_part( 'template-parts/components/service-card', null, array( 'card' => $card, 'size' => 'lg' ) ); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $button['title'] ) ) : ?>
				<a class="other-services__action ui-btn ui-btn--primary" href="<?php echo esc_url( $button['url'] ?: '#' ); ?>"<?php echo $button['target'] ? ' target="' . esc_attr( $button['target'] ) . '"' : ''; ?>>
					<span class="ui-btn__content">
						<span class="other-services__action-label other-services__action-label--desktop"><?php echo esc_html( $button['title'] ); ?></span>
						<span class="other-services__action-label other-services__action-label--mobile"><?php echo esc_html( $button['title'] ); ?></span>
					</span>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
