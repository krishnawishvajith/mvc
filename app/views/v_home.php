<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Hero Section -->
<section class="herohome">
    <div class="hero-container1">
        <div class="hero-content1">
            <div class="hero-text1">
                <h1 class="hero-title1">
                    <?php echo htmlspecialchars($data['hero']['hero_title_prefix'] ?? 'BOOK'); ?>
                    <span class="highlight"><?php echo htmlspecialchars($data['hero']['hero_title_highlight'] ?? 'YOUR'); ?></span><br>
                    <?php echo htmlspecialchars($data['hero']['hero_title_suffix'] ?? 'SPORT GROUND'); ?>
                </h1>
                <p class="hero-description1">
                    <?php echo htmlspecialchars($data['hero']['hero_description'] ?? 'Your All-in-One Solution for Finding and Booking Indoor & Outdoor Stadiums, Rent Sport Equipments, Attend Practise Sessions, Book Individual Coaching Sessions & Publish Your Advertisements'); ?>
                </p>
                <div class="hero-buttons1">
                    <a href="http://localhost/bookmygroundlk/stadiums" class="btn btn-primary">BOOK STADIUM</a>
                    <a href="http://localhost/bookmygroundlk/rental" class="btn btn-secondary">RENT SPORT GEARS</a>
                </div>
            </div>

            <div class="search-section1">
                <h3 class="search-title1">Search and Book Stadiums That Fit Your Needs and Price</h3>
                <form action="<?php echo URLROOT; ?>/stadiums" method="get" class="search-form1" id="heroSearchForm">
                    <div class="search-field1">
                        <div class="field-icon1">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                        </div>
                        <select name="location" class="search-input1">
                            <option value="">Location</option>
                            <option value="colombo-01">Colombo 01</option>
                            <option value="colombo-02">Colombo 02</option>
                            <option value="colombo-03">Colombo 03</option>
                            <option value="colombo-04">Colombo 04</option>
                            <option value="colombo-05">Colombo 05</option>
                            <option value="colombo-06">Colombo 06</option>
                            <option value="colombo-07">Colombo 07</option>
                            <option value="dehiwala">Dehiwala</option>
                            <option value="pepiliyana">Pepiliyana</option>
                            <option value="baththaramulla">Baththaramulla</option>
                            <option value="mount-lavinia">Mount Lavinia</option>
                        </select>
                    </div>

                    <div class="search-field1">
                        <div class="field-icon1">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M8 12l2 2 4-4"></path>
                            </svg>
                        </div>
                        <select name="sport" class="search-select1">
                            <option value="">Sport Type</option>
                            <option value="basketball">Basketball</option>
                            <option value="football">Football</option>
                            <option value="tennis">Tennis</option>
                            <option value="cricket">Cricket</option>
                        </select>
                    </div>

                    <div class="search-field price-field1">
                        <div class="field-icon1">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <div class="price-content1">
                            <span class="price-label1">Price Average</span>
                            <div class="price-range1">
                                <input type="range" name="price_max" min="500" max="5000" value="2000" class="price-slider1" id="heroPriceSlider">
                                <div class="price-values1">
                                    <span>LKR 500</span>
                                    <span id="heroPriceMaxLabel">LKR 2000</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-find1">Find Now</button>
                </form>
            </div>

            <div class="partners-section1">
                <h4 class="partners-title1">Our Partners</h4>
                <div class="partners-logos1">
                    <div class="partner-logo1">logoipsum</div>
                    <div class="partner-logo1">logoipsum</div>
                    <div class="partner-logo1">logoipsum</div>
                    <div class="partner-logo1">logoipsum</div>
                </div>
            </div>
        </div>

        <div class="hero-image1">
            <img src="<?php echo URLROOT; ?>/images/home/basketball-player.jpg" alt="Basketball Player" class="player-image1">
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const heroPriceSlider = document.getElementById('heroPriceSlider');
        const heroPriceMaxLabel = document.getElementById('heroPriceMaxLabel');

        if (heroPriceSlider && heroPriceMaxLabel) {
            // Initialize label with current slider value
            heroPriceMaxLabel.textContent = 'LKR ' + heroPriceSlider.value;

            heroPriceSlider.addEventListener('input', function() {
                heroPriceMaxLabel.textContent = 'LKR ' + this.value;
            });
        }
    });
</script>

