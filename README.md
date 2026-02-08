# MODULE 3: DATA PERSISTENCE & SQL INTEGRATION

## 📝 WHAT'S NEW IN MODULE 3

This module adds database integration with MySQL, enabling persistent data storage and a full CRUD (Create, Read, Update, Delete) admin panel for managing books.

### 🤖 AI Disclosure & Academic Integrity
* **Tools Used:** GitHub Copilot for code completion; Gemini and claude for logic troubleshooting.
* **Application:** AI was used to assist with regular expression patterns for form validation and to generate boilerplate structures for the PHP include files.
* **Human Oversight:** All AI-generated logic was reviewed, refactored for security (XSS prevention), and manually tested to ensure it meets the module requirements.

### ✅ Features Implemented

1. **MySQL Database Design**
   - `users` table: Stores user account information
   - `books` table: Stores book listings with foreign key to users
   - Normalized schema with appropriate data types
   - Indexes for performance optimization

2. **Database Connection with MySQLi**
   - Centralized database configuration
   - Connection pooling for efficiency
   - Error handling for database operations
   - UTF-8 character set support

3. **Dynamic Book Listings**
   - Homepage displays latest 6 books from database
   - Listings page shows all available books
   - Real-time statistics (total books, users, exchanges)
   - Data pulled directly from MySQL

4. **Search & Filter Functionality**
   - Server-side filtering by genre, condition, location
   - Search across title and author fields
   - Dynamic dropdowns populated from database
   - "Clear Filters" button

5. **Admin Panel (CRUD Operations)**
   - **Create**: Add new books with full details
   - **Read**: View all books in sortable table
   - **Update**: Edit existing book information
   - **Delete**: Remove books with confirmation
   - Clean, professional admin interface
   - Real-time statistics dashboard

---

## 📁 FILE STRUCTURE

```
module3-database/
├── index.php                      # Homepage with DB-driven content
├── listings.php                   # Book listings with filters
├── contact.php                    # Contact/About page
├── process_contact.php            # Form processing
├── database_schema.sql            # Complete database schema
├── includes/
│   ├── header.php                # Reusable header
│   ├── footer.php                # Reusable footer
│   ├── functions.php             # Helper functions (Module 2)
│   ├── db_config.php             # Database configuration
│   └── book_functions.php        # Book CRUD functions
├── admin/
│   ├── index.php                 # Admin panel main page
│   ├── book_action.php           # CRUD action handler
│   ├── admin-styles.css          # Admin panel styles
│   └── admin-script.js           # Admin panel JavaScript
├── css/
│   ├── styles.css                # Main styles
│   └── php-enhancements.css      # Module 2 enhancements
└── js/
    └── script.js                 # Main JavaScript
```

---

## 🗄️ DATABASE SCHEMA

### Users Table
```sql
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
    is_active BOOLEAN DEFAULT TRUE
);
```

### Books Table
```sql
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
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);
```

**Sample Data Included:**
- 6 sample users
- 12 sample books across various genres
- 1 admin user (for Module 4)

---

## 🚀 SETUP INSTRUCTIONS

### Step 1: Import Database

1. Open phpMyAdmin or MySQL command line
2. Create a new database called `book_exchange`
3. Import the `database_schema.sql` file:
   ```bash
   mysql -u root -p book_exchange < database_schema.sql
   ```
   OR use phpMyAdmin's Import feature

### Step 2: Configure Database Connection

1. Open `includes/db_config.php`
2. Update credentials if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');      // Your MySQL username
   define('DB_PASS', '');           // Your MySQL password
   define('DB_NAME', 'book_exchange');
   ```

### Step 3: Run the Application

**Using PHP Built-in Server:**
```bash
cd module3-database
php -S localhost:8000
```
Then open: `http://localhost:8000/index.php`

**Using XAMPP/WAMP:**
1. Copy folder to `htdocs` (XAMPP) or `www` (WAMP)
2. Start Apache and MySQL
3. Open: `http://localhost/Community Book Exchange Module3/index.php`

### Step 4: Access Admin Panel

Navigate to: `http://localhost/Community Book Exchange Module3/admin/index.php`

(Authentication will be added in Module 4)

---

## 🧪 TESTING INSTRUCTIONS

### Test 1: Database Connection
1. Open `index.php`
2. Should see book counts from database in statistics
3. Should see 6 featured books displayed
4. No database errors should appear

### Test 2: Dynamic Book Listings
1. Go to `listings.php`
2. Verify all books are displayed from database
3. Each book should show: title, author, genre, condition, location, description

