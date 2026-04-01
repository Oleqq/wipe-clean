import { MOTION_CONFIG, prefersReducedMotion } from "../motion/motion-config.js";

const POPUP_ACTIVE_CLASS = "is-active";
const POPUP_BODY_ACTIVE_CLASS = "is-popup-active";
const POPUP_OPEN_EVENT = "wipe-clean:popup-open";
const POPUP_OPENED_EVENT = "wipe-clean:popup-opened";
const PROMOTION_POPUP_SCROLL_LIMIT = 890;
const POPUP_CLOSE_DURATION = prefersReducedMotion() ? 220 : MOTION_CONFIG.popup.closeDuration;
const POPUP_SWITCH_DELAY = prefersReducedMotion() ? 120 : MOTION_CONFIG.popup.switchDelay;
const PROMOTION_DIALOG_SELECTOR = ".popup__dialog--promotion";
const FOCUSABLE_SELECTORS = [
    "a[href]",
    "button:not([disabled])",
    "textarea:not([disabled])",
    "input:not([disabled]):not([type='hidden'])",
    "select:not([disabled])",
    "[tabindex]:not([tabindex='-1'])"
].join(", ");

export const registerPopups = () => {
    const popups = Array.from(document.querySelectorAll("[data-popup]"));

    if (!popups.length) {
        return null;
    }

    const popupMap = new Map();

    popups.forEach((popup) => {
        const popupId = popup.dataset.popup || "";

        if (!popupId) {
            return;
        }

        if (!popup.id) {
            popup.id = popupId;
        }

        popupMap.set(popupId, popup);
    });

    const popupOpenButtons = Array.from(document.querySelectorAll("[data-popup-open]"));
    const popupHashLinks = Array.from(document.querySelectorAll("a[href^='#popup-']"));
    let activePopup = null;
    let lastTrigger = null;
    let closeTimerId = null;
    let switchTimerId = null;

    const syncPromotionDialogLayout = (popup) => {
        if (!popup) {
            return;
        }

        const dialog = popup.querySelector(PROMOTION_DIALOG_SELECTOR);

        if (!(dialog instanceof HTMLElement)) {
            return;
        }

        dialog.classList.remove("is-scrollable");

        const isMobile = window.matchMedia("(max-width: 650px)").matches;
        const viewportHeight = window.innerHeight || document.documentElement.clientHeight || 0;
        const viewportOffset = isMobile ? 24 : 48;
        const availableHeight = Math.max(320, viewportHeight - viewportOffset);
        const maxDialogHeight = Math.min(PROMOTION_POPUP_SCROLL_LIMIT, availableHeight);
        const naturalHeight = dialog.scrollHeight;

        dialog.classList.toggle("is-scrollable", naturalHeight > maxDialogHeight);
    };

    const getHeaderMenuState = () => {
        const header = document.querySelector("[data-header]");

        return Boolean(header && header.classList.contains("is-menu-open"));
    };

    const syncBodyLock = () => {
        const hasOpenPopup = Boolean(activePopup);

        document.body.classList.toggle("_lock", hasOpenPopup || getHeaderMenuState());
        document.body.classList.toggle(POPUP_BODY_ACTIVE_CLASS, hasOpenPopup);
    };

    const getFocusableElements = (popup) => {
        if (!popup) {
            return [];
        }

        return Array.from(popup.querySelectorAll(FOCUSABLE_SELECTORS)).filter((element) => !element.hasAttribute("hidden"));
    };

    const focusFirstElement = (popup) => {
        const focusableElements = getFocusableElements(popup);
        const target = focusableElements.find((element) => element.matches("input, textarea, select, button")) || focusableElements[0];

        target?.focus();
    };

    const openPopup = (popup, triggerElement = null, options = {}) => {
        if (!popup) {
            return;
        }

        const shouldPreserveTrigger = options.preserveTrigger === true;

        window.clearTimeout(closeTimerId);
        window.clearTimeout(switchTimerId);

        if (!shouldPreserveTrigger) {
            lastTrigger = triggerElement || document.activeElement;
        }

        activePopup = popup;
        popup.hidden = false;
        popup.setAttribute("aria-hidden", "false");
        syncBodyLock();

        requestAnimationFrame(() => {
            popup.classList.add(POPUP_ACTIVE_CLASS);
            document.dispatchEvent(new CustomEvent(POPUP_OPENED_EVENT, {
                detail: { popup }
            }));
            syncPromotionDialogLayout(popup);

            window.setTimeout(() => {
                syncPromotionDialogLayout(popup);
            }, 120);

            window.setTimeout(() => {
                if (activePopup === popup) {
                    focusFirstElement(popup);
                }
            }, 90);
        });
    };

    const hidePopup = (popup, options = {}) => {
        const shouldRestoreFocus = options.restoreFocus !== false;

        if (!popup || popup !== activePopup) {
            return;
        }

        window.clearTimeout(switchTimerId);
        popup.classList.remove(POPUP_ACTIVE_CLASS);
        popup.setAttribute("aria-hidden", "true");

        window.clearTimeout(closeTimerId);
        closeTimerId = window.setTimeout(() => {
            popup.hidden = true;

            if (activePopup === popup) {
                activePopup = null;
            }

            syncBodyLock();

            if (shouldRestoreFocus && lastTrigger && typeof lastTrigger.focus === "function") {
                lastTrigger.focus();
            }
        }, POPUP_CLOSE_DURATION);
    };

    const showPopup = (popupId, triggerElement = null, options = {}) => {
        const popup = popupMap.get(popupId);

        if (!popup) {
            return;
        }

        if (activePopup === popup && popup.classList.contains(POPUP_ACTIVE_CLASS)) {
            return;
        }

        if (activePopup && activePopup !== popup) {
            const previousPopup = activePopup;

            previousPopup.classList.remove(POPUP_ACTIVE_CLASS);
            previousPopup.setAttribute("aria-hidden", "true");

            window.clearTimeout(closeTimerId);
            window.clearTimeout(switchTimerId);

            switchTimerId = window.setTimeout(() => {
                previousPopup.hidden = true;
                openPopup(popup, triggerElement, options);
            }, POPUP_SWITCH_DELAY);

            return;
        }

        openPopup(popup, triggerElement, options);
    };

    const handleDocumentClick = (event) => {
        const openButton = event.target.closest("[data-popup-open]");
        const hashLink = event.target.closest("a[href^='#popup-']");
        const closeButton = event.target.closest("[data-popup-close]");

        if (openButton) {
            event.preventDefault();
            showPopup(openButton.dataset.popupOpen, openButton);
            return;
        }

        if (hashLink) {
            const popupId = (hashLink.getAttribute("href") || "").slice(1);

            if (popupId && popupMap.has(popupId)) {
                event.preventDefault();
                showPopup(popupId, hashLink);
                return;
            }
        }

        if (closeButton) {
            event.preventDefault();
            hidePopup(activePopup);
        }
    };

    const handlePopupOpenRequest = (event) => {
        const detail = event.detail || {};

        if (!detail.id) {
            return;
        }

        showPopup(detail.id, detail.trigger || null, detail.options || {});
    };

    const handleDocumentKeydown = (event) => {
        if (!activePopup) {
            return;
        }

        if (event.key === "Escape") {
            event.preventDefault();
            hidePopup(activePopup);
            return;
        }

        if (event.key !== "Tab") {
            return;
        }

        const focusableElements = getFocusableElements(activePopup);

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
    };

    document.addEventListener("click", handleDocumentClick);
    document.addEventListener("keydown", handleDocumentKeydown);
    document.addEventListener(POPUP_OPEN_EVENT, handlePopupOpenRequest);

    popupOpenButtons.forEach((button) => {
        const popupId = button.dataset.popupOpen || "";

        if (!popupId || !popupMap.has(popupId)) {
            return;
        }

        button.setAttribute("aria-haspopup", "dialog");
        button.setAttribute("aria-controls", popupId);
    });

    popupHashLinks.forEach((link) => {
        const popupId = (link.getAttribute("href") || "").slice(1);

        if (!popupId || !popupMap.has(popupId)) {
            return;
        }

        link.setAttribute("aria-haspopup", "dialog");
        link.setAttribute("aria-controls", popupId);
    });

    const handleWindowResize = () => {
        if (activePopup) {
            syncPromotionDialogLayout(activePopup);
        }
    };

    const promotionImages = Array.from(document.querySelectorAll(`${PROMOTION_DIALOG_SELECTOR} img`));
    const handlePromotionImageLoad = (event) => {
        const popup = event.currentTarget.closest("[data-popup]");

        syncPromotionDialogLayout(popup);
    };

    window.addEventListener("resize", handleWindowResize);
    promotionImages.forEach((image) => {
        image.addEventListener("load", handlePromotionImageLoad);
    });

    return () => {
        window.clearTimeout(closeTimerId);
        window.clearTimeout(switchTimerId);
        document.removeEventListener("click", handleDocumentClick);
        document.removeEventListener("keydown", handleDocumentKeydown);
        document.removeEventListener(POPUP_OPEN_EVENT, handlePopupOpenRequest);
        window.removeEventListener("resize", handleWindowResize);
        promotionImages.forEach((image) => {
            image.removeEventListener("load", handlePromotionImageLoad);
        });
        activePopup = null;
        syncBodyLock();
    };
};
