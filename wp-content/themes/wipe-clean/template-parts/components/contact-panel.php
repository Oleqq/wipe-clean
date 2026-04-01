<?php
/**
 * Shared contact panel component.
 *
 * @package wipe-clean
 */

$panel        = isset( $args['panel'] ) && is_array( $args['panel'] ) ? $args['panel'] : array();
$social_links = ! empty( $panel['social_links'] ) ? array_values( $panel['social_links'] ) : array();
$class_name   = 'contact-panel';
$raw_prefix   = (string) ( $panel['id_prefix'] ?? 'contact-panel' );
$field_prefix = sanitize_html_class( $raw_prefix );
$phone_href   = 'tel:' . preg_replace( '/[^+\d]/', '', (string) ( $panel['phone_value'] ?? '' ) );
$email_href   = 'mailto:' . sanitize_email( strtolower( (string) ( $panel['email_value'] ?? '' ) ) );
$form_context = isset( $args['form_context'] ) && is_array( $args['form_context'] ) ? $args['form_context'] : array();

if ( ! empty( $args['class_name'] ) ) {
	$class_name .= ' ' . sanitize_html_class( (string) $args['class_name'] );
}
?>
<div class="<?php echo esc_attr( $class_name ); ?>">
	<div class="contact-panel__aside">
		<h2 class="ui-title contact-panel__title"><?php echo esc_html( $panel['title'] ?? '' ); ?></h2>
		<div class="contact-panel__cards">
			<div class="contact-panel__cards-row">
				<div class="contact-card contact-panel__card">
					<p class="contact-card__label"><?php echo esc_html( $panel['phone_label'] ?? '' ); ?></p>
					<a class="contact-card__value contact-card__value--link" href="<?php echo esc_url( $phone_href ); ?>">
						<?php echo esc_html( $panel['phone_value'] ?? '' ); ?>
					</a>
				</div>

				<div class="contact-card contact-card--socials contact-panel__card">
					<p class="contact-card__label"><?php echo esc_html( $panel['socials_label'] ?? '' ); ?></p>
					<div class="contact-card__socials">
						<?php foreach ( $social_links as $social_link ) : ?>
							<?php $link = wipe_clean_resolve_link( $social_link ); ?>
							<a class="contact-card__social-link ui-btn ui-btn--primary" href="<?php echo esc_url( $link['url'] ); ?>" aria-label="<?php echo esc_attr( $social_link['label'] ?? '' ); ?>"<?php echo ! empty( $link['target'] ) ? ' target="' . esc_attr( $link['target'] ) . '"' : ''; ?>>
								<span class="ui-btn__content">
									<?php echo wipe_clean_render_media( $social_link['icon'] ?? array(), array( 'aria-hidden' => 'true' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</span>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<div class="contact-card contact-panel__card">
				<p class="contact-card__label"><?php echo esc_html( $panel['email_label'] ?? '' ); ?></p>
				<a class="contact-card__value contact-card__value--link" href="<?php echo esc_url( $email_href ); ?>">
					<?php echo esc_html( $panel['email_value'] ?? '' ); ?>
				</a>
			</div>
		</div>
	</div>

	<div class="contact-panel__form-block">
		<h3 class="contact-panel__form-title"><?php echo esc_html( $panel['form_title'] ?? '' ); ?></h3>
		<?php
		echo wipe_clean_render_managed_cf7_form(
			'contact_panel',
			array(
				'render_context' => $form_context,
			)
		); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
	</div>
</div>
