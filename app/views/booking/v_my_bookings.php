<?php require APPROOT . '/views/inc/components/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/my-bookings.css">

<div class="my-bookings-container">
    <div class="page-header">
        <h1>📅 My Bookings</h1>
        <p>Manage and view all your stadium bookings</p>
    </div>

    <?php if (empty($data['bookings'])): ?>
        <div class="no-bookings">
            <div class="empty-state">
                <div class="empty-icon">📅</div>
                <h2>No Bookings Yet</h2>
                <p>You haven't made any bookings yet. Start exploring stadiums and make your first booking!</p>
                <a href="<?php echo URLROOT; ?>/stadiums" class="btn-browse">Browse Stadiums</a>
            </div>
        </div>
    <?php else: ?>

        <div class="bookings-tabs">
            <button class="tab-btn active" data-filter="all">All Bookings</button>
            <button class="tab-btn" data-filter="confirmed">Confirmed</button>
            <button class="tab-btn" data-filter="pending">Pending</button>
            <button class="tab-btn" data-filter="completed">Completed</button>
            <button class="tab-btn" data-filter="cancelled">Cancelled</button>
        </div>

        <div class="bookings-list">
            <?php foreach ($data['bookings'] as $booking): ?>
                <div class="booking-card" data-status="<?php echo strtolower($booking->status); ?>" data-booking-id="<?php echo $booking->id; ?>" data-booking-json="<?php echo htmlspecialchars(json_encode($booking), ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="booking-header">
                        <div class="booking-title">
                            <h3><?php echo htmlspecialchars($booking->stadium_name); ?></h3>
                            <p class="booking-ref">#BK<?php echo str_pad($booking->id, 6, '0', STR_PAD_LEFT); ?></p>
                        </div>
                        <span class="status-badge status-<?php echo strtolower($booking->status); ?>">
                            <?php echo strtoupper($booking->status); ?>
                        </span>
                    </div>

                    <div class="booking-content">
                        <div class="booking-details">
                            <div class="detail">
                                <span class="label">📍 Location</span>
                                <span class="value"><?php echo htmlspecialchars($booking->location); ?></span>
                            </div>

                            <div class="detail">
                                <span class="label">📅 Date</span>
                                <span class="value"><?php echo date('F d, Y', strtotime($booking->booking_date)); ?></span>
                            </div>

                            <div class="detail">
                                <span class="label">⏰ Time</span>
                                <span class="value"><?php echo date('h:i A', strtotime($booking->start_time)); ?> - <?php echo date('h:i A', strtotime($booking->end_time)); ?></span>
                            </div>

                            <div class="detail">
                                <span class="label">⏱️ Duration</span>
                                <span class="value"><?php echo $booking->duration_hours; ?> hours</span>
                            </div>
                        </div>

                        <div class="booking-footer">
                            <div class="price-info">
                                <span class="price-label">Total Amount</span>
                                <span class="price-value">LKR <?php echo number_format($booking->total_price, 2); ?></span>
                            </div>

                            <div class="payment-status payment-<?php echo strtolower($booking->payment_status); ?>">
                                <?php if ($booking->payment_status === 'paid'): ?>
                                    ✓ Paid
                                <?php else: ?>
                                    ⏳ <?php echo ucfirst($booking->payment_status); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="booking-actions">
                        <button class="btn-action btn-details" onclick="openBookingDetailsModal(this)">
                            📋 Booking Details
                        </button>

                        <?php
                        // Show cancel button for confirmed, pending, and reserved bookings
                        if (in_array($booking->status, ['confirmed', 'pending', 'reserved'])): ?>
                            <button class="btn-action btn-cancel" onclick="cancelBooking(<?php echo $booking->id; ?>)">
                                ❌ Cancel Booking
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</div>

