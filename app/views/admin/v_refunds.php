<?php require APPROOT . '/views/admin/inc/header.php'; ?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>Refund Requests</h1>
        <div class="header-actions">
            <button class="btn-bulk-approve" onclick="bulkApproveRefunds()">✅ Approve All</button>
        </div>
    </div>

    <!-- Refund Stats -->
    <div class="refund-stats">
        <div class="stat-item">
            <div class="stat-icon">⏳</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo isset($data['refund_counts']['pending']) ? $data['refund_counts']['pending'] : 0; ?></span>
                <span class="stat-label">Pending Requests</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">✅</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo isset($data['refund_counts']['refunded']) ? $data['refund_counts']['refunded'] : 0; ?></span>
                <span class="stat-label">Approved This Month</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">💰</div>
            <div class="stat-details">
                <span class="stat-number">LKR <?php echo isset($data['total_refund_amount']) ? number_format($data['total_refund_amount']) : 0; ?></span>
                <span class="stat-label">Total Refund Amount</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">📊</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo isset($data['refund_counts']['total']) && $data['refund_counts']['total'] > 0 ? round((count($data['refunds']) / $data['refund_counts']['total']) * 100) : 0; ?>%</span>
                <span class="stat-label">Refund Rate</span>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <div class="filter-group">
            <select class="filter-select" id="statusFilter">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="processed">Processed</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        <div class="filter-group">
            <select class="filter-select" id="reasonFilter">
                <option value="">All Reasons</option>
                <option value="weather">Weather Conditions</option>
                <option value="emergency">Emergency</option>
                <option value="error">Booking Error</option>
                <option value="facility">Facility Issues</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div class="filter-group">
            <input type="text" class="search-input" placeholder="Search refunds..." id="refundSearch">
        </div>
    </div>

    <!-- Refund Requests Table -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Refund Requests</h3>
            <span class="total-count"><?php echo isset($data['refunds']) ? count($data['refunds']) : 0; ?> total requests</span>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                        </th>
                        <th>Request ID</th>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Stadium</th>
                        <th>Amount</th>
                        <th>Reason</th>
                        <th>Request Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($data['refunds']) && is_array($data['refunds']) && count($data['refunds']) > 0): ?>
                        <?php foreach ($data['refunds'] as $refund): ?>
                            <tr class="refund-row" data-status="<?php echo strtolower($refund->status); ?>">
                                <td>
                                    <?php if ($refund->status == 'pending'): ?>
                                        <input type="checkbox" class="refund-checkbox" value="<?php echo $refund->id; ?>">
                                    <?php endif; ?>
                                </td>
                                <td>#RF<?php echo str_pad($refund->id, 3, '0', STR_PAD_LEFT); ?></td>
                                <td>
                                    <a href="#" class="booking-link" onclick="viewBookingDetails('<?php echo $refund->booking_id; ?>')">
                                        #<?php echo $refund->booking_id; ?>
                                    </a>
                                </td>
                                <td>
                                    <div class="customer-info">
                                        <div class="customer-avatar"><?php echo substr($refund->first_name . ' ' . $refund->last_name, 0, 1); ?></div>
                                        <span><?php echo htmlspecialchars($refund->first_name . ' ' . $refund->last_name); ?></span>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($refund->stadium_name); ?></td>
                                <td>
                                    <strong class="refund-amount">LKR <?php echo number_format($refund->refund_amount); ?></strong>
                                </td>
                                <td>
                                    <span class="reason-tag">
                                        <?php echo htmlspecialchars($refund->reason_for_cancellation ?? 'No reason provided'); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($refund->created_at)); ?></td>
                                <td>
                                    <span class="status-badge <?php echo strtolower($refund->status); ?>">
                                        <?php echo ucfirst($refund->status); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <?php if ($refund->status == 'pending'): ?>
                                            <button class="btn-action-sm btn-approve" onclick="approveRefund(<?php echo $refund->id; ?>, <?php echo $refund->refund_amount; ?>)">Approve</button>
                                            <button class="btn-action-sm btn-reject" onclick="rejectRefund(<?php echo $refund->id; ?>)">Reject</button>
                                        <?php elseif ($refund->status == 'refunded'): ?>
                                            <button class="btn-action-sm btn-view" onclick="viewRefundDetails(<?php echo $refund->id; ?>)">View</button>
                                        <?php else: ?>
                                            <button class="btn-action-sm btn-view" onclick="viewRefundDetails(<?php echo $refund->id; ?>)">View</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" style="text-align: center; padding: 40px; color: #999;">
                                <p>📋 No refund requests at this time</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Refund Activity -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Recent Refund Activity</h3>
        </div>
        <div class="activity-list">
            <div class="activity-item">
                <div class="activity-icon approved">✅</div>
                <div class="activity-details">
                    <p><strong>Refund #RF004</strong> approved for Mike Johnson</p>
                    <small>Basketball Hub booking - LKR 4,000</small>
                    <small>2 hours ago</small>
                </div>
                <div class="activity-amount approved">+LKR 4,000</div>
            </div>
            <div class="activity-item">
                <div class="activity-icon processed">💰</div>
                <div class="activity-details">
                    <p><strong>Refund #RF003</strong> processed successfully</p>
                    <small>Emma Davis - Cricket Ground booking</small>
                    <small>5 hours ago</small>
                </div>
                <div class="activity-amount processed">LKR 5,000</div>
            </div>
            <div class="activity-item">
                <div class="activity-icon pending">⏳</div>
                <div class="activity-details">
                    <p><strong>New refund request #RF005</strong> received</p>
                    <small>Sarah Wilson - Football Arena Pro</small>
                    <small>1 day ago</small>
                </div>
                <div class="activity-amount pending">LKR 7,500</div>
            </div>
        </div>
    </div>
