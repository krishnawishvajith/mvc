<?php require APPROOT.'/views/admin/inc/header.php'; ?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>Edit Stadium Listing</h1>
        <div class="header-actions">
            <a href="<?php echo URLROOT; ?>/admin/listings" class="btn-back">← Back to Listings</a>
            <button class="btn-preview" onclick="previewListing()">👁️ Preview</button>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if(isset($data['success']) && !empty($data['success'])): ?>
        <div class="alert alert-success">
            <?php echo $data['success']; ?>
        </div>
    <?php endif; ?>

    <?php if(isset($data['error']) && !empty($data['error'])): ?>
        <div class="alert alert-error">
            <?php echo $data['error']; ?>
        </div>
    <?php endif; ?>

    <div class="edit-listing-layout">
        <!-- Main Form -->
        <div class="edit-form-section">
            <form class="edit-listing-form" method="POST" action="<?php echo URLROOT; ?>/admin/edit_listing/<?php echo $data['listing']['id']; ?>">
                
                <!-- Basic Information -->
                <div class="form-card">
                    <div class="card-header">
                        <h3>Basic Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Stadium Name</label>
                                <input type="text" name="name" value="<?php echo $data['listing']['name']; ?>" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Owner Name</label>
                                <input type="text" name="owner" value="<?php echo $data['listing']['owner']; ?>" class="form-control" readonly>
                                <small class="form-help">Owner information cannot be changed</small>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Sport Type</label>
                                <select name="type" class="form-control" required>
                                    <option value="Cricket" <?php echo $data['listing']['type'] == 'Cricket' ? 'selected' : ''; ?>>Cricket</option>
                                    <option value="Football" <?php echo $data['listing']['type'] == 'Football' ? 'selected' : ''; ?>>Football</option>
                                    <option value="Tennis" <?php echo $data['listing']['type'] == 'Tennis' ? 'selected' : ''; ?>>Tennis</option>
                                    <option value="Basketball" <?php echo $data['listing']['type'] == 'Basketball' ? 'selected' : ''; ?>>Basketball</option>
                                    <option value="Swimming" <?php echo $data['listing']['type'] == 'Swimming' ? 'selected' : ''; ?>>Swimming</option>
                                    <option value="Badminton" <?php echo $data['listing']['type'] == 'Badminton' ? 'selected' : ''; ?>>Badminton</option>
                                    <option value="Multi-Sport" <?php echo $data['listing']['type'] == 'Multi-Sport' ? 'selected' : ''; ?>>Multi-Sport</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <select name="category" class="form-control" required>
                                    <option value="Indoor" <?php echo $data['listing']['category'] == 'Indoor' ? 'selected' : ''; ?>>Indoor</option>
                                    <option value="Outdoor" <?php echo $data['listing']['category'] == 'Outdoor' ? 'selected' : ''; ?>>Outdoor</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Price per Hour (LKR)</label>
                                <input type="number" name="price" value="<?php echo $data['listing']['price']; ?>" class="form-control" required min="0" step="50">
                            </div>
                            <div class="form-group">
                                <label>Location</label>
                                <input type="text" name="location" value="<?php echo $data['listing']['location']; ?>" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Full Address</label>
                            <textarea name="address" class="form-control" rows="3" required><?php echo $data['listing']['address']; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="4" required><?php echo $data['listing']['description']; ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Features & Amenities -->
                <div class="form-card">
                    <div class="card-header">
                        <h3>Features & Amenities</h3>
                    </div>
                    <div class="card-body">
                        <div class="features-grid">
                            <?php 
                            $availableFeatures = ['Lighting', 'Parking', 'WiFi', 'Changing Rooms', 'Air Conditioning', 'Equipment Rental', 'Cafeteria', 'Security', 'First Aid', 'Sound System', 'Seating', 'Lockers'];
                            foreach($availableFeatures as $feature): 
                            ?>
                            <label class="feature-checkbox">
                                <input type="checkbox" 
                                       name="features[]" 
                                       value="<?php echo $feature; ?>"
                                       <?php echo in_array($feature, $data['listing']['features']) ? 'checked' : ''; ?>>
                                <span class="checkmark"></span>
                                <?php echo $feature; ?>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Status & Settings -->
                <div class="form-card">
                    <div class="card-header">
                        <h3>Status & Settings</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Listing Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="Active" <?php echo $data['listing']['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="Inactive" <?php echo $data['listing']['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                                    <option value="Maintenance" <?php echo $data['listing']['status'] == 'Maintenance' ? 'selected' : ''; ?>>Under Maintenance</option>
                                    <option value="Suspended" <?php echo $data['listing']['status'] == 'Suspended' ? 'selected' : ''; ?>>Suspended</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Visibility</label>
                                <div class="checkbox-group">
                                    <label class="checkbox-label">
                                        <input type="checkbox" 
                                               name="featured" 
                                               value="1"
                                               <?php echo $data['listing']['featured'] ? 'checked' : ''; ?>>
                                        <span class="checkmark"></span>
                                        Featured Listing
                                    </label>
                                    <small class="form-help">Featured listings appear prominently on the homepage</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image Management -->
                <div class="form-card">
                    <div class="card-header">
                        <h3>Images</h3>
                    </div>
                    <div class="card-body">
                        <div class="current-images">
                            <h4>Current Images</h4>
                            <div class="images-grid">
                                <?php foreach($data['listing']['images'] as $index => $image): ?>
                                <div class="image-item">
                                    <img src="<?php echo URLROOT; ?>/images/stadiums/<?php echo $image; ?>" alt="Stadium Image">
                                    <div class="image-actions">
                                        <button type="button" class="btn-action-sm btn-delete" onclick="removeImage('<?php echo $image; ?>')">Remove</button>
                                        <?php if($index == 0): ?>
                                        <span class="primary-badge">Primary</span>
                                        <?php else: ?>
                                        <button type="button" class="btn-action-sm btn-primary" onclick="setPrimary('<?php echo $image; ?>')">Set Primary</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="upload-section">
                            <h4>Add New Images</h4>
                            <div class="file-upload">
                                <input type="file" name="new_images[]" multiple accept="image/*" id="newImages">
                                <label for="newImages" class="upload-area">
                                    <div class="upload-icon">📷</div>
                                    <p>Click to upload or drag and drop</p>
                                    <small>PNG, JPG up to 5MB each (Max 10 images)</small>
                                </label>
                            </div>
                            <div class="upload-preview" id="uploadPreview"></div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="window.location.href='<?php echo URLROOT; ?>/admin/listings'">Cancel</button>
                    <button type="submit" class="btn-save">Save Changes</button>
                </div>
            </form>
        </div>

        <!-- Sidebar Info -->
        <div class="listing-sidebar">
            <!-- Listing Statistics -->
            <div class="sidebar-card">
                <div class="card-header">
                    <h4>Listing Statistics</h4>
                </div>
                <div class="card-body">
                    <div class="stat-row">
                        <span class="stat-label">Total Views:</span>
                        <span class="stat-value"><?php echo $data['listing']['views']; ?></span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">Total Bookings:</span>
                        <span class="stat-value"><?php echo $data['listing']['bookings']; ?></span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">Created:</span>
                        <span class="stat-value"><?php echo date('M j, Y', strtotime($data['listing']['created'])); ?></span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label">Status:</span>
                        <span class="status-badge <?php echo strtolower($data['listing']['status']); ?>">
                            <?php echo $data['listing']['status']; ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Owner Information -->
            <div class="sidebar-card">
                <div class="card-header">
                    <h4>Owner Information</h4>
                </div>
                <div class="card-body">
                    <div class="owner-profile">
                        <div class="owner-avatar large"><?php echo substr($data['listing']['owner'], 0, 1); ?></div>
                        <div class="owner-details">
                            <h5><?php echo $data['listing']['owner']; ?></h5>
                            <p><?php echo $data['listing']['owner_email']; ?></p>
                        </div>
                    </div>
                    <div class="owner-actions">
                        <button class="btn-contact" onclick="contactOwner()">📧 Contact Owner</button>
                        <button class="btn-view-profile" onclick="viewOwnerProfile()">👤 View Profile</button>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="sidebar-card">
                <div class="card-header">
                    <h4>Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <button class="action-btn" onclick="viewPublicListing()">
                            <span class="action-icon">👁️</span>
                            View Public Page
                        </button>
                        <button class="action-btn" onclick="duplicateListing()">
                            <span class="action-icon">📋</span>
                            Duplicate Listing
                        </button>
                        <button class="action-btn" onclick="exportListing()">
                            <span class="action-icon">📊</span>
                            Export Data
                        </button>
                        <button class="action-btn danger" onclick="deleteListing()">
                            <span class="action-icon">🗑️</span>
                            Delete Listing
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Image upload preview
document.getElementById('newImages').addEventListener('change', function() {
    const preview = document.getElementById('uploadPreview');
    preview.innerHTML = '';
    
    Array.from(this.files).forEach(file => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'preview-item';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="remove-preview" onclick="this.parentElement.remove()">×</button>
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        }
    });
});

