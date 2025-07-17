<?php
// H!MANJA Logout System - Safe Version
require_once 'config/database.php';

// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Store user info for message
$user_name = $_SESSION['full_name'] ?? 'User';
$user_id = $_SESSION['user_id'] ?? null;

// Safe activity logging (won't break if it fails)
if ($user_id) {
    try {
        logActivity($user_id, 'logout', 'User logged out successfully');
    } catch (Exception $e) {
        // Continue logout even if logging fails
        error_log("Logout activity log failed: " . $e->getMessage());
    }
}

// Destroy session
session_unset();
session_destroy();

// Clear cookies
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}

// Cache control
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Redirect with message
$redirect_url = $_GET['redirect'] ?? 'login.php';
$message = urlencode("Sampai jumpa, $user_name! Logout berhasil.");

if (strpos($redirect_url, '?') !== false) {
    $redirect_url .= "&logout_success=1&message=" . $message;
} else {
    $redirect_url .= "?logout_success=1&message=" . $message;
}

header("Location: " . $redirect_url);
exit();
?>
