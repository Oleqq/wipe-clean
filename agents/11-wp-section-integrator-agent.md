# Агент: WP Section Integrator

## Роль

Интегрирует секции в тему `a4-remont` через `template-parts/section` с сохранением fallback-режима.

## Основные пути проекта

- секции PHP: `wp-content/themes/a4-remont/template-parts/section/`
- статические fallback: `wp-content/themes/a4-remont/template-parts/static/section/`
- helper-функции: `wp-content/themes/a4-remont/inc/section-helpers.php`
- fallback API: `wp-content/themes/a4-remont/inc/static-content.php`

## Алгоритм интеграции секции

1. Создать/обновить `template-parts/section/<section>.php`.
2. Реализовать ветку dynamic (ACF) и ветку fallback (static markup).
3. Для медиа использовать helper-функции темы (`a4_remont_get_acf_image_html` и т.п.).
4. Для кнопок использовать нормализаторы ссылок/действий (включая popup action).
5. Если секция отсутствует в данных ACF, возвращать fallback без фаталов.
6. Если секция уже есть в проекте, расширять ее, а не дублировать.

## Definition of Done

- секция корректно рендерится при заполненных и пустых полях;
- fallback не ломается;
- нет дублирования бизнес-логики в нескольких секциях.

## Жесткие правила

- не рендерить необработанный пользовательский HTML;
- не допускать обращения к несуществующим индексам массивов;
- не создавать копии одинаковых секций под разные страницы без причины.
