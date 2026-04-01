<?php
/**
 * Default data and fallbacks for the reviews archive page.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_review_image' ) ) {
	function wipe_clean_review_image( $path, $alt = '', $width = 0, $height = 0 ) {
		if ( function_exists( 'wipe_clean_theme_image' ) ) {
			return wipe_clean_theme_image( $path, $alt, $width, $height );
		}

		return array(
			'path'   => ltrim( (string) $path, '/' ),
			'alt'    => (string) $alt,
			'width'  => (int) $width,
			'height' => (int) $height,
		);
	}
}

if ( ! function_exists( 'wipe_clean_create_default_message_review_item' ) ) {
	function wipe_clean_create_default_message_review_item( $index, $size, $desktop_column, $mobile_column, $mobile_order ) {
		$placeholder = wipe_clean_review_image(
			'static/images/section/message-reviews/message-review-placeholder.svg',
			'Скриншот рекомендации клиента из переписки №' . $index
		);

		return array(
			'seed_key'      => 'message-review-' . $index,
			'title'         => 'Рекомендация клиента №' . $index,
			'id'            => 'message-review-' . $index,
			'size'          => (string) $size,
			'desktopColumn' => (int) $desktop_column,
			'desktopOrder'  => (int) $index,
			'mobileColumn'  => (int) $mobile_column,
			'mobileOrder'   => (int) $mobile_order,
			'image'         => $placeholder,
			'lightboxImage' => $placeholder,
			'alt'           => 'Скриншот рекомендации клиента из переписки №' . $index,
			'caption'       => 'Рекомендация клиента из переписки №' . $index,
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_default_text_items' ) ) {
	function wipe_clean_get_reviews_default_text_items() {
		return array(
			array(
				'seed_key' => 'archive-review-text-1',
				'title'    => 'Анна Морозова',
				'author'   => 'Анна Морозова',
				'text'     => 'Очень понравился подход команды: приехали вовремя, уточнили детали по квартире и аккуратно прошли все зоны, о которых договаривались. После уборки дома действительно стало легче дышать, а кухня и ванная выглядели как после ремонта.',
				'rating'   => 5,
			),
			array(
				'seed_key' => 'archive-review-text-2',
				'title'    => 'Игорь Сафонов',
				'author'   => 'Игорь Сафонов',
				'text'     => 'Заказывали уборку перед приездом гостей и остались довольны. Специалисты работали спокойно, без суеты, при этом заметно внимательно относились к мелочам. Отдельно понравилось, что после себя все оставили в полном порядке.',
				'rating'   => 5,
			),
			array(
				'seed_key' => 'archive-review-text-3',
				'title'    => 'Марина К.',
				'author'   => 'Марина К.',
				'text'     => 'У нас дома маленький ребенок, поэтому особенно важно было подобрать деликатные средства. Команда все учла, объяснила, чем будет пользоваться, и действительно привела квартиру в порядок без резких запахов и лишней химии.',
				'rating'   => 5,
			),
			array(
				'seed_key' => 'archive-review-text-4',
				'title'    => 'Сергей Лаптев',
				'author'   => 'Сергей Лаптев',
				'text'     => 'После уборки офис стал выглядеть заметно свежее. Проработали полы, стеклянные перегородки, санузлы и кухонную зону. Руководству понравился результат, поэтому уже договорились о регулярном графике.',
				'rating'   => 5,
			),
			array(
				'seed_key' => 'archive-review-text-5',
				'title'    => 'Елена Жукова',
				'author'   => 'Елена Жукова',
				'text'     => 'Нужно было срочно привести квартиру в порядок после отъезда арендаторов. Работу выполнили быстро и без лишних вопросов, а самое главное, убрали даже те места, куда обычно никто не заглядывает. Результат реально порадовал.',
				'rating'   => 5,
			),
			array(
				'seed_key' => 'archive-review-text-6',
				'title'    => 'Артем Власов',
				'author'   => 'Артем Власов',
				'text'     => 'Понравилась коммуникация: до выезда все четко согласовали, а на месте сотрудники просто сделали свою работу качественно и спокойно. Уборка получилась именно такой, какую ожидали, без неприятных сюрпризов.',
				'rating'   => 5,
			),
			array(
				'seed_key' => 'archive-review-text-7',
				'title'    => 'Ольга Демина',
				'author'   => 'Ольга Демина',
				'text'     => 'Обращались после семейного праздника, когда квартира была в полном беспорядке. Команда аккуратно и методично все восстановила, а особенно впечатлило, как бережно отнеслись к текстилю и светлым поверхностям.',
				'rating'   => 5,
			),
			array(
				'seed_key' => 'archive-review-text-8',
				'title'    => 'Николай Рябов',
				'author'   => 'Николай Рябов',
				'text'     => 'Редко пишу отзывы, но здесь действительно есть за что поблагодарить. Все было организовано профессионально: без опозданий, без спешки и с очень достойным итоговым качеством. Будем обращаться повторно.',
				'rating'   => 5,
			),
			array(
				'seed_key' => 'archive-review-text-9',
				'title'    => 'Виктория Левина',
				'author'   => 'Виктория Левина',
				'text'     => 'Генеральная уборка прошла отлично. Особенно понравилось, что команда не пыталась сделать все формально, а реально проверяла результат по ходу работы. После окончания квартира выглядела свежо и ухоженно.',
				'rating'   => 5,
			),
			array(
				'seed_key' => 'archive-review-text-10',
				'title'    => 'Максим Орлов',
				'author'   => 'Максим Орлов',
				'text'     => 'Заказывали уборку дома после долгого отсутствия. Пыли было много, но ребята справились очень достойно. Понравилось уважительное отношение, аккуратная работа и то, что можно спокойно доверить объект без постоянного контроля.',
				'rating'   => 5,
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_default_video_items' ) ) {
	function wipe_clean_get_reviews_default_video_items() {
		$poster = wipe_clean_review_image(
			'static/images/section/video-reviews/video-review-poster-placeholder.svg',
			'Видео отзыв клиента о клининге'
		);
		$video  = 'static/images/section/gallery-preview/test-video.mp4';

		return array(
			array(
				'seed_key'    => 'archive-review-video-1',
				'title'       => 'Видео отзыв о клининге квартиры',
				'poster'      => $poster,
				'videoSrc'    => $video,
				'alt'         => 'Видео отзыв клиента о клининге квартиры',
				'caption'     => 'Видео отзыв клиента о работе ВАЙП-Клин',
				'videoWidth'  => 720,
				'videoHeight' => 1280,
			),
			array(
				'seed_key'    => 'archive-review-video-2',
				'title'       => 'Видео отзыв об уборке дома',
				'poster'      => $poster,
				'videoSrc'    => $video,
				'alt'         => 'Видео отзыв клиента об уборке дома',
				'caption'     => 'Отзыв клиента после уборки дома',
				'videoWidth'  => 720,
				'videoHeight' => 1280,
			),
			array(
				'seed_key'    => 'archive-review-video-3',
				'title'       => 'Видео отзыв о регулярном клининге',
				'poster'      => $poster,
				'videoSrc'    => $video,
				'alt'         => 'Видео отзыв клиента о регулярном клининге',
				'caption'     => 'Отзыв о регулярном обслуживании',
				'videoWidth'  => 720,
				'videoHeight' => 1280,
			),
			array(
				'seed_key'    => 'archive-review-video-4',
				'title'       => 'Видео отзыв об уборке офиса',
				'poster'      => $poster,
				'videoSrc'    => $video,
				'alt'         => 'Видео отзыв клиента об уборке офиса',
				'caption'     => 'Отзыв о клининге офисного пространства',
				'videoWidth'  => 720,
				'videoHeight' => 1280,
			),
			array(
				'seed_key'    => 'archive-review-video-5',
				'title'       => 'Видео отзыв о генеральной уборке',
				'poster'      => $poster,
				'videoSrc'    => $video,
				'alt'         => 'Видео отзыв клиента о генеральной уборке',
				'caption'     => 'Отзыв о генеральной уборке',
				'videoWidth'  => 720,
				'videoHeight' => 1280,
			),
			array(
				'seed_key'    => 'archive-review-video-6',
				'title'       => 'Видео отзыв о деликатной уборке',
				'poster'      => $poster,
				'videoSrc'    => $video,
				'alt'         => 'Видео отзыв клиента о деликатной уборке',
				'caption'     => 'Отзыв о деликатной уборке квартиры',
				'videoWidth'  => 720,
				'videoHeight' => 1280,
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_default_message_items' ) ) {
	function wipe_clean_get_reviews_default_message_items() {
		return array(
			wipe_clean_create_default_message_review_item( 1, 'tall', 1, 1, 3 ),
			wipe_clean_create_default_message_review_item( 2, 'tall', 1, 1, 7 ),
			wipe_clean_create_default_message_review_item( 3, 'short', 2, 1, 1 ),
			wipe_clean_create_default_message_review_item( 4, 'short', 2, 1, 5 ),
			wipe_clean_create_default_message_review_item( 5, 'tall', 2, 2, 10 ),
			wipe_clean_create_default_message_review_item( 6, 'tall', 3, 2, 2 ),
			wipe_clean_create_default_message_review_item( 7, 'short', 3, 2, 4 ),
			wipe_clean_create_default_message_review_item( 8, 'short', 3, 2, 8 ),
			wipe_clean_create_default_message_review_item( 9, 'short', 4, 2, 12 ),
			wipe_clean_create_default_message_review_item( 10, 'tall', 4, 2, 6 ),
			wipe_clean_create_default_message_review_item( 11, 'short', 1, 1, 9 ),
			wipe_clean_create_default_message_review_item( 12, 'tall', 2, 2, 14 ),
			wipe_clean_create_default_message_review_item( 13, 'short', 3, 1, 15 ),
			wipe_clean_create_default_message_review_item( 14, 'short', 4, 1, 17 ),
			wipe_clean_create_default_message_review_item( 15, 'tall', 1, 1, 11 ),
			wipe_clean_create_default_message_review_item( 16, 'short', 2, 1, 13 ),
			wipe_clean_create_default_message_review_item( 17, 'tall', 3, 2, 16 ),
			wipe_clean_create_default_message_review_item( 18, 'tall', 4, 2, 18 ),
		);
	}
}

if ( ! function_exists( 'wipe_clean_create_before_after_results_item' ) ) {
	function wipe_clean_create_before_after_results_item( $id, $before_path, $after_path, $alt, $control_label, $start, $mobile_start ) {
		return array(
			'id'            => (string) $id,
			'before_image'  => wipe_clean_review_image( $before_path, $alt ),
			'after_image'   => wipe_clean_review_image( $after_path, '' ),
			'alt'           => (string) $alt,
			'control_label' => (string) $control_label,
			'start'         => (float) $start,
			'mobile_start'  => (float) $mobile_start,
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_before_after_results_default_items' ) ) {
	function wipe_clean_get_before_after_results_default_items() {
		return array(
			wipe_clean_create_before_after_results_item( 'before-after-room-1', 'static/images/section/before-after-results/comparison-room-before.png', 'static/images/section/before-after-results/comparison-room-after.png', 'Сравнение комнаты до и после уборки', 'Сравнить комнату до и после уборки', 53.16, 52.78 ),
			wipe_clean_create_before_after_results_item( 'before-after-kitchen-1', 'static/images/section/before-after-results/comparison-kitchen-before.png', 'static/images/section/before-after-results/comparison-kitchen-after.png', 'Сравнение кухни до и после уборки', 'Сравнить кухню до и после уборки', 43.79, 52.78 ),
			wipe_clean_create_before_after_results_item( 'before-after-living-1', 'static/images/section/before-after-results/comparison-living-before.png', 'static/images/section/before-after-results/comparison-living-after.png', 'Сравнение гостиной до и после уборки', 'Сравнить гостиную до и после уборки', 76.81, 52.78 ),
			wipe_clean_create_before_after_results_item( 'before-after-living-2', 'static/images/section/before-after-results/comparison-living-before.png', 'static/images/section/before-after-results/comparison-living-after.png', 'Сравнение гостиной до и после уборки', 'Сравнить гостиную до и после уборки', 76.81, 52.78 ),
			wipe_clean_create_before_after_results_item( 'before-after-room-2', 'static/images/section/before-after-results/comparison-room-before.png', 'static/images/section/before-after-results/comparison-room-after.png', 'Сравнение комнаты до и после уборки', 'Сравнить комнату до и после уборки', 53.16, 52.78 ),
			wipe_clean_create_before_after_results_item( 'before-after-kitchen-2', 'static/images/section/before-after-results/comparison-kitchen-before.png', 'static/images/section/before-after-results/comparison-kitchen-after.png', 'Сравнение кухни до и после уборки', 'Сравнить кухню до и после уборки', 43.79, 52.78 ),
			wipe_clean_create_before_after_results_item( 'before-after-room-3', 'static/images/section/before-after-results/comparison-room-before.png', 'static/images/section/before-after-results/comparison-room-after.png', 'Сравнение комнаты до и после уборки', 'Сравнить комнату до и после уборки', 53.16, 52.78 ),
			wipe_clean_create_before_after_results_item( 'before-after-kitchen-3', 'static/images/section/before-after-results/comparison-kitchen-before.png', 'static/images/section/before-after-results/comparison-kitchen-after.png', 'Сравнение кухни до и после уборки', 'Сравнить кухню до и после уборки', 43.79, 52.78 ),
			wipe_clean_create_before_after_results_item( 'before-after-living-3', 'static/images/section/before-after-results/comparison-living-before.png', 'static/images/section/before-after-results/comparison-living-after.png', 'Сравнение гостиной до и после уборки', 'Сравнить гостиную до и после уборки', 76.81, 52.78 ),
			wipe_clean_create_before_after_results_item( 'before-after-living-4', 'static/images/section/before-after-results/comparison-living-before.png', 'static/images/section/before-after-results/comparison-living-after.png', 'Сравнение гостиной до и после уборки', 'Сравнить гостиную до и после уборки', 76.81, 52.78 ),
			wipe_clean_create_before_after_results_item( 'before-after-room-4', 'static/images/section/before-after-results/comparison-room-before.png', 'static/images/section/before-after-results/comparison-room-after.png', 'Сравнение комнаты до и после уборки', 'Сравнить комнату до и после уборки', 53.16, 52.78 ),
			wipe_clean_create_before_after_results_item( 'before-after-kitchen-4', 'static/images/section/before-after-results/comparison-kitchen-before.png', 'static/images/section/before-after-results/comparison-kitchen-after.png', 'Сравнение кухни до и после уборки', 'Сравнить кухню до и после уборки', 43.79, 52.78 ),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_before_after_results_section_defaults' ) ) {
	function wipe_clean_get_before_after_results_section_defaults() {
		return array(
			'acf_fc_layout'   => 'before_after_results',
			'title'           => "Результаты нашего\nклининга до и после",
			'button_label'    => 'Больше',
			'loading_label'   => 'Загрузка...',
			'initial_desktop' => 6,
			'initial_mobile'  => 3,
			'step_desktop'    => 6,
			'step_mobile'     => 3,
			'items'           => wipe_clean_get_before_after_results_default_items(),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_archive_default_sections_map' ) ) {
	function wipe_clean_get_reviews_archive_default_sections_map() {
		$faq          = function_exists( 'wipe_clean_get_front_page_section_defaults' )
			? wipe_clean_get_front_page_section_defaults( 'faq' )
			: array( 'acf_fc_layout' => 'faq' );
		$gallery      = function_exists( 'wipe_clean_get_front_page_section_defaults' )
			? wipe_clean_get_front_page_section_defaults( 'gallery_preview' )
			: array( 'acf_fc_layout' => 'gallery_preview' );
		$contacts     = function_exists( 'wipe_clean_get_front_page_section_defaults' )
			? wipe_clean_get_front_page_section_defaults( 'contacts' )
			: array( 'acf_fc_layout' => 'contacts' );

		return array(
			'reviews_archive'      => array(
				'acf_fc_layout'   => 'reviews_archive',
				'kicker'          => 'Отзывы и рецензии',
				'title'           => 'Отзывы наших клиентов',
				'top_action'      => wipe_clean_theme_link( '#popup-review', 'Оставить отзыв' ),
				'load_more_label' => 'Больше отзывов',
				'initial_desktop' => 8,
				'initial_mobile'  => 6,
				'step_desktop'    => 2,
				'step_mobile'     => 3,
				'items'           => wipe_clean_get_reviews_default_text_items(),
			),
			'video_reviews'       => array(
				'acf_fc_layout' => 'video_reviews',
				'title'         => "Видео Отзывы\nнаших клиентов",
				'top_action'    => wipe_clean_theme_link( '#popup-review', 'Оставить отзыв' ),
				'items'         => wipe_clean_get_reviews_default_video_items(),
			),
			'message_reviews'     => array(
				'acf_fc_layout'   => 'message_reviews',
				'title'           => 'Рекомендации наших клиентов из переписок',
				'button_label'    => 'Больше отзывов',
				'initial_desktop' => 10,
				'initial_mobile'  => 6,
				'step_desktop'    => 4,
				'step_mobile'     => 2,
				'items'           => wipe_clean_get_reviews_default_message_items(),
			),
			'before_after_results' => wipe_clean_get_before_after_results_section_defaults(),
			'faq'                => array_merge(
				(array) $faq,
				array(
					'acf_fc_layout' => 'faq',
				)
			),
			'gallery_preview'    => array_merge(
				(array) $gallery,
				array(
					'acf_fc_layout' => 'gallery_preview',
				)
			),
			'contacts'           => array_merge(
				(array) $contacts,
				array(
					'acf_fc_layout' => 'contacts',
				)
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_archive_layout_order' ) ) {
	function wipe_clean_get_reviews_archive_layout_order() {
		return array(
			'reviews_archive',
			'video_reviews',
			'message_reviews',
			'before_after_results',
			'faq',
			'gallery_preview',
			'contacts',
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_archive_section_defaults' ) ) {
	function wipe_clean_get_reviews_archive_section_defaults( $layout ) {
		$defaults_map = wipe_clean_get_reviews_archive_default_sections_map();

		return $defaults_map[ $layout ] ?? array(
			'acf_fc_layout' => $layout,
		);
	}
}
