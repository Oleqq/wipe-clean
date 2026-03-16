import { registerBreakpointSwiper } from "./register-breakpoint-swiper.js";

export const registerWhyUsSwiper = () => {
    const elements = Array.from(document.querySelectorAll("[data-why-us-swiper]"));

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
