<?php require APPROOT.'/views/inc/components/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password | BookMyGround.com</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">
</head>
<body>

  <section class="login-content-section">
    <div class="login-content-container">
      <div class="welcome-content">
        <h2 class="welcome-heading">SET NEW <span class="green">PASSWORD</span></h2>
        <p style="color: #ccc; margin-top: 16px;">Choose a strong password that you don't use elsewhere. It must be at least 6 characters.</p>
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
          <p style="margin-top: 16px;"><a href="<?php echo URLROOT; ?>/login" class="forgot-link">Go to Login</a></p>
        <?php else: ?>

        <form class="login-form" method="POST" action="<?php echo URLROOT; ?>/login/resetPassword/<?php echo htmlspecialchars($data['token'] ?? ''); ?>">
          <h2 class="login-heading">Reset Password</h2>

          <input type="hidden" name="token" value="<?php echo htmlspecialchars($data['token'] ?? ''); ?>">

          <div class="form-group">
            <label for="password" class="login-label">New Password</label>
            <input type="password"
                   id="password"
                   name="password"
                   class="login-input"
                   placeholder="Enter new password (min 6 characters)"
                   minlength="6"
                   required>
          </div>

          <div class="form-group">
            <label for="confirm_password" class="login-label">Confirm Password</label>
            <input type="password"
                   id="confirm_password"
                   name="confirm_password"
                   class="login-input"
                   placeholder="Confirm new password"
                   minlength="6"
                   required>
          </div>

          <button type="submit" class="login-button">Reset Password</button>

          <div class="signup-prompt" style="margin-top: 24px;">
            <a href="<?php echo URLROOT; ?>/login/forgot" class="forgot-link">Request new reset link</a> &nbsp;|&nbsp;
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
