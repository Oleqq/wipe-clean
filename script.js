import { registerBeforeAfterComparisons } from "./modules/content/register-before-after-comparisons.js";
import { registerBeforeAfterResultsLoadMore } from "./modules/content/register-before-after-results-load-more.js";
import { registerBlogArchiveLoadMore } from "./modules/content/register-blog-archive-load-more.js";
import { registerDemoForms } from "./modules/content/register-demo-forms.js";
import { registerMessageReviewsLoadMore } from "./modules/content/register-message-reviews-load-more.js";
import { registerPhoneMask } from "./modules/content/register-phone-mask.js";
import { registerPromotionsArchiveLoadMore } from "./modules/content/register-promotions-archive-load-more.js";
import { registerReadMore } from "./modules/content/register-read-more.js";
import { registerReviewsArchiveLoadMore } from "./modules/content/register-reviews-archive-load-more.js";
import { registerScrollReveal } from "./modules/content/register-scroll-reveal.js";
import { registerSmoothScroll } from "./modules/content/register-smooth-scroll.js";
import { registerServiceIncludesTabs } from "./modules/content/register-service-includes-tabs.js";
import { registerHeader } from "./modules/navigation/register-header.js";
import { registerPopups } from "./modules/popup/register-popups.js";
import { registerCompanyPreviewBenefitsSwiper } from "./modules/swipers/register-company-preview-benefits-swiper.js";
import { registerFancybox } from "./modules/fancybox/register-fancybox.js";
import { registerFaqAccordion } from "./modules/faq/register-faq-accordion.js";
import { registerGalleryPreviewMarquee } from "./modules/gallery/register-gallery-preview-marquee.js";
import { registerHeroAreaSwiper } from "./modules/swipers/register-hero-area-swiper.js";
import { registerPriceAdvantagesSwiper } from "./modules/swipers/register-price-advantages-swiper.js";
import { registerPriceFactorsSwiper } from "./modules/swipers/register-price-factors-swiper.js";
import { registerPopupChoiceSwiper } from "./modules/swipers/register-popup-choice-swiper.js";
import { registerReviewsPreviewSwiper } from "./modules/swipers/register-reviews-preview-swiper.js";
import { registerServicesBenefitsSwiper } from "./modules/swipers/register-services-benefits-swiper.js";
import { registerServicesIntroSwiper } from "./modules/swipers/register-services-intro-swiper.js";
import { registerServiceBenefitsSwiper } from "./modules/swipers/register-service-benefits-swiper.js";
import { registerTeamPreviewSwiper } from "./modules/swipers/register-team-preview-swiper.js";
import { registerVideoReviewsSwiper } from "./modules/swipers/register-video-reviews-swiper.js";
import { registerWorkApproachSwiper } from "./modules/swipers/register-work-approach-swiper.js";
import { registerWorkStepsSwiper } from "./modules/swipers/register-work-steps-swiper.js";
import { registerWhyUsSwiper } from "./modules/swipers/register-why-us-swiper.js";

const initApp = () => {
    const safeRegister = (name, register) => {
        try {
            return register();
        } catch (error) {
            console.error(`[wipe-clean] ${name} init failed`, error);

            return null;
        }
    };

    const destroyers = [
        safeRegister("smooth scroll", registerSmoothScroll),
        safeRegister("scroll reveal", registerScrollReveal),
        safeRegister("header", registerHeader),
        safeRegister("popups", registerPopups),
        safeRegister("phone mask", registerPhoneMask),
        safeRegister("read more", registerReadMore),
        safeRegister("faq accordion", registerFaqAccordion),
        safeRegister("fancybox", registerFancybox),
        safeRegister("gallery marquee", registerGalleryPreviewMarquee),
        safeRegister("service includes tabs", registerServiceIncludesTabs),
        safeRegister("before/after comparisons", registerBeforeAfterComparisons),
        safeRegister("before/after load more", registerBeforeAfterResultsLoadMore),
        safeRegister("blog archive load more", registerBlogArchiveLoadMore),
        safeRegister("demo forms", registerDemoForms),
        safeRegister("message reviews load more", registerMessageReviewsLoadMore),
        safeRegister("promotions archive load more", registerPromotionsArchiveLoadMore),
        safeRegister("reviews archive load more", registerReviewsArchiveLoadMore),
        safeRegister("service benefits swiper", registerServiceBenefitsSwiper),
        safeRegister("company preview swiper", registerCompanyPreviewBenefitsSwiper),
        safeRegister("hero area swiper", registerHeroAreaSwiper),
        safeRegister("price advantages swiper", registerPriceAdvantagesSwiper),
        safeRegister("price factors swiper", registerPriceFactorsSwiper),
        safeRegister("popup choice swiper", registerPopupChoiceSwiper),
        safeRegister("reviews preview swiper", registerReviewsPreviewSwiper),
        safeRegister("services benefits swiper", registerServicesBenefitsSwiper),
        safeRegister("services intro swiper", registerServicesIntroSwiper),
        safeRegister("team preview swiper", registerTeamPreviewSwiper),
        safeRegister("video reviews swiper", registerVideoReviewsSwiper),
        safeRegister("work approach swiper", registerWorkApproachSwiper),
        safeRegister("work steps swiper", registerWorkStepsSwiper),
        safeRegister("why us swiper", registerWhyUsSwiper)
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
