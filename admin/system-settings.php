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
            case 'update_general':
                $site_name = sanitizeInput($_POST['site_name']);
                $site_description = sanitizeInput($_POST['site_description']);
                $contact_email = sanitizeInput($_POST['contact_email']);
                $contact_phone = sanitizeInput($_POST['contact_phone']);
                $maintenance_mode = isset($_POST['maintenance_mode']) ? 1 : 0;
                
                try {
                    // Update or insert settings
                    $settings = [
                        'site_name' => $site_name,
                        'site_description' => $site_description,
                        'contact_email' => $contact_email,
                        'contact_phone' => $contact_phone,
                        'maintenance_mode' => $maintenance_mode
                    ];
                    
                    foreach ($settings as $key => $value) {
                        $query = "INSERT INTO system_settings (setting_key, setting_value) VALUES (:key, :value) 
                                 ON DUPLICATE KEY UPDATE setting_value = :value";
                        $stmt = $db->prepare($query);
                        $stmt->bindParam(':key', $key);
                        $stmt->bindParam(':value', $value);
                        $stmt->execute();
                    }
                    
                    logActivity(getUserId(), 'settings_update', 'Updated general settings');
                    $message = 'Pengaturan umum berhasil diperbarui!';
                } catch (Exception $e) {
                    $error = 'Gagal memperbarui pengaturan: ' . $e->getMessage();
                }
                break;
                
            case 'update_email':
                $smtp_host = sanitizeInput($_POST['smtp_host']);
                $smtp_port = (int)$_POST['smtp_port'];
                $smtp_username = sanitizeInput($_POST['smtp_username']);
                $smtp_password = $_POST['smtp_password'];
                $smtp_encryption = sanitizeInput($_POST['smtp_encryption']);
                $email_from_name = sanitizeInput($_POST['email_from_name']);
                $email_from_address = sanitizeInput($_POST['email_from_address']);
                
                try {
                    $settings = [
                        'smtp_host' => $smtp_host,
                        'smtp_port' => $smtp_port,
                        'smtp_username' => $smtp_username,
                        'smtp_password' => $smtp_password,
                        'smtp_encryption' => $smtp_encryption,
                        'email_from_name' => $email_from_name,
                        'email_from_address' => $email_from_address
                    ];
                    
                    foreach ($settings as $key => $value) {
                        $query = "INSERT INTO system_settings (setting_key, setting_value) VALUES (:key, :value) 
                                 ON DUPLICATE KEY UPDATE setting_value = :value";
                        $stmt = $db->prepare($query);
                        $stmt->bindParam(':key', $key);
                        $stmt->bindParam(':value', $value);
                        $stmt->execute();
                    }
                    
                    logActivity(getUserId(), 'settings_update', 'Updated email settings');
                    $message = 'Pengaturan email berhasil diperbarui!';
                } catch (Exception $e) {
                    $error = 'Gagal memperbarui pengaturan email: ' . $e->getMessage();
                }
                break;
                
            case 'clear_cache':
                try {
                    // Clear various cache files
                    $cache_dirs = ['cache/', 'logs/'];
                    foreach ($cache_dirs as $dir) {
                        if (is_dir($dir)) {
                            $files = glob($dir . '*');
                            foreach ($files as $file) {
                                if (is_file($file)) {
                                    unlink($file);
                                }
                            }
                        }
                    }
                    
                    logActivity(getUserId(), 'cache_clear', 'Cleared system cache');
                    $message = 'Cache berhasil dibersihkan!';
                } catch (Exception $e) {
                    $error = 'Gagal membersihkan cache: ' . $e->getMessage();
                }
                break;
                
            case 'backup_database':
                try {
                    $backup_file = 'backups/himanja_backup_' . date('Y-m-d_H-i-s') . '.sql';
                    
                    // Create backups directory if not exists
                    if (!is_dir('backups')) {
                        mkdir('backups', 0755, true);
                    }
                    
                    // Simple backup command (adjust based on your server setup)
                    $command = "mysqldump -h localhost -u root himanja_db > $backup_file";
                    exec($command, $output, $return_var);
                    
                    if ($return_var === 0) {
                        logActivity(getUserId(), 'database_backup', "Created backup: $backup_file");
                        $message = "Backup database berhasil dibuat: $backup_file";
                    } else {
                        $error = 'Gagal membuat backup database';
                    }
                } catch (Exception $e) {
                    $error = 'Gagal membuat backup: ' . $e->getMessage();
                }
                break;
        }
    }
}

