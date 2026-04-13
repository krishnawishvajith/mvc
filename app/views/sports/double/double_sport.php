<?php require APPROOT . '/views/inc/components/header.php'; ?>

<div class="kala-page-wrap">
    <section class="stadiums-hero">
        <div class="hero-container">
            <div class="hero-content">
                <h1 class="kala-single-title"><?php echo $data['title'] ?? 'Single Sports'; ?></h1>
                <p class="kala-single-sub">Choose a sport to view available stadiums, contacts and details.</p>
            </div>
        </div>
    </section>
    <div class="kala-sports-grid">
        <?php foreach (($data['sports'] ?? []) as $sport): ?>
            <div class="kala-sport-box">
                <div class="kala-sport-media">
                    <img src="<?php echo htmlspecialchars($sport['image']); ?>" alt="<?php echo htmlspecialchars($sport['title']); ?>" />
                </div>
                <div class="kala-sport-body">
                    <h3><?php echo htmlspecialchars($sport['title']); ?></h3>
                    <p><?php echo htmlspecialchars($sport['description']); ?></p>
                    <a class="kala-btn kala-btn-primary" href="<?php echo URLROOT; ?>/stadiums?sport=<?php echo urlencode($sport['slug']); ?>">View Listings</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="kala-actions" style="justify-content:center;margin-top:20px;">
        <a href="<?php echo URLROOT; ?>/sports" class="kala-btn kala-btn-back">Back to Categories</a>
    </div>
</div>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>
