<?php require APPROOT.'/views/rentalowner/inc/header.php'; ?>

<div class="kal-rental-dash-message-main-content">
    <?php $conversations = $data['conversations'] ?? []; ?>
    <div class="kal-rental-dash-message-dashboard-header">
        <h1>Message Center</h1>
        <div class="kal-rental-dash-message-header-actions">
            <button class="kal-rental-dash-message-btn-compose" onclick="openComposeModal()">✉️ Compose Message</button>
        </div>
    </div>

    <!-- Message Stats -->
    <div class="kal-rental-dash-message-message-stats">
        <div class="kal-rental-dash-message-stat-item">
            <div class="kal-rental-dash-message-stat-icon">📧</div>
            <div class="kal-rental-dash-message-stat-details">
                <span class="kal-rental-dash-message-stat-number"><?php echo count($conversations); ?></span>
                <span class="kal-rental-dash-message-stat-label">Conversations</span>
            </div>
        </div>
        <div class="kal-rental-dash-message-stat-item">
            <div class="kal-rental-dash-message-stat-icon">📬</div>
            <div class="kal-rental-dash-message-stat-details">
                <span class="kal-rental-dash-message-stat-number"><?php echo $data['unread_count'] ?? 0; ?></span>
                <span class="kal-rental-dash-message-stat-label">Unread</span>
            </div>
        </div>
        <div class="kal-rental-dash-message-stat-item">
            <div class="kal-rental-dash-message-stat-icon">⚡</div>
            <div class="kal-rental-dash-message-stat-details">
                <span class="kal-rental-dash-message-stat-number">8</span>
                <span class="kal-rental-dash-message-stat-label">Priority</span>
            </div>
        </div>
        <div class="kal-rental-dash-message-stat-item">
            <div class="kal-rental-dash-message-stat-icon">💬</div>
            <div class="kal-rental-dash-message-stat-details">
                <span class="kal-rental-dash-message-stat-number">24</span>
                <span class="kal-rental-dash-message-stat-label">Conversations</span>
            </div>
        </div>
    </div>

    <div class="kal-rental-dash-message-messages-layout">
        <!-- Message Sidebar -->
        <div class="kal-rental-dash-message-messages-sidebar">
            <div class="kal-rental-dash-message-message-filters">
                <button class="kal-rental-dash-message-filter-btn kal-rental-dash-message-active" data-filter="all">All Messages</button>
                <button class="kal-rental-dash-message-filter-btn" data-filter="unread">Unread (12)</button>
                <button class="kal-rental-dash-message-filter-btn" data-filter="priority">Priority</button>
                <button class="kal-rental-dash-message-filter-btn" data-filter="support">Support</button>
                <button class="kal-rental-dash-message-filter-btn" data-filter="complaints">Complaints</button>
            </div>

            <div class="kal-rental-dash-message-messages-list" id="messagesList">
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
                            $statusClass = !empty($conversation['unread_count']) ? 'kal-rental-dash-message-unread' : 'kal-rental-dash-message-read';
                        ?>
                        <div class="kal-rental-dash-message-message-item<?php echo $index === 0 ? ' kal-rental-dash-message-active' : ''; ?>" data-other-user-id="<?php echo htmlspecialchars($conversation['other_user_id'] ?? '', ENT_QUOTES); ?>" data-other-user-email="<?php echo htmlspecialchars($conversation['other_email'] ?? '', ENT_QUOTES); ?>" data-stadium-id="<?php echo htmlspecialchars($conversation['stadium_id'] ?? '', ENT_QUOTES); ?>" data-message-id="<?php echo htmlspecialchars($conversation['last_message_id'] ?? '', ENT_QUOTES); ?>" data-property-name="<?php echo htmlspecialchars($conversation['property_name'] ?? $conversation['stadium_name'] ?? '', ENT_QUOTES); ?>" data-subject="<?php echo htmlspecialchars($conversation['subject'] ?? 'No subject', ENT_QUOTES); ?>" data-sender-name="<?php echo htmlspecialchars($senderName, ENT_QUOTES); ?>" data-created-at="<?php echo htmlspecialchars($conversation['last_created_at'] ?? '', ENT_QUOTES); ?>" data-last-message="<?php echo htmlspecialchars($conversation['last_message'] ?? '', ENT_QUOTES); ?>">
                            <div class="kal-rental-dash-message-message-avatar"><?php echo strtoupper(substr($senderName, 0, 1)); ?></div>
                            <div class="kal-rental-dash-message-message-preview">
                                <div class="kal-rental-dash-message-message-header">
                                    <span class="kal-rental-dash-message-sender-name"><?php echo htmlspecialchars($senderName); ?></span>
                                    <span class="kal-rental-dash-message-message-time"><?php echo htmlspecialchars($timeLabel); ?></span>
                                </div>
                                <div class="kal-rental-dash-message-message-subject"><?php echo htmlspecialchars($conversation['subject'] ?? 'No subject'); ?></div>
                                <div class="kal-rental-dash-message-message-excerpt"><?php echo htmlspecialchars($excerpt); ?></div>
                                <?php if (!empty($conversation['property_name'])): ?>
                                    <div class="kal-rental-dash-message-message-property"><?php echo htmlspecialchars($conversation['property_name']); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="kal-rental-dash-message-message-status <?php echo $statusClass; ?>"></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Message Content -->
        <div class="kal-rental-dash-message-message-content">
            <div class="kal-rental-dash-message-message-header-bar">
                <div class="kal-rental-dash-message-conversation-info">
                    <h3>Equipment Rental Issue</h3>
                    <p>Conversation with John Doe</p>
                </div>
                <div class="kal-rental-dash-message-message-actions">
                    <button class="kal-rental-dash-message-btn-action-sm kal-rental-dash-message-btn-archive">Archive</button>
                    <button class="kal-rental-dash-message-btn-action-sm kal-rental-dash-message-btn-priority">Mark Priority</button>
                    <button class="kal-rental-dash-message-btn-action-sm kal-rental-dash-message-btn-delete">Delete</button>
                </div>
            </div>

            <div class="kal-rental-dash-message-conversation-thread">
                <div class="kal-rental-dash-message-message-bubble kal-rental-dash-message-received">
                    <div class="kal-rental-dash-message-message-info">
                        <span class="kal-rental-dash-message-sender">Kalana Ekanayake</span>
                        <span class="kal-rental-dash-message-timestamp">Today at 11:45 AM</span>
                    </div>
                    <div class="kal-rental-dash-message-message-text">
                        Hi Rental Owner,<br><br>
                       I’m writing to inform you that one of the rental equipments I used today at the Colombo Cricket Ground appears to be damaged. The issue was noticed after my booking session from 2:00 PM to 4:00 PM.<br><br>
                        My booking ID is #BK0045. Could you please look into this and advise on the next steps?<br><br>
                        Thanks!
                    </div>
                </div>

                <div class="kal-rental-dash-message-message-bubble kal-rental-dash-message-sent">
                    <div class="kal-rental-dash-message-message-info">
                        <span class="kal-rental-dash-message-sender">Admin (You)</span>
                        <span class="kal-rental-dash-message-timestamp">Today at 2:35 PM</span>
                    </div>
                    <div class="kal-rental-dash-message-message-text">
                        Hi Krishna,<br><br>
                        I've received your cancellation request for booking #BK0045. Since it's more than 6 hours before your booking time, 
                        you're eligible for a full refund according to our policy.<br><br>
                        I'll process the cancellation and refund now. You should see the refund in your account within 3-5 business days.
                    </div>
                </div>

                <div class="kal-rental-dash-message-message-bubble kal-rental-dash-message-received">
                    <div class="kal-rental-dash-message-message-info">
                        <span class="kal-rental-dash-message-sender">Krishna Wishvajith</span>
                        <span class="kal-rental-dash-message-timestamp">Today at 2:40 PM</span>
                    </div>
                    <div class="kal-rental-dash-message-message-text">
                        Perfect! Thank you so much for the quick response. Really appreciate the excellent customer service! 🙏
                    </div>
                </div>
            </div>

            <div class="kal-rental-dash-message-reply-section">
                <div class="kal-rental-dash-message-reply-form">
                    <textarea placeholder="Type your reply..." rows="4"></textarea>
                    <div class="kal-rental-dash-message-reply-actions">
                        <button class="kal-rental-dash-message-btn-attach">📎</button>
                        <button class="kal-rental-dash-message-btn-emoji">😊</button>
                        <button class="kal-rental-dash-message-btn-send">Send Reply</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Compose Message Modal -->
