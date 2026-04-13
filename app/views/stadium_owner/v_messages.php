<?php require APPROOT.'/views/stadium_owner/inc/header.php'; ?>

<div class="main-content messages-page">
    <div class="dashboard-header">
        <h1>Messages</h1>
        <div class="header-actions">
            <button class="btn-compose" onclick="openComposeModal()">✉️ Compose Message</button>
            <button class="btn-mark-all-read" onclick="markAllAsRead()">📖 Mark All Read</button>
        </div>
    </div>

    <?php
        $conversations = $data['conversations'] ?? [];
    ?>

    <!-- Message Stats -->
    <div class="message-stats">
        <div class="stat-item">
            <div class="stat-icon">📧</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo count($conversations); ?></span>
                <span class="stat-label">Conversations</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">📬</div>
            <div class="stat-details">
                <span class="stat-number"><?php echo $data['unread_count'] ?? 3; ?></span>
                <span class="stat-label">Unread</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">⚡</div>
            <div class="stat-details">
                <span class="stat-number">1</span>
                <span class="stat-label">Priority</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">🏆</div>
            <div class="stat-details">
                <span class="stat-number">98%</span>
                <span class="stat-label">Response Rate</span>
            </div>
        </div>
    </div>

    <div class="messages-layout">
        <!-- Conversation Sidebar -->
        <div class="messages-sidebar">
            <div class="message-filters">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="unread">Unread (<?php echo $data['unread_count'] ?? 0; ?>)</button>
            </div>

            <div class="messages-list" id="messagesList">
                <?php if (empty($conversations)): ?>
                    <div class="message-empty">
                        <p>No conversations found yet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($conversations as $index => $conversation): ?>
                        <?php
                            $senderName = trim(($conversation['other_first_name'] ?? '') . ' ' . ($conversation['other_last_name'] ?? '')) ?: 'Guest User';
                            $excerpt = strlen($conversation['last_message'] ?? '') > 80 ? substr($conversation['last_message'], 0, 77) . '...' : ($conversation['last_message'] ?? 'No message yet');
                            $timeLabel = !empty($conversation['last_created_at']) ? date('g:i A', strtotime($conversation['last_created_at'])) : 'Unknown';
                            $statusClass = !empty($conversation['unread_count']) ? 'unread' : 'read';
                        ?>
                        <div class="message-item<?php echo $index === 0 ? ' active' : ''; ?>" data-other-user-id="<?php echo htmlspecialchars($conversation['other_user_id'] ?? '', ENT_QUOTES); ?>" data-other-user-email="<?php echo htmlspecialchars($conversation['other_email'] ?? '', ENT_QUOTES); ?>" data-stadium-id="<?php echo htmlspecialchars($conversation['stadium_id'] ?? '', ENT_QUOTES); ?>" data-message-id="<?php echo htmlspecialchars($conversation['last_message_id'] ?? '', ENT_QUOTES); ?>" data-subject="<?php echo htmlspecialchars($conversation['subject'] ?? 'No subject', ENT_QUOTES); ?>" data-sender-name="<?php echo htmlspecialchars($senderName, ENT_QUOTES); ?>" data-created-at="<?php echo htmlspecialchars($conversation['last_created_at'] ?? '', ENT_QUOTES); ?>" data-last-message="<?php echo htmlspecialchars($conversation['last_message'] ?? '', ENT_QUOTES); ?>">
                            <div class="message-avatar"><?php echo strtoupper(substr($senderName, 0, 1)); ?></div>
                            <div class="message-preview">
                                <div class="message-header">
                                    <span class="sender-name"><?php echo htmlspecialchars($senderName); ?></span>
                                    <span class="message-time"><?php echo htmlspecialchars($timeLabel); ?></span>
                                </div>
                                <div class="message-subject"><?php echo htmlspecialchars($conversation['subject'] ?? 'No subject'); ?></div>
                                <div class="message-excerpt"><?php echo htmlspecialchars($excerpt); ?></div>
                                <?php if (!empty($conversation['stadium_name'])): ?>
                                    <div class="message-property"><?php echo htmlspecialchars($conversation['stadium_name']); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="message-status <?php echo $statusClass; ?>"></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Conversation Content -->
        <div class="message-content">
            <div class="message-header-bar">
                <div class="conversation-info">
                    <div class="contact-details">
                        <h3 id="conversationTitle">Select a conversation</h3>
                        <p id="conversationWith">Conversation with ...</p>
                        <div class="contact-meta">
                            <span class="contact-phone">📞 Not available</span>
                            <span class="contact-email">📧 owner@example.com</span>
                            <span class="property-tag">🏏 Stadium</span>
                        </div>
                    </div>
                </div>
                <div class="message-actions">
                    <button class="btn-action-sm btn-mark-read" onclick="markAsRead()">Mark as Read</button>
                    <button class="btn-action-sm btn-archive" onclick="archiveMessage()">Archive</button>
                </div>
            </div>

            <div class="conversation-thread" id="conversationThread">
                <div class="message-empty-thread">
                    <p>Select a conversation from the left to load the full message thread.</p>
                </div>
            </div>

            <div class="reply-section">
                <div class="reply-form">
                    <textarea id="replyMessage" placeholder="Type your reply..." rows="4" onkeyup="showTypingIndicator()"></textarea>
                    <div class="reply-actions">
                        <button class="btn-send" onclick="sendReply()">Send Reply</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Templates -->
    <div class="message-templates-section">
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Quick Response Templates</h3>
                <button class="btn-add-template" onclick="openTemplateModal()">+ Add Template</button>
            </div>
            <div class="templates-grid">
                <div class="template-card" onclick="useTemplate('booking-confirmation')">
                    <h4>Booking Confirmation</h4>
                    <p>Thank you for your booking. Here are the details...</p>
                    <span class="template-category">Booking</span>
                </div>
                <div class="template-card" onclick="useTemplate('payment-request')">
                    <h4>Payment Request</h4>
                    <p>To confirm your booking, please make the payment...</p>
                    <span class="template-category">Payment</span>
                </div>
                <div class="template-card" onclick="useTemplate('cancellation-policy')">
                    <h4>Cancellation Policy</h4>
                    <p>Our cancellation policy allows free cancellation...</p>
                    <span class="template-category">Policy</span>
                </div>
                <div class="template-card" onclick="useTemplate('thank-you')">
                    <h4>Thank You Message</h4>
                    <p>Thank you for choosing our facility...</p>
                    <span class="template-category">General</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Compose Message Modal -->
