<?php require APPROOT.'/views/stadium_owner/inc/header.php'; ?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>Edit Property</h1>
        <div class="header-actions">
            <a href="<?php echo URLROOT; ?>/stadium_owner/properties" class="btn-back">← Back to Properties</a>
        </div>
    </div>

    <!-- Edit Property Form -->
    <div class="edit-property-container">
        <div class="property-form-wrapper">
            <?php if(isset($data['error']) && !empty($data['error'])): ?>
                <div class="error-message">
                    <?php echo $data['error']; ?>
                </div>
            <?php endif; ?>

            <?php if(isset($data['success']) && !empty($data['success'])): ?>
                <div class="success-message">
                    <?php echo $data['success']; ?>
                </div>
            <?php endif; ?>

            <form class="edit-property-form" method="POST" action="<?php echo URLROOT; ?>/stadium_owner/edit_property/<?php echo $data['property']['id'] ?? ''; ?>" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $data['property']['id'] ?? ''; ?>">    

                <!-- Basic Information -->
                <div class="form-section">
                    <h3 class="section-title">Basic Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="property-name">Property Name *</label>
                            <input type="text" 
                                   id="property-name" 
                                   name="name" 
                                   placeholder="e.g., Central Cricket Ground"
                                   value="<?php echo $data['property']['name'] ?? ''; ?>"
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label for="property-type">Sports Type *</label>
                            <select id="property-type" name="type" required>
                                <option value="">Select sports type</option>
                                <option value="Cricket" <?php echo (isset($data['property']['type']) && $data['property']['type'] == 'Cricket') ? 'selected' : ''; ?>>Cricket</option>
                                <option value="Football" <?php echo (isset($data['property']['type']) && $data['property']['type'] == 'Football') ? 'selected' : ''; ?>>Football</option>
                                <option value="Tennis" <?php echo (isset($data['property']['type']) && $data['property']['type'] == 'Tennis') ? 'selected' : ''; ?>>Tennis</option>
                                <option value="Basketball" <?php echo (isset($data['property']['type']) && $data['property']['type'] == 'Basketball') ? 'selected' : ''; ?>>Basketball</option>
                                <option value="Badminton" <?php echo (isset($data['property']['type']) && $data['property']['type'] == 'Badminton') ? 'selected' : ''; ?>>Badminton</option>
                                <option value="Swimming" <?php echo (isset($data['property']['type']) && $data['property']['type'] == 'Swimming') ? 'selected' : ''; ?>>Swimming</option>
                                <option value="Volleyball" <?php echo (isset($data['property']['type']) && $data['property']['type'] == 'Volleyball') ? 'selected' : ''; ?>>Volleyball</option>
                                <option value="Multi-Sport" <?php echo (isset($data['property']['type']) && $data['property']['type'] == 'Multi-Sport') ? 'selected' : ''; ?>>Multi-Sport</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="property-category">Category *</label>
                            <select id="property-category" name="category" required>
                                <option value="">Select category</option>
                                <option value="Indoor" <?php echo (isset($data['property']['category']) && $data['property']['category'] == 'Indoor') ? 'selected' : ''; ?>>Indoor</option>
                                <option value="Outdoor" <?php echo (isset($data['property']['category']) && $data['property']['category'] == 'Outdoor') ? 'selected' : ''; ?>>Outdoor</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="property-price">Price per Hour (LKR) *</label>
                            <input type="number" 
                                   id="property-price" 
                                   name="price" 
                                   placeholder="e.g., 5000"
                                   min="100"
                                   step="100"
                                   value="<?php echo $data['property']['price'] ?? ''; ?>"
                                   required>
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div class="form-section">
                    <h3 class="section-title">Location Information</h3>
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="property-location">Full Address *</label>
                            <input type="text" 
                                   id="property-location" 
                                   name="location" 
                                   placeholder="e.g., 123 Galle Road, Colombo 03"
                                   value="<?php echo $data['property']['location'] ?? ''; ?>"
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label for="property-district">District *</label>
                            <select id="property-district" name="district" required>
                                <option value="">Select district</option>
                                <option value="Colombo" <?php echo (isset($data['property']['district']) && $data['property']['district'] == 'Colombo') ? 'selected' : ''; ?>>Colombo</option>
                                <option value="Kandy" <?php echo (isset($data['property']['district']) && $data['property']['district'] == 'Kandy') ? 'selected' : ''; ?>>Kandy</option>
                                <option value="Galle" <?php echo (isset($data['property']['district']) && $data['property']['district'] == 'Galle') ? 'selected' : ''; ?>>Galle</option>
                                <option value="Jaffna" <?php echo (isset($data['property']['district']) && $data['property']['district'] == 'Jaffna') ? 'selected' : ''; ?>>Jaffna</option>
                                <option value="Negombo" <?php echo (isset($data['property']['district']) && $data['property']['district'] == 'Negombo') ? 'selected' : ''; ?>>Negombo</option>
                                <option value="Anuradhapura" <?php echo (isset($data['property']['district']) && $data['property']['district'] == 'Anuradhapura') ? 'selected' : ''; ?>>Anuradhapura</option>
                                <option value="Kurunegala" <?php echo (isset($data['property']['district']) && $data['property']['district'] == 'Kurunegala') ? 'selected' : ''; ?>>Kurunegala</option>
                                <option value="Matara" <?php echo (isset($data['property']['district']) && $data['property']['district'] == 'Matara') ? 'selected' : ''; ?>>Matara</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Property Description -->
                <div class="form-section">
                    <h3 class="section-title">Property Description</h3>
                    <div class="form-group full-width">
                        <label for="property-description">Description *</label>
                        <textarea id="property-description" 
                                  name="description" 
                                  rows="5" 
                                  placeholder="Describe your property, facilities, and what makes it special..."
                                  required><?php echo $data['property']['description'] ?? ''; ?></textarea>
                        <small class="form-help">Minimum 50 characters. Be detailed about your facilities and amenities.</small>
                    </div>
                </div>

                <!-- Features & Amenities -->
                <div class="form-section">
                    <h3 class="section-title">Features & Amenities</h3>
                    <div class="features-grid">
                        <?php 
                        $features = ['Lighting', 'Parking', 'WiFi', 'Air Conditioning', 'Changing Rooms', 'Equipment Rental', 'Seating', 'Sound System', 'Cafeteria', 'Professional Turf'];
                        $propertyFeatures = $data['property']['features'] ?? [];
                        foreach($features as $feature): 
                        ?>
                        <label class="feature-checkbox">
                            <input type="checkbox" name="features[]" value="<?php echo $feature; ?>" <?php echo in_array($feature, $propertyFeatures) ? 'checked' : ''; ?>>
                            <span class="checkmark">
                                <?php 
                                $icons = [
                                    'Lighting' => '💡', 'Parking' => '🚗', 'WiFi' => '📶', 
                                    'Air Conditioning' => '❄️', 'Changing Rooms' => '🚿', 
                                    'Equipment Rental' => '⚽', 'Seating' => '💺', 
                                    'Sound System' => '🔊', 'Cafeteria' => '🍕', 
                                    'Professional Turf' => '🌱'
                                ];
                                echo $icons[$feature] ?? '✓';
                                ?>
                            </span>
                            <span class="feature-text"><?php echo $feature; ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Current Images -->
                <?php if(isset($data['property']['images']) && !empty($data['property']['images'])): ?>
                <div class="form-section">
                    <h3 class="section-title">Current Images</h3>
                    <div class="current-images-grid">
                        <?php foreach($data['property']['images'] as $image): ?>
                        <div class="current-image-item">
                            <img src="<?php echo URLROOT; ?>/images/properties/<?php echo $image; ?>" alt="Property Image">
                            <button type="button" class="remove-current-image" onclick="removeCurrentImage('<?php echo $image; ?>')">
                                Remove
                            </button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Upload New Images -->
                <div class="form-section">
                    <h3 class="section-title">Update Property Images</h3>
                    <div class="upload-section">
                        <div class="upload-info">
                            <p>Upload new images to replace existing ones (optional).</p>
                            <small>Recommended size: 1200x800px. Supported formats: JPG, PNG (Max 5MB each)</small>
                        </div>
                        
                        <div class="file-upload-area" id="imageUploadArea">
                            <div class="upload-placeholder">
                                <div class="upload-icon">📷</div>
                                <h4>Upload New Property Images</h4>
                                <p>Drag and drop images here or click to browse</p>
                                <input type="file" 
                                       id="property-images" 
                                       name="images[]" 
                                       multiple 
                                       accept="image/*">
                            </div>
                            <div class="uploaded-images" id="uploadedImages"></div>
                        </div>
                    </div>
                </div>

                <!-- Property Status -->
                <div class="form-section">
                    <h3 class="section-title">Property Status</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="property-status">Status</label>
                            <select id="property-status" name="status">
                                <option value="Active" <?php echo (isset($data['property']['status']) && $data['property']['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                <option value="Inactive" <?php echo (isset($data['property']['status']) && $data['property']['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                <option value="Maintenance" <?php echo (isset($data['property']['status']) && $data['property']['status'] == 'Maintenance') ? 'selected' : ''; ?>>Under Maintenance</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="window.history.back()">Cancel</button>
                    <button type="button" class="btn-save-draft">Save as Draft</button>
                    <button type="submit" class="btn-update-property">Update Property</button>
                </div>
            </form>
        </div>

        <!-- Property Quick Stats Sidebar -->
        <div class="property-stats-sidebar">
            <div class="stats-card">
                <h4>Property Performance</h4>
                <div class="stat-item">
                    <span class="stat-label">Total Bookings:</span>
                    <span class="stat-value"><?php echo $data['property']['total_bookings'] ?? '0'; ?></span>
                </div>
                
                <div class="stat-item">
                    <span class="stat-label">Monthly Revenue:</span>
                    <span class="stat-value">LKR <?php echo number_format($data['property']['monthly_revenue'] ?? 0); ?></span>
                </div>
                
                <div class="stat-item">
                    <span class="stat-label">Average Rating:</span>
                    <span class="stat-value"><?php echo $data['property']['rating'] ?? '0.0'; ?> ⭐</span>
                </div>
                
                <div class="stat-item">
                    <span class="stat-label">Occupancy Rate:</span>
                    <span class="stat-value"><?php echo $data['property']['occupancy_rate'] ?? '0'; ?>%</span>
                </div>
            </div>
            
            <div class="actions-card">
                <h4>Quick Actions</h4>
                <button class="sidebar-action-btn" onclick="viewPropertyDetails()">
                    📊 View Analytics
                </button>
                <button class="sidebar-action-btn" onclick="manageBookings()">
                    📅 Manage Bookings
                </button>
                <button class="sidebar-action-btn" onclick="duplicateProperty()">
                    📋 Duplicate Property
                </button>
                <button class="sidebar-action-btn danger" onclick="deleteProperty()">
                    🗑️ Delete Property
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Image Upload Functionality (same as add property)
document.getElementById('property-images').addEventListener('change', function(e) {
    const files = e.target.files;
    const uploadedImages = document.getElementById('uploadedImages');
    
    uploadedImages.innerHTML = '';
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const imagePreview = document.createElement('div');
            imagePreview.className = 'image-preview';
            imagePreview.innerHTML = `
                <img src="${e.target.result}" alt="Property Image">
                <button type="button" class="remove-image" onclick="removeImage(this)">×</button>
                <div class="image-name">${file.name}</div>
            `;
            uploadedImages.appendChild(imagePreview);
        };
        
        reader.readAsDataURL(file);
    }
});

