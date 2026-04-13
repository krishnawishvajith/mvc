<?php require APPROOT.'/views/admin/inc/header.php'; ?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>Edit User</h1>
        <div class="header-actions">
            <a href="<?php echo URLROOT; ?>/admin/users" class="btn-back">← Back to Users</a>
        </div>
    </div>

    <!-- Error/Success Messages -->
    <?php if(!empty($data['error'])): ?>
        <div class="alert alert-error" style="background: rgba(255, 0, 0, 0.1); border: 1px solid #ff4444; color: #ff6666; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
            <?php echo $data['error']; ?>
        </div>
    <?php endif; ?>

    <?php if(!empty($data['success'])): ?>
        <div class="alert alert-success" style="background: rgba(0, 255, 0, 0.1); border: 1px solid #28a745; color: #28a745; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
            <?php echo $data['success']; ?>
        </div>
    <?php endif; ?>

    <!-- Edit User Form -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Edit User Account</h3>
            <p>Update user information below</p>
        </div>
        
        <form method="POST" action="<?php echo URLROOT; ?>/admin/edit_user/<?php echo $data['user']->id; ?>" class="user-form">
            
            <!-- Basic Information -->
            <div class="form-section">
                <h4>Basic Information</h4>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name *</label>
                        <input type="text" 
                               id="first_name" 
                               name="first_name" 
                               class="form-control"
                               value="<?php echo htmlspecialchars($data['form_data']['first_name'] ?? $data['user']->first_name ?? ''); ?>"
                               placeholder="Enter first name"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <input type="text" 
                               id="last_name" 
                               name="last_name" 
                               class="form-control"
                               value="<?php echo htmlspecialchars($data['form_data']['last_name'] ?? $data['user']->last_name ?? ''); ?>"
                               placeholder="Enter last name"
                               required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-control"
                               value="<?php echo htmlspecialchars($data['form_data']['email'] ?? $data['user']->email ?? ''); ?>"
                               placeholder="Enter email address"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               class="form-control"
                               value="<?php echo htmlspecialchars($data['form_data']['phone'] ?? $data['user']->phone ?? ''); ?>"
                               placeholder="Enter phone number"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="role">User Role</label>
                    <select id="role" name="role" class="form-control" disabled>
                        <option value="customer" <?php echo ($data['user']->role == 'customer') ? 'selected' : ''; ?>>
                            Customer (Sports Player)
                        </option>
                        <option value="stadium_owner" <?php echo ($data['user']->role == 'stadium_owner') ? 'selected' : ''; ?>>
                            Stadium Owner
                        </option>
                        <option value="coach" <?php echo ($data['user']->role == 'coach') ? 'selected' : ''; ?>>
                            Sports Coach
                        </option>
                        <option value="rental_owner" <?php echo ($data['user']->role == 'rental_owner') ? 'selected' : ''; ?>>
                            Equipment Rental Owner
                        </option>
                    </select>
                    <small class="form-help">User role cannot be changed after account creation</small>
                </div>

                <div class="form-group">
                    <label for="status">Account Status *</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="active" <?php echo ($data['form_data']['status'] ?? $data['user']->status ?? '') == 'active' ? 'selected' : ''; ?>>
                            Active
                        </option>
                        <option value="inactive" <?php echo ($data['form_data']['status'] ?? $data['user']->status ?? '') == 'inactive' ? 'selected' : ''; ?>>
                            Inactive
                        </option>
                        <option value="suspended" <?php echo ($data['form_data']['status'] ?? $data['user']->status ?? '') == 'suspended' ? 'selected' : ''; ?>>
                            Suspended
                        </option>
                    </select>
                </div>
            </div>

            <!-- Account Information -->
            <div class="form-section">
                <h4>Account Information</h4>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Account Created</label>
                        <input type="text" 
                               class="form-control" 
                               value="<?php echo date('F j, Y', strtotime($data['user']->created_at)); ?>" 
                               readonly>
                    </div>

                    <div class="form-group">
                        <label>Last Updated</label>
                        <input type="text" 
                               class="form-control" 
                               value="<?php echo $data['user']->updated_at ? date('F j, Y', strtotime($data['user']->updated_at)) : 'Never'; ?>" 
                               readonly>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>User ID</label>
                        <input type="text" 
                               class="form-control" 
                               value="#<?php echo $data['user']->id; ?>" 
                               readonly>
                    </div>

                    <div class="form-group">
                        <label>Current Role</label>
                        <input type="text" 
                               class="form-control" 
                               value="<?php 
                                   switch($data['user']->role) {
                                       case 'stadium_owner': echo 'Stadium Owner'; break;
                                       case 'rental_owner': echo 'Rental Owner'; break;
                                       default: echo ucfirst($data['user']->role); break;
                                   }
                               ?>" 
                               readonly>
                    </div>
                </div>
            </div>

            <!-- Password Reset Section -->
            <div class="form-section">
                <h4>Password Management</h4>
                <p>To reset the user's password, check the box below and enter a new password.</p>
                
                <div class="form-group">
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="reset_password" name="reset_password">
                            <span class="checkmark"></span>
                            Reset user password
                        </label>
                    </div>
                </div>

                <div id="password_fields" style="display: none;">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" 
                                   id="new_password" 
                                   name="new_password" 
                                   class="form-control"
                                   placeholder="Enter new password (min 6 characters)">
                            <small class="form-help">Leave blank to keep current password</small>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   class="form-control"
                                   placeholder="Confirm new password">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" 
                        class="btn-cancel" 
                        onclick="window.location.href='<?php echo URLROOT; ?>/admin/users'">
                    Cancel
                </button>
                <button type="submit" class="btn-save">Update User</button>
            </div>
        </form>
    </div>
