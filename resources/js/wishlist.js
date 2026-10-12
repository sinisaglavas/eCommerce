const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

const updateWishlistDropdown = (wishlisted, productId, wishlistItem) => {
    const list = document.querySelector('[data-wishlist-dropdown-list]');

    if (!list) {
        return;
    }

    list.querySelector(`[data-product-id="${productId}"]`)?.remove();

    if (wishlisted && wishlistItem) {
        const item = document.createElement('a');
        item.href = wishlistItem.url;
        item.className = 'notif-item c-pink';
        item.dataset.wishlistDropdownItem = '';
        item.dataset.productId = wishlistItem.id;
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
        item.append(imageContainer, content);
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
        button.dataset.wishlisted = String(wishlisted);
        button.querySelector('[data-wishlist-label]').textContent = wishlisted ? 'Remove from wishlist' : 'Add to wishlist';
        button.classList.toggle('btn-outline-light', !wishlisted);
        button.classList.toggle('btn-light', wishlisted);
        document.querySelectorAll('[data-wishlist-count]').forEach((count) => {
            count.textContent = wishlistCount;
        });
        updateWishlistDropdown(wishlisted, productId, wishlistItem);
    } catch {
        button.querySelector('[data-wishlist-label]').textContent = 'Try again';
    } finally {
        button.disabled = false;
    }
});
