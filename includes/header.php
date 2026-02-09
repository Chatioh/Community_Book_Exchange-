<?php
// Start session at the very beginning
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include auth and wishlist functions with full path
if (!function_exists('isLoggedIn')) {
    require_once __DIR__ . '/auth_functions.php';
}
if (!function_exists('initWishlist')) {
    require_once __DIR__ . '/wishlist_functions.php';
}

// Check if user is logged in
$isLoggedIn = isLoggedIn();
$currentUser = $isLoggedIn ? getCurrentUser() : null;
$wishlistCount = $isLoggedIn ? getWishlistCount() : 0;

// Handle success/error messages
$globalMessage = '';
$globalMessageType = '';

if (isset($_SESSION['success_message'])) {
    $globalMessage = $_SESSION['success_message'];
    $globalMessageType = 'success';
    unset($_SESSION['success_message']);
} elseif (isset($_SESSION['error_message'])) {
    $globalMessage = $_SESSION['error_message'];
    $globalMessageType = 'error';
    unset($_SESSION['error_message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : ''; ?>Community Book Exchange</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/php-enhancements.css">
    <link rel="stylesheet" href="css/auth-styles.css">
    <link rel="stylesheet" href="css/ajax-styles.css">
    <!-- <script src="js/ajax-search.js" defer></script> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php if (!empty($globalMessage)): ?>
    <div class="global-message alert-<?php echo $globalMessageType; ?>">
        <div class="container">
            <?php echo htmlspecialchars($globalMessage); ?>
        </div>
    </div>
    <?php endif; ?>

    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="index.php" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 10C8 8.89543 8.89543 8 10 8H18C19.1046 8 20 8.89543 20 10V30C20 31.1046 19.1046 32 18 32H10C8.89543 32 8 31.1046 8 30V10Z" fill="#8B4513" opacity="0.8"/>
                            <path d="M20 10C20 8.89543 20.8954 8 22 8H30C31.1046 8 32 8.89543 32 10V30C32 31.1046 31.1046 32 30 32H22C20.8954 32 20 31.1046 20 30V10Z" fill="#D2691E" opacity="0.8"/>
                            <rect x="12" y="12" width="4" height="1" fill="white" opacity="0.6"/>
                            <rect x="12" y="16" width="4" height="1" fill="white" opacity="0.6"/>
                            <rect x="24" y="12" width="4" height="1" fill="white" opacity="0.6"/>
                            <rect x="24" y="16" width="4" height="1" fill="white" opacity="0.6"/>
                        </svg>
                        <span class="logo-text">BookExchange</span>
                    </a>
                </div>
                <nav class="main-nav">
                    <ul class="nav-list">
                        <li><a href="index.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">Home</a></li>
                        <li><a href="listings.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'listings.php') ? 'active' : ''; ?>">Browse Books</a></li>
                        <?php if ($isLoggedIn): ?>
                        <li>
                            <a href="wishlist.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'wishlist.php') ? 'active' : ''; ?>">
                                <span class="wishlist-icon">💚</span> Wishlist
                                <?php if ($wishlistCount > 0): ?>
                                <span class="wishlist-badge"><?php echo $wishlistCount; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <?php endif; ?>
                        <li><a href="contact.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'active' : ''; ?>">Contact</a></li>
                        
                        <?php if ($isLoggedIn): ?>
                        <li class="nav-user-menu">
                            <span class="nav-username">👤 <?php echo htmlspecialchars($currentUser['full_name']); ?></span>
                            <ul class="user-dropdown">
                                <li><a href="profile.php">My Profile</a></li>
                                <li><a href="my-books.php">My Books</a></li>
                                <?php if (isAdmin()): ?>
                                <li><a href="admin/index.php">Admin Panel</a></li>
                                <?php endif; ?>
                                <li><a href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                        <?php else: ?>
                        <li><a href="login.php" class="nav-link btn-secondary">Sign In</a></li>
                        <?php endif; ?>
                    </ul>
                    <button class="mobile-menu-toggle" aria-label="Toggle menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </nav>
            </div>
        </div>
    </header>