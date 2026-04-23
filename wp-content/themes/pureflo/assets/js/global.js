(function() {
  "use strict";

	function toggleScrolled() {
		const selectBody = document.querySelector('body');
		const selectHeader = document.querySelector('header');
		if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
		window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
	}

	document.addEventListener('scroll', toggleScrolled);
	window.addEventListener('load', toggleScrolled);

	const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');

	function mobileNavToogle() {
		document.querySelector('body').classList.toggle('mobile-nav-active');
		mobileNavToggleBtn.classList.toggle('bi-list');
		mobileNavToggleBtn.classList.toggle('bi-x');
	}
	if (mobileNavToggleBtn) {
		mobileNavToggleBtn.addEventListener('click', mobileNavToogle);
	}

	/* Hide mobile nav on same-page and hash links */
	document.querySelectorAll('#navmenu a').forEach(navmenu => {
		navmenu.addEventListener('click', () => {
		if (document.querySelector('.mobile-nav-active')) {
			mobileNavToogle();
		}
		});

	});

	document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
		navmenu.addEventListener('click', function(e) {
		e.preventDefault();
		this.parentNode.classList.toggle('active');
		this.parentNode.nextElementSibling.classList.toggle('dropdown-active');
		e.stopImmediatePropagation();
		});
	});

	new AOS.init();    /* Intiate AOS */
	const glightbox = GLightbox({ selector: '.glightbox'  });   /* Intiate glightbox  */

	let scrollTop = document.querySelector('.scroll-top');
	function toggleScrollTop() {
		if (scrollTop) { window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active'); }
	}
	scrollTop.addEventListener('click', (e) => {
		e.preventDefault();
		window.scrollTo({ top: 0, behavior: 'smooth' });
	});

	window.addEventListener('load', toggleScrollTop);
	document.addEventListener('scroll', toggleScrollTop);

	/* Correct scrolling position upon page load for URLs containing hash links. */
	window.addEventListener('load', function(e) {
		if (window.location.hash) {
		if (document.querySelector(window.location.hash)) {
			setTimeout(() => {
			let section = document.querySelector(window.location.hash);
			let scrollMarginTop = getComputedStyle(section).scrollMarginTop;
			window.scrollTo({
				top: section.offsetTop - parseInt(scrollMarginTop),
				behavior: 'smooth'
			});
			}, 100);
		}
		}
	});

	document.addEventListener("DOMContentLoaded", function () {
		const toggleBtn = document.querySelector(".search-toggle");
		const searchForm = document.querySelector(".search-form");
		const searchInput = document.querySelector(".search-input");

		toggleBtn.addEventListener("click", function () {
			searchForm.classList.toggle("active");
			if (searchForm.classList.contains("active")) { searchInput.focus(); } else { searchInput.blur(); searchInput.value = ""; }
		});
	});

	document.addEventListener("DOMContentLoaded", function () {
		const accordions = document.querySelectorAll('.accordion');
		accordions.forEach(accordion => {
			accordion.addEventListener('show.bs.collapse', function (event) {
			const item = event.target.closest('.accordion-item');
			if (item) { item.classList.add('active'); }
			});

			accordion.addEventListener('hide.bs.collapse', function (event) {
			const item = event.target.closest('.accordion-item');
			if (item) { item.classList.remove('active');  }
			});
		});
	});

	/* Navmenu Scrollspy  */
	let navmenulinks = document.querySelectorAll('.navmenu a');

	function navmenuScrollspy() {
		navmenulinks.forEach(navmenulink => {
		if (!navmenulink.hash) return;
		let section = document.querySelector(navmenulink.hash);
		if (!section) return;
		let position = window.scrollY + 200;
		if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
			document.querySelectorAll('.navmenu a.active').forEach(link => link.classList.remove('active'));
			navmenulink.classList.add('active');
		} else {
			navmenulink.classList.remove('active');
		}
		})
	}
	window.addEventListener('load', navmenuScrollspy);
	document.addEventListener('scroll', navmenuScrollspy);

})();


function toggleRegionMenu() {
	const menu = document.getElementById('regionMenu');
	menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
}

// close if clicked outside
document.addEventListener('click', function(e) {
	const switcher = document.querySelector('.region-switcher');
	if (!switcher.contains(e.target)) {
		document.getElementById('regionMenu').style.display = 'none';
	}
});

function switchRegion(region) {
	if (window.REGION_URLS && window.REGION_URLS[region]) {
		window.location.href = window.REGION_URLS[region];
		return;
	}

	window.location.href = '/' + region + '/';
}