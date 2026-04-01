<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section       = isset( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : array();
$reasons       = ! empty( $section['reasons'] ) && is_array( $section['reasons'] ) ? array_values( $section['reasons'] ) : array();
$summary_id    = 'service-purpose-body-' . wp_unique_id();
$summary_label = 'Ещё';
$close_label   = 'Свернуть';
?>
<section class="service-purpose">
	<div class="_container">
		<div class="service-purpose__wrapper">
			<div class="service-purpose__content">
				<div class="service-purpose__head">
					<?php if ( ! empty( $section['title'] ) ) : ?>
						<h2 class="ui-title service-purpose__title"><?php echo esc_html( $section['title'] ); ?></h2>
					<?php endif; ?>
				</div>

				<div class="service-purpose__copy" data-read-more>
					<?php if ( ! empty( $section['summary_text'] ) ) : ?>
						<div class="service-purpose__summary" data-read-more-summary>
							<p class="ui-text ui-text--sm service-purpose__summary-text">
								<?php echo esc_html( $section['summary_text'] ); ?>
								<button class="service-purpose__more" type="button" data-read-more-toggle data-read-more-open-label="<?php echo esc_attr( $summary_label ); ?>" data-read-more-close-label="<?php echo esc_attr( $close_label ); ?>" aria-expanded="false" aria-controls="<?php echo esc_attr( $summary_id ); ?>">
									<?php echo esc_html( $summary_label ); ?>
								</button>
							</p>
						</div>
					<?php endif; ?>

					<div id="<?php echo esc_attr( $summary_id ); ?>" class="service-purpose__body" data-read-more-body>
						<?php if ( ! empty( $section['intro_text'] ) ) : ?>
							<p class="ui-text service-purpose__text"><?php echo esc_html( $section['intro_text'] ); ?></p>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( ! empty( $section['note_text'] ) ) : ?>
					<div class="service-purpose__note">
						<p class="service-purpose__note-text"><?php echo esc_html( $section['note_text'] ); ?></p>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $section['lead_text'] ) ) : ?>
					<div class="service-purpose__lead">
						<p class="ui-text service-purpose__lead-text"><?php echo esc_html( $section['lead_text'] ); ?></p>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $reasons ) ) : ?>
					<ul class="service-purpose__list">
						<?php foreach ( $reasons as $reason ) : ?>
							<?php
							get_template_part(
								'template-parts/components/check-list-item',
								null,
								array(
									'item' => array(
										'text'        => (string) ( $reason['text'] ?? '' ),
										'mobile_text' => (string) ( $reason['mobile_text'] ?? '' ),
										'icon'        => $reason['icon'] ?? array(),
									),
								)
							);
							?>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( ! empty( $section['ending_text'] ) ) : ?>
					<div class="service-purpose__ending">
						<p class="ui-text service-purpose__text"><?php echo esc_html( $section['ending_text'] ); ?></p>
					</div>
				<?php endif; ?>
			</div>

			<div class="service-purpose__media">
				<?php echo wipe_clean_render_media( $section['image'] ?? array(), array( 'alt' => (string) ( $section['title'] ?? '' ) ) ); ?>
			</div>
		</div>
	</div>
</section>
