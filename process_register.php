<?php
/**
 * Process Registration - POST-Redirect-GET Pattern
 * Module 4: State Management & Application Logic
 */

session_start();
require_once 'includes/db_config.php';
require_once 'includes/auth_functions.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit();
}

// Get form data
$userData = [
    'username' => isset($_POST['username']) ? trim($_POST['username']) : '',
    'email' => isset($_POST['email']) ? trim($_POST['email']) : '',
    'full_name' => isset($_POST['full_name']) ? trim($_POST['full_name']) : '',
    'password' => isset($_POST['password']) ? $_POST['password'] : '',
    'confirm_password' => isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '',
    'phone' => isset($_POST['phone']) ? trim($_POST['phone']) : '',
    'location' => isset($_POST['location']) ? trim($_POST['location']) : ''
];

// Validate input
$errors = validateRegistration($userData);

if (!empty($errors)) {
    // Validation failed
    $_SESSION['registration_errors'] = $errors;
    
    // Store form data (except passwords) for repopulation
    $_SESSION['registration_form_data'] = [
        'username' => $userData['username'],
        'email' => $userData['email'],
        'full_name' => $userData['full_name'],
        'phone' => $userData['phone'],
        'location' => $userData['location']
    ];
    
    header('Location: register.php');
    exit();
}

// Attempt registration
$result = registerUser($userData);

if ($result['success']) {
    // Registration successful - auto-login the user
    $_SESSION['user_id'] = $result['user_id'];
    $_SESSION['username'] = $userData['username'];
    $_SESSION['email'] = $userData['email'];
    $_SESSION['full_name'] = $userData['full_name'];
    $_SESSION['logged_in_at'] = time();
    
    // Regenerate session ID for security
    session_regenerate_id(true);
    
    $_SESSION['success_message'] = 'Welcome to Book Exchange, ' . $userData['full_name'] . '! Your account has been created successfully.';
    header('Location: index.php');
    exit();
} else {
    // Registration failed
    $_SESSION['registration_errors'] = [$result['message']];
    
    // Store form data for repopulation
    $_SESSION['registration_form_data'] = [
        'username' => $userData['username'],
        'email' => $userData['email'],
        'full_name' => $userData['full_name'],
        'phone' => $userData['phone'],
        'location' => $userData['location']
    ];
    
    header('Location: register.php');
    exit();
}
?>