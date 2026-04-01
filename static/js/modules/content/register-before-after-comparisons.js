const getInitialPosition = (card, isMobile) => {
    var mobileValue = Number(card.getAttribute("data-start-mobile"));
    var desktopValue = Number(card.getAttribute("data-start"));

    if (isMobile && !Number.isNaN(mobileValue)) {
        return mobileValue;
    }

    if (!Number.isNaN(desktopValue)) {
        return desktopValue;
    }

    return 50;
};

const clampPosition = (value) => {
    return Math.min(100, Math.max(0, value));
};

const setCardPosition = (card, input, value) => {
    var nextValue = clampPosition(value);

    card.style.setProperty("--before-after-position", nextValue + "%");
    card.setAttribute("data-position", String(nextValue));

    if (input && Number(input.value) !== nextValue) {
        input.value = String(nextValue);
    }
};

const getPointerPosition = (card, clientX) => {
    var rect = card.getBoundingClientRect();

    if (!rect.width) {
        return 50;
    }

    return ((clientX - rect.left) / rect.width) * 100;
};

export const registerBeforeAfterComparisons = () => {
    var cards = Array.from(document.querySelectorAll("[data-before-after]"));

    if (!cards.length) {
        return function() {};
    }

    var mobileMedia = window.matchMedia("(max-width: 650px)");

    var cleanups = cards.map(function(card) {
        var input = card.querySelector("[data-before-after-range]");
        var media = card.querySelector(".before-after-card__media");
        var dragPointerId = null;
        var dragTarget = null;

        if (!input) {
            return null;
        }

        setCardPosition(card, input, getInitialPosition(card, mobileMedia.matches));

        var handleInput = function() {
            card.classList.add("is-dragging");
            card.dataset.interacted = "true";
            setCardPosition(card, input, Number(input.value));
        };

        var handleChange = function() {
            card.classList.remove("is-dragging");
            setCardPosition(card, input, Number(input.value));
        };

        var handleFocus = function() {
            card.classList.add("is-focused");
        };

        var handleBlur = function() {
            card.classList.remove("is-focused");
            card.classList.remove("is-dragging");
        };

        var handlePointerUp = function() {
            card.classList.remove("is-dragging");
        };

        var startDrag = function(event) {
            if (event.pointerType === "mouse" && event.button !== 0) {
                return;
            }

            dragPointerId = event.pointerId;
            dragTarget = event.currentTarget;
            card.classList.add("is-dragging");
            card.dataset.interacted = "true";
            setCardPosition(card, input, getPointerPosition(card, event.clientX));

            if (dragTarget && typeof dragTarget.setPointerCapture === "function") {
                dragTarget.setPointerCapture(event.pointerId);
            }
        };

        var moveDrag = function(event) {
            if (dragPointerId !== event.pointerId) {
                return;
            }

            card.classList.add("is-dragging");
            card.dataset.interacted = "true";
            setCardPosition(card, input, getPointerPosition(card, event.clientX));
        };

        var endDrag = function(event) {
            if (dragPointerId !== event.pointerId) {
                return;
            }

            if (dragTarget && typeof dragTarget.releasePointerCapture === "function" && dragTarget.hasPointerCapture(event.pointerId)) {
                dragTarget.releasePointerCapture(event.pointerId);
            }

            dragPointerId = null;
            dragTarget = null;
            card.classList.remove("is-dragging");
        };

        var handleMediaChange = function() {
            if (card.dataset.interacted === "true") {
                return;
            }

            setCardPosition(card, input, getInitialPosition(card, mobileMedia.matches));
        };

        input.addEventListener("input", handleInput);
        input.addEventListener("change", handleChange);
        input.addEventListener("focus", handleFocus);
        input.addEventListener("blur", handleBlur);
        input.addEventListener("pointerup", handlePointerUp);
        input.addEventListener("pointerdown", startDrag);
        input.addEventListener("pointermove", moveDrag);
        input.addEventListener("pointercancel", endDrag);

        if (media) {
            media.addEventListener("pointerdown", startDrag);
            media.addEventListener("pointermove", moveDrag);
            media.addEventListener("pointerup", endDrag);
            media.addEventListener("pointercancel", endDrag);
        }

        if (typeof mobileMedia.addEventListener === "function") {
            mobileMedia.addEventListener("change", handleMediaChange);
        } else {
            mobileMedia.addListener(handleMediaChange);
        }

        return function() {
            input.removeEventListener("input", handleInput);
            input.removeEventListener("change", handleChange);
            input.removeEventListener("focus", handleFocus);
            input.removeEventListener("blur", handleBlur);
            input.removeEventListener("pointerup", handlePointerUp);
            input.removeEventListener("pointerdown", startDrag);
            input.removeEventListener("pointermove", moveDrag);
            input.removeEventListener("pointercancel", endDrag);

            if (media) {
                media.removeEventListener("pointerdown", startDrag);
                media.removeEventListener("pointermove", moveDrag);
                media.removeEventListener("pointerup", endDrag);
                media.removeEventListener("pointercancel", endDrag);
            }

            if (typeof mobileMedia.removeEventListener === "function") {
                mobileMedia.removeEventListener("change", handleMediaChange);
            } else {
                mobileMedia.removeListener(handleMediaChange);
            }
        };
    });

    return function() {
        cleanups.forEach(function(cleanup) {
            if (typeof cleanup === "function") {
                cleanup();
            }
        });
    };
};
