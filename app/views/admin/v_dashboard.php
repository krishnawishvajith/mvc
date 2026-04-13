<?php require APPROOT.'/views/admin/inc/header.php'; ?>

<div class="main-content">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1>Dashboard Overview</h1>
        <div class="date-range">
            <span>📅 <?php echo date('F j, Y'); ?></span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <h3><?php echo number_format($data['total_users']); ?></h3>
                <p>Total Users</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-info">
                <h3><?php echo number_format($data['total_bookings']); ?></h3>
                <p>Total Bookings</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-info">
                <h3>LKR <?php echo number_format($data['monthly_revenue']); ?></h3>
                <p>Monthly Revenue</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">🏟️</div>
            <div class="stat-info">
                <h3><?php echo number_format($data['active_stadiums']); ?></h3>
                <p>Active Stadiums</p>
            </div>
        </div>
    </div>

    <!-- Dashboard Content Grid -->
    <div class="dashboard-grid">
        <!-- Recent Bookings -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Recent Bookings</h3>
                <a href="<?php echo URLROOT; ?>/admin/bookings" class="view-all">View All →</a>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Stadium</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($data['recent_bookings']) && count($data['recent_bookings']) > 0): ?>
                            <?php foreach($data['recent_bookings'] as $booking): ?>
                            <tr>
                                <td>#BK<?php echo str_pad($booking->id, 4, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo htmlspecialchars($booking->stadium_name); ?></td>
                                <td><?php echo htmlspecialchars($booking->customer_first_name . ' ' . $booking->customer_last_name); ?></td>
                                <td>LKR <?php echo number_format($booking->total_price); ?></td>
                                <td><?php echo date('M d, Y', strtotime($booking->booking_date)); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 20px; color: #999;">No recent bookings</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- NEW: Advertisement Revenue Summary -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Advertisement Revenue</h3>
                <span class="revenue-badge">LKR <?php echo number_format($data['ad_revenue_monthly'] ?? 47000); ?></span>
            </div>
            <div class="ad-summary-content">
                <!-- Active Ads Display -->
                <div class="active-ads-section">
                    <h4>Currently Active (<?php echo $data['active_ads_count'] ?? 2; ?>)</h4>
                    <div class="active-ads-list">
                        <div class="ad-item active">
                            <div class="ad-company">
                                <div class="company-logo">N</div>
                                <div class="company-info">
                                    <span class="company-name">Nike Lanka</span>
                                    <small>Expires: Sept 15</small>
                                </div>
                            </div>
                            <div class="ad-revenue">LKR 25,000</div>
                        </div>
                        
                        <div class="ad-item active">
                            <div class="ad-company">
                                <div class="company-logo">A</div>
                                <div class="company-info">
                                    <span class="company-name">Adidas Store</span>
                                    <small>Expires: Sept 10</small>
                                </div>
                            </div>
                            <div class="ad-revenue">LKR 22,000</div>
                        </div>
                    </div>
                </div>

                <!-- Pending Ads -->
                <div class="pending-ads-section">
                    <h4>Pending Approval (<?php echo $data['pending_ads_count'] ?? 3; ?>)</h4>
                    <div class="pending-ads-list">
                        <div class="ad-item pending">
                            <div class="ad-company">
                                <div class="company-logo">S</div>
                                <div class="company-info">
                                    <span class="company-name">Sport Gear Lanka</span>
                                    <small>Payment Pending</small>
                                </div>
                            </div>
                            <div class="ad-revenue pending">LKR 15,000</div>
                        </div>
                        
                        <div class="ad-item pending">
                            <div class="ad-company">
                                <div class="company-logo">F</div>
                                <div class="company-info">
                                    <span class="company-name">Fitness Pro</span>
                                    <small>Payment Verified</small>
                                </div>
                            </div>
                            <div class="ad-revenue pending">LKR 20,000</div>
                        </div>
                        
                        <div class="ad-item pending">
                            <div class="ad-company">
                                <div class="company-logo">A</div>
                                <div class="company-info">
                                    <span class="company-name">Athletic Store</span>
                                    <small>Ready to Publish</small>
                                </div>
                            </div>
                            <div class="ad-revenue pending">LKR 12,000</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Ad Actions -->
                <div class="ad-actions">
                    <a href="<?php echo URLROOT; ?>/admin/advertisements" class="btn-manage-ads">Manage Ads</a>
                    <button class="btn-publish-pending" onclick="publishPendingAds()">Publish Ready (2)</button>
                </div>
            </div>
        </div>

        <!-- Pending Payouts -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Pending Payouts</h3>
                <span class="badge">LKR <?php echo number_format($data['pending_payouts']); ?></span>
            </div>
            <div class="payout-list">
                <?php foreach($data['pending_payouts_list'] as $payout): ?>
                <div class="payout-item">
                    <div class="payout-info">
                        <h4><?php echo $payout['owner']; ?></h4>
                        <p><?php echo $payout['stadium']; ?></p>
                    </div>
                    <div class="payout-amount">
                        <span class="amount">LKR <?php echo number_format($payout['amount']); ?></span>
                        <span class="commission">Commission: LKR <?php echo number_format($payout['commission']); ?></span>
                    </div>
                    <button class="btn-payout">Release</button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Quick Actions</h3>
            </div>
            <div class="quick-actions">
                <div class="action-item">
                    <span class="action-icon">🔔</span>
                    <div class="action-info">
                        <h4>Refund Requests</h4>
                        <p><?php echo $data['pending_refunds']; ?> pending requests</p>
                    </div>
                    <button class="btn-action">Review</button>
                </div>
                
                <!-- NEW: Advertisement Approvals Action -->
                <div class="action-item">
                    <span class="action-icon">📢</span>
                    <div class="action-info">
                        <h4>Advertisement Approvals</h4>
                        <p><?php echo $data['pending_ads_count'] ?? 3; ?> awaiting approval</p>
                    </div>
                    <a href="<?php echo URLROOT; ?>/admin/advertisements" class="btn-action">Review</a>
                </div>
                
                <div class="action-item">
                    <span class="action-icon">💬</span>
                    <div class="action-info">
                        <h4>Messages</h4>
                        <p>3 unread messages</p>
                    </div>
                    <a href="<?php echo URLROOT; ?>/admin/messages" class="btn-action">View</a>
                </div>
                
                <div class="action-item">
                    <span class="action-icon">⚙️</span>
                    <div class="action-info">
                        <h4>Content Management</h4>
                        <p>Update website content</p>
                    </div>
                    <a href="<?php echo URLROOT; ?>/admin/content" class="btn-action">Manage</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function publishPendingAds() {
    if(confirm('Are you sure you want to publish all ready advertisements?')) {
        alert('Publishing 2 pending advertisements...');
        // Here you would make an AJAX call to publish the ads
        setTimeout(() => {
            alert('Advertisements published successfully!');
            location.reload();
        }, 1500);
    }
}

// Auto-refresh advertisement data every 2 minutes
setInterval(function() {
    if (window.location.pathname.includes('/admin') && 
        !window.location.pathname.includes('/admin/login')) {
        // This would be replaced with actual AJAX call to refresh ad data
        console.log('Refreshing advertisement data...');
    }
}, 120000); // 2 minutes
</script>

<?php require APPROOT.'/views/admin/inc/footer.php'; ?>