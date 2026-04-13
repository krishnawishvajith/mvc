<?php require APPROOT.'/views/inc/components/header.php'; ?>
<!-- Hero Section (plain, no background image / no search) -->
<section class="stadiums-hero">
    <div class="hero-container">
        <div class="hero-content">
            <h1>Find Your Perfect Coach</h1>
            <p>Connect with experienced coaches and trainers across Sri Lanka </p>
        </div>
    </div>
</section>
    <!--browns by sport-->
    <div class="browse-sport">
        <h1>Browse by sport</h1>
        
        <div class="browse-sport-types">
            <?php foreach (($data['sports'] ?? []) as $sport): ?>
                <!--This is how using single code travel through the sport base coaches-->
            <a class="browse-sport-card" href="<?php echo URLROOT . '/coach/sport/' . urlencode(strtolower($sport['title'])); ?>">  
                <img src="<?php echo htmlspecialchars($sport['image']);?>" alt="<?php echo htmlspecialchars($sport['title']);?>" />
                <p><?php echo htmlspecialchars($sport['title']);?></p>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Featured coaches listing -->
    <div class="featured-coach">
        <h1>Featured Coaches</h1>
        <div class="featured-coach-list">
            <?php foreach (($data['featured'] ?? []) as $coach): ?>
            <div class="featured-coach-card">
                <div class="coach-feature-photo">
                    <?php
                        // Prefer current status from card details (`availability_text`) when present
                        $rawStatus = trim((string)($coach['availability_text'] ?? $coach['availability'] ?? 'unavailable'));
                        $statusNorm = strtolower(str_replace(' ', '_', $rawStatus));
                        // Normalize common synonyms
                        if (in_array($statusNorm, ['flexible', 'flexibility'])) {
                            $statusNorm = 'flexibility';
                        }
                        $statusDisplay = ucfirst($rawStatus);
                    ?>
                    <span class="featured-coach-availability <?php echo htmlspecialchars($statusNorm); ?>"><?php echo htmlspecialchars($statusDisplay); ?></span>
                    <img src="<?php echo htmlspecialchars($coach['image'] ?? (URLROOT . '/public/images/coaches/back.jpg')); ?>" alt="<?php echo htmlspecialchars($coach['name'] ?? 'Coach'); ?>">
                    <div class="featured-coach-ratings">
                        <span class="featured-coach-ratings-star">⭐</span>
                        <span class="featured-coach-ratings-number"><?php echo htmlspecialchars($coach['rating'] ?? '0.0'); ?></span>
                    </div>
                </div>
                <div class="coach-feature-details">
                    <h3><?php echo htmlspecialchars($coach['name'] ?? 'Unnamed'); ?></h3>
                    <div class="coach-feature-details-location">
                        <img src="<?php echo URLROOT; ?>/public/images/coaches/location.png" alt="location icon">
                        <h5><?php echo htmlspecialchars($coach['location'] ?? 'Unknown'); ?></h5>
                        <h3><?php echo htmlspecialchars($coach['sport'] ?? ''); ?></h3>
                    </div>
                    <div class="coach-feature-details-button">
                        <a class="coach-feature-details-btn" href="<?php echo URLROOT; ?>/coach/show/<?php echo (int)($coach['id'] ?? 0); ?>">View Profile</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>

<?php require APPROOT.'/views/inc/components/footer.php'; ?>