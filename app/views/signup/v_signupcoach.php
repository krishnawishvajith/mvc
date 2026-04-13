<?php require APPROOT.'/views/inc/components/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Coach SignUp | BookMyGround.com</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/styledinesh.css">
</head>
<body>
  <!-- Coach Sign Up Section -->
  <section class="user-signup-section">
    <div class="signup-container">
      <!-- Left Side - Welcome Text -->
      <div class="welcome-content">
        <h1 class="sign-in-dis">JOIN AS A <span class="green">COACH</span></h1>
        <p class="description">
          Share your expertise, connect with athletes, and grow your coaching career 
          — become part of our thriving sports community.
        </p>
        
        <div class="features-list">
          <div class="feature-item">
            <span class="feature-icon">📅</span>
            <p>Flexible scheduling system</p>
          </div>
          <div class="feature-item">
            <span class="feature-icon">👥</span>
            <p>Manage multiple students</p>
          </div>
          <div class="feature-item">
            <span class="feature-icon">🏆</span>
            <p>Showcase your achievements</p>
          </div>
          <div class="feature-item">
            <span class="feature-icon">💰</span>
            <p>Secure payment system</p>
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

        <form class="signup-form" method="POST" action="<?php echo URLROOT; ?>/register/coach">
          <h2 class="signup-heading">Create Coach Account</h2>
          
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
              <label for="specialization" class="signup-label">Sports Specialization</label>
              <select id="specialization" name="specialization" class="signup-input" required>
                <option value="" disabled <?php echo !isset($data['form_data']['specialization']) ? 'selected' : ''; ?>>Select primary sport</option>
                <?php foreach($data['sports'] as $key => $sport): ?>
                <option value="<?php echo $key; ?>" <?php echo isset($data['form_data']['specialization']) && $data['form_data']['specialization'] == $key ? 'selected' : ''; ?>>
                  <?php echo $sport; ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="experience" class="signup-label">Years of Experience</label>
              <select id="experience" name="experience" class="signup-input" required>
                <option value="" disabled <?php echo !isset($data['form_data']['experience']) ? 'selected' : ''; ?>>Select experience</option>
                <?php foreach($data['experience_levels'] as $key => $experience_level): ?>
                <option value="<?php echo $key; ?>" <?php echo isset($data['form_data']['experience']) && $data['form_data']['experience'] == $key ? 'selected' : ''; ?>>
                  <?php echo $experience_level; ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="certification" class="signup-label">Certification Level</label>
              <select id="certification" name="certification" class="signup-input" required>
                <option value="" disabled <?php echo !isset($data['form_data']['certification']) ? 'selected' : ''; ?>>Select certification</option>
                <?php foreach($data['certification_levels'] as $key => $certification_level): ?>
                <option value="<?php echo $key; ?>" <?php echo isset($data['form_data']['certification']) && $data['form_data']['certification'] == $key ? 'selected' : ''; ?>>
                  <?php echo $certification_level; ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="coaching-type" class="signup-label">Coaching Type</label>
              <select id="coaching-type" name="coaching_type" class="signup-input" required>
                <option value="" disabled <?php echo !isset($data['form_data']['coaching_type']) ? 'selected' : ''; ?>>Select type</option>
                <?php foreach($data['coaching_types'] as $key => $coaching_type): ?>
                <option value="<?php echo $key; ?>" <?php echo isset($data['form_data']['coaching_type']) && $data['form_data']['coaching_type'] == $key ? 'selected' : ''; ?>>
                  <?php echo $coaching_type; ?>
                </option>
                <?php endforeach; ?>
              </select>
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
              <label for="availability" class="signup-label">Availability</label>
              <select id="availability" name="availability" class="signup-input" required>
                <option value="" disabled <?php echo !isset($data['form_data']['availability']) ? 'selected' : ''; ?>>Select availability</option>
                <?php foreach($data['availability_options'] as $key => $availability_option): ?>
                <option value="<?php echo $key; ?>" <?php echo isset($data['form_data']['availability']) && $data['form_data']['availability'] == $key ? 'selected' : ''; ?>>
                  <?php echo $availability_option; ?>
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

          <button type="submit" class="signup-button">Create Coach Account</button>
          
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