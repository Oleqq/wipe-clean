<?php
/**
 * Blog article content section.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section        = isset( $args['section'] ) && is_array( $args['section'] ) ? $args['section'] : array();
$content_markup = (string) ( $section['content_markup'] ?? '' );

if ( '' === trim( $content_markup ) ) {
	return;
}
?>
<section class="blog-article-content">
	<div class="_container">
		<div class="blog-article-content__wrapper">
			<article class="blog-article-content__article entry-content">
				<?php echo $content_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</article>
		</div>
	</div>
</section>
