<?php
// Admin Configuration
define('ADMIN_USERNAME', 'monika');
define('ADMIN_PASSWORD', password_hash('MonisFellschliff2025!', PASSWORD_DEFAULT)); // ÄNDERN SIE DIES!

// Paths
define('GALLERY_DIR', '../assets/images/gallery/');
define('DATA_FILE', __DIR__ . '/gallery-data.json');
define('GALLERY_HTML', '../Galerie.html');

// Security
session_start();

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
