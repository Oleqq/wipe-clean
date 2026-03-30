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
        initial: getResponsiveCount(section, mediaQuery.matches, "data-initial-desktop", "data-initial-mobile", 6),
        step: getResponsiveCount(section, mediaQuery.matches, "data-step-desktop", "data-step-mobile", 6)
    };
};

const setButtonState = (sectionState, state) => {
    var button = sectionState.button;
    var footer = sectionState.footer;
    var loader = sectionState.loader;
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
    button.setAttribute("aria-hidden", isLoading ? "true" : "false");

    if (footer) {
        footer.classList.toggle("is-loading", isLoading);
    }

    if (loader) {
        loader.setAttribute("aria-hidden", isLoading ? "false" : "true");
    }

    if (isExhausted) {
        button.__hideTimeoutId = window.setTimeout(function() {
            button.hidden = true;
            button.__hideTimeoutId = 0;
        }, 250);
        return;
    }

    button.hidden = false;
};

const renderItems = (sectionState, options) => {
    var settings = getCurrentSettings(sectionState.section, sectionState.mediaQuery);
    var visibleCount = typeof options.visibleCount === "number" ? options.visibleCount : sectionState.visibleCount;
    var animate = Boolean(options.animate);
    var startIndex = typeof options.startIndex === "number" ? options.startIndex : 0;

    sectionState.items.forEach(function(item, index) {
        var isVisible = index < visibleCount;

        item.hidden = !isVisible;

        if (animate && index >= startIndex && isVisible) {
            item.style.setProperty("--before-after-delay", ((index - startIndex) * 0.08) + "s");
            item.classList.add("is-revealed");
        }
    });

    sectionState.visibleCount = visibleCount;

    if (sectionState.button) {
        if (visibleCount >= sectionState.items.length) {
            setButtonState(sectionState, "exhausted");
        } else {
            setButtonState(sectionState, "idle");
        }
    }

    sectionState.section.setAttribute("data-visible-count", String(visibleCount));
    sectionState.section.setAttribute("data-current-step", String(settings.step));
};

const createSectionState = (section, mediaQuery) => {
    var items = Array.from(section.querySelectorAll("[data-before-after-item]"));
    var button = section.querySelector("[data-before-after-more]");
    var footer = section.querySelector("[data-before-after-footer]");
    var loader = section.querySelector("[data-before-after-loader]");

    if (!items.length) {
        return null;
    }

    return {
        section: section,
        items: items,
        button: button,
        footer: footer,
        loader: loader,
        mediaQuery: mediaQuery,
        visibleCount: 0
    };
};

export const registerBeforeAfterResultsLoadMore = () => {
    var sections = Array.from(document.querySelectorAll("[data-before-after-results]"));

    if (!sections.length) {
        return function() {};
    }

    var mobileMedia = window.matchMedia("(max-width: 650px)");

    var cleanups = sections.map(function(section) {
        var sectionState = createSectionState(section, mobileMedia);
        var loadingTimeoutId = 0;

        if (!sectionState) {
            return null;
        }

        var initialSettings = getCurrentSettings(section, mobileMedia);

        renderItems(sectionState, {
            visibleCount: initialSettings.initial,
            animate: false
        });

        var handleRevealAnimationEnd = function(event) {
            var item = event.target.closest("[data-before-after-item]");

            if (!item || !section.contains(item)) {
                return;
            }

            item.classList.remove("is-revealed");
            item.style.removeProperty("--before-after-delay");
        };

        var handleClick = function() {
            if (!sectionState.button || sectionState.button.classList.contains("is-loading")) {
                return;
            }

            var settings = getCurrentSettings(section, mobileMedia);
            var nextVisibleCount = Math.min(sectionState.visibleCount + settings.step, sectionState.items.length);
            var startIndex = sectionState.visibleCount;

            setButtonState(sectionState, "loading");

            loadingTimeoutId = window.setTimeout(function() {
                renderItems(sectionState, {
                    visibleCount: nextVisibleCount,
                    startIndex: startIndex,
                    animate: true
                });
                loadingTimeoutId = 0;
            }, 380);
        };

        var handleMediaChange = function() {
            var settings = getCurrentSettings(section, mobileMedia);
            var nextVisibleCount = Math.max(sectionState.visibleCount, settings.initial);

            renderItems(sectionState, {
                visibleCount: Math.min(nextVisibleCount, sectionState.items.length),
                animate: false
            });
        };

        if (sectionState.button) {
            sectionState.button.addEventListener("click", handleClick);
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

            if (sectionState.button) {
                if (sectionState.button.__hideTimeoutId) {
                    window.clearTimeout(sectionState.button.__hideTimeoutId);
                    sectionState.button.__hideTimeoutId = 0;
                }

                sectionState.button.removeEventListener("click", handleClick);
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
