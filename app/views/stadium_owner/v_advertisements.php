<?php require APPROOT . '/views/stadium_owner/inc/header.php'; ?>

<div class="dashboard-content ads-page">
    <div class="dashboard-header">
        <h1>📢 Advertisement Management</h1>
    </div>

    <!-- Flash Messages -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success" style="background:#1d2a1f;color:#9be7ad;padding:15px;border-radius:8px;margin-bottom:20px;border:1px solid #2f5d3a;">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger" style="background:#2b161b;color:#f5b9c0;padding:15px;border-radius:8px;margin-bottom:20px;border:1px solid #5e2930;">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Submit Advertisement Form -->
    <div class="form-card" style="background:#fff;border-radius:12px;padding:25px;margin-bottom:30px;box-shadow:0 2px 10px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom:5px;">📢 Submit New Advertisement</h3>
        <p style="color:#666;margin-bottom:20px;">Promote your stadium or sports business to our community</p>

        <form action="<?php echo URLROOT; ?>/stadium_owner/submitAdvertisement" method="POST" enctype="multipart/form-data">
            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));gap:20px;">
                
                <div class="form-group">
                    <label style="display:block;margin-bottom:8px;font-weight:600;">Business/Company Name *</label>
                    <input type="text" name="company_name" placeholder="Your business name" required 
                           style="width:100%;padding:12px;border:1px solid #ddd;border-radius:8px;box-sizing:border-box;">
                </div>

                <div class="form-group">
                    <label style="display:block;margin-bottom:8px;font-weight:600;">Website (Optional)</label>
                    <input type="url" name="website" placeholder="https://example.com" 
                           style="width:100%;padding:12px;border:1px solid #ddd;border-radius:8px;box-sizing:border-box;">
                </div>

                <div class="form-group">
                    <label style="display:block;margin-bottom:8px;font-weight:600;">Package *</label>
                    <select name="package" required style="width:100%;padding:12px;border:1px solid #ddd;border-radius:8px;">
                        <?php foreach($data['ad_packages'] as $key => $pkg): ?>
                            <option value="<?php echo $key; ?>">
                                <?php echo $pkg['name']; ?> - LKR <?php echo number_format($pkg['price']); ?> (<?php echo $pkg['duration']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label style="display:block;margin-bottom:8px;font-weight:600;">Ad Image (Optional)</label>
                    <input type="file" name="ad_image" accept="image/*" 
                           style="width:100%;padding:12px;border:1px solid #ddd;border-radius:8px;box-sizing:border-box;">
                    <small style="color:#888;font-size:12px;display:block;margin-top:5px;">
                        📐 Recommended: <strong>1200 x 100 pixels</strong> (landscape banner format)<br>
                        📁 Accepted: JPG, PNG, GIF, WEBP | Max size: 5MB
                    </small>
                </div>

                <div class="form-group" style="grid-column:1/-1;">
                    <label style="display:block;margin-bottom:8px;font-weight:600;">Description/Message</label>
                    <textarea name="message" rows="3" placeholder="Describe your advertisement..." 
                              style="width:100%;padding:12px;border:1px solid #ddd;border-radius:8px;box-sizing:border-box;resize:vertical;"></textarea>
                </div>

            </div>
            <button type="submit" style="margin-top:20px;padding:12px 30px;background:#28a745;color:#fff;border:none;border-radius:8px;cursor:pointer;font-weight:600;font-size:16px;">
                📢 Submit Advertisement
            </button>
        </form>
    </div>

    <!-- My Advertisements List -->
    <div class="ads-list-card" style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 2px 10px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom:20px;">📋 My Submitted Advertisements</h3>

        <?php if(empty($data['advertisements'])): ?>
            <div style="text-align:center;padding:40px;color:#666;">
                <p style="font-size:18px;margin-bottom:10px;">📢 No advertisements yet.</p>
                <p>Submit your first advertisement above to promote your business!</p>
            </div>
        <?php else: ?>
            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(300px, 1fr));gap:20px;">
                <?php foreach($data['advertisements'] as $ad): ?>
                <div class="ad-card" style="border:1px solid #e0e0e0;border-radius:12px;overflow:hidden;">
                    <?php if(!empty($ad->file_path)): ?>
                    <div style="height:100px;overflow:hidden;background:#f5f5f5;">
                        <img src="<?php echo URLROOT; ?>/images/advertisements/<?php echo $ad->file_path; ?>" 
                             alt="<?php echo htmlspecialchars($ad->company_name); ?>" 
                             style="width:100%;height:100%;object-fit:cover;">
                    </div>
                    <?php endif; ?>
                    
                    <div style="padding:15px;">
                        <h4 style="margin:0 0 10px 0;">🏢 <?php echo htmlspecialchars($ad->company_name); ?></h4>
                        
                        <p style="margin:5px 0;font-size:14px;"><strong>📦 Package:</strong> <?php echo ucfirst($ad->package ?? 'basic'); ?></p>
                        
                        <?php if(!empty($ad->website)): ?>
                            <p style="margin:5px 0;font-size:14px;"><strong>🌐 Website:</strong> 
                                <a href="<?php echo htmlspecialchars($ad->website); ?>" target="_blank" style="color:#3498db;">
                                    <?php echo strlen($ad->website) > 30 ? htmlspecialchars(substr($ad->website, 0, 30)) . '...' : htmlspecialchars($ad->website); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        
                        <p style="margin:5px 0;font-size:14px;"><strong>📅 Submitted:</strong> <?php echo date('M d, Y', strtotime($ad->submitted_at)); ?></p>
                        
                        <?php if(!empty($ad->message)): ?>
                            <p style="margin:5px 0;font-size:14px;"><strong>📝 Message:</strong> 
                                <?php echo htmlspecialchars(substr($ad->message, 0, 60)); ?><?php echo strlen($ad->message) > 60 ? '...' : ''; ?>
                            </p>
                        <?php endif; ?>
                        
                        <?php 
                        $statusStyles = [
                            'pending' => 'background:#2c2413;color:#ffe69c;',
                            'active' => 'background:#1d2a1f;color:#9be7ad;',
                            'approved' => 'background:#1d2a1f;color:#9be7ad;',
                            'rejected' => 'background:#2b161b;color:#f5b9c0;',
                            'expired' => 'background:#212529;color:#ced4da;'
                        ];
                        $statusIcons = ['pending' => '⏳', 'active' => '🟢', 'approved' => '✓', 'rejected' => '✗', 'expired' => '⚪'];
                        ?>
                        <span style="display:inline-block;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;<?php echo $statusStyles[$ad->status] ?? ''; ?>">
                            <?php echo ($statusIcons[$ad->status] ?? '') . ' ' . ucfirst($ad->status); ?>
                        </span>
                    </div>
                    
                    <div style="padding:10px 15px;background:#f8f9fa;display:flex;gap:10px;border-top:1px solid #e0e0e0;">
                        <?php if(in_array($ad->status, ['pending', 'active', 'approved'])): ?>
                            <button onclick="openEditModal(<?php echo $ad->id; ?>, '<?php echo htmlspecialchars(addslashes($ad->company_name)); ?>', '<?php echo htmlspecialchars(addslashes($ad->website ?? '')); ?>', '<?php echo htmlspecialchars(addslashes($ad->message ?? '')); ?>')" 
                                    style="padding:8px 16px;background:#f39c12;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:13px;">
                                ✏️ Edit
                            </button>
                        <?php endif; ?>
                        <a href="<?php echo URLROOT; ?>/stadium_owner/deleteAdvertisement/<?php echo $ad->id; ?>" 
                           onclick="return confirm('Remove this advertisement?');" 
                           style="padding:8px 16px;background:#e74c3c;color:#fff;border:none;border-radius:6px;cursor:pointer;text-decoration:none;font-size:13px;">
                            🗑️ Delete
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Edit Ad Modal -->
<div id="editModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:9999;overflow-y:auto;">
    <div style="background:#fff;max-width:500px;margin:50px auto;border-radius:12px;overflow:hidden;">
        <div style="background:#2c3e50;color:#fff;padding:20px;display:flex;justify-content:space-between;align-items:center;">
            <h3 style="margin:0;">Edit Advertisement</h3>
            <span onclick="closeEditModal()" style="font-size:28px;cursor:pointer;">&times;</span>
        </div>
        <div style="padding:20px;">
            <form id="editForm" method="POST" enctype="multipart/form-data">
                <div style="margin-bottom:20px;">
                    <label style="display:block;margin-bottom:8px;font-weight:600;">Business/Company Name *</label>
                    <input type="text" name="company_name" id="edit_company_name" required 
                           style="width:100%;padding:12px;border:1px solid #ddd;border-radius:8px;box-sizing:border-box;">
                </div>
                
                <div style="margin-bottom:20px;">
                    <label style="display:block;margin-bottom:8px;font-weight:600;">Website (Optional)</label>
                    <input type="url" name="website" id="edit_website" 
                           style="width:100%;padding:12px;border:1px solid #ddd;border-radius:8px;box-sizing:border-box;">
                </div>
                
                <div style="margin-bottom:20px;">
                    <label style="display:block;margin-bottom:8px;font-weight:600;">New Image (Optional)</label>
                    <input type="file" name="ad_image" accept="image/*" 
                           style="width:100%;padding:12px;border:1px solid #ddd;border-radius:8px;box-sizing:border-box;">
                    <small style="color:#888;font-size:12px;display:block;margin-top:5px;">
                        📐 Recommended: 1200 x 100 px | Leave empty to keep current image
                    </small>
                </div>
                
                <div style="margin-bottom:20px;">
                    <label style="display:block;margin-bottom:8px;font-weight:600;">Description/Message</label>
                    <textarea name="message" id="edit_message" rows="3" 
                              style="width:100%;padding:12px;border:1px solid #ddd;border-radius:8px;box-sizing:border-box;resize:vertical;"></textarea>
                </div>
                
                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" onclick="closeEditModal()" 
                            style="padding:12px 24px;border:1px solid #ddd;background:#fff;border-radius:8px;cursor:pointer;">Cancel</button>
                    <button type="submit" 
                            style="padding:12px 24px;background:#f39c12;color:#fff;border:none;border-radius:8px;cursor:pointer;font-weight:600;">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(id, companyName, website, message) {
    document.getElementById('editForm').action = '<?php echo URLROOT; ?>/stadium_owner/editAdvertisement/' + id;
    document.getElementById('edit_company_name').value = companyName;
    document.getElementById('edit_website').value = website;
    document.getElementById('edit_message').value = message;
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

window.onclick = function(event) {
    if (event.target.id === 'editModal') {
        event.target.style.display = 'none';
    }
}
</script>

<?php require APPROOT . '/views/stadium_owner/inc/footer.php'; ?>
