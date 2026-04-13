<?php require APPROOT.'/views/stadium_owner/inc/header.php'; ?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>Add New Property</h1>
        <div class="header-actions">
            <a href="<?php echo URLROOT; ?>/stadium_owner/properties" class="btn-back">← Back to Properties</a>
        </div>
    </div>

    <!-- Package Limits Check -->
    <?php if(isset($data['limits']) && !$data['limits']['can_add_more']): ?>
    <div class="alert alert-warning">
        <div class="alert-icon">⚠️</div>
        <div class="alert-content">
            <h4>Stadium Limit Reached</h4>
            <p>You've reached the maximum number of stadiums (<?php echo $data['limits']['stadium_limit']; ?>) for your current package. Upgrade your package to add more stadiums.</p>
            <a href="<?php echo URLROOT; ?>/pricing" class="btn-upgrade">Upgrade Package</a>
        </div>
    </div>
    <?php else: ?>

    <!-- Add Property Form -->
    <div class="add-property-container">
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

            <form class="add-property-form" method="POST" action="<?php echo URLROOT; ?>/stadium_owner/add_property" enctype="multipart/form-data">
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
                                   value="<?php echo isset($data['form_data']['name']) ? $data['form_data']['name'] : ''; ?>"
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label for="property-type">Sports Type *</label>
                            <select id="property-type" name="type" required>
                                <option value="">Select sports type</option>
                                <option value="Cricket" <?php echo isset($data['form_data']['type']) && $data['form_data']['type'] == 'Cricket' ? 'selected' : ''; ?>>Cricket</option>
                                <option value="Football" <?php echo isset($data['form_data']['type']) && $data['form_data']['type'] == 'Football' ? 'selected' : ''; ?>>Football</option>
                                <option value="Tennis" <?php echo isset($data['form_data']['type']) && $data['form_data']['type'] == 'Tennis' ? 'selected' : ''; ?>>Tennis</option>
                                <option value="Basketball" <?php echo isset($data['form_data']['type']) && $data['form_data']['type'] == 'Basketball' ? 'selected' : ''; ?>>Basketball</option>
                                <option value="Badminton" <?php echo isset($data['form_data']['type']) && $data['form_data']['type'] == 'Badminton' ? 'selected' : ''; ?>>Badminton</option>
                                <option value="Swimming" <?php echo isset($data['form_data']['type']) && $data['form_data']['type'] == 'Swimming' ? 'selected' : ''; ?>>Swimming</option>
                                <option value="Volleyball" <?php echo isset($data['form_data']['type']) && $data['form_data']['type'] == 'Volleyball' ? 'selected' : ''; ?>>Volleyball</option>
                                <option value="Multi-Sport" <?php echo isset($data['form_data']['type']) && $data['form_data']['type'] == 'Multi-Sport' ? 'selected' : ''; ?>>Multi-Sport</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="property-category">Category *</label>
                            <select id="property-category" name="category" required>
                                <option value="">Select category</option>
                                <option value="Indoor" <?php echo isset($data['form_data']['category']) && $data['form_data']['category'] == 'Indoor' ? 'selected' : ''; ?>>Indoor</option>
                                <option value="Outdoor" <?php echo isset($data['form_data']['category']) && $data['form_data']['category'] == 'Outdoor' ? 'selected' : ''; ?>>Outdoor</option>
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
                                   value="<?php echo isset($data['form_data']['price']) ? $data['form_data']['price'] : ''; ?>"
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
                                   value="<?php echo isset($data['form_data']['location']) ? $data['form_data']['location'] : ''; ?>"
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label for="property-district">District *</label>
                            <select id="property-district" name="district" required>
                                <option value="">Select district</option>
                                <option value="Colombo" <?php echo isset($data['form_data']['district']) && $data['form_data']['district'] == 'Colombo' ? 'selected' : ''; ?>>Colombo</option>
                                <option value="Kandy" <?php echo isset($data['form_data']['district']) && $data['form_data']['district'] == 'Kandy' ? 'selected' : ''; ?>>Kandy</option>
                                <option value="Galle" <?php echo isset($data['form_data']['district']) && $data['form_data']['district'] == 'Galle' ? 'selected' : ''; ?>>Galle</option>
                                <option value="Jaffna" <?php echo isset($data['form_data']['district']) && $data['form_data']['district'] == 'Jaffna' ? 'selected' : ''; ?>>Jaffna</option>
                                <option value="Negombo" <?php echo isset($data['form_data']['district']) && $data['form_data']['district'] == 'Negombo' ? 'selected' : ''; ?>>Negombo</option>
                                <option value="Anuradhapura" <?php echo isset($data['form_data']['district']) && $data['form_data']['district'] == 'Anuradhapura' ? 'selected' : ''; ?>>Anuradhapura</option>
                                <option value="Kurunegala" <?php echo isset($data['form_data']['district']) && $data['form_data']['district'] == 'Kurunegala' ? 'selected' : ''; ?>>Kurunegala</option>
                                <option value="Matara" <?php echo isset($data['form_data']['district']) && $data['form_data']['district'] == 'Matara' ? 'selected' : ''; ?>>Matara</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="property-postal">Postal Code</label>
                            <input type="text" 
                                   id="property-postal" 
                                   name="postal_code" 
                                   placeholder="e.g., 00300"
                                   value="<?php echo isset($data['form_data']['postal_code']) ? $data['form_data']['postal_code'] : ''; ?>">
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
                                  required><?php echo isset($data['form_data']['description']) ? $data['form_data']['description'] : ''; ?></textarea>
                        <small class="form-help">Minimum 50 characters. Be detailed about your facilities and amenities.</small>
                    </div>
                </div>

                <!-- Features & Amenities -->
                <div class="form-section">
                    <h3 class="section-title">Features & Amenities</h3>
                    <div class="features-grid">
                        <label class="feature-checkbox">
                            <input type="checkbox" name="features[]" value="Lighting" <?php echo isset($data['form_data']['features']) && in_array('Lighting', $data['form_data']['features']) ? 'checked' : ''; ?>>
                            <span class="checkmark">💡</span>
                            <span class="feature-text">Lighting</span>
                        </label>
                        
                        <label class="feature-checkbox">
                            <input type="checkbox" name="features[]" value="Parking" <?php echo isset($data['form_data']['features']) && in_array('Parking', $data['form_data']['features']) ? 'checked' : ''; ?>>
                            <span class="checkmark">🚗</span>
                            <span class="feature-text">Parking</span>
                        </label>
                        
                        <label class="feature-checkbox">
                            <input type="checkbox" name="features[]" value="WiFi" <?php echo isset($data['form_data']['features']) && in_array('WiFi', $data['form_data']['features']) ? 'checked' : ''; ?>>
                            <span class="checkmark">📶</span>
                            <span class="feature-text">WiFi</span>
                        </label>
                        
                        <label class="feature-checkbox">
                            <input type="checkbox" name="features[]" value="Air Conditioning" <?php echo isset($data['form_data']['features']) && in_array('Air Conditioning', $data['form_data']['features']) ? 'checked' : ''; ?>>
                            <span class="checkmark">❄️</span>
                            <span class="feature-text">Air Conditioning</span>
                        </label>
                        
                        <label class="feature-checkbox">
                            <input type="checkbox" name="features[]" value="Changing Rooms" <?php echo isset($data['form_data']['features']) && in_array('Changing Rooms', $data['form_data']['features']) ? 'checked' : ''; ?>>
                            <span class="checkmark">🚿</span>
                            <span class="feature-text">Changing Rooms</span>
                        </label>
                        
                        <label class="feature-checkbox">
                            <input type="checkbox" name="features[]" value="Equipment Rental" <?php echo isset($data['form_data']['features']) && in_array('Equipment Rental', $data['form_data']['features']) ? 'checked' : ''; ?>>
                            <span class="checkmark">⚽</span>
                            <span class="feature-text">Equipment Rental</span>
                        </label>
                        
                        <label class="feature-checkbox">
                            <input type="checkbox" name="features[]" value="Seating" <?php echo isset($data['form_data']['features']) && in_array('Seating', $data['form_data']['features']) ? 'checked' : ''; ?>>
                            <span class="checkmark">💺</span>
                            <span class="feature-text">Seating</span>
                        </label>
                        
                        <label class="feature-checkbox">
                            <input type="checkbox" name="features[]" value="Sound System" <?php echo isset($data['form_data']['features']) && in_array('Sound System', $data['form_data']['features']) ? 'checked' : ''; ?>>
                            <span class="checkmark">🔊</span>
                            <span class="feature-text">Sound System</span>
                        </label>
                        
                        <label class="feature-checkbox">
                            <input type="checkbox" name="features[]" value="Cafeteria" <?php echo isset($data['form_data']['features']) && in_array('Cafeteria', $data['form_data']['features']) ? 'checked' : ''; ?>>
                            <span class="checkmark">🍕</span>
                            <span class="feature-text">Cafeteria</span>
                        </label>
                        
                        <label class="feature-checkbox">
                            <input type="checkbox" name="features[]" value="Professional Turf" <?php echo isset($data['form_data']['features']) && in_array('Professional Turf', $data['form_data']['features']) ? 'checked' : ''; ?>>
                            <span class="checkmark">🌱</span>
                            <span class="feature-text">Professional Turf</span>
                        </label>
                    </div>
                </div>

                <!-- Google Maps Location Picker -->
                <div class="form-section">
                    <h3 class="section-title">📍 Select Location on Map</h3>
                    <div class="map-section">
                        <div class="map-info">
                            <p>Click on the map to select your stadium's exact location. The address will be automatically filled.</p>
                            <small>💡 Tip: You can search for a location using the search box or drag the marker to adjust.</small>
                        </div>
                        
                        <!-- Search Box -->
                        <div class="form-group full-width">
                            <label for="map-search">Search Location</label>
                            <input type="text" 
                                   id="map-search" 
                                   class="map-search-input"
                                   placeholder="Search for your stadium location...">
                        </div>
                        
                        <!-- Map Container -->
                        <div id="map" class="google-map"></div>
                        
                        <!-- Hidden fields to store lat/lng -->
                        <input type="hidden" id="latitude" name="latitude" value="<?php echo isset($data['form_data']['latitude']) ? $data['form_data']['latitude'] : '6.9271'; ?>">
                        <input type="hidden" id="longitude" name="longitude" value="<?php echo isset($data['form_data']['longitude']) ? $data['form_data']['longitude'] : '79.8612'; ?>">
                        
                        <!-- Selected Location Display -->
                        <div class="selected-location" id="selected-location">
                            <div class="location-icon">📍</div>
                            <div class="location-details">
                                <strong>Selected Location:</strong>
                                <p id="selected-address">Click on the map to select a location</p>
                                <small id="selected-coords">Lat: 6.9271, Lng: 79.8612 (Default: Colombo, Sri Lanka)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Availability Settings -->
                <div class="form-section">
                    <h3 class="section-title">Availability Settings</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="opening-hours">Opening Hours</label>
                            <select id="opening-hours" name="opening_hours">
                                <option value="24/7">24/7 Available</option>
                                <option value="6:00 AM - 10:00 PM">6:00 AM - 10:00 PM</option>
                                <option value="7:00 AM - 9:00 PM">7:00 AM - 9:00 PM</option>
                                <option value="8:00 AM - 8:00 PM">8:00 AM - 8:00 PM</option>
                                <option value="Custom">Custom Hours</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="advance-booking">Advance Booking</label>
                            <select id="advance-booking" name="advance_booking">
                                <option value="1">1 day in advance</option>
                                <option value="3">3 days in advance</option>
                                <option value="7" selected>1 week in advance</option>
                                <option value="14">2 weeks in advance</option>
                                <option value="30">1 month in advance</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="minimum-duration">Minimum Booking Duration</label>
                            <select id="minimum-duration" name="minimum_duration">
                                <option value="1" selected>1 hour</option>
                                <option value="2">2 hours</option>
                                <option value="3">3 hours</option>
                                <option value="4">4 hours</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="cancellation-policy">Cancellation Policy</label>
                            <select id="cancellation-policy" name="cancellation_policy">
                                <option value="6">6 hours before</option>
                                <option value="12" selected>12 hours before</option>
                                <option value="24">24 hours before</option>
                                <option value="48">48 hours before</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Stadium Images -->
                <div class="form-section">
                    <h3 class="section-title">Stadium Images *</h3>
                    <div class="package-limit-banner">
                        <span class="limit-icon">📸</span>
                        <span class="limit-text">Your package allows <strong><?php echo isset($data['limits']['photos_per_stadium']) ? $data['limits']['photos_per_stadium'] : 0; ?> photos</strong> per stadium</span>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="stadium-images">Upload Images (Required) *</label>
                        <div class="image-upload-container">
                            <div class="upload-box" id="upload-box">
                                <input type="file" 
                                       id="stadium-images" 
                                       name="stadium_images[]" 
                                       accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                                       multiple 
                                       required
                                       onchange="handleImageUpload(this)">
                                <div class="upload-placeholder">
                                    <span class="upload-icon">📷</span>
                                    <p class="upload-text">Click or drag images here</p>
                                    <p class="upload-hint">Upload up to <?php echo isset($data['limits']['photos_per_stadium']) ? $data['limits']['photos_per_stadium'] : 0; ?> images (JPG, PNG, GIF, WebP - Max 5MB each)</p>
                                </div>
                            </div>
                            <div id="image-preview-container" class="image-preview-container"></div>
                        </div>
                        <small class="form-help">The first image will be used as the primary image for your stadium.</small>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="form-section">
                    <h3 class="section-title">Contact Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="contact-person">Contact Person</label>
                            <input type="text" 
                                   id="contact-person" 
                                   name="contact_person" 
                                   placeholder="Name of contact person"
                                   value="<?php echo isset($data['form_data']['contact_person']) ? $data['form_data']['contact_person'] : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="contact-phone">Contact Phone *</label>
                            <input type="tel" 
                                   id="contact-phone" 
                                   name="contact_phone" 
                                   placeholder="+94 71 234 5678"
                                   value="<?php echo isset($data['form_data']['contact_phone']) ? $data['form_data']['contact_phone'] : ''; ?>"
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label for="contact-email">Contact Email</label>
                            <input type="email" 
                                   id="contact-email" 
                                   name="contact_email" 
                                   placeholder="property@example.com"
                                   value="<?php echo isset($data['form_data']['contact_email']) ? $data['form_data']['contact_email'] : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="whatsapp-number">WhatsApp Number</label>
                            <input type="tel" 
                                   id="whatsapp-number" 
                                   name="whatsapp_number" 
                                   placeholder="+94 71 234 5678"
                                   value="<?php echo isset($data['form_data']['whatsapp_number']) ? $data['form_data']['whatsapp_number'] : ''; ?>">
                        </div>
                    </div>
                </div>

                <!-- Special Instructions -->
                <div class="form-section">
                    <h3 class="section-title">Additional Information</h3>
                    <div class="form-group full-width">
                        <label for="special-instructions">Special Instructions / Rules</label>
                        <textarea id="special-instructions" 
                                  name="special_instructions" 
                                  rows="4" 
                                  placeholder="Any special rules, instructions, or additional information customers should know..."><?php echo isset($data['form_data']['special_instructions']) ? $data['form_data']['special_instructions'] : ''; ?></textarea>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="window.history.back()">Cancel</button>
                    <button type="button" class="btn-save-draft">Save as Draft</button>
                    <button type="submit" class="btn-add-property">Add Property</button>
                </div>
            </form>
        </div>

        <!-- Package Limits Sidebar -->
        <div class="package-limits-sidebar">
            <?php if(isset($data['limits'])): ?>
            <div class="limits-card">
                <h4>Your Package Limits</h4>
                <div class="limit-item">
                    <span class="limit-label">Stadiums:</span>
                    <span class="limit-value"><?php echo $data['limits']['current_stadiums']; ?>/<?php echo $data['limits']['stadium_limit']; ?></span>
                    <div class="limit-bar">
                        <?php 
                        $percentage = 0;
                        if ($data['limits']['stadium_limit'] !== 'unlimited' && $data['limits']['stadium_limit_numeric'] > 0) {
                            $percentage = ($data['limits']['current_stadiums'] / $data['limits']['stadium_limit_numeric']) * 100;
                        }
                        ?>
                        <div class="limit-fill" style="width: <?php echo min($percentage, 100); ?>%"></div>
                    </div>
                </div>
                
                <div class="limit-item">
                    <span class="limit-label">Photos per stadium:</span>
                    <span class="limit-value"><?php echo $data['limits']['photos_per_stadium']; ?></span>
                </div>
                
                <div class="limit-item">
                    <span class="limit-label">Videos per stadium:</span>
                    <span class="limit-value"><?php echo $data['limits']['videos_per_stadium']; ?></span>
                </div>
                
                <a href="<?php echo URLROOT; ?>/pricing" class="btn-upgrade-sidebar">Upgrade Package</a>
            </div>
            <?php endif; ?>
            
            <div class="tips-card">
                <h4>💡 Tips for Success</h4>
                <ul class="tips-list">
                    <li>Use high-quality images to attract more bookings</li>
                    <li>Write detailed descriptions highlighting unique features</li>
                    <li>Set competitive pricing for your area</li>
                    <li>Respond quickly to customer inquiries</li>
                    <li>Maintain your property in excellent condition</li>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
