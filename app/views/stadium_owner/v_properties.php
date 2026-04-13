<?php require APPROOT.'/views/stadium_owner/inc/header.php'; ?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>My Properties</h1>
        <div class="header-actions">
            <?php if(isset($data['package_limits']['can_add_more']) && $data['package_limits']['can_add_more']): ?>
            <a href="<?php echo URLROOT; ?>/stadium_owner/add_property" class="btn-add-property">🏟️ Add New Stadium</a>
            <?php else: ?>
            <span class="limit-reached-msg">Stadium limit reached for your package</span>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Success Message -->
    <?php if(isset($_GET['success']) && $_GET['success'] === 'added'): ?>
    <div class="alert alert-success" id="successAlert">
        <div class="alert-icon">✅</div>
        <div class="alert-content">
            <strong>Success!</strong> Your stadium has been submitted for review. You'll receive an email notification once it's approved.
        </div>
        <button class="alert-close" onclick="closeAlert()">&times;</button>
    </div>
    <?php endif; ?>

    <!-- Package Limits Info -->
    <?php if(isset($data['package_limits'])): ?>
    <div class="package-limits-banner">
        <div class="limits-info">
            <div class="limit-item">
                <span class="limit-label">Stadiums:</span>
                <span class="limit-value"><?php echo $data['package_limits']['current_stadiums'] ?? 0; ?>/<?php echo $data['package_limits']['stadium_limit'] ?? 0; ?></span>
            </div>
            <div class="limit-item">
                <span class="limit-label">Photos per stadium:</span>
                <span class="limit-value"><?php echo $data['package_limits']['photos_per_stadium'] ?? 0; ?></span>
            </div>
            <div class="limit-item">
                <span class="limit-label">Videos per stadium:</span>
                <span class="limit-value"><?php echo $data['package_limits']['videos_per_stadium'] ?? 0; ?></span>
            </div>
        </div>
        <a href="<?php echo URLROOT; ?>/pricing" class="btn-upgrade">Upgrade Package</a>
    </div>
    <?php endif; ?>

    <!-- Properties Grid -->
    <div class="properties-grid">
        <?php foreach($data['properties'] as $property): ?>
            
        <div class="property-card">
            <!-- Property Image -->
            <div class="property-image">    
                <?php
                // Check if image is from new upload system (starts with 'stadium_')
                if (strpos($property['image'], 'stadium_') === 0) {
                    $imageSrc = URLROOT . '/public/uploads/stadiums/' . $property['image'];
                } else {
                    $imageSrc = URLROOT . '/images/stadiums/' . $property['image'];
                }
                ?>
                <img src="<?php echo $imageSrc; ?>" 
                     alt="<?php echo $property['name']; ?>"
                     onerror="this.src='<?php echo URLROOT; ?>/images/stadiums/default-stadium.jpg'">
                
                <!-- Approval Status Badge -->
                <?php if(isset($property['approval_status'])): ?>
                <div class="approval-badge approval-<?php echo strtolower($property['approval_status']); ?>">
                    <?php 
                    if ($property['approval_status'] === 'pending') echo '⏳ Pending Review';
                    elseif ($property['approval_status'] === 'approved') echo '✅ Approved';
                    elseif ($property['approval_status'] === 'rejected') echo '❌ Rejected';
                    else echo ucfirst($property['approval_status']);
                    ?>
                </div>
                <?php endif; ?>
                
                <!-- Status Badge -->
                <div class="status-badge status-<?php echo strtolower($property['status']); ?>">
                    <?php echo $property['status']; ?>
                </div>
                
                <!-- Category Badge -->
                <div class="category-badge">
                    <?php echo $property['category']; ?>
                </div>
                
                <!-- Rating Badge -->
                <div class="rating-badge">
                    <span class="star">⭐</span>
                    <span class="rating"><?php echo $property['rating']; ?></span>
                </div>
            </div>
            
            <!-- Property Info -->
            <div class="property-info">
                <div class="property-header">
                    <h3 class="property-name"><?php echo $property['name']; ?></h3>
                    <div class="property-price">
                        <span class="currency">LKR </span>
                        <span class="amount"><?php echo number_format($property['price']); ?></span>
                        <span class="period">per hour</span>
                    </div>
                </div>
                
                <div class="property-location">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                    <span><?php echo $property['location']; ?></span>
                </div>
                
                <!-- Rejection Reason (if rejected) -->
                <?php if(isset($property['approval_status']) && $property['approval_status'] === 'rejected' && !empty($property['rejection_reason'])): ?>
                <div class="rejection-notice">
                    <strong>⚠️ Rejection Reason:</strong>
                    <p><?php echo htmlspecialchars($property['rejection_reason']); ?></p>
                </div>
                <?php endif; ?>
                
                <!-- Property Stats -->
                 <div class="property-stats">
                    <div class="stat-item">
                        <div class="stat-icon">📅</div>
                        <div class="stat-details">
                            <span class="stat-number"><?php echo $property['total_bookings']; ?></span>
                            <span class="stat-label">Total Bookings</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">💰</div>
                        <div class="stat-details">
                            <span class="stat-number">LKR <?php echo number_format($property['monthly_revenue']); ?></span>
                            <span class="stat-label">Monthly Revenue</span>
                        </div>
                    </div>
                </div>
                 
                <!-- Property Type -->
                <div class="property-type">
                    <span class="type-badge <?php echo strtolower($property['type']); ?>">
                        <?php 
                        $icon = '';
                        switch(strtolower($property['type'])) {
                            case 'cricket': $icon = '🏏'; break;
                            case 'football': $icon = '⚽'; break;
                            case 'tennis': $icon = '🎾'; break;
                            case 'basketball': $icon = '🏀'; break;
                            default: $icon = '🏆'; break;
                        }
                        echo $icon . ' ' . $property['type'];
                        ?>
                    </span>
                </div>
                
                <!-- Action Buttons -->
                <div class="property-actions">
                    <button class="btn-view" onclick="viewPropertyDetails(<?php echo $property['id']; ?>)">
                        View Details
                    </button>
                    <a href="<?php echo URLROOT; ?>/stadium_owner/edit_property/<?php echo $property['id']; ?>" class="btn-edit">
                        Edit
                    </a>
                    <button class="btn-toggle" onclick="togglePropertyStatus(<?php echo $property['id']; ?>, '<?php echo $property['status']; ?>')">
                        <?php echo $property['status'] === 'Active' ? 'Deactivate' : 'Activate'; ?>
                    </button>
                    <button class="btn-delete" onclick="deleteProperty(<?php echo $property['id']; ?>)">
                        Delete
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <!-- Add New Property Card (if within limits) -->
        <?php if(isset($data['package_limits']['can_add_more']) && $data['package_limits']['can_add_more']): ?>
        <div class="property-card add-new-card">
            <div class="add-new-content">
                <div class="add-icon">➕</div>
                <h3>Add New Property</h3>
                <p>List your stadium or sports facility</p>
                <a href="<?php echo URLROOT; ?>/stadium_owner/add_property" class="btn-add-new">Add Property</a>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Properties Performance Table -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Properties Performance</h3>
            <div class="performance-filters">
                <select class="filter-select">
                    <option value="this_month">This Month</option>
                    <option value="last_month">Last Month</option>
                    <option value="last_3_months">Last 3 Months</option>
                </select>
            </div>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Bookings</th>
                        <th>Revenue</th>
                        <th>Rating</th>
                        <th>Occupancy</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['properties'] as $property): ?>
                    <tr>
                        <td>
                            <div class="property-cell">
                                <?php
                                // Check if image is from new upload system (starts with 'stadium_')
                                if (strpos($property['image'], 'stadium_') === 0) {
                                    $imageSrc = URLROOT . '/public/uploads/stadiums/' . $property['image'];
                                } else {
                                    $imageSrc = URLROOT . '/images/stadiums/' . $property['image'];
                                }
                                ?>
                                <img src="<?php echo $imageSrc; ?>" 
                                     alt="<?php echo $property['name']; ?>" class="property-thumb"
                                     onerror="this.src='<?php echo URLROOT; ?>/images/stadiums/default-stadium.jpg'">
                                <div class="property-details">
                                    <strong><?php echo $property['name']; ?></strong>
                                    <small><?php echo $property['location']; ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="type-badge <?php echo strtolower($property['type']); ?>">
                                <?php echo $property['type']; ?>
                            </span>
                        </td>
                        <td>
                            <span class="status-badge <?php echo strtolower($property['status']); ?>">
                                <?php echo $property['status']; ?>
                            </span>
                        </td>
                        <td><?php echo $property['total_bookings']; ?></td>
                        <td>LKR <?php echo number_format($property['monthly_revenue']); ?></td>
                        <td>
                            <div class="rating-cell">
                                ⭐ <?php echo $property['rating']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="occupancy-bar">
                                <?php $occupancy = rand(60, 90); ?>
                                <div class="occupancy-fill" style="width: <?php echo $occupancy; ?>%"></div>
                                <span class="occupancy-text"><?php echo $occupancy; ?>%</span>
                            </div>
                        </td>
                        <td>
                            <div class="table-actions">
                                <button class="btn-action-sm btn-view" onclick="viewPropertyDetails(<?php echo $property['id']; ?>)">View</button>
                                <a href="<?php echo URLROOT; ?>/stadium_owner/edit_property/<?php echo $property['id']; ?>" class="btn-action-sm btn-edit">Edit</a>
                                <button class="btn-action-sm btn-toggle" onclick="togglePropertyStatus(<?php echo $property['id']; ?>, '<?php echo $property['status']; ?>')">
                                    <?php echo $property['status'] === 'Active' ? 'Hide' : 'Show'; ?>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
