<?php require APPROOT.'/views/inc/components/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/single-stadium.css">

<!-- Stadium Detail Section -->
<section class="stadium-detail-section">
    <div class="detail-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="<?php echo URLROOT; ?>">Home</a>
            <span>/</span>
            <a href="<?php echo URLROOT; ?>/stadiums">Stadiums</a>
            <span>/</span>
            <span><?php echo $data['stadium']->name; ?></span>
        </div>

        <!-- Stadium Header -->
        <div class="stadium-header">
            <div class="stadium-title">
                <h1><?php echo $data['stadium']->name; ?></h1>
                <div class="stadium-meta">
                    <div class="location">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                        <span><?php echo $data['stadium']->location; ?></span>
                    </div>
                    <div class="rating">
                        <div class="stars">
                            <?php 
                            $rating = $data['stadium']->rating;
                            for($i = 1; $i <= 5; $i++): 
                            ?>
                                <span class="star <?php echo $i <= floor($rating) ? 'filled' : ''; ?>">★</span>
                            <?php endfor; ?>
                        </div>
                        <span class="rating-number"><?php echo $data['stadium']->rating; ?></span>
                        <span class="review-count">(24 reviews)</span>
                    </div>
                    <div class="stadium-type">
                        <span class="type-badge"><?php echo $data['stadium']->type; ?></span>
                        <span class="category-badge"><?php echo $data['stadium']->category; ?></span>
                    </div>
                </div>
            </div>
            <div class="stadium-price">
                <div class="price-info">
                    <span class="price-amount">LKR <?php echo number_format($data['stadium']->price); ?></span>
                    <span class="price-period">per hour</span>
                </div>
                <div class="status-badge status-<?php echo strtolower($data['stadium']->status); ?>">
                    <?php echo $data['stadium']->status; ?>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="stadium-main-content">
            <!-- Left Column -->
            <div class="stadium-content">
                <!-- Image Gallery -->
                <div class="image-gallery">
                    <div class="main-image">
                        <?php
                        // Determine main image path
                        $firstImage = $data['gallery_images'][0];
                        $mainImageSrc = $firstImage['is_uploaded'] 
                            ? URLROOT . '/public/uploads/stadiums/' . $firstImage['path']
                            : URLROOT . '/images/stadiums/' . $firstImage['path'];
                        ?>
                        <img id="mainImage" src="<?php echo $mainImageSrc; ?>" alt="<?php echo $data['stadium']->name; ?>">
                        <button class="fullscreen-btn" onclick="openGalleryModal()">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="gallery-thumbnails">
                        <?php foreach(array_slice($data['gallery_images'], 0, 4) as $index => $image): ?>
                        <?php
                        $thumbSrc = $image['is_uploaded'] 
                            ? URLROOT . '/public/uploads/stadiums/' . $image['path']
                            : URLROOT . '/images/stadiums/' . $image['path'];
                        ?>
                        <div class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" onclick="changeMainImage('<?php echo $thumbSrc; ?>')">
                            <img src="<?php echo $thumbSrc; ?>" alt="Gallery Image <?php echo $index + 1; ?>">
                        </div>
                        <?php endforeach; ?>
                        <?php if(count($data['gallery_images']) > 4): ?>
                        <?php
                        $moreImageSrc = $data['gallery_images'][4]['is_uploaded'] 
                            ? URLROOT . '/public/uploads/stadiums/' . $data['gallery_images'][4]['path']
                            : URLROOT . '/images/stadiums/' . $data['gallery_images'][4]['path'];
                        ?>
                        <div class="thumbnail more-images" onclick="openGalleryModal()">
                            <div class="more-overlay">
                                <span>+<?php echo count($data['gallery_images']) - 4; ?></span>
                            </div>
                            <img src="<?php echo $moreImageSrc; ?>" alt="More Images">
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Stadium Description -->
                <div class="stadium-description">
                    <h2>About This Stadium</h2>
                    <?php if (!empty($data['stadium']->description)): ?>
                        <p><?php echo nl2br(htmlspecialchars($data['stadium']->description)); ?></p>
                    <?php else: ?>
                        <p>Experience world-class sports facilities at <?php echo $data['stadium']->name; ?>. Our premium <?php echo strtolower($data['stadium']->type); ?> stadium offers professional-grade playing surfaces and top-notch amenities for players of all skill levels. Whether you're planning a competitive match or casual practice session, our facility provides the perfect environment for your sporting needs.</p>
                        
                        <p>Located in the heart of <?php echo $data['stadium']->location; ?>, we've been serving the local sports community with excellence and dedication. Our commitment to maintaining the highest standards ensures every game is played on a surface that meets professional requirements.</p>
                    <?php endif; ?>
                </div>

                <!-- Features & Amenities -->
                <div class="stadium-features-section">
                    <h2>Features & Amenities</h2>
                    <div class="features-grid">
                        <?php foreach($data['stadium']->features as $feature): ?>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <?php 
                                // Add icons for features
                                $icon = '';
                                switch(strtolower($feature)) {
                                    case 'lighting': $icon = '💡'; break;
                                    case 'parking': $icon = '🚗'; break;
                                    case 'wifi': $icon = '📶'; break;
                                    case 'air conditioning': $icon = '❄️'; break;
                                    case 'professional turf': $icon = '🌱'; break;
                                    case 'equipment rental': $icon = '⚽'; break;
                                    case 'changing rooms': $icon = '🚿'; break;
                                    case 'seating': $icon = '💺'; break;
                                    case 'sound system': $icon = '🔊'; break;
                                    case 'cafeteria': $icon = '🍕'; break;
                                    default: $icon = '✓'; break;
                                }
                                echo $icon;
                                ?>
                            </div>
                            <span class="feature-name"><?php echo $feature; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Location & Map -->
                <div class="location-section">
                    <h2>Location</h2>
                    <div class="location-info">
                        <div class="address">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                            </svg>
                            <span><?php echo $data['stadium']->location; ?>, Sri Lanka</span>
                        </div>
                    </div>
                    <div class="map-container">
                        <div id="map" class="stadium-map">
                            <!-- Google Map will be embedded here -->
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63371.8174852742!2d79.82132259999999!3d6.921837400000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae253d10f7a7003%3A0x320b2e4d32d3838d!2sColombo!5e0!3m2!1sen!2slk!4v1642678901234!5m2!1sen!2slk"
                                width="100%" 
                                height="300" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy">
                            </iframe>
                        </div>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="reviews-section">
                    <div class="reviews-header">
                        <h2>Customer Reviews</h2>
                        <div class="review-summary">
                            <div class="average-rating">
                                <span class="rating-big"><?php echo $data['stadium']->rating; ?></span>
                                <div class="rating-stars">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <span class="star <?php echo $i <= floor($data['stadium']->rating) ? 'filled' : ''; ?>">★</span>
                                    <?php endfor; ?>
                                </div>
                                <span class="total-reviews">Based on 24 reviews</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="reviews-list">
                        <?php foreach($data['reviews'] as $review): ?>
                        <div class="review-item">
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">
                                    <?php echo substr($review['customer_name'], 0, 1); ?>
                                </div>
                                <div class="reviewer-details">
                                    <h4><?php echo $review['customer_name']; ?></h4>
                                    <div class="review-meta">
                                        <div class="review-rating">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <span class="star <?php echo $i <= $review['rating'] ? 'filled' : ''; ?>">★</span>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="review-date"><?php echo date('M j, Y', strtotime($review['date'])); ?></span>
                                        <?php if($review['verified']): ?>
                                        <span class="verified-badge">Verified</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <p class="review-comment"><?php echo $review['comment']; ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button class="btn-load-more-reviews">Load More Reviews</button>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="stadium-sidebar">
                <!-- Booking Card -->
                <div class="booking-card">
                    <div class="booking-header">
                        <h3>Book This Stadium</h3>
                        <div class="price-display">
                            <span class="price">LKR <?php echo number_format($data['stadium']->price); ?></span>
                            <span class="period">per hour</span>
                        </div>
                    </div>
                    
                    <form class="booking-form" id="bookingForm">
                        <div class="form-group">
                            <label for="booking-date">Date</label>
                            <input type="date" id="booking-date" name="date" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="start-time">Start Time</label>
                                <select id="start-time" name="start_time" required>
                                    <option value="">Select Time</option>
                                    <option value="06:00">6:00 AM</option>
                                    <option value="07:00">7:00 AM</option>
                                    <option value="08:00">8:00 AM</option>
                                    <option value="09:00">9:00 AM</option>
                                    <option value="10:00">10:00 AM</option>
                                    <option value="11:00">11:00 AM</option>
                                    <option value="12:00">12:00 PM</option>
                                    <option value="13:00">1:00 PM</option>
                                    <option value="14:00">2:00 PM</option>
                                    <option value="15:00">3:00 PM</option>
                                    <option value="16:00">4:00 PM</option>
                                    <option value="17:00">5:00 PM</option>
                                    <option value="18:00">6:00 PM</option>
                                    <option value="19:00">7:00 PM</option>
                                    <option value="20:00">8:00 PM</option>
                                    <option value="21:00">9:00 PM</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="duration">Duration</label>
                                <select id="duration" name="duration" required>
                                    <option value="">Hours</option>
                                    <option value="1">1 Hour</option>
                                    <option value="2">2 Hours</option>
                                    <option value="3">3 Hours</option>
                                    <option value="4">4 Hours</option>
                                    <option value="5">5 Hours</option>
                                    <option value="6">6 Hours</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="booking-summary">
                            <div class="summary-row" style="color: #000000 !important">
                                <span>Subtotal:</span>
                                <span id="subtotal">LKR 0</span>
                            </div>
                            <div class="summary-row" style="color: #000000 !important">
                                <span>Service Fee:</span>
                                <span id="service-fee">LKR 0</span>
                            </div>
                            <div class="summary-row total">
                                <span>Total:</span>
                                <span id="total-amount">LKR 0</span>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-book-now">Book Now</button>
                    </form>
                    
                    <div class="booking-info">
                        <p><strong>Free cancellation</strong> up to 12 hours before booking</p>
                        <p><strong>Instant confirmation</strong> - You'll receive confirmation immediately</p>
                    </div>
                </div>

                <!-- Owner Info Card -->
                <div class="owner-info-card">
                    <div class="owner-header">
                        <div class="owner-avatar">
                            <?php echo substr($data['stadium']->owner, 0, 1); ?>
                        </div>
                        <div class="owner-details">
                            <h4><?php echo $data['stadium']->owner; ?></h4>
                            <div class="owner-status">
                                <span class="status-dot status-<?php echo strtolower($data['stadium']->owner_status); ?>"></span>
                                <span><?php echo $data['stadium']->owner_status; ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="owner-stats">
                        <div class="stat-item">
                            <span class="stat-number">4.8</span>
                            <span class="stat-label">Rating</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">127</span>
                            <span class="stat-label">Reviews</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">3</span>
                            <span class="stat-label">Properties</span>
                        </div>
                    </div>
                    
                    <div class="owner-actions">
                        <button class="btn-contact-owner" onclick="openMessageModal(<?php echo $data['stadium']->id; ?>, <?php echo $data['stadium']->owner_id ?? 0; ?>)">Message Owner</button>
                    </div>
                </div>

                <!-- Quick Info Card -->
                <div class="quick-info-card">
                    <h4>Quick Information</h4>
                    <div class="info-list">
                        <div class="info-item">
                            <span class="info-label">Sport Type:</span>
                            <span class="info-value"><?php echo $data['stadium']->type; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Category:</span>
                            <span class="info-value"><?php echo $data['stadium']->category; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Capacity:</span>
                            <span class="info-value">22 Players</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Surface:</span>
                            <span class="info-value">Natural Grass</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Parking:</span>
                            <span class="info-value">Available</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Nearby Stadiums Section -->
