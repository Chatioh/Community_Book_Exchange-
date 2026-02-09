# MODULE 5: AJAX ERROR HANDLING APPROACH

## OVERVIEW

This document explains the comprehensive error handling strategy implemented in Module 5 for AJAX requests, covering network failures, API errors, timeout handling, and user feedback mechanisms.

---

## 1. ERROR HANDLING ARCHITECTURE

### Layered Error Handling Approach

```
Layer 1: Network Level (Fetch API)
    ↓
Layer 2: HTTP Status Codes
    ↓
Layer 3: Application Level (JSON Response)
    ↓
Layer 4: User Feedback (UI Messages)
```

---

## 2. FETCH API ERROR HANDLING

### Basic Pattern

```javascript
try {
    const response = await fetch('api/search_books.php');
    
    // Layer 1: Check if fetch succeeded
    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    // Layer 2: Parse JSON
    const data = await response.json();
    
    // Layer 3: Check application-level success
    if (data.success) {
        // Process successful response
    } else {
        // Handle application error
        this.showError(data.error || 'An error occurred');
    }
    
} catch (error) {
    // Layer 4: Handle any errors
    console.error('Request failed:', error);
    this.showError('Failed to load data. Please try again.');
}
```

### Why This Approach?

1. **Network Errors**: Catch before parsing (server unreachable, DNS failure)
2. **HTTP Errors**: Handle bad status codes (404, 500, etc.)
3. **JSON Errors**: Catch malformed responses
4. **Application Errors**: Handle business logic failures

---

## 3. ERROR TYPES & HANDLING

### 3.1 Network Errors

**Causes:**
- Server unreachable
- No internet connection
- DNS resolution failure
- Request timeout

**Detection:**
```javascript
try {
    const response = await fetch(url);
} catch (error) {
    // Network error occurred
    if (error instanceof TypeError) {
        // Likely a network failure
        this.showError('Network error. Please check your internet connection.');
    }
}
```

**User Feedback:**
```
⚠️ Network error. Please check your internet connection.
[Retry Button]
```

### 3.2 HTTP Status Errors

**Common Status Codes:**
- `400` Bad Request - Invalid parameters
- `401` Unauthorized - Authentication required
- `404` Not Found - Endpoint doesn't exist
- `500` Internal Server Error - Server-side error
- `503` Service Unavailable - Server down

**Detection:**
```javascript
if (!response.ok) {
    switch (response.status) {
        case 400:
            throw new Error('Invalid request parameters');
        case 401:
            throw new Error('Please login to continue');
        case 404:
            throw new Error('Endpoint not found');
        case 500:
            throw new Error('Server error. Please try again later');
        default:
            throw new Error(`HTTP error! status: ${response.status}`);
    }
}
```

**Server-Side Implementation:**
```php
// In API endpoint
if ($error) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database query failed'
    ]);
}
```

### 3.3 JSON Parsing Errors

**Cause:**
- Server returned non-JSON response
- Malformed JSON
- Empty response body

**Detection:**
```javascript
try {
    const data = await response.json();
} catch (error) {
    console.error('JSON parse error:', error);
    throw new Error('Invalid response from server');
}
```

**Prevention (Server-Side):**
```php
header('Content-Type: application/json');
echo json_encode($data); // Always return valid JSON
```

### 3.4 Application-Level Errors

**Cause:**
- Business logic failures
- Validation errors
- Database errors

**Server Response:**
```php
echo json_encode([
    'success' => false,
    'error' => 'No books found matching your criteria',
    'code' => 'NO_RESULTS'
]);
```

**Client Handling:**
```javascript
if (data.success) {
    this.displayBooks(data.books);
} else {
    // Application-specific error
    this.showError(data.error || 'An error occurred');
}
```

---

## 4. TIMEOUT HANDLING

### Implementation

```javascript
async search() {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 second timeout
    
    try {
        const response = await fetch(url, {
            signal: controller.signal
        });
        clearTimeout(timeoutId);
        
        // Process response...
        
    } catch (error) {
        clearTimeout(timeoutId);
        
        if (error.name === 'AbortError') {
            this.showError('Request timed out. Please try again.');
        } else {
            this.showError('Failed to load data.');
        }
    }
}
```

### Why Timeout?

- Prevents indefinite hanging
- Better user experience
- Allows retry mechanism
- Frees up browser resources