<div id="composeModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Compose New Message</h3>
            <span class="close" onclick="closeComposeModal()">&times;</span>
        </div>
        <form class="compose-form">
            <div class="form-group">
                <label>To:</label>
                <select name="recipient" required>
                    <option value="">Select Recipient</option>
                    <option value="all_customers">All Customers</option>
                    <option value="recent_customers">Recent Customers</option>
                    <option value="frequent_customers">Frequent Customers</option>
                    <option value="individual">Specific Customer</option>
                </select>
            </div>
            
            <div class="form-group" id="customerSelect" style="display: none;">
                <label>Select Customer:</label>
                <input type="text" placeholder="Search customer by name or email..." id="customerSearch">
                <div class="customer-suggestions" id="customerSuggestions"></div>
            </div>

            <div class="form-group">
                <label>Subject:</label>
                <input type="text" name="subject" required placeholder="Enter message subject">
            </div>

            <div class="form-group">
                <label>Property (Optional):</label>
                <select name="property">
                    <option value="">General Message</option>
                    <option value="cricket-ground">Colombo Cricket Ground</option>
                    <option value="football-arena">Football Arena Pro</option>
                    <option value="tennis-courts">Tennis Academy Courts</option>
                </select>
            </div>

            <div class="form-group">
                <label>Priority:</label>
                <select name="priority">
                    <option value="normal">Normal</option>
                    <option value="high">High Priority</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>

            <div class="form-group">
                <label>Message:</label>
                <textarea name="message" rows="8" required placeholder="Type your message here..."></textarea>
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="save_template">
                    <span class="checkmark"></span>
                    Save as template for future use
                </label>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeComposeModal()">Cancel</button>
                <button type="button" class="btn-save-draft">Save Draft</button>
                <button type="submit" class="btn-send-message">Send Message</button>
            </div>
        </form>
    </div>
