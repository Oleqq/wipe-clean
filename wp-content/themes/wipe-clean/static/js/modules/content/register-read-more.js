export const registerReadMore = () => {
    const items = Array.from(document.querySelectorAll("[data-read-more]"));
    const defaultCloseLabel = "\u0421\u0432\u0435\u0440\u043d\u0443\u0442\u044c";

    if (!items.length) {
        return () => {};
    }

    const cleanups = items.map((item) => {
        const button = item.querySelector("[data-read-more-toggle]");

        if (!button) {
            return null;
        }

        const defaultOpenLabel = button.dataset.readMoreOpenLabel || button.textContent.trim() || "\u0415\u0449\u0451";
        const closeLabel = button.dataset.readMoreCloseLabel || defaultCloseLabel;

        const syncState = () => {
            const isExpanded = item.classList.contains("is-expanded");

            button.setAttribute("aria-expanded", String(isExpanded));
            button.textContent = isExpanded ? closeLabel : defaultOpenLabel;
        };

        const handleClick = () => {
            item.classList.toggle("is-expanded");
            syncState();
        };

        syncState();
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
