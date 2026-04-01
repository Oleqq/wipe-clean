<?php
/**
 * Promotions archive template.
 *
 * @package wipe-clean
 */

get_header();
?>
<main id="primary" class="main site-main">
	<?php wipe_clean_render_promotions_archive_content(); ?>
</main>
<?php wipe_clean_render_promotions_archive_popups(); ?>
<?php
get_footer();