</div>

<!-- Template Modal -->
<div id="templateModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add Message Template</h3>
            <span class="close" onclick="closeTemplateModal()">&times;</span>
        </div>
        <form class="template-form">
            <div class="form-group">
                <label>Template Name:</label>
                <input type="text" name="template_name" required placeholder="e.g., Booking Confirmation">
            </div>
            
            <div class="form-group">
                <label>Category:</label>
                <select name="category" required>
                    <option value="">Select Category</option>
                    <option value="booking">Booking</option>
                    <option value="payment">Payment</option>
                    <option value="policy">Policy</option>
                    <option value="general">General</option>
                    <option value="complaint">Complaint Response</option>
                </select>
            </div>

            <div class="form-group">
                <label>Template Content:</label>
                <textarea name="template_content" rows="8" required placeholder="Enter your template message..."></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeTemplateModal()">Cancel</button>
                <button type="submit" class="btn-save-template">Save Template</button>
            </div>
        </form>
    </div>
</div>

<script>
const appBaseUrl = '<?php echo URLROOT; ?>';

// Message item selection
function attachConversationHandlers() {
    document.querySelectorAll('.message-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.message-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            loadConversation(this);
        });
    });
}

attachConversationHandlers();

// Filter functionality
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        filterMessages(filter);
    });
});

const initialActiveItem = document.querySelector('.message-item.active');
if (initialActiveItem) {
    loadConversation(initialActiveItem);
}

function filterMessages(filter) {
    const messageItems = document.querySelectorAll('.message-item');
    
    messageItems.forEach(item => {
        if (filter === 'all') {
            item.style.display = 'flex';
        } else if (filter === 'unread') {
            item.style.display = item.querySelector('.message-status.unread') ? 'flex' : 'none';
        } else {
            item.style.display = item.dataset.type === filter ? 'flex' : 'none';
        }
    });
}

function loadConversation(messageItem) {
    const title = messageItem.dataset.subject || 'New conversation';
    const sender = messageItem.dataset.senderName || 'Guest User';
    const stadium = messageItem.dataset.stadiumId ? messageItem.dataset.stadiumId : 'General';
    const messageText = messageItem.dataset.lastMessage || '';
    const createdAt = messageItem.dataset.createdAt ? new Date(messageItem.dataset.createdAt).toLocaleString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) : 'Unknown time';

    document.getElementById('conversationTitle').textContent = title;
    document.getElementById('conversationWith').textContent = `Conversation with ${sender}`;
    document.querySelector('.contact-phone').textContent = '📞 Not available';
    document.querySelector('.contact-email').textContent = `📧 ${messageItem.dataset.otherUserEmail || 'No email provided'}`;
    document.querySelector('.property-tag').textContent = `🏟️ ${messageItem.querySelector('.message-property') ? messageItem.querySelector('.message-property').textContent : 'Stadium'}`;

    const thread = document.getElementById('conversationThread');
    thread.innerHTML = `<div class="message-empty-thread"><p>Loading conversation…</p></div>`;

    const formData = new FormData();
    formData.append('other_user_id', messageItem.dataset.otherUserId || '');
    formData.append('stadium_id', messageItem.dataset.stadiumId || '');

    fetch(`${appBaseUrl}/messages/getConversation`, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(result => {
        if (result.success && Array.isArray(result.messages)) {
            thread.innerHTML = '';
            result.messages.forEach(message => {
                const bubbleClass = message.is_sent ? 'sent' : 'received';
                const senderName = message.is_sent ? 'You' : (message.sender_name || sender);
                const when = message.created_at ? new Date(message.created_at).toLocaleString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) : 'Unknown time';
                const messageHtml = `
                    <div class="message-bubble ${bubbleClass}">
                        <div class="message-info">
                            <span class="sender">${senderName}</span>
                            <span class="timestamp">${when}</span>
                        </div>
                        <div class="message-text">${(message.message || '').replace(/\n/g, '<br>')}</div>
                    </div>
                `;
                thread.insertAdjacentHTML('beforeend', messageHtml);
            });
            thread.insertAdjacentHTML('beforeend', `
                <div class="typing-indicator" id="typingIndicator" style="display: none;">
                    <div class="typing-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <span class="typing-text">You are typing...</span>
                </div>
            `);
            thread.scrollTop = thread.scrollHeight;
        } else {
            thread.innerHTML = `<div class="message-empty-thread"><p>Unable to load conversation. Please try again.</p></div>`;
        }
    })
    .catch((error) => {
        console.error('Owner conversation error:', error);
        thread.innerHTML = `<div class="message-empty-thread"><p>Unable to load conversation. Please try again later.</p></div>`;
    });
}