/* Success Alert Styles */
.alert {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    position: relative;
    transition: opacity 0.3s ease;
}

.alert-success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.alert-icon {
    font-size: 24px;
}

.alert-content {
    flex: 1;
}

.alert-content strong {
    font-weight: 700;
}

.alert-close {
    background: none;
    border: none;
    font-size: 24px;
    color: #155724;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: background 0.2s ease;
}

.alert-close:hover {
    background: rgba(0, 0, 0, 0.1);
}

/* Approval Status Badge Styles */
.approval-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    z-index: 10;
    backdrop-filter: blur(10px);
}

.approval-pending {
    background: rgba(255, 152, 0, 0.9);
    color: white;
}

.approval-approved {
    background: rgba(3, 178, 0, 0.9);
    color: white;
}

.approval-rejected {
    background: rgba(244, 67, 54, 0.9);
    color: white;
}

.rejection-notice {
    background: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 8px;
    padding: 12px;
    margin-top: 10px;
    color: #856404;
}

.rejection-notice strong {
    display: block;
    margin-bottom: 5px;
    color: #721c24;
}

.rejection-notice p {
    margin: 0;
    font-size: 13px;
    line-height: 1.5;
}
</style>

<!-- Property Details Modal -->
<div id="propertyModal" class="modal">
    <div class="modal-content large">
        <div class="modal-header">
            <h3>Property Details</h3>
            <span class="close" onclick="closePropertyModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="property-details-content">
                <div class="property-images">
                    <div class="main-image">
                        <img id="modalPropertyImage" src="" alt="Property Image">
                    </div>
                </div>
                <div class="property-info-details">
                    <h2 id="modalPropertyName"></h2>
                    <div class="property-meta">
                        <span id="modalPropertyType" class="type-badge"></span>
                        <span id="modalPropertyCategory" class="category-badge"></span>
                        <span id="modalPropertyStatus" class="status-badge"></span>
                    </div>
                    <div class="property-price-info">
                        <span class="price-label">Price per hour:</span>
                        <span id="modalPropertyPrice" class="price-value"></span>
                    </div>
                    <div class="property-location-info">
                        <span class="location-label">Location:</span>
                        <span id="modalPropertyLocation"></span>
                    </div>
                    <div class="property-stats-grid">
                        <div class="stat-box">
                            <div class="stat-value" id="modalTotalBookings"></div>
                            <div class="stat-label">Total Bookings</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-value" id="modalMonthlyRevenue"></div>
                            <div class="stat-label">Monthly Revenue</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-value" id="modalRating"></div>
                            <div class="stat-label">Rating</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closePropertyModal()">Close</button>
            <a id="modalEditBtn" href="#" class="btn-edit-modal">Edit Property</a>
        </div>
    </div>
