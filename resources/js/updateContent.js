const SELECTORS = Object.freeze({
    ajaxLink: '[data-ajax-link]',
    ajaxFilterForm: '[data-ajax-filter-form]',
    categoryNavigationLink: '[data-category-nav]',
    wishlistToggle: '[data-wishlist-toggle]',
});

const ATTRIBUTES = Object.freeze({
    updateTarget: 'data-update-target',
});

const AJAX_HEADERS = Object.freeze({
    'X-Requested-With': 'XMLHttpRequest',
    Accept: 'application/json',
});

const PAGE_CONTENT_TARGET = '#page-content';

const isModifiedClick = (event) => event.button !== 0
    || event.metaKey
    || event.ctrlKey
    || event.shiftKey
    || event.altKey;

const getTargetElement = (target) => document.querySelector(target);

const shouldUpdateWithAjax = (target) => target && getTargetElement(target);

const fetchRenderedContent = async (url) => {
    const response = await fetch(url, { headers: AJAX_HEADERS });

    if (!response.ok) {
        throw new Error(`Request failed with ${response.status}`);
    }

    return response.json();
};

const updateActiveCategoryNavigation = (activeCategorySlug) => {
    document.querySelectorAll(SELECTORS.categoryNavigationLink).forEach((link) => {
        link.classList.toggle('active', link.dataset.categorySlug === (activeCategorySlug ?? ''));
    });
};

const scrollAfterNavigation = (url, pushState) => {
    const hash = new URL(url, window.location.origin).hash;

    if (hash) {
        document.querySelector(hash)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        return;
    }

    if (pushState) {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
};

/**
 * Fetches backend-rendered Blade HTML and replaces the configured page region.
 */
export const updateContent = async ({ url, target, pushState = true }) => {
    const targetElement = getTargetElement(target);

    if (!targetElement) {
        window.location.assign(url);
        return;
    }

    targetElement.setAttribute('aria-busy', 'true');

    try {
        const payload = await fetchRenderedContent(url);

        targetElement.innerHTML = payload.html;
        document.title = payload.title;
        updateActiveCategoryNavigation(payload.activeCategorySlug);

        if (pushState) {
            window.history.pushState({}, '', url);
        }

        scrollAfterNavigation(url, pushState);
    } catch {
        window.location.assign(url);
    } finally {
        targetElement.removeAttribute('aria-busy');
    }
};

const handleAjaxLinkClick = (event) => {
    const link = event.target.closest(SELECTORS.ajaxLink);
    const target = link?.getAttribute(ATTRIBUTES.updateTarget);

    if (!link || event.defaultPrevented || isModifiedClick(event) || link.closest('.disabled') || !shouldUpdateWithAjax(target)) {
        return;
    }

    event.preventDefault();
    updateContent({ url: link.href, target });
};

const handleFilterSubmit = (event) => {
    const form = event.target.closest(SELECTORS.ajaxFilterForm);
    const target = form?.getAttribute(ATTRIBUTES.updateTarget);

    if (!form || !shouldUpdateWithAjax(target)) {
        return;
    }

    event.preventDefault();

    const url = new URL(form.action);
    new FormData(form).forEach((value, key) => {
        if (value !== '') {
            url.searchParams.set(key, value);
        }
    });

    updateContent({ url: url.toString(), target });
};

const handleWishlistToggle = (event) => {
    const button = event.target.closest(SELECTORS.wishlistToggle);

    if (!button) {
        return;
    }

    event.preventDefault();
    button.classList.toggle('is-active');
};

document.addEventListener('click', handleWishlistToggle);
document.addEventListener('click', handleAjaxLinkClick);
document.addEventListener('submit', handleFilterSubmit);

window.addEventListener('popstate', () => {
    if (getTargetElement(PAGE_CONTENT_TARGET)) {
        updateContent({
            url: window.location.href,
            target: PAGE_CONTENT_TARGET,
            pushState: false,
        });
    }
});
