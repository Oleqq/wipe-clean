<?php
/**
 * Front-page contacts section.
 *
 * @package wipe-clean
 */

$section      = $args['section'] ?? wipe_clean_get_front_page_section_defaults( 'contacts' );
$social_links = ! empty( $section['social_links'] ) ? $section['social_links'] : array();
$phone_href   = 'tel:' . preg_replace( '/[^+\d]/', '', (string) ( $section['phone_value'] ?? '' ) );
$email_href   = 'mailto:' . sanitize_email( strtolower( (string) ( $section['email_value'] ?? '' ) ) );
$raw_prefix   = (string) ( $section['id_prefix'] ?? $section['idPrefix'] ?? 'contacts' );
$section_id   = sanitize_title( $raw_prefix );
$field_prefix = sanitize_html_class( $raw_prefix );
$form_key     = ! empty( $section['managed_form_key'] )
	? sanitize_key( (string) $section['managed_form_key'] )
	: ( 'promotions-contacts' === $raw_prefix ? 'promotions_contacts' : 'front_contacts' );
?>
<section class="contacts"<?php echo '' !== $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="_container">
		<div class="contacts__wrapper">
			<div class="contacts__head">
				<h2 class="ui-title contacts__title"><?php echo esc_html( $section['title'] ?? '' ); ?></h2>
				<?php if ( ! empty( $section['text'] ) ) : ?>
					<p class="ui-text contacts__text"><?php echo esc_html( $section['text'] ); ?></p>
				<?php endif; ?>
			</div>

			<div class="contacts__content">
				<div class="contacts__aside">
					<div class="contacts__cards">
						<div class="contacts__cards-row">
							<div class="contact-card">
								<p class="contact-card__label"><?php echo esc_html( $section['phone_label'] ?? '' ); ?></p>
								<a class="contact-card__value contact-card__value--link" href="<?php echo esc_url( $phone_href ); ?>">
									<?php echo esc_html( $section['phone_value'] ?? '' ); ?>
								</a>
							</div>

							<div class="contact-card contact-card--socials">
								<p class="contact-card__label"><?php echo esc_html( $section['socials_label'] ?? '' ); ?></p>
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

						<div class="contact-card">
							<p class="contact-card__label"><?php echo esc_html( $section['email_label'] ?? '' ); ?></p>
							<a class="contact-card__value contact-card__value--link" href="<?php echo esc_url( $email_href ); ?>">
								<?php echo esc_html( $section['email_value'] ?? '' ); ?>
							</a>
						</div>
					</div>
				</div>

				<div class="contacts__form-block">
					<h3 class="contacts__form-title"><?php echo esc_html( $section['form_title'] ?? '' ); ?></h3>
					<?php echo wipe_clean_render_managed_cf7_form( $form_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</div>
	</div>
</section>
