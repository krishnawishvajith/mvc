<?php require APPROOT . '/views/admin/inc/header.php'; ?>
<?php
function stadiumImageUrl($path)
{
    if (empty($path)) {
        return URLROOT . '/public/images/default-stadium.jpg';
    }
    if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
        return $path;
    }
    if (strpos($path, '/') === 0) {
        return $path;
    }
    if (strpos($path, 'uploads/') === 0) {
        return URLROOT . '/' . ltrim($path, '/');
    }
    return URLROOT . '/public/uploads/stadiums/' . ltrim($path, '/');
}
?>

<div class="kal-admin-main-content">
    <div class="kal-admin-header">
        <h1>🏟️ Stadium Listings Management</h1>
        <p>Review and approve stadium listings</p>
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
                <p>Total Stadiums</p>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="<?php echo URLROOT; ?>/admin/stadiumListings?filter=all"
            class="filter-tab <?php echo $data['current_filter'] === 'all' ? 'active' : ''; ?>">
            All Stadiums (<?php echo $data['stats']['total']; ?>)
        </a>
        <a href="<?php echo URLROOT; ?>/admin/stadiumListings?filter=pending"
            class="filter-tab <?php echo $data['current_filter'] === 'pending' ? 'active' : ''; ?>">
            Pending (<?php echo $data['stats']['pending']; ?>)
        </a>
        <a href="<?php echo URLROOT; ?>/admin/stadiumListings?filter=approved"
            class="filter-tab <?php echo $data['current_filter'] === 'approved' ? 'active' : ''; ?>">
            Approved (<?php echo $data['stats']['approved']; ?>)
        </a>
        <a href="<?php echo URLROOT; ?>/admin/stadiumListings?filter=rejected"
            class="filter-tab <?php echo $data['current_filter'] === 'rejected' ? 'active' : ''; ?>">
            Rejected (<?php echo $data['stats']['rejected']; ?>)
        </a>
    </div>

    <!-- Stadiums Table -->
    <div class="kal-admin-content-section">
        <div class="table-container">
            <table class="kal-admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Stadium Image</th>
                        <th>Stadium Name</th>
                        <th>Owner</th>
                        <th>Type</th>
                        <th>District</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['stadiums'])): ?>
                        <?php foreach ($data['stadiums'] as $stadium): ?>
                            <tr>
                                <td><?php echo $stadium->id; ?></td>
                                <td>
                                    <?php if (!empty($stadium->primary_image) || !empty($stadium->image)): ?>
                                        <?php
                                        $stadiumImage = !empty($stadium->primary_image) ? $stadium->primary_image : $stadium->image;
                                        ?>
                                        <img src="<?php echo stadiumImageUrl($stadiumImage); ?>"
                                            alt="<?php echo htmlspecialchars($stadium->name); ?>"
                                            class="stadium-thumbnail">
                                    <?php else: ?>
                                        <div class="no-image">🏟️</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($stadium->name); ?></strong>
                                    <br>
                                    <small><?php echo $stadium->image_count ?? 0; ?> images</small>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars(($stadium->first_name ?? '') . ' ' . ($stadium->last_name ?? '')); ?>
                                    <br>
                                    <small><?php echo htmlspecialchars($stadium->owner_email ?? 'N/A'); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($stadium->type ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($stadium->district ?? 'N/A'); ?></td>
                                <td>LKR <?php echo number_format($stadium->price ?? 0); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $stadium->approval_status; ?>">
                                        <?php
                                        if ($stadium->approval_status === 'pending') echo '⏳ Pending';
                                        elseif ($stadium->approval_status === 'approved') echo '✅ Approved';
                                        elseif ($stadium->approval_status === 'rejected') echo '❌ Rejected';
                                        else echo ucfirst($stadium->approval_status);
                                        ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($stadium->created_at)); ?></td>
                                <td>
                                    <button class="action-btn view-btn" onclick="viewStadium(<?php echo $stadium->id; ?>)">
                                        👁️ View
                                    </button>
                                    <?php if ($stadium->approval_status === 'pending'): ?>
                                        <button class="action-btn approve-btn" onclick="approveStadium(<?php echo $stadium->id; ?>)">
                                            ✅ Approve
                                        </button>
                                    <?php endif; ?>
                                    <?php if ($stadium->approval_status === 'pending' || $stadium->approval_status === 'approved'): ?>
                                        <button class="action-btn reject-btn" onclick="rejectStadium(<?php echo $stadium->id; ?>)">
                                            ❌ Reject
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="no-data">No stadiums found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Stadium Details Modal -->
<div id="stadiumModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="stadiumDetails"></div>
    </div>
