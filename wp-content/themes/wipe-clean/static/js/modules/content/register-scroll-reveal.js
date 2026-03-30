import { MOTION_CONFIG } from "../motion/motion-config.js";

const READY_CLASS = "has-scroll-reveal";
const ACTIVE_CLASS = "is-reveal-active";
const SECTION_SELECTOR = "main section";
const FOOTER_SELECTOR = ".footer";
const MAX_COLLECTION_ITEMS = 8;
const MAX_CONTENT_ITEMS = 8;

const MEDIA_NAME_RE = /(media|visual|image|photo|picture|poster|figure|gallery|map|brand-card|hero)/i;
const TEXT_NAME_RE = /(head|intro|content|text|copy|summary|aside|info|note|cta|form|top|bottom|body)/i;
const COLLECTION_NAME_RE = /(cards|grid|list|items|columns|requisites|contacts|legal|benefits|reviews|services|results|actions|fields|stats|nav-body|members)/i;
const CARD_NAME_RE = /(card|item|member|review|service|benefit|result|factor|advantage|promotion|post|article|message|step|feature|price|contact|tile)/i;
const SKIP_NAME_RE = /(wave|divider|overlay|backdrop|spinner|shadow)/i;
const ATOMIC_NAME_RE = /(title|subtitle|heading|kicker|label|description|text|copy|summary|note|meta|caption|date|btn|button|link|field|input|textarea|checkbox|select|status|badge|chip|tag|counter|stat|lead|form-title|form-text|legend)/i;

const isElement = (value) => value instanceof HTMLElement;

const getClassName = (element) => {
    if (!isElement(element)) {
        return "";
    }

    return typeof element.className === "string" ? element.className : "";
};

const hasWrapperClass = (element) => /(^|\s)[a-z0-9-]+__wrapper(\s|$)/i.test(getClassName(element));
const hasNamedClass = (element, expression) => expression.test(getClassName(element));

const isEligibleElement = (element) => {
    if (!isElement(element) || element.hidden) {
        return false;
    }

    if (element.matches("[data-reveal-ignore], script, style, link, meta, br")) {
        return false;
    }

    if (element.closest(".popup-system") || element.closest("[data-gallery-marquee-track]")) {
        return false;
    }

    if (element.matches(".swiper-wrapper, .swiper-slide") || element.closest(".swiper-wrapper")) {
        return false;
    }

    if (hasNamedClass(element, SKIP_NAME_RE)) {
        return false;
    }

    return true;
};

const getPrimaryScope = (section) => {
    const directChildren = Array.from(section.children).filter(isElement);
    const directContainer = directChildren.find((child) => child.classList.contains("_container"));

    if (directContainer) {
        const directWrapper = Array.from(directContainer.children).find((child) => hasWrapperClass(child));

        return directWrapper || directContainer;
    }

    const directWrapper = directChildren.find((child) => hasWrapperClass(child));

    return directWrapper || section;
};

const setReveal = (target, type, delay, targets, mode = "minor") => {
    if (!isEligibleElement(target)) {
        return;
    }

    if (!target.dataset.reveal) {
        target.dataset.reveal = type;
    }

    if (!target.style.getPropertyValue("--reveal-delay")) {
        target.style.setProperty("--reveal-delay", `${delay}ms`);
    }

    if (!target.dataset.revealMode) {
        target.dataset.revealMode = mode;
    }

    targets.add(target);
};

const getScopeTargets = (scope) => Array.from(scope.querySelectorAll("[data-reveal]"));

const getNestedRevealType = (index, sectionIndex) => {
    if (index % 3 === 0) {
        return "up-soft";
    }

    if ((sectionIndex + index) % 2 === 0) {
        return "left-soft";
    }

    return "right-soft";
};

const getCollectionRevealType = (index, sectionIndex) => {
    const isEvenSection = sectionIndex % 2 === 0;

    if (index === 0) {
        return isEvenSection ? "left" : "right";
    }

    if (index === 1) {
        return "up";
    }

    if (index === 2) {
        return isEvenSection ? "right" : "left";
    }

    if (index % 2 === 0) {
        return isEvenSection ? "left-soft" : "right-soft";
    }

    return "up-soft";
};

const getContentRevealType = (index, sectionIndex) => {
    const isEvenSection = sectionIndex % 2 === 0;

    if (index === 0) {
        return isEvenSection ? "left" : "right";
    }

    if (index === 1) {
        return "up";
    }

    if (index === 2) {
        return isEvenSection ? "right-soft" : "left-soft";
    }

    if (index === 3) {
        return "up-soft";
    }

    return getNestedRevealType(index, sectionIndex);
};

