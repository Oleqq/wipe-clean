<?php
/**
 * Contacts page hero section.
 *
 * @package wipe-clean
 */

$section      = $args['section'] ?? wipe_clean_get_contacts_page_section_defaults( 'contacts_hero' );
$social_links = ! empty( $section['social_links'] ) ? array_values( $section['social_links'] ) : array();
$phone_href   = 'tel:' . preg_replace( '/[^+\d]/', '', (string) ( $section['phone_value'] ?? '' ) );
$email_href   = 'mailto:' . sanitize_email( strtolower( (string) ( $section['email_value'] ?? '' ) ) );
$raw_prefix   = (string) ( $section['id_prefix'] ?? 'contacts-page' );
$field_prefix = sanitize_html_class( $raw_prefix );
?>
<section class="contacts-hero">
	<div class="_container">
		<div class="contacts-hero__wrapper">
			<div class="contacts-hero__layout">
				<div class="contacts-hero__head ui-section-head ui-section-head--compact">
					<?php if ( ! empty( $section['kicker'] ) ) : ?>
						<span class="ui-kicker contacts-hero__kicker"><?php echo esc_html( $section['kicker'] ); ?></span>
					<?php endif; ?>
					<h1 class="ui-title ui-title--hero contacts-hero__title"><?php echo esc_html( $section['title'] ?? '' ); ?></h1>
					<?php if ( ! empty( $section['text'] ) ) : ?>
						<p class="ui-text contacts-hero__text"><?php echo esc_html( $section['text'] ); ?></p>
					<?php endif; ?>
				</div>

				<?php echo wipe_clean_render_managed_cf7_form( 'contacts_page_hero' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<div class="contacts-hero__cards">
				<div class="contact-card contacts-hero__card">
					<p class="contact-card__label"><?php echo esc_html( $section['phone_label'] ?? '' ); ?></p>
					<a class="contact-card__value contact-card__value--link" href="<?php echo esc_url( $phone_href ); ?>">
						<?php echo esc_html( $section['phone_value'] ?? '' ); ?>
					</a>
				</div>

				<div class="contact-card contact-card--socials contacts-hero__card">
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

				<div class="contact-card contacts-hero__card">
					<p class="contact-card__label"><?php echo esc_html( $section['email_label'] ?? '' ); ?></p>
					<a class="contact-card__value contact-card__value--link" href="<?php echo esc_url( $email_href ); ?>">
						<?php echo esc_html( $section['email_value'] ?? '' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>
