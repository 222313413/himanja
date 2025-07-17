<?php
require_once __DIR__ . '/../config/database.php';
requireSuperAdmin();

$database = new Database();
$db = $database->getConnection();

$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $username = sanitizeInput($_POST['username']);
                $email = sanitizeInput($_POST['email']);
                $password = $_POST['password'];
                $full_name = sanitizeInput($_POST['full_name']);
                $kelas = sanitizeInput($_POST['kelas']);
                $nim = sanitizeInput($_POST['nim']);
                $phone = sanitizeInput($_POST['phone']);
                $role = sanitizeInput($_POST['role']);
                $himada_id = !empty($_POST['himada_id']) ? (int)$_POST['himada_id'] : null;
                
                // Validate email
                if (!isValidSTISEmail($email)) {
                    $error = 'Email harus menggunakan domain @stis.ac.id';
                    break;
                }
                
                // Check if username or email already exists
                $check_query = "SELECT id FROM users WHERE username = :username OR email = :email";
                $check_stmt = $db->prepare($check_query);
                $check_stmt->bindParam(':username', $username);
                $check_stmt->bindParam(':email', $email);
                $check_stmt->execute();
                
                if ($check_stmt->rowCount() > 0) {
                    $error = 'Username atau email sudah digunakan';
                    break;
                }
                
                try {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    $query = "INSERT INTO users (username, email, password, full_name, kelas, nim, phone, role, himada_id, email_verified, is_active) 
                             VALUES (:username, :email, :password, :full_name, :kelas, :nim, :phone, :role, :himada_id, TRUE, TRUE)";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $hashed_password);
                    $stmt->bindParam(':full_name', $full_name);
                    $stmt->bindParam(':kelas', $kelas);
                    $stmt->bindParam(':nim', $nim);
                    $stmt->bindParam(':phone', $phone);
                    $stmt->bindParam(':role', $role);
                    $stmt->bindParam(':himada_id', $himada_id);
                    $stmt->execute();
                    
                    logActivity(getUserId(), 'user_add', "Added user: $username ($role)");
                    $message = 'User berhasil ditambahkan!';
                } catch (Exception $e) {
                    $error = 'Gagal menambahkan user: ' . $e->getMessage();
                }
                break;
                
            case 'edit':
                $user_id = (int)$_POST['user_id'];
                $username = sanitizeInput($_POST['username']);
                $email = sanitizeInput($_POST['email']);
                $full_name = sanitizeInput($_POST['full_name']);
                $kelas = sanitizeInput($_POST['kelas']);
                $nim = sanitizeInput($_POST['nim']);
                $phone = sanitizeInput($_POST['phone']);
                $role = sanitizeInput($_POST['role']);
                $himada_id = !empty($_POST['himada_id']) ? (int)$_POST['himada_id'] : null;
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                
                // Validate email
                if (!isValidSTISEmail($email)) {
                    $error = 'Email harus menggunakan domain @stis.ac.id';
                    break;
                }
                
                // Check if username or email already exists (excluding current user)
                $check_query = "SELECT id FROM users WHERE (username = :username OR email = :email) AND id != :user_id";
                $check_stmt = $db->prepare($check_query);
                $check_stmt->bindParam(':username', $username);
                $check_stmt->bindParam(':email', $email);
                $check_stmt->bindParam(':user_id', $user_id);
                $check_stmt->execute();
                
                if ($check_stmt->rowCount() > 0) {
                    $error = 'Username atau email sudah digunakan';
                    break;
                }
                
                try {
                    $query = "UPDATE users SET username = :username, email = :email, full_name = :full_name, 
                             kelas = :kelas, nim = :nim, phone = :phone, role = :role, himada_id = :himada_id, 
                             is_active = :is_active WHERE id = :user_id";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':full_name', $full_name);
                    $stmt->bindParam(':kelas', $kelas);
                    $stmt->bindParam(':nim', $nim);
                    $stmt->bindParam(':phone', $phone);
                    $stmt->bindParam(':role', $role);
                    $stmt->bindParam(':himada_id', $himada_id);
                    $stmt->bindParam(':is_active', $is_active);
                    $stmt->bindParam(':user_id', $user_id);
                    $stmt->execute();
                    
                    // Update password if provided
                    if (!empty($_POST['password'])) {
                        $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        $pwd_query = "UPDATE users SET password = :password WHERE id = :user_id";
                        $pwd_stmt = $db->prepare($pwd_query);
                        $pwd_stmt->bindParam(':password', $new_password);
                        $pwd_stmt->bindParam(':user_id', $user_id);
                        $pwd_stmt->execute();
                    }
                    
                    logActivity(getUserId(), 'user_edit', "Updated user: $username");
                    $message = 'User berhasil diperbarui!';
                } catch (Exception $e) {
                    $error = 'Gagal memperbarui user: ' . $e->getMessage();
                }
                break;
                
            case 'delete':
                $user_id = (int)$_POST['user_id'];
                
                // Prevent deleting current user
                if ($user_id == getUserId()) {
                    $error = 'Tidak dapat menghapus akun sendiri';
                    break;
                }
                
                try {
                    // Get user info for logging
                    $query = "SELECT username FROM users WHERE id = :user_id";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':user_id', $user_id);
                    $stmt->execute();
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($user) {
                        $query = "DELETE FROM users WHERE id = :user_id";
                        $stmt = $db->prepare($query);
                        $stmt->bindParam(':user_id', $user_id);
                        $stmt->execute();
                        
                        logActivity(getUserId(), 'user_delete', "Deleted user: " . $user['username']);
                        $message = 'User berhasil dihapus!';
                    }
                } catch (Exception $e) {
                    $error = 'Gagal menghapus user: ' . $e->getMessage();
                }
                break;
        }
    }
}

