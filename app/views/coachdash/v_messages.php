<?php require APPROOT . '/views/coachdash/inc/header.php'; ?>


<div class="kal-coach-dash-message-main-content">
    <div class="kal-coach-dash-message-dashboard-header">
        <h1>Message Center</h1>
        <div class="kal-coach-dash-message-header-actions">
            <button class="kal-coach-dash-message-btn-compose" onclick="openComposeModal()">✉️ Compose Message</button>
        </div>
    </div>

    <!-- Message Stats -->
    <div class="kal-coach-dash-message-message-stats">
        <div class="kal-coach-dash-message-stat-item">
            <div class="kal-coach-dash-message-stat-icon">📧</div>
            <div class="kal-coach-dash-message-stat-details">
                <span class="kal-coach-dash-message-stat-number">48</span>
                <span class="kal-coach-dash-message-stat-label">Total Messages</span>
            </div>
        </div>
        <div class="kal-coach-dash-message-stat-item">
            <div class="kal-coach-dash-message-stat-icon">📬</div>
            <div class="kal-coach-dash-message-stat-details">
                <span class="kal-coach-dash-message-stat-number">12</span>
                <span class="kal-coach-dash-message-stat-label">Unread</span>
            </div>
        </div>
        <div class="kal-coach-dash-message-stat-item">
            <div class="kal-coach-dash-message-stat-icon">⚡</div>
            <div class="kal-coach-dash-message-stat-details">
                <span class="kal-coach-dash-message-stat-number">8</span>
                <span class="kal-coach-dash-message-stat-label">Priority</span>
            </div>
        </div>
        <div class="kal-coach-dash-message-stat-item">
            <div class="kal-coach-dash-message-stat-icon">💬</div>
            <div class="kal-coach-dash-message-stat-details">
                <span class="kal-coach-dash-message-stat-number">24</span>
                <span class="kal-coach-dash-message-stat-label">Conversations</span>
            </div>
        </div>
    </div>

    <div class="kal-coach-dash-message-messages-layout">
        <!-- Message Sidebar -->
        <div class="kal-coach-dash-message-messages-sidebar">
            <div class="kal-coach-dash-message-message-filters">
                <button class="kal-coach-dash-message-filter-btn kal-coach-dash-message-active" data-filter="all">All Messages</button>
                <button class="kal-coach-dash-message-filter-btn" data-filter="unread">Unread (12)</button>
                <button class="kal-coach-dash-message-filter-btn" data-filter="priority">Priority</button>
                <button class="kal-coach-dash-message-filter-btn" data-filter="support">Support</button>
                <button class="kal-coach-dash-message-filter-btn" data-filter="complaints">Complaints</button>
            </div>

            <div class="kal-coach-dash-message-messages-list">
                <div class="kal-coach-dash-message-message-item kal-coach-dash-message-active" data-message="1">
                    <div class="kal-coach-dash-message-message-avatar">J</div>
                    <div class="kal-coach-dash-message-message-preview">
                        <div class="kal-coach-dash-message-message-header">
                            <span class="kal-coach-dash-message-sender-name">Krishna Wishvajith</span>
                            <span class="kal-coach-dash-message-message-time">2:30 PM</span>
                        </div>
                        <div class="kal-coach-dash-message-message-subject">Training session inquiry</div>
                        <div class="kal-coach-dash-message-message-excerpt">Hi, I'm interested in booking a training session...</div>
                    </div>
                    <div class="kal-coach-dash-message-message-status kal-coach-dash-message-unread"></div>
                </div>

                <div class="kal-coach-dash-message-message-item" data-message="2">
                    <div class="kal-coach-dash-message-message-avatar">S</div>
                    <div class="kal-coach-dash-message-message-preview">
                        <div class="kal-coach-dash-message-message-header">
                            <span class="kal-coach-dash-message-sender-name">Sports Academy</span>
                            <span class="kal-coach-dash-message-message-time">1:15 PM</span>
                        </div>
                        <div class="kal-coach-dash-message-message-subject">Request for additional sessions</div>
                        <div class="kal-coach-dash-message-message-excerpt">Can you add more training sessions for next week...</div>
                    </div>
                    <div class="kal-coach-dash-message-message-status kal-coach-dash-message-read"></div>
                </div>

                <div class="kal-coach-dash-message-message-item" data-message="3">
                    <div class="kal-coach-dash-message-message-avatar">M</div>
                    <div class="kal-coach-dash-message-message-preview">
                        <div class="kal-coach-dash-message-message-header">
                            <span class="kal-coach-dash-message-sender-name">Kalana Ekanayake</span>
                            <span class="kal-coach-dash-message-message-time">11:45 AM</span>
                        </div>
                        <div class="kal-coach-dash-message-message-subject">Session Feedback</div>
                        <div class="kal-coach-dash-message-message-excerpt">The training session yesterday was excellent...</div>
                    </div>
                    <div class="kal-coach-dash-message-message-status kal-coach-dash-message-priority"></div>
                </div>

            </div>
        </div>

        <!-- Message Content -->
        <div class="kal-coach-dash-message-message-content">
            <div class="kal-coach-dash-message-message-header-bar">
                <div class="kal-coach-dash-message-conversation-info">
                    <h3>Session Feedback</h3>
                    <p>Conversation with Kalana Ekanayake</p>
                </div>
                <div class="kal-coach-dash-message-message-actions">
                    <button class="kal-coach-dash-message-btn-action-sm kal-coach-dash-message-btn-archive">Archive</button>
                    <button class="kal-coach-dash-message-btn-action-sm kal-coach-dash-message-btn-priority">Mark Priority</button>
                    <button class="kal-coach-dash-message-btn-action-sm kal-coach-dash-message-btn-delete">Delete</button>
                </div>
            </div>

            <div class="kal-coach-dash-message-conversation-thread">
                <div class="kal-coach-dash-message-message-bubble kal-coach-dash-message-received">
                    <div class="kal-coach-dash-message-message-info">
                        <span class="kal-coach-dash-message-sender">Kalana Ekanayake</span>
                        <span class="kal-coach-dash-message-timestamp">Today at 11:45 AM</span>
                    </div>
                    <div class="kal-coach-dash-message-message-text">
                        Hi Coach,<br><br>
                        I wanted to share some feedback about yesterday's training session. The drills were excellent and really helped improve my technique.<br><br>
                        My session ID is #TR0045. Could we schedule another session for next week?<br><br>
                        Thanks!
                    </div>
                </div>

                <div class="kal-coach-dash-message-message-bubble kal-coach-dash-message-sent">
                    <div class="kal-coach-dash-message-message-info">
                        <span class="kal-coach-dash-message-sender">Coach (You)</span>
                        <span class="kal-coach-dash-message-timestamp">Today at 2:35 PM</span>
                    </div>
                    <div class="kal-coach-dash-message-message-text">
                        Hi Kalana,<br><br>
                        I'm glad to hear you enjoyed the session! I've checked my schedule and I have availability on Tuesday and Thursday next week.<br><br>
                        Let me know which day works better for you, and I'll confirm the booking.
                    </div>
                </div>

                <div class="kal-coach-dash-message-message-bubble kal-coach-dash-message-received">
                    <div class="kal-coach-dash-message-message-info">
                        <span class="kal-coach-dash-message-sender">Kalana Ekanayake</span>
                        <span class="kal-coach-dash-message-timestamp">Today at 2:40 PM</span>
                    </div>
                    <div class="kal-coach-dash-message-message-text">
                        Perfect! Thursday would work best for me. Thank you so much for the quick response. Really appreciate your coaching style! 🙏
                    </div>
                </div>
            </div>

            <div class="kal-coach-dash-message-reply-section">
                <div class="kal-coach-dash-message-reply-form">
                    <textarea placeholder="Type your reply..." rows="4"></textarea>
                    <div class="kal-coach-dash-message-reply-actions">
                        <button class="kal-coach-dash-message-btn-attach">📎</button>
                        <button class="kal-coach-dash-message-btn-emoji">😊</button>
                        <button class="kal-coach-dash-message-btn-send">Send Reply</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Compose Message Modal -->
