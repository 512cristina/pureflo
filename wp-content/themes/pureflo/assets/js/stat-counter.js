const counterSection = document.querySelector('.stats-container');
const counters = document.querySelectorAll('.counter');

function startCounters() {
    counters.forEach(counter => {
        const target = parseFloat(counter.dataset.target);
        const duration = parseInt(counter.dataset.duration) || 2000;
        const decimals = parseInt(counter.dataset.decimals) || 0;

        const startTime = performance.now();

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            const current = target * progress;

            if (decimals > 0) {
                counter.textContent = current.toFixed(decimals);
            } else {
                counter.textContent = Math.floor(current);
            }

            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                // Ensure the final value is exact
                if (decimals > 0) {
                    counter.textContent = target.toFixed(decimals);
                } else {
                    counter.textContent = target;
                }
            }
        }

        requestAnimationFrame(update);
    });
}

// Intersection Observer
const options = {
    root: null,
    threshold: 0.35
};

const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            startCounters();
            observer.unobserve(entry.target);
        }
    });
}, options);

observer.observe(counterSection);