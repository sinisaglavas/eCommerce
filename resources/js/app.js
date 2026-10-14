import './updateContent';
import './search';
import './wishlist';
import './cart';

document.addEventListener('click', (event) => {
    const thumbnail = event.target.closest('[data-product-gallery-thumbnail]');

    const arrow = event.target.closest('[data-product-gallery-prev], [data-product-gallery-next]');
    const gallery = (thumbnail || arrow)?.closest('.product-gallery');

    if (!gallery) {
        return;
    }

    const mainImage = gallery?.querySelector('[data-product-gallery-main]');
    const thumbnails = [...gallery.querySelectorAll('[data-product-gallery-thumbnail]')];

    if (arrow) {
        const activeIndex = thumbnails.findIndex((item) => item.classList.contains('is-active'));
        const currentIndex = activeIndex >= 0 ? activeIndex : 0;
        const direction = arrow.matches('[data-product-gallery-prev]') ? -1 : 1;
        const nextIndex = (currentIndex + direction + thumbnails.length) % thumbnails.length;
        thumbnails[nextIndex]?.click();
        return;
    }

    const thumbnailImage = thumbnail.querySelector('img');

    if (!mainImage || !thumbnailImage) {
        return;
    }

    mainImage.src = thumbnailImage.src;
    mainImage.alt = thumbnailImage.alt;

    gallery.querySelectorAll('[data-product-gallery-thumbnail]').forEach((item) => {
        const isActive = item === thumbnail;
        item.classList.toggle('is-active', isActive);
        item.setAttribute('aria-pressed', String(isActive));
    });
});