// Google Maps Integration
let map;
let marker;
let geocoder;
let autocomplete;

function initMap() {
    // Default center (Colombo, Sri Lanka)
    const defaultLat = parseFloat(document.getElementById('latitude').value) || 6.9271;
    const defaultLng = parseFloat(document.getElementById('longitude').value) || 79.8612;
    const defaultCenter = { lat: defaultLat, lng: defaultLng };

    // Initialize map
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: defaultCenter,
        mapTypeControl: true,
        streetViewControl: false,
        fullscreenControl: true,
    });

    // Initialize geocoder
    geocoder = new google.maps.Geocoder();

    // Initialize marker
    marker = new google.maps.Marker({
        position: defaultCenter,
        map: map,
        draggable: true,
        animation: google.maps.Animation.DROP,
    });

    // Initialize autocomplete for search box
    const searchInput = document.getElementById('map-search');
    autocomplete = new google.maps.places.Autocomplete(searchInput, {
        componentRestrictions: { country: 'lk' }, // Restrict to Sri Lanka
        fields: ['formatted_address', 'geometry', 'name'],
    });

    // When place is selected from autocomplete
    autocomplete.addListener('place_changed', function() {
        const place = autocomplete.getPlace();
        if (place.geometry) {
            map.setCenter(place.geometry.location);
            map.setZoom(15);
            marker.setPosition(place.geometry.location);
            updateLocation(place.geometry.location.lat(), place.geometry.location.lng(), place.formatted_address);
        }
    });

    // Click on map to place marker
    map.addListener('click', function(event) {
        marker.setPosition(event.latLng);
        updateLocationFromLatLng(event.latLng);
    });

    // Drag marker to new position
    marker.addListener('dragend', function(event) {
        updateLocationFromLatLng(event.latLng);
    });

    // Get address from lat/lng on load if available
    if (defaultLat !== 6.9271 || defaultLng !== 79.8612) {
        updateLocationFromLatLng(new google.maps.LatLng(defaultLat, defaultLng));
    }
}

