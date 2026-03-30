const HOVER_SPEED_FACTOR = 0.18;
const SPEED_LERP = 5;

const createGalleryMarqueeRow = (row) => {
    const track = row.querySelector("[data-gallery-marquee-track]");

    if (!track) {
        return () => {};
    }

    const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
    const isReducedMotion = prefersReducedMotion.matches;

    row.dataset.marqueeReady = "true";

    const baseDirection = Number(row.dataset.direction || -1);
    const baseSpeed = isReducedMotion
        ? Math.max(12, Number(row.dataset.speed || 36) * 0.45)
        : Number(row.dataset.speed || 36);
    const slowSpeed = isReducedMotion
        ? Math.max(8, baseSpeed * 0.7)
        : baseSpeed * HOVER_SPEED_FACTOR;
    const clones = Array.from(track.querySelectorAll("[data-gallery-clone]"));
    const handlePointerEnter = () => setHovered(true);
    const handlePointerLeave = () => setHovered(false);
    const handleFocusIn = () => setHovered(true);
    const handleFocusOut = (event) => {
        if (!row.contains(event.relatedTarget)) {
            setHovered(false);
        }
    };
    const handleCloneDragStart = (event) => event.preventDefault();

    let currentSpeed = baseSpeed;
    let targetSpeed = baseSpeed;
    let offset = 0;
    let lastTime = 0;
    let sequenceWidth = 0;
    let rafId = 0;
    let isInViewport = true;

    const observer = new IntersectionObserver((entries) => {
        isInViewport = entries.some((entry) => entry.isIntersecting);
    }, {
        threshold: 0.05
    });

    const measure = () => {
        sequenceWidth = track.scrollWidth / 2;
        offset = baseDirection === -1 ? 0 : -sequenceWidth;
        track.style.transform = `translate3d(${offset}px, 0, 0)`;
    };

    const handleCloneClick = (event) => {
        const clone = event.target.closest("[data-gallery-clone]");

        if (!clone) {
            return;
        }

        event.preventDefault();

        const targetId = clone.getAttribute("data-gallery-target");
        const target = track.querySelector(`[data-gallery-item-id="${targetId}"]:not([data-gallery-clone])`);

        if (target) {
            target.click();
        }
    };

    const setHovered = (state) => {
        row.dataset.marqueeHovered = state ? "true" : "false";
        targetSpeed = state ? slowSpeed : baseSpeed;
    };

    const tick = (time) => {
        if (!lastTime) {
            lastTime = time;
        }

        const delta = Math.min((time - lastTime) / 1000, 0.05);
        lastTime = time;

        if (!document.hidden && isInViewport && sequenceWidth > 0) {
            currentSpeed += (targetSpeed - currentSpeed) * Math.min(1, delta * SPEED_LERP);
            offset += currentSpeed * delta * baseDirection;

            if (baseDirection === -1 && Math.abs(offset) >= sequenceWidth) {
                offset += sequenceWidth;
            }

            if (baseDirection === 1 && offset >= 0) {
                offset -= sequenceWidth;
            }

            track.style.transform = `translate3d(${offset}px, 0, 0)`;
        }

        rafId = window.requestAnimationFrame(tick);
    };

    const handleResize = () => {
        measure();
    };

    observer.observe(row);
    row.addEventListener("pointerenter", handlePointerEnter);
    row.addEventListener("pointerleave", handlePointerLeave);
    row.addEventListener("focusin", handleFocusIn);
    row.addEventListener("focusout", handleFocusOut);
    row.addEventListener("click", handleCloneClick);
    clones.forEach((clone) => {
        clone.addEventListener("dragstart", handleCloneDragStart);
    });

    measure();
    window.addEventListener("resize", handleResize);
    rafId = window.requestAnimationFrame(tick);

    return () => {
        observer.disconnect();
        row.removeEventListener("pointerenter", handlePointerEnter);
        row.removeEventListener("pointerleave", handlePointerLeave);
        row.removeEventListener("focusin", handleFocusIn);
        row.removeEventListener("focusout", handleFocusOut);
        row.removeEventListener("click", handleCloneClick);
        clones.forEach((clone) => {
            clone.removeEventListener("dragstart", handleCloneDragStart);
        });
        window.removeEventListener("resize", handleResize);
        window.cancelAnimationFrame(rafId);
    };
};

export const registerGalleryPreviewMarquee = () => {
    const rows = Array.from(document.querySelectorAll("[data-gallery-marquee-row]"));

    if (!rows.length) {
        return () => {};
    }

    const destroyers = rows.map((row) => createGalleryMarqueeRow(row));

    return () => {
        destroyers.forEach((destroy) => {
            if (typeof destroy === "function") {
                destroy();
            }
        });
    };
};
