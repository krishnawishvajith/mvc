<?php require APPROOT.'/views/admin/inc/header.php'; ?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>Stadium Reviews Management</h1>
        <div class="header-actions">
            <button class="btn-export-reviews" onclick="exportReviews()">📊 Export Reviews</button>
            <button class="btn-bulk-actions" onclick="openBulkActions()">⚙️ Bulk Actions</button>
        </div>
    </div>

    <!-- Reviews Stats -->
    <div class="reviews-stats">
        <div class="stat-item">
            <div class="stat-icon">⭐</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo $data['stats']['total_reviews']; ?></span>
                <span class="stat-label">Total Reviews</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">✅</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo $data['stats']['published_reviews']; ?></span>
                <span class="stat-label">Published</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">⏳</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo $data['stats']['pending_reviews']; ?></span>
                <span class="stat-label">Pending Review</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">🚩</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo $data['stats']['flagged_reviews']; ?></span>
                <span class="stat-label">Flagged</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">📊</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo $data['stats']['average_rating']; ?></span>
                <span class="stat-label">Average Rating</span>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="filters-section">
        <div class="filter-group">
            <select class="filter-select" id="statusFilter">
                <option value="">All Status</option>
                <option value="published">Published</option>
                <option value="pending">Pending</option>
                <option value="flagged">Flagged</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        <div class="filter-group">
            <select class="filter-select" id="ratingFilter">
                <option value="">All Ratings</option>
                <option value="5">5 Stars</option>
                <option value="4">4 Stars</option>
                <option value="3">3 Stars</option>
                <option value="2">2 Stars</option>
                <option value="1">1 Star</option>
            </select>
        </div>
        <div class="filter-group">
            <select class="filter-select" id="stadiumFilter">
                <option value="">All Stadiums</option>
                <option value="cricket">Cricket Grounds</option>
                <option value="football">Football Fields</option>
                <option value="tennis">Tennis Courts</option>
                <option value="basketball">Basketball Courts</option>
            </select>
        </div>
        <div class="filter-group">
            <input type="text" class="search-input" placeholder="Search reviews..." id="reviewSearch">
        </div>
    </div>

    <!-- Reviews Management Table -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Stadium Reviews</h3>
            <span class="total-count"><?php echo count($data['reviews']); ?> reviews shown</span>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                        </th>
                        <th>Review Details</th>
                        <th>Customer</th>
                        <th>Stadium</th>
                        <th>Rating</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['reviews'] as $review): ?>
                    <tr class="review-row" data-status="<?php echo strtolower($review['status']); ?>" data-rating="<?php echo $review['rating']; ?>">
                        <td>
                            <input type="checkbox" class="review-checkbox" value="<?php echo $review['id']; ?>">
                        </td>
                        <td>
                            <div class="review-details">
                                <div class="review-text">
                                    "<?php echo strlen($review['review_text']) > 100 ? substr($review['review_text'], 0, 100) . '...' : $review['review_text']; ?>"
                                </div>
                                <div class="review-meta">
                                    <?php if($review['verified_booking']): ?>
                                        <span class="verified-badge">✓ Verified Booking</span>
                                    <?php endif; ?>
                                    <span class="helpful-votes">👍 <?php echo $review['helpful_votes']; ?> helpful</span>
                                    <?php if($review['reported']): ?>
                                        <span class="reported-badge">🚩 Reported</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="customer-info">
                                <div class="customer-avatar"><?php echo substr($review['customer_name'], 0, 1); ?></div>
                                <div class="customer-details">
                                    <span class="customer-name"><?php echo $review['customer_name']; ?></span>
                                    <small class="customer-email"><?php echo $review['customer_email']; ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="stadium-info">
                                <span class="stadium-name"><?php echo $review['stadium_name']; ?></span>
                                <small class="stadium-id">ID: #<?php echo $review['stadium_id']; ?></small>
                            </div>
                        </td>
                        <td>
                            <div class="rating-display">
                                <div class="stars">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <span class="star <?php echo $i <= $review['rating'] ? 'filled' : ''; ?>">⭐</span>
                                    <?php endfor; ?>
                                </div>
                                <span class="rating-number"><?php echo $review['rating']; ?>/5</span>
                            </div>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($review['date'])); ?></td>
                        <td>
                            <span class="status-badge <?php echo strtolower($review['status']); ?>">
                                <?php echo $review['status']; ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action-sm btn-view" onclick="viewReviewDetails(<?php echo $review['id']; ?>)">View</button>
                                <?php if($review['status'] == 'Pending'): ?>
                                    <button class="btn-action-sm btn-approve" onclick="approveReview(<?php echo $review['id']; ?>)">Approve</button>
                                    <button class="btn-action-sm btn-reject" onclick="rejectReview(<?php echo $review['id']; ?>)">Reject</button>
                                <?php elseif($review['status'] == 'Published'): ?>
                                    <button class="btn-action-sm btn-hide" onclick="hideReview(<?php echo $review['id']; ?>)">Hide</button>
                                <?php elseif($review['status'] == 'Flagged'): ?>
                                    <button class="btn-action-sm btn-approve" onclick="approveReview(<?php echo $review['id']; ?>)">Approve</button>
                                    <button class="btn-action-sm btn-ban" onclick="banReview(<?php echo $review['id']; ?>)">Ban</button>
                                <?php endif; ?>
                                <button class="btn-action-sm btn-delete" onclick="deleteReview(<?php echo $review['id']; ?>)">Delete</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="dashboard-grid">
        <!-- Recent Activity -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Recent Review Activity</h3>
            </div>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon new">⭐</div>
                    <div class="activity-details">
                        <p><strong>New 5-star review</strong> for Colombo Cricket Ground</p>
                        <small>By Krishna Wishvajith • 2 hours ago</small>
                    </div>
                    <div class="activity-action">
                        <span class="rating-stars">⭐⭐⭐⭐⭐</span>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon reported">🚩</div>
                    <div class="activity-details">
                        <p><strong>Review reported</strong> for Premier Squash Courts</p>
                        <small>Inappropriate content • 5 hours ago</small>
                    </div>
                    <div class="activity-action">
                        <button class="btn-action-sm btn-review">Review</button>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon approved">✅</div>
                    <div class="activity-details">
                        <p><strong>Review approved</strong> for Tennis Academy Courts</p>
                        <small>By admin • 1 day ago</small>
                    </div>
                    <div class="activity-action">
                        <span class="status-badge published">Published</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rating Distribution -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Rating Distribution</h3>
            </div>
            <div class="rating-distribution">
                <div class="rating-bar">
                    <span class="rating-label">5 ⭐</span>
                    <div class="bar">
                        <div class="bar-fill" style="width: 65%"></div>
                    </div>
                    <span class="rating-count">102 (65%)</span>
                </div>
                <div class="rating-bar">
                    <span class="rating-label">4 ⭐</span>
                    <div class="bar">
                        <div class="bar-fill" style="width: 20%"></div>
                    </div>
                    <span class="rating-count">31 (20%)</span>
                </div>
                <div class="rating-bar">
                    <span class="rating-label">3 ⭐</span>
                    <div class="bar">
                        <div class="bar-fill" style="width: 8%"></div>
                    </div>
                    <span class="rating-count">12 (8%)</span>
                </div>
                <div class="rating-bar">
                    <span class="rating-label">2 ⭐</span>
                    <div class="bar">
                        <div class="bar-fill" style="width: 4%"></div>
                    </div>
                    <span class="rating-count">6 (4%)</span>
                </div>
                <div class="rating-bar">
                    <span class="rating-label">1 ⭐</span>
                    <div class="bar">
                        <div class="bar-fill" style="width: 3%"></div>
                    </div>
                    <span class="rating-count">5 (3%)</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Review Details Modal -->
