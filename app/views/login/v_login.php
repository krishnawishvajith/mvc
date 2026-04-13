<?php require APPROOT.'/views/inc/components/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | BookMyGround.com</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/styledinesh.css">
</head>
<body>


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
            <p>Quick access to recent venues</p>
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

        <?php if(isset($data['success']) && !empty($data['success'])): ?>
          <div class="success-message" style="background: rgba(0, 255, 0, 0.1); border: 1px solid #28a745; color: #28a745; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
            <?php echo $data['success']; ?>
          </div>
        <?php endif; ?>

        <form class="login-form" method="POST" action="<?php echo URLROOT; ?>/login">
          <h2 class="login-heading">Welcome Back!</h2>
          
          
          
          <div class="form-group">
            <label for="email" class="login-label">Email</label>
            <input type="email" 
                   id="email" 
                   name="email" 
                   class="login-input" 
                   placeholder="Enter your email" 
                   value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>"
                   required>
          </div>

          <div class="form-group">
            <label for="password" class="login-label">Password</label>
            <input type="password" 
                   id="password" 
                   name="password" 
                   class="login-input" 
                   placeholder="Enter your password" 
                   required>
          </div>

          <div class="remember-forgot">
            <label class="remember-me">
              <input type="checkbox" name="remember">
              Remember me
            </label>
            <a href="<?php echo URLROOT; ?>/login/forgot" class="forgot-link">Forgot password?</a>
          </div>

          <button type="submit" class="login-button">Sign In</button>

          <p class="login-info">
            By continuing, you agree to the <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.
          </p>

          <div class="signup-prompt">
            <p class="new-user-text">New to our community?</p>
            <div class="create-account-wrapper">
              <a href="<?php echo URLROOT; ?>/register" class="create-account-button">Create an account</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>

</body>
</html>

<?php require APPROOT.'/views/inc/components/footer.php'; ?>