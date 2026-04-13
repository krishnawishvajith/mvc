<?php

require APPROOT.'/views/admin/inc/header.php'; ?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>Newsletter Management</h1>
        <div class="header-actions">
            <a href="<?php echo URLROOT; ?>/admin/newsletter/compose" class="btn-compose-newsletter">✍️ Compose Newsletter</a>
            <button class="btn-import-subscribers" onclick="openImportModal()">📥 Import Subscribers</button>
        </div>
    </div>

    <!-- Newsletter Stats -->
    <div class="newsletter-stats">
        <div class="stat-item">
            <div class="stat-icon">👥</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo number_format($data['total_subscribers']); ?></span>
                <span class="stat-label">Total Subscribers</span>
            </div>
            <div class="stat-change positive">+<?php echo $data['subscriber_growth']['this_month']; ?> this month</div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">✅</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo number_format($data['active_subscribers']); ?></span>
                <span class="stat-label">Active Subscribers</span>
            </div>
            <div class="stat-change"><?php echo round(($data['active_subscribers']/$data['total_subscribers'])*100, 1); ?>% active rate</div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">📧</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo $data['newsletters_sent']; ?></span>
                <span class="stat-label">Newsletters Sent</span>
            </div>
            <div class="stat-change">This month</div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">📈</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo $data['subscriber_growth']['growth_percentage']; ?>%</span>
                <span class="stat-label">Growth Rate</span>
            </div>
            <div class="stat-change positive">Month over month</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-section">
        <div class="quick-action-card">
            <div class="action-icon">✍️</div>
            <div class="action-content">
                <h3>Compose Newsletter</h3>
                <p>Create and send a new newsletter to your subscribers</p>
                <a href="<?php echo URLROOT; ?>/admin/newsletter/compose" class="action-button">Start Writing</a>
            </div>
        </div>
        
        <div class="quick-action-card">
            <div class="action-icon">👥</div>
            <div class="action-content">
                <h3>Manage Subscribers</h3>
                <p>View and manage your newsletter subscriber list</p>
                <a href="<?php echo URLROOT; ?>/admin/newsletter/subscribers" class="action-button">View Subscribers</a>
            </div>
        </div>
        
        <div class="quick-action-card">
            <div class="action-icon">📊</div>
            <div class="action-content">
                <h3>View Analytics</h3>
                <p>Check open rates, clicks, and engagement metrics</p>
                <a href="<?php echo URLROOT; ?>/admin/newsletter/analytics" class="action-button">View Analytics</a>
            </div>
        </div>
        
        <div class="quick-action-card">
            <div class="action-icon">📋</div>
            <div class="action-content">
                <h3>Email Templates</h3>
                <p>Create and manage email templates for newsletters</p>
                <a href="<?php echo URLROOT; ?>/admin/newsletter/templates" class="action-button">Manage Templates</a>
            </div>
        </div>
    </div>

    <!-- Recent Newsletters -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Recent Newsletters</h3>
            <a href="<?php echo URLROOT; ?>/admin/newsletter/compose" class="btn-add-new">+ New Newsletter</a>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Recipients</th>
                        <th>Open Rate</th>
                        <th>Click Rate</th>
                        <th>Sent Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['recent_newsletters'] as $newsletter): ?>
                    <tr>
                        <td>
                            <div class="newsletter-subject">
                                <strong><?php echo $newsletter['subject']; ?></strong>
                            </div>
                        </td>
                        <td><?php echo number_format($newsletter['recipients']); ?></td>
                        <td>
                            <div class="metric-value">
                                <span class="percentage"><?php echo $newsletter['open_rate']; ?>%</span>
                                <div class="metric-bar">
                                    <div class="metric-fill" style="width: <?php echo $newsletter['open_rate']; ?>%"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="metric-value">
                                <span class="percentage"><?php echo $newsletter['click_rate']; ?>%</span>
                                <div class="metric-bar">
                                    <div class="metric-fill" style="width: <?php echo $newsletter['click_rate']; ?>%"></div>
                                </div>
                            </div>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($newsletter['sent_date'])); ?></td>
                        <td>
                            <span class="status-badge sent"><?php echo $newsletter['status']; ?></span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action-sm btn-view" onclick="viewNewsletter(<?php echo $newsletter['id']; ?>)">View</button>
                                <button class="btn-action-sm btn-duplicate" onclick="duplicateNewsletter(<?php echo $newsletter['id']; ?>)">Duplicate</button>
                                <button class="btn-action-sm btn-analytics" onclick="viewAnalytics(<?php echo $newsletter['id']; ?>)">Analytics</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Newsletter Performance Overview -->
    <div class="dashboard-grid">
        <!-- Subscriber Growth Chart -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Subscriber Growth</h3>
            </div>
            <div class="growth-chart">
                <div class="growth-summary">
                    <div class="growth-number">+<?php echo $data['subscriber_growth']['this_month']; ?></div>
                    <div class="growth-label">New subscribers this month</div>
                    <div class="growth-percentage positive">+<?php echo $data['subscriber_growth']['growth_percentage']; ?>% from last month</div>
                </div>
                <div class="chart-placeholder">
                    <div class="chart-bar" style="height: 20%"></div>
                    <div class="chart-bar" style="height: 35%"></div>
                    <div class="chart-bar" style="height: 45%"></div>
                    <div class="chart-bar" style="height: 60%"></div>
                    <div class="chart-bar" style="height: 80%"></div>
                    <div class="chart-bar" style="height: 100%"></div>
                </div>
            </div>
        </div>

        <!-- Top Content Categories -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Popular Content Categories</h3>
            </div>
            <div class="category-list">
                <?php foreach($data['top_categories'] as $category): ?>
                <div class="category-item">
                    <div class="category-info">
                        <span class="category-name"><?php echo $category['name']; ?></span>
                        <span class="category-percentage"><?php echo $category['percentage']; ?>%</span>
                    </div>
                    <div class="category-bar">
                        <div class="category-fill" style="width: <?php echo $category['percentage']; ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Import Subscribers Modal -->
