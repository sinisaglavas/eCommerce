const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

const updateCartCount = (cartCount) => {
    document.querySelectorAll('[data-cart-item-count]').forEach((count) => {
        count.textContent = cartCount;
    });

    const button = document.querySelector('[data-bs-toggle="dropdown"][title="Cart"]');
    if (!button) {
        return;
    }

    const badge = button.querySelector('[data-cart-badge]');
    if (cartCount === 0) {
        badge?.remove();
        return;
    }

    if (badge) {
        badge.textContent = cartCount;
        return;
    }

    const newBadge = document.createElement('span');
    newBadge.className = 'count';
    newBadge.dataset.cartBadge = '';
    newBadge.textContent = cartCount;
    button.append(newBadge);
};

const updateCartDropdown = (cartItem) => {
    const list = document.querySelector('[data-cart-dropdown-list]');
    if (!list || !cartItem) {
        return;
    }

    list.querySelector(`[data-product-id="${cartItem.id}"]`)?.remove();
    list.querySelector('[data-cart-empty]')?.remove();

    const item = document.createElement('div');
    item.className = 'notif-item c-blue';
    item.dataset.cartDropdownItem = '';
    item.dataset.productId = cartItem.id;

    const link = document.createElement('a');
    link.href = cartItem.url;
    link.className = 'd-flex align-items-center gap-2 text-decoration-none flex-grow-1';

    const imageContainer = document.createElement('span');
    imageContainer.className = 'notif-icon overflow-hidden';
    const image = document.createElement('img');
    image.src = cartItem.image;
    image.alt = '';
    image.className = 'w-100 h-100 object-fit-cover';
    imageContainer.append(image);

    const content = document.createElement('span');
    const name = document.createElement('span');
    name.className = 'd-block small fw-semibold';
    name.textContent = cartItem.name;
    const price = document.createElement('span');
    price.className = 'd-block small text-secondary';
    price.textContent = `€${cartItem.price}`;
    content.append(name, price);
    link.append(imageContainer, content);
    const remove = document.createElement('button');
    remove.type = 'button';
    remove.className = 'wishlist-remove-btn';
    remove.dataset.cartRemove = '';
    remove.dataset.cartRemoveUrl = cartItem.removeUrl;
    remove.dataset.productId = cartItem.id;
    remove.setAttribute('aria-label', `Remove ${cartItem.name} from cart`);
    remove.textContent = '×';
    item.append(link, remove);
    list.prepend(item);

    list.querySelectorAll('[data-cart-dropdown-item]').forEach((dropdownItem, index) => {
        if (index >= 5) {
            dropdownItem.remove();
        }
    });
};

const removeCartDropdownItem = (productId) => {
    const list = document.querySelector('[data-cart-dropdown-list]');
    if (!list) {
        return;
    }

    list.querySelector(`[data-product-id="${productId}"]`)?.remove();
    if (list.querySelectorAll('[data-cart-dropdown-item]').length === 0) {
        list.innerHTML = '<p class="small text-secondary text-center px-3 py-4 mb-0" data-cart-empty>Your cart is empty.</p>';
    }
};

const updateCartForm = (form, isInCart) => {
    form.dataset.cartInCart = String(isInCart);
    form.action = isInCart ? form.dataset.cartRemoveUrl : form.dataset.cartAddUrl;
    form.querySelector('input[name="_method"]')?.remove();

    if (isInCart) {
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        form.append(method);
    }

    const button = form.querySelector('[data-cart-button]');
    button.textContent = isInCart ? 'Remove from cart' : 'Add to cart';
    button.classList.toggle('btn-outline-light', isInCart);
    button.classList.toggle('btn-primary', !isInCart);
};

document.addEventListener('click', async (event) => {
    const removeButton = event.target.closest('[data-cart-remove]');
    if (!removeButton || removeButton.disabled) {
        return;
    }

    event.preventDefault();
    event.stopPropagation();
    removeButton.disabled = true;

    try {
        const response = await fetch(removeButton.dataset.cartRemoveUrl, {
            method: 'DELETE',
            headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
        });

        if (!response.ok) {
            throw new Error('Unable to remove product from cart.');
        }

        const { cartCount, productId } = await response.json();
        updateCartCount(cartCount);
        removeCartDropdownItem(productId);
        document.querySelectorAll('[data-cart-form]').forEach((form) => {
            if (form.dataset.cartInCart === 'true' && form.dataset.cartRemoveUrl === removeButton.dataset.cartRemoveUrl) {
                updateCartForm(form, false);
            }
        });
    } catch (error) {
        console.error('Cart removal failed:', error);
        removeButton.disabled = false;
    }
});

document.addEventListener('submit', async (event) => {
    const form = event.target.closest('[data-cart-form]');
    if (!form) {
        return;
    }

    event.preventDefault();
    const button = form.querySelector('button[type="submit"]');
    button.disabled = true;
    const isInCart = form.dataset.cartInCart === 'true';

    try {
        const response = await fetch(form.action, {
            method: isInCart ? 'DELETE' : 'POST',
            headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
        });

        if (!response.ok) {
            throw new Error('Unable to add product to cart.');
        }

        const { cartCount, cartItem, productId } = await response.json();
        updateCartCount(cartCount);
        if (isInCart) {
            removeCartDropdownItem(productId);
        } else {
            updateCartDropdown(cartItem);
        }
        updateCartForm(form, !isInCart);
    } catch (error) {
        console.error('Cart update failed:', error);
    } finally {
        button.disabled = false;
    }
});
