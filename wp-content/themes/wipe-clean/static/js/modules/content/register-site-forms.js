const FORM_SELECTOR = "form[data-managed-form='true']";
const POPUP_OPEN_EVENT = "wipe-clean:popup-open";
const FORM_STATE_MESSAGES = {
    loading: "Отправляем заявку..."
};

const getFieldWrapper = (field) => field.closest(".ui-field, .popup-form__field");
const getCheckboxWrapper = (field) => field.closest(".ui-checkbox, .popup-form__checkbox");
const escapeFieldName = (value) => {
    if (window.CSS && typeof window.CSS.escape === "function") {
        return window.CSS.escape(value);
    }

    return String(value).replace(/["\\]/g, "\\$&");
};

const getManagedForm = (target) => {
    if (!(target instanceof Element)) {
        return null;
    }

    if (target.matches(FORM_SELECTOR)) {
        return target;
    }

    return target.querySelector(FORM_SELECTOR) || target.closest(".wpcf7")?.querySelector(FORM_SELECTOR) || null;
};

const getStatusNode = (form) => {
    const responseOutput = form.querySelector(".wpcf7-response-output");

    if (responseOutput) {
        responseOutput.classList.add("ui-form-status");

        return responseOutput;
    }

    let statusNode = form.querySelector("[data-form-status]");

    if (statusNode) {
        return statusNode;
    }

    statusNode = document.createElement("p");
    statusNode.className = "ui-form-status";
    statusNode.setAttribute("data-form-status", "");
    statusNode.setAttribute("role", "status");
    statusNode.setAttribute("aria-live", "polite");
    statusNode.setAttribute("aria-atomic", "true");

    const actions = form.querySelector("[data-form-actions]");

    if (actions) {
        actions.insertAdjacentElement("beforebegin", statusNode);
    } else {
        form.appendChild(statusNode);
    }

    return statusNode;
};

const clearValidationState = (form) => {
    form.querySelectorAll(".ui-field.is-invalid, .ui-field.is-valid, .popup-form__field.is-invalid, .popup-form__field.is-valid").forEach((field) => {
        field.classList.remove("is-invalid", "is-valid");
    });

    form.querySelectorAll(".ui-checkbox.is-invalid, .popup-form__checkbox.is-invalid").forEach((field) => {
        field.classList.remove("is-invalid");
    });

    form.querySelectorAll(".wpcf7-not-valid").forEach((field) => {
        field.classList.remove("wpcf7-not-valid");
    });

    form.querySelectorAll("[aria-invalid='true']").forEach((field) => {
        field.setAttribute("aria-invalid", "false");
    });
};

const syncFieldState = (field) => {
    const isCheckbox = field.type === "checkbox";
    const value = isCheckbox ? field.checked : String(field.value || "").trim();
    const isValid = Boolean(value);
    const fieldWrapper = getFieldWrapper(field);
    const checkboxWrapper = getCheckboxWrapper(field);

    fieldWrapper?.classList.toggle("is-invalid", !isValid);
    fieldWrapper?.classList.toggle("is-valid", isValid);
    checkboxWrapper?.classList.toggle("is-invalid", !isValid);
    field.setAttribute("aria-invalid", isValid ? "false" : "true");

    return isValid;
};

const setFormState = (form, state, message = "") => {
    const submitButton = form.querySelector("[type='submit']");
    const controls = Array.from(form.querySelectorAll("input, select, textarea, button"));
    const statusNode = getStatusNode(form);
    const isLoading = state === "loading";

    form.classList.toggle("is-loading", isLoading);
    form.classList.toggle("is-success", state === "success");
    form.classList.toggle("is-error", state === "error");
    form.setAttribute("aria-busy", isLoading ? "true" : "false");

    controls.forEach((control) => {
        if (isLoading) {
            control.setAttribute("disabled", "disabled");
        } else {
            control.removeAttribute("disabled");
        }
    });

    if (submitButton) {
        submitButton.disabled = isLoading;
    }

    if (!statusNode) {
        return;
    }

    statusNode.classList.remove("ui-form-status--loading", "ui-form-status--success", "ui-form-status--error");

    if ("loading" === state) {
        statusNode.textContent = FORM_STATE_MESSAGES.loading;
        statusNode.classList.add("ui-form-status--loading");
    } else if ("success" === state && message) {
        statusNode.textContent = message;
        statusNode.classList.add("ui-form-status--success");
    } else if ("error" === state && message) {
        statusNode.textContent = message;
        statusNode.classList.add("ui-form-status--error");
    } else {
        statusNode.textContent = "";
    }
};

const getPopupUpload = (target) => target.closest("[data-popup-upload]");

const resetPopupUpload = (upload) => {
    if (!(upload instanceof HTMLElement)) {
        return;
    }

    const preview = upload.querySelector("[data-popup-upload-preview]");
    const emptyState = upload.querySelector("[data-popup-upload-empty]");

    upload.classList.remove("is-filled");

    if (preview) {
        preview.innerHTML = "";
        preview.hidden = true;
    }

    if (emptyState) {
        emptyState.hidden = false;
    }
};

const createPopupUploadPreviewItem = (file) => {
    const item = document.createElement("div");
    const removeButton = document.createElement("span");

    item.className = "popup-upload__thumb";
    removeButton.className = "popup-upload__thumb-remove";
    removeButton.setAttribute("aria-hidden", "true");
    removeButton.innerHTML = "<svg width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M3 3L11 11' stroke='currentColor' stroke-width='1.8' stroke-linecap='round'/><path d='M11 3L3 11' stroke='currentColor' stroke-width='1.8' stroke-linecap='round'/></svg>";

    if (file.type.startsWith("image/")) {
        const image = document.createElement("img");

        image.alt = file.name;
        image.src = URL.createObjectURL(file);
        image.addEventListener("load", () => URL.revokeObjectURL(image.src), { once: true });
        item.appendChild(image);
    } else if (file.type.startsWith("video/")) {
        const video = document.createElement("video");

        video.src = URL.createObjectURL(file);
        video.muted = true;
        video.playsInline = true;
        video.preload = "metadata";
        video.addEventListener("loadeddata", () => URL.revokeObjectURL(video.src), { once: true });
        item.appendChild(video);
    } else {
        item.classList.add("popup-upload__thumb--file");
        item.innerHTML = `<span class="popup-upload__thumb-filetype">${String(file.name || "").split(".").pop() || "FILE"}</span>`;
    }

    item.appendChild(removeButton);

    return item;
};

const syncPopupUploadPreview = (input) => {
    const upload = getPopupUpload(input);

    if (!upload) {
        return;
    }

    const preview = upload.querySelector("[data-popup-upload-preview]");
    const emptyState = upload.querySelector("[data-popup-upload-empty]");
    const files = Array.from(input.files || []);

    if (!preview) {
        return;
    }

    preview.innerHTML = "";

    if (!files.length) {
        resetPopupUpload(upload);
        return;
    }

    files.forEach((file) => {
        preview.appendChild(createPopupUploadPreviewItem(file));
    });

    upload.classList.add("is-filled");
    preview.hidden = false;

    if (emptyState) {
        emptyState.hidden = true;
    }
};

const resetPopupUploads = (form) => {
    form.querySelectorAll("[data-popup-upload]").forEach((upload) => resetPopupUpload(upload));
};

const openStatusPopup = (popupId, trigger, options = {}) => {
    if (!popupId) {
        return;
    }

    document.dispatchEvent(new CustomEvent(POPUP_OPEN_EVENT, {
        detail: {
            id: popupId,
            trigger: trigger || null,
            options
        }
    }));
};

const resetChoiceGroup = (group) => {
    const choices = Array.from(group.querySelectorAll("[data-form-choice]"));
    const input = group.parentElement?.querySelector("[data-form-choice-input]");

    choices.forEach((choice, index) => {
        const isActive = index === 0;

        choice.classList.toggle("ui-choice--active", isActive);
        choice.setAttribute("aria-pressed", isActive ? "true" : "false");

        if (isActive && input) {
            input.value = choice.dataset.choiceValue || "";
        }
    });
};

const activateChoice = (group, button) => {
    const choices = Array.from(group.querySelectorAll("[data-form-choice]"));
    const input = group.parentElement?.querySelector("[data-form-choice-input]");

    choices.forEach((choice) => {
        const isActive = choice === button;

        choice.classList.toggle("ui-choice--active", isActive);
        choice.setAttribute("aria-pressed", isActive ? "true" : "false");
    });

    if (input) {
        input.value = button.dataset.choiceValue || "";
    }
};

const focusInvalidField = (form, invalidFields = []) => {
    const firstInvalidField = invalidFields[0];
    const targetName = firstInvalidField?.field || firstInvalidField?.name || "";

    if (!targetName) {
        return;
    }

    const target = form.querySelector(`[name="${escapeFieldName(targetName)}"]`);

    if (target && typeof target.focus === "function") {
        target.focus();
    }
};

const applyInvalidFields = (form, invalidFields = []) => {
    clearValidationState(form);

    invalidFields.forEach((fieldData) => {
        const fieldName = fieldData?.field || fieldData?.name || "";

        if (!fieldName) {
            return;
        }

        const field = form.querySelector(`[name="${escapeFieldName(fieldName)}"]`);

        if (!field) {
            return;
        }

        field.setAttribute("aria-invalid", "true");
        getFieldWrapper(field)?.classList.add("is-invalid");
        getCheckboxWrapper(field)?.classList.add("is-invalid");
    });
};

export const registerSiteForms = () => {
    const forms = Array.from(document.querySelectorAll(FORM_SELECTOR));

    if (!forms.length) {
        return null;
    }

    const handleChoiceClick = (event) => {
        const button = event.target.closest("[data-form-choice]");
        const group = event.currentTarget;

        if (!button) {
            return;
        }

        event.preventDefault();
        activateChoice(group, button);
    };

    const handleFieldChange = (event) => {
        const field = event.currentTarget;
        const form = field.form;

        syncFieldState(field);
        if (field.type === "file") {
            syncPopupUploadPreview(field);
        }

        if (form && (form.classList.contains("is-error") || form.classList.contains("is-success"))) {
            setFormState(form, "idle");
        }
    };

    const handleBeforeSubmit = (event) => {
        const form = getManagedForm(event.target);

        if (!form) {
            return;
        }

        clearValidationState(form);
        setFormState(form, "loading");
    };

    const handleInvalid = (event) => {
        const form = getManagedForm(event.target);

        if (!form) {
            return;
        }

        const invalidFields = event.detail?.apiResponse?.invalid_fields || [];
        const message = event.detail?.apiResponse?.message || "";

        applyInvalidFields(form, invalidFields);
        focusInvalidField(form, invalidFields);
        setFormState(form, "error", message);

    };

    const handleMailFailed = (event) => {
        const form = getManagedForm(event.target);

        if (!form) {
            return;
        }

        const message = event.detail?.apiResponse?.message || "";

        setFormState(form, "error", message);

        const errorPopupId = form.dataset.formErrorPopup || "";

        if (errorPopupId) {
            openStatusPopup(errorPopupId, form.querySelector("[type='submit']"), {
                preserveTrigger: Boolean(form.closest("[data-popup]"))
            });
        }
    };

    const handleMailSent = (event) => {
        const form = getManagedForm(event.target);

        if (!form) {
            return;
        }

        const message = event.detail?.apiResponse?.message || "";
        const successPopupId = form.dataset.formSuccessPopup || "";

        setFormState(form, "success", message);
        form.reset();
        clearValidationState(form);
        resetPopupUploads(form);
        form.querySelectorAll("[data-form-choice-group]").forEach(resetChoiceGroup);

        if (successPopupId) {
            openStatusPopup(successPopupId, form.querySelector("[type='submit']"), {
                preserveTrigger: Boolean(form.closest("[data-popup]"))
            });
        }
    };

    const handleSubmitResult = (event) => {
        const form = getManagedForm(event.target);

        if (!form) {
            return;
        }

        const status = event.detail?.status || "";

        if (!["mail_sent", "validation_failed", "mail_failed", "spam"].includes(status)) {
            setFormState(form, "idle");
        }
    };

    forms.forEach((form) => {
        form.setAttribute("aria-busy", "false");
        getStatusNode(form);

        form.querySelectorAll("[required], input[type='checkbox'], input[type='radio'], select, textarea").forEach((field) => {
            const eventName = field.matches("select, input[type='checkbox'], input[type='radio']") ? "change" : "input";

            field.addEventListener(eventName, handleFieldChange);
        });

        form.querySelectorAll("input[type='file']").forEach((input) => {
            input.addEventListener("change", handleFieldChange);
            syncPopupUploadPreview(input);
        });

        form.querySelectorAll("[data-form-choice-group]").forEach((group) => {
            resetChoiceGroup(group);
            group.addEventListener("click", handleChoiceClick);
        });
    });

    document.addEventListener("wpcf7beforesubmit", handleBeforeSubmit);
    document.addEventListener("wpcf7invalid", handleInvalid);
    document.addEventListener("wpcf7mailfailed", handleMailFailed);
    document.addEventListener("wpcf7spam", handleMailFailed);
    document.addEventListener("wpcf7mailsent", handleMailSent);
    document.addEventListener("wpcf7submit", handleSubmitResult);

    return () => {
        document.removeEventListener("wpcf7beforesubmit", handleBeforeSubmit);
        document.removeEventListener("wpcf7invalid", handleInvalid);
        document.removeEventListener("wpcf7mailfailed", handleMailFailed);
        document.removeEventListener("wpcf7spam", handleMailFailed);
        document.removeEventListener("wpcf7mailsent", handleMailSent);
        document.removeEventListener("wpcf7submit", handleSubmitResult);

        forms.forEach((form) => {
            form.querySelectorAll("[required], input[type='checkbox'], input[type='radio'], select, textarea").forEach((field) => {
                const eventName = field.matches("select, input[type='checkbox'], input[type='radio']") ? "change" : "input";

                field.removeEventListener(eventName, handleFieldChange);
            });

            form.querySelectorAll("input[type='file']").forEach((input) => {
                input.removeEventListener("change", handleFieldChange);
            });

            form.querySelectorAll("[data-form-choice-group]").forEach((group) => {
                group.removeEventListener("click", handleChoiceClick);
                resetChoiceGroup(group);
            });

            resetPopupUploads(form);
            clearValidationState(form);
            setFormState(form, "idle");
        });
    };
};
