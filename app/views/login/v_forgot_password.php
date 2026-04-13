<?php require APPROOT.'/views/inc/components/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password | BookMyGround.com</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">
</head>
<body>

  <section class="login-content-section">
    <div class="login-content-container">
      <div class="welcome-content">
        <h2 class="welcome-heading">RESET YOUR <span class="green">PASSWORD</span></h2>
        <p style="color: #ccc; margin-top: 16px;">Enter the email address linked to your account. We'll send you a link to reset your password.</p>
        <div class="signin-quote" style="margin-top: 32px;">
          <p class="quote-text">If you don't receive an email within a few minutes, check your spam folder or try again.</p>
        </div>
      </div>

      <div class="login-form-container">
        <?php if(isset($data['error']) && !empty($data['error'])): ?>
          <div class="error-message" style="background: rgba(255, 0, 0, 0.1); border: 1px solid #ff4444; color: #ff6666; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
            <?php echo htmlspecialchars($data['error']); ?>
          </div>
        <?php endif; ?>

        <?php if(isset($data['success']) && !empty($data['success'])): ?>
          <div class="success-message" style="background: rgba(0, 255, 0, 0.1); border: 1px solid #28a745; color: #28a745; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
            <?php echo htmlspecialchars($data['success']); ?>
          </div>
          <?php if(!empty($data['dev_reset_link'])): ?>
          <div style="background: #2a2a2a; border: 1px solid #444; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
            <p style="color: #aaa; font-size: 13px; margin-bottom: 8px;">Reset link (XAMPP / local testing):</p>
            <a href="<?php echo htmlspecialchars($data['dev_reset_link']); ?>" style="color: #03B200; word-break: break-all;"><?php echo htmlspecialchars($data['dev_reset_link']); ?></a>
          </div>
          <?php endif; ?>
          <p style="margin-top: 16px;"><a href="<?php echo URLROOT; ?>/login" class="forgot-link">Back to Login</a></p>
        <?php else: ?>

        <form class="login-form" method="POST" action="<?php echo URLROOT; ?>/login/forgot">
          <h2 class="login-heading">Forgot Password</h2>

          <div class="form-group">
            <label for="email" class="login-label">Email</label>
            <input type="email"
                   id="email"
                   name="email"
                   class="login-input"
                   placeholder="Enter your email"
                   value="<?php echo isset($data['email']) ? htmlspecialchars($data['email']) : ''; ?>"
                   required>
          </div>

          <button type="submit" class="login-button">Send Reset Link</button>

          <div class="signup-prompt" style="margin-top: 24px;">
            <a href="<?php echo URLROOT; ?>/login" class="forgot-link">Back to Login</a>
          </div>
        </form>

        <?php endif; ?>
      </div>
    </div>
  </section>

  <style>
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
  </style>

</body>
</html>

<?php require APPROOT.'/views/inc/components/footer.php'; ?>