<div id="kal-rental-dash-message-composeModal" class="kal-rental-dash-message-modal">
    <div class="kal-rental-dash-message-modal-content">
        <div class="kal-rental-dash-message-modal-header">
            <h3>Compose New Message</h3>
            <span class="kal-rental-dash-message-close" onclick="closeComposeModal()">&times;</span>
        </div>
        <form class="kal-rental-dash-message-compose-form">
            <div class="kal-rental-dash-message-form-group">
                <label>To:</label>
                <select name="recipient" required>
                    <option value="">Select Recipient</option>
                    <option value="all_users">All Users</option>
                    <option value="customers">All Customers</option>
                    <option value="stadium_owners">All Stadium Owners</option>
                    <option value="coaches">All Coaches</option>
                    <option value="rental_owners">All Rental Owners</option>
                    <option value="individual">Specific User</option>
                </select>
            </div>
            
            <div class="kal-rental-dash-message-form-group" id="kal-rental-dash-message-userSelect" style="display: none;">
                <label>Select User:</label>
                <input type="text" placeholder="Search and select user..." id="kal-rental-dash-message-userSearch">
            </div>

            <div class="kal-rental-dash-message-form-group">
                <label>Subject:</label>
                <input type="text" name="subject" required placeholder="Enter message subject">
            </div>

            <div class="kal-rental-dash-message-form-group">
                <label>Priority:</label>
                <select name="priority">
                    <option value="normal">Normal</option>
                    <option value="high">High Priority</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>

            <div class="kal-rental-dash-message-form-group">
                <label>Message:</label>
                <textarea name="message" rows="8" required placeholder="Type your message here..."></textarea>
            </div>

            <div class="kal-rental-dash-message-modal-actions">
                <button type="button" class="kal-rental-dash-message-btn-cancel" onclick="closeComposeModal()">Cancel</button>
                <button type="submit" class="kal-rental-dash-message-btn-send-message">Send Message</button>
            </div>
        </form>
    </div>
