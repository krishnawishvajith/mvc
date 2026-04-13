<?php require APPROOT.'/views/rentalowner/inc/header.php'; ?>
<div class="kala-rental-dash-main-content">
    <!-- Dashboard Header -->
    <div class="kala-rental-dash-dashboard-header">
        <h1>Dashboard Overview</h1>
        <div class="kala-rental-dash-date-range">
            <span>📅 <?php echo date('F j, Y'); ?></span>
        </div>
    </div>

    <!-- Enhanced Package Details Card -->
    <?php if (isset($data['package_info']) && $data['package_info']['package_status'] !== 'inactive'): ?>
    <div class="package-card <?php echo isset($data['package_info']['is_expiring_soon']) && $data['package_info']['is_expiring_soon'] ? 'expiring-soon' : ''; ?> <?php echo isset($data['package_info']['has_expired']) && $data['package_info']['has_expired'] ? 'expired' : ''; ?>">
        <div class="package-header">
            <div class="package-title">
                <h2>📦 <?php echo htmlspecialchars($data['package_info']['package_name']); ?> Package</h2>
                <span class="package-status-badge status-<?php echo $data['package_info']['package_status']; ?>">
                    <?php echo strtoupper($data['package_info']['package_status']); ?>
                </span>
            </div>
            <div class="package-price">
                <span class="price-label">3 Months Package</span>
                <span class="price-amount">LKR <?php echo number_format($data['package_info']['monthly_price'], 2); ?></span>
            </div>
        </div>

        <div class="package-body">
            <div class="package-info-grid">
                <!-- Purchase Information -->
                <div class="info-section">
                    <h4>📅 Subscription Details</h4>
                    <div class="info-items">
                        <div class="info-item">
                            <span class="info-label">Purchased:</span>
                            <span class="info-value"><?php echo $data['package_info']['purchased_date_formatted'] ?? 'N/A'; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Valid Until:</span>
                            <span class="info-value <?php echo (isset($data['package_info']['is_expiring_soon']) && $data['package_info']['is_expiring_soon']) ? 'warning-text' : ''; ?>">
                                <?php echo $data['package_info']['expiry_date_formatted'] ?? 'N/A'; ?>
                                <?php if (isset($data['package_info']['days_until_expiry']) && $data['package_info']['days_until_expiry'] > 0): ?>
                                    <span class="days-remaining">(<?php echo $data['package_info']['days_until_expiry']; ?> days left)</span>
                                <?php endif; ?>
                            </span>
                        </div>
                        <?php if (isset($data['package_info']['has_expired']) && $data['package_info']['has_expired']): ?>
                        <div class="info-item">
                            <span class="expired-notice">⚠️ Package has expired. Please renew to continue using services.</span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Package Features -->
                <div class="info-section">
                    <h4>✨ Package Features</h4>
                    <div class="info-items">
                        <div class="info-item">
                            <span class="info-label">🏪 Shop Listings:</span>
                            <span class="info-value"><?php echo $data['package_info']['shops_limit']; ?> shops</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">📸 Images per Shop:</span>
                            <span class="info-value"><?php echo $data['package_info']['photos_limit']; ?> photos</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">💬 Support:</span>
                            <span class="info-value"><?php echo $data['package_info']['support_type']; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="info-section">
                    <h4>💳 Payment Details</h4>
                    <div class="info-items">
                        <div class="info-item">
                            <span class="info-label">Amount Paid:</span>
                            <span class="info-value success-text">LKR <?php echo number_format($data['package_info']['amount_paid'] ?? $data['package_info']['monthly_price'], 2); ?></span>
                        </div>
                        <?php if (isset($data['package_info']['stripe_payment_id'])): ?>
                        <div class="info-item">
                            <span class="info-label">Payment ID:</span>
                            <span class="info-value small-text"><?php echo substr($data['package_info']['stripe_payment_id'], 0, 20) . '...'; ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="package-footer">
            <?php if (isset($data['package_info']['is_expiring_soon']) && $data['package_info']['is_expiring_soon']): ?>
            <div class="expiry-warning">
                ⚠️ Your package is expiring soon! Renew now to avoid service interruption.
            </div>
            <?php endif; ?>
            <div class="package-actions">
                <a href="<?php echo URLROOT; ?>/rental_packages" class="btn-upgrade">🔄 Renew / Upgrade Package</a>
                <a href="<?php echo URLROOT; ?>/rentalowner/billing" class="btn-billing">📄 View Billing History</a>
            </div>
        </div>
    </div>
    <?php elseif (isset($data['package_info']) && $data['package_info']['package_status'] === 'inactive'): ?>
    <div class="no-package-banner">
        <div class="no-package-content">
            <h3>📦 No Active Package</h3>
            <p>Subscribe to a package to start adding your rental shops and reach more customers!</p>
            <a href="<?php echo URLROOT; ?>/rental_packages" class="btn-subscribe">🚀 View Packages & Subscribe</a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Shop Status Stats -->
    <div class="kala-rental-dash-stats-grid">
        <div class="kala-rental-dash-stat-card">
            <div class="kala-rental-dash-stat-icon">🏬</div>
            <div class="kala-rental-dash-stat-info">
                <h3><?php echo $data['stats']['total_shops'] ?? 0; ?></h3>
                <p>Total Shops</p>
            </div>
        </div>
        
        <div class="kala-rental-dash-stat-card status-approved">
            <div class="kala-rental-dash-stat-icon">✅</div>
            <div class="kala-rental-dash-stat-info">
                <h3><?php echo $data['stats']['approved_shops'] ?? 0; ?></h3>
                <p>Approved Shops</p>
            </div>
        </div>

        <div class="kala-rental-dash-stat-card status-pending">
            <div class="kala-rental-dash-stat-icon">⏳</div>
            <div class="kala-rental-dash-stat-info">
                <h3><?php echo $data['stats']['pending_shops'] ?? 0; ?></h3>
                <p>Pending Review</p>
            </div>
        </div>

        <?php if (isset($data['stats']['rejected_shops']) && $data['stats']['rejected_shops'] > 0): ?>
        <div class="kala-rental-dash-stat-card status-rejected">
            <div class="kala-rental-dash-stat-icon">❌</div>
            <div class="kala-rental-dash-stat-info">
                <h3><?php echo $data['stats']['rejected_shops']; ?></h3>
                <p>Rejected</p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Shop Usage Indicator -->
    <?php if (isset($data['shop_summary'])): ?>
    <div class="usage-indicator">
        <h3>Shop Usage</h3>
        <div class="usage-bar-container">
            <div class="usage-bar">
                <?php 
                $currentShops = $data['shop_summary']['current_shops'] ?? 0;
                $shopsLimit = $data['shop_summary']['shops_limit'] ?? 5;
                $percentage = $shopsLimit > 0 ? ($currentShops / $shopsLimit) * 100 : 0;
                ?>
                <div class="usage-fill" style="width: <?php echo min($percentage, 100); ?>%"></div>
            </div>
            <div class="usage-text">
                <?php echo $currentShops; ?> / <?php echo $shopsLimit; ?> shops used
                <?php if ($data['shop_summary']['can_add_more']): ?>
                    <span class="usage-available">✓ Can add more</span>
                <?php else: ?>
                    <span class="usage-limit">⚠ Limit reached</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3>Quick Actions</h3>
        <div class="action-buttons">
            <a href="<?php echo URLROOT; ?>/rentalowner/addShop" class="action-btn btn-primary">
                ➕ Add New Shop
            </a>
            <a href="<?php echo URLROOT; ?>/rentalowner/shopManagement" class="action-btn btn-secondary">
                🏪 Manage Shops
            </a>
            <?php if (isset($data['stats']['pending_shops']) && $data['stats']['pending_shops'] > 0): ?>
            <div class="action-info">
                <span class="info-badge">⏳ <?php echo $data['stats']['pending_shops']; ?> shop(s) awaiting approval</span>
            </div>
            <?php endif; ?>
            <?php if (isset($data['stats']['rejected_shops']) && $data['stats']['rejected_shops'] > 0): ?>
            <div class="action-info">
                <span class="info-badge rejected">❌ <?php echo $data['stats']['rejected_shops']; ?> shop(s) rejected - please review and resubmit</span>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.kala-rental-dash-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.kala-rental-dash-stat-card {
    background: #1a1a1a;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #333;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.3s ease;
}