<!-- Review Modal -->
<div id="reviewModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Leave a Review</h3>
            <button class="close-btn" onclick="closeReviewModal()">&times;</button>
        </div>
        <form id="reviewForm" class="review-form">
            <input type="hidden" name="stadium_id" id="reviewStadiumId">
            <input type="hidden" name="booking_id" id="reviewBookingId">

            <div class="form-group">
                <label>Overall Rating</label>
                <div class="rating-input">
                    <input type="radio" name="rating" value="5" id="rating5">
                    <label for="rating5">⭐⭐⭐⭐⭐ Excellent</label>

                    <input type="radio" name="rating" value="4" id="rating4">
                    <label for="rating4">⭐⭐⭐⭐ Good</label>

                    <input type="radio" name="rating" value="3" id="rating3">
                    <label for="rating3">⭐⭐⭐ Average</label>

                    <input type="radio" name="rating" value="2" id="rating2">
                    <label for="rating2">⭐⭐ Poor</label>

                    <input type="radio" name="rating" value="1" id="rating1">
                    <label for="rating1">⭐ Very Poor</label>
                </div>
            </div>

            <div class="form-group">
                <label for="comment">Your Review</label>
                <textarea id="comment" name="comment" placeholder="Share your experience..." rows="5"></textarea>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeReviewModal()">Cancel</button>
                <button type="submit" class="btn-submit">Submit Review</button>
            </div>
        </form>
    </div>
</div>

<!-- Cancel Booking Modal -->
<div id="cancelModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Cancel Booking</h3>
            <button class="close-btn" onclick="closeCancelModal()">&times;</button>
        </div>
        <form id="cancelForm" class="cancel-form">
            <input type="hidden" name="booking_id" id="cancelBookingId">

            <div id="cancellationPolicyInfo" class="cancellation-policy-box">
                <div class="policy-header">⚠️ Cancellation Policy</div>
                <div class="policy-content">
                    <p><strong>Bookings can only be cancelled 6 hours before the scheduled time.</strong></p>
                    <p id="hoursRemainingText" style="margin-top: 10px; color: #03B200; font-weight: 600;"></p>
                </div>
            </div>

            <div class="form-group" id="reasonSection" style="display: none;">
                <p style="color: #666; margin-bottom: 15px;">
                    Are you sure you want to cancel this booking?
                </p>
                <label for="cancelReason">Cancellation Reason (Optional)</label>
                <textarea id="cancelReason" name="reason" placeholder="Tell us why you're cancelling..." rows="4"></textarea>
            </div>

            <div class="form-actions" id="formActionsSection">
                <button type="button" class="btn-cancel" onclick="closeCancelModal()">Close</button>
                <button type="submit" class="btn-danger" id="submitCancelBtn" disabled>Cancel Booking</button>
            </div>
        </form>
    </div>
</div>

<!-- Booking Details Modal -->
<div id="bookingDetailsModal" class="modal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h3>Booking Details</h3>
            <button class="close-btn" onclick="closeBookingDetailsModal()">&times;</button>
        </div>
        <div class="booking-details-content" id="bookingDetailsContent">
            <!-- Content will be populated by JavaScript -->
        </div>
    </div>
</div>

