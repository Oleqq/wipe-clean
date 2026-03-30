import { prefersReducedMotion } from "../motion/motion-config.js";

const BODY_LOCK_CLASS = "_lock";
const SMOOTH_WRAPPER_SELECTOR = "#smooth-wrapper";
const SMOOTH_CONTENT_SELECTOR = "#smooth-content";

export const registerSmoothScroll = () => {
    if (prefersReducedMotion()) {
        return null;
    }

    const { gsap, ScrollTrigger, ScrollSmoother } = window;

    if (!gsap || !ScrollTrigger || !ScrollSmoother) {
        return null;
    }

    const wrapper = document.querySelector(SMOOTH_WRAPPER_SELECTOR);
    const content = document.querySelector(SMOOTH_CONTENT_SELECTOR);

    if (!(wrapper instanceof HTMLElement) || !(content instanceof HTMLElement)) {
        return null;
    }

    gsap.registerPlugin(ScrollTrigger, ScrollSmoother);
    ScrollTrigger.config({
        ignoreMobileResize: true
    });

    const smoother = ScrollSmoother.create({
        wrapper,
        content,
        normalizeScroll: true,
        effects: false,
        smooth: 1.55,
        smoothTouch: 0.16,
        speed: 0.92,
        ease: "expo.out"
    });

    const syncSmootherState = () => {
        if (document.body.classList.contains(BODY_LOCK_CLASS)) {
            smoother.paused(true);
            return;
        }

        smoother.paused(false);
        ScrollTrigger.update();
    };

    const bodyObserver = new MutationObserver(syncSmootherState);

    bodyObserver.observe(document.body, {
        attributes: true,
        attributeFilter: ["class"]
    });

    syncSmootherState();

    return () => {
        bodyObserver.disconnect();
        smoother.kill();
    };
};