<!-- Featured Stadiums Section -->
<section class="featured-stadiums-section">
    <div class="featured-container">
        <div class="section-header">
            <div class="section-title">
                <h2>FEATURED STADIUMS</h2>
                <p>Discover the most popular and highly rated stadiums hand picked for your next game or event.</p>
            </div>
            <a href="<?php echo URLROOT; ?>/stadiums" class="view-all-btn">
                VIEW ALL STADIUMS
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M13.025 1l-2.847 2.828 6.176 6.176h-16.354v3.992h16.354l-6.176 6.176 2.847 2.828 10.975-11z" />
                </svg>
            </a>
        </div>

        <!-- Featured Stadiums Grid -->
        <div class="featured-stadiums-grid">
            <?php foreach ($data['featured_stadiums'] as $stadium): ?>
                <div class="featured-stadium-card1">
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
                                <span class="currency">LKR</span>
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
                                        case 'equipment rental':
                                            $icon = '⚽';
                                            break;
                                        case 'professional court':
                                            $icon = '🏀';
                                            break;
                                        case 'professional courts':
                                            $icon = '🎾';
                                            break;
                                        case 'multiple sports':
                                            $icon = '🏆';
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

        <!-- Call-to-Action -->
        <div class="featured-cta">
            <div class="cta-content">
                <h3>Looking for More Options?</h3>
                <p>Explore our complete collection of stadiums across Sri Lanka</p>
                <a href="<?php echo URLROOT; ?>/stadiums" class="cta-button">
                    Browse All Stadiums
                    <span class="cta-count">(150+ venues available)</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Services We Are Offering Section -->
<section class="services-section">
    <div class="services-container">
        <div class="services-header">
            <h2>SERVICES WE ARE OFFERING</h2>
            <p>Our basketball club is more than just a team; it's a community built on dedication, teamwork, and the love of the game.</p>
        </div>

        <div class="services-grid">
            <!-- Sports Stadium Listing Service -->
            <div class="service-card large-card top-left">
                <div class="service-image">
                    <img src="<?php echo URLROOT; ?>/images/services/stadium-listing.jpg" alt="Sports Stadium Listing Service">
                </div>
                <div class="service-content">
                    <h3>Sports Stadium Listing Service</h3>
                    <p>Sports Stadium Owners Can List their Stadiums In This Website And Get More Bookings</p>
                    <a href="<?php echo URLROOT; ?>/stadiums" class="service-btn">
                        List Stadium Now
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M13.025 1l-2.847 2.828 6.176 6.176h-16.354v3.992h16.354l-6.176 6.176 2.847 2.828 10.975-11z" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Book Your Sport Ground -->
            <div class="service-card green-card top-center">
                <div class="service-content">
                    <h3>Book Your Sport Ground</h3>
                    <p>Customers can List their Sports ground By Choosing Date & Time</p>
                    <a href="<?php echo URLROOT; ?>/stadiums" class="service-btn">
                        Book Stadium Now
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M13.025 1l-2.847 2.828 6.176 6.176h-16.354v3.992h16.354l-6.176 6.176 2.847 2.828 10.975-11z" />
                        </svg>
                    </a>
                </div>
                <div class="service-decoration">
                    <div class="sport-icon">🏀</div>
                </div>
            </div>

            <!-- Rent Out Your Sports Gear -->
            <div class="service-card large-card top-right">
                <div class="service-image">
                    <img src="<?php echo URLROOT; ?>/images/services/sports-gear.jpg" alt="Rent Out Your Sports Gear">
                </div>
                <div class="service-content">
                    <h3>Rent Out Your Sports Gear</h3>
                    <p>Sport Equipments Rental Service Owners can Publish Their Listing In the website and list their Sports Gears.</p>
                    <a href="<?php echo URLROOT; ?>/rental" class="service-btn">
                        List Your Sport Gear Rental Service Listing
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M13.025 1l-2.847 2.828 6.176 6.176h-16.354v3.992h16.354l-6.176 6.176 2.847 2.828 10.975-11z" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Rent the Gear You Need to Play -->
            <div class="service-card green-card bottom-left">
                <div class="service-content">
                    <h3>Rent the Gear You Need to Play</h3>
                    <p>Sport Players Can Rent Sport Gears For Play, They will suggest near-by Sport Gear Rental Service Listings After they Successfully Book Sport Stadium.</p>
                    <a href="<?php echo URLROOT; ?>/rental" class="service-btn">
                        Rent Sport Gear
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M13.025 1l-2.847 2.828 6.176 6.176h-16.354v3.992h16.354l-6.176 6.176 2.847 2.828 10.975-11z" />
                        </svg>
                    </a>
                </div>
                <div class="service-decoration">
                    <div class="sport-icon">🎾</div>
                </div>
            </div>

            <!-- Participate For Practice Sessions -->
            <div class="service-card center-card">
                <div class="service-image">
                    <img src="<?php echo URLROOT; ?>/images/services/practice-sessions.jpg" alt="Participate For Practice Sessions">
                </div>
                <div class="service-content">
                    <h3>Participate For Practice Sessions</h3>
                    <p>Sport Coaches can List their Practising Events And Players can Participate to the sport sessions by filling form.</p>
                    <a href="<?php echo URLROOT; ?>/coaches" class="service-btn1">
                        Publish Your Practice Sessions
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M13.025 1l-2.847 2.828 6.176 6.176h-16.354v3.992h16.354l-6.176 6.176 2.847 2.828 10.975-11z" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Publish Your Advertisement -->
            <div class="service-card green-card bottom-right">
                <div class="service-content">
                    <h3>Publish Your Advertisement</h3>
                    <p>if Someone need to publish advertisement To get more sales or people engagement, They can Publish Their Advertisement.</p>
                    <a href="<?php echo URLROOT; ?>/advertisement" class="service-btn">
                        Publish Your Advertisement Now
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M13.025 1l-2.847 2.828 6.176 6.176h-16.354v3.992h16.354l-6.176 6.176 2.847 2.828 10.975-11z" />
                        </svg>
                    </a>
                </div>
                <div class="service-decoration">
                    <div class="sport-icon">📢</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Choose Your Play Style Section -->