<div id="kal-coach-dash-message-composeModal" class="kal-coach-dash-message-modal">
    <div class="kal-coach-dash-message-modal-content">
        <div class="kal-coach-dash-message-modal-header">
            <h3>Compose New Message</h3>
            <span class="kal-coach-dash-message-close" onclick="closeComposeModal()">&times;</span>
        </div>
        <form class="kal-coach-dash-message-compose-form">
            <div class="kal-coach-dash-message-form-group">
                <label>To:</label>
                <select name="recipient" required>
                    <option value="">Select Recipient</option>
                    <option value="all_users">All Users</option>
                    <option value="students">All Students</option>
                    <option value="academies">All Academies</option>
                    <option value="coaches">All Coaches</option>
                    <option value="individual">Specific User</option>
                </select>
            </div>
            
            <div class="kal-coach-dash-message-form-group" id="kal-coach-dash-message-userSelect" style="display: none;">
                <label>Select User:</label>
                <input type="text" placeholder="Search and select user..." id="kal-coach-dash-message-userSearch">
            </div>

            <div class="kal-coach-dash-message-form-group">
                <label>Subject:</label>
                <input type="text" name="subject" required placeholder="Enter message subject">
            </div>

            <div class="kal-coach-dash-message-form-group">
                <label>Priority:</label>
                <select name="priority">
                    <option value="normal">Normal</option>
                    <option value="high">High Priority</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>

            <div class="kal-coach-dash-message-form-group">
                <label>Message:</label>
                <textarea name="message" rows="8" required placeholder="Type your message here..."></textarea>
            </div>

            <div class="kal-coach-dash-message-modal-actions">
                <button type="button" class="kal-coach-dash-message-btn-cancel" onclick="closeComposeModal()">Cancel</button>
                <button type="submit" class="kal-coach-dash-message-btn-send-message">Send Message</button>
            </div>
        </form>
    </div>
