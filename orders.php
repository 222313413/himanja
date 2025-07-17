<?php
require_once 'config/database.php';
requireHimadaAdmin();

$database = new Database();
$db = $database->getConnection();

$himada_id = getUserHimadaId();
$user_id = getUserId();

// Get HIMADA info
$query = "SELECT * FROM himada WHERE id = :himada_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':himada_id', $himada_id);
$stmt->execute();
$himada = $stmt->fetch(PDO::FETCH_ASSOC);

$message = '';
$error = '';

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_status') {
        $order_item_id = (int)$_POST['order_item_id'];
        $new_status = sanitizeInput($_POST['new_status']);
        
        try {
            $query = "UPDATE order_items SET status = :status WHERE id = :id AND himada_id = :himada_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':status', $new_status);
            $stmt->bindParam(':id', $order_item_id);
            $stmt->bindParam(':himada_id', $himada_id);
            $stmt->execute();
            
            logActivity($user_id, 'order_status_update', "Updated order item $order_item_id status to $new_status");
            $message = 'Status pesanan berhasil diperbarui!';
        } catch (Exception $e) {
            $error = 'Gagal memperbarui status: ' . $e->getMessage();
        }
    }
}

// Get filter parameters
$status_filter = isset($_GET['status']) ? sanitizeInput($_GET['status']) : '';
$date_filter = isset($_GET['date']) ? sanitizeInput($_GET['date']) : '';
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';

// Build query
$query = "SELECT oi.*, o.order_number, o.created_at as order_date, 
                 u.full_name as customer_name, u.kelas, u.email,
                 p.nama_produk, p.gambar_url
          FROM order_items oi
          JOIN orders o ON oi.order_id = o.id
          JOIN users u ON o.user_id = u.id
          JOIN products p ON oi.product_id = p.id
          WHERE oi.himada_id = :himada_id";

$params = [':himada_id' => $himada_id];

if ($status_filter) {
    $query .= " AND oi.status = :status";
    $params[':status'] = $status_filter;
}

if ($date_filter) {
    $query .= " AND DATE(o.created_at) = :date";
    $params[':date'] = $date_filter;
}

if ($search) {
    $query .= " AND (o.order_number LIKE :search OR u.full_name LIKE :search OR p.nama_produk LIKE :search)";
    $params[':search'] = "%$search%";
}

$query .= " ORDER BY o.created_at DESC";

$stmt = $db->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get order statistics
$stats_query = "SELECT 
    COUNT(CASE WHEN oi.status = 'pending' THEN 1 END) as pending_count,
    COUNT(CASE WHEN oi.status = 'confirmed' THEN 1 END) as confirmed_count,
    COUNT(CASE WHEN oi.status = 'processing' THEN 1 END) as processing_count,
    COUNT(CASE WHEN oi.status = 'ready' THEN 1 END) as ready_count,
    COUNT(CASE WHEN oi.status = 'completed' THEN 1 END) as completed_count,
    SUM(CASE WHEN oi.status IN ('completed', 'ready') THEN oi.subtotal ELSE 0 END) as total_revenue
FROM order_items oi 
WHERE oi.himada_id = :himada_id";

