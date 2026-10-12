const SELECTORS = Object.freeze({
    wrapper: '.search-box-wrapper',
    form: '[data-search-form]',
    input: '[data-search-input]',
    results: '[data-search-results]',
    resultItem: '[data-search-result-item]',
});

const AJAX_HEADERS = Object.freeze({
    'X-Requested-With': 'XMLHttpRequest',
    Accept: 'application/json',
});

const MIN_QUERY_LENGTH = 2;
const DEBOUNCE_MS = 250;

let debounceTimer = null;

const escapeHtml = (value) => value
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');

const formatPrice = (price) => `€${Number(price).toLocaleString('de-DE')}`;

const closeResults = (container) => {
    container.hidden = true;
    container.innerHTML = '';
};

const renderResults = (container, query, results) => {
    if (results.length === 0) {
        container.innerHTML = `<p class="search-results-empty">No products found for "${escapeHtml(query)}"</p>`;
        return;
    }

    container.innerHTML = results.map((product) => `
        <a href="${product.url}" class="search-result-item" data-search-result-item data-ajax-link data-update-target="#page-content">
            <img src="${product.image}" alt="${escapeHtml(product.name)}" loading="lazy">
            <span class="search-result-info">
                <span class="search-result-name">${escapeHtml(product.name)}</span>
                <span class="search-result-brand">${escapeHtml(product.brand)}</span>
            </span>
            <span class="search-result-price">${formatPrice(product.price)}</span>
        </a>
    `).join('');
};

const performSearch = async (input, container) => {
    const query = input.value.trim();

    if (query.length < MIN_QUERY_LENGTH) {
        closeResults(container);
        return;
    }

    const url = new URL(input.closest('form').action);
    url.searchParams.set('q', query);

    try {
        const response = await fetch(url, { headers: AJAX_HEADERS });

        if (!response.ok) {
            closeResults(container);
            return;
        }

        const payload = await response.json();
        container.hidden = false;
        renderResults(container, payload.query, payload.results);
    } catch {
        closeResults(container);
    }
};

const getResultsContainer = (input) => input.closest(SELECTORS.wrapper)?.querySelector(SELECTORS.results);

document.addEventListener('input', (event) => {
    const input = event.target.closest(SELECTORS.input);

    if (!input) {
        return;
    }

    const container = getResultsContainer(input);

    if (!container) {
        return;
    }

    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => performSearch(input, container), DEBOUNCE_MS);
});

document.addEventListener('submit', (event) => {
    const form = event.target.closest(SELECTORS.form);

    if (!form) {
        return;
    }

    event.preventDefault();

    const input = form.querySelector(SELECTORS.input);
    const container = input && getResultsContainer(input);

    if (input && container) {
        clearTimeout(debounceTimer);
        performSearch(input, container);
    }
});

document.addEventListener('click', (event) => {
    if (event.target.closest(SELECTORS.resultItem)) {
        const container = event.target.closest(SELECTORS.results);

        if (container) {
            closeResults(container);
        }

        return;
    }

    document.querySelectorAll(SELECTORS.results).forEach((container) => {
        if (!container.hidden && !container.closest(SELECTORS.wrapper).contains(event.target)) {
            closeResults(container);
        }
    });
});

document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') {
        return;
    }

    document.querySelectorAll(SELECTORS.results).forEach((container) => closeResults(container));
});
