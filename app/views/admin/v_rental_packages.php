<?php require APPROOT.'/views/admin/inc/header.php'; ?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>Rental Service Owner Package Management</h1>
        <div class="header-actions">
            <button class="btn-save-packages" onclick="saveAllPackages()">💾 Save All Changes</button>
            <button class="btn-preview-packages" onclick="previewPackages()">👁️ Preview Public Page</button>
        </div>
    </div>

    <div id="success-message" style="display: none; background: rgba(0,255,0,0.1); border: 1px solid #28a745; color: #28a745; padding: 15px; border-radius: 8px; margin-bottom: 20px;"></div>
    <div id="error-message" style="display: none; background: rgba(255,0,0,0.1); border: 1px solid #ff4444; color: #ff6666; padding: 15px; border-radius: 8px; margin-bottom: 20px;"></div>

    <form id="packages-form">
        <div class="packages-grid">
            <?php 
            $packageTypes = array_keys($data['packages']);
            $packageIds = range(1, count($packageTypes));
            foreach($packageTypes as $index => $type): 
                $pkg = $data['packages'][$type];
                $id = $packageIds[$index];
            ?>
            <div class="package-card <?php echo $type; ?> <?php echo $pkg['is_popular'] ? 'popular' : ''; ?>">
                <div class="package-header">
                    <h3><?php echo $pkg['name']; ?> Package</h3>
                    <label>
                        <input type="checkbox" <?php echo $pkg['is_popular'] ? 'checked' : ''; ?> name="package_<?php echo $id; ?>_popular"> Mark as Popular
                    </label>
                </div>

                <div class="form-grid">
                    <div class="form-group-small">
                        <label>Package Name</label>
                        <input type="text" name="package_<?php echo $id; ?>_name" value="<?php echo htmlspecialchars($pkg['name']); ?>" class="form-control">
                    </div>

                    <div class="form-group-small">
                        <label>Icon (Emoji)</label>
                        <input type="text" name="package_<?php echo $id; ?>_icon" value="<?php echo htmlspecialchars($pkg['icon']); ?>" class="form-control" style="width: 80px;">
                    </div>

                    <div class="form-group-small">
                        <label>Package Price (LKR)</label>
                        <input type="number" name="package_<?php echo $id; ?>_price" value="<?php echo $pkg['price']; ?>" class="form-control" step="0.01">
                    </div>

                    <div class="form-group-small">
                        <label>Duration Text</label>
                        <input type="text" name="package_<?php echo $id; ?>_duration" value="<?php echo htmlspecialchars($pkg['duration_text']); ?>" class="form-control" placeholder="e.g., Valid for 3 months">
                    </div>

                    <div class="form-group-small">
                        <label>Shop Listings</label>
                        <input type="number" name="package_<?php echo $id; ?>_shops" value="<?php echo $pkg['shop_listings']; ?>" class="form-control" min="1" max="50">
                    </div>

                    <div class="form-group-small">
                        <label>Images per Listing</label>
                        <input type="number" name="package_<?php echo $id; ?>_images" value="<?php echo $pkg['images_per_listing']; ?>" class="form-control" min="1" max="20">
                    </div>
                </div>

                <div class="form-group">
                    <label>Package Description</label>
                    <textarea name="package_<?php echo $id; ?>_description" class="form-control" rows="3"><?php echo htmlspecialchars($pkg['description']); ?></textarea>
                </div>

                <div class="feature-checkboxes">
                    <label><input type="checkbox" name="package_<?php echo $id; ?>_phone" <?php echo $pkg['phone_contact'] ? 'checked' : ''; ?>> Phone Contact</label>
                    <label><input type="checkbox" name="package_<?php echo $id; ?>_email" <?php echo $pkg['email_contact'] ? 'checked' : ''; ?>> Email Contact</label>
                    <label><input type="checkbox" name="package_<?php echo $id; ?>_amenities" <?php echo $pkg['amenities_display'] ? 'checked' : ''; ?>> Amenities Display</label>
                    <label><input type="checkbox" name="package_<?php echo $id; ?>_priority" <?php echo $pkg['priority_placement'] ? 'checked' : ''; ?>> Priority Placement</label>
                    <label><input type="checkbox" name="package_<?php echo $id; ?>_support" <?php echo $pkg['email_phone_support'] ? 'checked' : ''; ?>> Email & Phone Support</label>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </form>

    <!-- Package Purchases Section -->
    <div class="purchases-section">
        <div class="section-header">
            <h2>🏪 Rental Package Purchase Management</h2>
            <div class="purchase-stats">
                <span class="stat-badge pending">Pending: <?php echo $data['purchase_counts']['pending']; ?></span>
                <span class="stat-badge active">Active: <?php echo $data['purchase_counts']['active']; ?></span>
                <span class="stat-badge suspended">Suspended: <?php echo $data['purchase_counts']['suspended']; ?></span>
                <span class="stat-badge failed">Failed: <?php echo $data['purchase_counts']['failed']; ?></span>
                <span class="stat-badge total">Total: <?php echo $data['purchase_counts']['total']; ?></span>
            </div>
        </div>

        <?php if (empty($data['package_purchases'])): ?>
            <div class="no-purchases">
                <p>📭 No rental package purchases yet. Users will appear here after purchasing packages.</p>
            </div>
        <?php else: ?>
            <div class="purchases-table-container">
                <table class="purchases-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User Details</th>
                            <th>Business Info</th>
                            <th>Package</th>
                            <th>Amount</th>
                            <th>Purchased Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['package_purchases'] as $purchase): ?>
                            <tr class="purchase-row" data-purchase-id="<?php echo $purchase->id; ?>">
                                <td>#<?php echo $purchase->id; ?></td>
                                <td>
                                    <div class="user-info">
                                        <strong><?php echo htmlspecialchars($purchase->first_name . ' ' . $purchase->last_name); ?></strong><br>
                                        <small><?php echo htmlspecialchars($purchase->email); ?></small><br>
                                        <small>📱 <?php echo htmlspecialchars($purchase->phone); ?></small>
                                    </div>
                                </td>
                                <td>
                                    <div class="business-info">
                                        <?php if (!empty($purchase->business_name)): ?>
                                            <strong><?php echo htmlspecialchars($purchase->business_name); ?></strong><br>
                                        <?php endif; ?>
                                        <?php if (!empty($purchase->district)): ?>
                                            <small>📍 <?php echo htmlspecialchars($purchase->district); ?></small><br>
                                        <?php endif; ?>
                                        <?php if (!empty($purchase->business_type)): ?>
                                            <small>🏪 <?php echo htmlspecialchars($purchase->business_type); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="package-badge <?php echo $purchase->package_color; ?>">
                                        <?php echo $purchase->package_icon; ?> <?php echo htmlspecialchars($purchase->package_name); ?>
                                    </span>
                                </td>
                                <td><strong>LKR <?php echo number_format($purchase->payment_amount, 2); ?></strong></td>
                                <td><?php echo date('M d, Y', strtotime($purchase->purchased_at)); ?><br><small><?php echo date('h:i A', strtotime($purchase->purchased_at)); ?></small></td>
                                <td>
                                    <select class="status-select status-<?php echo $purchase->package_status; ?>" onchange="updatePurchaseStatus(<?php echo $purchase->id; ?>, this.value)">
                                        <option value="pending" <?php echo $purchase->package_status === 'pending' ? 'selected' : ''; ?>>⏳ Pending</option>
                                        <option value="active" <?php echo $purchase->package_status === 'active' ? 'selected' : ''; ?>>✅ Active</option>
                                        <option value="suspended" <?php echo $purchase->package_status === 'suspended' ? 'selected' : ''; ?>>⛔ Suspended</option>
                                        <option value="expired" <?php echo $purchase->package_status === 'expired' ? 'selected' : ''; ?>>⏹️ Expired</option>
                                        <option value="failed" <?php echo $purchase->package_status === 'failed' ? 'selected' : ''; ?>>❌ Failed</option>
                                    </select>
                                </td>
                                <td>
                                    <button class="btn-view-details" onclick="viewPurchaseDetails(<?php echo $purchase->id; ?>)">👁️ View</button>
                                    <button class="btn-add-note" onclick="addAdminNote(<?php echo $purchase->id; ?>)">📝 Note</button>
                                </td>
                            </tr>
                            <?php if (!empty($purchase->admin_notes)): ?>
                                <tr class="notes-row">
                                    <td colspan="8">
                                        <div class="admin-notes">
                                            <strong>Admin Notes:</strong> <?php echo nl2br(htmlspecialchars($purchase->admin_notes)); ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function saveAllPackages() {
    const form = document.getElementById('packages-form');
    const formData = new FormData(form);
    const saveButton = document.querySelector('.btn-save-packages');
    
    saveButton.disabled = true;
    saveButton.textContent = '⏳ Saving...';
    
    fetch('<?php echo URLROOT; ?>/admin/rental_packages', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            const successMsg = document.getElementById('success-message');
            successMsg.textContent = data.message;
            successMsg.style.display = 'block';
            saveButton.style.background = '#03B200';
            saveButton.textContent = '✓ Saved Successfully';
            setTimeout(() => {
                successMsg.style.display = 'none';
                saveButton.textContent = '💾 Save All Changes';
                saveButton.disabled = false;
                location.reload();
            }, 1500);
        } else {
            const errorMsg = document.getElementById('error-message');
            errorMsg.textContent = 'Error: ' + (data.message || 'Unknown error');
            errorMsg.style.display = 'block';
            saveButton.disabled = false;
            saveButton.textContent = '💾 Save All Changes';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const errorMsg = document.getElementById('error-message');
        errorMsg.textContent = 'Failed to save packages. Check console for details.';
        errorMsg.style.display = 'block';
        saveButton.disabled = false;
        saveButton.textContent = '💾 Save All Changes';
    });
}