const getFormRevealType = (index, sectionIndex) => {
    const isEvenSection = sectionIndex % 2 === 0;

    if (index === 0) {
        return "up-soft";
    }

    if (index === 1) {
        return isEvenSection ? "left-soft" : "right-soft";
    }

    if (index === 2) {
        return isEvenSection ? "right-soft" : "left-soft";
    }

    if (index % 2 === 0) {
        return "up-soft";
    }

    return isEvenSection ? "left-soft" : "right-soft";
};

const getRevealType = (element, sectionIndex, index) => {
    if (hasNamedClass(element, MEDIA_NAME_RE)) {
        return sectionIndex % 2 === 0 ? "right" : "left";
    }

    if (hasNamedClass(element, TEXT_NAME_RE)) {
        return sectionIndex % 2 === 0 ? "left" : "right";
    }

    if (hasNamedClass(element, COLLECTION_NAME_RE)) {
        return "up";
    }

    if (element.matches("form")) {
        return "up";
    }

    if (index === 0) {
        return sectionIndex % 2 === 0 ? "left" : "right";
    }

    if (index === 1) {
        return sectionIndex % 2 === 0 ? "right" : "left";
    }

    return getNestedRevealType(index, sectionIndex);
};

const isCardElement = (element) => {
    if (!isEligibleElement(element)) {
        return false;
    }

    if (element.matches(".ui-card, .blog-card, .review-card, .feature-card, .service-card, .service-teaser-card, .team-member-card, .before-after-card, .message-review-card, .video-review-card, .promotion-card, .contact-card")) {
        return true;
    }

    return hasNamedClass(element, CARD_NAME_RE);
};

const isSequentialCollectionChild = (element) => {
    if (!isEligibleElement(element)) {
        return false;
    }

    if (element.matches(".ui-btn, .ui-link-inline, li")) {
        return true;
    }

    return isCardElement(element);
};

const isAtomicContentElement = (element) => {
    if (!isEligibleElement(element)) {
        return false;
    }

    if (element.matches("h1, h2, h3, h4, h5, h6, p, blockquote, button, label, input, textarea, select")) {
        return true;
    }

    if (element.matches(".ui-kicker, .ui-title, .ui-text, .ui-btn, .ui-field, .ui-checkbox, .ui-link-inline")) {
        return true;
    }

    if (hasNamedClass(element, ATOMIC_NAME_RE)) {
        return true;
    }

    return false;
};

const hasAtomicAncestorWithinCluster = (element, cluster) => {
    let current = element.parentElement;

    while (current && current !== cluster) {
        if (isAtomicContentElement(current)) {
            return true;
        }

        current = current.parentElement;
    }

    return false;
};

const isInsideNestedCollection = (element, cluster) => {
    let current = element.parentElement;

    while (current && current !== cluster) {
        if (current.matches(".swiper, .swiper-wrapper, .swiper-slide, ul, ol")) {
            return true;
        }

        if (hasNamedClass(current, COLLECTION_NAME_RE)) {
            return true;
        }

        current = current.parentElement;
    }

    return false;
};

const getContentClusterItems = (cluster) => {
    const descendants = Array.from(cluster.querySelectorAll("*")).filter((element) => (
        isAtomicContentElement(element)
        && !hasAtomicAncestorWithinCluster(element, cluster)
        && !isInsideNestedCollection(element, cluster)
    ));

    return descendants.slice(0, MAX_CONTENT_ITEMS);
};

const shouldDecorateCollection = (collection) => {
    const items = Array.from(collection.children).filter(isEligibleElement);

    if (items.length < 2) {
        return false;
    }

    if (items.some((item) => item.matches(".swiper") || item.querySelector(":scope > .swiper"))) {
        return false;
    }

    if (hasNamedClass(collection, COLLECTION_NAME_RE)) {
        return true;
    }

    const sequentialItems = items.filter(isSequentialCollectionChild);

    return sequentialItems.length >= 2 && sequentialItems.length >= Math.max(2, items.length - 1);
};

const isButtonCollection = (items) => items.length > 1 && items.every((item) => item.matches(".ui-btn, .ui-link-inline"));

const decorateCollection = (collection, sectionIndex, targets, baseDelay = 60) => {
    const items = Array.from(collection.children).filter(isEligibleElement).slice(0, MAX_COLLECTION_ITEMS);

    if (items.length < 2) {
        return;
    }

    if (!shouldDecorateCollection(collection)) {
        return;
    }

    const collectionStep = isButtonCollection(items)
        ? MOTION_CONFIG.reveal.buttonCollectionStaggerStep
        : MOTION_CONFIG.reveal.collectionStaggerStep;

    items.forEach((item, index) => {
        setReveal(
            item,
            getCollectionRevealType(index, sectionIndex),
            Math.min(baseDelay + index * collectionStep, MOTION_CONFIG.reveal.maxDelay),
            targets,
            "minor"
        );
    });
};

