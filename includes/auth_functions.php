<?php
/**
 * Authentication Functions
 * Module 4: State Management & Application Logic
 */

require_once 'db_config.php';

/**
 * Register a new user
 * @param array $userData User registration data
 * @return array Result with success status and message
 */
function registerUser($userData) {
    $conn = getDatabaseConnection();
    
    // Check if username already exists
    $sql = "SELECT user_id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $userData['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return ['success' => false, 'message' => 'Username already exists'];
    }
    $stmt->close();
    
    // Check if email already exists
    $sql = "SELECT user_id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $userData['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return ['success' => false, 'message' => 'Email already registered'];
    }
    $stmt->close();
    
    // Hash password
    $passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);
    
    // Insert new user
    $sql = "INSERT INTO users (username, email, password_hash, full_name, phone, location) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssss',
        $userData['username'],
        $userData['email'],
        $passwordHash,
        $userData['full_name'],
        $userData['phone'],
        $userData['location']
    );
    
    if ($stmt->execute()) {
        $userId = $conn->insert_id;
        $stmt->close();
        return ['success' => true, 'message' => 'Registration successful!', 'user_id' => $userId];
    } else {
        $stmt->close();
        return ['success' => false, 'message' => 'Registration failed. Please try again.'];
    }
}

/**
 * Login user
 * @param string $username Username or email
 * @param string $password Password
 * @return array Result with success status and user data
 */
function loginUser($username, $password) {
    $conn = getDatabaseConnection();
    
    // Check if login is email or username
    $sql = "SELECT * FROM users WHERE (username = ? OR email = ?) AND is_active = TRUE";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $stmt->close();
        return ['success' => false, 'message' => 'Invalid username or password'];
    }
    
    $user = $result->fetch_assoc();
    $stmt->close();
    
    // Verify password
    if (password_verify($password, $user['password_hash'])) {
        // Remove password hash from user data
        unset($user['password_hash']);
        return ['success' => true, 'message' => 'Login successful!', 'user' => $user];
    } else {
        return ['success' => false, 'message' => 'Invalid username or password'];
    }
}

/**
 * Check if user is logged in
 * @return bool True if logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current logged-in user data
 * @return array|null User data or null
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $conn = getDatabaseConnection();
    $sql = "SELECT user_id, username, email, full_name, phone, location, created_at 
            FROM users WHERE user_id = ? AND is_active = TRUE";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        // User not found or inactive, clear session
        logout();
        return null;
    }
    
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;
}

/**
 * Logout user
 */
function logout() {
    // Clear all session variables
    $_SESSION = array();
    
    // Destroy session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    // Destroy session
    session_destroy();
}

/**
 * Check if user is admin
 * @return bool True if admin
 */
function isAdmin() {
    if (!isLoggedIn()) {
        return false;
    }
    
    // Admin has username 'admin' or user_id 7 (from sample data)
    return $_SESSION['username'] === 'admin' || $_SESSION['user_id'] === 7;
}

/**
 * Require login - redirect to login page if not logged in
 * @param string $redirectUrl URL to redirect to after login
 */
function requireLogin($redirectUrl = '') {
    if (!isLoggedIn()) {
        if (!empty($redirectUrl)) {
            $_SESSION['redirect_after_login'] = $redirectUrl;
        }
        header('Location: login.php');
        exit();
    }
}

/**
 * Require admin - redirect if not admin
 */
function requireAdmin() {
    requireLogin();
    
    if (!isAdmin()) {
        $_SESSION['error_message'] = 'Access denied. Admin privileges required.';
        header('Location: index.php');
        exit();
    }
}

/**
 * Get user's books
 * @param int $userId User ID
 * @return array Array of books
 */
function getUserBooks($userId) {
    $conn = getDatabaseConnection();
    
    $sql = "SELECT * FROM books WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
    
    $stmt->close();
    return $books;
}

/**
 * Validate registration data
 * @param array $data Registration data
 * @return array Validation errors (empty if valid)
 */
function validateRegistration($data) {
    $errors = [];
    
    // Username validation
    if (empty($data['username'])) {
        $errors[] = 'Username is required';
    } elseif (strlen($data['username']) < 3) {
        $errors[] = 'Username must be at least 3 characters';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $data['username'])) {
        $errors[] = 'Username can only contain letters, numbers, and underscores';
    }
    
    // Email validation
    if (empty($data['email'])) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    
    // Password validation
    if (empty($data['password'])) {
        $errors[] = 'Password is required';
    } elseif (strlen($data['password']) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    }
    
    // Confirm password
    if (empty($data['confirm_password'])) {
        $errors[] = 'Please confirm your password';
    } elseif ($data['password'] !== $data['confirm_password']) {
        $errors[] = 'Passwords do not match';
    }
    
    // Full name validation
    if (empty($data['full_name'])) {
        $errors[] = 'Full name is required';
    }
    
    return $errors;
}
?>
