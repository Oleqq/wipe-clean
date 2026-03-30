const FORM_STATE_MESSAGES = {
    loading: "Отправляем форму...",
    success: "Форма успешно отправлена.",
    error: "Проверьте обязательные поля и попробуйте снова."
};

const POPUP_OPEN_EVENT = "wipe-clean:popup-open";

const getFieldWrapper = (field) => field.closest(".ui-field, .popup-form__field");
const getCheckboxWrapper = (field) => field.closest(".ui-checkbox, .popup-form__checkbox");

const ensureStatusNode = (form) => {
    let status = form.querySelector("[data-form-status]");

    if (status) {
        return status;
    }

    status = document.createElement("p");
    status.className = "ui-form-status";
    status.setAttribute("data-form-status", "");
    status.setAttribute("role", "status");
    status.setAttribute("aria-live", "polite");
    status.setAttribute("aria-atomic", "true");

    const actions = form.querySelector("[data-form-actions]");

    if (actions) {
        actions.insertAdjacentElement("beforebegin", status);
    } else {
        form.appendChild(status);
    }

    return status;
};

const clearValidationState = (form) => {
    form.querySelectorAll(".ui-field.is-invalid, .ui-field.is-valid, .popup-form__field.is-invalid, .popup-form__field.is-valid").forEach((field) => {
        field.classList.remove("is-invalid", "is-valid");
    });

    form.querySelectorAll(".ui-checkbox.is-invalid, .popup-form__checkbox.is-invalid").forEach((field) => {
        field.classList.remove("is-invalid");
    });

    form.querySelectorAll("[aria-invalid='true']").forEach((field) => {
        field.setAttribute("aria-invalid", "false");
    });
};

const validateForm = (form) => {
    const requiredFields = Array.from(form.querySelectorAll("[required]"));

    return requiredFields.every((field) => {
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

const openStatusPopup = (popupId, trigger, options = {}) => {
    if (!popupId) {
        return;
    }

    document.dispatchEvent(new CustomEvent(POPUP_OPEN_EVENT, {
        detail: {
            id: popupId,
            trigger: trigger || null,
            options: options
        }
    }));
};

const resetChoiceGroup = (group) => {
    const choices = Array.from(group.querySelectorAll("[data-demo-choice]"));
    const input = group.parentElement?.querySelector("[data-demo-choice-input]");

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
    const choices = Array.from(group.querySelectorAll("[data-demo-choice]"));
    const input = group.parentElement?.querySelector("[data-demo-choice-input]");

    choices.forEach((choice) => {
        const isActive = choice === button;

        choice.classList.toggle("ui-choice--active", isActive);
        choice.setAttribute("aria-pressed", isActive ? "true" : "false");
    });

    if (input) {
        input.value = button.dataset.choiceValue || "";
    }
};

export const registerDemoForms = () => {
    const forms = Array.from(document.querySelectorAll("[data-demo-form]"));

    if (!forms.length) {
        return null;
    }

    const timeouts = new WeakMap();

    const handleChoiceClick = (event) => {
        const button = event.target.closest("[data-demo-choice]");
        const group = event.currentTarget;

        if (!button) {
            return;
        }

        event.preventDefault();
        activateChoice(group, button);
    };

    const handleSubmit = (event) => {
        event.preventDefault();

        const form = event.currentTarget;
        const submitButton = event.submitter || form.querySelector("[type='submit']");
        const successPopupId = form.dataset.formSuccessPopup || "";
        const errorPopupId = form.dataset.formErrorPopup || "";
        const isValid = validateForm(form);

        if (!isValid) {
            setFormState(form, "error");
            openStatusPopup(errorPopupId, submitButton, { preserveTrigger: true });
            return;
        }

        setFormState(form, "loading");

        const timeoutId = window.setTimeout(() => {
            setFormState(form, "success");
            form.reset();
            clearValidationState(form);
            form.querySelectorAll("[data-demo-choice-group]").forEach(resetChoiceGroup);
            openStatusPopup(successPopupId, submitButton, { preserveTrigger: true });

            window.setTimeout(() => {
                setFormState(form, "idle");
            }, 120);
        }, 220);

        timeouts.set(form, timeoutId);
    };

    forms.forEach((form) => {
        form.setAttribute("aria-busy", "false");
        ensureStatusNode(form);
        form.addEventListener("submit", handleSubmit);
        form.querySelectorAll("[data-demo-choice-group]").forEach((group) => {
            resetChoiceGroup(group);
            group.addEventListener("click", handleChoiceClick);
        });
    });

    return () => {
        forms.forEach((form) => {
            const timeoutId = timeouts.get(form);

            if (timeoutId) {
                window.clearTimeout(timeoutId);
            }

            form.removeEventListener("submit", handleSubmit);
            form.querySelectorAll("[data-demo-choice-group]").forEach((group) => {
                group.removeEventListener("click", handleChoiceClick);
                resetChoiceGroup(group);
            });
            clearValidationState(form);
            setFormState(form, "idle");
        });
    };
};
