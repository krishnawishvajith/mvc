</div>

<!-- Load More Section -->

</main>
</div>
</section>

<script>
    // Initialize filters
    let activeFilters = {
        sport: [],
        location: [],
        category: '',
        price: {
            min: 0,
            max: 10000
        },
        rating: [],
        features: [],
        availability: ''
    };

    // Filter functionality
    function applyFilters() {
        const cards = document.querySelectorAll('.stadium-card1, .stadium-card');
        let visibleCount = 0;

        cards.forEach(card => {
            let show = true;

            // Sport filter
            if (activeFilters.sport.length > 0) {
                const cardSport = card.dataset.sport;
                if (!activeFilters.sport.includes(cardSport)) {
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

            // Category filter
            if (activeFilters.category && activeFilters.category !== '') {
                const cardCategory = card.dataset.category;
                if (cardCategory !== activeFilters.category) {
                    show = false;
                }
            }

            // Price filter
            const cardPrice = parseInt(card.dataset.price);
            if (cardPrice < activeFilters.price.min || cardPrice > activeFilters.price.max) {
                show = false;
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

            // Availability filter
            if (activeFilters.availability && activeFilters.availability !== '') {
                const cardStatus = card.dataset.status;
                if (activeFilters.availability === 'available' && cardStatus !== 'Available') {
                    show = false;
                } else if (activeFilters.availability === 'booked' && cardStatus !== 'Booked') {
                    show = false;
                }
            }

            // Show/hide card
            card.style.display = show ? 'block' : 'none';
            if (show) visibleCount++;
        });

        // Update results count
        document.getElementById('resultsCount').textContent = `${visibleCount} stadiums found`;

        // Update applied filters display
        updateAppliedFilters();
    }

    function updateAppliedFilters() {
        const filterTags = document.getElementById('filterTags');
        const appliedFiltersContainer = document.getElementById('appliedFilters');
        filterTags.innerHTML = '';

        let hasFilters = false;

        // Sport filters
        activeFilters.sport.forEach(sport => {
            hasFilters = true;
            filterTags.innerHTML += `<span class="filter-tag" data-type="sport" data-value="${sport}">
            ${sport.charAt(0).toUpperCase() + sport.slice(1)} <button onclick="removeFilter('sport', '${sport}')">×</button>
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

        // Category filter
        if (activeFilters.category && activeFilters.category !== '') {
            hasFilters = true;
            filterTags.innerHTML += `<span class="filter-tag" data-type="category" data-value="${activeFilters.category}">
            ${activeFilters.category.charAt(0).toUpperCase() + activeFilters.category.slice(1)} <button onclick="removeFilter('category', '${activeFilters.category}')">×</button>
        </span>`;
        }

        // Price filter (if not default)
        if (activeFilters.price.min > 0 || activeFilters.price.max < 10000) {
            hasFilters = true;
            filterTags.innerHTML += `<span class="filter-tag" data-type="price">
            LKR ${activeFilters.price.min} - LKR ${activeFilters.price.max} <button onclick="removeFilter('price', '')">×</button>
        </span>`;
        }

        // Rating filters
        activeFilters.rating.forEach(rating => {
            hasFilters = true;
            filterTags.innerHTML += `<span class="filter-tag" data-type="rating" data-value="${rating}">
            ${rating}+ Stars <button onclick="removeFilter('rating', '${rating}')">×</button>
        </span>`;
        });

        // Features filters
        activeFilters.features.forEach(feature => {
            hasFilters = true;
            const displayFeature = feature.replace('-', ' ').replace(/\b\w/g, l => l.toUpperCase());
            filterTags.innerHTML += `<span class="filter-tag" data-type="features" data-value="${feature}">
            ${displayFeature} <button onclick="removeFilter('features', '${feature}')">×</button>
        </span>`;
        });

        // Availability filter
        if (activeFilters.availability && activeFilters.availability !== '') {
            hasFilters = true;
            const displayAvailability = activeFilters.availability === 'available' ? 'Available Now' : 'Currently Booked';
            filterTags.innerHTML += `<span class="filter-tag" data-type="availability" data-value="${activeFilters.availability}">
            ${displayAvailability} <button onclick="removeFilter('availability', '${activeFilters.availability}')">×</button>
        </span>`;
        }

        appliedFiltersContainer.style.display = hasFilters ? 'block' : 'none';
    }

    function removeFilter(type, value) {
        switch (type) {
            case 'sport':
                activeFilters.sport = activeFilters.sport.filter(s => s !== value);
                document.querySelector(`input[name="sport"][value="${value}"]`).checked = false;
                break;
            case 'location':
                activeFilters.location = activeFilters.location.filter(l => l !== value);
                document.querySelector(`input[name="location"][value="${value}"]`).checked = false;
                break;
            case 'category':
                activeFilters.category = '';
                document.querySelector('input[name="category"][value=""]').checked = true;
                break;
            case 'price':
                activeFilters.price = {
                    min: 0,
                    max: 10000
                };
                document.getElementById('minPrice').value = '';
                document.getElementById('maxPrice').value = '';
                document.getElementById('priceSlider').value = 10000;
                break;
            case 'rating':
                activeFilters.rating = activeFilters.rating.filter(r => r !== value);
                document.querySelector(`input[name="rating"][value="${value}"]`).checked = false;
                break;
            case 'features':
                activeFilters.features = activeFilters.features.filter(f => f !== value);
                document.querySelector(`input[name="features"][value="${value}"]`).checked = false;
                break;
            case 'availability':
                activeFilters.availability = '';
                document.querySelector('input[name="availability"][value=""]').checked = true;
                break;
        }
        applyFilters();
    }

    function clearAllFilters() {
        // Reset all filters
        activeFilters = {
            sport: [],
            location: [],
            category: '',
            price: {
                min: 0,
                max: 10000
            },
            rating: [],
            features: [],
            availability: ''
        };

        // Reset all form inputs
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
        document.querySelectorAll('input[name="category"], input[name="availability"]').forEach(radio => {
            radio.checked = radio.value === '';
        });
        document.getElementById('minPrice').value = '';
        document.getElementById('maxPrice').value = '';
        document.getElementById('priceSlider').value = 10000;

        applyFilters();
    }

    function toggleView(view) {
        const grid = document.getElementById('stadiumsGrid');
        const gridBtn = document.querySelector('.grid-view');
        const listBtn = document.querySelector('.list-view');

        if (view === 'grid') {
            grid.className = 'stadiums-grid grid-view';
            gridBtn.classList.add('active');
            listBtn.classList.remove('active');
        } else {
            grid.className = 'stadiums-grid list-view';
            listBtn.classList.add('active');
            gridBtn.classList.remove('active');
        }
    }

    function sortStadiums() {
        const sortValue = document.getElementById('sortOptions').value;
        const grid = document.getElementById('stadiumsGrid');
        const cards = Array.from(grid.querySelectorAll('.stadium-card'));

        cards.sort((a, b) => {
            switch (sortValue) {
                case 'price-low':
                    return parseInt(a.dataset.price) - parseInt(b.dataset.price);
                case 'price-high':
                    return parseInt(b.dataset.price) - parseInt(a.dataset.price);
                case 'name':
                    return a.querySelector('.stadium-name').textContent.localeCompare(b.querySelector('.stadium-name').textContent);
                case 'rating':
                default:
                    const ratingA = parseFloat(a.dataset.rating);
                    const ratingB = parseFloat(b.dataset.rating);
                    return ratingB - ratingA;
            }
        });

        cards.forEach(card => grid.appendChild(card));
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Sport filter checkboxes
        document.querySelectorAll('input[name="sport"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    activeFilters.sport.push(this.value);
                } else {
                    activeFilters.sport = activeFilters.sport.filter(s => s !== this.value);
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

        // Category filter radio buttons
        document.querySelectorAll('input[name="category"]').forEach(radio => {
            radio.addEventListener('change', function() {
                activeFilters.category = this.value;
                applyFilters();
            });
        });

        // Rating filter checkboxes
        document.querySelectorAll('input[name="rating"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    activeFilters.rating.push(this.value);
                } else {
                    activeFilters.rating = activeFilters.rating.filter(r => r !== this.value);
                }
                applyFilters();
            });
        });

        // Features filter checkboxes
        document.querySelectorAll('input[name="features"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    activeFilters.features.push(this.value);
                } else {
                    activeFilters.features = activeFilters.features.filter(f => f !== this.value);
                }
                applyFilters();
            });
        });

        // Availability filter radio buttons
        document.querySelectorAll('input[name="availability"]').forEach(radio => {
            radio.addEventListener('change', function() {
                activeFilters.availability = this.value;
                applyFilters();
            });
        });

        // Price range inputs
        document.getElementById('minPrice').addEventListener('input', function() {
            const min = parseInt(this.value) || 0;
            activeFilters.price.min = min;
            applyFilters();
        });

        document.getElementById('maxPrice').addEventListener('input', function() {
            const max = parseInt(this.value) || 10000;
            activeFilters.price.max = max;
            applyFilters();
        });

        // Price range slider
        document.getElementById('priceSlider').addEventListener('input', function() {
            const max = parseInt(this.value);
            activeFilters.price.max = max;
            document.getElementById('maxPrice').value = max;
            applyFilters();
        });

        // Price quick filter buttons
        document.querySelectorAll('.price-quick-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const min = parseInt(this.dataset.min);
                const max = parseInt(this.dataset.max);

                activeFilters.price.min = min;
                activeFilters.price.max = max;

                document.getElementById('minPrice').value = min;
                document.getElementById('maxPrice').value = max;
                document.getElementById('priceSlider').value = max;

                // Remove active class from all buttons
                document.querySelectorAll('.price-quick-btn').forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');

                applyFilters();
            });
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

        // Sort functionality
        document.getElementById('sortOptions').addEventListener('change', sortStadiums);

        // Load more button
        document.getElementById('loadMoreBtn').addEventListener('click', function() {
            alert('Load more stadiums functionality would fetch additional results');
        });

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

        // Close sidebar on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const sidebar = document.getElementById('filterSidebar');
                const overlay = document.getElementById('sidebarOverlay');

                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        });

        // Apply filter values from URL query params when the page loads
        applyQueryParamsToFilters();
        applyFilters();
    });

    function applyQueryParamsToFilters() {
        const params = new URLSearchParams(window.location.search);
        const locationParam = params.get('location');
        const sportParam = params.get('sport');
        const priceMaxParam = params.get('price_max');

        if (sportParam) {
            const sportCheckbox = document.querySelector(`input[name="sport"][value="${sportParam}"]`);
            if (sportCheckbox) {
                sportCheckbox.checked = true;
                activeFilters.sport = [sportParam];
            }
        }

        if (locationParam) {
            const locationCheckbox = document.querySelector(`input[name="location"][value="${locationParam}"]`);
            if (locationCheckbox) {
                locationCheckbox.checked = true;
                activeFilters.location = [locationParam];
            }
        }

        if (priceMaxParam && !isNaN(priceMaxParam)) {
            const maxPrice = parseInt(priceMaxParam, 10);
            activeFilters.price.max = maxPrice;
            document.getElementById('maxPrice').value = maxPrice;
            document.getElementById('priceSlider').value = maxPrice;
        }
    }

    function showStadiumInfo(id) {
        alert(`Stadium info for ID: ${id} - Full details modal would open here`);
    }

    function contactOwner(id) {
        alert(`Contact owner functionality for stadium ID: ${id}`);
    }

    function bookStadium(id) {
        if (confirm('Proceed to booking for this stadium?')) {
            window.location.href = `<?php echo URLROOT; ?>/booking/stadium/${id}`;
        }
    }
</script><?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Hero Section -->
<section class="stadiums-hero">
    <div class="hero-container">
        <div class="hero-content">
            <h1><?php echo $data['title']; ?></h1>
            <p>Book premium stadiums and sports facilities across Sri Lanka</p>
        </div>
    </div>
</section>

<!-- Main Content Section -->
<section class="stadiums-main">
    <div class="main-container">
        <!-- Mobile Filter Toggle & Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Left Sidebar - Filters -->
        <aside class="filter-sidebar" id="filterSidebar">
            <div class="sidebar-header">
                <h3>Filter Stadiums</h3>
                <button class="clear-filters" id="clearFilters">Clear All</button>
            </div>

            <!-- Sport Type Filter -->
            <div class="filter-section">
                <div class="filter-section">
                    <h4>Search Stadiums</h4>
                    <div class="filter-search">
                        <input type="text" id="customSearchInput" class="custom-search-input" placeholder="Search by name, location, owner or feature" />
                    </div>
                </div>
                <h4>Sport Type</h4>
                <div class="filter-options">
                    <label class="filter-checkbox">
                        <input type="checkbox" value="cricket" name="sport">
                        <span class="checkmark"></span>
                        <span class="filter-text">Cricket</span>
                        <span class="filter-count">(2)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="football" name="sport">
                        <span class="checkmark"></span>
                        <span class="filter-text">Football</span>
                        <span class="filter-count">(1)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="tennis" name="sport">
                        <span class="checkmark"></span>
                        <span class="filter-text">Tennis</span>
                        <span class="filter-count">(1)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="basketball" name="sport">
                        <span class="checkmark"></span>
                        <span class="filter-text">Basketball</span>
                        <span class="filter-count">(1)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="swimming" name="sport">
                        <span class="checkmark"></span>
                        <span class="filter-text">Swimming</span>
                        <span class="filter-count">(1)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="multi-sport" name="sport">
                        <span class="checkmark"></span>
                        <span class="filter-text">Multi-Sport</span>
                        <span class="filter-count">(1)</span>
                    </label>
                </div>
            </div>

            <!-- Location Filter -->
            <div class="filter-section">
                <h4>Location</h4>
                <div class="filter-options">
                    <label class="filter-checkbox">
                        <input type="checkbox" value="colombo-01" name="location">
                        <span class="checkmark"></span>
                        <span class="filter-text">Colombo 01</span>
                        <span class="filter-count">(1)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="colombo-02" name="location">
                        <span class="checkmark"></span>
                        <span class="filter-text">Colombo 02</span>
                        <span class="filter-count">(1)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="colombo-03" name="location">
                        <span class="checkmark"></span>
                        <span class="filter-text">Colombo 03</span>
                        <span class="filter-count">(1)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="colombo-04" name="location">
                        <span class="checkmark"></span>
                        <span class="filter-text">Colombo 04</span>
                        <span class="filter-count">(1)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="colombo-05" name="location">
                        <span class="checkmark"></span>
                        <span class="filter-text">Colombo 05</span>
                        <span class="filter-count">(1)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="colombo-06" name="location">
                        <span class="checkmark"></span>
                        <span class="filter-text">Colombo 06</span>
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
                        <input type="checkbox" value="pepiliyana" name="location">
                        <span class="checkmark"></span>
                        <span class="filter-text">Pepiliyana</span>
                        <span class="filter-count">(1)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="baththaramulla" name="location">
                        <span class="checkmark"></span>
                        <span class="filter-text">Baththaramulla</span>
                        <span class="filter-count">(1)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="mount-lavinia" name="location">
                        <span class="checkmark"></span>
                        <span class="filter-text">Mount Lavinia</span>
                        <span class="filter-count">(1)</span>
                    </label>
                </div>
            </div>

            <!-- Category Filter -->
            <div class="filter-section">
                <h4>Category</h4>
                <div class="filter-options">
                    <label class="filter-radio">
                        <input type="radio" value="" name="category" checked>
                        <span class="radio-mark"></span>
                        <span class="filter-text">All</span>
                        <span class="filter-count">(6)</span>
                    </label>
                    <label class="filter-radio">
                        <input type="radio" value="indoor" name="category">
                        <span class="radio-mark"></span>
                        <span class="filter-text">Indoor</span>
                        <span class="filter-count">(2)</span>
                    </label>
                    <label class="filter-radio">
                        <input type="radio" value="outdoor" name="category">
                        <span class="radio-mark"></span>
                        <span class="filter-text">Outdoor</span>
                        <span class="filter-count">(4)</span>
                    </label>
                </div>
            </div>

            <!-- Price Range Filter -->
            <div class="filter-section">
                <h4>Price Range (per hour)</h4>
                <div class="price-range-container">
                    <div class="price-inputs">
                        <div class="price-input-group">
                            <label>Min</label>
                            <input type="number" id="minPrice" placeholder="0" min="0" max="10000" step="500">
                        </div>
                        <div class="price-input-group">
                            <label>Max</label>
                            <input type="number" id="maxPrice" placeholder="10000" min="0" max="10000" step="500">
                        </div>
                    </div>
                    <div class="price-range-slider">
                        <input type="range" id="priceSlider" min="0" max="10000" value="10000" step="500">
                    </div>
                    <div class="price-quick-filters">
                        <button class="price-quick-btn" data-min="0" data-max="3000">Under LKR 3K</button>
                        <button class="price-quick-btn" data-min="3000" data-max="5000">LKR 3K - LKR 5K</button>
                        <button class="price-quick-btn" data-min="5000" data-max="8000">LKR 5K - LKR 8K</button>
                        <button class="price-quick-btn" data-min="8000" data-max="10000">Above LKR 8K</button>
                    </div>
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
                        <span class="filter-count">(3)</span>
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

            <!-- Features Filter -->
            <div class="filter-section">
                <h4>Features</h4>
                <div class="filter-options">
                    <label class="filter-checkbox">
                        <input type="checkbox" value="parking" name="features">
                        <span class="checkmark"></span>
                        <span class="filter-text">🚗 Parking</span>
                        <span class="filter-count">(5)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="lighting" name="features">
                        <span class="checkmark"></span>
                        <span class="filter-text">💡 Lighting</span>
                        <span class="filter-count">(4)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="wifi" name="features">
                        <span class="checkmark"></span>
                        <span class="filter-text">📶 WiFi</span>
                        <span class="filter-count">(3)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="air-conditioning" name="features">
                        <span class="checkmark"></span>
                        <span class="filter-text">❄️ Air Conditioning</span>
                        <span class="filter-count">(2)</span>
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" value="equipment-rental" name="features">
                        <span class="checkmark"></span>
                        <span class="filter-text">⚽ Equipment Rental</span>
                        <span class="filter-count">(2)</span>
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
                        <input type="radio" value="available" name="availability">
                        <span class="radio-mark"></span>
                        <span class="filter-text">Available Now</span>
                        <span class="filter-count">(5)</span>
                    </label>
                    <label class="filter-radio">
                        <input type="radio" value="booked" name="availability">
                        <span class="radio-mark"></span>
                        <span class="filter-text">Currently Booked</span>
                        <span class="filter-count">(1)</span>
                    </label>
                </div>
            </div>
        </aside>

        <!-- Right Content Area -->
        <main class="content-area">
            <!-- Mobile Filter Toggle Button -->
            <button class="mobile-filter-toggle" id="mobileFilterToggle">
                🔍 Filter Stadiums
            </button>

            <div class="content-header">
                <div class="results-info">
                    <h2>Available Stadiums</h2>
                    <p class="results-count" id="resultsCount"><?php echo count($data['stadiums']); ?> stadiums found</p>
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
                            <option value="price-low">Price: Low to High</option>
                            <option value="price-high">Price: High to Low</option>
                            <option value="name">Name A-Z</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Applied Filters -->
            <div class="applied-filters" id="appliedFilters" style="display: none;">
                <h4>Applied Filters:</h4>
                <div class="filter-tags" id="filterTags"></div>
            </div>

            <!-- Stadium Cards Grid -->
            <div class="stadiums-grid grid-view" id="stadiumsGrid">
                <?php foreach ($data['stadiums'] as $stadium): ?>
                    <div class="stadium-card1"
                        data-sport="<?php echo strtolower($stadium->type); ?>"
                        data-category="<?php echo strtolower($stadium->category); ?>"
                        data-location="<?php echo strtolower(str_replace(' ', '-', $stadium->location)); ?>"
                        data-price="<?php echo $stadium->price; ?>"
                        data-rating="<?php echo $stadium->rating; ?>"
                        data-status="<?php echo $stadium->status; ?>">

                        <!-- Stadium Image -->
                        <div class="stadium-image">
                            <?php
                            // Check if image is from new upload system (starts with 'stadium_')
                            if (strpos($stadium->image, 'stadium_') === 0) {
                                $imageSrc = URLROOT . '/public/uploads/stadiums/' . $stadium->image;
                            } else {
                                $imageSrc = URLROOT . '/images/stadiums/' . $stadium->image;
                            }
                            ?>
                            <img src="<?php echo $imageSrc; ?>"
                                alt="<?php echo $stadium->name; ?>"
                                onerror="this.src='<?php echo URLROOT; ?>/images/stadiums/default-stadium.jpg'">

                            <!-- Status Badge -->
                            <div class="status-badge status-<?php echo strtolower($stadium->status); ?>">
                                <?php echo $stadium->status; ?>
                            </div>

                            <!-- Category Badge -->
                            <div class="category-badge">
                                <?php echo $stadium->category; ?>
                            </div>

                            <!-- Rating Badge -->
                            <div class="rating-badge">
                                <span class="star">⭐</span>
                                <span class="rating"><?php echo $stadium->rating; ?></span>
                            </div>
                        </div>

                        <!-- Stadium Info -->
                        <div class="stadium-info">
                            <div class="stadium-header">

                                <h3 class="stadium-name">
                                    <a href="<?php echo URLROOT; ?>/stadiums/single/<?php echo $stadium->id; ?>"
                                        style="color: black; text-decoration: none;">
                                        <?php echo $stadium->name; ?>
                                    </a>
                                </h3>
                                <div class="stadium-price">
                                    <span class="currency">LKR </span>
                                    <span class="amount"><?php echo number_format($stadium->price); ?></span>
                                    <span class="period">per hour</span>
                                </div>
                            </div>

                            <div class="stadium-location">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                                </svg>
                                <span><?php echo $stadium->location; ?></span>
                            </div>

                            <!-- Stadium Features -->
                            <div class="stadium-features">
                                <?php foreach (array_slice($stadium->features, 0, 3) as $feature): ?>
                                    <span class="feature-tag">
                                        <?php
                                        // Add icons for features
                                        $icon = '';
                                        switch (strtolower($feature)) {
                                            case 'lighting':
                                                $icon = '💡';
                                                break;
                                            case 'parking':
                                                $icon = '🚗';
                                                break;
                                            case 'wifi':
                                                $icon = '📶';
                                                break;
                                            case 'air conditioning':
                                                $icon = '❄️';
                                                break;
                                            case 'professional turf':
                                                $icon = '🌱';
                                                break;
                                            case 'clay courts':
                                                $icon = '🎾';
                                                break;
                                            case 'equipment rental':
                                                $icon = '⚽';
                                                break;
                                            case 'professional court':
                                                $icon = '🏀';
                                                break;
                                            case 'sound system':
                                                $icon = '🔊';
                                                break;
                                            case 'seating':
                                                $icon = '🪑';
                                                break;
                                            case 'olympic size':
                                                $icon = '🏊';
                                                break;
                                            case 'changing rooms':
                                                $icon = '🚪';
                                                break;
                                            case 'lifeguard':
                                                $icon = '🏊‍♂️';
                                                break;
                                            default:
                                                $icon = '✓';
                                                break;
                                        }
                                        echo $icon . ' ' . $feature;
                                        ?>
                                    </span>
                                <?php endforeach; ?>

                                <?php if ($stadium->more_features > 0): ?>
                                    <span class="more-features">+<?php echo $stadium->more_features; ?> more</span>
                                <?php endif; ?>
                            </div>

                            <!-- Stadium Owner -->
                            <div class="stadium-owner">
                                <div class="owner-avatar">
                                    <?php echo substr($stadium->owner, 0, 1); ?>
                                </div>
                                <div class="owner-info">
                                    <span class="owner-name"><?php echo $stadium->owner; ?></span>
                                    <span class="owner-status status-<?php echo strtolower($stadium->owner_status); ?>">
                                        <span class="status-dot"></span>
                                        <?php echo $stadium->owner_status; ?>
                                    </span>
                                </div>
                                <button class="info-btn" onclick="showStadiumInfo(<?php echo $stadium->id; ?>)">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Action Buttons -->
                            <div class="stadium-actions">
                                <button class="btn-contact" onclick="contactOwner(<?php echo $stadium->id; ?>)">
                                    Contact
                                </button>
                                <button class="btn-book <?php echo $stadium->status === 'Booked' ? 'btn-booked' : ''; ?>"
                                    <?php echo $stadium->status === 'Booked' ? 'disabled' : ''; ?>
                                    onclick="<?php echo $stadium->status === 'Booked' ? '' : 'bookStadium(' . $stadium->id . ')'; ?>">
                                    <?php echo $stadium->status === 'Booked' ? 'Booked' : 'Book Now'; ?>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>


            <!-- Load More Button -->
            <div class="load-more-section">
                <button class="btn-load-more" id="loadMoreBtn">
                    Load More Stadiums
                </button>
            </div>
    </div>
</section>

<script>
    // Stadium filtering functionality
    function filterStadiums() {
        const sport = document.getElementById('sportFilter').value;
        const category = document.getElementById('categoryFilter').value;
        const price = document.getElementById('priceFilter').value;
        const cards = document.querySelectorAll('.stadium-card');

        cards.forEach(card => {
            let show = true;

            // Filter by sport
            if (sport && card.dataset.sport !== sport) {
                show = false;
            }

            // Filter by category
            if (category && card.dataset.category !== category) {
                show = false;
            }

            // Filter by price range
            if (price) {
                const cardPrice = parseInt(card.dataset.price);
                const [min, max] = price.split('-').map(p => parseInt(p.replace('+', '')));

                if (price.includes('+')) {
                    if (cardPrice <= min) show = false;
                } else {
                    if (cardPrice < min || cardPrice > max) show = false;
                }
            }

            card.style.display = show ? 'block' : 'none';
        });

        updateResultsCount();
    }

    function updateResultsCount() {
        const visibleCards = document.querySelectorAll('.stadium-card[style*="block"], .stadium-card:not([style*="none"])').length;
        document.querySelector('.results-count').textContent = `${visibleCards} stadiums found`;
    }

    function sortStadiums() {
        const sortValue = document.getElementById('sortOptions').value;
        const grid = document.getElementById('stadiumsGrid');
        const cards = Array.from(grid.querySelectorAll('.stadium-card'));

        cards.sort((a, b) => {
            switch (sortValue) {
                case 'price-low':
                    return parseInt(a.dataset.price) - parseInt(b.dataset.price);
                case 'price-high':
                    return parseInt(b.dataset.price) - parseInt(a.dataset.price);
                case 'name':
                    return a.querySelector('.stadium-name').textContent.localeCompare(b.querySelector('.stadium-name').textContent);
                case 'rating':
                default:
                    const ratingA = parseFloat(a.querySelector('.rating').textContent);
                    const ratingB = parseFloat(b.querySelector('.rating').textContent);
                    return ratingB - ratingA;
            }
        });

        cards.forEach(card => grid.appendChild(card));
    }

    function showStadiumInfo(id) {
        alert(`Stadium info for ID: ${id} - Full details modal would open here`);
    }

    function contactOwner(id) {
        alert(`Contact owner functionality for stadium ID: ${id}`);
    }

    function bookStadium(id) {
        if (confirm('Proceed to booking for this stadium?')) {
            window.location.href = `<?php echo URLROOT; ?>/booking/stadium/${id}`;
        }
    }

    // Event listeners
    document.getElementById('sportFilter').addEventListener('change', filterStadiums);
    document.getElementById('categoryFilter').addEventListener('change', filterStadiums);
    document.getElementById('priceFilter').addEventListener('change', filterStadiums);
    document.getElementById('locationFilter').addEventListener('change', filterStadiums);
    document.getElementById('sortOptions').addEventListener('change', sortStadiums);

    // Search functionality
    document.querySelector('.search-input').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const cards = document.querySelectorAll('.stadium-card');

        cards.forEach(card => {
            const stadiumName = card.querySelector('.stadium-name').textContent.toLowerCase();
            const show = stadiumName.includes(searchTerm);
            card.style.display = show ? 'block' : 'none';
        });

        updateResultsCount();
    });

    // Load more functionality
    document.getElementById('loadMoreBtn').addEventListener('click', function() {
        alert('Load more stadiums functionality would fetch additional results');
    });

    /*
      Custom search behaviour:
      - matches stadium name, location, owner and visible feature tags
      - case-insensitive
      - works with both .stadium-card1 and .stadium-card selectors (covers existing variations)
      Note: this script only hides/shows cards; it is intentionally minimal and doesn't modify your existing filter logic.
    */
    (function() {
        const input = document.getElementById('customSearchInput');
        if (!input) return;

        function updateResultsCount(visible) {
            const countEl = document.getElementById('resultsCount') || document.querySelector('.results-count');
            if (countEl) countEl.textContent = `${visible} stadiums found`;
        }

        input.addEventListener('input', function() {
            const q = this.value.trim().toLowerCase();
            const cards = document.querySelectorAll('.stadium-card1, .stadium-card');
            let visible = 0;

            cards.forEach(card => {
                // Collect searchable text
                const nameEl = card.querySelector('.stadium-name');
                const name = nameEl ? nameEl.textContent.toLowerCase() : '';

                const locationEl = card.querySelector('.stadium-location');
                const location = locationEl ? locationEl.textContent.toLowerCase() : '';

                const ownerEl = card.querySelector('.owner-name');
                const owner = ownerEl ? ownerEl.textContent.toLowerCase() : '';

                // features: gather visible .feature-tag texts inside this card
                const featureEls = card.querySelectorAll('.stadium-features .feature-tag');
                const features = Array.from(featureEls).map(f => f.textContent.toLowerCase()).join(' ');

                const hay = [name, location, owner, features].join(' ');
                const match = q === '' || hay.indexOf(q) !== -1;

                /*
                  Preserve existing filter-hidden state:
                  - If the card is already hidden by other filters (computed display === 'none') and the search box is empty,
                    leave it hidden.
                  - If a search term exists, override to show matches and hide non-matches.
                */
                const wasHiddenByOtherFilters = window.getComputedStyle(card).display === 'none' && q === '';
                if (wasHiddenByOtherFilters) {
                    // keep as-is (hidden)
                    card.style.display = 'none';
                } else {
                    card.style.display = match ? 'block' : 'none';
                }

                if (window.getComputedStyle(card).display !== 'none') visible++;
            });

            updateResultsCount(visible);
        });
    })();
</script>

<style>
    /* Minimal styles — safe to include inline; adjust to match your theme if needed */
    .custom-search-input {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        box-sizing: border-box;
        font-size: 14px;
    }

    .filter-section h4 {
        margin-bottom: 8px;
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>