<?php
/**
 * Company highlight section for prices page.
 *
 * @package wipe-clean
 */

$section = $args['section'] ?? wipe_clean_get_prices_page_section_defaults( 'company_highlight' );
$body_id = 'prices-company-highlight-body';
?>
<section class="company-highlight">
	<div class="_container">
		<div class="company-highlight__wrapper">
			<div class="company-highlight__content">
				<div class="company-highlight__head">
					<h2 class="ui-title company-highlight__title"><?php echo esc_html( $section['title'] ?? '' ); ?></h2>
				</div>

				<div class="company-highlight__copy" data-read-more>
					<div class="company-highlight__summary" data-read-more-summary>
						<p class="ui-text ui-text--sm company-highlight__summary-text">
							<?php echo esc_html( $section['summary_text'] ?? '' ); ?>
							<button class="company-highlight__more" type="button" data-read-more-toggle aria-expanded="false" aria-controls="<?php echo esc_attr( $body_id ); ?>">Ещё</button>
						</p>
					</div>

					<div class="company-highlight__body" id="<?php echo esc_attr( $body_id ); ?>" data-read-more-body>
						<?php echo wipe_clean_format_rich_text( $section['body_content'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>

				<?php if ( ! empty( $section['note_text'] ) ) : ?>
					<div class="company-highlight__note">
						<p class="company-highlight__note-text"><?php echo esc_html( $section['note_text'] ); ?></p>
					</div>
				<?php endif; ?>
			</div>

			<div class="company-highlight__media">
				<div class="company-highlight__media-image">
					<?php echo wipe_clean_render_media( $section['image'] ?? array() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</div>
	</div>
</section>