function previewPackages() {
    window.open('<?php echo URLROOT; ?>/rental_packages', '_blank');
}

// Update rental package purchase status
function updatePurchaseStatus(purchaseId, newStatus) {
    if (!confirm(`Are you sure you want to change the status to "${newStatus}"?`)) {
        location.reload();
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'update_rental_purchase_status');
    formData.append('purchase_id', purchaseId);
    formData.append('status', newStatus);
    
    fetch('<?php echo URLROOT; ?>/admin/rental_packages', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('✅ Status updated successfully!');
            location.reload();
        } else {
            alert('❌ Error: ' + (data.message || 'Unknown error'));
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Failed to update status');
        location.reload();
    });
}

// View purchase details
function viewPurchaseDetails(purchaseId) {
    const row = document.querySelector(`tr[data-purchase-id="${purchaseId}"]`);
    const cells = row.querySelectorAll('td');
    
    let details = 'Purchase Details:\n\n';
    details += `Purchase ID: #${purchaseId}\n`;
    details += `User: ${cells[1].textContent.trim()}\n`;
    details += `Business: ${cells[2].textContent.trim()}\n`;
    details += `Package: ${cells[3].textContent.trim()}\n`;
    details += `Amount: ${cells[4].textContent.trim()}\n`;
    details += `Date: ${cells[5].textContent.trim()}\n`;
    
    alert(details);
}

