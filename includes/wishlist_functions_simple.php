<?php
/**
 * Simple Wishlist Functions for Module 3
 * Session-based, no authentication required
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
 */
function addToWishlist($bookId) {
    initWishlist();
    if (!in_array($bookId, $_SESSION['wishlist'])) {
        $_SESSION['wishlist'][] = $bookId;
        return true;
    }
    return false;
}

/**
 * Remove book from wishlist
 */
function removeFromWishlist($bookId) {
    initWishlist();
    $key = array_search($bookId, $_SESSION['wishlist']);
    if ($key !== false) {
        unset($_SESSION['wishlist'][$key]);
        $_SESSION['wishlist'] = array_values($_SESSION['wishlist']);
        return true;
    }
    return false;
}

/**
 * Check if book is in wishlist
 */
function isInWishlist($bookId) {
    initWishlist();
    return in_array($bookId, $_SESSION['wishlist']);
}

/**
 * Get wishlist IDs
 */
function getWishlistIds() {
    initWishlist();
    return $_SESSION['wishlist'];
}

/**
 * Get wishlist count
 */
function getWishlistCount() {
    initWishlist();
    return count($_SESSION['wishlist']);
}

/**
 * Clear wishlist
 */
function clearWishlist() {
    $_SESSION['wishlist'] = [];
}
?>
