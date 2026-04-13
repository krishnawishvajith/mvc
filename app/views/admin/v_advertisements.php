<?php require APPROOT.'/views/admin/inc/header.php'; ?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>Advertisement Management</h1>
        <div class="header-actions">
            <button class="btn-publish-ad" onclick="openPublishModal()">📢 Publish New Ad</button>
        </div>
    </div>

    <?php if(isset($_SESSION['success_msg'])): ?>
        <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error_msg'])): ?>
        <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <?php echo $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?>
        </div>
    <?php endif; ?>

    <!-- Ad Stats -->
    <div class="ad-stats">
        <div class="stat-item">
            <div class="stat-icon">⏳</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo count($data['pending_ads']); ?></span>
                <span class="stat-label">Pending Review</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">✅</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo count($data['published_ads']); ?></span>
                <span class="stat-label">Active Ads</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">💰</div>
            <div class="stat-details">
                <?php 
                $totalRevenue = 0;
                foreach($data['published_ads'] as $ad) {
                    $totalRevenue += 15000; // Average package price
                }
                ?>
                <span class="stat-number">LKR <?php echo number_format($totalRevenue); ?></span>
                <span class="stat-label">Active Revenue</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">📊</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo count($data['pending_ads']) + count($data['published_ads']); ?></span>
                <span class="stat-label">Total Ads</span>
            </div>
        </div>
    </div>

    <!-- Pending Advertisements -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Pending Advertisement Requests</h3>
            <span class="badge pending"><?php echo count($data['pending_ads']); ?> pending</span>
        </div>
        <?php if(empty($data['pending_ads'])): ?>
            <div style="padding: 40px; text-align: center; color: #666;">
                <p style="font-size: 48px; margin: 0;">📭</p>
                <p>No pending advertisement requests</p>
            </div>
        <?php else: ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Company</th>
                        <th>Contact Person</th>
                        <th>Phone</th>
                        <th>Package</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['pending_ads'] as $ad): ?>
                    <tr>
                        <td>#AD<?php echo str_pad($ad['id'], 3, '0', STR_PAD_LEFT); ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($ad['company']); ?></strong>
                        </td>
                        <td>
                            <div class="contact-info">
                                <span><?php echo htmlspecialchars($ad['contact']); ?></span>
                                <small><?php echo htmlspecialchars($ad['email']); ?></small>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($ad['phone']); ?></td>
                        <td>
                            <span class="package-badge <?php echo strtolower($ad['package']); ?>">
                                <?php echo $ad['package']; ?> - LKR <?php echo number_format($ad['amount']); ?>
                            </span>
                        </td>
                        <td>
                            <span class="status-badge <?php echo strtolower(str_replace(' ', '-', $ad['status'])); ?>">
                                <?php echo $ad['status']; ?>
                            </span>
                        </td>
                        <td><?php echo $ad['submitted']; ?></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action-sm btn-view" onclick="viewAdRequest(<?php echo $ad['id']; ?>, '<?php echo htmlspecialchars($ad['company'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($ad['message'] ?? '', ENT_QUOTES); ?>', '<?php echo htmlspecialchars($ad['website'] ?? '', ENT_QUOTES); ?>', '<?php echo htmlspecialchars($ad['file_path'] ?? '', ENT_QUOTES); ?>')">👁️ View</button>
                                <form action="<?php echo URLROOT; ?>/admin/approveAdvertisement/<?php echo $ad['id']; ?>" method="POST" style="display:inline;" onsubmit="return confirm('Approve and publish this advertisement?')">
                                    <button type="submit" class="btn-action-sm btn-verify">✅ Approve</button>
                                </form>
                                <form action="<?php echo URLROOT; ?>/admin/rejectAdvertisement/<?php echo $ad['id']; ?>" method="POST" style="display:inline;" onsubmit="return confirm('Reject this advertisement request?')">
                                    <button type="submit" class="btn-action-sm btn-delete">❌ Reject</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <!-- Published Advertisements -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Currently Published Ads</h3>
        </div>
        <?php if(empty($data['published_ads'])): ?>
            <div style="padding: 40px; text-align: center; color: #666;">
                <p style="font-size: 48px; margin: 0;">📢</p>
                <p>No active advertisements at the moment</p>
            </div>
        <?php else: ?>
        <div class="published-ads-grid">
            <?php foreach($data['published_ads'] as $published): ?>
            <div class="published-ad-card">
                <div class="ad-preview">
                    <?php if(!empty($published['file_path'])): ?>
                        <img src="<?php echo URLROOT; ?>/images/advertisements/<?php echo $published['file_path']; ?>" alt="<?php echo htmlspecialchars($published['company']); ?> Ad">
                    <?php else: ?>
                        <img src="<?php echo URLROOT; ?>/images/ads/placeholder-ad.jpg" alt="<?php echo htmlspecialchars($published['company']); ?> Ad">
                    <?php endif; ?>
                    <div class="ad-type"><?php echo $published['type']; ?></div>
                </div>
                <div class="ad-details">
                    <h4><?php echo htmlspecialchars($published['company']); ?></h4>
                    <p>Published: <?php echo $published['published']; ?></p>
                    <p>Expires: <?php echo $published['expires']; ?></p>
                    <span class="status-badge active"><?php echo $published['status']; ?></span>
                </div>
                <div class="ad-actions">
                    <button class="btn-action-sm btn-delete" onclick="deleteAd(<?php echo $published['id']; ?>)">🗑️ Delete</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Publish Ad Modal -->