function updateLocationFromLatLng(latLng) {
    geocoder.geocode({ location: latLng }, function(results, status) {
        if (status === 'OK' && results[0]) {
            updateLocation(latLng.lat(), latLng.lng(), results[0].formatted_address);
            // Also update the full address field
            document.getElementById('property-location').value = results[0].formatted_address;
        } else {
            updateLocation(latLng.lat(), latLng.lng(), 'Address not found');
        }
    });
}

function updateLocation(lat, lng, address) {
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
    document.getElementById('selected-address').textContent = address;
    document.getElementById('selected-coords').textContent = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
}

// OLD Image Upload Functionality (REMOVE THIS SECTION)
/*
document.getElementById('property-images').addEventListener('change', function(e) {
    const files = e.target.files;
    const uploadedImages = document.getElementById('uploadedImages');
    const maxFiles = <?php echo isset($data['limits']['photos_per_stadium']) ? $data['limits']['photos_per_stadium'] : 5; ?>;
    
    if (files.length > maxFiles) {
        alert(`You can only upload ${maxFiles} images with your current package.`);
        return;
    }
    
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
*/

uploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    
    const files = e.dataTransfer.files;
    document.getElementById('property-images').files = files;
    document.getElementById('property-images').dispatchEvent(new Event('change'));
});

