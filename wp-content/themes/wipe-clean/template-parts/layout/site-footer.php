<?php
/**
 * Global site footer markup.
 *
 * @package wipe-clean
 */

$settings = isset( $args['settings'] ) && is_array( $args['settings'] ) ? $args['settings'] : array();

$brand_url     = (string) ( $settings['brand_url'] ?? home_url( '/' ) );
$brand_mark    = $settings['brand_mark'] ?? array();
$brand_type    = $settings['brand_type'] ?? array();
$wave_image    = $settings['wave_image'] ?? array();
$requisites    = isset( $settings['requisites'] ) && is_array( $settings['requisites'] ) ? $settings['requisites'] : array();
$nav_title     = (string) ( $settings['nav_title'] ?? 'Меню сайта' );
$nav_columns   = isset( $settings['nav_columns'] ) && is_array( $settings['nav_columns'] ) ? $settings['nav_columns'] : array();
$phone_label   = (string) ( $settings['phone_label'] ?? 'Номер телефона' );
$phone         = trim( (string) ( $settings['phone'] ?? '' ) );
$phone_href    = function_exists( 'wipe_clean_get_site_shell_phone_href' ) ? wipe_clean_get_site_shell_phone_href( $phone ) : '';
$socials_label = (string) ( $settings['socials_label'] ?? 'Мессенджеры и соцсети' );
$social_links  = isset( $settings['social_links'] ) && is_array( $settings['social_links'] ) ? $settings['social_links'] : array();
$email_label   = (string) ( $settings['email_label'] ?? 'Электронная почта' );
$email         = trim( (string) ( $settings['email'] ?? '' ) );
$email_href    = function_exists( 'wipe_clean_get_site_shell_email_href' ) ? wipe_clean_get_site_shell_email_href( $email ) : '';
$copyright     = (string) ( $settings['copyright'] ?? '' );
$legal_links   = isset( $settings['legal_links'] ) && is_array( $settings['legal_links'] ) ? $settings['legal_links'] : array();
$made_by_badge  = (string) ( $settings['made_by_badge'] ?? 'DS' );
$made_by_link   = isset( $settings['made_by_link'] ) && is_array( $settings['made_by_link'] ) ? $settings['made_by_link'] : array();
$made_by_url    = ! empty( $made_by_link['url'] ) ? (string) $made_by_link['url'] : '';
$made_by_title  = ! empty( $made_by_link['title'] ) ? (string) $made_by_link['title'] : '';
$made_by_target = ! empty( $made_by_link['target'] ) ? (string) $made_by_link['target'] : '';
$made_by_rel    = '_blank' === $made_by_target ? 'noopener noreferrer' : '';
?>
<footer class="footer">
	<div class="footer__wave" aria-hidden="true"><?php echo wipe_clean_render_media( $wave_image, array( 'alt' => '', 'loading' => 'eager', 'decoding' => 'async' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	<div class="footer__body">
		<div class="_container">
			<div class="footer__wrapper">
				<div class="footer__top">
					<a class="footer__brand" href="<?php echo esc_url( $brand_url ); ?>" aria-label="На главную">
						<span class="footer__brand-mark"><?php echo wipe_clean_render_media( $brand_mark, array( 'alt' => '', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span class="footer__brand-type"><?php echo wipe_clean_render_media( $brand_type, array( 'alt' => '', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</a>
					<?php if ( ! empty( $requisites ) ) : ?>
						<ul class="footer__requisites">
							<?php foreach ( $requisites as $item ) : ?>
								<?php if ( empty( $item['label'] ) || empty( $item['value'] ) ) : ?>
									<?php continue; ?>
								<?php endif; ?>
								<li class="footer__requisite">
									<strong class="footer__requisite-label"><?php echo esc_html( (string) $item['label'] ); ?></strong>
									<span class="footer__requisite-value"><?php echo esc_html( (string) $item['value'] ); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
				<div class="footer__divider"></div>
				<div class="footer__nav">
					<p class="footer__nav-title"><?php echo esc_html( $nav_title ); ?></p>
					<div class="footer__nav-body">
						<?php foreach ( $nav_columns as $column ) : ?>
							<?php if ( ! is_array( $column ) || empty( $column ) ) : ?>
								<?php continue; ?>
							<?php endif; ?>
							<ul class="footer__nav-column">
								<?php foreach ( $column as $link ) : ?>
									<?php if ( empty( $link['url'] ) || empty( $link['title'] ) ) : ?>
										<?php continue; ?>
									<?php endif; ?>
									<li class="footer__nav-item">
										<a class="footer__nav-link" href="<?php echo esc_url( (string) $link['url'] ); ?>"<?php echo ! empty( $link['target'] ) ? ' target="' . esc_attr( (string) $link['target'] ) . '"' : ''; ?><?php echo ! empty( $link['target'] ) && '_blank' === (string) $link['target'] ? ' rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( (string) $link['title'] ); ?></a>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="footer__divider"></div>
				<div class="footer__contacts">
					<div class="contact-card footer__contact-card">
						<p class="contact-card__label"><?php echo esc_html( $phone_label ); ?></p>
						<a class="contact-card__value contact-card__value--link" href="<?php echo esc_url( '' !== $phone_href ? $phone_href : '#' ); ?>"><?php echo esc_html( $phone ); ?></a>
					</div>
					<div class="contact-card contact-card--socials footer__contact-card">
						<p class="contact-card__label"><?php echo esc_html( $socials_label ); ?></p>
						<div class="contact-card__socials">
							<?php foreach ( $social_links as $item ) : ?>
								<?php
								$link  = isset( $item['link'] ) && is_array( $item['link'] ) ? $item['link'] : array();
								$label = ! empty( $item['label'] ) ? (string) $item['label'] : (string) ( $link['title'] ?? 'Соцсеть' );
								$url   = ! empty( $link['url'] ) ? (string) $link['url'] : '#';
								?>
								<a class="contact-card__social-link ui-btn ui-btn--primary" href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo esc_attr( $label ); ?>"<?php echo ! empty( $link['target'] ) ? ' target="' . esc_attr( (string) $link['target'] ) . '"' : ''; ?><?php echo ! empty( $link['target'] ) && '_blank' === (string) $link['target'] ? ' rel="noopener noreferrer"' : ''; ?>>
									<span class="ui-btn__content"><?php echo wipe_clean_render_media( $item['icon'] ?? array(), array( 'alt' => '', 'aria-hidden' => 'true', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
								</a>
							<?php endforeach; ?>
						</div>
					</div>
					<div class="contact-card footer__contact-card">
						<p class="contact-card__label"><?php echo esc_html( $email_label ); ?></p>
						<a class="contact-card__value contact-card__value--link" href="<?php echo esc_url( '' !== $email_href ? $email_href : '#' ); ?>"><?php echo esc_html( $email ); ?></a>
					</div>
				</div>
				<div class="footer__bottom">
					<?php if ( '' !== $copyright ) : ?>
						<p class="footer__copyright"><?php echo esc_html( $copyright ); ?></p>
					<?php endif; ?>
					<div class="footer__legal">
						<?php foreach ( $legal_links as $link ) : ?>
							<?php if ( empty( $link['url'] ) || empty( $link['title'] ) ) : ?>
								<?php continue; ?>
							<?php endif; ?>
							<a class="footer__legal-link" href="<?php echo esc_url( (string) $link['url'] ); ?>"<?php echo ! empty( $link['target'] ) ? ' target="' . esc_attr( (string) $link['target'] ) . '"' : ''; ?><?php echo ! empty( $link['target'] ) && '_blank' === (string) $link['target'] ? ' rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( (string) $link['title'] ); ?></a>
						<?php endforeach; ?>
					</div>
					<?php if ( '' !== $made_by_url && '' !== $made_by_title ) : ?>
						<a class="footer__made-by" href="<?php echo esc_url( $made_by_url ); ?>"<?php echo $made_by_target ? ' target="' . esc_attr( $made_by_target ) . '"' : ''; ?><?php echo $made_by_rel ? ' rel="' . esc_attr( $made_by_rel ) . '"' : ''; ?> aria-label="<?php echo esc_attr( $made_by_title ); ?>">
							<span class="footer__made-by-mark"><?php echo esc_html( $made_by_badge ); ?></span>
							<span class="footer__made-by-text"><?php echo esc_html( $made_by_title ); ?></span>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</footer>
