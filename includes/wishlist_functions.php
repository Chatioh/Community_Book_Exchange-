<?php
/**
 * Wishlist Functions
 * Module 4: State Management & Application Logic
 * Session-based wishlist management
 */

/**
 * Initialize wishlist in session
 */
function initWishlist() {
    if (!isset($_SESSION['wishlist'])) {
        $_SESSION['wishlist'] = [];
    }
}

/**
 * Add book to wishlist
 * @param int $bookId Book ID to add
 * @return bool True if added, false if already exists
 */
function addToWishlist($bookId) {
    initWishlist();
    
    // Check if already in wishlist
    if (in_array($bookId, $_SESSION['wishlist'])) {
        return false;
    }
    
    $_SESSION['wishlist'][] = $bookId;
    return true;
}

/**
 * Remove book from wishlist
 * @param int $bookId Book ID to remove
 * @return bool True if removed
 */
function removeFromWishlist($bookId) {
    initWishlist();
    
    $key = array_search($bookId, $_SESSION['wishlist']);
    if ($key !== false) {
        unset($_SESSION['wishlist'][$key]);
        // Re-index array
        $_SESSION['wishlist'] = array_values($_SESSION['wishlist']);
        return true;
    }
    
    return false;
}

/**
 * Check if book is in wishlist
 * @param int $bookId Book ID to check
 * @return bool True if in wishlist
 */
function isInWishlist($bookId) {
    initWishlist();
    return in_array($bookId, $_SESSION['wishlist']);
}

/**
 * Get all wishlist book IDs
 * @return array Array of book IDs
 */
function getWishlistIds() {
    initWishlist();
    return $_SESSION['wishlist'];
}

/**
 * Get wishlist count
 * @return int Number of books in wishlist
 */
function getWishlistCount() {
    initWishlist();
    return count($_SESSION['wishlist']);
}

/**
 * Clear entire wishlist
 */
function clearWishlist() {
    $_SESSION['wishlist'] = [];
}

/**
 * Get full wishlist with book details
 * @return array Array of book details
 */
function getWishlistBooks() {
    initWishlist();
    
    if (empty($_SESSION['wishlist'])) {
        return [];
    }
    
    require_once 'db_config.php';
    $conn = getDatabaseConnection();
    
    // Create placeholders for IN clause
    $placeholders = str_repeat('?,', count($_SESSION['wishlist']) - 1) . '?';
    
    $sql = "SELECT b.*, u.full_name as owner_name, u.email as owner_email, u.phone as owner_phone 
            FROM books b 
            JOIN users u ON b.user_id = u.user_id 
            WHERE b.book_id IN ($placeholders) AND b.is_available = TRUE
            ORDER BY b.created_at DESC";
    
    $stmt = $conn->prepare($sql);
    
    // Bind parameters dynamically
    $types = str_repeat('i', count($_SESSION['wishlist']));
    $stmt->bind_param($types, ...$_SESSION['wishlist']);
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
    
    $stmt->close();
    
    // Remove unavailable books from wishlist
    $availableIds = array_column($books, 'book_id');
    $_SESSION['wishlist'] = array_intersect($_SESSION['wishlist'], $availableIds);
    
    return $books;
}

/**
 * Transfer wishlist from session to database (for future use)
 * This can be used in Module 5 or 6 to persist wishlist to database
 * @param int $userId User ID
 */
function saveWishlistToDatabase($userId) {
    // Placeholder for future database persistence
    // For now, wishlist is session-based only
    return true;
}

/**
 * Get wishlist statistics
 * @return array Statistics about wishlist
 */
function getWishlistStats() {
    $books = getWishlistBooks();
    
    $genres = [];
    foreach ($books as $book) {
        $genre = $book['genre'];
        if (!isset($genres[$genre])) {
            $genres[$genre] = 0;
        }
        $genres[$genre]++;
    }
    
    return [
        'total_books' => count($books),
        'genres' => $genres,
        'most_common_genre' => !empty($genres) ? array_keys($genres, max($genres))[0] : 'N/A'
    ];
}
?>