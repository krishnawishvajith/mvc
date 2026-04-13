<?php require APPROOT.'/views/inc/components/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SignUp | BookMyGround.com</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/styledinesh.css">
</head>
<body>



<!--Sign Up section-->
<section class="sign-up-section">
  <div class="signup-text">
    <div class="feature">
        <h3 class="sign-in-dis">ELEVATE YOUR <span class="green">GAME</span></h3>
        <p class="description">
          Premium Features for Every Sports Enthusiast
        </p>
    </div>
    
    <div class="features">
      <div class="feature">
        <h3 class="sign-in-dis">INSTANT <span class="green">BOOKING</span></h3>
        <p class="description">
          Reserve prime sports venues with just a few clicks. 
          No waiting, no hassle — pure convenience.
        </p>
      </div>
      
      <div class="feature">
        <h3 class="sign-in-dis">EXPERT <span class="green">COACHING</span></h3>
        <p class="description">
          Learn from certified professionals and transform your 
          potential into excellence.
        </p>
      </div>
    </div>
  </div>

    <!-- Right side - Signup Form -->
    <div class="signup">
      <form class="signup-form">
        <h2 class="role-heading">CHOOSE YOUR <span class="green">ROLE</span></h2>
        
        <div class="role-cards">
          <a href="<?php echo URLROOT; ?>/register/customer" class="role-card">
            <div class="role-icon">
              <span class="icon">👤</span>
              <div class="icon-ring"></div>
            </div>
            <h3>Sports Player</h3>
            <p>Book venues, join sessions, and connect with coaches and rental services</p>
            <div class="role-hover">
              <span class="arrow">→</span>
            </div>
          </a>
          
          <a href="<?php echo URLROOT; ?>/register/stadium_owner" class="role-card">
            <div class="role-icon">
              <span class="icon">🏟️</span>
              <div class="icon-ring"></div>
            </div>
            <h3>Stadium Owner</h3>
            <p>List your facilities and manage bookings efficiently</p>
            <div class="role-hover">
              <span class="arrow">→</span>
            </div>
          </a>
          
          <a href="<?php echo URLROOT; ?>/register/rental_owner" class="role-card">
            <div class="role-icon">
              <span class="icon">⚽</span>
              <div class="icon-ring"></div>
            </div>
            <h3>Equipment Rental Service</h3>
            <p>Offer sports gear and expand your rental business</p>
            <div class="role-hover">
              <span class="arrow">→</span>
            </div>
          </a>
          
          <a href="<?php echo URLROOT; ?>/register/coach" class="role-card">
            <div class="role-icon">
              <span class="icon">👨‍🏫</span>
              <div class="icon-ring"></div>
            </div>
            <h3>Sports Coach</h3>
            <p>Share your expertise and grow your client base</p>
            <div class="role-hover">
              <span class="arrow">→</span>
            </div>
          </a>
        </div>
        
        <!-- Already have an account section -->
        <div class="signup-prompt">
          <p class="new-user-text">Already have an account?</p>
          <div class="create-account-wrapper">
            <a href="<?php echo URLROOT; ?>/login" class="create-account-button">Sign In Here</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

</body>
</html>
<?php require APPROOT.'/views/inc/components/footer.php'; ?>