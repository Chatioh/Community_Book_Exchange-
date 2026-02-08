<?php
/**
 * Book CRUD Action Handler
 * Module 3: Data Persistence & SQL Integration
 */

session_start();
require_once '../includes/db_config.php';
require_once '../includes/book_functions.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'create':
        handleCreate();
        break;
    case 'update':
        handleUpdate();
        break;
    case 'delete':
        handleDelete();
        break;
    default:
        $_SESSION['admin_message'] = 'Invalid action';
        $_SESSION['admin_message_type'] = 'error';
        header('Location: index.php');
        exit();
}

/**
 * Handle book creation
 */
function handleCreate() {
    // Validate required fields
    $required = ['user_id', 'title', 'author', 'genre', 'book_condition', 'description', 'location'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['admin_message'] = "Missing required field: $field";
            $_SESSION['admin_message_type'] = 'error';
            header('Location: index.php');
            exit();
        }
    }
    
    // Prepare book data
    $bookData = [
        'user_id' => intval($_POST['user_id']),
        'title' => trim($_POST['title']),
        'author' => trim($_POST['author']),
        'isbn' => !empty($_POST['isbn']) ? trim($_POST['isbn']) : '',
        'genre' => trim($_POST['genre']),
        'book_condition' => $_POST['book_condition'],
        'description' => trim($_POST['description']),
        'exchange_preference' => !empty($_POST['exchange_preference']) ? trim($_POST['exchange_preference']) : '',
        'location' => trim($_POST['location']),
        'cover_color' => !empty($_POST['cover_color']) ? trim($_POST['cover_color']) : 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        'is_available' => isset($_POST['is_available']) ? intval($_POST['is_available']) : 1
    ];
    
    // Create book
    $bookId = createBook($bookData);
    
    if ($bookId) {
        $_SESSION['admin_message'] = "Book '{$bookData['title']}' added successfully!";
        $_SESSION['admin_message_type'] = 'success';
    } else {
        $_SESSION['admin_message'] = 'Failed to add book. Please try again.';
        $_SESSION['admin_message_type'] = 'error';
    }
    
    header('Location: index.php');
    exit();
}

/**
 * Handle book update
 */
function handleUpdate() {
    // Validate required fields
    if (empty($_POST['book_id'])) {
        $_SESSION['admin_message'] = 'Book ID is required';
        $_SESSION['admin_message_type'] = 'error';
        header('Location: index.php');
        exit();
    }
    
    $bookId = intval($_POST['book_id']);
    
    // Prepare book data
    $bookData = [
        'title' => trim($_POST['title']),
        'author' => trim($_POST['author']),
        'isbn' => !empty($_POST['isbn']) ? trim($_POST['isbn']) : '',
        'genre' => trim($_POST['genre']),
        'book_condition' => $_POST['book_condition'],
        'description' => trim($_POST['description']),
        'exchange_preference' => !empty($_POST['exchange_preference']) ? trim($_POST['exchange_preference']) : '',
        'location' => trim($_POST['location']),
        'cover_color' => !empty($_POST['cover_color']) ? trim($_POST['cover_color']) : 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        'is_available' => isset($_POST['is_available']) ? intval($_POST['is_available']) : 1
    ];
    
    // Update book
    $success = updateBook($bookId, $bookData);
    
    if ($success) {
        $_SESSION['admin_message'] = "Book '{$bookData['title']}' updated successfully!";
        $_SESSION['admin_message_type'] = 'success';
    } else {
        $_SESSION['admin_message'] = 'Failed to update book. Please try again.';
        $_SESSION['admin_message_type'] = 'error';
    }
    
    header('Location: index.php');
    exit();
}

/**
 * Handle book deletion
 */
function handleDelete() {
    if (empty($_POST['book_id'])) {
        $_SESSION['admin_message'] = 'Book ID is required';
        $_SESSION['admin_message_type'] = 'error';
        header('Location: index.php');
        exit();
    }
    
    $bookId = intval($_POST['book_id']);
    
    // Get book title before deletion
    $book = getBookById($bookId);
    $bookTitle = $book ? $book['title'] : 'Unknown';
    
    // Delete book
    $success = deleteBook($bookId);
    
    if ($success) {
        $_SESSION['admin_message'] = "Book '$bookTitle' deleted successfully!";
        $_SESSION['admin_message_type'] = 'success';
    } else {
        $_SESSION['admin_message'] = 'Failed to delete book. Please try again.';
        $_SESSION['admin_message_type'] = 'error';
    }
    
    header('Location: index.php');
    exit();
}
?>