</div>

<!-- Refund Details Modal -->
<div id="refundModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Refund Request Details</h3>
            <span class="close" onclick="closeRefundModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="refund-details">
                <div class="detail-section">
                    <h4>Booking Information</h4>
                    <div class="detail-row">
                        <label>Request ID:</label>
                        <span id="modal_request_id"></span>
                    </div>
                    <div class="detail-row">
                        <label>Booking ID:</label>
                        <span id="modal_booking_id"></span>
                    </div>
                    <div class="detail-row">
                        <label>Customer:</label>
                        <span id="modal_customer"></span>
                    </div>
                    <div class="detail-row">
                        <label>Stadium:</label>
                        <span id="modal_stadium"></span>
                    </div>
                    <div class="detail-row">
                        <label>Refund Amount:</label>
                        <span class="highlight" id="modal_amount"></span>
                    </div>
                </div>

                <div class="detail-section">
                    <h4>Bank Transfer Details</h4>
                    <div class="bank-details-box">
                        <div class="detail-row">
                            <label>Account Name:</label>
                            <span id="modal_account_name"></span>
                        </div>
                        <div class="detail-row">
                            <label>Account Number:</label>
                            <span id="modal_account_number"></span>
                        </div>
                        <div class="detail-row">
                            <label>Bank Name:</label>
                            <span id="modal_bank_name"></span>
                        </div>
                        <div class="detail-row">
                            <label>Branch:</label>
                            <span id="modal_branch"></span>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h4>Actions</h4>
                    <form id="refundActionForm" enctype="multipart/form-data">
                        <input type="hidden" id="action_refund_id" name="refund_id">
                        <div class="form-group">
                            <label>Admin Notes:</label>
                            <textarea name="notes" id="action_notes" placeholder="Enter notes about the refund..."></textarea>
                        </div>
                        <div class="form-group" id="slipUploadGroup">
                            <label>Payment Slip (Required for completion):</label>
                            <input type="file" name="payment_slip" id="action_slip" accept="image/*">
                            <small>Attach the bank transfer confirmation slip</small>
                        </div>
                        <div id="processedSlipView" style="display:none;">
                            <label>Payment Slip:</label>
                            <div class="slip-preview">
                                <a id="modal_slip_link" href="#" target="_blank">View Attached Slip</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closeRefundModal()">Close</button>
            <div id="pendingActions">
                <button class="btn-reject" onclick="handleReject()">Reject</button>
                <button class="btn-approve-modal" onclick="handleProcessRefund()">Process Refund (Mark as Paid)</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div id="bulkActionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Bulk Approve Refunds</h3>
            <span class="close" onclick="closeBulkModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>You are about to approve <span id="bulkCount">0</span> refund requests totaling <span id="bulkAmount">LKR 0</span>.</p>
            <div class="bulk-list" id="bulkList">
                <!-- Selected refunds will be listed here -->
            </div>
            <p><strong>Are you sure you want to proceed?</strong></p>
        </div>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closeBulkModal()">Cancel</button>
            <button class="btn-bulk-confirm" onclick="confirmBulkApproval()">Approve All</button>
        </div>
    </div>
</div>

