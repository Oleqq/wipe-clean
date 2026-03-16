export const registerFancybox = () => {
    if (!window.Fancybox || typeof window.Fancybox.bind !== "function") {
        return () => {};
    }

    const selector = "[data-fancybox='gallery-preview']";

    window.Fancybox.bind(selector, {
        Hash: false,
        Thumbs: false,
        dragToClose: false,
        Toolbar: {
            display: {
                left: [],
                middle: [],
                right: ["close"]
            }
        }
    });

    return () => {
        if (typeof window.Fancybox.unbind === "function") {
            window.Fancybox.unbind(selector);
        }
    };
};
