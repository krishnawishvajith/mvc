<?php require APPROOT.'/views/stadium_owner/inc/header.php'; ?>

<div class="main-content revenue-page">
    <div class="dashboard-header">
        <h1>Revenue & Analytics</h1>
        <div class="header-actions">
            <button class="btn-export" onclick="exportRevenueData()">📊 Export Report</button>
            <button class="btn-payout-request" onclick="requestPayout()">💰 Request Payout</button>
        </div>
    </div>

    <!-- Revenue Overview Cards -->
    <div class="revenue-stats">
        <div class="stat-item">
            <div class="stat-icon">💰</div>
            <div class="stat-details">
                <span class="stat-number">LKR <?php echo number_format($data['revenue_data']['total_revenue'] ?? 0); ?></span>
                <span class="stat-label">Total Revenue</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">📅</div>
            <div class="stat-details">
                <span class="stat-number">LKR <?php echo number_format($data['revenue_data']['this_month'] ?? 0); ?></span>
                <span class="stat-label">This Month</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">⏳</div>
            <div class="stat-details">
                <span class="stat-number">LKR <?php echo number_format($data['revenue_data']['pending_payout'] ?? 0); ?></span>
                <span class="stat-label">Pending Payout</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">📈</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo ($data['revenue_data']['growth_rate'] ?? 0) >= 0 ? '+' : ''; ?><?php echo number_format($data['revenue_data']['growth_rate'] ?? 0, 1); ?>%</span>
                <span class="stat-label">Growth Rate</span>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Revenue Trends</h3>
            <div class="chart-controls">
                <select class="period-select" id="chartPeriod">
                    <option value="last_6_months">Last 6 Months</option>
                    <option value="last_year">Last Year</option>
                    <option value="all_time">All Time</option>
                </select>
            </div>
        </div>
        <div class="chart-container" style="background-color: #161616;">
            <canvas id="revenueChart" width="800" height="300"></canvas>
        </div>
    </div>

    <!-- Revenue Breakdown -->
    <div class="revenue-breakdown-grid">
        <!-- Monthly Revenue -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Monthly Breakdown</h3>
            </div>
            <div class="monthly-revenue-list">
                <?php 
                $monthlyData = $data['revenue_data']['monthly_data'] ?? [];
                if(empty($monthlyData)): ?>
                <div class="monthly-item">
                    <div class="month-info">
                        <span class="month-name">No paid booking revenue yet</span>
                        <span class="month-year">-</span>
                    </div>
                    <div class="month-amount">
                        <span class="amount">LKR 0</span>
                        <div class="amount-bar">
                            <div class="amount-fill" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
                <?php else:
                $maxMonthlyAmount = max($monthlyData);
                $maxMonthlyAmount = $maxMonthlyAmount > 0 ? $maxMonthlyAmount : 1;
                foreach($monthlyData as $month => $amount):
                    $monthParts = explode(' ', $month, 2);
                    $monthName = $monthParts[0];
                    $monthYear = $monthParts[1] ?? '';
                    $barWidth = ($amount / $maxMonthlyAmount) * 100;
                ?>
                <div class="monthly-item">
                    <div class="month-info">
                        <span class="month-name"><?php echo $monthName; ?></span>
                        <span class="month-year"><?php echo $monthYear; ?></span>
                    </div>
                    <div class="month-amount">
                        <span class="amount">LKR <?php echo number_format($amount); ?></span>
                        <div class="amount-bar">
                            <div class="amount-fill" style="width: <?php echo $barWidth; ?>%"></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>

        <!-- Property Performance -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Property Performance</h3>
            </div>
            <div class="property-performance-list">
                <?php 
                $propertyPerformance = $data['analytics']['property_performance'] ?? [];
                if(empty($propertyPerformance)): ?>
                <div class="property-performance-item">
                    <div class="property-name">No performance data yet</div>
                    <div class="property-stats">
                        <div class="property-bookings">0 bookings</div>
                        <div class="property-revenue">LKR 0</div>
                    </div>
                </div>
                <?php else:
                foreach($propertyPerformance as $property): ?>
                <div class="property-performance-item">
                    <div class="property-name"><?php echo $property['name']; ?></div>
                    <div class="property-stats">
                        <div class="property-bookings"><?php echo $property['bookings']; ?> bookings</div>
                        <div class="property-revenue">LKR <?php echo number_format($property['revenue']); ?></div>
                    </div>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>

        <!-- Commission Breakdown -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Commission Details</h3>
            </div>
            <div class="commission-breakdown">
                <div class="commission-item">
                    <div class="commission-label">Gross Revenue</div>
                    <div class="commission-value">LKR <?php echo number_format($data['revenue_data']['gross_revenue'] ?? 0); ?></div>
                </div>
                <div class="commission-item">
                    <div class="commission-label">Platform Commission (<?php echo (int)($data['revenue_data']['commission_rate'] ?? 10); ?>%)</div>
                    <div class="commission-value commission-deduction">-LKR <?php echo number_format($data['revenue_data']['admin_commission'] ?? 0); ?></div>
                </div>
                <div class="commission-item total">
                    <div class="commission-label">Your Earnings</div>
                    <div class="commission-value">LKR <?php echo number_format($data['revenue_data']['owner_earnings'] ?? 0); ?></div>
                </div>
            </div>
        </div>

        <!-- Payout History -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Payout History</h3>
            </div>
            <div class="payout-history-list">
                <?php $payoutHistory = $data['revenue_data']['payout_history'] ?? []; ?>
                <?php if (empty($payoutHistory)): ?>
                <div class="payout-item">
                    <div class="payout-date">
                        <span class="date">No payouts yet</span>
                        <span class="year">-</span>
                    </div>
                    <div class="payout-details">
                        <div class="payout-amount">LKR 0</div>
                        <div class="payout-status pending">Pending</div>
                    </div>
                </div>
                <?php else: ?>
                <?php foreach($payoutHistory as $payout): ?>
                <div class="payout-item">
                    <div class="payout-date">
                        <span class="date"><?php echo $payout['month']; ?></span>
                        <span class="year"><?php echo $payout['year']; ?></span>
                    </div>
                    <div class="payout-details">
                        <div class="payout-amount">LKR <?php echo number_format($payout['amount']); ?></div>
                        <div class="payout-status completed"><?php echo $payout['status']; ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Analytics Dashboard -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Business Analytics</h3>
        </div>
        <div class="analytics-grid">
            <!-- Customer Demographics -->
            <div class="analytics-section">
                <h4>Customer Demographics</h4>
                <div class="demographics-chart">
                    <div class="demo-item">
                        <span class="demo-label">18-25 years</span>
                        <div class="demo-bar">
                            <div class="demo-fill" style="width: 45%"></div>
                        </div>
                        <span class="demo-percentage">45%</span>
                    </div>
                    <div class="demo-item">
                        <span class="demo-label">26-35 years</span>
                        <div class="demo-bar">
                            <div class="demo-fill" style="width: 35%"></div>
                        </div>
                        <span class="demo-percentage">35%</span>
                    </div>
                    <div class="demo-item">
                        <span class="demo-label">36+ years</span>
                        <div class="demo-bar">
                            <div class="demo-fill" style="width: 20%"></div>
                        </div>
                        <span class="demo-percentage">20%</span>
                    </div>
                </div>
            </div>

            <!-- Booking Trends -->
            <div class="analytics-section">
                <h4>Booking Trends</h4>
                <div class="trends-chart">
                    <div class="trend-item">
                        <span class="trend-label">Peak Hours</span>
                        <span class="trend-value">6-8 PM</span>
                    </div>
                    <div class="trend-item">
                        <span class="trend-label">Peak Days</span>
                        <span class="trend-value">Weekends</span>
                    </div>
                    <div class="trend-item">
                        <span class="trend-label">Average Duration</span>
                        <span class="trend-value">2.5 hours</span>
                    </div>
                    <div class="trend-item">
                        <span class="trend-label">Repeat Customers</span>
                        <span class="trend-value">68%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Goals -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Revenue Goals</h3>
            <button class="btn-set-goals" onclick="setRevenueGoals()">Set Goals</button>
        </div>
        <div class="goals-container">
            <div class="goal-item">
                <div class="goal-info">
                    <h4>Monthly Target</h4>
                    <div class="goal-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 75%"></div>
                        </div>
                        <div class="progress-text">LKR 45,000 / LKR 60,000</div>
                    </div>
                </div>
                <div class="goal-status">
                    <span class="status-badge in-progress">75% Complete</span>
                </div>
            </div>
            <div class="goal-item">
                <div class="goal-info">
                    <h4>Yearly Target</h4>
                    <div class="goal-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 46%"></div>
                        </div>
                        <div class="progress-text">LKR 278,000 / LKR 600,000</div>
                    </div>
                </div>
                <div class="goal-status">
                    <span class="status-badge in-progress">46% Complete</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Chart functionality (placeholder)
