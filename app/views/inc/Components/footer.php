<?php
// Load admin-editable footer + social settings (safe fallback to defaults)
$footerSettings = [
    'footer_company_name' => 'BOOKMYGROUND',
    'footer_tagline' => 'Defend, take care of the ball, rebound, and play hard.',
    'footer_address' => '4200 Reid Avenue, Colombo 07',
    'footer_phone' => '(071) 111 1111',
    'footer_email' => 'support@bookmyground.lk',
    'social_facebook' => '#',
    'social_instagram' => '#',
    'social_twitter' => '#',
    'social_linkedin' => '#',
    'social_youtube' => '#'
];

try {
    $db = new Database();
    $keys = array_keys($footerSettings);
    $in = "'" . implode("','", $keys) . "'";
    $db->query("SELECT setting_key, setting_value FROM site_settings WHERE setting_key IN ($in)");
    $rows = $db->resultSet();
    foreach ($rows as $row) {
        if (isset($footerSettings[$row->setting_key])) {
            $footerSettings[$row->setting_key] = $row->setting_value;
        }
    }
} catch (Exception $e) {
    // fall back to defaults silently
}
?>

<!-- Footer -->
    <footer class="main-footer">
        <div class="footer-container">
            <!-- Footer Top Section -->
            <div class="footer-top">
                <!-- Company Info -->
                <div class="footer-section company-info">
                    <h3 class="footer-logo"><?php echo htmlspecialchars($footerSettings['footer_company_name']); ?></h3>
                    <p class="footer-tagline"><?php echo htmlspecialchars($footerSettings['footer_tagline']); ?></p>
                    <div class="footer-basketball-icon">
                        <svg width="120" height="120" viewBox="0 0 120 120" class="basketball-svg">
                            <circle cx="60" cy="60" r="58" fill="none" stroke="#333" stroke-width="2"/>
                            <path d="M2 60 Q60 20, 118 60" fill="none" stroke="#333" stroke-width="2"/>
                            <path d="M2 60 Q60 100, 118 60" fill="none" stroke="#333" stroke-width="2"/>
                            <line x1="60" y1="2" x2="60" y2="118" stroke="#333" stroke-width="2"/>
                        </svg>
                    </div>
                </div>

                <!-- Quick Menu -->
                <div class="footer-section">
                    <h4>Quick Menu</h4>
                    <ul class="footer-links">
                        <li><a href="http://localhost/bookmygroundlk/">Home</a></li>
                        <li><a href="http://localhost/bookmygroundlk/stadiums">Stadiums</a></li>
                        <li><a href="http://localhost/bookmygroundlk/coach">Coaches</a></li>
                        <li><a href="http://localhost/bookmygroundlk/rental">Rental Services</a></li>
                        <li><a href="http://localhost/bookmygroundlk/faq">FAQ</a></li>
                        <li><a href="http://localhost/bookmygroundlk/contact">Contact</a></li>
                    </ul>
                </div>

                <!-- Help Section -->
                <div class="footer-section">
                    <h4>Pricing</h4>
                    <ul class="footer-links">
                        <li><a href="http://localhost/bookmygroundlk/pricing">Stadium Owners</a></li>
                        <li><a href="http://localhost/bookmygroundlk/rental_packages">Rental Service Owners</a></li>
                        <li><a href="http://localhost/bookmygroundlk/advertisement">For Advertisement Publishing</a></li>
                    </ul>
                </div>

                <!-- Company Section -->
                <div class="footer-section">
                    <h4>Connect With Us As:</h4>
                    <ul class="footer-links">
                        <li><a href="http://localhost/bookmygroundlk/register/customer">Player</a></li>
                        <li><a href="http://localhost/bookmygroundlk/register/stadium_owner">Stadium Owner</a></li>
                        <li><a href="http://localhost/bookmygroundlk/register/rental_owner">Sports Gear Rental Service Owner</a></li>
                        <li><a href="http://localhost/bookmygroundlk/register/coach">Sports Coach</a></li>
                    </ul>
                </div>

                <!-- Contact Section -->
                <div class="footer-section">
                    <h4>Contact</h4>
                    <div class="contact-info">
                        <p class="contact-item"><?php echo htmlspecialchars($footerSettings['footer_address']); ?></p>
                        <p class="contact-item">Phone: <?php echo htmlspecialchars($footerSettings['footer_phone']); ?></p>
                        <p class="contact-item">Email: <?php echo htmlspecialchars($footerSettings['footer_email']); ?></p>
                    </div>
                    
                    <!-- Social Media Icons -->
                    <div class="social-media">
                        <a href="<?php echo htmlspecialchars($footerSettings['social_facebook']); ?>" class="social-link" target="_blank" rel="noopener">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2.04c-5.5 0-9.96 4.46-9.96 9.96 0 5.5 4.46 9.96 9.96 9.96 5.5 0 9.96-4.46 9.96-9.96 0-5.5-4.46-9.96-9.96-9.96zm4.43 7.5c-.18 2.8-.93 5.33-2.18 7.38-1.25-2.05-2-4.58-2.18-7.38h4.36z"/>
                            </svg>
                        </a>
                        <a href="<?php echo htmlspecialchars($footerSettings['social_linkedin']); ?>" class="social-link" target="_blank" rel="noopener">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.47 2H3.53c-.83 0-1.53.7-1.53 1.53v16.94c0 .83.7 1.53 1.53 1.53h16.94c.83 0 1.53-.7 1.53-1.53V3.53c0-.83-.7-1.53-1.53-1.53zM8.11 18.53h-3V8.53h3v10zm-1.5-11.4c-.97 0-1.75-.79-1.75-1.75s.78-1.75 1.75-1.75 1.75.79 1.75 1.75-.78 1.75-1.75 1.75zm13.42 11.4h-3v-5.57c0-1.33-.48-2.24-1.68-2.24-.92 0-1.46.62-1.7 1.22-.09.21-.11.5-.11.79v5.8h-3V8.53h3v1.43c.4-.61 1.11-1.48 2.7-1.48 1.97 0 3.45 1.29 3.45 4.06v5.99z"/>
                            </svg>
                        </a>
                        <a href="<?php echo htmlspecialchars($footerSettings['social_twitter']); ?>" class="social-link" target="_blank" rel="noopener">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 4.37a9.6 9.6 0 0 1-2.83.8 5.04 5.04 0 0 0 2.17-2.8c-.95.58-2 1-3.13 1.22A4.86 4.86 0 0 0 16.61 2a4.99 4.99 0 0 0-4.79 6.2A13.87 13.87 0 0 1 1.67 2.92 5.12 5.12 0 0 0 3.2 9.67a4.82 4.82 0 0 1-2.23-.64v.07c0 2.44 1.7 4.48 3.95 4.95a4.84 4.84 0 0 1-2.22.08c.63 2.01 2.45 3.47 4.6 3.51A9.72 9.72 0 0 1 0 19.74 13.68 13.68 0 0 0 7.55 22c9.06 0 14-7.7 14-14.37v-.65c.96-.71 1.79-1.6 2.45-2.61z"/>
                            </svg>
                        </a>
                        <a href="<?php echo htmlspecialchars($footerSettings['social_youtube']); ?>" class="social-link" target="_blank" rel="noopener">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33zM9.75 15.02l5.75-3.27-5.75-3.27v6.54z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <p class="copyright">2025 © BookMyGround. All Rights Reserved.</p>
                    <div class="footer-legal">
                        <a href="<?php echo URLROOT; ?>/privacy">Privacy & Policy</a>
                        <a href="<?php echo URLROOT; ?>/terms">Terms & Condition</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript for Mobile Menu -->
    <script>
        // Mobile menu toggle
        const hamburger = document.querySelector('.hamburger');
        const navMenu = document.querySelector('.nav-menu');
        
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        // Close mobile menu when clicking on a link
        document.querySelectorAll('.nav-link').forEach(n => n.addEventListener('click', () => {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
        }));
    </script>
</body>
</html>