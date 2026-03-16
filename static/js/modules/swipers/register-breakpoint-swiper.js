const cleanupSwiperStyles = (container) => {
    container.removeAttribute("style");

    const wrapper = container.querySelector(".swiper-wrapper");

    if (wrapper) {
        wrapper.removeAttribute("style");
    }

    container.querySelectorAll(".swiper-slide").forEach((slide) => {
        slide.removeAttribute("style");
    });
};

export const registerBreakpointSwiper = ({
    elements,
    query = "(max-width: 650px)",
    options = {}
}) => {
    if (!elements.length || typeof window.Swiper !== "function") {
        return () => {};
    }

    const mediaQuery = window.matchMedia(query);
    const registry = new Map();

    const enable = (element) => {
        if (registry.has(element)) {
            return;
        }

        const instance = new window.Swiper(element, options);
        registry.set(element, instance);
    };

    const disable = (element) => {
        const instance = registry.get(element);

        if (!instance) {
            return;
        }

        instance.destroy(true, true);
        registry.delete(element);
        cleanupSwiperStyles(element);
    };

    const sync = () => {
        elements.forEach((element) => {
            if (mediaQuery.matches) {
                enable(element);
                return;
            }

            disable(element);
        });
    };

    const handleChange = () => {
        sync();
    };

    if (typeof mediaQuery.addEventListener === "function") {
        mediaQuery.addEventListener("change", handleChange);
    } else if (typeof mediaQuery.addListener === "function") {
        mediaQuery.addListener(handleChange);
    }

    sync();

    return () => {
        elements.forEach((element) => disable(element));

        if (typeof mediaQuery.removeEventListener === "function") {
            mediaQuery.removeEventListener("change", handleChange);
        } else if (typeof mediaQuery.removeListener === "function") {
            mediaQuery.removeListener(handleChange);
        }
    };
};
