<?php
/**
 * Wishlist Page - Module 3 Version (No Authentication Required)
 * Works with session only
 */

session_start();
require_once 'includes/functions.php';
require_once 'includes/db_config.php';
require_once 'includes/book_functions.php';
require_once 'includes/wishlist_functions_simple.php';

// Set page title
$pageTitle = 'My Wishlist';

// Initialize wishlist
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

// Get wishlist count
$wishlistCount = count($_SESSION['wishlist']);

// Get wishlist books from database
$wishlistBooks = [];
if (!empty($_SESSION['wishlist'])) {
    $conn = getDatabaseConnection();
    
    // Create placeholders for IN clause
    $placeholders = str_repeat('?,', count($_SESSION['wishlist']) - 1) . '?';
    
    $sql = "SELECT b.*, u.full_name as owner_name, u.email as owner_email 
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
    
    while ($row = $result->fetch_assoc()) {
        $wishlistBooks[] = $row;
    }
    
    $stmt->close();
}

// Calculate stats
$genres = [];
foreach ($wishlistBooks as $book) {
    $genre = $book['genre'];
    if (!isset($genres[$genre])) {
        $genres[$genre] = 0;
    }
    $genres[$genre]++;
}

$wishlistStats = [
    'total_books' => count($wishlistBooks),
    'genres' => $genres,
    'most_common_genre' => !empty($genres) ? array_keys($genres, max($genres))[0] : 'N/A'
];

// Handle success/error messages
$message = '';
$messageType = '';

if (isset($_SESSION['wishlist_message'])) {
    $message = $_SESSION['wishlist_message'];
    $messageType = $_SESSION['wishlist_message_type'];
    unset($_SESSION['wishlist_message']);
    unset($_SESSION['wishlist_message_type']);
}

include 'includes/header.php';
?>

    <main>
        <section class="page-header">
            <div class="container">
                <h1 class="page-title">My Wishlist</h1>
                <p class="page-subtitle">Books you want to read - <?php echo $wishlistCount; ?> item<?php echo $wishlistCount !== 1 ? 's' : ''; ?></p>
            </div>
        </section>

        <section class="wishlist-section">
            <div class="container">
                <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
                <?php endif; ?>

                <?php if ($wishlistCount > 0): ?>
                <div class="wishlist-actions">
                    <form action="process_wishlist.php" method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="clear">
                        <button type="submit" class="btn btn-outline" onclick="return confirm('Are you sure you want to clear your entire wishlist?')">
                            Clear All
                        </button>
                    </form>
                </div>

                <div class="wishlist-stats">
                    <div class="stat-box">
                        <h3><?php echo $wishlistStats['total_books']; ?></h3>
                        <p>Books Saved</p>
                    </div>
                    <div class="stat-box">
                        <h3><?php echo count($wishlistStats['genres']); ?></h3>
                        <p>Different Genres</p>
                    </div>
                    <div class="stat-box">
                        <h3><?php echo $wishlistStats['most_common_genre']; ?></h3>
                        <p>Top Genre</p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (empty($wishlistBooks)): ?>
                <div class="empty-wishlist">
                    <div class="empty-icon">📚</div>
                    <h2>Your wishlist is empty</h2>
                    <p>Start adding books you're interested in!</p>
                    <a href="listings.php" class="btn btn-primary">Browse Books</a>
                </div>
                <?php else: ?>
                <div class="books-grid">
                    <?php foreach ($wishlistBooks as $book): ?>
                    <article class="book-card">
                        <div class="book-image">
                            <div class="book-cover" style="background: <?php echo htmlspecialchars($book['cover_color']); ?>;">
                                <span class="book-title-overlay"><?php echo htmlspecialchars($book['title']); ?></span>
                            </div>
                            <span class="book-condition"><?php echo htmlspecialchars($book['book_condition']); ?></span>
                            <form action="process_wishlist.php" method="POST" class="wishlist-remove-form">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                                <button type="submit" class="btn-wishlist-remove" title="Remove from wishlist">
                                    ❌
                                </button>
                            </form>
                        </div>
                        <div class="book-info">
                            <h3 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                            <p class="book-author">by <?php echo htmlspecialchars($book['author']); ?></p>
                            <p class="book-description"><?php echo htmlspecialchars(substr($book['description'], 0, 120)) . '...'; ?></p>
                            <div class="book-meta">
                                <span class="book-genre"><?php echo htmlspecialchars($book['genre']); ?></span>
                                <span class="book-location">📍 <?php echo htmlspecialchars($book['location']); ?></span>
                            </div>
                            <button class="btn-toggle-details" data-book-id="<?php echo $book['book_id']; ?>">View Details</button>
                            <div class="book-details hidden" id="details-<?php echo $book['book_id']; ?>">
                                <p><strong>Condition:</strong> <?php echo htmlspecialchars($book['book_condition']); ?></p>
                                <p><strong>Exchange Preference:</strong> <?php echo htmlspecialchars($book['exchange_preference']); ?></p>
                                <p><strong>Owner:</strong> <?php echo htmlspecialchars($book['owner_name']); ?></p>
                                <?php if (!empty($book['owner_email'])): ?>
                                <p><strong>Contact:</strong> <?php echo htmlspecialchars($book['owner_email']); ?></p>
                                <?php endif; ?>
                                <a href="mailto:<?php echo htmlspecialchars($book['owner_email']); ?>?subject=Book Exchange Request: <?php echo urlencode($book['title']); ?>" class="btn btn-small">
                                    Contact Owner
                                </a>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

<?php include 'includes/footer.php'; ?>