$stmt = $db->prepare($stats_query);
$stmt->bindParam(':himada_id', $himada_id);
$stmt->execute();
$stats = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - Admin <?php echo htmlspecialchars($himada['nama']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .orders-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .orders-filters {
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
        
        .stat-pending .stat-number { color: #ffa502; }
        .stat-confirmed .stat-number { color: #3742fa; }
        .stat-processing .stat-number { color: #2f3542; }
        .stat-ready .stat-number { color: #2ed573; }
        .stat-completed .stat-number { color: #1e90ff; }
        .stat-revenue .stat-number { color: #ff4757; }
        
        .orders-table {
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
        }
        
        .order-item {
            display: grid;
            grid-template-columns: 1fr 2fr 1fr 1fr 1fr 120px;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
            align-items: center;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .order-item:hover {
            background: #f8f9fa;
        }
        
        .order-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .product-image {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .order-details h4 {
            margin: 0 0 0.25rem 0;
            font-size: 1rem;
            color: #333;
        }
        
        .order-details p {
            margin: 0;
            font-size: 0.9rem;
            color: #666;
        }
        
        .customer-info h4 {
            margin: 0 0 0.25rem 0;
            font-size: 1rem;
            color: #333;
        }
        
        .customer-info p {
            margin: 0;
            font-size: 0.9rem;
            color: #666;
        }
        
        .order-quantity {
            text-align: center;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .order-total {
            text-align: center;
            font-weight: 700;
            font-size: 1.1rem;
            color: #333;
        }
        
        .status-select {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.9rem;
            width: 100%;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-align: center;
        }
        
        .status-pending { background: #fff3cd; color: #856404; }
        .status-confirmed { background: #cce5ff; color: #004085; }
        .status-processing { background: #e2e3e5; color: #383d41; }
        .status-ready { background: #d4edda; color: #155724; }
        .status-completed { background: #d1ecf1; color: #0c5460; }
        
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
        
        @media (max-width: 768px) {
            .orders-header {
                flex-direction: column;
                align-items: stretch;
            }
            
            .orders-filters {
                flex-direction: column;
            }
            
            .stats-overview {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .order-item {
                grid-template-columns: 1fr;
                gap: 0.5rem;
                text-align: left;
            }
            
            .table-header {
                display: none;
            }
        }
    </style>
</head>
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
                <p class="himada-admin">Admin Dashboard</p>
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
                    <a href="products.php" class="nav-link">
                        <span class="nav-icon">🛍️</span>
                        <span class="nav-text">Kelola Produk</span>
                    </a>
                </li>
                <li class="nav-item active">
                    <a href="orders.php" class="nav-link">
                        <span class="nav-icon">📦</span>
                        <span class="nav-text">Pesanan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="stock.php" class="nav-link">
                        <span class="nav-icon">📋</span>
                        <span class="nav-text">Stok Barang</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="himart.php" class="nav-link">
                        <span class="nav-icon">🎨</span>
                        <span class="nav-text">HIMArt</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="reports.php" class="nav-link">
                        <span class="nav-icon">📈</span>
                        <span class="nav-text">Laporan</span>
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
                <h1 class="page-title">Kelola Pesanan <?php echo htmlspecialchars($himada['nama']); ?></h1>
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
            
            <!-- Statistics Overview -->
            <div class="stats-overview">
                <div class="stat-card stat-pending">
                    <div class="stat-number"><?php echo $stats['pending_count']; ?></div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-card stat-confirmed">
                    <div class="stat-number"><?php echo $stats['confirmed_count']; ?></div>
                    <div class="stat-label">Dikonfirmasi</div>
                </div>
                <div class="stat-card stat-processing">
                    <div class="stat-number"><?php echo $stats['processing_count']; ?></div>
                    <div class="stat-label">Diproses</div>
                </div>
                <div class="stat-card stat-ready">
                    <div class="stat-number"><?php echo $stats['ready_count']; ?></div>
                    <div class="stat-label">Siap</div>
                </div>
                <div class="stat-card stat-completed">
                    <div class="stat-number"><?php echo $stats['completed_count']; ?></div>
                    <div class="stat-label">Selesai</div>
                </div>
                <div class="stat-card stat-revenue">
                    <div class="stat-number"><?php echo formatCurrency($stats['total_revenue']); ?></div>
                    <div class="stat-label">Total Pendapatan</div>
                </div>
            </div>
            
            <!-- Orders Header -->
            <div class="orders-header">
                <div class="orders-filters">
                    <div class="filter-group">
                        <label>Cari Pesanan</label>
                        <input type="text" class="filter-input" id="searchInput" placeholder="Nomor order, nama, produk..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="filter-group">
                        <label>Status</label>
                        <select class="filter-input" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="confirmed" <?php echo $status_filter === 'confirmed' ? 'selected' : ''; ?>>Dikonfirmasi</option>
                            <option value="processing" <?php echo $status_filter === 'processing' ? 'selected' : ''; ?>>Diproses</option>
                            <option value="ready" <?php echo $status_filter === 'ready' ? 'selected' : ''; ?>>Siap</option>
                            <option value="completed" <?php echo $status_filter === 'completed' ? 'selected' : ''; ?>>Selesai</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Tanggal</label>
                        <input type="date" class="filter-input" id="dateFilter" value="<?php echo htmlspecialchars($date_filter); ?>">
                    </div>
                </div>
            </div>
            
            <!-- Orders Table -->
            <?php if (empty($order_items)): ?>
                <div class="empty-state">
                    <div class="empty-icon">📦</div>
                    <h3>Belum Ada Pesanan</h3>
                    <p>Pesanan untuk HIMADA <?php echo htmlspecialchars($himada['nama']); ?> akan muncul di sini</p>
                </div>
            <?php else: ?>
                <div class="orders-table">
                    <div class="table-header">
                        <div class="order-item">
                            <div>Produk</div>
                            <div>Pelanggan</div>
                            <div>Jumlah</div>
                            <div>Total</div>
                            <div>Tanggal</div>
                            <div>Status</div>
                        </div>
                    </div>
                    
                    <?php foreach ($order_items as $item): ?>
                        <div class="order-item">
                            <div class="order-info">
                                <div class="product-image">
                                    <?php if ($item['gambar_url']): ?>
                                        <img src="<?php echo htmlspecialchars($item['gambar_url']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['nama_produk']); ?>"
                                             onerror="this.style.display='none'; this.parentElement.innerHTML='📷';">
                                    <?php else: ?>
                                        📷
                                    <?php endif; ?>
                                </div>
                                <div class="order-details">
                                    <h4><?php echo htmlspecialchars($item['nama_produk']); ?></h4>
                                    <p>Order: <?php echo htmlspecialchars($item['order_number']); ?></p>
                                    <?php if ($item['catatan_produk']): ?>
                                        <p><em>Catatan: <?php echo htmlspecialchars($item['catatan_produk']); ?></em></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="customer-info">
                                <h4><?php echo htmlspecialchars($item['customer_name']); ?></h4>
                                <p><?php echo htmlspecialchars($item['kelas']); ?></p>
                                <p><?php echo htmlspecialchars($item['email']); ?></p>
                            </div>
                            
                            <div class="order-quantity">
                                <?php echo $item['quantity']; ?>x
                            </div>
                            
                            <div class="order-total">
                                <?php echo formatCurrency($item['subtotal']); ?>
                            </div>
                            
                            <div style="font-size: 0.9rem; color: #666;">
                                <?php echo date('d/m/Y H:i', strtotime($item['order_date'])); ?>
                            </div>
                            
                            <div>
                                <form method="POST" style="margin: 0;">
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="order_item_id" value="<?php echo $item['id']; ?>">
                                    <select name="new_status" class="status-select" onchange="this.form.submit()">
                                        <option value="pending" <?php echo $item['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="confirmed" <?php echo $item['status'] === 'confirmed' ? 'selected' : ''; ?>>Dikonfirmasi</option>
                                        <option value="processing" <?php echo $item['status'] === 'processing' ? 'selected' : ''; ?>>Diproses</option>
                                        <option value="ready" <?php echo $item['status'] === 'ready' ? 'selected' : ''; ?>>Siap</option>
                                        <option value="completed" <?php echo $item['status'] === 'completed' ? 'selected' : ''; ?>>Selesai</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <script>
        // Filter functions
        function applyFilters() {
            const search = document.getElementById('searchInput').value;
            const status = document.getElementById('statusFilter').value;
            const date = document.getElementById('dateFilter').value;
            
            const params = new URLSearchParams();
            if (search) params.set('search', search);
            if (status) params.set('status', status);
            if (date) params.set('date', date);
            
            window.location.href = 'orders.php?' + params.toString();
        }
        
        // Event listeners
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
        
        document.getElementById('statusFilter').addEventListener('change', applyFilters);
        document.getElementById('dateFilter').addEventListener('change', applyFilters);
        
        // Auto-refresh every 30 seconds
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
</body>
</html>