// Remove Image Function
function removeImage(button) {
    button.parentElement.remove();
}

// Form Validation
document.querySelector('.add-property-form').addEventListener('submit', function(e) {
    const name = document.getElementById('property-name').value;
    const description = document.getElementById('property-description').value;
    const price = document.getElementById('property-price').value;
    
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
    
    if (price < 100) {
        e.preventDefault();
        alert('Price must be at least LKR 100 per hour.');
        return;
    }
});

// Save as Draft Functionality
document.querySelector('.btn-save-draft').addEventListener('click', function() {
    const form = document.querySelector('.add-property-form');
    const formData = new FormData(form);
    formData.append('save_as_draft', '1');
    
    // Here you would make an AJAX call to save as draft
    alert('Property saved as draft!');
});

// Character Counter for Description
const descriptionTextarea = document.getElementById('property-description');
const charCounter = document.createElement('div');
charCounter.className = 'char-counter';
descriptionTextarea.parentNode.appendChild(charCounter);

descriptionTextarea.addEventListener('input', function() {
    const length = this.value.length;
    charCounter.textContent = `${length}/50 minimum characters`;
    charCounter.style.color = length >= 50 ? '#28a745' : '#dc3545';
});

// Initial character count
descriptionTextarea.dispatchEvent(new Event('input'));
</script>