<section class="nearby-stadiums-section">
    <div class="nearby-container">
        <div class="section-header">
            <h2>Nearby Stadiums</h2>
            <p>Other great options in <?php echo $data['stadium']->location; ?></p>
        </div>
        
        <div class="nearby-stadiums-grid">
            <?php foreach($data['nearby_stadiums'] as $stadium): ?>
            <div class="nearby-stadium-card">
                <div class="stadium-image">
                    <?php
                    // Check if image is from new upload system
                    $nearbySrc = (strpos($stadium->image, 'stadium_') === 0) 
                        ? URLROOT . '/public/uploads/stadiums/' . $stadium->image
                        : URLROOT . '/images/stadiums/' . $stadium->image;
                    ?>
                    <img src="<?php echo $nearbySrc; ?>" 
                         alt="<?php echo $stadium->name; ?>"
                         onerror="this.src='<?php echo URLROOT; ?>/images/stadiums/default-stadium.jpg'">
                    <div class="rating-badge">
                        <span class="star">⭐</span>
                        <span class="rating"><?php echo $stadium->rating; ?></span>
                    </div>
                </div>
                <div class="stadium-meta">
                    <h3><a href="<?php echo URLROOT; ?>/stadiums/single/<?php echo $stadium->id; ?>"><?php echo $stadium->name; ?></a></h3>
                    <div class="location">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                        <span><?php echo $stadium->location; ?></span>
                    </div>
                    <div class="price">
                        <span class="amount">LKR <?php echo number_format($stadium->price); ?></span>
                        <span class="period">per hour</span>
                    </div>
                    <div class="features">
                        <?php foreach(array_slice($stadium->features, 0, 2) as $feature): ?>
                            <span class="feature-tag"><?php echo $feature; ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="view-more-nearby">
            <a href="<?php echo URLROOT; ?>/stadiums" class="btn-view-more">View All Stadiums</a>
        </div>
    </div>
