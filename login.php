<?php
/**
 * Login Page
 * Module 4: State Management & Application Logic
 */

require_once 'includes/functions.php';
require_once 'includes/db_config.php';
require_once 'includes/auth_functions.php';

// If already logged in, redirect to index
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

// Set page title
$pageTitle = 'Login';

// Handle messages
$errorMessage = '';
$successMessage = '';

if (isset($_SESSION['error_message'])) {
    $errorMessage = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

if (isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

// Preserve form data if validation failed
$formData = isset($_SESSION['login_form_data']) ? $_SESSION['login_form_data'] : [];
if (isset($_SESSION['login_form_data'])) {
    unset($_SESSION['login_form_data']);
}

// Include header
include 'includes/header.php';
?>

<main>
    <section class="auth-section">
        <div class="container">
            <div class="auth-wrapper">
                <div class="auth-box">
                    <div class="auth-header">
                        <h1>Welcome Back</h1>
                        <p>Login to your Book Exchange account</p>
                    </div>

                    <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($errorMessage); ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($successMessage)): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($successMessage); ?>
                    </div>
                    <?php endif; ?>

                    <form action="process_login.php" method="POST" class="auth-form">
                        <div class="form-group">
                            <label for="username" class="form-label">Username or Email</label>
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                class="form-input" 
                                required
                                value="<?php echo isset($formData['username']) ? htmlspecialchars($formData['username']) : ''; ?>"
                                placeholder="Enter your username or email"
                            >
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-input" 
                                required
                                placeholder="Enter your password"
                            >
                        </div>

                        <div class="form-group form-checkbox-group">
                            <label class="checkbox-label">
                                <input 
                                    type="checkbox" 
                                    name="remember_me" 
                                    class="form-checkbox"
                                >
                                <span class="checkbox-text">Remember me</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-full">Login</button>
                    </form>

                    <div class="auth-footer">
                        <p>Don't have an account? <a href="register.php" class="auth-link">Register here</a></p>
                    </div>

                    <div class="demo-credentials">
                        <h4>Demo Credentials</h4>
                        <p><strong>Username:</strong> dens</p>
                        <p><strong>Password:</strong> densdens</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
