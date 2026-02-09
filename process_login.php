<?php
/**
 * Process Login - POST-Redirect-GET Pattern
 * Module 4: State Management & Application Logic
 */

session_start();
require_once 'includes/db_config.php';
require_once 'includes/auth_functions.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit();
}

// Get form data
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$rememberMe = isset($_POST['remember_me']);

// Validate input
if (empty($username) || empty($password)) {
    $_SESSION['error_message'] = 'Please enter both username and password';
    $_SESSION['login_form_data'] = ['username' => $username];
    header('Location: login.php');
    exit();
}

// Attempt login
$result = loginUser($username, $password);

if ($result['success']) {
    // Store user data in session
    $_SESSION['user_id'] = $result['user']['user_id'];
    $_SESSION['username'] = $result['user']['username'];
    $_SESSION['email'] = $result['user']['email'];
    $_SESSION['full_name'] = $result['user']['full_name'];
    $_SESSION['logged_in_at'] = time();
    
    // Save redirect URL BEFORE session regeneration
    $redirectUrl = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : '';
    
    // Set remember me cookie (optional - for future enhancement)
    if ($rememberMe) {
        // Set cookie for 30 days
        setcookie('remember_user', $result['user']['user_id'], time() + (30 * 24 * 60 * 60), '/');
    }
    
    // Regenerate session ID for security
    session_regenerate_id(true);
    
    // Check if there's a redirect URL (restore after regeneration)
    if (!empty($redirectUrl)) {
        header('Location: ' . $redirectUrl);
    } else {
        // Default redirect to homepage
        $_SESSION['success_message'] = 'Welcome back, ' . $result['user']['full_name'] . '!';
        header('Location: index.php');
    }
    exit();
} else {
    // Login failed
    $_SESSION['error_message'] = $result['message'];
    $_SESSION['login_form_data'] = ['username' => $username];
    header('Location: login.php');
    exit();
}
?>