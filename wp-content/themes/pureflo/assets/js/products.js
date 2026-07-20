 // MAIN IMAGE GALLERY SWAPPER
document.addEventListener('DOMContentLoaded', function () {
    // Elements
    const mainImage = document.getElementById('galleryMainImage');
    const mainLink = document.getElementById('galleryMainImageLink');
    const thumbs = document.querySelectorAll('.gallery-thumb');

    // Create ONE GLightbox instance
    const lightbox = GLightbox({ elements: [{ href: mainLink.href, type: 'image', title: mainLink.dataset.title }] });

    // Thumbnail clicks
    thumbs.forEach(function (thumb) {

        thumb.addEventListener('click', function () {
            const large = this.dataset.large;
            const title = this.dataset.title;

            // Update main image
            mainImage.src = large; 	mainImage.alt = title;

            // Update link
            mainLink.href = large; mainLink.dataset.title = title;

            // Update active thumbnail
            thumbs.forEach(t => t.classList.remove('active')); 	this.classList.add('active');

            // Update the lightbox image
            lightbox.setElements([ { href: large, type: 'image', title: title } ]);
        });
    });

    // Open the lightbox
    mainLink.addEventListener('click', function (e) { e.preventDefault(); lightbox.open();  });
});

document.getElementById('collapseGuide').addEventListener(
  'shown.bs.collapse', function () { this.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
);

// Reusable function for any carousel + button group
function setupCarousel(carouselId, buttonContainerSelector) {
  const carousel = document.querySelector(carouselId);
  const buttons = document.querySelectorAll(`${buttonContainerSelector} button`);

  if (!carousel || !buttons.length) return;

  carousel.addEventListener('slid.bs.carousel', function (e) {
    buttons.forEach(btn => btn.classList.remove('active'));

    if (buttons[e.to]) { buttons[e.to].classList.add('active'); }
  });
}
setupCarousel('#pfCarousel', '.pf-icons');