// Image management functions
function removeImage(image) {
    if(confirm('Are you sure you want to remove this image?')) {
        alert(`Image ${image} will be removed`);
        // Here you would make an AJAX call to remove the image
    }
}

function setPrimary(image) {
    if(confirm('Set this as the primary image for the listing?')) {
        alert(`${image} set as primary image`);
        // Here you would make an AJAX call to set primary image
    }
}

// Quick actions
function previewListing() {
    window.open(`<?php echo URLROOT; ?>/stadiums/single/<?php echo $data['listing']['id']; ?>`, '_blank');
}

function contactOwner() {
    window.location.href = 'mailto:<?php echo $data['listing']['owner_email']; ?>?subject=Regarding your stadium listing';
}

function viewOwnerProfile() {
    alert('Owner profile view functionality');
    // window.location.href = '<?php echo URLROOT; ?>/admin/users/view/owner_id';
}

function viewPublicListing() {
    window.open(`<?php echo URLROOT; ?>/stadiums/single/<?php echo $data['listing']['id']; ?>`, '_blank');
}

function duplicateListing() {
    if(confirm('Create a duplicate of this listing?')) {
        alert('Listing duplicated successfully');
        // Here you would make an AJAX call to duplicate the listing
    }
}

function exportListing() {
    alert('Exporting listing data...');
    // Here you would implement export functionality
}