<section class="play-style-section">
    <div class="play-style-container">
        <div class="play-style-header">
            <h2>CHOOSE <span class="highlight-green">YOUR</span> PLAY STYLE?</h2>
            <p>Our basketball club is more than just a team; it's a community built on dedication, teamwork, and the love of the game.</p>
        </div>

        <div class="play-style-grid">
            <!-- Single Play Style -->
            <div class="play-style-card">
                <div class="play-style-image">
                    <img src="<?php echo URLROOT; ?>/images/play-styles/single-player.jpg" alt="Single Player">
                    <div class="play-style-overlay">
                        <h3 class="play-style-title">Single</h3>
                        <div class="play-style-content">
                            <p>Perfect for individual training, skill development, and personal practice sessions. Book courts for solo workouts and improve your game at your own pace.</p>
                            <a href="<?php echo URLROOT; ?>/stadiums?style=single" class="play-style-btn">
                                Find Single Courts
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M13.025 1l-2.847 2.828 6.176 6.176h-16.354v3.992h16.354l-6.176 6.176 2.847 2.828 10.975-11z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Double Play Style -->
            <div class="play-style-card">
                <div class="play-style-image">
                    <img src="<?php echo URLROOT; ?>/images/play-styles/double-players.jpg" alt="Double Players">
                    <div class="play-style-overlay">
                        <h3 class="play-style-title">Double</h3>
                        <div class="play-style-content">
                            <p>Ideal for playing with a partner, doubles matches, or small group sessions. Book courts perfect for two-player games and competitive matches.</p>
                            <a href="<?php echo URLROOT; ?>/stadiums?style=double" class="play-style-btn">
                                Find Double Courts
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M13.025 1l-2.847 2.828 6.176 6.176h-16.354v3.992h16.354l-6.176 6.176 2.847 2.828 10.975-11z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Play Style -->
            <div class="play-style-card">
                <div class="play-style-image">
                    <img src="<?php echo URLROOT; ?>/images/play-styles/team-players.jpg" alt="Team Players">
                    <div class="play-style-overlay">
                        <h3 class="play-style-title">Team</h3>
                        <div class="play-style-content">
                            <p>Perfect for team sports, group training, and large gatherings. Book spacious venues that can accommodate full teams and organized tournaments.</p>
                            <a href="<?php echo URLROOT; ?>/stadiums?style=team" class="play-style-btn">
                                Find Team Venues
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M13.025 1l-2.847 2.828 6.176 6.176h-16.354v3.992h16.354l-6.176 6.176 2.847 2.828 10.975-11z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="play-style-info">
            <div class="info-cards">
                <div class="info-card">
                    <div class="info-icon">🏆</div>
                    <h4>Competition Ready</h4>
                    <p>All venues are equipped for competitive play</p>
                </div>
                <div class="info-card">
                    <div class="info-icon">⚡</div>
                    <h4>Instant Booking</h4>
                    <p>Book your preferred time slot immediately</p>
                </div>
                <div class="info-card">
                    <div class="info-icon">🎯</div>
                    <h4>Perfect Match</h4>
                    <p>Find venues that match your play style</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Choose Your Daily Practice Session Section -->
