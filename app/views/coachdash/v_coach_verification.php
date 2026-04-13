<?php require APPROOT . '/views/coachdash/inc/header.php'; ?>

<div class="kal-coach-profile-manager">
    <div class="kal-coach-profile-header">
        <h1>Coach Verification</h1>
    </div>

    <!-- ================= NOT SUBMITTED / EDIT ================= -->
    <?php if($data['status'] == 'not_submitted'): ?>

        <div class="kal-profile-section">
            <div class="kal-section-header">
                <h3>Submit Verification Request</h3>
            </div>

            <form action="<?php echo URLROOT; ?>/coachdash/SubmitVerification"
                  method="POST"
                  enctype="multipart/form-data"
                  class="kal-form-grid">

                <!-- Specialization -->
                <div class="kal-form-group">
                    <label>Specialization</label>
                    <input type="text"
                           class="kal-form-control"
                           value="<?php echo htmlspecialchars($data['Coach_details']['specialization'] ?? ''); ?>"
                           readonly>
                </div>

                <!-- Experience -->
                <div class="kal-form-group">
                    <label>Experience</label>
                    <input type="text"
                           class="kal-form-control"
                           value="<?php echo htmlspecialchars($data['Coach_details']['experience'] ?? ''); ?> years"
                           readonly>
                </div>

                <!-- Certification -->
                <div class="kal-form-group">
                    <label>Certification</label>
                    <input type="text"
                           class="kal-form-control"
                           name="certification"
                           value="<?php echo htmlspecialchars($data['verification']->certification ?? ''); ?>"
                           required>
                </div>

                <!-- Certificate Upload -->
                <div class="kal-form-group full-width">
                    <label>Upload Certificate</label>

                    <div class="kal-certificate-upload">

                        <!-- ✅ PREVIEW BOX (FIXED) -->
                        <div class="kal-preview-box" id="certificatePreview">

                            <?php if(!empty($data['verification']->file_path)): ?>

                                <?php 
                                    $file = $data['verification']->file_path;
                                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                ?>

                                <?php if($ext === 'pdf'): ?>
                                    <div class="kal-file-preview">
                                        <span class="kal-file-icon">📄</span>
                                        <div class="kal-file-info">
                                            <p class="kal-file-name">Existing Certificate (PDF)</p>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <img src="<?php echo URLROOT . '/' . htmlspecialchars($file); ?>" 
                                         class="kal-preview-img"
                                         alt="Existing Certificate">
                                <?php endif; ?>

                            <?php else: ?>
                                <div class="kal-upload-placeholder">
                                    <span class="kal-upload-icon">📄</span>
                                    <p>Click to upload certificate image</p>
                                    <small>JPG, PNG, PDF up to 5MB</small>
                                </div>
                            <?php endif; ?>

                        </div>

                        <!-- Hidden File Input -->
                        <input type="file"
                               id="certificateFile"
                               name="certificate_file"
                               accept="image/*,.pdf"
                               style="display: none;">

                        <button type="button"
                                class="kal-upload-btn"
                                onclick="document.getElementById('certificateFile').click()">
                            Choose File
                        </button>

                    </div>
                </div>

                <!-- Submit -->
                <div class="kal-form-group full-width">
                    <button type="submit" class="kal-profile-save-btn">
                        Submit for Verification
                    </button>
                </div>

            </form>
        </div>

    <!-- ================= PENDING ================= -->
    <?php elseif($data['status'] == 'pending') : ?>

        <div class="kal-profile-section">

            <div class="kal-status-card kal-status-pending">
                <div class="kal-status-icon">⏳</div>
                <div class="kal-status-content">
                    <h3>Verification Under Review</h3>
                    <p>Your request is being processed.</p>
                </div>
            </div>

            <div class="kal-verification-details">
                <div class="kal-info-grid">
                    <div class="kal-info-item">
                        <span class="kal-info-label">Specialization</span>
                        <span class="kal-info-value"><?php echo htmlspecialchars($data['Coach_details']['specialization']); ?></span>
                    </div>
                    <div class="kal-info-item">
                        <span class="kal-info-label">Experience</span>
                        <span class="kal-info-value"><?php echo htmlspecialchars($data['Coach_details']['experience']); ?> years</span>
                    </div>
                    <div class="kal-info-item">
                        <span class="kal-info-label">Certification</span>
                        <span class="kal-info-value"><?php echo htmlspecialchars($data['verification']->certification ?? ''); ?></span>
                    </div>
                </div>
            </div>

            <?php if(!empty($data['verification']->file_path)): ?>
                <div class="kal-certificate-display">
                    <div class="kal-certificate-preview">
                        <img src="<?php echo URLROOT . '/' . htmlspecialchars($data['verification']->file_path); ?>"
                             class="kal-certificate-img">
                    </div>
                </div>
            <?php endif; ?>

            <div class="kal-action-buttons">
                <a href="<?php echo URLROOT; ?>/coachdash/editverification" class="kal-edit-btn">
                    Edit & Resubmit
                </a>
            </div>

        </div>

    <!-- ================= VERIFIED ================= -->
    <?php elseif($data['status'] == 'verified') : ?>

        <div class="kal-profile-section">

            <div class="kal-status-card kal-status-verified">
                <div class="kal-status-icon">✅</div>
                <div class="kal-status-content">
                    <h3>Verified Coach</h3>
                </div>
            </div>

            <?php if(!empty($data['verification']->file_path)): ?>
                <div class="kal-certificate-display">
                    <div class="kal-certificate-preview">
                        <img src="<?php echo URLROOT . '/' . htmlspecialchars($data['verification']->file_path); ?>"
                             class="kal-certificate-img">
                    </div>
                </div>
            <?php endif; ?>

        </div>

    <!-- ================= REJECTED ================= -->
    <?php elseif($data['status'] == 'rejected') : ?>

        <div class="kal-profile-section">

            <div class="kal-status-card kal-status-rejected">
                <div class="kal-status-icon">❌</div>
                <div class="kal-status-content">
                    <h3>Verification Rejected</h3>
                </div>
            </div>

            <div class="kal-action-buttons">
                <a href="<?php echo URLROOT; ?>/coachdash/editverification" class="kal-edit-btn">
                    Edit & Resubmit
                </a>
            </div>

        </div>

    <?php endif; ?>

</div>

<?php require APPROOT . '/views/coachdash/inc/footer.php'; ?>


<!-- ================= JS ================= -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const fileInput = document.getElementById('certificateFile');
    const previewBox = document.getElementById('certificatePreview');

    if (fileInput && previewBox) {

        previewBox.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function (e) {

            const file = e.target.files[0];
            if (!file) return;

            const allowed = ['image/jpeg','image/png','image/webp','application/pdf'];
            if (!allowed.includes(file.type)) {
                alert('Invalid file type');
                fileInput.value = '';
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                alert('Max 5MB allowed');
                fileInput.value = '';
                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {

                if (file.type === 'application/pdf') {
                    previewBox.innerHTML = `
                        <div class="kal-file-preview">
                            <span class="kal-file-icon">📄</span>
                            <div class="kal-file-info">
                                <p class="kal-file-name">${file.name}</p>
                            </div>
                        </div>
                    `;
                } else {
                    previewBox.innerHTML = `
                        <img src="${event.target.result}" class="kal-preview-img">
                    `;
                }
            };

            reader.readAsDataURL(file);
        });
    }

});
</script>
