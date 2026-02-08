<?php
// Include functions
require_once 'includes/functions.php';

// Set page title
$pageTitle = 'Home';

// Include header
include 'includes/header.php';
?>

    <main>
        <section class="hero">
            <div class="hero-background"></div>
            <div class="container">
                <div class="hero-content">
                    <!-- DYNAMIC GREETING BASED ON SERVER TIME -->
                    <div class="dynamic-greeting">
                        <p class="greeting-text">
                            <span class="greeting-icon"><?php echo getTimeIcon(); ?></span>
                            <span class="greeting-message"><?php echo getTimeBasedGreeting(); ?>!</span>
                        </p>
                        <p class="greeting-time">
                            <small>Server Time: <?php echo getCurrentDateTime(); ?></small>
                        </p>
                    </div>
                    <h1 class="hero-title">
                        Share Stories,<br>
                        Build Community
                    </h1>
                    <p class="hero-subtitle">
                        Connect with fellow book lovers in your area. Exchange books, discover new reads, 
                        and give your favorite stories a second life.
                    </p>
                    <div class="hero-actions">
                        <a href="#featured" class="btn btn-primary">Explore Books</a>
                        <a href="contact.php    " class="btn btn-outline">Join Our Community</a>
                    </div>
                </div>
                <div class="hero-stats">
                    <div class="stat-card">
                        <span class="stat-number">1,247</span>
                        <span class="stat-label">Books Available</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">432</span>
                        <span class="stat-label">Active Members</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-number">2,891</span>
                        <span class="stat-label">Exchanges Made</span>
                    </div>
                </div>
            </div>
        </section>

        <section id="featured" class="featured-books">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Featured Books</h2>
                    <p class="section-subtitle">Discover what our community is reading and sharing this week</p>
                </div>
                
                <div class="books-grid">
                    <article class="book-card">
                        <div class="book-image">
                            <div class="book-cover" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <span class="book-title-overlay">The Midnight Library</span>
                            </div>
                            <span class="book-condition">Excellent</span>
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">The Midnight Library</h3>
                            <p class="book-author">by Matt Haig</p>
                            <p class="book-description">A dazzling novel about all the choices that go into a life well lived.</p>
                            <div class="book-meta">
                                <span class="book-genre">Fiction</span>
                                <span class="book-location">📍 Northwest</span>
                            </div>
                            <button class="btn-toggle-details">View Details</button>
                            <div class="book-details hidden">
                                <p><strong>Condition:</strong> Like new, minimal wear</p>
                                <p><strong>Exchange Preference:</strong> Any contemporary fiction</p>
                                <p><strong>Owner:</strong> Sarah M.</p>
                                <a href="#" class="btn btn-small">Request Exchange</a>
                            </div>
                        </div>
                    </article>

                    <article class="book-card">
                        <div class="book-image">
                            <div class="book-cover" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <span class="book-title-overlay">Educated</span>
                            </div>
                            <span class="book-condition">Good</span>
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">Educated</h3>
                            <p class="book-author">by Tara Westover</p>
                            <p class="book-description">A memoir about a young woman who leaves her survivalist family.</p>
                            <div class="book-meta">
                                <span class="book-genre">Memoir</span>
                                <span class="book-location">📍 East</span>
                            </div>
                            <button class="btn-toggle-details">View Details</button>
                            <div class="book-details hidden">
                                <p><strong>Condition:</strong> Gently used, some spine wear</p>
                                <p><strong>Exchange Preference:</strong> Biography or history</p>
                                <p><strong>Owner:</strong> James K.</p>
                                <a href="#" class="btn btn-small">Request Exchange</a>
                            </div>
                        </div>
                    </article>

                    <article class="book-card">
                        <div class="book-image">
                            <div class="book-cover" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                <span class="book-title-overlay">Atomic Habits</span>
                            </div>
                            <span class="book-condition">Very Good</span>
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">Atomic Habits</h3>
                            <p class="book-author">by James Clear</p>
                            <p class="book-description">Practical strategies for building good habits and breaking bad ones.</p>
                            <div class="book-meta">
                                <span class="book-genre">Self-Help</span>
                                <span class="book-location">📍 West</span>
                            </div>
                            <button class="btn-toggle-details">View Details</button>
                            <div class="book-details hidden">
                                <p><strong>Condition:</strong> Very good, clean pages</p>
                                <p><strong>Exchange Preference:</strong> Business or productivity books</p>
                                <p><strong>Owner:</strong> Maria L.</p>
                                <a href="#" class="btn btn-small">Request Exchange</a>
                            </div>
                        </div>
                    </article>

                    <article class="book-card">
                        <div class="book-image">
                            <div class="book-cover" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                                <span class="book-title-overlay">Where the Crawdads Sing</span>
                            </div>
                            <span class="book-condition">Excellent</span>
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">Where the Crawdads Sing</h3>
                            <p class="book-author">by Delia Owens</p>
                            <p class="book-description">A coming-of-age mystery set in the marshlands of North Carolina.</p>
                            <div class="book-meta">
                                <span class="book-genre">Mystery</span>
                                <span class="book-location">📍 Southwest</span>
                            </div>
                            <button class="btn-toggle-details">View Details</button>
                            <div class="book-details hidden">
                                <p><strong>Condition:</strong> Excellent, read once</p>
                                <p><strong>Exchange Preference:</strong> Mystery or thriller</p>
                                <p><strong>Owner:</strong> David R.</p>
                                <a href="#" class="btn btn-small">Request Exchange</a>
                            </div>
                        </div>
                    </article>

                    <article class="book-card">
                        <div class="book-image">
                            <div class="book-cover" style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);">
                                <span class="book-title-overlay">The Song of Achilles</span>
                            </div>
                            <span class="book-condition">Good</span>
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">The Song of Achilles</h3>
                            <p class="book-author">by Madeline Miller</p>
                            <p class="book-description">A reimagining of Homer's Iliad through the eyes of Patroclus.</p>
                            <div class="book-meta">
                                <span class="book-genre">Historical Fiction</span>
                                <span class="book-location">📍 Center</span>
                            </div>
                            <button class="btn-toggle-details">View Details</button>
                            <div class="book-details hidden">
                                <p><strong>Condition:</strong> Good, slight cover wear</p>
                                <p><strong>Exchange Preference:</strong> Fantasy or mythology</p>
                                <p><strong>Owner:</strong> Emma T.</p>
                                <a href="#" class="btn btn-small">Request Exchange</a>
                            </div>
                        </div>
                    </article>

                    <article class="book-card">
                        <div class="book-image">
                            <div class="book-cover" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                                <span class="book-title-overlay">The Vanishing Half</span>
                            </div>
                            <span class="book-condition">Very Good</span>
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">The Vanishing Half</h3>
                            <p class="book-author">by Brit Bennett</p>
                            <p class="book-description">A multi-generational story about identity, family, and race.</p>
                            <div class="book-meta">
                                <span class="book-genre">Literary Fiction</span>
                                <span class="book-location">📍 North</span>
                            </div>
                            <button class="btn-toggle-details">View Details</button>
                            <div class="book-details hidden">
                                <p><strong>Condition:</strong> Very good, no marks</p>
                                <p><strong>Exchange Preference:</strong> Contemporary literary fiction</p>
                                <p><strong>Owner:</strong> Lisa P.</p>
                                <a href="#" class="btn btn-small">Request Exchange</a>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="cta-section">
                    <a href="listings.php   " class="btn btn-primary">View All Books</a>
                </div>
            </div>
        </section>

        <section class="how-it-works">
            <div class="container">
                <h2 class="section-title">How It Works</h2>
                <div class="steps-grid">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h3 class="step-title">Create Account</h3>
                        <p class="step-description">Join our community of book lovers. It's free and takes less than a minute.</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h3 class="step-title">List Your Books</h3>
                        <p class="step-description">Add books you're willing to exchange. Include photos and condition details.</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h3 class="step-title">Find & Request</h3>
                        <p class="step-description">Browse available books and send exchange requests to other members.</p>
                    </div>
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h3 class="step-title">Exchange & Enjoy</h3>
                        <p class="step-description">Meet up safely, swap books, and discover your next great read!</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php
// Include footer
include 'includes/footer.php';
?>