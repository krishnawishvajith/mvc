<?php require APPROOT.'/views/inc/components/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Stadium Owner SignUp | BookMyGround.com</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/styledinesh.css">
</head>
<body>
  <!-- Owner Sign Up Section -->
  <section class="user-signup-section">
    <div class="signup-container">
      <!-- Left Side - Welcome Text -->
      <div class="welcome-content">
        <h1 class="sign-in-dis">JOIN AS A <span class="green">VENUE OWNER</span></h1>
        <p class="description">
          List your sports facilities, manage bookings efficiently, and grow your business 
          with our platform — reach more athletes and simplify management.
        </p>
        
        <div class="features-list">
          <div class="feature-item">
            <span class="feature-icon">📊</span>
            <p>Manage multiple venues effortlessly</p>
          </div>
          <div class="feature-item">
            <span class="feature-icon">💰</span>
            <p>Secure online payments and bookings</p>
          </div>
          <div class="feature-item">
            <span class="feature-icon">📱</span>
            <p>Real-time availability updates</p>
          </div>
          <div class="feature-item">
            <span class="feature-icon">📈</span>
            <p>Analytics and business insights</p>
          </div>
        </div>

        <div class="auth-options">
          <p class="auth-text">Already have an account?</p>
          <div class="auth-buttons">
            <a href="<?php echo URLROOT; ?>/login" class="hero-btn primary">Sign In</a>
            <a href="<?php echo URLROOT; ?>/register" class="hero-btn">Back</a>
          </div>
        </div>
      </div>

      <!-- Right Side - Sign Up Form -->
      <div class="signup-form-container">
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

        <!-- FIXED: Corrected form action URL -->
        <form class="signup-form" method="POST" action="<?php echo URLROOT; ?>/register/stadium_owner">
          <h2 class="signup-heading">Create Venue Owner Account</h2>
          
          <div class="form-row">
            <div class="form-group">
              <label for="owner-name" class="signup-label">Owner Name</label>
              <input type="text" 
                     id="owner-name" 
                     name="owner_name" 
                     class="signup-input" 
                     placeholder="Full name" 
                     value="<?php echo isset($data['form_data']['owner_name']) ? $data['form_data']['owner_name'] : ''; ?>"
                     required>
            </div>
            <div class="form-group">
              <label for="business-name" class="signup-label">Business Name</label>
              <input type="text" 
                     id="business-name" 
                     name="business_name" 
                     class="signup-input" 
                     placeholder="Business name" 
                     value="<?php echo isset($data['form_data']['business_name']) ? $data['form_data']['business_name'] : ''; ?>"
                     required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="email" class="signup-label">Email</label>
              <input type="email" 
                     id="email" 
                     name="email" 
                     class="signup-input" 
                     placeholder="Business email" 
                     value="<?php echo isset($data['form_data']['email']) ? $data['form_data']['email'] : ''; ?>"
                     required>
            </div>
            <div class="form-group">
              <label for="phone" class="signup-label">Phone Number</label>
              <input type="tel" 
                     id="phone" 
                     name="phone" 
                     class="signup-input" 
                     placeholder="Contact number" 
                     value="<?php echo isset($data['form_data']['phone']) ? $data['form_data']['phone'] : ''; ?>"
                     required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="district" class="signup-label">District</label>
              <select id="district" name="district" class="signup-input" required>
                <option value="" disabled <?php echo !isset($data['form_data']['district']) ? 'selected' : ''; ?>>Select your district</option>
                <?php foreach($data['cities'] as $city): ?>
                <option value="<?php echo $city; ?>" <?php echo isset($data['form_data']['district']) && $data['form_data']['district'] == $city ? 'selected' : ''; ?>>
                  <?php echo $city; ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="venue-type" class="signup-label">Venue Type</label>
              <select id="venue-type" name="venue_type" class="signup-input" required>
                <option value="" disabled <?php echo !isset($data['form_data']['venue_type']) ? 'selected' : ''; ?>>Select venue type</option>
                <?php foreach($data['venue_types'] as $key => $venue_type): ?>
                <option value="<?php echo $key; ?>" <?php echo isset($data['form_data']['venue_type']) && $data['form_data']['venue_type'] == $key ? 'selected' : ''; ?>>
                  <?php echo $venue_type; ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="business-reg" class="signup-label">Business Registration Number</label>
              <input type="text" 
                     id="business-reg" 
                     name="business_reg" 
                     class="signup-input" 
                     placeholder="Registration number" 
                     value="<?php echo isset($data['form_data']['business_reg']) ? $data['form_data']['business_reg'] : ''; ?>"
                     required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="password" class="signup-label">Password</label>
              <input type="password" 
                     id="password" 
                     name="password" 
                     class="signup-input" 
                     placeholder="Create password" 
                     required>
            </div>
            <div class="form-group">
              <label for="confirm-password" class="signup-label">Confirm Password</label>
              <input type="password" 
                     id="confirm-password" 
                     name="confirm_password" 
                     class="signup-input" 
                     placeholder="Confirm password" 
                     required>
            </div>
          </div>

          <button type="submit" class="signup-button">Create Owner Account</button>
          
          <p class="login-info">
            By creating an account, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
          </p>
        </form>
      </div>
    </div>
  </section>
</body>
</html>
<?php require APPROOT.'/views/inc/components/footer.php'; ?>