// Remove Image Function
function removeImage(button) {
    button.parentElement.remove();
}

// Remove Current Image
function removeCurrentImage(imageName) {
    if(confirm('Are you sure you want to remove this image?')) {
        // Here you would make an AJAX call to remove the image
        alert('Image removal functionality will be implemented');
    }
}

// Quick Actions
function viewPropertyDetails() {
    window.open('<?php echo URLROOT; ?>/stadiums/<?php echo $data['property']['id'] ?? ''; ?>', '_blank');
}

function manageBookings() {
    window.location.href = '<?php echo URLROOT; ?>/stadium_owner/bookings?property=<?php echo $data['property']['id'] ?? ''; ?>';
}

function duplicateProperty() {
    if(confirm('Create a copy of this property?')) {
        alert('Duplicate property functionality will be implemented');
    }
}

function deleteProperty() {
    if(confirm('Are you sure you want to delete this property? This action cannot be undone.')) {
        alert('Delete property functionality will be implemented');
    }
}

// Form validation
document.querySelector('.edit-property-form').addEventListener('submit', function(e) {
    const name = document.getElementById('property-name').value;
    const description = document.getElementById('property-description').value;
    
    if (name.length < 3) {
        e.preventDefault();
        alert('Property name must be at least 3 characters long.');
        return;
    }
    
    if (description.length < 50) {
        e.preventDefault();
        alert('Description must be at least 50 characters long.');
        return;
    }
});
</script>