.kala-rental-dash-stat-card:hover {
    transform: translateY(-2px);
    border-color: #03B200;
}

.kala-rental-dash-stat-card.status-approved {
    border-left: 4px solid #03B200;
}

.kala-rental-dash-stat-card.status-pending {
    border-left: 4px solid #ff9800;
}

.kala-rental-dash-stat-card.status-rejected {
    border-left: 4px solid #f44336;
}

.kala-rental-dash-stat-icon {
    font-size: 32px;
}

.kala-rental-dash-stat-info h3 {
    font-size: 28px;
    color: #fff;
    margin: 0;
}

.kala-rental-dash-stat-info p {
    color: #999;
    margin: 5px 0 0;
    font-size: 14px;
}

/* Enhanced Package Card */
.package-card {
    background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
    border: 2px solid #03B200;
    border-radius: 16px;
    margin-bottom: 30px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(3, 178, 0, 0.1);
    transition: all 0.3s ease;
}

.package-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 48px rgba(3, 178, 0, 0.2);
}

.package-card.expiring-soon {
    border-color: #ff9800;
    box-shadow: 0 8px 32px rgba(255, 152, 0, 0.2);
}

.package-card.expired {
    border-color: #f44336;
    box-shadow: 0 8px 32px rgba(244, 67, 54, 0.2);
}

