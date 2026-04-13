<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Hero Section (stadium-style) -->
<section class="stadiums-hero">
    <div class="hero-container">
        <div class="hero-content">
            <h1>Choose Your Sport</h1>
            <p>Select a category to find and book your perfect playing ground</p>
        </div>
    </div>
</section>

<div class="sports-categories">
    <div class="container">
        <div class="categories-grid">
            <div class="category-card single-sport">
                <a href="<?php echo URLROOT; ?>/sports/single" class="category-link">
                    <div class="category-image">
                        <img src="<?php echo URLROOT; ?>/public/images/sports/gplf.jpg" alt="Single Sport">
                        <div class="category-overlay"></div>
                    </div>
                    <div class="category-content">
                        <div class="category-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 17.58A5 5 0 0 0 18 8h-1.26A8 8 0 1 0 4 16.25"></path>
                                <path d="M8 16h.01"></path>
                                <path d="M8 20h.01"></path>
                                <path d="M12 18h.01"></path>
                                <path d="M12 22h.01"></path>
                                <path d="M16 16h.01"></path>
                                <path d="M16 20h.01"></path>
                            </svg>
                        </div>
                        <h3 class="category-title">Single Sports</h3>
                        <p class="category-description">Individual sports where you compete alone</p>
                        <div class="category-cta">
                            <span>Explore Sports</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>

            <div class="category-card double-sport">
                <a href="<?php echo URLROOT; ?>/sports/double" class="category-link">
                    <div class="category-image">
                        <img src="<?php echo URLROOT; ?>/public/images/sports/Badminton.jpeg" alt="Double Sport">
                        <div class="category-overlay"></div>
                    </div>
                    <div class="category-content">
                        <div class="category-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h3 class="category-title">Double Sports</h3>
                        <p class="category-description">Sports played with a partner against opponents</p>
                        <div class="category-cta">
                            <span>Explore Sports</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>

            <div class="category-card team-sport">
                <a href="<?php echo URLROOT; ?>/sports/team" class="category-link">
                    <div class="category-image">
                        <img src="<?php echo URLROOT; ?>/public/images/sports/teams.jpg" alt="Team Sport">
                        <div class="category-overlay"></div>
                    </div>
                    <div class="category-content">
                        <div class="category-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h3 class="category-title">Team Sports</h3>
                        <p class="category-description">Group sports where teams compete together</p>
                        <div class="category-cta">
                            <span>Explore Sports</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>

