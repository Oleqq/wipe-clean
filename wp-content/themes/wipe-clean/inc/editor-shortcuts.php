<?php
/**
 * Front-end admin tools and quick actions.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check whether the current user can access front-end admin tools.
 *
 * @return bool
 */
function wipe_clean_can_access_frontend_tools() {
	return ! is_admin() && ! wp_doing_ajax() && ! wp_doing_cron() && ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) || current_user_can( 'edit_theme_options' ) );
}

/**
 * Get current editable object id for front-end shortcuts.
 *
 * @return int
 */
function wipe_clean_get_editable_object_id() {
	if ( is_home() && ! is_front_page() ) {
		return (int) get_option( 'page_for_posts' );
	}

	$object_id = (int) get_queried_object_id();

	if ( ! $object_id && is_front_page() ) {
		$object_id = (int) get_option( 'page_on_front' );
	}

	return $object_id;
}

/**
 * Get edit link for the current front-end object.
 *
 * @return string
 */
function wipe_clean_get_frontend_edit_link() {
	if ( ! wipe_clean_can_access_frontend_tools() ) {
		return '';
	}

	$object_id = wipe_clean_get_editable_object_id();

	if ( ! $object_id || ! current_user_can( 'edit_post', $object_id ) ) {
		return '';
	}

	$link = get_edit_post_link( $object_id, '' );

	return $link ? (string) $link : '';
}

/**
 * Get current object label for toolbar subtitle.
 *
 * @return string
 */
function wipe_clean_get_frontend_tools_context_label() {
	$object_id = wipe_clean_get_editable_object_id();

	if ( $object_id ) {
		$title = get_the_title( $object_id );

		if ( $title ) {
			return wp_strip_all_tags( $title );
		}
	}

	if ( is_front_page() ) {
		return 'Главная страница';
	}

	if ( is_home() ) {
		return 'Страница записей';
	}

	if ( is_archive() ) {
		return 'Архивная страница';
	}

	if ( is_404() ) {
		return 'Страница 404';
	}

	return 'Текущая страница';
}

/**
 * Get front-end toolbar actions.
 *
 * @return array<int, array<string, string>>
 */
function wipe_clean_get_frontend_tools_actions() {
	$actions = array();

	if ( ! wipe_clean_can_access_frontend_tools() ) {
		return $actions;
	}

	$edit_link = wipe_clean_get_frontend_edit_link();

	if ( '' !== $edit_link ) {
		$actions[] = array(
			'type'        => 'link',
			'label'       => 'Редактировать страницу',
			'description' => 'Открыть редактор текущей страницы и поля ACF.',
			'url'         => $edit_link,
			'target'      => '',
			'icon'        => 'edit',
			'variant'     => 'primary',
		);
	}

	$actions[] = array(
		'type'        => 'link',
		'label'       => 'Открыть админку',
		'description' => 'Перейти в панель управления WordPress.',
		'url'         => admin_url(),
		'target'      => '',
		'icon'        => 'dashboard',
		'variant'     => 'secondary',
	);

	return $actions;
}

/**
 * Get inline SVG icon markup.
 *
 * @param string $icon Icon key.
 * @return string
 */
function wipe_clean_get_frontend_tools_icon_markup( $icon ) {
	switch ( $icon ) {
		case 'edit':
			return '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 20H8L18.5 9.5C19.0304 8.96957 19.3284 8.25014 19.3284 7.5C19.3284 6.74986 19.0304 6.03043 18.5 5.5C17.9696 4.96957 17.2501 4.67157 16.5 4.67157C15.7499 4.67157 15.0304 4.96957 14.5 5.5L4 16V20Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.5 6.5L17.5 10.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
		case 'dashboard':
			return '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 13H10V20H4V13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 4H20V11H14V4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 15H20V20H14V15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 4H10V9H4V4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
		case 'spark':
			return '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 3L13.9 8.1L19 10L13.9 11.9L12 17L10.1 11.9L5 10L10.1 8.1L12 3Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>';
		default:
			return '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="2"/><path d="M12 8V12L14.5 14.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
	}
}

