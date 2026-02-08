// ========================================
// Community Book Exchange - JavaScript
// ========================================

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    initMobileMenu();
    initBookDetailsToggle();
    initFormValidation();
    initSearchAndFilter();
    initSmoothScrolling();
});

// ========================================
// Mobile Menu Toggle
// ========================================
function initMobileMenu() {
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const navList = document.querySelector('.nav-list');
    
    if (menuToggle && navList) {
        menuToggle.addEventListener('click', function() {
            menuToggle.classList.toggle('active');
            navList.classList.toggle('active');
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.main-nav')) {
                menuToggle.classList.remove('active');
                navList.classList.remove('active');
            }
        });
        
        // Close menu on link click
        navList.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                menuToggle.classList.remove('active');
                navList.classList.remove('active');
            });
        });
    }
}

// ========================================
// Book Details Toggle
// ========================================
function initBookDetailsToggle() {
    const toggleButtons = document.querySelectorAll('.btn-toggle-details');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const bookCard = this.closest('.book-card');
            const details = bookCard.querySelector('.book-details');
            
            if (details) {
                const isHidden = details.classList.contains('hidden');
                
                // Close all other details
                document.querySelectorAll('.book-details').forEach(detail => {
                    if (detail !== details) {
                        detail.classList.add('hidden');
                        const btn = detail.closest('.book-card').querySelector('.btn-toggle-details');
                        if (btn) btn.textContent = 'View Details';
                    }
                });
                
                // Toggle current details
                if (isHidden) {
                    details.classList.remove('hidden');
                    this.textContent = 'Hide Details';
                    
                    // Smooth scroll into view if needed
                    setTimeout(() => {
                        const rect = bookCard.getBoundingClientRect();
                        const isInView = rect.bottom <= window.innerHeight;
                        if (!isInView) {
                            bookCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }
                    }, 100);
                } else {
                    details.classList.add('hidden');
                    this.textContent = 'View Details';
                }
            }
        });
    });
}

// ========================================
// Form Validation
// ========================================
function initFormValidation() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        // Real-time validation
        const inputs = contactForm.querySelectorAll('.form-input, .form-select, .form-textarea');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                // Clear error on input
                if (this.classList.contains('error')) {
                    validateField(this);
                }
            });
        });
        
        // Form submission
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let isValid = true;
            
            // Validate all required fields
            inputs.forEach(input => {
                if (!validateField(input)) {
                    isValid = false;
                }
            });
            
            if (isValid) {
                // Show success message
                showFormSuccess();
                
                // Reset form after a delay
                setTimeout(() => {
                    contactForm.reset();
                    clearAllErrors();
                }, 2000);
            } else {
                // Scroll to first error
                const firstError = contactForm.querySelector('.form-input.error, .form-select.error, .form-textarea.error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }
        });
        
        // Reset button
        contactForm.addEventListener('reset', function() {
            setTimeout(clearAllErrors, 0);
        });
    }
}

function validateField(field) {
    const fieldName = field.name;
    const fieldValue = field.value.trim();
    const errorSpan = document.getElementById(fieldName + 'Error');
    
    let isValid = true;
    let errorMessage = '';
    
    // Check if field is required
    if (field.hasAttribute('required') && !fieldValue) {
        isValid = false;
        errorMessage = 'This field is required.';
    } else if (fieldValue) {
        // Field-specific validation
        switch (fieldName) {
            case 'name':
                if (fieldValue.length < 2) {
                    isValid = false;
                    errorMessage = 'Name must be at least 2 characters.';
                } else if (!/^[a-zA-Z\s'-]+$/.test(fieldValue)) {
                    isValid = false;
                    errorMessage = 'Please enter a valid name.';
                }
                break;
                
            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(fieldValue)) {
                    isValid = false;
                    errorMessage = 'Please enter a valid email address.';
                }
                break;
                
            case 'phone':
                if (fieldValue && !/^[\d\s()+-]+$/.test(fieldValue)) {
                    isValid = false;
                    errorMessage = 'Please enter a valid phone number.';
                }
                break;
                
            case 'message':
                if (fieldValue.length < 10) {
                    isValid = false;
                    errorMessage = 'Message must be at least 10 characters.';
                }
                break;
                
            case 'subject':
                if (fieldValue === '') {
                    isValid = false;
                    errorMessage = 'Please select a subject.';
                }
                break;
        }
    }
    
    // Update UI
    if (isValid) {
        field.classList.remove('error');
        if (errorSpan) {
            errorSpan.textContent = '';
        }
    } else {
        field.classList.add('error');
        if (errorSpan) {
            errorSpan.textContent = errorMessage;
        }
    }
    
    return isValid;
}

