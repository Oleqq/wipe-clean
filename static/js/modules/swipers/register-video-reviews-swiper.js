export const registerVideoReviewsSwiper = () => {
    if (typeof window.Swiper !== "function") {
        return () => {};
    }

    const elements = Array.from(document.querySelectorAll("[data-video-reviews-swiper]"));
    const instances = elements.map((element) => {
        const scope = element.closest(".video-reviews__wrapper") || element.parentElement;
        const nextEl = scope?.querySelector("[data-video-reviews-next]") || null;
        const prevEl = scope?.querySelector("[data-video-reviews-prev]") || null;

        return new window.Swiper(element, {
            speed: 500,
            spaceBetween: 20,
            slidesPerView: "auto",
            watchOverflow: true,
            resistanceRatio: 0.75,
            navigation: nextEl && prevEl ? {
                nextEl,
                prevEl
            } : undefined
        });
    });

    return () => {
        instances.forEach((instance) => {
            instance.destroy(true, true);
        });
    };
};
