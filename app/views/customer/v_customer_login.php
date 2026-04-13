<?php require APPROOT.'/views/inc/components/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Login | BookMyGround.com</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/styledinesh.css">
</head>
<body>

  <!-- Hero Section -->
  <section class="login-hero">
    <div class="hero-text">
      <p class="sign-in-dis">
        <span class="green">CUSTOMER LOGIN</span><br>
        <span class="description">
          Access your dashboard to manage bookings, view stadiums, and continue your sports journey
          — your game <span class="green">awaits!</span>
        </span>
      </p>
      <div class="hero-buttons">
        <a href="<?php echo URLROOT; ?>/customer/login" class="hero-btn primary">Sign In</a>
        <a href="<?php echo URLROOT; ?>/register/customer" class="hero-btn">Sign Up</a>
      </div>
    </div>
    <div class="hero-image">
      <img src="<?php echo URLROOT; ?>/images/login/basketball_player.png" id="basketball" alt="Basketball Player">
    </div>
  </section>

  <!-- Login Content Section -->
  <section class="login-content-section">
    <div class="login-content-container">
      <!-- Left Side - Welcome Text -->
      <div class="welcome-content">
        <h2 class="welcome-heading">YOUR <span class="green">GAME</span>,<br>OUR <span class="green">PRIORITY</span></h2>
        
        <div class="features-list">
          <div class="feature-item">
            <span class="feature-icon">🎯</span>
            <p>Access your personalized dashboard</p>
          </div>
          <div class="feature-item">
            <span class="feature-icon">📅</span>
            <p>Track your upcoming bookings</p>
          </div>
          <div class="feature-item">
            <span class="feature-icon">⚡</span>
            <p>Quick access to favorite venues</p>
          </div>
          <div class="feature-item">
            <span class="feature-icon">💬</span>
            <p>Connect with coaches & players</p>
          </div>
        </div>

        <div class="signin-quote">
          <p class="quote-text">"Success in sports requires practice, dedication, and the right facilities. 
          We're here to help you focus on what matters most – your game."</p>
        </div>
      </div>

      <!-- Right Side - Login Form -->
      <div class="login-form-container">
        <?php if(isset($data['error']) && !empty($data['error'])): ?>
          <div class="error-message" style="background: rgba(255, 0, 0, 0.1); border: 1px solid #ff4444; color: #ff6666; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
            <?php echo $data['error']; ?>
          </div>
        <?php endif; ?>

        <form class="login-form" method="POST" action="<?php echo URLROOT; ?>/customer/login">
          <h2 class="login-heading">Welcome Back!</h2>
          
          <!-- Demo Credentials Notice -->
          <div style="background: rgba(3, 178, 0, 0.1); border: 1px solid #03B200; color: #03B200; padding: 15px; border-radius: 8px; margin-bottom: 25px; text-align: center;">
            <strong>Demo Login Credentials:</strong><br>
            Email: customer@test.com<br>
            Password: customer123
          </div>
          
          <div class="form-group">
            <label for="email" class="login-label">Email</label>
            <input type="email" 
                   id="email" 
                   name="email" 
                   class="login-input" 
                   placeholder="Enter your email" 
                   value="customer@test.com"
                   required>
          </div>

          <div class="form-group">
            <label for="password" class="login-label">Password</label>
            <input type="password" 
                   id="password" 
                   name="password" 
                   class="login-input" 
                   placeholder="Enter your password" 
                   value="customer123"
                   required>
          </div>

          <div class="remember-forgot">
            <label class="remember-me">
              <input type="checkbox" name="remember">
              Remember me
            </label>
            <a href="#" class="forgot-link">Forgot password?</a>
          </div>

          <button type="submit" class="login-button">Sign In to Dashboard</button>

          <p class="login-info">
            By continuing, you agree to the <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.
          </p>

          <div class="signup-prompt">
            <p class="new-user-text">New to our community?</p>
            <div class="create-account-wrapper">
              <a href="<?php echo URLROOT; ?>/register/customer" class="create-account-button">Create Customer Account</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>

  <style>
    .error-message {
      animation: shake 0.5s ease-in-out;
    }
    
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      75% { transform: translateX(5px); }
    }
    
    .login-form-container .login-button {
      background: linear-gradient(135deg, #03B200, #029800);
      color: white;
      border: none;
      padding: 15px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      width: 100%;
      margin-top: 10px;
      transition: all 0.3s ease;
    }
    
    .login-form-container .login-button:hover {
      background: linear-gradient(135deg, #03c900, #02af00);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(3, 178, 0, 0.2);
    }
    
    .login-form-container .login-button:active {
      transform: translateY(0);
    }
  </style>

</body>
</html>

<?php require APPROOT.'/views/inc/components/footer.php'; ?>