<style>
    .my-bookings-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 20px;
    }

    .page-header {
        margin-bottom: 40px;
    }

    .page-header h1 {
        color: #333;
        font-size: 32px;
        margin-bottom: 10px;
    }

    .page-header p {
        color: #666;
        font-size: 16px;
    }

    .no-bookings {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 400px;
    }

    .empty-state {
        text-align: center;
    }

    .empty-icon {
        font-size: 60px;
        margin-bottom: 20px;
    }

    .empty-state h2 {
        color: #333;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #666;
        margin-bottom: 20px;
    }

    .btn-browse {
        display: inline-block;
        background: linear-gradient(135deg, #03B200, #028a00);
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-browse:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(3, 178, 0, 0.3);
    }

    .bookings-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 30px;
        border-bottom: 2px solid #e0e0e0;
        overflow-x: auto;
        padding-bottom: 0;
    }

    .tab-btn {
        padding: 12px 20px;
        border: none;
        background: none;
        cursor: pointer;
        color: #666;
        font-weight: 600;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .tab-btn:hover {
        color: #03B200;
    }

    .tab-btn.active {
        color: #03B200;
        border-bottom-color: #03B200;
    }

    .bookings-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .booking-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .booking-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .booking-card[data-status="cancelled"] {
        opacity: 0.7;
        background: #f9f9f9;
    }

    .booking-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background: linear-gradient(135deg, #f9f9f9, #f5f5f5);
        border-bottom: 1px solid #e0e0e0;
    }

    .booking-title h3 {
        color: #333;
        margin: 0 0 5px 0;
        font-size: 18px;
    }

    .booking-ref {
        color: #999;
        font-size: 12px;
        margin: 0;
        font-family: monospace;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.status-confirmed {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-badge.status-completed {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-badge.status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .booking-content {
        padding: 20px;
    }

    .booking-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    .detail {
        display: flex;
        flex-direction: column;
    }

    .detail .label {
        color: #999;
        font-size: 12px;
        text-transform: uppercase;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .detail .value {
        color: #333;
        font-size: 14px;
        font-weight: 600;
    }

    .booking-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-top: 1px solid #e0e0e0;
    }

    .price-info {
        display: flex;
        flex-direction: column;
    }

    .price-label {
        color: #999;
        font-size: 12px;
        margin-bottom: 3px;
    }

    .price-value {
        color: #03B200;
        font-size: 18px;
        font-weight: 700;
    }

    .payment-status {
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
    }

    .payment-status.payment-paid {
        background: #d4edda;
        color: #155724;
    }

    .payment-status.payment-pending {
        background: #fff3cd;
        color: #856404;
    }

    .booking-actions {
        display: flex;
        gap: 10px;
        padding: 15px 20px;
        background: #f9f9f9;
        border-top: 1px solid #e0e0e0;
    }

    .btn-action {
        flex: 1;
        padding: 10px 15px;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
    }

    .btn-details {
        background: linear-gradient(135deg, #03B200, #028a00);
        color: white;
    }

    .btn-details:hover {
        background: linear-gradient(135deg, #028a00, #016d00);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(3, 178, 0, 0.3);
    }

    .btn-cancel {
        background: linear-gradient(135deg, #f44336, #d32f2f);
        color: white;
    }

    .btn-cancel:hover {
        background: linear-gradient(135deg, #d32f2f, #c62828);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(244, 67, 54, 0.3);
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: white;
        margin: 15% auto;
        padding: 30px;
        border-radius: 10px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .modal-content.modal-lg {
        max-width: 700px;
        max-height: 80vh;
        overflow-y: auto;
    }

    .booking-details-content {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .details-section {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .details-section-title {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        padding-bottom: 10px;
        border-bottom: 2px solid #03B200;
    }

    .details-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .details-row:last-child {
        border-bottom: none;
    }

    .details-label {
        color: #666;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .details-value {
        color: #333;
        font-size: 15px;
        font-weight: 500;
        text-align: right;
    }

    .details-value.highlight {
        color: #03B200;
        font-weight: 700;
    }

    .status-badge-large {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .status-badge-large.status-confirmed {
        background: #d4edda;
        color: #155724;
    }

    .status-badge-large.status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-badge-large.status-completed {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-badge-large.status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .payment-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .payment-badge.paid {
        background: #d4edda;
        color: #155724;
    }

    .payment-badge.pending {
        background: #fff3cd;
        color: #856404;
    }

    .pricing-breakdown {
        background: #f9f9f9;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #03B200;
    }

    .pricing-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 14px;
    }

    .pricing-row.total {
        border-top: 1px solid #e0e0e0;
        padding-top: 12px;
        font-weight: 700;
        font-size: 16px;
        color: #03B200;
    }

    .download-btn {
        background: linear-gradient(135deg, #03B200, #028a00);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
        width: 100%;
        text-align: center;
    }

    .download-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(3, 178, 0, 0.3);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e0e0e0;
    }

    .modal-header h3 {
        margin: 0;
        color: #333;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #999;
    }

    .close-btn:hover {
        color: #333;
    }

    .review-form,
    .cancel-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .form-group label {
        color: #333;
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }

    .rating-input {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .rating-input input[type="radio"] {
        display: none;
    }

    .rating-input label {
        padding: 10px;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: normal;
        margin-bottom: 0;
    }

    .rating-input input[type="radio"]:checked+label {
        background: #e8f5e9;
        border-color: #03B200;
        color: #038000;
    }

    textarea {
        padding: 10px;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        font-family: inherit;
        font-size: 14px;
        resize: vertical;
    }

    textarea:focus {
        outline: none;
        border-color: #03B200;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 15px;
    }

    .btn-cancel,
    .btn-submit,
    .btn-danger {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-cancel {
        background: #e0e0e0;
        color: #666;
    }

    .btn-cancel:hover {
        background: #d0d0d0;
    }

    .btn-submit {
        background: linear-gradient(135deg, #03B200, #028a00);
        color: white;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(3, 178, 0, 0.3);
    }

    .btn-danger {
        background: #f44336;
        color: white;
    }

    .btn-danger:hover {
        background: #d32f2f;
    }

    .btn-danger:disabled {
        background: #ccc;
        color: #999;
        cursor: not-allowed;
    }

    .cancellation-policy-box {
        background: #fff3cd;
        border: 2px solid #ffc107;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .policy-header {
        font-weight: 700;
        color: #856404;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .policy-content {
        color: #856404;
        font-size: 14px;
        line-height: 1.6;
    }

    .policy-content p {
        margin: 0;
    }

    @media (max-width: 768px) {
        .my-bookings-container {
            padding: 10px;
        }

        .page-header h1 {
            font-size: 24px;
        }

        .booking-details {
            grid-template-columns: 1fr;
        }

        .booking-actions {
            flex-direction: column;
        }

        .bookings-tabs {
            overflow-x: auto;
            white-space: nowrap;
        }

        .modal-content {
            width: 95%;
            margin: 30% auto;
        }

        .modal-content.modal-lg {
            max-width: 95%;
            margin: 20% auto;
        }

        .details-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .details-value {
            text-align: left;
            margin-top: 5px;
        }

        .pricing-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .pricing-row span:last-child {
            text-align: left;
            margin-top: 5px;
        }
    }
</style>

<script>
    // Tab filtering
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.dataset.filter;

            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            document.querySelectorAll('.booking-card').forEach(card => {
                if (filter === 'all' || card.dataset.status === filter) {
                    card.style.display = 'flex';
                    card.style.flexDirection = 'column';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Review modal functions
    function openReviewModal(stadiumId, bookingId) {
        document.getElementById('reviewStadiumId').value = stadiumId;
        document.getElementById('reviewBookingId').value = bookingId;
        document.getElementById('reviewModal').style.display = 'block';
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').style.display = 'none';
        document.getElementById('reviewForm').reset();
    }

    document.getElementById('reviewForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Review submitted! Thank you for your feedback.');
        closeReviewModal();
    });

    // Cancel modal functions
    const cancelModal = document.getElementById('cancelModal');

    function cancelBooking(bookingId) {
        const bookingCard = document.querySelector(`[data-booking-id="${bookingId}"]`);
        const bookingJsonStr = bookingCard.getAttribute('data-booking-json');

        if (!bookingJsonStr) {
            alert('Error loading booking information');
            return;
        }

        try {
            const bookingData = JSON.parse(bookingJsonStr);

            // Check if cancellation is allowed (6 hours before start time)
            const bookingDateTime = new Date(bookingData.booking_date + ' ' + bookingData.start_time);
            const currentDateTime = new Date();
            const timeDiff = bookingDateTime - currentDateTime;
            const hoursRemaining = timeDiff / (1000 * 60 * 60);

            document.getElementById('cancelBookingId').value = bookingId;

            const hoursRemainingText = document.getElementById('hoursRemainingText');
            const reasonSection = document.getElementById('reasonSection');
            const submitBtn = document.getElementById('submitCancelBtn');

            if (bookingData.status === 'cancelled') {
                hoursRemainingText.innerHTML = '<span style="color: #d32f2f;">❌ This booking is already cancelled and cannot be modified.</span>';
                reasonSection.style.display = 'none';
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Already Cancelled';
            } else if (bookingData.status === 'completed') {
                hoursRemainingText.innerHTML = '<span style="color: #d32f2f;">❌ Completed bookings cannot be cancelled.</span>';
                reasonSection.style.display = 'none';
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Cannot Cancel';
            } else if (hoursRemaining < 0) {
                hoursRemainingText.innerHTML = '<span style="color: #d32f2f;">❌ Booking time has already passed. Cannot cancel.</span>';
                reasonSection.style.display = 'none';
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Past Booking';
            } else if (hoursRemaining < 6) {
                const formattedHours = hoursRemaining.toFixed(1);
                hoursRemainingText.innerHTML = `<span style="color: #d32f2f;">❌ Only ${formattedHours} hours remaining. Minimum 6 hours required for cancellation.</span>`;
                reasonSection.style.display = 'none';
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Cannot Cancel (< 6 hrs)';
            } else {
                const formattedHours = hoursRemaining.toFixed(1);
                hoursRemainingText.innerHTML = `<span style="color: #03B200;">✓ ${formattedHours} hours remaining - Cancellation is allowed!</span>`;
                reasonSection.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Confirm Cancellation';
            }

            cancelModal.style.display = 'block';
        } catch (e) {
            console.error('Error parsing booking data:', e);
            alert('Error: Could not process booking information');
        }
    }

    function closeCancelModal() {
        cancelModal.style.display = 'none';
        document.getElementById('cancelForm').reset();
    }

    document.getElementById('cancelForm')?.addEventListener('submit', function(e) {
        e.preventDefault();

        const bookingId = document.getElementById('cancelBookingId').value;
        const reason = document.getElementById('cancelReason').value;

        const formData = new FormData();
        formData.append('reason', reason);

        const submitBtn = document.getElementById('submitCancelBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Cancelling...';

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
                    let errorMsg = data.message;
                    if (data.required_hours && data.hours_remaining) {
                        errorMsg += ` (${data.hours_remaining} hours remaining, ${data.required_hours} hours required)`;
                    }
                    alert('Cannot cancel: ' + errorMsg);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Confirm Cancellation';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while cancelling the booking');
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Confirm Cancellation';
            });
    });

    // Close modals when clicking outside
    window.onclick = function(event) {
        const reviewModal = document.getElementById('reviewModal');
        const cancelModal = document.getElementById('cancelModal');
        const bookingDetailsModal = document.getElementById('bookingDetailsModal');

        if (event.target == reviewModal) {
            reviewModal.style.display = 'none';
        }
        if (event.target == cancelModal) {
            cancelModal.style.display = 'none';
        }
        if (event.target == bookingDetailsModal) {
            bookingDetailsModal.style.display = 'none';
        }
    }

    // Booking Details Modal Functions
    function openBookingDetailsModal(buttonElement) {
        // Get the booking card element (parent)
        const bookingCard = buttonElement.closest('.booking-card');
        const bookingJsonStr = bookingCard.getAttribute('data-booking-json');

        if (!bookingJsonStr) {
            console.error('Booking data not found');
            return;
        }

        try {
            const bookingData = JSON.parse(bookingJsonStr);
            displayBookingDetails(bookingData);
        } catch (e) {
            console.error('Error parsing booking data:', e);
        }
    }

    function displayBookingDetails(bookingData) {
        const modal = document.getElementById('bookingDetailsModal');
        const content = document.getElementById('bookingDetailsContent');

        // Format dates
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

        // Calculate service fee
        const serviceFee = parseFloat(bookingData.total_price) * 0.02;
        const totalWithFee = parseFloat(bookingData.total_price) + serviceFee;

        // Build HTML content
        const statusClass = bookingData.status.toLowerCase();
        const paymentClass = bookingData.payment_status.toLowerCase();

        let paymentStatusText = bookingData.payment_status === 'paid' ? '✓ Paid' : '⏳ ' + bookingData.payment_status.charAt(0).toUpperCase() + bookingData.payment_status.slice(1);

        let html = `
        <div class="details-section">
            <div class="details-section-title">Booking Reference & Status</div>
            <div class="details-row">
                <span class="details-label">Booking ID</span>
                <span class="details-value">#BK${String(bookingData.id).padStart(6, '0')}</span>
            </div>
            <div class="details-row">
                <span class="details-label">Booking Status</span>
                <span class="status-badge-large status-${statusClass}">${bookingData.status.toUpperCase()}</span>
            </div>
            <div class="details-row">
                <span class="details-label">Payment Status</span>
                <span class="payment-badge ${paymentClass}">${paymentStatusText}</span>
            </div>
        </div>

        <div class="details-section">
            <div class="details-section-title">Stadium Information</div>
            <div class="details-row">
                <span class="details-label">Stadium Name</span>
                <span class="details-value">${escapeHtml(bookingData.stadium_name)}</span>
            </div>
            <div class="details-row">
                <span class="details-label">Location</span>
                <span class="details-value">${escapeHtml(bookingData.location)}</span>
            </div>
            <div class="details-row">
                <span class="details-label">Stadium Owner</span>
                <span class="details-value">${escapeHtml(bookingData.owner_first_name)} ${escapeHtml(bookingData.owner_last_name)}</span>
            </div>
        </div>

        <div class="details-section">
            <div class="details-section-title">Date & Time</div>
            <div class="details-row">
                <span class="details-label">Booking Date</span>
                <span class="details-value">${formattedDate}</span>
            </div>
            <div class="details-row">
                <span class="details-label">Start Time</span>
                <span class="details-value">${startTime}</span>
            </div>
            <div class="details-row">
                <span class="details-label">End Time</span>
                <span class="details-value">${endTime}</span>
            </div>
            <div class="details-row">
                <span class="details-label">Duration</span>
                <span class="details-value">${bookingData.duration_hours} hour(s)</span>
            </div>
        </div>

        <div class="details-section">
            <div class="details-section-title">Pricing Details</div>
            <div class="pricing-breakdown">
                <div class="pricing-row">
                    <span>Base Price (${bookingData.duration_hours} hrs)</span>
                    <span>LKR ${parseFloat(bookingData.total_price).toFixed(2)}</span>
                </div>
                <div class="pricing-row">
                    <span>Service Fee (2%)</span>
                    <span>LKR ${serviceFee.toFixed(2)}</span>
                </div>
                <div class="pricing-row total">
                    <span>Total Amount</span>
                    <span>LKR ${totalWithFee.toFixed(2)}</span>
                </div>
            </div>
        </div>
    `;

        // Add notes if available
        if (bookingData.customer_notes && bookingData.customer_notes.trim() !== '') {
            html += `
            <div class="details-section">
                <div class="details-section-title">Your Notes</div>
                <div style="padding: 12px; background: #f9f9f9; border-radius: 6px; color: #333; line-height: 1.6;">
                    ${escapeHtml(bookingData.customer_notes)}
                </div>
            </div>
        `;
        }

        // Add cancellation reason if booking is cancelled
        if (bookingData.status === 'cancelled' && bookingData.cancellation_reason) {
            html += `
            <div class="details-section">
                <div class="details-section-title">Cancellation Details</div>
                <div class="details-row">
                    <span class="details-label">Cancelled On</span>
                    <span class="details-value">${new Date(bookingData.cancelled_at).toLocaleString()}</span>
                </div>
                <div style="padding: 12px; background: #fef2f2; border-radius: 6px; color: #721c24; line-height: 1.6; margin-top: 10px;">
                    <strong>Reason:</strong> ${escapeHtml(bookingData.cancellation_reason)}
                </div>
            </div>
        `;
        }

        content.innerHTML = html;
        const detailsModal = document.getElementById('bookingDetailsModal');
        detailsModal.style.display = 'block';
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

    function closeBookingDetailsModal() {
        document.getElementById('bookingDetailsModal').style.display = 'none';
    }
</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>