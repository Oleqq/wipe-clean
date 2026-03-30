export const registerReviewsPreviewSwiper = () => {
    if (typeof window.Swiper !== "function") {
        return () => {};
    }

    const elements = Array.from(document.querySelectorAll("[data-reviews-preview-swiper]"));
    const instances = elements.map((element) => {
        const nextEl = element.parentElement?.querySelector("[data-reviews-preview-next]") || null;
        const prevEl = element.parentElement?.querySelector("[data-reviews-preview-prev]") || null;
        const slidesCount = element.querySelectorAll(".swiper-slide").length;

        return new window.Swiper(element, {
            speed: 720,
            spaceBetween: 16,
            slidesPerView: "auto",
            centeredSlides: true,
            loop: slidesCount > 1,
            loopAdditionalSlides: slidesCount,
            loopedSlides: slidesCount,
            watchOverflow: true,
            watchSlidesProgress: true,
            slideToClickedSlide: true,
            resistanceRatio: 0.75,
            navigation: nextEl && prevEl ? {
                nextEl,
                prevEl
            } : undefined,
            breakpoints: {
                651: {
                    centeredSlides: false,
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
