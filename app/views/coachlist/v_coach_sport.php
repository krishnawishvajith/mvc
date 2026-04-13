<?php require APPROOT.'/views/inc/components/header.php'; ?>

<!-- Hero Section (plain, sport-specific title & description) -->
<section class="stadiums-hero">
    <div class="hero-container">
        <div class="hero-content">
            <?php
                // Normalize sport name and handle synonyms (e.g., soccer -> football)
                $sportRaw = $data['sport'] ?? 'Sport';
                $sportKey = strtolower(trim($sportRaw));
                if ($sportKey === 'soccer') {
                    $sportKey = 'football';
                }
                $sportDisplay = ucwords($sportKey);
            ?>
            <h1>Find Perfect <?php echo htmlspecialchars($sportDisplay); ?> Coach</h1>
            <p>Connect with experienced <?php echo htmlspecialchars($sportKey); ?> coaches across Sri Lanka</p>
        </div>
    </div>
</section>

<div class="featured-coach">
    <h1><?php echo htmlspecialchars(ucwords($data['sport'] ?? 'Sport')); ?> Coaches</h1>
    <div class="featured-coach-list">
        <?php if (empty($data['coaches'])): ?>
            <p>No coaches found for <?php echo htmlspecialchars(ucwords($data['sport'] ?? 'this sport')); ?>.</p>
        <?php else: ?>
            <?php foreach (($data['coaches'] ?? []) as $coach): ?>
            <div class="featured-coach-card">
                <div class="coach-feature-photo">
                    <?php
                        // Prefer current status from card details (`availability_text`) when present
                        $rawStatus = trim((string)($coach['availability_text'] ?? $coach['availability'] ?? 'unavailable'));
                        $status = strtolower(str_replace(' ', '_', $rawStatus));
                        if (in_array($status, ['flexible', 'flexibility'])) {
                            $status = 'flexibility';
                        }
                        $statusDisplay = ucfirst($rawStatus);
                    ?>
                    <span class="featured-coach-availability <?php echo htmlspecialchars($status); ?>"><?php echo htmlspecialchars($statusDisplay); ?></span>
                    
                    


                    

                    <?php
                        $img = trim($coach['image'] ?? '');
                        $useDefault = false;
                        if (!empty($img)) {
                            if (preg_match('#^https?://#i', $img)) {
                                // already a full URL, use as-is
                            } else {
                                $projectRoot = dirname(__DIR__, 3);
                                if (strpos($img, URLROOT) === 0) {
                                    $relative = substr($img, strlen(URLROOT));
                                } else {
                                    $relative = $img;
                                }
                                $relative = '/' . ltrim($relative, '/');
                                $fsPath = $projectRoot . $relative;
                                if (file_exists($fsPath)) {
                                    $img = URLROOT . $relative;
                                } else {
                                    $useDefault = true;
                                }
                            }
                        } else {
                            $useDefault = true;
                        }

                        if ($useDefault) {
                            $gender = strtolower($coach['gender'] ?? 'male');
                            if ($gender === 'female' || $gender === 'f') {
                                $img = URLROOT . '/public/images/coaches/defultgirl.jpg';
                            } else {
                                $img = URLROOT . '/public/images/coaches/defultboy.jpg';
                            }
                        }
                    ?>






                    <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($coach['name'] ?? 'Coach'); ?>">
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
        <?php endif; ?>
    </div>
</div>

<?php require APPROOT.'/views/inc/components/footer.php'; ?>
