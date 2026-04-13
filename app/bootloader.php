<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Load Configuration
require_once 'config/config.php';

// Load libraries
require_once 'libraries/Core.php';
require_once 'libraries/Controller.php';
require_once 'libraries/Database.php';
require_once 'libraries/Auth.php';
require_once 'libraries/Mail.php';
require_once 'libraries/Stripe.php';