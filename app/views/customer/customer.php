<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - <?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/styledinesh.css?v=<?php echo time(); ?>">
</head>

<body>


    <div class="customer-admin-layout">


        <!-- ============================================
         SIDEBAR NAVIGATION
    ============================================ -->
        <aside class="customer-sidebar">

            <div class="customer-sidebar-header">
                <h2>BookMyGround.lk</h2>
                <span class="customer-badge">Customer Panel</span>
            </div>

            <nav class="customer-sidebar-nav">
                <ul>
                    <li>
                        <a href="#overview" class="customer-nav-link active">
                            <span class="customer-nav-icon">🏠</span>
                            <span class="customer-nav-text">Overview</span>
                        </a>
                    </li>
                    <li>
                        <a href="#bookings" class="customer-nav-link">
                            <span class="customer-nav-icon">📅</span>
                            <span class="customer-nav-text">My Bookings</span>
                        </a>
                    </li>
                    <li>
                        <a href="#messages" class="customer-nav-link">
                            <span class="customer-nav-icon">✉️</span>
                            <span class="customer-nav-text">Messages</span>
                        </a>
                    </li>
                    <li>
                        <a href="#profile" class="customer-nav-link">
                            <span class="customer-nav-icon">👤</span>
                            <span class="customer-nav-text">Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="#emergency-contacts" class="customer-nav-link">
                            <span class="customer-nav-icon">📞</span>
                            <span class="customer-nav-text">Emergency Contacts</span>
                        </a>
                    </li>
                    <li>
                        <a href="#stadiums" class="customer-nav-link">
                            <span class="customer-nav-icon">🏟️</span>
                            <span class="customer-nav-text">Stadiums</span>
                        </a>
                    </li>
                    <li>
                        <a href="#advertisements" class="customer-nav-link">
                            <span class="customer-nav-icon">📢</span>
                            <span class="customer-nav-text">Advertisements</span>
                        </a>
                    </li>
                    <li>
                        <a href="#payments" class="customer-nav-link">
                            <span class="customer-nav-icon">💳</span>
                            <span class="customer-nav-text">Payments</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="customer-sidebar-footer">
                <div class="customer-profile-info">
                    <?php
                    $sidebarPic = $data['profile_data']['profile_picture'] ?? '';
                    $sidebarPicActive = $data['profile_data']['profile_picture_active'] ?? 1;
                    $hasSidebarPic = !empty($sidebarPic) && $sidebarPicActive == 1 && file_exists(APPROOT . '/../public/images/profiles/' . $sidebarPic);
                    $sidebarDefaultExists = file_exists(APPROOT . '/../public/images/profiles/default-avatar.png');

                    if ($hasSidebarPic) {
                        $sidebarAvatarSrc = URLROOT . '/images/profiles/' . htmlspecialchars($sidebarPic);
                    } elseif ($sidebarDefaultExists) {
                        $sidebarAvatarSrc = URLROOT . '/images/profiles/default-avatar.png';
                    } else {
                        $sidebarAvatarSrc = 'https://ui-avatars.com/api/?name=' . urlencode(($data['profile_data']['first_name'] ?? 'U') . '+' . ($data['profile_data']['last_name'] ?? '')) . '&background=03B200&color=fff&size=45';
                    }
                    ?>
                    <img src="<?php echo $sidebarAvatarSrc; ?>" alt="Profile" class="customer-sidebar-avatar">
                    <div class="customer-sidebar-user">
                        <h4><?php echo htmlspecialchars(($data['profile_data']['first_name'] ?? '') . ' ' . ($data['profile_data']['last_name'] ?? '')); ?></h4>
                        <p><?php echo htmlspecialchars($data['profile_data']['email'] ?? ''); ?></p>
                    </div>
                </div>
                <a href="<?php echo URLROOT; ?>/customer/logout" class="customer-logout-btn">Logout</a>
            </div>

        </aside>


        <!-- ============================================
         MAIN CONTENT AREA
    ============================================ -->
        <div class="customer-main-content">


            <!-- Top Header -->
            <div class="customer-top-header">
                <div class="customer-header-title">
                    <h1>Customer Dashboard</h1>
                    <p>Manage your bookings, profile, and sports journey</p>
                </div>
                <div class="customer-header-actions">
                    <a href="<?php echo URLROOT; ?>" class="customer-view-site-btn">🌐 View Site</a>
                </div>
            </div>


            <!-- ============================================
             STATS GRID
        ============================================ -->
            <div class="customer-stats-grid">

                <div class="customer-stat-card">
                    <div class="customer-stat-icon">📅</div>
                    <div class="customer-stat-info">
                        <h3><?php echo $data['stats']['active_bookings'] ?? 0; ?></h3>
                        <p>Active Bookings</p>
                    </div>
                </div>

                <div class="customer-stat-card">
                    <div class="customer-stat-icon">🏟️</div>
                    <div class="customer-stat-info">
                        <h3><?php echo $data['stats']['stadiums_visited'] ?? 0; ?></h3>
                        <p>Stadiums Visited</p>
                    </div>
                </div>

                <div class="customer-stat-card">
                    <div class="customer-stat-icon">⭐</div>
                    <div class="customer-stat-info">
                        <h3><?php echo $data['stats']['rating_given'] ?? 0; ?></h3>
                        <p>Rating Given</p>
                    </div>
                </div>

                <div class="customer-stat-card">
                    <div class="customer-stat-icon">💰</div>
                    <div class="customer-stat-info">
                        <h3>LKR <?php echo number_format($data['stats']['total_spent'] ?? 0); ?></h3>
                        <p>Total Spent</p>
                    </div>
                </div>

            </div>


            <!-- ============================================
             DASHBOARD CONTENT SECTIONS
        ============================================ -->
            <div class="customer-dashboard-content">


                <!-- Global Alert Messages -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="customer-alert customer-alert-success" style="background: rgba(3, 178, 0, 0.1); border: 1px solid #03B200; color: #03B200; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                        ✓ <?php echo htmlspecialchars($_SESSION['success']);
                            unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="customer-alert customer-alert-error" style="background: rgba(255, 0, 0, 0.1); border: 1px solid #ff4444; color: #ff6666; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                        ✗ <?php echo htmlspecialchars($_SESSION['error']);
                            unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>


                <!-- ============================================
                 RECENT BOOKINGS SECTION
            ============================================ -->
                <div class="customer-content-section" id="bookings">
                    <h2 class="customer-section-heading">Recent Bookings</h2>

                    <div class="customer-bookings-grid">
                        <?php if (isset($data['recent_bookings']) && count($data['recent_bookings']) > 0): ?>

                            <?php foreach ($data['recent_bookings'] as $booking): ?>
                                <div class="customer-booking-card">

                                    <div class="customer-booking-header">
                                        <h3 class="customer-stadium-name"><?php echo htmlspecialchars($booking->stadium_name); ?></h3>
                                        <span class="customer-booking-status <?php echo strtolower($booking->status); ?>">
                                            <?php echo ucfirst($booking->status); ?>
                                        </span>
                                    </div>

                                    <div class="customer-booking-details">
                                        <p><strong>Date:</strong> <?php echo date('M d, Y', strtotime($booking->booking_date)); ?></p>
                                        <p><strong>Time:</strong> <?php echo date('g:i A', strtotime($booking->start_time)) . ' - ' . date('g:i A', strtotime($booking->end_time)); ?></p>
                                        <p><strong>Duration:</strong> <?php echo round($booking->duration_hours, 1); ?> hour(s)</p>
                                        <p><strong>Amount:</strong> LKR <?php echo number_format($booking->total_price); ?></p>
                                        <p><strong>Payment:</strong>
                                            <span class="customer-payment-badge <?php echo $booking->payment_status; ?>">
                                                <?php echo ucfirst($booking->payment_status); ?>
                                            </span>
                                        </p>
                                    </div>

                                    <div class="customer-booking-actions">
                                        <button class="customer-action-btn customer-details-btn" onclick="displayBookingDetailsModal('<?php echo base64_encode(json_encode($booking)); ?>')" style="background: linear-gradient(135deg, #03B200, #028a00); color: white;">
                                            📋 Booking Details
                                        </button>
                                        <button class="customer-action-btn customer-view-btn" onclick="checkRefundStatus(<?php echo $booking->id; ?>, '<?php echo htmlspecialchars($booking->stadium_name, ENT_QUOTES); ?>', <?php echo $booking->total_price; ?>)" style="background: linear-gradient(135deg, #FF9800, #F57C00); color: white;">
                                            💰 Refund Status
                                        </button>
                                        <?php if (in_array($booking->status, ['pending', 'confirmed', 'reserved'])): ?>
                                            <button class="customer-action-btn customer-cancel-btn" onclick="showRefundModal(<?php echo $booking->id; ?>, '<?php echo htmlspecialchars($booking->stadium_name, ENT_QUOTES); ?>', <?php echo $booking->total_price; ?>)" style="background: linear-gradient(135deg, #f44336, #d32f2f); color: white;">
                                                ❌ Cancel Booking
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            <?php endforeach; ?>

                        <?php else: ?>

                            <div class="customer-empty-state">
                                <p>📅 No bookings found.</p>
                                <p style="font-size: 14px; margin-top: 10px;">Start exploring stadiums and make your first booking!</p>
                                <a href="<?php echo URLROOT; ?>/stadiums" class="customer-action-btn customer-view-btn" style="display: inline-block; margin-top: 15px;">Browse Stadiums</a>
                            </div>

                        <?php endif; ?>
                    </div>
                </div>


                <!-- ============================================
                 REFUND REQUEST MODAL
            ============================================ -->
                <div id="refundModal" class="customer-modal">
                    <div class="customer-modal-content" style="max-width: 700px;">
                        <div class="customer-modal-header">
                            <div>
                                <h3 style="margin: 0;">🔄 Cancel Booking & Request Refund</h3>
                                <p style="margin: 5px 0 0 0; color: #999; font-size: 13px;">Complete the form below to cancel your booking</p>
                            </div>
                            <button type="button" class="customer-modal-close" onclick="closeRefundModal()">&times;</button>
                        </div>

                        <form id="refundForm" method="POST" onsubmit="submitRefundRequest(event)">
                            <div class="customer-modal-body">
                                <!-- BOOKING SUMMARY SECTION -->
                                <div style="background: #0d3d0d; border-radius: 10px; padding: 16px; margin-bottom: 25px; border-left: 4px solid #03B200;">
                                    <h4 style="color: #03B200; margin: 0 0 12px 0; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Booking Summary</h4>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                        <div>
                                            <label style="color: #888; font-size: 12px; text-transform: uppercase; display: block; margin-bottom: 5px;">Stadium</label>
                                            <input type="text" id="refundStadiumName" readonly style="background: #111; color: #fff; font-weight: 500;">
                                        </div>
                                        <div>
                                            <label style="color: #888; font-size: 12px; text-transform: uppercase; display: block; margin-bottom: 5px;">Refund Amount</label>
                                            <input type="text" id="refundAmount" readonly style="background: #111; color: #03B200; font-weight: 600; font-size: 15px;">
                                        </div>
                                    </div>
                                </div>

                                <!-- WARNING ALERT -->
                                <div style="background: rgba(255, 152, 0, 0.1); border: 1px solid #ff9800; border-radius: 8px; padding: 14px 16px; margin-bottom: 25px; display: flex; gap: 12px;">
                                    <div style="flex-shrink: 0; font-size: 18px; margin-top: 2px;">⏱️</div>
                                    <div>
                                        <p style="margin: 0; color: #ff9800; font-weight: 600; font-size: 13px;">Important Processing Time</p>
                                        <p style="margin: 5px 0 0 0; color: #ccc; font-size: 12px;">Your refund will be processed to your bank account within <strong>24-48 hours</strong> after approval.</p>
                                    </div>
                                </div>

                                <!-- BANK DETAILS SECTION -->
                                <div style="background: #222; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
                                    <h4 style="color: #03B200; margin: 0 0 18px 0; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px;">
                                        🏦 Bank Account Details
                                    </h4>

                                    <div class="customer-form-group" style="margin-bottom: 15px;">
                                        <label style="color: #ddd; font-weight: 600; display: flex; gap: 4px;">
                                            <span>Account Holder Name</span>
                                            <span style="color: #ff4444;">*</span>
                                        </label>
                                        <input type="text" name="account_name" placeholder="Full name as per bank account" required style="border: 1px solid #333; background: #1a1a1a;">
                                        <small style="color: #888; display: block; margin-top: 6px;">The name registered with your bank</small>
                                    </div>

                                    <div class="customer-form-group" style="margin-bottom: 15px;">
                                        <label style="color: #ddd; font-weight: 600; display: flex; gap: 4px;">
                                            <span>Bank Account Number</span>
                                            <span style="color: #ff4444;">*</span>
                                        </label>
                                        <input type="text" name="account_number" placeholder="Enter your bank account number" required style="border: 1px solid #333; background: #1a1a1a;">
                                        <small style="color: #888; display: block; margin-top: 6px;">We'll securely use this for your refund only</small>
                                    </div>

                                    <div class="customer-form-group" style="margin-bottom: 15px;">
                                        <label style="color: #ddd; font-weight: 600; display: flex; gap: 4px;">
                                            <span>Bank Name</span>
                                            <span style="color: #ff4444;">*</span>
                                        </label>
                                        <input type="text" name="bank_name" placeholder="e.g., Bank of Ceylon, Sampath Bank" required style="border: 1px solid #333; background: #1a1a1a;">
                                        <small style="color: #888; display: block; margin-top: 6px;">Name of your bank</small>
                                    </div>

                                    <div class="customer-form-group">
                                        <label style="color: #ddd; font-weight: 600;">Branch Name <span style="color: #888;">(Optional)</span></label>
                                        <input type="text" name="branch_name" placeholder="Your bank branch name" style="border: 1px solid #333; background: #1a1a1a;">
                                        <small style="color: #888; display: block; margin-top: 6px;">If applicable to your bank</small>
                                    </div>
                                </div>

                                <!-- CANCELLATION REASON SECTION -->
                                <div style="margin-bottom: 20px;">
                                    <h4 style="color: #ddd; margin: 0 0 12px 0; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                                        💬 Why are you cancelling? <span style="color: #888; font-size: 12px; font-weight: 400;">(Optional)</span>
                                    </h4>
                                    <textarea name="reason" placeholder="Tell us why you're cancelling your booking... (This helps us improve)" rows="3" style="width: 100%; padding: 12px 15px; background: #1a1a1a; border: 1px solid #333; border-radius: 8px; color: #fff; font-size: 14px; resize: vertical; font-family: inherit;"></textarea>
                                </div>

                                <!-- SECURITY NOTICE -->
                                <div style="background: rgba(3, 178, 0, 0.08); border: 1px solid #03B200; border-radius: 8px; padding: 12px 14px; display: flex; gap: 10px;">
                                    <div style="flex-shrink: 0; font-size: 16px;">🔒</div>
                                    <div>
                                        <p style="margin: 0; color: #888; font-size: 12px;">Your bank details are encrypted and stored securely. They will be used <strong>only for processing your refund</strong>.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="customer-modal-footer">
                                <button type="button" class="customer-modal-cancel-btn" onclick="closeRefundModal()">Cancel</button>
                                <button type="submit" class="customer-modal-save-btn" style="background: #03B200; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                    <span>💰</span>
                                    <span>Submit for Refund</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- REFUND STATUS MODAL -->
                <div id="refundStatusModal" class="customer-modal">
                    <div class="customer-modal-content" style="max-width: 600px;">
                        <div class="customer-modal-header">
                            <div>
                                <h3 style="margin: 0;">💰 Refund Status</h3>
                                <p style="margin: 5px 0 0 0; color: #999; font-size: 13px;">Track your refund request</p>
                            </div>
                            <button type="button" class="customer-modal-close" onclick="closeRefundStatusModal()">&times;</button>
                        </div>

                        <div class="customer-modal-body" id="refundStatusContent" style="padding: 30px 20px;">
                            <!-- Content will be populated by JavaScript -->
                        </div>

                        <div class="customer-modal-footer">
                            <button type="button" class="customer-modal-cancel-btn" onclick="closeRefundStatusModal()">Close</button>
                        </div>
                    </div>
                </div>


                <!-- ============================================
                 MESSAGES SECTION
            ============================================ -->
                <div class="customer-content-section" id="messages">
                    <div class="customer-section-header-row">
                        <div>
                            <h2 class="customer-section-heading">Messages</h2>
                            <p class="customer-section-subtitle">Review conversations with stadium owners and reply instantly.</p>
                        </div>
                        <div class="customer-message-summary">
                            <div class="customer-message-summary-card">
                                <span>Total Conversations</span>
                                <strong><?php echo count($data['conversations'] ?? []); ?></strong>
                            </div>
                            <div class="customer-message-summary-card">
                                <span>Unread Messages</span>
                                <strong><?php echo $data['unread_count'] ?? 0; ?></strong>
                            </div>
                        </div>
                    </div>

                    <?php $conversations = $data['conversations'] ?? []; ?>
                    <?php if (!empty($conversations)): ?>
                        <div class="customer-message-layout">
                            <div class="customer-message-sidebar">
                                <div class="customer-message-filters">
                                    <button class="customer-message-filter active" data-filter="all">All</button>
                                    <button class="customer-message-filter" data-filter="unread">Unread</button>
                                </div>

                                <div class="customer-message-list" id="customerMessageList">
                                    <?php foreach ($conversations as $index => $conversation): ?>
                                        <?php
                                        $senderName = trim(($conversation['other_first_name'] ?? '') . ' ' . ($conversation['other_last_name'] ?? '')) ?: 'Unknown Sender';
                                        $messageSnippet = strlen($conversation['last_message'] ?? '') > 90 ? substr($conversation['last_message'], 0, 87) . '...' : ($conversation['last_message'] ?? 'No message yet');
                                        ?>
                                        <div class="customer-message-item<?php echo $index === 0 ? ' active' : ''; ?>" data-other-user-id="<?php echo htmlspecialchars($conversation['other_user_id']); ?>" data-stadium-id="<?php echo htmlspecialchars($conversation['stadium_id'] ?? '', ENT_QUOTES); ?>" data-subject="<?php echo htmlspecialchars($conversation['subject'] ?? 'No subject', ENT_QUOTES); ?>" data-sender-name="<?php echo htmlspecialchars($senderName, ENT_QUOTES); ?>" data-last-created-at="<?php echo htmlspecialchars($conversation['last_created_at'] ?? '', ENT_QUOTES); ?>">
                                            <div class="customer-message-item-title">
                                                <strong><?php echo htmlspecialchars($conversation['subject'] ?? 'No subject'); ?></strong>
                                                <?php if (!empty($conversation['unread_count'])): ?>
                                                    <span class="customer-message-unread-count"><?php echo intval($conversation['unread_count']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="customer-message-item-meta">
                                                <span><?php echo htmlspecialchars($senderName); ?></span>
                                                <span><?php echo !empty($conversation['last_created_at']) ? date('M d, H:i', strtotime($conversation['last_created_at'])) : 'N/A'; ?></span>
                                            </div>
                                            <p><?php echo htmlspecialchars($messageSnippet); ?></p>
                                            <?php if (!empty($conversation['stadium_name'])): ?>
                                                <div class="customer-message-item-property"><?php echo htmlspecialchars($conversation['stadium_name']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="customer-message-preview">
                                <div class="customer-message-preview-header">
                                    <div>
                                        <h3 id="customerConversationSubject"><?php echo htmlspecialchars($conversations[0]['subject'] ?? 'No subject'); ?></h3>
                                        <p id="customerConversationFrom">Conversation with <?php echo htmlspecialchars(trim(($conversations[0]['other_first_name'] ?? '') . ' ' . ($conversations[0]['other_last_name'] ?? '')) ?: 'Unknown Sender'); ?></p>
                                        <p id="customerConversationInfo"><?php echo htmlspecialchars($conversations[0]['stadium_name'] ?? 'General'); ?> · <?php echo !empty($conversations[0]['last_created_at']) ? date('M d, Y H:i', strtotime($conversations[0]['last_created_at'])) : 'N/A'; ?></p>
                                    </div>
                                    <div class="customer-message-preview-actions">
                                        <?php if (!empty($conversations[0]['stadium_id'])): ?>
                                            <button class="customer-action-btn customer-view-btn" onclick="window.location.href='<?php echo URLROOT; ?>/stadiums/view/<?php echo htmlspecialchars($conversations[0]['stadium_id'], ENT_QUOTES); ?>'">View Stadium</button>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="customer-message-preview-body" id="customerConversationBody">
                                    <div class="customer-chat-thread" id="customerChatThread"></div>
                                </div>

                                <div class="customer-message-reply">
                                    <textarea id="customerReplyText" placeholder="Type your reply..." rows="4"></textarea>
                                    <button class="customer-action-btn customer-send-btn" onclick="sendCustomerReply()">Send Reply</button>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="customer-empty-state">
                            <p>✉️ No conversations found yet.</p>
                            <p style="font-size: 14px; margin-top: 10px;">Start a conversation from a stadium listing and it will appear here.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- ============================================
                 PROFILE SECTION
            ============================================ -->
                <div class="customer-content-section" id="profile">
                    <h2 class="customer-section-heading">My Profile</h2>

                    <form action="<?php echo URLROOT; ?>/customer/updateProfile" method="POST" enctype="multipart/form-data">

                        <div class="customer-profile-container">

                            <div class="customer-profile-avatar-section">
                                <?php
                                $profilePic = $data['profile_data']['profile_picture'] ?? '';
                                $picActive = $data['profile_data']['profile_picture_active'] ?? 1;
                                $hasProfilePic = !empty($profilePic) && $picActive == 1 && file_exists(APPROOT . '/../public/images/profiles/' . $profilePic);
                                $defaultExists = file_exists(APPROOT . '/../public/images/profiles/default-avatar.png');

                                if ($hasProfilePic) {
                                    $avatarSrc = URLROOT . '/images/profiles/' . htmlspecialchars($profilePic);
                                } elseif ($defaultExists) {
                                    $avatarSrc = URLROOT . '/images/profiles/default-avatar.png';
                                } else {
                                    $avatarSrc = 'https://ui-avatars.com/api/?name=' . urlencode(($data['profile_data']['first_name'] ?? 'U') . '+' . ($data['profile_data']['last_name'] ?? '')) . '&background=03B200&color=fff&size=150';
                                }
                                ?>
                                <img src="<?php echo $avatarSrc; ?>" alt="Profile Picture" id="profile-avatar">

                                <div class="customer-avatar-actions">
                                    <?php if ($hasProfilePic): ?>
                                        <label for="profile_picture_input" class="customer-avatar-btn customer-avatar-change-btn">
                                            📷 Change
                                        </label>
                                        <a href="javascript:void(0)"
                                            class="customer-avatar-btn customer-avatar-delete-btn"
                                            onclick="openConfirmModal('<?php echo URLROOT; ?>/customer/deleteProfilePicture', 'Delete your profile picture?', '🖼️ Delete Photo', '🗑️ Delete');">
                                            🗑️ Delete
                                        </a>
                                    <?php else: ?>
                                        <label for="profile_picture_input" class="customer-avatar-btn customer-avatar-add-btn">
                                            📷 Add Photo
                                        </label>
                                    <?php endif; ?>
                                    <input type="file" name="profile_picture" id="profile_picture_input" accept="image/*" style="display: none;" onchange="previewProfilePicture(this)">
                                </div>
                            </div>

                            <div class="customer-profile-details">

                                <div class="customer-profile-field">
                                    <label>First Name *</label>
                                    <input type="text" name="first_name" value="<?php echo htmlspecialchars($data['profile_data']['first_name'] ?? ''); ?>" placeholder="Enter your first name" class="customer-profile-input" required>
                                </div>

                                <div class="customer-profile-field">
                                    <label>Last Name *</label>
                                    <input type="text" name="last_name" value="<?php echo htmlspecialchars($data['profile_data']['last_name'] ?? ''); ?>" placeholder="Enter your last name" class="customer-profile-input" required>
                                </div>

                                <div class="customer-profile-field">
                                    <label>Email (Read Only)</label>
                                    <input type="email" value="<?php echo htmlspecialchars($data['profile_data']['email'] ?? ''); ?>" class="customer-profile-input" readonly>
                                </div>

                                <div class="customer-profile-field">
                                    <label>Phone *</label>
                                    <input type="tel" name="phone" value="<?php echo htmlspecialchars($data['profile_data']['phone'] ?? ''); ?>" placeholder="Enter your phone number" class="customer-profile-input" required>
                                </div>

                                <div class="customer-profile-field">
                                    <label>District *</label>
                                    <input type="text" name="district" value="<?php echo htmlspecialchars($data['profile_data']['location'] ?? ''); ?>" placeholder="Enter your district" class="customer-profile-input" required>
                                </div>

                                <div class="customer-profile-field">
                                    <label>Preferred Sports *</label>
                                    <select name="preferred_sports" class="customer-profile-select" required>
                                        <option value="">Select primary sport</option>
                                        <?php $sportSel = $data['profile_data']['favorite_sports'] ?? ''; ?>
                                        <option value="football" <?php echo ($sportSel == 'football')  ? 'selected' : ''; ?>>Football</option>
                                        <option value="cricket" <?php echo ($sportSel == 'cricket')   ? 'selected' : ''; ?>>Cricket</option>
                                        <option value="badminton" <?php echo ($sportSel == 'badminton') ? 'selected' : ''; ?>>Badminton</option>
                                        <option value="tennis" <?php echo ($sportSel == 'tennis')    ? 'selected' : ''; ?>>Tennis</option>
                                        <option value="other" <?php echo ($sportSel == 'other')     ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>

                                <div class="customer-profile-field">
                                    <label>Age Group *</label>
                                    <select name="age_group" class="customer-profile-select" required>
                                        <option value="">Select age group</option>
                                        <?php $ageSel = $data['profile_data']['age_group'] ?? ''; ?>
                                        <option value="under-18" <?php echo ($ageSel == 'under_18') ? 'selected' : ''; ?>>Under 18</option>
                                        <option value="18-25" <?php echo ($ageSel == '18_25')    ? 'selected' : ''; ?>>18-25 years</option>
                                        <option value="26-35" <?php echo ($ageSel == '26_35')    ? 'selected' : ''; ?>>26-35 years</option>
                                        <option value="above-35" <?php echo ($ageSel == 'above_35') ? 'selected' : ''; ?>>Above 35</option>
                                    </select>
                                </div>

                                <div class="customer-profile-field">
                                    <label>Skill Level *</label>
                                    <select name="skill_level" class="customer-profile-select" required>
                                        <option value="">Select skill level</option>
                                        <?php $skillSel = $data['profile_data']['skill_level'] ?? ''; ?>
                                        <option value="beginner" <?php echo ($skillSel == 'beginner')     ? 'selected' : ''; ?>>Beginner</option>
                                        <option value="intermediate" <?php echo ($skillSel == 'intermediate') ? 'selected' : ''; ?>>Intermediate</option>
                                        <option value="advanced" <?php echo ($skillSel == 'advanced')     ? 'selected' : ''; ?>>Advanced</option>
                                        <option value="professional" <?php echo ($skillSel == 'professional') ? 'selected' : ''; ?>>Professional</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="customer-profile-actions">
                            <button type="submit" class="customer-save-btn">💾 Save Changes</button>
                            <button type="reset" class="customer-reset-btn">🔄 Reset</button>
                        </div>

                    </form>
                </div>


                <!-- ============================================
                 EMERGENCY CONTACTS SECTION
            ============================================ -->
                <div class="customer-content-section" id="emergency-contacts">
                    <h2 class="customer-section-heading">Emergency Contacts</h2>

                    <!-- Add Contact Form -->
                    <div class="customer-emergency-form">
                        <h3>➕ Add New Contact</h3>

                        <form action="<?php echo URLROOT; ?>/customer/addEmergencyContact" method="POST">
                            <div class="customer-form-grid">

                                <div class="customer-form-field">
                                    <label>Contact Name *</label>
                                    <input type="text" name="contact_name" placeholder="Full name" required>
                                </div>

                                <div class="customer-form-field">
                                    <label>Relationship *</label>
                                    <input type="text" name="relationship" placeholder="e.g. Father, Sister" required>
                                </div>

                                <div class="customer-form-field">
                                    <label>Phone *</label>
                                    <input type="tel" name="phone" placeholder="+94771234567" required>
                                </div>

                                <div class="customer-form-field">
                                    <label>Email</label>
                                    <input type="email" name="email" placeholder="email@example.com">
                                </div>

                            </div>
                            <button type="submit" class="customer-add-contact-btn">➕ Add Contact</button>
                        </form>
                    </div>

                    <!-- Contacts List -->
                    <div class="customer-contacts-list">
                        <h3>📋 My Emergency Contacts</h3>

                        <div class="customer-bookings-grid">
                            <?php
                            $emergency_contacts = $data['emergency_contacts'] ?? [];
                            if ($emergency_contacts && count($emergency_contacts) > 0):
                                foreach ($emergency_contacts as $contact):
                            ?>
                                    <div class="customer-contact-card">

                                        <div class="customer-contact-header">
                                            <h3 class="customer-contact-name">👤 <?php echo htmlspecialchars($contact->contact_name); ?></h3>
                                            <span class="customer-contact-relationship"><?php echo htmlspecialchars($contact->relationship); ?></span>
                                        </div>

                                        <div class="customer-contact-details">
                                            <p><strong>📞 Phone:</strong> <?php echo htmlspecialchars($contact->phone); ?></p>
                                            <p><strong>📧 Email:</strong> <?php echo htmlspecialchars($contact->email ?? 'N/A'); ?></p>
                                            <p><strong>📅 Added:</strong> <?php echo isset($contact->created_at) ? date('M d, Y', strtotime($contact->created_at)) : ''; ?></p>
                                        </div>

                                        <a href="javascript:void(0)"
                                            class="customer-delete-contact-btn"
                                            onclick="openConfirmModal('<?php echo URLROOT; ?>/customer/deleteEmergencyContact/<?php echo $contact->id; ?>', 'Are you sure you want to delete this contact?', '📞 Delete Contact', '🗑️ Delete');">
                                            🗑️ Delete
                                        </a>

                                    </div>
                                <?php
                                endforeach;
                            else:
                                ?>
                                <p style="color: #888;">No emergency contacts added yet. Add your first contact above!</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>


                <!-- ============================================
                 FAVORITE STADIUMS SECTION
            ============================================ -->
                <div class="customer-content-section" id="stadiums">
                    <h2 class="customer-section-heading">Favorite Stadiums</h2>

                    <div class="customer-bookings-grid">
                        <?php
                        $favorite_stadiums = $data['favorite_stadiums'] ?? [];
                        if ($favorite_stadiums && count($favorite_stadiums) > 0):
                            foreach ($favorite_stadiums as $stadium):
                        ?>
                                <div class="customer-booking-card">

                                    <div class="customer-booking-header">
                                        <h3 class="customer-stadium-name">🏟️ <?php echo htmlspecialchars($stadium->nickname ?: $stadium->name); ?></h3>
                                        <span class="customer-booking-status confirmed">⭐ <?php echo $stadium->rating; ?></span>
                                    </div>

                                    <div class="customer-booking-details">
                                        <?php if ($stadium->nickname): ?>
                                            <p><strong>📛 Original:</strong> <?php echo htmlspecialchars($stadium->name); ?></p>
                                        <?php endif; ?>
                                        <p><strong>📍 Location:</strong> <?php echo htmlspecialchars($stadium->location); ?></p>
                                        <p><strong>⚽ Sport:</strong> <?php echo htmlspecialchars($stadium->type); ?></p>
                                        <p><strong>🏷️ Category:</strong> <?php echo htmlspecialchars($stadium->category); ?></p>
                                        <p><strong>📅 Added:</strong> <?php echo date('M d, Y', strtotime($stadium->created_at)); ?></p>
                                    </div>

                                    <div class="customer-booking-actions">
                                        <a href="<?php echo URLROOT; ?>/stadiums/view/<?php echo $stadium->stadium_id; ?>" class="customer-action-btn customer-view-btn">View Details</a>
                                        <a href="<?php echo URLROOT; ?>/stadiums/book/<?php echo $stadium->stadium_id; ?>" class="customer-action-btn customer-view-btn">Book Again</a>
                                    </div>

                                    <div class="customer-favorite-actions">
                                        <button type="button" class="customer-rename-btn"
                                            onclick="openRenameModal(<?php echo $stadium->id; ?>, '<?php echo addslashes($stadium->nickname ?: $stadium->name); ?>')">
                                            ✏️ Rename
                                        </button>
                                        <a href="javascript:void(0)"
                                            class="customer-remove-favorite-btn"
                                            onclick="openConfirmModal('<?php echo URLROOT; ?>/customer/removeFavorite/<?php echo $stadium->id; ?>', 'Remove this stadium from favorites?', '🏟️ Remove Favorite', '🗑️ Remove');">
                                            🗑️ Remove
                                        </a>
                                    </div>

                                </div>
                            <?php
                            endforeach;
                        else:
                            ?>
                            <div class="customer-empty-state">
                                <p>🏟️ No favorite stadiums yet.</p>
                                <p>Browse stadiums and add them to your favorites!</p>
                                <a href="<?php echo URLROOT; ?>/stadiums" class="customer-action-btn customer-view-btn">Browse Stadiums</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>


                <!-- ============================================
                 RENAME MODAL
            ============================================ -->
                <div id="renameModal" class="customer-modal">
                    <div class="customer-modal-content">

                        <div class="customer-modal-header">
                            <h3>✏️ Rename Stadium</h3>
                            <button type="button" class="customer-modal-close" onclick="closeRenameModal()">&times;</button>
                        </div>

                        <form action="<?php echo URLROOT; ?>/customer/renameFavorite" method="POST">
                            <input type="hidden" name="favorite_id" id="rename_favorite_id">

                            <div class="customer-modal-body">
                                <label for="nickname">Custom Name</label>
                                <input type="text" name="nickname" id="rename_nickname" placeholder="Enter custom name" class="customer-profile-input" required>
                                <p class="customer-modal-hint">Give this stadium a custom name</p>
                            </div>

                            <div class="customer-modal-footer">
                                <button type="button" class="customer-modal-cancel-btn" onclick="closeRenameModal()">Cancel</button>
                                <button type="submit" class="customer-modal-save-btn">💾 Save</button>
                            </div>
                        </form>

                    </div>
                </div>


                <!-- ============================================
                 CONFIRM DELETE MODAL
            ============================================ -->
                <div id="confirmModal" class="customer-modal">
                    <div class="customer-modal-content customer-confirm-modal">

                        <div class="customer-modal-header customer-confirm-header">
                            <h3 id="confirmModalTitle">⚠️ Confirm Action</h3>
                            <button type="button" class="customer-modal-close" onclick="closeConfirmModal()">&times;</button>
                        </div>

                        <div class="customer-modal-body">
                            <p id="confirmModalMessage" class="customer-confirm-message">Are you sure you want to proceed?</p>
                        </div>

                        <div class="customer-modal-footer">
                            <button type="button" class="customer-modal-cancel-btn" onclick="closeConfirmModal()">Cancel</button>
                            <a href="#" id="confirmModalAction" class="customer-modal-delete-btn">🗑️ Delete</a>
                        </div>

                    </div>
                </div>


                <!-- ============================================
                 EDIT ADVERTISEMENT MODAL
            ============================================ -->
                <div id="editAdModal" class="customer-modal">
                    <div class="customer-modal-content" style="max-width: 500px;">
                        <div class="customer-modal-header">
                            <h3>✏️ Edit Advertisement</h3>
                            <button type="button" class="customer-modal-close" onclick="closeEditAdModal()">&times;</button>
                        </div>

                        <form id="editAdForm" method="POST" enctype="multipart/form-data">
                            <div class="customer-modal-body">
                                <p style="background: #fff3cd; color: #856404; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px;">
                                    ⚠️ After editing, your ad will be sent for admin approval again.
                                </p>

                                <div class="customer-form-group">
                                    <label>🏢 Business/Company Name *</label>
                                    <input type="text" name="company_name" id="editAdCompany" required>
                                </div>

                                <div class="customer-form-group">
                                    <label>🌐 Website URL (Optional)</label>
                                    <input type="url" name="website" id="editAdWebsite" placeholder="https://yourwebsite.com">
                                </div>

                                <div class="customer-form-group">
                                    <label>📝 Message</label>
                                    <textarea name="message" id="editAdMessage" rows="3" placeholder="Describe your advertisement..."></textarea>
                                </div>

                                <div class="customer-form-group">
                                    <label>🖼️ Update Ad Image (Optional)</label>
                                    <input type="file" name="ad_image" accept="image/jpeg,image/png,image/gif,image/webp">
                                    <small style="color: #888; font-size: 12px;">
                                        📐 Recommended: <strong>1200 x 100 pixels</strong> (landscape banner)<br>
                                        Leave empty to keep existing image. Max 5MB.
                                    </small>
                                </div>
                            </div>

                            <div class="customer-modal-footer">
                                <button type="button" class="customer-modal-cancel-btn" onclick="closeEditAdModal()">Cancel</button>
                                <button type="submit" class="customer-modal-save-btn">💾 Save & Submit for Approval</button>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- ============================================
                 ADVERTISEMENTS SECTION
            ============================================ -->
                <div class="customer-content-section" id="advertisements">
                    <h2 class="customer-section-heading">My Advertisements</h2>

                    <!-- Submit Advertisement Form -->
                    <div class="customer-ad-form-container">
                        <h3>📢 Submit New Advertisement</h3>
                        <p class="customer-ad-subtitle">Promote your sports business, event, or service to our community</p>

                        <form action="<?php echo URLROOT; ?>/customer/submitAdvertisement" method="POST" enctype="multipart/form-data" class="customer-ad-form">
                            <div class="customer-form-grid">

                                <div class="customer-form-field">
                                    <label>Business/Company Name *</label>
                                    <input type="text" name="company_name" placeholder="Your business name" required>
                                </div>

                                <div class="customer-form-field">
                                    <label>Website (Optional)</label>
                                    <input type="url" name="website" placeholder="https://example.com">
                                </div>

                                <div class="customer-form-field">
                                    <label>Package *</label>
                                    <select name="package" class="customer-profile-select" required>
                                        <?php foreach ($data['ad_packages'] as $key => $pkg): ?>
                                            <option value="<?php echo $key; ?>">
                                                <?php echo $pkg['name']; ?> - LKR <?php echo number_format($pkg['price']); ?> (<?php echo $pkg['duration']; ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="customer-form-field">
                                    <label>Ad Image (Optional)</label>
                                    <input type="file" name="ad_image" accept="image/*" class="customer-file-input">
                                    <small style="color: #888; font-size: 12px; display: block; margin-top: 5px;">
                                        📐 Recommended: <strong>1200 x 100 pixels</strong> (landscape banner format)<br>
                                        📁 Accepted: JPG, PNG, GIF, WEBP | Max size: 5MB
                                    </small>
                                </div>

                                <div class="customer-form-field customer-form-field-full">
                                    <label>Description/Message</label>
                                    <textarea name="message" rows="3" placeholder="Describe your advertisement..."></textarea>
                                </div>

                            </div>
                            <button type="submit" class="customer-submit-ad-btn">📢 Submit Advertisement</button>
                        </form>
                    </div>

                    <!-- My Advertisements List -->
                    <div class="customer-ad-list">
                        <h3>📋 My Submitted Advertisements</h3>

                        <div class="customer-bookings-grid">
                            <?php
                            $advertisements = $data['advertisements'] ?? [];
                            if ($advertisements && count($advertisements) > 0):
                                foreach ($advertisements as $ad):
                            ?>
                                    <div class="customer-ad-card">
                                        <div class="customer-ad-header">
                                            <h3 class="customer-ad-name">🏢 <?php echo htmlspecialchars($ad->company_name); ?></h3>
                                            <span class="customer-ad-status <?php echo $ad->status; ?>">
                                                <?php
                                                $statusIcons = ['pending' => '⏳', 'approved' => '✓', 'rejected' => '✗', 'active' => '🟢', 'expired' => '⚪'];
                                                echo ($statusIcons[$ad->status] ?? '') . ' ' . ucfirst($ad->status);
                                                ?>
                                            </span>
                                        </div>

                                        <div class="customer-ad-details">
                                            <p><strong>📦 Package:</strong> <span><?php echo ucfirst($ad->package); ?></span></p>
                                            <?php if ($ad->website): ?>
                                                <p><strong>🌐 Website:</strong> <a href="<?php echo htmlspecialchars($ad->website); ?>" target="_blank" title="<?php echo htmlspecialchars($ad->website); ?>"><?php echo strlen($ad->website) > 40 ? htmlspecialchars(substr($ad->website, 0, 40)) . '...' : htmlspecialchars($ad->website); ?></a></p>
                                            <?php endif; ?>
                                            <p><strong>📅 Submitted:</strong> <span><?php echo date('M d, Y', strtotime($ad->submitted_at)); ?></span></p>
                                            <?php if ($ad->message): ?>
                                                <p><strong>📝 Message:</strong> <span><?php echo htmlspecialchars(substr($ad->message, 0, 80)); ?><?php echo strlen($ad->message) > 80 ? '...' : ''; ?></span></p>
                                            <?php endif; ?>
                                        </div>

                                        <?php if ($ad->file_path): ?>
                                            <div class="customer-ad-image">
                                                <img src="<?php echo URLROOT; ?>/images/advertisements/<?php echo htmlspecialchars($ad->file_path); ?>" alt="Ad Image">
                                            </div>
                                        <?php endif; ?>

                                        <div class="customer-ad-actions">
                                            <?php if (in_array($ad->status, ['pending', 'active', 'approved'])): ?>
                                                <a href="javascript:void(0)"
                                                    class="customer-action-btn customer-edit-btn"
                                                    onclick="openEditAdModal(<?php echo $ad->id; ?>, '<?php echo htmlspecialchars($ad->company_name, ENT_QUOTES); ?>', '<?php echo htmlspecialchars($ad->website ?? '', ENT_QUOTES); ?>', '<?php echo htmlspecialchars($ad->message ?? '', ENT_QUOTES); ?>');">
                                                    ✏️ Edit
                                                </a>
                                                <a href="javascript:void(0)"
                                                    class="customer-action-btn customer-cancel-btn"
                                                    onclick="openConfirmModal('<?php echo URLROOT; ?>/customer/deleteAdvertisement/<?php echo $ad->id; ?>', 'Remove this advertisement?', '📢 Remove Ad', '🗑️ Remove');">
                                                    🗑️ Delete
                                                </a>
                                            <?php elseif ($ad->status == 'rejected'): ?>
                                                <span class="customer-ad-status-note">❌ Rejected - You can submit a new ad</span>
                                                <a href="javascript:void(0)"
                                                    class="customer-action-btn customer-cancel-btn"
                                                    onclick="openConfirmModal('<?php echo URLROOT; ?>/customer/deleteAdvertisement/<?php echo $ad->id; ?>', 'Remove this rejected request?', '📢 Remove Ad', '🗑️ Remove');">
                                                    🗑️ Remove
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php
                                endforeach;
                            else:
                                ?>
                                <div class="customer-empty-state">
                                    <p>📢 No advertisements yet.</p>
                                    <p>Submit your first advertisement above to promote your business!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>


                <!-- ============================================
                 PAYMENT HISTORY SECTION
            ============================================ -->
                <div class="customer-content-section" id="payments">

                    <div class="customer-section-header-row">
                        <h2 class="customer-section-heading">Payment History</h2>
                        <a href="javascript:void(0)"
                            class="customer-clear-all-btn"
                            onclick="openConfirmModal('<?php echo URLROOT; ?>/customer/clearAllPayments', 'Clear all payment history? This cannot be undone.', '💳 Clear Payments', '🗑️ Clear All');">
                            🗑️ Clear All
                        </a>
                    </div>

                    <!-- Payment Table -->
                    <div class="customer-payment-table">
                        <table class="customer-data-table">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Date</th>
                                    <th>Stadium</th>
                                    <th>Payment Method</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $payments = $data['payment_history'] ?? [];
                                if ($payments && count($payments) > 0):
                                    foreach ($payments as $payment):
                                ?>
                                        <tr>
                                            <td><span class="payment-id">#<?php echo htmlspecialchars($payment->transaction_id); ?></span></td>
                                            <td><?php echo date('M d, Y', strtotime($payment->payment_date)); ?></td>
                                            <td><?php echo htmlspecialchars($payment->stadium_name); ?></td>
                                            <td><?php echo htmlspecialchars($payment->payment_method); ?></td>
                                            <td><strong style="color: #03B200;">LKR <?php echo number_format($payment->amount, 2); ?></strong></td>
                                            <td>
                                                <?php if ($payment->status == 'completed'): ?>
                                                    <span class="payment-status-completed">✓ Completed</span>
                                                <?php elseif ($payment->status == 'pending'): ?>
                                                    <span class="payment-status-pending">⏳ Pending</span>
                                                <?php elseif ($payment->status == 'failed'): ?>
                                                    <span class="payment-status-failed">✗ Failed</span>
                                                <?php elseif ($payment->status == 'refunded'): ?>
                                                    <span class="payment-status-refunded">↩ Refunded</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)"
                                                    class="customer-payment-delete-btn"
                                                    onclick="openConfirmModal('<?php echo URLROOT; ?>/customer/deletePayment/<?php echo $payment->id; ?>', 'Delete this payment record?', '💳 Delete Payment', '🗑️ Delete');"
                                                    title="Delete">
                                                    🗑️
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                    endforeach;
                                else:
                                    ?>
                                    <tr>
                                        <td colspan="7" style="text-align: center; color: #888; padding: 30px;">
                                            No payment history found.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Payment Summary -->
                    <div class="customer-payment-summary">
                        <div class="customer-payment-summary-header">
                            <h3>💳 Payment Summary</h3>
                        </div>

                        <?php $summary = $data['payment_summary'] ?? []; ?>

                        <div class="customer-payment-summary-grid">

                            <div class="customer-summary-item">
                                <p class="customer-summary-label">Total Transactions</p>
                                <h3 class="customer-summary-value"><?php echo $summary['total_transactions'] ?? 0; ?></h3>
                            </div>

                            <div class="customer-summary-item">
                                <p class="customer-summary-label">Total Amount Paid</p>
                                <h3 class="customer-summary-value customer-summary-highlight">LKR <?php echo number_format($summary['total_amount'] ?? 0, 2); ?></h3>
                            </div>

                            <div class="customer-summary-item">
                                <p class="customer-summary-label">Last Payment</p>
                                <h3 class="customer-summary-value">
                                    <?php echo ($summary['last_payment_date']) ? date('M d, Y', strtotime($summary['last_payment_date'])) : 'N/A'; ?>
                                </h3>
                            </div>

                            <div class="customer-summary-item">
                                <p class="customer-summary-label">Average Payment</p>
                                <h3 class="customer-summary-value">LKR <?php echo number_format($summary['avg_amount'] ?? 0, 2); ?></h3>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- Booking Details Modal -->
                <div id="bookingDetailsModal" class="customer-modal booking-details-modal-overlay">
                    <div class="customer-modal-content booking-details-modal-content">
                        <div class="booking-details-header">
                            <h3>📋 Booking Details</h3>
                            <button type="button" class="customer-modal-close" onclick="closeBookingDetailsModal()">&times;</button>
                        </div>
                        <div class="booking-details-body" id="bookingDetailsContent">
                            <!-- Content will be populated by JavaScript -->
                        </div>
                    </div>
                </div>


            </div>
        </div>


    </div>


    <!-- ============================================
     JAVASCRIPT
============================================ -->
    <script>
        // Smooth scroll for navigation links
        document.querySelectorAll('.customer-nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetSection = document.querySelector(targetId);

                if (targetSection) {
                    targetSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }

                document.querySelectorAll('.customer-nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });


        // Rename Modal Functions
        function openRenameModal(favoriteId, currentName) {
            document.getElementById('rename_favorite_id').value = favoriteId;
            document.getElementById('rename_nickname').value = currentName;
            document.getElementById('renameModal').style.display = 'flex';
        }

        function closeRenameModal() {
            document.getElementById('renameModal').style.display = 'none';
        }


        // Confirm Modal Functions
        function openConfirmModal(url, message, title = '⚠️ Confirm Action', buttonText = '🗑️ Delete') {
            document.getElementById('confirmModalTitle').textContent = title;
            document.getElementById('confirmModalMessage').textContent = message;
            document.getElementById('confirmModalAction').href = url;
            document.getElementById('confirmModalAction').textContent = buttonText;
            document.getElementById('confirmModal').style.display = 'flex';
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').style.display = 'none';
        }


        // Edit Advertisement Modal Functions
        function openEditAdModal(id, company, website, message) {
            document.getElementById('editAdForm').action = '<?php echo URLROOT; ?>/customer/editAdvertisement/' + id;
            document.getElementById('editAdCompany').value = company;
            document.getElementById('editAdWebsite').value = website || '';
            document.getElementById('editAdMessage').value = message || '';
            document.getElementById('editAdModal').style.display = 'flex';
        }

        function closeEditAdModal() {
            document.getElementById('editAdModal').style.display = 'none';
        }

        function closeBookingDetailsModal() {
            document.getElementById('bookingDetailsModal').style.display = 'none';
        }

        // ========== REFUND REQUEST FUNCTIONS ==========

        function showRefundModal(bookingId, stadiumName, refundAmount) {
            // Store booking ID in a data attribute for later use
            document.getElementById('refundForm').dataset.bookingId = bookingId;
            document.getElementById('refundStadiumName').value = stadiumName;
            document.getElementById('refundAmount').value = 'LKR ' + parseFloat(refundAmount).toLocaleString('en-US', {
                minimumFractionDigits: 2
            });
            document.getElementById('refundModal').style.display = 'flex';
        }

        function closeRefundModal() {
            document.getElementById('refundModal').style.display = 'none';
            document.getElementById('refundForm').reset();
        }

        function submitRefundRequest(e) {
            e.preventDefault();

            const bookingId = document.getElementById('refundForm').dataset.bookingId;
            const formData = new FormData(document.getElementById('refundForm'));
            formData.append('booking_id', bookingId);

            fetch('<?php echo URLROOT; ?>/customer/submitRefundRequest/' + bookingId, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('✅ ' + data.message);
                        closeRefundModal();
                        location.reload();
                    } else {
                        alert('❌ Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while submitting your refund request');
                });
        }

        function checkRefundStatus(bookingId, stadiumName, refundAmount) {
            fetch('<?php echo URLROOT; ?>/customer/getRefundStatus/' + bookingId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayRefundStatus(data, stadiumName, refundAmount);
                        document.getElementById('refundStatusModal').style.display = 'flex';
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error fetching refund status');
                });
        }

        function displayRefundStatus(data, stadiumName, refundAmount) {
            let html = `
                <!-- BOOKING INFO -->
                <div style="background: #0d3d0d; border-radius: 10px; padding: 16px; margin-bottom: 20px; border-left: 4px solid #03B200;">
                    <h4 style="color: #03B200; margin: 0 0 12px 0; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Booking Information</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <label style="color: #888; font-size: 12px; text-transform: uppercase; display: block; margin-bottom: 5px;">Stadium</label>
                            <p style="margin: 0; color: #fff; font-weight: 500;">${escapeHtml(stadiumName)}</p>
                        </div>
                        <div>
                            <label style="color: #888; font-size: 12px; text-transform: uppercase; display: block; margin-bottom: 5px;">Booking Amount</label>
                            <p style="margin: 0; color: #03B200; font-weight: 600; font-size: 15px;">LKR ${parseFloat(refundAmount).toLocaleString('en-US', {minimumFractionDigits: 2})}</p>
                        </div>
                    </div>
                </div>
            `;

            if (data.has_refund) {
                const refund = data.refund;
                let statusBg = '#0d3d0d';
                let statusBorder = '#03B200';
                let statusColor = '#03B200';
                let statusIcon = '⏳';
                let statusText = 'PENDING';
                let statusMessage = 'Your refund is being processed. This usually takes 24-48 hours.';

                if (refund.status === 'refunded') {
                    statusBg = '#0d3d1a';
                    statusBorder = '#4CAF50';
                    statusColor = '#4CAF50';
                    statusIcon = '✅';
                    statusText = 'COMPLETED';
                    statusMessage = 'Your refund has been successfully processed! Check your bank account.';
                } else if (refund.status === 'rejected') {
                    statusBg = '#3d0d0d';
                    statusBorder = '#ff6b6b';
                    statusColor = '#ff6b6b';
                    statusIcon = '❌';
                    statusText = 'REJECTED';
                    statusMessage = 'Your refund request was rejected. See details below.';
                }

                html += `
                    <!-- STATUS BADGE -->
                    <div style="background: ${statusBg}; border-radius: 10px; padding: 20px; margin-bottom: 20px; border-left: 4px solid ${statusBorder};">
                        <div style="text-align: center;">
                            <div style="font-size: 36px; margin-bottom: 12px;">${statusIcon}</div>
                            <p style="margin: 0 0 8px 0; color: #888; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Current Status</p>
                            <h2 style="margin: 0; color: ${statusColor}; font-size: 24px; font-weight: 600;">${statusText}</h2>
                        </div>
                    </div>

                    <!-- REFUND DETAILS -->
                    <div style="background: #222; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
                        <h4 style="color: #03B200; margin: 0 0 16px 0; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Refund Details</h4>
                        
                        <div style="margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid #333;">
                            <label style="color: #888; font-size: 12px; text-transform: uppercase; display: block; margin-bottom: 6px;">Refund Amount</label>
                            <p style="margin: 0; color: #03B200; font-weight: 600; font-size: 16px;">LKR ${parseFloat(refund.amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</p>
                        </div>

                        <div style="margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid #333;">
                            <label style="color: #888; font-size: 12px; text-transform: uppercase; display: block; margin-bottom: 6px;">Bank</label>
                            <p style="margin: 0; color: #ddd; font-weight: 500;">${escapeHtml(refund.bank)}</p>
                        </div>

                        <div style="margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid #333;">
                            <label style="color: #888; font-size: 12px; text-transform: uppercase; display: block; margin-bottom: 6px;">Account Number</label>
                            <p style="margin: 0; color: #ddd; font-weight: 500; font-family: monospace; letter-spacing: 1px;">****${refund.account_number}</p>
                            <small style="color: #666; margin-top: 6px; display: block;">Last 4 digits shown for security</small>
                        </div>

                        <div style="margin-bottom: 0;">
                            <label style="color: #888; font-size: 12px; text-transform: uppercase; display: block; margin-bottom: 6px;">📅 Timeline</label>
                            <p style="margin: 0 0 6px 0; color: #ddd; font-size: 13px;">
                                <strong>Requested:</strong> ${refund.created_at}
                            </p>
                            ${refund.updated_at ? `<p style="margin: 0; color: #ddd; font-size: 13px;"><strong>Updated:</strong> ${refund.updated_at}</p>` : ''}
                        </div>
                    </div>

                    <!-- ADMIN NOTES IF ANY -->
                    ${refund.admin_notes ? `
                    <div style="background: rgba(255, 152, 0, 0.08); border: 1px solid #ff9800; border-radius: 8px; padding: 14px 16px; margin-bottom: 20px;">
                        <p style="margin: 0 0 8px 0; color: #ff9800; font-weight: 600; font-size: 13px;">📝 Admin Notes</p>
                        <p style="margin: 0; color: #ddd; font-size: 13px;">${escapeHtml(refund.admin_notes)}</p>
                    </div>
                    ` : ''}

                    <!-- PAYMENT SLIP IF AVAILABLE -->
                    ${refund.status === 'refunded' && refund.payment_slip ? `
                    <div style="background: rgba(3, 178, 0, 0.1); border: 1px dashed #03B200; border-radius: 10px; padding: 16px; margin-bottom: 20px; text-align: center;">
                        <p style="margin: 0 0 10px 0; color: #ddd; font-size: 13px;">The admin has attached a payment slip for your bank transfer.</p>
                        <a href="<?php echo URLROOT; ?>/images/refunds/${refund.payment_slip}" target="_blank" style="display: inline-block; background: #03B200; color: #000; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 13px;">
                            📄 View Payment Slip
                        </a>
                    </div>
                    ` : ''}

                    <!-- STATUS MESSAGE -->
                    <div style="background: rgba(3, 178, 0, 0.08); border: 1px solid #03B200; border-radius: 8px; padding: 14px 16px; display: flex; gap: 10px;">
                        <div style="flex-shrink: 0; font-size: 16px;">ℹ️</div>
                        <p style="margin: 0; color: #ccc; font-size: 12px;">${statusMessage}</p>
                    </div>
                `;
            } else {
                html += `
                    <!-- NO REFUND REQUEST -->
                    <div style="text-align: center; padding: 30px 20px;">
                        <div style="font-size: 48px; margin-bottom: 15px;">📋</div>
                        <h3 style="color: #ddd; margin: 0 0 10px 0; font-size: 16px;">No Refund Request</h3>
                        <p style="color: #888; margin: 0; font-size: 13px; line-height: 1.6;">You haven't submitted a refund request for this booking yet.<br>Use the "Cancel Booking & Request Refund" button to get started.</p>
                    </div>
                `;
            }

            document.getElementById('refundStatusContent').innerHTML = html;
        }

        function closeRefundStatusModal() {
            document.getElementById('refundStatusModal').style.display = 'none';
        }


        // Close modals when clicking outside
        window.onclick = function(event) {
            const renameModal = document.getElementById('renameModal');
            const confirmModal = document.getElementById('confirmModal');
            const editAdModal = document.getElementById('editAdModal');
            const bookingDetailsModal = document.getElementById('bookingDetailsModal');
            const refundModal = document.getElementById('refundModal');
            const refundStatusModal = document.getElementById('refundStatusModal');

            if (event.target === renameModal) {
                closeRenameModal();
            }
            if (event.target === confirmModal) {
                closeConfirmModal();
            }
            if (event.target === editAdModal) {
                closeEditAdModal();
            }
            if (event.target === bookingDetailsModal) {
                closeBookingDetailsModal();
            }
            if (event.target === refundModal) {
                closeRefundModal();
            }
            if (event.target === refundStatusModal) {
                closeRefundStatusModal();
            }
        }


        // Profile Picture Preview
        function previewProfilePicture(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-avatar').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        const appBaseUrl = '<?php echo URLROOT; ?>';
        let activeCustomerConversation = {
            otherUserId: null,
            stadiumId: null,
            subject: null,
            senderName: null
        };

        function selectCustomerConversation(item) {
            document.querySelectorAll('.customer-message-item').forEach(card => card.classList.remove('active'));
            item.classList.add('active');

            activeCustomerConversation.otherUserId = item.dataset.otherUserId;
            activeCustomerConversation.stadiumId = item.dataset.stadiumId || null;
            activeCustomerConversation.subject = item.dataset.subject || 'No subject';
            activeCustomerConversation.senderName = item.dataset.senderName || 'Unknown Sender';

            document.getElementById('customerConversationSubject').textContent = activeCustomerConversation.subject;
            document.getElementById('customerConversationFrom').textContent = `Conversation with ${activeCustomerConversation.senderName}`;
            document.getElementById('customerConversationInfo').textContent = `${item.dataset.stadiumId ? item.dataset.stadiumId : 'General'} · Loading...`;
            loadCustomerConversation(activeCustomerConversation.otherUserId, activeCustomerConversation.stadiumId);
        }

        async function loadCustomerConversation(otherUserId, stadiumId) {
            const formData = new FormData();
            formData.append('other_user_id', otherUserId);
            if (stadiumId) {
                formData.append('stadium_id', stadiumId);
            }

            try {
                const response = await fetch(`${appBaseUrl}/customer/getConversation`, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                });
                const result = await response.json();

                if (!result.success) {
                    alert(result.message || 'Unable to load conversation.');
                    return;
                }

                renderCustomerConversation(result.messages);
            } catch (error) {
                console.error('Conversation load error:', error);
                alert('Unable to load conversation. Please try again.');
            }
        }

        function renderCustomerConversation(messages) {
            const thread = document.getElementById('customerChatThread');
            thread.innerHTML = '';

            if (!messages || messages.length === 0) {
                thread.innerHTML = '<p class="customer-no-chat">No conversation history available.</p>';
                return;
            }

            messages.forEach(message => {
                const bubble = document.createElement('div');
                bubble.className = `chat-bubble ${message.is_sent ? 'sent' : 'received'}`;
                bubble.innerHTML = `
                <div class="chat-meta">
                    <span class="chat-author">${message.is_sent ? 'You' : message.sender_name}</span>
                    <span class="chat-time">${new Date(message.created_at).toLocaleString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })}</span>
                </div>
                <div class="chat-text">${message.message.replace(/\n/g, '<br>')}</div>
            `;
                thread.appendChild(bubble);
            });

            thread.scrollTop = thread.scrollHeight;
        }

        async function sendCustomerReply() {
            const messageText = document.getElementById('customerReplyText').value.trim();

            if (!activeCustomerConversation.otherUserId) {
                alert('Select a conversation first.');
                return;
            }

            if (!messageText) {
                alert('Type a reply before sending.');
                return;
            }

            let subject = activeCustomerConversation.subject || 'No subject';
            if (!subject.toLowerCase().startsWith('re:')) {
                subject = `Re: ${subject}`;
            }

            const formData = new FormData();
            formData.append('receiver_id', activeCustomerConversation.otherUserId);
            if (activeCustomerConversation.stadiumId) {
                formData.append('stadium_id', activeCustomerConversation.stadiumId);
            }
            formData.append('subject', subject);
            formData.append('message', messageText);

            try {
                const response = await fetch(`${appBaseUrl}/Messages/send`, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                });
                const result = await response.json();

                if (!result.success) {
                    alert(result.message || 'Unable to send message.');
                    return;
                }

                const thread = document.getElementById('customerChatThread');
                const bubble = document.createElement('div');
                bubble.className = 'chat-bubble sent';
                bubble.innerHTML = `
                <div class="chat-meta">
                    <span class="chat-author">You</span>
                    <span class="chat-time">${new Date().toLocaleString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })}</span>
                </div>
                <div class="chat-text">${messageText.replace(/\n/g, '<br>')}</div>
            `;
                thread.appendChild(bubble);
                thread.scrollTop = thread.scrollHeight;
                document.getElementById('customerReplyText').value = '';
            } catch (error) {
                console.error('Send reply error:', error);
                alert('Unable to send message. Please try again.');
            }
        }

        document.querySelectorAll('.customer-message-item').forEach(item => {
            item.addEventListener('click', function() {
                selectCustomerConversation(this);
            });
        });

        document.querySelectorAll('.customer-message-filter').forEach(filterBtn => {
            filterBtn.addEventListener('click', function() {
                document.querySelectorAll('.customer-message-filter').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                const filter = this.dataset.filter;

                document.querySelectorAll('.customer-message-item').forEach(item => {
                    if (filter === 'unread') {
                        item.style.display = item.querySelector('.customer-message-unread-count') ? 'block' : 'none';
                    } else {
                        item.style.display = 'block';
                    }
                });
            });
        });

        const initialCustomerItem = document.querySelector('.customer-message-item.active');
        if (initialCustomerItem) {
            selectCustomerConversation(initialCustomerItem);
        }

        // ========== BOOKING DETAILS & CANCEL FUNCTIONS ==========

        function displayBookingDetailsModal(encodedData) {
            try {
                const bookingData = JSON.parse(atob(encodedData));
                displayBookingDetails(bookingData);
            } catch (e) {
                console.error('Error loading booking:', e);
                alert('Error loading booking details');
            }
        }

        function displayBookingDetails(bookingData) {
            // Format booking date
            const bookingDate = new Date(bookingData.booking_date);
            const formattedDate = bookingDate.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            // Format times
            const startTime = new Date('2000-01-01 ' + bookingData.start_time).toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

            const endTime = new Date('2000-01-01 ' + bookingData.end_time).toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

            // Calculate service fee based on total_price
            const baseAmount = parseFloat(bookingData.total_price || 0);
            const serviceFee = baseAmount * 0.02;
            const totalWithFee = baseAmount + serviceFee;

            // Get status and payment status
            const statusClass = bookingData.status.toLowerCase();
            const paymentClass = bookingData.payment_status.toLowerCase();
            let paymentStatusText = bookingData.payment_status === 'paid' ? '✓ Paid' : '⏳ ' + bookingData.payment_status.charAt(0).toUpperCase() + bookingData.payment_status.slice(1);

            let html = `
            <div class="booking-details-section">
                <h4 class="booking-details-title">Booking Reference & Status</h4>
                <div class="booking-details-grid">
                    <div class="booking-detail-item">
                        <span class="booking-detail-label">Booking ID</span>
                        <span class="booking-detail-value">#BK${String(bookingData.id).padStart(6, '0')}</span>
                    </div>
                    <div class="booking-detail-item">
                        <span class="booking-detail-label">Status</span>
                        <span class="booking-status-badge status-${statusClass}">${bookingData.status.toUpperCase()}</span>
                    </div>
                    <div class="booking-detail-item">
                        <span class="booking-detail-label">Payment</span>
                        <span class="booking-payment-badge payment-${paymentClass}">${paymentStatusText}</span>
                    </div>
                </div>
            </div>

            <div class="booking-details-section">
                <h4 class="booking-details-title">Stadium Information</h4>
                <div class="booking-details-grid">
                    <div class="booking-detail-item">
                        <span class="booking-detail-label">Stadium Name</span>
                        <span class="booking-detail-value">${escapeHtml(bookingData.stadium_name || 'N/A')}</span>
                    </div>
                    <div class="booking-detail-item">
                        <span class="booking-detail-label">Location</span>
                        <span class="booking-detail-value">${escapeHtml(bookingData.location || 'N/A')}</span>
                    </div>
                </div>
            </div>

            <div class="booking-details-section">
                <h4 class="booking-details-title">Date & Time</h4>
                <div class="booking-details-grid">
                    <div class="booking-detail-item">
                        <span class="booking-detail-label">Booking Date</span>
                        <span class="booking-detail-value">${formattedDate}</span>
                    </div>
                    <div class="booking-detail-item">
                        <span class="booking-detail-label">Start Time</span>
                        <span class="booking-detail-value">${startTime}</span>
                    </div>
                    <div class="booking-detail-item">
                        <span class="booking-detail-label">End Time</span>
                        <span class="booking-detail-value">${endTime}</span>
                    </div>
                    <div class="booking-detail-item">
                        <span class="booking-detail-label">Duration</span>
                        <span class="booking-detail-value">${parseFloat(bookingData.duration_hours).toFixed(1)} hour(s)</span>
                    </div>
                </div>
            </div>

            <div class="booking-details-section">
                <h4 class="booking-details-title">Pricing Details</h4>
                <div class="booking-pricing-breakdown">
                    <div class="booking-pricing-row">
                        <span>Base Price (${parseFloat(bookingData.duration_hours).toFixed(1)} hrs)</span>
                        <span class="booking-pricing-value">LKR ${baseAmount.toFixed(2)}</span>
                    </div>
                    <div class="booking-pricing-row">
                        <span>Service Fee (2%)</span>
                        <span class="booking-pricing-value">LKR ${serviceFee.toFixed(2)}</span>
                    </div>
                    <div class="booking-pricing-row booking-pricing-total">
                        <span>Total Amount</span>
                        <span class="booking-pricing-value">LKR ${totalWithFee.toFixed(2)}</span>
                    </div>
                </div>
            </div>
        `;

            document.getElementById('bookingDetailsContent').innerHTML = html;
            document.getElementById('bookingDetailsModal').style.display = 'flex';
        }

        function showCancelConfirm(encodedData) {
            try {
                const bookingData = JSON.parse(atob(encodedData));
                cancelBooking(bookingData.id, JSON.stringify(bookingData));
            } catch (e) {
                console.error('Error:', e);
                alert('Error loading booking information');
            }
        }

        function cancelBooking(bookingId, bookingJsonStr) {
            try {
                const bookingData = JSON.parse(bookingJsonStr);

                let message = '';

                if (bookingData.status === 'cancelled') {
                    alert('❌ This booking is already cancelled.');
                    return;
                } else if (bookingData.status === 'completed') {
                    alert('❌ Completed bookings cannot be cancelled.');
                    return;
                } else {
                    // Show confirmation message
                    const startTime = new Date('2000-01-01 ' + bookingData.start_time).toLocaleTimeString('en-US', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });
                    message = '⚠️ CANCEL BOOKING?\n\n' +
                        'Stadium: ' + (bookingData.stadium_name || 'N/A') + '\n' +
                        'Date: ' + bookingData.booking_date + '\n' +
                        'Time: ' + startTime + '\n\n' +
                        'Note: Cancellations are only allowed 6 hours before the booking time.\n\n' +
                        'Are you sure you want to cancel?';

                    if (confirm(message)) {
                        const reason = prompt('Optional: Tell us why you are cancelling (or press OK to skip):', 'Customer requested cancellation');
                        if (reason !== null) {
                            // Perform the cancellation
                            const formData = new FormData();
                            formData.append('reason', reason || 'Customer requested cancellation');

                            fetch('<?php echo URLROOT; ?>/booking/cancel_booking/' + bookingId, {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        alert('✓ Booking cancelled successfully!');
                                        location.reload();
                                    } else {
                                        alert('Error: ' + (data.message || 'Could not cancel booking'));
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('An error occurred while cancelling the booking');
                                });
                        }
                    }
                }
            } catch (e) {
                console.error('Error parsing booking data:', e);
                alert('Error: Could not process booking information');
            }
        }

        function escapeHtml(text) {
            if (!text) return '';
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.toString().replace(/[&<>"']/g, m => map[m]);
        }
    </script>

    <style>
        .customer-message-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(160px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .customer-message-stat-card {
            background: #0f172a;
            border: 1px solid #1f2937;
            border-radius: 16px;
            padding: 20px;
            color: #e2e8f0;
        }

        .customer-message-stat-card span {
            display: block;
            color: #94a3b8;
            margin-bottom: 6px;
            font-size: 13px;
        }

        .customer-message-stat-card strong {
            font-size: 24px;
            color: #f8fafc;
        }

        .customer-message-layout {
            display: grid;
            grid-template-columns: 340px 1fr;
            gap: 20px;
        }

        .customer-message-sidebar {
            background: #0f172a;
            border: 1px solid #1f2937;
            border-radius: 18px;
            padding: 18px;
        }

        .customer-message-filters {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .customer-message-filter {
            flex: 1;
            min-width: 120px;
            padding: 10px 14px;
            border: 1px solid #1f2937;
            border-radius: 12px;
            background: transparent;
            color: #cbd5e1;
            cursor: pointer;
            transition: background 0.2s ease, border-color 0.2s ease;
        }

        .customer-message-filter.active,
        .customer-message-filter:hover {
            background: #111827;
            border-color: #2563eb;
            color: #f8fafc;
        }

        .customer-message-list {
            max-height: 520px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .customer-message-item {
            background: #111827;
            border: 1px solid #1f2937;
            border-radius: 18px;
            padding: 16px;
            margin-bottom: 14px;
            cursor: pointer;
            transition: border-color 0.2s ease, transform 0.15s ease;
        }

        .customer-message-item:hover {
            border-color: #2563eb;
        }

        .customer-message-item.active {
            border-color: #2563eb;
            box-shadow: 0 0 0 1px rgba(37, 99, 235, 0.2);
            transform: translateY(-1px);
        }

        .customer-message-item-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .customer-message-item-title strong {
            color: #f8fafc;
            font-size: 14px;
        }

        .customer-message-pill {
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .customer-message-pill-unread {
            background: rgba(59, 130, 246, 0.15);
            color: #93c5fd;
        }

        .customer-message-pill-read {
            background: rgba(34, 197, 94, 0.12);
            color: #86efac;
        }

        .customer-message-item-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #94a3b8;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .customer-message-item p {
            margin: 0;
            font-size: 13px;
            color: #cbd5e1;
            line-height: 1.6;
        }

        .customer-message-item-property {
            margin-top: 10px;
            display: inline-block;
            background: rgba(16, 185, 129, 0.12);
            color: #a7f3d0;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
        }

        .customer-message-summary {
            display: flex;
            gap: 14px;
            margin-top: 16px;
            flex-wrap: wrap;
        }

        .customer-message-summary-card {
            background: #0f172a;
            border: 1px solid #1f2937;
            border-radius: 16px;
            padding: 18px 22px;
            min-width: 180px;
            color: #e2e8f0;
        }

        .customer-message-summary-card span {
            display: block;
            color: #94a3b8;
            margin-bottom: 6px;
            font-size: 13px;
        }

        .customer-message-summary-card strong {
            font-size: 24px;
            color: #f8fafc;
        }

        .customer-chat-thread {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .chat-bubble {
            max-width: 78%;
            padding: 16px;
            border-radius: 18px;
            position: relative;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.2);
        }

        .chat-bubble.received {
            align-self: flex-start;
            background: #111827;
            color: #e2e8f0;
        }

        .chat-bubble.sent {
            align-self: flex-end;
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            color: white;
        }

        .chat-meta {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 10px;
            font-size: 12px;
            color: #94a3b8;
        }

        .chat-author {
            font-weight: 600;
        }

        .chat-text {
            font-size: 14px;
            line-height: 1.7;
        }

        .customer-no-chat {
            color: #94a3b8;
            font-size: 14px;
        }

        .customer-message-preview {
            background: #0f172a;
            border: 1px solid #1f2937;
            border-radius: 18px;
            display: flex;
            flex-direction: column;
            min-height: 520px;
            overflow: hidden;
        }

        .customer-message-preview-header {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 24px;
            border-bottom: 1px solid #1f2937;
            background: #111827;
        }

        .customer-message-preview-header h3 {
            margin: 0 0 8px 0;
            color: #f8fafc;
            font-size: 18px;
        }

        .customer-message-preview-header p {
            margin: 0;
            color: #94a3b8;
            font-size: 13px;
        }

        .customer-message-preview-body {
            flex: 1;
            padding: 24px;
            color: #cbd5e1;
            line-height: 1.8;
            overflow-y: auto;
        }

        .customer-message-reply {
            padding: 20px 24px 24px;
            border-top: 1px solid #1f2937;
            background: #111827;
        }

        .customer-message-reply textarea {
            width: 100%;
            min-height: 120px;
            border-radius: 14px;
            border: 1px solid #1f2937;
            background: #0f172a;
            color: #e2e8f0;
            padding: 14px;
            resize: vertical;
            margin-bottom: 14px;
            outline: none;
        }

        .customer-message-reply textarea:focus {
            border-color: #2563eb;
        }

        .customer-action-btn.customer-send-btn {
            background: #2563eb;
            color: #ffffff;
            border: none;
            padding: 12px 22px;
            border-radius: 14px;
            cursor: pointer;
        }

        .customer-action-btn.customer-send-btn:hover {
            background: #1d4ed8;
        }

        /* Booking Details Modal Styles */
        .booking-details-modal-overlay {
            display: none !important;
        }

        .booking-details-modal-content {
            width: 90%;
            max-width: 700px;
            max-height: 80vh;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .booking-details-header {
            background: linear-gradient(135deg, #03B200, #028a00);
            color: white;
            padding: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .booking-details-header h3 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
        }

        .booking-details-body {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
        }

        .booking-details-section {
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }

        .booking-details-section:last-child {
            border-bottom: none;
        }

        .booking-details-title {
            margin: 0 0 16px 0;
            font-size: 16px;
            font-weight: 700;
            color: #333;
            padding-bottom: 12px;
            border-bottom: 2px solid #03B200;
        }

        .booking-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }

        .booking-detail-item {
            display: flex;
            flex-direction: column;
        }

        .booking-detail-label {
            font-size: 11px;
            font-weight: 700;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .booking-detail-value {
            font-size: 15px;
            font-weight: 600;
            color: #333;
        }

        .booking-status-badge {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .booking-status-badge.status-confirmed {
            background: #d4edda;
            color: #155724;
        }

        .booking-status-badge.status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .booking-status-badge.status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .booking-payment-badge {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .booking-payment-badge.payment-paid {
            background: #d4edda;
            color: #155724;
        }

        .booking-payment-badge.payment-pending {
            background: #fff3cd;
            color: #856404;
        }

        .booking-pricing-breakdown {
            background: #f9f9f9;
            border-left: 4px solid #03B200;
            padding: 16px;
            border-radius: 8px;
        }

        .booking-pricing-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 14px;
            border-bottom: 1px solid #e0e0e0;
        }

        .booking-pricing-row:last-child {
            border-bottom: none;
        }

        .booking-pricing-row.booking-pricing-total {
            border-top: 2px solid #e0e0e0;
            padding-top: 12px;
            margin-top: 8px;
            font-weight: 700;
            color: #03B200;
            font-size: 16px;
        }

        .booking-pricing-value {
            font-weight: 600;
            color: #333;
        }

        @media (max-width: 992px) {
            .customer-message-layout {
                grid-template-columns: 1fr;
            }

            .customer-message-preview {
                min-height: auto;
            }
        }
    </style>

</body>

</html>