<div id="importModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Import Subscribers</h3>
            <span class="close" onclick="closeImportModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="import-section">
                <h4>Upload CSV File</h4>
                <div class="file-upload-area">
                    <input type="file" id="csvFile" accept=".csv" onchange="handleFileSelect(this)">
                    <div class="upload-text">
                        <div class="upload-icon">📄</div>
                        <p>Click to upload or drag and drop your CSV file</p>
                        <small>File should contain: email, name, interests (optional)</small>
                    </div>
                </div>
            </div>
            
            <div class="import-options">
                <h4>Import Options</h4>
                <label class="checkbox-label">
                    <input type="checkbox" checked>
                    <span>Skip duplicate emails</span>
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" checked>
                    <span>Send welcome email to new subscribers</span>
                </label>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closeImportModal()">Cancel</button>
            <button class="btn-import" onclick="processImport()">Import Subscribers</button>
        </div>
    </div>
</div>

<script>
function viewNewsletter(id) {
    alert(`Viewing newsletter details for ID: ${id}`);
    // Here you would open a modal or redirect to view page
}

function duplicateNewsletter(id) {
    if(confirm('Create a copy of this newsletter?')) {
        alert(`Newsletter ${id} duplicated successfully!`);
        // Here you would make an AJAX call to duplicate
    }
}

function viewAnalytics(id) {
    window.location.href = `<?php echo URLROOT; ?>/admin/newsletter/analytics?id=${id}`;
}

function openImportModal() {
    document.getElementById('importModal').style.display = 'block';
}

function closeImportModal() {
    document.getElementById('importModal').style.display = 'none';
}

function handleFileSelect(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const uploadText = input.parentElement.querySelector('.upload-text p');
        uploadText.textContent = `Selected: ${file.name}`;
    }
}

