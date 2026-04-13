<?php
class Login extends Controller {
    private $loginModel;

    public function __construct()
    {
        // Ensure session is started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->loginModel = $this->model('M_Login');
    }

    public function index() {
        // Check if already logged in
        if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
            $this->redirectToDashboard($_SESSION['user_role']);
            return;
        }

        // Check if admin is logged in
        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
            header('Location: ' . URLROOT . '/admin');
            exit;
        }

        // Data to pass to the login view
        $data = [
            'title' => 'Login - BookMyGround',
            'error' => '',
            'success' => ''
        ];

        // Handle POST request (when form is submitted)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->processLogin($data);
        }

        // Load the login view
        $this->view('login/v_login', $data);
    }

    private function processLogin($data) {
        // Get form data
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        // Basic validation
        if (empty($email) || empty($password)) {
            $data['error'] = 'Please fill in all fields';
            return $data;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $data['error'] = 'Please enter a valid email address';
            return $data;
        }

        // Check for too many login attempts
        $attempts = $this->loginModel->getLoginAttempts($email);
        if ($attempts >= 5) {
            $data['error'] = 'Too many failed login attempts. Please try again after 15 minutes.';
            return $data;
        }

        // Attempt to authenticate user (this now checks both users and admins)
        $user = $this->loginModel->login($email, $password);
        
        if ($user) {
            // Check if it's an admin
            if ($user->role === 'admin') {
                // Admin login process
                if ($user->status !== 'active') {
                    $data['error'] = 'Your admin account is inactive. Please contact system administrator.';
                    return $data;
                }

                // Clear any previous login attempts
                $this->loginModel->clearLoginAttempts($email);
                
                // Update last login for admin
                $this->loginModel->updateLastLogin($user->id, true);
                
                // Set admin session variables
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user->id;
                $_SESSION['admin_email'] = $user->email;
                $_SESSION['admin_name'] = $user->full_name;
                $_SESSION['admin_role'] = 'admin';

                // Log the activity
                $this->loginModel->logActivity($user->id, 'Admin logged in', true);

                // Check for redirect_to parameter (e.g., from checkout)
                $redirectTo = trim($_POST['redirect_to'] ?? '');
                if (!empty($redirectTo) && strpos($redirectTo, URLROOT) === 0) {
                    header('Location: ' . $redirectTo);
                } else {
                    header('Location: ' . URLROOT . '/admin');
                }
                exit;

            } else {
                // Regular user login process
                
                // Check if user account is active
                if ($user->status !== 'active') {
                    $data['error'] = 'Your account is not active. Please contact support.';
                    return $data;
                }
                
                // Clear any previous login attempts
                $this->loginModel->clearLoginAttempts($email);
                
                // Update last login
                $this->loginModel->updateLastLogin($user->id);
                
                // Set user session variables
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_email'] = $user->email;
                $_SESSION['user_name'] = $user->first_name . ' ' . $user->last_name;
                $_SESSION['user_role'] = $user->role;
                $_SESSION['user_first_name'] = $user->first_name;
                $_SESSION['user_last_name'] = $user->last_name;
                $_SESSION['user_status'] = $user->status;

                // Set remember me cookie if requested
                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/'); // 30 days
                }

                // Log the activity
                $this->loginModel->logActivity($user->id, 'User logged in');

                // Check for redirect_to parameter (e.g., from checkout)
                $redirectTo = trim($_POST['redirect_to'] ?? '');
                if (!empty($redirectTo) && strpos($redirectTo, URLROOT) === 0) {
                    header('Location: ' . $redirectTo);
                    exit;
                }

                // Redirect to appropriate dashboard
                $this->redirectToDashboard($user->role);
            }
            
        } else {
            // Record failed login attempt
            $this->loginModel->recordLoginAttempt($email, $_SERVER['REMOTE_ADDR']);
            
            $data['error'] = 'Invalid email or password';
            $data['email'] = $email; // Keep email in form
        }

        return $data;
    }

    private function redirectToDashboard($role) {
        switch($role) {
            case 'customer':
                header('Location: ' . URLROOT . '/customer');
                break;
            case 'stadium_owner':
                header('Location: ' . URLROOT . '/stadium_owner');
                break;
            case 'coach':
                header('Location: ' . URLROOT . '/coachdash'); // FIXED: Changed from /coach to /coachdash
                break;
            case 'rental_owner':
                header('Location: ' . URLROOT . '/rentalowner');
                break;
            case 'admin':
                header('Location: ' . URLROOT . '/admin');
                break;
            default:
                header('Location: ' . URLROOT . '/customer');
                break;
        }
        exit;
    }

    public function ajax() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        if (empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all fields']);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Please enter a valid email address']);
            exit;
        }

        $attempts = $this->loginModel->getLoginAttempts($email);
        if ($attempts >= 5) {
            echo json_encode(['success' => false, 'message' => 'Too many failed login attempts. Please try again after 15 minutes.']);
            exit;
        }

        $user = $this->loginModel->login($email, $password);

        if (!$user) {
            $this->loginModel->recordLoginAttempt($email, $_SERVER['REMOTE_ADDR']);
            echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
            exit;
        }

        if ($user->role === 'admin') {
            echo json_encode(['success' => false, 'message' => 'Admin login is not supported in this form']);
            exit;
        }

        if ($user->status !== 'active') {
            echo json_encode(['success' => false, 'message' => 'Your account is not active. Please contact support.']);
            exit;
        }

        $this->loginModel->clearLoginAttempts($email);
        $this->loginModel->updateLastLogin($user->id);

        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->first_name . ' ' . $user->last_name;
        $_SESSION['user_role'] = $user->role;
        $_SESSION['user_first_name'] = $user->first_name;
        $_SESSION['user_last_name'] = $user->last_name;
        $_SESSION['user_status'] = $user->status;

        if ($remember) {
            $token = bin2hex(random_bytes(32));
            setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/');
        }

        echo json_encode(['success' => true, 'message' => 'Logged in successfully']);
        exit;
    }

    public function check_session() {
        // Check if user is logged in (for AJAX verification)
        header('Content-Type: application/json');
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (Auth::isLoggedIn()) {
            echo json_encode([
                'success' => true, 
                'message' => 'User is logged in',
                'user_id' => Auth::getUserId(),
                'user_email' => $_SESSION['user_email'] ?? '',
                'user_name' => $_SESSION['user_name'] ?? ''
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'User is not logged in']);
        }
        exit;
    }

    public function forgot() {
        // Handle forgot password for both users and admins
        $data = [
            'title' => 'Forgot Password - BookMyGround',
            'error' => '',
            'success' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email'] ?? '');
            
            if (empty($email)) {
                $data['error'] = 'Please enter your email address';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['error'] = 'Please enter a valid email address';
            } elseif ($this->loginModel->emailExists($email)) {
                // Generate password reset token
                $token = $this->loginModel->createPasswordResetToken($email);
                if ($token) {
                    $sent = $this->sendPasswordResetEmail($email, $token);
                    if ($sent) {
                        $data['success'] = 'Password reset instructions have been sent to your email address.';
                    } elseif (Mail::isConfigured()) {
                        $data['error'] = 'We could not send the email. Please try again later or contact support.';
                    } else {
                        // No SMTP and likely localhost: show reset link for testing
                        $data['success'] = 'On this server we cannot send email. Use the link below to reset your password (valid for 1 hour).';
                        $data['dev_reset_link'] = URLROOT . '/login/resetPassword/' . $token;
                    }
                } else {
                    $data['error'] = 'Unable to process password reset. Please try again.';
                }
            } else {
                $data['error'] = 'No account found with that email address.';
            }
        }

        $this->view('login/v_forgot_password', $data);
    }

    public function logout() {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Log the activity before clearing session
        if (isset($_SESSION['user_id'])) {
            $this->loginModel->logActivity($_SESSION['user_id'], 'User logged out');
        } elseif (isset($_SESSION['admin_id'])) {
            $this->loginModel->logActivity($_SESSION['admin_id'], 'Admin logged out', true);
        }
        
        // Clear session
        session_unset();
        session_destroy();
        
        // Clear remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }
        
        // Start new session
        session_start();
        
        // Redirect to login page
        header('Location: ' . URLROOT . '/login');
        exit;
    }

    public function register() {
        // Redirect to register page
        header('Location: ' . URLROOT . '/register');
        exit;
    }

    public function resetPassword($token = null) {
        $token = $token !== null ? trim((string) $token) : '';
        if ($token === '') {
            header('Location: ' . URLROOT . '/login/forgot');
            exit;
        }

        $data = [
            'title' => 'Reset Password - BookMyGround',
            'error' => '',
            'success' => '',
            'token' => $token
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $resetToken = trim((string) ($_POST['token'] ?? ''));

            // Validate inputs
            if (empty($password) || empty($confirmPassword)) {
                $data['error'] = 'Please fill in all fields';
            } elseif (strlen($password) < 6) {
                $data['error'] = 'Password must be at least 6 characters long';
            } elseif ($password !== $confirmPassword) {
                $data['error'] = 'Passwords do not match';
            } else {
                $userEmail = $this->loginModel->getEmailByResetToken($resetToken);
                if ($this->loginModel->resetPassword($resetToken, $password)) {
                    if ($userEmail !== null) {
                        $this->sendPasswordResetSuccessEmail($userEmail);
                    }
                    $data['success'] = 'Password reset successfully. You can now login with your new password.';
                    header('refresh:3;url=' . URLROOT . '/login');
                    $data['error'] = 'Invalid or expired reset token. Please request a new password reset.';
                }
            }
        } else {
            // Verify token is valid
            if (!$this->loginModel->verifyResetToken($token)) {
                $data['error'] = 'Invalid or expired reset token. Please request a new password reset.';
            }
        }

        $this->view('login/v_reset_password', $data);
    }

    /**
     * Send password reset email. Uses SMTP if configured, otherwise PHP mail().
     * Returns true if sent successfully.
     */
    private function sendPasswordResetEmail($email, $token) {
        $resetLink = URLROOT . '/login/resetPassword/' . $token;
        $subject = 'Reset your password - ' . SITENAME;
        $message = "Hello,\n\n";
        $message .= "You requested a password reset for your " . SITENAME . " account.\n\n";
        $message .= "Click the link below to set a new password (valid for 1 hour):\n";
        $message .= $resetLink . "\n\n";
        $message .= "If you did not request this, please ignore this email.\n\n";
        $message .= "— " . SITENAME;

        $fromEmail = (defined('SMTP_FROM_EMAIL') && trim(SMTP_FROM_EMAIL) !== '')
            ? SMTP_FROM_EMAIL
            : (defined('MAIL_FROM') ? MAIL_FROM : 'noreply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));

        if (Mail::isConfigured()) {
            $mail = new Mail();
            return $mail->send($email, $subject, $message, $fromEmail, SITENAME);
        }

        $headers = "From: " . SITENAME . " <" . $fromEmail . ">\r\n";
        $headers .= "Reply-To: " . $fromEmail . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        return @mail($email, $subject, $message, $headers);
    }

    /**
     * Send "password reset successfully" confirmation email.
     */
    private function sendPasswordResetSuccessEmail($email) {
        $subject = 'Your password was reset - ' . SITENAME;
        $message = "Hello,\n\n";
        $message .= "Your " . SITENAME . " account password was successfully reset.\n\n";
        $message .= "If you did not make this change, please contact support or use Forgot Password to secure your account.\n\n";
        $message .= "— " . SITENAME;

        $fromEmail = (defined('SMTP_FROM_EMAIL') && trim(SMTP_FROM_EMAIL) !== '')
            ? SMTP_FROM_EMAIL
            : (defined('MAIL_FROM') ? MAIL_FROM : 'noreply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));

        if (Mail::isConfigured()) {
            $mail = new Mail();
            return $mail->send($email, $subject, $message, $fromEmail, SITENAME);
        }
        $headers = "From: " . SITENAME . " <" . $fromEmail . ">\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        return @mail($email, $subject, $message, $headers);
    }

    public function verify($token = null) {
        // Handle email verification
        if (!$token) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }

        if ($this->loginModel->verifyEmail($token)) {
            $_SESSION['verification_success'] = 'Email verified successfully! You can now login.';
        } else {
            $_SESSION['verification_error'] = 'Invalid or expired verification token.';
        }

        header('Location: ' . URLROOT . '/login');
        exit;
    }
}
?>