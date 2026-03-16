import { registerBreakpointSwiper } from "./register-breakpoint-swiper.js";

export const registerServicesBenefitsSwiper = () => {
    const elements = Array.from(document.querySelectorAll("[data-services-benefits-swiper]"));

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
