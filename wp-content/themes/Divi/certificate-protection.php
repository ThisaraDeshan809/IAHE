<?php
// Additional PDF protection measures
function iahs_pdf_protection_headers() {
    if (isset($_GET['student_id'])) {
        // Security headers to prevent embedding in other sites
        header('X-Frame-Options: SAMEORIGIN');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: same-origin');
        
        // Cache control
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
    }
}
add_action('template_redirect', 'iahs_pdf_protection_headers');

// Block direct access to PDF files via .htaccess
function iahs_generate_htaccess_rules() {
    $htaccess_content = "
# Block direct PDF access
<Files \"*.pdf\">
    Order Deny,Allow
    Deny from all
</Files>

# Allow access only through WordPress
<FilesMatch \"^(.+)\.pdf$\">
    SetEnvIf Referer \"" . home_url() . "\" allowed_referer
    Order Deny,Allow
    Deny from all
    Allow from env=allowed_referer
</FilesMatch>
";
    
    // This would need to be manually added to .htaccess
    // For now, we'll handle protection via PHP
}
?>