### Test 3: Search & Filter
1. On listings page, search for "Midnight"
2. Should show only "The Midnight Library"
3. Filter by Genre "Fiction"
4. Should show only fiction books
5. Click "Clear Filters" - all books return

### Test 4: Admin Panel - CREATE
1. Go to `admin/index.php`
2. Click "Add New Book" button
3. Fill out form with:
   - Owner: Select a user
   - Title: Test Book
   - Author: Test Author
   - Genre: Test Genre
   - Condition: Good
   - Location: Test Location
   - Description: This is a test book
4. Click "Add Book"
5. Should see success message
6. New book should appear in table

### Test 5: Admin Panel - UPDATE
1. Find the test book you just created
2. Click edit icon (✏️)
3. Change title to "Updated Test Book"
4. Click "Update Book"
5. Should see success message
6. Book title should be updated in table

### Test 6: Admin Panel - DELETE
1. Find the test book
2. Click delete icon (🗑️)
3. Confirm deletion
4. Should see success message
5. Book should be removed from table

### Test 7: Statistics
1. Check admin panel stats at top
2. Should show accurate counts for:
   - Total Books
   - Available Books
   - Total Users


## 🔧 KEY FUNCTIONS

### Database Functions (book_functions.php)

1. **getAllBooks($genre, $condition, $location, $search)**
   - Returns filtered array of books with owner info
   - Uses prepared statements for security
   - Supports multiple filter combinations

2. **getBookById($bookId)**
   - Returns single book with full details
   - Joins with users table for owner info

3. **createBook($bookData)**
   - Inserts new book into database
   - Returns new book ID or false on failure

4. **updateBook($bookId, $bookData)**
   - Updates existing book
   - Returns true/false for success

5. **deleteBook($bookId)**
   - Removes book from database
   - Returns true/false for success

6. **getBookStats()**
   - Returns total books, users, exchanges
   - Used for dashboard statistics

7. **getGenres()**
   - Returns array of unique genres from books
   - Used to populate filter dropdown

8. **getLocations()**
   - Returns array of unique locations
   - Used to populate filter dropdown

---

## 🔐 SECURITY FEATURES

### SQL Injection Prevention
- All queries use **prepared statements**
- User input never directly concatenated into SQL
- Parameters bound with correct types

Example:
```php
$stmt = $conn->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->bind_param('i', $bookId);
$stmt->execute();
```

### XSS Prevention
- All output escaped with `htmlspecialchars()`
- Special characters converted to HTML entities
- Prevents malicious script injection

Example:
```php
echo htmlspecialchars($book['title']);
```

### Data Validation
- Server-side validation for all form inputs
- Required field checking
- Data type validation

**Note:** Module 6 will add: CSRF protection, password hashing, session security

---

## 📊 DATABASE NORMALIZATION

The schema follows **Third Normal Form (3NF)**:

1. **No Repeating Groups**: Each column contains atomic values
2. **Full Functional Dependency**: All non-key attributes depend on primary key
3. **No Transitive Dependencies**: No non-key attribute depends on another non-key attribute

**Foreign Key Relationship:**
- `books.user_id` references `users.user_id`
- CASCADE on delete: If user deleted, their books are too
- Maintains referential integrity

---

## 💡 ADMIN PANEL FEATURES

### Dashboard
- Real-time statistics cards
- Book management table with sorting
- Quick access to all CRUD operations

### Modal-Based UI
- Add/Edit forms open in modal windows
- No page reloads needed
- Smooth animations and transitions

### User Feedback
- Success messages (green) after operations
- Error messages (red) if something fails
- Auto-hide alerts after 5 seconds

### Responsive Design
- Works on desktop, tablet, and mobile
- Sidebar collapses on small screens
- Table adapts to narrow viewports

---

## 🐛 TROUBLESHOOTING

**"Connection failed" error:**
- Check MySQL is running
- Verify credentials in db_config.php
- Ensure database 'book_exchange' exists

**No books displaying:**
- Run database_schema.sql to import sample data
- Check for PHP errors (enable error_reporting)
- Verify database connection is successful

**Admin panel not showing books:**
- Check that books table has data
- View browser console for JavaScript errors
- Ensure all admin files are in admin/ folder

**Search not working:**
- Verify form method is GET
- Check SQL query in getAllBooks()
- Ensure search parameter is being passed

**CRUD operations failing:**
- Check book_action.php for errors
- Verify form action points to correct file
- Ensure all required fields are filled

## 🔜 NEXT MODULE

**Module 4: State Management & Application Logic**
- User authentication (login/registration)
- Session-based "My Wishlist" feature
- POST-Redirect-GET pattern improvements
- Admin authentication
- User profile pages

Stay tuned!

---

**End of Module 3 README**