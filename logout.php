<?php
/**
 * Logout Script
 * Module 4: State Management & Application Logic
 */

session_start();
require_once 'includes/auth_functions.php';

// Logout user
logout();

// Set success message for next session
session_start();
$_SESSION['success_message'] = 'You have been logged out successfully.';

// Redirect to login page
header('Location: login.php');
exit();
?>