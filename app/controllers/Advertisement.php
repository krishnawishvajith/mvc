<?php
class Advertisement extends Controller {
    
    public function __construct()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        // Check if user is logged in
        if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
            // Redirect based on user role to their respective dashboard
            $role = $_SESSION['user_role'] ?? 'customer';
            
            switch($role) {
                case 'stadium_owner':
                    header('Location: ' . URLROOT . '/stadium_owner#advertisements');
                    break;
                case 'coach':
                    header('Location: ' . URLROOT . '/coachdash#advertisements');
                    break;
                case 'rental_owner':
                    header('Location: ' . URLROOT . '/rentalowner#advertisements');
                    break;
                case 'customer':
                default:
                    header('Location: ' . URLROOT . '/customer#advertisements');
                    break;
            }
        } elseif (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
            // Admin goes to admin advertisement management
            header('Location: ' . URLROOT . '/admin/advertisements');
        } else {
            // Not logged in - redirect to login
            $_SESSION['redirect_after_login'] = URLROOT . '/advertisement';
            header('Location: ' . URLROOT . '/login');
        }
        exit;
    }

    // Redirect any other method calls
    public function submit() {
        $this->index();
    }
}
?>