</div>

<script>
// Message item selection
document.querySelectorAll('.kal-coach-dash-message-message-item').forEach(item => {
    item.addEventListener('click', function() {
        document.querySelectorAll('.kal-coach-dash-message-message-item').forEach(i => i.classList.remove('kal-coach-dash-message-active'));
        this.classList.add('kal-coach-dash-message-active');
        
        // Load message content here
        const messageId = this.dataset.message;
        console.log('Loading message:', messageId);
    });
});

// Filter functionality
document.querySelectorAll('.kal-coach-dash-message-filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.kal-coach-dash-message-filter-btn').forEach(b => b.classList.remove('kal-coach-dash-message-active'));
        this.classList.add('kal-coach-dash-message-active');
        
        const filter = this.dataset.filter;
        console.log('Filtering messages by:', filter);
    });
});

// Compose modal
function openComposeModal() {
    document.getElementById('kal-coach-dash-message-composeModal').style.display = 'block';
}

function closeComposeModal() {
    document.getElementById('kal-coach-dash-message-composeModal').style.display = 'none';
}

// Recipient selection change
document.querySelector('select[name="recipient"]').addEventListener('change', function() {
    const userSelect = document.getElementById('kal-coach-dash-message-userSelect');
    if (this.value === 'individual') {
        userSelect.style.display = 'block';
    } else {
        userSelect.style.display = 'none';
    }
});

// Send reply
document.querySelector('.kal-coach-dash-message-btn-send').addEventListener('click', function() {
    const textarea = document.querySelector('.kal-coach-dash-message-reply-form textarea');
    const message = textarea.value.trim();
    
    if (message) {
        // Add message to conversation
        alert('Reply sent: ' + message);
        textarea.value = '';
    }
});

// Message actions
document.querySelector('.kal-coach-dash-message-btn-archive').addEventListener('click', function() {
    alert('Message archived');
});

document.querySelector('.kal-coach-dash-message-btn-priority').addEventListener('click', function() {
    alert('Message marked as priority');
});

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('kal-coach-dash-message-composeModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<?php require APPROOT . '/views/coachdash/inc/footer.php'; ?>