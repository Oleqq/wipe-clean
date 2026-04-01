export const registerReviewsPreviewSwiper = () => {
    if (typeof window.Swiper !== "function") {
        return () => {};
    }

    const elements = document.querySelectorAll("[data-reviews-preview-swiper]");

    const instances = Array.from(elements).map((element) => {
        const parent = element.parentElement;
        const nextEl = parent?.querySelector("[data-reviews-preview-next]");
        const prevEl = parent?.querySelector("[data-reviews-preview-prev]");

        return new window.Swiper(element, {
            speed: 720,
            spaceBetween: 16,
            slidesPerView: "auto",
            centeredSlides: true,
            loop: true,
            navigation: nextEl && prevEl ? {
                nextEl,
                prevEl
            } : undefined,
            breakpoints: {
                651: {
                    spaceBetween: 20,
                    centeredSlides: false,
                    slideToClickedSlide: true
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