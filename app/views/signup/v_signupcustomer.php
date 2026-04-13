<?php require APPROOT.'/views/inc/components/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User SignUp | BookMyGround.com</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/styledinesh.css">
</head>
<body>
  <!-- User Sign Up Section -->
  <section class="user-signup-section">
    <div class="signup-container">
      <!-- Left Side - Welcome Text -->
      <div class="welcome-content">
        <h1 class="sign-in-dis">JOIN AS A <span class="green">USER</span></h1>
        <p class="description">
          Create your account to book stadiums, join practice sessions,
          and get coaching — start your sports journey today!
        </p>
        
        <div class="features-list">
          <div class="feature-item">
            <span class="feature-icon">🎯</span>
            <p>Quick and easy stadium booking</p>
          </div>
          <div class="feature-item">
            <span class="feature-icon">🤝</span>
            <p>Join practice sessions with other players</p>
          </div>
          <div class="feature-item">
            <span class="feature-icon">👨‍🏫</span>
            <p>Connect with professional coaches</p>
          </div>
          <div class="feature-item">
            <span class="feature-icon">📅</span>
            <p>Manage all your bookings in one place</p>
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

        <form class="signup-form" method="POST" action="<?php echo URLROOT; ?>/register/customer">
          <h2 class="signup-heading">Create User Account</h2>
          
          <div class="form-row">
            <div class="form-group">
              <label for="first-name" class="signup-label">First Name</label>
              <input type="text" 
                     id="first-name" 
                     name="first_name" 
                     class="signup-input" 
                     placeholder="First name" 
                     value="<?php echo isset($data['form_data']['first_name']) ? $data['form_data']['first_name'] : ''; ?>"
                     required>
            </div>
            <div class="form-group">
              <label for="last-name" class="signup-label">Last Name</label>
              <input type="text" 
                     id="last-name" 
                     name="last_name" 
                     class="signup-input" 
                     placeholder="Last name" 
                     value="<?php echo isset($data['form_data']['last_name']) ? $data['form_data']['last_name'] : ''; ?>"
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
                     placeholder="Your email" 
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
              <label for="sports" class="signup-label">Preferred Sports</label>
              <select id="sports" name="sports" class="signup-input" required>
                <option value="" disabled <?php echo !isset($data['form_data']['sports']) ? 'selected' : ''; ?>>Select primary sport</option>
                <?php foreach($data['sports'] as $key => $sport): ?>
                <option value="<?php echo $key; ?>" <?php echo isset($data['form_data']['sports']) && $data['form_data']['sports'] == $key ? 'selected' : ''; ?>>
                  <?php echo $sport; ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="age-group" class="signup-label">Age Group</label>
              <select id="age-group" name="age_group" class="signup-input" required>
                <option value="" disabled <?php echo !isset($data['form_data']['age_group']) ? 'selected' : ''; ?>>Select age group</option>
                <?php foreach($data['age_groups'] as $key => $age_group): ?>
                <option value="<?php echo $key; ?>" <?php echo isset($data['form_data']['age_group']) && $data['form_data']['age_group'] == $key ? 'selected' : ''; ?>>
                  <?php echo $age_group; ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="skill-level" class="signup-label">Skill Level</label>
              <select id="skill-level" name="skill_level" class="signup-input" required>
                <option value="" disabled <?php echo !isset($data['form_data']['skill_level']) ? 'selected' : ''; ?>>Select skill level</option>
                <?php foreach($data['skill_levels'] as $key => $skill_level): ?>
                <option value="<?php echo $key; ?>" <?php echo isset($data['form_data']['skill_level']) && $data['form_data']['skill_level'] == $key ? 'selected' : ''; ?>>
                  <?php echo $skill_level; ?>
                </option>
                <?php endforeach; ?>
              </select>
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

          <button type="submit" class="signup-button">Create Account</button>
          
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