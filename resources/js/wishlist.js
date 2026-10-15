const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

const updateWishlistCount = (wishlistCount) => {
    document.querySelectorAll('[data-wishlist-saved-count]').forEach((count) => {
        count.textContent = wishlistCount;
    });

    const wishlistButton = document.querySelector('[data-bs-toggle="dropdown"][title="Wishlist"]');
    if (!wishlistButton) {
        return;
    }

    const badge = wishlistButton.querySelector('[data-wishlist-badge]');
    if (wishlistCount === 0) {
        badge?.remove();
        return;
    }

    if (badge) {
        badge.textContent = wishlistCount;
        return;
    }

    const newBadge = document.createElement('span');
    newBadge.className = 'count pink';
    newBadge.dataset.wishlistBadge = '';
    newBadge.textContent = wishlistCount;
    wishlistButton.append(newBadge);
};

const updateWishlistButton = (productId, wishlisted) => {
    document.querySelectorAll('[data-wishlist-button]').forEach((button) => {
        if (String(button.dataset.productId) !== String(productId)) {
            return;
        }

        button.dataset.wishlisted = String(wishlisted);
        button.setAttribute('aria-pressed', String(wishlisted));
        const label = button.querySelector('[data-wishlist-label]');

        if (label) {
            label.textContent = wishlisted
                ? 'Remove from wishlist'
                : 'Add to wishlist';
            button.classList.toggle('btn-outline-light', !wishlisted);
            button.classList.toggle('btn-light', wishlisted);
        }

        if (button.classList.contains('product-wishlist-btn')) {
            button.classList.toggle('is-active', wishlisted);
            button.setAttribute('aria-label', wishlisted ? 'Remove from wishlist' : 'Add to wishlist');
        }
    });
};

const updateWishlistDropdown = (wishlisted, productId, wishlistItem) => {
    const list = document.querySelector('[data-wishlist-dropdown-list]');

    if (!list) {
        return;
    }

    list.querySelector(`[data-product-id="${productId}"]`)?.remove();

    if (wishlisted && wishlistItem) {
        const item = document.createElement('div');
        item.className = 'notif-item c-pink';
        item.dataset.wishlistDropdownItem = '';
        item.dataset.productId = wishlistItem.id;
        const link = document.createElement('a');
        link.href = wishlistItem.url;
        link.className = 'd-flex align-items-center gap-2 text-decoration-none flex-grow-1';
        const imageContainer = document.createElement('span');
        imageContainer.className = 'notif-icon overflow-hidden';
        const image = document.createElement('img');
        image.src = wishlistItem.image;
        image.alt = '';
        image.className = 'w-100 h-100 object-fit-cover';
        imageContainer.append(image);
        const content = document.createElement('span');
        const name = document.createElement('span');
        name.className = 'd-block small fw-semibold';
        name.textContent = wishlistItem.name;
        const price = document.createElement('span');
        price.className = 'd-block small text-secondary';
        price.textContent = `€${wishlistItem.price}`;
        content.append(name, price);
        link.append(imageContainer, content);
        const remove = document.createElement('button');
        remove.type = 'button';
        remove.className = 'wishlist-remove-btn';
        remove.dataset.wishlistRemove = '';
        remove.dataset.wishlistUrl = wishlistItem.toggleUrl;
        remove.dataset.productId = wishlistItem.id;
        remove.setAttribute('aria-label', `Remove ${wishlistItem.name} from wishlist`);
        remove.textContent = '×';
        item.append(link, remove);
        list.querySelector('[data-wishlist-empty]')?.remove();
        list.prepend(item);
        list.querySelectorAll('[data-wishlist-dropdown-item]').forEach((dropdownItem, index) => {
            if (index >= 5) {
                dropdownItem.remove();
            }
        });
    }

    if (list.querySelectorAll('[data-wishlist-dropdown-item]').length === 0) {
        list.innerHTML = '<p class="small text-secondary text-center px-3 py-4 mb-0" data-wishlist-empty>Your wishlist is empty.</p>';
    }
};

document.addEventListener('click', async (event) => {
    const removeButton = event.target.closest('[data-wishlist-remove]');

    if (!removeButton || removeButton.disabled) {
        return;
    }

    event.preventDefault();
    event.stopPropagation();
    removeButton.disabled = true;

    try {
        const response = await fetch(removeButton.dataset.wishlistUrl, {
            method: 'POST',
            headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
        });

        if (!response.ok) {
            throw new Error('Unable to remove wishlist item.');
        }

        const { wishlistCount, productId } = await response.json();
        updateWishlistButton(productId, false);
        updateWishlistCount(wishlistCount);
        updateWishlistDropdown(false, productId);
    } catch (error) {
        console.error('Wishlist removal failed:', error);
        removeButton.disabled = false;
    }
});

document.addEventListener('click', async (event) => {
    const button = event.target.closest('[data-wishlist-button]');
    if (!button || button.disabled) {
        return;
    }

    event.preventDefault();
    button.disabled = true;

    try {
        const response = await fetch(button.dataset.wishlistUrl, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        });

        if (!response.ok) {
            throw new Error('Unable to update wishlist.');
        }

        const { wishlisted, wishlistCount, productId, wishlistItem } = await response.json();
        updateWishlistButton(productId, wishlisted);
        updateWishlistCount(wishlistCount);
        updateWishlistDropdown(wishlisted, productId, wishlistItem);
    } catch (error) {
        console.error('JS Greška:', error);
        const label = button.querySelector('[data-wishlist-label]');
        if (label) {
            label.textContent = 'Try again';
        }
    } finally {
        button.disabled = false;
    }
});
