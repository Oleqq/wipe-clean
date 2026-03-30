<?php
/**
 * Base ACF integration.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_acf_json_save_path() {
	return wipe_clean_asset_path( 'acf-json' );
}

function wipe_clean_filter_acf_save_json( $path ) {
	return wipe_clean_acf_json_save_path();
}
add_filter( 'acf/settings/save_json', 'wipe_clean_filter_acf_save_json' );

function wipe_clean_filter_acf_load_json( $paths ) {
	$paths[] = wipe_clean_acf_json_save_path();
	return array_values( array_unique( $paths ) );
}
add_filter( 'acf/settings/load_json', 'wipe_clean_filter_acf_load_json' );

function wipe_clean_acf_key( $suffix, $context = '' ) {
	return 'field_' . substr( md5( 'wipe_clean_' . $suffix . '_' . $context ), 0, 24 );
}

function wipe_clean_acf_get_callsite_context( $depth = 1 ) {
	$trace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, $depth + 2 );
	$frame = $trace[ $depth + 1 ] ?? array();
	$file  = isset( $frame['file'] ) ? (string) $frame['file'] : __FILE__;
	$line  = isset( $frame['line'] ) ? (string) $frame['line'] : '0';

	return $file . ':' . $line;
}

function wipe_clean_acf_field( $type, $name, $label, $args = array() ) {
	$key_context = isset( $args['wipe_clean_key_context'] ) && '' !== (string) $args['wipe_clean_key_context']
		? (string) $args['wipe_clean_key_context']
		: wipe_clean_acf_get_callsite_context( 1 );

	unset( $args['wipe_clean_key_context'] );

	return array_merge(
		array(
			'key'   => wipe_clean_acf_key( $name . '_' . $type, $key_context ),
			'label' => $label,
			'name'  => $name,
			'type'  => $type,
		),
		$args
	);
}

function wipe_clean_acf_repeater( $name, $label, $sub_fields, $args = array() ) {
	$key_context = wipe_clean_acf_get_callsite_context( 1 );

	return wipe_clean_acf_field(
		'repeater',
		$name,
		$label,
		array_merge(
			array(
				'layout'                 => 'row',
				'button_label'           => 'Добавить элемент',
				'sub_fields'             => $sub_fields,
				'wipe_clean_key_context' => $key_context,
			),
			$args
		)
	);
}

function wipe_clean_acf_tab( $label, $name ) {
	$key_context = wipe_clean_acf_get_callsite_context( 1 );

	return wipe_clean_acf_field(
		'tab',
		$name,
		$label,
		array(
			'placement'              => 'top',
			'endpoint'               => 0,
			'wipe_clean_key_context' => $key_context,
		)
	);
}