</section>

<!-- Gallery Modal -->
<div id="galleryModal" class="modal">
    <div class="modal-content gallery-modal">
        <div class="modal-header">
            <h3><?php echo $data['stadium']->name; ?> - Gallery</h3>
            <span class="close" onclick="closeGalleryModal()">&times;</span>
        </div>
        <div class="gallery-modal-content">
            <div class="gallery-main-image">
                <img id="modalMainImage" src="" alt="Gallery Image">
                <button class="prev-btn" onclick="previousImage()">❮</button>
                <button class="next-btn" onclick="nextImage()">❯</button>
            </div>
            <div class="gallery-thumbnails-modal">
                <?php foreach($data['gallery_images'] as $index => $image): ?>
                <?php
                $modalThumbSrc = $image['is_uploaded'] 
                    ? URLROOT . '/public/uploads/stadiums/' . $image['path']
                    : URLROOT . '/images/stadiums/' . $image['path'];
                ?>
                <div class="thumbnail-modal" onclick="selectModalImage(<?php echo $index; ?>)">
                    <img src="<?php echo $modalThumbSrc; ?>" alt="Gallery Image <?php echo $index + 1; ?>">
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Login/Register Modal for Booking -->
<div id="bookingAuthModal" class="modal">
    <div class="modal-content booking-auth-modal">
        <div class="modal-header">
            <h3>🎫 Complete Your Booking</h3>
            <span class="close" onclick="closeBookingAuthModal()">&times;</span>
        </div>
        <div class="modal-body">
            <!-- Tab Navigation -->
            <div class="auth-tabs">
                <button class="auth-tab active" data-tab="login">
                    🔐 Login
                </button>
                <button class="auth-tab" data-tab="register">
                    📝 Register
                </button>
            </div>

            <!-- Login Tab -->
            <div id="loginTab" class="auth-tab-content active">
                <h4>Login to Your Account</h4>
                <form id="bookingLoginForm">
                    <div class="form-group">
                        <label for="booking_login_email">Email *</label>
                        <input type="email" id="booking_login_email" name="email" placeholder="your@email.com" required>
                    </div>
                    <div class="form-group">
                        <label for="booking_login_password">Password *</label>
                        <input type="password" id="booking_login_password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="form-group checkbox">
                        <label>
                            <input type="checkbox" name="remember">
                            Remember me
                        </label>
                    </div>
                    <button type="submit" class="btn-submit">🔓 Login</button>
                    <p class="auth-error" id="loginAuthError" style="display:none;"></p>
                </form>
            </div>

            <!-- Register Tab -->
            <div id="registerTab" class="auth-tab-content">
                <h4>Create New Account</h4>
                <form id="bookingRegisterForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="booking_register_first_name">First Name *</label>
                            <input type="text" id="booking_register_first_name" name="first_name" placeholder="John" required>
                        </div>
                        <div class="form-group">
                            <label for="booking_register_last_name">Last Name *</label>
                            <input type="text" id="booking_register_last_name" name="last_name" placeholder="Doe" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="booking_register_email">Email *</label>
                        <input type="email" id="booking_register_email" name="email" placeholder="your@email.com" required>
                    </div>
                    <div class="form-group">
                        <label for="booking_register_phone">Phone *</label>
                        <input type="tel" id="booking_register_phone" name="phone" placeholder="+94712345678" required>
                    </div>
                    <div class="form-group">
                        <label for="booking_register_password">Password *</label>
                        <input type="password" id="booking_register_password" name="password" placeholder="Min. 8 characters" required>
                    </div>
                    <div class="form-group">
                        <label for="booking_register_confirm_password">Confirm Password *</label>
                        <input type="password" id="booking_register_confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                    </div>
                    <div class="form-group checkbox">
                        <label>
                            <input type="checkbox" name="agree_terms" required>
                            I agree to the <a href="#">Terms & Conditions</a>
                        </label>
                    </div>
                    <button type="submit" class="btn-submit">📝 Create Account</button>
                    <p class="auth-error" id="registerAuthError" style="display:none;"></p>
                </form>
            </div>

            <div class="auth-footer">
                <p>After authentication, you'll be redirected to the checkout page to complete your booking.</p>
            </div>
        </div>
    </div>
