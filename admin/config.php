<?php
// Admin Configuration
define('ADMIN_USERNAME', 'monika');
// Pre-hashed password for: MonisFellschliff2025!
define('ADMIN_PASSWORD', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

// Paths
define('GALLERY_DIR', __DIR__ . '/../assets/images/gallery/');
define('DATA_FILE', __DIR__ . '/gallery-data.json');
define('GALLERY_HTML', __DIR__ . '/../Galerie.html');

// Security
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// CSRF Protection
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php');
        exit;
    }
}
?>
