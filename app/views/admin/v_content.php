<?php require APPROOT.'/views/admin/inc/header.php'; ?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>Home Page Management</h1>
        <div class="content-actions">
            <!-- form submit button is inside the form below -->
        </div>
    </div>

    <div class="content-grid">
        <?php if(isset($_SESSION['admin_message'])): ?>
            <div class="alert alert-success" style="margin-bottom: 16px; grid-column: 1 / -1; width: 100%;">
                <?php echo htmlspecialchars($_SESSION['admin_message']); ?>
                <?php unset($_SESSION['admin_message']); ?>
            </div>
        <?php endif; ?>
        <?php if(isset($_SESSION['admin_error'])): ?>
            <div class="alert alert-danger" style="margin-bottom: 16px; grid-column: 1 / -1; width: 100%;">
                <?php echo htmlspecialchars($_SESSION['admin_error']); ?>
                <?php unset($_SESSION['admin_error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo URLROOT; ?>/admin/content" style="grid-column: 1 / -1; width: 100%;">
        <!-- Hero Section Management -->
        <div class="content-card">
            <div class="card-header">
                <h3>Hero Section</h3>
            </div>
            <div class="content-form">
                <div class="form-group">
                    <label for="hero-title-prefix">Hero Title Prefix</label>
                    <input type="text" id="hero-title-prefix" name="hero_title_prefix" value="<?php echo htmlspecialchars($data['hero_title_prefix'] ?? 'BOOK'); ?>" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="hero-title-highlight">Hero Title Highlight</label>
                    <input type="text" id="hero-title-highlight" name="hero_title_highlight" value="<?php echo htmlspecialchars($data['hero_title_highlight'] ?? 'YOUR'); ?>" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="hero-title-suffix">Hero Title Suffix</label>
                    <input type="text" id="hero-title-suffix" name="hero_title_suffix" value="<?php echo htmlspecialchars($data['hero_title_suffix'] ?? 'SPORT GROUND'); ?>" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="hero-description">Hero Description</label>
                    <textarea id="hero-description" name="hero_description" rows="4" class="form-control" required><?php echo htmlspecialchars($data['hero_description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="hero-bg">Background Image</label>
                    <div class="image-upload">
                        <input type="file" id="hero-bg" accept="image/*" class="file-input">
                        <div class="image-preview">
                            <img src="<?php echo URLROOT; ?>/images/<?php echo $data['hero_bg_image']; ?>" alt="Current Hero Image">
                        </div>
                        <button type="button" class="btn-upload">Change Image</button>
                    </div>
                    <small style="color:#999; display:block; margin-top:8px;">
                        Background image upload is not enabled yet (titles/descriptions are saved).
                    </small>
                </div>
            </div>
        </div>

        <!-- Footer Content Management -->
        <div class="content-card">
            <div class="card-header">
                <h3>Footer Content</h3>
            </div>
            <div class="content-form">
                <div class="form-group">
                    <label for="company-name">Company Name</label>
                    <input type="text" id="company-name" name="footer_company_name" value="<?php echo htmlspecialchars($data['footer_company_name'] ?? 'BOOKMYGROUND'); ?>" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="tagline">Tagline</label>
                    <input type="text" id="tagline" name="footer_tagline" value="<?php echo htmlspecialchars($data['footer_tagline'] ?? ''); ?>" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="footer_address" rows="3" class="form-control" required><?php echo htmlspecialchars($data['footer_address'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="footer_phone" value="<?php echo htmlspecialchars($data['footer_phone'] ?? ''); ?>" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="footer_email" value="<?php echo htmlspecialchars($data['footer_email'] ?? ''); ?>" class="form-control" required>
                </div>
            </div>
        </div>

        <!-- Social Media Links -->
        <div class="content-card">
            <div class="card-header">
                <h3>Social Media Links</h3>
            </div>
            <div class="content-form">
                <div class="form-group">
                    <label for="facebook">Facebook URL</label>
                    <input type="url" id="facebook" name="social_facebook" value="<?php echo htmlspecialchars($data['social_facebook'] ?? '#'); ?>" placeholder="https://facebook.com/bookmyground" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="instagram">Instagram URL</label>
                    <input type="url" id="instagram" name="social_instagram" value="<?php echo htmlspecialchars($data['social_instagram'] ?? '#'); ?>" placeholder="https://instagram.com/bookmyground" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="twitter">Twitter URL</label>
                    <input type="url" id="twitter" name="social_twitter" value="<?php echo htmlspecialchars($data['social_twitter'] ?? '#'); ?>" placeholder="https://twitter.com/bookmyground" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="linkedin">LinkedIn URL</label>
                    <input type="url" id="linkedin" name="social_linkedin" value="<?php echo htmlspecialchars($data['social_linkedin'] ?? '#'); ?>" placeholder="https://linkedin.com/company/bookmyground" class="form-control">
                </div>

                <div class="form-group">
                    <label for="youtube">YouTube URL</label>
                    <input type="url" id="youtube" name="social_youtube" value="<?php echo htmlspecialchars($data['social_youtube'] ?? '#'); ?>" placeholder="https://youtube.com/@bookmyground" class="form-control">
                </div>
            </div>
        </div>

        <!-- Navigation Menu Management -->
        <div class="content-card">
            <div class="card-header">
                <h3>Navigation Menu</h3>
            </div>
            <div class="menu-items" id="nav-menu-items">
                <?php
                    $navItems = $data['nav_items'] ?? [];
                    if (!is_array($navItems) || empty($navItems)) {
                        $navItems = [
                            ['label' => 'Home', 'url' => '/', 'is_active' => 1],
                            ['label' => 'Stadiums', 'url' => '/stadiums', 'is_active' => 1],
                            ['label' => 'Coaches', 'url' => '/coach', 'is_active' => 1],
                            ['label' => 'Sports', 'url' => '/sports', 'is_active' => 1],
                            ['label' => 'Rental Services', 'url' => '/rental', 'is_active' => 1],
                        ];
                    }
                    $i = 0;
                ?>

                <?php foreach ($navItems as $item): ?>
                    <div class="menu-item">
                        <input type="text" name="nav_label[]" value="<?php echo htmlspecialchars($item['label'] ?? ''); ?>" class="form-control" placeholder="Menu name" required>
                        <input type="text" name="nav_url[]" value="<?php echo htmlspecialchars($item['url'] ?? ''); ?>" class="form-control" placeholder="/path or https://..." required>
                        <label style="display:flex; align-items:center; gap:8px; color:#ddd; font-size:13px; margin:0 10px;">
                            <input type="checkbox" name="nav_active[<?php echo $i; ?>]" <?php echo (!isset($item['is_active']) || $item['is_active']) ? 'checked' : ''; ?>>
                            Active
                        </label>
                        <button type="button" class="btn-remove" onclick="removeNavItem(this)">Remove</button>
                    </div>
                    <?php $i++; ?>
                <?php endforeach; ?>

                <button type="button" class="btn-add-menu" onclick="addNavItem()">+ Add Menu Item</button>
            </div>
        </div>

        <div style="margin-top: 18px; text-align: right;">
            <button type="submit" class="btn-save">Save Changes</button>
        </div>
        </form>
    </div>
</div>

<script>
function addNavItem() {
    var container = document.getElementById('nav-menu-items');
    if (!container) return;

    // Find current index count based on existing nav_label inputs
    var currentCount = container.querySelectorAll('input[name="nav_label[]"]').length;

    var row = document.createElement('div');
    row.className = 'menu-item';
    row.innerHTML = `
        <input type="text" name="nav_label[]" value="" class="form-control" placeholder="Menu name" required>
        <input type="text" name="nav_url[]" value="" class="form-control" placeholder="/path or https://..." required>
        <label style="display:flex; align-items:center; gap:8px; color:#ddd; font-size:13px; margin:0 10px;">
            <input type="checkbox" name="nav_active[${currentCount}]" checked>
            Active
        </label>
        <button type="button" class="btn-remove" onclick="removeNavItem(this)">Remove</button>
    `;

    var addBtn = container.querySelector('.btn-add-menu');
    container.insertBefore(row, addBtn);
}

function removeNavItem(btn) {
    var row = btn.closest('.menu-item');
    if (row) row.remove();
}
</script>

<?php require APPROOT.'/views/admin/inc/footer.php'; ?>