<!-- Google Maps API Script -->
<script src="AIzaSyAoQeLXHgPfe6zhSo4oX3gWD9YGKBw5-jw" async defer></script>

<style>
.add-property-container {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 30px;
    margin-bottom: 40px;
}

.property-form-wrapper {
    background: #161616;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.form-section {
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 1px solid #e9ecef;
}

.form-section:last-of-type {
    border-bottom: none;
    margin-bottom: 30px;
}

.section-title {
    color: #212529;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    font-weight: 500;
    margin-bottom: 8px;
    color: #495057;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 12px 16px;
    border: 2px solid #161616;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #28a745;
}

.form-help {
    font-size: 12px;
    color: #6c757d;
    margin-top: 4px;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.feature-checkbox {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.feature-checkbox:hover {
    background: #e9ecef;
}

.feature-checkbox input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: all 0.3s ease;
}

.feature-checkbox input[type="checkbox"]:checked + .checkmark {
    background: #28a745;
    color: white;
}

.feature-text {
    font-weight: 500;
    color: #495057;
}

/* Google Maps Styles */
.map-section {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
}

.map-info {
    background: #e3f2fd;
    border-left: 4px solid #2196F3;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
}

.map-info p {
    margin: 0 0 5px 0;
    color: #1565c0;
    font-weight: 500;
}

.map-info small {
    color: #1976d2;
}

.map-search-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #03B200;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 15px;
    background: white;
    color: #333;
}

