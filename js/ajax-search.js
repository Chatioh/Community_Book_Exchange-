/**
 * AJAX Functionality for Book Exchange
 * Module 5: Asynchronous Interactions & Client-Side Dynamics
 */

// ============================================
// AJAX Live Search Implementation
// ============================================

class BookSearch {
    constructor() {
        this.searchInput = document.getElementById('searchInput');
        this.genreFilter = document.getElementById('genreFilter');
        this.conditionFilter = document.getElementById('conditionFilter');
        this.locationFilter = document.getElementById('locationFilter');
        this.booksGrid = document.getElementById('booksGrid');
        this.loadMoreBtn = document.getElementById('loadMoreBtn');
        this.resultsCount = document.getElementById('resultsCount');
        this.loadingIndicator = document.getElementById('loadingIndicator');
        
        this.currentOffset = 0;
        this.limit = 9;
        this.hasMore = true;
        this.isLoading = false;
        this.debounceTimer = null;
        
        this.init();
    }
    
    init() {
        // Live search with debouncing (300ms delay)
        if (this.searchInput) {
            this.searchInput.addEventListener('input', () => {
                clearTimeout(this.debounceTimer);
                this.debounceTimer = setTimeout(() => {
                    this.resetAndSearch();
                }, 300);
            });
        }
        
        // Filter change handlers
        if (this.genreFilter) {
            this.genreFilter.addEventListener('change', () => this.resetAndSearch());
        }
        if (this.conditionFilter) {
            this.conditionFilter.addEventListener('change', () => this.resetAndSearch());
        }
        if (this.locationFilter) {
            this.locationFilter.addEventListener('change', () => this.resetAndSearch());
        }
        
        // Load more button
        if (this.loadMoreBtn) {
            this.loadMoreBtn.addEventListener('click', () => this.loadMore());
        }
    }
    
    resetAndSearch() {
        this.currentOffset = 0;
        this.hasMore = true;
        this.search(true);
    }
    
    loadMore() {
        if (this.hasMore && !this.isLoading) {
            this.currentOffset += this.limit;
            this.search(false);
        }
    }
    
    
    async search(replace = true) {
        if (this.isLoading) return;
        
        this.isLoading = true;
        this.showLoading();
        
        // Build query parameters
        const params = new URLSearchParams({
            search: this.searchInput?.value || '',
            genre: this.genreFilter?.value || '',
            condition: this.conditionFilter?.value || '',
            location: this.locationFilter?.value || '',
            offset: this.currentOffset,
            limit: this.limit
        });
        
        try {
            const response = await fetch(`api/search_books.php?${params}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                if (replace) {
                    this.displayBooks(data.books);
                } else {
                    this.appendBooks(data.books);
                }
                
                this.updateResultsCount(data.total);
                this.hasMore = data.hasMore;
                this.updateLoadMoreButton();
            } else {
                this.showError(data.error || 'Failed to load books');
            }
            
        } catch (error) {
            console.error('Search error:', error);
            this.showError('Failed to load books. Please check your connection and try again.');
        } finally {
            this.isLoading = false;
            this.hideLoading();
        }
    }
    
    displayBooks(books) {
        if (books.length === 0) {
            this.booksGrid.innerHTML = `
                <div class="no-results">
                    <p>No books found matching your search criteria. Try adjusting your filters.</p>
                </div>
            `;
            return;
        }
        
        this.booksGrid.innerHTML = books.map(book => this.createBookCard(book)).join('');
        this.attachEventListeners();
    }
    
    appendBooks(books) {
        const booksHTML = books.map(book => this.createBookCard(book)).join('');
        this.booksGrid.insertAdjacentHTML('beforeend', booksHTML);
        this.attachEventListeners();
    }
    
    createBookCard(book) {
        const isInWishlist = window.wishlistIds?.includes(book.book_id) || false;
        const isLoggedIn = window.isUserLoggedIn || false;
        
        return `
            <article class="book-card fade-in" data-book-id="${book.book_id}">
                <div class="book-image">
                    <div class="book-cover" style="background: ${this.escapeHtml(book.cover_color)};">
                        <span class="book-title-overlay">${this.escapeHtml(book.title)}</span>
                    </div>
                    <span class="book-condition">${this.escapeHtml(book.book_condition)}</span>
                </div>
                <div class="book-info">
                    <h3 class="book-title">${this.escapeHtml(book.title)}</h3>
                    <p class="book-author">by ${this.escapeHtml(book.author)}</p>
                    <p class="book-description">${this.escapeHtml(book.description.substring(0, 120))}...</p>
                    <div class="book-meta">
                        <span class="book-genre">${this.escapeHtml(book.genre)}</span>
                        <span class="book-location">📍 ${this.escapeHtml(book.location)}</span>
                    </div>
                    <div class="book-actions">
                        <button class="btn-toggle-details" data-book-id="${book.book_id}">View Details</button>
                        ${isLoggedIn ? this.createWishlistButton(book.book_id, isInWishlist) : ''}
                    </div>
                    <div class="book-details hidden" id="details-${book.book_id}">
                        <p><strong>Condition:</strong> ${this.escapeHtml(book.book_condition)}</p>
                        <p><strong>Exchange Preference:</strong> ${this.escapeHtml(book.exchange_preference)}</p>
                        <p><strong>Owner:</strong> ${this.escapeHtml(book.owner_name)}</p>
                        ${book.isbn ? `<p><strong>ISBN:</strong> ${this.escapeHtml(book.isbn)}</p>` : ''}
                        <a href="#" class="btn btn-small">Request Exchange</a>
                    </div>
                </div>
            </article>
        `;
    }
    
    createWishlistButton(bookId, isInWishlist) {
        if (isInWishlist) {
            return `
                <form action="process_wishlist.php" method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="remove">
                    <input type="hidden" name="book_id" value="${bookId}">
                    <button type="submit" class="btn-wishlist active" title="Remove from wishlist">
                        💚 In Wishlist
                    </button>
                </form>
            `;
        } else {
            return `
                <form action="process_wishlist.php" method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="book_id" value="${bookId}">
                    <button type="submit" class="btn-wishlist" title="Add to wishlist">
                        🤍 Add to Wishlist
                    </button>
                </form>
            `;
        }
    }
    
    attachEventListeners() {
        // Reattach toggle details buttons
        document.querySelectorAll('.btn-toggle-details').forEach(button => {
            button.addEventListener('click', function() {
                const bookId = this.getAttribute('data-book-id');
                const details = document.getElementById(`details-${bookId}`);
                
                if (details) {
                    details.classList.toggle('hidden');
                    this.textContent = details.classList.contains('hidden') 
                        ? 'View Details' 
                        : 'Hide Details';
                }
            });
        });
    }
    
    updateResultsCount(total) {
        if (this.resultsCount) {
            this.resultsCount.textContent = `${total} book${total !== 1 ? 's' : ''} found`;
        }
    }
    
    updateLoadMoreButton() {
        if (this.loadMoreBtn) {
            if (this.hasMore) {
                this.loadMoreBtn.style.display = 'block';
                this.loadMoreBtn.disabled = false;
            } else {
                this.loadMoreBtn.style.display = 'none';
            }
        }
    }
    
    showLoading() {
        if (this.loadingIndicator) {
            this.loadingIndicator.style.display = 'flex';
        }
        if (this.loadMoreBtn) {
            this.loadMoreBtn.disabled = true;
            this.loadMoreBtn.textContent = 'Loading...';
        }
    }
    
    hideLoading() {
        if (this.loadingIndicator) {
            this.loadingIndicator.style.display = 'none';
        }
        if (this.loadMoreBtn) {
            this.loadMoreBtn.disabled = false;
            this.loadMoreBtn.textContent = 'Load More Books';
        }
    }
    
    showError(message) {
        this.booksGrid.innerHTML = `
            <div class="error-message">
                <p>⚠️ ${this.escapeHtml(message)}</p>
                <button onclick="location.reload()" class="btn btn-small">Retry</button>
            </div>
        `;
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// ============================================
// Open Library API Integration
// ============================================

class BookCoverFetcher {
    constructor() {
        this.cache = new Map();
    }
    
    async fetchCover(isbn) {
        if (!isbn) return null;
        
        // Check cache first
        if (this.cache.has(isbn)) {
            return this.cache.get(isbn);
        }
        
        try {
            const response = await fetch(`api/fetch_book_cover.php?isbn=${encodeURIComponent(isbn)}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success && data.cover) {
                this.cache.set(isbn, data.cover);
                return data.cover;
            }
            
            return null;
            
        } catch (error) {
            console.error('Error fetching book cover:', error);
            return null;
        }
    }
    
