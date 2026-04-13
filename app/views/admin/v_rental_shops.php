<?php require APPROOT . '/views/admin/inc/header.php'; ?>

<div class="kal-admin-main-content">
    <div class="kal-admin-header">
        <h1>🏪 Rental Shop Management</h1>
        <p>Review and approve rental shop listings</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card pending">
            <div class="stat-icon">⏳</div>
            <div class="stat-details">
                <h3><?php echo $data['stats']['pending'] ?? 0; ?></h3>
                <p>Pending Review</p>
            </div>
        </div>
        <div class="stat-card approved">
            <div class="stat-icon">✅</div>
            <div class="stat-details">
                <h3><?php echo $data['stats']['approved'] ?? 0; ?></h3>
                <p>Approved</p>
            </div>
        </div>
        <div class="stat-card rejected">
            <div class="stat-icon">❌</div>
            <div class="stat-details">
                <h3><?php echo $data['stats']['rejected'] ?? 0; ?></h3>
                <p>Rejected</p>
            </div>
        </div>
        <div class="stat-card total">
            <div class="stat-icon">📊</div>
            <div class="stat-details">
                <h3><?php echo $data['stats']['total'] ?? 0; ?></h3>
                <p>Total Shops</p>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="<?php echo URLROOT; ?>/admin/rentalShops?filter=all" 
           class="filter-tab <?php echo $data['current_filter'] === 'all' ? 'active' : ''; ?>">
            All Shops (<?php echo $data['stats']['total']; ?>)
        </a>
        <a href="<?php echo URLROOT; ?>/admin/rentalShops?filter=pending" 
           class="filter-tab <?php echo $data['current_filter'] === 'pending' ? 'active' : ''; ?>">
            Pending (<?php echo $data['stats']['pending']; ?>)
        </a>
        <a href="<?php echo URLROOT; ?>/admin/rentalShops?filter=approved" 
           class="filter-tab <?php echo $data['current_filter'] === 'approved' ? 'active' : ''; ?>">
            Approved (<?php echo $data['stats']['approved']; ?>)
        </a>
        <a href="<?php echo URLROOT; ?>/admin/rentalShops?filter=rejected" 
           class="filter-tab <?php echo $data['current_filter'] === 'rejected' ? 'active' : ''; ?>">
            Rejected (<?php echo $data['stats']['rejected']; ?>)
        </a>
    </div>

    <!-- Shops Table -->
    <div class="kal-admin-content-section">
        <div class="table-container">
            <table class="kal-admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Shop Image</th>
                        <th>Shop Name</th>
                        <th>Owner</th>
                        <th>Category</th>
                        <th>District</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['shops'])): ?>
                        <?php foreach ($data['shops'] as $shop): ?>
                        <tr>
                            <td><?php echo $shop->id; ?></td>
                            <td>
                                <?php if (!empty($shop->primary_image)): ?>
                                    <img src="<?php echo URLROOT; ?>/<?php echo $shop->primary_image; ?>" 
                                         alt="<?php echo htmlspecialchars($shop->store_name); ?>" 
                                         class="shop-thumbnail">
                                <?php else: ?>
                                    <div class="no-image">📷</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($shop->store_name); ?></strong>
                                <br>
                                <small><?php echo $shop->image_count ?? 0; ?> images</small>
                            </td>
                            <td>
                                <?php echo htmlspecialchars(($shop->first_name ?? '') . ' ' . ($shop->last_name ?? '')); ?>
                                <br>
                                <small><?php echo htmlspecialchars($shop->owner_email ?? 'N/A'); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($shop->category ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($shop->location ?? 'N/A'); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $shop->status; ?>">
                                    <?php 
                                    if ($shop->status === 'pending') echo '⏳ Pending';
                                    elseif ($shop->status === 'approved') echo '✅ Approved';
                                    elseif ($shop->status === 'rejected') echo '❌ Rejected';
                                    else echo ucfirst($shop->status);
                                    ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($shop->created_at)); ?></td>
                            <td>
                                <div class="actions-cell">
                                    <button class="action-btn view-btn" onclick="viewShop(<?php echo $shop->id; ?>)">
                                        👁️ View
                                    </button>
                                    <?php if ($shop->status === 'pending'): ?>
                                        <button class="action-btn approve-btn" onclick="approveShop(<?php echo $shop->id; ?>)">
                                            ✅ Approve
                                        </button>
                                    <?php endif; ?>
                                    <?php if ($shop->status === 'pending' || $shop->status === 'approved'): ?>
                                        <button class="action-btn reject-btn" onclick="rejectShop(<?php echo $shop->id; ?>)">
                                            ❌ Reject
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="no-data">No shops found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Shop Details Modal -->
<div id="shopModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="shopDetails"></div>
    </div>