</div>

<!-- Rejection Reason Modal -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeRejectModal()">&times;</span>
        <h2>Reject Stadium</h2>
        <form id="rejectForm">
            <input type="hidden" id="rejectStadiumId" name="stadium_id">
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
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: #151515;
        padding: 24px;
        border-radius: 18px;
        border: 1px solid #222;
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .stat-card.pending {
        border-left: 5px solid #ff9800;
    }

    .stat-card.approved {
        border-left: 5px solid #03B200;
    }

    .stat-card.rejected {
        border-left: 5px solid #f44336;
    }

    .stat-card.total {
        border-left: 5px solid #2196F3;
    }

    .stat-icon {
        font-size: 34px;
    }

    .stat-details h3 {
        font-size: 30px;
        color: #fff;
        margin: 0;
    }

    .stat-details p {
        color: #b4b4b4;
        margin: 6px 0 0;
        font-size: 13px;
    }

    .filter-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 24px;
    }

    .filter-tab {
        padding: 11px 20px;
        border-radius: 999px;
        color: #ccc;
        background: #121212;
        border: 1px solid #2a2a2a;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .filter-tab:hover {
        color: #fff;
        border-color: #03b200;
    }

    .filter-tab.active {
        background: #03b200;
        color: #111;
        border-color: #03b200;
    }

    .table-container {
        overflow-x: auto;
        background: #121212;
        border: 1px solid #222;
        border-radius: 18px;
        padding: 18px;
    }

    .kal-admin-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1040px;
    }

    .kal-admin-table th,
    .kal-admin-table td {
        text-align: left;
        padding: 14px 16px;
        border-bottom: 1px solid #242424;
        color: #ddd;
        vertical-align: middle;
    }

    .kal-admin-table th {
        color: #999;
        font-size: 13px;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    .kal-admin-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.03);
    }

    .stadium-thumbnail {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 14px;
        border: 1px solid #2f2f2f;
    }

    .no-image {
        width: 70px;
        height: 70px;
        background: #242424;
        border-radius: 14px;
        display: grid;
        place-items: center;
        color: #888;
        font-size: 22px;
    }

    .status-badge {
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-pending {
        background: rgba(255, 152, 0, 0.12);
        color: #ffb74d;
        border: 1px solid rgba(255, 152, 0, 0.25);
    }

    .status-approved {
        background: rgba(3, 178, 0, 0.12);
        color: #a5d6a7;
        border: 1px solid rgba(3, 178, 0, 0.25);
    }

    .status-rejected {
        background: rgba(244, 67, 54, 0.12);
        color: #ef9a9a;
        border: 1px solid rgba(244, 67, 54, 0.25);
    }

    .action-btn {
        padding: 8px 14px;
        border: none;
        border-radius: 999px;
        cursor: pointer;
        font-size: 12px;
        margin: 2px 2px 2px 0;
        transition: transform 0.2s ease, opacity 0.2s ease;
    }

    .action-btn:hover {
        transform: translateY(-1px);
    }

    .view-btn {
        background: #1e88e5;
        color: #fff;
    }

    .approve-btn {
        background: #43a047;
        color: #fff;
    }

    .reject-btn {
        background: #e53935;
        color: #fff;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.75);
        padding: 32px;
    }

    .modal-content {
        background: #101010;
        margin: auto;
        padding: 28px;
        border: 1px solid #222;
        border-radius: 20px;
        width: 100%;
        max-width: 900px;
        max-height: 88vh;
        overflow-y: auto;
        color: #eee;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 20px;
    }

    .modal-header h3 {
        margin: 0;
    }

    .modal-close {
        color: #aaa;
        font-size: 30px;
        cursor: pointer;
    }

    .modal-close:hover {
        color: #fff;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 280px;
        gap: 24px;
    }

    .detail-info {
        display: grid;
        gap: 16px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        gap: 16px;
    }

    .detail-row span:first-child {
        color: #999;
    }

    .image-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 12px;
    }

    .gallery-image {
        width: 100%;
        min-height: 100px;
        object-fit: cover;
        border-radius: 14px;
        border: 1px solid #222;
    }

    .feature-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .feature-pill {
        padding: 8px 12px;
        background: #181818;
        border: 1px solid #2a2a2a;
        border-radius: 999px;
        font-size: 12px;
        color: #ccc;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #bbb;
    }

    .form-group textarea {
        width: 100%;
        padding: 14px;
        background: #141414;
        border: 1px solid #222;
        border-radius: 14px;
        color: #fff;
        font-family: inherit;
        resize: vertical;
        min-height: 120px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .btn-secondary,
    .btn-danger {
        padding: 12px 22px;
        border-radius: 14px;
        border: none;
        cursor: pointer;
    }

    .btn-secondary {
        background: #333;
        color: #fff;
    }

    .btn-danger {
        background: #e53935;
        color: #fff;
    }

    .no-data {
        text-align: center;
        padding: 40px;
        color: #999;
    }

    @media (max-width: 960px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    const apiBase = '<?php echo URLROOT; ?>/admin';

    function viewStadium(stadiumId) {
        fetch(`${apiBase}/stadiumDetails?stadium_id=${stadiumId}`)
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert('Error loading stadium details: ' + data.message);
                    return;
                }

                const stadium = data.stadium;
                const modal = document.getElementById('stadiumModal');
                const details = document.getElementById('stadiumDetails');

                const imageHtml = stadium.images.length > 0 ?
                    stadium.images.map(src => `<img src="${src}" class="gallery-image" alt="${stadium.name}">`).join('') :
                    `<div class="no-image" style="width:100%;height:200px;">No images</div>`;

                const featureHtml = stadium.features.length > 0 ?
                    stadium.features.map(feature => `<span class="feature-pill">${feature}</span>`).join('') :
                    '<span class="feature-pill">No features listed</span>';

                details.innerHTML = `
                <div class="modal-header">
                    <div>
                        <h3>${stadium.name}</h3>
                        <p style="color:#aaa;margin-top:10px;">${stadium.description || 'No description available.'}</p>
                    </div>
                    <span class="modal-close" onclick="closeModal()">&times;</span>
                </div>
                <div class="detail-grid">
                    <div class="detail-info">
                        <div class="detail-row"><span>Approval Status</span><strong>${stadium.approval_status}</strong></div>
                        <div class="detail-row"><span>Category</span><strong>${stadium.category || 'N/A'}</strong></div>
                        <div class="detail-row"><span>Type</span><strong>${stadium.type || 'N/A'}</strong></div>
                        <div class="detail-row"><span>District</span><strong>${stadium.district || 'N/A'}</strong></div>
                        <div class="detail-row"><span>Price</span><strong>LKR ${Number(stadium.price || 0).toLocaleString()}</strong></div>
                        <div class="detail-row"><span>Submitted</span><strong>${stadium.submitted_at ? new Date(stadium.submitted_at).toLocaleDateString() : 'N/A'}</strong></div>
                        <div class="detail-row"><span>Owner</span><strong>${stadium.owner_name || 'N/A'}</strong></div>
                        <div class="detail-row"><span>Owner Email</span><strong>${stadium.owner_email || 'N/A'}</strong></div>
                        <div class="detail-row"><span>Owner Phone</span><strong>${stadium.owner_phone || 'N/A'}</strong></div>
                        <div><strong>Features</strong><div class="feature-list">${featureHtml}</div></div>
                    </div>
                    <div>
                        <h4 style="margin-bottom:12px;color:#fff;">Images</h4>
                        <div class="image-gallery">${imageHtml}</div>
                    </div>
                </div>
            `;

                modal.style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Unable to load stadium details.');
            });
    }

    function approveStadium(stadiumId) {
        if (!confirm('Are you sure you want to approve this stadium? It will become visible to customers.')) {
            return;
        }

        fetch(`${apiBase}/approveStadium`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `stadium_id=${stadiumId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Stadium approved successfully!');
                    location.reload();
                } else {
                    alert('❌ ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to approve stadium');
            });
    }

    function rejectStadium(stadiumId) {
        document.getElementById('rejectStadiumId').value = stadiumId;
        document.getElementById('rejectModal').style.display = 'block';
    }

    function closeRejectModal() {
        const rejectModal = document.getElementById('rejectModal');
        rejectModal.style.display = 'none';
        const rejectForm = document.getElementById('rejectForm');
        if (rejectForm) rejectForm.reset();
    }

    function closeModal() {
        const stadiumModal = document.getElementById('stadiumModal');
        stadiumModal.style.display = 'none';
    }

    window.addEventListener('DOMContentLoaded', () => {
        const rejectForm = document.getElementById('rejectForm');
        if (rejectForm) {
            rejectForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const stadiumId = document.getElementById('rejectStadiumId').value;
                const reason = document.getElementById('rejectReason').value.trim();

                if (!reason) {
                    alert('Please enter a reason for rejection.');
                    return;
                }

                fetch(`${apiBase}/rejectStadium`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `stadium_id=${stadiumId}&reason=${encodeURIComponent(reason)}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('✅ Stadium rejected successfully.');
                            closeRejectModal();
                            location.reload();
                        } else {
                            alert('❌ ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to reject stadium');
                    });
            });
        }

        window.addEventListener('click', function(event) {
            const stadiumModal = document.getElementById('stadiumModal');
            const rejectModal = document.getElementById('rejectModal');
            if (event.target === stadiumModal) {
                closeModal();
            }
            if (event.target === rejectModal) {
                closeRejectModal();
            }
        });
    });
