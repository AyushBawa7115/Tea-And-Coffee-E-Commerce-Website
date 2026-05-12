document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const nav = document.querySelector('nav');
    
    if (mobileMenuToggle && nav) {
        mobileMenuToggle.addEventListener('click', function() {
            nav.classList.toggle('active');
        });
    }
    document.addEventListener("DOMContentLoaded", function () {
        const sliderWrapper = document.querySelector('.slider-wrapper');
        const slides = Array.from(document.querySelectorAll('.slide'));
    
        if (sliderWrapper && slides.length > 0) {
            // Clone slides for an infinite effect
            slides.forEach(slide => {
                const clone = slide.cloneNode(true);
                sliderWrapper.appendChild(clone);
            });
    
            let index = 0;
            let slideWidth = slides[0].offsetWidth;
            let isPaused = false;
            let animationId;
    
            // Function to move slides
            function moveSlide() {
                if (!isPaused) {
                    index++;
                    if (index >= slides.length) {
                        // Reset index for seamless looping
                        sliderWrapper.style.transition = "none";
                        sliderWrapper.style.transform = `translateX(0px)`;
                        index = 0;
                        setTimeout(() => {
                            sliderWrapper.style.transition = "transform 0.5s ease-in-out";
                        }, 50); // Small delay to avoid flickering
                    } else {
                        sliderWrapper.style.transition = "transform 0.5s ease-in-out";
                        sliderWrapper.style.transform = `translateX(-${index * slideWidth}px)`;
                    }
                }
                animationId = setTimeout(moveSlide, 3000); // Slide every 3s
            }
    
            // Start Auto-Sliding
            moveSlide();
    
            // Pause on hover
            sliderWrapper.addEventListener('mouseenter', () => {
                isPaused = true;
                clearTimeout(animationId);
            });
    
            sliderWrapper.addEventListener('mouseleave', () => {
                isPaused = false;
                moveSlide();
            });
    
            // Handle window resize
            window.addEventListener('resize', () => {
                slideWidth = slides[0].offsetWidth; // Update slide width
                sliderWrapper.style.transition = "none";
                sliderWrapper.style.transform = `translateX(-${index * slideWidth}px)`;
            });
        }
    });
    
    // Add to cart functionality
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-id');
            
            // In a real application, you would send this to the server
            // For now, we'll just update the cart count
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                let count = parseInt(cartCount.textContent);
                cartCount.textContent = count + 1;
            }
            
            // Show a confirmation message
            alert('Product added to cart!');
        });
    });
});
