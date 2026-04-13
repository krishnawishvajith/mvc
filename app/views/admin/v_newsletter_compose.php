<?php

require APPROOT.'/views/admin/inc/header.php'; ?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>Compose Newsletter</h1>
        <div class="header-actions">
            <a href="<?php echo URLROOT; ?>/admin/newsletter" class="btn-back">← Back to Newsletter</a>
            <button class="btn-save-draft" onclick="saveDraft()">💾 Save Draft</button>
            <button class="btn-preview" onclick="previewNewsletter()">👁️ Preview</button>
        </div>
    </div>

    <!-- Error/Success Messages -->
    <?php if(isset($data['error']) && !empty($data['error'])): ?>
        <div class="alert alert-error">
            <?php echo $data['error']; ?>
        </div>
    <?php endif; ?>

    <?php if(isset($data['success']) && !empty($data['success'])): ?>
        <div class="alert alert-success">
            <?php echo $data['success']; ?>
        </div>
    <?php endif; ?>

    <form class="newsletter-compose-form" method="POST" action="<?php echo URLROOT; ?>/admin/newsletter/compose">
        
        <!-- Newsletter Settings -->
        <div class="compose-section">
            <div class="section-header">
                <h3>Newsletter Settings</h3>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="subject">Email Subject *</label>
                    <input type="text" 
                           id="subject" 
                           name="subject" 
                           class="form-control"
                           placeholder="Enter compelling subject line"
                           value="<?php echo htmlspecialchars($data['form_data']['subject'] ?? ''); ?>"
                           required>
                    <small class="form-help">Keep it under 50 characters for better open rates</small>
                </div>

                <div class="form-group">
                    <label for="template">Email Template</label>
                    <select id="template" name="template" class="form-control" onchange="loadTemplate(this.value)">
                        <?php foreach($data['templates'] as $template): ?>
                        <option value="<?php echo $template['id']; ?>" 
                                <?php echo (isset($data['form_data']['template']) && $data['form_data']['template'] == $template['id']) ? 'selected' : ''; ?>>
                            <?php echo $template['name']; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Recipients -->
        <div class="compose-section">
            <div class="section-header">
                <h3>Recipients</h3>
            </div>
            
            <div class="recipient-selection">
                <?php foreach($data['subscriber_segments'] as $key => $segment): ?>
                <label class="recipient-option">
                    <input type="radio" 
                           name="recipient_type" 
                           value="<?php echo $key; ?>"
                           <?php echo (isset($data['form_data']['recipient_type']) && $data['form_data']['recipient_type'] == $key) || $key == 'all' ? 'checked' : ''; ?>>
                    <span class="recipient-label">
                        <strong><?php echo explode(' (', $segment)[0]; ?></strong>
                        <small><?php echo $segment; ?></small>
                    </span>
                </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Content Editor -->
        <div class="compose-section">
            <div class="section-header">
                <h3>Newsletter Content</h3>
                <div class="editor-tools">
                    <button type="button" class="tool-btn" onclick="insertBlock('header')" title="Add Header">
                        <span>H</span>
                    </button>
                    <button type="button" class="tool-btn" onclick="insertBlock('text')" title="Add Text Block">
                        <span>T</span>
                    </button>
                    <button type="button" class="tool-btn" onclick="insertBlock('image')" title="Add Image">
                        <span>📷</span>
                    </button>
                    <button type="button" class="tool-btn" onclick="insertBlock('button')" title="Add Button">
                        <span>🔘</span>
                    </button>
                    <button type="button" class="tool-btn" onclick="insertBlock('divider')" title="Add Divider">
                        <span>—</span>
                    </button>
                </div>
            </div>
            
            <div class="content-editor">
                <div class="editor-sidebar">
                    <h4>Content Blocks</h4>
                    <div class="content-blocks">
                        <div class="content-block" draggable="true" data-type="header">
                            <span class="block-icon">📰</span>
                            <span class="block-name">Header</span>
                        </div>
                        <div class="content-block" draggable="true" data-type="text">
                            <span class="block-icon">📝</span>
                            <span class="block-name">Text Block</span>
                        </div>
                        <div class="content-block" draggable="true" data-type="image">
                            <span class="block-icon">🖼️</span>
                            <span class="block-name">Image</span>
                        </div>
                        <div class="content-block" draggable="true" data-type="stadium">
                            <span class="block-icon">🏟️</span>
                            <span class="block-name">Featured Stadium</span>
                        </div>
                        <div class="content-block" draggable="true" data-type="equipment">
                            <span class="block-icon">⚽</span>
                            <span class="block-name">Equipment Offer</span>
                        </div>
                        <div class="content-block" draggable="true" data-type="button">
                            <span class="block-icon">🔘</span>
                            <span class="block-name">Call-to-Action</span>
                        </div>
                    </div>
                </div>
                
                <div class="editor-canvas">
                    <div class="email-preview">
                        <div class="email-header">
                            <img src="<?php echo URLROOT; ?>/images/logo.png" alt="BookMyGround" class="email-logo">
                            <h2>BookMyGround Newsletter</h2>
                        </div>
                        
                        <div class="email-content" id="emailContent">
                            <div class="content-placeholder">
                                <p>Drag content blocks from the sidebar or use the toolbar above to start building your newsletter.</p>
                                <p>You can add headers, text blocks, images, stadium features, and call-to-action buttons.</p>
                            </div>
                        </div>
                        
                        <div class="email-footer">
                            <p>You're receiving this because you subscribed to BookMyGround newsletter.</p>
                            <p><a href="#">Unsubscribe</a> | <a href="#">Update Preferences</a></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden textarea for form submission -->
            <textarea name="content" id="hiddenContent" style="display: none;"><?php echo htmlspecialchars($data['form_data']['content'] ?? ''); ?></textarea>
        </div>

        <!-- Schedule Settings -->
        <div class="compose-section">
            <div class="section-header">
                <h3>Delivery Schedule</h3>
            </div>
            
            <div class="schedule-options">
                <label class="schedule-option">
                    <input type="radio" 
                           name="schedule_type" 
                           value="now"
                           <?php echo (isset($data['form_data']['schedule_type']) && $data['form_data']['schedule_type'] == 'now') || !isset($data['form_data']['schedule_type']) ? 'checked' : ''; ?>>
                    <span class="schedule-label">
                        <strong>Send Now</strong>
                        <small>Newsletter will be sent immediately after creation</small>
                    </span>
                </label>
                
                <label class="schedule-option">
                    <input type="radio" 
                           name="schedule_type" 
                           value="schedule"
                           <?php echo (isset($data['form_data']['schedule_type']) && $data['form_data']['schedule_type'] == 'schedule') ? 'checked' : ''; ?>>
                    <span class="schedule-label">
                        <strong>Schedule for Later</strong>
                        <small>Choose specific date and time to send</small>
                    </span>
                </label>
            </div>
            
            <div class="schedule-datetime" id="scheduleDateTime" style="display: none;">
                <div class="form-row">
                    <div class="form-group">
                        <label for="schedule_date">Send Date</label>
                        <input type="date" 
                               id="schedule_date" 
                               name="schedule_date" 
                               class="form-control"
                               value="<?php echo $data['form_data']['schedule_date'] ?? ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="schedule_time">Send Time</label>
                        <input type="time" 
                               id="schedule_time" 
                               name="schedule_time" 
                               class="form-control"
                               value="<?php echo $data['form_data']['schedule_time'] ?? '10:00'; ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="button" class="btn-cancel" onclick="window.location.href='<?php echo URLROOT; ?>/admin/newsletter'">
                Cancel
            </button>
            <button type="button" class="btn-save-draft" onclick="saveDraft()">
                Save Draft
            </button>
            <button type="submit" class="btn-send-newsletter">
                <span id="sendButtonText">Send Newsletter</span>
            </button>
        </div>
    </form>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="modal">
    <div class="modal-content large">
        <div class="modal-header">
            <h3>Newsletter Preview</h3>
            <span class="close" onclick="closePreviewModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="preview-controls">
                <button class="preview-device active" data-device="desktop">🖥️ Desktop</button>
                <button class="preview-device" data-device="mobile">📱 Mobile</button>
            </div>
            <div class="preview-frame" id="previewFrame">
                <!-- Preview content will be loaded here -->
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closePreviewModal()">Close</button>
            <button class="btn-send-test" onclick="sendTestEmail()">Send Test Email</button>
        </div>
    </div>
