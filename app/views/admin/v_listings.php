<?php require APPROOT.'/views/admin/inc/header.php'; ?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>Stadium Listings Management</h1>
        <div class="header-actions">
            <button class="btn-add-listing" onclick="addNewListing()">+ Add New Listing</button>
            <button class="btn-bulk-actions" onclick="openBulkActions()">⚙️ Bulk Actions</button>
        </div>
    </div>

    <!-- Listings Stats -->
    <div class="listings-stats">
        <div class="stat-item">
            <div class="stat-icon">🏟️</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo $data['statistics']['total_listings']; ?></span>
                <span class="stat-label">Total Listings</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">✅</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo $data['statistics']['active_listings']; ?></span>
                <span class="stat-label">Active Listings</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">⏳</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo $data['statistics']['pending_approval']; ?></span>
                <span class="stat-label">Pending Approval</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">⭐</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo $data['statistics']['featured_listings']; ?></span>
                <span class="stat-label">Featured</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">💰</div>
            <div class="stat-details">
                <span class="stat-number">LKR <?php echo number_format($data['statistics']['this_month_revenue']); ?></span>
                <span class="stat-label">Monthly Revenue</span>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="filters-section">
        <div class="filter-group">
            <select class="filter-select" id="statusFilter">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="pending">Pending</option>
                <option value="expired">Expired</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>
        <div class="filter-group">
            <select class="filter-select" id="typeFilter">
                <option value="">All Types</option>
                <option value="cricket">Cricket</option>
                <option value="football">Football</option>
                <option value="tennis">Tennis</option>
                <option value="basketball">Basketball</option>
                <option value="swimming">Swimming</option>
            </select>
        </div>
        <div class="filter-group">
            <select class="filter-select" id="categoryFilter">
                <option value="">All Categories</option>
                <option value="indoor">Indoor</option>
                <option value="outdoor">Outdoor</option>
            </select>
        </div>
        <div class="filter-group">
            <input type="text" class="search-input" placeholder="Search listings..." id="listingSearch">
        </div>
    </div>

    <!-- Listing Tabs -->
    <div class="listing-tabs">
        <button class="tab-btn active" data-tab="active">Active Listings (<?php echo count($data['active_listings']); ?>)</button>
        <button class="tab-btn" data-tab="pending">Pending Approval (<?php echo count($data['pending_listings']); ?>)</button>
        <button class="tab-btn" data-tab="expired">Expired Listings (<?php echo count($data['expired_listings']); ?>)</button>
    </div>

    <!-- Active Listings Tab -->
    <div class="tab-content active" id="active-tab">
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Active Stadium Listings</h3>
                <div class="header-actions">
                    <button class="btn-export" onclick="exportListings('active')">📊 Export</button>
                </div>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAllActive" onchange="toggleSelectAll('active')"></th>
                            <th>Listing</th>
                            <th>Owner</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Performance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['active_listings'] as $listing): ?>
                        <tr class="listing-row" data-status="<?php echo strtolower($listing['status']); ?>" data-type="<?php echo strtolower($listing['type']); ?>">
                            <td><input type="checkbox" class="listing-checkbox" value="<?php echo $listing['id']; ?>"></td>
                            <td>
                                <div class="listing-info">
                                    <div class="listing-image">
                                        <img src="<?php echo URLROOT; ?>/images/stadiums/<?php echo strtolower(str_replace(' ', '-', $listing['name'])); ?>.jpg" 
                                             alt="<?php echo $listing['name']; ?>" 
                                             onerror="this.src='<?php echo URLROOT; ?>/images/stadiums/default.jpg'">
                                        <?php if($listing['featured']): ?>
                                            <span class="featured-badge">⭐</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="listing-details">
                                        <h4><?php echo $listing['name']; ?></h4>
                                        <p class="listing-location">📍 <?php echo $listing['location']; ?></p>
                                        <p class="listing-meta">
                                            <span class="category-badge <?php echo strtolower($listing['category']); ?>">
                                                <?php echo $listing['category']; ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="owner-info">
                                    <div class="owner-avatar"><?php echo substr($listing['owner'], 0, 1); ?></div>
                                    <span><?php echo $listing['owner']; ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="type-badge <?php echo strtolower($listing['type']); ?>">
                                    <?php echo $listing['type']; ?>
                                </span>
                            </td>
                            <td><strong>LKR <?php echo number_format($listing['price']); ?></strong></td>
                            <td>
                                <div class="performance-metrics">
                                    <div class="metric">
                                        <span class="metric-value"><?php echo $listing['views']; ?></span>
                                        <span class="metric-label">views</span>
                                    </div>
                                    <div class="metric">
                                        <span class="metric-value"><?php echo $listing['bookings']; ?></span>
                                        <span class="metric-label">bookings</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge active"><?php echo $listing['status']; ?></span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action-sm btn-edit" onclick="editListing(<?php echo $listing['id']; ?>)">Edit</button>
                                    <button class="btn-action-sm btn-view" onclick="viewListing(<?php echo $listing['id']; ?>)">View</button>
                                    <?php if($listing['featured']): ?>
                                        <button class="btn-action-sm btn-unfeature" onclick="toggleFeature(<?php echo $listing['id']; ?>, false)">Unfeature</button>
                                    <?php else: ?>
                                        <button class="btn-action-sm btn-feature" onclick="toggleFeature(<?php echo $listing['id']; ?>, true)">Feature</button>
                                    <?php endif; ?>
                                    <button class="btn-action-sm btn-suspend" onclick="suspendListing(<?php echo $listing['id']; ?>)">Suspend</button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pending Listings Tab -->
    <div class="tab-content" id="pending-tab">
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Pending Approval</h3>
                <span class="pending-count"><?php echo count($data['pending_listings']); ?> listings awaiting review</span>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Listing</th>
                            <th>Owner</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Submitted</th>
                            <th>Review Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['pending_listings'] as $listing): ?>
                        <tr>
                            <td>
                                <div class="listing-info">
                                    <div class="listing-image">
                                        <img src="<?php echo URLROOT; ?>/images/stadiums/placeholder.jpg" alt="<?php echo $listing['name']; ?>">
                                        <span class="pending-overlay">📋</span>
                                    </div>
                                    <div class="listing-details">
                                        <h4><?php echo $listing['name']; ?></h4>
                                        <p class="listing-location">📍 <?php echo $listing['location']; ?></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="owner-info">
                                    <div class="owner-avatar"><?php echo substr($listing['owner'], 0, 1); ?></div>
                                    <span><?php echo $listing['owner']; ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="type-badge <?php echo strtolower($listing['type']); ?>">
                                    <?php echo $listing['type']; ?>
                                </span>
                            </td>
                            <td><strong>LKR <?php echo number_format($listing['price']); ?></strong></td>
                            <td><?php echo $listing['submitted']; ?></td>
                            <td>
                                <span class="review-note"><?php echo $listing['reason']; ?></span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action-sm btn-approve" onclick="approveListing(<?php echo $listing['id']; ?>)">Approve</button>
                                    <button class="btn-action-sm btn-edit" onclick="editListing(<?php echo $listing['id']; ?>)">Review</button>
                                    <button class="btn-action-sm btn-reject" onclick="rejectListing(<?php echo $listing['id']; ?>)">Reject</button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Expired Listings Tab -->
    <div class="tab-content" id="expired-tab">
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Expired Listings</h3>
                <div class="header-actions">
                    <button class="btn-cleanup" onclick="cleanupExpired()">🗑️ Cleanup Old</button>
                </div>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Listing</th>
                            <th>Former Owner</th>
                            <th>Type</th>
                            <th>Expired Date</th>
                            <th>Last Booking</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['expired_listings'] as $listing): ?>
                        <tr>
                            <td>
                                <div class="listing-info">
                                    <div class="listing-image">
                                        <img src="<?php echo URLROOT; ?>/images/stadiums/placeholder.jpg" alt="<?php echo $listing['name']; ?>">
                                        <span class="expired-overlay">⏰</span>
                                    </div>
                                    <div class="listing-details">
                                        <h4><?php echo $listing['name']; ?></h4>
                                        <p class="listing-location">📍 <?php echo $listing['location']; ?></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="owner-info">
                                    <div class="owner-avatar"><?php echo substr($listing['owner'], 0, 1); ?></div>
                                    <span><?php echo $listing['owner']; ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="type-badge <?php echo strtolower($listing['type']); ?>">
                                    <?php echo $listing['type']; ?>
                                </span>
                            </td>
                            <td><span class="expired-date"><?php echo $listing['expired']; ?></span></td>
                            <td><?php echo $listing['last_booking']; ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action-sm btn-reactivate" onclick="reactivateListing(<?php echo $listing['id']; ?>)">Reactivate</button>
                                    <button class="btn-action-sm btn-view" onclick="viewListing(<?php echo $listing['id']; ?>)">View</button>
                                    <button class="btn-action-sm btn-delete" onclick="deleteListing(<?php echo $listing['id']; ?>)">Delete</button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Recent Listing Activity</h3>
        </div>
        <div class="activity-list">
            <div class="activity-item">
                <div class="activity-icon new">🆕</div>
                <div class="activity-details">
                    <p><strong>New listing submitted:</strong> "Premium Basketball Court"</p>
                    <small>By Kevin Rodrigo • 2 hours ago</small>
                </div>
                <div class="activity-action">
                    <button class="btn-action-sm btn-review">Review</button>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon update">✏️</div>
                <div class="activity-details">
                    <p><strong>Listing updated:</strong> "Football Arena Pro" - price changed</p>
                    <small>By David Fernando • 5 hours ago</small>
                </div>
                <div class="activity-action">
                    <button class="btn-action-sm btn-view">View</button>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon feature">⭐</div>
                <div class="activity-details">
                    <p><strong>Listing featured:</strong> "Tennis Academy Courts"</p>
                    <small>By Admin • 1 day ago</small>
                </div>
                <div class="activity-action">
                    <span class="status-badge featured">Featured</span>
                </div>
            </div>
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
            <p>Select an action for <span id="selectedCount">0</span> selected listings:</p>
            <div class="bulk-actions">
                <button class="bulk-action-btn" onclick="bulkAction('feature')">⭐ Feature Selected</button>
                <button class="bulk-action-btn" onclick="bulkAction('unfeature')">⭐ Remove Feature</button>
                <button class="bulk-action-btn" onclick="bulkAction('suspend')">⏸️ Suspend Selected</button>
                <button class="bulk-action-btn" onclick="bulkAction('activate')">✅ Activate Selected</button>
                <button class="bulk-action-btn danger" onclick="bulkAction('delete')">🗑️ Delete Selected</button>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closeBulkModal()">Cancel</button>
        </div>
    </div>
