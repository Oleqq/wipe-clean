export const registerReadMore = () => {
    const items = Array.from(document.querySelectorAll("[data-read-more]"));
    const defaultCloseLabel = "\u0421\u0432\u0435\u0440\u043d\u0443\u0442\u044c";

    const findPart = (item, partName) => {
        const dataPart = item.querySelector(`[data-read-more-${partName}]`);

        if (dataPart) {
            return dataPart;
        }

        return Array.from(item.children).find((child) => {
            if (!(child instanceof HTMLElement)) {
                return false;
            }

            return Array.from(child.classList).some((className) => className.includes(`__${partName}`) || className.endsWith(`-${partName}`));
        }) || null;
    };

    if (!items.length) {
        return () => {};
    }

    const cleanups = items.map((item) => {
        const button = item.querySelector("[data-read-more-toggle]");
        const body = findPart(item, "body");
        const originMarker = document.createComment("read-more-origin");

        if (!button) {
            return null;
        }

        button.parentNode?.insertBefore(originMarker, button);

        const defaultOpenLabel = button.dataset.readMoreOpenLabel || button.textContent.trim() || "\u0415\u0449\u0451";
        const closeLabel = button.dataset.readMoreCloseLabel || defaultCloseLabel;

        const restoreButtonPosition = () => {
            if (!originMarker.parentNode) {
                return;
            }

            if (button.parentNode !== originMarker.parentNode || button.previousSibling !== originMarker) {
                originMarker.parentNode.insertBefore(button, originMarker.nextSibling);
            }
        };

        const moveButtonToExpandedBody = () => {
            if (!body) {
                return;
            }

            const target = Array.from(body.children)
                .reverse()
                .find((child) => child instanceof HTMLElement && /^(P|DIV|SPAN|LI)$/u.test(child.tagName));

            if (target instanceof HTMLElement) {
                target.appendChild(button);
                return;
            }

            body.appendChild(button);
        };

        const syncState = () => {
            const isExpanded = item.classList.contains("is-expanded");

            button.setAttribute("aria-expanded", String(isExpanded));
            button.textContent = isExpanded ? closeLabel : defaultOpenLabel;

            if (isExpanded) {
                moveButtonToExpandedBody();
            } else {
                restoreButtonPosition();
            }
        };

        const handleClick = (event) => {
            event.preventDefault();
            item.classList.toggle("is-expanded");
            syncState();
        };

        const handleResize = () => {
            syncState();
        };

        syncState();
        button.addEventListener("click", handleClick);
        window.addEventListener("resize", handleResize, { passive: true });

        return () => {
            button.removeEventListener("click", handleClick);
            window.removeEventListener("resize", handleResize);
            restoreButtonPosition();
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
