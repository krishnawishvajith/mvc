<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Hero Section -->
<section class="rental-hero">
    <div class="hero-container">
        <div class="hero-content">
            <h1><?php echo $data['title']; ?></h1>
            <p>Rent premium sports equipment from trusted providers across Sri Lanka</p>
        </div>
    </div>
</section>

<!-- Main Content Section -->
<section class="rental-main">
    <div class="main-container">
        <!-- Mobile Filter Toggle & Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Left Sidebar - Filters -->
        <aside class="filter-sidebar" id="filterSidebar">
            <div class="sidebar-header">
                <h3>Filter Equipment</h3>
                <button class="clear-filters" id="clearFilters">Clear All</button>
            </div>

            <!-- Equipment Category Filter -->
            <div class="filter-section">
                <h4>Equipment Type</h4>
                <div class="filter-options">
                    <label class="filter-checkbox">
                        <input type="checkbox" value="cricket" name="equipment">
                        <span class="checkmark"></span>
                        <span class="filter-text">🏏 Cricket</span>
                        <span class="filter-count">(3)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="football" name="equipment">
                        <span class="checkmark"></span>
                        <span class="filter-text">⚽ Football</span>
                        <span class="filter-count">(3)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="tennis" name="equipment">
                        <span class="checkmark"></span>
                        <span class="filter-text">🎾 Tennis</span>
                        <span class="filter-count">(3)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="basketball" name="equipment">
                        <span class="checkmark"></span>
                        <span class="filter-text">🏀 Basketball</span>
                        <span class="filter-count">(3)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="badminton" name="equipment">
                        <span class="checkmark"></span>
                        <span class="filter-text">🏸 Badminton</span>
                        <span class="filter-count">(1)</span>
                    </label>
                </div>
            </div>

            <!-- Store Category Filter -->
            <div class="filter-section">
                <h4>Store Type</h4>
                <div class="filter-options">
                    <label class="filter-radio">
                        <input type="radio" value="" name="store_type" checked>
                        <span class="radio-mark"></span>
                        <span class="filter-text">All Stores</span>
                        <span class="filter-count">(6)</span>
                    </label>
                    <label class="filter-radio">
                        <input type="radio" value="multi-sport" name="store_type">
                        <span class="radio-mark"></span>
                        <span class="filter-text">Multi-Sport</span>
                        <span class="filter-count">(2)</span>
                    </label>
                    <label class="filter-radio">
                        <input type="radio" value="specialized" name="store_type">
                        <span class="radio-mark"></span>
                        <span class="filter-text">Specialized</span>
                        <span class="filter-count">(4)</span>
                    </label>
                </div>
            </div>

            <!-- Location Filter -->
            <div class="filter-section">
                <h4>Location</h4>
                <div class="filter-options">
                    <label class="filter-checkbox">
                        <input type="checkbox" value="colombo-03" name="location">
                        <span class="checkmark"></span>
                        <span class="filter-text">Colombo 03</span>
                        <span class="filter-count">(1)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="colombo-05" name="location">
                        <span class="checkmark"></span>
                        <span class="filter-text">Colombo 05</span>
                        <span class="filter-count">(1)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="colombo-07" name="location">
                        <span class="checkmark"></span>
                        <span class="filter-text">Colombo 07</span>
                        <span class="filter-count">(1)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="dehiwala" name="location">
                        <span class="checkmark"></span>
                        <span class="filter-text">Dehiwala</span>
                        <span class="filter-count">(1)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="mount-lavinia" name="location">
                        <span class="checkmark"></span>
                        <span class="filter-text">Mount Lavinia</span>
                        <span class="filter-count">(1)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="nugegoda" name="location">
                        <span class="checkmark"></span>
                        <span class="filter-text">Nugegoda</span>
                        <span class="filter-count">(1)</span>
                    </label>
                </div>
            </div>

            <!-- Rating Filter -->
            <div class="filter-section">
                <h4>Rating</h4>
                <div class="filter-options">
                    <label class="filter-checkbox">
                        <input type="checkbox" value="4.5" name="rating">
                        <span class="checkmark"></span>
                        <span class="filter-text">4.5+ ⭐⭐⭐⭐⭐</span>
                        <span class="filter-count">(4)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="4.0" name="rating">
                        <span class="checkmark"></span>
                        <span class="filter-text">4.0+ ⭐⭐⭐⭐</span>
                        <span class="filter-count">(6)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="3.5" name="rating">
                        <span class="checkmark"></span>
                        <span class="filter-text">3.5+ ⭐⭐⭐</span>
                        <span class="filter-count">(6)</span>
                    </label>
                </div>
            </div>

            <!-- Services Filter -->
            <div class="filter-section">
                <h4>Services</h4>
                <div class="filter-options">
                    <label class="filter-checkbox">
                        <input type="checkbox" value="delivery" name="services">
                        <span class="checkmark"></span>
                        <span class="filter-text">🚚 Home Delivery</span>
                        <span class="filter-count">(5)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="online-booking" name="services">
                        <span class="checkmark"></span>
                        <span class="filter-text">💻 Online Booking</span>
                        <span class="filter-count">(4)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="expert-advice" name="services">
                        <span class="checkmark"></span>
                        <span class="filter-text">👨‍🏫 Expert Advice</span>
                        <span class="filter-count">(3)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="team-packages" name="services">
                        <span class="checkmark"></span>
                        <span class="filter-text">👥 Team Packages</span>
                        <span class="filter-count">(2)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="maintenance" name="services">
                        <span class="checkmark"></span>
                        <span class="filter-text">🔧 Equipment Maintenance</span>
                        <span class="filter-count">(2)</span>
                    </label>
                </div>
            </div>

            <!-- Experience Filter -->
            <div class="filter-section">
                <h4>Experience</h4>
                <div class="filter-options">
                    <label class="filter-radio">
                        <input type="radio" value="" name="experience" checked>
                        <span class="radio-mark"></span>
                        <span class="filter-text">Any Experience</span>
                        <span class="filter-count">(6)</span>
                    </label>
                    <label class="filter-radio">
                        <input type="radio" value="10+" name="experience">
                        <span class="radio-mark"></span>
                        <span class="filter-text">10+ Years</span>
                        <span class="filter-count">(2)</span>
                    </label>
                    <label class="filter-radio">
                        <input type="radio" value="5+" name="experience">
                        <span class="radio-mark"></span>
                        <span class="filter-text">5+ Years</span>
                        <span class="filter-count">(6)</span>
                    </label>
                </div>
            </div>

            <!-- Availability Filter -->
            <div class="filter-section">
                <h4>Availability</h4>
                <div class="filter-options">
                    <label class="filter-radio">
                        <input type="radio" value="" name="availability" checked>
                        <span class="radio-mark"></span>
                        <span class="filter-text">All</span>
                        <span class="filter-count">(6)</span>
                    </label>
                    <label class="filter-radio">
                        <input type="radio" value="open" name="availability">
                        <span class="radio-mark"></span>
                        <span class="filter-text">Open Now</span>
                        <span class="filter-count">(5)</span>
                    </label>
                    <label class="filter-radio">
                        <input type="radio" value="closed" name="availability">
                        <span class="radio-mark"></span>
                        <span class="filter-text">Closed</span>
                        <span class="filter-count">(1)</span>
                    </label>
                </div>
            </div>
        </aside>

        <!-- Right Content Area -->
        <main class="content-area">
            <!-- Mobile Filter Toggle Button -->
            <button class="mobile-filter-toggle" id="mobileFilterToggle">
                🔍 Filter Equipment Stores
            </button>

            <div class="content-header">
                <div class="results-info">
                    <h2>Equipment Rental Stores</h2>
                    <p class="results-count" id="resultsCount"><?php echo count($data['rentals']); ?> stores found</p>
                </div>

                <div class="content-controls">
                    <div class="view-toggle">
                        <button class="view-btn grid-view active" data-view="grid">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 3h8v8H3V3zm10 0h8v8h-8V3zM3 13h8v8H3v-8zm10 0h8v8h-8v-8z" />
                            </svg>
                        </button>
                        <button class="view-btn list-view" data-view="list">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z" />
                            </svg>
                        </button>
                    </div>

                    <div class="sort-options">
                        <select class="sort-select" id="sortOptions">
                            <option value="rating">Sort by Rating</option>
                            <option value="experience">Most Experienced</option>
                            <option value="name">Name A-Z</option>
                            <option value="location">Location</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Applied Filters -->
            <div class="applied-filters" id="appliedFilters" style="display: none;">
                <h4>Applied Filters:</h4>
                <div class="filter-tags" id="filterTags"></div>
            </div>

            <!-- Rental Cards Grid -->
            <div class="rentals-grid grid-view" id="rentalsGrid">
                <?php foreach ($data['rentals'] as $rental): ?>
                    <div class="rental-card"
                        data-equipment="<?php echo implode(',', array_map('strtolower', $rental->equipment_types)); ?>"
                        data-category="<?php echo strtolower($rental->category); ?>"
                        data-location="<?php echo strtolower(str_replace(' ', '-', $rental->location)); ?>"
                        data-rating="<?php echo $rental->rating; ?>"
                        data-status="<?php echo strtolower($rental->status); ?>"
                        data-delivery="<?php echo $rental->delivery ? 'true' : 'false'; ?>"
                        data-experience="<?php echo str_replace(' years', '', strtolower($rental->experience)); ?>">

                        <!-- Store Image -->
                        <div class="rental-image">
                            <?php 
                            // Determine correct image path
                            $imageUrl = '';
                            if (!empty($rental->display_image)) {
                                // Check if it's a new uploaded image (starts with uploads/)
                                if (strpos($rental->display_image, 'uploads/') === 0) {
                                    $imageUrl = URLROOT . '/' . $rental->display_image;
                                } else {
                                    // Old image format
                                    $imageUrl = URLROOT . '/images/rental/' . $rental->display_image;
                                }
                            } else {
                                $imageUrl = URLROOT . '/images/rental/default-store.jpg';
                            }
                            ?>
                            <img src="<?php echo $imageUrl; ?>"
                                alt="<?php echo $rental->store_name; ?>"
                                onerror="this.src='https://via.placeholder.com/400x300?text=<?php echo urlencode($rental->store_name); ?>'">

                            <!-- Status Badge -->
                            <div class="status-badge status-<?php echo strtolower($rental->status); ?>">
                                <?php echo $rental->status; ?>
                            </div>

                            <!-- Category Badge -->
                            <div class="category-badge">
                                <?php echo $rental->category; ?>
                            </div>

                            <!-- Rating Badge -->
                            <div class="rating-badge">
                                <span class="star">⭐</span>
                                <span class="rating"><?php echo $rental->rating; ?></span>
                            </div>

                            <!-- Experience Badge -->
                            <div class="experience-badge">
                                <?php echo $rental->experience; ?>
                            </div>
                        </div>

                        <!-- Store Info -->
                        <div class="rental-info">
                            <div class="rental-header">
                                <h3 class="store-name"><?php echo $rental->store_name; ?></h3>
                                <?php if ($rental->delivery): ?>
                                    <div class="delivery-badge">
                                        🚚 Delivery
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="store-location">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                                </svg>
                                <span><?php echo $rental->location; ?></span>
                            </div>

                            <!-- Equipment Types -->
                            <div class="equipment-types">
                                <?php foreach (array_slice($rental->equipment_types, 0, 3) as $equipment): ?>
                                    <span class="equipment-tag">
                                        <?php
                                        // Add icons for equipment
                                        $icon = '';
                                        switch (strtolower($equipment)) {
                                            case 'cricket':
                                                $icon = '🏏';
                                                break;
                                            case 'football':
                                                $icon = '⚽';
                                                break;
                                            case 'tennis':
                                                $icon = '🎾';
                                                break;
                                            case 'basketball':
                                                $icon = '🏀';
                                                break;
                                            case 'badminton':
                                                $icon = '🏸';
                                                break;
                                            default:
                                                $icon = '🏆';
                                                break;
                                        }
                                        echo $icon . ' ' . $equipment;
                                        ?>
                                    </span>
                                <?php endforeach; ?>

                                <?php if (count($rental->equipment_types) > 3): ?>
                                    <span class="more-equipment">+<?php echo count($rental->equipment_types) - 3; ?> more</span>
                                <?php endif; ?>
                            </div>

                            <!-- Features -->
                            <div class="rental-features">
                                <?php foreach (array_slice($rental->features, 0, 2) as $feature): ?>
                                    <span class="feature-tag">
                                        <?php
                                        $icon = '';
                                        switch (strtolower(str_replace(' ', '-', $feature))) {
                                            case 'home-delivery':
                                                $icon = '🚚';
                                                break;
                                            case 'quality-guarantee':
                                                $icon = '✅';
                                                break;
                                            case 'online-booking':
                                                $icon = '💻';
                                                break;
                                            case 'expert-advice':
                                                $icon = '👨‍🏫';
                                                break;
                                            case 'equipment-maintenance':
                                                $icon = '🔧';
                                                break;
                                            case 'bulk-discounts':
                                                $icon = '💰';
                                                break;
                                            case 'team-packages':
                                                $icon = '👥';
                                                break;
                                            case 'size-fitting':
                                                $icon = '📏';
                                                break;
                                            default:
                                                $icon = '✓';
                                                break;
                                        }
                                        echo $icon . ' ' . $feature;
                                        ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>

                            <!-- Store Hours -->
                            <div class="store-hours">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M16.2,16.2L11,13V7H12.5V12.2L17,14.7L16.2,16.2Z" />
                                </svg>
                                <span><?php echo $rental->hours; ?></span>
                            </div>

                            <!-- Store Owner -->
                            <div class="store-owner">
                                <div class="owner-avatar">
                                    <?php echo substr($rental->owner, 0, 1); ?>
                                </div>
                                <div class="owner-info">
                                    <span class="owner-name" onclick="openMessageModal(<?php echo $rental->id; ?>, <?php echo $rental->owner_id ?? 0; ?>)" style="cursor:pointer; text-decoration:underline;">
                                        <?php echo $rental->owner; ?>
                                    </span>
                                    <span class="owner-status status-<?php echo strtolower($rental->owner_status); ?>">
                                        <span class="status-dot"></span>
                                        <?php echo $rental->owner_status; ?>
                                    </span>
                                </div>
                                <button class="info-btn" onclick="showStoreInfo(<?php echo $rental->id; ?>)">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Contact Buttons -->
                            <div class="contact-actions">
                                <button class="btn-message" onclick="openMessageModal(<?php echo $rental->id; ?>, <?php echo $rental->owner_id ?? 0; ?>)">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6l-4 4V6a2 2 0 0 1 2-2z" />
                                    </svg>
                                    Message Owner
                                </button>
                                <button class="btn-phone" onclick="callStore('<?php echo $rental->phone; ?>')">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z" />
                                    </svg>
                                    Call
                                </button>
                                <button class="btn-email" onclick="emailStore('<?php echo $rental->email; ?>')">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M20,8L12,13L4,8V6L12,11L20,6M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z" />
                                    </svg>
                                    Email
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Load More Section -->
            <div class="load-more-section">
                <button class="btn-load-more" id="loadMoreBtn">
                    Load More Stores
                </button>
            </div>
        </main>
    </div>