function renderOwnerConversation(messages, fallbackSender) {
    const thread = document.getElementById('conversationThread');
    thread.innerHTML = '';

    if (!messages.length) {
        thread.innerHTML = `<div class="message-empty-thread"><p>No conversation history found.</p></div>`;
        return;
    }

    messages.forEach(message => {
        const bubbleClass = message.is_sent ? 'sent' : 'received';
        const senderName = message.is_sent ? 'You' : (message.sender_name || fallbackSender || 'Guest User');
        const when = message.created_at ? new Date(message.created_at).toLocaleString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) : 'Unknown time';
        const messageHtml = `
            <div class="message-bubble ${bubbleClass}">
                <div class="message-info">
                    <span class="sender">${senderName}</span>
                    <span class="timestamp">${when}</span>
                </div>
                <div class="message-text">${(message.message || '').replace(/\n/g, '<br>')}</div>
            </div>
        `;
        thread.insertAdjacentHTML('beforeend', messageHtml);
    });

    thread.insertAdjacentHTML('beforeend', `
        <div class="typing-indicator" id="typingIndicator" style="display: none;">
            <div class="typing-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <span class="typing-text">You are typing...</span>
        </div>
    `);
    thread.scrollTop = thread.scrollHeight;
}

// Compose modal
function openComposeModal() {
    document.getElementById('composeModal').style.display = 'block';
}

function closeComposeModal() {
    document.getElementById('composeModal').style.display = 'none';
}

// Template modal
function openTemplateModal() {
    document.getElementById('templateModal').style.display = 'block';
}

function closeTemplateModal() {
    document.getElementById('templateModal').style.display = 'none';
}

// Quick replies
function insertQuickReply(text) {
    document.getElementById('replyMessage').value = text;
    document.getElementById('replyMessage').focus();
}

// Send reply
function sendReply() {
    const message = document.getElementById('replyMessage').value.trim();
    const activeItem = document.querySelector('.message-item.active');

    if (!activeItem) {
        alert('Please select a message first.');
        return;
    }

    if (!message) {
        alert('Please type a reply before sending.');
        return;
    }

    const messageId = activeItem.dataset.messageId;
    const otherUserId = activeItem.dataset.otherUserId || '';
    const stadiumId = activeItem.dataset.stadiumId || '';
    const formData = new FormData();
    formData.append('message_id', messageId);
    formData.append('other_user_id', otherUserId);
    formData.append('stadium_id', stadiumId);
    formData.append('reply_content', message);

    fetch(`${appBaseUrl}/stadium_owner/send_reply`, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            const now = new Date();
            activeItem.dataset.lastMessage = message;
            activeItem.dataset.createdAt = now.toISOString();
            const statusLabel = activeItem.querySelector('.message-status');
            if (statusLabel) {
                statusLabel.className = 'message-status read';
            }
            document.getElementById('replyMessage').value = '';
            document.getElementById('typingIndicator').style.display = 'none';
            updateUnreadCount();
            loadConversation(activeItem);
        } else {
            alert(result.message || 'Failed to send reply.');
        }
    })
    .catch((error) => {
        console.error('Reply send error:', error);
        alert('Unable to send reply. Please try again later.');
    });
}

