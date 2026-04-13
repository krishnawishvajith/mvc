<?php require APPROOT.'/views/stadium_owner/inc/header.php'; ?>

<div class="main-content profile-page">
    <div class="dashboard-header">
        <h1>My Profile</h1>
        <div class="header-actions">
            <button class="btn-edit-profile" onclick="toggleEditMode()">✏️ Edit Profile</button>
        </div>
    </div>

    <!-- Profile Overview -->
    <div class="profile-container">
        <div class="profile-main">
            <!-- Basic Profile Information -->
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <div class="avatar-circle">
                            <?php echo substr($data['profile_data']['owner_name'] ?? 'Owner', 0, 1); ?>
                        </div>
                        <button class="avatar-edit-btn" onclick="changeAvatar()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2c5.514 0 10 4.486 10 10s-4.486 10-10 10-10-4.486-10-10 4.486-10 10-10zm0-2c-6.627 0-12 5.373-12 12 0 6.628 5.373 12 12 12s12-5.372 12-12c0-6.627-5.373-12-12-12zm6 13h-6v6h-2v-6h-6v-2h6v-6h2v6h6v2z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="profile-info">
                        <h2><?php echo $data['profile_data']['owner_name'] ?? 'Stadium Owner'; ?></h2>
                        <p class="business-name"><?php echo $data['profile_data']['business_name'] ?? 'Sports Complex'; ?></p>
                        <div class="profile-badges">
                            <span class="package-badge <?php echo strtolower($data['profile_data']['package_type'] ?? 'standard'); ?>">
                                <?php echo $data['profile_data']['package_type'] ?? 'Standard'; ?> Package
                            </span>
                            <span class="status-badge active">Active</span>
                        </div>
                    </div>
                </div>

                <div class="profile-stats">
                    <div class="stat-item">
                        <div class="stat-value"><?php echo $data['profile_data']['total_properties'] ?? 3; ?></div>
                        <div class="stat-label">Properties</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">LKR <?php echo number_format($data['profile_data']['total_revenue'] ?? 278000); ?></div>
                        <div class="stat-label">Total Revenue</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?php echo $data['profile_data']['rating'] ?? '4.6'; ?> ⭐</div>
                        <div class="stat-label">Rating</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?php echo $data['profile_data']['member_since'] ?? 'January 2024'; ?></div>
                        <div class="stat-label">Member Since</div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Contact Information</h3>
                    <div class="edit-indicator" id="editMode" style="display: none;">
                        <span class="edit-badge">Edit Mode</span>
                    </div>
                </div>
                
                <form class="profile-form" id="profileForm" method="POST" action="<?php echo URLROOT; ?>/stadium_owner/profile">
                    <?php if(isset($data['error']) && !empty($data['error'])): ?>
                        <div class="error-message">
                            <?php echo $data['error']; ?>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($data['success']) && !empty($data['success'])): ?>
                        <div class="success-message">
                            <?php echo $data['success']; ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="owner_name">Owner Name</label>
                            <input type="text" 
                                   id="owner_name" 
                                   name="owner_name" 
                                   value="<?php echo $data['profile_data']['owner_name'] ?? ''; ?>"
                                   class="form-control profile-input"
                                   readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="business_name">Business Name</label>
                            <input type="text" 
                                   id="business_name" 
                                   name="business_name" 
                                   value="<?php echo $data['profile_data']['business_name'] ?? ''; ?>"
                                   class="form-control profile-input"
                                   readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo $data['profile_data']['email'] ?? ''; ?>"
                                   class="form-control profile-input"
                                   readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="<?php echo $data['profile_data']['phone'] ?? ''; ?>"
                                   class="form-control profile-input"
                                   readonly>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="address">Business Address</label>
                            <textarea id="address" 
                                      name="address" 
                                      rows="3"
                                      class="form-control profile-input"
                                      readonly><?php echo $data['profile_data']['address'] ?? ''; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="business_registration">Business Registration</label>
                            <input type="text" 
                                   id="business_registration" 
                                   name="business_registration" 
                                   value="<?php echo $data['profile_data']['business_registration'] ?? ''; ?>"
                                   class="form-control profile-input"
                                   readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="website">Website (Optional)</label>
                            <input type="url" 
                                   id="website" 
                                   name="website" 
                                   value="<?php echo $data['profile_data']['website'] ?? ''; ?>"
                                   placeholder="https://yourwebsite.com"
                                   class="form-control profile-input"
                                   readonly>
                        </div>
                    </div>

                    <div class="form-actions" id="formActions" style="display: none;">
                        <button type="button" class="btn-cancel" onclick="cancelEdit()">Cancel</button>
                        <button type="submit" class="btn-save">Save Changes</button>
                    </div>
                </form>
            </div>

            <!-- Business Information -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Business Information</h3>
                </div>
                <div class="business-info-grid">
                    <div class="info-item">
                        <div class="info-label">Package Type</div>
                        <div class="info-value">
                            <span class="package-badge <?php echo strtolower($data['profile_data']['package_type'] ?? 'standard'); ?>">
                                <?php echo $data['profile_data']['package_type'] ?? 'Standard'; ?>
                            </span>
                            <a href="<?php echo URLROOT; ?>/pricing" class="upgrade-link">Upgrade</a>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Commission Rate</div>
                        <div class="info-value">12%</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Properties Limit</div>
                        <div class="info-value">6 properties</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Support Level</div>
                        <div class="info-value">Email & Phone Support</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Account Status</div>
                        <div class="info-value">
                            <span class="status-badge active">Active</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Next Billing</div>
                        <div class="info-value">N/A (Commission Based)</div>
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Security Settings</h3>
                </div>
                <div class="security-settings">
                    <div class="security-item">
                        <div class="security-info">
                            <h4>Password</h4>
                            <p>Last changed 3 months ago</p>
                        </div>
                        <button class="btn-change-password" onclick="changePassword()">Change Password</button>
                    </div>
                    <div class="security-item">
                        <div class="security-info">
                            <h4>Two-Factor Authentication</h4>
                            <p>Add an extra layer of security to your account</p>
                        </div>
                        <button class="btn-enable-2fa" onclick="enable2FA()">Enable 2FA</button>
                    </div>
                    <div class="security-item">
                        <div class="security-info">
                            <h4>Login Notifications</h4>
                            <p>Get notified when someone logs into your account</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Notification Preferences -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Notification Preferences</h3>
                </div>
                <div class="notification-preferences">
                    <div class="notification-item">
                        <div class="notification-info">
                            <h4>New Bookings</h4>
                            <p>Receive notifications for new booking requests</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="notification-item">
                        <div class="notification-info">
                            <h4>Payment Notifications</h4>
                            <p>Get notified when payments are processed</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="notification-item">
                        <div class="notification-info">
                            <h4>Promotional Emails</h4>
                            <p>Receive marketing emails and promotions</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="notification-item">
                        <div class="notification-info">
                            <h4>Monthly Reports</h4>
                            <p>Get monthly performance and revenue reports</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Sidebar -->
        <div class="profile-sidebar">
            <!-- Quick Actions -->
            <div class="sidebar-card">
                <h4>Quick Actions</h4>
                <div class="quick-actions">
                    <a href="<?php echo URLROOT; ?>/stadium_owner/add_property" class="action-btn">
                        <span class="action-icon">🏟️</span>
                        <span class="action-text">Add New Property</span>
                    </a>
                    <a href="<?php echo URLROOT; ?>/stadium_owner/bookings" class="action-btn">
                        <span class="action-icon">📅</span>
                        <span class="action-text">View Bookings</span>
                    </a>
                    <a href="<?php echo URLROOT; ?>/stadium_owner/revenue" class="action-btn">
                        <span class="action-icon">💰</span>
                        <span class="action-text">Revenue Report</span>
                    </a>
                    <a href="<?php echo URLROOT; ?>/stadium_owner/messages" class="action-btn">
                        <span class="action-icon">💬</span>
                        <span class="action-text">Messages</span>
                    </a>
                </div>
            </div>

            <!-- Account Summary -->
            <div class="sidebar-card">
                <h4>Account Summary</h4>
                <div class="account-summary">
                    <div class="summary-item">
                        <span class="summary-label">Total Properties:</span>
                        <span class="summary-value"><?php echo $data['profile_data']['total_properties'] ?? 3; ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Active Bookings:</span>
                        <span class="summary-value">8</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">This Month Revenue:</span>
                        <span class="summary-value">LKR 45,000</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Customer Rating:</span>
                        <span class="summary-value"><?php echo $data['profile_data']['rating'] ?? '4.6'; ?> ⭐</span>
                    </div>
                </div>
            </div>

            <!-- Support & Help -->
            <div class="sidebar-card">
                <h4>Support & Help</h4>
                <div class="support-links">
                    <a href="<?php echo URLROOT; ?>/faq" class="support-link">
                        <span class="support-icon">❓</span>
                        <span class="support-text">FAQ</span>
                    </a>
                    <a href="<?php echo URLROOT; ?>/contact" class="support-link">
                        <span class="support-icon">📞</span>
                        <span class="support-text">Contact Support</span>
                    </a>
                    <a href="<?php echo URLROOT; ?>/help" class="support-link">
                        <span class="support-icon">📖</span>
                        <span class="support-text">Help Center</span>
                    </a>
                    <a href="<?php echo URLROOT; ?>/terms" class="support-link">
                        <span class="support-icon">📄</span>
                        <span class="support-text">Terms & Conditions</span>
                    </a>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="sidebar-card danger-zone">
                <h4>Danger Zone</h4>
                <div class="danger-actions">
                    <button class="btn-danger" onclick="deactivateAccount()">
                        Deactivate Account
                    </button>
                    <button class="btn-danger" onclick="deleteAccount()">
                        Delete Account
                    </button>
                </div>
                <p class="danger-warning">
                    These actions are irreversible. Please be careful.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div id="passwordModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Change Password</h3>
            <span class="close" onclick="closePasswordModal()">&times;</span>
        </div>
        <form class="password-form">
            <div class="form-group">
                <label>Current Password</label>
                <input type="password" name="current_password" required class="form-control">
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" required class="form-control" minlength="6">
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" required class="form-control" minlength="6">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closePasswordModal()">Cancel</button>
                <button type="submit" class="btn-change">Change Password</button>
            </div>
        </form>
    </div>
