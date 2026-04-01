<?php
/**
 * Document page content section.
 *
 * @package wipe-clean
 */

$title     = (string) ( $args['title'] ?? '' );
$body_html = (string) ( $args['body_html'] ?? '' );
?>
<section class="document-content">
	<div class="_container">
		<div class="document-content__wrapper">
			<h1 class="ui-title ui-title--hero document-content__title"><?php echo esc_html( $title ); ?></h1>
			<div class="document-content__body entry-content">
				<?php echo wp_kses_post( $body_html ); ?>
			</div>
		</div>
	</div>
</section>