<div id="reviewModal" class="modal">
    <div class="modal-content large">
        <div class="modal-header">
            <h3>Review Details</h3>
            <span class="close" onclick="closeReviewModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="review-full-details">
                <div class="review-header">
                    <div class="customer-info-full">
                        <div class="customer-avatar large">K</div>
                        <div class="customer-details">
                            <h4>Krishna Wishvajith</h4>
                            <p>krishna@email.com</p>
                            <span class="verified-badge">✓ Verified Customer</span>
                        </div>
                    </div>
                    <div class="review-rating-full">
                        <div class="stars large">⭐⭐⭐⭐⭐</div>
                        <span class="rating-text">5 out of 5 stars</span>
                        <small>Posted on January 20, 2025</small>
                    </div>
                </div>

                <div class="stadium-info-full">
                    <h5>Review for: <span class="stadium-name">Colombo Cricket Ground</span></h5>
                    <span class="booking-info">✓ Verified booking on January 18, 2025</span>
                </div>

                <div class="review-content-full">
                    <p>Excellent facilities and well-maintained ground. The lighting system is perfect for evening matches. Highly recommend for cricket tournaments. The staff was very professional and helpful throughout our stay.</p>
                </div>

                <div class="review-metrics">
                    <div class="metric-item">
                        <span class="metric-label">Helpful Votes:</span>
                        <span class="metric-value">15 people found this helpful</span>
                    </div>
                    <div class="metric-item">
                        <span class="metric-label">Reports:</span>
                        <span class="metric-value">0 reports</span>
                    </div>
                    <div class="metric-item">
                        <span class="metric-label">Current Status:</span>
                        <span class="status-badge published">Published</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closeReviewModal()">Close</button>
            <button class="btn-action moderate" onclick="moderateReview()">Moderate Review</button>
            <button class="btn-action delete" onclick="deleteReviewFromModal()">Delete Review</button>
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div id="bulkModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Bulk Actions</h3>
            <span class="close" onclick="closeBulkModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>Select an action for <span id="selectedCount">0</span> selected reviews:</p>
            <div class="bulk-actions">
                <button class="bulk-action-btn" onclick="bulkAction('approve')">✅ Approve Selected</button>
                <button class="bulk-action-btn" onclick="bulkAction('hide')">👁️ Hide Selected</button>
                <button class="bulk-action-btn" onclick="bulkAction('flag')">🚩 Flag Selected</button>
                <button class="bulk-action-btn danger" onclick="bulkAction('delete')">🗑️ Delete Selected</button>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closeBulkModal()">Cancel</button>
        </div>
    </div>