// Typing indicator
let typingTimer;
function showTypingIndicator() {
    const indicator = document.getElementById('typingIndicator');
    indicator.style.display = 'flex';
    
    clearTimeout(typingTimer);
    typingTimer = setTimeout(() => {
        indicator.style.display = 'none';
    }, 2000);
}

// Message actions
function markAsRead() {
    const activeItem = document.querySelector('.message-item.active');
    if (!activeItem) {
        alert('Select a message first.');
        return;
    }

    const messageId = activeItem.dataset.messageId;
    const formData = new FormData();
    formData.append('message_id', messageId);

    fetch(`${appBaseUrl}/messages/markRead`, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            activeItem.querySelector('.message-status').className = 'message-status read';
            updateUnreadCount();
            alert('Message marked as read');
        } else {
            alert(result.message || 'Unable to mark as read.');
        }
    })
    .catch(() => {
        alert('Unable to mark message as read. Please try again later.');
    });
}

function togglePriority() {
    const status = document.querySelector('.message-item.active .message-status');
    if (status.classList.contains('priority')) {
        status.className = 'message-status read';
        alert('Priority removed');
    } else {
        status.className = 'message-status priority';
        alert('Message marked as priority');
    }
}

function updateUnreadCount() {
    const unreadCount = document.querySelectorAll('.message-item .message-status.unread').length;
    const unreadButton = document.querySelector('.filter-btn[data-filter="unread"]');
    const unreadStat = document.querySelector('.message-stats .stat-item:nth-child(2) .stat-number');

    if (unreadButton) {
        unreadButton.textContent = `Unread (${unreadCount})`;
    }
    if (unreadStat) {
        unreadStat.textContent = unreadCount;
    }
}

function archiveMessage() {
    if (confirm('Archive this message?')) {
        document.querySelector('.message-item.active').style.opacity = '0.5';
        alert('Message archived');
    }
}

function blockSender() {
    if (confirm('Block this sender? They will not be able to send you messages.')) {
        alert('Sender blocked');
    }
}

function markAllAsRead() {
    if (confirm('Mark all messages as read?')) {
        document.querySelectorAll('.message-status.unread').forEach(status => {
            status.className = 'message-status read';
        });
        updateUnreadCount();
        alert('All messages marked as read');
    }
}

// Template functions
function useTemplate(templateId) {
    const templates = {
        'booking-confirmation': 'Thank you for your booking request. I\'m pleased to confirm your reservation...',
        'payment-request': 'To secure your booking, please complete the payment using the following details...',
        'cancellation-policy': 'Our cancellation policy allows free cancellation up to 12 hours before your booking...',
        'thank-you': 'Thank you for choosing our facility. We appreciate your business and hope you had a great experience!'
    };
    
    document.getElementById('replyMessage').value = templates[templateId] || '';
}

// Recipient selection change
document.querySelector('select[name="recipient"]').addEventListener('change', function() {
    const customerSelect = document.getElementById('customerSelect');
    if (this.value === 'individual') {
        customerSelect.style.display = 'block';
    } else {
        customerSelect.style.display = 'none';
    }
});

// Templates
function showTemplates() {
    alert('Template selector will be implemented');
}