<div id="publishModal" class="modal">
    <div class="modal-content large">
        <div class="modal-header">
            <h3>Publish Advertisement</h3>
            <span class="close" onclick="closePublishModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form class="publish-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Company Name</label>
                        <input type="text" name="company" required>
                    </div>
                    <div class="form-group">
                        <label>Ad Type</label>
                        <select name="ad_type" required>
                            <option value="">Select Type</option>
                            <option value="image">Image</option>
                            <option value="video">Video</option>
                            <option value="gif">Animated GIF</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Upload Advertisement (1200px × 386px recommended)</label>
                    <div class="file-upload">
                        <input type="file" accept="image/*,video/*" id="adFile">
                        <div class="upload-preview" id="uploadPreview"></div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Link URL (Optional)</label>
                        <input type="url" name="link_url" placeholder="https://company.com">
                    </div>
                    <div class="form-group">
                        <label>Duration (Days)</label>
                        <input type="number" name="duration" value="30" min="1" max="365">
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closePublishModal()">Cancel</button>
                    <button type="submit" class="btn-publish">Publish Advertisement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openPublishModal() {
    document.getElementById('publishModal').style.display = 'block';
}

function closePublishModal() {
    document.getElementById('publishModal').style.display = 'none';
}

function viewAdRequest(id, company, message, website, filePath) {
    let content = `<strong>Company:</strong> ${company}<br><br>`;
    if(message) content += `<strong>Message:</strong> ${message}<br><br>`;
    if(website) content += `<strong>Website:</strong> <a href="${website}" target="_blank">${website}</a><br><br>`;
    if(filePath) content += `<strong>Attached File:</strong><br><img src="<?php echo URLROOT; ?>/images/advertisements/${filePath}" style="max-width: 100%; max-height: 300px; border-radius: 8px; margin-top: 10px;">`;
    
    // Show in a simple modal/alert
    document.getElementById('viewModalContent').innerHTML = content;
    document.getElementById('viewModal').style.display = 'block';
}

function closeViewModal() {
    document.getElementById('viewModal').style.display = 'none';
}

function deleteAd(id) {
    if(confirm('Are you sure you want to delete this advertisement?')) {
        // Create and submit form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo URLROOT; ?>/admin/rejectAdvertisement/' + id;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<!-- View Ad Modal -->
<div id="viewModal" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Advertisement Details</h3>
            <span class="close" onclick="closeViewModal()">&times;</span>
        </div>
        <div class="modal-body" id="viewModalContent">
        </div>
    </div>
</div>

<style>
.package-badge {
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}
.package-badge.basic { background: #e3f2fd; color: #1565c0; }
.package-badge.standard { background: #fff3e0; color: #e65100; }
.package-badge.premium { background: #f3e5f5; color: #7b1fa2; }
.status-badge.pending-review { background: #fff3cd; color: #856404; }
.status-badge.approved { background: #d4edda; color: #155724; }
.btn-verify { background: #28a745 !important; color: white !important; }
.btn-delete { background: #dc3545 !important; color: white !important; }
</style>

<?php require APPROOT.'/views/admin/inc/footer.php'; ?>