</div>

<script>
function viewPropertyDetails(id) {
    // In a real application, this would fetch property details via AJAX
    // For demo purposes, we'll populate with sample data
    
    const properties = <?php echo json_encode($data['properties']); ?>;
    const property = properties.find(p => p.id == id);
    
    if (property) {
        document.getElementById('modalPropertyImage').src = '<?php echo URLROOT; ?>/images/stadiums/' + property.image;
        document.getElementById('modalPropertyName').textContent = property.name;
        document.getElementById('modalPropertyType').textContent = property.type;
        document.getElementById('modalPropertyType').className = 'type-badge ' + property.type.toLowerCase();
        document.getElementById('modalPropertyCategory').textContent = property.category;
        document.getElementById('modalPropertyStatus').textContent = property.status;
        document.getElementById('modalPropertyStatus').className = 'status-badge ' + property.status.toLowerCase();
        document.getElementById('modalPropertyPrice').textContent = 'LKR ' + property.price.toLocaleString();
        document.getElementById('modalPropertyLocation').textContent = property.location;
        document.getElementById('modalTotalBookings').textContent = property.total_bookings;
        document.getElementById('modalMonthlyRevenue').textContent = 'LKR ' + property.monthly_revenue.toLocaleString();
        document.getElementById('modalRating').textContent = '⭐ ' + property.rating;
        document.getElementById('modalEditBtn').href = '<?php echo URLROOT; ?>/stadium_owner/edit_property/' + property.id;
        
        document.getElementById('propertyModal').style.display = 'block';
    }
}

