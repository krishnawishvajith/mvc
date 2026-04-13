<?php require APPROOT.'/views/admin/inc/header.php'; ?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>Add New User</h1>
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

    <!-- Add User Form -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Create New User Account</h3>
            <p>Fill in the details below to create a new user account</p>
        </div>
        
        <form method="POST" action="<?php echo URLROOT; ?>/admin/add_user" class="user-form">
            
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
                               value="<?php echo htmlspecialchars($data['form_data']['first_name'] ?? ''); ?>"
                               placeholder="Enter first name"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
                        <input type="text" 
                               id="last_name" 
                               name="last_name" 
                               class="form-control"
                               value="<?php echo htmlspecialchars($data['form_data']['last_name'] ?? ''); ?>"
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
                               value="<?php echo htmlspecialchars($data['form_data']['email'] ?? ''); ?>"
                               placeholder="Enter email address"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" 
                            id="phone" 
                            name="phone" 
                            class="form-control"
                            value="<?php echo htmlspecialchars($data['form_data']['phone'] ?? ''); ?>"
                            placeholder="0XXXXXXXXX (e.g., 0712345678)"
                            maxlength="10"
                            pattern="0[0-9]{9}"
                        required>
                    <small class="form-help">Enter 10-digit phone number starting with 0</small>
                </div>
                </div>

                <div class="form-group">
                    <label for="role">User Role *</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="">Select User Role</option>
                        <option value="customer" <?php echo (isset($data['form_data']['role']) && $data['form_data']['role'] == 'customer') ? 'selected' : ''; ?>>
                            Customer (Sports Player)
                        </option>
                        <option value="stadium_owner" <?php echo (isset($data['form_data']['role']) && $data['form_data']['role'] == 'stadium_owner') ? 'selected' : ''; ?>>
                            Stadium Owner
                        </option>
                        <option value="coach" <?php echo (isset($data['form_data']['role']) && $data['form_data']['role'] == 'coach') ? 'selected' : ''; ?>>
                            Sports Coach
                        </option>
                        <option value="rental_owner" <?php echo (isset($data['form_data']['role']) && $data['form_data']['role'] == 'rental_owner') ? 'selected' : ''; ?>>
                            Equipment Rental Owner
                        </option>
                    </select>
                </div>
            </div>

            <!-- Password Section -->
            <div class="form-section">
                <h4>Account Security</h4>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-control"
                               placeholder="Enter password (min 6 characters)"
                               required>
                        <small class="form-help">Password must be at least 6 characters long</small>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password *</label>
                        <input type="password" 
                               id="confirm_password" 
                               name="confirm_password" 
                               class="form-control"
                               placeholder="Confirm password"
                               required>
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
                <button type="submit" class="btn-save">Create User</button>
            </div>
        </form>
    </div>
</div>

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

.form-help {
    display: block;
    margin-top: 4px;
    color: #666;
    font-size: 12px;
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
    background: #28a745;
    color: white;
}

.btn-save:hover {
    background: #218838;
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

<script>
// Phone number validation
document.getElementById('phone').addEventListener('input', function(e) {
    // Remove any non-digit characters
    this.value = this.value.replace(/[^0-9]/g, '');
    
    // Limit to 10 digits
    if (this.value.length > 10) {
        this.value = this.value.slice(0, 10);
    }
    
    // Validate format
    validatePhoneNumber(this);
});

document.getElementById('phone').addEventListener('blur', function(e) {
    validatePhoneNumber(this);
});

function validatePhoneNumber(input) {
    const phoneNumber = input.value;
    const errorDiv = document.getElementById('phone-error');
    
    // Remove existing error if present
    if (errorDiv) {
        errorDiv.remove();
    }
    
    if (phoneNumber.length === 0) {
        return; // Don't show error for empty field (required validation will handle it)
    }
    
    if (phoneNumber.length !== 10) {
        showPhoneError(input, 'Phone number must be exactly 10 digits');
        input.setCustomValidity('Phone number must be exactly 10 digits');
        return false;
    }
    
    if (!phoneNumber.startsWith('0')) {
        showPhoneError(input, 'Phone number must start with 0');
        input.setCustomValidity('Phone number must start with 0');
        return false;
    }
    
    // Valid phone number
    input.setCustomValidity('');
    input.style.borderColor = '#28a745';
    return true;
}

function showPhoneError(input, message) {
    input.style.borderColor = '#dc3545';
    
    const errorDiv = document.createElement('small');
    errorDiv.id = 'phone-error';
    errorDiv.className = 'form-help';
    errorDiv.style.color = '#dc3545';
    errorDiv.textContent = message;
    
    input.parentNode.appendChild(errorDiv);
}

// Form submission validation
document.querySelector('.user-form').addEventListener('submit', function(e) {
    const phoneInput = document.getElementById('phone');
    
    if (!validatePhoneNumber(phoneInput)) {
        e.preventDefault();
        phoneInput.focus();
        return false;
    }
});

// Clear error styling when user starts typing again
document.getElementById('phone').addEventListener('focus', function() {
    this.style.borderColor = '#e1e5e9';
    const errorDiv = document.getElementById('phone-error');
    if (errorDiv) {
        errorDiv.remove();
    }
});
</script>

<?php require APPROOT.'/views/admin/inc/footer.php'; ?>