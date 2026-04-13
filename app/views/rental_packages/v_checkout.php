<?php require APPROOT.'/views/inc/components/header.php'; ?>

<style>
.checkout-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.checkout-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 30px;
    margin-top: 30px;
}

@media (max-width: 968px) {
    .checkout-grid {
        grid-template-columns: 1fr;
    }
}

.checkout-header {
    margin-bottom: 30px;
}

.checkout-header h1 {
    font-size: 32px;
    color: #fff;
    margin-bottom: 8px;
}

.checkout-header p {
    color: #aaa;
}

/* Left Column - Form */
.checkout-form-section {
    background: #1a1a1a;
    border: 1px solid #333;
    border-radius: 12px;
    padding: 30px;
}

.checkout-form-section h2 {
    color: #fff;
    font-size: 24px;
    margin-bottom: 20px;
}

.auth-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 25px;
    border-bottom: 2px solid #333;
}

.auth-tab {
    padding: 12px 24px;
    background: none;
    border: none;
    color: #aaa;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    border-bottom: 3px solid transparent;
    margin-bottom: -2px;
    transition: all 0.3s;
}

.auth-tab.active {
    color: #03B200;
    border-bottom-color: #03B200;
}

.auth-tab:hover {
    color: #fff;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    color: #ccc;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
}

.form-input {
    width: 100%;
    padding: 12px;
    background: #0a0a0a;
    border: 1px solid #333;
    border-radius: 8px;
    color: #fff;
    font-size: 14px;
}

.form-input:focus {
    outline: none;
    border-color: #03B200;
}

.form-input option {
    background: #0a0a0a;
    color: #fff;
}

.user-info-box {
    background: #0a0a0a;
    border: 1px solid #333;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}

.user-info-box h3 {
    color: #fff;
    font-size: 18px;
    margin-bottom: 15px;
}

.user-info-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #222;
}

.user-info-item:last-child {
    border-bottom: none;
}

.user-info-item label {
    color: #aaa;
    font-size: 14px;
}

.user-info-item span {
    color: #fff;
    font-weight: 600;
}

.info-message {
    background: rgba(3, 178, 0, 0.1);
    border: 1px solid #03B200;
    border-radius: 8px;
    padding: 15px;
    color: #03B200;
    font-size: 14px;
    margin-bottom: 20px;
}

/* Right Column - Summary */
.order-summary {
    background: #1a1a1a;
    border: 1px solid #333;
    border-radius: 12px;
    padding: 30px;
    height: fit-content;
    position: sticky;
    top: 90px;
}

.order-summary h2 {
    color: #fff;
    font-size: 24px;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #333;
}

.package-summary {
    margin-bottom: 20px;
}

.package-summary-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.package-icon-large {
    font-size: 40px;
    background: #0a0a0a;
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    border: 1px solid #333;
}

.package-summary-details h3 {
    color: #fff;
    font-size: 18px;
    margin-bottom: 5px;
}

.package-summary-details p {
    color: #aaa;
    font-size: 13px;
}

.summary-features {
    background: #0a0a0a;
    border-radius: 8px;
    padding: 15px;
    margin: 20px 0;
}

.summary-features h4 {
    color: #fff;
    font-size: 14px;
    margin-bottom: 12px;
}

.summary-features ul {
    list-style: none;
    padding: 0;
}

.summary-features li {
    color: #aaa;
    font-size: 13px;
    padding: 6px 0;
    display: flex;
    align-items: start;
    gap: 8px;
}

.summary-features li::before {
    content: '✓';
    color: #03B200;
    font-weight: bold;
}

.price-breakdown {
    margin: 20px 0;
    padding: 20px 0;
    border-top: 2px solid #333;
    border-bottom: 2px solid #333;
}

.price-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    color: #aaa;
}

.price-row.total {
    font-size: 20px;
    font-weight: 700;
    color: #fff;
    padding-top: 15px;
    margin-top: 10px;
    border-top: 2px solid #333;
}

.price-row.total span:last-child {
    color: #03B200;
}

