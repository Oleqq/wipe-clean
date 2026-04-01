<?php
/**
 * 404 page section.
 *
 * @package wipe-clean
 */

$section          = isset( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : wipe_clean_get_error_page_default_data();
$primary_action   = wipe_clean_resolve_link( $section['primary_action'] ?? array() );
$secondary_action = wipe_clean_resolve_link( $section['secondary_action'] ?? array() );
?>
<section class="error-404">
	<div class="_container">
		<div class="error-404__wrapper">
			<div class="error-404__hero">
				<div class="error-404__head ui-section-head">
					<?php if ( ! empty( $section['kicker'] ) ) : ?>
						<span class="ui-kicker error-404__kicker"><?php echo esc_html( $section['kicker'] ); ?></span>
					<?php endif; ?>

					<h1 class="ui-title ui-title--hero error-404__title"><?php echo esc_html( $section['title'] ?? '' ); ?></h1>

					<?php if ( ! empty( $section['text'] ) ) : ?>
						<p class="ui-text error-404__text"><?php echo esc_html( $section['text'] ); ?></p>
					<?php endif; ?>

					<div class="error-404__actions">
						<?php if ( ! empty( $primary_action['url'] ) ) : ?>
							<a class="ui-btn ui-btn--primary error-404__action" href="<?php echo esc_url( $primary_action['url'] ); ?>">
								<span class="ui-btn__content"><?php echo esc_html( $primary_action['title'] ?? '' ); ?></span>
							</a>
						<?php endif; ?>

						<?php if ( ! empty( $secondary_action['url'] ) ) : ?>
							<a class="ui-btn ui-btn--secondary error-404__action" href="<?php echo esc_url( $secondary_action['url'] ); ?>">
								<span class="ui-btn__content"><?php echo esc_html( $secondary_action['title'] ?? '' ); ?></span>
							</a>
						<?php endif; ?>
					</div>
				</div>

				<div class="error-404__visual">
					<?php echo wipe_clean_render_media( $section['visual_image'] ?? array(), array( 'class' => 'error-404__image', 'alt' => '', 'aria-hidden' => 'true' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>

			<?php
			get_template_part(
				'template-parts/components/contact-panel',
				null,
				array(
					'panel'        => $section['contact_panel'] ?? array(),
					'class_name'   => 'error-404__contacts',
					'form_context' => array(
						'form_context_label'   => 'Контактная панель на странице 404',
						'form_context_page'    => '404',
						'form_context_surface' => 'Контактный блок',
					),
				)
			);
			?>
		</div>
	</div>
</section>
