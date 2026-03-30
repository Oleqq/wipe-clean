const getResponsiveCount = (section, isMobile, desktopName, mobileName, fallback) => {
    var desktopValue = Number(section.getAttribute(desktopName));
    var mobileValue = Number(section.getAttribute(mobileName));

    if (isMobile && !Number.isNaN(mobileValue)) {
        return mobileValue;
    }

    if (!Number.isNaN(desktopValue)) {
        return desktopValue;
    }

    return fallback;
};

const getCurrentSettings = (section, mediaQuery) => {
    return {
        initial: getResponsiveCount(section, mediaQuery.matches, "data-initial-desktop", "data-initial-mobile", 10),
        step: getResponsiveCount(section, mediaQuery.matches, "data-step-desktop", "data-step-mobile", 2)
    };
};

const getCurrentColumnCount = (section, mediaQuery) => {
    return getResponsiveCount(section, mediaQuery.matches, "data-columns-desktop", "data-columns-mobile", 4);
};

const getCurrentItemOrder = (item, mediaQuery) => {
    var attrName = mediaQuery.matches ? "data-mobile-order" : "data-desktop-order";
    var value = Number(item.getAttribute(attrName));

    if (!Number.isNaN(value)) {
        return value;
    }

    return Number(item.getAttribute("data-item-index")) || 0;
};

const getCurrentItemColumn = (item, mediaQuery) => {
    var attrName = mediaQuery.matches ? "data-mobile-column" : "data-desktop-column";
    var value = Number(item.getAttribute(attrName));

    if (!Number.isNaN(value) && value > 0) {
        return value;
    }

    return 1;
};

const getOrderedItems = (items, mediaQuery) => {
    return items.slice().sort(function(itemA, itemB) {
        var orderA = getCurrentItemOrder(itemA, mediaQuery);
        var orderB = getCurrentItemOrder(itemB, mediaQuery);

        if (orderA !== orderB) {
            return orderA - orderB;
        }

        return getCurrentItemColumn(itemA, mediaQuery) - getCurrentItemColumn(itemB, mediaQuery);
    });
};

const setButtonState = (button, state) => {
    var isLoading = state === "loading";
    var isExhausted = state === "exhausted";

    if (!button) {
        return;
    }

    if (button.__hideTimeoutId) {
        window.clearTimeout(button.__hideTimeoutId);
        button.__hideTimeoutId = 0;
    }

    button.classList.toggle("is-loading", isLoading);
    button.classList.toggle("is-exhausted", isExhausted);
    button.disabled = isLoading;
    button.setAttribute("aria-busy", isLoading ? "true" : "false");

    if (isExhausted) {
        button.__hideTimeoutId = window.setTimeout(function() {
            button.hidden = true;
            button.__hideTimeoutId = 0;
        }, 250);
        return;
    }

    button.hidden = false;
};

const setFooterLoadingState = (footer, isLoading) => {
    if (!footer) {
        return;
    }

    footer.classList.toggle("is-loading", isLoading);
};

const layoutItems = (sectionState) => {
    var columnCount = getCurrentColumnCount(sectionState.section, sectionState.mediaQuery);
    var orderedItems = getOrderedItems(sectionState.items, sectionState.mediaQuery);

    sectionState.columns.forEach(function(column, index) {
        column.hidden = index >= columnCount;
        column.innerHTML = "";
    });

    orderedItems.forEach(function(item) {
        var targetColumnIndex = getCurrentItemColumn(item, sectionState.mediaQuery) - 1;
        var targetColumn = sectionState.columns[targetColumnIndex];

        if (targetColumn) {
            targetColumn.appendChild(item);
        }
    });

    sectionState.section.setAttribute("data-current-columns", String(columnCount));
};