function initializeRevenueChart() {
    const canvas = document.getElementById('revenueChart');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        // Placeholder for chart implementation
        console.log('Revenue chart will be implemented with Chart.js');
    }
}

function exportRevenueData() {
    alert('Revenue data export functionality will be implemented');
}

function requestPayout() {
    const pendingPayout = <?php echo json_encode((float)($data['revenue_data']['pending_payout'] ?? 0)); ?>;
    if (confirm(`Request payout of LKR ${pendingPayout.toLocaleString()}?`)) {
        alert('Payout request submitted successfully!');
    }
}

function setRevenueGoals() {
    alert('Revenue goals setting functionality will be implemented');
}

// Initialize chart on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeRevenueChart();
});
</script>

<style>
.revenue-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.chart-container {
    padding: 20px;
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #161616 !important;
    border-radius: 8px;
}

.chart-controls {
    display: flex;
    gap: 10px;
}

.period-select {
    padding: 6px 12px;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    font-size: 13px;
    background: white;
}

.revenue-breakdown-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.monthly-revenue-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.monthly-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
}

.month-info {
    display: flex;
    flex-direction: column;
}

.month-name {
    font-weight: 600;
    color: #212529;
}

.month-year {
    font-size: 12px;
    color: #6c757d;
}

.month-amount {
    text-align: right;
    min-width: 120px;
}

