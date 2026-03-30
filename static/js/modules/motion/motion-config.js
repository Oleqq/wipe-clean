export const MOTION_CONFIG = {
    header: {
        scrollTopThreshold: 18,
        scrollHideThreshold: 140,
        scrollDelta: 6
    },
    popup: {
        closeDuration: 440,
        switchDelay: 220
    },
    reveal: {
        thresholds: [0, 0.04, 0.12],
        rootMargin: "12% 0px -8% 0px",
        initialViewportTopFactor: -0.08,
        initialViewportBottomFactor: 1.08,
        baseDelay: 90,
        staggerStep: 148,
        scopeStaggerStep: 104,
        contentStaggerStep: 132,
        formStaggerStep: 144,
        collectionStaggerStep: 188,
        buttonCollectionStaggerStep: 156,
        maxDelay: 1320
    }
};

export const prefersReducedMotion = () => window.matchMedia("(prefers-reduced-motion: reduce)").matches;
