<?php
/**
 * AJAX API Endpoint for Book Search
 * Module 5: Asynchronous Interactions & Client-Side Dynamics
 */

header('Content-Type: application/json');

require_once '../includes/db_config.php';
require_once '../includes/book_functions.php';

// Get search parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$genre = isset($_GET['genre']) ? trim($_GET['genre']) : '';
$condition = isset($_GET['condition']) ? trim($_GET['condition']) : '';
$location = isset($_GET['location']) ? trim($_GET['location']) : '';
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 9;

try {
    // Get books with pagination
    $conn = getDatabaseConnection();
    
    // Build query
    $sql = "SELECT b.*, u.full_name as owner_name, u.email as owner_email 
            FROM books b 
            JOIN users u ON b.user_id = u.user_id 
            WHERE b.is_available = TRUE";
    
    $params = [];
    $types = '';
    
    if (!empty($genre)) {
        $sql .= " AND b.genre = ?";
        $params[] = $genre;
        $types .= 's';
    }
    
    if (!empty($condition)) {
        $sql .= " AND b.book_condition = ?";
        $params[] = $condition;
        $types .= 's';
    }
    
    if (!empty($location)) {
        $sql .= " AND b.location LIKE ?";
        $params[] = '%' . $location . '%';
        $types .= 's';
    }
    
    if (!empty($search)) {
        $sql .= " AND (b.title LIKE ? OR b.author LIKE ? OR b.description LIKE ?)";
        $searchTerm = '%' . $search . '%';
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= 'sss';
    }
    
    // Get total count (before pagination)
    $countStmt = $conn->prepare($sql);
    if (!empty($params)) {
        $countStmt->bind_param($types, ...$params);
    }
    $countStmt->execute();
    $totalBooks = $countStmt->get_result()->num_rows;
    $countStmt->close();
    
    // Add pagination
    $sql .= " ORDER BY b.created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= 'ii';
    
    // Execute query
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
    
    $stmt->close();
    
    // Calculate pagination info
    $hasMore = ($offset + $limit) < $totalBooks;
    
    // Return JSON response
    echo json_encode([
        'success' => true,
        'books' => $books,
        'total' => $totalBooks,
        'offset' => $offset,
        'limit' => $limit,
        'hasMore' => $hasMore,
        'count' => count($books)
    ]);
    
} catch (Exception $e) {
    // Error handling
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'An error occurred while searching for books',
        'message' => $e->getMessage()
    ]);
}
?>
