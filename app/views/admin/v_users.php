<?php require APPROOT.'/views/admin/inc/header.php'; ?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>User Management</h1>
        <div class="header-actions">
            <button class="btn-add-user" onclick="openAddUserModal()">+ Add New User</button>
        </div>
    </div>

    <!-- User Stats -->
    <div class="user-stats">
        <div class="stat-item">
            <span class="stat-number"><?php echo count(array_filter($data['users'], function($user) { return $user->role == 'customer'; })); ?></span>
            <span class="stat-label">Customers</span>
        </div>
        <div class="stat-item">
            <span class="stat-number"><?php echo count(array_filter($data['users'], function($user) { return $user->role == 'stadium_owner'; })); ?></span>
            <span class="stat-label">Stadium Owners</span>
        </div>
        <div class="stat-item">
            <span class="stat-number"><?php echo count(array_filter($data['users'], function($user) { return $user->role == 'coach'; })); ?></span>
            <span class="stat-label">Coaches</span>
        </div>
        <div class="stat-item">
            <span class="stat-number"><?php echo count(array_filter($data['users'], function($user) { return $user->role == 'rental_owner'; })); ?></span>
            <span class="stat-label">Rental Owners</span>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <div class="filter-group">
            <select class="filter-select" id="roleFilter">
                <option value="">All Roles</option>
                <option value="customer">Customer</option>
                <option value="stadium_owner">Stadium Owner</option>
                <option value="coach">Coach</option>
                <option value="rental_owner">Rental Owner</option>
            </select>
        </div>
        <div class="filter-group">
            <select class="filter-select" id="statusFilter">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>
        <div class="filter-group">
            <input type="text" class="search-input" placeholder="Search users..." id="userSearch">
        </div>
    </div>

    <!-- Users Table -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>All Users</h3>
            <span class="total-count"><?php echo count($data['users']); ?> total users</span>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($data['users']) && !empty($data['users'])): ?>
                        <?php foreach($data['users'] as $user): ?>
                        <tr data-role="<?php echo $user->role; ?>" data-status="<?php echo $user->status; ?>">
                            <td>#<?php echo $user->id; ?></td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar"><?php echo substr($user->first_name, 0, 1); ?></div>
                                    <span><?php echo $user->display_name ? $user->display_name : $user->first_name . ' ' . $user->last_name; ?></span>
                                </div>
                            </td>
                            <td><?php echo $user->email; ?></td>
                            <td>
                                <span class="role-badge <?php echo strtolower(str_replace('_', '-', $user->role)); ?>">
                                    <?php 
                                    switch($user->role) {
                                        case 'stadium_owner': echo 'Stadium Owner'; break;
                                        case 'rental_owner': echo 'Rental Owner'; break;
                                        default: echo ucfirst($user->role); break;
                                    }
                                    ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge <?php echo strtolower($user->status); ?>">
                                    <?php echo ucfirst($user->status); ?>
                                </span>
                            </td>
                            <td><?php echo date('Y-m-d', strtotime($user->created_at)); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action-sm btn-edit" onclick="editUser(<?php echo $user->id; ?>)">Edit</button>
                                    <?php if($user->status == 'active'): ?>
                                        <button class="btn-action-sm btn-suspend" onclick="suspendUser(<?php echo $user->id; ?>)">Suspend</button>
                                    <?php else: ?>
                                        <button class="btn-action-sm btn-activate" onclick="activateUser(<?php echo $user->id; ?>)">Activate</button>
                                    <?php endif; ?>
                                    <button class="btn-action-sm btn-delete" onclick="deleteUser(<?php echo $user->id; ?>)">Delete</button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #666;">
                                No users found. <a href="<?php echo URLROOT; ?>/admin/add_user">Add the first user</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Show success/error messages if any -->
    <?php if(isset($_SESSION['admin_message'])): ?>
        <div class="alert alert-success" style="position: fixed; top: 20px; right: 20px; background: rgba(0, 255, 0, 0.1); border: 1px solid #28a745; color: #28a745; padding: 12px; border-radius: 8px; z-index: 1000;">
            <?php echo $_SESSION['admin_message']; unset($_SESSION['admin_message']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['admin_error'])): ?>
        <div class="alert alert-error" style="position: fixed; top: 20px; right: 20px; background: rgba(255, 0, 0, 0.1); border: 1px solid #ff4444; color: #ff6666; padding: 12px; border-radius: 8px; z-index: 1000;">
            <?php echo $_SESSION['admin_error']; unset($_SESSION['admin_error']); ?>
        </div>
    <?php endif; ?>
