<?php
// Include functions
require_once 'includes/functions.php';
require_once 'includes/db_config.php';
require_once 'includes/auth_functions.php';
require_once 'includes/wishlist_functions.php';
// Set page title
$pageTitle = 'About & Contact';

// Check for success/error messages
$showSuccess = isset($_GET['success']) && $_GET['success'] == '1' && isset($_SESSION['form_success']);
$showError = isset($_GET['error']) && $_GET['error'] == '1' && isset($_SESSION['contact_errors']);

// Retrieve form data if validation failed (to repopulate form)
$formData = isset($_SESSION['contact_form_data']) ? $_SESSION['contact_form_data'] : [];

// Include header
include 'includes/header.php';
?>

    <main>
        <section class="page-header">
            <div class="container">
                <h1 class="page-title">About Us</h1>
                <p class="page-subtitle">Building a community around the love of reading</p>
            </div>
        </section>

        <section class="about-section">
            <div class="container">
                <div class="about-content">
                    <div class="about-text">
                        <h2 class="section-title">Our Story</h2>
                        <p>Community Book Exchange was born from a simple idea: books are meant to be shared. We noticed too many wonderful books sitting idle on shelves when they could be bringing joy to new readers.</p>
                        <p>Founded in 2023, our platform connects book lovers in local communities, making it easy to exchange books you've finished for ones you're excited to read. We believe in sustainable reading, reducing waste, and building connections through shared stories.</p>
                        <p>Today, we're proud to have facilitated thousands of book exchanges, helping readers discover new favorites while reducing their environmental footprint.</p>
                    </div>
                    <div class="about-image">
                        <div class="image-placeholder">
                            <svg width="100%" height="100%" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="400" height="300" fill="#f4f1ea"/>
                                <g opacity="0.3">
                                    <rect x="80" y="60" width="60" height="180" rx="4" fill="#8B4513"/>
                                    <rect x="150" y="60" width="60" height="180" rx="4" fill="#D2691E"/>
                                    <rect x="220" y="60" width="60" height="180" rx="4" fill="#A0522D"/>
                                    <rect x="290" y="60" width="60" height="180" rx="4" fill="#CD853F"/>
                                </g>
                                <text x="200" y="260" text-anchor="middle" fill="#8B4513" font-size="16" font-family="serif">Community Reading</text>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="values-section">
                    <h2 class="section-title">Our Values</h2>
                    <div class="values-grid">
                        <div class="value-card">
                            <div class="value-icon">📚</div>
                            <h3 class="value-title">Community First</h3>
                            <p class="value-description">We prioritize building meaningful connections between readers in local communities.</p>
                        </div>
                        <div class="value-card">
                            <div class="value-icon">♻️</div>
                            <h3 class="value-title">Sustainability</h3>
                            <p class="value-description">Every book exchanged is a step toward more sustainable reading habits.</p>
                        </div>
                        <div class="value-card">
                            <div class="value-icon">🤝</div>
                            <h3 class="value-title">Trust & Safety</h3>
                            <p class="value-description">We provide guidelines and support to ensure safe, positive exchanges.</p>
                        </div>
                        <div class="value-card">
                            <div class="value-icon">💡</div>
                            <h3 class="value-title">Discovery</h3>
                            <p class="value-description">Helping readers discover new genres, authors, and perspectives through exchange.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="contact-section">
            <div class="container">
                <div class="contact-wrapper">
                    <div class="contact-info">
                        <h2 class="section-title">Get In Touch</h2>
                        <p class="contact-description">Have questions? Want to suggest a feature? We'd love to hear from you!</p>
                        
                        <div class="contact-details">
                            <div class="contact-item">
                                <div class="contact-icon">📧</div>
                                <div class="contact-text">
                                    <h4>Email Us</h4>
                                    <p>hello@bookexchange.com</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon">📞</div>
                                <div class="contact-text">
                                    <h4>Call Us</h4>
                                    <p>+237 676 208 584</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon">📍</div>
                                <div class="contact-text">
                                    <h4>Visit Us</h4>
                                    <p>Cameroon<br>Yaounde, Simbock</p>
                                </div>
                            </div>
                        </div>

                        <div class="social-links">
                            <h4>Follow Us</h4>
                            <div class="social-icons">
                                <a href="#" class="social-link" aria-label="Facebook">FB</a>
                                <a href="#" class="social-link" aria-label="Twitter">TW</a>
                                <a href="#" class="social-link" aria-label="Instagram">IG</a>
                            </div>
                        </div>
                    </div>

                    <div class="contact-form-wrapper">
                        <?php
                        // Display success message
                        if ($showSuccess && isset($_SESSION['contact_form'])) {
                            $submittedData = $_SESSION['contact_form'];
                            echo createSuccessAlert("Thank you for your message, " . htmlspecialchars($submittedData['name']) . "! We've received your inquiry and will respond within 24-48 hours.");
                            
                            echo '<div class="submission-summary">';
                            echo '<h4>Submission Summary:</h4>';
                            echo '<p><strong>Name:</strong> ' . htmlspecialchars($submittedData['name']) . '</p>';
                            echo '<p><strong>Email:</strong> ' . htmlspecialchars($submittedData['email']) . '</p>';
                            echo '<p><strong>Subject:</strong> ' . htmlspecialchars($submittedData['subject']) . '</p>';
                            echo '<p><strong>Submitted:</strong> ' . htmlspecialchars($submittedData['submitted_at']) . '</p>';
                            if ($submittedData['newsletter']) {
                                echo '<p><em>You have been subscribed to our newsletter.</em></p>';
                            }
                            echo '</div>';
                            
                            // Clear success session data
                            unset($_SESSION['form_success']);
                            unset($_SESSION['contact_form']);
                        }
                        
                        // Display error messages
                        if ($showError && isset($_SESSION['contact_errors'])) {
                            $errors = $_SESSION['contact_errors'];
                            echo '<div class="alert alert-error">';
                            echo '<strong>Please correct the following errors:</strong>';
                            echo '<ul>';
                            foreach ($errors as $error) {
                                echo '<li>' . htmlspecialchars($error) . '</li>';
                            }
                            echo '</ul>';
                            echo '</div>';
                            
                            // Clear error session data after display
                            unset($_SESSION['contact_errors']);
                        }
                        ?>
                        
                        <form id="contactForm" class="contact-form" action="process_contact.php" method="POST" novalidate>
                            <h3 class="form-title">Send us a Message</h3>
                            
                            <div class="form-group">
                                <label for="name" class="form-label">Your Name *</label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    class="form-input" 
                                    required
                                    placeholder="John Doe"
                                    value="<?php echo isset($formData['name']) ? htmlspecialchars($formData['name']) : ''; ?>"
                                    aria-required="true"
                                >
                                <span class="form-error" id="nameError"></span>
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Email Address *</label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    class="form-input" 
                                    required
                                    placeholder="john@example.com"
                                    value="<?php echo isset($formData['email']) ? htmlspecialchars($formData['email']) : ''; ?>"
                                    aria-required="true"
                                >
                                <span class="form-error" id="emailError"></span>
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="phone" 
                                    class="form-input"
                                    placeholder="+237 676 208 584"
                                    value="<?php echo isset($formData['phone']) ? htmlspecialchars($formData['phone']) : ''; ?>"
                                >
                                <span class="form-error" id="phoneError"></span>
                            </div>

                            <div class="form-group">
                                <label for="subject" class="form-label">Subject *</label>
                                <select 
                                    id="subject" 
                                    name="subject" 
                                    class="form-input form-select" 
                                    required
                                    aria-required="true"
                                >
                                    <option value="">Select a subject</option>
                                    <option value="general" <?php echo (isset($formData['subject']) && $formData['subject'] == 'general') ? 'selected' : ''; ?>>General Inquiry</option>
                                    <option value="support" <?php echo (isset($formData['subject']) && $formData['subject'] == 'support') ? 'selected' : ''; ?>>Technical Support</option>
                                    <option value="feedback" <?php echo (isset($formData['subject']) && $formData['subject'] == 'feedback') ? 'selected' : ''; ?>>Feedback or Suggestion</option>
                                    <option value="report" <?php echo (isset($formData['subject']) && $formData['subject'] == 'report') ? 'selected' : ''; ?>>Report an Issue</option>
                                    <option value="partnership" <?php echo (isset($formData['subject']) && $formData['subject'] == 'partnership') ? 'selected' : ''; ?>>Partnership Opportunity</option>
                                </select>
                                <span class="form-error" id="subjectError"></span>
                            </div>

                            <div class="form-group">
                                <label for="message" class="form-label">Message *</label>
                                <textarea 
                                    id="message" 
                                    name="message" 
                                    class="form-input form-textarea" 
                                    rows="6" 
                                    required
                                    placeholder="Tell us what's on your mind..."
                                    aria-required="true"
                                ><?php echo isset($formData['message']) ? htmlspecialchars($formData['message']) : ''; ?></textarea>
                                <span class="form-error" id="messageError"></span>
                            </div>

                            <div class="form-group form-checkbox-group">
                                <label class="checkbox-label">
                                    <input 
                                        type="checkbox" 
                                        id="newsletter" 
                                        name="newsletter" 
                                        class="form-checkbox"
                                        <?php echo (isset($formData['newsletter']) && $formData['newsletter']) ? 'checked' : ''; ?>
                                    >
                                    <span class="checkbox-text">Subscribe to our newsletter for book recommendations and community updates</span>
                                </label>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-full">Send Message</button>
                                <button type="reset" class="btn btn-outline btn-full">Clear Form</button>
                            </div>

                            <p class="form-note">* Required fields</p>
                        </form>
                        
                        <?php
                        // Clear form data from session after displaying
                        if (isset($_SESSION['contact_form_data'])) {
                            unset($_SESSION['contact_form_data']);
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>

        <section class="faq-section">
            <div class="container">
                <h2 class="section-title">Frequently Asked Questions</h2>
                <div class="faq-grid">
                    <div class="faq-item">
                        <h3 class="faq-question">Is the service free?</h3>
                        <p class="faq-answer">Yes! Community Book Exchange is completely free to use. We believe books should be accessible to everyone.</p>
                    </div>
                    <div class="faq-item">
                        <h3 class="faq-question">How do exchanges work?</h3>
                        <p class="faq-answer">Browse available books, send an exchange request, and arrange a safe meetup with the book owner. You can exchange 1-for-1 or simply give books to the community.</p>
                    </div>
                    <div class="faq-item">
                        <h3 class="faq-question">What if I don't have books to trade?</h3>
                        <p class="faq-answer">Many members are happy to give books away! Just specify in your request that you're looking for a giveaway rather than a trade.</p>
                    </div>
                    <div class="faq-item">
                        <h3 class="faq-question">How do I ensure safe meetups?</h3>
                        <p class="faq-answer">We recommend meeting in public places like libraries, coffee shops, or community centers. Always prioritize your safety.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

<?php
// Include footer
include 'includes/footer.php';
?>
