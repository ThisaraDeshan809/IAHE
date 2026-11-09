(function($) {
    'use strict';

    class IAHSModern {
        constructor() {
            this.currentSlide = 0;
            this.slideInterval = null;
            this.init();
        }

        init() {
            this.smoothScroll();
            this.animateOnScroll();
            this.contactForm();
            this.statsCounter();
            this.navigation();
            this.initializeAnimations();
            this.parallaxEffects();
            this.formEnhancements();
            this.initCarousel();
            this.galleryLightbox();
        }

        smoothScroll() {
            // Smooth scrolling for anchor links
            $('a[href^="#"]').on('click', function(e) {
                if (this.getAttribute('href') === '#') return;
                
                e.preventDefault();
                const target = $(this.getAttribute('href'));
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 1000, 'easeInOutCubic');
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
                $('.feature-card, .programme-card, .testimonial-card, .recognition-feature, .gallery-item').each(function(index) {
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

            // Pause on hover
            $('.hero-image-carousel').on('mouseenter', () => {
                clearInterval(this.slideInterval);
            }).on('mouseleave', () => {
                this.startCarousel();
            });

            // Touch swipe support
            this.initTouchSwipe();
        }

        initTouchSwipe() {
            let startX = 0;
            let endX = 0;
            const carousel = $('.hero-image-carousel')[0];

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

        galleryLightbox() {
            // Simple lightbox functionality
            $('.gallery-item').on('click', function() {
                const imgSrc = $(this).find('img').attr('src');
                const imgAlt = $(this).find('img').attr('alt');
                
                // Create lightbox
                const lightbox = $(`
                    <div class="lightbox-overlay">
                        <div class="lightbox-content">
                            <img src="${imgSrc}" alt="${imgAlt}">
                            <button class="lightbox-close">&times;</button>
                        </div>
                    </div>
                `);
                
                $('body').append(lightbox);
                
                // Close lightbox
                lightbox.on('click', function(e) {
                    if (e.target === this || $(e.target).hasClass('lightbox-close')) {
                        $(this).remove();
                    }
                });
                
                // ESC key to close
                $(document).on('keyup', function(e) {
                    if (e.key === 'Escape') {
                        $('.lightbox-overlay').remove();
                    }
                });
            });
        }

        contactForm() {
            $('#iahs-contact-form').on('submit', (e) => {
                e.preventDefault();
                
                const form = $(e.target);
                const submitBtn = form.find('.btn-submit-modern');
                const formData = new FormData(form[0]);
                
                // Add nonce
                formData.append('nonce', iahs_ajax.nonce);
                formData.append('action', 'iahs_contact_form');
                
                // Validate form
                if (!this.validateForm(form)) {
                    return;
                }
                
                // Show loading state
                submitBtn.addClass('btn-loading loading');
                submitBtn.find('span').text('Sending...');
                
                $.ajax({
                    url: iahs_ajax.ajax_url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: (response) => {
                        if (response.success) {
                            this.showMessage('success', response.data.message);
                            form[0].reset();
                            this.animateSuccess(form);
                        } else {
                            this.showMessage('error', 'Something went wrong. Please try again.');
                        }
                    },
                    error: () => {
                        this.showMessage('error', 'Network error. Please try again.');
                    },
                    complete: () => {
                        submitBtn.removeClass('btn-loading loading');
                        submitBtn.find('span').text('Send Message');
                    }
                });
            });
        }

        validateForm(form) {
            let isValid = true;
            const inputs = form.find('input[required], select[required], textarea[required]');
            
            inputs.each(function() {
                const input = $(this);
                if (!input.val().trim()) {
                    input.addClass('error');
                    this.showMessage('error', 'Please fill in all required fields.');
                    isValid = false;
                } else {
                    input.removeClass('error');
                }
                
                // Email validation
                if (input.attr('type') === 'email' && input.val().trim()) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(input.val())) {
                        input.addClass('error');
                        this.showMessage('error', 'Please enter a valid email address.');
                        isValid = false;
                    }
                }
            });
            
            return isValid;
        }

        animateSuccess(form) {
            form.addClass('success');
            setTimeout(() => {
                form.removeClass('success');
            }, 3000);
        }

        showMessage(type, message) {
            // Remove existing messages
            $('.message-notification').remove();
            
            const messageEl = $(`<div class="message-notification message-${type}">${message}</div>`);
            $('body').append(messageEl);
            
            // Style the message
            messageEl.css({
                'position': 'fixed',
                'top': '30px',
                'right': '30px',
                'padding': '16px 24px',
                'border-radius': '12px',
                'color': '#fff',
                'font-weight': '600',
                'z-index': '10000',
                'max-width': '400px',
                'box-shadow': 'var(--shadow-xl)',
                'backdrop-filter': 'blur(20px)',
                'border': '1px solid rgba(255,255,255,0.2)',
                'transform': 'translateX(100%)',
                'transition': 'transform 0.5s cubic-bezier(0.4, 0, 0.2, 1)'
            });
            
            if (type === 'success') {
                messageEl.css('background', 'linear-gradient(135deg, var(--success-color), #059669)');
            } else {
                messageEl.css('background', 'linear-gradient(135deg, var(--error-color), #dc2626)');
            }
            
            // Animate in
            setTimeout(() => {
                messageEl.css('transform', 'translateX(0)');
            }, 100);
            
            // Animate out and remove
            setTimeout(() => {
                messageEl.css('transform', 'translateX(100%)');
                setTimeout(() => messageEl.remove(), 500);
            }, 5000);
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
            const duration = 2500;
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
            $(window).on('scroll', () => {
                const scrolled = $(window).scrollTop();
                $('.hero-pattern').css('transform', `translateY(${scrolled * 0.5}px)`);
            });
        }

        formEnhancements() {
            // Add focus effects to form elements
            $('.form-control').on('focus', function() {
                $(this).parent().addClass('focused');
            }).on('blur', function() {
                if (!$(this).val()) {
                    $(this).parent().removeClass('focused');
                }
            });

            // Real-time validation
            $('.form-control').on('input', function() {
                const $this = $(this);
                if ($this.val().trim()) {
                    $this.removeClass('error');
                }
            });
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
            gsap.utils.toArray('.feature-card, .programme-card, .testimonial-card, .recognition-feature, .gallery-item').forEach((card, i) => {
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

    // Initialize when document is ready
    $(document).ready(() => {
        new IAHSModern();
    });

    // Initialize when Divi page is fully loaded
    $(window).on('et_pb_after_init_modules', () => {
        setTimeout(() => {
            new IAHSModern();
        }, 100);
    });

    // Handle page transitions
    $(window).on('beforeunload', () => {
        $('body').addClass('page-transition');
    });

})(jQuery);