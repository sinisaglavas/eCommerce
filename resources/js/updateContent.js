const SELECTORS = Object.freeze({
    productContent: '#product-content',
    categoryTitle: '#category-title',
    categoryIcon: '#category-icon',
    categoryFilterForm: '#category-filter-form',
    categoryNavigationLink: '[data-category-nav]',
    ajaxLink: '[data-ajax-link]',
});

const AJAX_HEADERS = Object.freeze({
    'X-Requested-With': 'XMLHttpRequest',
    Accept: 'application/json',
});

const CATEGORY_PATH_PREFIX = '/categories/';

const getProductContent = () => document.querySelector(SELECTORS.productContent);
const getFilterForm = () => document.querySelector(SELECTORS.categoryFilterForm);

const isModifiedClick = (event) => event.button !== 0
    || event.metaKey
    || event.ctrlKey
    || event.shiftKey
    || event.altKey;

const getCategoryUrl = (slug) => {
    const navigationLink = document.querySelector(
        `[data-category-nav][data-category-slug="${CSS.escape(slug)}"]`,
    );

    return navigationLink?.href ?? new URL(`${CATEGORY_PATH_PREFIX}${slug}`, window.location.origin).href;
};

const setFilterValues = (filters) => {
    const filterForm = getFilterForm();

    if (!filterForm) {
        return;
    }

    filterForm.elements.min_price.value = filters.min_price ?? '';
    filterForm.elements.max_price.value = filters.max_price ?? '';
};

const updateCategoryDetails = (category, filters) => {
    document.querySelector(SELECTORS.categoryTitle)?.replaceChildren(category.name);

    const categoryIcon = document.querySelector(SELECTORS.categoryIcon);
    if (categoryIcon) {
        categoryIcon.className = `section-icon ${category.color}`;
        categoryIcon.querySelector('use')?.setAttribute('href', `#i-${category.icon}`);
    }

    document.querySelectorAll(SELECTORS.categoryNavigationLink).forEach((link) => {
        link.classList.toggle('active', link.dataset.categorySlug === category.slug);
    });

    const filterForm = getFilterForm();
    if (filterForm) {
        filterForm.action = getCategoryUrl(category.slug);
    }

    setFilterValues(filters);
};

const fetchProductContent = async (url) => {
    const response = await fetch(url, { headers: AJAX_HEADERS });

    if (!response.ok) {
        throw new Error(`Request failed with ${response.status}`);
    }

    return response.json();
};

const updateProductContent = async (url, { pushState = true } = {}) => {
    const productContent = getProductContent();

    if (!productContent) {
        window.location.assign(url);
        return;
    }

    productContent.setAttribute('aria-busy', 'true');

    try {
        const payload = await fetchProductContent(url);

        productContent.innerHTML = payload.html;
        document.title = payload.title;
        updateCategoryDetails(payload.category, payload.filters);

        if (pushState) {
            window.history.pushState({}, '', url);
        }
    } catch {
        window.location.assign(url);
    } finally {
        productContent.removeAttribute('aria-busy');
    }
};

const handleAjaxLinkClick = (event) => {
    const link = event.target.closest(SELECTORS.ajaxLink);

    if (!link || event.defaultPrevented || isModifiedClick(event) || link.closest('.disabled')) {
        return;
    }

    event.preventDefault();
    updateProductContent(link.href);
};

const handleFilterSubmit = (event) => {
    const filterForm = event.target.closest(SELECTORS.categoryFilterForm);

    if (!filterForm || !getProductContent()) {
        return;
    }

    event.preventDefault();

    const url = new URL(filterForm.action);
    new FormData(filterForm).forEach((value, key) => {
        if (value !== '') {
            url.searchParams.set(key, value);
        }
    });

    updateProductContent(url.toString());
};

document.addEventListener('click', handleAjaxLinkClick);
document.addEventListener('submit', handleFilterSubmit);

window.addEventListener('popstate', () => {
    if (window.location.pathname.startsWith(CATEGORY_PATH_PREFIX)) {
        updateProductContent(window.location.href, { pushState: false });
    }
});