</div>

<script>
function openAddUserModal() {
    // Redirect to add user page instead of using modal
    window.location.href = '<?php echo URLROOT; ?>/admin/add_user';
}

function editUser(id) {
    window.location.href = '<?php echo URLROOT; ?>/admin/edit_user/' + id;
}

function suspendUser(id) {
    if(confirm('Are you sure you want to suspend this user?')) {
        window.location.href = '<?php echo URLROOT; ?>/admin/toggle_user_status/' + id;
    }
}

function activateUser(id) {
    if(confirm('Are you sure you want to activate this user?')) {
        window.location.href = '<?php echo URLROOT; ?>/admin/toggle_user_status/' + id;
    }
}

function deleteUser(id) {
    if(confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        window.location.href = '<?php echo URLROOT; ?>/admin/delete_user/' + id;
    }
}

// Filter functionality
document.getElementById('roleFilter').addEventListener('change', function() {
    const selectedRole = this.value.toLowerCase();
    const rows = document.querySelectorAll('.data-table tbody tr');
    
    rows.forEach(row => {
        const roleData = row.getAttribute('data-role');
        if (selectedRole === '' || roleData === selectedRole) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

document.getElementById('statusFilter').addEventListener('change', function() {
    const selectedStatus = this.value.toLowerCase();
    const rows = document.querySelectorAll('.data-table tbody tr');
    
    rows.forEach(row => {
        const statusData = row.getAttribute('data-status');
        if (selectedStatus === '' || statusData === selectedStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Search functionality
document.getElementById('userSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('.data-table tbody tr');
    
    rows.forEach(row => {
        const name = row.cells[1].textContent.toLowerCase();
        const email = row.cells[2].textContent.toLowerCase();
        
        if (name.includes(searchTerm) || email.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Auto-hide alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.display = 'none';
    });
}, 5000);
</script>

<style>
.user-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-item {
    background: #171717;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 32px;
    font-weight: bold;
    color: #ffffff;
    margin-bottom: 5px;
}

.stat-label {
    color: #ffffff;
    font-size: 14px;
}

.filters-section {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.filter-group {
    min-width: 200px;
}

.filter-select, .search-input {
    width: 100%;
    padding: 10px;
    border: 2px solid #e1e5e9;
    border-radius: 6px;
    font-size: 14px;
}

.filter-select:focus, .search-input:focus {
    outline: none;
    border-color: #007bff;
}

.dashboard-card {
    background: #171717;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-header {
    padding: 20px;
    border-bottom: 1px solid #3b3b3bff;
    display: flex;
    justify-content: between;
    align-items: center;
}

.card-header h3 {
    margin: 0;
    color: #ffffff;
}

.total-count {
    color: #666;
    font-size: 14px;
}

.table-container {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #3b3b3bff;
}

.data-table th {
    background: #080808ff;
    font-weight: 600;
    color: #ffffff;
    font-size: 14px;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #007bff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}

.role-badge, .status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.role-badge.customer { background: #e3f2fd; color: #1976d2; }
.role-badge.stadium-owner { background: #f3e5f5; color: #7b1fa2; }
.role-badge.coach { background: #e8f5e8; color: #388e3c; }
.role-badge.rental-owner { background: #fff3e0; color: #f57c00; }

.status-badge.active { background: #e8f5e8; color: #388e3c; }
.status-badge.inactive { background: #fafafa; color: #757575; }
.status-badge.suspended { background: #ffebee; color: #d32f2f; }

.action-buttons {
    display: flex;
    gap: 5px;
}

.btn-action-sm {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-edit { background: #007bff; color: white; }
.btn-edit:hover { background: #0056b3; }

.btn-suspend { background: #ffc107; color: black; }
.btn-suspend:hover { background: #e0a800; }

.btn-activate { background: #28a745; color: white; }
.btn-activate:hover { background: #218838; }

.btn-delete { background: #dc3545; color: white; }
.btn-delete:hover { background: #c82333; }

.btn-add-user {
    background: #28a745;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-add-user:hover {
    background: #218838;
}

@media (max-width: 768px) {
    .filters-section {
        flex-direction: column;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>

<?php require APPROOT.'/views/admin/inc/footer.php'; ?>