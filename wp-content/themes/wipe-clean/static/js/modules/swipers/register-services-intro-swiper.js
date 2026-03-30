export const registerServicesIntroSwiper = () => {
    if (typeof window.Swiper !== "function") {
        return () => {};
    }

    const elements = Array.from(document.querySelectorAll("[data-services-intro-swiper]"));
    const instances = elements.map((element) => {
        const scope = element.closest(".services-intro__wrapper") || element.parentElement;
        const nextEl = scope?.querySelector("[data-services-intro-next]") || null;
        const prevEl = scope?.querySelector("[data-services-intro-prev]") || null;

        return new window.Swiper(element, {
            speed: 500,
            spaceBetween: 16,
            slidesPerView: "auto",
            watchOverflow: true,
            resistanceRatio: 0.75,
            navigation: nextEl && prevEl ? {
                nextEl,
                prevEl
            } : undefined,
            breakpoints: {
                651: {
                    spaceBetween: 20
                }
            }
        });
    });

    return () => {
        instances.forEach((instance) => {
            instance.destroy(true, true);
        });
    };
};
