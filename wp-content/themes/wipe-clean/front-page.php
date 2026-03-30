<?php
/**
 * Front page template.
 *
 * @package wipe-clean
 */

get_header();
?>

<main id="primary" class="main site-main">
	<?php wipe_clean_render_front_page_sections(); ?>
</main>

<?php
get_footer();