</div>

<script>
// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const tabId = this.dataset.tab;
        
        // Update active tab button
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        // Update active tab content
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        document.getElementById(tabId + '-tab').classList.add('active');
    });
});

// Listing actions
function editListing(id) {
    window.location.href = `<?php echo URLROOT; ?>/admin/edit_listing/${id}`;
}

function viewListing(id) {
    window.open(`<?php echo URLROOT; ?>/stadiums/single/${id}`, '_blank');
}

function approveListing(id) {
    if(confirm('Are you sure you want to approve this listing?')) {
        alert(`Listing #${id} approved successfully!`);
        // Here you would make an AJAX call to approve the listing
    }
}

function rejectListing(id) {
    const reason = prompt('Please provide a reason for rejection:');
    if(reason) {
        alert(`Listing #${id} rejected. Reason: ${reason}`);
        // Here you would make an AJAX call to reject the listing
    }
}

function toggleFeature(id, feature) {
    const action = feature ? 'feature' : 'unfeature';
    if(confirm(`Are you sure you want to ${action} this listing?`)) {
        alert(`Listing #${id} ${action}d successfully!`);
        // Here you would make an AJAX call to toggle feature status
    }
}

function suspendListing(id) {
    if(confirm('Are you sure you want to suspend this listing?')) {
        alert(`Listing #${id} suspended successfully!`);
        // Here you would make an AJAX call to suspend the listing
    }
}

