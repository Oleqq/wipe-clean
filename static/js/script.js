import { registerBeforeAfterComparisons } from "./modules/content/register-before-after-comparisons.js";
import { registerBeforeAfterResultsLoadMore } from "./modules/content/register-before-after-results-load-more.js";
import { registerBlogArchiveLoadMore } from "./modules/content/register-blog-archive-load-more.js";
import { registerReadMore } from "./modules/content/register-read-more.js";
import { registerServiceIncludesTabs } from "./modules/content/register-service-includes-tabs.js";
import { registerCompanyPreviewBenefitsSwiper } from "./modules/swipers/register-company-preview-benefits-swiper.js";
import { registerFancybox } from "./modules/fancybox/register-fancybox.js";
import { registerFaqAccordion } from "./modules/faq/register-faq-accordion.js";
import { registerGalleryPreviewMarquee } from "./modules/gallery/register-gallery-preview-marquee.js";
import { registerHeroAreaSwiper } from "./modules/swipers/register-hero-area-swiper.js";
import { registerPriceAdvantagesSwiper } from "./modules/swipers/register-price-advantages-swiper.js";
import { registerPriceFactorsSwiper } from "./modules/swipers/register-price-factors-swiper.js";
import { registerReviewsPreviewSwiper } from "./modules/swipers/register-reviews-preview-swiper.js";
import { registerServicesBenefitsSwiper } from "./modules/swipers/register-services-benefits-swiper.js";
import { registerServicesIntroSwiper } from "./modules/swipers/register-services-intro-swiper.js";
import { registerServiceBenefitsSwiper } from "./modules/swipers/register-service-benefits-swiper.js";
import { registerTeamPreviewSwiper } from "./modules/swipers/register-team-preview-swiper.js";
import { registerWorkApproachSwiper } from "./modules/swipers/register-work-approach-swiper.js";
import { registerWorkStepsSwiper } from "./modules/swipers/register-work-steps-swiper.js";
import { registerWhyUsSwiper } from "./modules/swipers/register-why-us-swiper.js";

const initApp = () => {
    const destroyers = [
        registerBeforeAfterComparisons(),
        registerBeforeAfterResultsLoadMore(),
        registerBlogArchiveLoadMore(),
        registerReadMore(),
        registerServiceIncludesTabs(),
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