<section class="practice-session-section">
    <div class="practice-session-container">
        <div class="practice-session-header">
            <h2>Choose Your Daily Practice Session</h2>
            <p>Our basketball club is more than just a team; it's a community built on dedication, teamwork, and the love of the game.</p>
        </div>

        <div class="practice-sessions-grid">
            <!-- Football Practice Session -->
            <div class="practice-session-card left-aligned">
                <div class="session-image">
                    <img src="<?php echo URLROOT; ?>/images/practice-sessions/football-session.jpg" alt="Football Practice Session">
                </div>
                <div class="session-content">
                    <h3>Football Practice Session</h3>
                    <div class="session-details">
                        <div class="session-detail">
                            <span class="detail-label">Venue:</span>
                            <span class="detail-value">University Of Colombo Grounds</span>
                        </div>
                        <div class="session-detail">
                            <span class="detail-label">Date & Time:</span>
                            <span class="detail-value">15th Spetember - 5:00 PM Onwards</span>
                        </div>
                        <div class="session-detail">
                            <span class="detail-label">Coach:</span>
                            <span class="detail-value">Mr. Ar. Rahuman</span>
                        </div>
                    </div>
                    <a href="javascript:void(0)" onclick="openParticipationForm('Football Practice Session', 'University Of Colombo Grounds', '15th September - 5:00 PM Onwards', 'Mr. Ar. Rahuman')" class="session-btn">
                        Fill The Form & Participate Now
                    </a>
                </div>
            </div>

            <!-- Rugby Practice Session -->
            <div class="practice-session-card right-aligned">
                <div class="session-content">
                    <h3>Rugby Practice Session</h3>
                    <div class="session-details">
                        <div class="session-detail">
                            <span class="detail-label">Venue:</span>
                            <span class="detail-value">Dehiwala Indoor Stadium</span>
                        </div>
                        <div class="session-detail">
                            <span class="detail-label">Time:</span>
                            <span class="detail-value">17th Spetember - 5:00 AM Onwards</span>
                        </div>
                        <div class="session-detail">
                            <span class="detail-label">Coach:</span>
                            <span class="detail-value">Mr. Ar. Virath Kholi</span>
                        </div>
                    </div>
                    <a href="javascript:void(0)" onclick="openParticipationForm('Football Practice Session', 'University Of Colombo Grounds', '15th September - 5:00 PM Onwards', 'Mr. Ar. Rahuman')" class="session-btn">
                        Fill The Form & Participate Now
                    </a>
                </div>
                <div class="session-image">
                    <img src="<?php echo URLROOT; ?>/images/practice-sessions/rugby-session.jpg" alt="Rugby Practice Session">
                </div>
            </div>

            <!-- Cricket Practice Session -->
            <div class="practice-session-card left-aligned">
                <div class="session-image">
                    <img src="<?php echo URLROOT; ?>/images/practice-sessions/cricket-session.jpg" alt="Cricket Practice Session">
                </div>
                <div class="session-content">
                    <h3>Cricket Practice Session</h3>
                    <div class="session-details">
                        <div class="session-detail">
                            <span class="detail-label">Venue:</span>
                            <span class="detail-value">University Of Colombo Ground</span>
                        </div>
                        <div class="session-detail">
                            <span class="detail-label">Time:</span>
                            <span class="detail-value">20th Spetember - 6:00 PM Onwards</span>
                        </div>
                        <div class="session-detail">
                            <span class="detail-label">Coach:</span>
                            <span class="detail-value">Mr. Ar. Kumar Sangakkara</span>
                        </div>
                    </div>
                    <a href="javascript:void(0)" onclick="openParticipationForm('Football Practice Session', 'University Of Colombo Grounds', '15th September - 5:00 PM Onwards', 'Mr. Ar. Rahuman')" class="session-btn">
                        Fill The Form & Participate Now
                    </a>
                </div>
            </div>

            <!-- Futsal Practice Session -->
            <div class="practice-session-card right-aligned">
                <div class="session-content">
                    <h3>Futsal Practice Session</h3>
                    <div class="session-details">
                        <div class="session-detail">
                            <span class="detail-label">Venue:</span>
                            <span class="detail-value">Arcade Indoor Stadium</span>
                        </div>
                        <div class="session-detail">
                            <span class="detail-label">Time:</span>
                            <span class="detail-value">19th Spetember - 8:00 AM Onwards</span>
                        </div>
                        <div class="session-detail">
                            <span class="detail-label">Coach:</span>
                            <span class="detail-value">Mr. Ar. Kamal Perera</span>
                        </div>
                    </div>
                    <a href="javascript:void(0)" onclick="openParticipationForm('Football Practice Session', 'University Of Colombo Grounds', '15th September - 5:00 PM Onwards', 'Mr. Ar. Rahuman')" class="session-btn">
                        Fill The Form & Participate Now
                    </a>
                </div>
                <div class="session-image">
                    <img src="<?php echo URLROOT; ?>/images/practice-sessions/futsal-session.jpg" alt="Futsal Practice Session">
                </div>
            </div>
        </div>

        <!-- View All Sessions Button -->
        <div class="practice-sessions-cta">
            <a href="<?php echo URLROOT; ?>/coach" class="view-all-sessions-btn">
                VIEW ALL PRACTISE SESSIONS
            </a>
        </div>
    </div>
