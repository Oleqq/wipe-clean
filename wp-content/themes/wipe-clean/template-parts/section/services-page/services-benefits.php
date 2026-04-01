<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section         = isset( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : array();
$cards           = isset( $section['cards'] ) && is_array( $section['cards'] ) ? $section['cards'] : array();
$checklist_items = isset( $section['checklist_items'] ) && is_array( $section['checklist_items'] ) ? $section['checklist_items'] : array();
$offer_button    = wipe_clean_resolve_link( $section['offer_button'] ?? array() );
?>
<section class="services-benefits">
	<div class="_container">
		<div class="services-benefits__wrapper">
			<div class="services-benefits__intro">
				<div class="services-benefits__head ui-section-head">
					<?php if ( ! empty( $section['title'] ) ) : ?>
						<h2 class="ui-title services-benefits__title"><?php echo esc_html( $section['title'] ); ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $section['text'] ) ) : ?>
						<p class="ui-text services-benefits__text"><?php echo esc_html( $section['text'] ); ?></p>
					<?php endif; ?>
				</div>

				<div class="services-benefits__slider swiper" data-services-benefits-swiper>
					<div class="services-benefits__cards swiper-wrapper">
						<?php foreach ( $cards as $card ) : ?>
							<div class="services-benefits__slide swiper-slide">
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
			</div>

			<div class="services-benefits__details">
				<div class="services-benefits__offer">
					<div class="services-benefits__offer-content">
						<?php if ( ! empty( $section['offer_title'] ) ) : ?>
							<h3 class="ui-title services-benefits__offer-title"><?php echo esc_html( $section['offer_title'] ); ?></h3>
						<?php endif; ?>
						<?php if ( ! empty( $section['offer_text'] ) ) : ?>
							<p class="ui-text services-benefits__offer-text"><?php echo esc_html( $section['offer_text'] ); ?></p>
						<?php endif; ?>
					</div>

					<?php if ( ! empty( $offer_button['title'] ) ) : ?>
						<a class="ui-btn services-benefits__offer-action ui-btn--primary" href="<?php echo esc_url( $offer_button['url'] ?: '#' ); ?>"<?php echo $offer_button['target'] ? ' target="' . esc_attr( $offer_button['target'] ) . '"' : ''; ?>>
							<span class="ui-btn__content"><?php echo esc_html( $offer_button['title'] ); ?></span>
						</a>
					<?php endif; ?>
				</div>

				<div class="services-benefits__checklist">
					<?php if ( ! empty( $section['checklist_title'] ) ) : ?>
						<h3 class="ui-title services-benefits__checklist-title"><?php echo esc_html( $section['checklist_title'] ); ?></h3>
					<?php endif; ?>
					<?php if ( ! empty( $section['checklist_text'] ) ) : ?>
						<p class="ui-text services-benefits__checklist-text"><?php echo esc_html( $section['checklist_text'] ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $checklist_items ) ) : ?>
						<ul class="services-benefits__checklist-list">
							<?php foreach ( $checklist_items as $item ) : ?>
								<?php get_template_part( 'template-parts/components/check-list-item', null, array( 'item' => $item ) ); ?>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>

				<div class="services-benefits__visual" aria-hidden="true">
					<div class="services-benefits__visual-image">
						<?php echo wipe_clean_render_media( $section['visual_image'] ?? array(), array( 'alt' => '' ) ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
