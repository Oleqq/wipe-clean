<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section        = isset( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : array();
$fallback_cards = function_exists( 'wipe_clean_get_services_page_default_service_cards' )
	? wipe_clean_get_services_page_default_service_cards()
	: array();
$cards = isset( $section['service_cards'] ) && is_array( $section['service_cards'] ) ? $section['service_cards'] : array();

if ( empty( $cards ) ) {
	$cards = isset( $section['cards'] ) && is_array( $section['cards'] ) ? $section['cards'] : array();
}

if ( empty( $cards ) && function_exists( 'wipe_clean_get_services_page_cpt_service_cards' ) ) {
	$cards = wipe_clean_get_services_page_cpt_service_cards( $fallback_cards );
}

$hero_primary_action     = $section['hero_primary_action'] ?? array();
$hero_secondary_action   = $section['hero_secondary_action'] ?? array();
$footer_primary_action   = $section['footer_primary_action'] ?? array();
$footer_secondary_action = $section['footer_secondary_action'] ?? array();
$overview_body           = isset( $section['overview_body'] ) && is_array( $section['overview_body'] ) ? $section['overview_body'] : array();
$overview_more_label     = (string) ( $section['overview_more_label'] ?? 'Ещё' );
$overview_less_label     = (string) ( $section['overview_less_label'] ?? 'Свернуть' );
$overview_body_id        = 'services-intro-overview-' . wp_unique_id();

$render_action = static function ( $action, $class_name ) {
	$link = wipe_clean_resolve_link( $action );

	if ( empty( $link['title'] ) ) {
		return;
	}
	?>
	<a class="<?php echo esc_attr( $class_name ); ?>" href="<?php echo esc_url( $link['url'] ?: '#' ); ?>"<?php echo $link['target'] ? ' target="' . esc_attr( $link['target'] ) . '"' : ''; ?>>
		<span class="ui-btn__content"><?php echo esc_html( $link['title'] ); ?></span>
	</a>
	<?php
};
?>
<section class="services-intro">
	<div class="_container">
		<div class="services-intro__wrapper">
			<div class="services-intro__hero">
				<div class="services-intro__hero-copy">
					<div class="services-intro__hero-head ui-section-head">
						<?php if ( ! empty( $section['hero_kicker'] ) ) : ?>
							<span class="ui-kicker services-intro__hero-kicker"><?php echo esc_html( $section['hero_kicker'] ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $section['hero_title'] ) ) : ?>
							<h1 class="ui-title ui-title--hero services-intro__hero-title"><?php echo esc_html( $section['hero_title'] ); ?></h1>
						<?php endif; ?>
					</div>

					<div class="services-intro__hero-content">
						<?php if ( ! empty( $section['hero_text'] ) ) : ?>
							<p class="ui-text services-intro__hero-text"><?php echo esc_html( $section['hero_text'] ); ?></p>
						<?php endif; ?>

						<div class="services-intro__hero-actions">
							<?php $render_action( $hero_primary_action, 'ui-btn services-intro__hero-action ui-btn--primary' ); ?>
							<?php $render_action( $hero_secondary_action, 'ui-btn services-intro__hero-action ui-btn--secondary' ); ?>
						</div>
					</div>
				</div>

				<div class="services-intro__hero-media" aria-hidden="true">
					<div class="services-intro__hero-decor">
						<?php echo wipe_clean_render_media( $section['hero_decor_image'] ?? array(), array( 'class' => 'services-intro__hero-image', 'alt' => '' ) ); ?>
					</div>
					<div class="services-intro__hero-cleaner">
						<?php echo wipe_clean_render_media( $section['hero_cleaner_image'] ?? array(), array( 'class' => 'services-intro__hero-image', 'alt' => '' ) ); ?>
					</div>
					<div class="services-intro__hero-interior">
						<?php echo wipe_clean_render_media( $section['hero_interior_image'] ?? array(), array( 'class' => 'services-intro__hero-image', 'alt' => '' ) ); ?>
					</div>
				</div>
			</div>

			<div class="services-intro__overview">
				<div class="services-intro__overview-content">
					<?php if ( ! empty( $section['overview_title'] ) ) : ?>
						<h2 class="ui-title services-intro__overview-title"><?php echo esc_html( $section['overview_title'] ); ?></h2>
					<?php endif; ?>

					<div class="services-intro__overview-copy" data-read-more>
						<?php if ( ! empty( $section['overview_summary'] ) ) : ?>
							<div class="services-intro__overview-summary">
								<p class="ui-text services-intro__overview-summary-text">
									<?php echo esc_html( $section['overview_summary'] ); ?>
									<?php if ( ! empty( $overview_body ) ) : ?>
										<button class="services-intro__overview-more" type="button" data-read-more-toggle data-read-more-open-label="<?php echo esc_attr( $overview_more_label ); ?>" data-read-more-close-label="<?php echo esc_attr( $overview_less_label ); ?>" aria-expanded="false" aria-controls="<?php echo esc_attr( $overview_body_id ); ?>">
											<?php echo esc_html( $overview_more_label ); ?>
										</button>
									<?php endif; ?>
								</p>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $overview_body ) ) : ?>
							<div id="<?php echo esc_attr( $overview_body_id ); ?>" class="services-intro__overview-body">
								<?php foreach ( $overview_body as $paragraph ) : ?>
									<?php if ( ! empty( $paragraph['text'] ) ) : ?>
										<p class="ui-text services-intro__overview-text"><?php echo esc_html( $paragraph['text'] ); ?></p>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div class="services-intro__overview-media" aria-hidden="true">
					<?php echo wipe_clean_render_media( $section['overview_image'] ?? array(), array( 'alt' => '' ) ); ?>
				</div>
			</div>

			<div class="services-intro__slider swiper" data-services-intro-swiper>
				<div class="services-intro__slider-track swiper-wrapper">
					<?php foreach ( $cards as $card ) : ?>
						<div class="services-intro__slide swiper-slide">
							<?php get_template_part( 'template-parts/components/service-teaser-card', null, array( 'card' => $card ) ); ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="services-intro__footer">
				<div class="services-intro__actions">
					<?php $render_action( $footer_primary_action, 'ui-btn services-intro__action ui-btn--primary' ); ?>
					<?php $render_action( $footer_secondary_action, 'ui-btn services-intro__action ui-btn--secondary' ); ?>
				</div>

				<div class="services-intro__nav ui-slider-nav ui-slider-nav--desktop-only">
					<button class="services-intro__nav-btn ui-btn ui-btn--primary ui-slider-nav__btn" type="button" aria-label="Предыдущая услуга" data-services-intro-prev>
						<span class="ui-btn__content">
							<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M28 18H8" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M16 10L8 18L16 26" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</span>
					</button>
					<button class="services-intro__nav-btn ui-btn ui-btn--primary ui-slider-nav__btn" type="button" aria-label="Следующая услуга" data-services-intro-next>
						<span class="ui-btn__content">
							<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M8 18H28" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M20 10L28 18L20 26" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</span>
					</button>
				</div>
			</div>
		</div>
	</div>
</section>