<style>
.edit-property-container {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 30px;
    margin-bottom: 40px;
}

.property-stats-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.stats-card,
.actions-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.stats-card h4,
.actions-card h4 {
    margin: 0 0 16px 0;
    color: #212529;
    font-size: 16px;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: 14px;
}

.stat-label {
    color: #6c757d;
}

.stat-value {
    font-weight: 600;
    color: #212529;
}

.sidebar-action-btn {
    width: 100%;
    padding: 10px 16px;
    margin-bottom: 8px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    text-align: left;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
}

.sidebar-action-btn:hover {
    background: #e9ecef;
}

.sidebar-action-btn.danger {
    color: #dc3545;
    border-color: #dc3545;
}

.sidebar-action-btn.danger:hover {
    background: #f8d7da;
}

.current-images-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 16px;
}

.current-image-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    background: #f8f9fa;
}

.current-image-item img {
    width: 100%;
    height: 120px;
    object-fit: cover;
}

.remove-current-image {
    position: absolute;
    bottom: 8px;
    left: 8px;
    background: rgba(220, 53, 69, 0.9);
    color: white;
    border: none;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    cursor: pointer;
}

.btn-update-property {
    background: #007bff;
    color: white;
    border: 2px solid #007bff;
}

.btn-update-property:hover {
    background: #0056b3;
    border-color: #0056b3;
}

@media (max-width: 768px) {
    .edit-property-container {
        grid-template-columns: 1fr;
    }
    
    .current-images-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    }
}
</style>

<?php require APPROOT.'/views/stadium_owner/inc/footer.php'; ?>