</div>

<!-- Rejection Reason Modal -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeRejectModal()">&times;</span>
        <h2>Reject Shop</h2>
        <form id="rejectForm">
            <input type="hidden" id="rejectShopId" name="shop_id">
            <div class="form-group">
                <label for="rejectReason">Reason for Rejection <span class="required">*</span></label>
                <textarea id="rejectReason" name="reason" rows="4" required 
                          placeholder="Please provide a clear reason for rejection..."></textarea>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="closeRejectModal()">Cancel</button>
                <button type="submit" class="btn-danger">Submit Rejection</button>
            </div>
        </form>
    </div>
</div>

<style>
.kal-admin-table {
    width: 100%;
    table-layout: fixed;
}

.kal-admin-table th,
.kal-admin-table td {
    padding: 10px 8px;
    vertical-align: middle;
    word-wrap: break-word;
}

.kal-admin-table th {
    white-space: nowrap;
}

.actions-cell {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
}

.shop-details-grid {
    display: flex;
    gap: 20px;
    align-items: flex-start;
}

.shop-details-left {
    flex: 1;
    min-width: 240px;
}

.shop-details-right {
    flex: 1;
    min-width: 240px;
}

.shop-name {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 10px;
    color: #fff;
}

.owner-meta,
.shop-meta {
    color: #ddd;
    font-size: 14px;
    margin-bottom: 10px;
}

.gallery-title {
    color: #ccc;
    font-weight: 700;
    margin: 0 0 10px 0;
}