</div>

<script>
// Newsletter Compose JavaScript
let contentBlocks = [];

document.addEventListener('DOMContentLoaded', function() {
    // Schedule type change handler
    const scheduleOptions = document.querySelectorAll('input[name="schedule_type"]');
    scheduleOptions.forEach(option => {
        option.addEventListener('change', function() {
            const scheduleDateTime = document.getElementById('scheduleDateTime');
            const sendButtonText = document.getElementById('sendButtonText');
            
            if (this.value === 'schedule') {
                scheduleDateTime.style.display = 'block';
                sendButtonText.textContent = 'Schedule Newsletter';
            } else {
                scheduleDateTime.style.display = 'none';
                sendButtonText.textContent = 'Send Newsletter';
            }
        });
    });

    // Initialize drag and drop
    initializeDragAndDrop();
    
    // Load existing content if any
    loadExistingContent();
});

function initializeDragAndDrop() {
    const contentBlocks = document.querySelectorAll('.content-block');
    const emailContent = document.getElementById('emailContent');
    
    contentBlocks.forEach(block => {
        block.addEventListener('dragstart', function(e) {
            e.dataTransfer.setData('text/plain', this.dataset.type);
        });
    });
    
    emailContent.addEventListener('dragover', function(e) {
        e.preventDefault();
    });
    
    emailContent.addEventListener('drop', function(e) {
        e.preventDefault();
        const blockType = e.dataTransfer.getData('text/plain');
        insertBlock(blockType);
    });
}

