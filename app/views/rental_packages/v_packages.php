<?php require_once APPROOT . '/views/inc/Components/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/rental-packages.css">

<div class="packages-container1">
 <!-- Hero Section -->
<section class="pricing-hero">
    <div class="hero-container">
        <div class="hero-content">
            <h1>Rental Service Owner Packages</h1>
            <p>Choose the perfect plan to grow your sports equipment rental business.</p>
            <div class="hero-features">
                <div class="hero-feature">
                    <span class="feature-icon">✅</span>
                    <span>One Time Fixed Fee</span>
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

    <!-- Packages Section -->
    <div class="packages-section">
        <div class="section-header">
            <h2>Select Your Package</h2>
            <p>All packages come with no monthly fees - you only pay commission on successful bookings</p>
        </div>

        <div class="packages-grid">
            <?php foreach($data['packages'] as $key => $package): ?>
            <div class="package-card <?php echo $package['color']; ?> <?php echo $package['popular'] ? 'popular' : ''; ?>">
                <?php if($package['popular']): ?>
                <div class="popular-badge">
                    <span>⭐ MOST POPULAR</span>
                </div>
                <?php endif; ?>

                <div class="package-header">
                    <div class="package-icon"><?php echo $package['icon']; ?></div>
                    <h3 class="package-name"><?php echo $package['name']; ?></h3>
                    <div class="package-price">
                        <span class="price-amount">LKR <?php echo number_format($package['price']); ?></span>
                        <span class="price-duration"><?php echo $package['duration']; ?></span>
                    </div>
                </div>

                <div class="package-body">
                    <div class="package-highlights">
                        <div class="highlight-item">
                            <span class="highlight-icon">🏪</span>
                            <div>
                                <strong><?php echo $package['listings']; ?></strong>
                                <span>Shop Listings</span>
                            </div>
                        </div>
                        <div class="highlight-item">
                            <span class="highlight-icon">📸</span>
                            <div>
                                <strong><?php echo $package['images_per_listing']; ?></strong>
                                <span>Images Each</span>
                            </div>
                        </div>
                    </div>

                    <div class="features-list">
                        <h4>✓ What's Included</h4>
                        <ul>
                            <?php foreach($package['features'] as $feature): ?>
                            <li>
                                <span class="check-icon">✓</span>
                                <?php echo $feature; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>

                        <?php if(!empty($package['not_included'])): ?>
                        <h4 class="not-included-title">✗ Not Included</h4>
                        <ul class="not-included-list">
                            <?php foreach($package['not_included'] as $feature): ?>
                            <li>
                                <span class="cross-icon">✗</span>
                                <?php echo $feature; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="package-footer">
                    <a href="<?php echo URLROOT; ?>/rental_packages/checkout/<?php echo $key; ?>" class="btn-select-package <?php echo $package['popular'] ? 'btn-popular' : ''; ?>" style="display: inline-block; text-align: center; text-decoration: none;">
                        Choose <?php echo $package['name']; ?>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="how-it-works-section">
        <h2>🚀 How It Works</h2>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <div class="step-icon">📝</div>
                <h3>Choose Package</h3>
                <p>Select the package that best fits your business needs and number of locations</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <div class="step-icon">🏪</div>
                <h3>Add Your Shops</h3>
                <p>List your rental shops with images, contact info, amenities, and equipment details</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <div class="step-icon">👥</div>
                <h3>Get Customers</h3>
                <p>Customers find and book your equipment through our platform</p>
            </div>
            <div class="step-card">
                <div class="step-number">4</div>
                <div class="step-icon">💰</div>
                <h3>Earn Revenue</h3>
                <p>Get paid directly - we only charge commission on successful bookings</p>
            </div>
        </div>
    </div>


</div>

<script src="<?php echo URLROOT; ?>/js/rental-packages.js"></script>
<?php require_once APPROOT . '/views/inc/Components/footer.php'; ?>