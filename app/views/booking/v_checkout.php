<?php require APPROOT.'/views/inc/components/header.php'; ?>

<style>
/* Override body background for checkout page */
body {
    background: #0f0f0f !important;
    color: #fff !important;
}
</style>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/booking.css">

<div class="booking-container">
    <div class="breadcrumb">
        <a href="<?php echo URLROOT; ?>/">Home</a>
        <span>/</span>
        <a href="<?php echo URLROOT; ?>/stadiums">Stadiums</a>
        <span>/</span>
        <span>Checkout</span>
    </div>

    <div class="checkout-section">
        <h1>Complete Your Booking</h1>
        
        <!-- Timer Warning -->
        <div class="timer-warning">
            <div class="timer-icon">⏱️</div>
            <div class="timer-content">
                <h3>Your booking is reserved for:</h3>
                <div class="timer-display" id="timerDisplay">5:00</div>
                <p class="timer-subtitle">Complete payment before time runs out to secure your booking</p>
            </div>
        </div>

        <div class="checkout-grid">
            <!-- Left Column: Order Summary -->
            <div class="order-summary">
                <h3>📋 Booking Summary</h3>
                
                <div class="booking-details">
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
                        <span class="value"><?php echo $data['booking']->duration_hours; ?> hour<?php echo $data['booking']->duration_hours > 1 ? 's' : ''; ?></span>
                    </div>
                </div>

                <hr>

                <div class="price-breakdown">
                    <div class="detail-row">
                        <span class="label">Price per Hour:</span>
                        <span class="value">LKR <?php echo number_format($data['booking']->stadium_price, 2); ?></span>
                    </div>

                    <div class="detail-row">
                        <span class="label">Duration:</span>
                        <span class="value"><?php echo $data['booking']->duration_hours; ?> hour<?php echo $data['booking']->duration_hours > 1 ? 's' : ''; ?></span>
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

                <div class="booking-info">
                    <p><strong>✓ Free cancellation</strong> up to 12 hours before booking</p>
                    <p><strong>✓ Instant confirmation</strong> - You'll receive confirmation immediately</p>
                </div>
            </div>

            <!-- Right Column: Payment Form -->
            <div class="payment-section">
                <h3>💳 Payment via Stripe</h3>

                <form id="paymentForm" class="payment-form">
                    <input type="hidden" name="booking_id" value="<?php echo $data['booking']->id; ?>">
                    <input type="hidden" name="payment_method" value="stripe">

                    <!-- Credit Card Details (Stripe Payment) -->
                    <div id="creditCardDetails" class="card-details-form">
                        <div class="form-group">
                            <label>Cardholder Name *</label>
                            <input type="text" id="cardholder_name" name="cardholder_name" placeholder="Full Name" required>
                        </div>

                        <div class="form-group">
                            <label>Card Details *</label>
                            <div id="card-element" class="stripe-element"></div>
                            <div id="card-errors" role="alert" style="color: #ff6b6b; margin-top: 8px; font-size: 13px;"></div>
                        </div>
                    </div>

                    <div class="form-group checkbox">
                        <label>
                            <input type="checkbox" id="agree_terms" name="agree_terms" required>
                            I agree to the <a href="#" target="_blank">Terms & Conditions</a> and <a href="#" target="_blank">Cancellation Policy</a>
                        </label>
                    </div>

                    <button type="submit" class="btn-pay-now" id="payBtn">
                        Pay LKR <?php echo number_format($data['booking']->total_price * 1.02, 2); ?>
                    </button>

                    <button type="button" class="btn-cancel-booking" id="cancelBtn">
                        Cancel Booking
                    </button>
                </form>

                <div class="payment-note">
                    <p>🔒 <strong>Secure Payment:</strong> Your payment information is encrypted and secure. We never store your card details.</p>
                    <p style="margin-top: 10px; font-size: 12px;">💳 <strong>Testing:</strong> Use card <strong>4242 4242 4242 4242</strong> with any future expiry date and any 3-digit CVC in test mode.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Main Container */
