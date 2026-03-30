export const registerFancybox = () => {
    if (!window.Fancybox || typeof window.Fancybox.bind !== "function") {
        return () => {};
    }

    const selectors = [
        "[data-fancybox='gallery-preview']",
        "[data-fancybox='video-reviews']",
        "[data-fancybox='message-reviews']"
    ];
    const options = {
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
    };

    selectors.forEach((selector) => {
        window.Fancybox.bind(selector, options);
    });

    return () => {
        if (typeof window.Fancybox.unbind !== "function") {
            return;
        }

        selectors.forEach((selector) => {
            window.Fancybox.unbind(selector);
        });
    };
};