function deleteListing() {
    if(confirm('Are you sure you want to delete this listing? This action cannot be undone.')) {
        if(confirm('This will permanently remove the listing and all associated data. Continue?')) {
            alert('Listing deleted successfully');
            // window.location.href = '<?php echo URLROOT; ?>/admin/listings';
        }
    }
}

// Form validation
document.querySelector('.edit-listing-form').addEventListener('submit', function(e) {
    const price = document.querySelector('input[name="price"]').value;
    if (price <= 0) {
        e.preventDefault();
        alert('Price must be greater than 0');
        return;
    }
    
    const features = document.querySelectorAll('input[name="features[]"]:checked');
    if (features.length === 0) {
        e.preventDefault();
        alert('Please select at least one feature');
        return;
    }
});
</script>

<style>
.edit-listing-layout {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 30px;
    margin-top: 20px;
}

.form-card {
    background: #000000ec;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-card .card-header {
    padding: 20px;
    border-bottom: 1px solid #3b3b3bff;
}

.form-card .card-header h3 {
    margin: 0;
    color: #ffffff;
    font-size: 18px;
}

.form-card .card-body {
    padding: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    color: #ffffff;
    font-weight: 500;
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 2px solid #e1e5e9;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.2s;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: #007bff;
}

.form-control[readonly] {
    background-color: #f8f9fa;
    color: #6c757d;
}

.form-help {
    display: block;
    margin-top: 4px;
    color: #666;
    font-size: 12px;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.feature-checkbox {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #ffffff;
    cursor: pointer;
    font-size: 14px;
}

.feature-checkbox input[type="checkbox"] {
    width: 18px;
    height: 18px;
}

.checkbox-group {
    margin-top: 10px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #ffffff;
    cursor: pointer;
    font-size: 14px;
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
}

.images-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.image-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #e1e5e9;
}