</section>

<!-- Rent Your Sport Equipments Section -->
<section class="sport-equipments-section">
    <div class="equipments-container">
        <div class="equipments-header">
            <h2>RENT YOUR SPORT EQUIPMENTS</h2>
            <p>Browse real rental service listings and book the best sports gear providers near you.</p>
        </div>

        <div class="rentals-grid grid-view">
            <?php if (!empty($data['featured_rentals'])): ?>
                <?php foreach ($data['featured_rentals'] as $rental): ?>
                    <div class="rental-card"
                        data-equipment="<?php echo implode(',', array_map('strtolower', $rental->equipment_types)); ?>"
                        data-category="<?php echo strtolower($rental->category); ?>"
                        data-location="<?php echo strtolower(str_replace(' ', '-', $rental->location)); ?>"
                        data-rating="<?php echo $rental->rating; ?>"
                        data-status="<?php echo strtolower($rental->status); ?>"
                        data-delivery="<?php echo $rental->delivery ? 'true' : 'false'; ?>"
                        data-experience="<?php echo str_replace(' years', '', strtolower($rental->experience)); ?>">

                        <div class="rental-image">
                            <?php
                            if (!empty($rental->display_image) && strpos($rental->display_image, 'uploads/') === 0) {
                                $imageUrl = URLROOT . '/' . $rental->display_image;
                            } elseif (!empty($rental->display_image)) {
                                $imageUrl = URLROOT . '/images/rental/' . $rental->display_image;
                            } else {
                                $imageUrl = URLROOT . '/images/rental/default-store.jpg';
                            }
                            ?>
                            <img src="<?php echo $imageUrl; ?>"
                                alt="<?php echo htmlspecialchars($rental->store_name); ?>"
                                onerror="this.src='https://via.placeholder.com/400x300?text=<?php echo urlencode($rental->store_name); ?>'">

                            <div class="status-badge status-<?php echo strtolower($rental->status); ?>">
                                <?php echo $rental->status; ?>
                            </div>

                            <div class="category-badge">
                                <?php echo $rental->category; ?>
                            </div>

                            <div class="rating-badge">
                                <span class="star">⭐</span>
                                <span class="rating"><?php echo $rental->rating; ?></span>
                            </div>

                            <div class="experience-badge">
                                <?php echo $rental->experience; ?>
                            </div>
                        </div>

                        <div class="rental-info">
                            <div class="rental-header">
                                <h3 class="store-name"><?php echo htmlspecialchars($rental->store_name); ?></h3>
                                <?php if (!empty($rental->delivery)): ?>
                                    <div class="delivery-badge">🚚 Delivery</div>
                                <?php endif; ?>
                            </div>

                            <div class="store-location">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                                </svg>
                                <span><?php echo htmlspecialchars($rental->location ?? $rental->address ?? 'Unknown location'); ?></span>
                            </div>

                            <div class="equipment-types">
                                <?php foreach (array_slice($rental->equipment_types, 0, 3) as $equipment): ?>
                                    <span class="equipment-tag">
                                        <?php
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
                                            default:
                                                $icon = '✓';
                                                break;
                                        }
                                        echo $icon . ' ' . $feature;
                                        ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>

                            <?php if (!empty($rental->hours)): ?>
                                <div class="store-hours">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M16.2,16.2L11,13V7H12.5V12.2L17,14.7L16.2,16.2Z" />
                                    </svg>
                                    <span><?php echo htmlspecialchars($rental->hours); ?></span>
                                </div>
                            <?php endif; ?>

                            <div class="store-owner">
                                <div class="owner-avatar"><?php echo substr($rental->owner ?? '', 0, 1); ?></div>
                                <div class="owner-info">
                                    <span class="owner-name"><?php echo htmlspecialchars($rental->owner ?? 'Store Owner'); ?></span>
                                    <span class="owner-status status-<?php echo strtolower($rental->owner_status ?? 'offline'); ?>">
                                        <span class="status-dot"></span>
                                        <?php echo htmlspecialchars($rental->owner_status ?? 'Offline'); ?>
                                    </span>
                                </div>
                            </div>

                            <div class="contact-actions">
                                <button class="btn-phone" onclick="window.location.href='tel:<?php echo htmlspecialchars($rental->phone ?? ''); ?>'">
                                    Call
                                </button>
                                <button class="btn-email" onclick="window.location.href='mailto:<?php echo htmlspecialchars($rental->email ?? ''); ?>'">
                                    Email
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="equipment-store-empty">
                    <p>There are no rental services available at the moment. Please check back later.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="equipments-cta">
            <a href="<?php echo URLROOT; ?>/rental" class="view-more-equipments-btn">
                VIEW MORE
            </a>
        </div>
    </div>
