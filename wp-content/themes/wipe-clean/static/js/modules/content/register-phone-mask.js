const PHONE_PREFIX = "+7";
const PHONE_MAX_DIGITS = 10;

const normalizeDigits = (value) => {
    let digits = String(value || "").replace(/\D/g, "");

    if (digits.startsWith("7") || digits.startsWith("8")) {
        digits = digits.slice(1);
    }

    return digits.slice(0, PHONE_MAX_DIGITS);
};

const formatPhoneValue = (value) => {
    const digits = normalizeDigits(value);

    if (!digits.length) {
        return "";
    }

    let formattedValue = `${PHONE_PREFIX}`;

    if (digits.length > 0) {
        formattedValue += ` ${digits.slice(0, 3)}`;
    }

    if (digits.length > 3) {
        formattedValue += ` ${digits.slice(3, 6)}`;
    }

    if (digits.length > 6) {
        formattedValue += ` ${digits.slice(6, 8)}`;
    }

    if (digits.length > 8) {
        formattedValue += ` ${digits.slice(8, 10)}`;
    }

    return formattedValue;
};

export const registerPhoneMask = () => {
    const phoneInputs = Array.from(document.querySelectorAll("input[type='tel']"));

    if (!phoneInputs.length) {
        return null;
    }

    const cleanups = phoneInputs.map((input) => {
        if (input.dataset.phoneMaskReady === "true") {
            return null;
        }

        const handleFocus = () => {
            if (!String(input.value || "").trim()) {
                input.value = `${PHONE_PREFIX} `;
            }
        };

        const handleInput = () => {
            const formattedValue = formatPhoneValue(input.value);
            input.value = formattedValue || `${PHONE_PREFIX} `;
            window.requestAnimationFrame(() => {
                const cursorPosition = input.value.length;
                input.setSelectionRange(cursorPosition, cursorPosition);
            });
        };

        const handleBlur = () => {
            if (!normalizeDigits(input.value).length) {
                input.value = "";
            }
        };

        const handlePaste = (event) => {
            event.preventDefault();

            const pastedValue = event.clipboardData?.getData("text") || "";
            input.value = formatPhoneValue(pastedValue) || `${PHONE_PREFIX} `;
        };

        input.dataset.phoneMaskReady = "true";
        input.setAttribute("inputmode", "tel");
        input.setAttribute("autocomplete", "tel");
        input.setAttribute("maxlength", "16");
        input.value = formatPhoneValue(input.value);

        input.addEventListener("focus", handleFocus);
        input.addEventListener("input", handleInput);
        input.addEventListener("blur", handleBlur);
        input.addEventListener("paste", handlePaste);

        return () => {
            delete input.dataset.phoneMaskReady;
            input.removeEventListener("focus", handleFocus);
            input.removeEventListener("input", handleInput);
            input.removeEventListener("blur", handleBlur);
            input.removeEventListener("paste", handlePaste);
        };
    });

    return () => {
        cleanups.forEach((cleanup) => {
            if (typeof cleanup === "function") {
                cleanup();
            }
        });
    };
};
