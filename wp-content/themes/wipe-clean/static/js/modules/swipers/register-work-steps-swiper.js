import { registerBreakpointSwiper } from "./register-breakpoint-swiper.js";

export const registerWorkStepsSwiper = () => {
    const elements = Array.from(document.querySelectorAll("[data-work-steps-swiper]"));

    return registerBreakpointSwiper({
        elements,
        options: {
            slidesPerView: "auto",
            spaceBetween: 16,
            speed: 500,
            watchOverflow: true,
            resistanceRatio: 0.75
        }
    });
};
