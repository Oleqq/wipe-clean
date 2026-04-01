<?php
/**
 * Default data and fallbacks for blog archive and single post pages.
 *
 * @package wipe-clean
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wipe_clean_blog_image' ) ) {
	function wipe_clean_blog_image( $path, $alt = '', $width = 0, $height = 0 ) {
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

if ( ! function_exists( 'wipe_clean_blog_link' ) ) {
	function wipe_clean_blog_link( $title, $url = '#', $target = '' ) {
		return array(
			'title'  => (string) $title,
			'url'    => (string) $url,
			'target' => (string) $target,
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_archive_default_items' ) ) {
	function wipe_clean_get_blog_archive_default_items() {
		$placeholder = wipe_clean_blog_image(
			'static/images/section/blog-archive/blog-card-placeholder.svg',
			'Превью статьи блога'
		);

		return array(
			array(
				'title'     => 'Как поддерживать чистоту в квартире между генеральными уборками',
				'excerpt'   => 'Разбираем понятный домашний режим, который помогает сохранять порядок без перегруза и не доводить квартиру до авральной уборки.',
				'dateLabel' => '29 / 01 / 2025',
				'dateTime'  => '2025-01-29',
				'href'      => '#',
				'image'     => $placeholder,
				'imageAlt'  => 'Превью статьи о поддерживающей уборке',
			),
			array(
				'title'     => 'Какие ошибки чаще всего совершают при уборке кухни',
				'excerpt'   => 'Показываем, какие привычные действия мешают добиться чистоты на кухне и почему профессиональный подход экономит время и силы.',
				'dateLabel' => '24 / 01 / 2025',
				'dateTime'  => '2025-01-24',
				'href'      => '#',
				'image'     => $placeholder,
				'imageAlt'  => 'Превью статьи об уборке кухни',
			),
			array(
				'title'     => 'Чем отличается генеральная уборка от поддерживающего клининга',
				'excerpt'   => 'Сравниваем два формата уборки, чтобы было проще выбрать подходящий сценарий под ваш объект, график и текущий уровень загрязнений.',
				'dateLabel' => '18 / 01 / 2025',
				'dateTime'  => '2025-01-18',
				'href'      => '#',
				'image'     => $placeholder,
				'imageAlt'  => 'Превью статьи о форматах клининга',
			),
			array(
				'title'     => 'Как подготовить квартиру к приезду клининговой службы',
				'excerpt'   => 'Короткий чек-лист перед выездом команды: что лучше убрать заранее, а что можно спокойно оставить на специалистов.',
				'dateLabel' => '13 / 01 / 2025',
				'dateTime'  => '2025-01-13',
				'href'      => '#',
				'image'     => $placeholder,
				'imageAlt'  => 'Превью статьи о подготовке квартиры к клинингу',
			),
			array(
				'title'     => 'Безопасная химия для дома: что важно знать заказчику',
				'excerpt'   => 'Объясняем, как выбираются составы для разных поверхностей и почему грамотная химия важна для семей с детьми и питомцами.',
				'dateLabel' => '09 / 01 / 2025',
				'dateTime'  => '2025-01-09',
				'href'      => '#',
				'image'     => $placeholder,
				'imageAlt'  => 'Превью статьи о безопасной бытовой химии',
			),
			array(
				'title'     => '5 признаков, что офису уже нужна профессиональная уборка',
				'excerpt'   => 'Рассказываем, по каким сигналам становится понятно, что регулярной поверхностной уборки уже недостаточно для рабочего пространства.',
				'dateLabel' => '05 / 01 / 2025',
				'dateTime'  => '2025-01-05',
				'href'      => '#',
				'image'     => $placeholder,
				'imageAlt'  => 'Превью статьи об уборке офисов',
			),
			array(
				'title'     => 'Как ухаживать за окнами, чтобы реже заказывать мойку',
				'excerpt'   => 'Собрали простые рекомендации по уходу за стеклом, рамами и фурнитурой, которые реально помогают дольше сохранять чистоту.',
				'dateLabel' => '28 / 12 / 2024',
				'dateTime'  => '2024-12-28',
				'href'      => '#',
				'image'     => $placeholder,
				'imageAlt'  => 'Превью статьи о мойке окон',
			),
			array(
				'title'     => 'Почему срочная уборка требует отдельной организации',
				'excerpt'   => 'Разбираем, как формируется срочный выезд, что влияет на скорость выполнения работ и как заранее согласовать реалистичные сроки.',
				'dateLabel' => '22 / 12 / 2024',
				'dateTime'  => '2024-12-22',
				'href'      => '#',
				'image'     => $placeholder,
				'imageAlt'  => 'Превью статьи о срочной уборке',
			),
			array(
				'title'     => 'Как сократить время на уборку в большом доме',
				'excerpt'   => 'Советы по зонированию, графику и последовательности действий, которые помогают поддерживать порядок даже на большой площади.',
				'dateLabel' => '16 / 12 / 2024',
				'dateTime'  => '2024-12-16',
				'href'      => '#',
				'image'     => $placeholder,
				'imageAlt'  => 'Превью статьи об уборке домов',
			),
			array(
				'title'     => 'Что входит в уборку после ремонта и почему это отдельный формат',
				'excerpt'   => 'Пыль после стройки, следы смесей, мусор и чувствительные покрытия требуют другого подхода, чем обычная бытовая уборка.',
				'dateLabel' => '11 / 12 / 2024',
				'dateTime'  => '2024-12-11',
				'href'      => '#',
				'image'     => $placeholder,
				'imageAlt'  => 'Превью статьи об уборке после ремонта',
			),
			array(
				'title'     => 'Как не испортить деликатные поверхности при самостоятельной уборке',
				'excerpt'   => 'Показываем, с какими материалами чаще всего возникают проблемы и какие ошибки обходятся дороже всего.',
				'dateLabel' => '06 / 12 / 2024',
				'dateTime'  => '2024-12-06',
				'href'      => '#',
				'image'     => $placeholder,
				'imageAlt'  => 'Превью статьи о деликатных поверхностях',
			),
			array(
				'title'     => 'Регулярная уборка или разовые выезды: что выгоднее в долгую',
				'excerpt'   => 'Сравниваем два сценария обслуживания и объясняем, когда регулярный формат помогает реально снизить расходы на чистоту.',
				'dateLabel' => '30 / 11 / 2024',
				'dateTime'  => '2024-11-30',
				'href'      => '#',
				'image'     => $placeholder,
				'imageAlt'  => 'Превью статьи о регулярном клининге',
			),
			array(
				'title'     => 'Как выбрать клининговую компанию и не пожалеть',
				'excerpt'   => 'Собрали список критериев, на которые стоит смотреть до оформления заявки: от прозрачности сметы до контроля качества работ.',
				'dateLabel' => '25 / 11 / 2024',
				'dateTime'  => '2024-11-25',
				'href'      => '#',
				'image'     => $placeholder,
				'imageAlt'  => 'Превью статьи о выборе клининговой компании',
			),
			array(
				'title'     => 'Что влияет на итоговую стоимость клининга и как считать честно',
				'excerpt'   => 'Поясняем, как формируется стоимость уборки и почему открытый расчёт помогает избежать неприятных сюрпризов в смете.',
				'dateLabel' => '19 / 11 / 2024',
				'dateTime'  => '2024-11-19',
				'href'      => '#',
				'image'     => $placeholder,
				'imageAlt'  => 'Превью статьи о расчёте стоимости уборки',
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_related_default_items' ) ) {
	function wipe_clean_get_blog_related_default_items() {
		return array_slice( wipe_clean_get_blog_archive_default_items(), 0, 4 );
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_single_default_content_html' ) ) {
	function wipe_clean_get_blog_single_default_content_html() {
		$figure_one = esc_url( wipe_clean_asset_uri( 'static/images/section/blog-article-content/blog-article-content-figure-1-placeholder.svg' ) );
		$figure_two = esc_url( wipe_clean_asset_uri( 'static/images/section/blog-article-content/blog-article-content-figure-2-placeholder.svg' ) );

		return sprintf(
			<<<'HTML'
<section class="entry-content__section">
	<h2>Почему уборка важна для здоровья питомца</h2>
	<p>Наши животные проводят почти весь день дома: спят на полу, играют на ковре, контактируют с мисками, лежанками и мягкой мебелью. Если в квартире скапливаются пыль, шерсть, бактерии и бытовые загрязнения, это быстро начинает влиять на самочувствие питомца.</p>
	<div class="entry-content__feature-list">
		<article class="entry-content__feature">
			<h3 class="entry-content__feature-title">Профилактика инфекций</h3>
			<p class="entry-content__feature-text">Постоянная гигиена зон кормления и отдыха снижает риск развития опасных бактериальных заболеваний.</p>
		</article>
		<article class="entry-content__feature">
			<h3 class="entry-content__feature-title">Снижение уровня стресса</h3>
			<p class="entry-content__feature-text">Чистый дом без резких посторонних запахов помогает любимцу чувствовать себя спокойно и безопасно.</p>
		</article>
		<article class="entry-content__feature">
			<h3 class="entry-content__feature-title">Защита иммунитета</h3>
			<p class="entry-content__feature-text">Когда уборка проводится правильно, организм питомца не тратит ресурсы на борьбу с бытовыми токсинами и пылью.</p>
		</article>
	</div>
	<p>Когда порядок наведен с умом, животные меньше болеют, а дом становится по-настоящему безопасным и приятным для всех членов семьи.</p>
</section>
<figure class="entry-content__figure">
	<img src="%1$s" alt="Иллюстрация к блоку о пользе регулярной уборки для питомцев" loading="lazy">
</figure>
<section class="entry-content__section">
	<h2>Какие опасности для животного скрываются в грязном доме</h2>
	<p>Если в углах копится грязь, дом перестаёт быть надёжным убежищем для питомца. В собственной среде обитания животные сталкиваются с пылью и мусором ежеминутно, и это часто бьёт по их самочувствию.</p>
	<p class="entry-content__list-lead">Чтобы в доме было безопасно для всех, нужно убрать:</p>
	<ul class="entry-content__list">
		<li class="entry-content__list-item"><strong>Шерсть и пыль.</strong> Скопления волос и домашнего мусора часто становятся причиной кожных раздражений, зуда и проблем с дыханием у животных.</li>
		<li class="entry-content__list-item"><strong>Бактерии и грибки.</strong> Влажные участки и остатки органики становятся идеальной средой для размножения микробов.</li>
		<li class="entry-content__list-item"><strong>Остатки старого корма.</strong> Забытые под мебелью частицы еды быстро портятся и могут вызвать отравление.</li>
		<li class="entry-content__list-item"><strong>Паразитов.</strong> Пыльные плинтусы и ковры могут скрывать личинки блох и микроскопических клещей.</li>
		<li class="entry-content__list-item"><strong>Застарелые запахи.</strong> Молекулы старых загрязнений заставляют животных нервничать и нарушают комфорт дома.</li>
	</ul>
	<p>Хорошая уборка убирает все эти риски ещё до того, как у питомца начнутся проблемы со здоровьем.</p>
</section>
<section class="entry-content__section">
	<h2>Какие зоны в доме требуют регулярной уборки</h2>
	<p>Больше всего внимания стоит уделять тем местам, где животные бывают чаще всего. Чтобы дома было действительно безопасно, мало просто протереть пол: нужно системно пройтись по каждой зоне.</p>
	<ul class="entry-content__list">
		<li class="entry-content__list-item"><strong>Место кормления.</strong> Миски и подставки нужно очищать ежедневно.</li>
		<li class="entry-content__list-item"><strong>Зона туалета.</strong> Лотки и прилегающее пространство требуют регулярной дезинфекции безопасными составами.</li>
		<li class="entry-content__list-item"><strong>Мягкая мебель и ковры.</strong> Здесь скапливается максимум шерсти и эпидермиса.</li>
		<li class="entry-content__list-item"><strong>Подоконники и лежанки.</strong> Любимые места отдыха нужно регулярно очищать от уличной пыли.</li>
		<li class="entry-content__list-item"><strong>Прихожая.</strong> Здесь животные чаще всего контактируют с уличной грязью и реагентами.</li>
	</ul>
</section>
<figure class="entry-content__figure">
	<img src="%2$s" alt="Иллюстрация к блоку о безопасных средствах для уборки дома с животными" loading="lazy">
</figure>
<section class="entry-content__section">
	<h2>Какие средства подходят для домов с животными</h2>
	<p>Главный принцип работы в доме, где есть питомцы, это полное отсутствие агрессивной бытовой химии. Безопасная уборка должна проводиться средствами без хлора, аммиака и резких синтетических ароматов.</p>
	<ul class="entry-content__list">
		<li class="entry-content__list-item"><strong>Продукты с хлором.</strong> Такие составы раздражают слизистые и тяжело переносятся животными.</li>
		<li class="entry-content__list-item"><strong>Средства с аммиаком и фосфатами.</strong> Они могут вызвать аллергию и даже отравление.</li>
		<li class="entry-content__list-item"><strong>Химию с синтетическими ароматизаторами.</strong> Сильные запахи часто вызывают у питомцев тревогу.</li>
	</ul>
	<p>Для наведения порядка лучше выбирать специализированные эко-средства, чтобы уборка была максимально безопасной и не создавала проблем даже чувствительным животным.</p>
</section>
<section class="entry-content__section">
	<h2>Регулярность клининга и его влияние на здоровье</h2>
	<p>Если следить за чистотой постоянно, ваши животные будут дольше оставаться бодрыми и активными. Регулярный клининг не даёт накапливаться микробам и пыли, которые незаметно портят здоровье и подрывают иммунитет.</p>
	<p>Когда график плотный, поддерживать идеальный порядок своими силами бывает накладно. В таких случаях лучше доверить работу специалистам, которые умеют безопасно и системно ухаживать за домом, где живут питомцы.</p>
</section>
HTML,
			$figure_one,
			$figure_two
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_archive_default_sections_map' ) ) {
	function wipe_clean_get_blog_archive_default_sections_map() {
		$contacts = function_exists( 'wipe_clean_get_front_page_section_defaults' )
			? wipe_clean_get_front_page_section_defaults( 'contacts' )
			: array( 'acf_fc_layout' => 'contacts' );

		return array(
			'blog_archive' => array(
				'acf_fc_layout'       => 'blog_archive',
				'title'               => "Статьи и новости\nв сфере уборки",
				'text_top'            => 'В этом разделе вы найдёте актуальные статьи и новости из мира профессиональной уборки: разбираем тренды, делимся экспертными советами и рассказываем о современных средствах и технологиях клининга.',
				'text_bottom'         => 'Материалы помогут оптимизировать процесс уборки, выбрать подходящие решения для любых задач и быть в курсе последних достижений отрасли.',
				'hero_image'          => wipe_clean_blog_image(
					'static/images/section/blog-archive/blog-archive-hero.png',
					'Инвентарь и средства для профессиональной уборки',
					1536,
					1024
				),
				'button_label'        => 'Показать ещё',
				'button_loading_label'=> 'Загрузка...',
				'initial_desktop'     => 10,
				'initial_mobile'      => 6,
				'step_desktop'        => 4,
				'step_mobile'         => 3,
				'items'               => wipe_clean_get_blog_archive_default_items(),
			),
			'contacts'     => array_merge(
				$contacts,
				array(
					'acf_fc_layout' => 'contacts',
				)
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_single_default_sections_map' ) ) {
	function wipe_clean_get_blog_single_default_sections_map() {
		$contacts = function_exists( 'wipe_clean_get_front_page_section_defaults' )
			? wipe_clean_get_front_page_section_defaults( 'contacts' )
			: array( 'acf_fc_layout' => 'contacts' );

		return array(
			'blog_article_hero'    => array(
				'acf_fc_layout' => 'blog_article_hero',
				'title'         => "Уборка\n«Здоровый питомец»",
				'excerpt'       => 'Для тех, у кого дома живёт собака или кот, уборка перестаёт быть просто борьбой с пылью. Это прежде всего способ сделать квартиру комфортной и безопасной для всех, а не только для четвероногого друга.',
				'date_label'    => 'Дата публикации:',
				'image'         => wipe_clean_blog_image(
					'static/images/section/blog-article-hero/blog-article-hero-media-placeholder.svg',
					'Иллюстрация к статье об уборке дома с питомцами'
				),
			),
			'blog_article_content' => array(
				'acf_fc_layout' => 'blog_article_content',
				'content'       => wipe_clean_get_blog_single_default_content_html(),
			),
			'related_posts'        => array(
				'acf_fc_layout' => 'related_posts',
				'title'         => 'Рекомендованные статьи',
				'mobile_limit'  => 3,
				'primary_action'=> wipe_clean_blog_link( 'Наши статьи', home_url( '/blog/' ) ),
				'secondary_action' => wipe_clean_blog_link( 'Наши услуги', home_url( '/services/' ) ),
				'items'         => wipe_clean_get_blog_related_default_items(),
			),
			'contacts'             => array_merge(
				$contacts,
				array(
					'acf_fc_layout' => 'contacts',
				)
			),
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_archive_layout_order' ) ) {
	function wipe_clean_get_blog_archive_layout_order() {
		return array(
			'blog_archive',
			'contacts',
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_single_layout_order' ) ) {
	function wipe_clean_get_blog_single_layout_order() {
		return array(
			'blog_article_hero',
			'blog_article_content',
			'related_posts',
			'contacts',
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_archive_section_defaults' ) ) {
	function wipe_clean_get_blog_archive_section_defaults( $layout ) {
		$defaults_map = wipe_clean_get_blog_archive_default_sections_map();

		return $defaults_map[ $layout ] ?? array(
			'acf_fc_layout' => $layout,
		);
	}
}

if ( ! function_exists( 'wipe_clean_get_blog_single_section_defaults' ) ) {
	function wipe_clean_get_blog_single_section_defaults( $layout ) {
		$defaults_map = wipe_clean_get_blog_single_default_sections_map();

		return $defaults_map[ $layout ] ?? array(
			'acf_fc_layout' => $layout,
		);
	}
}
