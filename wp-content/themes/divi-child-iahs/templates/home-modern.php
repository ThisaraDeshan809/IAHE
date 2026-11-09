<?php
/**
 * Template Name: IAHS Modern Home
 * Description: Modern home page template for IAHS
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
                    <a href="<?php echo esc_url(home_url('/programs-qualifications/')); ?>" class="et_pb_button btn-primary-modern">
                        <span>Explore Programs</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="et_pb_button btn-secondary-modern">
                        <span>Speak to Advisor</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M21 11.5C21 16.75 16.75 21 11.5 21C6.25 21 2 16.75 2 11.5C2 6.25 6.25 2 11.5 2C16.75 2 21 6.25 21 11.5Z" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M15 11.5C15 13.433 13.433 15 11.5 15C9.567 15 8 13.433 8 11.5C8 9.567 9.567 8 11.5 8C13.433 8 15 9.567 15 11.5Z" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M19 19L16.5 16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </a>
                </div>
                <div class="hero-stats animate-fade-in-up">
                    <!-- <div class="stat-item">
                        <span class="stat-number">10,000+</span>
                        <span class="stat-label">Students Empowered</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">50+</span>
                        <span class="stat-label">Countries Worldwide</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">98%</span>
                        <span class="stat-label">Success Rate</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">Student Support</span>
                    </div> -->
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
                    <a href="<?php echo esc_url(home_url('/about-us/')); ?>" class="feature-link">
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
                    <a href="<?php echo esc_url(home_url('/services/')); ?>" class="feature-link">
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
                    <a href="<?php echo esc_url(home_url('/careers/')); ?>" class="feature-link">
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
                    <a href="<?php echo esc_url(home_url('/programs-qualifications/') . '#business'); ?>" class="programme-link">
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
                    <a href="<?php echo esc_url(home_url('/programs-qualifications/') . '#hr'); ?>" class="programme-link">
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
                    <a href="<?php echo esc_url(home_url('/programs-qualifications/') . '#it'); ?>" class="programme-link">
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
                    <a href="<?php echo esc_url(home_url('/programs-qualifications/') . '#education'); ?>" class="programme-link">
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
                    <a href="<?php echo esc_url(home_url('/programs-qualifications/') . '#early-years'); ?>" class="programme-link">
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
                    <a href="<?php echo esc_url(home_url('/programs-qualifications/') . '#sales'); ?>" class="programme-link">
                        Explore Sales Programs
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>

                <!-- Hospitality and Tourism -->
                <div class="programme-card">
                    <div class="programme-image">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/programme-hospitality.jpg" alt="Hospitality and Tourism" loading="lazy">
                    </div>
                    <div class="programme-icon">🏨</div>
                    <h3>Hospitality and Tourism</h3>
                    <p>Hospitality management diplomas from Level 4 to Level 7 PGC, including business management for the hospitality industry.</p>
                    <a href="<?php echo esc_url(home_url('/programs-qualifications/') . '#hospitality'); ?>" class="programme-link">
                        Explore Hospitality Programs
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>

                <!-- Accounting and Finance -->
                <div class="programme-card">
                    <div class="programme-image">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/programme-accounting.jpg" alt="Accounting and Finance" loading="lazy">
                    </div>
                    <div class="programme-icon">💰</div>
                    <h3>Accounting and Finance</h3>
                    <p>Start with the IAHE Level 4 Diploma in Accounting and Finance for foundational knowledge in the field.</p>
                    <a href="<?php echo esc_url(home_url('/programs-qualifications/') . '#accounting'); ?>" class="programme-link">
                        Explore Accounting Programs
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>

                <!-- Supply Chain Management -->
                <div class="programme-card">
                    <div class="programme-image">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/programme-supply-chain.jpg" alt="Supply Chain Management" loading="lazy">
                    </div>
                    <div class="programme-icon">🚚</div>
                    <h3>Supply Chain Management</h3>
                    <p>Dive into logistics and supply chain with Level 4 and Level 7 Diplomas, preparing for advanced roles in the field.</p>
                    <a href="<?php echo esc_url(home_url('/programs-qualifications/') . '#supply-chain'); ?>" class="programme-link">
                        Explore Supply Chain Programs
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>

                <!-- Health and Social Care -->
                <div class="programme-card">
                    <div class="programme-image">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/programme-health.jpg" alt="Health and Social Care" loading="lazy">
                    </div>
                    <div class="programme-icon">🏥</div>
                    <h3>Health and Social Care</h3>
                    <p>Comprehensive care programs from nursing assistant diplomas to Level 7 Diplomas in Health & Social Care Management.</p>
                    <a href="<?php echo esc_url(home_url('/programs-qualifications/') . '#health'); ?>" class="programme-link">
                        Explore Health Programs
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>

                <!-- Psychology -->
                <div class="programme-card">
                    <div class="programme-image">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/programme-psychology.jpg" alt="Psychology" loading="lazy">
                    </div>
                    <div class="programme-icon">🧠</div>
                    <h3>Psychology</h3>
                    <p>Delve into psychology with diplomas ranging from guidance and counselling to Level 7 PGC in Clinical Psychology.</p>
                    <a href="<?php echo esc_url(home_url('/programs-qualifications/') . '#psychology'); ?>" class="programme-link">
                        Explore Psychology Programs
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>

                <!-- Special Education -->
                <div class="programme-card">
                    <div class="programme-image">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/programme-special-education.jpg" alt="Special Education" loading="lazy">
                    </div>
                    <div class="programme-icon">🌟</div>
                    <h3>Special Education</h3>
                    <p>Specialize with diplomas and a Professional Certification in Therapeutic Play Practitioner, focusing on inclusive education from Level 4 to Level 7.</p>
                    <a href="<?php echo esc_url(home_url('/programs-qualifications/') . '#special-education'); ?>" class="programme-link">
                        Explore Special Education
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
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

    <!-- Campus Gallery Section -->
    <section class="et_pb_section gallery-section">
        <div class="container">
            <div class="section-header">
                <span class="section-pre-title">Our Community</span>
                <h2 class="section-title">Global Learning Environment</h2>
                <p class="section-subtitle">
                    Experience our vibrant international community and state-of-the-art learning facilities
                </p>
            </div>
            <div class="gallery-grid">
                <div class="gallery-item large">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/gallery-1.jpg" alt="Campus Life" loading="lazy">
                    <div class="gallery-overlay">
                        <h4>Campus Life</h4>
                        <p>Vibrant international community</p>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/gallery-2.jpg" alt="Online Learning" loading="lazy">
                    <div class="gallery-overlay">
                        <h4>Online Learning</h4>
                        <p>Flexible digital platform</p>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/gallery-3.jpg" alt="Graduation" loading="lazy">
                    <div class="gallery-overlay">
                        <h4>Graduation</h4>
                        <p>Celebrating success</p>
                    </div>
                </div>
                <div class="gallery-item">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/gallery-4.jpg" alt="Student Support" loading="lazy">
                    <div class="gallery-overlay">
                        <h4>Student Support</h4>
                        <p>Dedicated assistance</p>
                    </div>
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
                    <a href="<?php echo esc_url(home_url('/programs-qualifications/')); ?>" class="et_pb_button btn-primary-modern">
                        <span>View All Programs</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="et_pb_button btn-secondary-modern">
                        <span>Get Free Consultation</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M21 11.5C21 16.75 16.75 21 11.5 21C6.25 21 2 16.75 2 11.5C2 6.25 6.25 2 11.5 2C16.75 2 21 6.25 21 11.5Z" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M15 11.5C15 13.433 13.433 15 11.5 15C9.567 15 8 13.433 8 11.5C8 9.567 9.567 8 11.5 8C13.433 8 15 9.567 15 11.5Z" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M19 19L16.5 16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="et_pb_section contact-modern-section">
        <div class="container">
            <div class="contact-grid">
                <div class="contact-info">
                    <div class="contact-header">
                        <h3>Get in Touch</h3>
                        <p>
                            Have questions about our programs? Our dedicated team is here to help you make the right choice for your educational journey.
                        </p>
                    </div>
                    <div class="contact-image">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/contact-office.jpg" alt="IAHS Office" loading="lazy">
                    </div>
                    <ul class="contact-details">
                        <li>
                            <div class="contact-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 21C15.5 17.4 19 14.1764 19 10.2C19 6.22355 15.7764 3 12 3C8.22355 3 5 6.22355 5 10.2C5 14.1764 8.5 17.4 12 21Z" stroke="currentColor" stroke-width="2"/>
                                    <path d="M12 12C13.1046 12 14 11.1046 14 10C14 8.89543 13.1046 8 12 8C10.8954 8 10 8.89543 10 10C10 11.1046 10.8954 12 12 12Z" stroke="currentColor" stroke-width="2"/>
                                </svg>
                            </div>
                            <div>
                                <strong>Address</strong>
                                <p>5B, Central Avenue, St Andrews Business Park<br>London, UK</p>
                            </div>
                        </li>
                        <li>
                            <div class="contact-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M5 4H9L11 9L8.5 10.5C9.57096 12.6715 11.3285 14.429 13.5 15.5L15 13L20 15V19C20 19.5304 19.7893 20.0391 19.4142 20.4142C19.0391 20.7893 18.5304 21 18 21C14.0993 20.763 10.4202 19.1065 7.65683 16.3432C4.8935 13.5798 3.23705 9.90074 3 6C3 5.46957 3.21071 4.96086 3.58579 4.58579C3.96086 4.21071 4.46957 4 5 4Z" stroke="currentColor" stroke-width="2"/>
                                </svg>
                            </div>
                            <div>
                                <strong>Hotline</strong>
                                <p>+44 7888 113999</p>
                            </div>
                        </li>
                        <li>
                            <div class="contact-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="currentColor" stroke-width="2"/>
                                    <path d="M22 6L12 13L2 6" stroke="currentColor" stroke-width="2"/>
                                </svg>
                            </div>
                            <div>
                                <strong>Email</strong>
                                <p>info@iahs.co.uk</p>
                            </div>
                        </li>
                        <li>
                            <div class="contact-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 8V12L15 15" stroke="currentColor" stroke-width="2"/>
                                    <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2"/>
                                </svg>
                            </div>
                            <div>
                                <strong>Response Time</strong>
                                <p>Within 2 business days</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="contact-form-modern">
                    <form id="iahs-contact-form" method="post">
                        <div class="form-group">
                            <label class="form-label" for="name">Full Name *</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email">Email Address *</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="program">Program of Interest</label>
                            <select id="program" name="program" class="form-control">
                                <option value="">Select a program</option>
                                <option value="education">Education & Training</option>
                                <option value="business">Business & Management</option>
                                <option value="health">Health & Social Care</option>
                                <option value="hospitality">Hospitality & Tourism</option>
                                <option value="psychology">Psychology</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="message">Message</label>
                            <textarea id="message" name="message" class="form-control" rows="5" placeholder="Tell us about your educational goals..."></textarea>
                        </div>
                        <button type="submit" class="et_pb_button btn-submit-modern">
                            <span>Send Message</span>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M22 2L11 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M22 2L15 22L11 13L2 9L22 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

</div><!-- #main-content -->

<?php get_footer(); ?>
