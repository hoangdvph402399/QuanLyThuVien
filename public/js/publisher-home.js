// update cart count badge (reuse existing endpoint)
function updateCartCountNXB(){
    const cartBtn = document.querySelector('[data-cart-count-route]');
    if (!cartBtn) return;
    
    const cartCountRoute = cartBtn.dataset.cartCountRoute;
    if (!cartCountRoute) return;
    
    fetch(cartCountRoute, {headers: {Accept: 'application/json'}})
        .then(r=>r.json())
        .then(d=>{
            const el = document.getElementById('cart-count');
            if(!el) return;
            el.textContent = d.count || 0;
            el.style.display = (d.count||0) > 0 ? 'inline-block' : 'none';
        }).catch(()=>{});
}

document.addEventListener('DOMContentLoaded', ()=>{
    updateCartCountNXB();
    setInterval(updateCartCountNXB, 30000);
    
    // Book Carousel Navigation - Function to initialize carousel
    function initCarousel(carouselSelector, prevBtnSelector, nextBtnSelector) {
        const carousel = document.querySelector(carouselSelector);
        const prevBtn = document.querySelector(prevBtnSelector);
        const nextBtn = document.querySelector(nextBtnSelector);
        
        if (carousel && prevBtn && nextBtn) {
            const scrollAmount = 580; // Width of book item (180px) + gap (12px) * 3
            
            const updateButtons = () => {
                const scrollLeft = carousel.scrollLeft;
                const maxScroll = carousel.scrollWidth - carousel.clientWidth;
                
                prevBtn.style.opacity = scrollLeft > 0 ? '1' : '0.5';
                nextBtn.style.opacity = scrollLeft < maxScroll - 1 ? '1' : '0.5';
            };
            
            prevBtn.addEventListener('click', () => {
                carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });
            
            nextBtn.addEventListener('click', () => {
                carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });
            
            carousel.addEventListener('scroll', updateButtons);
            updateButtons();
            
            // Auto-hide buttons on mobile
            if (window.innerWidth <= 992) {
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'none';
            }
        }
    }
    
    // Initialize carousel for "Sách nói"
    setTimeout(() => {
        initCarousel('.nxb-audiobook-carousel', '.nxb-carousel-prev-audiobook', '.nxb-carousel-next-audiobook');
    }, 100);
    
    // Initialize featured books carousel
    const featuredCarousel = document.getElementById('featured-books-carousel');
    if (featuredCarousel) {
        // Auto-hide navigation buttons on scroll
        featuredCarousel.addEventListener('scroll', function() {
            const scrollLeft = featuredCarousel.scrollLeft;
            const maxScroll = featuredCarousel.scrollWidth - featuredCarousel.clientWidth;
            const prevBtn = document.querySelector('.featured-carousel-prev');
            const nextBtn = document.querySelector('.featured-carousel-next');
            
            if (prevBtn) prevBtn.style.opacity = scrollLeft > 0 ? '1' : '0.5';
            if (nextBtn) nextBtn.style.opacity = scrollLeft < maxScroll - 1 ? '1' : '0.5';
        });
    }
});

// Function to scroll featured books carousel
function scrollFeaturedBooks(direction) {
    const carousel = document.getElementById('featured-books-carousel');
    if (!carousel) return;
    
    const scrollAmount = 200; // Width of book card + gap
    const currentScroll = carousel.scrollLeft;
    const targetScroll = currentScroll + (scrollAmount * direction);
    
    carousel.scrollTo({
        left: targetScroll,
        behavior: 'smooth'
    });
}

