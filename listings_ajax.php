<?php
// Include required files
require_once 'includes/functions.php';
require_once 'includes/db_config.php';
require_once 'includes/book_functions.php';
require_once 'includes/auth_functions.php';
require_once 'includes/wishlist_functions.php';

// Set page title
$pageTitle = 'Browse Books';

// Get available genres and locations for dropdowns
$availableGenres = getGenres();
$availableLocations = getLocations();

// Get wishlist IDs and Login status for JavaScript
$wishlistIds = getWishlistIds();
$isLoggedIn = isLoggedIn();

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
                    <div class="filter-form">
                        <div class="search-box">
                            <input type="text" 
                                   id="searchInput" 
                                   placeholder="Search by title, author, or description..." 
                                   class="search-input"
                                   autocomplete="off">
                            <button type="button" class="search-button">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="2"/>
                                    <path d="M12.5 12.5L17 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>
                        <div class="filter-controls">
                            <select id="genreFilter" class="filter-select">
                                <option value="">All Genres</option>
                                <?php foreach ($availableGenres as $genre): ?>
                                    <option value="<?php echo htmlspecialchars($genre); ?>">
                                        <?php echo htmlspecialchars($genre); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            
                            <select id="conditionFilter" class="filter-select">
                                <option value="">All Conditions</option>
                                <option value="Excellent">Excellent</option>
                                <option value="Very Good">Very Good</option>
                                <option value="Good">Good</option>
                                <option value="Fair">Fair</option>
                            </select>
                            
                            <select id="locationFilter" class="filter-select">
                                <option value="">All Locations</option>
                                <?php foreach ($availableLocations as $location): ?>
                                    <option value="<?php echo htmlspecialchars($location); ?>">
                                        <?php echo htmlspecialchars($location); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="results-header">
                    <p id="resultsCount" class="results-count">Loading books...</p>
                    <div id="loadingIndicator" class="loading-indicator" style="display: none;">
                        <div class="spinner"></div>
                        <span>Searching...</span>
                    </div>
                </div>

                <div class="books-grid" id="booksGrid">
                    <div class="initial-loading">
                        <div class="spinner-large"></div>
                        <p>Loading books...</p>
                    </div>
                </div>

                <div class="load-more-container">
                    <button id="loadMoreBtn" class="btn btn-primary" style="display: none;">
                        Load More Books
                    </button>
                </div>
            </div>
        </section>
    </main>

    <script>
        window.wishlistIds = <?php echo json_encode($wishlistIds); ?>;
        window.isUserLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
    </script>
    <script src="js/ajax-search.js"></script>

<?php
// Include footer
include 'includes/footer.php';
?>