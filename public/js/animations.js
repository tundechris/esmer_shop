/**
 * D2E SHOP - Scroll Reveal Animations
 * Subtle but alive animations for a better user experience
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAnimations);
    } else {
        initAnimations();
    }

    function initAnimations() {
        // Scroll Reveal Observer
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe all elements with scroll-reveal class
        const revealElements = document.querySelectorAll('.scroll-reveal');
        revealElements.forEach(element => {
            observer.observe(element);
        });

        // Add smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && document.querySelector(href)) {
                    e.preventDefault();
                    document.querySelector(href).scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add ripple effect to buttons
        document.querySelectorAll('.btn-apple').forEach(button => {
            button.addEventListener('click', createRipple);
        });

        // Parallax effect on hero section (very subtle)
        const hero = document.querySelector('.hero-apple');
        if (hero) {
            let ticking = false;
            window.addEventListener('scroll', () => {
                if (!ticking) {
                    window.requestAnimationFrame(() => {
                        const scrolled = window.pageYOffset;
                        const rate = scrolled * 0.2;
                        hero.style.transform = `translateY(${rate}px)`;
                        ticking = false;
                    });
                    ticking = true;
                }
            });
        }

        // Navbar hide/show on scroll
        let lastScroll = 0;
        let ticking = false;
        const navbar = document.querySelector('.navbar-apple');

        if (navbar) {
            window.addEventListener('scroll', () => {
                if (!ticking) {
                    window.requestAnimationFrame(() => {
                        const currentScroll = window.pageYOffset;

                        if (currentScroll <= 0) {
                            navbar.style.transform = 'translateY(0)';
                        } else if (currentScroll > lastScroll && currentScroll > 100) {
                            // Scrolling down
                            navbar.style.transform = 'translateY(-100%)';
                        } else if (currentScroll < lastScroll) {
                            // Scrolling up
                            navbar.style.transform = 'translateY(0)';
                        }

                        lastScroll = currentScroll;
                        ticking = false;
                    });
                    ticking = true;
                }
            });
        }

        // Add loading animation for images
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            if (img.complete) {
                img.style.opacity = '1';
            } else {
                img.style.opacity = '0';
                img.addEventListener('load', function() {
                    this.style.transition = 'opacity 0.4s ease-out';
                    this.style.opacity = '1';
                });
            }
        });
    }

    // Ripple effect function
    function createRipple(e) {
        const button = e.currentTarget;
        const ripple = document.createElement('span');
        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;

        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple');

        button.appendChild(ripple);

        setTimeout(() => ripple.remove(), 600);
    }
})();

// Add ripple CSS dynamically
const style = document.createElement('style');
style.textContent = `
    .btn-apple {
        position: relative;
        overflow: hidden;
    }

    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }

    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
