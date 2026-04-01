<?php
/**
 * Shared site popups.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_object_id = (int) get_queried_object_id();
$current_page_label = 'Сайт';

if ( is_front_page() ) {
	$current_page_label = 'Главная';
} elseif ( is_404() ) {
	$current_page_label = '404';
} elseif ( is_post_type_archive( 'wipe_service' ) || is_page_template( 'template-services-page.php' ) ) {
	$current_page_label = 'Услуги';
} elseif ( is_post_type_archive( 'wipe_review' ) || is_page_template( 'template-reviews-page.php' ) ) {
	$current_page_label = 'Отзывы';
} elseif ( is_post_type_archive( 'wipe_promotion' ) ) {
	$current_page_label = 'Акции';
} elseif ( is_page() && $current_object_id ) {
	$current_page_label = trim( (string) get_the_title( $current_object_id ) );
} elseif ( is_singular() && $current_object_id ) {
	$current_page_label = trim( (string) get_the_title( $current_object_id ) );
}

if ( '' === $current_page_label ) {
	$current_page_label = 'Сайт';
}

$popup_order_visual = wipe_clean_asset_uri( 'static/images/section/popup-system/popup-order-service-visual.png' );
$popup_calc_visual  = wipe_clean_asset_uri( 'static/images/section/popup-system/popup-calc-visual.png' );
$popup_question_visual = wipe_clean_asset_uri( 'static/images/section/popup-system/popup-question-visual.png' );
?>
<div class="popup" id="popup-review" data-popup="popup-review" data-lenis-prevent hidden aria-hidden="true">
	<button class="popup__backdrop" type="button" tabindex="-1" aria-hidden="true" data-popup-close></button>
	<div class="popup__dialog popup__dialog--review" data-lenis-prevent role="dialog" aria-modal="true" aria-labelledby="popup-review-title" aria-describedby="popup-review-description">
		<button class="popup__close" type="button" aria-label="Закрыть окно" data-popup-close>
			<svg class="popup__close-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
				<path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
				<path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
			</svg>
		</button>
		<div class="popup-form popup-form--review">
			<div class="popup-form__head">
				<h2 class="popup-form__title" id="popup-review-title">Оставьте свой отзыв о<br>работе компании ВАЙП-Клин</h2>
				<p class="popup-form__text" id="popup-review-description">Для нас будет очень ценно, если вы поделитесь впечатлением о нашей работе. Фото или видео тоже можно приложить: заявка сохранится в разделе отзывов как черновик для проверки.</p>
			</div>
			<?php
			echo wipe_clean_render_managed_cf7_form(
				'popup_review',
				array(
					'render_context' => array(
						'form_context_label'   => 'Попап отзыва',
						'form_context_page'    => $current_page_label,
						'form_context_surface' => 'Попап',
					),
				)
			);
			?>
		</div>
	</div>
</div>

<div class="popup" id="popup-order-service" data-popup="popup-order-service" data-lenis-prevent hidden aria-hidden="true">
	<button class="popup__backdrop" type="button" tabindex="-1" aria-hidden="true" data-popup-close></button>
	<div class="popup__dialog popup__dialog--wide popup__dialog--service" data-lenis-prevent role="dialog" aria-modal="true" aria-labelledby="popup-order-service-title" aria-describedby="popup-order-service-description">
		<button class="popup__close" type="button" aria-label="Закрыть окно" data-popup-close>
			<svg class="popup__close-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
				<path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
				<path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
			</svg>
		</button>
		<div class="popup-form popup-form--split popup-form--service">
			<div class="popup-form__content">
				<div class="popup-form__head">
					<h2 class="popup-form__title" id="popup-order-service-title">Хотите заказать клининг<br>домой? Оставляйте заявку</h2>
					<p class="popup-form__text" id="popup-order-service-description">Заполните форму ниже, и менеджер свяжется с вами в ближайшее время, чтобы уточнить детали уборки и подобрать удобный формат услуги.</p>
				</div>
				<?php
				echo wipe_clean_render_managed_cf7_form(
					'popup_order_service',
					array(
						'render_context' => array(
							'form_context_label'   => 'Попап заказа услуги',
							'form_context_page'    => $current_page_label,
							'form_context_surface' => 'Попап',
						),
					)
				);
				?>
			</div>
			<div class="popup-form__visual popup-form__visual--order" aria-hidden="true">
				<img src="<?php echo esc_url( $popup_order_visual ); ?>" alt="" loading="lazy" decoding="async">
			</div>
		</div>
	</div>
</div>

<div class="popup" id="popup-calc" data-popup="popup-calc" data-lenis-prevent hidden aria-hidden="true">
	<button class="popup__backdrop" type="button" tabindex="-1" aria-hidden="true" data-popup-close></button>
	<div class="popup__dialog popup__dialog--wide popup__dialog--calc" data-lenis-prevent role="dialog" aria-modal="true" aria-labelledby="popup-calc-title" aria-describedby="popup-calc-description">
		<button class="popup__close" type="button" aria-label="Закрыть окно" data-popup-close>
			<svg class="popup__close-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
				<path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
				<path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
			</svg>
		</button>
		<div class="popup-form popup-form--split popup-form--calculator">
			<div class="popup-form__content">
				<div class="popup-form__head">
					<h2 class="popup-form__title" id="popup-calc-title">Узнайте стоимость уборки</h2>
					<p class="popup-form__text" id="popup-calc-description">Заполните форму ниже, чтобы мы оперативно посчитали стоимость и связались с вами с готовым расчётом.</p>
				</div>
				<?php
				echo wipe_clean_render_managed_cf7_form(
					'popup_calc',
					array(
						'render_context' => array(
							'form_context_label'   => 'Попап расчёта стоимости',
							'form_context_page'    => $current_page_label,
							'form_context_surface' => 'Попап',
						),
					)
				);
				?>
			</div>
			<div class="popup-form__visual popup-form__visual--calc" aria-hidden="true">
				<img src="<?php echo esc_url( $popup_calc_visual ); ?>" alt="" loading="lazy" decoding="async">
			</div>
		</div>
	</div>
</div>

<div class="popup" id="popup-question" data-popup="popup-question" data-lenis-prevent hidden aria-hidden="true">
	<button class="popup__backdrop" type="button" tabindex="-1" aria-hidden="true" data-popup-close></button>
	<div class="popup__dialog popup__dialog--wide popup__dialog--question" data-lenis-prevent role="dialog" aria-modal="true" aria-labelledby="popup-question-title" aria-describedby="popup-question-description">
		<button class="popup__close" type="button" aria-label="Закрыть окно" data-popup-close>
			<svg class="popup__close-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
				<path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
				<path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
			</svg>
		</button>
		<div class="popup-form popup-form--split popup-form--question">
			<div class="popup-form__content">
				<div class="popup-form__head">
					<h2 class="popup-form__title" id="popup-question-title">У вас остались вопросы?</h2>
					<p class="popup-form__text" id="popup-question-description">Напишите свой вопрос в форме ниже. Мы свяжемся с вами и поможем по услугам, стоимости и организации уборки.</p>
				</div>
				<?php
				echo wipe_clean_render_managed_cf7_form(
					'popup_question',
					array(
						'render_context' => array(
							'form_context_label'   => 'Попап вопроса',
							'form_context_page'    => $current_page_label,
							'form_context_surface' => 'Попап',
						),
					)
				);
				?>
			</div>
			<div class="popup-form__visual popup-form__visual--question" aria-hidden="true">
				<img src="<?php echo esc_url( $popup_question_visual ); ?>" alt="" loading="lazy" decoding="async">
			</div>
		</div>
	</div>
</div>

<div class="popup" id="popup-status-success" data-popup="popup-status-success" data-lenis-prevent hidden aria-hidden="true">
	<button class="popup__backdrop" type="button" tabindex="-1" aria-hidden="true" data-popup-close></button>
	<div class="popup__dialog popup__dialog--status popup__dialog--status-success" data-lenis-prevent role="dialog" aria-modal="true" aria-labelledby="popup-status-success-title" aria-describedby="popup-status-success-description">
		<button class="popup__close" type="button" aria-label="Закрыть окно" data-popup-close>
			<svg class="popup__close-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
				<path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
				<path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
			</svg>
		</button>
		<div class="popup-status popup-status--success">
			<div class="popup-status__badge">
				<svg class="popup-status__badge-icon" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
					<path d="M9.62499 21.55C9.2601 19.9063 9.31613 18.1971 9.78789 16.5808C10.2596 14.9646 11.1319 13.4936 12.3237 12.3043C13.5155 11.115 14.9883 10.2459 16.6055 9.77749C18.2228 9.30911 19.9321 9.25666 21.575 9.625C22.4793 8.21075 23.725 7.0469 25.1974 6.24071C26.6697 5.43453 28.3214 5.01196 30 5.01196C31.6786 5.01196 33.3302 5.43453 34.8026 6.24071C36.275 7.0469 37.5207 8.21075 38.425 9.625C40.0704 9.25506 41.7827 9.30727 43.4025 9.77678C45.0223 10.2463 46.4971 11.1179 47.6896 12.3104C48.8821 13.5029 49.7537 14.9777 50.2232 16.5975C50.6927 18.2173 50.7449 19.9296 50.375 21.575C51.7892 22.4793 52.9531 23.725 53.7593 25.1974C54.5655 26.6697 54.988 28.3214 54.988 30C54.988 31.6786 54.5655 33.3303 53.7593 34.8026C52.9531 36.275 51.7892 37.5207 50.375 38.425C50.7433 40.0679 50.6909 41.7772 50.2225 43.3945C49.7541 45.0117 48.885 46.4845 47.6957 47.6763C46.5064 48.8681 45.0354 49.7403 43.4191 50.2121C41.8029 50.6839 40.0937 50.7399 38.45 50.375C37.5469 51.7947 36.3002 52.9635 34.8253 53.7733C33.3504 54.5831 31.6951 55.0076 30.0125 55.0076C28.3299 55.0076 26.6746 54.5831 25.1997 53.7733C23.7248 52.9635 22.4781 51.7947 21.575 50.375C19.9321 50.7433 18.2228 50.6909 16.6055 50.2225C14.9883 49.7541 13.5155 48.885 12.3237 47.6957C11.1319 46.5064 10.2596 45.0354 9.78789 43.4192C9.31613 41.8029 9.2601 40.0937 9.62499 38.45C8.19989 37.5481 7.02603 36.3004 6.21262 34.823C5.3992 33.3456 4.97266 31.6865 4.97266 30C4.97266 28.3135 5.3992 26.6544 6.21262 25.177C7.02603 23.6996 8.19989 22.4519 9.62499 21.55Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
					<path d="M22.5 30L27.5 35L37.5 25" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
				</svg>
			</div>
			<div class="popup-status__content">
				<h2 class="popup-status__title" id="popup-status-success-title">Ваша заявка принята</h2>
				<p class="popup-status__text" id="popup-status-success-description">Мы получили заявку и свяжемся с вами в ближайшее время.</p>
			</div>
		</div>
	</div>
</div>

<div class="popup" id="popup-status-error" data-popup="popup-status-error" data-lenis-prevent hidden aria-hidden="true">
	<button class="popup__backdrop" type="button" tabindex="-1" aria-hidden="true" data-popup-close></button>
	<div class="popup__dialog popup__dialog--status popup__dialog--status-error" data-lenis-prevent role="dialog" aria-modal="true" aria-labelledby="popup-status-error-title" aria-describedby="popup-status-error-description">
		<button class="popup__close" type="button" aria-label="Закрыть окно" data-popup-close>
			<svg class="popup__close-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
				<path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
				<path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
			</svg>
		</button>
		<div class="popup-status popup-status--error">
			<div class="popup-status__badge">
				<svg class="popup-status__badge-icon" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
					<path d="M54.3251 45.0001L34.3251 10.0001C33.889 9.23058 33.2566 8.59054 32.4924 8.14525C31.7282 7.69995 30.8595 7.46533 29.9751 7.46533C29.0906 7.46533 28.222 7.69995 27.4578 8.14525C26.6936 8.59054 26.0612 9.23058 25.6251 10.0001L5.62507 45.0001C5.18427 45.7635 4.95314 46.6298 4.95509 47.5113C4.95704 48.3929 5.19201 49.2582 5.63618 50.0196C6.08035 50.7811 6.71794 51.4115 7.48431 51.8471C8.25067 52.2828 9.11859 52.508 10.0001 52.5001H50.0001C50.8773 52.4992 51.7389 52.2675 52.4983 51.8283C53.2577 51.3891 53.8881 50.7578 54.3263 49.9979C54.7646 49.2379 54.9952 48.376 54.9949 47.4988C54.9947 46.6215 54.7637 45.7598 54.3251 45.0001Z" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
					<path d="M30 22.5V32.5" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
					<path d="M30 42.5H30.03" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
				</svg>
			</div>
			<div class="popup-status__content">
				<h2 class="popup-status__title" id="popup-status-error-title">Не удалось отправить форму</h2>
				<p class="popup-status__text" id="popup-status-error-description">Проверьте заполнение полей и попробуйте ещё раз.</p>
			</div>
		</div>
	</div>
</div>
