<?php
// H!MANJA Database Configuration

class Database {
    private $host = 'localhost';
    private $db_name = 'projec15_himanja';
    private $username = 'projec15_root';
    private $password = '@kaesquare123';
    private $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            error_log("Connection error: " . $exception->getMessage());
            die("Database connection failed. Please check your configuration.");
        }
        
        return $this->conn;
    }
}

// Session Management
function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Authentication Functions
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function isAdmin() {
    return isLoggedIn() && ($_SESSION['role'] === 'super_admin' || $_SESSION['role'] === 'himada_admin');
}

function isSuperAdmin() {
    return isLoggedIn() && $_SESSION['role'] === 'super_admin';
}

function isHimadaAdmin() {
    return isLoggedIn() && $_SESSION['role'] === 'himada_admin';
}

function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

function getUserHimadaId() {
    return $_SESSION['himada_id'] ?? null;
}

function getUserRole() {
    return $_SESSION['role'] ?? 'user';
}

// Access Control
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: index.php');
        exit();
    }
}

function requireSuperAdmin() {
    requireLogin();
    if (!isSuperAdmin()) {
        header('Location: index.php');
        exit();
    }
}

function requireHimadaAdmin() {
    requireLogin();
    if (!isHimadaAdmin()) {
        header('Location: index.php');
        exit();
    }
}

// Validation Functions
function isValidSTISEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) && 
           (str_ends_with($email, '@stis.ac.id') || $email === 'admin@stis.ac.id');
}

function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

// Utility Functions
function formatCurrency($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function logActivity($user_id, $action, $description) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent, created_at) 
                 VALUES (:user_id, :action, :description, :ip_address, :user_agent, NOW())";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':ip_address', $_SERVER['REMOTE_ADDR']);
        $stmt->bindParam(':user_agent', $_SERVER['HTTP_USER_AGENT']);
        $stmt->execute();
    } catch (Exception $e) {
        error_log("Activity log error: " . $e->getMessage());
    }
}

// Initialize session
startSession();
?>
