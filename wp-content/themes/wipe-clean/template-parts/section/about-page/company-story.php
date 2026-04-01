<?php
/**
 * Company story section.
 *
 * @package wipe-clean
 */

$section = $args['section'] ?? wipe_clean_get_about_page_section_defaults( 'company_story' );
$body_id = 'company-story-body';
?>
<section class="company-story">
	<div class="_container">
		<div class="company-story__wrapper">
			<div class="company-story__content">
				<div class="company-story__head">
					<h2 class="ui-title company-story__title"><?php echo esc_html( $section['title'] ?? '' ); ?></h2>
				</div>

				<div class="company-story__copy" data-read-more>
					<div class="company-story__summary" data-read-more-summary>
						<p class="ui-text ui-text--sm company-story__summary-text">
							<?php echo esc_html( $section['summary_text'] ?? '' ); ?>
							<button class="company-story__more" type="button" data-read-more-toggle aria-expanded="false" aria-controls="<?php echo esc_attr( $body_id ); ?>">Ещё</button>
						</p>
					</div>

					<div class="company-story__body" id="<?php echo esc_attr( $body_id ); ?>" data-read-more-body>
						<?php if ( ! empty( $section['body_content'] ) ) : ?>
							<div class="company-story__text ui-text"><?php echo wipe_clean_format_rich_text( $section['body_content'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						<?php endif; ?>
						<?php if ( ! empty( $section['featured_content'] ) ) : ?>
							<div class="company-story__text company-story__text--featured ui-text"><?php echo wipe_clean_format_rich_text( $section['featured_content'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( ! empty( $section['note_text'] ) ) : ?>
					<div class="company-story__note">
						<p class="company-story__note-text"><?php echo esc_html( $section['note_text'] ); ?></p>
					</div>
				<?php endif; ?>
			</div>

			<div class="company-story__media">
				<?php echo wipe_clean_render_media( $section['image'] ?? array() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
	</div>
</section>