// Get filter parameters
$role_filter = isset($_GET['role']) ? sanitizeInput($_GET['role']) : '';
$himada_filter = isset($_GET['himada']) ? (int)$_GET['himada'] : null;
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';

// Get users with HIMADA info
$query = "SELECT u.*, h.nama as himada_nama, h.warna_tema
          FROM users u 
          LEFT JOIN himada h ON u.himada_id = h.id
          WHERE 1=1";

$params = [];

if ($role_filter) {
    $query .= " AND u.role = :role";
    $params[':role'] = $role_filter;
}

if ($himada_filter) {
    $query .= " AND u.himada_id = :himada_id";
    $params[':himada_id'] = $himada_filter;
}

if ($search) {
    $query .= " AND (u.username LIKE :search OR u.full_name LIKE :search OR u.email LIKE :search OR u.nim LIKE :search)";
    $params[':search'] = "%$search%";
}

$query .= " ORDER BY u.created_at DESC";

$stmt = $db->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get all HIMADA for dropdowns
$himada_query = "SELECT id, nama FROM himada WHERE is_active = 1 ORDER BY nama";
$himada_stmt = $db->prepare($himada_query);
$himada_stmt->execute();
$himadas = $himada_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get user statistics
$stats_query = "SELECT 
    COUNT(CASE WHEN role = 'super_admin' THEN 1 END) as super_admin_count,
    COUNT(CASE WHEN role = 'himada_admin' THEN 1 END) as himada_admin_count,
    COUNT(CASE WHEN role = 'user' THEN 1 END) as user_count,
    COUNT(CASE WHEN is_active = 1 THEN 1 END) as active_count,
    COUNT(CASE WHEN is_active = 0 THEN 1 END) as inactive_count
FROM users";

$stats_stmt = $db->prepare($stats_query);
$stats_stmt->execute();
$stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);

