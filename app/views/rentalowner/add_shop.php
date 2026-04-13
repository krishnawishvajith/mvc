<?php require APPROOT.'/views/rentalowner/inc/header.php'; ?>

<div class="kal-rental-dashboard-shop-container">
    <div class="kal-rental-dashboard-shop-main">
        <header class="kal-rental-dashboard-shop-header">
            <h2>Add New Rental Shop</h2>
            <a href="<?php echo URLROOT; ?>/rentalowner/shopManagement" class="kal-rental-dashboard-shop-btn kal-rental-dashboard-shop-btn-secondary">← Back to Shops</a>
        </header>

        <?php if (isset($data['error'])): ?>
            <div class="alert alert-error">
                ❌ <?php echo $data['error']; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($data['success'])): ?>
            <div class="alert alert-success">
                ✅ <?php echo $data['success']; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($data['limits'])): ?>
            <div class="package-info-box">
                <h3>📦 Your Package Limits</h3>
                <div class="limits-grid">
                    <div class="limit-item">
                        <span class="limit-label">Shops:</span>
                        <span class="limit-value"><?php echo $data['limits']['current_shops']; ?> / <?php echo $data['limits']['shops_limit']; ?></span>
                    </div>
                    <div class="limit-item">
                        <span class="limit-label">Images per shop:</span>
                        <span class="limit-value">Up to <?php echo $data['limits']['images_per_shop']; ?> images</span>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="add-shop-form" id="addShopForm">
            <!-- Basic Information -->
            <div class="form-section">
                <h3>📝 Basic Information</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="shop_name">Shop Name <span class="required">*</span></label>
                        <input type="text" id="shop_name" name="shop_name" required class="form-control" placeholder="e.g., Pro Sports Gear Rentals">
                    </div>

                    <div class="form-group">
                        <label for="category">Category <span class="required">*</span></label>
                        <select id="category" name="category" required class="form-control">
                            <option value="">Select Category</option>
                            <option value="Multi-Sport">Multi-Sport</option>
                            <option value="Cricket">Cricket</option>
                            <option value="Football">Football</option>
                            <option value="Tennis">Tennis</option>
                            <option value="Basketball">Basketball</option>
                            <option value="Badminton">Badminton</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="address">Address <span class="required">*</span></label>
                        <textarea id="address" name="address" required class="form-control" rows="2" placeholder="Street address, area"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="district">District</label>
                        <input type="text" id="district" name="district" class="form-control" placeholder="e.g., Colombo">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="Describe your shop, equipment quality, and services..."></textarea>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="form-section">
                <h3>📞 Contact Information</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="contact_email">Contact Email <span class="required">*</span></label>
                        <input type="email" id="contact_email" name="contact_email" required class="form-control" placeholder="shop@example.com">
                    </div>

                    <div class="form-group">
                        <label for="contact_phone">Contact Phone <span class="required">*</span></label>
                        <input type="tel" id="contact_phone" name="contact_phone" required class="form-control" placeholder="+94 71 234 5678">
                    </div>
                </div>

                <div class="form-group">
                    <label for="operating_hours">Operating Hours</label>
                    <input type="text" id="operating_hours" name="operating_hours" class="form-control" placeholder="e.g., Mon-Sun: 8:00 AM - 8:00 PM">
                </div>
            </div>

            <!-- Equipment & Pricing -->
            <div class="form-section">
                <h3>💰 Equipment & Pricing</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="daily_rate">Daily Rate (LKR)</label>
                        <input type="number" id="daily_rate" name="daily_rate" class="form-control" min="0" step="0.01" placeholder="1500">
                    </div>

                    <div class="form-group">
                        <label for="equipment_count">Total Equipment Items</label>
                        <input type="number" id="equipment_count" name="equipment_count" class="form-control" min="0" placeholder="85">
                    </div>
                </div>
            </div>

            <!-- Equipment Types -->
            <div class="form-section">
                <h3>🎯 Equipment Types Available</h3>
                <p class="section-hint">Select all sports equipment types you offer for rent</p>
                
                <div class="checkbox-grid">
                    <label class="checkbox-item">
                        <input type="checkbox" name="equipment_types[]" value="Cricket">
                        <span class="checkbox-label">🏏 Cricket</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="equipment_types[]" value="Football">
                        <span class="checkbox-label">⚽ Football</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="equipment_types[]" value="Tennis">
                        <span class="checkbox-label">🎾 Tennis</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="equipment_types[]" value="Basketball">
                        <span class="checkbox-label">🏀 Basketball</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="equipment_types[]" value="Badminton">
                        <span class="checkbox-label">🏸 Badminton</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="equipment_types[]" value="Volleyball">
                        <span class="checkbox-label">🏐 Volleyball</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="equipment_types[]" value="Baseball">
                        <span class="checkbox-label">⚾ Baseball</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="equipment_types[]" value="Hockey">
                        <span class="checkbox-label">🏒 Hockey</span>
                    </label>
                </div>
            </div>

            <!-- Amenities -->
            <div class="form-section">
                <h3>✨ Essential Amenities</h3>
                <p class="section-hint">Select the services and features you provide</p>
                
                <div class="checkbox-grid">
                    <label class="checkbox-item">
                        <input type="checkbox" name="amenities[]" value="Home Delivery">
                        <span class="checkbox-label">🚚 Home Delivery</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="amenities[]" value="Quality Guarantee">
                        <span class="checkbox-label">✅ Quality Guarantee</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="amenities[]" value="Online Booking">
                        <span class="checkbox-label">💻 Online Booking</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="amenities[]" value="Expert Advice">
                        <span class="checkbox-label">👨‍🏫 Expert Advice</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="amenities[]" value="Equipment Maintenance">
                        <span class="checkbox-label">🔧 Equipment Maintenance</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="amenities[]" value="Bulk Discounts">
                        <span class="checkbox-label">💵 Bulk Discounts</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="amenities[]" value="Team Packages">
                        <span class="checkbox-label">👥 Team Packages</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="amenities[]" value="Size Fitting">
                        <span class="checkbox-label">📏 Size Fitting</span>
                    </label>
                </div>
            </div>

            <!-- Images -->
            <div class="form-section">
                <h3>📸 Shop Images</h3>
                <p class="section-hint">Upload up to <?php echo $data['limits']['images_per_shop'] ?? 5; ?> images (JPEG, PNG, GIF only, max 5MB each)</p>
                
                <div class="form-group">
                    <label for="images" class="file-upload-label">
                        <span class="upload-icon">📁</span>
                        <span class="upload-text">Click to upload images</span>
                    </label>
                    <input type="file" id="images" name="images[]" multiple accept="image/jpeg,image/jpg,image/png,image/gif" class="file-input">
                    <div id="imagePreview" class="image-preview-grid"></div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-actions">
                <button type="submit" class="kal-rental-dashboard-shop-btn kal-rental-dashboard-shop-btn-primary btn-submit">
                    ➕ Add Shop
                </button>
                <a href="<?php echo URLROOT; ?>/rentalowner/shopManagement" class="kal-rental-dashboard-shop-btn kal-rental-dashboard-shop-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.add-shop-form {
    background: #1a1a1a;
    padding: 30px;
    border-radius: 12px;
    border: 1px solid #333;
}

