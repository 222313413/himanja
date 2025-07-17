<?php
require_once 'config/database.php';
requireLogin();

$database = new Database();
$db = $database->getConnection();

$user_id = getUserId();

// Get filter parameters
$status_filter = isset($_GET['status']) ? sanitizeInput($_GET['status']) : '';

// Get user orders with items
$query = "SELECT o.*, oi.*, p.nama_produk, p.gambar_url, h.nama as himada_nama, h.warna_tema
          FROM orders o
          JOIN order_items oi ON o.id = oi.order_id
          JOIN products p ON oi.product_id = p.id
          JOIN himada h ON oi.himada_id = h.id
          WHERE o.user_id = :user_id";

$params = [':user_id' => $user_id];

if ($status_filter) {
    $query .= " AND oi.status = :status";
    $params[':status'] = $status_filter;
}

$query .= " ORDER BY o.created_at DESC";

$stmt = $db->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group by order
$orders = [];
foreach ($order_items as $item) {
    $order_id = $item['order_id'];
    if (!isset($orders[$order_id])) {
        $orders[$order_id] = [
            'id' => $item['order_id'],
            'order_number' => $item['order_number'],
            'created_at' => $item['created_at'],
            'total_amount' => $item['total_amount'],
            'items' => []
        ];
    }
    $orders[$order_id]['items'][] = $item;
}

// Get order statistics
$stats_query = "SELECT 
    COUNT(CASE WHEN oi.status = 'pending' THEN 1 END) as pending_count,
    COUNT(CASE WHEN oi.status = 'confirmed' THEN 1 END) as confirmed_count,
    COUNT(CASE WHEN oi.status = 'processing' THEN 1 END) as processing_count,
    COUNT(CASE WHEN oi.status = 'ready' THEN 1 END) as ready_count,
    COUNT(CASE WHEN oi.status = 'completed' THEN 1 END) as completed_count,
    COUNT(CASE WHEN oi.status = 'shipped' THEN 1 END) as shipped_count,
    COUNT(CASE WHEN oi.status = 'delivered' THEN 1 END) as delivered_count
FROM order_items oi 
JOIN orders o ON oi.order_id = o.id
WHERE o.user_id = :user_id";

