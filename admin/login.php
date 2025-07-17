<?php
require_once __DIR__ . '/../config/database.php';
startSession();

// Redirect if already logged in
if (isLoggedIn()) {
    $role = getUserRole();
    if ($role === 'super_admin') {
        header('Location: admin/himanja-dashboard.php');
    } elseif ($role === 'himada_admin') {
        header('Location: admin/himada-dashboard.php');
    } else {
        header('Location: index.php');
    }
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi';
    } else {
        try {
            // Query user by username or email
            $query = "SELECT u.id, u.username, u.email, u.password, u.full_name, u.kelas, u.role, u.himada_id, u.is_active,
                             h.nama as himada_nama
                     FROM users u 
                     LEFT JOIN himada h ON u.himada_id = h.id
                     WHERE (u.username = :username OR u.email = :username) AND u.is_active = TRUE";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                // Validate STIS email
                if (!isValidSTISEmail($user['email'])) {
                    $error = 'Hanya email @stis.ac.id yang diizinkan untuk mengakses sistem';
                } else {
                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['kelas'] = $user['kelas'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['himada_id'] = $user['himada_id'];
                    $_SESSION['himada_nama'] = $user['himada_nama'];
                    
                    // Update last login
                    $updateQuery = "UPDATE users SET last_login = NOW() WHERE id = :user_id";
                    $updateStmt = $db->prepare($updateQuery);
                    $updateStmt->bindParam(':user_id', $user['id']);
                    $updateStmt->execute();
                    
                    // Log login activity
                    logActivity($user['id'], 'login', 'User logged in successfully');
                    
                    // Redirect based on role
                    if ($user['role'] === 'super_admin') {
                        header('Location: admin/himanja-dashboard.php');
                    } elseif ($user['role'] === 'himada_admin') {
                        header('Location: admin/himada-dashboard.php');
                    } else {
                        header('Location: index.php');
                    }
                    exit();
                }
            } else {
                $error = 'Username/email atau password salah';
                // Log failed login attempt
                if ($user) {
                    logActivity($user['id'], 'login_failed', 'Failed login attempt for user: ' . $username);
                }
            }
        } catch (Exception $e) {
            $error = 'Terjadi kesalahan sistem. Silakan coba lagi.';
            error_log("Login error: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - H!MANJA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <div class="logo-icon">🛍️</div>
                    <span class="logo-text">H!MANJA</span>
                </div>
                <h1 class="auth-title">Selamat Datang Kembali!</h1>
                <p class="auth-subtitle">Masuk ke akun H!MANJA Anda</p>
                <div class="auth-notice">
                    <span class="notice-icon">ℹ️</span>
                    <span class="notice-text">Hanya email @stis.ac.id yang dapat mengakses sistem</span>
                </div>
            </div>
            
            <form class="auth-form" method="POST" action="">
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <span class="alert-icon">⚠️</span>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <span class="alert-icon">✅</span>
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="username" class="form-label">Username atau Email STIS</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="form-input" 
                        placeholder="Masukkan username atau email @stis.ac.id"
                        value="<?php echo htmlspecialchars($username ?? ''); ?>"
                        required
                        autocomplete="username"
                    >
                    <div class="input-hint">
                        <span class="hint-icon">💡</span>
                        <span class="hint-text">Gunakan email STIS Anda (contoh: nama@stis.ac.id)</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-input-container">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input" 
                            placeholder="Masukkan password"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            👁️
                        </button>
                    </div>
                </div>
                
                <div class="form-options">
                    <label class="checkbox-container">
                        <input type="checkbox" name="remember" value="1">
                        <span class="checkmark"></span>
                        Ingat saya
                    </label>
                    <a href="forgot-password.php" class="forgot-link">Lupa password?</a>
                </div>
                
                <button type="submit" class="btn-auth btn-primary" id="loginBtn">
                    <span class="btn-text">Masuk</span>
                    <span class="btn-loading" style="display: none;">
                        <span class="loading-spinner"></span>
                        Memproses...
                    </span>
                </button>
                
                <div class="demo-accounts">
                    <h4>🔑 Akun Demo untuk Testing:</h4>
                    <div class="demo-list">
                        <div class="demo-item" onclick="fillLogin('admin@stis.ac.id', 'password')">
                            <strong>Super Admin:</strong> admin@stis.ac.id / password
                            <span class="demo-fill">Klik untuk isi otomatis</span>
                        </div>
                        <div class="demo-item" onclick="fillLogin('admin.gist@stis.ac.id', 'password')">
                            <strong>Admin GIST:</strong> admin.gist@stis.ac.id / password
                            <span class="demo-fill">Klik untuk isi otomatis</span>
                        </div>
                        <div class="demo-item" onclick="fillLogin('mahasiswa@stis.ac.id', 'password')">
                            <strong>Mahasiswa:</strong> mahasiswa@stis.ac.id / password
                            <span class="demo-fill">Klik untuk isi otomatis</span>
                        </div>
                    </div>
                </div>
            </form>
            
            <div class="auth-footer">
                <p>Belum punya akun? <a href="register.php" class="auth-link">Daftar sekarang</a></p>
                <div class="auth-divider">
                    <span>atau</span>
                </div>
                <a href="index.php" class="btn-secondary">Kembali ke Beranda</a>
            </div>
        </div>
        
        <div class="auth-visual">
            <div class="visual-content">
                <h2 class="visual-title">Jajan Lokal, Rasa Nasional!</h2>
                <p class="visual-description">
                    Bergabunglah dengan ribuan mahasiswa STIS yang sudah merasakan kemudahan berbelanja produk khas daerah dari 20 HIMADA.
                </p>
                <div class="visual-features">
                    <div class="feature-item">
                        <span class="feature-icon">🛍️</span>
                        <span class="feature-text">Produk Berkualitas</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">🚚</span>
                        <span class="feature-text">Pengiriman Terpercaya</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">💰</span>
                        <span class="feature-text">Harga Terjangkau</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">👥</span>
                        <span class="feature-text">Khusus Mahasiswa STIS</span>
                    </div>
                </div>
                
                <div class="role-info">
                    <h3>Jenis Akun:</h3>
                    <div class="role-list">
                        <div class="role-item">
                            <span class="role-icon">👑</span>
                            <div class="role-content">
                                <strong>Super Admin</strong>
                                <p>Mengelola seluruh sistem dan semua HIMADA</p>
                            </div>
                        </div>
                        <div class="role-item">
                            <span class="role-icon">🏛️</span>
                            <div class="role-content">
                                <strong>Admin HIMADA</strong>
                                <p>Mengelola produk dan pesanan HIMADA masing-masing</p>
                            </div>
                        </div>
                        <div class="role-item">
                            <span class="role-icon">🎓</span>
                            <div class="role-content">
                                <strong>Mahasiswa</strong>
                                <p>Berbelanja produk dari berbagai HIMADA</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="visual-decoration">
                <div class="floating-element" style="top: 10%; left: 10%;">🍜</div>
                <div class="floating-element" style="top: 20%; right: 15%;">👕</div>
                <div class="floating-element" style="bottom: 30%; left: 20%;">🔑</div>
                <div class="floating-element" style="bottom: 20%; right: 10%;">🎁</div>
            </div>
        </div>
    </div>
    
    <script src="assets/js/auth.js"></script>
    <script>
        // Auto-fill demo accounts
        function fillLogin(email, password) {
            document.getElementById('username').value = email;
            document.getElementById('password').value = password;
            
            // Add visual feedback
            const demoItems = document.querySelectorAll('.demo-item');
            demoItems.forEach(item => item.classList.remove('selected'));
            event.currentTarget.classList.add('selected');
        }
        
        // Form submission with loading state
        document.querySelector('.auth-form').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            const btnText = btn.querySelector('.btn-text');
            const btnLoading = btn.querySelector('.btn-loading');
            
            btnText.style.display = 'none';
            btnLoading.style.display = 'flex';
            btn.disabled = true;
        });
        
        // Password toggle
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input.nextElementSibling;
            
            if (input.type === 'password') {
                input.type = 'text';
                button.textContent = '🙈';
            } else {
                input.type = 'password';
                button.textContent = '👁️';
            }
        }
    </script>
    
    <style>
        .demo-item {
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 8px;
            border-radius: 6px;
            position: relative;
        }
        
        .demo-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        
        .demo-item.selected {
            background: rgba(255, 255, 255, 0.2);
            border-left: 3px solid #fff;
        }
        
        .demo-fill {
            display: block;
            font-size: 0.7rem;
            opacity: 0.7;
            margin-top: 2px;
        }
        
        .input-hint {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            font-size: 0.8rem;
            color: #6b7280;
        }
        
        .auth-notice {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 8px;
            padding: 12px;
            margin-top: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .notice-text {
            font-size: 0.9rem;
            color: #1e40af;
        }
    </style>
</body>
</html>