</div>

<script>
const appBaseUrl = '<?php echo URLROOT; ?>';

function attachRentalConversationHandlers() {
    document.querySelectorAll('.kal-rental-dash-message-message-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.kal-rental-dash-message-message-item').forEach(i => i.classList.remove('kal-rental-dash-message-active'));
            this.classList.add('kal-rental-dash-message-active');
            loadRentalConversation(this);
        });
    });
}

attachRentalConversationHandlers();

function filterMessages(filter) {
    document.querySelectorAll('.kal-rental-dash-message-message-item').forEach(item => {
        if (filter === 'all') {
            item.style.display = 'flex';
        } else if (filter === 'unread') {
            item.style.display = item.querySelector('.kal-rental-dash-message-message-status.kal-rental-dash-message-unread') ? 'flex' : 'none';
        } else {
            item.style.display = item.dataset.type === filter ? 'flex' : 'none';
        }
    });
}

document.querySelectorAll('.kal-rental-dash-message-filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.kal-rental-dash-message-filter-btn').forEach(b => b.classList.remove('kal-rental-dash-message-active'));
        this.classList.add('kal-rental-dash-message-active');

        filterMessages(this.dataset.filter);
    });
});

const initialRentalItem = document.querySelector('.kal-rental-dash-message-message-item.kal-rental-dash-message-active');
if (initialRentalItem) {
    loadRentalConversation(initialRentalItem);
}

