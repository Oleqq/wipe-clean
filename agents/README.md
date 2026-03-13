# Набор агентов для проекта `a4-remont`

Этот каталог содержит агентские markdown-доки для LLM-разработки.

Назначение набора:

- ускорять понимание структуры темы;
- помогать переносить новые дизайн-макеты в статическую верстку;
- помогать интегрировать эту верстку в WordPress по текущему стилю проекта;
- не допускать хаотичных решений в ACF, CSS, JS и шаблонах.

Это не пользовательская инструкция и не редакторская документация.  
Это рабочий набор правил для разработки статики и темы под новые макеты в рамках методологии `a4-remont`.

## Базовые принципы набора

- статическая верстка на БЭМ;
- интеграция в WordPress через `template-parts/section`;
- ACF Flexible Content и option pages как основной редакторский слой;
- fallback-разметка из `template-parts/static`;
- посекционная загрузка CSS через `inc/enqueue.php`;
- минимизация риска для уже существующего контента на проде.

## Как использовать этот набор для нового макета

1. Сначала открыть карту проекта: [00-project-structure-agent.md](./00-project-structure-agent.md).
2. Затем выбрать нужный поток:
   - чистая статическая подготовка секции;
   - JS-поведение секции;
   - интеграция секции в WordPress;
   - ACF/Flexible Content;
   - архивы, single, performance, release.
3. При добавлении новой секции или нового макета не отходить от текущих путей проекта, даже если статический исходник организован иначе.

## Вёрстка

- [00-project-structure-agent.md](./00-project-structure-agent.md)
- [01-frontend-html-scss-agent.md](./01-frontend-html-scss-agent.md)
- [02-frontend-js-interactions-agent.md](./02-frontend-js-interactions-agent.md)
- [03-frontend-section-prep-agent.md](./03-frontend-section-prep-agent.md)

## WordPress интеграция

- [11-wp-section-integrator-agent.md](./11-wp-section-integrator-agent.md)
- [12-wp-acf-flex-builder-agent.md](./12-wp-acf-flex-builder-agent.md)
- [13-wp-cpt-archive-single-agent.md](./13-wp-cpt-archive-single-agent.md)
- [14-wp-css-loading-perf-agent.md](./14-wp-css-loading-perf-agent.md)
- [15-wp-qa-release-agent.md](./15-wp-qa-release-agent.md)

## Общий принцип

Любой агент должен сохранять совместимость с текущей архитектурой темы:

1. Не ломать fallback-ветки секций.
2. Не дублировать поля ACF вручную в GUI, если они уже описаны в `inc/acf-*.php`.
3. Переиспользовать существующие секции и helper-функции.
4. Не тащить весь `style.css` как основной источник секционных стилей.
5. Для новых зависимостей секций обновлять карту зависимостей в `inc/enqueue.php`.
6. Не ломать контентные данные при обычной заливке обновленной темы на прод.
