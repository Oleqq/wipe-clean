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

function wipe_clean_make_admin_text_friendly( $text ) {
	if ( ! is_string( $text ) || '' === $text ) {
		return $text;
	}

	return strtr(
		$text,
		array(
			'Заполнить контентом'      => 'Заполнить готовым',
			'Обновить контент'         => 'Обновить содержимое',
			'Кикер'                    => 'Надзаголовок',
			'CTA-блок'                 => 'Блок с кнопками',
			'CTA секция'               => 'Блок с кнопками',
			'Финальный CTA'            => 'Финальный блок с кнопками',
			'Контент статьи'           => 'Текст статьи',
			'Fallback заголовок'       => 'Запасной заголовок',
			'Fallback описание'        => 'Запасное описание',
			'Fallback изображение'     => 'Запасное изображение',
			'Fallback контент'         => 'Запасной текст',
			'Заголовок внутри pop-up'  => 'Заголовок во всплывающем окне',
			'Изображение для pop-up'   => 'Изображение для всплывающего окна',
			'pop-up содержимое'        => 'содержимое всплывающего окна',
			'для pop-up не задано'     => 'для всплывающего окна не задано',
			'pop-up возьмёт'           => 'всплывающее окно возьмёт',
			'открывает pop-up прямо на архиве' => 'открывает всплывающее окно прямо в списке акций',
			'Alt / aria label'         => 'Описание изображения',
			'Изображение для lightbox' => 'Большое изображение',
			'Подпись range-control'    => 'Подпись ползунка',
			'Alt'                      => 'Описание изображения',
			'статической версии'      => 'готового варианта сайта',
			'статичной версии'        => 'готового варианта сайта',
			'статической страницы'    => 'готовой страницы',
			'статичной страницы'      => 'готовой страницы',
			'статической верстке'     => 'готовому макету',
			'статичной верстке'       => 'готовому макету',
			'из статики'              => 'из готового варианта сайта',
			'по статике'              => 'по готовому варианту сайта',
			'статичного пресета'      => 'готового заполнения',
			'готовые секции'          => 'готовые блоки',
			'секции страницы'         => 'блоки страницы',
			'секции самой страницы'   => 'блоки страницы',
			'fallback-статики'        => 'готового варианта сайта',
			'fallback-версии'         => 'готового варианта',
			'fallback-секции'         => 'готовые блоки',
			'fallback-верстке'        => 'готовому макету',
			'fallback-контент'        => 'запасной текст',
			'fallback-контента'       => 'запасного текста',
			'fallback, если'          => 'запасной вариант, если',
			'fallback и для'          => 'как запасной вариант и для',
			'fallback'                => 'запасной вариант',
			'готовым контентом'       => 'готовым содержимым',
			'готовый контент'         => 'готовое содержимое',
			'flexible-блоки'          => 'блоки страницы',
			'flexible-блоков'         => 'блоков страницы',
			'flexible-секции'         => 'блоки страницы',
			'flexible-секций'         => 'блоков страницы',
			'ACF-секции'              => 'блоки страницы',
			'ACF-секций'              => 'блоков страницы',
			'repeater-списком'        => 'списком',
			'repeater-списка'         => 'списка',
			'repeater'                => 'список',
			'dropdown-подменю'        => 'выпадающее подменю',
			'dropdown'                => 'выпадающее меню',
			'pop-up'                  => 'всплывающее окно',
			'popup'                   => 'всплывающее окно',
			'preview-полях'           => 'отдельных полях',
			'preview-поля'            => 'отдельные поля',
			'preview'                 => 'превью',
			'frontend'                => 'сайте',
			'title, excerpt, featured image и дату публикации' => 'название, краткое описание, изображение записи и дату публикации',
			'title, excerpt, featured image' => 'название, краткое описание и изображение записи',
			'заголовок, excerpt, дату и featured image' => 'заголовок, краткое описание, дату и изображение записи',
			'заголовок, excerpt, дата и миниатюра' => 'заголовок, краткое описание, дата и изображение записи',
			'featured image'          => 'изображение записи',
			'excerpt'                 => 'краткое описание',
			'URL видео'               => 'Ссылка на видео',
			'Внешний URL видео'       => 'Внешняя ссылка на видео',
			'URL'                     => 'ссылка',
			'CPT '                    => '',
			'single-страницы'         => 'страницы',
			'single-странице'         => 'странице',
			'single-страница'         => 'страница',
			'layout'                  => 'блок',
			'tooltip'                 => 'подсказка',
			'Кнопка desktop'          => 'Кнопка на компьютере',
			'Кнопка mobile'           => 'Кнопка на телефоне',
			'Только mobile'           => 'Только на телефоне',
			'Текст для mobile'        => 'Текст для телефона',
			'Карточек сразу на desktop' => 'Карточек сразу на компьютере',
			'Карточек сразу на mobile' => 'Карточек сразу на телефоне',
			'Сколько карточек показывать на desktop' => 'Сколько карточек показывать на компьютере',
			'Сколько карточек показывать на mobile' => 'Сколько карточек показывать на телефоне',
			'Шаг загрузки на desktop' => 'Сколько добавлять на компьютере',
			'Шаг загрузки на mobile'  => 'Сколько добавлять на телефоне',
			'Позиция стартового слайдера desktop' => 'Начальное положение ползунка на компьютере',
			'Позиция стартового слайдера mobile'  => 'Начальное положение ползунка на телефоне',
			' на desktop'             => ' на компьютере',
			' на mobile'              => ' на телефоне',
			' для mobile'             => ' для телефона',
			' из fallback-версии'     => ' из готового варианта',
			' из fallback-статики'    => ' из готового варианта сайта',
			'read more'               => 'раскрытием текста',
			'checklist'               => 'список',
		)
	);
}