function loadRentalConversation(item) {
    const conversationTitle = document.getElementById('conversationTitle');
    const conversationWith = document.getElementById('conversationWith');
    const contactEmail = document.querySelector('.contact-email');
    const propertyTag = document.querySelector('.property-tag');
    const thread = document.getElementById('conversationThread');

    const subject = item.dataset.subject || 'No subject';
    const sender = item.dataset.senderName || 'Guest User';
    const email = item.dataset.otherUserEmail || 'No email available';
    const property = item.dataset.propertyName || 'Rental Service';

    conversationTitle.textContent = subject;
    conversationWith.textContent = `Conversation with ${sender}`;
    if (contactEmail) contactEmail.textContent = `📧 ${email}`;
    if (propertyTag) propertyTag.textContent = `🏬 ${property}`;

    thread.innerHTML = `<div class="message-empty-thread"><p>Loading conversation...</p></div>`;

    const formData = new FormData();
    formData.append('other_user_id', item.dataset.otherUserId || '');
    formData.append('stadium_id', item.dataset.stadiumId || '');

    fetch(`${appBaseUrl}/messages/getConversation`, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(result => {
        if (result.success && Array.isArray(result.messages)) {
            renderRentalConversation(result.messages);
        } else {
            thread.innerHTML = `<div class="message-empty-thread"><p>Unable to load conversation.</p></div>`;
        }
    })
    .catch(error => {
        console.error('Conversation load error:', error);
        thread.innerHTML = `<div class="message-empty-thread"><p>Unable to load conversation. Please try again.</p></div>`;
    });
}

function renderRentalConversation(messages) {
    const thread = document.getElementById('conversationThread');
    thread.innerHTML = '';

    if (!messages.length) {
        thread.innerHTML = `<div class="message-empty-thread"><p>No conversation history found.</p></div>`;
        return;
    }

    messages.forEach(message => {
        const bubbleClass = message.is_sent ? 'kal-rental-dash-message-sent' : 'kal-rental-dash-message-received';
        const senderName = message.is_sent ? 'You' : (message.sender_name || 'User');
        const when = message.created_at ? new Date(message.created_at).toLocaleString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }) : 'Unknown time';
        thread.insertAdjacentHTML('beforeend', `
            <div class="kal-rental-dash-message-message-bubble ${bubbleClass}">
                <div class="kal-rental-dash-message-message-info">
                    <span class="kal-rental-dash-message-sender">${senderName}</span>
                    <span class="kal-rental-dash-message-timestamp">${when}</span>
                </div>
                <div class="kal-rental-dash-message-message-text">${(message.message || '').replace(/\n/g, '<br>')}</div>
            </div>
        `);
    });

    thread.scrollTop = thread.scrollHeight;
}

function openComposeModal() {
    document.getElementById('kal-rental-dash-message-composeModal').style.display = 'block';
}

function closeComposeModal() {
    document.getElementById('kal-rental-dash-message-composeModal').style.display = 'none';
}

document.querySelector('select[name="recipient"]').addEventListener('change', function() {
    const userSelect = document.getElementById('kal-rental-dash-message-userSelect');
    if (this.value === 'individual') {
        userSelect.style.display = 'block';
    } else {
        userSelect.style.display = 'none';
    }
});

function sendReply() {
    const activeItem = document.querySelector('.kal-rental-dash-message-message-item.kal-rental-dash-message-active');
    if (!activeItem) {
        alert('Select a conversation first.');
        return;
    }

    const messageField = document.querySelector('.kal-rental-dash-message-reply-form textarea');
    const message = messageField.value.trim();

    if (!message) {
        alert('Please type a reply before sending.');
        return;
    }

    const formData = new FormData();
    formData.append('receiver_id', activeItem.dataset.otherUserId || '');
    formData.append('stadium_id', activeItem.dataset.stadiumId || '');
    formData.append('subject', `Re: ${activeItem.dataset.subject || 'Message'}`);
    formData.append('message', message);

    fetch(`${appBaseUrl}/messages/send`, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            messageField.value = '';
            loadRentalConversation(activeItem);
            alert('Reply sent successfully');
        } else {
            alert(result.message || 'Failed to send reply');
        }
    })
    .catch(error => {
        console.error('Send reply error:', error);
        alert('Unable to send reply. Please try again later.');
    });
}

const replyButton = document.querySelector('.kal-rental-dash-message-btn-send');
if (replyButton) {
    replyButton.addEventListener('click', sendReply);
}

function markAsRead() {
    const activeItem = document.querySelector('.kal-rental-dash-message-message-item.kal-rental-dash-message-active');
    if (!activeItem) {
        alert('Select a conversation first.');
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
            activeItem.querySelector('.kal-rental-dash-message-message-status').classList.remove('kal-rental-dash-message-unread');
            activeItem.querySelector('.kal-rental-dash-message-message-status').classList.add('kal-rental-dash-message-read');
            alert('Message marked as read');
        } else {
            alert(result.message || 'Unable to update read status');
        }
    })
    .catch(error => {
        console.error('Mark read error:', error);
        alert('Unable to mark message as read. Please try again later.');
    });
}

function archiveMessage() {
    alert('Archive action is not yet implemented.');
}

window.onclick = function(event) {
    const modal = document.getElementById('kal-rental-dash-message-composeModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>


<?php require APPROOT.'/views/rentalowner/inc/footer.php'; ?>