.btn-complete-purchase {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #03B200, #029800);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    margin-top: 10px;
}

.btn-complete-purchase:hover {
    background: linear-gradient(135deg, #03c900, #02af00);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(3, 178, 0, 0.3);
}

.btn-complete-purchase:disabled {
    background: #333;
    cursor: not-allowed;
    transform: none;
}

.payment-note {
    background: #0a0a0a;
    border: 1px solid #333;
    border-radius: 8px;
    padding: 12px;
    color: #aaa;
    font-size: 12px;
    text-align: center;
    margin-top: 15px;
}

#card-element {
    padding: 14px;
    background: #0a0a0a;
    border: 1px solid #333;
    border-radius: 8px;
    margin-bottom: 15px;
}

#card-element.StripeElement--focus {
    border-color: #03B200;
}

#card-errors {
    color: #ff6666;
    font-size: 13px;
    margin-bottom: 10px;
    min-height: 20px;
}

.error-message {
    background: rgba(255, 0, 0, 0.1);
    border: 1px solid #ff4444;
    color: #ff6666;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}

.success-message {
    background: rgba(0, 255, 0, 0.1);
    border: 1px solid #28a745;
    color: #28a745;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}
</style>

<div class="checkout-container">
    <div class="checkout-header">
        <h1>Checkout</h1>
        <p>Complete your purchase to start listing your rental services</p>
    </div>

    <div class="checkout-grid">
        <!-- Left Column - Form -->
        <div class="checkout-form-section">
            <?php if(isset($data['error']) && !empty($data['error'])): ?>
            <div class="error-message"><?php echo htmlspecialchars($data['error']); ?></div>
            <?php endif; ?>

            <?php if(isset($data['success']) && !empty($data['success'])): ?>
            <div class="success-message"><?php echo htmlspecialchars($data['success']); ?></div>
            <?php endif; ?>

            <?php if($data['is_logged_in']): ?>
                <!-- User is logged in -->
                <h2>Account Details</h2>
                <div class="user-info-box">
                    <h3>Logged in as:</h3>
                    <div class="user-info-item">
                        <label>Name:</label>
                        <span><?php echo htmlspecialchars($data['user_name']); ?></span>
                    </div>
                    <div class="user-info-item">
                        <label>Email:</label>
                        <span><?php echo htmlspecialchars($data['user_email']); ?></span>
                    </div>
                    <div class="user-info-item">
                        <label>Current Role:</label>
                        <span><?php echo ucfirst(str_replace('_', ' ', $data['user_role'])); ?></span>
                    </div>
                </div>

                <div class="info-message">
                    📋 After purchase, "Rental Owner" role will be added to your account and you'll be able to access the Rental Owner Dashboard.
                </div>

            <?php else: ?>
                <!-- User not logged in - show login/register tabs -->
                <h2>Sign in or Create Account</h2>
                
                <div class="auth-tabs">
                    <button class="auth-tab active" onclick="switchTab('login')">Login</button>
                    <button class="auth-tab" onclick="switchTab('register')">Register</button>
                </div>

                <!-- Login Tab -->
                <div id="login-tab" class="tab-content active">
                    <form method="POST" action="<?php echo URLROOT; ?>/login">
                        <input type="hidden" name="redirect_to" value="<?php echo URLROOT; ?>/rental_packages/checkout/<?php echo $data['package']['slug']; ?>">
                        
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-input" placeholder="your@email.com" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-input" placeholder="••••••••" required>
                        </div>

                        <button type="submit" class="btn-complete-purchase">Login & Continue</button>
                        
                        <p style="text-align: center; margin-top: 15px; color: #aaa; font-size: 14px;">
                            <a href="<?php echo URLROOT; ?>/login/forgot" style="color: #03B200;">Forgot password?</a>
                        </p>
                    </form>
                </div>

                <!-- Register Tab -->
                <div id="register-tab" class="tab-content">
                    <div class="info-message">
                        ℹ️ You'll create a Rental Owner account. You can add other roles later if needed.
                    </div>

                    <form method="POST" action="<?php echo URLROOT; ?>/register/rental_owner">
                        <input type="hidden" name="redirect_to" value="<?php echo URLROOT; ?>/rental_packages/checkout/<?php echo $data['package']['slug']; ?>">
                        
                        <div class="form-group">
                            <label class="form-label">Owner Name</label>
                            <input type="text" name="owner-name" class="form-input" placeholder="John Doe" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Business Name</label>
                            <input type="text" name="business-name" class="form-input" placeholder="Your Business Name" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-input" placeholder="your@email.com" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="tel" name="phone" class="form-input" placeholder="+94 71 234 5678" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">District</label>
                            <select name="district" class="form-input" required>
                                <option value="">Select District</option>
                                <option value="Colombo">Colombo</option>
                                <option value="Gampaha">Gampaha</option>
                                <option value="Kalutara">Kalutara</option>
                                <option value="Kandy">Kandy</option>
                                <option value="Matale">Matale</option>
                                <option value="Nuwara Eliya">Nuwara Eliya</option>
                                <option value="Galle">Galle</option>
                                <option value="Matara">Matara</option>
                                <option value="Hambantota">Hambantota</option>
                                <option value="Jaffna">Jaffna</option>
                                <option value="Kilinochchi">Kilinochchi</option>
                                <option value="Mannar">Mannar</option>
                                <option value="Vavuniya">Vavuniya</option>
                                <option value="Mullaitivu">Mullaitivu</option>
                                <option value="Batticaloa">Batticaloa</option>
                                <option value="Ampara">Ampara</option>
                                <option value="Trincomalee">Trincomalee</option>
                                <option value="Kurunegala">Kurunegala</option>
                                <option value="Puttalam">Puttalam</option>
                                <option value="Anuradhapura">Anuradhapura</option>
                                <option value="Polonnaruwa">Polonnaruwa</option>
                                <option value="Badulla">Badulla</option>
                                <option value="Moneragala">Moneragala</option>
                                <option value="Ratnapura">Ratnapura</option>
                                <option value="Kegalle">Kegalle</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Business Type</label>
                            <select name="business-type" class="form-input" required>
                                <option value="">Select Business Type</option>
                                <option value="Sports Equipment Rental">Sports Equipment Rental</option>
                                <option value="Fitness Equipment">Fitness Equipment</option>
                                <option value="Outdoor Gear">Outdoor Gear</option>
                                <option value="Multi-Category">Multi-Category</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Equipment Categories</label>
                            <select name="equipment-categories" class="form-input" required>
                                <option value="">Select Category</option>
                                <option value="Cricket">Cricket</option>
                                <option value="Football">Football</option>
                                <option value="Tennis">Tennis</option>
                                <option value="Badminton">Badminton</option>
                                <option value="Basketball">Basketball</option>
                                <option value="Swimming">Swimming</option>
                                <option value="Gym Equipment">Gym Equipment</option>
                                <option value="Multi-Sport">Multi-Sport</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Delivery Service</label>
                            <select name="delivery-service" class="form-input" required>
                                <option value="">Select Option</option>
                                <option value="yes">Yes - We offer delivery</option>
                                <option value="no">No - Pick-up only</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-input" placeholder="Min 6 characters" required minlength="6">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm-password" class="form-input" placeholder="Re-enter password" required>
                        </div>

                        <button type="submit" class="btn-complete-purchase">Create Account & Continue</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Column - Order Summary -->
        <div class="order-summary">
            <h2>Order Summary</h2>

            <div class="package-summary">
                <div class="package-summary-header">
                    <div class="package-icon-large"><?php echo $data['package']['icon']; ?></div>
                    <div class="package-summary-details">
                        <h3><?php echo htmlspecialchars($data['package']['name']); ?></h3>
                        <p><?php echo htmlspecialchars($data['package']['duration']); ?></p>
                    </div>
                </div>

                <div class="summary-features">
                    <h4>Package Includes:</h4>
                    <ul>
                        <?php foreach(array_slice($data['package']['features'], 0, 4) as $feature): ?>
                        <li><?php echo htmlspecialchars($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="price-breakdown">
                <div class="price-row">
                    <span>Package Price:</span>
                    <span>LKR <?php echo number_format($data['package']['price']); ?></span>
                </div>
                <div class="price-row">
                    <span>Tax:</span>
                    <span>LKR 0</span>
                </div>
                <div class="price-row total">
                    <span>Total:</span>
                    <span>LKR <?php echo number_format($data['package']['price']); ?></span>
                </div>
            </div>

            <?php if($data['is_logged_in']): ?>
                <?php if(Stripe::isConfigured()): ?>
                <form method="POST" id="payment-form">
                    <div id="card-element" style="padding: 12px; background: #0a0a0a; border: 1px solid #333; border-radius: 8px; margin-bottom: 15px;"></div>
                    <div id="card-errors" style="color: #ff6666; font-size: 13px; margin-bottom: 10px;"></div>
                    <button type="submit" class="btn-complete-purchase" id="submit-button">
                        Complete Purchase
                    </button>
                </form>
                <?php else: ?>
                <div style="background: rgba(255, 165, 0, 0.1); border: 1px solid #ffa500; color: #ffa500; padding: 12px; border-radius: 8px; font-size: 13px; text-align: center;">
                    ⚠️ Payment system not configured. Add your Stripe keys to config.php
                </div>
                <?php endif; ?>
            <?php else: ?>
            <button type="button" class="btn-complete-purchase" disabled>
                Login or Register to Continue
            </button>
            <?php endif; ?>

            <div class="payment-note">
                <?php if($data['is_logged_in'] && Stripe::isConfigured()): ?>
                🔒 Secure payment powered by Stripe. Your card details are never stored on our servers.
                <?php else: ?>
                🔒 Secure checkout. Test mode - use card 4242 4242 4242 4242
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
function switchTab(tab) {
    const loginTab = document.getElementById('login-tab');
    const registerTab = document.getElementById('register-tab');
    const tabs = document.querySelectorAll('.auth-tab');
    
    tabs.forEach(t => t.classList.remove('active'));
    
    if (tab === 'login') {
        loginTab.classList.add('active');
        registerTab.classList.remove('active');
        tabs[0].classList.add('active');
    } else {
        loginTab.classList.remove('active');
        registerTab.classList.add('active');
        tabs[1].classList.add('active');
    }
}

<?php if($data['is_logged_in'] && Stripe::isConfigured()): ?>
// Stripe payment form
const stripe = Stripe('<?php echo (new Stripe())->getPublishableKey(); ?>');
const elements = stripe.elements();

const style = {
    base: {
        color: '#fff',
        fontFamily: '"Segoe UI", Tahoma, Geneva, Verdana, sans-serif',
        fontSize: '14px',
        '::placeholder': {
            color: '#666'
        }
    },
    invalid: {
        color: '#ff6666',
        iconColor: '#ff6666'
    }
};

const cardElement = elements.create('card', {
    style: style,
    hidePostalCode: true
});
cardElement.mount('#card-element');

cardElement.on('change', function(event) {
    const displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});

const form = document.getElementById('payment-form');
const submitButton = document.getElementById('submit-button');

form.addEventListener('submit', async function(event) {
    event.preventDefault();
    
    submitButton.disabled = true;
    submitButton.textContent = 'Processing...';

    try {
        const {token, error} = await stripe.createToken(cardElement);

        if (error) {
            const errorElement = document.getElementById('card-errors');
            errorElement.textContent = error.message;
            submitButton.disabled = false;
            submitButton.textContent = 'Complete Purchase';
        } else {
            // Send token to server
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);
            form.submit();
        }
    } catch (err) {
        console.error('Stripe error:', err);
        const errorElement = document.getElementById('card-errors');
        errorElement.textContent = 'Payment processing error. Please check console and ensure you are using test card 4242 4242 4242 4242';
        submitButton.disabled = false;
        submitButton.textContent = 'Complete Purchase';
    }
});
<?php endif; ?>
</script>

<?php require APPROOT.'/views/inc/components/footer.php'; ?>
