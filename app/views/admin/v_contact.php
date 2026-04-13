<?php require APPROOT.'/views/admin/inc/header.php'; ?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>Contact Page Management</h1>
        <div class="header-actions">
            <button class="btn-preview-contact" type="button" onclick="previewContact()">👁️ Preview</button>
        </div>
    </div>

    <?php if(isset($_SESSION['admin_message'])): ?>
        <div class="alert alert-success" style="margin-bottom: 16px;">
            <?php echo htmlspecialchars($_SESSION['admin_message']); ?>
            <?php unset($_SESSION['admin_message']); ?>
        </div>
    <?php endif; ?>
    <?php if(isset($_SESSION['admin_error'])): ?>
        <div class="alert alert-danger" style="margin-bottom: 16px;">
            <?php echo htmlspecialchars($_SESSION['admin_error']); ?>
            <?php unset($_SESSION['admin_error']); ?>
        </div>
    <?php endif; ?>

    <?php $s = $data['settings'] ?? []; ?>
    <form method="POST" action="<?php echo URLROOT; ?>/admin/contact">
    <!-- Contact Info Grid -->
    <div class="contact-grid">
        <!-- Primary Contact Information -->
        <div class="contact-card">
            <div class="card-header">
                <h3>Primary Contact Information</h3>
                <span class="edit-indicator">✏️ Editable</span>
            </div>
            <div class="contact-form">
                <div class="form-group">
                    <label>Main Phone Number</label>
                    <input type="tel" id="mainPhone" name="main_phone" value="<?php echo htmlspecialchars($s['main_phone'] ?? ''); ?>" class="form-control" required>
                    <small class="form-help">This will be displayed as the primary contact number</small>
                </div>
                
                <div class="form-group">
                    <label>Support Phone</label>
                    <input type="tel" id="supportPhone" name="support_phone" value="<?php echo htmlspecialchars($s['support_phone'] ?? ''); ?>" class="form-control">
                    <small class="form-help">For customer support inquiries</small>
                </div>
                
                <div class="form-group">
                    <label>Main Email Address</label>
                    <input type="email" id="mainEmail" name="email" value="<?php echo htmlspecialchars($s['email'] ?? ''); ?>" class="form-control" required>
                    <small class="form-help">Primary business email</small>
                </div>
                
                <div class="form-group">
                    <label>Support Email</label>
                    <input type="email" id="supportEmail" name="support_email" value="<?php echo htmlspecialchars($s['support_email'] ?? ''); ?>" class="form-control">
                    <small class="form-help">For customer support emails</small>
                </div>
                
                <div class="form-group">
                    <label>Emergency Contact</label>
                    <input type="tel" id="emergencyContact" name="emergency_contact" value="<?php echo htmlspecialchars($s['emergency_contact'] ?? ''); ?>" class="form-control">
                    <small class="form-help">24/7 emergency contact number</small>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="contact-card">
            <div class="card-header">
                <h3>Address & Location</h3>
            </div>
            <div class="contact-form">
                <div class="form-group">
                    <label>Business Address</label>
                    <textarea id="businessAddress" name="address" rows="3" class="form-control" required><?php echo htmlspecialchars($s['address'] ?? ''); ?></textarea>
                    <small class="form-help">Full business address with postal code</small>
                </div>
                
                <div class="form-group">
                    <label>Working Hours</label>
                    <input type="text" id="workingHours" name="working_hours" value="<?php echo htmlspecialchars($s['working_hours'] ?? ''); ?>" class="form-control">
                    <small class="form-help">Business operating hours</small>
                </div>
            </div>
        </div>

        <!-- Contact Page Content -->
        <div class="contact-card full-width">
            <div class="card-header">
                <h3>Contact Page Content</h3>
            </div>
            <div class="contact-form">
                <div class="form-group">
                    <label>Page Headline</label>
                    <input type="text" id="pageHeadline" name="page_title" value="<?php echo htmlspecialchars($s['page_title'] ?? ''); ?>" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>Page Description</label>
                    <textarea id="pageDescription" name="page_subtitle" rows="4" class="form-control" required><?php echo htmlspecialchars($s['page_subtitle'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top: 18px; text-align: right;">
        <button type="submit" class="btn-save-contact">💾 Save Changes</button>
    </div>
    </form>

    <!-- Contact Statistics -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Contact Form Statistics</h3>
        </div>
        <div class="contact-stats">
            <div class="stat-item">
                <div class="stat-icon">📧</div>
                <div class="stat-details">
                    <span class="stat-number"><?php echo (int)($data['stats']['this_month'] ?? 0); ?></span>
                    <span class="stat-label">Messages This Month</span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">📞</div>
                <div class="stat-details">
                    <span class="stat-number"><?php echo (int)($data['stats']['total_messages'] ?? 0); ?></span>
                    <span class="stat-label">Total Messages</span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">⚡</div>
                <div class="stat-details">
                    <span class="stat-number"><?php echo (int)($data['stats']['new_messages'] ?? 0); ?></span>
                    <span class="stat-label">New Messages</span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">📈</div>
                <div class="stat-details">
                    <span class="stat-number">—</span>
                    <span class="stat-label">Response KPI (coming soon)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Contact Form Submissions -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Recent Contact Form Entries</h3>
            <a href="<?php echo URLROOT; ?>/admin/contact" class="view-all">Refresh →</a>
        </div>
        <div class="submissions-list">
            <?php $msgs = $data['messages'] ?? []; ?>
            <?php if (empty($msgs)): ?>
                <div style="padding: 18px; color:#bbb;">No contact form submissions yet.</div>
            <?php else: ?>
                <?php foreach ($msgs as $m): ?>
                    <div class="submission-item">
                        <div class="submission-info">
                            <h4><?php echo htmlspecialchars(trim(($m->first_name ?? '') . ' ' . ($m->last_name ?? ''))); ?></h4>
                            <p><?php echo htmlspecialchars($m->email ?? ''); ?></p>
                            <small><?php echo htmlspecialchars(($m->subject ?? '') . ' - ' . ($m->submitted_at ?? '')); ?></small>
                        </div>
                        <?php
                            $st = $m->status ?? 'new';
                            $cls = 'unread';
                            if ($st === 'read') $cls = 'read';
                            if ($st === 'replied') $cls = 'replied';
                        ?>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <button type="button" class="btn-preview-contact" style="padding:8px 12px; font-size:12px;" onclick="viewContactMessage(<?php echo (int)($m->id ?? 0); ?>)">View</button>
                            <div class="submission-status <?php echo $cls; ?>"><?php echo htmlspecialchars(ucfirst($st)); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Message Details Modal -->
<div id="contactMsgModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.6); z-index:9999; padding:24px;">
    <div style="max-width: 760px; margin: 40px auto; background:#111; border:1px solid rgba(255,255,255,.12); border-radius: 12px; overflow:hidden;">
        <div style="display:flex; justify-content:space-between; align-items:center; padding:16px 18px; border-bottom:1px solid rgba(255,255,255,.08);">
            <div>
                <div style="color:#fff; font-weight:700; font-size:16px;">Contact Submission Details</div>
                <div id="contactMsgModalMeta" style="color:#aaa; font-size:12px; margin-top:2px;"></div>
            </div>
            <button type="button" onclick="closeContactMsgModal()" style="background:transparent; border:none; color:#fff; font-size:20px; cursor:pointer;">×</button>
        </div>
        <div style="padding:18px; color:#ddd;">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px; margin-bottom:14px;">
                <div>
                    <div style="color:#9aa; font-size:12px;">Name</div>
                    <div id="cm_name" style="color:#fff; font-weight:600;"></div>
                </div>
                <div>
                    <div style="color:#9aa; font-size:12px;">Status</div>
                    <div id="cm_status" style="color:#fff; font-weight:600;"></div>
                </div>
                <div>
                    <div style="color:#9aa; font-size:12px;">Email</div>
                    <div id="cm_email" style="color:#fff;"></div>
                </div>
                <div>
                    <div style="color:#9aa; font-size:12px;">Phone</div>
                    <div id="cm_phone" style="color:#fff;"></div>
                </div>
                <div style="grid-column:1 / -1;">
                    <div style="color:#9aa; font-size:12px;">Subject</div>
                    <div id="cm_subject" style="color:#fff;"></div>
                </div>
            </div>
            <div>
                <div style="color:#9aa; font-size:12px; margin-bottom:6px;">Message</div>
                <div id="cm_message" style="white-space:pre-wrap; background:#0b0b0b; border:1px solid rgba(255,255,255,.08); border-radius:10px; padding:14px; color:#eaeaea;"></div>
            </div>
        </div>
        <div style="display:flex; justify-content:flex-end; gap:10px; padding:14px 18px; border-top:1px solid rgba(255,255,255,.08);">
            <button type="button" class="btn-preview-contact" onclick="closeContactMsgModal()">Close</button>
        </div>
    </div>
</div>

<script>
function previewContact() {
    window.open('<?php echo URLROOT; ?>/contact?preview=1', '_blank');
}

function escapeHtml(str) {
    return String(str ?? '').replace(/[&<>"'`=\/]/g, function(s) {
        return ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
            '/': '&#x2F;',
            '`': '&#x60;',
            '=': '&#x3D;'
        })[s];
    });
}

