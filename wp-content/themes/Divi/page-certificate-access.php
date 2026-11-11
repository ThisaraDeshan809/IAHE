<?php
/**
 * Template Name: Certificate Access - Mobile Responsive
 */
get_header(); ?>

<div id="main-content">
    <div class="container">
        <div id="content-area" class="clearfix">
            <div class="certificate-access-page">
                <!-- Mobile Header Section -->
                <div class="mobile-header-section">
                    <div class="mobile-header-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                    </div>
                    <h1>Certificate Verification</h1>
                    <p class="page-subtitle">Verify your e-certificate securely</p>
                </div>
                
                <!-- Main Content Section -->
                <div class="certificate-content-grid">
                    
                    <!-- Right Column - Search Form -->
                    <div class="form-column">
                        <div class="certificate-search-box">
                            <div class="form-header">
                                <h2>Verify Certificate</h2>
                                <p>Enter your Student ID to access your certificate</p>
                            </div>
                            
                            <form method="get" class="certificate-access-form">
                                <div class="form-group">
                                    <label for="student_id">Student ID</label>
                                    <input type="text" id="student_id" name="student_id" 
                                           placeholder="e.g., STU12345" required
                                           value="<?php echo isset($_GET['student_id']) ? esc_attr($_GET['student_id']) : ''; ?>">
                                    <div class="input-hint">Enter your unique Student ID provided in your certificate</div>
                                </div>
                                <button type="submit" class="btn-primary">
                                    <span class="btn-text">View Certificate</span>
                                    <span class="btn-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                </button>
                            </form>
                            
                            <div class="form-footer">
                                <p>Forgot your Student ID? <a href="#help" class="help-link">Get Help</a></p>
                            </div>
                        </div>
                    </div>
                                        <!-- Left Column - Instructions -->
                    <div class="instructions-column">
                        
                    </div>
                </div>
                
                <!-- Error Message Section -->
                <?php 
                if (isset($_GET['error']) && $_GET['error'] == 'not_found') {
                    $student_id = get_transient('certificate_error');
                    delete_transient('certificate_error');
                    ?>
                    <div class="error-message">
                        <div class="error-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                <line x1="9" y1="9" x2="15" y2="15"></line>
                            </svg>
                        </div>
                        <div class="error-content">
                            <h3>Certificate Not Found</h3>
                            <p>No certificate found for Student ID: <strong><?php echo esc_html($student_id); ?></strong></p>
                            <p>Please check your Student ID and try again. If the problem persists, contact our support team.</p>
                        </div>
                        <button class="error-close" onclick="this.parentElement.style.display='none'">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <?php
                }
                ?>
                
                <!-- Help Section -->
                <div class="help-section" id="help">
                    <div class="help-header">
                        <h2>Need Assistance?</h2>
                        <p>We're here to help you access your certificate</p>
                    </div>
                    
                    <div class="help-content-grid">
                        <div class="help-contact-info">
                            <h3>Contact Support</h3>
                            <div class="contact-methods">
                                <div class="contact-method">
                                    <div class="contact-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                            <polyline points="22,6 12,13 2,6"></polyline>
                                        </svg>
                                    </div>
                                    <div class="contact-details">
                                        <strong>Email</strong>
                                        <span style="font-size:12px;">verify@iahsaward.org.uk</span>
                                    </div>
                                </div>
                                <div class="contact-method">
                                    <div class="contact-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                        </svg>
                                    </div>
                                    <div class="contact-details">
                                        <strong>Phone</strong>
                                        <p>+44 7888 113999</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>