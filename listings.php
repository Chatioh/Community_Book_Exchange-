<?php
// Include required files
require_once 'includes/functions.php';
require_once 'includes/db_config.php';
require_once 'includes/book_functions.php';
require_once 'includes/auth_functions.php';
require_once 'includes/wishlist_functions.php';

// Set page title
$pageTitle = 'Browse Books';

// Get filter parameters
$genreFilter = isset($_GET['genre']) ? $_GET['genre'] : '';
$conditionFilter = isset($_GET['condition']) ? $_GET['condition'] : '';
$locationFilter = isset($_GET['location']) ? $_GET['location'] : '';
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Get all books with filters
$books = getAllBooks($genreFilter, $conditionFilter, $locationFilter, $searchQuery);

// Get available genres and locations for dropdowns
$availableGenres = getGenres();
$availableLocations = getLocations();

// Include header
include 'includes/header.php';
?>

    <main>
        <section class="page-header">
            <div class="container">
                <h1 class="page-title">Browse Available Books</h1>
                <p class="page-subtitle">Discover your next great read from our community collection</p>
            </div>
        </section>

        <section class="listings-section">
            <div class="container">
                <div class="search-filter-bar">
                    <form method="GET" action="listings.php" class="filter-form">
                        <div class="search-box">
                            <input type="text" 
                                   id="searchInput" 
                                   name="search" 
                                   placeholder="Search by title, author, or genre..." 
                                   class="search-input"
                                   value="<?php echo htmlspecialchars($searchQuery); ?>">
                            <button type="submit" class="search-button">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="2"/>
                                    <path d="M12.5 12.5L17 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>
                        <div class="filter-controls">
                            <select id="genreFilter" name="genre" class="filter-select" onchange="this.form.submit()">
                                <option value="">All Genres</option>
                                <?php foreach ($availableGenres as $genre): ?>
                                    <option value="<?php echo htmlspecialchars($genre); ?>" 
                                            <?php echo ($genreFilter === $genre) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($genre); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            
                            <select id="conditionFilter" name="condition" class="filter-select" onchange="this.form.submit()">
                                <option value="">All Conditions</option>
                                <option value="Excellent" <?php echo ($conditionFilter === 'Excellent') ? 'selected' : ''; ?>>Excellent</option>
                                <option value="Very Good" <?php echo ($conditionFilter === 'Very Good') ? 'selected' : ''; ?>>Very Good</option>
                                <option value="Good" <?php echo ($conditionFilter === 'Good') ? 'selected' : ''; ?>>Good</option>
                                <option value="Fair" <?php echo ($conditionFilter === 'Fair') ? 'selected' : ''; ?>>Fair</option>
                            </select>
                            
                            <select id="locationFilter" name="location" class="filter-select" onchange="this.form.submit()">
                                <option value="">All Locations</option>
                                <?php foreach ($availableLocations as $location): ?>
                                    <option value="<?php echo htmlspecialchars($location); ?>" 
                                            <?php echo ($locationFilter === $location) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($location); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            
                            <?php if (!empty($genreFilter) || !empty($conditionFilter) || !empty($locationFilter) || !empty($searchQuery)): ?>
                                <a href="listings.php" class="btn btn-small btn-outline">Clear Filters</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <div class="results-count">
                    <p><?php echo count($books); ?> book<?php echo count($books) !== 1 ? 's' : ''; ?> found</p>
                </div>

                <div class="books-grid" id="booksGrid">
                    <?php if (empty($books)): ?>
                        <div class="no-results">
                            <p>No books found matching your search criteria. Try adjusting your filters.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($books as $book): ?>
                        <article class="book-card" 
                                 data-genre="<?php echo strtolower($book['genre']); ?>" 
                                 data-condition="<?php echo strtolower(str_replace(' ', '-', $book['book_condition'])); ?>" 
                                 data-location="<?php echo strtolower($book['location']); ?>">
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
            </div>
        </section>
    </main>

<?php
// Include footer
include 'includes/footer.php';
?>