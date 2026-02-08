/**
 * Admin Panel JavaScript
 * Module 3: Data Persistence & SQL Integration
 */

// Modal elements
const bookModal = document.getElementById('bookModal');
const deleteModal = document.getElementById('deleteModal');
const bookForm = document.getElementById('bookForm');

/**
 * Open Add Book Modal
 */
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add New Book';
    document.getElementById('formAction').value = 'create';
    document.getElementById('submitBtn').textContent = 'Add Book';
    bookForm.reset();
    document.getElementById('bookId').value = '';
    bookModal.classList.add('active');
}

/**
 * Open Edit Book Modal
 * @param {Object} book - Book data
 */
function editBook(book) {
    document.getElementById('modalTitle').textContent = 'Edit Book';
    document.getElementById('formAction').value = 'update';
    document.getElementById('submitBtn').textContent = 'Update Book';
    
    // Populate form fields
    document.getElementById('bookId').value = book.book_id;
    document.getElementById('user_id').value = book.user_id;
    document.getElementById('title').value = book.title;
    document.getElementById('author').value = book.author;
    document.getElementById('isbn').value = book.isbn || '';
    document.getElementById('genre').value = book.genre;
    document.getElementById('book_condition').value = book.book_condition;
    document.getElementById('location').value = book.location;
    document.getElementById('description').value = book.description;
    document.getElementById('exchange_preference').value = book.exchange_preference || '';
    document.getElementById('cover_color').value = book.cover_color;
    document.getElementById('is_available').value = book.is_available ? '1' : '0';
    
    bookModal.classList.add('active');
}

/**
 * Close Add/Edit Modal
 */
function closeModal() {
    bookModal.classList.remove('active');
    bookForm.reset();
}

/**
 * Open Delete Confirmation Modal
 * @param {number} bookId - Book ID
 * @param {string} bookTitle - Book title
 */
function deleteBookConfirm(bookId, bookTitle) {
    document.getElementById('deleteBookId').value = bookId;
    document.getElementById('deleteBookTitle').textContent = bookTitle;
    deleteModal.classList.add('active');
}

/**
 * Close Delete Modal
 */
function closeDeleteModal() {
    deleteModal.classList.remove('active');
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target === bookModal) {
        closeModal();
    }
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
}

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
        closeDeleteModal();
    }
});

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.3s';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});