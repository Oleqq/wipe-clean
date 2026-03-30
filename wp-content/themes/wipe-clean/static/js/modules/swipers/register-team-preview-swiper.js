export const registerTeamPreviewSwiper = () => {
    if (typeof window.Swiper !== "function") {
        return () => {};
    }

    const elements = Array.from(document.querySelectorAll("[data-team-preview-swiper]"));
    const instances = elements.map((element) => {
        const scope = element.closest(".team-preview__wrapper") || element.parentElement;
        const nextEl = scope?.querySelector("[data-team-preview-next]") || null;
        const prevEl = scope?.querySelector("[data-team-preview-prev]") || null;

        return new window.Swiper(element, {
            speed: 500,
            spaceBetween: 16,
            slidesPerView: 1.2,
            watchOverflow: true,
            resistanceRatio: 0.75,
            navigation: nextEl && prevEl ? {
                nextEl,
                prevEl
            } : undefined,
            breakpoints: {
                651: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                992: {
                    slidesPerView: 4,
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
