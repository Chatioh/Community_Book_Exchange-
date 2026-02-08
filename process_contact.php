<?php
/**
 * Contact Form Processing Script
 * Handles POST data and redirects to contact page (PRG pattern)
 */

// Include functions
require_once 'includes/functions.php';

// Initialize variables
$errors = [];
$formData = [];

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Retrieve and sanitize form data
    $name = isset($_POST['name']) ? sanitizeInput($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitizeInput($_POST['phone']) : '';
    $subject = isset($_POST['subject']) ? sanitizeInput($_POST['subject']) : '';
    $message = isset($_POST['message']) ? sanitizeInput($_POST['message']) : '';
    $newsletter = isset($_POST['newsletter']) ? true : false;
    
    // Validation
    if (empty($name)) {
        $errors[] = "Name is required.";
    } elseif (!validateName($name)) {
        $errors[] = "Please enter a valid name (letters only, minimum 2 characters).";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!validateEmail($email)) {
        $errors[] = "Please enter a valid email address.";
    }
    
    if (!empty($phone) && !validatePhone($phone)) {
        $errors[] = "Please enter a valid phone number.";
    }
    
    if (empty($subject)) {
        $errors[] = "Please select a subject.";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required.";
    } elseif (strlen($message) < 10) {
        $errors[] = "Message must be at least 10 characters long.";
    }
    
    // If no errors, process the form
    if (empty($errors)) {
        // Store form data in session for confirmation display
        $_SESSION['contact_form'] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message,
            'newsletter' => $newsletter,
            'submitted_at' => date('Y-m-d H:i:s')
        ];
        
        // Mark form as successfully submitted
        $_SESSION['form_success'] = true;
        
        // In a real application, you would:
        // - Send email notification
        // - Save to database
        // - Send confirmation email to user
        
        // Redirect to contact page (POST-Redirect-GET pattern)
        header('Location: contact.php?success=1');
        exit();
        
    } else {
        // Store errors and form data in session
        $_SESSION['contact_errors'] = $errors;
        $_SESSION['contact_form_data'] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message,
            'newsletter' => $newsletter
        ];
        
        // Redirect back to contact page with error
        header('Location: contact.php?error=1');
        exit();
    }
    
} else {
    // If not POST request, redirect to contact page
    header('Location: contact.php');
    exit();
}
?>