const renderItems = (sectionState, options) => {
    var settings = getCurrentSettings(sectionState.section, sectionState.mediaQuery);
    var visibleCount = typeof options.visibleCount === "number" ? options.visibleCount : sectionState.visibleCount;
    var animate = Boolean(options.animate);
    var startIndex = typeof options.startIndex === "number" ? options.startIndex : 0;
    var orderedItems = getOrderedItems(sectionState.items, sectionState.mediaQuery);

    orderedItems.forEach(function(item, index) {
        var isVisible = index < visibleCount;

        item.hidden = !isVisible;

        if (animate && index >= startIndex && isVisible) {
            item.style.setProperty("--message-reviews-delay", ((index - startIndex) * 0.08) + "s");
            item.classList.add("is-revealed");
        }
    });

    sectionState.visibleCount = visibleCount;

    if (visibleCount >= sectionState.items.length) {
        setButtonState(sectionState.button, "exhausted");
    } else {
        setButtonState(sectionState.button, "idle");
    }

    sectionState.section.setAttribute("data-visible-count", String(visibleCount));
    sectionState.section.setAttribute("data-current-step", String(settings.step));
};

export const registerMessageReviewsLoadMore = () => {
    var sections = Array.from(document.querySelectorAll("[data-message-reviews]"));

    if (!sections.length) {
        return function() {};
    }

    var mobileMedia = window.matchMedia("(max-width: 650px)");

    var cleanups = sections.map(function(section) {
        var items = Array.from(section.querySelectorAll("[data-message-review-item]"));
        var button = section.querySelector("[data-message-reviews-more]");
        var footer = section.querySelector("[data-message-reviews-footer]");
        var columns = Array.from(section.querySelectorAll("[data-message-reviews-column]"));
        var loadingTimeoutId = 0;

        if (!items.length || !columns.length) {
            return null;
        }

        var sectionState = {
            section: section,
            items: items,
            columns: columns,
            button: button,
            footer: footer,
            mediaQuery: mobileMedia,
            visibleCount: 0
        };

        layoutItems(sectionState);

        var initialSettings = getCurrentSettings(section, mobileMedia);

        renderItems(sectionState, {
            visibleCount: initialSettings.initial,
            animate: false
        });

        var handleRevealAnimationEnd = function(event) {
            var item = event.target.closest("[data-message-review-item]");

            if (!item || !section.contains(item)) {
                return;
            }

            item.classList.remove("is-revealed");
            item.style.removeProperty("--message-reviews-delay");
        };

        var handleClick = function() {
            if (!button || button.classList.contains("is-loading")) {
                return;
            }

            var settings = getCurrentSettings(section, mobileMedia);
            var nextVisibleCount = Math.min(sectionState.visibleCount + settings.step, items.length);
            var startIndex = sectionState.visibleCount;

            setButtonState(button, "loading");
            setFooterLoadingState(footer, true);

            loadingTimeoutId = window.setTimeout(function() {
                renderItems(sectionState, {
                    visibleCount: nextVisibleCount,
                    startIndex: startIndex,
                    animate: true
                });

                setFooterLoadingState(footer, false);
                loadingTimeoutId = 0;
            }, 420);
        };

        var handleMediaChange = function() {
            var settings = getCurrentSettings(section, mobileMedia);
            var nextVisibleCount = Math.max(sectionState.visibleCount, settings.initial);

            layoutItems(sectionState);
            renderItems(sectionState, {
                visibleCount: Math.min(nextVisibleCount, items.length),
                animate: false
            });
        };

        if (button) {
            button.addEventListener("click", handleClick);
        }

        section.addEventListener("animationend", handleRevealAnimationEnd);

        if (typeof mobileMedia.addEventListener === "function") {
            mobileMedia.addEventListener("change", handleMediaChange);
        } else {
            mobileMedia.addListener(handleMediaChange);
        }

        return function() {
            if (loadingTimeoutId) {
                window.clearTimeout(loadingTimeoutId);
            }

            setFooterLoadingState(footer, false);

            if (button) {
                if (button.__hideTimeoutId) {
                    window.clearTimeout(button.__hideTimeoutId);
                    button.__hideTimeoutId = 0;
                }

                button.removeEventListener("click", handleClick);
            }

            section.removeEventListener("animationend", handleRevealAnimationEnd);

            if (typeof mobileMedia.removeEventListener === "function") {
                mobileMedia.removeEventListener("change", handleMediaChange);
            } else {
                mobileMedia.removeListener(handleMediaChange);
            }
        };
    });

    return function() {
        cleanups.forEach(function(cleanup) {
            if (typeof cleanup === "function") {
                cleanup();
            }
        });
    };
};
