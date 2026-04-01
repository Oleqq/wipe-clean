<?php
/**
 * Template Name: Настройки архива услуг
 * Template Post Type: page
 *
 * @package wipe-clean
 */

get_header();
?>

<main id="primary" class="main site-main">
	<div class="services-page">
		<?php wipe_clean_render_services_page_sections(); ?>
	</div>
</main>

<?php
get_footer();