</div>

<script>
// Show/hide password fields based on checkbox
document.getElementById('reset_password').addEventListener('change', function() {
    const passwordFields = document.getElementById('password_fields');
    const newPasswordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    if (this.checked) {
        passwordFields.style.display = 'block';
        newPasswordInput.required = true;
        confirmPasswordInput.required = true;
    } else {
        passwordFields.style.display = 'none';
        newPasswordInput.required = false;
        confirmPasswordInput.required = false;
        newPasswordInput.value = '';
        confirmPasswordInput.value = '';
    }
});

// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = this.value;
    
    if (newPassword !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});
</script>

<style>
.dashboard-card {
    background: #000000ec;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 24px;
    margin-bottom: 24px;
}

.card-header h3 {
    margin: 0 0 8px 0;
    color: #ffffff;
    font-size: 20px;
}

.card-header p {
    margin: 0;
    color: #ffffff;
    font-size: 14px;
}

.form-section {
    margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1px solid #eee;
}

.form-section:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
}

.form-section h4 {
    margin: 0 0 20px 0;
    color: #ffffff;
    font-size: 16px;
    font-weight: 600;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    color: #ffffff;
    font-weight: 500;
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 2px solid #e1e5e9;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.2s;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: #007bff;
}

.form-control:disabled, .form-control[readonly] {
    background-color: #f8f9fa;
    color: #6c757d;
}

.form-help {
    display: block;
    margin-top: 4px;
    color: #666;
    font-size: 12px;
}

.checkbox-group {
    margin-bottom: 15px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    color: #ffffff;
    font-size: 14px;
}

.checkbox-label input[type="checkbox"] {
    width: 18px;
    height: 18px;
}

.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    padding-top: 24px;
}

.btn-cancel, .btn-save {
    padding: 12px 24px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-cancel {
    background: #f8f9fa;
    color: #666;
    border: 1px solid #dee2e6;
}

.btn-cancel:hover {
    background: #e9ecef;
}

.btn-save {
    background: #007bff;
    color: white;
}

.btn-save:hover {
    background: #0056b3;
}

.btn-back {
    padding: 8px 16px;
    background: #f8f9fa;
    color: #666;
    text-decoration: none;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    font-size: 14px;
    transition: all 0.2s;
}

.btn-back:hover {
    background: #e9ecef;
    text-decoration: none;
}

.alert {
    margin-bottom: 20px;
    padding: 12px;
    border-radius: 6px;
    font-size: 14px;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>

<?php require APPROOT.'/views/admin/inc/footer.php'; ?>