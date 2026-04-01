<?php
/**
 * Front-page services preview section.
 *
 * @package wipe-clean
 */

$section         = $args['section'] ?? wipe_clean_get_front_page_section_defaults( 'services_preview' );
$cards_variant   = sanitize_key( (string) ( $section['cards_variant'] ?? '' ) );
$cards           = 'layered' === $cards_variant && function_exists( 'wipe_clean_get_prices_page_service_cards' )
	? wipe_clean_get_prices_page_service_cards()
	: wipe_clean_get_front_page_service_cards();
$featured_cards  = array_values( $cards['featured'] ?? array() );
$secondary_cards = array_values( $cards['secondary'] ?? array() );
$primary_action  = wipe_clean_resolve_link( $section['primary_action'] ?? array() );
$secondary_action = wipe_clean_resolve_link( $section['secondary_action'] ?? array() );
$body_id         = sanitize_title( (string) ( $section['body_id'] ?? $section['bodyId'] ?? 'services-preview-text' ) );
$extra_classes   = trim( (string) ( $section['class_name'] ?? $section['className'] ?? '' ) );
$section_class   = trim( 'services-preview ' . $extra_classes );
?>
<section class="<?php echo esc_attr( $section_class ); ?>">
	<div class="_container">
		<div class="services-preview__wrapper">
			<div class="services-preview__head ui-section-head ui-section-head--center">
				<h2 class="ui-title"><?php echo esc_html( $section['title'] ?? '' ); ?></h2>
				<?php if ( ! empty( $section['summary_text'] ) ) : ?>
					<div class="services-preview__copy" data-read-more>
						<div class="services-preview__summary" data-read-more-summary>
							<p class="ui-text services-preview__summary-text">
								<?php echo esc_html( $section['summary_text'] ); ?>
								<button class="services-preview__more" type="button" data-read-more-toggle aria-expanded="false" aria-controls="<?php echo esc_attr( $body_id ); ?>">Ещё</button>
							</p>
						</div>
						<div class="services-preview__body" id="<?php echo esc_attr( $body_id ); ?>" data-read-more-body>
							<p class="ui-text"><?php echo esc_html( $section['text'] ?? '' ); ?></p>
						</div>
					</div>
				<?php elseif ( ! empty( $section['text'] ) ) : ?>
					<p class="ui-text"><?php echo esc_html( $section['text'] ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( $featured_cards ) : ?>
				<div class="services-preview__grid services-preview__grid--featured">
					<?php foreach ( $featured_cards as $card ) : ?>
						<?php get_template_part( 'template-parts/components/service-card', null, array( 'card' => $card, 'size' => 'lg' ) ); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( $secondary_cards ) : ?>
				<div class="services-preview__grid services-preview__grid--secondary">
					<?php foreach ( $secondary_cards as $card ) : ?>
						<?php get_template_part( 'template-parts/components/service-card', null, array( 'card' => $card, 'size' => 'sm' ) ); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $section['note_text'] ) ) : ?>
				<div class="ui-banner services-preview__note">
					<p class="ui-text services-preview__note-text"><?php echo esc_html( $section['note_text'] ); ?></p>
				</div>
			<?php endif; ?>

			<div class="services-preview__actions">
				<?php if ( ! empty( $primary_action['url'] ) ) : ?>
					<a class="ui-btn ui-btn--primary ui-btn--full" href="<?php echo esc_url( $primary_action['url'] ); ?>"<?php echo ! empty( $primary_action['target'] ) ? ' target="' . esc_attr( $primary_action['target'] ) . '"' : ''; ?>>
						<span class="ui-btn__content"><?php echo esc_html( $primary_action['title'] ); ?></span>
					</a>
				<?php endif; ?>
				<?php if ( ! empty( $secondary_action['url'] ) ) : ?>
					<a class="ui-btn ui-btn--secondary ui-btn--full" href="<?php echo esc_url( $secondary_action['url'] ); ?>"<?php echo ! empty( $secondary_action['target'] ) ? ' target="' . esc_attr( $secondary_action['target'] ) . '"' : ''; ?>>
						<span class="ui-btn__content"><?php echo esc_html( $secondary_action['title'] ); ?></span>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
