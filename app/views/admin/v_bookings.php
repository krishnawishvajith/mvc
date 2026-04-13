<?php require APPROOT.'/views/admin/inc/header.php'; ?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>Booking Management</h1>
        <div class="header-actions">
            <button class="btn-export">📊 Export Data</button>
        </div>
    </div>

    <!-- Booking Stats -->
    <div class="booking-stats">
        <div class="stat-item">
            <div class="stat-icon">✅</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo isset($data['status_counts']['completed']) ? $data['status_counts']['completed'] : 0; ?></span>
                <span class="stat-label">Completed</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">⏳</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo isset($data['status_counts']['pending']) ? $data['status_counts']['pending'] : 0; ?></span>
                <span class="stat-label">Pending</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">❌</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo isset($data['status_counts']['cancelled']) ? $data['status_counts']['cancelled'] : 0; ?></span>
                <span class="stat-label">Cancelled</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">💰</div>
            <div class="stat-details">
                <span class="stat-number">LKR <?php echo number_format((int)$data['total_revenue']); ?></span>
                <span class="stat-label">Total Revenue</span>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <div class="filter-group">
            <select class="filter-select" id="statusFilter">
                <option value="">All Status</option>
                <option value="completed">Completed</option>
                <option value="pending">Pending</option>
                <option value="cancelled">Cancelled</option>
                <option value="refunded">Refunded</option>
            </select>
        </div>
        <div class="filter-group">
            <select class="filter-select" id="dateFilter">
                <option value="">All Time</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
                <option value="custom">Custom Range</option>
            </select>
        </div>
        <div class="filter-group">
            <input type="text" class="search-input" placeholder="Search bookings..." id="bookingSearch">
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>All Bookings</h3>
            <span class="total-count"><?php echo isset($data['total_bookings']) ? $data['total_bookings'] : 0; ?> total bookings</span>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Stadium</th>
                        <th>Customer</th>
                        <th>Date & Time</th>
                        <th>Duration</th>
                        <th>Amount</th>
                        <th>Commission</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($data['bookings']) && count($data['bookings']) > 0): ?>
                        <?php foreach($data['bookings'] as $booking): ?>
                        <tr>
                            <td>#BK<?php echo str_pad($booking->id, 4, '0', STR_PAD_LEFT); ?></td>
                            <td>
                                <div class="stadium-info">
                                    <strong><?php echo htmlspecialchars($booking->stadium_name); ?></strong>
                                    <small><?php echo htmlspecialchars($booking->location ?? 'Not specified'); ?></small>
                                </div>
                            </td>
                            <td>
                                <div class="customer-info">
                                    <strong><?php echo htmlspecialchars($booking->customer_first_name . ' ' . $booking->customer_last_name); ?></strong>
                                    <small><?php echo htmlspecialchars($booking->customer_email ?? 'N/A'); ?></small>
                                </div>
                            </td>
                            <td>
                                <div class="datetime-info">
                                    <strong><?php echo date('M d, Y', strtotime($booking->booking_date)); ?></strong>
                                    <small><?php echo date('g:i A', strtotime($booking->start_time)) . ' - ' . date('g:i A', strtotime($booking->end_time)); ?></small>
                                </div>
                            </td>
                            <td><?php echo number_format($booking->duration_hours, 1); ?> hours</td>
                            <td>LKR <?php echo number_format($booking->total_price); ?></td>
                            <td>LKR <?php echo number_format($booking->total_price * 0.2); ?></td>
                            <td>
                                <span class="status-badge <?php echo strtolower($booking->status); ?>">
                                    <?php echo ucfirst($booking->status); ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge <?php echo strtolower($booking->payment_status); ?>">
                                    <?php echo ucfirst($booking->payment_status); ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action-sm btn-view" onclick="viewBookingDetails('<?php echo base64_encode(json_encode($booking)); ?>')">View</button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" style="text-align: center; padding: 40px;">
                                <p style="color: #999; font-size: 16px;">No bookings found</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Recent Activity</h3>
        </div>
        <div class="activity-list">
            <div class="activity-item">
                <div class="activity-icon completed">✅</div>
                <div class="activity-details">
                    <p><strong>Booking #BK0045</strong> completed at Colombo Cricket Ground</p>
                    <small>2 minutes ago</small>
                </div>
                <div class="activity-amount">+LKR 1,000</div>
            </div>
            <div class="activity-item">
                <div class="activity-icon pending">⏳</div>
                <div class="activity-details">
                    <p><strong>New booking #BK0046</strong> pending approval</p>
                    <small>5 minutes ago</small>
                </div>
                <div class="activity-amount">LKR 3,500</div>
            </div>
            <div class="activity-item">
                <div class="activity-icon refund">💸</div>
                <div class="activity-details">
                    <p><strong>Refund processed</strong> for booking #BK0032</p>
                    <small>15 minutes ago</small>
                </div>
                <div class="activity-amount">-LKR 800</div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Details Modal -->