</div>

<!-- Message Owner Modal -->
<div id="messageModal" class="modal">
    <div class="modal-content message-modal">
        <div class="modal-header">
            <h3>📧 Contact Stadium Owner</h3>
            <span class="close" onclick="closeMessageModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div id="messageLoginSection" style="display:none;">
                <p class="login-prompt">Please log in to send a message to the stadium owner.</p>
                <form id="messageLoginForm">
                    <div class="form-group">
                        <label for="message_login_email">Email *</label>
                        <input type="email" id="message_login_email" name="email" placeholder="your@email.com" required>
                    </div>
                    <div class="form-group">
                        <label for="message_login_password">Password *</label>
                        <input type="password" id="message_login_password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" id="message_login_remember">
                            Remember me
                        </label>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="closeMessageModal()">Cancel</button>
                        <button type="submit" class="btn-login">Login</button>
                    </div>
                    <p class="login-error" id="messageLoginError" style="display:none;"></p>
                </form>
            </div>
            <div id="messageFormSection" style="display:none;">
                <form id="messageForm" method="POST">
                    <input type="hidden" id="stadium_id" name="stadium_id">
                    <input type="hidden" id="receiver_id" name="receiver_id">
                    
                    <div class="form-group">
                        <label for="message_subject">Subject *</label>
                        <input type="text" 
                               id="message_subject" 
                               name="subject" 
                               placeholder="e.g., Booking Inquiry"
                               required
                               maxlength="255">
                    </div>
                    
                    <div class="form-group">
                        <label for="message_text">Your Message *</label>
                        <textarea id="message_text" 
                                  name="message" 
                                  rows="6" 
                                  placeholder="Write your message here..."
                                  required
                                  maxlength="1000"></textarea>
                        <small class="char-count">0/1000 characters</small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="closeMessageModal()">Cancel</button>
                        <button type="submit" class="btn-send">Send Message 📨</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<style>
/* Login/Register Modal for Booking */
.booking-auth-modal {
    max-width: 500px;
    margin: 50px auto;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
}

.booking-auth-modal .modal-header {
    background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
    color: white;
    padding: 20px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.booking-auth-modal .modal-header h3 {
    margin: 0;
    font-size: 20px;
}

.booking-auth-modal .close {
    font-size: 28px;
    font-weight: bold;
    color: white;
    cursor: pointer;
    opacity: 0.8;
    transition: opacity 0.2s;
    background: none;
    border: none;
    padding: 0;
}

.booking-auth-modal .close:hover {
    opacity: 1;
}

.booking-auth-modal .modal-body {
    padding: 30px;
}

/* Auth Tabs */
.auth-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 30px;
    border-bottom: 2px solid #e0e0e0;
}

.auth-tab {
    padding: 12px 20px;
    background: none;
    border: none;
    border-bottom: 3px solid transparent;
    font-size: 15px;
    font-weight: 600;
    color: #666;
    cursor: pointer;
    transition: all 0.3s;
}

.auth-tab:hover {
    color: #2e7d32;
}

.auth-tab.active {
    color: #2e7d32;
    border-bottom-color: #2e7d32;
}

/* Tab Content */
.auth-tab-content {
    display: none;
}

.auth-tab-content.active {
    display: block;
}

.auth-tab-content h4 {
    color: #333;
    margin-bottom: 20px;
    font-size: 16px;
}

/* Form Styling */
.booking-auth-modal .form-group {
    margin-bottom: 18px;
}

.booking-auth-modal .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.booking-auth-modal .form-group label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 600;
    font-size: 14px;
}

.booking-auth-modal .form-group input {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    transition: all 0.3s;
}

.booking-auth-modal .form-group input:focus {
    outline: none;
    border-color: #2e7d32;
    box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
}

.booking-auth-modal .form-group.checkbox {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.booking-auth-modal .form-group.checkbox label {
    display: flex;
    align-items: center;
    margin-bottom: 0;
    font-weight: 500;
    cursor: pointer;
}

.booking-auth-modal .form-group.checkbox input[type="checkbox"] {
    width: auto;
    margin-right: 8px;
    cursor: pointer;
}

.booking-auth-modal .form-group.checkbox a {
    color: #2e7d32;
    text-decoration: none;
    margin-left: 4px;
}

.booking-auth-modal .form-group.checkbox a:hover {
    text-decoration: underline;
}

/* Buttons */
.booking-auth-modal .btn-submit {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
}

.booking-auth-modal .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(46, 125, 50, 0.3);
}

.booking-auth-modal .btn-submit:active {
    transform: translateY(0);
}

/* Error Messages */
.auth-error {
    margin-top: 12px;
    padding: 12px;
    background: #ffebee;
    color: #c62828;
    border-radius: 6px;
    font-size: 13px;
    border-left: 4px solid #c62828;
}

/* Auth Footer */
.auth-footer {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e0e0e0;
    text-align: center;
    color: #666;
    font-size: 13px;
}

/* Message Modal Styles */
.message-modal {
    max-width: 600px;
    margin: 50px auto;
}

