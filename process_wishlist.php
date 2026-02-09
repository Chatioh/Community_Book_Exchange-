<?php
/**
 * Simple Wishlist Processor for Module 3 (No Authentication)
 */

session_start();
require_once 'includes/wishlist_functions_simple.php';

// Initialize wishlist
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: wishlist.php');
    exit();
}

$action = isset($_POST['action']) ? $_POST['action'] : '';
$bookId = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;

switch ($action) {
    case 'add':
        if ($bookId > 0) {
            if (!in_array($bookId, $_SESSION['wishlist'])) {
                $_SESSION['wishlist'][] = $bookId;
                $_SESSION['wishlist_message'] = 'Book added to your wishlist!';
                $_SESSION['wishlist_message_type'] = 'success';
            } else {
                $_SESSION['wishlist_message'] = 'This book is already in your wishlist.';
                $_SESSION['wishlist_message_type'] = 'error';
            }
        }
        // Redirect back to referring page or listings
        $redirectUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'listings.php';
        header('Location: ' . $redirectUrl);
        exit();
        
    case 'remove':
        if ($bookId > 0) {
            $key = array_search($bookId, $_SESSION['wishlist']);
            if ($key !== false) {
                unset($_SESSION['wishlist'][$key]);
                $_SESSION['wishlist'] = array_values($_SESSION['wishlist']);
                $_SESSION['wishlist_message'] = 'Book removed from your wishlist.';
                $_SESSION['wishlist_message_type'] = 'success';
            }
        }
        header('Location: wishlist.php');
        exit();
        
    case 'clear':
        clearWishlist();
        $_SESSION['wishlist_message'] = 'Your wishlist has been cleared.';
        $_SESSION['wishlist_message_type'] = 'success';
        header('Location: wishlist.php');
        exit();
        
    default:
        header('Location: wishlist.php');
        exit();
}
?>