</script>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?> if (confirm('Are you sure you want to approve this stadium? It will become visible to all customers.')) {
fetch('<?php echo URLROOT; ?>/admin/approveStadium', {
method: 'POST',
headers: {
'Content-Type': 'application/x-www-form-urlencoded',
},
body: 'stadium_id=' + stadiumId
})
.then(response => response.json())
.then(data => {
if (data.success) {
alert('✅ Stadium approved successfully! Owner will be notified via email.');
location.reload();
} else {
alert('❌ Error: ' + data.message);
}
})
.catch(error => {
console.error('Error:', error);
alert('Failed to approve stadium');
});
}
}

function rejectStadium(stadiumId) {
document.getElementById('rejectStadiumId').value = stadiumId;
document.getElementById('rejectModal').style.display = 'block';
}

function closeRejectModal() {
document.getElementById('rejectModal').style.display = 'none';
document.getElementById('rejectForm').reset();
}

function closeModal() {
document.getElementById('stadiumModal').style.display = 'none';
}

document.getElementById('rejectForm').addEventListener('submit', function(e) {
e.preventDefault();

const stadiumId = document.getElementById('rejectStadiumId').value;
const reason = document.getElementById('rejectReason').value;

fetch('<?php echo URLROOT; ?>/admin/rejectStadium', {
method: 'POST',
headers: {
'Content-Type': 'application/x-www-form-urlencoded',
},
body: 'stadium_id=' + stadiumId + '&reason=' + encodeURIComponent(reason)
})
.then(response => response.json())
.then(data => {
if (data.success) {
alert('Stadium rejected. Owner will be notified via email.');
closeRejectModal();
location.reload();
} else {
alert('Error: ' + data.message);
}
})
.catch(error => {
console.error('Error:', error);
alert('Failed to reject stadium');
});
});

// Close modal when clicking outside
window.onclick = function(event) {
const stadiumModal = document.getElementById('stadiumModal');
const rejectModal = document.getElementById('rejectModal');
if (event.target === stadiumModal) {
closeModal();
}
if (event.target === rejectModal) {
closeRejectModal();
}
}
</script>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>