</section>

<!-- Publish Your Ad Section -->
<section class="publish-ad-section">
    <div class="publish-ad-container">
        <div class="publish-ad-content">
            <!-- Left Column - Images -->
            <div class="ad-images-grid">
                <!-- Man with phone image -->
                <div class="ad-image-large">
                    <img src="<?php echo URLROOT; ?>/images/advertisements/man-phone.jpg" alt="Man using phone">
                </div>

                <!-- Top right - phone/app image -->
                <div class="ad-image-small top">
                    <img src="<?php echo URLROOT; ?>/images/advertisements/phone-app.jpg" alt="Mobile app">
                </div>

                <!-- Bottom right - done checkmark -->
                <div class="ad-image-small bottom">
                    <div class="done-badge">
                        <div class="checkmark-icon">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
                                <path d="M9 12l2 2 4-4" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <span class="done-text">DONE</span>
                    </div>
                </div>
            </div>

            <!-- Right Column - Content -->
            <div class="ad-content">
                <h2>Publish Your Ad In BookMY Ground</h2>
                <p class="ad-description">
                    Reach Thousands of Sports Enthusiasts! Promote your brand on BookMY Ground the go-to platform for stadium bookings. Advertise directly to players, ground owners, and sports fans across the country.
                </p>

                <!-- Features List -->
                <div class="ad-features">
                    <div class="ad-feature">
                        <div class="feature-dot"></div>
                        <div class="feature-content">
                            <h3>Show On Top Of the website</h3>
                            <p>Your Advertisement will show on top of the website</p>
                        </div>
                    </div>

                    <div class="ad-feature">
                        <div class="feature-dot"></div>
                        <div class="feature-content">
                            <h3>Affordable Prices</h3>
                            <p>You Can Publish Your Advertisement At Affordable Price</p>
                        </div>
                    </div>
                </div>

                <!-- CTA Button -->
                <a href="<?php echo URLROOT; ?>/advertisement" class="publish-ad-btn">
                    Publish Your Ad Now
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Subscription Section -->
<section class="newsletter-subscription-section">
    <div class="subscription-container">
        <div class="subscription-content">
            <!-- Left Column - Content -->
            <div class="subscription-text">
                <h2>Stay Updated with BookMyGround</h2>
                <p>Get the latest updates on new stadiums, exclusive deals, coaching sessions, and sports events delivered straight to your inbox.</p>

                <div class="subscription-benefits">
                    <div class="benefit-item">
                        <div class="benefit-icon">🏟️</div>
                        <span>New stadium listings</span>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">💰</div>
                        <span>Exclusive booking discounts</span>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">🏃‍♂️</div>
                        <span>Sports events & tournaments</span>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">👨‍🏫</div>
                        <span>Coaching session updates</span>
                    </div>
                </div>
            </div>

            <!-- Right Column - Subscription Form -->
            <div class="subscription-form-container">
                <div class="subscription-form-wrapper">
                    <h3>Subscribe Now</h3>
                    <p class="form-subtitle">Join 10,000+ sports enthusiasts</p>

                    <form class="subscription-form" id="subscriptionForm">
                        <div class="form-group">
                            <div class="input-wrapper">
                                <input type="email"
                                    id="email"
                                    name="email"
                                    placeholder="Your email address"
                                    class="email-input"
                                    required>
                                <button type="submit" class="send-btn" id="subscribeBtn">
                                    <span class="btn-text">SEND</span>
                                    <svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="form-privacy">
                            <label class="privacy-checkbox">
                                <input type="checkbox" id="privacy" name="privacy" required>
                                <span class="checkmark"></span>
                                <span class="privacy-text">I agree to receive newsletters and promotional emails</span>
                            </label>
                        </div>

                        <div class="success-message" id="successMessage" style="display: none;">
                            <div class="success-icon">✅</div>
                            <span>Successfully subscribed! Welcome to BookMyGround community.</span>
                        </div>
                    </form>

                    <div class="subscription-stats">
                        <div class="stat-item">
                            <span class="stat-number">10K+</span>
                            <span class="stat-label">Subscribers</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">95%</span>
                            <span class="stat-label">Satisfaction</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">Weekly</span>
                            <span class="stat-label">Updates</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Participation Form Popup -->
