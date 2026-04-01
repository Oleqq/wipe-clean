<?php
/**
 * Global site header markup.
 *
 * @package wipe-clean
 */

$settings = isset( $args['settings'] ) && is_array( $args['settings'] ) ? $args['settings'] : array();

$brand_url         = (string) ( $settings['brand_url'] ?? home_url( '/' ) );
$brand_mark        = $settings['brand_mark'] ?? array();
$brand_type        = $settings['brand_type'] ?? array();
$phone             = trim( (string) ( $settings['phone'] ?? '' ) );
$phone_href        = function_exists( 'wipe_clean_get_site_shell_phone_href' ) ? wipe_clean_get_site_shell_phone_href( $phone ) : '';
$cta               = isset( $settings['cta'] ) && is_array( $settings['cta'] ) ? $settings['cta'] : array();
$menu_items        = isset( $settings['menu_items'] ) && is_array( $settings['menu_items'] ) ? $settings['menu_items'] : array();
$cta_url           = ! empty( $cta['url'] ) ? (string) $cta['url'] : '#';
$cta_title         = ! empty( $cta['title'] ) ? (string) $cta['title'] : 'Связаться с нами';
$cta_target        = ! empty( $cta['target'] ) ? (string) $cta['target'] : '';
$cta_rel           = '_blank' === $cta_target ? 'noopener noreferrer' : '';
?>
<header class="header" data-header>
	<div class="_container">
		<div class="header__wrapper">
			<a class="header__brand" href="<?php echo esc_url( $brand_url ); ?>" aria-label="На главную">
				<span class="header__brand-mark"><?php echo wipe_clean_render_media( $brand_mark, array( 'alt' => '', 'loading' => 'eager', 'decoding' => 'async' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span class="header__brand-type"><?php echo wipe_clean_render_media( $brand_type, array( 'alt' => '', 'loading' => 'eager', 'decoding' => 'async' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</a>
			<nav class="header__nav" id="header-nav" data-header-nav aria-label="Основная навигация">
				<button class="header__nav-backdrop" type="button" data-header-close aria-label="Закрыть меню"></button>
				<div class="header__nav-panel" data-lenis-prevent>
					<ul class="header__menu">
						<?php foreach ( $menu_items as $index => $item ) : ?>
							<?php
							if ( ! is_array( $item ) ) {
								continue;
							}

							$item_link       = isset( $item['link'] ) && is_array( $item['link'] ) ? $item['link'] : array();
							$item_label      = trim( (string) ( $item['label'] ?? ( $item_link['title'] ?? '' ) ) );
							$submenu_links   = isset( $item['submenu_links'] ) && is_array( $item['submenu_links'] ) ? $item['submenu_links'] : array();
							$has_submenu     = ! empty( $item['has_submenu'] ) && ! empty( $submenu_links );
							$submenu_columns = $has_submenu && function_exists( 'wipe_clean_get_site_header_menu_columns' )
								? wipe_clean_get_site_header_menu_columns( $submenu_links )
								: array();
							$submenu_style   = count( $submenu_columns ) > 1 ? 'wide' : 'compact';
							$submenu_id      = 'header-submenu-' . sanitize_title( $item_label ? $item_label . '-' . ( $index + 1 ) : 'item-' . $index );
							$item_classes    = array( 'header__item' );

							if ( $has_submenu ) {
								$item_classes[] = 'header__item--has-children';
								$item_classes[] = 'header__item--submenu-' . $submenu_style;
							}

							if ( ! empty( $item['mobile_only'] ) ) {
								$item_classes[] = 'header__item--desktop-hidden';
							}

							$item_target = ! empty( $item_link['target'] ) ? (string) $item_link['target'] : '';
							$item_rel    = '_blank' === $item_target ? 'noopener noreferrer' : '';
							?>
							<li class="<?php echo esc_attr( implode( ' ', $item_classes ) ); ?>"<?php echo $has_submenu ? ' data-header-item' : ''; ?>>
								<?php if ( $has_submenu ) : ?>
									<div class="header__item-row">
										<a class="header__link header__link--parent" href="<?php echo esc_url( (string) ( $item_link['url'] ?? '#' ) ); ?>" data-header-link data-header-parent-link<?php echo $item_target ? ' target="' . esc_attr( $item_target ) . '"' : ''; ?><?php echo $item_rel ? ' rel="' . esc_attr( $item_rel ) . '"' : ''; ?>><?php echo esc_html( $item_label ); ?></a>
										<button class="header__submenu-toggle" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $submenu_id ); ?>" aria-label="<?php echo esc_attr( 'Показать подменю раздела ' . $item_label ); ?>" data-header-submenu-toggle>
											<svg class="header__submenu-toggle-icon" viewBox="0 0 9 6" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
												<path d="M0.353516 0.353516L4.35352 4.35352L8.35352 0.353516" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
											</svg>
										</button>
									</div>
									<div class="header__submenu" id="<?php echo esc_attr( $submenu_id ); ?>" data-header-submenu aria-hidden="true">
										<div class="header__submenu-panel header__submenu-panel--<?php echo esc_attr( $submenu_style ); ?>">
											<?php foreach ( $submenu_columns as $column ) : ?>
												<?php if ( ! is_array( $column ) || empty( $column ) ) : ?>
													<?php continue; ?>
												<?php endif; ?>
												<ul class="header__submenu-column">
													<?php foreach ( $column as $submenu_link ) : ?>
														<?php
														$submenu_target = ! empty( $submenu_link['target'] ) ? (string) $submenu_link['target'] : '';
														$submenu_rel    = '_blank' === $submenu_target ? 'noopener noreferrer' : '';
														?>
														<?php if ( empty( $submenu_link['url'] ) || empty( $submenu_link['title'] ) ) : ?>
															<?php continue; ?>
														<?php endif; ?>
														<li class="header__submenu-item">
															<a class="header__submenu-link" href="<?php echo esc_url( (string) $submenu_link['url'] ); ?>" data-header-link<?php echo $submenu_target ? ' target="' . esc_attr( $submenu_target ) . '"' : ''; ?><?php echo $submenu_rel ? ' rel="' . esc_attr( $submenu_rel ) . '"' : ''; ?>>
																<span class="header__submenu-link-text"><?php echo esc_html( (string) $submenu_link['title'] ); ?></span>
																<span class="header__submenu-arrow">
																	<svg class="header__submenu-arrow-icon" viewBox="0 0 30 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
																		<path d="M21 2L28 9L21 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
																		<path d="M2 9H28" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
																	</svg>
																</span>
															</a>
														</li>
													<?php endforeach; ?>
												</ul>
											<?php endforeach; ?>
										</div>
									</div>
								<?php else : ?>
									<a class="header__link" href="<?php echo esc_url( (string) ( $item_link['url'] ?? '#' ) ); ?>" data-header-link<?php echo $item_target ? ' target="' . esc_attr( $item_target ) . '"' : ''; ?><?php echo $item_rel ? ' rel="' . esc_attr( $item_rel ) . '"' : ''; ?>><?php echo esc_html( $item_label ); ?></a>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</nav>
			<div class="header__actions">
				<a class="ui-btn ui-btn--primary ui-btn--icon header__phone" href="<?php echo esc_url( '' !== $phone_href ? $phone_href : '#' ); ?>" aria-label="Позвонить">
					<span class="ui-btn__content">
						<span class="header__action-icon">
							<svg class="header__action-icon-svg" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
								<path d="M22 20.2448V23.667C22 24.2858 21.7542 24.8793 21.3167 25.3167C20.8793 25.7542 20.2858 26 19.667 26C15.2332 25.7304 10.9848 23.8476 7.81016 20.673C4.63555 17.4983 2.75276 13.2499 2.48315 8.81616C2.48315 8.19735 2.72899 7.60389 3.16642 7.16644C3.60386 6.729 4.19731 6.48315 4.81612 6.48315H8.23831C8.65696 6.47904 9.06282 6.6286 9.37925 6.90325C9.69567 7.17789 9.90063 7.55849 9.95565 7.97353C10.0583 8.7517 10.2488 9.51583 10.523 10.2511C10.6378 10.557 10.6642 10.889 10.599 11.2092C10.5338 11.5294 10.3796 11.8248 10.1541 12.0614L8.70538 13.5101C10.3298 16.3668 12.6991 18.7361 15.5558 20.3605L17.0045 18.9118C17.2411 18.6863 17.5365 18.5321 17.8567 18.4669C18.1769 18.4017 18.5089 18.4281 18.8148 18.5429C19.5501 18.8171 20.3142 19.0076 21.0924 19.1103C21.5115 19.166 21.8954 19.375 22.1704 19.6973C22.4453 20.0197 22.5928 20.432 22.5854 20.855L22 20.2448Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
							</svg>
						</span>
					</span>
				</a>
				<a class="ui-btn ui-btn--primary header__cta" href="<?php echo esc_url( $cta_url ); ?>"<?php echo $cta_target ? ' target="' . esc_attr( $cta_target ) . '"' : ''; ?><?php echo $cta_rel ? ' rel="' . esc_attr( $cta_rel ) . '"' : ''; ?>><span class="ui-btn__content"><?php echo esc_html( $cta_title ); ?></span></a>
				<button class="ui-btn ui-btn--primary ui-btn--icon header__menu-toggle" type="button" aria-expanded="false" aria-controls="header-nav" aria-label="Открыть меню" data-header-toggle>
					<span class="ui-btn__content">
						<span class="header__action-icon header__action-icon--menu">
							<svg class="header__action-icon-svg" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
								<path d="M4.66699 5.8335H23.3337" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
								<path d="M4.66699 14H23.3337" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
								<path d="M4.66699 22.1665H23.3337" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
							</svg>
						</span>
						<span class="header__action-icon header__action-icon--close">
							<svg class="header__action-icon-svg" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
								<path d="M21 7L7 21" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
								<path d="M7 7L21 21" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path>
							</svg>
						</span>
					</span>
				</button>
			</div>
		</div>
	</div>
</header>
