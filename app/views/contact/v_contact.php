<?php include_once '../app/views/inc/Components/header.php'; ?>

<style>
    /* Contact Page Specific Styles */
    .contact-page {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 2rem 0;
    }

    .contact-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Page Header */
    .contact-header {
        background: linear-gradient(135deg, #1a1a1a 0%, #0d4d0d 100%);
        color: white;
        padding: 4rem 2rem;
        border-radius: 15px;
        margin-bottom: 3rem;
        text-align: center;
        border: 2px solid #00ff00;
        position: relative;
        overflow: hidden;
    }

    .contact-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="%2300ff0020" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.3;
    }

    .contact-header .content {
        position: relative;
        z-index: 1;
    }

    .contact-header h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #00ff00;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }

    .contact-header p {
        font-size: 1.2rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Contact Content Wrapper */
    .contact-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-bottom: 3rem;
    }

    /* Contact Info Section */
    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .info-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
        transition: all 0.3s ease;
        border-left: 4px solid #00ff00;
        position: relative;
        overflow: hidden;
    }

    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, transparent 0%, rgba(0,255,0,0.05) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,255,0,0.15);
    }

    .info-card:hover::before {
        opacity: 1;
    }

    .info-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #00ff00 0%, #00cc00 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        flex-shrink: 0;
        position: relative;
        z-index: 2;
        box-shadow: 0 5px 15px rgba(0,255,0,0.3);
    }

    .info-content {
        position: relative;
        z-index: 2;
    }

    .info-content h3 {
        font-size: 1.3rem;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .info-content p {
        color: #666;
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 0.5rem;
    }

    .info-content a {
        color: #00cc00;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .info-content a:hover {
        color: #00ff00;
        text-decoration: underline;
    }

    /* Contact Form Section */
    .contact-form-section {
        background: white;
        border-radius: 15px;
        padding: 2.5rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border: 2px solid #e9ecef;
        position: relative;
        overflow: hidden;
    }

    .contact-form-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(0,255,0,0.05) 0%, transparent 70%);
    }

    .form-title {
        font-size: 1.8rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 1.5rem;
        text-align: center;
        position: relative;
        z-index: 2;
    }

    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 2;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-group label .required {
        color: #00ff00;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 0.9rem;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 1rem;
        font-family: inherit;
        transition: all 0.3s ease;
        background: white;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: #00ff00;
        box-shadow: 0 0 0 3px rgba(0,255,0,0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 120px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .submit-btn {
        background: linear-gradient(135deg, #00ff00 0%, #00cc00 100%);
        color: #000;
        border: none;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        width: 100%;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 255, 0, 0.4);
    }

    .submit-btn:active {
        transform: translateY(0);
    }

    .submit-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    /* Success/Error Messages */
    .message {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        display: none;
        font-weight: 500;
    }

    .message.success {
        background: #d4edda;
        color: #155724;
        border-left: 4px solid #00ff00;
    }

    .message.error {
        background: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    .message.show {
        display: block;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Office Hours Section */
    .office-hours {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .hours-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .hours-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #00ff00;
        transition: all 0.3s ease;
    }

    .hours-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .day {
        font-weight: 600;
        color: #1a1a1a;
    }

    .time {
        color: #666;
        font-weight: 500;
    }

    /* Responsive Design */
    @media (max-width: 968px) {
        .contact-content {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .contact-header h1 {
            font-size: 2rem;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .hours-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .contact-container {
            padding: 0 15px;
        }
        
        .contact-header {
            padding: 2rem 1rem;
        }
        
        .contact-form-section,
        .info-card,
        .office-hours {
            padding: 1.5rem;
        }
    }
</style>

<div class="contact-page">
    <div class="contact-container">
        <!-- Page Header -->
        <div class="contact-header">
            <div class="content">
                <h1><?php echo $data['page_title']; ?></h1>
                <p><?php echo $data['page_subtitle']; ?></p>
            </div>
        </div>

        <!-- Contact Information & Form -->
        <div class="contact-content">
            <!-- Contact Information Cards -->
            <div class="contact-info">
                <div class="info-card">
                    <div class="info-icon">📧</div>
                    <div class="info-content">
                        <h3>Email Us</h3>
                        <p>For general inquiries and booking support</p>
                        <a href="mailto:<?php echo $data['contact_info']['email']; ?>"><?php echo $data['contact_info']['email']; ?></a>
                        <br>
                        <a href="mailto:<?php echo $data['contact_info']['support_email']; ?>"><?php echo $data['contact_info']['support_email']; ?></a>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-icon">📞</div>
                    <div class="info-content">
                        <h3>Call Us</h3>
                        <p><?php echo $data['contact_info']['working_hours']; ?></p>
                        <a href="tel:<?php echo str_replace(['(', ')', ' '], '', $data['contact_info']['main_phone']); ?>"><?php echo $data['contact_info']['main_phone']; ?></a>
                        <br>
                        <a href="tel:<?php echo str_replace(['(', ')', ' '], '', $data['contact_info']['support_phone']); ?>"><?php echo $data['contact_info']['support_phone']; ?></a> (Support)
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-icon">📍</div>
                    <div class="info-content">
                        <h3>Visit Our Office</h3>
                        <p>BookMyGround Headquarters<br>
                        <?php echo $data['contact_info']['address']; ?><br>
                        Sri Lanka</p>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-icon">🚨</div>
                    <div class="info-content">
                        <h3>Emergency Support</h3>
                        <p>24/7 emergency booking assistance</p>
                        <a href="tel:<?php echo str_replace(['(', ')', ' '], '', $data['contact_info']['emergency_contact']); ?>"><?php echo $data['contact_info']['emergency_contact']; ?></a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form-section">
                <h2 class="form-title">Send Us a Message</h2>
                
                <div id="successMessage" class="message success">
                    ✓ Thank you! Your message has been sent successfully. We'll get back to you soon.
                </div>

                <div id="errorMessage" class="message error">
                    ❌ <span id="errorText">Please correct the errors and try again.</span>
                </div>

                <form id="contactForm" action="<?php echo URLROOT; ?>/contact/submit" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name <span class="required">*</span></label>
                            <input type="text" id="firstName" name="firstName" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name <span class="required">*</span></label>
                            <input type="text" id="lastName" name="lastName" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address <span class="required">*</span></label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" placeholder="+94 71 234 5678">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject <span class="required">*</span></label>
                        <select id="subject" name="subject" required>
                            <option value="">Select a subject</option>
                            <option value="stadium_booking">Stadium Booking Inquiry</option>
                            <option value="equipment_rental">Equipment Rental</option>
                            <option value="coaching_services">Coaching Services</option>
                            <option value="pricing">Pricing Information</option>
                            <option value="technical_support">Technical Support</option>
                            <option value="partnership">Partnership Opportunity</option>
                            <option value="feedback">Feedback & Suggestions</option>
                            <option value="complaint">Complaint</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">Message <span class="required">*</span></label>
                        <textarea id="message" name="message" required placeholder="Please provide as much detail as possible about your inquiry..."></textarea>
                    </div>

                    <button type="submit" class="submit-btn">
                        <span class="btn-text">Send Message</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Office Hours -->
        <div class="office-hours">
            <h2 class="section-title">Office Hours & Availability</h2>
            <div class="hours-grid">
                <?php foreach($data['office_hours'] as $hours): ?>
                <div class="hours-item">
                    <span class="day"><?php echo $hours['day']; ?></span>
                    <span class="time"><?php echo $hours['time']; ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Contact Form Handler
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('.submit-btn');
    const btnText = submitBtn.querySelector('.btn-text');
    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');
    
    // Reset messages
    successMessage.classList.remove('show');
    errorMessage.classList.remove('show');
    
    // Show loading state
    submitBtn.disabled = true;
    btnText.textContent = 'Sending...';
    
    // Get form data
    const formData = new FormData(this);
    
    // Submit form
    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            successMessage.classList.add('show');
            this.reset();
            
            // Hide success message after 5 seconds
            setTimeout(() => {
                successMessage.classList.remove('show');
            }, 5000);
        } else {
            errorMessage.querySelector('#errorText').textContent = data.errors ? data.errors.join(', ') : 'Please try again.';
            errorMessage.classList.add('show');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorMessage.querySelector('#errorText').textContent = 'Network error. Please try again.';
        errorMessage.classList.add('show');
    })
    .finally(() => {
        // Reset button state
        submitBtn.disabled = false;
        btnText.textContent = 'Send Message';
    });
});

// Email validation
document.getElementById('email').addEventListener('blur', function() {
    const email = this.value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (email && !emailRegex.test(email)) {
        this.style.borderColor = '#dc3545';
    } else {
        this.style.borderColor = '#e9ecef';
    }
});

// Phone number formatting
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    
    if (value.length > 0) {
        // Format as Sri Lankan number
        if (value.startsWith('94')) {
            value = '+' + value;
        } else if (value.startsWith('0')) {
            value = '+94 ' + value.substring(1);
        }
    }
    
    // You can add more sophisticated formatting here
});

// Form field animations
document.querySelectorAll('input, textarea, select').forEach(field => {
    field.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.02)';
    });
    
    field.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
    });
});
</script>

<?php include_once '../app/views/inc/Components/footer.php'; ?>