</div>

<script>
// Review management functions
function viewReviewDetails(id) {
    document.getElementById('reviewModal').style.display = 'block';
}

function closeReviewModal() {
    document.getElementById('reviewModal').style.display = 'none';
}

function approveReview(id) {
    if(confirm('Are you sure you want to approve this review?')) {
        alert(`Review #${id} approved successfully!`);
        // Here you would make an AJAX call to approve the review
    }
}

function rejectReview(id) {
    const reason = prompt('Please provide a reason for rejection:');
    if(reason) {
        alert(`Review #${id} rejected. Reason: ${reason}`);
        // Here you would make an AJAX call to reject the review
    }
}

function hideReview(id) {
    if(confirm('Are you sure you want to hide this review from public view?')) {
        alert(`Review #${id} hidden successfully!`);
        // Here you would make an AJAX call to hide the review
    }
}

function banReview(id) {
    if(confirm('Are you sure you want to ban this review? This action cannot be undone.')) {
        alert(`Review #${id} banned successfully!`);
        // Here you would make an AJAX call to ban the review
    }
}

function deleteReview(id) {
    if(confirm('Are you sure you want to permanently delete this review? This action cannot be undone.')) {
        alert(`Review #${id} deleted successfully!`);
        // Here you would make an AJAX call to delete the review
    }
}

function deleteReviewFromModal() {
    if(confirm('Are you sure you want to permanently delete this review?')) {
        alert('Review deleted successfully!');
        closeReviewModal();
    }
}

function moderateReview() {
    alert('Review moderation tools would be implemented here');
}

// Bulk actions
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.review-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function openBulkActions() {
    const selected = document.querySelectorAll('.review-checkbox:checked').length;
    if(selected === 0) {
        alert('Please select at least one review.');
        return;
    }
    
    document.getElementById('selectedCount').textContent = selected;
    document.getElementById('bulkModal').style.display = 'block';
}

function closeBulkModal() {
    document.getElementById('bulkModal').style.display = 'none';
}

function bulkAction(action) {
    const selected = document.querySelectorAll('.review-checkbox:checked');
    if(confirm(`Are you sure you want to ${action} ${selected.length} selected reviews?`)) {
        alert(`Bulk ${action} completed for ${selected.length} reviews!`);
        closeBulkModal();
        // Here you would make an AJAX call to perform the bulk action
    }
}

function exportReviews() {
    alert('Exporting reviews to CSV...');
    // Here you would implement CSV export functionality
}