function viewContactMessage(messageId) {
    if (!messageId) return;

    const modal = document.getElementById('contactMsgModal');
    const meta = document.getElementById('contactMsgModalMeta');
    modal.style.display = 'block';
    meta.textContent = 'Loading...';

    fetch('<?php echo URLROOT; ?>/admin/contactMessageDetails', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'message_id=' + encodeURIComponent(messageId)
    })
    .then(r => r.json())
    .then(data => {
        if (!data || !data.success) {
            meta.textContent = data && data.message ? data.message : 'Failed to load message';
            return;
        }

        const m = data.message || {};
        const name = ((m.first_name || '') + ' ' + (m.last_name || '')).trim();
        document.getElementById('cm_name').textContent = name || '-';
        document.getElementById('cm_status').textContent = (m.status || 'new');
        document.getElementById('cm_email').textContent = (m.email || '-');
        document.getElementById('cm_phone').textContent = (m.phone || '-');
        document.getElementById('cm_subject').textContent = (m.subject || '-');
        document.getElementById('cm_message').textContent = (m.message || '');
        meta.textContent = 'Submitted at: ' + (m.submitted_at || '-');
    })
    .catch(() => {
        meta.textContent = 'Failed to load message';
    });
}

function closeContactMsgModal() {
    const modal = document.getElementById('contactMsgModal');
    if (modal) modal.style.display = 'none';
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('contactMsgModal');
    if (!modal || modal.style.display === 'none') return;
    if (e.target === modal) closeContactMsgModal();
});

</script>

<?php require APPROOT.'/views/admin/inc/footer.php'; ?>