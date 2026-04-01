<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section        = isset( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : array();
$tabs           = ! empty( $section['includes_tabs'] ) && is_array( $section['includes_tabs'] ) ? array_values( $section['includes_tabs'] ) : array();
$benefit_cards  = ! empty( $section['benefits_cards'] ) && is_array( $section['benefits_cards'] ) ? array_values( $section['benefits_cards'] ) : array();
$id_prefix      = 'service-includes-' . wp_unique_id();
$render_hotspot = static function ( $hotspot ) {
	$style = sprintf(
		'--hotspot-x-desktop:%1$s%%;--hotspot-y-desktop:%2$s%%;--hotspot-x-mobile:%3$s%%;--hotspot-y-mobile:%4$s%%;',
		(float) ( $hotspot['x_desktop'] ?? 0 ),
		(float) ( $hotspot['y_desktop'] ?? 0 ),
		(float) ( $hotspot['x_mobile'] ?? 0 ),
		(float) ( $hotspot['y_mobile'] ?? 0 )
	);

	return $style;
};
$render_tooltip = static function ( $hotspot ) {
	$style = sprintf(
		'--tooltip-x-desktop:%1$s%%;--tooltip-y-desktop:%2$s%%;--tooltip-x-mobile:%3$s%%;--tooltip-y-mobile:%4$s%%;--tooltip-width-desktop:%5$spx;--tooltip-width-mobile:%6$spx;',
		(float) ( $hotspot['tooltip_x_desktop'] ?? 0 ),
		(float) ( $hotspot['tooltip_y_desktop'] ?? 0 ),
		(float) ( $hotspot['tooltip_x_mobile'] ?? 0 ),
		(float) ( $hotspot['tooltip_y_mobile'] ?? 0 ),
		(float) ( $hotspot['tooltip_width_desktop'] ?? 0 ),
		(float) ( $hotspot['tooltip_width_mobile'] ?? 0 )
	);

	return $style;
};
?>
<div class="ui-wave-group">
	<section class="service-includes" data-service-includes>
		<div class="_container">
			<div class="service-includes__wrapper">
				<div class="service-includes__content">
					<div class="service-includes__head">
						<?php if ( ! empty( $section['includes_title'] ) ) : ?>
							<h2 class="ui-title service-includes__title"><?php echo esc_html( $section['includes_title'] ); ?></h2>
						<?php endif; ?>
						<?php if ( ! empty( $section['includes_text'] ) ) : ?>
							<p class="ui-text service-includes__text"><?php echo esc_html( $section['includes_text'] ); ?></p>
						<?php endif; ?>
					</div>

					<?php if ( ! empty( $tabs ) ) : ?>
						<div class="service-includes__tabs" role="tablist" aria-label="<?php echo esc_attr( $section['includes_title'] ?? 'Состав услуги' ); ?>">
							<?php foreach ( $tabs as $index => $tab ) : ?>
								<?php
								$tab_id    = $id_prefix . '-tab-' . ( $tab['id'] ?? $index );
								$panel_id  = $id_prefix . '-panel-' . ( $tab['id'] ?? $index );
								$is_active = 0 === $index;
								?>
								<button id="<?php echo esc_attr( $tab_id ); ?>" class="service-includes__tab<?php echo $is_active ? ' is-active' : ''; ?>" type="button" role="tab" data-service-includes-tab data-tab-id="<?php echo esc_attr( $tab['id'] ?? $index ); ?>" aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $panel_id ); ?>" tabindex="<?php echo $is_active ? '0' : '-1'; ?>">
									<span class="service-includes__tab-label"><?php echo esc_html( $tab['label'] ?? '' ); ?></span>
									<span class="service-includes__tab-icon" aria-hidden="true">
										<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
											<path d="M20 18.666L26.6667 11.9994L20 5.33269" stroke="url(#paint0_linear_<?php echo esc_attr( $index ); ?>)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
											<path d="M5.33398 26.666L5.33398 17.3327C5.33398 15.9182 5.89589 14.5616 6.89608 13.5614C7.89628 12.5612 9.25283 11.9993 10.6673 11.9993L26.6673 11.9993" stroke="url(#paint1_linear_<?php echo esc_attr( $index ); ?>)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
											<defs>
												<linearGradient id="paint0_linear_<?php echo esc_attr( $index ); ?>" x1="20" y1="11.9994" x2="26.6667" y2="11.9994" gradientUnits="userSpaceOnUse">
													<stop stop-color="#0086B3"/>
													<stop offset="1" stop-color="#40A5C1"/>
												</linearGradient>
												<linearGradient id="paint1_linear_<?php echo esc_attr( $index ); ?>" x1="5.33398" y1="19.3327" x2="26.6673" y2="19.3327" gradientUnits="userSpaceOnUse">
													<stop stop-color="#0086B3"/>
													<stop offset="1" stop-color="#40A5C1"/>
												</linearGradient>
											</defs>
										</svg>
									</span>
								</button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="service-includes__visual">
					<div class="service-includes__visual-stage">
						<?php foreach ( $tabs as $tab_index => $tab ) : ?>
							<?php
							$panel_id          = $id_prefix . '-panel-' . ( $tab['id'] ?? $tab_index );
							$labelled_by       = $id_prefix . '-tab-' . ( $tab['id'] ?? $tab_index );
							$is_active_panel   = 0 === $tab_index;
							$hotspots          = ! empty( $tab['hotspots'] ) && is_array( $tab['hotspots'] ) ? array_values( $tab['hotspots'] ) : array();
							$active_hotspot_id = ! empty( $tab['active_hotspot_id'] ) ? (string) $tab['active_hotspot_id'] : (string) ( $hotspots[0]['id'] ?? '' );
							?>
							<div id="<?php echo esc_attr( $panel_id ); ?>" class="service-includes__panel<?php echo $is_active_panel ? ' is-active' : ''; ?>" role="tabpanel" data-service-includes-panel data-tab-id="<?php echo esc_attr( $tab['id'] ?? $tab_index ); ?>" aria-labelledby="<?php echo esc_attr( $labelled_by ); ?>" aria-hidden="<?php echo $is_active_panel ? 'false' : 'true'; ?>">
								<div class="service-includes__panel-image">
									<?php echo wipe_clean_render_media( $tab['image'] ?? array(), array( 'alt' => (string) ( $tab['label'] ?? '' ) ) ); ?>
								</div>

								<?php foreach ( $hotspots as $hotspot_index => $hotspot ) : ?>
									<?php
									$hotspot_id        = (string) ( $hotspot['id'] ?? 'hotspot-' . $tab_index . '-' . $hotspot_index );
									$button_id         = $id_prefix . '-hotspot-' . ( $tab['id'] ?? $tab_index ) . '-' . $hotspot_id;
									$tooltip_id        = $id_prefix . '-tooltip-' . ( $tab['id'] ?? $tab_index ) . '-' . $hotspot_id;
									$is_active_hotspot = $hotspot_id === $active_hotspot_id;
									?>
									<button id="<?php echo esc_attr( $button_id ); ?>" class="service-includes__hotspot<?php echo $is_active_hotspot ? ' is-active' : ''; ?>" type="button" data-service-includes-hotspot data-hotspot-id="<?php echo esc_attr( $hotspot_id ); ?>" aria-controls="<?php echo esc_attr( $tooltip_id ); ?>" aria-expanded="<?php echo $is_active_hotspot ? 'true' : 'false'; ?>" tabindex="<?php echo $is_active_panel ? '0' : '-1'; ?>" style="<?php echo esc_attr( $render_hotspot( $hotspot ) ); ?>">
										<span class="service-includes__hotspot-core" aria-hidden="true"></span>
									</button>
								<?php endforeach; ?>

								<div class="service-includes__tooltips">
									<?php foreach ( $hotspots as $hotspot_index => $hotspot ) : ?>
										<?php
										$hotspot_id        = (string) ( $hotspot['id'] ?? 'hotspot-' . $tab_index . '-' . $hotspot_index );
										$tooltip_id        = $id_prefix . '-tooltip-' . ( $tab['id'] ?? $tab_index ) . '-' . $hotspot_id;
										$is_active_hotspot = $hotspot_id === $active_hotspot_id;
										?>
										<div id="<?php echo esc_attr( $tooltip_id ); ?>" class="service-includes__tooltip<?php echo $is_active_hotspot ? ' is-active' : ''; ?>" role="tooltip" data-service-includes-tooltip data-hotspot-id="<?php echo esc_attr( $hotspot_id ); ?>" aria-hidden="<?php echo $is_active_hotspot ? 'false' : 'true'; ?>" style="<?php echo esc_attr( $render_tooltip( $hotspot ) ); ?>">
											<p class="service-includes__tooltip-text"><?php echo esc_html( $hotspot['tooltip'] ?? '' ); ?></p>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="service-benefits">
		<div class="_container">
			<div class="service-benefits__wrapper">
				<div class="service-benefits__head ui-section-head">
					<?php if ( ! empty( $section['benefits_title'] ) ) : ?>
						<h2 class="ui-title service-benefits__title"><?php echo esc_html( $section['benefits_title'] ); ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $section['benefits_text'] ) ) : ?>
						<p class="ui-text service-benefits__text"><?php echo esc_html( $section['benefits_text'] ); ?></p>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $benefit_cards ) ) : ?>
					<div class="service-benefits__desktop-grid">
						<?php foreach ( $benefit_cards as $card ) : ?>
							<div class="service-benefits__desktop-item">
								<?php
								get_template_part(
									'template-parts/components/feature-card',
									null,
									array(
										'item' => array(
											'title'      => (string) ( $card['title'] ?? '' ),
											'text'       => (string) ( $card['text'] ?? '' ),
											'icon'       => $card['icon'] ?? array(),
											'class_name' => 'feature-card--compact',
										),
									)
								);
								?>
							</div>
						<?php endforeach; ?>

						<?php if ( ! empty( $section['benefits_summary'] ) ) : ?>
							<div class="service-benefits__summary">
								<p class="ui-text service-benefits__summary-text"><?php echo esc_html( $section['benefits_summary'] ); ?></p>
							</div>
						<?php endif; ?>
					</div>

					<div class="service-benefits__slider swiper" data-service-benefits-swiper>
						<div class="service-benefits__track swiper-wrapper">
							<?php foreach ( $benefit_cards as $card ) : ?>
								<div class="service-benefits__slide swiper-slide">
									<?php
									get_template_part(
										'template-parts/components/feature-card',
										null,
										array(
											'item' => array(
												'title'      => (string) ( $card['title'] ?? '' ),
												'text'       => (string) ( $card['text'] ?? '' ),
												'icon'       => $card['icon'] ?? array(),
												'class_name' => 'feature-card--compact',
											),
										)
									);
									?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $section['benefits_summary'] ) ) : ?>
					<div class="service-benefits__summary-mobile">
						<p class="ui-text service-benefits__summary-text"><?php echo esc_html( $section['benefits_summary'] ); ?></p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
</div>
