<?php
/**
 * Database Functions for Books
 * Module 3: Data Persistence & SQL Integration
 */

require_once 'db_config.php';

/**
 * Get all available books with owner information
 * @param string $genre Filter by genre (optional)
 * @param string $condition Filter by condition (optional)
 * @param string $location Filter by location (optional)
 * @param string $search Search in title and author (optional)
 * @return array Array of books
 */
function getAllBooks($genre = '', $condition = '', $location = '', $search = '') {
    $conn = getDatabaseConnection();
    
    // Build query with filters
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
        $sql .= " AND (b.title LIKE ? OR b.author LIKE ?)";
        $searchTerm = '%' . $search . '%';
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $types .= 'ss';
    }
    
    $sql .= " ORDER BY b.created_at DESC";
    
    // Prepare and execute
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
    return $books;
}

/**
 * Get a single book by ID
 * @param int $bookId
 * @return array|null Book data or null if not found
 */
function getBookById($bookId) {
    $conn = getDatabaseConnection();
    
    $sql = "SELECT b.*, u.full_name as owner_name, u.email as owner_email, u.phone as owner_phone 
            FROM books b 
            JOIN users u ON b.user_id = u.user_id 
            WHERE b.book_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $bookId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $book = $result->fetch_assoc();
    $stmt->close();
    
    return $book;
}

/**
 * Create a new book listing
 * @param array $bookData Book information
 * @return int|false New book ID or false on failure
 */
function createBook($bookData) {
    $conn = getDatabaseConnection();
    
    $sql = "INSERT INTO books (user_id, title, author, isbn, genre, book_condition, 
            description, exchange_preference, location, cover_color, is_available) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isssssssssi',
        $bookData['user_id'],
        $bookData['title'],
        $bookData['author'],
        $bookData['isbn'],
        $bookData['genre'],
        $bookData['book_condition'],
        $bookData['description'],
        $bookData['exchange_preference'],
        $bookData['location'],
        $bookData['cover_color'],
        $bookData['is_available']
    );
    
    $success = $stmt->execute();
    $insertId = $success ? $conn->insert_id : false;
    
    $stmt->close();
    return $insertId;
}

/**
 * Update an existing book
 * @param int $bookId Book ID to update
 * @param array $bookData Updated book information
 * @return bool True on success, false on failure
 */
function updateBook($bookId, $bookData) {
    $conn = getDatabaseConnection();
    
    $sql = "UPDATE books SET 
            title = ?, 
            author = ?, 
            isbn = ?, 
            genre = ?, 
            book_condition = ?, 
            description = ?, 
            exchange_preference = ?, 
            location = ?, 
            cover_color = ?, 
            is_available = ?
            WHERE book_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssssii',
        $bookData['title'],
        $bookData['author'],
        $bookData['isbn'],
        $bookData['genre'],
        $bookData['book_condition'],
        $bookData['description'],
        $bookData['exchange_preference'],
        $bookData['location'],
        $bookData['cover_color'],
        $bookData['is_available'],
        $bookId
    );
    
    $success = $stmt->execute();
    $stmt->close();
    
    return $success;
}

/**
 * Delete a book
 * @param int $bookId Book ID to delete
 * @return bool True on success, false on failure
 */
function deleteBook($bookId) {
    $conn = getDatabaseConnection();
    
    $sql = "DELETE FROM books WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $bookId);
    
    $success = $stmt->execute();
    $stmt->close();
    
    return $success;
}

/**
 * Get book statistics
 * @return array Statistics data
 */
function getBookStats() {
    $conn = getDatabaseConnection();
    
    // Total books
    $result = $conn->query("SELECT COUNT(*) as total FROM books WHERE is_available = TRUE");
    $totalBooks = $result->fetch_assoc()['total'];
    
    // Total users
    $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE is_active = TRUE");
    $totalUsers = $result->fetch_assoc()['total'];
    
    // Total exchanges (placeholder for now)
    $totalExchanges = 2891;
    
    return [
        'total_books' => $totalBooks,
        'total_users' => $totalUsers,
        'total_exchanges' => $totalExchanges
    ];
}

/**
 * Get available genres from database
 * @return array List of genres
 */
function getGenres() {
    $conn = getDatabaseConnection();
    
    $sql = "SELECT DISTINCT genre FROM books WHERE is_available = TRUE ORDER BY genre";
    $result = $conn->query($sql);
    
    $genres = [];
    while ($row = $result->fetch_assoc()) {
        $genres[] = $row['genre'];
    }
    
    return $genres;
}

/**
 * Get available locations from database
 * @return array List of locations
 */
function getLocations() {
    $conn = getDatabaseConnection();
    
    $sql = "SELECT DISTINCT location FROM books WHERE is_available = TRUE ORDER BY location";
    $result = $conn->query($sql);
    
    $locations = [];
    while ($row = $result->fetch_assoc()) {
        $locations[] = $row['location'];
    }
    
    return $locations;
}

/**
 * Format book condition for display
 * @param string $condition
 * @return string Badge class
 */
function getConditionBadgeClass($condition) {
    $classes = [
        'Excellent' => 'badge-excellent',
        'Very Good' => 'badge-very-good',
        'Good' => 'badge-good',
        'Fair' => 'badge-fair'
    ];
    
    return $classes[$condition] ?? 'badge-good';
}
?>