/**
 * Enqueue toolbar assets.
 *
 * @return void
 */
function wipe_clean_enqueue_editor_shortcuts_assets() {
	if ( ! wipe_clean_can_access_frontend_tools() ) {
		return;
	}

	wp_register_style( 'wipe-clean-editor-shortcuts', false, array( 'wipe-clean-app' ), WIPE_CLEAN_VERSION );
	wp_enqueue_style( 'wipe-clean-editor-shortcuts' );

	$css = <<<'CSS'
.wipe-clean-admin-tools {
	position: fixed;
	right: max(18px, env(safe-area-inset-right));
	bottom: max(18px, env(safe-area-inset-bottom));
	z-index: 99998;
	display: flex;
	flex-direction: column;
	align-items: flex-end;
	gap: 14px;
	pointer-events: none;
}

.wipe-clean-admin-tools > * {
	pointer-events: auto;
}

.wipe-clean-admin-tools__panel {
	width: min(368px, calc(100vw - 28px));
	max-height: min(70dvh, 520px);
	padding: 14px;
	border: 1px solid rgba(64, 165, 193, 0.28);
	border-radius: 24px;
	background: linear-gradient(180deg, rgba(255, 255, 255, 0.97) 0%, rgba(255, 252, 248, 0.94) 100%);
	box-shadow: 0 22px 50px rgba(21, 15, 49, 0.18), 0 2px 4px rgba(1, 112, 150, 0.12);
	backdrop-filter: blur(20px);
	-webkit-backdrop-filter: blur(20px);
	overflow: auto;
	scrollbar-width: thin;
	scrollbar-color: rgba(0, 134, 179, 0.5) rgba(64, 165, 193, 0.08);
	transform: translateY(12px) scale(0.95);
	transform-origin: right bottom;
	opacity: 0;
	pointer-events: none;
	transition: transform 0.34s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.24s ease, visibility 0.24s ease;
	visibility: hidden;
}

.wipe-clean-admin-tools.is-open .wipe-clean-admin-tools__panel {
	transform: translateY(0) scale(1);
	opacity: 1;
	pointer-events: auto;
	visibility: visible;
}

.wipe-clean-admin-tools__panel-head {
	display: flex;
	align-items: flex-start;
	justify-content: space-between;
	gap: 16px;
	margin-bottom: 14px;
}

.wipe-clean-admin-tools__title {
	display: block;
	margin: 0 0 4px;
	color: #150F31;
	font-family: "Manrope", "Segoe UI", sans-serif;
	font-size: 16px;
	font-weight: 800;
	line-height: 1.15;
}

.wipe-clean-admin-tools__subtitle {
	display: block;
	margin: 0;
	color: #5D5779;
	font-family: "Golos Text", "Segoe UI", sans-serif;
	font-size: 12px;
	line-height: 1.45;
}

.wipe-clean-admin-tools__context {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	padding: 6px 9px;
	border-radius: 999px;
	background: rgba(27, 158, 116, 0.12);
	color: #1B9E74;
	font-family: "Manrope", "Segoe UI", sans-serif;
	font-size: 11px;
	font-weight: 800;
	line-height: 1;
	white-space: nowrap;
}

.wipe-clean-admin-tools__list {
	display: grid;
	gap: 10px;
}

.wipe-clean-admin-tools__action {
	display: grid;
	grid-template-columns: 44px minmax(0, 1fr) 20px;
	align-items: center;
	gap: 12px;
	padding: 12px;
	border: 1px solid rgba(64, 165, 193, 0.16);
	border-radius: 18px;
	background: rgba(255, 255, 255, 0.96);
	box-shadow: 0 6px 18px rgba(21, 15, 49, 0.08);
	color: #150F31;
	text-decoration: none;
	transition: transform 0.26s ease, box-shadow 0.26s ease, border-color 0.26s ease, background-color 0.26s ease;
}

.wipe-clean-admin-tools__action:hover,
.wipe-clean-admin-tools__action:focus-visible {
	transform: translateY(-2px);
	border-color: rgba(64, 165, 193, 0.34);
	box-shadow: 0 12px 24px rgba(21, 15, 49, 0.12);
	color: #150F31;
	text-decoration: none;
}

.wipe-clean-admin-tools__action-icon {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 44px;
	height: 44px;
	border-radius: 14px;
	background: linear-gradient(180deg, #1290BC 0%, #0086B3 100%);
	border: 2px solid #40A5C1;
	box-shadow: 0 2px 4px rgba(1, 112, 150, 0.44), inset 0 1px 0 rgba(255, 255, 255, 0.28);
	color: #fff;
}

.wipe-clean-admin-tools__action--secondary .wipe-clean-admin-tools__action-icon {
	background: linear-gradient(180deg, #1B9E74 0%, #28B789 100%);
	border-color: #54C39E;
	box-shadow: 0 2px 4px rgba(27, 158, 116, 0.32), inset 0 1px 0 rgba(255, 255, 255, 0.24);
}

.wipe-clean-admin-tools__action-icon svg,
.wipe-clean-admin-tools__toggle-glyph svg,
.wipe-clean-admin-tools__action-arrow svg {
	display: block;
	width: 20px;
	height: 20px;
}

.wipe-clean-admin-tools__action-body {
	display: grid;
	gap: 3px;
	min-width: 0;
}

.wipe-clean-admin-tools__action-label {
	display: block;
	font-family: "Manrope", "Segoe UI", sans-serif;
	font-size: 14px;
	font-weight: 800;
	line-height: 1.25;
	color: inherit;
}

.wipe-clean-admin-tools__action-description {
	display: block;
	font-family: "Golos Text", "Segoe UI", sans-serif;
	font-size: 12px;
	line-height: 1.4;
	color: #5D5779;
}

.wipe-clean-admin-tools__action-arrow {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 20px;
	height: 20px;
	color: #0086B3;
}

.wipe-clean-admin-tools__toggle {
	display: inline-flex;
	align-items: center;
	gap: 12px;
	padding: 10px 14px 10px 10px;
	border: 2px solid #40A5C1;
	border-radius: 999px;
	background: linear-gradient(180deg, #1290BC 0%, #0086B3 100%);
	box-shadow: 0 14px 34px rgba(0, 134, 179, 0.24), 0 2px 4px rgba(1, 112, 150, 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.24);
	color: #fff;
	cursor: pointer;
	transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.3s ease, filter 0.3s ease;
}

.wipe-clean-admin-tools__toggle:hover,
.wipe-clean-admin-tools__toggle:focus-visible {
	transform: translateY(-2px);
	filter: saturate(1.06);
	box-shadow: 0 18px 40px rgba(0, 134, 179, 0.28), 0 2px 4px rgba(1, 112, 150, 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.3);
}

.wipe-clean-admin-tools__toggle-glyph {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 42px;
	height: 42px;
	border-radius: 50%;
	background: linear-gradient(180deg, rgba(255,255,255,0.24) 0%, rgba(255,255,255,0.08) 100%);
	box-shadow: inset 0 1px 0 rgba(255,255,255,0.28);
	flex-shrink: 0;
}

.wipe-clean-admin-tools__toggle-copy {
	display: grid;
	gap: 2px;
	text-align: left;
}

.wipe-clean-admin-tools__toggle-title {
	font-family: "Manrope", "Segoe UI", sans-serif;
	font-size: 14px;
	font-weight: 800;
	line-height: 1.1;
	white-space: nowrap;
}

.wipe-clean-admin-tools__toggle-hint {
	font-family: "Golos Text", "Segoe UI", sans-serif;
	font-size: 11px;
	line-height: 1.2;
	opacity: 0.86;
}

.wipe-clean-admin-tools__toggle-chevron {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 18px;
	height: 18px;
	flex-shrink: 0;
	transition: transform 0.28s ease;
}

.wipe-clean-admin-tools__toggle-chevron svg {
	display: block;
	width: 16px;
	height: 16px;
}

.wipe-clean-admin-tools.is-open .wipe-clean-admin-tools__toggle-chevron {
	transform: rotate(180deg);
}

.wipe-clean-admin-tools__action,
.wipe-clean-admin-tools__panel {
	-webkit-font-smoothing: antialiased;
}

.wipe-clean-admin-tools.is-open .wipe-clean-admin-tools__action {
	animation: wipe-clean-admin-tools-item-in 0.34s cubic-bezier(0.22, 1, 0.36, 1) both;
}

.wipe-clean-admin-tools.is-open .wipe-clean-admin-tools__action:nth-child(2) {
	animation-delay: 0.04s;
}

.wipe-clean-admin-tools.is-open .wipe-clean-admin-tools__action:nth-child(3) {
	animation-delay: 0.08s;
}

@keyframes wipe-clean-admin-tools-item-in {
	from {
		opacity: 0;
		transform: translateY(8px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}

@media (max-width: 782px) {
	html.admin-bar .wipe-clean-admin-tools {
		bottom: max(14px, env(safe-area-inset-bottom));
	}
}

@media (max-width: 650px) {
	.wipe-clean-admin-tools {
		right: max(12px, env(safe-area-inset-right));
		left: max(12px, env(safe-area-inset-left));
		bottom: max(12px, env(safe-area-inset-bottom));
		align-items: stretch;
	}

	.wipe-clean-admin-tools__panel {
		width: 100%;
		max-height: min(62dvh, 440px);
		padding: 12px;
		border-radius: 20px;
	}

	.wipe-clean-admin-tools__panel-head {
		gap: 12px;
	}

	.wipe-clean-admin-tools__toggle {
		width: 100%;
		justify-content: space-between;
		padding: 9px 12px 9px 9px;
	}

	.wipe-clean-admin-tools__toggle-glyph {
		width: 38px;
		height: 38px;
	}

	.wipe-clean-admin-tools__toggle-title {
		font-size: 13px;
	}

	.wipe-clean-admin-tools__toggle-hint {
		font-size: 10px;
	}

	.wipe-clean-admin-tools__action {
		grid-template-columns: 40px minmax(0, 1fr) 18px;
		gap: 10px;
		padding: 11px;
	}

	.wipe-clean-admin-tools__action-icon {
		width: 40px;
		height: 40px;
		border-radius: 12px;
	}
}

@media (prefers-reduced-motion: reduce) {
	.wipe-clean-admin-tools__panel,
	.wipe-clean-admin-tools__toggle,
	.wipe-clean-admin-tools__toggle-chevron,
	.wipe-clean-admin-tools__action {
		transition: none;
		animation: none;
	}
}
CSS;

	wp_add_inline_style( 'wipe-clean-editor-shortcuts', $css );

	wp_register_script( 'wipe-clean-editor-shortcuts', '', array(), WIPE_CLEAN_VERSION, true );
	wp_enqueue_script( 'wipe-clean-editor-shortcuts' );

	$js = <<<'JS'
(function () {
	function initAdminTools() {
		const root = document.querySelector('[data-admin-tools]');

		if (!root || root.dataset.adminToolsReady === 'true') {
			return;
		}

		const toggle = root.querySelector('[data-admin-tools-toggle]');
		const panel = root.querySelector('[data-admin-tools-panel]');

		if (!toggle || !panel) {
			return;
		}

		root.dataset.adminToolsReady = 'true';

		function setOpen(nextState) {
			root.classList.toggle('is-open', nextState);
			toggle.setAttribute('aria-expanded', nextState ? 'true' : 'false');
			panel.setAttribute('aria-hidden', nextState ? 'false' : 'true');
		}

		toggle.addEventListener('click', function () {
			setOpen(!root.classList.contains('is-open'));
		});

		document.addEventListener('click', function (event) {
			if (!root.contains(event.target)) {
				setOpen(false);
			}
		});

		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape') {
				setOpen(false);
			}
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initAdminTools, { once: true });
	} else {
		initAdminTools();
	}
})();
JS;

	wp_add_inline_script( 'wipe-clean-editor-shortcuts', $js );
}
add_action( 'wp_enqueue_scripts', 'wipe_clean_enqueue_editor_shortcuts_assets', 20 );

/**
 * Render floating front-end toolbar.
 *
 * @return void
 */
function wipe_clean_render_frontend_tools() {
	if ( ! wipe_clean_can_access_frontend_tools() ) {
		return;
	}

	$actions       = wipe_clean_get_frontend_tools_actions();
	$context_label = wipe_clean_get_frontend_tools_context_label();
	$panel_id      = 'wipe-clean-admin-tools-panel';
	?>
	<div class="wipe-clean-admin-tools" data-admin-tools>
		<div class="wipe-clean-admin-tools__panel" id="<?php echo esc_attr( $panel_id ); ?>" data-admin-tools-panel aria-hidden="true">
			<div class="wipe-clean-admin-tools__panel-head">
				<div>
					<span class="wipe-clean-admin-tools__title"><?php esc_html_e( 'Быстрые действия сайта', 'wipe-clean' ); ?></span>
					<span class="wipe-clean-admin-tools__subtitle"><?php esc_html_e( 'Быстрый переход к редактированию текущей страницы и панели управления.', 'wipe-clean' ); ?></span>
				</div>
				<span class="wipe-clean-admin-tools__context"><?php echo esc_html( $context_label ); ?></span>
			</div>

			<div class="wipe-clean-admin-tools__list">
				<?php foreach ( $actions as $action ) : ?>
					<a class="wipe-clean-admin-tools__action wipe-clean-admin-tools__action--<?php echo esc_attr( (string) ( $action['variant'] ?? 'secondary' ) ); ?>" href="<?php echo esc_url( (string) $action['url'] ); ?>"<?php echo ! empty( $action['target'] ) ? ' target="' . esc_attr( (string) $action['target'] ) . '"' : ''; ?>>
						<span class="wipe-clean-admin-tools__action-icon" aria-hidden="true">
							<?php echo wipe_clean_get_frontend_tools_icon_markup( (string) ( $action['icon'] ?? '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
						<span class="wipe-clean-admin-tools__action-body">
							<span class="wipe-clean-admin-tools__action-label"><?php echo esc_html( (string) $action['label'] ); ?></span>
							<span class="wipe-clean-admin-tools__action-description"><?php echo esc_html( (string) $action['description'] ); ?></span>
						</span>
						<span class="wipe-clean-admin-tools__action-arrow" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M12 5L19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>

		<button class="wipe-clean-admin-tools__toggle" type="button" data-admin-tools-toggle aria-expanded="false" aria-controls="<?php echo esc_attr( $panel_id ); ?>">
			<span class="wipe-clean-admin-tools__toggle-glyph" aria-hidden="true">
				<?php echo wipe_clean_get_frontend_tools_icon_markup( 'spark' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</span>
			<span class="wipe-clean-admin-tools__toggle-copy">
				<span class="wipe-clean-admin-tools__toggle-title"><?php esc_html_e( 'Инструменты', 'wipe-clean' ); ?></span>
				<span class="wipe-clean-admin-tools__toggle-hint"><?php esc_html_e( 'Админ-быстрые действия', 'wipe-clean' ); ?></span>
			</span>
			<span class="wipe-clean-admin-tools__toggle-chevron" aria-hidden="true">
				<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</span>
		</button>
	</div>
	<?php
}
add_action( 'wp_footer', 'wipe_clean_render_frontend_tools', 30 );
