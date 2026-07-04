// Lazy loading de imágenes
const lazyImages = document.querySelectorAll('.lazy-image');

const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const img = entry.target;
            const dataSrc = img.getAttribute('data-src');
            
            if (dataSrc) {
                const tempImg = new Image();
                tempImg.onload = function() {
                    img.src = dataSrc;
                    img.classList.remove('opacity-50');
                    img.classList.add('opacity-100');
                    img.removeAttribute('data-src');
                };
                tempImg.onerror = function() {
                    img.src = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 80 80'%3E%3Crect width='80' height='80' fill='%23374151'/%3E%3Ctext x='50%25' y='50%25' text-anchor='middle' dy='.3em' fill='%23fff' font-size='12'%3E🛡️%3C/text%3E%3C/svg%3E";
                    img.classList.remove('opacity-50');
                    img.classList.add('opacity-100');
                    img.removeAttribute('data-src');
                };
                tempImg.src = dataSrc;
            }
            
            observer.unobserve(img);
        }
    });
}, {
    rootMargin: '50px 0px',
    threshold: 0.1
});

lazyImages.forEach(img => {
    imageObserver.observe(img);
});