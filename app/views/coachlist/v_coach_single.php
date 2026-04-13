<?php require APPROOT . '/views/inc/Components/header.php'; ?>

<div class="coach-single-page">
    <div class="coach-single-hero">
        <div class="coach-single-photo">
            <?php
                $coach = $data['coach'];
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
                    $img = URLROOT . '/public/images/coaches/' . (strtolower($coach['gender'] ?? 'male') === 'female' ? 'defultgirl.jpg' : 'defultboy.jpg');
                }
            ?>
            <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($coach['name']); ?>">
        </div>
        <div class="coach-single-meta">
            <div class="coach-header">
                <h1><?php echo htmlspecialchars($coach['name']); ?></h1>
                <span class="coach-badge featured-badge">Featured Coach</span>
            </div>
            <p class="coach-single-sport"><?php echo htmlspecialchars($coach['sport']); ?> Coach</p>
            
            <!-- Mobile Number Display -->
            <div class="coach-mobile-display">
                <span class="mobile-icon">📱</span>
                <span class="mobile-number"><?php echo htmlspecialchars($coach['mobile'] ?? 'Not Available'); ?></span>
            </div>
            
            <div class="coach-single-info">
                <span class="coach-single-rating">
                    <span class="stars">★★★★★</span>
                    <span class="rating-text"><?php echo htmlspecialchars($coach['rating']); ?>/5</span>
                </span>
                <span class="coach-single-location">📍 <?php echo htmlspecialchars($coach['location']); ?></span>
                <span class="coach-single-availability <?php echo htmlspecialchars($coach['availability']); ?>">
                    <?php echo htmlspecialchars(ucfirst($coach['availability'])); ?>
                </span>
            </div>
            
            <div class="coach-quick-stats">
                <div class="stat">
                    <span class="stat-value"><?php echo htmlspecialchars($coach['experience'] ?? 'N/A'); ?></span>
                    <span class="stat-label">Experience</span>
                </div>
                <div class="stat">
                    <span class="stat-value"><?php echo htmlspecialchars($coach['certification'] ?? 'Certified'); ?></span>
                    <span class="stat-label">Certification</span>
                </div>
                <div class="stat">
                    <span class="stat-value">LKR <?php echo htmlspecialchars($coach['hourly_rate'] ?? $coach['rate'] ?? 'Negotiable'); ?>/hr</span>
                    <span class="stat-label">Rate</span>
                </div>
            </div>

            <div class="coach-single-actions">
                <a class="coach-single-btn coach-single-btn-primary" href="<?php echo URLROOT; ?>/contact?coach=<?php echo (int)$coach['id']; ?>">
                    📞 Contact Coach
                </a>
                <a class="coach-single-btn coach-single-btn-secondary" href="<?php echo URLROOT; ?>/coach/sport/<?php echo urlencode($coach['sport']); ?>">
                    ← Back to <?php echo htmlspecialchars($coach['sport']); ?> coaches
                </a>
            </div>
        </div>
    </div>

    <div class="coach-single-details">
        <div class="coach-main-content">
            <div class="coach-single-section about-section">
                <h3>About Me</h3>
                <p><?php echo htmlspecialchars($coach['bio'] ?? 'Experienced coach with years of practice and training. Contact for pricing and availability.'); ?></p>
                
                <!-- Specializations removed -->

                <?php if(!empty($coach['languages'])): ?>
                <div class="languages-section">
                    <h4>Languages Spoken</h4>
                    <div class="language-tags">
                        <?php foreach($coach['languages'] as $lang): ?>
                            <span class="language-tag"><?php echo htmlspecialchars($lang); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>




            <div class="coach-single-section free-slots-section">
                <h3>🆓 Free Training Sessions</h3>
                <p class="section-description">Join these complimentary sessions to experience my coaching style</p>
                
                <?php if(!empty($coach['free_slots'])): ?>
                    <div class="free-slots-grid">
                        <?php foreach($coach['free_slots'] as $slot): ?>
                            <div class="free-slot-card">
                                <div class="slot-day"><?php echo htmlspecialchars($slot->day); ?></div>
                                <div class="slot-time"><?php echo htmlspecialchars($slot->time_slot); ?></div>
                                <div class="slot-type"><?php echo htmlspecialchars($slot->session_type); ?></div>
                                <button class="book-slot-btn">Contact Coach</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="no-slots">No free slots currently available. Please contact coach for scheduling.</p>
                <?php endif; ?>
            </div>






        </div>

        <div class="coach-sidebar">
            <div class="coach-single-section pricing-section">
                <h3>Pricing & Availability</h3>
                <div class="pricing-info">
                    <div class="price-item">
                        <span class="price-label">Hourly Rate</span>
                        <span class="price-value">LKR <?php echo htmlspecialchars($coach['hourly_rate'] ?? $coach['rate'] ?? 'Negotiable'); ?></span>
                    </div>
                    <div class="price-item">
                        <span class="price-label">Availability</span>
                        <span class="availability-status <?php echo htmlspecialchars($coach['availability']); ?>">
                            <?php echo htmlspecialchars(ucfirst($coach['availability'])); ?>
                        </span>
                    </div>
                    <div class="price-item">
                        <span class="price-label">Mobile</span>
                        <span class="mobile-value"><?php echo htmlspecialchars($coach['mobile'] ?? 'Not Available'); ?></span>
                    </div>
                </div>
            </div>

            <?php if(!empty($coach['achievements'])): ?>
            <div class="coach-single-section achievements-section">
                <h3>🏆 Achievements</h3>
                <ul class="achievements-list">
                    <?php foreach($coach['achievements'] as $achievement): ?>
                        <li><?php echo htmlspecialchars($achievement); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <div class="coach-single-section contact-cta">
                <h3>Ready to Start?</h3>
                <p>Book your first session or get more information</p>
                <div class="cta-buttons">
                    <a class="coach-single-btn coach-single-btn-primary" href="<?php echo URLROOT; ?>/contact?coach=<?php echo (int)$coach['id']; ?>">
                        📞 Contact Now
                    </a>
                    <?php if(!empty($coach['mobile'])): ?>
                    <a class="coach-single-btn coach-single-btn-primary" href="tel:<?php echo htmlspecialchars($coach['mobile']); ?>">
                        📞 Call Direct
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/Components/footer.php'; ?>