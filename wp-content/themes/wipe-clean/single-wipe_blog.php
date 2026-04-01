<?php
/**
 * Blog single template for CPT.
 *
 * @package wipe-clean
 */

get_header();
?>
<main id="primary" class="main site-main">
	<?php wipe_clean_render_blog_single_content( function_exists( 'wipe_clean_get_current_blog_post_id' ) ? wipe_clean_get_current_blog_post_id() : get_the_ID() ); ?>
</main>
<?php
get_footer();
