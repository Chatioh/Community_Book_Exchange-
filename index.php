<?php
// Include required files
require_once 'includes/functions.php';
require_once 'includes/db_config.php';
require_once 'includes/book_functions.php';
require_once 'includes/auth_functions.php';
require_once 'includes/wishlist_functions.php';

// Set page title
$pageTitle = 'Home';

// Get statistics from database
$stats = getBookStats();

// Get featured books (latest 6 books)
$conn = getDatabaseConnection();
$sql = "SELECT b.*, u.full_name as owner_name 
        FROM books b 
        JOIN users u ON b.user_id = u.user_id 
        WHERE b.is_available = TRUE 
        ORDER BY b.created_at DESC 
        LIMIT 6";
$result = $conn->query($sql);
$featuredBooks = [];
while ($row = $result->fetch_assoc()) {
    $featuredBooks[] = $row;
}

// Include header
include 'includes/header.php';
?>

    <main>
        <section class="hero">
            <div class="hero-background"></div>
            <div class="container">
                <div class="hero-content">
                    <!-- DYNAMIC GREETING BASED ON SERVER TIME -->
                    <div class="dynamic-greeting">
                        <p class="greeting-text">
                            <span class="greeting-icon"><?php echo getTimeIcon(); ?></span>
                            <span class="greeting-message"><?php echo getTimeBasedGreeting(); ?>!</span>
                        </p>
                        <p class="greeting-time">
                            <small>Server Time: <?php echo getCurrentDateTime(); ?></small>
                        </p>
                    </div>
                    
                    <h1 class="hero-title">
                        Share Stories,<br>
                        Build Community
                    </h1>
                    <p class="hero-subtitle">
                        Connect with fellow book lovers in your area. Exchange books, discover new reads, 
                        and give your favorite stories a second life.
                    </p>
                    <div class="hero-actions">
                        <a href="#featured" class="btn btn-primary">Explore Books</a>
                        <a href="contact.php" class="btn btn-outline">Join Our Community</a>
                    </div>
                </div>
                <div class="hero-stats">
                    <div class="stat-card">
                        <span class="stat-number"><?php echo number_format($stats['total_books']); ?></span>
                        <span class="stat-label">Books Available</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number"><?php echo number_format($stats['total_users']); ?></span>
                        <span class="stat-label">Active Members</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number"><?php echo number_format($stats['total_exchanges']); ?></span>
                        <span class="stat-label">Exchanges Made</span>
                    </div>
                </div>
            </div>
        </section>

        <section id="featured" class="featured-books">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Featured Books</h2>
                    <p class="section-subtitle">Discover what our community is reading and sharing this week</p>
                </div>
                
                <div class="books-grid">
                    <?php if (empty($featuredBooks)): ?>
                        <p class="no-books-message">No books available at the moment. Check back soon!</p>
                    <?php else: ?>
                        <?php foreach ($featuredBooks as $book): ?>
                        <article class="book-card">
                            <div class="book-image">
                                <div class="book-cover" style="background: <?php echo htmlspecialchars($book['cover_color']); ?>;">
                                    <span class="book-title-overlay"><?php echo htmlspecialchars($book['title']); ?></span>
                                </div>
                                <span class="book-condition <?php echo getConditionBadgeClass($book['book_condition']); ?>">
                                    <?php echo htmlspecialchars($book['book_condition']); ?>
                                </span>
                            </div>
                            <div class="book-info">
                                <h3 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                                <p class="book-author">by <?php echo htmlspecialchars($book['author']); ?></p>
                                <p class="book-description"><?php echo htmlspecialchars(substr($book['description'], 0, 120)) . '...'; ?></p>
                                <div class="book-meta">
                                    <span class="book-genre"><?php echo htmlspecialchars($book['genre']); ?></span>
                                    <span class="book-location">📍 <?php echo htmlspecialchars($book['location']); ?></span>
                                </div>
                                <div class="book-actions">
                                    <button class="btn-toggle-details" data-book-id="<?php echo $book['book_id']; ?>">View Details</button>
                                    <?php if (isLoggedIn()): ?>
                                        <?php if (isInWishlist($book['book_id'])): ?>
                                        <form action="process_wishlist.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="remove">
                                            <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                                            <button type="submit" class="btn-wishlist active" title="Remove from wishlist">
                                                💚 In Wishlist
                                            </button>
                                        </form>
                                        <?php else: ?>
                                        <form action="process_wishlist.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="add">
                                            <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                                            <button type="submit" class="btn-wishlist" title="Add to wishlist">
                                                🤍 Add to Wishlist
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="book-details hidden" id="details-<?php echo $book['book_id']; ?>">
                                    <p><strong>Condition:</strong> <?php echo htmlspecialchars($book['book_condition']); ?></p>
                                    <p><strong>Exchange Preference:</strong> <?php echo htmlspecialchars($book['exchange_preference']); ?></p>
                                    <p><strong>Owner:</strong> <?php echo htmlspecialchars($book['owner_name']); ?></p>
                                    <?php if (!empty($book['isbn'])): ?>
                                    <p><strong>ISBN:</strong> <?php echo htmlspecialchars($book['isbn']); ?></p>
                                    <?php endif; ?>
                                    <a href="#" class="btn btn-small">Request Exchange</a>
                                </div>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="cta-section">
                    <a href="listings.php" class="btn btn-primary">View All Books</a>
                </div>
            </div>
        </section>

        <section class="how-it-works">
            <div class="container">
                <h2 class="section-title">How It Works</h2>
                <div class="steps-grid">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h3 class="step-title">Create Account</h3>
                        <p class="step-description">Join our community of book lovers. It's free and takes less than a minute.</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h3 class="step-title">List Your Books</h3>
                        <p class="step-description">Add books you're willing to exchange. Include photos and condition details.</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h3 class="step-title">Find & Request</h3>
                        <p class="step-description">Browse available books and send exchange requests to other members.</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h3 class="step-title">Exchange & Enjoy</h3>
                        <p class="step-description">Meet up safely, swap books, and discover your next great read!</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

<?php
// Include footer
include 'includes/footer.php';
?>