.form-section {
    margin-bottom: 30px;
    padding-bottom: 30px;
    border-bottom: 1px solid #333;
}

.form-section:last-of-type {
    border-bottom: none;
}

.form-section h3 {
    color: #fff;
    font-size: 20px;
    margin-bottom: 15px;
}

.section-hint {
    color: #999;
    font-size: 14px;
    margin-bottom: 20px;
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
    color: #ccc;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
}

.required {
    color: #ff6666;
}

.form-control {
    width: 100%;
    padding: 12px;
    background: #0a0a0a;
    border: 1px solid #333;
    border-radius: 8px;
    color: #fff;
    font-size: 14px;
}

.form-control:focus {
    outline: none;
    border-color: #03B200;
}

textarea.form-control {
    resize: vertical;
    font-family: inherit;
}

.checkbox-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
}

.checkbox-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    background: #0a0a0a;
    border: 1px solid #333;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.checkbox-item:hover {
    border-color: #03B200;
    background: #1a1a1a;
}

.checkbox-item input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.checkbox-label {
    color: #ccc;
    font-size: 14px;
}

.file-upload-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px;
    background: #0a0a0a;
    border: 2px dashed #333;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.file-upload-label:hover {
    border-color: #03B200;
    background: #1a1a1a;
}

.upload-icon {
    font-size: 48px;
    margin-bottom: 10px;
}

.upload-text {
    color: #ccc;
    font-size: 14px;
}

.file-input {
    display: none;
}

.image-preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.preview-item {
    position: relative;
    aspect-ratio: 1;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #333;
}

.preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.remove-image {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(255, 0, 0, 0.8);
    color: white;
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    cursor: pointer;
    font-size: 16px;
    line-height: 1;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-start;
}

.btn-submit {
    padding: 15px 40px;
    font-size: 16px;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-error {
    background: rgba(255, 0, 0, 0.1);
    border: 1px solid #ff6666;
    color: #ff6666;
}

.alert-success {
    background: rgba(0, 255, 0, 0.1);
    border: 1px solid #03B200;
    color: #03B200;
}

.package-info-box {
    background: #0a0a0a;
    border: 1px solid #03B200;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
}

.package-info-box h3 {
    color: #03B200;
    font-size: 18px;
    margin-bottom: 15px;
}

.limits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.limit-item {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    background: #1a1a1a;
    border-radius: 6px;
}

.limit-label {
    color: #999;
    font-size: 14px;
}

.limit-value {
    color: #03B200;
    font-weight: 600;
    font-size: 14px;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .checkbox-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Image preview functionality
const imageInput = document.getElementById('images');
const previewGrid = document.getElementById('imagePreview');
let selectedFiles = [];

imageInput.addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const maxImages = <?php echo $data['limits']['images_per_shop'] ?? 5; ?>;
    
    // Limit to max images
    selectedFiles = files.slice(0, maxImages);
    
    // Update file input
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    imageInput.files = dataTransfer.files;
    
    // Show preview
    previewGrid.innerHTML = '';
    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewItem = document.createElement('div');
            previewItem.className = 'preview-item';
            previewItem.innerHTML = `
                <img src="${e.target.result}" alt="Preview ${index + 1}">
                <button type="button" class="remove-image" onclick="removeImage(${index})">×</button>
            `;
            previewGrid.appendChild(previewItem);
        };
        reader.readAsDataURL(file);
    });
    
    if (files.length > maxImages) {
        alert(`Only ${maxImages} images allowed. First ${maxImages} images were selected.`);
    }
});

function removeImage(index) {
    selectedFiles.splice(index, 1);
    
    // Update file input
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    imageInput.files = dataTransfer.files;
    
    // Trigger change event to update preview
    imageInput.dispatchEvent(new Event('change'));
}

// Form validation
document.getElementById('addShopForm').addEventListener('submit', function(e) {
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.style.borderColor = '#ff6666';
        } else {
            field.style.borderColor = '#333';
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('❌ Please fill in all required fields');
    }
});
</script>

<?php require APPROOT.'/views/rentalowner/inc/footer.php'; ?>
