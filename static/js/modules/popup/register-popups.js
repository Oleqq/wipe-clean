import { MOTION_CONFIG, prefersReducedMotion } from "../motion/motion-config.js";

const POPUP_ACTIVE_CLASS = "is-active";
const POPUP_BODY_ACTIVE_CLASS = "is-popup-active";
const POPUP_OPEN_EVENT = "wipe-clean:popup-open";
const POPUP_CLOSE_DURATION = prefersReducedMotion() ? 220 : MOTION_CONFIG.popup.closeDuration;
const POPUP_SWITCH_DELAY = prefersReducedMotion() ? 120 : MOTION_CONFIG.popup.switchDelay;
const FORM_STATE_MESSAGES = {
    loading: "Отправляем форму...",
    success: "Форма успешно отправлена.",
    error: "Проверьте обязательные поля и попробуйте снова."
};
const UPLOAD_ADD_ICON = `
<svg viewBox="0 0 70 70" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <rect x="1" y="1" width="68" height="68" rx="9" fill="white"/>
    <rect x="1" y="1" width="68" height="68" rx="9" stroke="url(#popup-upload-add-border)" stroke-width="2" stroke-linecap="round" stroke-dasharray="8 8"/>
    <path d="M23.333 35H46.6663" stroke="url(#popup-upload-add-line)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    <path d="M35 23.3333V46.6666" stroke="url(#popup-upload-add-line-vertical)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    <defs>
        <linearGradient id="popup-upload-add-border" x1="35" y1="0" x2="35" y2="70" gradientUnits="userSpaceOnUse">
            <stop stop-color="#40A5C1"/>
            <stop offset="1" stop-color="#0086B3"/>
        </linearGradient>
        <linearGradient id="popup-upload-add-line" x1="34.9997" y1="35" x2="34.9997" y2="36" gradientUnits="userSpaceOnUse">
            <stop stop-color="#0086B3"/>
            <stop offset="1" stop-color="#40A5C1"/>
        </linearGradient>
        <linearGradient id="popup-upload-add-line-vertical" x1="35.5" y1="23.3333" x2="35.5" y2="46.6666" gradientUnits="userSpaceOnUse">
            <stop stop-color="#0086B3"/>
            <stop offset="1" stop-color="#40A5C1"/>
        </linearGradient>
    </defs>
</svg>`;

const FOCUSABLE_SELECTORS = [
    "a[href]",
    "button:not([disabled])",
    "textarea:not([disabled])",
    "input:not([disabled]):not([type='hidden'])",
    "select:not([disabled])",
    "[tabindex]:not([tabindex='-1'])"
].join(", ");