<div class="participation-popup-overlay" id="participationPopup">
    <div class="participation-popup">
        <button class="popup-close" onclick="closeParticipationForm()">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
            </svg>
        </button>

        <div class="popup-header">
            <h2>Practice Session Participation Form</h2>
            <p class="session-info" id="sessionInfo"></p>
        </div>

        <form class="participation-form" id="participationForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="firstName">First Name *</label>
                    <input type="text" id="firstName" name="firstName" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name *</label>
                    <input type="text" id="lastName" name="lastName" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
            </div>

            <div class="form-group">
                <label for="ageGroup">Age Group *</label>
                <select id="ageGroup" name="ageGroup" required>
                    <option value="">Select Age Group</option>
                    <option value="under_18">Under 18</option>
                    <option value="18_25">18-25 years</option>
                    <option value="26_35">26-35 years</option>
                    <option value="36_45">36-45 years</option>
                    <option value="above_45">Above 45 years</option>
                </select>
            </div>

            <div class="form-group">
                <label for="skillLevel">Skill Level *</label>
                <select id="skillLevel" name="skillLevel" required>
                    <option value="">Select Skill Level</option>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                    <option value="professional">Professional</option>
                </select>
            </div>

            <div class="form-group">
                <label for="experience">Previous Experience</label>
                <textarea id="experience" name="experience" rows="3" placeholder="Tell us about your previous experience in this sport (optional)"></textarea>
            </div>

            <div class="form-group">
                <label for="expectations">What are your expectations from this session?</label>
                <textarea id="expectations" name="expectations" rows="3" placeholder="Share your goals and expectations (optional)"></textarea>
            </div>

            <div class="form-group checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" id="terms" name="terms" required>
                    <span>I agree to the terms and conditions and understand the session requirements *</span>
                </label>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeParticipationForm()">Cancel</button>
                <button type="submit" class="btn-submit">Submit Participation</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Reuse the same functions from stadiums page
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

    // Newsletter subscription functionality
    document.addEventListener('DOMContentLoaded', function() {
        const subscriptionForm = document.getElementById('subscriptionForm');
        const subscribeBtn = document.getElementById('subscribeBtn');
        const successMessage = document.getElementById('successMessage');

        if (subscriptionForm) {
            subscriptionForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const email = document.getElementById('email').value;
                const privacy = document.getElementById('privacy').checked;

                if (!email || !privacy) {
                    alert('Please fill in your email and accept the privacy policy');
                    return;
                }

                // Real subscription request
                subscribeBtn.innerHTML = '<span class="btn-text">SENDING...</span>';
                subscribeBtn.disabled = true;

                fetch('<?php echo URLROOT; ?>/newsletterapi/subscribe', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'email=' + encodeURIComponent(email) + '&privacy=' + (privacy ? '1' : '0')
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Newsletter subscribe response:', data);
                        if (data && data.success) {
                            successMessage.style.display = 'flex';
                            subscriptionForm.style.display = 'none';

                            // Reset form after 3 seconds
                            setTimeout(() => {
                                subscriptionForm.style.display = 'block';
                                successMessage.style.display = 'none';
                                subscriptionForm.reset();
                                subscribeBtn.innerHTML = '<span class="btn-text">SEND</span><svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>';
                                subscribeBtn.disabled = false;
                            }, 3000);
                        } else {
                            const msg = (data && data.message) ? data.message : 'Failed to subscribe. Please try again.';
                            const dbg = (data && data.debug) ? ('\n\nDebug: ' + data.debug) : '';
                            alert(msg + dbg);
                            subscribeBtn.innerHTML = '<span class="btn-text">SEND</span><svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>';
                            subscribeBtn.disabled = false;
                        }
                    })
                    .catch(() => {
                        alert('Failed to subscribe. Please try again.');
                        subscribeBtn.innerHTML = '<span class="btn-text">SEND</span><svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>';
                        subscribeBtn.disabled = false;
                    });
            });
        }

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Animate stadium cards
        document.querySelectorAll('.featured-stadium-card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
            observer.observe(card);
        });

        // Animate subscription section
        const subscriptionSection = document.querySelector('.newsletter-subscription-section');
        if (subscriptionSection) {
            subscriptionSection.style.opacity = '0';
            subscriptionSection.style.transform = 'translateY(30px)';
            subscriptionSection.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
            observer.observe(subscriptionSection);
        }
    });

    // Practice Session Participation Form Functions
    function openParticipationForm(sessionName, venue, dateTime, coach) {
        const popup = document.getElementById('participationPopup');
        const sessionInfo = document.getElementById('sessionInfo');

        sessionInfo.innerHTML = `
        <strong>${sessionName}</strong><br>
        📍 ${venue} | 📅 ${dateTime} | 👨‍🏫 ${coach}
    `;

        popup.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        // Animate popup entrance
        setTimeout(() => {
            popup.querySelector('.participation-popup').style.transform = 'scale(1)';
            popup.querySelector('.participation-popup').style.opacity = '1';
        }, 10);
    }

    function closeParticipationForm() {
        const popup = document.getElementById('participationPopup');
        const form = document.getElementById('participationForm');

        popup.querySelector('.participation-popup').style.transform = 'scale(0.9)';
        popup.querySelector('.participation-popup').style.opacity = '0';

        setTimeout(() => {
            popup.style.display = 'none';
            document.body.style.overflow = 'auto';
            form.reset();
        }, 300);
    }

    // Handle form submission
    document.addEventListener('DOMContentLoaded', function() {
        const participationForm = document.getElementById('participationForm');

        if (participationForm) {
            participationForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Show success message
                alert('Thank you for your participation! We will contact you soon with further details.');
                closeParticipationForm();
            });
        }

        // Close popup when clicking outside
        const popup = document.getElementById('participationPopup');
        if (popup) {
            popup.addEventListener('click', function(e) {
                if (e.target === popup) {
                    closeParticipationForm();
                }
            });
        }

        // Close popup with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && popup.style.display === 'flex') {
                closeParticipationForm();
            }
        });
    });

    // ============================================
    // ADVERTISEMENT CAROUSEL
    // ============================================
    let currentAdSlide = 0;
    let adSlideInterval;
    const adSlides = document.querySelectorAll('.ad-slide');
    const adDots = document.querySelectorAll('.ad-dot');

    function showAdSlide(index) {
        if (adSlides.length === 0) return;

        // Wrap around
        if (index >= adSlides.length) currentAdSlide = 0;
        else if (index < 0) currentAdSlide = adSlides.length - 1;
        else currentAdSlide = index;

        // Hide all slides - directly set opacity style
        adSlides.forEach(slide => {
            slide.style.opacity = '0';
            slide.classList.remove('active');
        });
        adDots.forEach(dot => {
            dot.style.background = 'rgba(255,255,255,0.4)';
            dot.classList.remove('active');
        });

        // Show current slide - directly set opacity style
        adSlides[currentAdSlide].style.opacity = '1';
        adSlides[currentAdSlide].classList.add('active');
        if (adDots[currentAdSlide]) {
            adDots[currentAdSlide].style.background = 'white';
            adDots[currentAdSlide].classList.add('active');
        }
    }

    function nextAdSlide() {
        showAdSlide(currentAdSlide + 1);
        resetAdInterval();
    }

    function prevAdSlide() {
        showAdSlide(currentAdSlide - 1);
        resetAdInterval();
    }

    function goToAdSlide(index) {
        showAdSlide(index);
        resetAdInterval();
    }

    function resetAdInterval() {
        clearInterval(adSlideInterval);
        if (adSlides.length > 1) {
            adSlideInterval = setInterval(nextAdSlide, 5000); // Auto-slide every 5 seconds
        }
    }

    // Initialize auto-slide if multiple ads
    if (adSlides.length > 1) {
        adSlideInterval = setInterval(nextAdSlide, 5000);
    }
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>