function clearAllErrors() {
    const errorInputs = document.querySelectorAll('.form-input.error, .form-select.error, .form-textarea.error');
    const errorSpans = document.querySelectorAll('.form-error');
    
    errorInputs.forEach(input => input.classList.remove('error'));
    errorSpans.forEach(span => span.textContent = '');
}

function showFormSuccess() {
    const form = document.getElementById('contactForm');
    if (!form) return;
    
    // Create success message
    const successMessage = document.createElement('div');
    successMessage.className = 'form-success-message';
    successMessage.style.cssText = `
        background: linear-gradient(135deg, #2d6a4f 0%, #40916c 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        text-align: center;
        font-weight: 600;
        animation: slideDown 0.3s ease;
    `;
    successMessage.innerHTML = `
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="display: inline-block; margin-right: 8px; vertical-align: middle;">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
        Thank you! Your message has been sent successfully.
    `;
    
    // Insert at top of form
    form.insertBefore(successMessage, form.firstChild);
    
    // Remove after delay
    setTimeout(() => {
        successMessage.style.opacity = '0';
        successMessage.style.transition = 'opacity 0.3s ease';
        setTimeout(() => successMessage.remove(), 300);
    }, 3000);
}

// ========================================
// Search and Filter (Listings Page)
// ========================================
function initSearchAndFilter() {
    const searchInput = document.getElementById('searchInput');
    const genreFilter = document.getElementById('genreFilter');
    const conditionFilter = document.getElementById('conditionFilter');
    const locationFilter = document.getElementById('locationFilter');
    const booksGrid = document.getElementById('booksGrid');
    const noResults = document.getElementById('noResults');
    
    if (!searchInput || !booksGrid) return;
    
    function filterBooks() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedGenre = genreFilter ? genreFilter.value.toLowerCase() : '';
        const selectedCondition = conditionFilter ? conditionFilter.value.toLowerCase() : '';
        const selectedLocation = locationFilter ? locationFilter.value.toLowerCase() : '';
        
        const bookCards = booksGrid.querySelectorAll('.book-card');
        let visibleCount = 0;
        
        bookCards.forEach(card => {
            const title = card.querySelector('.book-title')?.textContent.toLowerCase() || '';
            const author = card.querySelector('.book-author')?.textContent.toLowerCase() || '';
            const description = card.querySelector('.book-description')?.textContent.toLowerCase() || '';
            const genre = card.dataset.genre || '';
            const condition = card.dataset.condition || '';
            const location = card.dataset.location || '';
            
            // Check search term
            const matchesSearch = !searchTerm || 
                title.includes(searchTerm) || 
                author.includes(searchTerm) || 
                description.includes(searchTerm);
            
            // Check filters
            const matchesGenre = !selectedGenre || genre === selectedGenre;
            const matchesCondition = !selectedCondition || condition === selectedCondition;
            const matchesLocation = !selectedLocation || location === selectedLocation;
            
            // Show/hide card with animation
            if (matchesSearch && matchesGenre && matchesCondition && matchesLocation) {
                card.style.display = '';
                // Trigger reflow for animation
                card.style.animation = 'none';
                setTimeout(() => {
                    card.style.animation = 'fadeIn 0.3s ease';
                }, 10);
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Show/hide no results message
        if (noResults) {
            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }
    }
    
    // Attach event listeners
    searchInput.addEventListener('input', debounce(filterBooks, 300));
    
    if (genreFilter) {
        genreFilter.addEventListener('change', filterBooks);
    }
    
    if (conditionFilter) {
        conditionFilter.addEventListener('change', filterBooks);
    }
    
    if (locationFilter) {
        locationFilter.addEventListener('change', filterBooks);
    }
}

// ========================================
// Smooth Scrolling
// ========================================
function initSmoothScrolling() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            // Ignore empty hashes
            if (href === '#' || href === '#!') return;
            
            const target = document.querySelector(href);
            
            if (target) {
                e.preventDefault();
                const headerHeight = document.querySelector('.site-header')?.offsetHeight || 0;
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// ========================================
// Utility Functions
// ========================================

// Debounce function for search input
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Add scroll animations for elements
function initScrollAnimations() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    const animatedElements = document.querySelectorAll('.book-card, .step-card, .value-card');
    animatedElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
}

// Initialize scroll animations if IntersectionObserver is supported
if ('IntersectionObserver' in window) {
    document.addEventListener('DOMContentLoaded', initScrollAnimations);
}

// ========================================
// Console Message
// ========================================
console.log('%c📚 Community Book Exchange', 'font-size: 20px; font-weight: bold; color: #8B4513;');
console.log('%cWelcome to our book-sharing community!', 'font-size: 14px; color: #6b5447;');