// Create system_settings table if not exists
try {
    $create_table_query = "CREATE TABLE IF NOT EXISTS system_settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) UNIQUE NOT NULL,
        setting_value TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $db->exec($create_table_query);
} catch (Exception $e) {
    // Table might already exist
}

// Get current settings
$settings = [];
try {
    $query = "SELECT setting_key, setting_value FROM system_settings";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($results as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} catch (Exception $e) {
    // Settings table might not exist yet
}

// Default values
$defaults = [
    'site_name' => 'H!MANJA - Himada Belanja',
    'site_description' => 'Platform jastip terpercaya untuk mahasiswa STIS',
    'contact_email' => 'info@himanja.stis.ac.id',
    'contact_phone' => '+62 812-3456-7890',
    'maintenance_mode' => '0',
    'smtp_host' => '',
    'smtp_port' => '587',
    'smtp_username' => '',
    'smtp_password' => '',
    'smtp_encryption' => 'tls',
    'email_from_name' => 'H!MANJA',
    'email_from_address' => 'noreply@himanja.stis.ac.id'
];

foreach ($defaults as $key => $value) {
    if (!isset($settings[$key])) {
        $settings[$key] = $value;
    }
}

// Get system information
$system_info = [
    'php_version' => PHP_VERSION,
    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
    'mysql_version' => $db->query('SELECT VERSION()')->fetchColumn(),
    'disk_space' => disk_free_space('.'),
    'memory_limit' => ini_get('memory_limit'),
    'max_execution_time' => ini_get('max_execution_time'),
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'post_max_size' => ini_get('post_max_size')
];

// Get database statistics
$db_stats = [];
try {
    $tables = ['users', 'himada', 'products', 'orders', 'order_items', 'himart_posts'];
    foreach ($tables as $table) {
        $query = "SELECT COUNT(*) as count FROM $table";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $db_stats[$table] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
} catch (Exception $e) {
    // Some tables might not exist
}

// Get recent activity logs
$recent_logs = [];
try {
    $query = "SELECT al.*, u.full_name 
              FROM activity_logs al 
              LEFT JOIN users u ON al.user_id = u.id 
              ORDER BY al.created_at DESC 
              LIMIT 10";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $recent_logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Activity logs table might not exist
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Sistem - Super Admin H!MANJA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin-navbar.css">
    <style>
        .settings-tabs {
            display: flex;
            background: white;
            border-radius: 12px;
            padding: 0.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .tab-button {
            flex: 1;
            padding: 1rem;
            border: none;
            background: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #666;
        }
        
        .tab-button.active {
            background: var(--soft-blue);
            color: palevioletred;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .settings-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            font-family: inherit;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .system-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }
        
        .info-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid var(--soft-blue);
        }
        
        .info-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.25rem;
        }
        
        .info-value {
            font-weight: 600;
            color: #333;
        }
        
        .stats-grid {
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
            color: var(--soft-blue);
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #666;
            text-transform: capitalize;
        }
        
        .activity-log {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .log-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .log-item:last-child {
            border-bottom: none;
        }
        
        .log-info h4 {
            margin: 0 0 0.25rem 0;
            font-size: 1rem;
            color: #333;
        }
        
        .log-info p {
            margin: 0;
            font-size: 0.9rem;
            color: #666;
        }
        
        .log-time {
            font-size: 0.8rem;
            color: #999;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .btn-action {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-primary {
            background: var(--soft-blue);
            color: white;
        }
        
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-action:hover {
            opacity: 0.8;
            transform: translateY(-2px);
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
        
        .maintenance-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            color: #856404;
        }
        
        @media (max-width: 768px) {
            .settings-tabs {
                flex-direction: column;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .system-info-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
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
                <li class="nav-item">
                    <a href="manage-users.php" class="nav-link">
                        <span class="nav-icon">👥</span>
                        <span class="nav-text">Kelola Users</span>
                    </a>
                </li>
                <li class="nav-item active">
                    <a href="system-settings.php" class="nav-link">
                        <span class="nav-icon">⚙️</span>
                        <span class="nav-text">Pengaturan Sistem</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo substr($_SESSION['full_name'], 0, 1); ?>
                </div>
                <div class="user-details">
                    <p class="user-name"><?php echo htmlspecialchars($_SESSION['full_name']); ?></p>
                    <p class="user-role">Super Admin</p>
                </div>
            </div>
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
                <h1 class="page-title">Pengaturan Sistem</h1>
            </div>
            <div class="header-right">
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
            
            <?php if ($settings['maintenance_mode'] == '1'): ?>
                <div class="maintenance-notice">
                    <strong>⚠️ Mode Maintenance Aktif</strong><br>
                    Website sedang dalam mode maintenance. Hanya admin yang dapat mengakses sistem.
                </div>
            <?php endif; ?>
            
            <!-- Settings Tabs -->
            <div class="settings-tabs">
                <button class="tab-button active" onclick="switchTab('general')">
                    ⚙️ Pengaturan Umum
                </button>
                <button class="tab-button" onclick="switchTab('system')">
                    💻 Info Sistem
                </button>
                <button class="tab-button" onclick="switchTab('maintenance')">
                    🔧 Maintenance
                </button>
                <button class="tab-button" onclick="switchTab('logs')">
                    📋 Activity Logs
                </button>
            </div>
            
            <!-- General Settings Tab -->
            <div id="general" class="tab-content active">
                <div class="settings-card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <span>⚙️</span>
                            Pengaturan Umum
                        </h2>
                    </div>
                    
                    <form method="POST">
                        <input type="hidden" name="action" value="update_general">
                        
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label for="site_name">Nama Website</label>
                                <input type="text" name="site_name" id="site_name" value="<?php echo htmlspecialchars($settings['site_name']); ?>" required>
                            </div>
                            
                            <div class="form-group full-width">
                                <label for="site_description">Deskripsi Website</label>
                                <textarea name="site_description" id="site_description" required><?php echo htmlspecialchars($settings['site_description']); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_email">Email Kontak</label>
                                <input type="email" name="contact_email" id="contact_email" value="<?php echo htmlspecialchars($settings['contact_email']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_phone">Nomor Telepon</label>
                                <input type="tel" name="contact_phone" id="contact_phone" value="<?php echo htmlspecialchars($settings['contact_phone']); ?>">
                            </div>
                        </div>
                        
                        <div class="action-buttons">
                            <button type="submit" class="btn-action btn-primary">Simpan Pengaturan</button>
                        </div>
                    </form>
                </div>
            </div>
        
            <!-- System Info Tab -->
            <div id="system" class="tab-content">
                <div class="settings-card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <span>💻</span>
                            Informasi Sistem
                        </h2>
                    </div>
                    
                    <div class="system-info-grid">
                        <div class="info-item">
                            <div class="info-label">PHP Version</div>
                            <div class="info-value"><?php echo $system_info['php_version']; ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Server Software</div>
                            <div class="info-value"><?php echo $system_info['server_software']; ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">MySQL Version</div>
                            <div class="info-value"><?php echo $system_info['mysql_version']; ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Free Disk Space</div>
                            <div class="info-value"><?php echo formatBytes($system_info['disk_space']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Memory Limit</div>
                            <div class="info-value"><?php echo $system_info['memory_limit']; ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Max Execution Time</div>
                            <div class="info-value"><?php echo $system_info['max_execution_time']; ?>s</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Upload Max Filesize</div>
                            <div class="info-value"><?php echo $system_info['upload_max_filesize']; ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Post Max Size</div>
                            <div class="info-value"><?php echo $system_info['post_max_size']; ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="settings-card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <span>📊</span>
                            Statistik Database
                        </h2>
                    </div>
                    
                    <div class="stats-grid">
                        <?php foreach ($db_stats as $table => $count): ?>
                            <div class="stat-card">
                                <div class="stat-number"><?php echo number_format($count); ?></div>
                                <div class="stat-label"><?php echo ucfirst(str_replace('_', ' ', $table)); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Maintenance Tab -->
            <div id="maintenance" class="tab-content">
                <div class="settings-card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <span>🔧</span>
                            Tools Maintenance
                        </h2>
                    </div>
                    
                    <div class="action-buttons">
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="clear_cache">
                            <button type="submit" class="btn-action btn-warning" onclick="return confirm('Yakin ingin membersihkan cache?')">
                                🗑️ Bersihkan Cache
                            </button>
                        </form>
                        
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="backup_database">
                            <button type="submit" class="btn-action btn-success" onclick="return confirm('Yakin ingin membuat backup database?')">
                                💾 Backup Database
                            </button>
                        </form>
                        
                        <button type="button" class="btn-action btn-danger" onclick="optimizeDatabase()">
                            ⚡ Optimasi Database
                        </button>
                        
                        <button type="button" class="btn-action btn-primary" onclick="checkUpdates()">
                            🔄 Cek Update
                        </button>
                    </div>
                    
                    <div style="margin-top: 2rem;">
                        <h3>Informasi Backup</h3>
                        <p>Backup database akan disimpan di folder <code>backups/</code> dengan format nama file: <code>himanja_backup_YYYY-MM-DD_HH-mm-ss.sql</code></p>
                        <p>Disarankan untuk melakukan backup secara berkala, terutama sebelum melakukan update sistem.</p>
                    </div>
                </div>
            </div>
            
            <!-- Activity Logs Tab -->
            <div id="logs" class="tab-content">
                <div class="settings-card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <span>📋</span>
                            Activity Logs Terbaru
                        </h2>
                        <button class="btn-action btn-warning" onclick="clearLogs()">
                            🗑️ Bersihkan Logs
                        </button>
                    </div>
                    
                    <div class="activity-log">
                        <?php if (empty($recent_logs)): ?>
                            <div style="text-align: center; padding: 2rem; color: #666;">
                                <p>Belum ada activity logs</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($recent_logs as $log): ?>
                                <div class="log-item">
                                    <div class="log-info">
                                        <h4><?php echo htmlspecialchars($log['action']); ?></h4>
                                        <p><?php echo htmlspecialchars($log['description']); ?></p>
                                        <p><strong>User:</strong> <?php echo htmlspecialchars($log['full_name'] ?? 'System'); ?></p>
                                        <?php if ($log['ip_address']): ?>
                                            <p><strong>IP:</strong> <?php echo htmlspecialchars($log['ip_address']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="log-time">
                                        <?php echo date('d/m/Y H:i:s', strtotime($log['created_at'])); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <script>
        function switchTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Remove active class from all tab buttons
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => button.classList.remove('active'));
            
            // Show selected tab content
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active');
        }
        
        function testEmail() {
            alert('Fitur test email akan segera tersedia');
        }
        
        function optimizeDatabase() {
            if (confirm('Yakin ingin mengoptimasi database? Proses ini mungkin memakan waktu beberapa menit.')) {
                alert('Fitur optimasi database akan segera tersedia');
            }
        }
        
        function checkUpdates() {
            alert('Fitur cek update akan segera tersedia');
        }
        
        function clearLogs() {
            if (confirm('Yakin ingin menghapus semua activity logs?')) {
                alert('Fitur clear logs akan segera tersedia');
            }
        }
    </script>
</body>
</html>

<?php
function formatBytes($size, $precision = 2) {
    $base = log($size, 1024);
    $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');
    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}
?>
