// static/js/modules/content/register-before-after-comparisons.js
var getInitialPosition = (card, isMobile) => {
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
var clampPosition = (value) => {
  return Math.min(100, Math.max(0, value));
};
var setCardPosition = (card, input, value) => {
  var nextValue = clampPosition(value);
  card.style.setProperty("--before-after-position", nextValue + "%");
  card.setAttribute("data-position", String(nextValue));
  if (input && Number(input.value) !== nextValue) {
    input.value = String(nextValue);
  }
};
var registerBeforeAfterComparisons = () => {
  var cards = Array.from(document.querySelectorAll("[data-before-after]"));
  if (!cards.length) {
    return function() {
    };
  }
  var mobileMedia = window.matchMedia("(max-width: 650px)");
  var cleanups = cards.map(function(card) {
    var input = card.querySelector("[data-before-after-range]");
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

// static/js/modules/content/register-before-after-results-load-more.js
var getResponsiveCount = (section, isMobile, desktopName, mobileName, fallback) => {
  var desktopValue = Number(section.getAttribute(desktopName));
  var mobileValue = Number(section.getAttribute(mobileName));
  if (isMobile && !Number.isNaN(mobileValue)) {
    return mobileValue;
  }
  if (!Number.isNaN(desktopValue)) {
    return desktopValue;
  }
  return fallback;
};
var getCurrentSettings = (section, mediaQuery) => {
  return {
    initial: getResponsiveCount(section, mediaQuery.matches, "data-initial-desktop", "data-initial-mobile", 6),
    step: getResponsiveCount(section, mediaQuery.matches, "data-step-desktop", "data-step-mobile", 6)
  };
};
var setButtonState = (button, state) => {
  var isLoading = state === "loading";
  var isExhausted = state === "exhausted";
  if (button.__hideTimeoutId) {
    window.clearTimeout(button.__hideTimeoutId);
    button.__hideTimeoutId = 0;
  }
  button.classList.toggle("is-loading", isLoading);
  button.classList.toggle("is-exhausted", isExhausted);
  button.disabled = isLoading;
  button.setAttribute("aria-busy", isLoading ? "true" : "false");
  if (isExhausted) {
    button.__hideTimeoutId = window.setTimeout(function() {
      button.hidden = true;
      button.__hideTimeoutId = 0;
    }, 250);
    return;
  }
  button.hidden = false;
};
var renderItems = (sectionState, options) => {
  var settings = getCurrentSettings(sectionState.section, sectionState.mediaQuery);
  var visibleCount = typeof options.visibleCount === "number" ? options.visibleCount : sectionState.visibleCount;
  var animate = Boolean(options.animate);
  var startIndex = typeof options.startIndex === "number" ? options.startIndex : 0;
  sectionState.items.forEach(function(item, index) {
    var isVisible = index < visibleCount;
    item.hidden = !isVisible;
    if (animate && index >= startIndex && isVisible) {
      item.style.setProperty("--before-after-delay", (index - startIndex) * 0.08 + "s");
      item.classList.add("is-revealed");
    }
  });
  sectionState.visibleCount = visibleCount;
  if (sectionState.button) {
    if (visibleCount >= sectionState.items.length) {
      setButtonState(sectionState.button, "exhausted");
    } else {
      setButtonState(sectionState.button, "idle");
    }
  }
  sectionState.section.setAttribute("data-visible-count", String(visibleCount));
  sectionState.section.setAttribute("data-current-step", String(settings.step));
};
var createSectionState = (section, mediaQuery) => {
  var items = Array.from(section.querySelectorAll("[data-before-after-item]"));
  var button = section.querySelector("[data-before-after-more]");
  if (!items.length) {
    return null;
  }
  return {
    section,
    items,
    button,
    mediaQuery,
    visibleCount: 0
  };
};
var registerBeforeAfterResultsLoadMore = () => {
  var sections = Array.from(document.querySelectorAll("[data-before-after-results]"));
  if (!sections.length) {
    return function() {
    };
  }
  var mobileMedia = window.matchMedia("(max-width: 650px)");
  var cleanups = sections.map(function(section) {
    var sectionState = createSectionState(section, mobileMedia);
    var loadingTimeoutId = 0;
    if (!sectionState) {
      return null;
    }
    var initialSettings = getCurrentSettings(section, mobileMedia);
    renderItems(sectionState, {
      visibleCount: initialSettings.initial,
      animate: false
    });
    var handleRevealAnimationEnd = function(event) {
      var item = event.target.closest("[data-before-after-item]");
      if (!item || !section.contains(item)) {
        return;
      }
      item.classList.remove("is-revealed");
      item.style.removeProperty("--before-after-delay");
    };
    var handleClick = function() {
      if (!sectionState.button || sectionState.button.classList.contains("is-loading")) {
        return;
      }
      var settings = getCurrentSettings(section, mobileMedia);
      var nextVisibleCount = Math.min(sectionState.visibleCount + settings.step, sectionState.items.length);
      var startIndex = sectionState.visibleCount;
      setButtonState(sectionState.button, "loading");
      loadingTimeoutId = window.setTimeout(function() {
        renderItems(sectionState, {
          visibleCount: nextVisibleCount,
          startIndex,
          animate: true
        });
        loadingTimeoutId = 0;
      }, 380);
    };
    var handleMediaChange = function() {
      var settings = getCurrentSettings(section, mobileMedia);
      var nextVisibleCount = Math.max(sectionState.visibleCount, settings.initial);
      renderItems(sectionState, {
        visibleCount: Math.min(nextVisibleCount, sectionState.items.length),
        animate: false
      });
    };
    if (sectionState.button) {
      sectionState.button.addEventListener("click", handleClick);
    }
    section.addEventListener("animationend", handleRevealAnimationEnd);
    if (typeof mobileMedia.addEventListener === "function") {
      mobileMedia.addEventListener("change", handleMediaChange);
    } else {
      mobileMedia.addListener(handleMediaChange);
    }
    return function() {
      if (loadingTimeoutId) {
        window.clearTimeout(loadingTimeoutId);
      }
      if (sectionState.button) {
        if (sectionState.button.__hideTimeoutId) {
          window.clearTimeout(sectionState.button.__hideTimeoutId);
          sectionState.button.__hideTimeoutId = 0;
        }
        sectionState.button.removeEventListener("click", handleClick);
      }
      section.removeEventListener("animationend", handleRevealAnimationEnd);
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

// static/js/modules/content/register-blog-archive-load-more.js
var getResponsiveCount2 = (section, isMobile, desktopName, mobileName, fallback) => {
  var desktopValue = Number(section.getAttribute(desktopName));
  var mobileValue = Number(section.getAttribute(mobileName));
  if (isMobile && !Number.isNaN(mobileValue)) {
    return mobileValue;
  }
  if (!Number.isNaN(desktopValue)) {
    return desktopValue;
  }
  return fallback;
};
var getCurrentSettings2 = (section, mediaQuery) => {
  return {
    initial: getResponsiveCount2(section, mediaQuery.matches, "data-initial-desktop", "data-initial-mobile", 6),
    step: getResponsiveCount2(section, mediaQuery.matches, "data-step-desktop", "data-step-mobile", 4)
  };
};
var setButtonState2 = (button, state) => {
  var isLoading = state === "loading";
  var isExhausted = state === "exhausted";
  if (!button) {
    return;
  }
  if (button.__hideTimeoutId) {
    window.clearTimeout(button.__hideTimeoutId);
    button.__hideTimeoutId = 0;
  }
  button.classList.toggle("is-loading", isLoading);
  button.classList.toggle("is-exhausted", isExhausted);
  button.disabled = isLoading;
  button.setAttribute("aria-busy", isLoading ? "true" : "false");
  if (isExhausted) {
    button.__hideTimeoutId = window.setTimeout(function() {
      button.hidden = true;
      button.__hideTimeoutId = 0;
    }, 250);
    return;
  }
  button.hidden = false;
};
var setFooterLoadingState = (footer, isLoading) => {
  if (!footer) {
    return;
  }
  footer.classList.toggle("is-loading", isLoading);
};
var renderItems2 = (sectionState, options) => {
  var settings = getCurrentSettings2(sectionState.section, sectionState.mediaQuery);
  var visibleCount = typeof options.visibleCount === "number" ? options.visibleCount : sectionState.visibleCount;
  var animate = Boolean(options.animate);
  var startIndex = typeof options.startIndex === "number" ? options.startIndex : 0;
  sectionState.items.forEach(function(item, index) {
    var isVisible = index < visibleCount;
    item.hidden = !isVisible;
    if (animate && index >= startIndex && isVisible) {
      item.style.setProperty("--blog-archive-delay", (index - startIndex) * 0.08 + "s");
      item.classList.add("is-revealed");
    }
  });
  sectionState.visibleCount = visibleCount;
  if (visibleCount >= sectionState.items.length) {
    setButtonState2(sectionState.button, "exhausted");
  } else {
    setButtonState2(sectionState.button, "idle");
  }
  sectionState.section.setAttribute("data-visible-count", String(visibleCount));
  sectionState.section.setAttribute("data-current-step", String(settings.step));
};
var registerBlogArchiveLoadMore = () => {
  var sections = Array.from(document.querySelectorAll("[data-blog-archive]"));
  if (!sections.length) {
    return function() {
    };
  }
  var mobileMedia = window.matchMedia("(max-width: 650px)");
  var cleanups = sections.map(function(section) {
    var items = Array.from(section.querySelectorAll("[data-blog-item]"));
    var button = section.querySelector("[data-blog-more]");
    var footer = section.querySelector("[data-blog-footer]");
    var loadingTimeoutId = 0;
    if (!items.length) {
      return null;
    }
    var sectionState = {
      section,
      items,
      button,
      footer,
      mediaQuery: mobileMedia,
      visibleCount: 0
    };
    var initialSettings = getCurrentSettings2(section, mobileMedia);
    renderItems2(sectionState, {
      visibleCount: initialSettings.initial,
      animate: false
    });
    var handleRevealAnimationEnd = function(event) {
      var item = event.target.closest("[data-blog-item]");
      if (!item || !section.contains(item)) {
        return;
      }
      item.classList.remove("is-revealed");
      item.style.removeProperty("--blog-archive-delay");
    };
    var handleClick = function() {
      if (!button || button.classList.contains("is-loading")) {
        return;
      }
      var settings = getCurrentSettings2(section, mobileMedia);
      var nextVisibleCount = Math.min(sectionState.visibleCount + settings.step, items.length);
      var startIndex = sectionState.visibleCount;
      setButtonState2(button, "loading");
      setFooterLoadingState(footer, true);
      loadingTimeoutId = window.setTimeout(function() {
        renderItems2(sectionState, {
          visibleCount: nextVisibleCount,
          startIndex,
          animate: true
        });
        setFooterLoadingState(footer, false);
        loadingTimeoutId = 0;
      }, 420);
    };
    var handleMediaChange = function() {
      var settings = getCurrentSettings2(section, mobileMedia);
      var nextVisibleCount = Math.max(sectionState.visibleCount, settings.initial);
      renderItems2(sectionState, {
        visibleCount: Math.min(nextVisibleCount, items.length),
        animate: false
      });
    };
    if (button) {
      button.addEventListener("click", handleClick);
    }
    section.addEventListener("animationend", handleRevealAnimationEnd);
    if (typeof mobileMedia.addEventListener === "function") {
      mobileMedia.addEventListener("change", handleMediaChange);
    } else {
      mobileMedia.addListener(handleMediaChange);
    }
    return function() {
      if (loadingTimeoutId) {
        window.clearTimeout(loadingTimeoutId);
      }
      setFooterLoadingState(footer, false);
      if (button) {
        if (button.__hideTimeoutId) {
          window.clearTimeout(button.__hideTimeoutId);
          button.__hideTimeoutId = 0;
        }
        button.removeEventListener("click", handleClick);
      }
      section.removeEventListener("animationend", handleRevealAnimationEnd);
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

// static/js/modules/content/register-message-reviews-load-more.js
var getResponsiveCount3 = (section, isMobile, desktopName, mobileName, fallback) => {
  var desktopValue = Number(section.getAttribute(desktopName));
  var mobileValue = Number(section.getAttribute(mobileName));
  if (isMobile && !Number.isNaN(mobileValue)) {
    return mobileValue;
  }
  if (!Number.isNaN(desktopValue)) {
    return desktopValue;
  }
  return fallback;
};
var getCurrentSettings3 = (section, mediaQuery) => {
  return {
    initial: getResponsiveCount3(section, mediaQuery.matches, "data-initial-desktop", "data-initial-mobile", 10),
    step: getResponsiveCount3(section, mediaQuery.matches, "data-step-desktop", "data-step-mobile", 2)
  };
};
var getCurrentColumnCount = (section, mediaQuery) => {
  return getResponsiveCount3(section, mediaQuery.matches, "data-columns-desktop", "data-columns-mobile", 4);
};
var getCurrentItemOrder = (item, mediaQuery) => {
  var attrName = mediaQuery.matches ? "data-mobile-order" : "data-desktop-order";
  var value = Number(item.getAttribute(attrName));
  if (!Number.isNaN(value)) {
    return value;
  }
  return Number(item.getAttribute("data-item-index")) || 0;
};
var getCurrentItemColumn = (item, mediaQuery) => {
  var attrName = mediaQuery.matches ? "data-mobile-column" : "data-desktop-column";
  var value = Number(item.getAttribute(attrName));
  if (!Number.isNaN(value) && value > 0) {
    return value;
  }
  return 1;
};
var getOrderedItems = (items, mediaQuery) => {
  return items.slice().sort(function(itemA, itemB) {
    var orderA = getCurrentItemOrder(itemA, mediaQuery);
    var orderB = getCurrentItemOrder(itemB, mediaQuery);
    if (orderA !== orderB) {
      return orderA - orderB;
    }
    return getCurrentItemColumn(itemA, mediaQuery) - getCurrentItemColumn(itemB, mediaQuery);
  });
};
var setButtonState3 = (button, state) => {
  var isLoading = state === "loading";
  var isExhausted = state === "exhausted";
  if (!button) {
    return;
  }
  if (button.__hideTimeoutId) {
    window.clearTimeout(button.__hideTimeoutId);
    button.__hideTimeoutId = 0;
  }
  button.classList.toggle("is-loading", isLoading);
  button.classList.toggle("is-exhausted", isExhausted);
  button.disabled = isLoading;
  button.setAttribute("aria-busy", isLoading ? "true" : "false");
  if (isExhausted) {
    button.__hideTimeoutId = window.setTimeout(function() {
      button.hidden = true;
      button.__hideTimeoutId = 0;
    }, 250);
    return;
  }
  button.hidden = false;
};
var setFooterLoadingState2 = (footer, isLoading) => {
  if (!footer) {
    return;
  }
  footer.classList.toggle("is-loading", isLoading);
};
var layoutItems = (sectionState) => {
  var columnCount = getCurrentColumnCount(sectionState.section, sectionState.mediaQuery);
  var orderedItems = getOrderedItems(sectionState.items, sectionState.mediaQuery);
  sectionState.columns.forEach(function(column, index) {
    column.hidden = index >= columnCount;
    column.innerHTML = "";
  });
  orderedItems.forEach(function(item) {
    var targetColumnIndex = getCurrentItemColumn(item, sectionState.mediaQuery) - 1;
    var targetColumn = sectionState.columns[targetColumnIndex];
    if (targetColumn) {
      targetColumn.appendChild(item);
    }
  });
  sectionState.section.setAttribute("data-current-columns", String(columnCount));
};
var renderItems3 = (sectionState, options) => {
  var settings = getCurrentSettings3(sectionState.section, sectionState.mediaQuery);
  var visibleCount = typeof options.visibleCount === "number" ? options.visibleCount : sectionState.visibleCount;
  var animate = Boolean(options.animate);
  var startIndex = typeof options.startIndex === "number" ? options.startIndex : 0;
  var orderedItems = getOrderedItems(sectionState.items, sectionState.mediaQuery);
  orderedItems.forEach(function(item, index) {
    var isVisible = index < visibleCount;
    item.hidden = !isVisible;
    if (animate && index >= startIndex && isVisible) {
      item.style.setProperty("--message-reviews-delay", (index - startIndex) * 0.08 + "s");
      item.classList.add("is-revealed");
    }
  });
  sectionState.visibleCount = visibleCount;
  if (visibleCount >= sectionState.items.length) {
    setButtonState3(sectionState.button, "exhausted");
  } else {
    setButtonState3(sectionState.button, "idle");
  }
  sectionState.section.setAttribute("data-visible-count", String(visibleCount));
  sectionState.section.setAttribute("data-current-step", String(settings.step));
};
var registerMessageReviewsLoadMore = () => {
  var sections = Array.from(document.querySelectorAll("[data-message-reviews]"));
  if (!sections.length) {
    return function() {
    };
  }
  var mobileMedia = window.matchMedia("(max-width: 650px)");
  var cleanups = sections.map(function(section) {
    var items = Array.from(section.querySelectorAll("[data-message-review-item]"));
    var button = section.querySelector("[data-message-reviews-more]");
    var footer = section.querySelector("[data-message-reviews-footer]");
    var columns = Array.from(section.querySelectorAll("[data-message-reviews-column]"));
    var loadingTimeoutId = 0;
    if (!items.length || !columns.length) {
      return null;
    }
    var sectionState = {
      section,
      items,
      columns,
      button,
      footer,
      mediaQuery: mobileMedia,
      visibleCount: 0
    };
    layoutItems(sectionState);
    var initialSettings = getCurrentSettings3(section, mobileMedia);
    renderItems3(sectionState, {
      visibleCount: initialSettings.initial,
      animate: false
    });
    var handleRevealAnimationEnd = function(event) {
      var item = event.target.closest("[data-message-review-item]");
      if (!item || !section.contains(item)) {
        return;
      }
      item.classList.remove("is-revealed");
      item.style.removeProperty("--message-reviews-delay");
    };
    var handleClick = function() {
      if (!button || button.classList.contains("is-loading")) {
        return;
      }
      var settings = getCurrentSettings3(section, mobileMedia);
      var nextVisibleCount = Math.min(sectionState.visibleCount + settings.step, items.length);
      var startIndex = sectionState.visibleCount;
      setButtonState3(button, "loading");
      setFooterLoadingState2(footer, true);
      loadingTimeoutId = window.setTimeout(function() {
        renderItems3(sectionState, {
          visibleCount: nextVisibleCount,
          startIndex,
          animate: true
        });
        setFooterLoadingState2(footer, false);
        loadingTimeoutId = 0;
      }, 420);
    };
    var handleMediaChange = function() {
      var settings = getCurrentSettings3(section, mobileMedia);
      var nextVisibleCount = Math.max(sectionState.visibleCount, settings.initial);
      layoutItems(sectionState);
      renderItems3(sectionState, {
        visibleCount: Math.min(nextVisibleCount, items.length),
        animate: false
      });
    };
    if (button) {
      button.addEventListener("click", handleClick);
    }
    section.addEventListener("animationend", handleRevealAnimationEnd);
    if (typeof mobileMedia.addEventListener === "function") {
      mobileMedia.addEventListener("change", handleMediaChange);
    } else {
      mobileMedia.addListener(handleMediaChange);
    }
    return function() {
      if (loadingTimeoutId) {
        window.clearTimeout(loadingTimeoutId);
      }
      setFooterLoadingState2(footer, false);
      if (button) {
        if (button.__hideTimeoutId) {
          window.clearTimeout(button.__hideTimeoutId);
          button.__hideTimeoutId = 0;
        }
        button.removeEventListener("click", handleClick);
      }
      section.removeEventListener("animationend", handleRevealAnimationEnd);
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

// static/js/modules/content/register-phone-mask.js
var PHONE_PREFIX = "+7";
var PHONE_MAX_DIGITS = 10;
var normalizeDigits = (value) => {
  let digits = String(value || "").replace(/\D/g, "");
  if (digits.startsWith("7") || digits.startsWith("8")) {
    digits = digits.slice(1);
  }
  return digits.slice(0, PHONE_MAX_DIGITS);
};
var formatPhoneValue = (value) => {
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
var registerPhoneMask = () => {
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

// static/js/modules/content/register-promotions-archive-load-more.js
var getResponsiveCount4 = (section, isMobile, desktopName, mobileName, fallback) => {
  var desktopValue = Number(section.getAttribute(desktopName));
  var mobileValue = Number(section.getAttribute(mobileName));
  if (isMobile && !Number.isNaN(mobileValue)) {
    return mobileValue;
  }
  if (!Number.isNaN(desktopValue)) {
    return desktopValue;
  }
  return fallback;
};
var getCurrentSettings4 = (section, mediaQuery) => {
  return {
    initial: getResponsiveCount4(section, mediaQuery.matches, "data-initial-desktop", "data-initial-mobile", 3),
    step: getResponsiveCount4(section, mediaQuery.matches, "data-step-desktop", "data-step-mobile", 3)
  };
};
var setButtonState4 = (button, state) => {
  var isLoading = state === "loading";
  var isExhausted = state === "exhausted";
  if (!button) {
    return;
  }
  if (button.__hideTimeoutId) {
    window.clearTimeout(button.__hideTimeoutId);
    button.__hideTimeoutId = 0;
  }
  button.classList.toggle("is-loading", isLoading);
  button.classList.toggle("is-exhausted", isExhausted);
  button.disabled = isLoading;
  button.setAttribute("aria-busy", isLoading ? "true" : "false");
  if (isExhausted) {
    button.__hideTimeoutId = window.setTimeout(function() {
      button.hidden = true;
      button.__hideTimeoutId = 0;
    }, 250);
    return;
  }
  button.hidden = false;
};
var setFooterLoadingState3 = (footer, isLoading) => {
  if (!footer) {
    return;
  }
  footer.classList.toggle("is-loading", isLoading);
};
var renderItems4 = (sectionState, options) => {
  var settings = getCurrentSettings4(sectionState.section, sectionState.mediaQuery);
  var visibleCount = typeof options.visibleCount === "number" ? options.visibleCount : sectionState.visibleCount;
  var animate = Boolean(options.animate);
  var startIndex = typeof options.startIndex === "number" ? options.startIndex : 0;
  sectionState.items.forEach(function(item, index) {
    var isVisible = index < visibleCount;
    item.hidden = !isVisible;
    if (animate && index >= startIndex && isVisible) {
      item.style.setProperty("--promotions-archive-delay", (index - startIndex) * 0.08 + "s");
      item.classList.add("is-revealed");
    }
  });
  sectionState.visibleCount = visibleCount;
  if (visibleCount >= sectionState.items.length) {
    setButtonState4(sectionState.button, "exhausted");
  } else {
    setButtonState4(sectionState.button, "idle");
  }
  sectionState.section.setAttribute("data-visible-count", String(visibleCount));
  sectionState.section.setAttribute("data-current-step", String(settings.step));
};
var registerPromotionsArchiveLoadMore = () => {
  var sections = Array.from(document.querySelectorAll("[data-promotions-archive]"));
  if (!sections.length) {
    return function() {
    };
  }
  var mobileMedia = window.matchMedia("(max-width: 650px)");
  var cleanups = sections.map(function(section) {
    var items = Array.from(section.querySelectorAll("[data-promotions-item]"));
    var button = section.querySelector("[data-promotions-more]");
    var footer = section.querySelector("[data-promotions-footer]");
    var loadingTimeoutId = 0;
    if (!items.length) {
      return null;
    }
    var sectionState = {
      section,
      items,
      button,
      footer,
      mediaQuery: mobileMedia,
      visibleCount: 0
    };
    var initialSettings = getCurrentSettings4(section, mobileMedia);
    renderItems4(sectionState, {
      visibleCount: initialSettings.initial,
      animate: false
    });
    var handleRevealAnimationEnd = function(event) {
      var item = event.target.closest("[data-promotions-item]");
      if (!item || !section.contains(item)) {
        return;
      }
      item.classList.remove("is-revealed");
      item.style.removeProperty("--promotions-archive-delay");
    };
    var handleClick = function() {
      if (!button || button.classList.contains("is-loading")) {
        return;
      }
      var settings = getCurrentSettings4(section, mobileMedia);
      var nextVisibleCount = Math.min(sectionState.visibleCount + settings.step, items.length);
      var startIndex = sectionState.visibleCount;
      setButtonState4(button, "loading");
      setFooterLoadingState3(footer, true);
      loadingTimeoutId = window.setTimeout(function() {
        renderItems4(sectionState, {
          visibleCount: nextVisibleCount,
          startIndex,
          animate: true
        });
        setFooterLoadingState3(footer, false);
        loadingTimeoutId = 0;
      }, 420);
    };
    var handleMediaChange = function() {
      var settings = getCurrentSettings4(section, mobileMedia);
      var nextVisibleCount = Math.max(sectionState.visibleCount, settings.initial);
      renderItems4(sectionState, {
        visibleCount: Math.min(nextVisibleCount, items.length),
        animate: false
      });
    };
    if (button) {
      button.addEventListener("click", handleClick);
    }
    section.addEventListener("animationend", handleRevealAnimationEnd);
    if (typeof mobileMedia.addEventListener === "function") {
      mobileMedia.addEventListener("change", handleMediaChange);
    } else {
      mobileMedia.addListener(handleMediaChange);
    }
    return function() {
      if (loadingTimeoutId) {
        window.clearTimeout(loadingTimeoutId);
      }
      setFooterLoadingState3(footer, false);
      if (button) {
        if (button.__hideTimeoutId) {
          window.clearTimeout(button.__hideTimeoutId);
          button.__hideTimeoutId = 0;
        }
        button.removeEventListener("click", handleClick);
      }
      section.removeEventListener("animationend", handleRevealAnimationEnd);
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

// static/js/modules/content/register-read-more.js
var registerReadMore = () => {
  const items = Array.from(document.querySelectorAll("[data-read-more]"));
  if (!items.length) {
    return () => {
    };
  }
  const cleanups = items.map((item) => {
    const button = item.querySelector("[data-read-more-toggle]");
    if (!button) {
      return null;
    }
    const handleClick = () => {
      item.classList.add("is-expanded");
      button.setAttribute("aria-expanded", "true");
    };
    button.addEventListener("click", handleClick);
    return () => {
      button.removeEventListener("click", handleClick);
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

// static/js/modules/content/register-reviews-archive-load-more.js
var getResponsiveCount5 = (section, isMobile, desktopName, mobileName, fallback) => {
  var desktopValue = Number(section.getAttribute(desktopName));
  var mobileValue = Number(section.getAttribute(mobileName));
  if (isMobile && !Number.isNaN(mobileValue)) {
    return mobileValue;
  }
  if (!Number.isNaN(desktopValue)) {
    return desktopValue;
  }
  return fallback;
};
var getCurrentSettings5 = (section, mediaQuery) => {
  return {
    initial: getResponsiveCount5(section, mediaQuery.matches, "data-initial-desktop", "data-initial-mobile", 8),
    step: getResponsiveCount5(section, mediaQuery.matches, "data-step-desktop", "data-step-mobile", 2)
  };
};
var setButtonState5 = (button, state) => {
  var isLoading = state === "loading";
  var isExhausted = state === "exhausted";
  if (!button) {
    return;
  }
  if (button.__hideTimeoutId) {
    window.clearTimeout(button.__hideTimeoutId);
    button.__hideTimeoutId = 0;
  }
  button.classList.toggle("is-loading", isLoading);
  button.classList.toggle("is-exhausted", isExhausted);
  button.disabled = isLoading;
  button.setAttribute("aria-busy", isLoading ? "true" : "false");
  if (isExhausted) {
    button.__hideTimeoutId = window.setTimeout(function() {
      button.hidden = true;
      button.__hideTimeoutId = 0;
    }, 250);
    return;
  }
  button.hidden = false;
};
var setFooterLoadingState4 = (footer, isLoading) => {
  if (!footer) {
    return;
  }
  footer.classList.toggle("is-loading", isLoading);
};
var renderItems5 = (sectionState, options) => {
  var settings = getCurrentSettings5(sectionState.section, sectionState.mediaQuery);
  var visibleCount = typeof options.visibleCount === "number" ? options.visibleCount : sectionState.visibleCount;
  var animate = Boolean(options.animate);
  var startIndex = typeof options.startIndex === "number" ? options.startIndex : 0;
  sectionState.items.forEach(function(item, index) {
    var isVisible = index < visibleCount;
    item.hidden = !isVisible;
    if (animate && index >= startIndex && isVisible) {
      item.style.setProperty("--reviews-archive-delay", (index - startIndex) * 0.08 + "s");
      item.classList.add("is-revealed");
    }
  });
  sectionState.visibleCount = visibleCount;
  if (visibleCount >= sectionState.items.length) {
    setButtonState5(sectionState.button, "exhausted");
  } else {
    setButtonState5(sectionState.button, "idle");
  }
  sectionState.section.setAttribute("data-visible-count", String(visibleCount));
  sectionState.section.setAttribute("data-current-step", String(settings.step));
};
var registerReviewsArchiveLoadMore = () => {
  var sections = Array.from(document.querySelectorAll("[data-reviews-archive]"));
  if (!sections.length) {
    return function() {
    };
  }
  var mobileMedia = window.matchMedia("(max-width: 650px)");
  var cleanups = sections.map(function(section) {
    var items = Array.from(section.querySelectorAll("[data-reviews-archive-item]"));
    var button = section.querySelector("[data-reviews-archive-more]");
    var footer = section.querySelector("[data-reviews-archive-footer]");
    var loadingTimeoutId = 0;
    if (!items.length) {
      return null;
    }
    var sectionState = {
      section,
      items,
      button,
      footer,
      mediaQuery: mobileMedia,
      visibleCount: 0
    };
    var initialSettings = getCurrentSettings5(section, mobileMedia);
    renderItems5(sectionState, {
      visibleCount: initialSettings.initial,
      animate: false
    });
    var handleRevealAnimationEnd = function(event) {
      var item = event.target.closest("[data-reviews-archive-item]");
      if (!item || !section.contains(item)) {
        return;
      }
      item.classList.remove("is-revealed");
      item.style.removeProperty("--reviews-archive-delay");
    };
    var handleClick = function() {
      if (!button || button.classList.contains("is-loading")) {
        return;
      }
      var settings = getCurrentSettings5(section, mobileMedia);
      var nextVisibleCount = Math.min(sectionState.visibleCount + settings.step, items.length);
      var startIndex = sectionState.visibleCount;
      setButtonState5(button, "loading");
      setFooterLoadingState4(footer, true);
      loadingTimeoutId = window.setTimeout(function() {
        renderItems5(sectionState, {
          visibleCount: nextVisibleCount,
          startIndex,
          animate: true
        });
        setFooterLoadingState4(footer, false);
        loadingTimeoutId = 0;
      }, 380);
    };
    var handleMediaChange = function() {
      var settings = getCurrentSettings5(section, mobileMedia);
      var nextVisibleCount = Math.max(sectionState.visibleCount, settings.initial);
      renderItems5(sectionState, {
        visibleCount: Math.min(nextVisibleCount, items.length),
        animate: false
      });
    };
    if (button) {
      button.addEventListener("click", handleClick);
    }
    section.addEventListener("animationend", handleRevealAnimationEnd);
    if (typeof mobileMedia.addEventListener === "function") {
      mobileMedia.addEventListener("change", handleMediaChange);
    } else {
      mobileMedia.addListener(handleMediaChange);
    }
    return function() {
      if (loadingTimeoutId) {
        window.clearTimeout(loadingTimeoutId);
      }
      setFooterLoadingState4(footer, false);
      if (button) {
        if (button.__hideTimeoutId) {
          window.clearTimeout(button.__hideTimeoutId);
          button.__hideTimeoutId = 0;
        }
        button.removeEventListener("click", handleClick);
      }
      section.removeEventListener("animationend", handleRevealAnimationEnd);
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

// static/js/modules/motion/motion-config.js
var MOTION_CONFIG = {
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
    thresholds: [0, 0.04, 0.18],
    rootMargin: "6% 0px -4% 0px",
    initialViewportTopFactor: -0.08,
    initialViewportBottomFactor: 1.08,
    baseDelay: 60,
    staggerStep: 70,
    maxDelay: 420
  }
};
var prefersReducedMotion = () => window.matchMedia("(prefers-reduced-motion: reduce)").matches;

// static/js/modules/content/register-scroll-reveal.js
var READY_CLASS = "has-scroll-reveal";
var ACTIVE_CLASS = "is-reveal-active";
var SECTION_SELECTOR = "main section";
var FOOTER_SELECTOR = ".footer";
var MEDIA_NAME_RE = /(media|visual|image|photo|picture|poster|figure|gallery|map|brand-card|hero)/i;
var TEXT_NAME_RE = /(head|intro|content|text|copy|summary|aside|info|note|cta|form|top|bottom|body)/i;
var COLLECTION_NAME_RE = /(cards|grid|list|items|columns|requisites|contacts|legal|benefits|reviews|services|results|actions|fields|stats|nav-body|members)/i;
var SKIP_NAME_RE = /(wave|divider|overlay|backdrop|spinner|shadow)/i;
var isElement = (value) => value instanceof HTMLElement;
var getClassName = (element) => {
  if (!isElement(element)) {
    return "";
  }
  return typeof element.className === "string" ? element.className : "";
};
var hasWrapperClass = (element) => /(^|\s)[a-z0-9-]+__wrapper(\s|$)/i.test(getClassName(element));
var hasNamedClass = (element, expression) => expression.test(getClassName(element));
var isEligibleElement = (element) => {
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
var getPrimaryScope = (section) => {
  const directChildren = Array.from(section.children).filter(isElement);
  const directContainer = directChildren.find((child) => child.classList.contains("_container"));
  if (directContainer) {
    const directWrapper2 = Array.from(directContainer.children).find((child) => hasWrapperClass(child));
    return directWrapper2 || directContainer;
  }
  const directWrapper = directChildren.find((child) => hasWrapperClass(child));
  return directWrapper || section;
};
var setReveal = (target, type, delay, targets) => {
  if (!isEligibleElement(target)) {
    return;
  }
  if (!target.dataset.reveal) {
    target.dataset.reveal = type;
  }
  if (!target.style.getPropertyValue("--reveal-delay")) {
    target.style.setProperty("--reveal-delay", `${delay}ms`);
  }
  targets.add(target);
};
var getNestedRevealType = (index, sectionIndex) => {
  if (index % 3 === 0) {
    return "up-soft";
  }
  if ((sectionIndex + index) % 2 === 0) {
    return "left-soft";
  }
  return "right-soft";
};
var getRevealType = (element, sectionIndex, index) => {
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
    return "up";
  }
  return getNestedRevealType(index, sectionIndex);
};
var decorateCollection = (collection, sectionIndex, targets, baseDelay = 60) => {
  const items = Array.from(collection.children).filter(isEligibleElement);
  if (items.length < 2) {
    return;
  }
  if (items.some((item) => item.matches(".swiper") || item.querySelector(":scope > .swiper"))) {
    return;
  }
  items.forEach((item, index) => {
    setReveal(
      item,
      getNestedRevealType(index, sectionIndex),
      Math.min(baseDelay + index * MOTION_CONFIG.reveal.staggerStep, MOTION_CONFIG.reveal.maxDelay),
      targets
    );
  });
};
var decorateScope = (scope, sectionIndex, targets) => {
  const children = Array.from(scope.children).filter(isEligibleElement);
  if (!children.length) {
    setReveal(scope, "up", 0, targets);
    return;
  }
  children.forEach((child, index) => {
    setReveal(
      child,
      getRevealType(child, sectionIndex, index),
      Math.min(index * (MOTION_CONFIG.reveal.staggerStep + 20), 320),
      targets
    );
  });
  children.forEach((child, index) => {
    if (hasNamedClass(child, COLLECTION_NAME_RE) || child.matches("ul, ol, form")) {
      decorateCollection(child, sectionIndex, targets, 120 + index * 30);
      return;
    }
    const nestedCollections = Array.from(child.children).filter((nestedChild) => isEligibleElement(nestedChild) && (hasNamedClass(nestedChild, COLLECTION_NAME_RE) || nestedChild.matches("ul, ol, form")));
    nestedCollections.forEach((nestedCollection, nestedIndex) => {
      decorateCollection(nestedCollection, sectionIndex, targets, 120 + nestedIndex * 30);
    });
  });
};
var markInitiallyVisible = (targets) => {
  const viewportTop = window.innerHeight * MOTION_CONFIG.reveal.initialViewportTopFactor;
  const viewportBottom = window.innerHeight * MOTION_CONFIG.reveal.initialViewportBottomFactor;
  targets.forEach((target) => {
    const rect = target.getBoundingClientRect();
    if (rect.top < viewportBottom && rect.bottom > viewportTop) {
      target.classList.add(ACTIVE_CLASS);
    }
  });
};
var registerScrollReveal = () => {
  if (!("IntersectionObserver" in window)) {
    return null;
  }
  if (prefersReducedMotion()) {
    return null;
  }
  const targets = /* @__PURE__ */ new Set();
  const sections = Array.from(document.querySelectorAll(SECTION_SELECTOR));
  sections.forEach((section, sectionIndex) => {
    decorateScope(getPrimaryScope(section), sectionIndex, targets);
  });
  const footer = document.querySelector(FOOTER_SELECTOR);
  if (footer) {
    const footerScope = footer.querySelector(".footer__wrapper") || getPrimaryScope(footer);
    decorateScope(footerScope, sections.length + 1, targets);
  }
  const revealTargets = Array.from(targets);
  if (!revealTargets.length) {
    return null;
  }
  markInitiallyVisible(revealTargets);
  document.body.classList.add(READY_CLASS);
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting && entry.intersectionRatio >= MOTION_CONFIG.reveal.thresholds[1]) {
        entry.target.classList.add(ACTIVE_CLASS);
        return;
      }
      entry.target.classList.remove(ACTIVE_CLASS);
    });
  }, {
    threshold: MOTION_CONFIG.reveal.thresholds,
    rootMargin: MOTION_CONFIG.reveal.rootMargin
  });
  revealTargets.forEach((target) => observer.observe(target));
  return () => {
    observer.disconnect();
    document.body.classList.remove(READY_CLASS);
    revealTargets.forEach((target) => {
      target.classList.remove(ACTIVE_CLASS);
    });
  };
};

// static/js/modules/content/register-service-includes-tabs.js
var getSectionParts = (section) => {
  var tabs = Array.from(section.querySelectorAll("[data-service-includes-tab]"));
  var panels = Array.from(section.querySelectorAll("[data-service-includes-panel]"));
  if (!tabs.length || !panels.length) {
    return null;
  }
  return { tabs, panels };
};
var getPanelHotspots = (panel) => {
  return Array.from(panel.querySelectorAll("[data-service-includes-hotspot]"));
};
var getPanelTooltips = (panel) => {
  return Array.from(panel.querySelectorAll("[data-service-includes-tooltip]"));
};
var activateHotspot = (panel, hotspotId) => {
  var hotspots = getPanelHotspots(panel);
  var tooltips = getPanelTooltips(panel);
  hotspots.forEach(function(hotspot) {
    var isActive = hotspot.getAttribute("data-hotspot-id") === hotspotId;
    hotspot.classList.toggle("is-active", isActive);
    hotspot.setAttribute("aria-expanded", isActive ? "true" : "false");
  });
  tooltips.forEach(function(tooltip) {
    var isActive = tooltip.getAttribute("data-hotspot-id") === hotspotId;
    tooltip.classList.toggle("is-active", isActive);
    tooltip.setAttribute("aria-hidden", isActive ? "false" : "true");
  });
};
var ensurePanelHotspot = (panel) => {
  var activeHotspot = panel.querySelector("[data-service-includes-hotspot].is-active");
  var firstHotspot = panel.querySelector("[data-service-includes-hotspot]");
  if (activeHotspot) {
    activateHotspot(panel, activeHotspot.getAttribute("data-hotspot-id"));
    return;
  }
  if (firstHotspot) {
    activateHotspot(panel, firstHotspot.getAttribute("data-hotspot-id"));
  }
};
var setPanelAccessibility = (panel, isActive) => {
  var hotspots = getPanelHotspots(panel);
  panel.classList.toggle("is-active", isActive);
  panel.setAttribute("aria-hidden", isActive ? "false" : "true");
  hotspots.forEach(function(hotspot) {
    hotspot.tabIndex = isActive ? 0 : -1;
  });
  if (isActive) {
    ensurePanelHotspot(panel);
  }
};
var activateTab = (section, tabId, options) => {
  var settings = options || {};
  var parts = getSectionParts(section);
  if (!parts) {
    return;
  }
  parts.tabs.forEach(function(tab) {
    var isActive = tab.getAttribute("data-tab-id") === tabId;
    tab.classList.toggle("is-active", isActive);
    tab.setAttribute("aria-selected", isActive ? "true" : "false");
    tab.tabIndex = isActive ? 0 : -1;
    if (isActive && settings.focus) {
      tab.focus();
    }
  });
  parts.panels.forEach(function(panel) {
    setPanelAccessibility(panel, panel.getAttribute("data-tab-id") === tabId);
  });
};
var getActiveTabIndex = (tabs) => {
  return tabs.findIndex(function(tab) {
    return tab.getAttribute("aria-selected") === "true";
  });
};
var handleTabKeyboard = (section, event) => {
  var key = event.key;
  var parts = getSectionParts(section);
  if (!parts) {
    return;
  }
  if (["ArrowLeft", "ArrowUp", "ArrowRight", "ArrowDown", "Home", "End"].indexOf(key) === -1) {
    return;
  }
  event.preventDefault();
  var tabs = parts.tabs;
  var currentIndex = getActiveTabIndex(tabs);
  var nextIndex = currentIndex;
  if (key === "Home") {
    nextIndex = 0;
  }
  if (key === "End") {
    nextIndex = tabs.length - 1;
  }
  if (key === "ArrowLeft" || key === "ArrowUp") {
    nextIndex = currentIndex <= 0 ? tabs.length - 1 : currentIndex - 1;
  }
  if (key === "ArrowRight" || key === "ArrowDown") {
    nextIndex = currentIndex >= tabs.length - 1 ? 0 : currentIndex + 1;
  }
  activateTab(section, tabs[nextIndex].getAttribute("data-tab-id"), { focus: true });
};
var registerServiceIncludesTabs = () => {
  var sections = Array.from(document.querySelectorAll("[data-service-includes]"));
  if (!sections.length) {
    return function() {
    };
  }
  var canHover = window.matchMedia("(hover: hover) and (pointer: fine)");
  var cleanups = sections.map(function(section) {
    var parts = getSectionParts(section);
    if (!parts) {
      return null;
    }
    var activeTab = parts.tabs.find(function(tab) {
      return tab.getAttribute("aria-selected") === "true";
    }) || parts.tabs[0];
    activateTab(section, activeTab.getAttribute("data-tab-id"));
    var handleClick = function(event) {
      var tab = event.target.closest("[data-service-includes-tab]");
      var hotspot = event.target.closest("[data-service-includes-hotspot]");
      if (tab && section.contains(tab)) {
        activateTab(section, tab.getAttribute("data-tab-id"));
      }
      if (hotspot && section.contains(hotspot)) {
        var panel = hotspot.closest("[data-service-includes-panel]");
        if (!panel) {
          return;
        }
        activateHotspot(panel, hotspot.getAttribute("data-hotspot-id"));
      }
    };
    var handleKeydown = function(event) {
      if (event.target.closest("[data-service-includes-tab]")) {
        handleTabKeyboard(section, event);
      }
    };
    var handlePointerEnter = function(event) {
      var hotspot = event.target.closest("[data-service-includes-hotspot]");
      if (!hotspot || !section.contains(hotspot) || !canHover.matches) {
        return;
      }
      var panel = hotspot.closest("[data-service-includes-panel]");
      if (!panel) {
        return;
      }
      activateHotspot(panel, hotspot.getAttribute("data-hotspot-id"));
    };
    var handleFocusIn = function(event) {
      var hotspot = event.target.closest("[data-service-includes-hotspot]");
      if (!hotspot || !section.contains(hotspot)) {
        return;
      }
      var panel = hotspot.closest("[data-service-includes-panel]");
      if (!panel) {
        return;
      }
      activateHotspot(panel, hotspot.getAttribute("data-hotspot-id"));
    };
    section.addEventListener("click", handleClick);
    section.addEventListener("keydown", handleKeydown);
    section.addEventListener("pointerenter", handlePointerEnter, true);
    section.addEventListener("focusin", handleFocusIn);
    return function() {
      section.removeEventListener("click", handleClick);
      section.removeEventListener("keydown", handleKeydown);
      section.removeEventListener("pointerenter", handlePointerEnter, true);
      section.removeEventListener("focusin", handleFocusIn);
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

// static/js/modules/navigation/register-header.js
var HEADER_SCROLL_TOP_THRESHOLD = MOTION_CONFIG.header.scrollTopThreshold;
var HEADER_SCROLL_HIDE_THRESHOLD = MOTION_CONFIG.header.scrollHideThreshold;
var HEADER_SCROLL_DELTA = MOTION_CONFIG.header.scrollDelta;
var HEADER_FOCUSABLE_SELECTORS = [
  "a[href]",
  "button:not([disabled])",
  "textarea:not([disabled])",
  "input:not([disabled]):not([type='hidden'])",
  "select:not([disabled])",
  "[tabindex]:not([tabindex='-1'])"
].join(", ");
var registerHeader = () => {
  const header = document.querySelector("[data-header]");
  if (!header) {
    return null;
  }
  const nav = header.querySelector("[data-header-nav]");
  const menuToggle = header.querySelector("[data-header-toggle]");
  const closeButton = header.querySelector("[data-header-close]");
  const submenuItems = Array.from(header.querySelectorAll("[data-header-item]"));
  const submenuToggles = Array.from(header.querySelectorAll("[data-header-submenu-toggle]"));
  const navLinks = Array.from(header.querySelectorAll("[data-header-link]"));
  const parentLinks = Array.from(header.querySelectorAll("[data-header-parent-link]"));
  const desktopMediaQuery = window.matchMedia("(min-width: 1100px)");
  let lastScrollY = window.scrollY;
  let scrollFrameId = 0;
  const isDesktop = () => desktopMediaQuery.matches;
  const hasOpenPopup = () => Boolean(document.querySelector("[data-popup].is-active"));
  const syncBodyLock = () => {
    const shouldLockBody = header.classList.contains("is-menu-open") && !isDesktop() || hasOpenPopup();
    document.body.classList.toggle("_lock", shouldLockBody);
  };
  const getFocusableNavElements = () => {
    if (!nav) {
      return [];
    }
    return Array.from(nav.querySelectorAll(HEADER_FOCUSABLE_SELECTORS)).filter((element) => {
      return !element.hasAttribute("hidden") && element.offsetParent !== null;
    });
  };
  const setSubmenuState = (item, shouldOpen) => {
    if (!item) {
      return;
    }
    const toggle = item.querySelector("[data-header-submenu-toggle]");
    const submenu = item.querySelector("[data-header-submenu]");
    item.classList.toggle("is-open", shouldOpen);
    if (toggle) {
      toggle.setAttribute("aria-expanded", shouldOpen ? "true" : "false");
    }
    if (submenu) {
      submenu.setAttribute("aria-hidden", shouldOpen ? "false" : "true");
    }
  };
  const closeAllSubmenus = () => {
    submenuItems.forEach((item) => {
      setSubmenuState(item, false);
    });
  };
  const applyScrollState = () => {
    const currentScrollY = Math.max(window.scrollY, 0);
    const isMenuOpen = header.classList.contains("is-menu-open");
    const isNearTop = currentScrollY <= HEADER_SCROLL_TOP_THRESHOLD;
    const scrollDelta = Math.abs(currentScrollY - lastScrollY);
    const scrollingDown = currentScrollY > lastScrollY;
    header.classList.toggle("is-scrolled", currentScrollY > HEADER_SCROLL_TOP_THRESHOLD);
    if (isMenuOpen) {
      header.classList.remove("is-hidden");
      lastScrollY = currentScrollY;
      return;
    }
    if (isNearTop) {
      header.classList.remove("is-hidden");
      lastScrollY = currentScrollY;
      return;
    }
    if (scrollDelta >= HEADER_SCROLL_DELTA) {
      if (scrollingDown && currentScrollY > HEADER_SCROLL_HIDE_THRESHOLD) {
        header.classList.add("is-hidden");
      } else {
        header.classList.remove("is-hidden");
      }
      lastScrollY = currentScrollY;
    }
  };
  const handleScroll = () => {
    if (scrollFrameId) {
      return;
    }
    scrollFrameId = window.requestAnimationFrame(() => {
      applyScrollState();
      scrollFrameId = 0;
    });
  };
  const setMenuState = (shouldOpen) => {
    header.classList.toggle("is-menu-open", shouldOpen);
    if (nav) {
      nav.classList.toggle("is-open", shouldOpen);
      nav.setAttribute("aria-hidden", !isDesktop() && !shouldOpen ? "true" : "false");
    }
    if (menuToggle) {
      menuToggle.setAttribute("aria-expanded", shouldOpen ? "true" : "false");
      menuToggle.setAttribute("aria-label", shouldOpen ? "\u0417\u0430\u043A\u0440\u044B\u0442\u044C \u043C\u0435\u043D\u044E" : "\u041E\u0442\u043A\u0440\u044B\u0442\u044C \u043C\u0435\u043D\u044E");
    }
    if (!shouldOpen) {
      closeAllSubmenus();
    }
    header.classList.remove("is-hidden");
    syncBodyLock();
    applyScrollState();
    if (!isDesktop() && shouldOpen) {
      window.requestAnimationFrame(() => {
        getFocusableNavElements()[0]?.focus();
      });
    }
  };
  const handleMenuToggleClick = () => {
    setMenuState(!header.classList.contains("is-menu-open"));
  };
  const handleCloseClick = () => {
    setMenuState(false);
  };
  const handleSubmenuToggleClick = (event) => {
    event.preventDefault();
    const item = event.currentTarget.closest("[data-header-item]");
    const shouldOpen = !item?.classList.contains("is-open");
    if (!item) {
      return;
    }
    if (!isDesktop()) {
      closeAllSubmenus();
    }
    setSubmenuState(item, shouldOpen);
  };
  const handleParentLinkClick = (event) => {
    if (isDesktop()) {
      return;
    }
    const item = event.currentTarget.closest("[data-header-item]");
    if (!item) {
      return;
    }
    if (!item.classList.contains("is-open")) {
      event.preventDefault();
      closeAllSubmenus();
      setSubmenuState(item, true);
    }
  };
  const handleNavLinkClick = (event) => {
    if (event.defaultPrevented) {
      return;
    }
    if (!isDesktop()) {
      setMenuState(false);
    }
  };
  const handleDocumentClick = (event) => {
    if (!isDesktop()) {
      return;
    }
    if (!header.contains(event.target)) {
      closeAllSubmenus();
    }
  };
  const handleKeydown = (event) => {
    if (event.key !== "Escape") {
      if (!isDesktop() && header.classList.contains("is-menu-open") && event.key === "Tab") {
        const focusableElements = getFocusableNavElements();
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
      }
      return;
    }
    if (header.classList.contains("is-menu-open")) {
      setMenuState(false);
      menuToggle?.focus();
      return;
    }
    if (submenuItems.some((item) => item.classList.contains("is-open"))) {
      closeAllSubmenus();
    }
  };
  const handleMediaChange = () => {
    if (isDesktop()) {
      header.classList.remove("is-menu-open");
      nav?.classList.remove("is-open");
      menuToggle?.setAttribute("aria-expanded", "false");
      nav?.setAttribute("aria-hidden", "false");
      menuToggle?.setAttribute("aria-label", "\u041E\u0442\u043A\u0440\u044B\u0442\u044C \u043C\u0435\u043D\u044E");
    } else {
      nav?.setAttribute("aria-hidden", header.classList.contains("is-menu-open") ? "false" : "true");
    }
    closeAllSubmenus();
    syncBodyLock();
    applyScrollState();
  };
  menuToggle?.addEventListener("click", handleMenuToggleClick);
  closeButton?.addEventListener("click", handleCloseClick);
  submenuToggles.forEach((toggle) => {
    toggle.addEventListener("click", handleSubmenuToggleClick);
  });
  parentLinks.forEach((link) => {
    link.addEventListener("click", handleParentLinkClick);
  });
  navLinks.forEach((link) => {
    link.addEventListener("click", handleNavLinkClick);
  });
  document.addEventListener("click", handleDocumentClick);
  document.addEventListener("keydown", handleKeydown);
  window.addEventListener("scroll", handleScroll, { passive: true });
  desktopMediaQuery.addEventListener("change", handleMediaChange);
  handleMediaChange();
  return () => {
    window.cancelAnimationFrame(scrollFrameId);
    menuToggle?.removeEventListener("click", handleMenuToggleClick);
    closeButton?.removeEventListener("click", handleCloseClick);
    submenuToggles.forEach((toggle) => {
      toggle.removeEventListener("click", handleSubmenuToggleClick);
    });
    parentLinks.forEach((link) => {
      link.removeEventListener("click", handleParentLinkClick);
    });
    navLinks.forEach((link) => {
      link.removeEventListener("click", handleNavLinkClick);
    });
    document.removeEventListener("click", handleDocumentClick);
    document.removeEventListener("keydown", handleKeydown);
    window.removeEventListener("scroll", handleScroll);
    desktopMediaQuery.removeEventListener("change", handleMediaChange);
    document.body.classList.remove("_lock");
  };
};

// static/js/modules/popup/register-popups.js
var POPUP_ACTIVE_CLASS = "is-active";
var POPUP_BODY_ACTIVE_CLASS = "is-popup-active";
var POPUP_CLOSE_DURATION = prefersReducedMotion() ? 0 : MOTION_CONFIG.popup.closeDuration;
var POPUP_SWITCH_DELAY = prefersReducedMotion() ? 0 : MOTION_CONFIG.popup.switchDelay;
var FORM_STATE_MESSAGES = {
  loading: "\u041E\u0442\u043F\u0440\u0430\u0432\u043B\u044F\u0435\u043C \u0444\u043E\u0440\u043C\u0443...",
  success: "\u0424\u043E\u0440\u043C\u0430 \u0443\u0441\u043F\u0435\u0448\u043D\u043E \u043E\u0442\u043F\u0440\u0430\u0432\u043B\u0435\u043D\u0430.",
  error: "\u041F\u0440\u043E\u0432\u0435\u0440\u044C\u0442\u0435 \u043E\u0431\u044F\u0437\u0430\u0442\u0435\u043B\u044C\u043D\u044B\u0435 \u043F\u043E\u043B\u044F \u0438 \u043F\u043E\u043F\u0440\u043E\u0431\u0443\u0439\u0442\u0435 \u0441\u043D\u043E\u0432\u0430."
};
var FOCUSABLE_SELECTORS = [
  "a[href]",
  "button:not([disabled])",
  "textarea:not([disabled])",
  "input:not([disabled]):not([type='hidden'])",
  "select:not([disabled])",
  "[tabindex]:not([tabindex='-1'])"
].join(", ");
var registerPopups = () => {
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
  const renderUploadPreview = (uploadBlock, files) => {
    const preview = uploadBlock.querySelector("[data-popup-upload-preview]");
    const emptyState = uploadBlock.querySelector("[data-popup-upload-empty]");
    if (!preview || !emptyState) {
      return;
    }
    resetUploadBlock(uploadBlock);
    if (!files.length) {
      return;
    }
    const previewUrls = [];
    files.slice(0, 2).forEach((file) => {
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
        previewItem.classList.add("popup-upload__thumb--more");
        previewItem.textContent = "MP4";
      }
      preview.appendChild(previewItem);
    });
    if (files.length > 2) {
      const moreItem = document.createElement("span");
      moreItem.className = "popup-upload__thumb popup-upload__thumb--more";
      moreItem.textContent = `+${files.length - 2}`;
      preview.appendChild(moreItem);
    } else {
      const addItem = document.createElement("span");
      addItem.className = "popup-upload__thumb popup-upload__thumb--more";
      addItem.textContent = "+";
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
    const openButton = event.target.closest("[data-popup-open]");
    const closeButton = event.target.closest("[data-popup-close]");
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
    renderUploadPreview(uploadBlock, Array.from(input.files || []));
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

// static/js/modules/swipers/register-breakpoint-swiper.js
var cleanupSwiperStyles = (container) => {
  container.removeAttribute("style");
  const wrapper = container.querySelector(".swiper-wrapper");
  if (wrapper) {
    wrapper.removeAttribute("style");
  }
  container.querySelectorAll(".swiper-slide").forEach((slide) => {
    slide.removeAttribute("style");
  });
};
var registerBreakpointSwiper = ({
  elements,
  query = "(max-width: 650px)",
  options = {}
}) => {
  if (!elements.length || typeof window.Swiper !== "function") {
    return () => {
    };
  }
  const mediaQuery = window.matchMedia(query);
  const registry = /* @__PURE__ */ new Map();
  const enable = (element) => {
    if (registry.has(element)) {
      return;
    }
    const instance = new window.Swiper(element, options);
    registry.set(element, instance);
  };
  const disable = (element) => {
    const instance = registry.get(element);
    if (!instance) {
      return;
    }
    instance.destroy(true, true);
    registry.delete(element);
    cleanupSwiperStyles(element);
  };
  const sync = () => {
    elements.forEach((element) => {
      if (mediaQuery.matches) {
        enable(element);
        return;
      }
      disable(element);
    });
  };
  const handleChange = () => {
    sync();
  };
  if (typeof mediaQuery.addEventListener === "function") {
    mediaQuery.addEventListener("change", handleChange);
  } else if (typeof mediaQuery.addListener === "function") {
    mediaQuery.addListener(handleChange);
  }
  sync();
  return () => {
    elements.forEach((element) => disable(element));
    if (typeof mediaQuery.removeEventListener === "function") {
      mediaQuery.removeEventListener("change", handleChange);
    } else if (typeof mediaQuery.removeListener === "function") {
      mediaQuery.removeListener(handleChange);
    }
  };
};

// static/js/modules/swipers/register-company-preview-benefits-swiper.js
var registerCompanyPreviewBenefitsSwiper = () => {
  const elements = Array.from(document.querySelectorAll("[data-company-preview-benefits-swiper]"));
  return registerBreakpointSwiper({
    elements,
    options: {
      slidesPerView: "auto",
      spaceBetween: 16,
      speed: 450,
      watchOverflow: true,
      resistanceRatio: 0.75
    }
  });
};

// static/js/modules/fancybox/register-fancybox.js
var registerFancybox = () => {
  if (!window.Fancybox || typeof window.Fancybox.bind !== "function") {
    return () => {
    };
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

// static/js/modules/faq/register-faq-accordion.js
var getFaqElements = (item) => {
  const button = item.querySelector("[data-faq-toggle]");
  const answer = item.querySelector("[data-faq-answer]");
  if (!button || !answer) {
    return null;
  }
  return { button, answer };
};
var removeTransitionHandler = (answer) => {
  if (typeof answer.__faqTransitionHandler !== "function") {
    return;
  }
  answer.removeEventListener("transitionend", answer.__faqTransitionHandler);
  delete answer.__faqTransitionHandler;
};
var openFaqItem = (item, options = {}) => {
  const { immediate = false } = options;
  const elements = getFaqElements(item);
  if (!elements) {
    return;
  }
  const { button, answer } = elements;
  removeTransitionHandler(answer);
  if (immediate) {
    answer.hidden = false;
    answer.setAttribute("aria-hidden", "false");
    answer.style.height = "auto";
    answer.style.overflow = "";
    button.setAttribute("aria-expanded", "true");
    item.classList.add("is-open");
    return;
  }
  answer.hidden = false;
  answer.setAttribute("aria-hidden", "false");
  answer.style.overflow = "hidden";
  answer.style.height = "0px";
  button.setAttribute("aria-expanded", "true");
  item.classList.add("is-open");
  const targetHeight = `${answer.scrollHeight}px`;
  void answer.offsetHeight;
  const handleTransitionEnd = (event) => {
    if (event.target !== answer || event.propertyName !== "height") {
      return;
    }
    answer.style.height = "auto";
    answer.style.overflow = "";
    removeTransitionHandler(answer);
  };
  answer.__faqTransitionHandler = handleTransitionEnd;
  answer.addEventListener("transitionend", handleTransitionEnd);
  window.requestAnimationFrame(() => {
    answer.style.height = targetHeight;
  });
};
var closeFaqItem = (item, options = {}) => {
  const { immediate = false } = options;
  const elements = getFaqElements(item);
  if (!elements) {
    return;
  }
  const { button, answer } = elements;
  removeTransitionHandler(answer);
  if (!immediate && !item.classList.contains("is-open")) {
    button.setAttribute("aria-expanded", "false");
    answer.setAttribute("aria-hidden", "true");
    answer.hidden = true;
    answer.style.height = "";
    answer.style.overflow = "";
    return;
  }
  button.setAttribute("aria-expanded", "false");
  item.classList.remove("is-open");
  answer.setAttribute("aria-hidden", "true");
  if (immediate) {
    answer.hidden = true;
    answer.style.height = "";
    answer.style.overflow = "";
    return;
  }
  answer.hidden = false;
  answer.style.overflow = "hidden";
  answer.style.height = `${answer.scrollHeight}px`;
  void answer.offsetHeight;
  const handleTransitionEnd = (event) => {
    if (event.target !== answer || event.propertyName !== "height") {
      return;
    }
    answer.hidden = true;
    answer.style.height = "";
    answer.style.overflow = "";
    removeTransitionHandler(answer);
  };
  answer.__faqTransitionHandler = handleTransitionEnd;
  answer.addEventListener("transitionend", handleTransitionEnd);
  window.requestAnimationFrame(() => {
    answer.style.height = "0px";
  });
};
var registerFaqAccordion = () => {
  const groups = Array.from(document.querySelectorAll("[data-faq-group]"));
  if (!groups.length) {
    return () => {
    };
  }
  const cleanups = groups.map((group) => {
    const items = Array.from(group.querySelectorAll("[data-faq-item]"));
    items.forEach((item) => {
      closeFaqItem(item, { immediate: true });
    });
    const initiallyOpenItem = items.find((item) => item.getAttribute("data-faq-initially-open") === "true");
    if (initiallyOpenItem) {
      openFaqItem(initiallyOpenItem, { immediate: true });
    }
    const handleClick = (event) => {
      const button = event.target.closest("[data-faq-toggle]");
      if (!button) {
        return;
      }
      const currentItem = button.closest("[data-faq-item]");
      if (!currentItem) {
        return;
      }
      const isOpen = currentItem.classList.contains("is-open");
      items.forEach((item) => {
        if (item !== currentItem && item.classList.contains("is-open")) {
          closeFaqItem(item);
        }
      });
      if (isOpen) {
        closeFaqItem(currentItem);
        return;
      }
      openFaqItem(currentItem);
    };
    group.addEventListener("click", handleClick);
    return () => {
      group.removeEventListener("click", handleClick);
      items.forEach((item) => {
        const elements = getFaqElements(item);
        if (!elements) {
          return;
        }
        removeTransitionHandler(elements.answer);
      });
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

// static/js/modules/gallery/register-gallery-preview-marquee.js
var HOVER_SPEED_FACTOR = 0.18;
var SPEED_LERP = 5;
var createGalleryMarqueeRow = (row) => {
  const track = row.querySelector("[data-gallery-marquee-track]");
  if (!track) {
    return () => {
    };
  }
  const prefersReducedMotion2 = window.matchMedia("(prefers-reduced-motion: reduce)");
  if (prefersReducedMotion2.matches) {
    row.dataset.marqueeDisabled = "true";
    return () => {
    };
  }
  row.dataset.marqueeReady = "true";
  const baseDirection = Number(row.dataset.direction || -1);
  const baseSpeed = Number(row.dataset.speed || 36);
  const slowSpeed = baseSpeed * HOVER_SPEED_FACTOR;
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
    const delta = Math.min((time - lastTime) / 1e3, 0.05);
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
var registerGalleryPreviewMarquee = () => {
  const rows = Array.from(document.querySelectorAll("[data-gallery-marquee-row]"));
  if (!rows.length) {
    return () => {
    };
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

// static/js/modules/swipers/register-hero-area-swiper.js
var registerHeroAreaSwiper = () => {
  const elements = Array.from(document.querySelectorAll("[data-hero-area-swiper]"));
  return registerBreakpointSwiper({
    elements,
    options: {
      slidesPerView: "auto",
      spaceBetween: 6,
      speed: 400,
      watchOverflow: true,
      resistanceRatio: 0.7
    }
  });
};

// static/js/modules/swipers/register-price-advantages-swiper.js
var registerPriceAdvantagesSwiper = () => {
  const elements = Array.from(document.querySelectorAll("[data-price-advantages-swiper]"));
  return registerBreakpointSwiper({
    elements,
    options: {
      slidesPerView: "auto",
      spaceBetween: 16,
      speed: 450,
      watchOverflow: true,
      resistanceRatio: 0.75
    }
  });
};

// static/js/modules/swipers/register-price-factors-swiper.js
var registerPriceFactorsSwiper = () => {
  const elements = Array.from(document.querySelectorAll("[data-price-factors-swiper]"));
  return registerBreakpointSwiper({
    elements,
    options: {
      slidesPerView: "auto",
      spaceBetween: 16,
      speed: 500,
      watchOverflow: true,
      resistanceRatio: 0.75
    }
  });
};

// static/js/modules/swipers/register-reviews-preview-swiper.js
var registerReviewsPreviewSwiper = () => {
  if (typeof window.Swiper !== "function") {
    return () => {
    };
  }
  const elements = Array.from(document.querySelectorAll("[data-reviews-preview-swiper]"));
  const instances = elements.map((element) => {
    const nextEl = element.parentElement?.querySelector("[data-reviews-preview-next]") || null;
    const prevEl = element.parentElement?.querySelector("[data-reviews-preview-prev]") || null;
    return new window.Swiper(element, {
      speed: 550,
      spaceBetween: 16,
      slidesPerView: "auto",
      centeredSlides: true,
      watchOverflow: true,
      resistanceRatio: 0.75,
      navigation: nextEl && prevEl ? {
        nextEl,
        prevEl
      } : void 0,
      breakpoints: {
        651: {
          slidesPerView: 2,
          centeredSlides: false,
          spaceBetween: 20
        }
      }
    });
  });
  return () => {
    instances.forEach((instance) => {
      instance.destroy(true, true);
    });
  };
};

// static/js/modules/swipers/register-services-benefits-swiper.js
var registerServicesBenefitsSwiper = () => {
  const elements = Array.from(document.querySelectorAll("[data-services-benefits-swiper]"));
  return registerBreakpointSwiper({
    elements,
    options: {
      slidesPerView: "auto",
      spaceBetween: 16,
      speed: 450,
      watchOverflow: true,
      resistanceRatio: 0.75
    }
  });
};

// static/js/modules/swipers/register-services-intro-swiper.js
var registerServicesIntroSwiper = () => {
  if (typeof window.Swiper !== "function") {
    return () => {
    };
  }
  const elements = Array.from(document.querySelectorAll("[data-services-intro-swiper]"));
  const instances = elements.map((element) => {
    const scope = element.closest(".services-intro__wrapper") || element.parentElement;
    const nextEl = scope?.querySelector("[data-services-intro-next]") || null;
    const prevEl = scope?.querySelector("[data-services-intro-prev]") || null;
    return new window.Swiper(element, {
      speed: 500,
      spaceBetween: 16,
      slidesPerView: "auto",
      watchOverflow: true,
      resistanceRatio: 0.75,
      navigation: nextEl && prevEl ? {
        nextEl,
        prevEl
      } : void 0,
      breakpoints: {
        651: {
          spaceBetween: 20
        }
      }
    });
  });
  return () => {
    instances.forEach((instance) => {
      instance.destroy(true, true);
    });
  };
};

// static/js/modules/swipers/register-service-benefits-swiper.js
var registerServiceBenefitsSwiper = () => {
  const elements = Array.from(document.querySelectorAll("[data-service-benefits-swiper]"));
  return registerBreakpointSwiper({
    elements,
    options: {
      slidesPerView: "auto",
      spaceBetween: 16,
      speed: 450,
      watchOverflow: true,
      resistanceRatio: 0.75
    }
  });
};

// static/js/modules/swipers/register-team-preview-swiper.js
var registerTeamPreviewSwiper = () => {
  if (typeof window.Swiper !== "function") {
    return () => {
    };
  }
  const elements = Array.from(document.querySelectorAll("[data-team-preview-swiper]"));
  const instances = elements.map((element) => {
    const scope = element.closest(".team-preview__wrapper") || element.parentElement;
    const nextEl = scope?.querySelector("[data-team-preview-next]") || null;
    const prevEl = scope?.querySelector("[data-team-preview-prev]") || null;
    return new window.Swiper(element, {
      speed: 500,
      spaceBetween: 16,
      slidesPerView: "auto",
      watchOverflow: true,
      resistanceRatio: 0.75,
      navigation: nextEl && prevEl ? {
        nextEl,
        prevEl
      } : void 0,
      breakpoints: {
        651: {
          slidesPerView: 2,
          spaceBetween: 20
        },
        992: {
          slidesPerView: 4,
          spaceBetween: 20
        }
      }
    });
  });
  return () => {
    instances.forEach((instance) => {
      instance.destroy(true, true);
    });
  };
};

// static/js/modules/swipers/register-video-reviews-swiper.js
var registerVideoReviewsSwiper = () => {
  if (typeof window.Swiper !== "function") {
    return () => {
    };
  }
  const elements = Array.from(document.querySelectorAll("[data-video-reviews-swiper]"));
  const instances = elements.map((element) => {
    const scope = element.closest(".video-reviews__wrapper") || element.parentElement;
    const nextEl = scope?.querySelector("[data-video-reviews-next]") || null;
    const prevEl = scope?.querySelector("[data-video-reviews-prev]") || null;
    return new window.Swiper(element, {
      speed: 500,
      spaceBetween: 20,
      slidesPerView: "auto",
      watchOverflow: true,
      resistanceRatio: 0.75,
      navigation: nextEl && prevEl ? {
        nextEl,
        prevEl
      } : void 0
    });
  });
  return () => {
    instances.forEach((instance) => {
      instance.destroy(true, true);
    });
  };
};

// static/js/modules/swipers/register-work-approach-swiper.js
var registerWorkApproachSwiper = () => {
  const elements = Array.from(document.querySelectorAll("[data-work-approach-swiper]"));
  return registerBreakpointSwiper({
    elements,
    options: {
      slidesPerView: "auto",
      spaceBetween: 16,
      speed: 450,
      watchOverflow: true,
      resistanceRatio: 0.75
    }
  });
};

// static/js/modules/swipers/register-work-steps-swiper.js
var registerWorkStepsSwiper = () => {
  const elements = Array.from(document.querySelectorAll("[data-work-steps-swiper]"));
  return registerBreakpointSwiper({
    elements,
    options: {
      slidesPerView: "auto",
      spaceBetween: 16,
      speed: 500,
      watchOverflow: true,
      resistanceRatio: 0.75
    }
  });
};

// static/js/modules/swipers/register-why-us-swiper.js
var registerWhyUsSwiper = () => {
  const elements = Array.from(document.querySelectorAll("[data-why-us-swiper]"));
  return registerBreakpointSwiper({
    elements,
    options: {
      slidesPerView: "auto",
      spaceBetween: 16,
      speed: 450,
      watchOverflow: true,
      resistanceRatio: 0.75
    }
  });
};

// static/js/script.js
var initApp = () => {
  const destroyers = [
    registerBeforeAfterComparisons(),
    registerBeforeAfterResultsLoadMore(),
    registerBlogArchiveLoadMore(),
    registerMessageReviewsLoadMore(),
    registerPhoneMask(),
    registerPromotionsArchiveLoadMore(),
    registerReadMore(),
    registerReviewsArchiveLoadMore(),
    registerScrollReveal(),
    registerServiceIncludesTabs(),
    registerHeader(),
    registerPopups(),
    registerServiceBenefitsSwiper(),
    registerCompanyPreviewBenefitsSwiper(),
    registerFancybox(),
    registerFaqAccordion(),
    registerGalleryPreviewMarquee(),
    registerHeroAreaSwiper(),
    registerPriceAdvantagesSwiper(),
    registerPriceFactorsSwiper(),
    registerReviewsPreviewSwiper(),
    registerServicesBenefitsSwiper(),
    registerServicesIntroSwiper(),
    registerTeamPreviewSwiper(),
    registerVideoReviewsSwiper(),
    registerWorkApproachSwiper(),
    registerWorkStepsSwiper(),
    registerWhyUsSwiper()
  ].filter(Boolean);
  window.addEventListener("pagehide", () => {
    destroyers.forEach((destroy) => {
      if (typeof destroy === "function") {
        destroy();
      }
    });
  }, { once: true });
};
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initApp, { once: true });
} else {
  initApp();
}