// Close modals when clicking outside
window.onclick = function(event) {
    const composeModal = document.getElementById('composeModal');
    const templateModal = document.getElementById('templateModal');
    
    if (event.target == composeModal) {
        composeModal.style.display = "none";
    }
    if (event.target == templateModal) {
        templateModal.style.display = "none";
    }
}

// Auto-scroll to bottom of conversation on load
document.addEventListener('DOMContentLoaded', function() {
    const thread = document.getElementById('conversationThread');
    thread.scrollTop = thread.scrollHeight;
});
</script>

<style>
.main-content {
    background: #0f172a;
    padding: 30px 30px 20px;
    color: #e2e8f0;
}

.dashboard-header {
    background: transparent;
    margin-bottom: 18px;
}

.messages-layout {
    display: grid;
    grid-template-columns: 360px 1fr;
    gap: 0;
    background: #111827;
    border: 1px solid #1f2937;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
    margin-bottom: 30px;
    min-height: 650px;
}

.messages-sidebar {
    background: #111827;
    border-right: 1px solid #1f2937;
    display: flex;
    flex-direction: column;
    padding-bottom: 20px;
}

.message-filters {
    padding: 22px 20px 16px;
    border-bottom: 1px solid #1f2937;
    display: flex;
    flex-direction: column;
    gap: 10px;
    background: #111827;
}

.filter-btn {
    padding: 10px 16px;
    background: transparent;
    border: none;
    border-radius: 8px;
    text-align: left;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
    color: #cbd5e1;
}

.filter-btn.active,
.filter-btn:hover {
    background: #1f2937;
    color: #f8fafc;
    box-shadow: 0 2px 6px rgba(255, 255, 255, 0.05);
}

.messages-list {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
}

.message-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    border-radius: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 12px;
    position: relative;
    background: #111827;
    border: 1px solid #1f2937;
}

.message-item:hover {
    background: #1f2937;
}

.message-item.active {
    background: #1f2937;
    border-color: #3b82f6;
    box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.3);
}

.message-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #2563eb;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 16px;
    flex-shrink: 0;
}

.message-preview {
    flex: 1;
    min-width: 0;
}

.message-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
}

.sender-name {
    font-weight: 600;
    color: #f8fafc;
    font-size: 14px;
}

.message-time {
    font-size: 12px;
    color: #94a3b8;
}

.message-subject {
    font-weight: 500;
    color: #e2e8f0;
    font-size: 13px;
    margin-bottom: 4px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.message-excerpt {
    font-size: 12px;
    color: #94a3b8;
    line-height: 1.4;
    margin-bottom: 6px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.message-property {
    font-size: 11px;
    color: #d1fae5;
    background: rgba(16, 185, 129, 0.15);
    padding: 2px 6px;
    border-radius: 10px;
    display: inline-block;
}

.message-status {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    position: absolute;
    top: 16px;
    right: 16px;
}

.message-status.unread {
    background: #007bff;
}

.message-status.read {
    background: #28a745;
}

.message-status.priority {
    background: #dc3545;
}

.message-content {
    display: flex;
    flex-direction: column;
    background: #0f172a;
}

.message-header-bar {
    padding: 24px 26px;
    border-bottom: 1px solid #1f2937;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    background: #111827;
}

.contact-details h3 {
    margin: 0 0 4px 0;
    color: #e2e8f0;
    font-size: 18px;
}

.contact-details p {
    margin: 0 0 8px 0;
    color: #94a3b8;
    font-size: 14px;
}

.contact-meta {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.contact-meta span {
    font-size: 12px;
    color: #94a3b8;
}

.property-tag {
    background: rgba(52, 211, 153, 0.15);
    color: #a7f3d0 !important;
    padding: 2px 8px;
    border-radius: 12px;
}

.conversation-thread {
    flex: 1;
    padding: 24px;
    overflow-y: auto;
    background: #111827;
    max-height: 460px;
}

.message-bubble {
    margin-bottom: 18px;
    max-width: 72%;
    border-radius: 22px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
}

.message-bubble.received {
    align-self: flex-start;
}

.message-bubble.sent {
    align-self: flex-end;
    margin-left: auto;
    text-align: right;
}

.message-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    font-size: 12px;
    color: #94a3b8;
}

.message-text {
    background: #1e293b;
    padding: 14px 18px;
    border-radius: 18px;
    line-height: 1.6;
    font-size: 14px;
    color: #e2e8f0;
}

.message-bubble.sent .message-text {
    background: #2563eb;
    color: white;
}

.typing-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 16px 0;
    font-size: 12px;
    color: #94a3b8;
}

.typing-dots {
    display: flex;
    gap: 4px;
}

.typing-dots span {
    width: 6px;
    height: 6px;
    background: #64748b;
    border-radius: 50%;
    animation: typing 1.5s infinite ease-in-out;
}

.typing-dots span:nth-child(2) {
    animation-delay: 0.3s;
}

.typing-dots span:nth-child(3) {
    animation-delay: 0.6s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
        opacity: 0.5;
    }
    30% {
        transform: translateY(-10px);
        opacity: 1;
    }
}