</section>

<div class="message-modal" id="messageModal" style="display:none;">
    <div class="message-modal-backdrop" onclick="closeMessageModal()"></div>
    <div class="message-modal-panel">
        <div class="message-modal-header">
            <h3>Contact Rental Owner</h3>
            <button class="close-modal" onclick="closeMessageModal()">×</button>
        </div>
        <div class="message-modal-body">
            <div id="messageModalContent">
                <p>Send a message to the owner to ask about availability and equipment details.</p>
                <form id="messageOwnerForm" onsubmit="event.preventDefault(); sendMessageToOwner();">
                    <input type="hidden" id="messageRentalId" name="rental_id" value="">
                    <input type="hidden" id="messageReceiverId" name="receiver_id" value="">
                    <div class="form-group">
                        <label for="messageSubject">Subject</label>
                        <input type="text" id="messageSubject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="messageBody">Message</label>
                        <textarea id="messageBody" name="message" rows="5" required></textarea>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn-secondary" onclick="closeMessageModal()">Cancel</button>
                        <button type="submit" class="btn-primary">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize filters for rental services
    let activeFilters = {
        equipment: [],
        location: [],
        store_type: '',
        rating: [],
        services: [],
        experience: '',
        availability: ''
    };

    // Filter functionality
    function applyFilters() {
        const cards = document.querySelectorAll('.rental-card');
        let visibleCount = 0;

        cards.forEach(card => {
            let show = true;

            // Equipment filter
            if (activeFilters.equipment.length > 0) {
                const cardEquipment = card.dataset.equipment.split(',');
                const hasMatch = activeFilters.equipment.some(filter =>
                    cardEquipment.some(equipment => equipment.includes(filter))
                );
                if (!hasMatch) {
                    show = false;
                }
            }

            // Location filter
            if (activeFilters.location.length > 0) {
                const cardLocation = card.dataset.location;
                if (!activeFilters.location.includes(cardLocation)) {
                    show = false;
                }
            }

            // Store type filter
            if (activeFilters.store_type && activeFilters.store_type !== '') {
                const cardCategory = card.dataset.category;
                if (activeFilters.store_type === 'specialized' && cardCategory === 'multi-sport') {
                    show = false;
                } else if (activeFilters.store_type === 'multi-sport' && cardCategory !== 'multi-sport') {
                    show = false;
                }
            }

            // Rating filter
            if (activeFilters.rating.length > 0) {
                const cardRating = parseFloat(card.dataset.rating);
                let matchRating = false;
                activeFilters.rating.forEach(minRating => {
                    if (cardRating >= parseFloat(minRating)) {
                        matchRating = true;
                    }
                });
                if (!matchRating) show = false;
            }

            // Services filter (delivery, etc.)
            if (activeFilters.services.length > 0) {
                activeFilters.services.forEach(service => {
                    if (service === 'delivery' && card.dataset.delivery !== 'true') {
                        show = false;
                    }
                });
            }

            // Experience filter
            if (activeFilters.experience && activeFilters.experience !== '') {
                const cardExperience = card.dataset.experience;
                const requiredYears = parseInt(activeFilters.experience.replace('+', ''));
                const cardYears = parseInt(cardExperience.replace('+', ''));

                if (cardYears < requiredYears) {
                    show = false;
                }
            }

            // Availability filter
            if (activeFilters.availability && activeFilters.availability !== '') {
                const cardStatus = card.dataset.status;
                if (activeFilters.availability === 'open' && cardStatus !== 'open') {
                    show = false;
                } else if (activeFilters.availability === 'closed' && cardStatus !== 'closed') {
                    show = false;
                }
            }

            // Show/hide card
            card.style.display = show ? 'block' : 'none';
            if (show) visibleCount++;
        });

        // Update results count
        document.getElementById('resultsCount').textContent = `${visibleCount} stores found`;

        // Update applied filters display
        updateAppliedFilters();
    }

    function updateAppliedFilters() {
        const filterTags = document.getElementById('filterTags');
        const appliedFiltersContainer = document.getElementById('appliedFilters');
        filterTags.innerHTML = '';

        let hasFilters = false;

        // Equipment filters
        activeFilters.equipment.forEach(equipment => {
            hasFilters = true;
            filterTags.innerHTML += `<span class="filter-tag" data-type="equipment" data-value="${equipment}">
            ${equipment.charAt(0).toUpperCase() + equipment.slice(1)} <button onclick="removeFilter('equipment', '${equipment}')">×</button>
        </span>`;
        });

        // Location filters
        activeFilters.location.forEach(location => {
            hasFilters = true;
            const displayLocation = location.replace('-', ' ').replace(/\b\w/g, l => l.toUpperCase());
            filterTags.innerHTML += `<span class="filter-tag" data-type="location" data-value="${location}">
            ${displayLocation} <button onclick="removeFilter('location', '${location}')">×</button>
        </span>`;
        });

        // Store type filter
        if (activeFilters.store_type && activeFilters.store_type !== '') {
            hasFilters = true;
            const displayType = activeFilters.store_type.replace('-', ' ').replace(/\b\w/g, l => l.toUpperCase());
            filterTags.innerHTML += `<span class="filter-tag" data-type="store_type" data-value="${activeFilters.store_type}">
            ${displayType} <button onclick="removeFilter('store_type', '${activeFilters.store_type}')">×</button>
        </span>`;
        }

        // Other filters...
        appliedFiltersContainer.style.display = hasFilters ? 'block' : 'none';
    }

    function removeFilter(type, value) {
        switch (type) {
            case 'equipment':
                activeFilters.equipment = activeFilters.equipment.filter(e => e !== value);
                document.querySelector(`input[name="equipment"][value="${value}"]`).checked = false;
                break;
            case 'location':
                activeFilters.location = activeFilters.location.filter(l => l !== value);
                document.querySelector(`input[name="location"][value="${value}"]`).checked = false;
                break;
            case 'store_type':
                activeFilters.store_type = '';
                document.querySelector('input[name="store_type"][value=""]').checked = true;
                break;
                // Add other cases...
        }
        applyFilters();
    }

    function clearAllFilters() {
        // Reset all filters
        activeFilters = {
            equipment: [],
            location: [],
            store_type: '',
            rating: [],
            services: [],
            experience: '',
            availability: ''
        };

        // Reset all form inputs
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
        document.querySelectorAll('input[name="store_type"], input[name="experience"], input[name="availability"]').forEach(radio => {
            radio.checked = radio.value === '';
        });

        applyFilters();
    }

    // Contact functions
    function callStore(phone) {
        window.location.href = `tel:${phone}`;
    }

    function whatsappStore(phone) {
        const cleanPhone = phone.replace(/[^0-9]/g, '');
        window.open(`https://wa.me/${cleanPhone}`, '_blank');
    }

    function emailStore(email) {
        window.location.href = `mailto:${email}`;
    }

    function showStoreInfo(id) {
        alert(`Store info for ID: ${id} - Full details modal would open here`);
    }

    function openMessageModal(rentalId, receiverId) {
        const modal = document.getElementById('messageModal');
        document.getElementById('messageRentalId').value = rentalId;
        document.getElementById('messageReceiverId').value = receiverId;
        modal.style.display = 'block';
    }

    function closeMessageModal() {
        const modal = document.getElementById('messageModal');
        modal.style.display = 'none';
        document.getElementById('messageOwnerForm').reset();
    }

    function sendMessageToOwner() {
        const isLoggedIn = <?php echo Auth::isLoggedIn() ? 'true' : 'false'; ?>;
        if (!isLoggedIn) {
            window.location.href = '<?php echo URLROOT; ?>/login';
            return;
        }

        const rentalId = document.getElementById('messageRentalId').value;
        const receiverId = document.getElementById('messageReceiverId').value;
        const subject = document.getElementById('messageSubject').value.trim();
        const message = document.getElementById('messageBody').value.trim();

        if (!subject || !message) {
            alert('Please enter both a subject and a message.');
            return;
        }

        const payload = new FormData();
        payload.append('stadium_id', rentalId);
        payload.append('receiver_id', receiverId);
        payload.append('subject', subject);
        payload.append('message', message);

        fetch('<?php echo URLROOT; ?>/messages/send', {
            method: 'POST',
            body: payload,
            credentials: 'same-origin'
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Your message has been sent to the owner.');
                    closeMessageModal();
                } else {
                    alert(data.message || 'Unable to send the message. Please try again.');
                }
            })
            .catch(() => {
                alert('There was an error sending your message. Please try again later.');
            });
    }

    function toggleView(view) {
        const grid = document.getElementById('rentalsGrid');
        const gridBtn = document.querySelector('.grid-view');
        const listBtn = document.querySelector('.list-view');

        if (view === 'grid') {
            grid.className = 'rentals-grid grid-view';
            gridBtn.classList.add('active');
            listBtn.classList.remove('active');
        } else {
            grid.className = 'rentals-grid list-view';
            listBtn.classList.add('active');
            gridBtn.classList.remove('active');
        }
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Equipment filter checkboxes
        document.querySelectorAll('input[name="equipment"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    activeFilters.equipment.push(this.value);
                } else {
                    activeFilters.equipment = activeFilters.equipment.filter(e => e !== this.value);
                }
                applyFilters();
            });
        });

        // Location filter checkboxes
        document.querySelectorAll('input[name="location"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    activeFilters.location.push(this.value);
                } else {
                    activeFilters.location = activeFilters.location.filter(l => l !== this.value);
                }
                applyFilters();
            });
        });

        // Store type filter radio buttons
        document.querySelectorAll('input[name="store_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                activeFilters.store_type = this.value;
                applyFilters();
            });
        });

        // Other event listeners...

        // Mobile filter toggle
        document.getElementById('mobileFilterToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('filterSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.add('active');
            overlay.classList.add('active');
        });

        // Close sidebar when clicking overlay
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            const sidebar = document.getElementById('filterSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });

        // Clear filters button
        document.getElementById('clearFilters').addEventListener('click', clearAllFilters);

        // View toggle buttons
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const view = this.dataset.view;
                toggleView(view);
            });
        });

        // Initialize with all stores visible
        applyFilters();
    });
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>