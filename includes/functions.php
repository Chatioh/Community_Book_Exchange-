<?php
/**
 * Community Book Exchange - Helper Functions
 * Module 2: Server-Side Programming with PHP
 */

/**
 * Get time-based greeting based on server time
 * @return string Greeting message
 */
function getTimeBasedGreeting() {
    $hour = (int)date('H'); // 24-hour format
    
    if ($hour >= 5 && $hour < 12) {
        return "Good Morning";
    } elseif ($hour >= 12 && $hour < 17) {
        return "Good Afternoon";
    } elseif ($hour >= 17 && $hour < 21) {
        return "Good Evening";
    } else {
        return "Good Night";
    }
}

/**
 * Get icon for time of day
 * @return string Emoji icon
 */
function getTimeIcon() {
    $hour = (int)date('H');
    
    if ($hour >= 5 && $hour < 12) {
        return "🌅"; // Morning
    } elseif ($hour >= 12 && $hour < 17) {
        return "☀️"; // Afternoon
    } elseif ($hour >= 17 && $hour < 21) {
        return "🌆"; // Evening
    } else {
        return "🌙"; // Night
    }
}

/**
 * Sanitize user input
 * @param string $data Input data
 * @return string Sanitized data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Validate email address
 * @param string $email Email to validate
 * @return bool True if valid
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate name (letters, spaces, hyphens, apostrophes only)
 * @param string $name Name to validate
 * @return bool True if valid
 */
function validateName($name) {
    return preg_match("/^[a-zA-Z\s'-]+$/", $name) && strlen($name) >= 2;
}

/**
 * Validate phone number (flexible format)
 * @param string $phone Phone to validate
 * @return bool True if valid
 */
function validatePhone($phone) {
    // Allow digits, spaces, parentheses, hyphens, plus sign
    return preg_match("/^[\d\s()+-]+$/", $phone);
}

/**
 * Create success alert HTML
 * @param string $message Success message
 * @return string HTML for success alert
 */
function createSuccessAlert($message) {
    return '<div class="alert alert-success" role="alert">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="display: inline-block; margin-right: 8px; vertical-align: middle; stroke-width: 2;">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
        ' . htmlspecialchars($message) . '
    </div>';
}

/**
 * Create error alert HTML
 * @param string $message Error message
 * @return string HTML for error alert
 */
function createErrorAlert($message) {
    return '<div class="alert alert-error" role="alert">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="display: inline-block; margin-right: 8px; vertical-align: middle; stroke-width: 2;">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="15" y1="9" x2="9" y2="15"></line>
            <line x1="9" y1="9" x2="15" y2="15"></line>
        </svg>
        ' . htmlspecialchars($message) . '
    </div>';
}

/**
 * Get current date/time formatted nicely
 * @return string Formatted date/time
 */
function getCurrentDateTime() {
    return date('l, F j, Y - g:i A');
}

/**
 * Check if form was just submitted (for PRG pattern)
 * @return bool True if just redirected after form submission
 */
function wasFormSubmitted() {
    return isset($_SESSION['form_submitted']) && $_SESSION['form_submitted'] === true;
}

/**
 * Mark form as submitted (for PRG pattern)
 */
function markFormSubmitted() {
    $_SESSION['form_submitted'] = true;
}

/**
 * Clear form submission flag (after displaying message)
 */
function clearFormSubmitted() {
    unset($_SESSION['form_submitted']);
}
?>