.message-modal .modal-header {
    background: linear-gradient(135deg, #03B200 0%, #028a00 100%);
    color: white;
    padding: 20px 30px;
    border-radius: 12px 12px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.message-modal .modal-header h3 {
    margin: 0;
    font-size: 20px;
}

.message-modal .close {
    font-size: 28px;
    font-weight: bold;
    color: white;
    cursor: pointer;
    opacity: 0.8;
    transition: opacity 0.2s;
}

.message-modal .close:hover {
    opacity: 1;
}

.message-modal .modal-body {
    padding: 30px;
    background: white;
    border-radius: 0 0 12px 12px;
}

.message-modal .form-group {
    margin-bottom: 20px;
}

.message-modal .form-group label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 600;
    font-size: 14px;
}

.message-modal .form-group input[type="text"],
.message-modal .form-group textarea,
.message-modal .form-group input[type="email"],
.message-modal .form-group input[type="password"] {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    transition: border-color 0.3s;
}

.message-modal .form-group input[type="text"]:focus,
.message-modal .form-group textarea:focus,
.message-modal .form-group input[type="email"]:focus,
.message-modal .form-group input[type="password"]:focus {
    outline: none;
    border-color: #03B200;
}

.message-modal .form-group textarea {
    resize: vertical;
    min-height: 120px;
}

.message-modal .char-count {
    display: block;
    margin-top: 5px;
    color: #666;
    font-size: 12px;
    text-align: right;
}

.message-modal .login-prompt {
    margin-bottom: 20px;
    color: #444;
}

.message-modal .form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    margin-top: 25px;
}

.message-modal .btn-cancel,
.message-modal .btn-send,
.message-modal .btn-login {
    padding: 12px 30px;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.message-modal .btn-cancel {
    background: #f5f5f5;
    color: #666;
}

.message-modal .btn-cancel:hover {
    background: #e0e0e0;
}

.message-modal .btn-send,
.message-modal .btn-login {
    background: linear-gradient(135deg, #03B200 0%, #028a00 100%);
    color: white;
}

.message-modal .btn-send:hover,
.message-modal .btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(3, 178, 0, 0.3);
}

.message-modal .login-error {
    margin-top: 10px;
    color: #d63031;
    font-size: 13px;
}

/* Checkout Loading Overlay */
#checkoutLoadingOverlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    animation: fadeIn 0.3s ease-in;
}

.checkout-loading-content {
    text-align: center;
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
}

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #2e7d32;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 15px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

#checkoutLoadingMessage {
    font-size: 16px;
    color: #333;
    margin: 0;
    font-weight: 500;
}
</style>

<script>
// Character counter for message
document.addEventListener('DOMContentLoaded', function() {
    const messageTextarea = document.getElementById('message_text');
    const charCount = document.querySelector('.char-count');
    
    if(messageTextarea && charCount) {
        messageTextarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = `${length}/1000 characters`;
            charCount.style.color = length >= 1000 ? '#dc3545' : '#666';
        });
    }
});
</script>

<script>
// Gallery functionality
let currentImageIndex = 0;
const galleryImages = <?php echo json_encode($data['gallery_images']); ?>;

function getImageSrc(image) {
    if (typeof image === 'object') {
        return image.is_uploaded 
            ? '<?php echo URLROOT; ?>/public/uploads/stadiums/' + image.path
            : '<?php echo URLROOT; ?>/images/stadiums/' + image.path;
    }
    // Fallback for old format
    return '<?php echo URLROOT; ?>/images/stadiums/' + image;
}

function changeMainImage(imageSrc) {
    document.getElementById('mainImage').src = imageSrc;
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail').forEach(thumb => thumb.classList.remove('active'));
    event.target.closest('.thumbnail').classList.add('active');
}

function openGalleryModal() {
    const modal = document.getElementById('galleryModal');
    modal.style.display = 'block';
    
    if (galleryImages.length > 0) {
        currentImageIndex = 0;
        document.getElementById('modalMainImage').src = getImageSrc(galleryImages[currentImageIndex]);
        updateModalThumbnails();
    }
}

function closeGalleryModal() {
    document.getElementById('galleryModal').style.display = 'none';
}

function selectModalImage(index) {
    currentImageIndex = index;
    document.getElementById('modalMainImage').src = getImageSrc(galleryImages[currentImageIndex]);
    updateModalThumbnails();
}

function previousImage() {
    currentImageIndex = currentImageIndex > 0 ? currentImageIndex - 1 : galleryImages.length - 1;
    document.getElementById('modalMainImage').src = getImageSrc(galleryImages[currentImageIndex]);
    updateModalThumbnails();
}

function nextImage() {
    currentImageIndex = currentImageIndex < galleryImages.length - 1 ? currentImageIndex + 1 : 0;
    document.getElementById('modalMainImage').src = getImageSrc(galleryImages[currentImageIndex]);
    updateModalThumbnails();
}

function updateModalThumbnails() {
    document.querySelectorAll('.thumbnail-modal').forEach((thumb, index) => {
        thumb.classList.toggle('active', index === currentImageIndex);
    });
}

// Booking form functionality
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bookingForm');
    const startTime = document.getElementById('start-time');
    const duration = document.getElementById('duration');
    const pricePerHour = <?php echo $data['stadium']->price; ?>;
    
    function updateBookingSummary() {
        const hours = parseInt(duration.value) || 0;
        const subtotal = pricePerHour * hours;
        const serviceFee = Math.round(subtotal * 0.05); // 5% service fee
        const total = subtotal + serviceFee;
        
        document.getElementById('subtotal').textContent = 'LKR ' + subtotal.toLocaleString();
        document.getElementById('service-fee').textContent = 'LKR ' + serviceFee.toLocaleString();
        document.getElementById('total-amount').textContent = 'LKR ' + total.toLocaleString();
    }
    
    duration.addEventListener('change', updateBookingSummary);
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const date = formData.get('date');
        const startTimeValue = formData.get('start_time');
        const durationValue = formData.get('duration');
        
        if (!date || !startTimeValue || !durationValue) {
            alert('Please fill in all required fields');
            return;
        }
        
        // Simulate booking process
        alert('Booking request submitted! You will be redirected to payment page.');
        // In real implementation, this would redirect to booking/payment page
        // window.location.href = '<?php echo URLROOT; ?>/booking/confirm';
    });
});