---

## 5. USER FEEDBACK MECHANISMS

### 5.1 Loading States

**Show Loading Indicator:**
```javascript
showLoading() {
    this.loadingIndicator.style.display = 'flex';
    this.booksGrid.classList.add('loading');
    this.loadMoreBtn.disabled = true;
    this.loadMoreBtn.textContent = 'Loading...';
}
```

**Benefits:**
- User knows something is happening
- Prevents double-clicks
- Sets expectations

### 5.2 Error Messages

**Display Error:**
```javascript
showError(message) {
    this.booksGrid.innerHTML = `
        <div class="error-message">
            <p>⚠️ ${this.escapeHtml(message)}</p>
            <button onclick="location.reload()" class="btn btn-small">
                Retry
            </button>
        </div>
    `;
}
```

**Error Message Principles:**
1. **Clear**: User understands what happened
2. **Actionable**: Provides next steps
3. **Non-Technical**: Avoids jargon
4. **Helpful**: Suggests solutions

**Examples:**

| Technical Error | User-Friendly Message |
|----------------|----------------------|
| `TypeError: Failed to fetch` | "Network error. Please check your internet connection." |
| `HTTP 500` | "Server error. Please try again in a few moments." |
| `Empty response` | "No books found. Try adjusting your search." |
| `Timeout` | "Request timed out. Please try again." |

### 5.3 Empty States

**No Results:**
```javascript
if (books.length === 0) {
    this.booksGrid.innerHTML = `
        <div class="no-results">
            <p>No books found matching your search criteria.</p>
            <p>Try adjusting your filters.</p>
        </div>
    `;
}
```

**Different from Errors:**
- Not an error - just no matches
- Suggests adjusting criteria
- Less alarming visual style

---

## 6. RETRY MECHANISMS

### 6.1 Manual Retry

**Retry Button:**
```javascript
showError(message) {
    this.booksGrid.innerHTML = `
        <div class="error-message">
            <p>${message}</p>
            <button onclick="bookSearch.search(true)" class="btn-retry">
                Try Again
            </button>
        </div>
    `;
}
```

### 6.2 Automatic Retry (with Exponential Backoff)

```javascript
async searchWithRetry(maxRetries = 3) {
    let retries = 0;
    
    while (retries < maxRetries) {
        try {
            const response = await fetch(url);
            if (response.ok) {
                return await response.json();
            }
        } catch (error) {
            retries++;
            if (retries === maxRetries) {
                throw error;
            }
            // Exponential backoff: 1s, 2s, 4s
            await this.sleep(Math.pow(2, retries) * 1000);
        }
    }
}

sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
```

**When to Use:**
- Temporary network glitches
- Server temporarily unavailable
- Rate limiting issues

**When NOT to Use:**
- Client-side errors (400)
- Authentication errors (401)
- Not found errors (404)

---

## 7. DEBOUNCING & REQUEST CANCELLATION

### Debouncing (Prevent Excessive Requests)

```javascript
constructor() {
    this.debounceTimer = null;
}

// In event listener
this.searchInput.addEventListener('input', () => {
    clearTimeout(this.debounceTimer);
    this.debounceTimer = setTimeout(() => {
        this.resetAndSearch();
    }, 300); // Wait 300ms after user stops typing
});
```