.package-header {
    background: linear-gradient(135deg, #03B200 0%, #028a00 100%);
    padding: 25px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.package-card.expiring-soon .package-header {
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
}

.package-card.expired .package-header {
    background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
}

.package-title h2 {
    color: #fff;
    margin: 0 0 8px 0;
    font-size: 24px;
    font-weight: 700;
}

.package-status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.package-status-badge.status-active {
    background: rgba(255, 255, 255, 0.3);
    color: #fff;
}

.package-price {
    text-align: right;
}

.price-label {
    display: block;
    color: rgba(255, 255, 255, 0.8);
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 5px;
}

.price-amount {
    display: block;
    color: #fff;
    font-size: 28px;
    font-weight: 700;
}

.package-body {
    padding: 30px;
}

.package-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
}

.info-section h4 {
    color: #03B200;
    font-size: 16px;
    margin: 0 0 15px 0;
    font-weight: 600;
}

.info-items {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 10px;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 8px;
    border-left: 3px solid #333;
    transition: all 0.3s ease;
}

.info-item:hover {
    background: rgba(255, 255, 255, 0.05);
    border-left-color: #03B200;
}

.info-label {
    color: #999;
    font-size: 14px;
    font-weight: 500;
}

.info-value {
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    text-align: right;
}

.days-remaining {
    display: block;
    font-size: 12px;
    color: #03B200;
    margin-top: 4px;
}

.warning-text {
    color: #ff9800 !important;
}

.success-text {
    color: #03B200 !important;
}

.small-text {
    font-size: 12px !important;
    color: #999 !important;
}

.expired-notice {
    color: #f44336;
    font-weight: 600;
    font-size: 13px;
    display: block;
    padding: 10px;
    background: rgba(244, 67, 54, 0.1);
    border-radius: 6px;
    border-left: 3px solid #f44336;
}

.package-footer {
    background: rgba(0, 0, 0, 0.2);
    padding: 20px 30px;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
}

.expiry-warning {
    background: rgba(255, 152, 0, 0.2);
    border: 1px solid #ff9800;
    color: #ff9800;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 15px;
    font-weight: 600;
    font-size: 14px;
}

.package-actions {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.btn-upgrade, .btn-billing {
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    display: inline-block;
}

.btn-upgrade {
    background: #03B200;
    color: white;
    flex: 1;
    text-align: center;
}

.btn-upgrade:hover {
    background: #028a00;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(3, 178, 0, 0.3);
}

.btn-billing {
    background: #333;
    color: white;
}

.btn-billing:hover {
    background: #444;
    transform: translateY(-2px);
}

/* No Package Banner */
.no-package-banner {
    background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%);
    border: 2px dashed #666;
    border-radius: 16px;
    padding: 40px;
    margin-bottom: 30px;
    text-align: center;
}

.no-package-content h3 {
    color: #fff;
    margin: 0 0 10px 0;
    font-size: 24px;
}

.no-package-content p {
    color: #999;
    margin: 0 0 20px 0;
    font-size: 16px;
}

.btn-subscribe {
    display: inline-block;
    background: #03B200;
    color: white;
    padding: 14px 32px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
    font-size: 16px;
    transition: all 0.3s ease;
}

.btn-subscribe:hover {
    background: #028a00;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(3, 178, 0, 0.4);
}

.usage-indicator {
    background: #1a1a1a;
    border: 1px solid #333;
    border-radius: 12px;
    padding: 20px;
    margin: 20px 0;
}

.usage-indicator h3 {
    color: #fff;
    margin: 0 0 15px 0;
}

.usage-bar-container {
    margin-top: 10px;
}

.usage-bar {
    height: 30px;
    background: #0a0a0a;
    border-radius: 15px;
    overflow: hidden;
    position: relative;
}

.usage-fill {
    height: 100%;
    background: linear-gradient(90deg, #03B200 0%, #05d400 100%);
    border-radius: 15px;
    transition: width 0.5s ease;
}

.usage-text {
    margin-top: 10px;
    color: #ccc;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.usage-available {
    color: #03B200;
    font-weight: 600;
}

.usage-limit {
    color: #ff9800;
    font-weight: 600;
}

.quick-actions {
    background: #1a1a1a;
    border: 1px solid #333;
    border-radius: 12px;
    padding: 20px;
    margin: 20px 0;
}

.quick-actions h3 {
    color: #fff;
    margin: 0 0 15px 0;
}

.action-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: center;
}

.action-btn {
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #03B200;
    color: white;
}

.btn-primary:hover {
    background: #028a00;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #333;
    color: white;
}

.btn-secondary:hover {
    background: #444;
    transform: translateY(-2px);
}

.action-info {
    flex: 1 1 100%;
    margin-top: 5px;
}

.info-badge {
    display: inline-block;
    background: rgba(255, 152, 0, 0.2);
    color: #ff9800;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    border: 1px solid #ff9800;
}

.info-badge.rejected {
    background: rgba(244, 67, 54, 0.2);
    color: #f44336;
    border: 1px solid #f44336;
}

@media (max-width: 768px) {
    .package-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .package-title h2 {
        font-size: 20px;
    }
    
    .price-amount {
        font-size: 24px;
    }
    
    .package-info-grid {
        grid-template-columns: 1fr;
    }
    
    .package-body {
        padding: 20px;
    }
    
    .package-footer {
        padding: 15px 20px;
    }
    
    .package-actions {
        flex-direction: column;
    }
    
    .btn-upgrade, .btn-billing {
        width: 100%;
    }
    
    .kala-rental-dash-stats-grid {
        grid-template-columns: 1fr;
    }
    
    .no-package-banner {
        padding: 30px 20px;
    }
}
</style>

<?php require APPROOT.'/views/rentalowner/inc/footer.php'; ?>
