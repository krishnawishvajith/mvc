<?php require APPROOT.'/views/stadium_owner/inc/header.php'; ?>

<div class="main-content">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1>Stadium Owner Dashboard</h1>
        <div class="welcome-message">
            <p>Welcome back, <?php echo $data['user_first_name']; ?>! Here's your business overview.</p>
        </div>
    </div>

    <!-- Enhanced Package Details Card -->
    <?php if (isset($data['package_info']) && $data['package_info']['package_status'] !== 'inactive'): ?>
    <div class="package-card">
        <div class="package-header">
            <div class="package-title">
                <h2>📦 <?php echo htmlspecialchars($data['package_info']['package_name']); ?> Package</h2>
                <span class="package-status-badge status-<?php echo $data['package_info']['package_status']; ?>">
                    <?php echo strtoupper($data['package_info']['package_status']); ?>
                </span>
            </div>
            <div class="package-price">
                <span class="price-label">One-Time Setup</span>
                <span class="price-amount">LKR <?php echo number_format($data['package_info']['setup_fee'], 2); ?></span>
            </div>
        </div>

        <div class="package-body">
            <div class="package-info-grid">
                <!-- Purchase Information -->
                <div class="info-section">
                    <h4>📅 Purchase Details</h4>
                    <div class="info-items">
                        <div class="info-item">
                            <span class="info-label">Purchased:</span>
                            <span class="info-value"><?php echo $data['package_info']['purchased_date_formatted'] ?? 'N/A'; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Package Type:</span>
                            <span class="info-value">One-Time (No Expiry)</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Days Active:</span>
                            <span class="info-value success-text"><?php echo $data['package_info']['days_since_purchase'] ?? 0; ?> days</span>
                        </div>
                    </div>
                </div>

                <!-- Package Features -->
                <div class="info-section">
                    <h4>✨ Package Features</h4>
                    <div class="info-items">
                        <div class="info-item">
                            <span class="info-label">🏟️ Stadium Listings:</span>
                            <span class="info-value">
                                <?php 
                                echo $data['package_info']['is_unlimited'] ? '∞ Unlimited' : $data['package_info']['stadium_limit'] . ' stadiums';
                                ?>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">📸 Photos per Stadium:</span>
                            <span class="info-value"><?php echo $data['package_info']['photos_limit']; ?> photos</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">🎥 Videos per Stadium:</span>
                            <span class="info-value"><?php echo $data['package_info']['videos_limit']; ?> videos</span>
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
                            <span class="info-value success-text">LKR <?php echo number_format($data['package_info']['amount_paid'], 2); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Commission Rate:</span>
                            <span class="info-value"><?php echo $data['package_info']['commission_rate']; ?>%</span>
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
            <div class="package-actions">
                <a href="<?php echo URLROOT; ?>/pricing" class="btn-upgrade">⬆️ Upgrade Package</a>
                <a href="<?php echo URLROOT; ?>/stadium_owner/billing" class="btn-billing">📄 View Billing History</a>
            </div>
        </div>
    </div>
    <?php elseif (isset($data['package_info']) && $data['package_info']['package_status'] === 'inactive'): ?>
    <div class="no-package-banner">
        <div class="no-package-content">
            <h3>📦 No Active Package</h3>
            <p>Purchase a package to start listing your stadiums and reach more customers!</p>
            <a href="<?php echo URLROOT; ?>/pricing" class="btn-subscribe">🚀 View Packages & Subscribe</a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Stadium Usage Indicator -->
    <?php if (isset($data['stadium_summary']) && $data['stadium_summary']['stadium_limit'] !== '0'): ?>
    <div class="usage-indicator">
        <h3>Stadium Usage</h3>
        <div class="usage-bar-container">
            <div class="usage-bar">
                <?php 
                $currentStadiums = $data['stadium_summary']['current_stadiums'] ?? 0;
                $stadiumLimit = $data['stadium_summary']['is_unlimited'] ? 100 : intval($data['stadium_summary']['stadium_limit']);
                $percentage = $stadiumLimit > 0 ? ($currentStadiums / $stadiumLimit) * 100 : 0;
                ?>
                <div class="usage-fill" style="width: <?php echo min($percentage, 100); ?>%"></div>
            </div>
            <div class="usage-text">
                <?php 
                if ($data['stadium_summary']['is_unlimited']) {
                    echo $currentStadiums . ' / ∞ stadiums (Unlimited)';
                } else {
                    echo $currentStadiums . ' / ' . $data['stadium_summary']['stadium_limit'] . ' stadiums used';
                }
                ?>
                <?php if ($data['stadium_summary']['can_add_more']): ?>
                    <span class="usage-available">✓ Can add more</span>
                <?php elseif (!$data['stadium_summary']['is_unlimited']): ?>
                    <span class="usage-limit">⚠ Limit reached</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">🏟️</div>
            <div class="stat-info">
                <h3><?php echo $data['stats']['total_properties'] ?? 0; ?></h3>
                <p>Total Stadiums</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-info">
                <h3><?php echo $data['stats']['active_bookings'] ?? 0; ?></h3>
                <p>Active Bookings</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-info">
                <h3>LKR <?php echo number_format($data['stats']['monthly_revenue'] ?? 0); ?></h3>
                <p>Monthly Revenue</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <h3><?php echo $data['stats']['total_customers'] ?? 0; ?></h3>
                <p>Total Customers</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3>Quick Actions</h3>
        <div class="action-buttons">
            <?php if (isset($data['stadium_summary']) && $data['stadium_summary']['can_add_more']): ?>
            <a href="<?php echo URLROOT; ?>/stadium_owner/add_property" class="action-btn btn-primary">
                ➕ Add New Stadium
            </a>
            <?php else: ?>
            <div class="action-btn btn-disabled" title="Upgrade package to add more stadiums">
                ➕ Add New Stadium (Limit Reached)
            </div>
            <?php endif; ?>
            <a href="<?php echo URLROOT; ?>/stadium_owner/properties" class="action-btn btn-secondary">
                🏟️ Manage Stadiums
            </a>
            <a href="<?php echo URLROOT; ?>/stadium_owner/bookings" class="action-btn btn-secondary">
                📅 View Bookings
            </a>
        </div>
    </div>

    <!-- Dashboard Content Grid -->
    <div class="dashboard-grid">
        <!-- Recent Bookings -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Recent Bookings</h3>
                <a href="<?php echo URLROOT; ?>/stadium_owner/bookings" class="view-all">View All →</a>
            </div>
            <div class="recent-bookings">
                <?php if (!empty($data['recent_bookings'])): ?>
                    <?php foreach($data['recent_bookings'] as $booking): ?>
                    <div class="booking-item">
                        <div class="booking-info">
                            <h4><?php echo $booking['customer']; ?></h4>
                            <p class="booking-property"><?php echo $booking['property']; ?></p>
                            <p class="booking-time"><?php echo $booking['date']; ?> • <?php echo $booking['time']; ?></p>
                        </div>
                        <div class="booking-amount">
                            <span class="amount">LKR <?php echo number_format($booking['amount']); ?></span>
                            <span class="status-badge <?php echo strtolower($booking['status']); ?>">
                                <?php echo $booking['status']; ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-data">No recent bookings</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Revenue Overview -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Revenue Overview</h3>
                <a href="<?php echo URLROOT; ?>/stadium_owner/revenue" class="view-all">View Details →</a>
            </div>
            <div class="revenue-content">
                <div class="revenue-comparison">
                    <div class="revenue-item">
                        <div class="revenue-label">This Month</div>
                        <div class="revenue-value">LKR <?php echo number_format($data['revenue_overview']['this_month'] ?? 0); ?></div>
                    </div>
                    <div class="revenue-growth">
                        <div class="growth-indicator positive">
                            +<?php echo $data['revenue_overview']['growth_percentage'] ?? 0; ?>%
                        </div>
                        <small>vs last month</small>
                    </div>
                    <div class="revenue-item">
                        <div class="revenue-label">Last Month</div>
                        <div class="revenue-value">LKR <?php echo number_format($data['revenue_overview']['last_month'] ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Package Card Styles */
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

.package-header {
    background: linear-gradient(135deg, #03B200 0%, #028a00 100%);
    padding: 25px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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

.success-text {
    color: #03B200 !important;
}

.small-text {
    font-size: 12px !important;
    color: #999 !important;
}

.package-footer {
    background: rgba(0, 0, 0, 0.2);
    padding: 20px 30px;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
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

/* Usage Indicator */
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

/* Quick Actions */
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
}

.action-btn {
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
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

.btn-disabled {
    background: #666;
    color: #999;
    cursor: not-allowed;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.stat-card {
    background: #1a1a1a;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #333;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    border-color: #03B200;
}

.stat-icon {
    font-size: 32px;
}

.stat-info h3 {
    font-size: 28px;
    color: #fff;
    margin: 0;
}

.stat-info p {
    color: #999;
    margin: 5px 0 0;
    font-size: 14px;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.dashboard-card {
    background: #1a1a1a;
    border: 1px solid #333;
    border-radius: 12px;
    padding: 20px;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.card-header h3 {
    color: #fff;
    margin: 0;
}

.view-all {
    color: #03B200;
    text-decoration: none;
    font-size: 14px;
}

.view-all:hover {
    text-decoration: underline;
}

.no-data {
    color: #999;
    text-align: center;
    padding: 20px;
    font-style: italic;
}

.booking-item {
    display: flex;
    justify-content: space-between;
    padding: 15px;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 8px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
}

.booking-item:hover {
    background: rgba(255, 255, 255, 0.05);
}

.booking-info h4 {
    color: #fff;
    margin: 0 0 5px 0;
    font-size: 16px;
}

.booking-property {
    color: #999;
    margin: 0 0 5px 0;
    font-size: 14px;
}

.booking-time {
    color: #666;
    margin: 0;
    font-size: 12px;
}

.booking-amount {
    text-align: right;
}

.amount {
    display: block;
    color: #03B200;
    font-weight: 700;
    margin-bottom: 5px;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.status-badge.confirmed {
    background: rgba(3, 178, 0, 0.2);
    color: #03B200;
}

.status-badge.pending {
    background: rgba(255, 152, 0, 0.2);
    color: #ff9800;
}

.revenue-comparison {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    gap: 20px;
    align-items: center;
}

.revenue-item {
    text-align: center;
}

.revenue-label {
    color: #999;
    font-size: 12px;
    margin-bottom: 5px;
}

.revenue-value {
    color: #fff;
    font-size: 20px;
    font-weight: 700;
}

.revenue-growth {
    text-align: center;
}

.growth-indicator {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 5px;
}

.growth-indicator.positive {
    color: #03B200;
}

@media (max-width: 768px) {
    .package-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .package-info-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .action-btn {
        width: 100%;
    }
}
</style>

<script>
// Auto-refresh dashboard data every 2 minutes
setInterval(function() {
    if (window.location.pathname.includes('/stadium_owner') && 
        !window.location.pathname.includes('/stadium_owner/login')) {
        console.log('Refreshing stadium owner dashboard data...');
    }
}, 120000);

// Animate stat cards on load
document.addEventListener('DOMContentLoaded', function() {
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>

<?php require APPROOT.'/views/stadium_owner/inc/footer.php'; ?>
