(function($) {
    'use strict';

    class IAHSModernMobile {
        constructor() {
            this.currentSlide = 0;
            this.slideInterval = null;
            this.isMobile = window.innerWidth < 768;
            this.init();
        }

        init() {
            this.smoothScroll();
            this.animateOnScroll();
            this.initCarousel();
            this.statsCounter();
            this.navigation();
            this.initializeAnimations();
            this.parallaxEffects();
            this.handleResize();
        }

        handleResize() {
            $(window).on('resize', () => {
                this.isMobile = window.innerWidth < 768;
                this.adjustLayoutForMobile();
            });
        }

        adjustLayoutForMobile() {
            // Adjust carousel for mobile
            if (this.isMobile) {
                $('.carousel-dots').css('bottom', '30px');
            } else {
                $('.carousel-dots').css('bottom', '40px');
            }
        }

        smoothScroll() {
            // Smooth scrolling for anchor links
            $('a[href^="#"]').on('click', function(e) {
                if (this.getAttribute('href') === '#') return;
                
                e.preventDefault();
                const target = $(this.getAttribute('href'));
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 80
                    }, 800, 'easeInOutCubic');
                }
            });
        }

        animateOnScroll() {
            // Intersection Observer for animations
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('animate-fade-in-up');
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                });

                // Observe elements with delay
                $('.feature-card, .programme-card, .recognition-feature').each(function(index) {
                    $(this).css('animation-delay', `${index * 0.1}s`);
                    observer.observe(this);
                });
            }
        }

        initCarousel() {
            const track = $('.carousel-track');
            const slides = $('.carousel-slide');
            const dots = $('.dot');
            const totalSlides = slides.length;

            // Auto slide function
            this.startCarousel = () => {
                this.slideInterval = setInterval(() => {
                    this.nextSlide();
                }, 5000);
            };

            // Next slide function
            this.nextSlide = () => {
                this.currentSlide = (this.currentSlide + 1) % totalSlides;
                this.updateCarousel();
            };

            // Update carousel position
            this.updateCarousel = () => {
                const translateX = -this.currentSlide * 33.333;
                track.css('transform', `translateX(${translateX}%)`);
                
                // Update dots
                dots.removeClass('active');
                dots.eq(this.currentSlide).addClass('active');
            };

            // Dot click handler
            dots.on('click', (e) => {
                const slideIndex = parseInt($(e.target).data('slide'));
                this.currentSlide = slideIndex;
                this.updateCarousel();
                this.resetInterval();
            });

            // Start the carousel
            this.startCarousel();

            // Pause on hover (desktop only)
            if (!this.isMobile) {
                $('.hero-image-carousel').on('mouseenter', () => {
                    clearInterval(this.slideInterval);
                }).on('mouseleave', () => {
                    this.startCarousel();
                });
            }

            // Touch swipe support for mobile
            this.initTouchSwipe();
        }

        initTouchSwipe() {
            let startX = 0;
            let endX = 0;
            const carousel = $('.hero-image-carousel')[0];

            if (!carousel) return;

            carousel.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
            });

            carousel.addEventListener('touchend', (e) => {
                endX = e.changedTouches[0].clientX;
                this.handleSwipe(startX, endX);
            });
        }

        handleSwipe(startX, endX) {
            const swipeThreshold = 50;
            
            if (startX - endX > swipeThreshold) {
                // Swipe left - next slide
                this.nextSlide();
            } else if (endX - startX > swipeThreshold) {
                // Swipe right - previous slide
                this.currentSlide = (this.currentSlide - 1 + $('.carousel-slide').length) % $('.carousel-slide').length;
                this.updateCarousel();
            }
            this.resetInterval();
        }

        resetInterval() {
            clearInterval(this.slideInterval);
            this.startCarousel();
        }

        statsCounter() {
            // Animate statistics counters
            if ('IntersectionObserver' in window) {
                const statsObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            this.animateNumbers(entry.target);
                            statsObserver.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.5 });
                
                $('.stat-number').each(function() {
                    statsObserver.observe(this);
                });
            }
        }

        animateNumbers(element) {
            const $el = $(element);
            const finalValue = this.parseNumber($el.text());
            const duration = 2000;
            const startTime = performance.now();
            
            const animate = (currentTime) => {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // Easing function
                const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                const current = Math.floor(finalValue * easeOutQuart);
                
                $el.text(this.formatNumber(current));
                
                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    $el.text(this.formatNumber(finalValue));
                }
            };
            
            requestAnimationFrame(animate);
        }

        parseNumber(text) {
            return parseInt(text.replace(/[^0-9]/g, ''));
        }

        formatNumber(num) {
            if (num >= 1000) {
                return (num / 1000).toFixed(0) + ',000+';
            }
            return num.toString();
        }

        navigation() {
            // Mobile menu toggle
            $('.mobile-menu-toggle').on('click', function() {
                $('.mobile-nav').toggleClass('active');
                $('body').toggleClass('menu-open');
            });

            // Sticky header with throttle
            let scrollTimer;
            $(window).on('scroll', () => {
                if (!scrollTimer) {
                    scrollTimer = setTimeout(() => {
                        const scroll = $(window).scrollTop();
                        if (scroll > 100) {
                            $('.main-header').addClass('sticky');
                        } else {
                            $('.main-header').removeClass('sticky');
                        }
                        scrollTimer = null;
                    }, 10);
                }
            });
        }

        parallaxEffects() {
            // Simple parallax for hero section
            if (!this.isMobile) {
                $(window).on('scroll', () => {
                    const scrolled = $(window).scrollTop();
                    $('.hero-pattern').css('transform', `translateY(${scrolled * 0.5}px)`);
                });
            }
        }

        initializeAnimations() {
            // Initialize GSAP animations if available
            if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
                this.initGSAPAnimations();
            }
        }

        initGSAPAnimations() {
            // Hero section animations
            gsap.from('.hero-badge', {
                duration: 1.2,
                y: -60,
                opacity: 0,
                ease: "power3.out",
                delay: 0.2
            });

            gsap.from('.hero-title', {
                duration: 1.4,
                y: 80,
                opacity: 0,
                delay: 0.4,
                ease: "power3.out"
            });

            gsap.from('.hero-subtitle', {
                duration: 1.4,
                y: 40,
                opacity: 0,
                delay: 0.6,
                ease: "power3.out"
            });

            gsap.from('.hero-buttons', {
                duration: 1.2,
                y: 40,
                opacity: 0,
                delay: 0.8,
                ease: "power3.out",
                stagger: 0.2
            });

            gsap.from('.stat-item', {
                duration: 1.2,
                y: 40,
                opacity: 0,
                delay: 1,
                ease: "power3.out",
                stagger: 0.15
            });

            // Section animations with scroll trigger
            if (!this.isMobile) {
                gsap.utils.toArray('.feature-card, .programme-card, .recognition-feature').forEach((card, i) => {
                    gsap.from(card, {
                        scrollTrigger: {
                            trigger: card,
                            start: "top 80%",
                            end: "bottom 20%",
                            toggleActions: "play none none reverse"
                        },
                        y: 60,
                        opacity: 0,
                        duration: 1,
                        delay: i * 0.1,
                        ease: "power3.out"
                    });
                });
            }
        }
    }

    // Initialize when document is ready
    $(document).ready(() => {
        new IAHSModernMobile();
    });

    // Initialize when Divi page is fully loaded
    $(window).on('et_pb_after_init_modules', () => {
        setTimeout(() => {
            new IAHSModernMobile();
        }, 100);
    });

})(jQuery);