.image-item img {
    width: 100%;
    height: 120px;
    object-fit: cover;
}

.image-actions {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.8));
    padding: 10px;
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

.primary-badge {
    background: #28a745;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: bold;
}

.file-upload {
    margin-bottom: 20px;
}

.upload-area {
    display: block;
    border: 2px dashed #ddd;
    border-radius: 8px;
    padding: 30px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.upload-area:hover {
    border-color: #007bff;
    background: #f0f8ff;
}

.upload-icon {
    font-size: 48px;
    margin-bottom: 10px;
}

.upload-preview {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 10px;
}

.preview-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
}

.preview-item img {
    width: 100%;
    height: 80px;
    object-fit: cover;
}

.remove-preview {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(255,0,0,0.8);
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    cursor: pointer;
    font-weight: bold;
}

.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    padding-top: 24px;
    border-top: 1px solid #eee;
    margin-top: 30px;
}

.btn-cancel, .btn-save {
    padding: 12px 24px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-cancel {
    background: #f8f9fa;
    color: #666;
    border: 1px solid #dee2e6;
}

.btn-cancel:hover {
    background: #e9ecef;
}

.btn-save {
    background: #007bff;
    color: white;
}

.btn-save:hover {
    background: #0056b3;
}

.btn-back, .btn-preview {
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.2s;
}

.btn-back {
    background: #f8f9fa;
    color: #666;
    border: 1px solid #dee2e6;
}

.btn-preview {
    background: #17a2b8;
    color: white;
    border: none;
    cursor: pointer;
}

/* Sidebar Styles */
.listing-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.sidebar-card {
    background: #000000ec;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.sidebar-card .card-header {
    padding: 15px 20px;
    border-bottom: 1px solid #3b3b3bff;
}

.sidebar-card .card-header h4 {
    margin: 0;
    color: #ffffff;
    font-size: 16px;
}

.sidebar-card .card-body {
    padding: 20px;
}

.stat-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.stat-row:last-child {
    border-bottom: none;
}

.stat-label {
    color: #ffffff;
    font-size: 14px;
}

.stat-value {
    color: #ffffff;
    font-weight: 600;
    font-size: 14px;
}

.owner-profile {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.owner-avatar.large {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #007bff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 20px;
}

.owner-details h5 {
    margin: 0 0 5px 0;
    color: #ffffff;
    font-size: 16px;
}

.owner-details p {
    margin: 0;
    color: #ffffff;
    font-size: 14px;
}

.owner-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn-contact, .btn-view-profile {
    padding: 10px 15px;
    border: none;
    border-radius: 6px;
    background: #007bff;
    color: white;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s;
}

.btn-contact:hover, .btn-view-profile:hover {
    background: #0056b3;
}

.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 15px;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    background: #f8f9fa;
    color: #495057;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s;
    text-align: left;
}

.action-btn:hover {
    background: #e9ecef;
    border-color: #007bff;
}

.action-btn.danger {
    border-color: #dc3545;
    color: #dc3545;
}

.action-btn.danger:hover {
    background: #f8d7da;
    border-color: #dc3545;
}

.action-icon {
    width: 20px;
    text-align: center;
}

.alert {
    margin-bottom: 20px;
    padding: 12px;
    border-radius: 6px;
    font-size: 14px;
}

.alert-success {
    background: rgba(0, 255, 0, 0.1);
    border: 1px solid #28a745;
    color: #28a745;
}

.alert-error {
    background: rgba(255, 0, 0, 0.1);
    border: 1px solid #ff4444;
    color: #ff6666;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.active { background: #e8f5e8; color: #388e3c; }
.status-badge.inactive { background: #fafafa; color: #757575; }
.status-badge.maintenance { background: #fff3e0; color: #f57c00; }
.status-badge.suspended { background: #ffebee; color: #d32f2f; }

@media (max-width: 1024px) {
    .edit-listing-layout {
        grid-template-columns: 1fr;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require APPROOT.'/views/admin/inc/footer.php'; ?>