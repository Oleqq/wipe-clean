import { registerBreakpointSwiper } from "./register-breakpoint-swiper.js";

export const registerWorkApproachSwiper = () => {
    const elements = Array.from(document.querySelectorAll("[data-work-approach-swiper]"));

    return registerBreakpointSwiper({
        elements,
        options: {
            slidesPerView: "auto",
            spaceBetween: 16,
            speed: 450,
            watchOverflow: true,
            resistanceRatio: 0.75
        }
    });
};
