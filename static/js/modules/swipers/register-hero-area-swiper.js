import { registerBreakpointSwiper } from "./register-breakpoint-swiper.js";

export const registerHeroAreaSwiper = () => {
    const elements = Array.from(document.querySelectorAll("[data-hero-area-swiper]"));

    return registerBreakpointSwiper({
        elements,
        options: {
            slidesPerView: "auto",
            spaceBetween: 6,
            speed: 400,
            watchOverflow: true,
            resistanceRatio: 0.7
        }
    });
};
