<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Hero Section -->
<section class="pricing-hero">
    <div class="hero-container">
        <div class="hero-content">
            <h1>Simple, Transparent Pricing</h1>
            <p>Choose the perfect plan for your stadium business. Start free and scale as you grow.</p>
            <div class="hero-features">
                <div class="hero-feature">
                    <span class="feature-icon">✅</span>
                    <span>No Setup Fees</span>
                </div>
                <div class="hero-feature">
                    <span class="feature-icon">✅</span>
                    <span>Cancel Anytime</span>
                </div>
                <div class="hero-feature">
                    <span class="feature-icon">✅</span>
                    <span>24/7 Support</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Cards Section -->
<section class="pricing-section">
    <div class="pricing-container">
        <div class="section-header">
            <h2>Choose Your Plan</h2>
            <p>All plans include our core features. Pay only when you earn.</p>
        </div>
                                                                               
        <!-- Pricing Cards -->
        <div class="pricing-cards">
            <?php foreach ($data['packages'] as $package): ?>
                <div class="pricing-card <?php echo $package->color; ?> <?php echo $package->popular ? 'popular' : ''; ?>">
                    <?php if ($package->popular): ?>
                        <div class="popular-badge">
                            <span>🔥 Most Popular</span>
                        </div>
                    <?php endif; ?>

                    <div class="card-header">
                        <div class="plan-icon"><?php echo $package->icon; ?></div>
                        <h3 class="plan-name"><?php echo $package->name; ?></h3>
                        <p class="plan-description"><?php echo $package->description; ?></p>
                    </div>

                    <div class="card-pricing">
                        <div class="price">
                            <span class="price-amount">LKR <?php echo number_format($package->setup_fee); ?></span>
                            <span class="price-period">one-time setup</span>
                        </div>
                        <div class="commission-info">
                            <span class="commission-rate"><?php echo $package->commission; ?>%</span>
                            <span class="commission-text">commission per booking</span>
                        </div>
                    </div>
                                                            
                    <div class="card-features">
                        <!-- Stadium Listings -->
                        <div class="feature-item">
                            <span class="feature-icon">🏟️</span>
                            <div class="feature-content">
                                <span class="feature-title">Stadium Listings</span>
                                <span class="feature-value">
                                    <?php echo $package->features->stadium_limit === 'unlimited' ? 'Unlimited' : $package->features->stadium_limit . ' stadiums'; ?>
                                </span>
                            </div>
                        </div>

                        <!-- Photos -->
                        <div class="feature-item">
                            <span class="feature-icon">📸</span>
                            <div class="feature-content">
                                <span class="feature-title">Photos per Stadium</span>
                                <span class="feature-value"><?php echo $package->features->photos_per_property; ?> photos</span>
                            </div>
                        </div>

                        <!-- Support -->
                        <div class="feature-item">
                            <span class="feature-icon">🎧</span>
                            <div class="feature-content">
                                <span class="feature-title">Support</span>
                                <span class="feature-value"><?php echo $package->features->support; ?></span>
                            </div>
                        </div>


                    </div>

                    <div class="card-action">
                        <a href="<?php echo URLROOT; ?>/pricing/checkout/<?php echo strtolower($package->name); ?>" class="btn-choose-plan <?php echo $package->color; ?>" style="display: inline-block; text-align: center; text-decoration: none;">
                            Choose <?php echo $package->name; ?> Plan
                        </a>
                        <p class="plan-note">One-time setup: LKR <?php echo number_format($package->setup_fee); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="how-it-works">
    <div class="works-container">
        <div class="section-header">
            <h2>How Our Commission Works</h2>
            <p>You only pay when you earn. We succeed when you succeed.</p>
        </div>

        <div class="works-steps">
            <div class="work-step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h3>Customer Books</h3>
                    <p>Customer finds and books your stadium through our platform</p>
                </div>
            </div>

            <div class="step-arrow">→</div>

            <div class="work-step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h3>Payment Processed</h3>
                    <p>We collect payment securely and hold it during the booking period</p>
                </div>
            </div>

            <div class="step-arrow">→</div>

            <div class="work-step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h3>Commission Deducted</h3>
                    <p>We deduct our commission based on your plan (8%, 12%, or 20%)</p>
                </div>
            </div>

            <div class="step-arrow">→</div>

            <div class="work-step">
                <div class="step-number">4</div>
                <div class="step-content">
                    <h3>You Get Paid</h3>
                    <p>Receive your earnings directly to your bank account weekly</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="pricing-cta">
    <div class="cta-container">
        <div class="cta-content">
            <h2>Ready to Start Earning?</h2>
            <p>Join thousands of stadium owners already earning with BookMyGround</p>
            <div class="cta-buttons">
                <button class="btn-get-started" onclick="getStarted()">Get Started Free</button>
                <button class="btn-contact-sales" onclick="contactSales()">Contact Sales</button>
            </div>
            <p class="cta-note">✅ No credit card required • ✅ Start earning in 24 hours</p>
        </div>
    </div>
</section>

<script>
    function choosePlan(planName) {
        if (confirm(`Start your journey with the ${planName} plan?`)) {
            // Redirect to registration with selected plan
            window.location.href = `<?php echo URLROOT; ?>/register?plan=${planName.toLowerCase()}`;
        }
    }

    function getStarted() {
        window.location.href = '<?php echo URLROOT; ?>/register';
    }

    function contactSales() {
        window.location.href = '<?php echo URLROOT; ?>/contact';
    }

    function toggleFAQ(element) {
        const faqItem = element.parentElement;
        const answer = faqItem.querySelector('.faq-answer');
        const toggle = element.querySelector('.faq-toggle');

        // Close all other FAQs
        document.querySelectorAll('.faq-item').forEach(item => {
            if (item !== faqItem) {
                item.classList.remove('active');
                item.querySelector('.faq-answer').style.display = 'none';
                item.querySelector('.faq-toggle').textContent = '+';
            }
        });

        // Toggle current FAQ
        if (faqItem.classList.contains('active')) {
            faqItem.classList.remove('active');
            answer.style.display = 'none';
            toggle.textContent = '+';
        } else {
            faqItem.classList.add('active');
            answer.style.display = 'block';
            toggle.textContent = '−';
        }
    }

    // Add smooth scroll animations
    document.addEventListener('DOMContentLoaded', function() {
        // Animate pricing cards on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.pricing-card').forEach(card => {
            observer.observe(card);
        });
    });
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>