.booking-container {
    background: #0f0f0f;
    min-height: 100vh;
    padding: 40px 20px;
    color: #fff;
}

.checkout-section {
    max-width: 1400px;
    margin: 0 auto;
}

.checkout-section h1 {
    color: #fff;
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 30px;
    text-shadow: 0 2px 10px rgba(46, 125, 50, 0.2);
}

.breadcrumb {
    color: #aaa;
    margin-bottom: 30px;
    font-size: 14px;
}

.breadcrumb a {
    color: #2e7d32;
    text-decoration: none;
    transition: color 0.3s;
}

.breadcrumb a:hover {
    color: #4caf50;
}

/* Timer Warning */
.timer-warning {
    display: flex;
    align-items: center;
    gap: 30px;
    background: linear-gradient(135deg, rgba(46, 125, 50, 0.15) 0%, rgba(76, 175, 80, 0.1) 100%);
    border: 2px solid #2e7d32;
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 40px;
    animation: pulse-border 2s infinite;
    box-shadow: 0 8px 32px rgba(46, 125, 50, 0.15);
}

@keyframes pulse-border {
    0%, 100% {
        border-color: #2e7d32;
        box-shadow: 0 8px 32px rgba(46, 125, 50, 0.15);
    }
    50% {
        box-shadow: 0 8px 40px rgba(46, 125, 50, 0.25);
    }
}

.timer-icon {
    font-size: 48px;
    animation: spin 3s linear infinite;
    flex-shrink: 0;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.timer-content h3 {
    color: #fff;
    margin-bottom: 12px;
    font-size: 16px;
    font-weight: 600;
}

.timer-display {
    font-size: 56px;
    font-weight: 900;
    background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-family: 'Courier New', monospace;
    letter-spacing: 2px;
}

.timer-display.warning {
    animation: blink 1s infinite;
}

@keyframes blink {
    0%, 49% { opacity: 1; }
    50%, 100% { opacity: 0.6; }
}

.timer-subtitle {
    color: #aaa;
    font-size: 14px;
    margin-top: 8px;
}

/* Checkout Grid */
.checkout-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-top: 40px;
}

.order-summary, .payment-section {
    background: linear-gradient(135deg, #1a1a1a 0%, #242424 100%);
    border: 1px solid #2e7d32;
    border-radius: 16px;
    padding: 35px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
}

.order-summary::before, .payment-section::before {
    content: '';
    position: absolute;
    top: -1px;
    left: -1px;
    right: -1px;
    bottom: -1px;
    background: linear-gradient(135deg, rgba(46, 125, 50, 0.3) 0%, rgba(76, 175, 80, 0.1) 100%);
    border-radius: 16px;
    z-index: -1;
    opacity: 0;
}

.order-summary, .payment-section {
    position: relative;
}

.order-summary h3, .payment-section h3 {
    color: #4caf50;
    margin-bottom: 25px;
    font-size: 20px;
    font-weight: 700;
    display: flex;
    align-items: center;
}

.booking-details {
    margin-bottom: 25px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 15px 0;
    border-bottom: 1px solid rgba(46, 125, 50, 0.2);
    font-size: 15px;
}

.detail-row:last-child {
    border-bottom: none;
}

.label {
    color: #aaa;
    font-weight: 500;
}

.value {
    color: #fff;
    text-align: right;
    font-weight: 600;
}

hr {
    border: none;
    border-top: 1px solid rgba(46, 125, 50, 0.3);
    margin: 25px 0;
}

.price-breakdown {
    padding: 25px;
    background: rgba(46, 125, 50, 0.08);
    border-left: 4px solid #2e7d32;
    border-radius: 8px;
    margin-bottom: 25px;
}

.detail-row.subtotal, .detail-row.fee {
    border-bottom: 1px solid rgba(46, 125, 50, 0.2);
}

.detail-row.total {
    border-bottom: none;
    border-top: 2px solid #2e7d32;
    font-weight: bold;
    font-size: 18px;
    padding: 20px 0;
    color: #4caf50;
}

.booking-info {
    background: rgba(46, 125, 50, 0.12);
    padding: 20px;
    border-radius: 12px;
    margin-top: 25px;
    border-left: 4px solid #4caf50;
}

.booking-info p {
    margin: 8px 0;
    color: #4caf50;
    font-size: 14px;
    line-height: 1.6;
}

/* Payment Methods */
.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-bottom: 25px;
}