.reply-section {
    border-top: 1px solid #1f2937;
    padding: 20px;
    background: #111827;
}

.quick-replies {
    display: flex;
    gap: 8px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}

.quick-reply-btn {
    background: #111827;
    border: 1px solid #1f2937;
    color: #cbd5e1;
    padding: 8px 14px;
    border-radius: 16px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.quick-reply-btn:hover {
    background: #1f2937;
}

.reply-input-wrapper {
    background: #0f172a;
    border-radius: 12px;
    padding: 16px;
}

.reply-input-wrapper textarea {
    width: 100%;
    border: none;
    background: transparent;
    resize: none;
    outline: none;
    font-family: inherit;
    font-size: 14px;
    color: #e2e8f0;
    margin-bottom: 12px;
}

.reply-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.reply-actions button {
    padding: 10px 18px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s ease;
}

.btn-action-sm {
    background: #111827;
    color: #e2e8f0;
    border: 1px solid #1f2937;
    border-radius: 10px;
    padding: 9px 14px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s ease;
    margin-left: 8px;
}

.btn-action-sm:hover {
    background: #1f2937;
    border-color: #374151;
}

.btn-template {
    background: #111827;
    color: #cbd5e1;
    border: 1px solid #1f2937;
}

.btn-template:hover {
    background: #1f2937;
    color: #f8fafc;
}

.btn-send {
    background: #2563eb;
    color: white;
    font-weight: 600;
    min-width: 140px;
}

.btn-send:hover {
    background: #1d4ed8;
}

.message-templates-section {
    margin-top: 30px;
}

.templates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 16px;
}

.template-card {
    background: #111827;
    border: 1px solid #1f2937;
    border-radius: 12px;
    padding: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.template-card:hover {
    border-color: #2563eb;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.18);
}

.template-card h4 {
    margin: 0 0 8px 0;
    color: #e2e8f0;
    font-size: 16px;
}

.template-card p {
    margin: 0 0 12px 0;
    color: #94a3b8;
    font-size: 14px;
    line-height: 1.4;
}

.template-category {
    background: #1f2937;
    color: #cbd5e1;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
}

.customer-suggestions {
    background: #111827;
    border: 1px solid #1f2937;
    border-radius: 8px;
    max-height: 150px;
    overflow-y: auto;
    display: none;
}

.customer-suggestion {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #111827;
    color: #e2e8f0;
}

.customer-suggestion:hover {
    background: #1f2937;
}

@media (max-width: 768px) {
    .messages-layout {
        grid-template-columns: 1fr;
    }
    
    .messages-sidebar {
        display: none;
    }
    
    .contact-meta {
        flex-direction: column;
        gap: 8px;
    }
    
    .quick-replies {
        flex-direction: column;
    }
    
    .templates-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require APPROOT.'/views/stadium_owner/inc/footer.php'; ?>