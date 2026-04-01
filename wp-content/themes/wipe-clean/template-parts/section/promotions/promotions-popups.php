<?php
/**
 * Promotions popups section.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items = isset( $args['items'] ) && is_array( $args['items'] ) ? array_values( $args['items'] ) : array();

if ( empty( $items ) ) {
	return;
}
?>
<?php foreach ( $items as $item ) : ?>
	<?php
	$popup_id       = trim( (string) ( $item['popupId'] ?? '' ) );
	$title          = trim( (string) ( $item['popupTitle'] ?? $item['title'] ?? '' ) );
	$image          = $item['popupImage'] ?? ( $item['image'] ?? array() );
	$image_alt      = trim( (string) ( $item['popupImageAlt'] ?? $item['imageAlt'] ?? $title ) );
	$paragraphs     = array_values( array_filter( (array) ( $item['popupText'] ?? array() ), 'strlen' ) );
	$conditions     = array_values( array_filter( (array) ( $item['popupConditions'] ?? array() ), 'strlen' ) );
	$title_id       = '' !== $popup_id ? $popup_id . '-title' : '';
	$description_id = '' !== $popup_id ? $popup_id . '-description' : '';

	if ( '' === $popup_id ) {
		continue;
	}
	?>
	<div class="popup" id="<?php echo esc_attr( $popup_id ); ?>" data-popup="<?php echo esc_attr( $popup_id ); ?>" data-lenis-prevent hidden aria-hidden="true">
		<button class="popup__backdrop" type="button" tabindex="-1" aria-hidden="true" data-popup-close></button>
		<div class="popup__dialog popup__dialog--promotion" data-lenis-prevent role="dialog" aria-modal="true"<?php echo '' !== $title_id ? ' aria-labelledby="' . esc_attr( $title_id ) . '"' : ''; ?><?php echo '' !== $description_id ? ' aria-describedby="' . esc_attr( $description_id ) . '"' : ''; ?>>
			<button class="popup__close" type="button" aria-label="Закрыть попап" data-popup-close>
				<svg class="popup__close-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
					<path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
					<path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
				</svg>
			</button>
			<div class="promotion-popup popup-form popup-form--promotion">
				<div class="promotion-popup__media">
					<?php
					echo wipe_clean_render_media( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						$image,
						array(
							'alt' => $image_alt,
						)
					);
					?>
				</div>
				<div class="promotion-popup__content">
					<div class="popup-form__head">
						<h2 class="popup-form__title"<?php echo '' !== $title_id ? ' id="' . esc_attr( $title_id ) . '"' : ''; ?>><?php echo esc_html( $title ); ?></h2>
						<?php foreach ( $paragraphs as $index => $paragraph ) : ?>
							<div class="popup-form__text"<?php echo 0 === $index && '' !== $description_id ? ' id="' . esc_attr( $description_id ) . '"' : ''; ?>>
								<?php echo wp_kses_post( wipe_clean_format_rich_text( (string) $paragraph ) ); ?>
							</div>
						<?php endforeach; ?>
					</div>

					<?php if ( ! empty( $conditions ) ) : ?>
						<div class="promotion-popup__conditions">
							<p class="promotion-popup__conditions-title">Условия:</p>
							<ul class="promotion-popup__conditions-list">
								<?php foreach ( $conditions as $condition ) : ?>
									<?php
									get_template_part(
										'template-parts/components/check-list-item',
										null,
										array(
											'item' => array(
												'text' => $condition,
											),
										)
									);
									?>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>

					<?php
					echo wipe_clean_render_managed_cf7_form(
						'promotion_popup',
						array(
							'render_context' => array(
								'form_context_label'   => 'Попап акции: ' . $title,
								'form_context_page'    => 'Акции',
								'form_context_surface' => 'Попап акции',
								'promotion_title'      => $title,
								'popup_id'             => $popup_id,
							),
						)
					); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</div>
			</div>
		</div>
	</div>
<?php endforeach; ?>