    async updateBookCovers() {
        const bookCards = document.querySelectorAll('.book-card[data-isbn]');
        
        for (const card of bookCards) {
            const isbn = card.getAttribute('data-isbn');
            if (!isbn) continue;
            
            const cover = await this.fetchCover(isbn);
            
            if (cover && cover.medium) {
                const bookCoverDiv = card.querySelector('.book-cover');
                if (bookCoverDiv) {
                    bookCoverDiv.style.backgroundImage = `url(${cover.medium})`;
                    bookCoverDiv.style.backgroundSize = 'cover';
                    bookCoverDiv.style.backgroundPosition = 'center';
                }
            }
        }
    }
}

// ============================================
// Initialize on page load
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    // Initialize AJAX search if on listings page
    if (document.getElementById('booksGrid')) {
        window.bookSearch = new BookSearch();
    }
    
    // Initialize book cover fetcher
    window.bookCoverFetcher = new BookCoverFetcher();
    
    // Fetch covers for books with ISBN
    if (document.querySelectorAll('.book-card[data-isbn]').length > 0) {
        window.bookCoverFetcher.updateBookCovers();
    }
    
    // Mobile menu toggle (existing functionality)
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const navList = document.querySelector('.nav-list');
    
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            navList.classList.toggle('active');
        });
    }
    
    // Existing book details toggle for non-AJAX pages
    document.querySelectorAll('.btn-toggle-details').forEach(button => {
        button.addEventListener('click', function() {
            const bookId = this.getAttribute('data-book-id');
            const details = document.getElementById(`details-${bookId}`);
            
            if (details) {
                // Close other open details
                document.querySelectorAll('.book-details').forEach(d => {
                    if (d !== details && !d.classList.contains('hidden')) {
                        d.classList.add('hidden');
                        const btn = d.parentElement.querySelector('.btn-toggle-details');
                        if (btn) btn.textContent = 'View Details';
                    }
                });
                
                // Toggle current details
                details.classList.toggle('hidden');
                this.textContent = details.classList.contains('hidden') 
                    ? 'View Details' 
                    : 'Hide Details';
                
                // Scroll into view if expanded
                if (!details.classList.contains('hidden')) {
                    details.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }
        });
    });
});
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AJAX search if on listings page
    if (document.getElementById('booksGrid')) {
        window.bookSearch = new BookSearch();
        // TRIGGER THE INITIAL LOAD HERE
        window.bookSearch.search(true); 
    }
    
    // ... rest of your existing initialization code ...
});