function closePropertyModal() {
    document.getElementById('propertyModal').style.display = 'none';
}

function togglePropertyStatus(id, currentStatus) {
    const newStatus = currentStatus === 'Active' ? 'Inactive' : 'Active';
    const action = currentStatus === 'Active' ? 'deactivate' : 'activate';
    
    if(confirm(`Are you sure you want to ${action} this property?`)) {
        alert(`Property ${action}d successfully!`);
        location.reload();
    }
}

function deleteProperty(id) {
    if(confirm('Are you sure you want to delete this property? This action cannot be undone.')) {
        fetch('<?php echo URLROOT; ?>/stadium_owner/delete_property/' + id, {
            method: 'POST'
        })
        .then(response => {
            if(response.ok) {
                alert('Property deleted successfully!');
                location.reload();
            } else {
                alert('Failed to delete property. Please try again.');
            }
        })
        .catch(error => {
            alert('An error occurred while deleting the property.');
            console.error('Error:', error);
        });
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('propertyModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Auto-hide success alert after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const successAlert = document.getElementById('successAlert');
    if (successAlert) {
        setTimeout(function() {
            successAlert.style.opacity = '0';
            setTimeout(function() {
                successAlert.style.display = 'none';
                // Clean URL without refreshing
                const url = new URL(window.location);
                url.searchParams.delete('success');
                window.history.replaceState({}, '', url);
            }, 300);
        }, 5000);
    }
});

function closeAlert() {
    const successAlert = document.getElementById('successAlert');
    if (successAlert) {
        successAlert.style.opacity = '0';
        setTimeout(function() {
            successAlert.style.display = 'none';
            // Clean URL without refreshing
            const url = new URL(window.location);
            url.searchParams.delete('success');
            window.history.replaceState({}, '', url);
        }, 300);
    }
}
</script>

<?php require APPROOT.'/views/stadium_owner/inc/footer.php'; ?>