<div id="bookingModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Booking Details</h3>
            <span class="close" onclick="closeBookingModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="booking-details" id="bookingDetailsContent">
                <!-- Booking details will be populated here by JavaScript -->
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closeBookingModal()">Close</button>
        </div>
    </div>
</div>

<script>
function viewBookingDetails(encodedData) {
    try {
        const bookingData = JSON.parse(atob(encodedData));
        
        const startTime = new Date('2000-01-01 ' + bookingData.start_time).toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
        
        const endTime = new Date('2000-01-01 ' + bookingData.end_time).toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
        
        const commission = parseFloat(bookingData.total_price) * 0.2;
        const ownerPayout = parseFloat(bookingData.total_price) - commission;
        
        let html = `
            <div class="detail-row">
                <label>Booking ID:</label>
                <span>#BK${String(bookingData.id).padStart(4, '0')}</span>
            </div>
            <div class="detail-row">
                <label>Stadium:</label>
                <span>${escapeHtml(bookingData.stadium_name)}</span>
            </div>
            <div class="detail-row">
                <label>Location:</label>
                <span>${escapeHtml(bookingData.location || 'Not specified')}</span>
            </div>
            <div class="detail-row">
                <label>Customer:</label>
                <span>${escapeHtml(bookingData.customer_first_name + ' ' + bookingData.customer_last_name)} (${escapeHtml(bookingData.customer_email)})</span>
            </div>
            <div class="detail-row">
                <label>Phone:</label>
                <span>${escapeHtml(bookingData.customer_phone || 'N/A')}</span>
            </div>
            <div class="detail-row">
                <label>Booking Date:</label>
                <span>${new Date(bookingData.booking_date).toLocaleDateString('en-US', {year: 'numeric', month: 'long', day: 'numeric'})}</span>
            </div>
            <div class="detail-row">
                <label>Time:</label>
                <span>${startTime} - ${endTime}</span>
            </div>
            <div class="detail-row">
                <label>Duration:</label>
                <span>${parseFloat(bookingData.duration_hours).toFixed(1)} hours</span>
            </div>
            <div class="detail-row">
                <label>Total Amount:</label>
                <span>LKR ${Number(bookingData.total_price).toLocaleString()}</span>
            </div>
            <div class="detail-row">
                <label>Admin Commission (20%):</label>
                <span>LKR ${commission.toLocaleString()}</span>
            </div>
            <div class="detail-row">
                <label>Stadium Owner Payout:</label>
                <span>LKR ${ownerPayout.toLocaleString()}</span>
            </div>
            <div class="detail-row">
                <label>Booking Status:</label>
                <span class="status-badge ${bookingData.status.toLowerCase()}">${bookingData.status.toUpperCase()}</span>
            </div>
            <div class="detail-row">
                <label>Payment Status:</label>
                <span class="status-badge ${bookingData.payment_status.toLowerCase()}">${bookingData.payment_status.toUpperCase()}</span>
            </div>
        `;
        
        document.getElementById('bookingDetailsContent').innerHTML = html;
        document.getElementById('bookingModal').style.display = 'block';
    } catch (e) {
        console.error('Error loading booking:', e);
        alert('Error loading booking details');
    }
}

function escapeHtml(text) {
    if (!text) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.toString().replace(/[&<>"']/g, m => map[m]);
}

function closeBookingModal() {
    document.getElementById('bookingModal').style.display = 'none';
}

function processRefund(id) {
    if(confirm('Are you sure you want to process refund for booking #' + id + '?')) {
        alert('Refund processed for booking #' + id);
        // Here you would make an AJAX call to process the refund
    }
}

// Filter functionality
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    // Filter table rows based on status
    console.log('Filtering by status:', status);
});

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('bookingModal');
    if (event.target === modal) {
        closeBookingModal();
    }
}

document.getElementById('dateFilter').addEventListener('change', function() {
    const dateRange = this.value;
    // Filter table rows based on date range
    console.log('Filtering by date:', dateRange);
});

document.getElementById('bookingSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    // Search through table rows
    console.log('Searching for:', searchTerm);
});

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('bookingModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Export functionality
document.querySelector('.btn-export').addEventListener('click', function() {
    alert('Export functionality will be implemented');
});
</script>