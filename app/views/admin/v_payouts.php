<?php require APPROOT.'/views/admin/inc/header.php'; ?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>Payout Management</h1>
        <div class="header-actions">
            <button class="btn-release-all" onclick="releaseAllPayouts()">💰 Release All Pending</button>
        </div>
    </div>

    <!-- Payout Stats -->
    <div class="payout-stats">
        <div class="stat-item">
            <div class="stat-icon">⏳</div>
            <div class="stat-details">
                <span class="stat-number">LKR 114,000</span>
                <span class="stat-label">Pending Payouts</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">✅</div>
            <div class="stat-details">
                <span class="stat-number">LKR 890,000</span>
                <span class="stat-label">Released This Month</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">💼</div>
            <div class="stat-details">
                <span class="stat-number">LKR 178,000</span>
                <span class="stat-label">Admin Commission</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">📊</div>
            <div class="stat-details">
                <span class="stat-number">23</span>
                <span class="stat-label">Active Partners</span>
            </div>
        </div>
    </div>

    <!-- Pending Payouts -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Pending Payouts</h3>
            <span class="badge pending">3 pending releases</span>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Stadium Owner</th>
                        <th>Stadium</th>
                        <th>Bookings</th>
                        <th>Gross Amount</th>
                        <th>Commission (20%)</th>
                        <th>Net Payout</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['pending_payouts'] as $payout): ?>
                    <tr>
                        <td>
                            <div class="owner-info">
                                <div class="owner-avatar"><?php echo substr($payout['owner'], 0, 1); ?></div>
                                <span><?php echo $payout['owner']; ?></span>
                            </div>
                        </td>
                        <td><?php echo $payout['stadium']; ?></td>
                        <td>
                            <span class="booking-count"><?php echo $payout['total_bookings']; ?> bookings</span>
                        </td>
                        <td>LKR <?php echo number_format($payout['gross_amount']); ?></td>
                        <td>LKR <?php echo number_format($payout['commission']); ?></td>
                        <td>
                            <strong class="payout-amount">LKR <?php echo number_format($payout['net_payout']); ?></strong>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action-sm btn-view" onclick="viewPayoutDetails('<?php echo $payout['owner']; ?>')">View</button>
                                <button class="btn-action-sm btn-release" onclick="releasePayout('<?php echo $payout['owner']; ?>', <?php echo $payout['net_payout']; ?>)">Release</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payout History -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Payout History</h3>
            <div class="filter-actions">
                <select class="filter-select">
                    <option value="week">This Week</option>
                    <option value="month" selected>This Month</option>
                    <option value="quarter">This Quarter</option>
                    <option value="year">This Year</option>
                </select>
            </div>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Stadium Owner</th>
                        <th>Amount</th>
                        <th>Release Date</th>
                        <th>Status</th>
                        <th>Transaction ID</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['completed_payouts'] as $completed): ?>
                    <tr>
                        <td>
                            <div class="owner-info">
                                <div class="owner-avatar"><?php echo substr($completed['owner'], 0, 1); ?></div>
                                <span><?php echo $completed['owner']; ?></span>
                            </div>
                        </td>
                        <td>LKR <?php echo number_format($completed['amount']); ?></td>
                        <td><?php echo $completed['date']; ?></td>
                        <td>
                            <span class="status-badge completed"><?php echo $completed['status']; ?></span>
                        </td>
                        <td>TXN<?php echo rand(100000, 999999); ?></td>
                        <td>
                            <button class="btn-action-sm btn-receipt" onclick="downloadReceipt('TXN<?php echo rand(100000, 999999); ?>')">Receipt</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Weekly Payout Schedule -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Weekly Payout Schedule</h3>
            <span class="schedule-info">Next payout: Friday, August 23rd</span>
        </div>
        <div class="schedule-content">
            <div class="schedule-item">
                <div class="schedule-day">
                    <span class="day-name">Monday</span>
                    <span class="day-date">Aug 19</span>
                </div>
                <div class="schedule-info">
                    <span class="schedule-status">No payouts scheduled</span>
                </div>
            </div>
            
            <div class="schedule-item">
                <div class="schedule-day">
                    <span class="day-name">Tuesday</span>
                    <span class="day-date">Aug 20</span>
                </div>
                <div class="schedule-info">
                    <span class="schedule-status">No payouts scheduled</span>
                </div>
            </div>
            
            <div class="schedule-item">
                <div class="schedule-day">
                    <span class="day-name">Wednesday</span>
                    <span class="day-date">Aug 21</span>
                </div>
                <div class="schedule-info">
                    <span class="schedule-status">No payouts scheduled</span>
                </div>
            </div>
            
            <div class="schedule-item active">
                <div class="schedule-day">
                    <span class="day-name">Friday</span>
                    <span class="day-date">Aug 23</span>
                </div>
                <div class="schedule-info">
                    <span class="schedule-amount">LKR 114,000</span>
                    <span class="schedule-status">Weekly payout to 3 partners</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payout Details Modal -->
