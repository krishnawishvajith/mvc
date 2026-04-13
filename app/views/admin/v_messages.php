<?php require APPROOT.'/views/admin/inc/header.php'; ?>

<div class="main-content">
    <div class="dashboard-header">
        <h1>Messages</h1>
        <div class="header-actions">
            <button class="btn-compose" onclick="openComposeModal()">✉️ Compose Message</button>
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
                <span class="stat-number"><?php echo $data['unread_count'] ?? 0; ?></span>
                <span class="stat-label">Unread</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">⚡</div>
            <div class="stat-details">
                <span class="stat-number">Live</span>
                <span class="stat-label">Chat Ready</span>
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
                            <span class="contact-email">📧 user@example.com</span>
                            <span class="property-tag">🏏 General</span>
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
    document.querySelector('.contact-email').textContent = `📧 ${messageItem.dataset.otherUserEmail || 'No email provided'}`;
    document.querySelector('.property-tag').textContent = `🏟️ ${messageItem.querySelector('.message-property') ? messageItem.querySelector('.message-property').textContent : 'General'}`;

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
            renderAdminConversation(result.messages, sender);
        } else {
            thread.innerHTML = `<div class="message-empty-thread"><p>Unable to load conversation. Please try again.</p></div>`;
        }
    })
    .catch((error) => {
        console.error('Admin conversation error:', error);
        thread.innerHTML = `<div class="message-empty-thread"><p>Unable to load conversation. Please try again later.</p></div>`;
    });
}

function renderAdminConversation(messages, fallbackSender) {
    const thread = document.getElementById('conversationThread');
    thread.innerHTML = '';

    if (!messages.length) {
        thread.innerHTML = `<div class="message-empty-thread"><p>No conversation history found.</p></div>`;
        return;
    }

    messages.forEach(message => {
        const bubbleClass = message.is_sent ? 'sent' : 'received';
        const senderName = message.is_sent ? 'You (Admin)' : (message.sender_name || fallbackSender || 'User');
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

    fetch(`${appBaseUrl}/admin/send_reply`, {
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

// Templates
function useTemplate(templateId) {
    const templates = {
        'booking-confirmation': 'Thank you for your booking. Here are the details...',
        'payment-request': 'To confirm your booking, please make the payment...',
        'cancellation-policy': 'Our cancellation policy allows free cancellation...',
        'thank-you': 'Thank you for choosing our facility...'
    };

    document.getElementById('replyMessage').value = templates[templateId] || '';
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
                    <option value="all_users">All Users</option>
                    <option value="customers">All Customers</option>
                    <option value="stadium_owners">All Stadium Owners</option>
                    <option value="coaches">All Coaches</option>
                    <option value="rental_owners">All Rental Owners</option>
                    <option value="individual">Specific User</option>
                </select>
            </div>
            
            <div class="form-group" id="userSelect" style="display: none;">
                <label>Select User:</label>
                <input type="text" placeholder="Search and select user..." id="userSearch">
            </div>

            <div class="form-group">
                <label>Subject:</label>
                <input type="text" name="subject" required placeholder="Enter message subject">
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

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeComposeModal()">Cancel</button>
                <button type="submit" class="btn-send-message">Send Message</button>
            </div>
        </form>
    </div>
</div>


<script>
// Message item selection
document.querySelectorAll('.message-item').forEach(item => {
    item.addEventListener('click', function() {
        document.querySelectorAll('.message-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
        
        // Load message content here
        const messageId = this.dataset.message;
        console.log('Loading message:', messageId);
    });
});

// Filter functionality
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        console.log('Filtering messages by:', filter);
    });
});

// Compose modal
function openComposeModal() {
    document.getElementById('composeModal').style.display = 'block';
}

function closeComposeModal() {
    document.getElementById('composeModal').style.display = 'none';
}

// Recipient selection change
document.querySelector('select[name="recipient"]').addEventListener('change', function() {
    const userSelect = document.getElementById('userSelect');
    if (this.value === 'individual') {
        userSelect.style.display = 'block';
    } else {
        userSelect.style.display = 'none';
    }
});

// Send reply
document.querySelector('.btn-send').addEventListener('click', function() {
    const textarea = document.querySelector('.reply-form textarea');
    const message = textarea.value.trim();
    
    if (message) {
        // Add message to conversation
        alert('Reply sent: ' + message);
        textarea.value = '';
    }
});

// Message actions
document.querySelector('.btn-archive').addEventListener('click', function() {
    alert('Message archived');
});

document.querySelector('.btn-priority').addEventListener('click', function() {
    alert('Message marked as priority');
});

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('composeModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<?php require APPROOT.'/views/admin/inc/footer.php'; ?>