const decorateContentCluster = (cluster, sectionIndex, targets, baseDelay = 80, kind = "content") => {
    const items = getContentClusterItems(cluster);

    if (items.length < 2) {
        return false;
    }

    items.forEach((item, index) => {
        const revealType = kind === "form"
            ? getFormRevealType(index, sectionIndex)
            : getContentRevealType(index, sectionIndex);
        const staggerStep = kind === "form"
            ? MOTION_CONFIG.reveal.formStaggerStep
            : MOTION_CONFIG.reveal.contentStaggerStep;

        setReveal(
            item,
            revealType,
            Math.min(baseDelay + index * staggerStep, MOTION_CONFIG.reveal.maxDelay),
            targets,
            "minor"
        );
    });

    return true;
};

const decorateScope = (scope, sectionIndex, targets) => {
    const children = Array.from(scope.children).filter(isEligibleElement);

    if (!children.length) {
        setReveal(scope, "up", 0, targets);
        return;
    }

    children.forEach((child, index) => {
        if (hasNamedClass(child, TEXT_NAME_RE) && decorateContentCluster(child, sectionIndex, targets, 48 + index * 28, "content")) {
            setReveal(child, sectionIndex % 2 === 0 ? "left-soft" : "right-soft", Math.min(index * 36, 180), targets, "major");
            return;
        }

        if (child.matches("form") && decorateContentCluster(child, sectionIndex, targets, 72 + index * 30, "form")) {
            setReveal(child, "up-soft", Math.min(index * 36, 180), targets, "major");
            return;
        }

        setReveal(
            child,
            getRevealType(child, sectionIndex, index),
            Math.min(index * MOTION_CONFIG.reveal.scopeStaggerStep, 420),
            targets,
            "major"
        );
    });

    children.forEach((child, index) => {
        if (shouldDecorateCollection(child) || child.matches("ul, ol, form")) {
            decorateCollection(child, sectionIndex, targets, 140 + index * 36);
            return;
        }

        const nestedCollections = Array.from(child.children).filter((nestedChild) => (
            isEligibleElement(nestedChild)
            && (shouldDecorateCollection(nestedChild) || nestedChild.matches("ul, ol, form"))
        ));

        nestedCollections.forEach((nestedCollection, nestedIndex) => {
            decorateCollection(nestedCollection, sectionIndex, targets, 140 + nestedIndex * 36);
        });
    });
};

export const registerScrollReveal = () => {
    if (!("IntersectionObserver" in window)) {
        return null;
    }

    const targets = new Set();
    const scopes = [];
    const sections = Array.from(document.querySelectorAll(SECTION_SELECTOR));

    sections.forEach((section, sectionIndex) => {
        const scope = getPrimaryScope(section);

        decorateScope(scope, sectionIndex, targets);
        scopes.push(scope);
    });

    const footer = document.querySelector(FOOTER_SELECTOR);

    if (footer) {
        const footerScope = footer.querySelector(".footer__wrapper") || getPrimaryScope(footer);

        decorateScope(footerScope, sections.length + 1, targets);
        scopes.push(footerScope);
    }

    const revealTargets = Array.from(targets);

    if (!revealTargets.length || !scopes.length) {
        return null;
    }

    const activateScope = (scope) => {
        getScopeTargets(scope).forEach((target) => {
            target.classList.add(ACTIVE_CLASS);
        });
    };

    const markInitiallyVisibleScopes = () => {
        const viewportTop = window.innerHeight * MOTION_CONFIG.reveal.initialViewportTopFactor;
        const viewportBottom = window.innerHeight * MOTION_CONFIG.reveal.initialViewportBottomFactor;

        scopes.forEach((scope) => {
            const rect = scope.getBoundingClientRect();

            if (rect.top < viewportBottom && rect.bottom > viewportTop) {
                activateScope(scope);
            }
        });
    };

    markInitiallyVisibleScopes();
    document.body.classList.add(READY_CLASS);

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting && entry.intersectionRatio >= MOTION_CONFIG.reveal.thresholds[1]) {
                activateScope(entry.target);
                observer.unobserve(entry.target);
                return;
            }
        });
    }, {
        threshold: MOTION_CONFIG.reveal.thresholds,
        rootMargin: MOTION_CONFIG.reveal.rootMargin
    });

    scopes.forEach((scope) => observer.observe(scope));

    return () => {
        observer.disconnect();
        document.body.classList.remove(READY_CLASS);

        revealTargets.forEach((target) => {
            target.classList.remove(ACTIVE_CLASS);
        });
    };
};
