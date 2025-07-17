<?php
require_once __DIR__ . '/../config/database.php';
requireHimadaAdmin();

$database = new Database();
$db = $database->getConnection();

$himada_id = getUserHimadaId();
$user_id = $_SESSION['user_id'];

// Get HIMADA info
$query = "SELECT * FROM himada WHERE id = :himada_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':himada_id', $himada_id);
$stmt->execute();
$himada = $stmt->fetch(PDO::FETCH_ASSOC);

// Get dashboard statistics
$stats = [];

// Total products
$query = "SELECT COUNT(*) as count FROM products WHERE himada_id = :himada_id AND is_available = TRUE";
$stmt = $db->prepare($query);
$stmt->bindParam(':himada_id', $himada_id);
$stmt->execute();
$stats['total_products'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Total orders (pending + processing)
$query = "SELECT COUNT(DISTINCT oi.order_id) as count 
          FROM order_items oi 
          WHERE oi.himada_id = :himada_id AND oi.status IN ('pending', 'confirmed', 'processing')";
$stmt = $db->prepare($query);
$stmt->bindParam(':himada_id', $himada_id);
$stmt->execute();
$stats['pending_orders'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Total revenue this month
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

// Recent orders
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

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin <?php echo htmlspecialchars($himada['nama']); ?> - H!MANJA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<style>
    /* Sidebar Styles */
.admin-sidebar {
  width: 280px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  display: flex;
  flex-direction: column;
  position: fixed;
  height: 100vh;
  left: 0;
  top: 0;
  z-index: 1000;
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

/* Sidebar Header */
.sidebar-header {
  padding: 2rem 1.5rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-logo {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1rem;
}

.logo-icon {
  width: 50px;
  height: 50px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  backdrop-filter: blur(10px);
}

.logo-text {
  font-size: 1.5rem;
  font-weight: 700;
  color: white;
}

.role-info {
  text-align: center;
}

.role-badge {
  background: rgba(255, 255, 255, 0.2);
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  backdrop-filter: blur(10px);
}

.role-title {
  font-size: 0.9rem;
  opacity: 0.8;
}
    /* Sidebar Navigation */
.sidebar-nav {
  flex: 1;
  padding: 1rem 0;
  flex-direction: column;
}

.nav-menu {
  list-style: none;
  flex-direction: column;
}

.nav-item {
  margin-bottom: 0.5rem;
}

.nav-link {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem 1.5rem;
  color: rgba(255, 255, 255, 0.8);
  text-decoration: none;
  transition: all 0.3s ease;
  border-left: 3px solid transparent;
}

.nav-link:hover {
  background: rgba(255, 255, 255, 0.1);
  color: white;
  border-left-color: rgba(255, 255, 255, 0.5);
}

.nav-item.active .nav-link {
  background: rgba(255, 255, 255, 0.15);
  color: white;
  border-left-color: white;
  font-weight: 600;
}

.nav-icon {
  font-size: 1.2rem;
  width: 24px;
  text-align: center;
}

.nav-text {
  font-size: 0.95rem;
}

/* Sidebar Footer */
.sidebar-footer {
  padding: 1.5rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.user-info {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1rem;
}

.user-avatar {
  width: 45px;
  height: 45px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  font-weight: 600;
  backdrop-filter: blur(10px);
}

.user-details {
  flex: 1;
}

.user-name {
  font-size: 0.9rem;
  font-weight: 600;
  margin-bottom: 0.25rem;
}

.user-role {
  font-size: 0.8rem;
  opacity: 0.7;
}

.logout-btn {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 8px;
  color: white;
  text-decoration: none;
  transition: all 0.3s ease;
  font-size: 0.9rem;
}

.logout-btn:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: translateY(-1px);
}

.logout-icon {
  font-size: 1.1rem;
}
</style>
<body class="admin-page">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <div class="logo-icon">🛍️</div>
                <span class="logo-text">H!MANJA</span>
            </div>
            <div class="himada-info">
                <div class="himada-badge" style="background: <?php echo $himada['warna_tema']; ?>">
                    <?php echo htmlspecialchars($himada['nama']); ?>
                </div>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="nav-menu">
                <li class="nav-item active">
                    <a href="himada-dashboard.php" class="nav-link">
                        <span class="nav-icon">📊</span>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
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
            </ul>
        </nav>
        
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo substr($_SESSION['full_name'], 0, 1); ?>
                </div>
                <div class="user-details">
                    <p class="user-name"><?php echo htmlspecialchars($_SESSION['full_name']); ?></p>
                    <p class="user-role">Admin <?php echo htmlspecialchars($himada['nama']); ?></p>
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
                <h1 class="page-title">Dashboard Admin <?php echo htmlspecialchars($himada['nama']); ?></h1>
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
                

            </div>
            
            <!-- Main Dashboard Grid -->
            <div class="dashboard-grid">
                <!-- Recent Orders -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <span class="card-icon">📦</span>
                            Pesanan Terbaru
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
                                            <h4 class="order-number"><?php echo htmlspecialchars($order['order_number']); ?></h4>
                                            <p class="order-customer"><?php echo htmlspecialchars($order['customer_name']); ?></p>
                                            <p class="order-date"><?php echo date('d M Y H:i', strtotime($order['created_at'])); ?></p>
                                        </div>
                                        <div class="order-details">
                                            <p class="order-items"><?php echo $order['item_count']; ?> item</p>
                                            <p class="order-total"><?php echo formatCurrency($order['total_amount']); ?></p>
                                        </div>
                                        <div class="order-status">
                                            <?php
                                            $statuses = explode(',', $order['statuses']);
                                            $main_status = $statuses[0];
                                            $status_class = '';
                                            switch ($main_status) {
                                                case 'pending': $status_class = 'pending'; break;
                                                case 'confirmed': $status_class = 'confirmed'; break;
                                                case 'processing': $status_class = 'processing'; break;
                                                case 'ready': $status_class = 'ready'; break;
                                                case 'completed': $status_class = 'completed'; break;
                                                default: $status_class = 'pending';
                                            }
                                            ?>
                                            <span class="status-badge <?php echo $status_class; ?>">
                                                <?php echo ucfirst($main_status); ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                </div>
                
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
                            <a href="products.php?action=add" class="quick-action-btn">
                                <span class="action-icon">➕</span>
                                <span class="action-text">Tambah Produk</span>
                            </a>
                            <a href="orders.php?status=pending" class="quick-action-btn">
                                <span class="action-icon">📦</span>
                                <span class="action-text">Proses Pesanan</span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- HIMADA Info -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <span class="card-icon">🏛️</span>
                            Info HIMADA
                        </h2>
                        <a href="himada-profile.php" class="card-action">Edit</a>
                    </div>
                    <div class="card-content">
                        <div class="himada-profile">
                            <div class="himada-header">
                                <div class="himada-logo" style="background: <?php echo $himada['warna_tema']; ?>">
                                    🏛️
                                </div>
                                <div class="himada-details">
                                    <h3 class="himada-name"><?php echo htmlspecialchars($himada['nama']); ?></h3>
                                    <p class="himada-full-name"><?php echo htmlspecialchars($himada['nama_lengkap']); ?></p>
                                    <p class="himada-region">📍 <?php echo htmlspecialchars($himada['daerah_asal']); ?></p>
                                </div>
                            </div>
                            <div class="himada-description">
                                <p><?php echo htmlspecialchars($himada['deskripsi']); ?></p>
                            </div>
                            <div class="himada-contact">
                                <?php if ($himada['contact_email']): ?>
                                    <p class="contact-item">
                                        <span class="contact-icon">📧</span>
                                        <span class="contact-text"><?php echo htmlspecialchars($himada['contact_email']); ?></span>
                                    </p>
                                <?php endif; ?>
                                <?php if ($himada['contact_phone']): ?>
                                    <p class="contact-item">
                                        <span class="contact-icon">📱</span>
                                        <span class="contact-text"><?php echo htmlspecialchars($himada['contact_phone']); ?></span>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <script src="../assets/js/admin.js"></script>
    <script>
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
        
        // Auto-refresh dashboard data every 5 minutes
        setInterval(() => {
            location.reload();
        }, 300000);
    </script>
</body>
</html>