.amount {
    font-weight: 600;
    color: #28a745;
    display: block;
    margin-bottom: 4px;
}

.amount-bar {
    height: 4px;
    background: #e9ecef;
    border-radius: 2px;
    overflow: hidden;
    width: 100px;
}

.amount-fill {
    height: 100%;
    background: linear-gradient(135deg, #28a745, #20c997);
    transition: width 0.3s ease;
}

.property-performance-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.property-performance-item {
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
}

.property-name {
    font-weight: 600;
    color: #212529;
    margin-bottom: 8px;
}

.property-stats {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
}

.property-bookings {
    color: #6c757d;
}

.property-revenue {
    color: #28a745;
    font-weight: 500;
}

.commission-breakdown {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.commission-item {
    display: flex;
    justify-content: space-between;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
}

.commission-item.total {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
}

.commission-label {
    font-size: 14px;
}

.commission-value {
    font-weight: 600;
}

.commission-deduction {
    color: #dc3545;
}

.payout-history-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.payout-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
}

.payout-date {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 60px;
}

.payout-date .date {
    font-weight: 600;
    color: #212529;
}

.payout-date .year {
    font-size: 12px;
    color: #6c757d;
}

.payout-details {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.payout-amount {
    font-weight: 600;
    color: #28a745;
}

.payout-status.completed {
    background: #d4edda;
    color: #155724;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
}

.analytics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
}

.analytics-section h4 {
    margin: 0 0 16px 0;
    color: #495057;
    font-size: 16px;
}

.demographics-chart {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.demo-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.demo-label {
    min-width: 80px;
    font-size: 13px;
    color: #6c757d;
}

.demo-bar {
    flex: 1;
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}

.demo-fill {
    height: 100%;
    background: linear-gradient(135deg, #007bff, #0056b3);
    transition: width 0.3s ease;
}

.demo-percentage {
    min-width: 35px;
    text-align: right;
    font-size: 13px;
    font-weight: 600;
    color: #495057;
}

.trends-chart {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.trend-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #e9ecef;
}

.trend-item:last-child {
    border-bottom: none;
}

.trend-label {
    color: #6c757d;
    font-size: 13px;
}

.trend-value {
    font-weight: 600;
    color: #212529;
    font-size: 13px;
}

.goals-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.goal-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
}

.goal-info h4 {
    margin: 0 0 12px 0;
    color: #212529;
    font-size: 16px;
}

.goal-progress {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.progress-bar {
    width: 200px;
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(135deg, #28a745, #20c997);
    transition: width 0.3s ease;
}

.progress-text {
    font-size: 13px;
    color: #6c757d;
}

.goal-status .status-badge.in-progress {
    background: #fff3cd;
    color: #856404;
    padding: 6px 12px;
    border-radius: 16px;
    font-size: 12px;
    font-weight: 500;
}

.btn-set-goals {
    background: #007bff;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    border: none;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-set-goals:hover {
    background: #0056b3;
}

.btn-export,
.btn-payout-request {
    background: #28a745;
    color: white;
    padding: 10px 16px;
    border-radius: 8px;
    border: none;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-export:hover,
.btn-payout-request:hover {
    background: #218838;
    transform: translateY(-2px);
}

.btn-payout-request {
    background: #ffc107;
    color: #212529;
}

.btn-payout-request:hover {
    background: #e0a800;
}

@media (max-width: 768px) {
    .revenue-breakdown-grid {
        grid-template-columns: 1fr;
    }
    
    .analytics-grid {
        grid-template-columns: 1fr;
    }
    
    .goal-item {
        flex-direction: column;
        gap: 16px;
        text-align: center;
    }
    
    .progress-bar {
        width: 100%;
        max-width: 250px;
    }
}
</style>

<?php require APPROOT.'/views/stadium_owner/inc/footer.php'; ?>