// Message owner functionality
let isMessageUserLoggedIn = <?php echo Auth::isLoggedIn() ? 'true' : 'false'; ?>;

function showMessageForm() {
    document.getElementById('messageLoginSection').style.display = 'none';
    document.getElementById('messageFormSection').style.display = 'block';
}

function showLoginSection() {
    document.getElementById('messageLoginSection').style.display = 'block';
    document.getElementById('messageFormSection').style.display = 'none';
}

function openMessageModal(stadiumId, ownerId) {
    document.getElementById('stadium_id').value = stadiumId;
    document.getElementById('receiver_id').value = ownerId;

    if (isMessageUserLoggedIn) {
        showMessageForm();
    } else {
        showLoginSection();
    }

    document.getElementById('messageModal').style.display = 'block';
}

function closeMessageModal() {
    document.getElementById('messageModal').style.display = 'none';
    const messageForm = document.getElementById('messageForm');
    const loginForm = document.getElementById('messageLoginForm');
    if (messageForm) messageForm.reset();
    if (loginForm) loginForm.reset();
    document.getElementById('messageLoginError').style.display = 'none';
    document.getElementById('messageLoginError').textContent = '';
}

// Send message or login within popup
function initializeMessageModalForms() {
    const messageForm = document.getElementById('messageForm');
    const loginForm = document.getElementById('messageLoginForm');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            fetch('<?php echo URLROOT; ?>/login/ajax', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const errorEl = document.getElementById('messageLoginError');
                if (data.success) {
                    isMessageUserLoggedIn = true;
                    showMessageForm();
                    errorEl.style.display = 'none';
                    errorEl.textContent = '';
                } else {
                    errorEl.style.display = 'block';
                    errorEl.textContent = data.message || 'Login failed. Please try again.';
                }
            })
            .catch(error => {
                console.error('Login error:', error);
                const errorEl = document.getElementById('messageLoginError');
                errorEl.style.display = 'block';
                errorEl.textContent = 'Login failed. Please try again later.';
            });
        });
    }

    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            fetch('<?php echo URLROOT; ?>/messages/send', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Message sent successfully! The owner will respond to you soon.');
                    closeMessageModal();
                } else {
                    alert('❌ Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to send message. Please try again.');
            });
        });
    }
}

// Booking form price calculation and submission
function calculatePrice() {
    const duration = document.getElementById('duration').value;
    const pricePerHour = <?php echo (int)$data['stadium']->price; ?>;
    
    if (!duration) {
        document.getElementById('subtotal').textContent = 'LKR 0';
        document.getElementById('service-fee').textContent = 'LKR 0';
        document.getElementById('total-amount').textContent = 'LKR 0';
        return;
    }
    
    const subtotal = pricePerHour * parseInt(duration);
    const serviceFee = subtotal * 0.02; // 2% service fee
    const total = subtotal + serviceFee;
    
    document.getElementById('subtotal').textContent = 'LKR ' + subtotal.toLocaleString('en-US');
    document.getElementById('service-fee').textContent = 'LKR ' + serviceFee.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('total-amount').textContent = 'LKR ' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

// Store booking data for later use
let pendingBookingData = null;

// Global booking state
let isUserLoggedIn = <?php echo Auth::isLoggedIn() ? 'true' : 'false'; ?>;

// Update UI after successful login
function updateUIAfterLogin(userData) {
    console.log('Updating UI after login:', userData);
    isUserLoggedIn = true;
    
    // Show success message
    const successMsg = document.createElement('div');
    successMsg.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #2e7d32;
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        z-index: 10000;
        animation: slideInRight 0.4s ease-out;
        box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
    `;
    successMsg.innerHTML = `✅ Welcome ${userData.user_name}! Preparing checkout...`;
    document.body.appendChild(successMsg);
    
    // Remove after 2 seconds
    setTimeout(() => {
        successMsg.style.animation = 'slideOutRight 0.3s ease-in';
        setTimeout(() => successMsg.remove(), 300);
    }, 2000);
    
    // Add animation styles if not already present
    if (!document.getElementById('bookingAnimationStyles')) {
        const style = document.createElement('style');
        style.id = 'bookingAnimationStyles';
        style.innerHTML = `
            @keyframes slideInRight {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
}

// Modal functions for booking auth
function openBookingAuthModal(bookingData) {
    pendingBookingData = bookingData;
    document.getElementById('bookingAuthModal').style.display = 'block';
    // Reset forms
    document.getElementById('bookingLoginForm').reset();
    document.getElementById('bookingRegisterForm').reset();
    // Hide error messages
    document.getElementById('loginAuthError').style.display = 'none';
    document.getElementById('registerAuthError').style.display = 'none';
}

function closeBookingAuthModal() {
    document.getElementById('bookingAuthModal').style.display = 'none';
    pendingBookingData = null;
}

// Handle booking form submission
const bookingForm = document.getElementById('bookingForm');
if (bookingForm) {
    // Add event listeners to duration and start time for price calculation
    document.getElementById('duration').addEventListener('change', calculatePrice);
    
    // Form submission
    bookingForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form values
        const bookingDate = document.getElementById('booking-date').value;
        const startTime = document.getElementById('start-time').value;
        const duration = document.getElementById('duration').value;
        
        // Validate all fields
        if (!bookingDate || !startTime || !duration) {
            alert('❌ Please select date, start time, and duration.');
            return;
        }
        
        // Prepare booking data
        const stadiumId = <?php echo (int)$data['stadium']->id; ?>;
        const stadiumPrice = <?php echo (float)$data['stadium']->price; ?>;
        
        if (!stadiumId || !stadiumPrice) {
            alert('❌ Stadium information not available. Please refresh the page.');
            return;
        }
        
        const bookingData = {
            stadium_id: stadiumId,
            booking_date: bookingDate,
            start_time: startTime,
            duration_hours: parseInt(duration),
            stadium_price: stadiumPrice
        };
        
        if (!isUserLoggedIn) {
            // Show login/register modal instead of redirecting
            openBookingAuthModal(bookingData);
        } else {
            // Proceed directly to checkout
            proceedToCheckout(bookingData);
        }
    });
}