**Benefits:**
- Reduces server load
- Better performance
- Fewer unnecessary requests
- Better UX (doesn't flash results)

### Request Cancellation

```javascript
async search() {
    // Cancel previous request if still running
    if (this.currentRequest) {
        this.currentRequest.abort();
    }
    
    this.currentRequest = new AbortController();
    
    try {
        const response = await fetch(url, {
            signal: this.currentRequest.signal
        });
        // Process response...
    } catch (error) {
        if (error.name === 'AbortError') {
            // Request was cancelled, not an error
            return;
        }
        // Handle actual errors...
    }
}
```

**Use Cases:**
- User types quickly (cancel old searches)
- User changes filters (cancel in-progress search)
- User navigates away (cleanup)

---

## 8. LOGGING & DEBUGGING

### Console Logging Strategy

```javascript
try {
    const response = await fetch(url);
    const data = await response.json();
    
    // Development logging
    if (window.location.hostname === 'localhost') {
        console.log('Search response:', data);
    }
    
} catch (error) {
    // Always log errors (even in production)
    console.error('Search error:', {
        message: error.message,
        stack: error.stack,
        timestamp: new Date().toISOString()
    });
    
    // Could send to error tracking service
    // this.sendErrorToTracking(error);
}
```

### Error Tracking (Future Enhancement)

```javascript
sendErrorToTracking(error) {
    // Send to service like Sentry, LogRocket, etc.
    if (window.errorTracker) {
        window.errorTracker.captureException(error);
    }
}
```

---

## 9. PROGRESSIVE ENHANCEMENT

### Fallback for No JavaScript

```html
<noscript>
    <div class="no-js-message">
        <p>JavaScript is required for live search.</p>
        <a href="listings.php">View all books</a>
    </div>
</noscript>
```

### Graceful Degradation

```javascript
// Check if Fetch API is supported
if (!window.fetch) {
    this.showError('Your browser does not support live search. Please upgrade your browser.');
    return;
}

// Check if Promises are supported
if (!window.Promise) {
    this.showError('Live search is not available in your browser.');
    return;
}
```

---

## 10. SECURITY CONSIDERATIONS

### 10.1 Input Sanitization

**Client-Side (XSS Prevention):**
```javascript
escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Use when displaying user input
this.booksGrid.innerHTML = `<h3>${this.escapeHtml(book.title)}</h3>`;
```

**Server-Side:**
```php
$search = htmlspecialchars(trim($_GET['search']));
```

### 10.2 CSRF Protection (Future)

```javascript
// Include CSRF token in requests
const response = await fetch(url, {
    headers: {
        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
    }
});
```

---

## 11. PERFORMANCE OPTIMIZATION

### Request Deduplication

```javascript
constructor() {
    this.cache = new Map();
}

async search(params) {
    const cacheKey = JSON.stringify(params);
    
    // Check cache first
    if (this.cache.has(cacheKey)) {
        return this.cache.get(cacheKey);
    }
    
    const data = await this.fetchData(params);
    this.cache.set(cacheKey, data);
    return data;
}
```

### Loading States Prevent Duplicate Requests

```javascript
async search() {
    if (this.isLoading) return; // Prevent duplicate requests
    
    this.isLoading = true;
    try {
        // Make request...
    } finally {
        this.isLoading = false;
    }
}
```

---

## 12. TESTING ERROR SCENARIOS

### Manual Testing Checklist

- [ ] Disconnect internet → Should show network error
- [ ] Invalid API endpoint → Should show 404 error
- [ ] Slow 3G connection → Should show loading state
- [ ] Server returns 500 → Should show server error
- [ ] Empty search results → Should show "no results"
- [ ] Rapid typing → Should debounce requests
- [ ] Click search while loading → Should not send duplicate request

### Simulating Errors

```javascript
// Add to fetch for testing
const response = await fetch(url, {
    // Simulate slow connection
    // signal: AbortSignal.timeout(100)
});

// Simulate 500 error (in PHP)
// http_response_code(500);

// Simulate network error (disconnect internet)
```

---

## 13. ERROR HANDLING BEST PRACTICES SUMMARY

### DO ✅

1. **Always use try-catch** with async/await
2. **Check response.ok** before parsing JSON
3. **Provide clear error messages** to users
4. **Log errors** for debugging
5. **Offer retry options** when appropriate
6. **Debounce** user input
7. **Cancel** unnecessary requests
8. **Show loading states** immediately
9. **Sanitize** all user input
10. **Test** error scenarios

### DON'T ❌

1. **Don't expose technical errors** to users
2. **Don't ignore errors** silently
3. **Don't retry** indefinitely
4. **Don't block UI** without feedback
5. **Don't trust** user input
6. **Don't forget** to hide loading states
7. **Don't make** simultaneous identical requests
8. **Don't assume** network is always available
9. **Don't skip** error logging
10. **Don't use** alert() for errors

---

## CONCLUSION

Effective AJAX error handling requires:

1. **Multiple Layers**: Network, HTTP, application, UI
2. **User-Focused**: Clear messages, actionable feedback
3. **Resilient**: Retry mechanisms, graceful degradation
4. **Performant**: Debouncing, request cancellation
5. **Secure**: Input sanitization, output escaping
6. **Debuggable**: Comprehensive logging

The implementation in Module 5 follows industry best practices while remaining accessible for learning purposes.

---

**End of AJAX Error Handling Approach Document**