.map-search-input:focus {
    outline: none;
    border-color: #028a00;
    box-shadow: 0 0 0 3px rgba(3, 178, 0, 0.1);
}

.google-map {
    width: 100%;
    height: 450px;
    border-radius: 12px;
    border: 2px solid #dee2e6;
    margin-bottom: 20px;
}

.selected-location {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.location-icon {
    font-size: 32px;
    line-height: 1;
}

.location-details {
    flex: 1;
}

.location-details strong {
    display: block;
    color: #28a745;
    font-size: 14px;
    margin-bottom: 8px;
}

.location-details p {
    margin: 0 0 5px 0;
    color: #495057;
    font-size: 14px;
    line-height: 1.5;
}

.location-details small {
    color: #6c757d;
    font-size: 12px;
}

/* Old upload styles - keep for the functional upload section below */
.file-upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    padding: 40px;
    text-align: center;
    transition: all 0.3s ease;
}

.file-upload-area.dragover {
    border-color: #28a745;
    background: #f8fff8;
}

.upload-placeholder {
    cursor: pointer;
}

.upload-icon {
    font-size: 48px;
    margin-bottom: 16px;
}

.upload-placeholder h4 {
    margin: 0 0 8px 0;
    color: #495057;
}

.upload-placeholder p {
    margin: 0;
    color: #6c757d;
}

.upload-placeholder input[type="file"] {
    display: none;
}

.uploaded-images {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 16px;
    margin-top: 20px;
}

.image-preview {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    background: #f8f9fa;
}

.image-preview img {
    width: 100%;
    height: 120px;
    object-fit: cover;
}

