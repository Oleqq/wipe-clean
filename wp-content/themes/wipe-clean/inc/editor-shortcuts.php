<?php
/**
 * Frontend quick admin shortcuts.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_can_use_editor_toolbar' ) ) {
	function wipe_clean_can_use_editor_toolbar() {
		return is_user_logged_in() && current_user_can( 'edit_posts' );
	}
}

if ( ! function_exists( 'wipe_clean_is_services_frontend_context' ) ) {
	function wipe_clean_is_services_frontend_context() {
		return (
			( function_exists( 'wipe_clean_is_services_archive_request' ) && wipe_clean_is_services_archive_request() ) ||
			( function_exists( 'wipe_clean_is_services_single_request' ) && wipe_clean_is_services_single_request() ) ||
			is_post_type_archive( 'wipe_service' ) ||
			is_singular( 'wipe_service' )
		);
	}
}

if ( ! function_exists( 'wipe_clean_is_blog_frontend_context' ) ) {
	function wipe_clean_is_blog_frontend_context() {
		return (
			( function_exists( 'wipe_clean_is_blog_archive_request' ) && wipe_clean_is_blog_archive_request() ) ||
			( function_exists( 'wipe_clean_is_blog_single_request' ) && wipe_clean_is_blog_single_request() )
		);
	}
}

if ( ! function_exists( 'wipe_clean_is_reviews_frontend_context' ) ) {
	function wipe_clean_is_reviews_frontend_context() {
		return function_exists( 'wipe_clean_is_reviews_archive_request' ) && wipe_clean_is_reviews_archive_request();
	}
}

if ( ! function_exists( 'wipe_clean_is_promotions_frontend_context' ) ) {
	function wipe_clean_is_promotions_frontend_context() {
		return function_exists( 'wipe_clean_is_promotions_archive_request' ) && wipe_clean_is_promotions_archive_request();
	}
}

if ( ! function_exists( 'wipe_clean_is_error_page_frontend_context' ) ) {
	function wipe_clean_is_error_page_frontend_context() {
		return function_exists( 'wipe_clean_is_error_page_request' ) && wipe_clean_is_error_page_request();
	}
}

if ( ! function_exists( 'wipe_clean_get_services_archive_settings_action' ) ) {
	function wipe_clean_get_services_archive_settings_action() {
		$url = function_exists( 'wipe_clean_get_services_archive_settings_url' )
			? wipe_clean_get_services_archive_settings_url()
			: '';

		if ( ! $url ) {
			return array();
		}

		return array(
			'label'       => 'Редактировать архив услуг',
			'url'         => $url,
			'description' => 'Открыть блоки страницы архива услуг.',
			'variant'     => 'settings',
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_archive_settings_action' ) ) {
	function wipe_clean_get_blog_archive_settings_action() {
		$url = function_exists( 'wipe_clean_get_blog_archive_settings_url' )
			? wipe_clean_get_blog_archive_settings_url()
			: '';

		if ( ! $url ) {
			return array();
		}

		return array(
			'label'       => 'Редактор архива блога',
			'url'         => $url,
			'description' => 'Открыть блоки страницы архива блога.',
			'variant'     => 'settings',
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_reviews_archive_settings_action' ) ) {
	function wipe_clean_get_reviews_archive_settings_action() {
		$url = function_exists( 'wipe_clean_get_reviews_archive_settings_url' )
			? wipe_clean_get_reviews_archive_settings_url()
			: '';

		if ( ! $url ) {
			return array();
		}

		return array(
			'label'       => 'Редактор архива отзывов',
			'url'         => $url,
			'description' => 'Открыть блоки страницы отзывов.',
			'variant'     => 'settings',
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_promotions_archive_settings_action' ) ) {
	function wipe_clean_get_promotions_archive_settings_action() {
		$url = function_exists( 'wipe_clean_get_promotions_archive_settings_url' )
			? wipe_clean_get_promotions_archive_settings_url()
			: '';

		if ( ! $url ) {
			return array();
		}

		return array(
			'label'       => 'Редактор архива акций',
			'url'         => $url,
			'description' => 'Открыть блоки страницы акций.',
			'variant'     => 'settings',
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_error_page_settings_action' ) ) {
	function wipe_clean_get_error_page_settings_action() {
		$url = function_exists( 'wipe_clean_get_error_page_settings_url' )
			? wipe_clean_get_error_page_settings_url()
			: '';

		if ( ! $url || ! current_user_can( 'edit_pages' ) ) {
			return array();
		}

		return array(
			'label'       => 'Настройки 404',
			'url'         => $url,
			'description' => 'Открыть редактор контента страницы 404 в разделе Настройки.',
			'variant'     => 'settings',
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_site_shell_settings_action' ) ) {
	function wipe_clean_get_site_shell_settings_action() {
		$url = function_exists( 'wipe_clean_get_site_shell_settings_url' )
			? wipe_clean_get_site_shell_settings_url()
			: '';

		if ( ! $url || ! current_user_can( 'edit_pages' ) ) {
			return array();
		}

		return array(
			'label'       => 'Шапка и подвал',
			'url'         => $url,
			'description' => 'Открыть общие настройки шапки и подвала.',
			'variant'     => 'settings',
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_leads_hub_action' ) ) {
	function wipe_clean_get_leads_hub_action() {
		$url = function_exists( 'wipe_clean_get_leads_hub_url' )
			? wipe_clean_get_leads_hub_url()
			: '';

		if ( ! $url || ! current_user_can( 'manage_options' ) ) {
			return array();
		}

		return array(
			'label'       => 'Заявки',
			'url'         => $url,
			'description' => 'Открыть хаб заявок, уведомлений и всех форм сайта.',
			'variant'     => 'admin',
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_archive_admin_bar_action' ) ) {
	function wipe_clean_get_archive_admin_bar_action() {
		if ( function_exists( 'wipe_clean_is_services_archive_request' ) && wipe_clean_is_services_archive_request() ) {
			return array(
				'url'         => function_exists( 'wipe_clean_get_services_archive_settings_url' ) ? wipe_clean_get_services_archive_settings_url() : '',
				'description' => 'Открыть редактор архива услуг.',
			);
		}

		if ( function_exists( 'wipe_clean_is_blog_archive_request' ) && wipe_clean_is_blog_archive_request() ) {
			return array(
				'url'         => function_exists( 'wipe_clean_get_blog_archive_settings_url' ) ? wipe_clean_get_blog_archive_settings_url() : '',
				'description' => 'Открыть редактор архива блога.',
			);
		}

		if ( function_exists( 'wipe_clean_is_reviews_archive_request' ) && wipe_clean_is_reviews_archive_request() ) {
			return array(
				'url'         => function_exists( 'wipe_clean_get_reviews_archive_settings_url' ) ? wipe_clean_get_reviews_archive_settings_url() : '',
				'description' => 'Редактор архива отзывов.',
			);
		}

		if ( function_exists( 'wipe_clean_is_promotions_archive_request' ) && wipe_clean_is_promotions_archive_request() ) {
			return array(
				'url'         => function_exists( 'wipe_clean_get_promotions_archive_settings_url' ) ? wipe_clean_get_promotions_archive_settings_url() : '',
				'description' => 'Редактор архива акций.',
			);
		}

		return array();
	}
}

if ( ! function_exists( 'wipe_clean_get_toolbar_blog_post' ) ) {
	function wipe_clean_get_toolbar_blog_post() {
		if ( ! function_exists( 'wipe_clean_get_current_blog_post' ) ) {
			return null;
		}

		$post = wipe_clean_get_current_blog_post();

		return $post instanceof WP_Post ? $post : null;
	}
}

if ( ! function_exists( 'wipe_clean_get_toolbar_service_post' ) ) {
	function wipe_clean_get_toolbar_service_post() {
		if ( ! function_exists( 'wipe_clean_get_current_service_post' ) ) {
			return null;
		}

		$post = wipe_clean_get_current_service_post();

		return $post instanceof WP_Post ? $post : null;
	}
}

if ( ! function_exists( 'wipe_clean_get_editor_toolbar_target_post' ) ) {
	function wipe_clean_get_editor_toolbar_target_post() {
		if ( function_exists( 'wipe_clean_is_services_single_request' ) && wipe_clean_is_services_single_request() ) {
			$post = wipe_clean_get_toolbar_service_post();

			if ( $post instanceof WP_Post ) {
				return $post;
			}
		}

		if ( function_exists( 'wipe_clean_is_blog_single_request' ) && wipe_clean_is_blog_single_request() ) {
			$post = wipe_clean_get_toolbar_blog_post();

			if ( $post instanceof WP_Post ) {
				return $post;
			}
		}

		if ( is_singular() ) {
			$post = get_queried_object();

			if ( $post instanceof WP_Post ) {
				return $post;
			}
		}

		return null;
	}
}

if ( ! function_exists( 'wipe_clean_get_editor_toolbar_context' ) ) {
	function wipe_clean_get_editor_toolbar_context() {
		if ( function_exists( 'wipe_clean_is_services_single_request' ) && wipe_clean_is_services_single_request() ) {
			$post = wipe_clean_get_toolbar_service_post();

			return array(
				'eyebrow' => 'Услуга',
				'title'   => $post instanceof WP_Post ? get_the_title( $post ) : 'Управление услугой',
			);
		}

		if ( function_exists( 'wipe_clean_is_blog_single_request' ) && wipe_clean_is_blog_single_request() ) {
			$post = wipe_clean_get_toolbar_blog_post();

			return array(
				'eyebrow' => 'Статья блога',
				'title'   => $post instanceof WP_Post ? get_the_title( $post ) : 'Управление статьёй',
			);
		}

		if ( function_exists( 'wipe_clean_is_blog_archive_request' ) && wipe_clean_is_blog_archive_request() ) {
			return array(
				'eyebrow' => 'Блог',
				'title'   => 'Архив статей',
			);
		}

		if ( wipe_clean_is_reviews_frontend_context() ) {
			return array(
				'eyebrow' => 'Отзывы',
				'title'   => 'Архив отзывов',
			);
		}

		if ( wipe_clean_is_promotions_frontend_context() ) {
			return array(
				'eyebrow' => 'Акции',
				'title'   => 'Архив акций',
			);
		}

		if ( wipe_clean_is_error_page_frontend_context() ) {
			return array(
				'eyebrow' => '404',
				'title'   => 'Страница не найдена',
			);
		}

		if ( wipe_clean_is_services_frontend_context() ) {
			return array(
				'eyebrow' => 'Архив услуг',
				'title'   => 'Управление контентом',
			);
		}

		if ( is_singular() ) {
			return array(
				'eyebrow' => 'Страница',
				'title'   => get_the_title(),
			);
		}

		return array(
			'eyebrow' => 'Админка',
			'title'   => 'Быстрые действия',
		);
	}
}

if ( ! function_exists( 'wipe_clean_normalize_editor_toolbar_actions' ) ) {
	function wipe_clean_normalize_editor_toolbar_actions( $actions ) {
		return array_values(
			array_filter(
				(array) $actions,
				static function ( $action ) {
					return ! empty( $action['label'] ) && ! empty( $action['url'] );
				}
			)
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_editor_toolbar_actions' ) ) {
	function wipe_clean_get_editor_toolbar_actions() {
		$actions     = array();
		$target_post = wipe_clean_get_editor_toolbar_target_post();
		$site_shell_action = wipe_clean_get_site_shell_settings_action();

		if ( $target_post instanceof WP_Post ) {
			$edit_link = get_edit_post_link( $target_post->ID, '' );

			if ( $edit_link ) {
				$label = 'Редактор страницы';

				if ( wipe_clean_get_blog_post_type() === $target_post->post_type ) {
					$label = 'Редактор статьи';
				} elseif ( 'wipe_service' === $target_post->post_type ) {
					$label = 'Редактор услуги';
				}

				$actions[] = array(
					'label'       => $label,
					'url'         => $edit_link,
					'description' => 'Открыть текущую запись в админке WordPress.',
					'variant'     => 'edit',
				);
			}
		}

		if ( ! empty( $site_shell_action ) ) {
			$actions[] = $site_shell_action;
		}

		$leads_hub_action = wipe_clean_get_leads_hub_action();

		if ( ! empty( $leads_hub_action ) ) {
			$actions[] = $leads_hub_action;
		}

		if ( function_exists( 'wipe_clean_is_services_single_request' ) && wipe_clean_is_services_single_request() ) {
			$archive_link = get_post_type_archive_link( 'wipe_service' );

			if ( $archive_link ) {
				$actions[] = array(
					'label'       => 'Архив услуг',
					'url'         => $archive_link,
					'description' => 'Открыть страницу архива услуг на сайте.',
					'variant'     => 'view',
				);
			}
		}

		if ( function_exists( 'wipe_clean_is_blog_single_request' ) && wipe_clean_is_blog_single_request() ) {
			$settings_action = wipe_clean_get_blog_archive_settings_action();

			if ( ! empty( $settings_action ) ) {
				$actions[] = $settings_action;
			}

			$archive_link = function_exists( 'wipe_clean_get_blog_archive_page_url' )
				? wipe_clean_get_blog_archive_page_url()
				: '';

			if ( $archive_link ) {
				$actions[] = array(
					'label'       => 'Архив блога',
					'url'         => $archive_link,
					'description' => 'Вернуться к архиву статей.',
					'variant'     => 'view',
				);
			}

			$actions[] = array(
				'label'       => 'Все статьи',
				'url'         => admin_url( 'edit.php?post_type=' . wipe_clean_get_blog_post_type() ),
				'description' => 'Открыть список всех статей.',
				'variant'     => 'list',
			);
		}

		if ( wipe_clean_is_services_frontend_context() ) {
			$actions[] = wipe_clean_get_services_archive_settings_action();

			$actions[] = array(
				'label'       => 'Все услуги',
				'url'         => admin_url( 'edit.php?post_type=wipe_service' ),
				'description' => 'Открыть список всех услуг.',
				'variant'     => 'list',
			);
		}

		if ( function_exists( 'wipe_clean_is_blog_archive_request' ) && wipe_clean_is_blog_archive_request() ) {
			$actions[] = wipe_clean_get_blog_archive_settings_action();

			$actions[] = array(
				'label'       => 'Все статьи',
				'url'         => admin_url( 'edit.php?post_type=' . wipe_clean_get_blog_post_type() ),
				'description' => 'Открыть список всех статей.',
				'variant'     => 'list',
			);

			$actions[] = array(
				'label'       => 'Новая статья',
				'url'         => admin_url( 'post-new.php?post_type=' . wipe_clean_get_blog_post_type() ),
				'description' => 'Создать новую статью.',
				'variant'     => 'edit',
			);
		}

		if ( wipe_clean_is_error_page_frontend_context() ) {
			$actions[] = wipe_clean_get_error_page_settings_action();
		}

		$actions[] = array(
			'label'       => 'Открыть админку',
			'url'         => admin_url(),
			'description' => 'Перейти в панель управления WordPress.',
			'variant'     => 'admin',
		);

		if ( wipe_clean_is_reviews_frontend_context() ) {
			$actions[] = wipe_clean_get_reviews_archive_settings_action();

			$actions[] = array(
				'label'       => 'Все отзывы',
				'url'         => admin_url( 'edit.php?post_type=' . wipe_clean_get_reviews_post_type() ),
				'description' => 'Открыть список всех отзывов.',
				'variant'     => 'list',
			);

			$actions[] = array(
				'label'       => 'Новый отзыв',
				'url'         => admin_url( 'post-new.php?post_type=' . wipe_clean_get_reviews_post_type() ),
				'description' => 'Создать новый отзыв и выбрать его тип.',
				'variant'     => 'edit',
			);
		}

		if ( wipe_clean_is_promotions_frontend_context() ) {
			$actions[] = wipe_clean_get_promotions_archive_settings_action();

			$actions[] = array(
				'label'       => 'Все акции',
				'url'         => admin_url( 'edit.php?post_type=' . wipe_clean_get_promotions_post_type() ),
				'description' => 'Открыть список всех акций.',
				'variant'     => 'list',
			);

			$actions[] = array(
				'label'       => 'Новая акция',
				'url'         => admin_url( 'post-new.php?post_type=' . wipe_clean_get_promotions_post_type() ),
				'description' => 'Создать новую акцию для карточки и всплывающего окна.',
				'variant'     => 'edit',
			);
		}

		return wipe_clean_normalize_editor_toolbar_actions( $actions );
	}
}

if ( ! function_exists( 'wipe_clean_register_archive_edit_admin_bar_node' ) ) {
	function wipe_clean_register_archive_edit_admin_bar_node( $wp_admin_bar ) {
		if ( is_admin() || ! wipe_clean_can_use_editor_toolbar() || ! is_admin_bar_showing() || ! $wp_admin_bar instanceof WP_Admin_Bar ) {
			return;
		}

		$action = wipe_clean_get_archive_admin_bar_action();

		if ( empty( $action['url'] ) ) {
			return;
		}

		if ( method_exists( $wp_admin_bar, 'get_node' ) && $wp_admin_bar->get_node( 'edit' ) ) {
			return;
		}

		$wp_admin_bar->add_node(
			array(
				'id'    => 'edit',
				'title' => 'Редактировать архив',
				'href'  => $action['url'],
				'meta'  => array(
					'title' => $action['description'] ?? 'Открыть редактор архива.',
				),
			)
		);
	}
}
add_action( 'admin_bar_menu', 'wipe_clean_register_archive_edit_admin_bar_node', 80 );

if ( ! function_exists( 'wipe_clean_register_leads_admin_bar_node' ) ) {
	function wipe_clean_register_leads_admin_bar_node( $wp_admin_bar ) {
		if ( ! wipe_clean_can_use_editor_toolbar() || ! is_admin_bar_showing() || ! $wp_admin_bar instanceof WP_Admin_Bar ) {
			return;
		}

		$action = wipe_clean_get_leads_hub_action();

		if ( empty( $action['url'] ) ) {
			return;
		}

		$wp_admin_bar->add_node(
			array(
				'id'    => 'wipe-clean-leads',
				'title' => 'Заявки',
				'href'  => $action['url'],
				'meta'  => array(
					'title' => $action['description'] ?? 'Открыть хаб заявок.',
				),
			)
		);
	}
}
add_action( 'admin_bar_menu', 'wipe_clean_register_leads_admin_bar_node', 81 );

if ( ! function_exists( 'wipe_clean_render_editor_toolbar' ) ) {
	function wipe_clean_render_editor_toolbar() {
		if ( ! wipe_clean_can_use_editor_toolbar() ) {
			return;
		}

		$actions = wipe_clean_get_editor_toolbar_actions();

		if ( empty( $actions ) ) {
			return;
		}

		$context = wipe_clean_get_editor_toolbar_context();
		?>
		<div class="wipe-admin-dock" data-wipe-admin-dock>
			<div class="wipe-admin-dock__panel" id="wipe-admin-dock-panel" data-wipe-admin-dock-panel hidden>
				<div class="wipe-admin-dock__panel-orb wipe-admin-dock__panel-orb--a" aria-hidden="true"></div>
				<div class="wipe-admin-dock__panel-orb wipe-admin-dock__panel-orb--b" aria-hidden="true"></div>
				<div class="wipe-admin-dock__head">
					<div class="wipe-admin-dock__eyebrow"><?php echo esc_html( (string) ( $context['eyebrow'] ?? 'Админка' ) ); ?></div>
					<div class="wipe-admin-dock__title"><?php echo esc_html( (string) ( $context['title'] ?? 'Быстрые действия' ) ); ?></div>
					<div class="wipe-admin-dock__line" aria-hidden="true"></div>
				</div>
				<div class="wipe-admin-dock__actions">
					<?php foreach ( $actions as $index => $action ) : ?>
						<a class="wipe-admin-dock__action wipe-admin-dock__action--<?php echo esc_attr( (string) ( $action['variant'] ?? 'default' ) ); ?>" href="<?php echo esc_url( (string) $action['url'] ); ?>" style="--wipe-admin-action-index:<?php echo esc_attr( (string) $index ); ?>;">
							<span class="wipe-admin-dock__action-mark" aria-hidden="true"></span>
							<span class="wipe-admin-dock__action-copy">
								<span class="wipe-admin-dock__action-title"><?php echo esc_html( (string) $action['label'] ); ?></span>
								<?php if ( ! empty( $action['description'] ) ) : ?>
									<span class="wipe-admin-dock__action-text"><?php echo esc_html( (string) $action['description'] ); ?></span>
								<?php endif; ?>
							</span>
						</a>
					<?php endforeach; ?>
				</div>
			</div>

			<button class="wipe-admin-dock__trigger" type="button" data-wipe-admin-dock-toggle aria-expanded="false" aria-controls="wipe-admin-dock-panel">
				<span class="wipe-admin-dock__trigger-shine" aria-hidden="true"></span>
				<span class="wipe-admin-dock__trigger-badge" aria-hidden="true">WC</span>
				<span class="wipe-admin-dock__trigger-copy">
					<span class="wipe-admin-dock__trigger-eyebrow"><?php echo esc_html( (string) ( $context['eyebrow'] ?? 'Админка' ) ); ?></span>
					<span class="wipe-admin-dock__trigger-title">Быстрое меню</span>
				</span>
			</button>
		</div>
		<style>
			.wipe-admin-dock{--dock-glow-x:76%;--dock-glow-y:18%;position:fixed;right:20px;bottom:20px;z-index:10000;display:grid;justify-items:end;gap:16px}
			.wipe-admin-dock__trigger,.wipe-admin-dock__panel{position:relative;overflow:hidden}
			.wipe-admin-dock__trigger{display:flex;align-items:center;gap:12px;min-height:72px;padding:12px 18px 12px 12px;border:0;border-radius:24px;background:
				radial-gradient(circle at var(--dock-glow-x) var(--dock-glow-y),rgba(255,255,255,.95) 0,rgba(255,255,255,.6) 18%,rgba(255,255,255,0) 44%),
				linear-gradient(145deg,rgba(255,255,255,.96) 0%,rgba(242,251,255,.94) 45%,rgba(223,246,252,.98) 100%);
				box-shadow:0 20px 42px rgba(21,15,49,.16),0 0 0 1px rgba(64,165,193,.14),inset 0 1px 0 rgba(255,255,255,.85);
				cursor:pointer;transform:translateZ(0);transition:transform .28s ease,box-shadow .28s ease,border-radius .28s ease;animation:wipeAdminFloat 4.2s ease-in-out infinite}
			.wipe-admin-dock__trigger:hover{transform:translateY(-4px) scale(1.01);box-shadow:0 28px 54px rgba(21,15,49,.2),0 0 0 1px rgba(64,165,193,.24),inset 0 1px 0 rgba(255,255,255,.92)}
			.wipe-admin-dock__trigger-shine{position:absolute;inset:-120% auto auto -20%;width:68%;height:260%;background:linear-gradient(90deg,rgba(255,255,255,0) 0%,rgba(255,255,255,.48) 42%,rgba(255,255,255,0) 100%);transform:rotate(16deg);animation:wipeAdminShine 4.8s ease-in-out infinite}
			.wipe-admin-dock__trigger-badge{display:grid;place-items:center;width:46px;height:46px;border-radius:16px;background:linear-gradient(180deg,#2db2d7 0%,#0086b3 100%);box-shadow:0 10px 18px rgba(0,134,179,.28),inset 0 -2px 0 rgba(0,0,0,.08);color:#fff;font:800 14px/1 "Manrope",sans-serif;letter-spacing:.04em}
			.wipe-admin-dock__trigger-copy{position:relative;display:grid;gap:3px;text-align:left}
			.wipe-admin-dock__trigger-eyebrow{font:700 10px/1.1 "Manrope",sans-serif;letter-spacing:.14em;text-transform:uppercase;color:#40a5c1}
			.wipe-admin-dock__trigger-title{font:800 15px/1.2 "Golos Text",sans-serif;color:#150f31}
			.wipe-admin-dock__panel{width:min(408px,calc(100vw - 24px));padding:20px;border-radius:30px;background:
				radial-gradient(circle at var(--dock-glow-x) var(--dock-glow-y),rgba(255,255,255,.94) 0%,rgba(255,255,255,.58) 18%,rgba(255,255,255,0) 46%),
				linear-gradient(160deg,rgba(255,252,248,.98) 0%,rgba(252,255,255,.96) 55%,rgba(242,251,255,.98) 100%);
				backdrop-filter:blur(20px);border:1px solid rgba(64,165,193,.16);box-shadow:0 28px 64px rgba(21,15,49,.22),inset 0 1px 0 rgba(255,255,255,.78);
				transform-origin:100% 100%;opacity:0;transform:translateY(16px) scale(.94) rotateX(8deg);pointer-events:none}
			.wipe-admin-dock__panel-orb{position:absolute;border-radius:999px;filter:blur(4px);pointer-events:none}
			.wipe-admin-dock__panel-orb--a{top:-26px;right:-18px;width:108px;height:108px;background:radial-gradient(circle,rgba(64,165,193,.28) 0%,rgba(64,165,193,0) 72%)}
			.wipe-admin-dock__panel-orb--b{left:-14px;bottom:18px;width:94px;height:94px;background:radial-gradient(circle,rgba(255,193,94,.24) 0%,rgba(255,193,94,0) 74%)}
			.wipe-admin-dock.is-open .wipe-admin-dock__panel{opacity:1;transform:translateY(0) scale(1) rotateX(0);pointer-events:auto;transition:opacity .28s ease,transform .28s cubic-bezier(.2,.8,.2,1)}
			.wipe-admin-dock__head{position:relative;display:grid;gap:6px;margin-bottom:16px;padding-right:24px}
			.wipe-admin-dock__eyebrow{font:700 10px/1.1 "Manrope",sans-serif;letter-spacing:.16em;text-transform:uppercase;color:#40a5c1}
			.wipe-admin-dock__title{font:800 22px/1.08 "Golos Text",sans-serif;color:#150f31;max-width:280px}
			.wipe-admin-dock__line{width:84px;height:4px;border-radius:999px;background:linear-gradient(90deg,#2db2d7 0%,rgba(45,178,215,.2) 100%);box-shadow:0 8px 20px rgba(45,178,215,.24)}
			.wipe-admin-dock__actions{display:grid;gap:11px}
			.wipe-admin-dock__action{position:relative;display:grid;grid-template-columns:16px minmax(0,1fr);gap:13px;align-items:flex-start;padding:15px 16px;border-radius:22px;background:
				linear-gradient(180deg,rgba(255,255,255,.96) 0%,rgba(248,252,255,.98) 100%);
				text-decoration:none;box-shadow:0 12px 24px rgba(21,15,49,.08),inset 0 1px 0 rgba(255,255,255,.76);
				transform:translateY(14px) scale(.98);opacity:0;transition:transform .26s ease,box-shadow .26s ease,opacity .26s ease,background .26s ease;transition-delay:calc(var(--wipe-admin-action-index,0) * .045s)}
			.wipe-admin-dock__action::after{content:"";position:absolute;inset:1px;border-radius:21px;background:radial-gradient(circle at 0% 0%,rgba(255,255,255,.55),rgba(255,255,255,0) 48%);opacity:.9;pointer-events:none}
			.wipe-admin-dock.is-open .wipe-admin-dock__action{transform:translateY(0) scale(1);opacity:1}
			.wipe-admin-dock__action:hover{transform:translateY(-3px) scale(1.01);box-shadow:0 18px 30px rgba(21,15,49,.12),inset 0 1px 0 rgba(255,255,255,.84)}
			.wipe-admin-dock__action-mark{display:block;width:16px;height:16px;margin-top:4px;border-radius:999px;background:linear-gradient(180deg,#40a5c1 0%,#0086b3 100%);box-shadow:0 0 0 6px rgba(64,165,193,.12),0 8px 14px rgba(0,134,179,.16)}
			.wipe-admin-dock__action--edit .wipe-admin-dock__action-mark{background:linear-gradient(180deg,#6c7bff 0%,#4150dd 100%);box-shadow:0 0 0 6px rgba(65,80,221,.12),0 8px 14px rgba(65,80,221,.16)}
			.wipe-admin-dock__action--settings .wipe-admin-dock__action-mark{background:linear-gradient(180deg,#f5a623 0%,#db8500 100%);box-shadow:0 0 0 6px rgba(245,166,35,.14),0 8px 14px rgba(219,133,0,.16)}
			.wipe-admin-dock__action--admin .wipe-admin-dock__action-mark{background:linear-gradient(180deg,#150f31 0%,#32275e 100%);box-shadow:0 0 0 6px rgba(21,15,49,.12),0 8px 14px rgba(21,15,49,.16)}
			.wipe-admin-dock__action-copy{display:grid;gap:4px;min-width:0}
			.wipe-admin-dock__action-title{font:800 15px/1.18 "Golos Text",sans-serif;color:#150f31}
			.wipe-admin-dock__action-text{font:500 12px/1.45 "Manrope",sans-serif;color:#5d5779}
			@keyframes wipeAdminFloat{
				0%,100%{transform:translateY(0)}
				50%{transform:translateY(-3px)}
			}
			@keyframes wipeAdminShine{
				0%,18%,100%{transform:translateX(-120%) rotate(16deg)}
				34%,62%{transform:translateX(190%) rotate(16deg)}
			}
			@media (prefers-reduced-motion:reduce){
				.wipe-admin-dock__trigger,.wipe-admin-dock__action,.wipe-admin-dock__panel{animation:none;transition:none}
				.wipe-admin-dock__trigger-shine{display:none}
			}
			@media (max-width:767px){
				.wipe-admin-dock{right:12px;left:12px;bottom:12px;justify-items:stretch}
				.wipe-admin-dock__panel{width:100%}
				.wipe-admin-dock__trigger{justify-content:flex-start}
			}
		</style>
		<script>
			(function(){
				function initDock(){
					var root=document.querySelector('[data-wipe-admin-dock]');
					if(!root){return;}

					var toggle=root.querySelector('[data-wipe-admin-dock-toggle]');
					var panel=root.querySelector('[data-wipe-admin-dock-panel]');
					var hideTimer=0;

					if(!toggle||!panel){return;}

					var setGlow=function(event){
						var rect=(event.currentTarget||root).getBoundingClientRect();
						var x=((event.clientX-rect.left)/Math.max(rect.width,1))*100;
						var y=((event.clientY-rect.top)/Math.max(rect.height,1))*100;
						root.style.setProperty('--dock-glow-x',x.toFixed(2)+'%');
						root.style.setProperty('--dock-glow-y',y.toFixed(2)+'%');
					};

					var openPanel=function(){
						window.clearTimeout(hideTimer);
						panel.hidden=false;
						requestAnimationFrame(function(){
							root.classList.add('is-open');
							toggle.setAttribute('aria-expanded','true');
						});
					};

					var closePanel=function(){
						root.classList.remove('is-open');
						toggle.setAttribute('aria-expanded','false');
						window.clearTimeout(hideTimer);
						hideTimer=window.setTimeout(function(){
							if(!root.classList.contains('is-open')){
								panel.hidden=true;
							}
						},280);
					};

					toggle.addEventListener('click',function(event){
						event.preventDefault();
						if(root.classList.contains('is-open')){
							closePanel();
							return;
						}
						openPanel();
					});

					toggle.addEventListener('pointermove',setGlow);
					panel.addEventListener('pointermove',setGlow);

					document.addEventListener('click',function(event){
						if(!root.contains(event.target)){
							closePanel();
						}
					});

					document.addEventListener('keydown',function(event){
						if(event.key==='Escape'){
							closePanel();
						}
					});
				}

				if(document.readyState==='loading'){
					document.addEventListener('DOMContentLoaded',initDock,{once:true});
				}else{
					initDock();
				}
			})();
		</script>
		<?php
	}
}
add_action( 'wp_footer', 'wipe_clean_render_editor_toolbar', 999 );

if ( ! function_exists( 'wipe_clean_create_services_archive_page' ) ) {
	function wipe_clean_create_services_archive_page() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( esc_html__( 'Недостаточно прав для выполнения действия.', 'wipe-clean' ) );
		}

		$url = function_exists( 'wipe_clean_get_services_archive_settings_url' )
			? wipe_clean_get_services_archive_settings_url()
			: '';

		wp_safe_redirect( $url ? $url : admin_url() );
		exit;
	}
}
add_action( 'admin_post_wipe_clean_create_services_archive_page', 'wipe_clean_create_services_archive_page' );
