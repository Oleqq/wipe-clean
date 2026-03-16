const getSectionParts = (section) => {
    var tabs = Array.from(section.querySelectorAll("[data-service-includes-tab]"));
    var panels = Array.from(section.querySelectorAll("[data-service-includes-panel]"));

    if (!tabs.length || !panels.length) {
        return null;
    }

    return { tabs: tabs, panels: panels };
};

const getPanelHotspots = (panel) => {
    return Array.from(panel.querySelectorAll("[data-service-includes-hotspot]"));
};

const getPanelTooltips = (panel) => {
    return Array.from(panel.querySelectorAll("[data-service-includes-tooltip]"));
};

const activateHotspot = (panel, hotspotId) => {
    var hotspots = getPanelHotspots(panel);
    var tooltips = getPanelTooltips(panel);

    hotspots.forEach(function(hotspot) {
        var isActive = hotspot.getAttribute("data-hotspot-id") === hotspotId;

        hotspot.classList.toggle("is-active", isActive);
        hotspot.setAttribute("aria-expanded", isActive ? "true" : "false");
    });

    tooltips.forEach(function(tooltip) {
        var isActive = tooltip.getAttribute("data-hotspot-id") === hotspotId;

        tooltip.classList.toggle("is-active", isActive);
        tooltip.setAttribute("aria-hidden", isActive ? "false" : "true");
    });
};

const ensurePanelHotspot = (panel) => {
    var activeHotspot = panel.querySelector("[data-service-includes-hotspot].is-active");
    var firstHotspot = panel.querySelector("[data-service-includes-hotspot]");

    if (activeHotspot) {
        activateHotspot(panel, activeHotspot.getAttribute("data-hotspot-id"));
        return;
    }

    if (firstHotspot) {
        activateHotspot(panel, firstHotspot.getAttribute("data-hotspot-id"));
    }
};

const setPanelAccessibility = (panel, isActive) => {
    var hotspots = getPanelHotspots(panel);

    panel.classList.toggle("is-active", isActive);
    panel.setAttribute("aria-hidden", isActive ? "false" : "true");

    hotspots.forEach(function(hotspot) {
        hotspot.tabIndex = isActive ? 0 : -1;
    });

    if (isActive) {
        ensurePanelHotspot(panel);
    }
};

const activateTab = (section, tabId, options) => {
    var settings = options || {};
    var parts = getSectionParts(section);

    if (!parts) {
        return;
    }

    parts.tabs.forEach(function(tab) {
        var isActive = tab.getAttribute("data-tab-id") === tabId;

        tab.classList.toggle("is-active", isActive);
        tab.setAttribute("aria-selected", isActive ? "true" : "false");
        tab.tabIndex = isActive ? 0 : -1;

        if (isActive && settings.focus) {
            tab.focus();
        }
    });

    parts.panels.forEach(function(panel) {
        setPanelAccessibility(panel, panel.getAttribute("data-tab-id") === tabId);
    });
};

const getActiveTabIndex = (tabs) => {
    return tabs.findIndex(function(tab) {
        return tab.getAttribute("aria-selected") === "true";
    });
};

const handleTabKeyboard = (section, event) => {
    var key = event.key;
    var parts = getSectionParts(section);

    if (!parts) {
        return;
    }

    if (["ArrowLeft", "ArrowUp", "ArrowRight", "ArrowDown", "Home", "End"].indexOf(key) === -1) {
        return;
    }

    event.preventDefault();

    var tabs = parts.tabs;
    var currentIndex = getActiveTabIndex(tabs);
    var nextIndex = currentIndex;

    if (key === "Home") {
        nextIndex = 0;
    }

    if (key === "End") {
        nextIndex = tabs.length - 1;
    }

    if (key === "ArrowLeft" || key === "ArrowUp") {
        nextIndex = currentIndex <= 0 ? tabs.length - 1 : currentIndex - 1;
    }

    if (key === "ArrowRight" || key === "ArrowDown") {
        nextIndex = currentIndex >= tabs.length - 1 ? 0 : currentIndex + 1;
    }

    activateTab(section, tabs[nextIndex].getAttribute("data-tab-id"), { focus: true });
};

export const registerServiceIncludesTabs = () => {
    var sections = Array.from(document.querySelectorAll("[data-service-includes]"));

    if (!sections.length) {
        return function() {};
    }

    var canHover = window.matchMedia("(hover: hover) and (pointer: fine)");

    var cleanups = sections.map(function(section) {
        var parts = getSectionParts(section);

        if (!parts) {
            return null;
        }

        var activeTab = parts.tabs.find(function(tab) {
            return tab.getAttribute("aria-selected") === "true";
        }) || parts.tabs[0];

        activateTab(section, activeTab.getAttribute("data-tab-id"));

        var handleClick = function(event) {
            var tab = event.target.closest("[data-service-includes-tab]");
            var hotspot = event.target.closest("[data-service-includes-hotspot]");

            if (tab && section.contains(tab)) {
                activateTab(section, tab.getAttribute("data-tab-id"));
            }

            if (hotspot && section.contains(hotspot)) {
                var panel = hotspot.closest("[data-service-includes-panel]");

                if (!panel) {
                    return;
                }

                activateHotspot(panel, hotspot.getAttribute("data-hotspot-id"));
            }
        };

        var handleKeydown = function(event) {
            if (event.target.closest("[data-service-includes-tab]")) {
                handleTabKeyboard(section, event);
            }
        };

        var handlePointerEnter = function(event) {
            var hotspot = event.target.closest("[data-service-includes-hotspot]");

            if (!hotspot || !section.contains(hotspot) || !canHover.matches) {
                return;
            }

            var panel = hotspot.closest("[data-service-includes-panel]");

            if (!panel) {
                return;
            }

            activateHotspot(panel, hotspot.getAttribute("data-hotspot-id"));
        };

        var handleFocusIn = function(event) {
            var hotspot = event.target.closest("[data-service-includes-hotspot]");

            if (!hotspot || !section.contains(hotspot)) {
                return;
            }

            var panel = hotspot.closest("[data-service-includes-panel]");

            if (!panel) {
                return;
            }

            activateHotspot(panel, hotspot.getAttribute("data-hotspot-id"));
        };

        section.addEventListener("click", handleClick);
        section.addEventListener("keydown", handleKeydown);
        section.addEventListener("pointerenter", handlePointerEnter, true);
        section.addEventListener("focusin", handleFocusIn);

        return function() {
            section.removeEventListener("click", handleClick);
            section.removeEventListener("keydown", handleKeydown);
            section.removeEventListener("pointerenter", handlePointerEnter, true);
            section.removeEventListener("focusin", handleFocusIn);
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
