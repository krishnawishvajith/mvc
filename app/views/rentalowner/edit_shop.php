<?php require APPROOT.'/views/rentalowner/inc/header.php'; ?>

<div class="kal-rental-dashboard-shop-container">        
    <!-- Main Content -->
    <div class="kal-rental-dashboard-shop-main">
        <header class="kal-rental-dashboard-shop-header">
            <h2>Edit Shop Details</h2>
            <a href="<?php echo URLROOT; ?>/rentalowner/shopManagement" class="kal-rental-dashboard-shop-btn kal-rental-dashboard-shop-btn-primary">Back to Shops</a>
        </header>
        
        <?php if (isset($data['error'])): ?>
            <div class="alert alert-danger"><?php echo $data['error']; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo URLROOT; ?>/rentalowner/editShop/<?php echo $data['shop']->id; ?>" enctype="multipart/form-data" class="add-shop-form">
            
            <!-- Basic Information -->
            <div class="form-section">
                <h3>🏪 Basic Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="shop_name">Shop Name <span class="required">*</span></label>
                        <input type="text" id="shop_name" name="shop_name" class="form-control" value="<?php echo htmlspecialchars($data['shop']->shop_name); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Category <span class="required">*</span></label>
                        <select id="category" name="category" class="form-control" required>
                            <option value="">Select Category</option>
                            <option value="Multi-Sport" <?php echo ($data['shop']->category ?? '') == 'Multi-Sport' ? 'selected' : ''; ?>>Multi-Sport</option>
                            <option value="Cricket" <?php echo ($data['shop']->category ?? '') == 'Cricket' ? 'selected' : ''; ?>>Cricket Equipment</option>
                            <option value="Football" <?php echo ($data['shop']->category ?? '') == 'Football' ? 'selected' : ''; ?>>Football Equipment</option>
                            <option value="Tennis" <?php echo ($data['shop']->category ?? '') == 'Tennis' ? 'selected' : ''; ?>>Tennis Equipment</option>
                            <option value="Basketball" <?php echo ($data['shop']->category ?? '') == 'Basketball' ? 'selected' : ''; ?>>Basketball Equipment</option>
                            <option value="Other" <?php echo ($data['shop']->category ?? '') == 'Other' ? 'selected' : ''; ?>>Other Sports</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="address">Address <span class="required">*</span></label>
                    <textarea id="address" name="address" class="form-control" rows="2" required><?php echo htmlspecialchars($data['shop']->address); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="district">District <span class="required">*</span></label>
                        <select id="district" name="district" class="form-control" required>
                            <option value="">Select District</option>
                            <?php 
                            $districts = ['Colombo', 'Gampaha', 'Kalutara', 'Kandy', 'Matale', 'Nuwara Eliya', 'Galle', 'Matara', 'Hambantota', 'Jaffna', 'Kilinochchi', 'Mannar', 'Vavuniya', 'Mullaitivu', 'Batticaloa', 'Ampara', 'Trincomalee', 'Kurunegala', 'Puttalam', 'Anuradhapura', 'Polonnaruwa', 'Badulla', 'Moneragala', 'Ratnapura', 'Kegalle'];
                            foreach ($districts as $dist) {
                                $selected = (($data['shop']->district ?? '') == $dist) ? 'selected' : '';
                                echo "<option value='$dist' $selected>$dist</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="Describe your shop and services..."><?php echo htmlspecialchars($data['shop']->description ?? ''); ?></textarea>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="form-section">
                <h3>📞 Contact Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="contact_email">Email <span class="required">*</span></label>
                        <input type="email" id="contact_email" name="contact_email" class="form-control" value="<?php echo htmlspecialchars($data['shop']->contact_email); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_phone">Phone <span class="required">*</span></label>
                        <input type="tel" id="contact_phone" name="contact_phone" class="form-control" value="<?php echo htmlspecialchars($data['shop']->contact_phone); ?>" placeholder="+94 71 234 5678" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="operating_hours">Operating Hours</label>
                    <input type="text" id="operating_hours" name="operating_hours" class="form-control" value="<?php echo htmlspecialchars($data['shop']->operating_hours ?? ''); ?>" placeholder="e.g., Mon-Sun: 8:00 AM - 8:00 PM">
                </div>
            </div>

            <!-- Equipment & Pricing -->
            <div class="form-section">
                <h3>⚽ Equipment & Pricing</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="daily_rate">Daily Rate (LKR) <span class="required">*</span></label>
                        <input type="number" id="daily_rate" name="daily_rate" class="form-control" value="<?php echo htmlspecialchars($data['shop']->daily_rate ?? 0); ?>" min="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="equipment_count">Equipment Count <span class="required">*</span></label>
                        <input type="number" id="equipment_count" name="equipment_count" class="form-control" value="<?php echo htmlspecialchars($data['shop']->equipment_count ?? 0); ?>" min="0" required>
                    </div>
                </div>
            </div>

            <!-- Equipment Types -->
            <div class="form-section">
                <h3>🏅 Equipment Types</h3>
                <p class="section-hint">Select all sports equipment types you offer</p>
                <div class="checkbox-grid">
                    <?php 
                    $allTypes = ['Cricket', 'Football', 'Basketball', 'Tennis', 'Volleyball', 'Rugby', 'Badminton', 'Table Tennis', 'Hockey', 'Swimming', 'Cycling', 'Gym Equipment'];
                    $selectedTypes = $data['shop']->equipment_types ?? [];
                    foreach ($allTypes as $type): 
                        $checked = in_array($type, $selectedTypes) ? 'checked' : '';
                    ?>
                    <label class="checkbox-item">
                        <input type="checkbox" name="equipment_types[]" value="<?php echo $type; ?>" <?php echo $checked; ?>>
                        <span class="checkbox-label">⚽ <?php echo $type; ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Essential Amenities -->
            <div class="form-section">
                <h3>✨ Essential Amenities</h3>
                <p class="section-hint">Select the amenities and services you provide</p>
                <div class="checkbox-grid">
                    <?php 
                    $allAmenities = ['Home Delivery', 'Quality Guarantee', 'Online Booking', 'Expert Advice', 'Equipment Maintenance', 'Bulk Discounts', 'Size Fitting'];
                    $selectedAmenities = $data['shop']->features ?? [];
                    foreach ($allAmenities as $amenity): 
                        $checked = in_array($amenity, $selectedAmenities) ? 'checked' : '';
                    ?>
                    <label class="checkbox-item">
                        <input type="checkbox" name="amenities[]" value="<?php echo $amenity; ?>" <?php echo $checked; ?>>
                        <span class="checkbox-label">✅ <?php echo $amenity; ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Current Images -->
            <div class="form-section">
                <h3>📸 Current Images</h3>
                <div class="current-images-grid">
                    <?php 
                    $images = $data['shop']->images ?? [];
                    if (!empty($images)): 
                        foreach ($images as $index => $image): 
                    ?>
                        <div class="current-image-item">
                            <img src="<?php echo URLROOT; ?>/<?php echo $image->image_path; ?>" alt="Shop Image">
                            <div class="image-badge"><?php echo $image->is_primary ? 'Primary' : 'Image ' . ($index + 1); ?></div>
                        </div>
                    <?php 
                        endforeach;
                    else: 
                    ?>
                        <p class="no-images">No images uploaded yet</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Add New Images -->
            <div class="form-section">
                <h3>➕ Add More Images</h3>
                <p class="section-hint">You can add up to <?php echo $data['limits']['images_per_shop']; ?> images total (Currently: <?php echo count($data['shop']->images ?? []); ?>)</p>
                
                <div class="form-group">
                    <label for="images" class="file-upload-label">
                        <span class="upload-icon">📁</span>
                        <span class="upload-text">Click to upload new images</span>
                    </label>
                    <input type="file" id="images" name="images[]" multiple accept="image/jpeg,image/jpg,image/png,image/gif" class="file-input">
                    <div id="imagePreview" class="image-preview-grid"></div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-actions">
                <button type="submit" class="kal-rental-dashboard-shop-btn kal-rental-dashboard-shop-btn-primary btn-submit">
                    💾 Save Changes
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
    padding: 12px;
    background: #0a0a0a;
    border: 1px solid #333;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.checkbox-item:hover {
    border-color: #03B200;
    background: #111;
}

