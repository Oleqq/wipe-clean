const getFaqElements = (item) => {
    const button = item.querySelector("[data-faq-toggle]");
    const answer = item.querySelector("[data-faq-answer]");

    if (!button || !answer) {
        return null;
    }

    return { button, answer };
};

const removeTransitionHandler = (answer) => {
    if (typeof answer.__faqTransitionHandler !== "function") {
        return;
    }

    answer.removeEventListener("transitionend", answer.__faqTransitionHandler);
    delete answer.__faqTransitionHandler;
};

const openFaqItem = (item, options = {}) => {
    const { immediate = false } = options;
    const elements = getFaqElements(item);

    if (!elements) {
        return;
    }

    const { button, answer } = elements;

    removeTransitionHandler(answer);

    if (immediate) {
        answer.hidden = false;
        answer.setAttribute("aria-hidden", "false");
        answer.style.height = "auto";
        answer.style.overflow = "";
        button.setAttribute("aria-expanded", "true");
        item.classList.add("is-open");
        return;
    }

    answer.hidden = false;
    answer.setAttribute("aria-hidden", "false");
    answer.style.overflow = "hidden";
    answer.style.height = "0px";

    button.setAttribute("aria-expanded", "true");
    item.classList.add("is-open");

    const targetHeight = `${answer.scrollHeight}px`;
    void answer.offsetHeight;

    const handleTransitionEnd = (event) => {
        if (event.target !== answer || event.propertyName !== "height") {
            return;
        }

        answer.style.height = "auto";
        answer.style.overflow = "";
        removeTransitionHandler(answer);
    };

    answer.__faqTransitionHandler = handleTransitionEnd;
    answer.addEventListener("transitionend", handleTransitionEnd);

    window.requestAnimationFrame(() => {
        answer.style.height = targetHeight;
    });
};

const closeFaqItem = (item, options = {}) => {
    const { immediate = false } = options;
    const elements = getFaqElements(item);

    if (!elements) {
        return;
    }

    const { button, answer } = elements;

    removeTransitionHandler(answer);

    if (!immediate && !item.classList.contains("is-open")) {
        button.setAttribute("aria-expanded", "false");
        answer.setAttribute("aria-hidden", "true");
        answer.hidden = true;
        answer.style.height = "";
        answer.style.overflow = "";
        return;
    }

    button.setAttribute("aria-expanded", "false");
    item.classList.remove("is-open");
    answer.setAttribute("aria-hidden", "true");

    if (immediate) {
        answer.hidden = true;
        answer.style.height = "";
        answer.style.overflow = "";
        return;
    }

    answer.hidden = false;
    answer.style.overflow = "hidden";
    answer.style.height = `${answer.scrollHeight}px`;
    void answer.offsetHeight;

    const handleTransitionEnd = (event) => {
        if (event.target !== answer || event.propertyName !== "height") {
            return;
        }

        answer.hidden = true;
        answer.style.height = "";
        answer.style.overflow = "";
        removeTransitionHandler(answer);
    };

    answer.__faqTransitionHandler = handleTransitionEnd;
    answer.addEventListener("transitionend", handleTransitionEnd);

    window.requestAnimationFrame(() => {
        answer.style.height = "0px";
    });
};

export const registerFaqAccordion = () => {
    const groups = Array.from(document.querySelectorAll("[data-faq-group]"));

    if (!groups.length) {
        return () => {};
    }

    const cleanups = groups.map((group) => {
        const items = Array.from(group.querySelectorAll("[data-faq-item]"));

        items.forEach((item) => {
            closeFaqItem(item, { immediate: true });
        });

        const initiallyOpenItem = items.find((item) => item.getAttribute("data-faq-initially-open") === "true");

        if (initiallyOpenItem) {
            openFaqItem(initiallyOpenItem, { immediate: true });
        }

        const handleClick = (event) => {
            const button = event.target.closest("[data-faq-toggle]");

            if (!button) {
                return;
            }

            const currentItem = button.closest("[data-faq-item]");

            if (!currentItem) {
                return;
            }

            const isOpen = currentItem.classList.contains("is-open");

            items.forEach((item) => {
                if (item !== currentItem && item.classList.contains("is-open")) {
                    closeFaqItem(item);
                }
            });

            if (isOpen) {
                closeFaqItem(currentItem);
                return;
            }

            openFaqItem(currentItem);
        };

        group.addEventListener("click", handleClick);

        return () => {
            group.removeEventListener("click", handleClick);

            items.forEach((item) => {
                const elements = getFaqElements(item);

                if (!elements) {
                    return;
                }

                removeTransitionHandler(elements.answer);
            });
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
