<?php require APPROOT.'/views/inc/components/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/booking-success.css">

<div class="success-container">
    <div class="success-card">
        <div class="success-icon">✅</div>
        
        <h1>Booking Confirmed!</h1>
        <p class="success-message">Your booking has been successfully confirmed.</p>

        <div class="confirmation-details">
            <div class="booking-number">
                <span class="label">Booking Reference:</span>
                <span class="value">#BK<?php echo str_pad($data['booking']->id, 6, '0', STR_PAD_LEFT); ?></span>
            </div>

            <hr>

            <div class="detail-grid">
                <div class="detail">
                    <span class="label">🏟️ Stadium</span>
                    <span class="value"><?php echo htmlspecialchars($data['booking']->stadium_name); ?></span>
                </div>

                <div class="detail">
                    <span class="label">📍 Location</span>
                    <span class="value"><?php echo htmlspecialchars($data['booking']->location); ?></span>
                </div>

                <div class="detail">
                    <span class="label">📅 Date</span>
                    <span class="value"><?php echo date('F d, Y', strtotime($data['booking']->booking_date)); ?></span>
                </div>

                <div class="detail">
                    <span class="label">⏰ Time</span>
                    <span class="value"><?php echo date('h:i A', strtotime($data['booking']->start_time)); ?> - <?php echo date('h:i A', strtotime($data['booking']->end_time)); ?></span>
                </div>

                <div class="detail">
                    <span class="label">⏱️ Duration</span>
                    <span class="value"><?php echo $data['booking']->duration_hours; ?> hours</span>
                </div>

                <div class="detail">
                    <span class="label">💰 Total Amount</span>
                    <span class="value highlight">LKR <?php echo number_format($data['booking']->total_price, 2); ?></span>
                </div>
            </div>

            <hr>

            <div class="status-info">
                <div class="status-badge confirmed">
                    ✓ Booking <?php echo strtoupper($data['booking']->status); ?>
                </div>
                <div class="status-badge <?php echo $data['booking']->payment_status === 'paid' ? 'paid' : 'pending'; ?>">
                    💳 Payment <?php echo strtoupper($data['booking']->payment_status); ?>
                </div>
            </div>
        </div>

        <div class="next-steps">
            <h3>Next Steps</h3>
            <ol>
                <li>A confirmation email has been sent to your registered email address</li>
                <li>Check your email for booking details and stadium contact information</li>
                <li>Contact the stadium owner if you have any questions before your booking</li>
                <li>Arrive 15 minutes early on the day of your booking</li>
            </ol>
        </div>

        <div class="action-buttons">
            <a href="<?php echo URLROOT; ?>/booking/my_bookings" class="btn btn-primary">
                View My Bookings
            </a>
            <a href="<?php echo URLROOT; ?>/stadiums" class="btn btn-secondary">
                Browse More Stadiums
            </a>
        </div>

        <div class="contact-info">
            <h4>Need Help?</h4>
            <p>If you have any questions about your booking, you can:</p>
            <ul>
                <li>📧 Email us at: support@bookmyground.lk</li>
                <li>📞 Call us at: +94 11 234 5678</li>
                <li>💬 Send a message to the stadium owner through your dashboard</li>
            </ul>
        </div>
    </div>

    <!-- Recommended Stadiums Section -->
    <div class="recommended-section">
        <h2>You Might Also Like</h2>
        <div class="stadiums-carousel">
            <!-- This would be populated with JS or passed from controller -->
            <p style="text-align: center; color: #999;">Browse other stadiums to plan your next game</p>
        </div>
    </div>
</div>

<style>
.success-container {
    max-width: 800px;
    margin: 40px auto;
    padding: 20px;
}

.success-card {
    background: white;
    border-radius: 12px;
    padding: 40px 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.success-icon {
    font-size: 60px;
    margin-bottom: 20px;
    animation: scaleIn 0.5s ease-in-out;
}

@keyframes scaleIn {
    from {
        transform: scale(0);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

.success-card h1 {
    color: #333;
    font-size: 32px;
    margin-bottom: 10px;
}

.success-message {
    color: #666;
    font-size: 16px;
    margin-bottom: 30px;
}

.confirmation-details {
    background: #f9f9f9;
    padding: 25px;
    border-radius: 10px;
    margin: 30px 0;
    text-align: left;
}

.booking-number {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.booking-number .label {
    color: #666;
    font-weight: 600;
}

.booking-number .value {
    font-size: 18px;
    font-weight: 700;
    color: #03B200;
    font-family: monospace;
}

.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin: 20px 0;
}

.detail {
    text-align: left;
}

.detail .label {
    display: block;
    color: #666;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 5px;
    font-weight: 600;
}

.detail .value {
    display: block;
    color: #333;
    font-size: 16px;
    font-weight: 600;
}

.detail .value.highlight {
    color: #03B200;
    font-size: 18px;
}

.status-info {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin: 20px 0;
}

.status-badge {
    padding: 10px 20px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
}

.status-badge.confirmed {
    background: #d4edda;
    color: #155724;
}

.status-badge.paid {
    background: #d4edda;
    color: #155724;
}

.status-badge.pending {
    background: #fff3cd;
    color: #856404;
}

.next-steps {
    background: #e8f5e9;
    padding: 20px;
    border-radius: 10px;
    text-align: left;
    margin: 30px 0;
}

.next-steps h3 {
    color: #333;
    margin-bottom: 15px;
}

.next-steps ol {
    margin: 0;
    padding-left: 20px;
}

.next-steps li {
    color: #666;
    margin-bottom: 8px;
    line-height: 1.6;
}

.action-buttons {
    display: flex;
    gap: 15px;
    margin: 30px 0;
    flex-wrap: wrap;
}

.btn {
    flex: 1;
    min-width: 200px;
    padding: 14px 24px;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    text-align: center;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #03B200, #028a00);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(3, 178, 0, 0.3);
}

.btn-secondary {
    background: #e0e0e0;
    color: #333;
}

.btn-secondary:hover {
    background: #d0d0d0;
}

.contact-info {
    background: #f5f5f5;
    padding: 20px;
    border-radius: 10px;
    text-align: left;
    margin-top: 30px;
}

.contact-info h4 {
    color: #333;
    margin-bottom: 10px;
}

.contact-info p {
    color: #666;
    margin-bottom: 10px;
}

.contact-info ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.contact-info li {
    color: #666;
    padding: 5px 0;
    font-size: 14px;
}

.recommended-section {
    margin-top: 50px;
}

.recommended-section h2 {
    color: #333;
    text-align: center;
    margin-bottom: 30px;
}

.stadiums-carousel {
    background: #f9f9f9;
    padding: 40px 20px;
    border-radius: 10px;
}

@media (max-width: 600px) {
    .success-card {
        padding: 20px;
    }

    .detail-grid {
        grid-template-columns: 1fr;
    }

    .success-card h1 {
        font-size: 24px;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn {
        min-width: 100%;
    }

    .status-info {
        flex-direction: column;
    }
}
</style>

<?php require APPROOT.'/views/inc/components/footer.php'; ?>
