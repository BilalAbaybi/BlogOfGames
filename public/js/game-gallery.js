(() => {
    const gallery = document.querySelector('[data-game-gallery]');
    const dialog = document.querySelector('[data-gallery-dialog]');

    if (!gallery || !dialog) {
        return;
    }

    const thumbnails = [...gallery.querySelectorAll('[data-gallery-open]')];
    const image = dialog.querySelector('[data-gallery-current]');
    const counter = dialog.querySelector('[data-gallery-counter]');
    const previousButton = dialog.querySelector('[data-gallery-previous]');
    const nextButton = dialog.querySelector('[data-gallery-next]');
    let currentIndex = 0;

    const displayImage = (index) => {
        currentIndex = (index + thumbnails.length) % thumbnails.length;
        const thumbnail = thumbnails[currentIndex].querySelector('img');

        image.src = thumbnail.src;
        image.alt = thumbnail.alt;
        counter.textContent = `${currentIndex + 1} / ${thumbnails.length}`;
        previousButton.disabled = thumbnails.length < 2;
        nextButton.disabled = thumbnails.length < 2;
    };

    thumbnails.forEach((thumbnail) => {
        thumbnail.addEventListener('click', () => {
            displayImage(Number(thumbnail.dataset.galleryIndex));
            dialog.showModal();
        });
    });

    previousButton.addEventListener('click', () => displayImage(currentIndex - 1));
    nextButton.addEventListener('click', () => displayImage(currentIndex + 1));
    dialog.querySelector('[data-gallery-close]').addEventListener('click', () => dialog.close());

    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) {
            dialog.close();
        }
    });

    dialog.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowLeft') {
            displayImage(currentIndex - 1);
        }

        if (event.key === 'ArrowRight') {
            displayImage(currentIndex + 1);
        }
    });
})();
