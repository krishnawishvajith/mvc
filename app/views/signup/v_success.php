<?php require APPROOT.'/views/inc/components/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Registration Successful | BookMyGround.com</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="style.css">
</head>
<body>


  <!-- Success Content Section -->
  <section class="login-content-section">
    <div class="login-content-container">
      <!-- Left Side - Success Message -->
      <div class="welcome-content">
        <h2 class="welcome-heading">YOU'RE <span class="green">IN</span>!<br>WHAT'S <span class="green">NEXT</span>?</h2>
        
        <div class="features-list">
          <div class="feature-item">
            <span class="feature-icon">✅</span>
            <p>Account successfully created and pending verification</p>
          </div>
          <div class="feature-item">
            <span class="feature-icon">📧</span>
            <p>Check your email for verification instructions</p>
          </div>
          <div class="feature-item">
            <span class="feature-icon">🚀</span>
            <p>Once verified, you can access all platform features</p>
          </div>
          <div class="feature-item">
            <span class="feature-icon">💬</span>
            <p>Our support team is here if you need any help</p>
          </div>
        </div>

        <div class="signin-quote">
          <p class="quote-text">"Every champion was once a beginner. Every pro was once an amateur. 
          Welcome to your sports journey with BookMyGround!"</p>
        </div>
      </div>

      <!-- Right Side - Next Steps -->
      <div class="login-form-container">
        <div class="success-content">
          <h2 class="login-heading">🎉 Registration Complete!</h2>
          
          <div class="success-steps">
            <div class="step-item">
              <div class="step-number">1</div>
              <div class="step-content">
                <h3>Verify Your Email</h3>
                <p>Check your inbox and click the verification link we sent you.</p>
              </div>
            </div>
            
            <div class="step-item">
              <div class="step-number">2</div>
              <div class="step-content">
                <h3>Complete Your Profile</h3>
                <p>Add more details to help others connect with you.</p>
              </div>
            </div>
            
            <div class="step-item">
              <div class="step-number">3</div>
              <div class="step-content">
                <h3>Start Exploring</h3>
                <p>Browse stadiums, find coaches, or list your services.</p>
              </div>
            </div>
          </div>

          <div class="quick-actions">
            <h3>Quick Actions</h3>
            <div class="action-buttons">
              <a href="<?php echo URLROOT; ?>/stadiums" class="action-btn">
                🏟️ Browse Stadiums
              </a>
              <a href="<?php echo URLROOT; ?>/coaches" class="action-btn">
                👨‍🏫 Find Coaches
              </a>
              <a href="<?php echo URLROOT; ?>/rental" class="action-btn">
                ⚽ Rent Equipment
              </a>
            </div>
          </div>

          <div class="login-info">
            <p>Need help getting started? Contact our support team at 
            <a href="mailto:support@bookmyground.lk">support@bookmyground.lk</a></p>
          </div>

          <div class="signup-prompt">
            <p class="new-user-text">Ready to dive in?</p>
            <div class="create-account-wrapper">
              <a href="<?php echo URLROOT; ?>/login" class="create-account-button">Sign In to Your Account</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  
</body>
</html>

<?php require APPROOT.'/views/inc/components/footer.php'; ?>