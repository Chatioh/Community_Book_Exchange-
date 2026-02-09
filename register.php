<?php
/**
 * Registration Page
 * Module 4: State Management & Application Logic
 */

require_once 'includes/functions.php';
require_once 'includes/db_config.php';
require_once 'includes/auth_functions.php';

// If already logged in, redirect
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$pageTitle = 'Register';

// Handle messages
$errorMessages = [];
if (isset($_SESSION['registration_errors'])) {
    $errorMessages = $_SESSION['registration_errors'];
    unset($_SESSION['registration_errors']);
}

// Preserve form data
$formData = isset($_SESSION['registration_form_data']) ? $_SESSION['registration_form_data'] : [];
if (isset($_SESSION['registration_form_data'])) {
    unset($_SESSION['registration_form_data']);
}

include 'includes/header.php';
?>

<main>
    <section class="auth-section">
        <div class="container">
            <div class="auth-wrapper">
                <div class="auth-box">
                    <div class="auth-header">
                        <h1>Join Our Community</h1>
                        <p>Create your Book Exchange account</p>
                    </div>

                    <?php if (!empty($errorMessages)): ?>
                    <div class="alert alert-error">
                        <strong>Please correct the following errors:</strong>
                        <ul>
                            <?php foreach ($errorMessages as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <form action="process_register.php" method="POST" class="auth-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="username" class="form-label">Username *</label>
                                <input 
                                    type="text" 
                                    id="username" 
                                    name="username" 
                                    class="form-input" 
                                    required
                                    value="<?php echo isset($formData['username']) ? htmlspecialchars($formData['username']) : ''; ?>"
                                    placeholder="Choose a username"
                                >
                                <small class="form-hint">Letters, numbers, and underscores only (min 3 characters)</small>
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Email Address *</label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    class="form-input" 
                                    required
                                    value="<?php echo isset($formData['email']) ? htmlspecialchars($formData['email']) : ''; ?>"
                                    placeholder="your.email@example.com"
                                >
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="full_name" class="form-label">Full Name *</label>
                            <input 
                                type="text" 
                                id="full_name" 
                                name="full_name" 
                                class="form-input" 
                                required
                                value="<?php echo isset($formData['full_name']) ? htmlspecialchars($formData['full_name']) : ''; ?>"
                                placeholder="John Doe"
                            >
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="password" class="form-label">Password *</label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="form-input" 
                                    required
                                    placeholder="Min 6 characters"
                                >
                            </div>

                            <div class="form-group">
                                <label for="confirm_password" class="form-label">Confirm Password *</label>
                                <input 
                                    type="password" 
                                    id="confirm_password" 
                                    name="confirm_password" 
                                    class="form-input" 
                                    required
                                    placeholder="Re-enter password"
                                >
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="phone" 
                                    class="form-input"
                                    value="<?php echo isset($formData['phone']) ? htmlspecialchars($formData['phone']) : ''; ?>"
                                    placeholder="(555) 123-4567"
                                >
                            </div>

                            <div class="form-group">
                                <label for="location" class="form-label">Location</label>
                                <input 
                                    type="text" 
                                    id="location" 
                                    name="location" 
                                    class="form-input"
                                    value="<?php echo isset($formData['location']) ? htmlspecialchars($formData['location']) : ''; ?>"
                                    placeholder="City or neighborhood"
                                >
                            </div>
                        </div>

                        <div class="form-group form-checkbox-group">
                            <label class="checkbox-label">
                                <input 
                                    type="checkbox" 
                                    name="agree_terms" 
                                    class="form-checkbox"
                                    required
                                >
                                <span class="checkbox-text">I agree to the Terms of Service and Privacy Policy</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-full">Create Account</button>
                    </form>

                    <div class="auth-footer">
                        <p>Already have an account? <a href="login.php" class="auth-link">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