.shop-gallery {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.thumb-card img {
    width: 90px;
    height: 70px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #333;
}

.no-image-large {
    width: 100%;
    height: 120px;
    border-radius: 12px;
    background: #333;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
}

.shop-description {
    color: #ddd;
    line-height: 1.5;
    white-space: pre-wrap;
    margin-top: 8px;
}

.rejection-reason-section {
    margin-top: 20px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: #1a1a1a;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #333;
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-card.pending { border-left: 4px solid #ff9800; }
.stat-card.approved { border-left: 4px solid #03B200; }
.stat-card.rejected { border-left: 4px solid #f44336; }
.stat-card.total { border-left: 4px solid #2196F3; }

.stat-icon {
    font-size: 32px;
}

.stat-details h3 {
    font-size: 28px;
    color: #fff;
    margin: 0;
}

.stat-details p {
    color: #999;
    margin: 5px 0 0;
    font-size: 14px;
}

.filter-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    border-bottom: 2px solid #333;
}

.filter-tab {
    padding: 12px 24px;
    background: none;
    border: none;
    color: #999;
    cursor: pointer;
    text-decoration: none;
    border-bottom: 3px solid transparent;
    transition: all 0.3s ease;
}

.filter-tab:hover {
    color: #03B200;
}

.filter-tab.active {
    color: #03B200;
    border-bottom-color: #03B200;
}

.shop-thumbnail {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}

.no-image {
    width: 60px;
    height: 60px;
    background: #333;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

.status-pending {
    background: #ff980020;
    color: #ff9800;
    border: 1px solid #ff9800;
}

.status-approved {
    background: #03B20020;
    color: #03B200;
    border: 1px solid #03B200;
}

.status-rejected {
    background: #f4433620;
    color: #f44336;
    border: 1px solid #f44336;
}

.action-btn {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 12px;
    margin: 2px;
    transition: all 0.3s ease;
}

.view-btn {
    background: #2196F3;
    color: white;
}

.view-btn:hover {
    background: #1976D2;
}

.approve-btn {
    background: #03B200;
    color: white;
}

.approve-btn:hover {
    background: #028a00;
}

.reject-btn {
    background: #f44336;
    color: white;
}

.reject-btn:hover {
    background: #d32f2f;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);
}

.modal-content {
    background: #1a1a1a;
    margin: 5% auto;
    padding: 30px;
    border: 1px solid #333;
    border-radius: 12px;
    width: 90%;
    max-width: 800px;
    max-height: 80vh;
    overflow-y: auto;
    color: #fff;
}

.close {
    color: #999;
    float: right;
    font-size: 32px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #fff;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #ccc;
}

.form-group textarea {
    width: 100%;
    padding: 12px;
    background: #0a0a0a;
    border: 1px solid #333;
    border-radius: 8px;
    color: #fff;
    font-family: inherit;
    resize: vertical;
}

.form-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.btn-secondary {
    padding: 10px 20px;
    background: #666;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.btn-danger {
    padding: 10px 20px;
    background: #f44336;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.required {
    color: #f44336;
}

.no-data {
    text-align: center;
    padding: 40px;
    color: #999;
}
</style>

<script>
function viewShop(shopId) {
    const modal = document.getElementById('shopModal');
    const detailsEl = document.getElementById('shopDetails');
    detailsEl.innerHTML = '<p style="color:#fff">Loading shop details...</p>';
    modal.style.display = 'block';

    fetch('<?php echo URLROOT; ?>/admin/rentalShopDetails', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'shop_id=' + encodeURIComponent(shopId)
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            detailsEl.innerHTML = '<p style="color:#ff6b6b; margin:0">' + (data.message || 'Failed to load details') + '</p>';
            alert('❌ ' + (data.message || 'Failed to load details'));
            return;
        }

        const shop = data.shop || {};
        const baseUrl = '<?php echo URLROOT; ?>';

        function escapeHtml(text) {
            return String(text ?? '').replace(/[&<>"']/g, function (m) {
                switch (m) {
                    case '&': return '&amp;';
                    case '<': return '&lt;';
                    case '>': return '&gt;';
                    case '"': return '&quot;';
                    case "'": return '&#039;';
                    default: return m;
                }
            });
        }

        function imgUrl(p) {
            if (!p) return '';
            if (p.startsWith('http')) return p;
            if (p.startsWith('/')) return baseUrl + p;
            return baseUrl + '/' + p;
        }

        const storeName = escapeHtml(shop.store_name || shop.shop_name || 'N/A');
        const ownerName = escapeHtml((shop.first_name || '') + ' ' + (shop.last_name || '')).trim();
        const ownerEmail = escapeHtml(shop.owner_email || 'N/A');

        const category = escapeHtml(shop.category || 'N/A');
        const district = escapeHtml(shop.district || shop.location || 'N/A');
        const address = escapeHtml(shop.address || 'N/A');
        const description = escapeHtml(shop.description || 'N/A');
        const contactEmail = escapeHtml(shop.contact_email || shop.email || 'N/A');
        const contactPhone = escapeHtml(shop.contact_phone || shop.phone || 'N/A');
        const operatingHours = escapeHtml(shop.operating_hours || shop.hours || 'N/A');
        const equipmentTypes = Array.isArray(shop.equipment_types) ? shop.equipment_types : [];
        const amenities = Array.isArray(shop.features) ? shop.features : [];
        const equipmentTypesText = equipmentTypes.length ? equipmentTypes.map(escapeHtml).join(', ') : '';
        const amenitiesText = amenities.length ? amenities.map(escapeHtml).join(', ') : '';
        const status = String(shop.status || '').toLowerCase();
        const createdAt = shop.created_at ? escapeHtml(shop.created_at) : '';

        const primaryImage = shop.primary_image ? imgUrl(shop.primary_image) : '';

        let galleryHtml = '';
        const images = Array.isArray(shop.images) ? shop.images : [];
        if (images.length > 0) {
            galleryHtml = images.slice(0, 10).map(img => {
                const src = imgUrl(img.image_path);
                return '<div class="thumb-card"><img src="' + escapeHtml(src) + '" alt="Shop image"></div>';
            }).join('');
        } else if (primaryImage) {
            galleryHtml = '<div class="thumb-card"><img src="' + escapeHtml(primaryImage) + '" alt="Shop image"></div>';
        } else {
            galleryHtml = '<div class="no-image-large">📷</div>';
        }

        const statusLabel =
            status === 'pending' ? '⏳ Pending' :
            status === 'approved' ? '✅ Approved' :
            status === 'rejected' ? '❌ Rejected' :
            (shop.status ? escapeHtml(shop.status) : 'N/A');

        const statusClass =
            status === 'pending' ? 'status-pending' :
            status === 'approved' ? 'status-approved' :
            status === 'rejected' ? 'status-rejected' : '';

        const rejectionReason = shop.rejection_reason ? escapeHtml(shop.rejection_reason) : '';

        detailsEl.innerHTML = `
            <div class="shop-details-grid">
                <div class="shop-details-left">
                    <div class="shop-name">${storeName}</div>
                    <div class="owner-meta">
                        <div><strong>Owner:</strong> ${ownerName || 'N/A'}</div>
                        <div><strong>Email:</strong> ${ownerEmail}</div>
                    </div>
                    <div class="shop-meta">
                        <div><strong>Category:</strong> ${category}</div>
                        <div><strong>District:</strong> ${district}</div>
                        <div><strong>Address:</strong> ${address}</div>
                        <div><strong>Contact Email:</strong> ${contactEmail}</div>
                        <div><strong>Contact Phone:</strong> ${contactPhone}</div>
                        <div><strong>Operating Hours:</strong> ${operatingHours}</div>
                        ${equipmentTypesText ? `<div><strong>Equipment Types:</strong> ${equipmentTypesText}</div>` : ''}
                        ${amenitiesText ? `<div><strong>Amenities:</strong> ${amenitiesText}</div>` : ''}
                        <div><strong>Submitted:</strong> ${createdAt ? createdAt : 'N/A'}</div>
                        <div>
                            <span class="status-badge ${statusClass}">
                                ${statusLabel}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="shop-details-right">
                    <div class="gallery-title">Shop Images</div>
                    <div class="shop-gallery">${galleryHtml}</div>
                </div>
            </div>

            <div class="shop-desc-section">
                <div class="gallery-title">Description</div>
                <p class="shop-description">${description}</p>
            </div>

            ${rejectionReason ? `
                <div class="rejection-reason-section">
                    <div class="gallery-title">Rejection Reason</div>
                    <p class="shop-description">${rejectionReason}</p>
                </div>
            ` : ''}
        `;
    })
    .catch(err => {
        console.error(err);
        detailsEl.innerHTML = '<p style="color:#ff6b6b; margin:0">Failed to load shop details. Please try again.</p>';
        alert('❌ Failed to load shop details');
    });
}

function approveShop(shopId) {
    if (confirm('Are you sure you want to approve this shop? It will become visible to all customers.')) {
        fetch('<?php echo URLROOT; ?>/admin/approveShop', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'shop_id=' + shopId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ Shop approved successfully! Owner will be notified via email.');
                location.reload();
            } else {
                alert('❌ Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to approve shop');
        });
    }
}

function rejectShop(shopId) {
    document.getElementById('rejectShopId').value = shopId;
    document.getElementById('rejectModal').style.display = 'block';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
    document.getElementById('rejectForm').reset();
}

function closeModal() {
    document.getElementById('shopModal').style.display = 'none';
}

document.getElementById('rejectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const shopId = document.getElementById('rejectShopId').value;
    const reason = document.getElementById('rejectReason').value;
    
    fetch('<?php echo URLROOT; ?>/admin/rejectShop', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'shop_id=' + shopId + '&reason=' + encodeURIComponent(reason)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Shop rejected. Owner will be notified via email.');
            closeRejectModal();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to reject shop');
    });
});

// Close modal when clicking outside
window.onclick = function(event) {
    const shopModal = document.getElementById('shopModal');
    const rejectModal = document.getElementById('rejectModal');
    if (event.target === shopModal) {
        closeModal();
    }
    if (event.target === rejectModal) {
        closeRejectModal();
    }
}
</script>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>