</div>

<script>
let isEditMode = false;

function toggleEditMode() {
    isEditMode = !isEditMode;
    const inputs = document.querySelectorAll('.profile-input');
    const editBtn = document.querySelector('.btn-edit-profile');
    const formActions = document.getElementById('formActions');
    const editIndicator = document.getElementById('editMode');
    
    if (isEditMode) {
        inputs.forEach(input => {
            input.removeAttribute('readonly');
            input.classList.add('editing');
        });
        editBtn.textContent = '❌ Cancel Edit';
        formActions.style.display = 'flex';
        editIndicator.style.display = 'block';
    } else {
        inputs.forEach(input => {
            input.setAttribute('readonly', true);
            input.classList.remove('editing');
        });
        editBtn.textContent = '✏️ Edit Profile';
        formActions.style.display = 'none';
        editIndicator.style.display = 'none';
    }
}

function cancelEdit() {
    // Reset form to original values
    location.reload();
}

function changeAvatar() {
    alert('Avatar change functionality will be implemented');
}

function changePassword() {
    document.getElementById('passwordModal').style.display = 'block';
}

function closePasswordModal() {
    document.getElementById('passwordModal').style.display = 'none';
}

function enable2FA() {
    alert('Two-Factor Authentication setup will be implemented');
}

function deactivateAccount() {
    if (confirm('Are you sure you want to deactivate your account? You can reactivate it later.')) {
        alert('Account deactivation functionality will be implemented');
    }
}