// Get edit user if requested
$edit_user = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $query = "SELECT * FROM users WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $edit_id);
    $stmt->execute();
    $edit_user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Users - Super Admin H!MANJA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin-navbar.css">
    <style>
        .users-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .users-filters {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .filter-group label {
            font-size: 0.9rem;
            font-weight: 500;
            color: #666;
        }
        
        .filter-input {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.9rem;
        }
        
        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #666;
        }
        
        .stat-super-admin .stat-number { color: #e74c3c; }
        .stat-himada-admin .stat-number { color: #3498db; }
        .stat-user .stat-number { color: #2ecc71; }
        .stat-active .stat-number { color: #27ae60; }
        .stat-inactive .stat-number { color: #95a5a6; }
        
        .users-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .table-header {
            background: #f8f9fa;
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
            font-weight: 600;
            color: #333;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr 120px;
            gap: 1rem;
            align-items: center;
        }
        
        .user-item {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr 120px;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
            align-items: center;
        }
        
        .user-item:last-child {
            border-bottom: none;
        }
        
        .user-item:hover {
            background: #f8f9fa;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--soft-blue);
            color: palevioletred;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .user-details h4 {
            margin: 0 0 0.25rem 0;
            font-size: 1rem;
            color: palevioletred;
        }
        
        .user-details p {
            margin: 0;
            font-size: 0.9rem;
            color: black;
        }
        
        .role-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-align: center;
        }
        
        .role-super-admin {
            background: #fee;
            color: #c53030;
        }
        
        .role-himada-admin {
            background: #e6f3ff;
            color: #2b6cb0;
        }
        
        .role-user {
            background: #f0fff4;
            color: #2f855a;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-align: center;
        }
        
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        
        .himada-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            color: white;
            text-align: center;
        }
        
        .user-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-small {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .btn-edit {
            background: #007bff;
            color: white;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }
        
        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            font-family: inherit;
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }
        
        .btn-cancel {
            background: #6c757d;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        
        .btn-submit {
            background: #28a745;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        
        .empty-state .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        @media (max-width: 768px) {
            .users-header {
                flex-direction: column;
                align-items: stretch;
            }
            
            .users-filters {
                flex-direction: column;
            }
            
            .stats-overview {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .table-header,
            .user-item {
                grid-template-columns: 1fr;
                gap: 0.5rem;
                text-align: left;
            }
            
            .table-header {
                display: none;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .modal-content {
                margin: 1rem;
                width: calc(100% - 2rem);
            }
        }
    </style>
</head>
<body class="admin-page">
<!-- Sidebar Overlay untuk Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <div class="logo-icon">🛍️</div>
                <span class="logo-text">H!MANJA</span>
            </div>
            <div class="role-info">
                <div class="role-badge">SUPER ADMIN</div>
                <div class="role-title">Sistem Administrator</div>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="himanja-dashboard.php" class="nav-link">
                        <span class="nav-icon">📊</span>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="manage-himada.php" class="nav-link">
                        <span class="nav-icon">🏛️</span>
                        <span class="nav-text">Kelola HIMADA</span>
                    </a>
                </li>
                <li class="nav-item active">
                    <a href="manage-users.php" class="nav-link">
                        <span class="nav-icon">👥</span>
                        <span class="nav-text">Kelola Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="system-settings.php" class="nav-link">
                        <span class="nav-icon">⚙️</span>
                        <span class="nav-text">Pengaturan Sistem</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <div class="sidebar-footer">
            <a href="../logout.php" class="logout-btn">
                <span class="logout-icon">🚪</span>
                <span class="logout-text">Logout</span>
            </a>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="admin-main">
        <!-- Header -->
        <header class="admin-header">
            <div class="header-left">
                <button class="sidebar-toggle">☰</button>
                <h1 class="page-title">Kelola Users</h1>
            </div>
            <div class="header-right">
                <button class="btn-primary" onclick="openAddModal()">
                    <span>➕</span> Tambah User
                </button>
                <a href="../index.php" class="btn-secondary">
                    <span class="btn-icon">🏠</span>
                    <span class="btn-text">Ke Website</span>
                </a>
            </div>
        </header>
        
        <!-- Content -->
        <div class="dashboard-content">
            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <!-- Statistics Overview -->
            <div class="stats-overview">
                <div class="stat-card stat-super-admin">
                    <div class="stat-number"><?php echo $stats['super_admin_count']; ?></div>
                    <div class="stat-label">Super Admin</div>
                </div>
                <div class="stat-card stat-himada-admin">
                    <div class="stat-number"><?php echo $stats['himada_admin_count']; ?></div>
                    <div class="stat-label">Admin HIMADA</div>
                </div>
                <div class="stat-card stat-user">
                    <div class="stat-number"><?php echo $stats['user_count']; ?></div>
                    <div class="stat-label">Mahasiswa</div>
                </div>
                <div class="stat-card stat-active">
                    <div class="stat-number"><?php echo $stats['active_count']; ?></div>
                    <div class="stat-label">Aktif</div>
                </div>
                <div class="stat-card stat-inactive">
                    <div class="stat-number"><?php echo $stats['inactive_count']; ?></div>
                    <div class="stat-label">Nonaktif</div>
                </div>
            </div>
            
            <!-- Users Header -->
            <div class="users-header">
                <div class="users-filters">
                    <div class="filter-group">
                        <label>Cari User</label>
                        <input type="text" class="filter-input" id="searchInput" placeholder="Username, nama, email, NIM..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="filter-group">
                        <label>Role</label>
                        <select class="filter-input" id="roleFilter">
                            <option value="">Semua Role</option>
                            <option value="super_admin" <?php echo $role_filter === 'super_admin' ? 'selected' : ''; ?>>Super Admin</option>
                            <option value="himada_admin" <?php echo $role_filter === 'himada_admin' ? 'selected' : ''; ?>>Admin HIMADA</option>
                            <option value="user" <?php echo $role_filter === 'user' ? 'selected' : ''; ?>>Mahasiswa</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>HIMADA</label>
                        <select class="filter-input" id="himadaFilter">
                            <option value="">Semua HIMADA</option>
                            <?php foreach ($himadas as $himada): ?>
                                <option value="<?php echo $himada['id']; ?>" <?php echo $himada_filter == $himada['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($himada['nama']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Users Table -->
            <?php if (empty($users)): ?>
                <div class="empty-state">
                    <div class="empty-icon">👥</div>
                    <h3>Tidak Ada User</h3>
                    <p>Belum ada user yang sesuai dengan filter yang dipilih</p>
                </div>
            <?php else: ?>
                <div class="users-table">
                    <div class="table-header">
                        <div>User</div>
                        <div>Kontak</div>
                        <div>Role</div>
                        <div>HIMADA</div>
                        <div>Status</div>
                        <div>Aksi</div>
                    </div>
                    
                    <?php foreach ($users as $user): ?>
                        <div class="user-item">
                            <div class="user-info">
                                <div class="user-avatar">
                                    <?php echo substr($user['full_name'], 0, 1); ?>
                                </div>
                                <div class="user-details">
                                    <h4><?php echo htmlspecialchars($user['full_name']); ?></h4>
                                    <p>@<?php echo htmlspecialchars($user['username']); ?></p>
                                    <?php if ($user['nim']): ?>
                                        <p>NIM: <?php echo htmlspecialchars($user['nim']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div>
                                <p><?php echo htmlspecialchars($user['email']); ?></p>
                                <?php if ($user['kelas']): ?>
                                    <p>Kelas: <?php echo htmlspecialchars($user['kelas']); ?></p>
                                <?php endif; ?>
                                <?php if ($user['phone']): ?>
                                    <p><?php echo htmlspecialchars($user['phone']); ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div>
                                <span class="role-badge role-<?php echo str_replace('_', '-', $user['role']); ?>">
                                    <?php 
                                    switch ($user['role']) {
                                        case 'super_admin': echo 'Super Admin'; break;
                                        case 'himada_admin': echo 'Admin HIMADA'; break;
                                        case 'user': echo 'Mahasiswa'; break;
                                        default: echo ucfirst($user['role']);
                                    }
                                    ?>
                                </span>
                            </div>
                            
                            <div>
                                <?php if ($user['himada_nama']): ?>
                                    <span class="himada-badge" style="background: <?php echo $user['warna_tema'] ?? '#6c757d'; ?>">
                                        <?php echo htmlspecialchars($user['himada_nama']); ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: #999;">-</span>
                                <?php endif; ?>
                            </div>
                            
                            <div>
                                <span class="status-badge status-<?php echo $user['is_active'] ? 'active' : 'inactive'; ?>">
                                    <?php echo $user['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                                </span>
                            </div>
                            
                            <div class="user-actions">
                                <button class="btn-small btn-edit" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($user)); ?>)">
                                    ✏️
                                </button>
                                <?php if ($user['id'] != getUserId()): ?>
                                    <button class="btn-small btn-delete" onclick="confirmDelete(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')">
                                        🗑️
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <!-- Add/Edit User Modal -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <h2 id="modalTitle">Tambah User</h2>
            <form id="userForm" method="POST">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="user_id" id="userId">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="username">Username *</label>
                        <input type="text" name="username" id="username" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email STIS *</label>
                        <input type="email" name="email" id="email" required placeholder="nama@stis.ac.id">
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="full_name">Nama Lengkap *</label>
                        <input type="text" name="full_name" id="full_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password <span id="passwordNote">(kosongkan jika tidak ingin mengubah)</span></label>
                        <input type="password" name="password" id="password">
                    </div>
                    
                    <div class="form-group">
                        <label for="kelas">Kelas</label>
                        <input type="text" name="kelas" id="kelas" placeholder="Contoh: 3SI1">
                    </div>
                    
                    <div class="form-group">
                        <label for="nim">NIM</label>
                        <input type="text" name="nim" id="nim" placeholder="Contoh: 222110001">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Nomor Telepon</label>
                        <input type="tel" name="phone" id="phone" placeholder="081234567890">
                    </div>
                    
                    <div class="form-group">
                        <label for="role">Role *</label>
                        <select name="role" id="role" required onchange="toggleHimadaField()">
                            <option value="">Pilih Role</option>
                            <option value="super_admin">Super Admin</option>
                            <option value="himada_admin">Admin HIMADA</option>
                            <option value="user">Mahasiswa</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="himadaGroup" style="display: none;">
                        <label for="himada_id">HIMADA</label>
                        <select name="himada_id" id="himada_id">
                            <option value="">Pilih HIMADA</option>
                            <?php foreach ($himadas as $himada): ?>
                                <option value="<?php echo $himada['id']; ?>"><?php echo htmlspecialchars($himada['nama']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group full-width" id="activeGroup" style="display: none;">
                        <label>
                            <input type="checkbox" name="is_active" id="is_active" checked>
                            User aktif
                        </label>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h2>Konfirmasi Hapus</h2>
            <p>Apakah Anda yakin ingin menghapus user "<span id="deleteUserName"></span>"?</p>
            <p style="color: #dc3545; font-size: 0.9rem;">Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait.</p>
            
            <form id="deleteForm" method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="user_id" id="deleteUserId">
                
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Batal</button>
                    <button type="submit" class="btn-delete">Hapus</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Modal functions
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Tambah User';
            document.getElementById('formAction').value = 'add';
            document.getElementById('userForm').reset();
            document.getElementById('passwordNote').style.display = 'none';
            document.getElementById('password').required = true;
            document.getElementById('activeGroup').style.display = 'none';
            document.getElementById('himadaGroup').style.display = 'none';
            document.getElementById('userModal').classList.add('active');
        }
        
        function openEditModal(user) {
            document.getElementById('modalTitle').textContent = 'Edit User';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('userId').value = user.id;
            document.getElementById('username').value = user.username;
            document.getElementById('email').value = user.email;
            document.getElementById('full_name').value = user.full_name;
            document.getElementById('kelas').value = user.kelas || '';
            document.getElementById('nim').value = user.nim || '';
            document.getElementById('phone').value = user.phone || '';
            document.getElementById('role').value = user.role;
            document.getElementById('himada_id').value = user.himada_id || '';
            document.getElementById('is_active').checked = user.is_active == 1;
            document.getElementById('passwordNote').style.display = 'inline';
            document.getElementById('password').required = false;
            document.getElementById('activeGroup').style.display = 'block';
            toggleHimadaField();
            document.getElementById('userModal').classList.add('active');
        }
        
        function closeModal() {
            document.getElementById('userModal').classList.remove('active');
        }
        
        function confirmDelete(userId, username) {
            document.getElementById('deleteUserId').value = userId;
            document.getElementById('deleteUserName').textContent = username;
            document.getElementById('deleteModal').classList.add('active');
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }
        
        function toggleHimadaField() {
            const role = document.getElementById('role').value;
            const himadaGroup = document.getElementById('himadaGroup');
            
            if (role === 'himada_admin' || role === 'user') {
                himadaGroup.style.display = 'block';
                if (role === 'himada_admin') {
                    document.getElementById('himada_id').required = true;
                } else {
                    document.getElementById('himada_id').required = false;
                }
            } else {
                himadaGroup.style.display = 'none';
                document.getElementById('himada_id').required = false;
            }
        }
        
        // Filter functions
        function applyFilters() {
            const search = document.getElementById('searchInput').value;
            const role = document.getElementById('roleFilter').value;
            const himada = document.getElementById('himadaFilter').value;
            
            const params = new URLSearchParams();
            if (search) params.set('search', search);
            if (role) params.set('role', role);
            if (himada) params.set('himada', himada);
            
            window.location.href = 'manage-users.php?' + params.toString();
        }
        
        // Event listeners
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
        
        document.getElementById('roleFilter').addEventListener('change', applyFilters);
        document.getElementById('himadaFilter').addEventListener('change', applyFilters);
        
        // Close modal when clicking outside
        document.getElementById('userModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
        
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
        
        // Auto-open edit modal if edit parameter exists
        <?php if ($edit_user): ?>
            openEditModal(<?php echo json_encode($edit_user); ?>);
        <?php endif; ?>
    </script>
</body>
</html>