<script>
    const urlRoot = '<?php echo URLROOT; ?>';

    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.refund-checkbox');

        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
    }

    function viewRefundDetails(id) {
        fetch(`${urlRoot}/admin/getRefundDetails?id=${id}`)
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const refund = result.data;
                    document.getElementById('modal_request_id').textContent = `#RF${refund.id.toString().padStart(3, '0')}`;
                    document.getElementById('modal_booking_id').textContent = `#${refund.booking_id}`;
                    document.getElementById('modal_customer').textContent = `${refund.first_name} ${refund.last_name} (${refund.email})`;
                    document.getElementById('modal_stadium').textContent = refund.stadium_name;
                    document.getElementById('modal_amount').textContent = `LKR ${Number(refund.refund_amount).toLocaleString()}`;
                    
                    document.getElementById('modal_account_name').textContent = refund.account_name;
                    document.getElementById('modal_account_number').textContent = refund.account_number;
                    document.getElementById('modal_bank_name').textContent = refund.bank_name;
                    document.getElementById('modal_branch').textContent = refund.branch_name || 'N/A';
                    
                    document.getElementById('action_refund_id').value = refund.id;
                    document.getElementById('action_notes').value = refund.admin_notes || '';
                    
                    const slipGroup = document.getElementById('slipUploadGroup');
                    const processedView = document.getElementById('processedSlipView');
                    const actions = document.getElementById('pendingActions');
                    
                    if (refund.status === 'pending') {
                        slipGroup.style.display = 'block';
                        processedView.style.display = 'none';
                        actions.style.display = 'block';
                    } else if (refund.status === 'refunded') {
                        slipGroup.style.display = 'none';
                        processedView.style.display = 'block';
                        actions.style.display = 'none';
                        if (refund.payment_slip) {
                            document.getElementById('modal_slip_link').href = `${urlRoot}/images/refunds/${refund.payment_slip}`;
                            document.getElementById('modal_slip_link').style.display = 'inline';
                        } else {
                            document.getElementById('modal_slip_link').style.display = 'none';
                        }
                    } else {
                        slipGroup.style.display = 'none';
                        processedView.style.display = 'none';
                        actions.style.display = 'none';
                    }
                    
                    document.getElementById('refundModal').style.display = 'block';
                } else {
                    alert('Error: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while fetching refund details.');
            });
    }

    function closeRefundModal() {
        document.getElementById('refundModal').style.display = 'none';
    }

    function handleReject() {
        const id = document.getElementById('action_refund_id').value;
        const notes = document.getElementById('action_notes').value;
        
        if (!notes) {
            alert('Please provide a reason for rejection in the notes field.');
            return;
        }
        
        if (confirm('Are you sure you want to REJECT this refund request?')) {
            updateStatus(id, 'rejected', notes);
        }
    }

    function handleProcessRefund() {
        const id = document.getElementById('action_refund_id').value;
        const notes = document.getElementById('action_notes').value;
        const slipFile = document.getElementById('action_slip').files[0];
        
        if (!slipFile) {
            alert('Please upload a payment slip as proof of bank transfer.');
            return;
        }
        
        const formData = new FormData();
        formData.append('refund_id', id);
        formData.append('status', 'refunded');
        formData.append('notes', notes);
        formData.append('payment_slip', slipFile);
        
        fetch(`${urlRoot}/admin/updateRefundStatus`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('Refund processed successfully!');
                location.reload();
            } else {
                alert('Error: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing the refund.');
        });
    }

    function updateStatus(id, status, notes) {
        const formData = new FormData();
        formData.append('refund_id', id);
        formData.append('status', status);
        formData.append('notes', notes);
        
        fetch(`${urlRoot}/admin/updateRefundStatus`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(`Refund marked as ${status} successfully!`);
                location.reload();
            } else {
                alert('Error: ' + result.message);
            }
        });
    }

    function approveRefund(id, amount) {
        viewRefundDetails(id);
    }

    function rejectRefund(id) {
        viewRefundDetails(id);
    }

    // Filter functionality
    document.getElementById('statusFilter').addEventListener('change', function() {
        const status = this.value.toLowerCase();
        const rows = document.querySelectorAll('.refund-row');

        rows.forEach(row => {
            if (status === '' || row.dataset.status === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    window.onclick = function(event) {
        const refundModal = document.getElementById('refundModal');
        if (event.target == refundModal) {
            refundModal.style.display = "none";
        }
    }
</script>

<?php require APPROOT . '/views/admin/inc/footer.php'; ?>