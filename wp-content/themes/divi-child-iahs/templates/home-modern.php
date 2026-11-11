<?php
/**
 * Template Name: IAHS Modern Home
 * Description: Modern home page template for IAHS - Mobile Responsive
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div id="main-content" class="iahs-modern-home">

    <!-- Modern Hero Section -->
    <section class="et_pb_section modern-hero-section">
        <div class="hero-background">
            <div class="hero-pattern"></div>
            <div class="hero-gradient"></div>
            <!-- Hero Background Image -->
            <div class="hero-bg-image" style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/images/hero-bg.jpg')"></div>
        </div>
        <div class="container">
            <div class="hero-content-wrapper">
                <div class="hero-badge animate-fade-in-up">
                    🎓 Internationally Recognized Qualifications
                </div>
                <h1 class="hero-title animate-fade-in-up">
                    Transform Your Future with <span class="gradient-text">Global Education</span>
                </h1>
                <p class="hero-subtitle animate-fade-in-up">
                    Join 10,000+ students worldwide in achieving academic excellence with our UK-accredited programs.
                    Flexible learning, career-focused courses, and internationally recognized qualifications.
                </p>
                <div class="hero-buttons animate-fade-in-up">
                    <a href="/programs-qualifications" class="et_pb_button btn-primary-modern">
                        <span>Explore Programs</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <a href="/contact" class="et_pb_button btn-secondary-modern">
                        <span>Speak to Advisor</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M21 11.5C21 16.75 16.75 21 11.5 21C6.25 21 2 16.75 2 11.5C2 6.25 6.25 2 11.5 2C16.75 2 21 6.25 21 11.5Z" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M15 11.5C15 13.433 13.433 15 11.5 15C9.567 15 8 13.433 8 11.5C8 9.567 9.567 8 11.5 8C13.433 8 15 9.567 15 11.5Z" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M19 19L16.5 16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Hero Image Slider/Carousel -->
        <div class="hero-image-carousel">
            <div class="carousel-track">
                <div class="carousel-slide active">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/hero-slide-1.jpg" alt="Students Learning" loading="lazy">
                </div>
                <div class="carousel-slide">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/hero-slide-2.jpg" alt="Graduation Ceremony" loading="lazy">
                </div>
                <div class="carousel-slide">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/hero-slide-3.jpg" alt="Online Learning" loading="lazy">
                </div>
            </div>
            <div class="carousel-dots">
                <span class="dot active" data-slide="0"></span>
                <span class="dot" data-slide="1"></span>
                <span class="dot" data-slide="2"></span>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="et_pb_section features-section">
        <div class="container">
            <div class="section-header">
                <span class="section-pre-title">Why Choose IAHS</span>
                <h2 class="section-title">Your Pathway to Academic Excellence</h2>
                <p class="section-subtitle">
                    Discover why thousands of students choose IAHS for their educational journey
                </p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-image">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/feature-global.jpg" alt="Global Recognition" loading="lazy">
                    </div>
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon">🌍</div>
                        <div class="feature-bg"></div>
                    </div>
                    <h3>Globally Recognized</h3>
                    <p>Our qualifications are respected by employers and institutions worldwide, opening doors to international opportunities.</p>
                    <a href="/about-us" class="feature-link">
                        Learn More
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
                <div class="feature-card">
                    <div class="feature-image">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/feature-flexible.jpg" alt="Flexible Learning" loading="lazy">
                    </div>
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon">⚡</div>
                        <div class="feature-bg"></div>
                    </div>
                    <h3>Flexible Learning</h3>
                    <p>Study at your own pace with our innovative online and blended programs designed for modern learners.</p>
                    <a href="/services" class="feature-link">
                        View Options
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
                <div class="feature-card">
                    <div class="feature-image">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/feature-career.jpg" alt="Career Focused" loading="lazy">
                    </div>
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon">🎯</div>
                        <div class="feature-bg"></div>
                    </div>
                    <h3>Career Focused</h3>
                    <p>Gain practical skills and knowledge that employers value, with programs aligned with industry needs.</p>
                    <a href="/careers" class="feature-link">
                        Explore Careers
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Educational Programmes Section -->
    <section class="et_pb_section programmes-section">
        <div class="container">
            <div class="section-header">
                <span class="section-pre-title">Our Educational Programmes</span>
                <h2 class="section-title">Comprehensive Learning Pathways</h2>
                <p class="section-subtitle">
                    Discover our diverse range of internationally recognized qualifications designed to advance your career
                </p>
            </div>
            <div class="programmes-grid">
                <!-- Business Management -->
                <div class="programme-card">
                    <div class="programme-image">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/programme-business.jpg" alt="Business Management" loading="lazy">
                    </div>
                    <div class="programme-icon">💼</div>
                    <h3>Business Management</h3>
                    <p>From Level 4 Diploma in Business Management to Level 7 in Leadership and Strategic Management, develop critical business and leadership skills.</p>
                    <a href="/programs-qualifications/#business" class="programme-link">
                        Explore Business Programs
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>

                <!-- Human Resource Management -->
                <div class="programme-card">
                    <div class="programme-image">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/programme-hr.jpg" alt="Human Resource Management" loading="lazy">
                    </div>
                    <div class="programme-icon">👥</div>
                    <h3>Human Resource Management</h3>
                    <p>Build a career in HR from the ground up with diplomas leading to a Level 7 Postgraduate Certificate in Human Resources Management.</p>
                    <a href="/programs-qualifications/#hr" class="programme-link">
                        Explore HR Programs
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>

                <!-- Information Technology -->
                <div class="programme-card">
                    <div class="programme-image">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/programme-it.jpg" alt="Information Technology" loading="lazy">
                    </div>
                    <div class="programme-icon">💻</div>
                    <h3>Information Technology</h3>
                    <p>Comprehensive IT programs including Cyber Security, Web Design, Networking, and E-commerce from Level 3 to Level 4 Diplomas.</p>
                    <a href="/programs-qualifications/#it" class="programme-link">
                        Explore IT Programs
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>

                <!-- Teaching and Education -->
                <div class="programme-card">
                    <div class="programme-image">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/programme-education.jpg" alt="Teaching and Education" loading="lazy">
                    </div>
                    <div class="programme-icon">📚</div>
                    <h3>Teaching and Education</h3>
                    <p>Comprehensive programs from IAHE Level 4 Diplomas in Pre School Management to Level 7 Postgraduate Certificates in Education and Leadership.</p>
                    <a href="/programs-qualifications/#education" class="programme-link">
                        Explore Education Programs
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>

                <!-- Early Years Education -->
                <div class="programme-card">
                    <div class="programme-image">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/programme-early-years.jpg" alt="Early Years Education" loading="lazy">
                    </div>
                    <div class="programme-icon">👶</div>
                    <h3>Early Years Education</h3>
                    <p>Specialize in early childhood education with programs from Early Years Foundation Stage teaching to postgraduate certificates in Early Childhood Education.</p>
                    <a href="/programs-qualifications/#early-years" class="programme-link">
                        Explore Early Years Programs
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>

                <!-- Sales and Marketing -->
                <div class="programme-card">
                    <div class="programme-image">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/programme-sales.jpg" alt="Sales and Marketing" loading="lazy">
                    </div>
                    <div class="programme-icon">📊</div>
                    <h3>Sales and Marketing</h3>
                    <p>Enhance sales and marketing prowess with a pathway from Level 4 Diploma to Level 7 PGC in Sales and Marketing Management.</p>
                    <a href="/programs-qualifications/#sales" class="programme-link">
                        Explore Sales Programs
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="mobile-view-all">
                <a href="/programs-qualifications" class="et_pb_button btn-primary-modern">
                    <span>View All Programs</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Global Recognition Section -->
    <section class="et_pb_section recognition-section">
        <div class="container">
            <div class="recognition-grid">
                <div class="recognition-content">
                    <span class="section-pre-title">Global Recognition</span>
                    <h2 class="section-title">Internationally Accredited Qualifications</h2>
                    <p class="section-subtitle">
                        Our programs are recognized by employers and educational institutions worldwide, ensuring your qualifications open doors globally.
                    </p>
                    <div class="recognition-features">
                        <div class="recognition-feature">
                            <div class="feature-icon">🌎</div>
                            <div>
                                <h4>Worldwide Acceptance</h4>
                                <p>Recognized in 50+ countries across the globe</p>
                            </div>
                        </div>
                        <div class="recognition-feature">
                            <div class="feature-icon">🏆</div>
                            <div>
                                <h4>Quality Assured</h4>
                                <p>UK-accredited programs meeting international standards</p>
                            </div>
                        </div>
                        <div class="recognition-feature">
                            <div class="feature-icon">💼</div>
                            <div>
                                <h4>Career Advancement</h4>
                                <p>Boost your career with internationally recognized credentials</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="recognition-image">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/global-recognition.jpg" alt="Global Recognition" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="et_pb_section cta-section">
        <div class="cta-background" style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/images/cta-bg.jpg')"></div>
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">Ready to Start Your Journey?</h2>
                <p class="cta-description">
                    Join thousands of successful students who have transformed their careers with IAHS. Take the first step towards your educational goals today.
                </p>
                <div class="cta-buttons">
                    <a href="/programs-qualifications" class="et_pb_button btn-primary-modern">
                        <span>View All Programs</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

</div><!-- #main-content -->

<?php get_footer(); ?>