.payment-option {
    display: flex;
    align-items: flex-start;
    padding: 18px;
    border: 2px solid rgba(46, 125, 50, 0.3);
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: rgba(46, 125, 50, 0.05);
}

.payment-option:hover {
    border-color: #2e7d32;
    background: rgba(46, 125, 50, 0.1);
}

.payment-option input {
    margin-top: 2px;
    margin-right: 15px;
    cursor: pointer;
    accent-color: #2e7d32;
}

.payment-option input:checked ~ .option-label {
    color: #4caf50;
}

.payment-option:has(input:checked) {
    border-color: #2e7d32;
    background: rgba(46, 125, 50, 0.15);
    box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
}

.option-label {
    flex: 1;
}

.option-label strong {
    display: block;
    margin-bottom: 4px;
    color: #fff;
    font-size: 15px;
}

.option-label small {
    display: block;
    color: #999;
    font-size: 13px;
    margin-top: 2px;
}

/* Form Styles */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: #ccc;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-group input[type="text"],
.form-group input[type="file"],
.form-group select {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid rgba(46, 125, 50, 0.3);
    border-radius: 10px;
    font-size: 14px;
    font-family: inherit;
    background: rgba(255, 255, 255, 0.05);
    color: #fff;
    transition: all 0.3s ease;
}

.form-group input[type="text"]::placeholder,
.form-group input[type="file"]::placeholder {
    color: #666;
}

