<?php
/**
 * Admin hub for leads and managed forms.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wipe_clean_get_leads_hub_slug() {
	return 'wipe-clean-leads';
}

function wipe_clean_get_leads_hub_url( $args = array() ) {
	return add_query_arg(
		(array) $args,
		admin_url( 'admin.php?page=' . wipe_clean_get_leads_hub_slug() )
	);
}

function wipe_clean_is_leads_hub_page() {
	return is_admin() && wipe_clean_get_leads_hub_slug() === sanitize_key( (string) ( $_GET['page'] ?? '' ) );
}

function wipe_clean_register_leads_settings() {
	register_setting(
		'wipe_clean_leads_settings',
		wipe_clean_get_leads_settings_option_name(),
		'wipe_clean_sanitize_leads_settings'
	);
}
add_action( 'admin_init', 'wipe_clean_register_leads_settings' );

function wipe_clean_register_leads_hub_menu() {
	add_menu_page(
		'Заявки',
		'Заявки',
		'manage_options',
		wipe_clean_get_leads_hub_slug(),
		'wipe_clean_render_leads_hub_page',
		'dashicons-email-alt2',
		27
	);
}
add_action( 'admin_menu', 'wipe_clean_register_leads_hub_menu' );

function wipe_clean_enqueue_leads_hub_assets() {
	if ( ! current_user_can( 'manage_options' ) || ! wipe_clean_is_leads_hub_page() ) {
		return;
	}

	$relative_path = 'static/css/admin-leads-hub.css';
	$file_path     = trailingslashit( get_template_directory() ) . $relative_path;

	if ( ! file_exists( $file_path ) ) {
		return;
	}

	wp_enqueue_style(
		'wipe-clean-leads-hub-fonts',
		'https://fonts.googleapis.com/css2?family=Golos+Text:wght@400..900&family=Manrope:wght@200..800&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'wipe-clean-leads-hub',
		trailingslashit( get_template_directory_uri() ) . $relative_path,
		array( 'wipe-clean-leads-hub-fonts' ),
		(string) filemtime( $file_path )
	);
}
add_action( 'admin_enqueue_scripts', 'wipe_clean_enqueue_leads_hub_assets' );

function wipe_clean_handle_leads_hub_actions() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$page   = sanitize_key( (string) ( $_GET['page'] ?? '' ) );
	$action = sanitize_key( (string) ( $_GET['wipe_action'] ?? '' ) );

	if ( wipe_clean_get_leads_hub_slug() !== $page || '' === $action ) {
		return;
	}

	if ( 'sync_forms' === $action ) {
		check_admin_referer( 'wipe_clean_sync_forms' );
		wipe_clean_sync_managed_cf7_forms( true );

		wp_safe_redirect( wipe_clean_get_leads_hub_url( array( 'synced' => 1 ) ) );
		exit;
	}
}
add_action( 'admin_init', 'wipe_clean_handle_leads_hub_actions' );

function wipe_clean_leads_table_exists() {
	global $wpdb;

	$table_name = wipe_clean_get_leads_table_name();
	$previous    = $wpdb->suppress_errors( true );
	$wpdb->get_var( "SELECT 1 FROM {$table_name} LIMIT 1" );
	$last_error = (string) $wpdb->last_error;
	$wpdb->last_error = '';
	$wpdb->suppress_errors( $previous );

	return '' === $last_error;
}

function wipe_clean_get_leads_stats() {
	global $wpdb;

	if ( ! wipe_clean_leads_table_exists() ) {
		return array(
			'total' => 0,
			'today' => 0,
			'week'  => 0,
		);
	}

	$table_name = wipe_clean_get_leads_table_name();
	$dates       = (array) $wpdb->get_col( "SELECT created_at FROM {$table_name}" );
	$today_key   = current_time( 'Y-m-d' );
	$now         = current_time( 'timestamp' );
	$week_start  = $now - ( 7 * DAY_IN_SECONDS );
	$today_count = 0;
	$week_count  = 0;

	foreach ( $dates as $created_at ) {
		$created_at = (string) $created_at;
		$timestamp  = strtotime( $created_at );

		if ( ! $timestamp ) {
			continue;
		}

		if ( $today_key === wp_date( 'Y-m-d', $timestamp, wp_timezone() ) ) {
			++$today_count;
		}

		if ( $timestamp >= $week_start ) {
			++$week_count;
		}
	}

	return array(
		'total' => count( $dates ),
		'today' => $today_count,
		'week'  => $week_count,
	);
}

function wipe_clean_get_leads_count_by_form_key() {
	global $wpdb;

	if ( ! wipe_clean_leads_table_exists() ) {
		return array();
	}

	$table_name = wipe_clean_get_leads_table_name();
	$rows       = $wpdb->get_results(
		"SELECT form_key, COUNT(*) AS leads_count FROM {$table_name} GROUP BY form_key",
		ARRAY_A
	);
	$counts     = array();

	foreach ( (array) $rows as $row ) {
		$counts[ (string) ( $row['form_key'] ?? '' ) ] = (int) ( $row['leads_count'] ?? 0 );
	}

	return $counts;
}

function wipe_clean_get_recent_leads( $page = 1, $per_page = 20 ) {
	global $wpdb;

	if ( ! wipe_clean_leads_table_exists() ) {
		return array();
	}

	$page       = max( 1, (int) $page );
	$per_page   = max( 1, min( 100, (int) $per_page ) );
	$offset     = ( $page - 1 ) * $per_page;
	$table_name = wipe_clean_get_leads_table_name();

	return $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM {$table_name} ORDER BY created_at DESC, id DESC LIMIT %d OFFSET %d",
			$per_page,
			$offset
		),
		ARRAY_A
	);
}

function wipe_clean_get_leads_total_pages( $per_page = 20 ) {
	global $wpdb;

	if ( ! wipe_clean_leads_table_exists() ) {
		return 1;
	}

	$table_name = wipe_clean_get_leads_table_name();
	$total      = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table_name}" );

	return max( 1, (int) ceil( $total / max( 1, (int) $per_page ) ) );
}

function wipe_clean_get_status_badge_class( $status ) {
	$status = (string) $status;

	if ( in_array( $status, array( 'mail_sent', 'sent' ), true ) ) {
		return 'is-success';
	}

	if ( in_array( $status, array( 'mail_failed', 'failed', 'aborted' ), true ) ) {
		return 'is-error';
	}

	if ( 'disabled' === $status ) {
		return 'is-muted';
	}

	return 'is-info';
}

function wipe_clean_get_status_badge_label( $status ) {
	$labels = array(
		'mail_sent'   => 'Отправлено',
		'sent'        => 'Отправлено',
		'mail_failed' => 'Ошибка',
		'failed'      => 'Ошибка',
		'aborted'     => 'Остановлено',
		'disabled'    => 'Не настроено',
	);

	return $labels[ (string) $status ] ?? (string) $status;
}

function wipe_clean_get_leads_hub_tabs() {
	return array(
		'overview' => 'Сводка',
		'forms'    => 'Формы',
		'leads'    => 'Журнал',
		'settings' => 'Настройки',
	);
}

function wipe_clean_get_lead_payload_labels() {
	return array(
		'form_context_label'   => 'Форма',
		'form_context_page'    => 'Страница',
		'form_context_surface' => 'Раздел',
		'name'                 => 'Имя',
		'phone'                => 'Телефон',
		'email'                => 'Email',
		'promotion_title'      => 'Акция',
		'area'                 => 'Площадь',
		'service'              => 'Услуга',
		'frequency'            => 'Регулярность',
		'message'              => 'Сообщение',
		'review_files'         => 'Файлы',
	);
}

function wipe_clean_format_lead_payload_value( $value ) {
	if ( is_array( $value ) ) {
		$value = implode( ', ', array_map( 'wipe_clean_format_lead_payload_value', $value ) );
	}

	return trim( wp_strip_all_tags( (string) $value ) );
}

function wipe_clean_render_lead_payload_rows( $payload ) {
	$payload = is_array( $payload ) ? $payload : array();
	$labels  = wipe_clean_get_lead_payload_labels();
	$rows    = array();

	foreach ( $payload as $key => $value ) {
		$formatted_value = wipe_clean_format_lead_payload_value( $value );

		if ( '' === $formatted_value ) {
			continue;
		}

		$rows[] = array(
			'label' => $labels[ (string) $key ] ?? (string) $key,
			'value' => $formatted_value,
		);
	}

	return $rows;
}

function wipe_clean_render_leads_hub_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Недостаточно прав.' );
	}

	wipe_clean_sync_managed_cf7_forms();

	$tabs             = wipe_clean_get_leads_hub_tabs();
	$active_tab       = sanitize_key( (string) ( $_GET['tab'] ?? 'overview' ) );
	$active_tab       = isset( $tabs[ $active_tab ] ) ? $active_tab : 'overview';
	$settings         = wipe_clean_get_leads_settings();
	$registry         = wipe_clean_get_managed_forms_registry();
	$form_map         = wipe_clean_get_managed_form_map();
	$form_counts      = wipe_clean_get_leads_count_by_form_key();
	$stats            = wipe_clean_get_leads_stats();
	$current_page     = max( 1, (int) ( $_GET['paged'] ?? 1 ) );
	$per_page         = 20;
	$recent_leads     = wipe_clean_get_recent_leads( $current_page, $per_page );
	$total_pages      = wipe_clean_get_leads_total_pages( $per_page );
	$last_sync        = (string) get_option( wipe_clean_get_managed_forms_last_sync_option_name(), '' );
	$telegram_ready   = '' !== wipe_clean_get_telegram_bot_token() && ! empty( wipe_clean_get_telegram_chat_ids() );
	$email_targets    = ! empty( $settings['notification_emails'] ) ? implode( ', ', (array) $settings['notification_emails'] ) : ( get_option( 'admin_email' ) ?: 'Не указано' );
	$pending_reviews_count = function_exists( 'wipe_clean_get_pending_review_submissions_count' ) ? (int) wipe_clean_get_pending_review_submissions_count() : 0;
	$pending_reviews_url   = function_exists( 'wipe_clean_get_pending_review_submissions_url' ) ? (string) wipe_clean_get_pending_review_submissions_url() : '';
	$sync_url         = wp_nonce_url(
		wipe_clean_get_leads_hub_url( array( 'wipe_action' => 'sync_forms' ) ),
		'wipe_clean_sync_forms'
	);
	?>
	<div class="wrap wipe-clean-leads-hub">
		<?php if ( ! empty( $_GET['synced'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>Формы Contact Form 7 синхронизированы с темой.</p></div>
		<?php endif; ?>

		<section class="wipe-clean-leads-hub__hero">
			<div class="wipe-clean-leads-hub__hero-main">
				<div class="wipe-clean-leads-hub__eyebrow">Wipe Clean</div>
				<h1 class="wipe-clean-leads-hub__title">Заявки</h1>
				<p class="wipe-clean-leads-hub__intro">Единый хаб для всех форм сайта: синхронизация CF7, email-уведомления, Telegram и журнал входящих обращений.</p>
			</div>
			<div class="wipe-clean-leads-hub__hero-actions">
				<a class="button button-primary button-hero" href="<?php echo esc_url( $sync_url ); ?>">Синхронизировать формы</a>
				<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=wpcf7' ) ); ?>">Открыть Contact Form 7</a>
			</div>
		</section>

		<section class="wipe-clean-leads-hub__metrics">
			<article class="wipe-clean-leads-hub__metric-card">
				<div class="wipe-clean-leads-hub__metric-value"><?php echo esc_html( (string) $stats['total'] ); ?></div>
				<div class="wipe-clean-leads-hub__metric-label">Всего заявок</div>
			</article>
			<article class="wipe-clean-leads-hub__metric-card">
				<div class="wipe-clean-leads-hub__metric-value"><?php echo esc_html( (string) $stats['today'] ); ?></div>
				<div class="wipe-clean-leads-hub__metric-label">Сегодня</div>
			</article>
			<article class="wipe-clean-leads-hub__metric-card">
				<div class="wipe-clean-leads-hub__metric-value"><?php echo esc_html( (string) $stats['week'] ); ?></div>
				<div class="wipe-clean-leads-hub__metric-label">За 7 дней</div>
			</article>
			<article class="wipe-clean-leads-hub__metric-card">
				<div class="wipe-clean-leads-hub__metric-value"><?php echo esc_html( (string) $pending_reviews_count ); ?></div>
				<div class="wipe-clean-leads-hub__metric-label">Новых отзывов на проверке</div>
			</article>
			<article class="wipe-clean-leads-hub__metric-card">
				<div class="wipe-clean-leads-hub__metric-value"><?php echo esc_html( (string) count( $registry ) ); ?></div>
				<div class="wipe-clean-leads-hub__metric-label">Форм под управлением</div>
			</article>
		</section>

		<section class="wipe-clean-leads-hub__channels">
			<div class="wipe-clean-leads-hub__channel">
				<div class="wipe-clean-leads-hub__channel-title">Email</div>
				<div class="wipe-clean-leads-hub__channel-value"><?php echo esc_html( $email_targets ); ?></div>
			</div>
			<div class="wipe-clean-leads-hub__channel">
				<div class="wipe-clean-leads-hub__channel-title">Telegram</div>
				<div class="wipe-clean-leads-hub__channel-value"><?php echo esc_html( $telegram_ready ? 'Готов к отправке' : 'Пока не настроен' ); ?></div>
			</div>
			<div class="wipe-clean-leads-hub__channel">
				<div class="wipe-clean-leads-hub__channel-title">Последняя синхронизация</div>
				<div class="wipe-clean-leads-hub__channel-value"><?php echo esc_html( '' !== $last_sync ? $last_sync : 'Ещё не запускалась' ); ?></div>
			</div>
		</section>

		<nav class="wipe-clean-leads-hub__tabs" aria-label="Разделы хаба заявок">
			<?php foreach ( $tabs as $tab_key => $tab_label ) : ?>
				<a class="wipe-clean-leads-hub__tab<?php echo $tab_key === $active_tab ? ' is-active' : ''; ?>" href="<?php echo esc_url( wipe_clean_get_leads_hub_url( array( 'tab' => $tab_key ) ) ); ?>">
					<?php echo esc_html( $tab_label ); ?>
				</a>
			<?php endforeach; ?>
		</nav>

		<?php if ( 'overview' === $active_tab ) : ?>
			<div class="wipe-clean-leads-hub__grid">
				<section class="wipe-clean-leads-hub__panel wipe-clean-leads-hub__panel--wide">
					<h2>Что уже готово</h2>
					<ul class="wipe-clean-leads-hub__checklist">
						<li>Все рабочие формы сайта собираются темой и синхронизируются в Contact Form 7.</li>
						<li>Журнал заявок сохраняет имя, телефон, email, страницу и детали формы.</li>
						<li>Если заполнен Telegram-бот и chat ID, уведомления уходят туда же, куда и письма.</li>
						<li>Отзыв из popup создаёт черновик в разделе «Отзывы» и ждёт проверки перед публикацией.</li>
					</ul>
				</section>
				<section class="wipe-clean-leads-hub__panel">
					<h2>Что осталось настроить</h2>
					<ul class="wipe-clean-leads-hub__checklist">
						<li>Указать email для уведомлений или оставить email администратора WordPress.</li>
						<li>Настроить SMTP на уровне сайта или сервера, если письма ещё не уходят.</li>
						<li>Заполнить токен Telegram-бота и chat ID, если нужен дублирующий канал.</li>
					</ul>
					<?php if ( $pending_reviews_url ) : ?>
						<div class="wipe-clean-leads-hub__panel-actions">
							<a class="button button-primary" href="<?php echo esc_url( $pending_reviews_url ); ?>">Открыть отзывы на проверке</a>
						</div>
					<?php endif; ?>
				</section>
			</div>
		<?php endif; ?>

		<?php if ( 'forms' === $active_tab ) : ?>
			<div class="wipe-clean-leads-hub__forms-grid">
				<?php foreach ( $registry as $form_key => $config ) : ?>
					<?php
					$form_id    = (int) ( $form_map[ $form_key ] ?? 0 );
					$edit_url   = $form_id ? admin_url( 'admin.php?page=wpcf7&post=' . $form_id . '&action=edit' ) : '';
					$lead_count = (int) ( $form_counts[ $form_key ] ?? 0 );
					?>
					<article class="wipe-clean-leads-hub__form-card">
						<div class="wipe-clean-leads-hub__form-head">
							<div>
								<h2><?php echo esc_html( (string) ( $config['title'] ?? $form_key ) ); ?></h2>
								<p><?php echo esc_html( (string) ( $config['description'] ?? '' ) ); ?></p>
							</div>
							<span class="wipe-clean-leads-hub__counter"><?php echo esc_html( (string) $lead_count ); ?></span>
						</div>
						<div class="wipe-clean-leads-hub__form-meta">
							<div>
								<strong>Где находится</strong>
								<span><?php echo esc_html( (string) ( $config['location_label'] ?? '' ) ); ?></span>
							</div>
							<div>
								<strong>Формат</strong>
								<span><?php echo esc_html( (string) ( $config['surface_label'] ?? '' ) ); ?></span>
							</div>
							<div>
								<strong>CF7</strong>
								<span><?php echo $form_id ? esc_html( '#' . $form_id ) : 'Пока не создана'; ?></span>
							</div>
						</div>
						<div class="wipe-clean-leads-hub__tags">
							<?php foreach ( (array) ( $config['fields'] ?? array() ) as $field_label ) : ?>
								<span class="wipe-clean-leads-hub__tag"><?php echo esc_html( (string) $field_label ); ?></span>
							<?php endforeach; ?>
							<?php if ( 'popup_review' === $form_key ) : ?>
								<span class="wipe-clean-leads-hub__tag wipe-clean-leads-hub__tag--accent">Создаёт черновик отзыва</span>
							<?php endif; ?>
						</div>
						<div class="wipe-clean-leads-hub__form-actions">
							<?php if ( $form_id ) : ?>
								<a class="button button-secondary" href="<?php echo esc_url( $edit_url ); ?>">Редактировать форму</a>
							<?php endif; ?>
							<?php if ( ! empty( $config['page_url'] ) ) : ?>
								<a class="button" href="<?php echo esc_url( (string) $config['page_url'] ); ?>" target="_blank" rel="noreferrer noopener">Открыть страницу</a>
							<?php endif; ?>
							<?php if ( 'popup_review' === $form_key && $pending_reviews_url ) : ?>
								<a class="button" href="<?php echo esc_url( $pending_reviews_url ); ?>">Модерация отзывов</a>
							<?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( 'leads' === $active_tab ) : ?>
			<section class="wipe-clean-leads-hub__panel">
				<div class="wipe-clean-leads-hub__panel-head">
					<h2>Журнал заявок</h2>
					<p>Здесь сохраняются все обращения, которые пришли через формы сайта.</p>
				</div>
				<div class="wipe-clean-leads-hub__table-wrap">
					<table class="wipe-clean-leads-hub__table">
						<thead>
							<tr>
								<th>Дата</th>
								<th>Форма</th>
								<th>Контакт</th>
								<th>Доставка</th>
								<th>Детали</th>
							</tr>
						</thead>
						<tbody>
							<?php if ( empty( $recent_leads ) ) : ?>
								<tr>
									<td colspan="5">Пока нет сохранённых заявок.</td>
								</tr>
							<?php endif; ?>
							<?php foreach ( $recent_leads as $lead ) : ?>
								<?php $payload_rows = wipe_clean_render_lead_payload_rows( json_decode( (string) ( $lead['payload_json'] ?? '' ), true ) ); ?>
								<tr>
									<td><?php echo esc_html( (string) ( $lead['created_at'] ?? '' ) ); ?></td>
									<td>
										<strong><?php echo esc_html( (string) ( $lead['source_label'] ?? '' ) ); ?></strong>
										<div class="wipe-clean-leads-hub__subtle"><?php echo esc_html( (string) ( $lead['page_label'] ?? '' ) ); ?></div>
									</td>
									<td>
										<div><?php echo esc_html( (string) ( $lead['lead_name'] ?? '' ) ); ?></div>
										<div class="wipe-clean-leads-hub__subtle"><?php echo esc_html( (string) ( $lead['lead_phone'] ?? '' ) ); ?></div>
										<?php if ( ! empty( $lead['lead_email'] ) ) : ?>
											<div class="wipe-clean-leads-hub__subtle"><?php echo esc_html( (string) $lead['lead_email'] ); ?></div>
										<?php endif; ?>
									</td>
									<td>
										<span class="wipe-clean-leads-hub__badge <?php echo esc_attr( wipe_clean_get_status_badge_class( $lead['mail_status'] ?? '' ) ); ?>"><?php echo esc_html( wipe_clean_get_status_badge_label( $lead['mail_status'] ?? '' ) ); ?></span>
										<span class="wipe-clean-leads-hub__badge <?php echo esc_attr( wipe_clean_get_status_badge_class( $lead['telegram_status'] ?? '' ) ); ?>"><?php echo esc_html( wipe_clean_get_status_badge_label( $lead['telegram_status'] ?? '' ) ); ?></span>
									</td>
									<td>
										<?php if ( ! empty( $payload_rows ) ) : ?>
											<details class="wipe-clean-leads-hub__details">
												<summary>Открыть</summary>
												<div class="wipe-clean-leads-hub__detail-list">
													<?php foreach ( $payload_rows as $payload_row ) : ?>
														<div class="wipe-clean-leads-hub__detail-item">
															<strong><?php echo esc_html( (string) $payload_row['label'] ); ?></strong>
															<span><?php echo esc_html( (string) $payload_row['value'] ); ?></span>
														</div>
													<?php endforeach; ?>
												</div>
											</details>
										<?php else : ?>
											<span class="wipe-clean-leads-hub__subtle">Без дополнительных данных</span>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

				<?php if ( $total_pages > 1 ) : ?>
					<div class="tablenav">
						<div class="tablenav-pages">
							<?php
							echo paginate_links(
								array(
									'base'      => add_query_arg(
										array(
											'page'  => wipe_clean_get_leads_hub_slug(),
											'tab'   => 'leads',
											'paged' => '%#%',
										),
										admin_url( 'admin.php' )
									),
									'format'    => '',
									'current'   => $current_page,
									'total'     => $total_pages,
									'prev_text' => '←',
									'next_text' => '→',
								)
							);
							?>
						</div>
					</div>
				<?php endif; ?>
			</section>
		<?php endif; ?>

		<?php if ( 'settings' === $active_tab ) : ?>
			<section class="wipe-clean-leads-hub__panel">
				<div class="wipe-clean-leads-hub__panel-head">
					<h2>Настройки уведомлений</h2>
					<p>SMTP настраивается отдельно. Здесь задаются только адреса получателей и Telegram-параметры.</p>
				</div>
				<form method="post" action="options.php" class="wipe-clean-leads-hub__settings-form">
					<?php settings_fields( 'wipe_clean_leads_settings' ); ?>
					<div class="wipe-clean-leads-hub__settings-grid">
						<div class="wipe-clean-leads-hub__settings-card">
							<label for="wipe-clean-notification-emails">Email для уведомлений</label>
							<textarea id="wipe-clean-notification-emails" name="<?php echo esc_attr( wipe_clean_get_leads_settings_option_name() ); ?>[notification_emails]"><?php echo esc_textarea( implode( "\n", (array) ( $settings['notification_emails'] ?? array() ) ) ); ?></textarea>
							<p>По одному адресу на строку. Если поле пустое, используется email администратора WordPress.</p>
						</div>
						<div class="wipe-clean-leads-hub__settings-card">
							<label for="wipe-clean-telegram-token">Токен Telegram-бота</label>
							<input type="text" id="wipe-clean-telegram-token" name="<?php echo esc_attr( wipe_clean_get_leads_settings_option_name() ); ?>[telegram_bot_token]" value="<?php echo esc_attr( (string) ( $settings['telegram_bot_token'] ?? '' ) ); ?>">
							<p>Например: <code>123456789:AA...</code></p>

							<label for="wipe-clean-telegram-chats">ID чатов Telegram</label>
							<textarea id="wipe-clean-telegram-chats" name="<?php echo esc_attr( wipe_clean_get_leads_settings_option_name() ); ?>[telegram_chat_ids]"><?php echo esc_textarea( implode( "\n", (array) ( $settings['telegram_chat_ids'] ?? array() ) ) ); ?></textarea>
							<p>По одному chat ID на строку. Можно указывать личные чаты и группы.</p>
						</div>
					</div>
					<div class="wipe-clean-leads-hub__settings-note">
						<strong>Важно:</strong> формы уже готовы к работе на уровне темы. Для реальной доставки осталось настроить SMTP и, при необходимости, заполнить токен и chat ID Telegram.
					</div>
					<?php submit_button( 'Сохранить настройки' ); ?>
				</form>
			</section>
		<?php endif; ?>
	</div>
	<?php
}