.checkbox-item input[type="checkbox"] {
    margin-right: 10px;
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.checkbox-label {
    color: #ccc;
    font-size: 14px;
}

.checkbox-item input:checked + .checkbox-label {
    color: #03B200;
}

.current-images-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.current-image-item {
    position: relative;
    aspect-ratio: 1;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #333;
}

.current-image-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-badge {
    position: absolute;
    bottom: 8px;
    left: 8px;
    background: rgba(3, 178, 0, 0.9);
    color: #fff;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.no-images {
    color: #999;
    font-style: italic;
}

.file-upload-label {
    display: block;
    padding: 40px;
    background: #0a0a0a;
    border: 2px dashed #333;
    border-radius: 8px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.file-upload-label:hover {
    border-color: #03B200;
    background: #111;
}

.upload-icon {
    font-size: 48px;
    display: block;
    margin-bottom: 10px;
}

.upload-text {
    color: #ccc;
    font-size: 16px;
}

.file-input {
    display: none;
}

.image-preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.preview-item {
    position: relative;
    aspect-ratio: 1;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #03B200;
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
    width: 30px;
    height: 30px;
    background: #ff0000;
    color: #fff;
    border: none;
    border-radius: 50%;
    font-size: 20px;
    line-height: 1;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-start;
    margin-top: 30px;
}

.btn-submit {
    min-width: 150px;
}

.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-danger {
    background: #ff000020;
    border: 1px solid #ff0000;
    color: #ff6666;
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
    const maxImages = <?php echo $data['limits']['images_per_shop']; ?>;
    const currentImages = <?php echo count($data['shop']->images ?? []); ?>;
    const availableSlots = maxImages - currentImages;
    
    // Limit to available slots
    selectedFiles = files.slice(0, availableSlots);
    
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
    
    if (files.length > availableSlots) {
        alert(`You can only add ${availableSlots} more image(s). You already have ${currentImages} image(s).`);
    }
});

function removeImage(index) {
    selectedFiles.splice(index, 1);
    
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    imageInput.files = dataTransfer.files;
    
    // Refresh preview
    imageInput.dispatchEvent(new Event('change'));
}
</script>

<?php require APPROOT.'/views/rentalowner/inc/footer.php'; ?>