<div id="payoutModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Payout Details</h3>
            <span class="close" onclick="closePayoutModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="payout-summary">
                <h4 id="modalOwnerName">Stadium Owner 1</h4>
                <p id="modalStadiumName">Colombo Cricket Ground</p>
                
                <div class="summary-grid">
                    <div class="summary-item">
                        <label>Total Bookings:</label>
                        <span id="modalBookings">12 bookings</span>
                    </div>
                    <div class="summary-item">
                        <label>Gross Revenue:</label>
                        <span id="modalGross">LKR 60,000</span>
                    </div>
                    <div class="summary-item">
                        <label>Admin Commission (20%):</label>
                        <span id="modalCommission">LKR 12,000</span>
                    </div>
                    <div class="summary-item">
                        <label>Net Payout:</label>
                        <span id="modalNet" class="highlight">LKR 48,000</span>
                    </div>
                </div>
                
                <div class="booking-breakdown">
                    <h5>Recent Bookings Breakdown:</h5>
                    <div class="booking-list">
                        <div class="booking-item">
                            <span>BK0045 - John Doe</span>
                            <span>LKR 5,000</span>
                        </div>
                        <div class="booking-item">
                            <span>BK0043 - Sarah Johnson</span>
                            <span>LKR 5,000</span>
                        </div>
                        <div class="booking-item">
                            <span>BK0041 - Mike Wilson</span>
                            <span>LKR 5,000</span>
                        </div>
                        <div class="booking-more">
                            <span>+ 9 more bookings</span>
                            <span>LKR 45,000</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closePayoutModal()">Close</button>
            <button class="btn-release-single" onclick="confirmPayout()">Release Payout</button>
        </div>
    </div>
</div>

<script>
function viewPayoutDetails(owner) {
    document.getElementById('modalOwnerName').textContent = owner;
    document.getElementById('payoutModal').style.display = 'block';
}

function closePayoutModal() {
    document.getElementById('payoutModal').style.display = 'none';
}

function releasePayout(owner, amount) {
    if(confirm(`Are you sure you want to release LKR ${amount.toLocaleString()} to ${owner}?`)) {
        alert(`Payout of LKR ${amount.toLocaleString()} released to ${owner}`);
        // Here you would make an AJAX call to release the payout
    }
}

function releaseAllPayouts() {
    if(confirm('Are you sure you want to release all pending payouts totaling LKR 114,000?')) {
        alert('All pending payouts have been released successfully!');
        // Here you would make an AJAX call to release all payouts
    }
}

function confirmPayout() {
    alert('Payout confirmed and released!');
    closePayoutModal();
}

function downloadReceipt(txnId) {
    alert(`Downloading receipt for transaction ${txnId}`);
    // Here you would generate and download the receipt
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('payoutModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<?php require APPROOT.'/views/admin/inc/footer.php'; ?>