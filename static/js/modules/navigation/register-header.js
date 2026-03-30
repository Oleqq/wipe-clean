import { MOTION_CONFIG } from "../motion/motion-config.js";

const HEADER_SCROLL_TOP_THRESHOLD = MOTION_CONFIG.header.scrollTopThreshold;
const HEADER_SCROLL_HIDE_THRESHOLD = MOTION_CONFIG.header.scrollHideThreshold;
const HEADER_SCROLL_DELTA = MOTION_CONFIG.header.scrollDelta;
const HEADER_FOCUSABLE_SELECTORS = [
    "a[href]",
    "button:not([disabled])",
    "textarea:not([disabled])",
    "input:not([disabled]):not([type='hidden'])",
    "select:not([disabled])",
    "[tabindex]:not([tabindex='-1'])"
].join(", ");

export const registerHeader = () => {
    const header = document.querySelector("[data-header]");

    if (!header) {
        return null;
    }

    const nav = header.querySelector("[data-header-nav]");
    const menuToggle = header.querySelector("[data-header-toggle]");
    const closeButton = header.querySelector("[data-header-close]");
    const submenuItems = Array.from(header.querySelectorAll("[data-header-item]"));
    const submenuToggles = Array.from(header.querySelectorAll("[data-header-submenu-toggle]"));
    const navLinks = Array.from(header.querySelectorAll("[data-header-link]"));
    const parentLinks = Array.from(header.querySelectorAll("[data-header-parent-link]"));
    const desktopMediaQuery = window.matchMedia("(min-width: 1100px)");

    let lastScrollY = window.scrollY;
    let scrollFrameId = 0;

    const isDesktop = () => desktopMediaQuery.matches;
    const hasOpenPopup = () => Boolean(document.querySelector("[data-popup].is-active"));

    const syncBodyLock = () => {
        const shouldLockBody = (header.classList.contains("is-menu-open") && !isDesktop()) || hasOpenPopup();
        document.body.classList.toggle("_lock", shouldLockBody);
    };

    const getFocusableNavElements = () => {
        if (!nav) {
            return [];
        }

        return Array.from(nav.querySelectorAll(HEADER_FOCUSABLE_SELECTORS)).filter((element) => {
            return !element.hasAttribute("hidden") && element.offsetParent !== null;
        });
    };

    const setSubmenuState = (item, shouldOpen) => {
        if (!item) {
            return;
        }

        const toggle = item.querySelector("[data-header-submenu-toggle]");
        const submenu = item.querySelector("[data-header-submenu]");

        item.classList.toggle("is-open", shouldOpen);

        if (toggle) {
            toggle.setAttribute("aria-expanded", shouldOpen ? "true" : "false");
        }

        if (submenu) {
            submenu.setAttribute("aria-hidden", shouldOpen ? "false" : "true");
        }
    };

    const closeAllSubmenus = () => {
        submenuItems.forEach((item) => {
            setSubmenuState(item, false);
        });
    };

    const applyScrollState = () => {
        const currentScrollY = Math.max(window.scrollY, 0);
        const isMenuOpen = header.classList.contains("is-menu-open");
        const isNearTop = currentScrollY <= HEADER_SCROLL_TOP_THRESHOLD;
        const scrollDelta = Math.abs(currentScrollY - lastScrollY);
        const scrollingDown = currentScrollY > lastScrollY;

        header.classList.toggle("is-scrolled", currentScrollY > HEADER_SCROLL_TOP_THRESHOLD);

        if (isMenuOpen) {
            header.classList.remove("is-hidden");
            lastScrollY = currentScrollY;
            return;
        }

        if (isNearTop) {
            header.classList.remove("is-hidden");
            lastScrollY = currentScrollY;
            return;
        }

        if (scrollDelta >= HEADER_SCROLL_DELTA) {
            if (scrollingDown && currentScrollY > HEADER_SCROLL_HIDE_THRESHOLD) {
                header.classList.add("is-hidden");
            } else {
                header.classList.remove("is-hidden");
            }

            lastScrollY = currentScrollY;
        }
    };

    const handleScroll = () => {
        if (scrollFrameId) {
            return;
        }

        scrollFrameId = window.requestAnimationFrame(() => {
            applyScrollState();
            scrollFrameId = 0;
        });
    };

    const setMenuState = (shouldOpen) => {
        header.classList.toggle("is-menu-open", shouldOpen);

        if (nav) {
            nav.classList.toggle("is-open", shouldOpen);
            nav.setAttribute("aria-hidden", !isDesktop() && !shouldOpen ? "true" : "false");
        }

        if (menuToggle) {
            menuToggle.setAttribute("aria-expanded", shouldOpen ? "true" : "false");
            menuToggle.setAttribute("aria-label", shouldOpen ? "Закрыть меню" : "Открыть меню");
        }

        if (!shouldOpen) {
            closeAllSubmenus();
        }

        header.classList.remove("is-hidden");
        syncBodyLock();
        applyScrollState();

        if (!isDesktop() && shouldOpen) {
            window.requestAnimationFrame(() => {
                getFocusableNavElements()[0]?.focus();
            });
        }
    };

    const handleMenuToggleClick = () => {
        setMenuState(!header.classList.contains("is-menu-open"));
    };

    const handleCloseClick = () => {
        setMenuState(false);
    };

    const handleSubmenuToggleClick = (event) => {
        event.preventDefault();

        const item = event.currentTarget.closest("[data-header-item]");
        const shouldOpen = !item?.classList.contains("is-open");

        if (!item) {
            return;
        }

        if (!isDesktop()) {
            closeAllSubmenus();
        }

        setSubmenuState(item, shouldOpen);
    };

    const handleParentLinkClick = (event) => {
        if (isDesktop()) {
            return;
        }

        const item = event.currentTarget.closest("[data-header-item]");

        if (!item) {
            return;
        }

        if (!item.classList.contains("is-open")) {
            event.preventDefault();
            closeAllSubmenus();
            setSubmenuState(item, true);
        }
    };

    const handleNavLinkClick = (event) => {
        if (event.defaultPrevented) {
            return;
        }

        if (!isDesktop()) {
            setMenuState(false);
        }
    };

    const handleDocumentClick = (event) => {
        if (!isDesktop()) {
            return;
        }

        if (!header.contains(event.target)) {
            closeAllSubmenus();
        }
    };

    const handleKeydown = (event) => {
        if (event.key !== "Escape") {
            if (!isDesktop() && header.classList.contains("is-menu-open") && event.key === "Tab") {
                const focusableElements = getFocusableNavElements();

                if (!focusableElements.length) {
                    return;
                }

                const firstElement = focusableElements[0];
                const lastElement = focusableElements[focusableElements.length - 1];

                if (event.shiftKey && document.activeElement === firstElement) {
                    event.preventDefault();
                    lastElement.focus();
                }

                if (!event.shiftKey && document.activeElement === lastElement) {
                    event.preventDefault();
                    firstElement.focus();
                }
            }

            return;
        }

        if (header.classList.contains("is-menu-open")) {
            setMenuState(false);
            menuToggle?.focus();
            return;
        }

        if (submenuItems.some((item) => item.classList.contains("is-open"))) {
            closeAllSubmenus();
        }
    };

    const handleMediaChange = () => {
        if (isDesktop()) {
            header.classList.remove("is-menu-open");
            nav?.classList.remove("is-open");
            menuToggle?.setAttribute("aria-expanded", "false");
            nav?.setAttribute("aria-hidden", "false");
            menuToggle?.setAttribute("aria-label", "Открыть меню");
        } else {
            nav?.setAttribute("aria-hidden", header.classList.contains("is-menu-open") ? "false" : "true");
        }

        closeAllSubmenus();
        syncBodyLock();
        applyScrollState();
    };

    menuToggle?.addEventListener("click", handleMenuToggleClick);
    closeButton?.addEventListener("click", handleCloseClick);
    submenuToggles.forEach((toggle) => {
        toggle.addEventListener("click", handleSubmenuToggleClick);
    });
    parentLinks.forEach((link) => {
        link.addEventListener("click", handleParentLinkClick);
    });
    navLinks.forEach((link) => {
        link.addEventListener("click", handleNavLinkClick);
    });
    document.addEventListener("click", handleDocumentClick);
    document.addEventListener("keydown", handleKeydown);
    window.addEventListener("scroll", handleScroll, { passive: true });
    desktopMediaQuery.addEventListener("change", handleMediaChange);

    handleMediaChange();

    return () => {
        window.cancelAnimationFrame(scrollFrameId);
        menuToggle?.removeEventListener("click", handleMenuToggleClick);
        closeButton?.removeEventListener("click", handleCloseClick);
        submenuToggles.forEach((toggle) => {
            toggle.removeEventListener("click", handleSubmenuToggleClick);
        });
        parentLinks.forEach((link) => {
            link.removeEventListener("click", handleParentLinkClick);
        });
        navLinks.forEach((link) => {
            link.removeEventListener("click", handleNavLinkClick);
        });
        document.removeEventListener("click", handleDocumentClick);
        document.removeEventListener("keydown", handleKeydown);
        window.removeEventListener("scroll", handleScroll);
        desktopMediaQuery.removeEventListener("change", handleMediaChange);
        document.body.classList.remove("_lock");
    };
};
