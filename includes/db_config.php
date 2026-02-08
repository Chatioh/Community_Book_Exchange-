<?php
/**
 * Database Configuration
 * Module 3: Data Persistence & SQL Integration
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');           // Change this to your MySQL username
define('DB_PASS', '');                // Change this to your MySQL password
define('DB_NAME', 'book_exchange');

// Create database connection
function getDatabaseConnection() {
    static $conn = null;
    
    if ($conn === null) {
        // Create connection
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Set charset to utf8mb4
        $conn->set_charset("utf8mb4");
    }
    
    return $conn;
}

/**
 * Close database connection
 */
function closeDatabaseConnection() {
    $conn = getDatabaseConnection();
    if ($conn) {
        $conn->close();
    }
}

/**
 * Prepare SQL statement (prevents SQL injection)
 */
function prepareStatement($sql) {
    $conn = getDatabaseConnection();
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    return $stmt;
}

/**
 * Execute query and return result
 */
function executeQuery($sql) {
    $conn = getDatabaseConnection();
    $result = $conn->query($sql);
    
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
    
    return $result;
}
?>