// Add admin note
function addAdminNote(purchaseId) {
    const note = prompt('Enter admin note for this purchase:');
    if (!note || note.trim() === '') return;
    
    const formData = new FormData();
    formData.append('action', 'update_rental_purchase_status');
    formData.append('purchase_id', purchaseId);
    formData.append('status', document.querySelector(`tr[data-purchase-id="${purchaseId}"] select`).value);
    formData.append('admin_notes', note.trim());
    
    fetch('<?php echo URLROOT; ?>/admin/rental_packages', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('✅ Note added successfully!');
            location.reload();
        } else {
            alert('❌ Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Failed to add note');
    });
}

// Mark unsaved changes for package edits only
document.querySelectorAll('.packages-grid input, .packages-grid select, .packages-grid textarea').forEach(element => {
    element.addEventListener('change', function() {
        document.querySelector('.btn-save-packages').style.background = '#ff9800';
        document.querySelector('.btn-save-packages').textContent = '💾 Unsaved Changes';
    });
});
</script>

<style>
.packages-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 25px;
    margin-top: 30px;
}

.package-card {
    background: #1a1a1a;
    border: 2px solid #333;
    border-radius: 12px;
    padding: 25px;
}

.package-card.standard { border-color: #03B200; }
.package-card.popular { box-shadow: 0 0 20px rgba(3, 178, 0, 0.2); }

.package-header {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #333;
}

.package-header h3 {
    color: #fff;
    margin-bottom: 10px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 20px;
}

.form-group, .form-group-small {
    margin-bottom: 15px;
}

.form-group label, .form-group-small label {
    display: block;
    color: #ccc;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
}

.form-control {
    width: 100%;
    padding: 10px;
    background: #0a0a0a;
    border: 1px solid #333;
    border-radius: 6px;
    color: #fff;
    font-size: 14px;
}

.form-control:focus {
    outline: none;
    border-color: #03B200;
}

textarea.form-control {
    resize: vertical;
    font-family: inherit;
}

.feature-checkboxes {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 15px;
}

.feature-checkboxes label {
    color: #ccc;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.feature-checkboxes input[type="checkbox"] {
    width: 18px;
    height: 18px;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.dashboard-header h1 {
    color: #fff;
    font-size: 28px;
}

.header-actions {
    display: flex;
    gap: 10px;
}

.btn-save-packages, .btn-preview-packages {
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-save-packages {
    background: #03B200;
    color: white;
}

.btn-save-packages:hover {
    background: #029800;
    transform: translateY(-2px);
}

.btn-preview-packages {
    background: #17a2b8;
    color: white;
}

.btn-preview-packages:hover {
    background: #138496;
    transform: translateY(-2px);
}

@media (max-width: 1200px) {
    .packages-grid {
        grid-template-columns: 1fr;
    }
    .form-grid {
        grid-template-columns: 1fr;
    }
}

/* ========== PURCHASES SECTION STYLES ========== */

.purchases-section {
    margin-top: 50px;
    padding-top: 30px;
    border-top: 2px solid #333;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.section-header h2 {
    color: #fff;
    font-size: 24px;
    margin: 0;
}

.purchase-stats {
    display: flex;
    gap: 15px;
}

.stat-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.stat-badge.pending {
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
    border: 1px solid #ffc107;
}

.stat-badge.active {
    background: rgba(40, 167, 69, 0.2);
    color: #28a745;
    border: 1px solid #28a745;
}

.stat-badge.suspended {
    background: rgba(220, 53, 69, 0.2);
    color: #dc3545;
    border: 1px solid #dc3545;
}

.stat-badge.failed {
    background: rgba(255, 0, 0, 0.2);
    color: #ff0000;
    border: 1px solid #ff0000;
}

.stat-badge.total {
    background: rgba(3, 178, 0, 0.2);
    color: #03B200;
    border: 1px solid #03B200;
}

.no-purchases {
    background: #1a1a1a;
    border: 2px dashed #333;
    border-radius: 12px;
    padding: 60px;
    text-align: center;
}

.no-purchases p {
    color: #999;
    font-size: 16px;
    margin: 0;
}

.purchases-table-container {
    background: #1a1a1a;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #333;
}

.purchases-table {
    width: 100%;
    border-collapse: collapse;
}

.purchases-table thead {
    background: #0a0a0a;
}

.purchases-table th {
    padding: 15px 12px;
    text-align: left;
    color: #03B200;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    border-bottom: 2px solid #333;
}

.purchases-table td {
    padding: 15px 12px;
    color: #ccc;
    border-bottom: 1px solid #252525;
    font-size: 14px;
}

.purchase-row:hover {
    background: #222;
}

.user-info, .business-info {
    line-height: 1.6;
}

.user-info strong, .business-info strong {
    color: #fff;
    font-size: 14px;
}

.user-info small, .business-info small {
    color: #999;
    font-size: 12px;
}

.package-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
}

.package-badge.standard {
    background: rgba(3, 178, 0, 0.2);
    color: #03B200;
    border: 1px solid #03B200;
}

.status-select {
    padding: 8px 12px;
    background: #0a0a0a;
    border: 1px solid #333;
    border-radius: 6px;
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    min-width: 130px;
}

.status-select.status-pending {
    border-color: #ffc107;
    color: #ffc107;
}

.status-select.status-active {
    border-color: #28a745;
    color: #28a745;
}

.status-select.status-suspended {
    border-color: #dc3545;
    color: #dc3545;
}

.status-select.status-expired {
    border-color: #6c757d;
    color: #6c757d;
}

.status-select.status-failed {
    border-color: #ff0000;
    color: #ff0000;
}

.status-select:hover {
    background: #1a1a1a;
    border-color: #03B200;
}

.btn-view-details, .btn-add-note {
    padding: 6px 12px;
    border: 1px solid #333;
    border-radius: 6px;
    background: #0a0a0a;
    color: #fff;
    font-size: 12px;
    cursor: pointer;
    margin-right: 5px;
    transition: all 0.3s ease;
}

.btn-view-details:hover {
    background: #17a2b8;
    border-color: #17a2b8;
}

.btn-add-note:hover {
    background: #03B200;
    border-color: #03B200;
}

.notes-row {
    background: #0a0a0a;
}

.admin-notes {
    padding: 12px;
    color: #ffc107;
    font-size: 13px;
    border-left: 3px solid #ffc107;
    background: rgba(255, 193, 7, 0.05);
}

@media (max-width: 1400px) {
    .purchases-table {
        font-size: 12px;
    }
    
    .purchases-table th,
    .purchases-table td {
        padding: 10px 8px;
    }
}
</style>

<?php require APPROOT.'/views/admin/inc/footer.php'; ?>