$stmt = $db->prepare($stats_query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

// Check user role and redirect accordingly
$user_role = getUserRole();
$is_logged_in = isLoggedIn();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - H!MANJA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .orders-hero {
            background: var(--gradient-primary);
            padding: 6rem 0 3rem;
            text-align: center;
            color: white;
        }
        
        .orders-hero h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 0.8rem;
            color: #666;
        }
        
        .stat-pending .stat-number { color: #ffa502; }
        .stat-confirmed .stat-number { color: #3742fa; }
        .stat-processing .stat-number { color: #2f3542; }
        .stat-ready .stat-number { color: #2ed573; }
        .stat-shipped .stat-number { color: #1e90ff; }
        .stat-delivered .stat-number { color: #2ecc71; }
        .stat-completed .stat-number { color: #27ae60; }
        
        .filters-section {
            background: white;
            padding: 1.5rem 0;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 2rem;
        }
        
        .filter-group {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .filter-input {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.9rem;
        }
        
        .order-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            overflow: hidden;
        }
        
        .order-header {
            background: #f8f9fa;
            padding: 1.5rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .order-info h3 {
            margin: 0 0 0.5rem 0;
            color: #333;
            font-size: 1.1rem;
        }
        
        .order-info p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }
        
        .order-total {
            text-align: right;
        }
        
        .total-amount {
            font-size: 1.3rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.25rem;
        }
        
        .order-date {
            font-size: 0.9rem;
            color: #666;
        }
        
        .order-items {
            padding: 0;
        }
        
        .order-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .item-image {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .item-details {
            flex: 1;
        }
        
        .item-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: #333;
        }
        
        .item-himada {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.5rem;
        }
        
        .item-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.9rem;
            color: #666;
        }
        
        .item-status {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.5rem;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-align: center;
        }
        
        .status-pending { background: #fff3cd; color: #856404; }
        .status-confirmed { background: #cce5ff; color: #004085; }
        .status-processing { background: #e2e3e5; color: #383d41; }
        .status-ready { background: #d4edda; color: #155724; }
        .status-shipped { background: #d1ecf1; color: #0c5460; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-completed { background: #d1ecf1; color: #0c5460; }
        
        .item-price {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }
        
        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .status-timeline {
            margin-top: 0.5rem;
            font-size: 0.8rem;
            color: #666;
        }
        
        .quick-filters {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }
        
        .quick-filter {
            padding: 0.5rem 1rem;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 20px;
            text-decoration: none;
            color: #666;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .quick-filter:hover,
        .quick-filter.active {
            background: var(--soft-blue);
            color: white;
            border-color: var(--soft-blue);
        }
        
        @media (max-width: 768px) {
            .orders-hero h1 {
                font-size: 2rem;
            }
            
            .order-header {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
            }
            
            .order-item {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .item-status {
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <div class="logo-icon">🛍️</div>
                    <span class="logo-text">H!MANJA</span>
                </div>
                
                <ul class="nav-menu">
                    <li><a href="index.php" class="nav-link">Beranda</a></li>
                    <li><a href="himart.php" class="nav-link">HIMArt</a></li>
                    <li><a href="products.php" class="nav-link">Produk</a></li>
                    <li><a href="order.php" class="nav-link">Pemesanan</a></li>
                    <li><a href="my-orders.php" class="nav-link active">Pesanan Saya</a></li>
                </ul>
                
                <div class="nav-auth">
                    <span class="welcome-text">Halo, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</span>
                    <?php if (isAdmin()): ?>
                        <a href="admin/" class="btn-admin">Admin</a>
                    <?php endif; ?>
                    <a href="logout.php" class="btn-logout">Logout</a>
                </div>
                
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </nav>
    </header>

    <!-- Header -->
    <header class="header">
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-logo">
                    <div class="logo-icon">🛍️</div>
                    <span class="logo-text">H!MANJA</span>
                </div>

                <!-- KANAN: MENU + AUTH -->
                <div class="nav-right">
                    <ul class="nav-menu">
                        <li><a href="index.php" class="nav-link">Beranda</a></li>
                        <li><a href="history.php" class="nav-link">H!Story</a></li>
                        <li><a href="products.php" class="nav-link">Produk</a></li>
                        <?php if ($is_logged_in): ?>
                            <li><a href="order.php" class="nav-link">H!PO</a></li>
                            <li><a href="my-orders.php" class="nav-link active">H!Loot</a></li>
                        <?php endif; ?>
                    </ul>
                
                <div class="nav-auth">
                    <?php if ($is_logged_in): ?>
                        <span class="welcome-text">Halo, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</span>
                        <a href="logout.php" class="btn-logout">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn-login">Login</a>
                    <?php endif; ?>
                </div>
                
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="orders-hero">
        <div class="container">
            <h1>Pesanan Saya</h1>
            <p>Pantau status pesanan dan riwayat belanja Anda</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="orders-section">
        <div class="container">

            <!-- Filters -->
            <div class="filters-section">
                <div class="filter-group">
                    <label>Filter Status:</label>
                    <select class="filter-input" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="confirmed" <?php echo $status_filter === 'confirmed' ? 'selected' : ''; ?>>Dikonfirmasi</option>
                        <option value="processing" <?php echo $status_filter === 'processing' ? 'selected' : ''; ?>>Diproses</option>
                        <option value="ready" <?php echo $status_filter === 'ready' ? 'selected' : ''; ?>>Siap</option>
                        <option value="shipped" <?php echo $status_filter === 'shipped' ? 'selected' : ''; ?>>Dikirim</option>
                        <option value="delivered" <?php echo $status_filter === 'delivered' ? 'selected' : ''; ?>>Sampai</option>
                        <option value="completed" <?php echo $status_filter === 'completed' ? 'selected' : ''; ?>>Selesai</option>
                    </select>
                </div>
                
                <!-- Quick Filters -->
                <div class="quick-filters">
                    <a href="my-orders.php" class="quick-filter <?php echo !$status_filter ? 'active' : ''; ?>">
                        📋 Semua
                    </a>
                    <a href="my-orders.php?status=pending" class="quick-filter <?php echo $status_filter === 'pending' ? 'active' : ''; ?>">
                        ⏳ Pending
                    </a>
                    <a href="my-orders.php?status=processing" class="quick-filter <?php echo $status_filter === 'processing' ? 'active' : ''; ?>">
                        🔄 Diproses
                    </a>
                    <a href="my-orders.php?status=shipped" class="quick-filter <?php echo $status_filter === 'shipped' ? 'active' : ''; ?>">
                        🚚 Dikirim
                    </a>
                    <a href="my-orders.php?status=completed" class="quick-filter <?php echo $status_filter === 'completed' ? 'active' : ''; ?>">
                        ✅ Selesai
                    </a>
                </div>
            </div>

            <!-- Orders List -->
            <?php if (empty($orders)): ?>
                <div class="empty-state">
                    <div class="empty-icon">📦</div>
                    <h3>Belum Ada Pesanan</h3>
                    <p>Anda belum memiliki pesanan. Mulai berbelanja sekarang!</p>
                    <a href="products.php" class="btn-primary">Mulai Belanja</a>
                </div>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <h3>Order #<?php echo htmlspecialchars($order['order_number']); ?></h3>
                                <p><?php echo count($order['items']); ?> item(s)</p>
                            </div>
                            <div class="order-total">
                                <div class="total-amount"><?php echo formatCurrency($order['total_amount']); ?></div>
                                <div class="order-date"><?php echo date('d M Y, H:i', strtotime($order['created_at'])); ?></div>
                            </div>
                        </div>
                        
                        <div class="order-items">
                            <?php foreach ($order['items'] as $item): ?>
                                <div class="order-item">
                                    <div class="item-image">
                                        <?php if ($item['gambar_url']): ?>
                                            <img src="<?php echo htmlspecialchars($item['gambar_url']); ?>" 
                                                 alt="<?php echo htmlspecialchars($item['nama_produk']); ?>"
                                                 onerror="this.style.display='none'; this.parentElement.innerHTML='📷';">
                                        <?php else: ?>
                                            📷
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="item-details">
                                        <div class="item-name"><?php echo htmlspecialchars($item['nama_produk']); ?></div>
                                        <div class="item-himada" style="background: <?php echo $item['warna_tema']; ?>">
                                            <?php echo htmlspecialchars($item['himada_nama']); ?>
                                        </div>
                                        <div class="item-meta">
                                            <span>Qty: <?php echo $item['quantity']; ?></span>
                                            <span>@<?php echo formatCurrency($item['price']); ?></span>
                                            <?php if ($item['catatan_produk']): ?>
                                                <span>Catatan: <?php echo htmlspecialchars($item['catatan_produk']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="item-status">
                                        <div class="status-badge status-<?php echo $item['status']; ?>">
                                            <?php 
                                            switch ($item['status']) {
                                                case 'pending': echo '⏳ Pending'; break;
                                                case 'confirmed': echo '✅ Dikonfirmasi'; break;
                                                case 'processing': echo '🔄 Diproses'; break;
                                                case 'ready': echo '📦 Siap'; break;
                                                case 'shipped': echo '🚚 Dikirim'; break;
                                                case 'delivered': echo '📍 Sampai'; break;
                                                case 'completed': echo '✅ Selesai'; break;
                                                default: echo ucfirst($item['status']);
                                            }
                                            ?>
                                        </div>
                                        <div class="item-price"><?php echo formatCurrency($item['subtotal']); ?></div>
                                        <div class="status-timeline">
                                            <?php
                                            $status_messages = [
                                                'pending' => 'Menunggu konfirmasi admin',
                                                'confirmed' => 'Pesanan dikonfirmasi',
                                                'processing' => 'Sedang diproses',
                                                'ready' => 'Siap untuk diambil/dikirim',
                                                'shipped' => 'Dalam perjalanan',
                                                'delivered' => 'Sudah sampai tujuan',
                                                'completed' => 'Transaksi selesai'
                                            ];
                                            echo $status_messages[$item['status']] ?? '';
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <div class="logo-icon">🛍️</div>
                        <span class="logo-text"><strong>H!MANJA</strong></span>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3 class="footer-title">Menu</h3>
                    <ul class="footer-links">
                        <li><a href="index.php">Beranda</a></li>
                        <li><a href="history.php">H!Story</a></li>
                        <li><a href="products.php">Produk</a></li>
                        <?php if ($is_logged_in): ?>
                            <li><a href="#order">H!PO</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3 class="footer-title">Kontak</h3>
                    <div class="footer-contact">
                        <p>📧 info@himanja.stis.ac.id</p>
                        <p>📱 +62 812-3456-7890</p>
                        <p>📍 Politeknik Statistika STIS</p>
                        <p>Jl. Otto Iskandardinata No.64C, Jakarta</p>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 H!MANJA - Himada Belanja. Dibuat dengan ❤️ untuk mahasiswa STIS.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>

    <script>
        // Filter function
        function applyFilters() {
            const status = document.getElementById('statusFilter').value;
            
            const params = new URLSearchParams();
            if (status) params.set('status', status);
            
            window.location.href = 'my-orders.php?' + params.toString();
        }
        
        // Event listener
        document.getElementById('statusFilter').addEventListener('change', applyFilters);
    </script>
</body>
</html>
