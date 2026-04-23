
const counterSection = document.querySelector('.stats-container');
const counters = document.querySelectorAll('.counter');
const speed = 200; // Lower is faster

const startCounters = () => {
  counters.forEach((counter) => {
    const updateCount = () => {
      const target = +counter.getAttribute('data-target');
      const count = +counter.innerText;

      // Calculate increment
      const inc = target / speed;

      if (count < target) {
        // Add increment and wait
        counter.innerText = Math.ceil(count + inc);
        setTimeout(updateCount, 1);
      } else {
        counter.innerText = target; // Ensure final number is exact
      }
    };
    updateCount();
  });
};

// Intersection Observer Options
const options = {
  root: null, // use the viewport
  threshold: 0.5 // trigger when 50% of section is visible
};

const observer = new IntersectionObserver((entries, observer) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      startCounters();
      observer.unobserve(entry.target); // Run only once
    }
  });
}, options);

observer.observe(counterSection);