function insertBlock(type) {
    const emailContent = document.getElementById('emailContent');
    const placeholder = emailContent.querySelector('.content-placeholder');
    
    if (placeholder) {
        placeholder.remove();
    }
    
    let blockHTML = '';
    
    switch(type) {
        case 'header':
            blockHTML = `
                <div class="content-block-item" data-type="header">
                    <div class="block-controls">
                        <button onclick="editBlock(this)" class="control-btn">✏️</button>
                        <button onclick="deleteBlock(this)" class="control-btn">🗑️</button>
                    </div>
                    <h2 contenteditable="true" class="editable-header">Your Newsletter Header</h2>
                </div>
            `;
            break;
        case 'text':
            blockHTML = `
                <div class="content-block-item" data-type="text">
                    <div class="block-controls">
                        <button onclick="editBlock(this)" class="control-btn">✏️</button>
                        <button onclick="deleteBlock(this)" class="control-btn">🗑️</button>
                    </div>
                    <p contenteditable="true" class="editable-text">Write your newsletter content here. You can edit this text directly.</p>
                </div>
            `;
            break;
        case 'image':
            blockHTML = `
                <div class="content-block-item" data-type="image">
                    <div class="block-controls">
                        <button onclick="editBlock(this)" class="control-btn">✏️</button>
                        <button onclick="deleteBlock(this)" class="control-btn">🗑️</button>
                    </div>
                    <div class="image-placeholder" onclick="selectImage(this)">
                        <span>📷 Click to add image</span>
                    </div>
                </div>
            `;
            break;
        case 'stadium':
            blockHTML = `
                <div class="content-block-item" data-type="stadium">
                    <div class="block-controls">
                        <button onclick="editBlock(this)" class="control-btn">✏️</button>
                        <button onclick="deleteBlock(this)" class="control-btn">🗑️</button>
                    </div>
                    <div class="stadium-feature">
                        <h3>Featured Stadium</h3>
                        <div class="stadium-info">
                            <strong>Colombo Cricket Ground</strong>
                            <p>Professional cricket ground with modern facilities</p>
                            <a href="#" class="feature-button">Book Now</a>
                        </div>
                    </div>
                </div>
            `;
            break;
        case 'button':
            blockHTML = `
                <div class="content-block-item" data-type="button">
                    <div class="block-controls">
                        <button onclick="editBlock(this)" class="control-btn">✏️</button>
                        <button onclick="deleteBlock(this)" class="control-btn">🗑️</button>
                    </div>
                    <div class="button-container">
                        <a href="#" class="newsletter-cta-button" contenteditable="true">Call to Action</a>
                    </div>
                </div>
            `;
            break;
        case 'divider':
            blockHTML = `
                <div class="content-block-item" data-type="divider">
                    <div class="block-controls">
                        <button onclick="deleteBlock(this)" class="control-btn">🗑️</button>
                    </div>
                    <hr class="content-divider">
                </div>
            `;
            break;
    }
    
    emailContent.insertAdjacentHTML('beforeend', blockHTML);
    updateHiddenContent();
}

