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
?>
<section class="contacts">
	<div class="_container">
		<div class="contacts__wrapper">
			<div class="contacts__head">
				<h2 class="ui-title contacts__title"><?php echo esc_html( $section['title'] ?? '' ); ?></h2>
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
					<form class="contacts__form ui-form" action="#" method="post" novalidate>
						<div class="contacts__fields">
							<div class="ui-field contacts__field">
								<label class="ui-field__label" for="home-contacts-name"><?php echo esc_html( $section['form_name_label'] ?? '' ); ?></label>
								<input class="ui-field__control" id="home-contacts-name" type="text" name="name" placeholder="<?php echo esc_attr( $section['form_name_placeholder'] ?? '' ); ?>" autocomplete="name">
							</div>

							<div class="ui-field contacts__field">
								<label class="ui-field__label" for="home-contacts-phone"><?php echo esc_html( $section['form_phone_label'] ?? '' ); ?></label>
								<input class="ui-field__control" id="home-contacts-phone" type="tel" name="phone" placeholder="<?php echo esc_attr( $section['form_phone_placeholder'] ?? '' ); ?>" autocomplete="tel-national" inputmode="tel">
							</div>
						</div>

						<div class="contacts__form-actions">
							<label class="ui-checkbox contacts__checkbox" for="home-contacts-agreement">
								<input class="ui-checkbox__input" id="home-contacts-agreement" type="checkbox" name="agreement">
								<span class="ui-checkbox__label"><?php echo esc_html( $section['agreement_text'] ?? '' ); ?></span>
							</label>

							<button class="ui-btn ui-btn--primary contacts__submit" type="submit">
								<span class="ui-btn__content">
									<span class="contacts__submit-text contacts__submit-text--desktop"><?php echo esc_html( $section['submit_text'] ?? '' ); ?></span>
									<span class="contacts__submit-text contacts__submit-text--mobile"><?php echo esc_html( $section['submit_text_mobile'] ?? '' ); ?></span>
								</span>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
