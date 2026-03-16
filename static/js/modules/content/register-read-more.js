export const registerReadMore = () => {
    const items = Array.from(document.querySelectorAll("[data-read-more]"));

    if (!items.length) {
        return () => {};
    }

    const cleanups = items.map((item) => {
        const button = item.querySelector("[data-read-more-toggle]");

        if (!button) {
            return null;
        }

        const handleClick = () => {
            item.classList.add("is-expanded");
            button.setAttribute("aria-expanded", "true");
        };

        button.addEventListener("click", handleClick);

        return () => {
            button.removeEventListener("click", handleClick);
        };
    });

    return () => {
        cleanups.forEach((cleanup) => {
            if (typeof cleanup === "function") {
                cleanup();
            }
        });
    };
};
