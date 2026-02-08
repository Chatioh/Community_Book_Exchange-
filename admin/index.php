<?php
/**
 * Admin Panel - Book Management
 * Module 3: Data Persistence & SQL Integration
 */

require_once '../includes/db_config.php';
require_once '../includes/book_functions.php';

// Simple authentication (will be improved in Module 4)
session_start();

// For now, allow access without authentication (Module 4 will add proper auth)
// In production, you would check if user is logged in and is admin

// Get all books (including unavailable ones for admin)
$conn = getDatabaseConnection();
$sql = "SELECT b.*, u.full_name as owner_name, u.email as owner_email 
        FROM books b 
        JOIN users u ON b.user_id = u.user_id 
        ORDER BY b.created_at DESC";
$result = $conn->query($sql);
$allBooks = [];
while ($row = $result->fetch_assoc()) {
    $allBooks[] = $row;
}

// Get all users for dropdown
$sql = "SELECT user_id, full_name, email FROM users WHERE is_active = TRUE ORDER BY full_name";
$result = $conn->query($sql);
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

// Handle success/error messages
$message = '';
$messageType = '';

if (isset($_SESSION['admin_message'])) {
    $message = $_SESSION['admin_message'];
    $messageType = $_SESSION['admin_message_type'];
    unset($_SESSION['admin_message']);
    unset($_SESSION['admin_message_type']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Book Exchange</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="admin-styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body class="admin-body">
    <div class="admin-container">
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <h2>📚 Admin Panel</h2>
            </div>
            <nav class="admin-nav">
                <a href="index.php" class="admin-nav-link active">
                    <span class="nav-icon">📖</span>
                    Manage Books
                </a>
                <a href="../index.php" class="admin-nav-link">
                    <span class="nav-icon">🏠</span>
                    View Site
                </a>
                <a href="#" class="admin-nav-link" onclick="alert('Coming in Module 4');">
                    <span class="nav-icon">👥</span>
                    Manage Users
                </a>
                <a href="#" class="admin-nav-link" onclick="alert('Coming in Module 4');">
                    <span class="nav-icon">🚪</span>
                    Logout
                </a>
            </nav>
        </aside>

        <main class="admin-main">
            <header class="admin-header">
                <h1>Book Management</h1>
                <button class="btn btn-primary" onclick="openAddModal()">
                    <span>➕</span> Add New Book
                </button>
            </header>

            <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?>" role="alert">
                <?php echo htmlspecialchars($message); ?>
            </div>
            <?php endif; ?>

            <div class="admin-stats">
                <div class="stat-box">
                    <h3><?php echo count($allBooks); ?></h3>
                    <p>Total Books</p>
                </div>
                <div class="stat-box">
                    <h3><?php echo count(array_filter($allBooks, function($b) { return $b['is_available']; })); ?></h3>
                    <p>Available</p>
                </div>
                <div class="stat-box">
                    <h3><?php echo count($users); ?></h3>
                    <p>Total Users</p>
                </div>
            </div>

            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Genre</th>
                            <th>Condition</th>
                            <th>Owner</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allBooks as $book): ?>
                        <tr>
                            <td><?php echo $book['book_id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($book['title']); ?></strong></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td><span class="badge"><?php echo htmlspecialchars($book['genre']); ?></span></td>
                            <td><span class="badge badge-condition"><?php echo htmlspecialchars($book['book_condition']); ?></span></td>
                            <td><?php echo htmlspecialchars($book['owner_name']); ?></td>
                            <td>
                                <span class="status-badge <?php echo $book['is_available'] ? 'status-available' : 'status-unavailable'; ?>">
                                    <?php echo $book['is_available'] ? 'Available' : 'Unavailable'; ?>
                                </span>
                            </td>
                            <td class="actions">
                                <button class="btn-icon btn-edit" onclick='editBook(<?php echo json_encode($book); ?>)' title="Edit">
                                    ✏️
                                </button>
                                <button class="btn-icon btn-delete" onclick="deleteBookConfirm(<?php echo $book['book_id']; ?>, '<?php echo htmlspecialchars($book['title'], ENT_QUOTES); ?>')" title="Delete">
                                    🗑️
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Add/Edit Book Modal -->
    <div id="bookModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Book</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <form id="bookForm" action="book_action.php" method="POST">
                <input type="hidden" name="action" id="formAction" value="create">
                <input type="hidden" name="book_id" id="bookId">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="user_id">Book Owner *</label>
                        <select name="user_id" id="user_id" class="form-input" required>
                            <option value="">Select Owner</option>
                            <?php foreach ($users as $user): ?>
                            <option value="<?php echo $user['user_id']; ?>">
                                <?php echo htmlspecialchars($user['full_name']); ?> (<?php echo htmlspecialchars($user['email']); ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="title">Book Title *</label>
                        <input type="text" name="title" id="title" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="author">Author *</label>
                        <input type="text" name="author" id="author" class="form-input" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="isbn">ISBN</label>
                        <input type="text" name="isbn" id="isbn" class="form-input" maxlength="13">
                    </div>
                    <div class="form-group">
                        <label for="genre">Genre *</label>
                        <input type="text" name="genre" id="genre" class="form-input" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="book_condition">Condition *</label>
                        <select name="book_condition" id="book_condition" class="form-input" required>
                            <option value="Excellent">Excellent</option>
                            <option value="Very Good">Very Good</option>
                            <option value="Good">Good</option>
                            <option value="Fair">Fair</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="location">Location *</label>
                        <input type="text" name="location" id="location" class="form-input" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea name="description" id="description" class="form-input" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label for="exchange_preference">Exchange Preference</label>
                    <input type="text" name="exchange_preference" id="exchange_preference" class="form-input">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="cover_color">Cover Gradient</label>
                        <input type="text" name="cover_color" id="cover_color" class="form-input" 
                               value="linear-gradient(135deg, #667eea 0%, #764ba2 100%)">
                    </div>
                    <div class="form-group">
                        <label for="is_available">Status</label>
                        <select name="is_available" id="is_available" class="form-input">
                            <option value="1">Available</option>
                            <option value="0">Unavailable</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Add Book</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content modal-small">
            <div class="modal-header">
                <h2>Confirm Delete</h2>
                <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this book?</p>
                <p><strong id="deleteBookTitle"></strong></p>
                <p class="text-muted">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeDeleteModal()">Cancel</button>
                <form action="book_action.php" method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="book_id" id="deleteBookId">
                    <button type="submit" class="btn btn-danger">Delete Book</button>
                </form>
            </div>
        </div>
    </div>

    <script src="admin-script.js"></script>
</body>
</html>
