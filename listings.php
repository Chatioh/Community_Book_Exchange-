<?php
// Include functions
require_once 'includes/functions.php';

// Set page title
$pageTitle = 'Browse Books';

// Include header
include 'includes/header.php';
?>

    <main>
        <section class="page-header">
            <div class="container">
                <h1 class="page-title">Browse Available Books</h1>
                <p class="page-subtitle">Discover your next great read from our community collection</p>
            </div>
        </section>

        <section class="listings-section">
            <div class="container">
                <div class="search-filter-bar">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Search by title, author, or genre..." class="search-input">
                        <button type="button" class="search-button">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="2"/>
                                <path d="M12.5 12.5L17 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                    <div class="filter-controls">
                        <select id="genreFilter" class="filter-select">
                            <option value="">All Genres</option>
                            <option value="fiction">Fiction</option>
                            <option value="mystery">Mystery</option>
                            <option value="memoir">Memoir</option>
                            <option value="self-help">Self-Help</option>
                            <option value="historical">Historical Fiction</option>
                            <option value="literary">Literary Fiction</option>
                        </select>
                        <select id="conditionFilter" class="filter-select">
                            <option value="">All Conditions</option>
                            <option value="excellent">Excellent</option>
                            <option value="very-good">Very Good</option>
                            <option value="good">Good</option>
                        </select>
                        <select id="locationFilter" class="filter-select">
                            <option value="">All Locations</option>
                            <option value="northwest">Northwest</option>
                            <option value="east">East</option>
                            <option value="west">West</option>
                            <option value="southwest">Southwest</option>
                            <option value="center">Center</option>
                            <option value="north">North</option>
                        </select>
                    </div>
                </div>

                <div class="books-grid" id="booksGrid">
                    <article class="book-card" data-genre="fiction" data-condition="excellent" data-location="northwest">
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
                                <span class="book-location">📍 northwest</span>
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

                    <article class="book-card" data-genre="memoir" data-condition="good" data-location="east">
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

                    <article class="book-card" data-genre="self-help" data-condition="very-good" data-location="west">
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

                    <article class="book-card" data-genre="mystery" data-condition="excellent" data-location="southwest">
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
                                <span class="book-location">📍 southwest</span>
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

                    <article class="book-card" data-genre="historical" data-condition="good" data-location="center">
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

                    <article class="book-card" data-genre="literary" data-condition="very-good" data-location="north">
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

                    <article class="book-card" data-genre="fiction" data-condition="excellent" data-location="northwest">
                        <div class="book-cover" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                            <span class="book-title-overlay">Project Hail Mary</span>
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">Project Hail Mary</h3>
                            <p class="book-author">by Andy Weir</p>
                            <p class="book-description">A lone astronaut must save the earth from disaster in this gripping sci-fi tale.</p>
                            <div class="book-meta">
                                <span class="book-genre">Fiction</span>
                                <span class="book-location">📍 northwest</span>
                            </div>
                            <button class="btn-toggle-details">View Details</button>
                            <div class="book-details hidden">
                                <p><strong>Condition:</strong> Excellent condition</p>
                                <p><strong>Exchange Preference:</strong> Science fiction or thriller</p>
                                <p><strong>Owner:</strong> Tom H.</p>
                                <a href="#" class="btn btn-small">Request Exchange</a>
                            </div>
                        </div>
                    </article>

                    <article class="book-card" data-genre="mystery" data-condition="good" data-location="east">
                        <div class="book-image">
                            <div class="book-cover" style="background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);">
                                <span class="book-title-overlay">The Silent Patient</span>
                            </div>
                            <span class="book-condition">Good</span>
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">The Silent Patient</h3>
                            <p class="book-author">by Alex Michaelides</p>
                            <p class="book-description">A woman's act of violence against her husband and her refusal to speak.</p>
                            <div class="book-meta">
                                <span class="book-genre">Mystery</span>
                                <span class="book-location">📍 East</span>
                            </div>
                            <button class="btn-toggle-details">View Details</button>
                            <div class="book-details hidden">
                                <p><strong>Condition:</strong> Good, minor shelf wear</p>
                                <p><strong>Exchange Preference:</strong> Psychological thriller</p>
                                <p><strong>Owner:</strong> Rachel B.</p>
                                <a href="#" class="btn btn-small">Request Exchange</a>
                            </div>
                        </div>
                    </article>

                    <article class="book-card" data-genre="fiction" data-condition="very-good" data-location="southwest">
                        <div class="book-image">
                            <div class="book-cover" style="background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%);">
                                <span class="book-title-overlay">Circe</span>
                            </div>
                            <span class="book-condition">Very Good</span>
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">Circe</h3>
                            <p class="book-author">by Madeline Miller</p>
                            <p class="book-description">The story of the goddess Circe and her journey from exile to power.</p>
                            <div class="book-meta">
                                <span class="book-genre">Fiction</span>
                                <span class="book-location">📍 southwest</span>
                            </div>
                            <button class="btn-toggle-details">View Details</button>
                            <div class="book-details hidden">
                                <p><strong>Condition:</strong> Very good condition</p>
                                <p><strong>Exchange Preference:</strong> Mythology retellings</p>
                                <p><strong>Owner:</strong> Sophie W.</p>
                                <a href="#" class="btn btn-small">Request Exchange</a>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="no-results hidden" id="noResults">
                    <p>No books found matching your search criteria. Try adjusting your filters.</p>
                </div>
            </div>
        </section>
    </main>
<?php
// Include footer
include 'includes/footer.php';
?>