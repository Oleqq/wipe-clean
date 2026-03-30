<?php
/**
 * Front-page home hero section.
 *
 * @package wipe-clean
 */

$section = $args['section'] ?? wipe_clean_get_front_page_section_defaults( 'home_hero' );

$area_options      = ! empty( $section['area_options'] ) ? $section['area_options'] : array();
$service_options   = ! empty( $section['service_options'] ) ? $section['service_options'] : array();
$frequency_options = ! empty( $section['frequency_options'] ) ? $section['frequency_options'] : array();
$benefits          = ! empty( $section['benefits'] ) ? $section['benefits'] : array();
?>
<section class="home-hero">
	<div class="_container">
		<div class="home-hero__wrapper">
			<div class="home-hero__top">
				<div class="home-hero__intro ui-section-head">
					<?php if ( ! empty( $section['kicker'] ) ) : ?>
						<span class="ui-kicker"><?php echo esc_html( $section['kicker'] ); ?></span>
					<?php endif; ?>

					<?php if ( ! empty( $section['title'] ) ) : ?>
						<h1 class="ui-title ui-title--hero"><?php echo nl2br( esc_html( $section['title'] ) ); ?></h1>
					<?php endif; ?>

					<?php if ( ! empty( $section['text'] ) ) : ?>
						<p class="ui-text home-hero__text"><?php echo nl2br( esc_html( $section['text'] ) ); ?></p>
					<?php endif; ?>

					<div class="home-hero__calculator">
						<form class="home-hero__form ui-form" action="#" method="post" novalidate data-demo-form data-form-success-popup="popup-status-success" data-form-error-popup="popup-status-error">
							<fieldset class="home-hero__area">
								<legend class="home-hero__area-title"><?php echo esc_html( $section['area_title'] ?? '' ); ?></legend>
								<input type="hidden" name="area" value="<?php echo esc_attr( $area_options[0]['value'] ?? '' ); ?>" data-demo-choice-input>
								<div class="home-hero__area-slider swiper" data-hero-area-swiper>
									<div class="ui-choice-group home-hero__area-options swiper-wrapper" data-demo-choice-group>
										<?php foreach ( $area_options as $index => $option ) : ?>
											<?php $is_active = 0 === $index; ?>
											<button class="ui-choice<?php echo $is_active ? ' ui-choice--active' : ''; ?> home-hero__choice swiper-slide<?php echo ! empty( $option['is_meter'] ) ? ' home-hero__choice--meter' : ''; ?>" type="button" aria-pressed="<?php echo $is_active ? 'true' : 'false'; ?>" data-demo-choice data-choice-value="<?php echo esc_attr( $option['value'] ?? '' ); ?>">
												<span class="ui-choice__label"><?php echo wp_kses( (string) ( $option['label'] ?? '' ), wipe_clean_allowed_inline_html() ); ?></span>
											</button>
										<?php endforeach; ?>
									</div>
								</div>
							</fieldset>

							<div class="home-hero__form-grid">
								<div class="ui-field home-hero__field">
									<label class="ui-field__label" for="hero-service"><?php echo esc_html( $section['service_label'] ?? '' ); ?></label>
									<div class="ui-select home-hero__select">
										<select class="ui-field__control" id="hero-service" name="service" required>
											<?php foreach ( $service_options as $option ) : ?>
												<option value="<?php echo esc_attr( $option['value'] ?? '' ); ?>" <?php selected( (string) ( $section['service_default'] ?? '' ), (string) ( $option['value'] ?? '' ) ); ?>><?php echo esc_html( $option['label'] ?? '' ); ?></option>
											<?php endforeach; ?>
										</select>
										<span class="ui-select__icon" aria-hidden="true">
											<svg class="ui-select__icon-svg" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M1 1.25L6 6.25L11 1.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
											</svg>
										</span>
									</div>
								</div>

								<div class="ui-field home-hero__field">
									<label class="ui-field__label" for="hero-frequency"><?php echo esc_html( $section['frequency_label'] ?? '' ); ?></label>
									<div class="ui-select home-hero__select">
										<select class="ui-field__control" id="hero-frequency" name="frequency" required>
											<?php foreach ( $frequency_options as $option ) : ?>
												<option value="<?php echo esc_attr( $option['value'] ?? '' ); ?>" <?php selected( (string) ( $section['frequency_default'] ?? '' ), (string) ( $option['value'] ?? '' ) ); ?>><?php echo esc_html( $option['label'] ?? '' ); ?></option>
											<?php endforeach; ?>
										</select>
										<span class="ui-select__icon" aria-hidden="true">
											<svg class="ui-select__icon-svg" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M1 1.25L6 6.25L11 1.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
											</svg>
										</span>
									</div>
								</div>

								<div class="ui-field home-hero__field">
									<label class="ui-field__label" for="hero-name"><?php echo esc_html( $section['name_label'] ?? '' ); ?></label>
									<input class="ui-field__control" id="hero-name" type="text" name="name" placeholder="<?php echo esc_attr( $section['name_placeholder'] ?? '' ); ?>" autocomplete="name" required>
								</div>

								<div class="ui-field home-hero__field">
									<label class="ui-field__label" for="hero-phone"><?php echo esc_html( $section['phone_label'] ?? '' ); ?></label>
									<input class="ui-field__control" id="hero-phone" type="tel" name="phone" placeholder="<?php echo esc_attr( $section['phone_placeholder'] ?? '' ); ?>" autocomplete="tel-national" inputmode="tel" required>
								</div>
							</div>

							<div class="home-hero__actions">
								<label class="ui-checkbox home-hero__checkbox" for="hero-agreement">
									<input class="ui-checkbox__input" id="hero-agreement" type="checkbox" name="agreement" required>
									<span class="ui-checkbox__label"><?php echo esc_html( $section['agreement_text'] ?? '' ); ?></span>
								</label>
								<button class="ui-btn ui-btn--primary ui-btn--full home-hero__submit" type="submit">
									<span class="ui-btn__content"><?php echo esc_html( $section['submit_text'] ?? '' ); ?></span>
								</button>
							</div>
						</form>
					</div>
				</div>

				<div class="home-hero__visual" aria-hidden="true">
					<div class="home-hero__visual-tools">
						<?php echo wipe_clean_render_media( $section['tools_image'] ?? array(), array( 'loading' => 'eager' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div class="home-hero__visual-shadow"></div>
					<div class="home-hero__visual-room">
						<?php echo wipe_clean_render_media( $section['room_image'] ?? array(), array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div class="home-hero__visual-cleaner">
						<?php echo wipe_clean_render_media( $section['cleaner_image'] ?? array(), array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
			</div>

			<?php if ( $benefits ) : ?>
				<div class="home-hero__benefits">
					<?php foreach ( $benefits as $benefit ) : ?>
						<article class="ui-card ui-card--compact home-hero__benefit">
							<div class="ui-icon-badge home-hero__benefit-icon">
								<div class="ui-icon-badge__inner">
									<?php echo wipe_clean_render_media( $benefit['icon'] ?? array(), array( 'loading' => 'lazy', 'aria-hidden' => 'true' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
							</div>
							<p class="home-hero__benefit-title"><?php echo esc_html( $benefit['title'] ?? '' ); ?></p>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
