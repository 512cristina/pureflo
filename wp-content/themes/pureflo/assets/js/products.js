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

// CAROUSEL ACTIVE THUMB DETECTION

document.getElementById('collapseGuide')?.addEventListener(
  'shown.bs.collapse',
  function () {
    this.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
);

// Reusable function for any carousel + button group
function setupCarousel(carouselId, buttonContainerSelector) {
  const carousel = document.querySelector(carouselId);
  const buttons = document.querySelectorAll(`${buttonContainerSelector} button`);

  if (!carousel || !buttons.length) return;

  const slides = carousel.querySelectorAll('.carousel-item');

  function updateActiveButton(index) {
    buttons.forEach((btn, i) => {
      btn.classList.toggle('active', i === index);

      if (i === index) {
        btn.setAttribute('aria-current', 'true');
      } else {
        btn.removeAttribute('aria-current');
      }
    });
  }

  // Set the initial active button
  const initialIndex = [...slides].findIndex(slide =>
    slide.classList.contains('active')
  );
  updateActiveButton(initialIndex >= 0 ? initialIndex : 0);

  // Update after each slide change
  carousel.addEventListener('slid.bs.carousel', function () {
    const activeIndex = [...slides].findIndex(slide =>
      slide.classList.contains('active')
    );

    updateActiveButton(activeIndex);
  });
}

setupCarousel('#pfCarousel', '.pf-icons');