function processImport() {
    const fileInput = document.getElementById('csvFile');
    if (!fileInput.files || !fileInput.files[0]) {
        alert('Please select a CSV file to import.');
        return;
    }
    
    alert('Import functionality will be implemented. File selected: ' + fileInput.files[0].name);
    closeImportModal();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('importModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<style>
.newsletter-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-item {
    background: #000000ec;
    padding: 24px;
    border-radius: 12px;
    position: relative;
    overflow: hidden;
}

.stat-icon {
    font-size: 32px;
    margin-bottom: 15px;
}

.stat-details {
    margin-bottom: 10px;
}

.stat-number {
    display: block;
    font-size: 28px;
    font-weight: bold;
    color: #ffffff;
    margin-bottom: 5px;
}

.stat-label {
    color: #ffffff;
    font-size: 14px;
}

.stat-change {
    font-size: 12px;
    color: #666;
    padding: 4px 8px;
    background: #f8f9fa;
    border-radius: 12px;
    display: inline-block;
}

.stat-change.positive {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.quick-actions-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.quick-action-card {
    background: #000000ec;
    padding: 24px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: transform 0.2s ease;
}

.quick-action-card:hover {
    transform: translateY(-2px);
}

.action-icon {
    font-size: 40px;
    width: 60px;
    height: 60px;
    background: rgba(3, 178, 0, 0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-content h3 {
    margin: 0 0 8px 0;
    color: #ffffff;
    font-size: 18px;
}

.action-content p {
    margin: 0 0 15px 0;
    color: #ffffff;
    font-size: 14px;
}

.action-button {
    padding: 8px 16px;
    background: #03B200;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
}

.action-button:hover {
    background: #029800;
    text-decoration: none;
}

.dashboard-card {
    background: #000000ec;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #333;
}

.card-header h3 {
    color: #ffffff;
    margin: 0;
    font-size: 20px;
}

.btn-add-new {
    padding: 8px 16px;
    background: #03B200;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
}

.btn-add-new:hover {
    background: #029800;
    text-decoration: none;
}

.newsletter-subject strong {
    color: #ffffff;
    font-size: 14px;
}

.metric-value {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.percentage {
    font-weight: 600;
    color: #ffffff;
    font-size: 14px;
}

.metric-bar {
    width: 60px;
    height: 4px;
    background: #333;
    border-radius: 2px;
    overflow: hidden;
}

.metric-fill {
    height: 100%;
    background: #03B200;
    border-radius: 2px;
}

.status-badge.sent {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.growth-chart {
    text-align: center;
}

.growth-summary {
    margin-bottom: 20px;
}

.growth-number {
    font-size: 36px;
    font-weight: bold;
    color: #03B200;
    margin-bottom: 8px;
}

.growth-label {
    color: #ffffff;
    font-size: 16px;
    margin-bottom: 8px;
}

.growth-percentage {
    font-size: 14px;
    padding: 4px 8px;
    border-radius: 12px;
    display: inline-block;
}

.growth-percentage.positive {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.chart-placeholder {
    display: flex;
    align-items: end;
    justify-content: space-between;
    height: 100px;
    gap: 8px;
}

.chart-bar {
    flex: 1;
    background: linear-gradient(to top, #03B200, #29d128);
    border-radius: 4px 4px 0 0;
    min-height: 10px;
}

.category-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.category-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.category-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.category-name {
    color: #ffffff;
    font-size: 14px;
    font-weight: 500;
}

.category-percentage {
    color: #03B200;
    font-weight: 600;
    font-size: 14px;
}

.category-bar {
    height: 6px;
    background: #333;
    border-radius: 3px;
    overflow: hidden;
}

.category-fill {
    height: 100%;
    background: linear-gradient(90deg, #03B200, #29d128);
    border-radius: 3px;
}

.btn-compose-newsletter, .btn-import-subscribers {
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    margin-left: 10px;
}

.btn-compose-newsletter {
    background: #03B200;
    color: white;
}

.btn-compose-newsletter:hover {
    background: #029800;
    transform: translateY(-2px);
    text-decoration: none;
}

.btn-import-subscribers {
    background: #17a2b8;
    color: white;
}

.btn-import-subscribers:hover {
    background: #138496;
    transform: translateY(-2px);
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: #000000ec;
    margin: 5% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #333;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: #ffffff;
    font-size: 20px;
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s;
}

.close:hover {
    color: #ffffff;
}

.modal-body {
    padding: 20px;
}

.modal-actions {
    padding: 20px;
    border-top: 1px solid #333;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.import-section {
    margin-bottom: 30px;
}

.import-section h4 {
    color: #ffffff;
    margin: 0 0 15px 0;
    font-size: 16px;
}

.file-upload-area {
    position: relative;
    border: 2px dashed #666;
    border-radius: 8px;
    padding: 40px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.file-upload-area:hover {
    border-color: #03B200;
    background: rgba(3, 178, 0, 0.05);
}

.file-upload-area input[type="file"] {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.upload-text {
    pointer-events: none;
}

.upload-icon {
    font-size: 48px;
    margin-bottom: 15px;
}

.upload-text p {
    color: #ffffff;
    font-size: 16px;
    margin: 0 0 8px 0;
}

.upload-text small {
    color: #666;
    font-size: 12px;
}

.import-options h4 {
    color: #ffffff;
    margin: 0 0 15px 0;
    font-size: 16px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #ffffff;
    margin-bottom: 10px;
    cursor: pointer;
    font-size: 14px;
}

.checkbox-label input[type="checkbox"] {
    width: 16px;
    height: 16px;
}

.btn-cancel, .btn-import {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
}

.btn-cancel {
    background: #6c757d;
    color: white;
}

.btn-cancel:hover {
    background: #5a6268;
}

.btn-import {
    background: #03B200;
    color: white;
}

.btn-import:hover {
    background: #029800;
}

@media (max-width: 1024px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .quick-actions-section {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 768px) {
    .newsletter-stats {
        grid-template-columns: 1fr;
    }
    
    .quick-actions-section {
        grid-template-columns: 1fr;
    }
    
    .dashboard-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    
    .header-actions {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .btn-compose-newsletter, .btn-import-subscribers {
        width: 100%;
        margin-left: 0;
    }
}
</style>

<?php require APPROOT.'/views/admin/inc/footer.php'; ?>