function proceedToCheckout(bookingData) {
    console.log('proceedToCheckout called with:', bookingData);
    
    // Disable submit button if it exists
    const submitBtn = bookingForm ? bookingForm.querySelector('button[type="submit"]') : null;
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Processing...';
    }
    
    // Show loading overlay
    showCheckoutLoading();
    
    // Log the data being sent for debugging
    console.log('Booking Data being sent:', bookingData);
    console.log('JSON string:', JSON.stringify(bookingData));
    
    // Send booking request
    console.log('Sending POST request to:', '<?php echo URLROOT; ?>/booking/checkout');
    
    fetch('<?php echo URLROOT; ?>/booking/checkout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(bookingData)
    })
    .then(response => {
        console.log('Checkout Response status:', response.status, response.statusText);
        if (!response.ok) {
            throw new Error('HTTP ' + response.status + ': ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        console.log('Checkout Response data:', data);
        if (data.success) {
            console.log('Booking successful! Preparing checkout...');
            const redirectUrl = '<?php echo URLROOT; ?>/booking/checkout/' + data.booking_id;
            console.log('Redirect URL:', redirectUrl);
            
            // Show smooth transition message
            updateCheckoutLoadingMessage('🎫 Preparing your payment page...');
            
            // Add a small delay for smooth UX
            setTimeout(() => {
                window.location.href = redirectUrl;
            }, 800);
        } else {
            const errorMsg = data.message || 'Failed to create booking';
            console.error('Booking failed:', errorMsg);
            hideCheckoutLoading();
            alert('❌ Booking Error: ' + errorMsg);
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Book Now';
            }
        }
    })
    .catch(error => {
        console.error('Checkout fetch error:', error);
        hideCheckoutLoading();
        alert('❌ Connection Error: ' + error.message + '. Please check console for details.');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Book Now';
        }
    });
}

// Helper functions for checkout loading overlay
function showCheckoutLoading() {
    let overlay = document.getElementById('checkoutLoadingOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'checkoutLoadingOverlay';
        overlay.innerHTML = `
            <div class="checkout-loading-content">
                <div class="spinner"></div>
                <p id="checkoutLoadingMessage">🔄 Processing your booking...</p>
            </div>
        `;
        document.body.appendChild(overlay);
    }
    overlay.style.display = 'flex';
}

