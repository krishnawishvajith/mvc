<?php require APPROOT.'/views/inc/components/header.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/booking.css">

<div class="booking-container">
    <div class="breadcrumb">
        <a href="<?php echo URLROOT; ?>/">Home</a>
        <span>/</span>
        <a href="<?php echo URLROOT; ?>/stadiums">Stadiums</a>
        <span>/</span>
        <span>Confirm Booking</span>
    </div>

    <div class="confirm-booking-section">
        <h1>Confirm Your Booking</h1>
        
        <div class="booking-details-grid">
            <!-- Booking Summary -->
            <div class="booking-summary">
                <h3>🏟️ Booking Summary</h3>
                
                <div class="detail-row">
                    <span class="label">Stadium:</span>
                    <span class="value"><?php echo htmlspecialchars($data['booking']->stadium_name); ?></span>
                </div>

                <div class="detail-row">
                    <span class="label">Location:</span>
                    <span class="value"><?php echo htmlspecialchars($data['booking']->location); ?></span>
                </div>

                <div class="detail-row">
                    <span class="label">Date:</span>
                    <span class="value"><?php echo date('F d, Y', strtotime($data['booking']->booking_date)); ?></span>
                </div>

                <div class="detail-row">
                    <span class="label">Time:</span>
                    <span class="value"><?php echo date('h:i A', strtotime($data['booking']->start_time)); ?> - <?php echo date('h:i A', strtotime($data['booking']->end_time)); ?></span>
                </div>

                <div class="detail-row">
                    <span class="label">Duration:</span>
                    <span class="value"><?php echo $data['booking']->duration_hours; ?> hours</span>
                </div>

                <hr>

                <div class="price-breakdown">
                    <div class="detail-row">
                        <span class="label">Price per Hour:</span>
                        <span class="value">LKR <?php echo number_format($data['booking']->stadium_price, 2); ?></span>
                    </div>

                    <div class="detail-row">
                        <span class="label">Duration:</span>
                        <span class="value"><?php echo $data['booking']->duration_hours; ?> hours</span>
                    </div>

                    <div class="detail-row subtotal">
                        <span class="label">Subtotal:</span>
                        <span class="value">LKR <?php echo number_format($data['booking']->total_price, 2); ?></span>
                    </div>

                    <div class="detail-row fee">
                        <span class="label">Service Fee (2%):</span>
                        <span class="value">LKR <?php echo number_format($data['booking']->total_price * 0.02, 2); ?></span>
                    </div>

                    <div class="detail-row total">
                        <span class="label">Total Amount:</span>
                        <span class="value" id="totalAmount">LKR <?php echo number_format($data['booking']->total_price * 1.02, 2); ?></span>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="payment-section">
                <h3>💳 Payment Details</h3>

                <form id="paymentForm" class="payment-form">
                    <input type="hidden" name="booking_id" value="<?php echo $data['booking']->id; ?>">

                    <div class="form-group">
                        <label>Payment Method</label>
                        <div class="payment-methods">
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="stripe" checked>
                                <span class="option-label">
                                    💳 Credit/Debit Card (Stripe)
                                    <small>Secure online payment</small>
                                </span>
                            </label>

                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="bank_transfer">
                                <span class="option-label">
                                    🏦 Bank Transfer
                                    <small>Direct transfer to our account</small>
                                </span>
                            </label>

                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="cash">
                                <span class="option-label">
                                    💵 Pay at Venue
                                    <small>Pay when you arrive</small>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="stripe-element-container" id="stripeContainer" style="display: block;">
                        <div id="card-element" class="stripe-element">
                            <!-- Stripe card element will be inserted here -->
                        </div>
                        <div id="card-errors" class="stripe-errors" role="alert"></div>
                    </div>

                    <div class="form-group bank-transfer-info" style="display: none;">
                        <h4>Bank Transfer Details</h4>
                        <p><strong>Bank Name:</strong> Commercial Bank of Sri Lanka</p>
                        <p><strong>Account Name:</strong> BookMyGround</p>
                        <p><strong>Account Number:</strong> 1234567890</p>
                        <p><strong>Routing Number:</strong> 987654</p>
                        <p style="color: #ff9800; margin-top: 10px;">
                            <strong>⚠️ Note:</strong> Your booking will be confirmed once payment is received and verified.
                        </p>
                    </div>

                    <div class="form-group">
                        <label class="checkbox">
                            <input type="checkbox" id="termsCheckbox" required>
                            <span>I agree to the <a href="<?php echo URLROOT; ?>/pages/terms" target="_blank">terms and conditions</a></span>
                        </label>
                    </div>

                    <button type="submit" class="btn-confirm-payment" id="submitBtn">
                        <span id="btnText">Proceed to Payment (LKR <?php echo number_format($data['booking']->total_price * 1.02, 2); ?>)</span>
                        <span id="btnLoader" style="display: none;">
                            <i class="loader"></i> Processing...
                        </span>
                    </button>

                    <button type="button" class="btn-cancel" onclick="window.history.back()">
                        Back to Stadium
                    </button>
                </form>

                <!-- Cancellation Policy -->
                <div class="cancellation-policy">
                    <h4>📋 Cancellation Policy</h4>
                    <ul>
                        <li><strong>Free cancellation:</strong> Up to 7 days before booking</li>
                        <li><strong>50% refund:</strong> 3-7 days before booking</li>
                        <li><strong>No refund:</strong> Less than 3 days before booking</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.booking-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.breadcrumb {
    margin-bottom: 30px;
    font-size: 14px;
}