function reactivateListing(id) {
    if(confirm('Are you sure you want to reactivate this expired listing?')) {
        alert(`Listing #${id} reactivated successfully!`);
        // Here you would make an AJAX call to reactivate the listing
    }
}

function deleteListing(id) {
    if(confirm('Are you sure you want to permanently delete this listing? This action cannot be undone.')) {
        alert(`Listing #${id} deleted successfully!`);
        // Here you would make an AJAX call to delete the listing
    }
}

// Bulk actions
function toggleSelectAll(tab) {
    const checkboxes = document.querySelectorAll(`#${tab}-tab .listing-checkbox`);
    const selectAll = document.getElementById(`selectAll${tab.charAt(0).toUpperCase() + tab.slice(1)}`);
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function openBulkActions() {
    const selected = document.querySelectorAll('.listing-checkbox:checked').length;
    if(selected === 0) {
        alert('Please select at least one listing.');
        return;
    }
    
    document.getElementById('selectedCount').textContent = selected;
    document.getElementById('bulkModal').style.display = 'block';
}

function closeBulkModal() {
    document.getElementById('bulkModal').style.display = 'none';
}

function bulkAction(action) {
    const selected = document.querySelectorAll('.listing-checkbox:checked');
    if(confirm(`Are you sure you want to ${action} ${selected.length} selected listings?`)) {
        alert(`Bulk ${action} completed for ${selected.length} listings!`);
        closeBulkModal();
        // Here you would make an AJAX call to perform the bulk action
    }
}

// Add new listing
function addNewListing() {
    alert('Add new listing functionality will redirect to a form page');
    // window.location.href = '<?php echo URLROOT; ?>/admin/add_listing';
}

// Export functions
function exportListings(type) {
    alert(`Exporting ${type} listings to CSV...`);
    // Here you would implement CSV export functionality
}

function cleanupExpired() {
    if(confirm('Are you sure you want to cleanup old expired listings? This will permanently remove listings expired more than 6 months ago.')) {
        alert('Cleanup completed. Old expired listings have been removed.');
        // Here you would make an AJAX call to cleanup expired listings
    }
}

// Filter functionality
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value.toLowerCase();
    const rows = document.querySelectorAll('.listing-row');
    
    rows.forEach(row => {
        if(status === '' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

document.getElementById('typeFilter').addEventListener('change', function() {
    const type = this.value.toLowerCase();
    const rows = document.querySelectorAll('.listing-row');
    
    rows.forEach(row => {
        if(type === '' || row.dataset.type === type) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Search functionality
document.getElementById('listingSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('.listing-row');
    
    rows.forEach(row => {
        const listingName = row.querySelector('.listing-details h4').textContent.toLowerCase();
        const ownerName = row.querySelector('.owner-info span').textContent.toLowerCase();
        
        if(listingName.includes(searchTerm) || ownerName.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>

<?php require APPROOT.'/views/admin/inc/footer.php'; ?>