function hideCheckoutLoading() {
    const overlay = document.getElementById('checkoutLoadingOverlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
}

function updateCheckoutLoadingMessage(message) {
    const msgEl = document.getElementById('checkoutLoadingMessage');
    if (msgEl) {
        msgEl.textContent = message;
    }
}

// Modal Tab Switching
document.addEventListener('DOMContentLoaded', function() {
    const authTabs = document.querySelectorAll('.auth-tab');
    const tabContents = document.querySelectorAll('.auth-tab-content');
    
    authTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            
            // Remove active class from all tabs and contents
            authTabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(tc => tc.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            document.getElementById(tabName + 'Tab').classList.add('active');
        });
    });
    
    // Login form submission
    const loginForm = document.getElementById('bookingLoginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('booking_login_email').value;
            const password = document.getElementById('booking_login_password').value;
            const remember = loginForm.querySelector('input[name="remember"]').checked;
            
            console.log('Login form submitted:', {email, remember});
            console.log('Pending booking data:', pendingBookingData);
            
            const formData = new FormData();
            formData.append('email', email);
            formData.append('password', password);
            formData.append('remember', remember ? 1 : 0);
            
            const submitBtn = loginForm.querySelector('button[type="submit"]');
            const errorEl = document.getElementById('loginAuthError');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Logging in...';
            errorEl.style.display = 'none';
            
            console.log('Sending login request to /login/ajax');
            
            fetch('<?php echo URLROOT; ?>/login/ajax', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Login response status:', response.status, response.statusText);
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                console.log('Login response data:', data);
                if (data.success) {
                    console.log('Login successful! Verifying session...');
                    
                    // Verify session was established
                    setTimeout(() => {
                        fetch('<?php echo URLROOT; ?>/login/check_session', {
                            method: 'GET',
                            credentials: 'include'
                        })
                        .then(response => response.json())
                        .then(sessionData => {
                            console.log('Session check response:', sessionData);
                            if (sessionData.success) {
                                console.log('Session verified! User:', sessionData.user_name);
                                
                                // Update frontend to show user is logged in
                                updateUIAfterLogin({
                                    user_id: sessionData.user_id,
                                    user_email: sessionData.user_email,
                                    user_name: sessionData.user_name
                                });
                                
                                // Close modal smoothly
                                closeBookingAuthModal();
                                
                                if (pendingBookingData) {
                                    console.log('About to call proceedToCheckout with:', pendingBookingData);
                                    setTimeout(() => {
                                        proceedToCheckout(pendingBookingData);
                                    }, 300);
                                } else {
                                    console.error('ERROR: pendingBookingData is null!');
                                    errorEl.textContent = 'Error: Booking data lost. Please try again.';
                                    errorEl.style.display = 'block';
                                }
                            } else {
                                console.error('Session verification failed:', sessionData.message);
                                errorEl.textContent = 'Session error. Please try logging in again.';
                                errorEl.style.display = 'block';
                                submitBtn.disabled = false;
                                submitBtn.textContent = '🔓 Login';
                            }
                        })
                        .catch(err => {
                            console.error('Session check error:', err);
                            errorEl.textContent = 'Connection error during session verification.';
                            errorEl.style.display = 'block';
                            submitBtn.disabled = false;
                            submitBtn.textContent = '🔓 Login';
                        });
                    }, 100);
                } else {
                    // Show error message
                    console.log('Login failed:', data.message);
                    errorEl.textContent = data.message || 'Login failed. Please try again.';
                    errorEl.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.textContent = '🔓 Login';
                }
            })
            .catch(error => {
                console.error('Login fetch error:', error);
                errorEl.textContent = 'Connection error: ' + error.message + '. Please try again.';
                errorEl.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.textContent = '🔓 Login';
            });
        });
    }
    
    // Register form submission
    const registerForm = document.getElementById('bookingRegisterForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const firstName = document.getElementById('booking_register_first_name').value;
            const lastName = document.getElementById('booking_register_last_name').value;
            const email = document.getElementById('booking_register_email').value;
            const phone = document.getElementById('booking_register_phone').value;
            const password = document.getElementById('booking_register_password').value;
            const confirmPassword = document.getElementById('booking_register_confirm_password').value;
            const agreeTerms = registerForm.querySelector('input[name="agree_terms"]').checked;
            
            const errorEl = document.getElementById('registerAuthError');
            errorEl.style.display = 'none';
            
            console.log('Register form submitted:', {firstName, lastName, email, phone});
            console.log('Pending booking data:', pendingBookingData);
            
            // Validate passwords match
            if (password !== confirmPassword) {
                errorEl.textContent = 'Passwords do not match.';
                errorEl.style.display = 'block';
                return;
            }
            
            // Validate password length
            if (password.length < 8) {
                errorEl.textContent = 'Password must be at least 8 characters long.';
                errorEl.style.display = 'block';
                return;
            }
            
            if (!agreeTerms) {
                errorEl.textContent = 'You must agree to the Terms & Conditions.';
                errorEl.style.display = 'block';
                return;
            }
            
            const formData = new FormData();
            formData.append('first_name', firstName);
            formData.append('last_name', lastName);
            formData.append('email', email);
            formData.append('phone', phone);
            formData.append('password', password);
            formData.append('confirm_password', confirmPassword);
            
            const submitBtn = registerForm.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Creating account...';
            
            console.log('Sending registration request to /register/ajax');
            
            fetch('<?php echo URLROOT; ?>/register/ajax', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Register response status:', response.status, response.statusText);
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status + ': ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                console.log('Register response data:', data);
                if (data.success) {
                    console.log('Registration successful! Verifying session...');
                    
                    // Verify session was established
                    setTimeout(() => {
                        fetch('<?php echo URLROOT; ?>/login/check_session', {
                            method: 'GET',
                            credentials: 'include'
                        })
                        .then(response => response.json())
                        .then(sessionData => {
                            console.log('Session check response:', sessionData);
                            if (sessionData.success) {
                                console.log('Session verified! User:', sessionData.user_name);
                                
                                // Update frontend to show user is logged in
                                updateUIAfterLogin({
                                    user_id: sessionData.user_id,
                                    user_email: sessionData.user_email,
                                    user_name: sessionData.user_name
                                });
                                
                                // Close modal smoothly
                                closeBookingAuthModal();
                                
                                if (pendingBookingData) {
                                    console.log('About to call proceedToCheckout with:', pendingBookingData);
                                    setTimeout(() => {
                                        proceedToCheckout(pendingBookingData);
                                    }, 300);
                                } else {
                                    console.error('ERROR: pendingBookingData is null!');
                                    errorEl.textContent = 'Error: Booking data lost. Please try again.';
                                    errorEl.style.display = 'block';
                                }
                            } else {
                                console.error('Session verification failed:', sessionData.message);
                                errorEl.textContent = 'Session error. Please try logging in again.';
                                errorEl.style.display = 'block';
                                submitBtn.disabled = false;
                                submitBtn.textContent = '📝 Create Account';
                            }
                        })
                        .catch(err => {
                            console.error('Session check error:', err);
                            errorEl.textContent = 'Connection error during session verification.';
                            errorEl.style.display = 'block';
                            submitBtn.disabled = false;
                            submitBtn.textContent = '📝 Create Account';
                        });
                    }, 100);
                } else {
                    // Show error message
                    console.log('Registration failed:', data.message);
                    errorEl.textContent = data.message || 'Registration failed. Please try again.';
                    errorEl.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.textContent = '📝 Create Account';
                }
            })
            .catch(error => {
                console.error('Register fetch error:', error);
                errorEl.textContent = 'Connection error: ' + error.message + '. Please try again.';
                errorEl.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.textContent = '📝 Create Account';
            });
        });
    }
});

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const modal = document.getElementById('bookingAuthModal');
    if (event.target == modal) {
        closeBookingAuthModal();
    }
});

// Load more reviews functionality
document.querySelector('.btn-load-more-reviews').addEventListener('click', function() {
    alert('Load more reviews functionality will be implemented');
});

// Close modal when clicking outside
window.onclick = function(event) {
    const galleryModal = document.getElementById('galleryModal');
    const messageModal = document.getElementById('messageModal');
    
    if (event.target == galleryModal) {
        closeGalleryModal();
    }
    if (event.target == messageModal) {
        closeMessageModal();
    }
}

// Keyboard navigation for gallery
document.addEventListener('keydown', function(event) {
    const galleryModal = document.getElementById('galleryModal');
    if (galleryModal.style.display === 'block') {
        if (event.key === 'ArrowLeft') {
            previousImage();
        } else if (event.key === 'ArrowRight') {
            nextImage();
        } else if (event.key === 'Escape') {
            closeGalleryModal();
        }
    }
});
</script>

<?php require APPROOT.'/views/inc/components/footer.php'; ?>