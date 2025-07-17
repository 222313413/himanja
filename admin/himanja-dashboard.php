<?php
require_once __DIR__ . '/../config/database.php';
requireLogin();

$database = new Database();
$db = $database->getConnection();
$user_role = getUserRole();
$user_id = getUserId();
$himada_id = getUserHimadaId();

// Redirect logic berdasarkan role - pastikan user di tempat yang benar
if ($user_role === 'user') {
    header('Location: ../dashboard.php');
    exit();
}

// Get user info
$user_info = [
    'id' => $user_id,
    'name' => $_SESSION['full_name'],
    'email' => $_SESSION['email'],
    'role' => $user_role,
    'himada_id' => $himada_id,
    'himada_name' => $_SESSION['himada_nama'] ?? null
];

// Initialize variables
$himada = null;
$stats = [];
$recent_orders = [];
$low_stock_products = [];
$all_himada = [];

// SUPER ADMIN LOGIC
if ($user_role === 'super_admin') {
    // Get all HIMADA for super admin
    $query = "SELECT * FROM himada WHERE is_active = 1 ORDER BY nama";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $all_himada = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Super admin stats - system wide
    $stats = [
        'total_himada' => count($all_himada),
        'total_users' => 0,
        'total_products' => 0,
        'total_orders' => 0,
        'monthly_revenue' => 0
    ];
    
    // Get total users
    $query = "SELECT COUNT(*) as count FROM users WHERE is_active = 1";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $stats['total_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Get total products
    $query = "SELECT COUNT(*) as count FROM products WHERE is_available = 1";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $stats['total_products'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Get total orders
    $query = "SELECT COUNT(*) as count FROM orders";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $stats['total_orders'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Get monthly revenue
    $query = "SELECT COALESCE(SUM(total_amount), 0) as revenue 
              FROM orders 
              WHERE status = 'completed'
              AND MONTH(created_at) = MONTH(CURRENT_DATE())
              AND YEAR(created_at) = YEAR(CURRENT_DATE())";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $stats['monthly_revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'];
    
    // Recent orders for super admin (all orders)
    $query = "SELECT o.*, u.full_name AS customer_name, h.nama AS himada_name
                FROM orders o
                JOIN users u ON o.user_id = u.id
                LEFT JOIN order_items oi ON o.id = oi.order_id
                LEFT JOIN himada h ON oi.himada_id = h.id
                ORDER BY o.created_at DESC
                LIMIT 10";
            
    $stmt = $db->prepare($query);
    $stmt->execute();
    $recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// HIMADA ADMIN LOGIC
elseif ($user_role === 'himada_admin') {
    if (!$himada_id) {
        die('Error: HIMADA ID tidak ditemukan untuk admin ini.');
    }
    
    // Get HIMADA info
    $query = "SELECT * FROM himada WHERE id = :himada_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':himada_id', $himada_id);
    $stmt->execute();
    $himada = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$himada) {
        die('Error: Data HIMADA tidak ditemukan.');
    }
    
    // HIMADA admin stats
    $stats = [
        'total_products' => 0,
        'pending_orders' => 0,
        'monthly_revenue' => 0,
        'low_stock' => 0
    ];
    
    // Total products
    $query = "SELECT COUNT(*) as count FROM products WHERE himada_id = :himada_id AND is_available = TRUE";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':himada_id', $himada_id);
    $stmt->execute();
    $stats['total_products'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Pending orders
    $query = "SELECT COUNT(DISTINCT oi.order_id) as count 
              FROM order_items oi 
              WHERE oi.himada_id = :himada_id AND oi.status IN ('pending', 'confirmed', 'processing')";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':himada_id', $himada_id);
    $stmt->execute();
    $stats['pending_orders'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Monthly revenue
    $query = "SELECT COALESCE(SUM(oi.subtotal), 0) as revenue 
              FROM order_items oi 
              WHERE oi.himada_id = :himada_id 
              AND oi.status IN ('completed', 'ready')
              AND MONTH(oi.created_at) = MONTH(CURRENT_DATE())
              AND YEAR(oi.created_at) = YEAR(CURRENT_DATE())";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':himada_id', $himada_id);
    $stmt->execute();
    $stats['monthly_revenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'];
    
    // Low stock products
    $query = "SELECT COUNT(*) as count FROM products WHERE himada_id = :himada_id AND stok <= stok_minimum";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':himada_id', $himada_id);
    $stmt->execute();
    $stats['low_stock'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Recent orders for this HIMADA
    $query = "SELECT DISTINCT o.id, o.order_number, o.created_at, u.full_name as customer_name,
                     COUNT(oi.id) as item_count, SUM(oi.subtotal) as total_amount,
                     GROUP_CONCAT(DISTINCT oi.status) as statuses
              FROM orders o
              JOIN users u ON o.user_id = u.id
              JOIN order_items oi ON o.id = oi.order_id
              WHERE oi.himada_id = :himada_id
              GROUP BY o.id, o.order_number, o.created_at, u.full_name
              ORDER BY o.created_at DESC
              LIMIT 10";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':himada_id', $himada_id);
    $stmt->execute();
    $recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Low stock products detail
    $query = "SELECT id, nama_produk, stok, stok_minimum, kategori
              FROM products 
              WHERE himada_id = :himada_id AND stok <= stok_minimum
              ORDER BY stok ASC
              LIMIT 5";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':himada_id', $himada_id);
    $stmt->execute();
    $low_stock_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get unread notifications
$unread_notifications = 0;
try {
    $query = "SELECT COUNT(*) as count FROM notifications WHERE user_id = :user_id AND is_read = FALSE";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $unread_notifications = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
} catch (Exception $e) {
    // Table might not exist
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php if ($user_role === 'super_admin'): ?>
            Dashboard Super Admin - H!MANJA
        <?php else: ?>
            Dashboard Admin <?php echo htmlspecialchars($himada['nama'] ?? 'HIMADA'); ?> - H!MANJA
        <?php endif; ?>
    </title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin-navbar.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            color: #333;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        
        /* Main Content */
        .admin-main {
            flex: 1;
            margin-left: 280px;
            min-height: 100vh;
        }
        
        .admin-header {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .current-time {
            font-size: 0.9rem;
            color: #666;
        }
        
        .btn-secondary {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background:#98a0ee;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: background 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: #d4a7ff;
        }
        
        /* Dashboard Content */
        .dashboard-content {
            padding: 30px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card.warning {
            border-left: 4px solid #ffa502;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        .stat-icon.products { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-icon.orders { background: linear-gradient(135deg, #ffa502 0%, #ff6348 100%); }
        .stat-icon.revenue { background: linear-gradient(135deg, #2ed573 0%, #1e90ff 100%); }
        .stat-icon.stock { background: linear-gradient(135deg, #ff4757 0%, #ff3742 100%); }
        .stat-icon.himada { background: linear-gradient(135deg, #5352ed 0%, #3742fa 100%); }
        .stat-icon.users { background: linear-gradient(135deg, #2f3542 0%, #57606f 100%); }
        
        .stat-content {
            flex: 1;
        }
        
        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .stat-action {
            margin-left: auto;
        }
        
        .stat-link {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .stat-link:hover {
            text-decoration: underline;
        }
        
        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }
        
        .dashboard-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .card-header {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card-action {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .card-action:hover {
            text-decoration: underline;
        }
        
        .card-content {
            padding: 20px;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }
        
        .empty-state.success {
            color: #2ed573;
        }
        
        .empty-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }
        
        .empty-text {
            font-size: 1rem;
        }
        
        /* Orders List */
        .orders-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .order-info h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        
        .order-info p {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 3px;
        }
        
        .order-details {
            text-align: right;
        }
        
        .order-items {
            font-size: 0.9rem;
            color: #666;
        }
        
        .order-total {
            font-weight: 600;
            color: #333;
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-badge.pending { background: #fff3cd; color: #856404; }
        .status-badge.confirmed { background: #d4edda; color: #155724; }
        .status-badge.processing { background: #cce5ff; color: #004085; }
        .status-badge.ready { background: #e2e3e5; color: #383d41; }
        .status-badge.completed { background: #d4edda; color: #155724; }
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .quick-action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }
        
        .quick-action-btn:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }
        
        .action-icon {
            font-size: 1.5rem;
        }
        
        .action-text {
            font-size: 0.9rem;
            font-weight: 500;
            text-align: center;
        }
        
        /* Stock Alerts */
        .stock-alerts {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .stock-alert-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            background: #fff3cd;
            border-radius: 8px;
            border-left: 4px solid #ffa502;
        }
        
        .product-info h4 {
            font-size: 0.9rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 3px;
        }
        
        .product-category {
            font-size: 0.8rem;
            color: #666;
        }
        
        .current-stock {
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
        }
        
        .current-stock.low-stock {
            background: #ffa502;
            color: white;
        }
        
        .current-stock.out-of-stock {
            background: #ff4757;
            color: white;
        }
        
        .btn-small {
            padding: 6px 12px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .btn-small:hover {
            background: #5a67d8;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .admin-sidebar.active {
                transform: translateX(0);
            }
            
            .admin-main {
                margin-left: 0;
            }
            
            .sidebar-toggle {
                display: block;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
    <!-- Sidebar Overlay untuk Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <div class="logo-icon">🛍️</div>
                    <span class="logo-text">H!MANJA</span>
                </div>
                
                <?php if ($user_role === 'super_admin'): ?>
                    <div class="role-info">
                        <div class="role-badge">SUPER ADMIN</div>
                        <div class="role-title">Sistem Administrator</div>
                    </div>
                <?php else: ?>
                    <div class="himada-info">
                        <div class="himada-badge" style="background: <?php echo $himada['warna_tema'] ?? '#667eea'; ?>">
                            <?php echo htmlspecialchars($himada['nama'] ?? 'HIMADA'); ?>
                        </div>
                        <p class="himada-admin">Admin Dashboard</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <nav class="sidebar-nav">
                <ul class="nav-menu">
                    <li class="nav-item active">
                        <a href="himanja-dashboard.php" class="nav-link">
                            <span class="nav-icon">📊</span>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    
                    <?php if ($user_role === 'super_admin'): ?>
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
                        <li class="nav-item">
                            <a href="system-settings.php" class="nav-link">
                                <span class="nav-icon">⚙️</span>
                                <span class="nav-text">Pengaturan Sistem</span>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a href="products.php" class="nav-link">
                                <span class="nav-icon">🛍️</span>
                                <span class="nav-text">Kelola Produk</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="orders.php" class="nav-link">
                                <span class="nav-icon">📦</span>
                                <span class="nav-text">Pesanan</span>
                                <?php if ($stats['pending_orders'] > 0): ?>
                                    <span class="nav-badge"><?php echo $stats['pending_orders']; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="stock.php" class="nav-link">
                                <span class="nav-icon">📋</span>
                                <span class="nav-text">Stok Barang</span>
                                <?php if ($stats['low_stock'] > 0): ?>
                                    <span class="nav-badge warning"><?php echo $stats['low_stock']; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="himart.php" class="nav-link">
                                <span class="nav-icon">🎨</span>
                                <span class="nav-text">H!Story</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo substr($user_info['name'], 0, 1); ?>
                    </div>
                    <div class="user-details">
                        <p class="user-name"><?php echo htmlspecialchars($user_info['name']); ?></p>
                        <p class="user-role">
                            <?php 
                            if ($user_role === 'super_admin') {
                                echo 'Super Admin';
                            } else {
                                echo 'Admin ' . htmlspecialchars($himada['nama'] ?? 'HIMADA');
                            }
                            ?>
                        </p>
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
                    <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
                    <h1 class="page-title">
                        <?php if ($user_role === 'super_admin'): ?>
                            Dashboard Super Admin
                        <?php else: ?>
                            Dashboard Admin <?php echo htmlspecialchars($himada['nama'] ?? 'HIMADA'); ?>
                        <?php endif; ?>
                    </h1>
                </div>
                <div class="header-right">
                    <div class="header-stats">
                        <span class="current-time" id="currentTime"></span>
                    </div>
                    <a href="../index.php" class="btn-secondary">
                        <span class="btn-icon">🏠</span>
                        <span class="btn-text">Ke Website</span>
                    </a>
                </div>
            </header>
            
            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <?php if ($user_role === 'super_admin'): ?>
                        <!-- Super Admin Stats -->
                        <div class="stat-card">
                            <div class="stat-icon himada">🏛️</div>
                            <div class="stat-content">
                                <h3 class="stat-number"><?php echo $stats['total_himada']; ?></h3>
                                <p class="stat-label">Total HIMADA</p>
                            </div>
                            <div class="stat-action">
                                <a href="manage-himada.php" class="stat-link">Kelola →</a>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon users">👥</div>
                            <div class="stat-content">
                                <h3 class="stat-number"><?php echo $stats['total_users']; ?></h3>
                                <p class="stat-label">Total Users</p>
                            </div>
                            <div class="stat-action">
                                <a href="manage-users.php" class="stat-link">Kelola →</a>
                            </div>
                        </div>
                        
                    <?php else: ?>
                        <!-- HIMADA Admin Stats -->
                        <div class="stat-card">
                            <div class="stat-icon products">🛍️</div>
                            <div class="stat-content">
                                <h3 class="stat-number"><?php echo $stats['total_products']; ?></h3>
                                <p class="stat-label">Total Produk</p>
                            </div>
                            <div class="stat-action">
                                <a href="products.php" class="stat-link">Kelola →</a>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon orders">📦</div>
                            <div class="stat-content">
                                <h3 class="stat-number"><?php echo $stats['pending_orders']; ?></h3>
                                <p class="stat-label">Pesanan Pending</p>
                            </div>
                            <div class="stat-action">
                                <a href="orders.php" class="stat-link">Proses →</a>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon revenue">💰</div>
                            <div class="stat-content">
                                <h3 class="stat-number"><?php echo formatCurrency($stats['monthly_revenue']); ?></h3>
                                <p class="stat-label">Pendapatan Bulan Ini</p>
                            </div>
                        </div>
                        
                        <div class="stat-card <?php echo $stats['low_stock'] > 0 ? 'warning' : ''; ?>">
                            <div class="stat-icon stock">📋</div>
                            <div class="stat-content">
                                <h3 class="stat-number"><?php echo $stats['low_stock']; ?></h3>
                                <p class="stat-label">Stok Menipis</p>
                            </div>
                            <div class="stat-action">
                                <a href="stock.php" class="stat-link">Cek →</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Main Dashboard Grid -->
                <div class="dashboard-grid">
                    <!-- Recent Orders -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h2 class="card-title">
                                <span class="card-icon">📦</span>
                                <?php echo $user_role === 'super_admin' ? 'Pesanan Terbaru (Semua HIMADA)' : 'Pesanan Terbaru'; ?>
                            </h2>
                            <a href="orders.php" class="card-action">Lihat Semua</a>
                        </div>
                        <div class="card-content">
                            <?php if (empty($recent_orders)): ?>
                                <div class="empty-state">
                                    <div class="empty-icon">📦</div>
                                    <p class="empty-text">Belum ada pesanan</p>
                                </div>
                            <?php else: ?>
                                <div class="orders-list">
                                    <?php foreach ($recent_orders as $order): ?>
                                        <div class="order-item">
                                            <div class="order-info">
                                                <h4><?php echo htmlspecialchars($order['order_number']); ?></h4>
                                                <p><?php echo htmlspecialchars($order['customer_name']); ?></p>
                                                <?php if ($user_role === 'super_admin' && isset($order['himada_name'])): ?>
                                                    <p><strong>HIMADA:</strong> <?php echo htmlspecialchars($order['himada_name']); ?></p>
                                                <?php endif; ?>
                                                <p><?php echo date('d M Y H:i', strtotime($order['created_at'])); ?></p>
                                            </div>
                                            <div class="order-details">
                                                <?php if (isset($order['item_count'])): ?>
                                                    <p class="order-items"><?php echo $order['item_count']; ?> item</p>
                                                    <p class="order-total"><?php echo formatCurrency($order['total_amount']); ?></p>
                                                <?php endif; ?>
                                            </div>
                                            <div class="order-status">
                                                <?php if (isset($order['statuses'])): ?>
                                                    <?php
                                                    $statuses = explode(',', $order['statuses']);
                                                    $main_status = $statuses[0];
                                                    ?>
                                                    <span class="status-badge <?php echo $main_status; ?>">
                                                        <?php echo ucfirst($main_status); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div>
                        <?php if ($user_role === 'himada_admin'): ?>
                            <!-- Low Stock Alert for HIMADA Admin -->
                            <div class="dashboard-card" style="margin-bottom: 20px;">
                                <div class="card-header">
                                    <h2 class="card-title">
                                        <span class="card-icon">⚠️</span>
                                        Stok Menipis
                                    </h2>
                                    <a href="stock.php" class="card-action">Kelola Stok</a>
                                </div>
                                <div class="card-content">
                                    <?php if (empty($low_stock_products)): ?>
                                        <div class="empty-state success">
                                            <div class="empty-icon">✅</div>
                                            <p class="empty-text">Semua stok aman</p>
                                        </div>
                                    <?php else: ?>
                                        <div class="stock-alerts">
                                            <?php foreach ($low_stock_products as $product): ?>
                                                <div class="stock-alert-item">
                                                    <div class="product-info">
                                                        <h4 class="product-name"><?php echo htmlspecialchars($product['nama_produk']); ?></h4>
                                                        <p class="product-category"><?php echo ucfirst($product['kategori']); ?></p>
                                                    </div>
                                                    <div class="stock-info">
                                                        <span class="current-stock <?php echo $product['stok'] == 0 ? 'out-of-stock' : 'low-stock'; ?>">
                                                            <?php echo $product['stok']; ?> / <?php echo $product['stok_minimum']; ?>
                                                        </span>
                                                    </div>
                                                    <div class="stock-action">
                                                        <a href="products.php?edit=<?php echo $product['id']; ?>" class="btn-small">
                                                            Restock
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Quick Actions -->
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h2 class="card-title">
                                    <span class="card-icon">⚡</span>
                                    Aksi Cepat
                                </h2>
                            </div>
                            <div class="card-content">
                                <div class="quick-actions">
                                    <?php if ($user_role === 'super_admin'): ?>
                                        <a href="manage-himada.php?action=add" class="quick-action-btn">
                                            <span class="action-icon">🏛️</span>
                                            <span class="action-text">Tambah HIMADA</span>
                                        </a>
                                        <a href="manage-users.php?action=add" class="quick-action-btn">
                                            <span class="action-icon">👥</span>
                                            <span class="action-text">Tambah User</span>
                                        </a>
                                        <a href="system-settings.php" class="quick-action-btn">
                                            <span class="action-icon">⚙️</span>
                                            <span class="action-text">Pengaturan</span>
                                        </a>
                                    <?php else: ?>
                                        <a href="products.php?action=add" class="quick-action-btn">
                                            <span class="action-icon">➕</span>
                                            <span class="action-text">Tambah Produk</span>
                                        </a>
                                        <a href="himart.php?action=add" class="quick-action-btn">
                                            <span class="action-icon">🎨</span>
                                            <span class="action-text">Buat HIMArt</span>
                                        </a>
                                        <a href="orders.php?status=pending" class="quick-action-btn">
                                            <span class="action-icon">📦</span>
                                            <span class="action-text">Proses Pesanan</span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        // Toggle sidebar for mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }
        
        // Update current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById('currentTime').textContent = timeString;
        }
        
        updateTime();
        setInterval(updateTime, 60000); // Update every minute
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.sidebar-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !toggle.contains(event.target) && 
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });
    </script>
</body>
</html>
