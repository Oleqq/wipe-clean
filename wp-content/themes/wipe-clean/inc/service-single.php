<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/data/service-single.php';

function wipe_clean_render_service_single_sections( $post_id = 0 ) {
	$sections = wipe_clean_get_service_single_sections( $post_id );

	foreach ( $sections as $section ) {
		$layout = $section['acf_fc_layout'] ?? '';
		$slug   = str_replace( '_', '-', $layout );

		if ( ! $layout ) {
			continue;
		}

		if ( in_array( $layout, array( 'faq', 'contacts' ), true ) ) {
			get_template_part(
				'template-parts/section/front-page/' . $slug,
				null,
				array( 'section' => $section )
			);
			continue;
		}

		get_template_part(
			'template-parts/section/service-single/' . $slug,
			null,
			array( 'section' => $section )
		);
	}
}

function wipe_clean_render_service_single_content() {
	echo '<div class="service-page">';
	$post_id = function_exists( 'wipe_clean_get_current_service_post_id' ) ? wipe_clean_get_current_service_post_id() : get_the_ID();
	wipe_clean_render_service_single_sections( $post_id );
	echo '</div>';
}
