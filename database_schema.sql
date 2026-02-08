-- ============================================
-- Community Book Exchange Database Schema
-- Module 3: Data Persistence & SQL Integration
-- ============================================

-- Create database
CREATE DATABASE IF NOT EXISTS book_exchange;
USE book_exchange;

-- ============================================
-- Table: users
-- Stores user account information
-- ============================================
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    location VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    INDEX idx_email (email),
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: books
-- Stores book listings for exchange
-- ============================================
CREATE TABLE books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(150) NOT NULL,
    isbn VARCHAR(13),
    genre VARCHAR(50) NOT NULL,
    book_condition ENUM('Excellent', 'Very Good', 'Good', 'Fair') NOT NULL,
    description TEXT,
    exchange_preference VARCHAR(255),
    location VARCHAR(100),
    cover_color VARCHAR(50) DEFAULT '#667eea',
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_genre (genre),
    INDEX idx_condition (book_condition),
    INDEX idx_location (location),
    INDEX idx_available (is_available)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Sample Data: Users
-- ============================================
INSERT INTO users (username, email, password_hash, full_name, phone, location) VALUES
('sarahm', 'sarah.mitchell@email.com', '$2y$10$abcdefghijklmnopqrstuvwxyz1234567890', 'Sarah Mitchell', '(555) 123-4567', 'Downtown'),
('jamesk', 'james.kim@email.com', '$2y$10$abcdefghijklmnopqrstuvwxyz1234567890', 'James Kim', '(555) 234-5678', 'Eastside'),
('marial', 'maria.lopez@email.com', '$2y$10$abcdefghijklmnopqrstuvwxyz1234567890', 'Maria Lopez', '(555) 345-6789', 'Westwood'),
('davidr', 'david.roberts@email.com', '$2y$10$abcdefghijklmnopqrstuvwxyz1234567890', 'David Roberts', '(555) 456-7890', 'Riverside'),
('emmat', 'emma.taylor@email.com', '$2y$10$abcdefghijklmnopqrstuvwxyz1234567890', 'Emma Taylor', '(555) 567-8901', 'Central'),
('lisap', 'lisa.parker@email.com', '$2y$10$abcdefghijklmnopqrstuvwxyz1234567890', 'Lisa Parker', '(555) 678-9012', 'Northside');

-- ============================================
-- Sample Data: Books
-- ============================================
INSERT INTO books (user_id, title, author, isbn, genre, book_condition, description, exchange_preference, location, cover_color, is_available) VALUES
(1, 'The Midnight Library', 'Matt Haig', '9780525559474', 'Fiction', 'Excellent', 'A dazzling novel about all the choices that go into a life well lived.', 'Any contemporary fiction', 'Downtown', 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)', TRUE),
(2, 'Educated', 'Tara Westover', '9780399590504', 'Memoir', 'Good', 'A memoir about a young woman who leaves her survivalist family.', 'Biography or history', 'Eastside', 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)', TRUE),
(3, 'Atomic Habits', 'James Clear', '9780735211292', 'Self-Help', 'Very Good', 'Practical strategies for building good habits and breaking bad ones.', 'Business or productivity books', 'Westwood', 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)', TRUE),
(4, 'Where the Crawdads Sing', 'Delia Owens', '9780735219090', 'Mystery', 'Excellent', 'A coming-of-age mystery set in the marshlands of North Carolina.', 'Mystery or thriller', 'Riverside', 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)', TRUE),
(5, 'The Song of Achilles', 'Madeline Miller', '9780062060624', 'Historical Fiction', 'Good', 'A reimagining of Homer\'s Iliad through the eyes of Patroclus.', 'Fantasy or mythology', 'Central', 'linear-gradient(135deg, #30cfd0 0%, #330867 100%)', TRUE),
(6, 'The Vanishing Half', 'Brit Bennett', '9780525536291', 'Literary Fiction', 'Very Good', 'A multi-generational story about identity, family, and race.', 'Contemporary literary fiction', 'Northside', 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)', TRUE),
(1, 'Project Hail Mary', 'Andy Weir', '9780593135204', 'Science Fiction', 'Excellent', 'A lone astronaut must save the earth from disaster in this gripping sci-fi tale.', 'Science fiction or thriller', 'Downtown', 'linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)', TRUE),
(2, 'The Silent Patient', 'Alex Michaelides', '9781250301697', 'Thriller', 'Good', 'A woman\'s act of violence against her husband and her refusal to speak.', 'Psychological thriller', 'Eastside', 'linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%)', TRUE),
(4, 'Circe', 'Madeline Miller', '9780316556347', 'Fantasy', 'Very Good', 'The story of the goddess Circe and her journey from exile to power.', 'Mythology retellings', 'Riverside', 'linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%)', TRUE),
(3, 'Becoming', 'Michelle Obama', '9781524763138', 'Biography', 'Excellent', 'Michelle Obama\'s memoir chronicling her journey from Chicago to the White House.', 'Biography or memoir', 'Westwood', 'linear-gradient(135deg, #f8b500 0%, #fceabb 100%)', TRUE),
(5, 'The Seven Husbands of Evelyn Hugo', 'Taylor Jenkins Reid', '9781501161933', 'Historical Fiction', 'Very Good', 'An aging film icon tells the story of her glamorous and scandalous life.', 'Historical fiction or romance', 'Central', 'linear-gradient(135deg, #ff6b6b 0%, #feca57 100%)', TRUE),
(6, 'Sapiens', 'Yuval Noah Harari', '9780062316097', 'Non-Fiction', 'Good', 'A brief history of humankind from the Stone Age to the modern age.', 'Non-fiction or history', 'Northside', 'linear-gradient(135deg, #48c6ef 0%, #6f86d6 100%)', TRUE);

-- ============================================
-- Admin User (for Module 4)
-- Username: admin, Password: admin123
-- ============================================
INSERT INTO users (username, email, password_hash, full_name, phone, location) VALUES
('admin', 'admin@bookexchange.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', '(555) 000-0000', 'Main Office');

-- ============================================
-- Useful Queries for Testing
-- ============================================

-- Get all available books with owner information
-- SELECT b.*, u.full_name, u.location as user_location 
-- FROM books b 
-- JOIN users u ON b.user_id = u.user_id 
-- WHERE b.is_available = TRUE 
-- ORDER BY b.created_at DESC;

-- Count books by genre
-- SELECT genre, COUNT(*) as book_count 
-- FROM books 
-- WHERE is_available = TRUE 
-- GROUP BY genre 
-- ORDER BY book_count DESC;

-- Get books by specific user
-- SELECT * FROM books WHERE user_id = 1 ORDER BY created_at DESC;

-- Search books by title or author
-- SELECT * FROM books 
-- WHERE (title LIKE '%search_term%' OR author LIKE '%search_term%') 
-- AND is_available = TRUE;