function deleteAccount() {
    if (confirm('Are you sure you want to permanently delete your account? This action cannot be undone.')) {
        if (confirm('This will permanently delete all your data. Are you absolutely sure?')) {
            alert('Account deletion functionality will be implemented');
        }
    }
}

// Handle form submission
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!isEditMode) {
        return;
    }
    
    // Validate form
    const requiredFields = ['owner_name', 'business_name', 'email', 'phone'];
    let isValid = true;
    
    requiredFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (!field.value.trim()) {
            field.style.borderColor = '#dc3545';
            isValid = false;
        } else {
            field.style.borderColor = '';
        }
    });
    
    if (!isValid) {
        alert('Please fill in all required fields.');
        return;
    }
    
    // Submit form
    this.submit();
});

// Handle password change form
document.querySelector('.password-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const newPassword = this.new_password.value;
    const confirmPassword = this.confirm_password.value;
    
    if (newPassword !== confirmPassword) {
        alert('New passwords do not match.');
        return;
    }
    
    alert('Password changed successfully!');
    closePasswordModal();
});

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('passwordModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<style>
.profile-container {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 30px;
}

.profile-main {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.profile-card {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 30px;
}

.profile-avatar {
    position: relative;
}

.avatar-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    font-weight: 700;
}

.avatar-edit-btn {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.avatar-edit-btn:hover {
    background: #0056b3;
    transform: scale(1.1);
}

.profile-info h2 {
    margin: 0 0 4px 0;
    color: #212529;
    font-size: 24px;
}

.business-name {
    margin: 0 0 12px 0;
    color: #6c757d;
    font-size: 16px;
}

.profile-badges {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.package-badge {
    padding: 6px 12px;
    border-radius: 16px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
}

.package-badge.basic {
    background: #e9ecef;
    color: #495057;
}

.package-badge.standard {
    background: #d4edda;
    color: #155724;
}

.package-badge.gold {
    background: #fff3cd;
    color: #856404;
}

.status-badge.active {
    background: #d4edda;
    color: #155724;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
}

.profile-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
}

.stat-item {
    text-align: center;
}

.stat-value {
    font-size: 18px;
    font-weight: 700;
    color: #212529;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 12px;
    color: #6c757d;
    text-transform: uppercase;
}

.edit-indicator {
    background: #fff3cd;
    color: #856404;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    font-weight: 500;
    margin-bottom: 8px;
    color: #495057;
}

.form-control {
    padding: 12px 16px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #007bff;
}

.form-control.editing {
    border-color: #28a745;
    background: #f8fff8;
}

.form-control[readonly] {
    background: #f8f9fa;
    color: #6c757d;
}

.form-actions {
    display: flex;
    gap: 16px;
    justify-content: flex-end;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
    margin-top: 20px;
}

.btn-cancel {
    background: #6c757d;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-cancel:hover {
    background: #5a6268;
}

.btn-save {
    background: #28a745;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-save:hover {
    background: #218838;
}

.business-info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #e9ecef;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 500;
    color: #495057;
}

.info-value {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #212529;
}

.upgrade-link {
    color: #007bff;
    text-decoration: none;
    font-size: 12px;
    font-weight: 500;
}

.upgrade-link:hover {
    text-decoration: underline;
}

.security-settings,
.notification-preferences {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.security-item,
.notification-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background: #f8f9fa;
    border-radius: 8px;
}

.security-info h4,
.notification-info h4 {
    margin: 0 0 4px 0;
    color: #212529;
    font-size: 16px;
}

.security-info p,
.notification-info p {
    margin: 0;
    color: #6c757d;
    font-size: 14px;
}

.btn-change-password,
.btn-enable-2fa {
    background: #007bff;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    border: none;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-change-password:hover,
.btn-enable-2fa:hover {
    background: #0056b3;
}

.toggle-switch {
    position: relative;
    width: 44px;
    height: 24px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: #28a745;
}

input:checked + .toggle-slider:before {
    transform: translateX(20px);
}

.profile-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.sidebar-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.sidebar-card h4 {
    margin: 0 0 16px 0;
    color: #212529;
    font-size: 16px;
}

.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
    text-decoration: none;
    color: #495057;
    transition: all 0.3s ease;
}

.action-btn:hover {
    background: #e9ecef;
    color: #212529;
}

.action-icon {
    font-size: 18px;
}

.action-text {
    font-weight: 500;
}

.account-summary {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #f8f9fa;
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-label {
    color: #6c757d;
    font-size: 13px;
}

.summary-value {
    font-weight: 600;
    color: #212529;
    font-size: 13px;
}

.support-links {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.support-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 0;
    text-decoration: none;
    color: #495057;
    transition: color 0.3s ease;
}

.support-link:hover {
    color: #007bff;
}

.support-icon {
    font-size: 16px;
}

.support-text {
    font-size: 14px;
}

.danger-zone {
    border: 1px solid #dc3545;
}

.danger-zone h4 {
    color: #dc3545;
}

.danger-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 12px;
}

.btn-danger {
    background: #dc3545;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    border: none;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-danger:hover {
    background: #c82333;
}

.danger-warning {
    margin: 0;
    font-size: 12px;
    color: #6c757d;
    font-style: italic;
}

.btn-edit-profile {
    background: #007bff;
    color: white;
    padding: 10px 16px;
    border-radius: 8px;
    border: none;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-edit-profile:hover {
    background: #0056b3;
}

.password-form {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.btn-change {
    background: #28a745;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-change:hover {
    background: #218838;
}

@media (max-width: 768px) {
    .profile-container {
        grid-template-columns: 1fr;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .profile-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .business-info-grid {
        grid-template-columns: 1fr;
    }
    
    .profile-header {
        flex-direction: column;
        text-align: center;
    }
}

@media (max-width: 576px) {
    .profile-stats {
        grid-template-columns: 1fr;
    }
    
    .security-item,
    .notification-item {
        flex-direction: column;
        gap: 12px;
        text-align: center;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>

<?php require APPROOT.'/views/stadium_owner/inc/footer.php'; ?>