export const registerPopups = () => {
    const popups = Array.from(document.querySelectorAll("[data-popup]"));

    if (!popups.length) {
        return null;
    }

    const popupMap = new Map(popups.map((popup) => [popup.dataset.popup, popup]));
    const popupForms = Array.from(document.querySelectorAll("[data-popup-demo-form]"));
    const popupOpenButtons = Array.from(document.querySelectorAll("[data-popup-open]"));
    const uploadBlocks = Array.from(document.querySelectorAll("[data-popup-upload]"));
    const choiceGroups = Array.from(document.querySelectorAll("[data-popup-choice-group]"));

    let activePopup = null;
    let lastTrigger = null;
    let closeTimerId = null;
    let switchTimerId = null;

    const ensureStatusNode = (form) => {
        let status = form.querySelector("[data-form-status]");

        if (status) {
            return status;
        }

        status = document.createElement("p");
        status.className = "ui-form-status popup-form__status";
        status.setAttribute("data-form-status", "");
        status.setAttribute("role", "status");
        status.setAttribute("aria-live", "polite");
        status.setAttribute("aria-atomic", "true");

        const actions = form.querySelector(".popup-form__actions");

        if (actions) {
            actions.insertAdjacentElement("beforebegin", status);
        } else {
            form.appendChild(status);
        }

        return status;
    };

    const getHeaderMenuState = () => {
        const header = document.querySelector("[data-header]");

        return Boolean(header && header.classList.contains("is-menu-open"));
    };

    const syncBodyLock = () => {
        const hasOpenPopup = Boolean(activePopup);
        document.body.classList.toggle("_lock", hasOpenPopup || getHeaderMenuState());
        document.body.classList.toggle(POPUP_BODY_ACTIVE_CLASS, hasOpenPopup);
    };

    const getFocusableElements = (popup) => {
        if (!popup) {
            return [];
        }

        return Array.from(popup.querySelectorAll(FOCUSABLE_SELECTORS)).filter((element) => !element.hasAttribute("hidden"));
    };

    const focusFirstElement = (popup) => {
        const focusableElements = getFocusableElements(popup);
        const target = focusableElements.find((element) => element.matches("input, textarea, select, button")) || focusableElements[0];

        target?.focus();
    };

    const openPopup = (popup, triggerElement = null, options = {}) => {
        if (!popup) {
            return;
        }

        const shouldPreserveTrigger = options.preserveTrigger === true;

        window.clearTimeout(closeTimerId);
        window.clearTimeout(switchTimerId);

        if (!shouldPreserveTrigger) {
            lastTrigger = triggerElement || document.activeElement;
        }

        activePopup = popup;
        popup.hidden = false;
        popup.setAttribute("aria-hidden", "false");
        syncBodyLock();

        requestAnimationFrame(() => {
            popup.classList.add(POPUP_ACTIVE_CLASS);

            window.setTimeout(() => {
                if (activePopup === popup) {
                    focusFirstElement(popup);
                }
            }, 90);
        });
    };

    const hidePopup = (popup, options = {}) => {
        const shouldRestoreFocus = options.restoreFocus !== false;

        if (!popup || popup !== activePopup) {
            return;
        }

        window.clearTimeout(switchTimerId);
        popup.classList.remove(POPUP_ACTIVE_CLASS);
        popup.setAttribute("aria-hidden", "true");

        window.clearTimeout(closeTimerId);
        closeTimerId = window.setTimeout(() => {
            popup.hidden = true;

            if (activePopup === popup) {
                activePopup = null;
            }

            syncBodyLock();

            if (shouldRestoreFocus && lastTrigger && typeof lastTrigger.focus === "function") {
                lastTrigger.focus();
            }
        }, POPUP_CLOSE_DURATION);
    };

    const showPopup = (popupId, triggerElement = null, options = {}) => {
        const popup = popupMap.get(popupId);

        if (!popup) {
            return;
        }

        if (activePopup === popup && popup.classList.contains(POPUP_ACTIVE_CLASS)) {
            return;
        }

        if (activePopup && activePopup !== popup) {
            const previousPopup = activePopup;

            previousPopup.classList.remove(POPUP_ACTIVE_CLASS);
            previousPopup.setAttribute("aria-hidden", "true");

            window.clearTimeout(closeTimerId);
            window.clearTimeout(switchTimerId);

            switchTimerId = window.setTimeout(() => {
                previousPopup.hidden = true;
                openPopup(popup, triggerElement, options);
            }, POPUP_SWITCH_DELAY);

            return;
        }

        openPopup(popup, triggerElement, options);
    };

    const resetUploadBlock = (uploadBlock) => {
        const input = uploadBlock.querySelector("[data-popup-upload-input]");
        const preview = uploadBlock.querySelector("[data-popup-upload-preview]");
        const emptyState = uploadBlock.querySelector("[data-popup-upload-empty]");
        const previewUrls = uploadBlock._previewUrls || [];

        previewUrls.forEach((url) => {
            URL.revokeObjectURL(url);
        });

        uploadBlock._previewUrls = [];
        uploadBlock._files = [];
        uploadBlock.classList.remove("is-filled");

        if (preview) {
            preview.innerHTML = "";
            preview.hidden = true;
        }

        if (emptyState) {
            emptyState.hidden = false;
        }

        if (input) {
            input.value = "";
        }
    };

    const syncUploadInput = (uploadBlock) => {
        const input = uploadBlock.querySelector("[data-popup-upload-input]");
        const files = uploadBlock._files || [];

        if (!input || typeof DataTransfer === "undefined") {
            return;
        }

        const dataTransfer = new DataTransfer();
        files.forEach((file) => {
            dataTransfer.items.add(file);
        });

        input.files = dataTransfer.files;
    };

    const renderUploadPreview = (uploadBlock) => {
        const preview = uploadBlock.querySelector("[data-popup-upload-preview]");
        const emptyState = uploadBlock.querySelector("[data-popup-upload-empty]");
        const files = Array.isArray(uploadBlock._files) ? uploadBlock._files : [];

        if (!preview || !emptyState) {
            return;
        }

        resetUploadBlock(uploadBlock);

        if (!files.length) {
            return;
        }

        const previewUrls = [];

        files.forEach((file, index) => {
            const previewItem = document.createElement("span");
            previewItem.className = "popup-upload__thumb";

            if (file.type.startsWith("image/")) {
                const image = document.createElement("img");
                const objectUrl = URL.createObjectURL(file);
                image.src = objectUrl;
                image.alt = file.name;
                previewUrls.push(objectUrl);
                previewItem.appendChild(image);
            } else {
                previewItem.classList.add("popup-upload__thumb--file");

                const fileType = document.createElement("span");
                fileType.className = "popup-upload__thumb-filetype";
                fileType.textContent = "MP4";
                previewItem.appendChild(fileType);
            }

            const removeButton = document.createElement("button");
            removeButton.className = "popup-upload__thumb-remove";
            removeButton.type = "button";
            removeButton.setAttribute("aria-label", `Удалить файл ${file.name}`);
            removeButton.setAttribute("data-popup-upload-remove", "");
            removeButton.setAttribute("data-file-index", String(index));
            removeButton.textContent = "×";
            previewItem.appendChild(removeButton);

            preview.appendChild(previewItem);
        });

        if (files.length < 6) {
            const addItem = document.createElement("span");
            addItem.className = "popup-upload__thumb popup-upload__thumb--add";
            addItem.innerHTML = `<span class="popup-upload__thumb-add-icon">${UPLOAD_ADD_ICON}</span>`;
            preview.appendChild(addItem);
        }

        uploadBlock._previewUrls = previewUrls;
        uploadBlock.classList.add("is-filled");
        emptyState.hidden = true;
        preview.hidden = false;
    };

    const validateForm = (form) => {
        const requiredFields = Array.from(form.querySelectorAll("[required]"));

        return requiredFields.every((field) => {
            const isCheckbox = field.type === "checkbox";
            const value = isCheckbox ? field.checked : String(field.value || "").trim();
            const isValid = Boolean(value);
            const fieldWrapper = field.closest(".popup-form__field");
            const checkboxWrapper = field.closest(".popup-form__checkbox");

            fieldWrapper?.classList.toggle("is-invalid", !isValid);
            fieldWrapper?.classList.toggle("is-valid", isValid);
            checkboxWrapper?.classList.toggle("is-invalid", !isValid);
            field.setAttribute("aria-invalid", isValid ? "false" : "true");

            return isValid;
        });
    };

    const setFormState = (form, state) => {
        const states = ["is-loading", "is-success", "is-error", "is-disabled"];
        const submitButton = form.querySelector("[type='submit']");
        const controls = Array.from(form.querySelectorAll("input, select, textarea, button"));
        const isLoading = state === "loading";
        const isDisabled = state === "loading" || state === "disabled";
        const status = ensureStatusNode(form);

        states.forEach((className) => {
            form.classList.toggle(className, className === `is-${state}`);
        });

        form.setAttribute("aria-busy", isLoading ? "true" : "false");

        if (status) {
            status.classList.remove("ui-form-status--loading", "ui-form-status--success", "ui-form-status--error");
            status.textContent = FORM_STATE_MESSAGES[state] || "";

            if (state === "loading" || state === "success" || state === "error") {
                status.classList.add(`ui-form-status--${state}`);
            }
        }

        controls.forEach((control) => {
            if (control === submitButton) {
                return;
            }

            if (isDisabled) {
                control.setAttribute("disabled", "disabled");
            } else {
                control.removeAttribute("disabled");
            }
        });

        if (submitButton) {
            submitButton.disabled = isDisabled;
        }
    };

    const resetFormState = (form) => {
        form.reset();
        setFormState(form, "idle");

        Array.from(form.querySelectorAll(".popup-form__field.is-invalid")).forEach((field) => {
            field.classList.remove("is-invalid");
        });

        Array.from(form.querySelectorAll(".popup-form__field.is-valid")).forEach((field) => {
            field.classList.remove("is-valid");
        });

        Array.from(form.querySelectorAll(".popup-form__checkbox.is-invalid")).forEach((field) => {
            field.classList.remove("is-invalid");
        });

        Array.from(form.querySelectorAll("[aria-invalid='true']")).forEach((field) => {
            field.setAttribute("aria-invalid", "false");
        });

        Array.from(form.querySelectorAll("[data-popup-choice-group]")).forEach((group) => {
            const choices = Array.from(group.querySelectorAll("[data-popup-choice]"));

            choices.forEach((choice, index) => {
                const shouldBeActive = index === 0;
                choice.classList.toggle("ui-choice--active", shouldBeActive);
                choice.setAttribute("aria-pressed", shouldBeActive ? "true" : "false");
            });
        });

        Array.from(form.querySelectorAll("[data-popup-upload]")).forEach((uploadBlock) => {
            resetUploadBlock(uploadBlock);
        });
    };

    const handleDocumentClick = (event) => {
        const removeUploadButton = event.target.closest("[data-popup-upload-remove]");
        const openButton = event.target.closest("[data-popup-open]");
        const closeButton = event.target.closest("[data-popup-close]");

        if (removeUploadButton) {
            event.preventDefault();
            event.stopPropagation();

            const uploadBlock = removeUploadButton.closest("[data-popup-upload]");
            const fileIndex = Number(removeUploadButton.dataset.fileIndex);

            if (!uploadBlock || Number.isNaN(fileIndex)) {
                return;
            }

            const currentFiles = Array.isArray(uploadBlock._files) ? uploadBlock._files : [];
            uploadBlock._files = currentFiles.filter((_, index) => index !== fileIndex);
            syncUploadInput(uploadBlock);
            renderUploadPreview(uploadBlock);
            return;
        }

        if (openButton) {
            event.preventDefault();
            showPopup(openButton.dataset.popupOpen, openButton);
            return;
        }

        if (closeButton) {
            event.preventDefault();
            hidePopup(activePopup);
            return;
        }
    };

    const handlePopupOpenRequest = (event) => {
        const detail = event.detail || {};

        if (!detail.id) {
            return;
        }

        showPopup(detail.id, detail.trigger || null, detail.options || {});
    };

    const handleDocumentKeydown = (event) => {
        if (!activePopup) {
            return;
        }

        if (event.key === "Escape") {
            event.preventDefault();
            hidePopup(activePopup);
            return;
        }

        if (event.key !== "Tab") {
            return;
        }

        const focusableElements = getFocusableElements(activePopup);

        if (!focusableElements.length) {
            return;
        }

        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        if (event.shiftKey && document.activeElement === firstElement) {
            event.preventDefault();
            lastElement.focus();
        }

        if (!event.shiftKey && document.activeElement === lastElement) {
            event.preventDefault();
            firstElement.focus();
        }
    };

    const handleFormSubmit = (event) => {
        event.preventDefault();

        const form = event.currentTarget;
        const submitButton = event.submitter || form.querySelector("[type='submit']");
        const successPopupId = form.dataset.popupSuccess || "popup-status-success";
        const errorPopupId = form.dataset.popupError || "popup-status-error";
        const isValid = validateForm(form);

        if (isValid) {
            setFormState(form, "loading");

            window.setTimeout(() => {
                setFormState(form, "success");
                resetFormState(form);
                showPopup(successPopupId, submitButton, { preserveTrigger: true });
            }, Math.max(POPUP_SWITCH_DELAY, 160));

            return;
        }

        setFormState(form, "error");
        showPopup(errorPopupId, submitButton, { preserveTrigger: true });
    };

    const handleUploadChange = (event) => {
        const input = event.currentTarget;
        const uploadBlock = input.closest("[data-popup-upload]");

        if (!uploadBlock) {
            return;
        }

        const currentFiles = Array.isArray(uploadBlock._files) ? uploadBlock._files : [];
        const nextFiles = currentFiles.concat(Array.from(input.files || [])).slice(0, 6);

        uploadBlock._files = nextFiles;
        syncUploadInput(uploadBlock);
        renderUploadPreview(uploadBlock);
    };

    const handleChoiceClick = (event) => {
        const button = event.target.closest("[data-popup-choice]");
        const group = event.currentTarget;

        if (!button) {
            return;
        }

        event.preventDefault();

        Array.from(group.querySelectorAll("[data-popup-choice]")).forEach((choice) => {
            const isActive = choice === button;
            choice.classList.toggle("ui-choice--active", isActive);
            choice.setAttribute("aria-pressed", isActive ? "true" : "false");
        });
    };

    document.addEventListener("click", handleDocumentClick);
    document.addEventListener("keydown", handleDocumentKeydown);
    document.addEventListener(POPUP_OPEN_EVENT, handlePopupOpenRequest);

    popupOpenButtons.forEach((button) => {
        button.setAttribute("aria-haspopup", "dialog");
        if (button.dataset.popupOpen) {
            button.setAttribute("aria-controls", button.dataset.popupOpen);
        }
    });

    popupForms.forEach((form) => {
        form.setAttribute("aria-busy", "false");
        ensureStatusNode(form);
        form.addEventListener("submit", handleFormSubmit);
    });

    uploadBlocks.forEach((uploadBlock) => {
        uploadBlock._previewUrls = [];
        uploadBlock._files = [];
        uploadBlock.querySelector("[data-popup-upload-input]")?.addEventListener("change", handleUploadChange);
    });

    choiceGroups.forEach((group) => {
        group.addEventListener("click", handleChoiceClick);
    });

    return () => {
        window.clearTimeout(closeTimerId);
        window.clearTimeout(switchTimerId);
        document.removeEventListener("click", handleDocumentClick);
        document.removeEventListener("keydown", handleDocumentKeydown);
        document.removeEventListener(POPUP_OPEN_EVENT, handlePopupOpenRequest);
        popupForms.forEach((form) => {
            form.removeEventListener("submit", handleFormSubmit);
            setFormState(form, "idle");
        });
        uploadBlocks.forEach((uploadBlock) => {
            uploadBlock.querySelector("[data-popup-upload-input]")?.removeEventListener("change", handleUploadChange);
            resetUploadBlock(uploadBlock);
        });
        choiceGroups.forEach((group) => {
            group.removeEventListener("click", handleChoiceClick);
        });
        activePopup = null;
        syncBodyLock();
    };
};
