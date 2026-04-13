<?php
class Auth {
    
    // Check if user is logged in
    public static function isLoggedIn() {
        return isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
    }
    
    // Check if admin is logged in
    public static function isAdminLoggedIn() {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
    
    // Check if user has specific role
    public static function hasRole($role) {
        return self::isLoggedIn() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
    }
    
    // Get current user ID
    public static function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    // Get current user role
    public static function getUserRole() {
        return $_SESSION['user_role'] ?? null;
    }
    
    // Get current user email
    public static function getUserEmail() {
        return $_SESSION['user_email'] ?? null;
    }
    
    // Get current user name
    public static function getUserName() {
        return $_SESSION['user_name'] ?? null;
    }
    
    // Get current user first name
    public static function getUserFirstName() {
        return $_SESSION['user_first_name'] ?? null;
    }
    
    // Get current user last name
    public static function getUserLastName() {
        return $_SESSION['user_last_name'] ?? null;
    }
    
    // Get current admin ID
    public static function getAdminId() {
        return $_SESSION['admin_id'] ?? null;
    }
    
    // Require login - redirect if not logged in
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }
    }
    
    // Require admin - redirect if not admin
    public static function requireAdmin() {
        if (!self::isAdminLoggedIn()) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }
    }
    
    // Require specific role
    public static function requireRole($role) {
        if (!self::hasRole($role)) {
            header('Location: ' . URLROOT . '/login');
            exit;
        }
    }
    
    // Debug session information
    public static function debugSession() {
        return [
            'user_logged_in' => $_SESSION['user_logged_in'] ?? false,
            'user_id' => $_SESSION['user_id'] ?? null,
            'user_role' => $_SESSION['user_role'] ?? null,
            'user_email' => $_SESSION['user_email'] ?? null,
            'session_id' => session_id(),
            'all_session' => $_SESSION
        ];
    }
}