function editBlock(button) {
    // Advanced editing functionality would go here
    alert('Advanced block editing will be implemented');
}

function deleteBlock(button) {
    if (confirm('Are you sure you want to delete this block?')) {
        button.closest('.content-block-item').remove();
        updateHiddenContent();
    }
}

function selectImage(placeholder) {
    // Image selection functionality would go here
    placeholder.innerHTML = '<img src="' + '<?php echo URLROOT; ?>' + '/images/sample-newsletter-image.jpg" alt="Newsletter Image" style="max-width: 100%; height: auto;">';
    updateHiddenContent();
}

function updateHiddenContent() {
    const emailContent = document.getElementById('emailContent');
    const hiddenContent = document.getElementById('hiddenContent');
    hiddenContent.value = emailContent.innerHTML;
}

function loadExistingContent() {
    const hiddenContent = document.getElementById('hiddenContent');
    const emailContent = document.getElementById('emailContent');
    
    if (hiddenContent.value.trim()) {
        emailContent.innerHTML = hiddenContent.value;
    }
}

function loadTemplate(templateId) {
    // Template loading functionality would go here
    console.log('Loading template:', templateId);
}

function saveDraft() {
    updateHiddenContent();
    alert('Draft saved successfully!');
    // Here you would make an AJAX call to save the draft
}

function previewNewsletter() {
    updateHiddenContent();
    document.getElementById('previewModal').style.display = 'block';
    
    // Load preview content
    const emailContent = document.getElementById('emailContent').innerHTML;
    const subject = document.getElementById('subject').value;
    
    document.getElementById('previewFrame').innerHTML = `
        <div class="email-preview-full">
            <div class="preview-subject"><strong>Subject:</strong> ${subject}</div>
            <div class="email-header">
                <img src="<?php echo URLROOT; ?>/images/logo.png" alt="BookMyGround" class="email-logo">
                <h2>BookMyGround Newsletter</h2>
            </div>
            ${emailContent}
            <div class="email-footer">
                <p>You're receiving this because you subscribed to BookMyGround newsletter.</p>
                <p><a href="#">Unsubscribe</a> | <a href="#">Update Preferences</a></p>
            </div>
        </div>
    `;
}

function closePreviewModal() {
    document.getElementById('previewModal').style.display = 'none';
}

function sendTestEmail() {
    const email = prompt('Enter email address to send test:');
    if (email) {
        alert('Test email sent to: ' + email);
        // Here you would make an AJAX call to send test email
    }
}

// Preview device switching
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('preview-device')) {
        document.querySelectorAll('.preview-device').forEach(btn => btn.classList.remove('active'));
        e.target.classList.add('active');
        
        const device = e.target.dataset.device;
        const previewFrame = document.getElementById('previewFrame');
        
        if (device === 'mobile') {
            previewFrame.style.maxWidth = '375px';
            previewFrame.style.margin = '0 auto';
        } else {
            previewFrame.style.maxWidth = '100%';
            previewFrame.style.margin = '0';
        }
    }
});

// Auto-save content changes
document.addEventListener('input', function(e) {
    if (e.target.contentEditable === 'true') {
        updateHiddenContent();
    }
});

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('previewModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<?php require APPROOT.'/views/admin/inc/footer.php'; ?>