function wipe_clean_normalize_acf_ui_copy( $value, $context_key = '' ) {
	if ( is_array( $value ) ) {
		if ( 'choices' === $context_key ) {
			foreach ( $value as $choice_key => $choice_label ) {
				if ( is_string( $choice_label ) ) {
					$value[ $choice_key ] = wipe_clean_make_admin_text_friendly( $choice_label );
				}
			}

			return $value;
		}

		foreach ( $value as $item_key => $item_value ) {
			$value[ $item_key ] = wipe_clean_normalize_acf_ui_copy( $item_value, (string) $item_key );
		}

		return $value;
	}

	if ( ! is_string( $value ) ) {
		return $value;
	}

	if ( ! in_array( $context_key, array( 'label', 'instructions', 'message', 'button_label', 'title', 'page_title', 'menu_title', 'description' ), true ) ) {
		return $value;
	}

	return wipe_clean_make_admin_text_friendly( $value );
}

function wipe_clean_get_acf_field_group_post_id( $group_key ) {
	$posts = get_posts(
		array(
			'post_type'      => 'acf-field-group',
			'name'           => $group_key,
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		)
	);

	if ( empty( $posts ) ) {
		return 0;
	}

	return (int) $posts[0];
}

function wipe_clean_get_acf_field_group_schema_option_key( $group_key ) {
	return 'wipe_clean_acf_schema_hash_' . md5( (string) $group_key );
}

function wipe_clean_sync_acf_field_group( $field_group ) {
	if ( ! function_exists( 'acf_import_field_group' ) || ! is_array( $field_group ) ) {
		return false;
	}

	$field_group = wipe_clean_normalize_acf_ui_copy( $field_group );
	$field_group = wp_parse_args(
		$field_group,
		array(
			'key'    => '',
			'title'  => '',
			'fields' => array(),
			'active' => true,
		)
	);

	if ( '' === (string) $field_group['key'] || '' === (string) $field_group['title'] ) {
		return false;
	}

	$group_id    = wipe_clean_get_acf_field_group_post_id( $field_group['key'] );
	$schema_hash = md5( wp_json_encode( $field_group ) );
	$option_key  = wipe_clean_get_acf_field_group_schema_option_key( $field_group['key'] );

	if ( $group_id ) {
		$field_group['ID'] = $group_id;
	}

	if ( $group_id && get_option( $option_key ) === $schema_hash ) {
		return true;
	}

	$imported = acf_import_field_group( $field_group );

	if ( is_array( $imported ) && ! empty( $imported['key'] ) ) {
		update_option( $option_key, $schema_hash, false );
		return true;
	}

	return false;
}
