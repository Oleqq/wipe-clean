import { registerBreakpointSwiper } from "./register-breakpoint-swiper.js";

const POPUP_OPENED_EVENT = "wipe-clean:popup-opened";

export const registerPopupChoiceSwiper = () => {
    const elements = Array.from(document.querySelectorAll("[data-popup-choice-swiper]"));

    if (!elements.length) {
        return () => {};
    }

    const destroyBreakpointSwiper = registerBreakpointSwiper({
        elements,
        options: {
            slidesPerView: "auto",
            spaceBetween: 6,
            speed: 400,
            watchOverflow: true,
            resistanceRatio: 0.7,
            observer: true,
            observeParents: true
        }
    });

    const handlePopupOpened = (event) => {
        const popup = event.detail?.popup;

        if (!(popup instanceof HTMLElement)) {
            return;
        }

        popup.querySelectorAll("[data-popup-choice-swiper]").forEach((element) => {
            const instance = element.swiper;

            if (instance && !instance.destroyed) {
                instance.update();
                instance.slideTo(instance.activeIndex, 0);
            }
        });
    };

    document.addEventListener(POPUP_OPENED_EVENT, handlePopupOpened);

    return () => {
        document.removeEventListener(POPUP_OPENED_EVENT, handlePopupOpened);
        destroyBreakpointSwiper();
    };
};