.form-group input[type="text"]:focus,
.form-group input[type="file"]:focus,
.form-group select:focus {
    outline: none;
    border-color: #2e7d32;
    background: rgba(46, 125, 50, 0.08);
    box-shadow: 0 0 0 4px rgba(46, 125, 50, 0.15);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

/* Checkbox */
.form-group.checkbox {
    display: flex;
    align-items: flex-start;
    margin-bottom: 25px;
    gap: 10px;
}

.form-group.checkbox label {
    display: flex;
    align-items: flex-start;
    margin-bottom: 0;
    font-size: 13px;
    text-transform: none;
    letter-spacing: normal;
}

.form-group.checkbox input[type="checkbox"] {
    width: 20px;
    height: 20px;
    margin-right: 0;
    margin-top: 2px;
    cursor: pointer;
    accent-color: #2e7d32;
    flex-shrink: 0;
}

.form-group.checkbox a {
    color: #4caf50;
    text-decoration: none;
    transition: color 0.3s;
}

.form-group.checkbox a:hover {
    color: #66bb6a;
    text-decoration: underline;
}

/* Buttons */
.btn-pay-now {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 6px 20px rgba(46, 125, 50, 0.3);
}

.btn-pay-now:hover:not(:disabled) {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(46, 125, 50, 0.5);
    background: linear-gradient(135deg, #388e3c 0%, #2e7d32 100%);
}

.btn-pay-now:active:not(:disabled) {
    transform: translateY(-1px);
}

.btn-pay-now:disabled {
    background: #555;
    cursor: not-allowed;
    opacity: 0.6;
}

.btn-cancel-booking {
    width: 100%;
    padding: 13px;
    background: rgba(244, 67, 54, 0.15);
    color: #ff6b6b;
    border: 1px solid rgba(244, 67, 54, 0.4);
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-cancel-booking:hover {
    background: rgba(244, 67, 54, 0.25);
    border-color: rgba(244, 67, 54, 0.6);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(244, 67, 54, 0.2);
}

/* Payment Note */
.payment-note {
    background: rgba(46, 125, 50, 0.1);
    border: 1px solid rgba(46, 125, 50, 0.3);
    padding: 16px;
    border-radius: 12px;
    font-size: 13px;
    color: #aaa;
    margin-top: 20px;
}

.payment-note p {
    margin: 0;
}

/* Card Details Form */
.card-details-form, .bank-details-form {
    background: rgba(46, 125, 50, 0.08);
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    border: 1px solid rgba(46, 125, 50, 0.2);
}

/* Stripe Elements Styling */
.stripe-element {
    padding: 14px 16px;
    border: 1px solid rgba(46, 125, 50, 0.3);
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.05);
    font-size: 14px;
    font-family: inherit;
    color: #fff;
    transition: all 0.3s ease;
}

.stripe-element:focus {
    border-color: #2e7d32;
    background: rgba(46, 125, 50, 0.08);
    box-shadow: 0 0 0 4px rgba(46, 125, 50, 0.15);
}

/* Bank Info */
.bank-info {
    background: rgba(46, 125, 50, 0.08);
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    border-left: 4px solid #2e7d32;
}

.bank-info h4 {
    color: #4caf50;
    margin-bottom: 15px;
    font-size: 15px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.bank-detail-row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid rgba(46, 125, 50, 0.2);
    font-size: 14px;
}

.bank-detail-row .label {
    font-weight: 600;
    color: #aaa;
}

.bank-detail-row .value {
    font-family: 'Courier New', monospace;
    color: #4caf50;
    font-weight: 700;
}

.bank-note {
    background: rgba(255, 193, 7, 0.15);
    border: 1px solid rgba(255, 193, 7, 0.4);
    padding: 14px;
    border-radius: 10px;
    font-size: 13px;
    color: #fbc02d;
    margin-top: 15px;
    border-left: 4px solid #ff9800;
}

/* Responsive */
@media (max-width: 1024px) {
    .checkout-grid {
        gap: 30px;
    }

    .order-summary, .payment-section {
        padding: 25px;
    }
}

@media (max-width: 768px) {
    .booking-container {
        padding: 20px 15px;
    }

    .checkout-section h1 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .checkout-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .timer-warning {
        flex-direction: column;
        text-align: center;
        padding: 25px;
        gap: 20px;
    }

    .timer-icon {
        font-size: 40px;
    }

    .timer-display {
        font-size: 42px;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .order-summary, .payment-section {
        padding: 20px;
    }
}
</style>

<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>

<script>
// Timer functionality
const TIMER_DURATION = <?php echo $data['timer_duration']; ?>; // 5 minutes
const BOOKING_ID = <?php echo $data['booking']->id; ?>;
let timeRemaining = TIMER_DURATION;

function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${minutes}:${secs.toString().padStart(2, '0')}`;
}

function updateTimer() {
    const timerDisplay = document.getElementById('timerDisplay');
    timerDisplay.textContent = formatTime(timeRemaining);

    // Add warning animation when less than 1 minute
    if (timeRemaining < 60 && timeRemaining > 0) {
        timerDisplay.classList.add('warning');
    } else {
        timerDisplay.classList.remove('warning');
    }

    // When time expires
    if (timeRemaining <= 0) {
        clearInterval(timerInterval);
        handleTimerExpired();
    }

    timeRemaining--;
}

function handleTimerExpired() {
    // Disable payment button
    const payBtn = document.getElementById('payBtn');
    payBtn.disabled = true;
    payBtn.textContent = 'Time Expired';

    // Show alert
    alert('⏰ Your booking reservation has expired. The slot is now available for other users. Please start a new booking.');

    // Release the reservation
    releaseReservation();

    // Redirect after 3 seconds
    setTimeout(() => {
        window.location.href = '<?php echo URLROOT; ?>/stadiums';
    }, 3000);
}

function releaseReservation() {
    const formData = new FormData();
    formData.append('booking_id', BOOKING_ID);

    fetch('<?php echo URLROOT; ?>/booking/release_reservation', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Reservation released:', data);
    })
    .catch(error => {
        console.error('Error releasing reservation:', error);
    });
}

// Start timer on page load
const timerInterval = setInterval(updateTimer, 1000);
updateTimer();

// Initialize Stripe
const STRIPE_PUBLISHABLE_KEY = '<?php echo STRIPE_PUBLISHABLE_KEY; ?>';
const stripe = Stripe(STRIPE_PUBLISHABLE_KEY);
const elements = stripe.elements();
const cardElement = elements.create('card', {
    style: {
        base: {
            fontSize: '14px',
            color: '#fff',
            fontFamily: 'inherit',
            '::placeholder': {
                color: '#666'
            }
        },
        invalid: {
            color: '#ff6b6b'
        }
    }
});

cardElement.mount('#card-element');

// Handle card errors
cardElement.addEventListener('change', function(event) {
    const displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
        displayError.style.display = 'block';
    } else {
        displayError.textContent = '';
        displayError.style.display = 'none';
    }
});

// Payment form handling
document.addEventListener('DOMContentLoaded', function() {
    const paymentForm = document.getElementById('paymentForm');

    // Payment form submission
    paymentForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Validate terms agreement
        if (!document.getElementById('agree_terms').checked) {
            alert('❌ Please agree to the Terms & Conditions');
            return;
        }

        const payBtn = document.getElementById('payBtn');

        // Disable button to prevent double submission
        payBtn.disabled = true;
        payBtn.textContent = 'Processing Payment...';

        // Stop the timer
        clearInterval(timerInterval);

        // Process Stripe payment
        await processStripePayment();
    });

    // Cancel booking function
    document.getElementById('cancelBtn').addEventListener('click', function() {
        if (confirm('Are you sure you want to cancel this booking? Your reservation will be released and the slot will become available for other users.')) {
            clearInterval(timerInterval);
            releaseReservation();
            alert('Booking cancelled. Redirecting...');
            setTimeout(() => {
                window.location.href = '<?php echo URLROOT; ?>/stadiums';
            }, 1000);
        }
    });
});

async function processStripePayment() {
    const cardholderName = document.getElementById('cardholder_name').value;
    const payBtn = document.getElementById('payBtn');

    if (!cardholderName) {
        alert('❌ Please enter cardholder name');
        payBtn.disabled = false;
        payBtn.textContent = 'Pay LKR <?php echo number_format($data['booking']->total_price * 1.02, 2); ?>';
        return;
    }

    try {
        // Create payment method
        const {error, paymentMethod} = await stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
            billing_details: {
                name: cardholderName
            }
        });

        if (error) {
            document.getElementById('card-errors').textContent = error.message;
            document.getElementById('card-errors').style.display = 'block';
            payBtn.disabled = false;
            payBtn.textContent = 'Pay LKR <?php echo number_format($data['booking']->total_price * 1.02, 2); ?>';
            return;
        }

        // Send payment method to backend
        const formData = new FormData();
        formData.append('booking_id', BOOKING_ID);
        formData.append('payment_method', 'stripe');
        formData.append('stripe_payment_method_id', paymentMethod.id);
        formData.append('cardholder_name', cardholderName);

        const response = await fetch('<?php echo URLROOT; ?>/booking/process_payment', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            alert('✅ Payment successful! Your booking is confirmed.');
            window.location.href = data.redirect || '<?php echo URLROOT; ?>/booking/confirmation/' + BOOKING_ID;
        } else {
            alert('❌ Payment failed: ' + (data.message || 'Unknown error'));
            payBtn.disabled = false;
            payBtn.textContent = 'Pay LKR <?php echo number_format($data['booking']->total_price * 1.02, 2); ?>';
            
            // Restart timer
            timeRemaining = TIMER_DURATION;
            timerInterval = setInterval(updateTimer, 1000);
            updateTimer();
        }
    } catch (error) {
        console.error('Stripe payment error:', error);
        alert('❌ Payment error: ' + error.message);
        payBtn.disabled = false;
        payBtn.textContent = 'Pay LKR <?php echo number_format($data['booking']->total_price * 1.02, 2); ?>';
        
        // Restart timer
        timeRemaining = TIMER_DURATION;
        timerInterval = setInterval(updateTimer, 1000);
        updateTimer();
    }
}

// Clean up timer when leaving the page
window.addEventListener('beforeunload', function() {
    // Optionally release reservation when user leaves
    // This could be customized based on requirements
});
</script>

<?php require APPROOT.'/views/inc/components/footer.php'; ?>