// Filter functionality
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value.toLowerCase();
    const rows = document.querySelectorAll('.review-row');
    
    rows.forEach(row => {
        if(status === '' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

document.getElementById('ratingFilter').addEventListener('change', function() {
    const rating = this.value;
    const rows = document.querySelectorAll('.review-row');
    
    rows.forEach(row => {
        if(rating === '' || row.dataset.rating === rating) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Search functionality
document.getElementById('reviewSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('.review-row');
    
    rows.forEach(row => {
        const reviewText = row.querySelector('.review-text').textContent.toLowerCase();
        const customerName = row.querySelector('.customer-name').textContent.toLowerCase();
        const stadiumName = row.querySelector('.stadium-name').textContent.toLowerCase();
        
        if(reviewText.includes(searchTerm) || customerName.includes(searchTerm) || stadiumName.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Close modals when clicking outside
window.onclick = function(event) {
    const reviewModal = document.getElementById('reviewModal');
    const bulkModal = document.getElementById('bulkModal');
    
    if (event.target == reviewModal) {
        reviewModal.style.display = "none";
    }
    if (event.target == bulkModal) {
        bulkModal.style.display = "none";
    }
}
</script>

<style>
.reviews-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-item {
    background: #000000ec;
    padding: 20px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-icon {
    font-size: 32px;
    width: 60px;
    height: 60px;
    background: rgba(3, 178, 0, 0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-details {
    display: flex;
    flex-direction: column;
}

.stat-number {
    font-size: 24px;
    font-weight: bold;
    color: #ffffff;
    margin-bottom: 5px;
}

.stat-label {
    color: #ffffff;
    font-size: 14px;
}

.review-details {
    max-width: 300px;
}

.review-text {
    color: #ffffff;
    font-size: 14px;
    line-height: 1.4;
    margin-bottom: 8px;
}

.review-meta {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.verified-badge, .reported-badge {
    background: #28a745;
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 500;
}

.reported-badge {
    background: #dc3545;
}

.helpful-votes {
    background: #f8f9fa;
    color: #495057;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 11px;
}

.customer-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.customer-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #007bff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}

.customer-avatar.large {
    width: 50px;
    height: 50px;
    font-size: 20px;
}

.customer-details {
    display: flex;
    flex-direction: column;
}

.customer-name {
    color: #ffffff;
    font-weight: 500;
    font-size: 14px;
}

.customer-email {
    color: #666;
    font-size: 12px;
}

.stadium-info {
    display: flex;
    flex-direction: column;
}

.stadium-name {
    color: #ffffff;
    font-weight: 500;
    font-size: 14px;
}

.stadium-id {
    color: #666;
    font-size: 12px;
}

.rating-display {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
}

.stars {
    display: flex;
    gap: 2px;
}

.stars.large {
    font-size: 20px;
}

.star {
    font-size: 16px;
    color: #ddd;
}

.star.filled {
    color: #ffc107;
}

.rating-number {
    color: #ffffff;
    font-size: 12px;
    font-weight: 500;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.published { background: #e8f5e8; color: #388e3c; }
.status-badge.pending { background: #fff3e0; color: #f57c00; }
.status-badge.flagged { background: #ffebee; color: #d32f2f; }
.status-badge.rejected { background: #fafafa; color: #757575; }

.filters-section {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.filter-group {
    min-width: 200px;
}

.filter-select, .search-input {
    width: 100%;
    padding: 10px;
    border: 2px solid #e1e5e9;
    border-radius: 6px;
    font-size: 14px;
}

.filter-select:focus, .search-input:focus {
    outline: none;
    border-color: #007bff;
}

.dashboard-card {
    background: #000000ec;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #333;
}

.card-header h3 {
    color: #ffffff;
    margin: 0;
    font-size: 20px;
}

.total-count {
    color: #666;
    font-size: 14px;
}

.table-container {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #333;
}

.data-table th {
    background: #080808ff;
    font-weight: 600;
    color: #ffffff;
    font-size: 14px;
}

.action-buttons {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

.btn-action-sm {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-view { background: #17a2b8; color: white; }
.btn-view:hover { background: #138496; }

.btn-approve { background: #28a745; color: white; }
.btn-approve:hover { background: #218838; }

.btn-reject { background: #ffc107; color: black; }
.btn-reject:hover { background: #e0a800; }

.btn-hide { background: #6c757d; color: white; }
.btn-hide:hover { background: #5a6268; }

.btn-ban { background: #fd7e14; color: white; }
.btn-ban:hover { background: #e8650e; }

.btn-delete { background: #dc3545; color: white; }
.btn-delete:hover { background: #c82333; }

.btn-export-reviews, .btn-bulk-actions {
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-left: 10px;
}

.btn-export-reviews {
    background: #17a2b8;
    color: white;
}

.btn-export-reviews:hover {
    background: #138496;
    transform: translateY(-2px);
}

.btn-bulk-actions {
    background: #6c757d;
    color: white;
}

.btn-bulk-actions:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

.dashboard-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-top: 30px;
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #1a1a1a;
    border-radius: 8px;
    border-left: 4px solid transparent;
}

.activity-item .activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.activity-icon.new { background: rgba(40, 167, 69, 0.2); }
.activity-icon.reported { background: rgba(220, 53, 69, 0.2); }
.activity-icon.approved { background: rgba(23, 162, 184, 0.2); }

.activity-details {
    flex: 1;
}

.activity-details p {
    margin: 0 0 5px 0;
    color: #ffffff;
    font-size: 14px;
}

.activity-details small {
    color: #666;
    font-size: 12px;
}

.activity-action {
    display: flex;
    align-items: center;
}

.rating-stars {
    font-size: 14px;
}

.rating-distribution {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.rating-bar {
    display: flex;
    align-items: center;
    gap: 15px;
}

.rating-label {
    min-width: 40px;
    color: #ffffff;
    font-size: 14px;
}

.bar {
    flex: 1;
    height: 8px;
    background: #333;
    border-radius: 4px;
    overflow: hidden;
}

.bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #ffc107, #ff8f00);
    border-radius: 4px;
    transition: width 0.3s ease;
}

.rating-count {
    min-width: 80px;
    color: #ffffff;
    font-size: 12px;
    text-align: right;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: #000000ec;
    margin: 5% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-content.large {
    max-width: 800px;
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #333;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: #ffffff;
    font-size: 20px;
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s;
}

.close:hover {
    color: #ffffff;
}

.modal-body {
    padding: 20px;
}

.modal-actions {
    padding: 20px;
    border-top: 1px solid #333;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.review-full-details {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
}

.customer-info-full {
    display: flex;
    align-items: center;
    gap: 15px;
}

.customer-info-full .customer-details h4 {
    margin: 0 0 5px 0;
    color: #ffffff;
    font-size: 18px;
}

.customer-info-full .customer-details p {
    margin: 0 0 8px 0;
    color: #666;
    font-size: 14px;
}

.review-rating-full {
    text-align: right;
}

.review-rating-full .rating-text {
    display: block;
    color: #ffffff;
    font-size: 16px;
    margin: 8px 0 5px 0;
}

.review-rating-full small {
    color: #666;
    font-size: 12px;
}

.stadium-info-full h5 {
    margin: 0 0 8px 0;
    color: #ffffff;
    font-size: 16px;
}

.stadium-info-full .stadium-name {
    color: #03B200;
    font-weight: bold;
}

.booking-info {
    color: #28a745;
    font-size: 14px;
}

.review-content-full p {
    color: #ffffff;
    line-height: 1.6;
    font-size: 15px;
    margin: 0;
}

.review-metrics {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 15px;
    background: #1a1a1a;
    border-radius: 8px;
}

.metric-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.metric-label {
    color: #666;
    font-size: 14px;
}

.metric-value {
    color: #ffffff;
    font-size: 14px;
    font-weight: 500;
}

.bulk-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin: 20px 0;
}

.bulk-action-btn {
    padding: 12px 20px;
    border: 1px solid #333;
    border-radius: 6px;
    background: #1a1a1a;
    color: #ffffff;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s;
    text-align: left;
}

.bulk-action-btn:hover {
    background: #333;
    border-color: #007bff;
}

.bulk-action-btn.danger {
    border-color: #dc3545;
    color: #dc3545;
}

.bulk-action-btn.danger:hover {
    background: #dc3545;
    color: white;
}

.btn-cancel {
    padding: 10px 20px;
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

.btn-cancel:hover {
    background: #5a6268;
}

.btn-action {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    margin-left: 10px;
}

.btn-action.moderate {
    background: #ffc107;
    color: black;
}

.btn-action.moderate:hover {
    background: #e0a800;
}

.btn-action.delete {
    background: #dc3545;
    color: white;
}

.btn-action.delete:hover {
    background: #c82333;
}

@media (max-width: 1024px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .review-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .review-rating-full {
        text-align: left;
    }
}

@media (max-width: 768px) {
    .reviews-stats {
        grid-template-columns: 1fr;
    }
    
    .filters-section {
        flex-direction: column;
    }
    
    .action-buttons {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-action-sm {
        width: 100%;
        margin-bottom: 5px;
    }
    
    .dashboard-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    
    .header-actions {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .btn-export-reviews, .btn-bulk-actions {
        width: 100%;
        margin-left: 0;
    }
}