.remove-image {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: rgba(220, 53, 69, 0.8);
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.image-name {
    padding: 8px;
    font-size: 12px;
    color: #6c757d;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
}

.form-actions {
    display: flex;
    gap: 16px;
    justify-content: flex-end;
    padding-top: 30px;
    border-top: 1px solid #e9ecef;
}

.form-actions button {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-cancel {
    background: #f8f9fa;
    color: #495057;
    border: 2px solid #dee2e6;
}

.btn-cancel:hover {
    background: #e9ecef;
}

.btn-save-draft {
    background: #ffc107;
    color: #212529;
    border: 2px solid #ffc107;
}

.btn-save-draft:hover {
    background: #e0a800;
    border-color: #e0a800;
}

.btn-add-property {
    background: #28a745;
    color: white;
    border: 2px solid #28a745;
}

.btn-add-property:hover {
    background: #218838;
    border-color: #218838;
}

.package-limits-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.limits-card,
.tips-card {
    background: #161616;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.limits-card h4,
.tips-card h4 {
    margin: 0 0 16px 0;
    color: #212529;
    font-size: 16px;
}

.limit-item {
    margin-bottom: 16px;
}

.limit-item:last-child {
    margin-bottom: 20px;
}

.limit-label {
    font-size: 13px;
    color: #6c757d;
    display: block;
    margin-bottom: 4px;
}

.limit-value {
    font-weight: 600;
    color: #212529;
    margin-bottom: 8px;
    display: block;
}

.limit-bar {
    height: 6px;
    background: #e9ecef;
    border-radius: 3px;
    overflow: hidden;
}

.limit-fill {
    height: 100%;
    background: linear-gradient(135deg, #28a745, #20c997);
    transition: width 0.3s ease;
}

.btn-upgrade-sidebar {
    background: linear-gradient(135deg, #ffc107, #fd7e14);
    color: white;
    padding: 10px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    text-align: center;
    display: block;
    transition: all 0.3s ease;
}

.btn-upgrade-sidebar:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
}

.tips-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.tips-list li {
    padding: 8px 0;
    color: #495057;
    font-size: 13px;
    border-bottom: 1px solid #f8f9fa;
    position: relative;
    padding-left: 20px;
}

.tips-list li:before {
    content: "•";
    color: #28a745;
    font-weight: bold;
    position: absolute;
    left: 0;
}

.tips-list li:last-child {
    border-bottom: none;
}

.char-counter {
    font-size: 12px;
    margin-top: 4px;
    text-align: right;
}

.alert {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 30px;
}

.alert-warning {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    color: #856404;
}

.alert-icon {
    font-size: 24px;
}

.alert-content h4 {
    margin: 0 0 8px 0;
    color: #856404;
}

.alert-content p {
    margin: 0 0 16px 0;
}

.btn-back {
    background: #f8f9fa;
    color: #495057;
    padding: 10px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: #e9ecef;
}

@media (max-width: 768px) {
    .add-property-container {
        grid-template-columns: 1fr;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .uploaded-images {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    }
}

/* Image Upload Styles */
.package-limit-banner {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.limit-icon {
    font-size: 24px;
}

.limit-text {
    font-size: 14px;
}

.limit-text strong {
    font-weight: 700;
}

.image-upload-container {
    margin-top: 10px;
}

.upload-box {
    border: 2px dashed #03B200;
    border-radius: 12px;
    padding: 40px 20px;
    text-align: center;
    background: #f8fff8;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.upload-box:hover {
    border-color: #028a00;
    background: #f0fff0;
}

.upload-box input[type="file"] {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    opacity: 0;
    cursor: pointer;
}

.upload-placeholder {
    pointer-events: none;
}

.upload-icon {
    font-size: 48px;
    display: block;
    margin-bottom: 10px;
}

.upload-text {
    font-size: 16px;
    font-weight: 600;
    color: #03B200;
    margin: 0 0 8px 0;
}

.upload-hint {
    font-size: 13px;
    color: #666;
    margin: 0;
}

.image-preview-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.image-preview-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #e0e0e0;
    background: #f5f5f5;
}

.image-preview-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    display: block;
}

.image-preview-item .remove-image {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(255, 0, 0, 0.8);
    color: white;
    border: none;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 18px;
    transition: all 0.3s ease;
}

.image-preview-item .remove-image:hover {
    background: rgba(200, 0, 0, 1);
}

.image-preview-item .primary-badge {
    position: absolute;
    bottom: 8px;
    left: 8px;
    background: rgba(3, 178, 0, 0.9);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.image-order-number {
    position: absolute;
    top: 8px;
    left: 8px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 600;
}

/* Dark Theme Alignment Overrides */
.property-form-wrapper,
.limits-card,
.tips-card {
    background: #161616;
    color: #f1f3f5;
    border: 1px solid #2b2f33;
}

.form-section {
    border-bottom: 1px solid #2b2f33;
}

.section-title,
.limits-card h4,
.tips-card h4 {
    color: #f8f9fa;
}

.form-group label,
.feature-text,
.location-details p,
.tips-list li,
.upload-placeholder h4,
.upload-placeholder p {
    color: #ced4da;
}

.form-help,
.location-details small,
.image-name,
.upload-hint,
.limit-label {
    color: #adb5bd;
}

.form-group input,
.form-group select,
.form-group textarea,
.map-search-input {
    background: #212529;
    color: #f1f3f5;
    border-color: #343a40;
}

.form-group input::placeholder,
.form-group textarea::placeholder,
.map-search-input::placeholder {
    color: #868e96;
}

.feature-checkbox {
    background: #212529;
}

.feature-checkbox:hover {
    background: #2b3035;
}

.checkmark {
    background: #161616;
    border: 1px solid #343a40;
}

.map-section {
    background: #1f1f1f;
    border: 1px solid #2b2f33;
}

.map-info {
    background: #212529;
    border-left: 4px solid #28a745;
}

.map-info p,
.map-info small {
    color: #ced4da;
}

.google-map {
    border-color: #343a40;
}

.selected-location {
    background: #212529;
    border: 1px solid #343a40;
}

.upload-box {
    background: #1f1f1f;
    border-color: #28a745;
}

.upload-box:hover {
    background: #212529;
    border-color: #218838;
}

.upload-text {
    color: #28a745;
}

.image-preview,
.image-preview-item {
    background: #212529;
    border-color: #343a40;
}

.form-actions {
    border-top: 1px solid #2b2f33;
}

.btn-cancel,
.btn-back {
    background: #212529;
    color: #e9ecef;
    border-color: #343a40;
}

.btn-cancel:hover,
.btn-back:hover {
    background: #2b3035;
}

.tips-list li {
    border-bottom: 1px solid #2b2f33;
}

.alert-warning {
    background: #2c2413;
    border: 1px solid #5d4b1f;
    color: #ffe69c;
}

.alert-content h4 {
    color: #ffe69c;
}

.package-limit-banner {
    background: linear-gradient(135deg, #212529, #161616);
    border: 1px solid #343a40;
}
</style>

<script>
let selectedFiles = [];
const maxImages = <?php echo isset($data['limits']['photos_per_stadium']) ? $data['limits']['photos_per_stadium'] : 5; ?>;

function handleImageUpload(input) {
    const files = Array.from(input.files);
    
    // Check limit
    if (files.length > maxImages) {
        alert(`You can only upload up to ${maxImages} images with your current package.`);
        input.value = '';
        return;
    }
    
    // Store ALL selected files (don't clear previous)
    selectedFiles = files;
    
    // Render all previews
    renderPreviews();
}

function renderPreviews() {
    const previewContainer = document.getElementById('image-preview-container');
    previewContainer.innerHTML = '';
    
    // Process each file
    selectedFiles.forEach((file, index) => {
        // Validate file type
        if (!file.type.match('image.*')) {
            return;
        }
        
        // Create preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewItem = document.createElement('div');
            previewItem.className = 'image-preview-item';
            previewItem.innerHTML = `
                <img src="${e.target.result}" alt="Preview ${index + 1}">
                <div class="image-order-number">${index + 1}</div>
                ${index === 0 ? '<div class="primary-badge">Primary</div>' : ''}
                <button type="button" class="remove-image" onclick="removeImage(${index})">×</button>
            `;
            previewContainer.appendChild(previewItem);
        };
        reader.readAsDataURL(file);
    });
}

function removeImage(index) {
    // Remove from selectedFiles array
    selectedFiles.splice(index, 1);
    
    // Update file input
    const input = document.getElementById('stadium-images');
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;
    
    // Re-render all previews
    renderPreviews();
}

// Form validation before submit
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.add-property-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const fileInput = document.getElementById('stadium-images');
            if (!fileInput.files || fileInput.files.length === 0) {
                e.preventDefault();
                alert('Please upload at least one image for your stadium.');
                return false;
            }
            if (fileInput.files.length > maxImages) {
                e.preventDefault();
                alert(`You can only upload up to ${maxImages} images with your current package.`);
                return false;
            }
        });
    }
});
</script>

<?php require APPROOT.'/views/stadium_owner/inc/footer.php'; ?>