.breadcrumb a {
    color: #03B200;
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.confirm-booking-section h1 {
    color: #333;
    margin-bottom: 30px;
    text-align: center;
    font-size: 32px;
}

.booking-details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 40px;
}

.booking-summary, .payment-section {
    background: #f9f9f9;
    padding: 30px;
    border-radius: 12px;
    border: 1px solid #e0e0e0;
}

.booking-summary h3, .payment-section h3 {
    color: #333;
    margin-bottom: 20px;
    font-size: 18px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #e0e0e0;
    font-size: 14px;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-row .label {
    color: #666;
    font-weight: 500;
}

.detail-row .value {
    color: #333;
    font-weight: 600;
}

.detail-row.subtotal {
    background: #f0f0f0;
    padding: 12px 10px;
    margin: 10px -10px 0 -10px;
}

.detail-row.fee {
    font-size: 13px;
    color: #ff9800;
}

.detail-row.total {
    background: linear-gradient(135deg, #03B200, #028a00);
    color: white;
    padding: 15px 10px;
    margin: 15px -10px 0 -10px;
    border-radius: 8px;
    border: none;
}

.detail-row.total .label,
.detail-row.total .value {
    color: white;
    font-weight: 700;
    font-size: 16px;
}

.payment-form {
    background: white;
    padding: 20px;
    border-radius: 8px;
    margin-top: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 10px;
    color: #333;
    font-weight: 600;
    font-size: 14px;
}

.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.payment-option {
    display: flex;
    align-items: flex-start;
    padding: 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.payment-option:hover {
    border-color: #03B200;
    background: #f9fff3;
}

.payment-option input[type="radio"] {
    margin-right: 12px;
    margin-top: 2px;
    cursor: pointer;
}

.payment-option input[type="radio"]:checked ~ .option-label {
    color: #03B200;
    font-weight: 600;
}

.option-label {
    flex: 1;
}

.option-label small {
    display: block;
    color: #999;
    font-size: 12px;
    font-weight: normal;
    margin-top: 3px;
}

.stripe-element-container {
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 12px;
    background: white;
}

.stripe-element {
    padding: 10px;
}

.stripe-errors {
    color: #dc3545;
    margin-top: 10px;
    font-size: 14px;
}

.bank-transfer-info {
    background: #fffbe6;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #ff9800;
}

.bank-transfer-info h4 {
    color: #333;
    margin-bottom: 10px;
}

.bank-transfer-info p {
    margin: 8px 0;
    font-size: 13px;
    color: #666;
}

.checkbox {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.checkbox input {
    margin-right: 10px;
    cursor: pointer;
}

.checkbox a {
    color: #03B200;
    text-decoration: none;
}

.checkbox a:hover {
    text-decoration: underline;
}

.btn-confirm-payment, .btn-cancel {
    width: 100%;
    padding: 14px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 10px;
}

.btn-confirm-payment {
    background: linear-gradient(135deg, #03B200, #028a00);
    color: white;
}

.btn-confirm-payment:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(3, 178, 0, 0.3);
}

.btn-confirm-payment:disabled {
    background: #ccc;
    cursor: not-allowed;
    opacity: 0.6;
}

.btn-cancel {
    background: #e0e0e0;
    color: #666;
}

.btn-cancel:hover {
    background: #d0d0d0;
}

.cancellation-policy {
    background: #f5f5f5;
    padding: 15px;
    border-radius: 8px;
    margin-top: 20px;
}

.cancellation-policy h4 {
    color: #333;
    margin-bottom: 10px;
}

.cancellation-policy ul {
    list-style: none;
    padding: 0;
}

.cancellation-policy li {
    padding: 8px 0;
    font-size: 13px;
    color: #666;
    border-bottom: 1px solid #e0e0e0;
}

.cancellation-policy li:last-child {
    border-bottom: none;
}

@media (max-width: 768px) {
    .booking-details-grid {
        grid-template-columns: 1fr;
    }

    .confirm-booking-section h1 {
        font-size: 24px;
    }
}
</style>

<script>
// Handle payment method selection
document.querySelectorAll('input[name="payment_method"]').forEach(option => {
    option.addEventListener('change', function() {
        const stripeContainer = document.getElementById('stripeContainer');
        const bankTransferInfo = document.querySelector('.bank-transfer-info');
        
        if (this.value === 'stripe') {
            stripeContainer.style.display = 'block';
            bankTransferInfo.style.display = 'none';
        } else if (this.value === 'bank_transfer') {
            stripeContainer.style.display = 'none';
            bankTransferInfo.style.display = 'block';
        } else {
            stripeContainer.style.display = 'none';
            bankTransferInfo.style.display = 'none';
        }
    });
});

// Form submission
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const termsCheckbox = document.getElementById('termsCheckbox');
    if (!termsCheckbox.checked) {
        alert('Please agree to the terms and conditions');
        return;
    }

    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnLoader = document.getElementById('btnLoader');

    submitBtn.disabled = true;
    btnText.style.display = 'none';
    btnLoader.style.display = 'inline';

    // Get form data
    const formData = new FormData(this);

    // Submit booking payment
    fetch('<?php echo URLROOT; ?>/booking/process_payment', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Redirect to success page
            window.location.href = data.redirect;
        } else {
            alert('Payment failed: ' + data.message);
            submitBtn.disabled = false;
            btnText.style.display = 'inline';
            btnLoader.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing payment');
        submitBtn.disabled = false;
        btnText.style.display = 'inline';
        btnLoader.style.display = 'none';
    });
});
</script>

<?php require APPROOT.'/views/inc/components/footer.php'; ?>
