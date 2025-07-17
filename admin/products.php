<?php
require_once __DIR__ . '/../config/database.php';
startSession();

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

// Get dashboard statistics
$stats = [];

// Total orders (pending + processing)
$query = "SELECT COUNT(DISTINCT oi.order_id) as count 
          FROM order_items oi 
          WHERE oi.himada_id = :himada_id AND oi.status IN ('pending', 'confirmed', 'processing')";
$stmt = $db->prepare($query);
$stmt->bindParam(':himada_id', $himada_id);
$stmt->execute();
$stats['pending_orders'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $nama_produk = sanitizeInput($_POST['nama_produk']);
                $kategori = sanitizeInput($_POST['kategori']);
                $deskripsi = sanitizeInput($_POST['deskripsi']);
                $harga = (float)$_POST['harga'];
                $stok = (int)$_POST['stok'];
                $stok_minimum = (int)$_POST['stok_minimum'];
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (isset($_POST['action'])) {
                        switch ($_POST['action']) {
                            case 'add':
                                // ... variabel lain tetap
                                $gambar_url = null;

                                if (!empty($_FILES['gambar']['name'])) {
                                    $upload_dir = '../uploads/produk/';
                                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

                                    $filename = uniqid() . '-' . basename($_FILES['gambar']['name']);
                                    $target_path = $upload_dir . $filename;

                                    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_path)) {
                                        $gambar_url = $target_path;
                                    } else {
                                        $error = 'Gagal upload gambar.';
                                    }
                                }
                            }
                        }
                    }
                        

                                // lanjut query insert pakai $gambar_url

                
                try {
                    $query = "INSERT INTO products (himada_id, nama_produk, kategori, deskripsi, harga, stok, stok_minimum, gambar_url, created_by) 
                             VALUES (:himada_id, :nama_produk, :kategori, :deskripsi, :harga, :stok, :stok_minimum, :gambar_url, :created_by)";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':himada_id', $himada_id);
                    $stmt->bindParam(':nama_produk', $nama_produk);
                    $stmt->bindParam(':kategori', $kategori);
                    $stmt->bindParam(':deskripsi', $deskripsi);
                    $stmt->bindParam(':harga', $harga);
                    $stmt->bindParam(':stok', $stok);
                    $stmt->bindParam(':stok_minimum', $stok_minimum);
                    $stmt->bindParam(':gambar_url', $gambar_url);
                    $stmt->bindParam(':created_by', $user_id);
                    $stmt->execute();
                    
                    logActivity($user_id, 'product_add', "Added product: $nama_produk");
                    $message = 'Produk berhasil ditambahkan!';
                } catch (Exception $e) {
                    $error = 'Gagal menambahkan produk: ' . $e->getMessage();
                }
                break;
                
            case 'edit':
                $product_id = (int)$_POST['product_id'];
                $nama_produk = sanitizeInput($_POST['nama_produk']);
                $kategori = sanitizeInput($_POST['kategori']);
                $deskripsi = sanitizeInput($_POST['deskripsi']);
                $harga = (float)$_POST['harga'];
                $stok = (int)$_POST['stok'];
                $stok_minimum = (int)$_POST['stok_minimum'];
                $gambar_url = $edit_product['gambar_url'] ?? null;

                if (!empty($_FILES['gambar']['name'])) {
                    $upload_dir = '../uploads/produk/';
                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

                    $filename = uniqid() . '-' . basename($_FILES['gambar']['name']);
                    $target_path = $upload_dir . $filename;

                    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_path)) {
                        $gambar_url = $target_path;
                    } else {
                        $error = 'Gagal upload gambar.';
                    }
                }

                // lanjut query update pakai $gambar_url
                $is_available = isset($_POST['is_available']) ? 1 : 0;
                
                try {
                    $query = "UPDATE products SET nama_produk = :nama_produk, kategori = :kategori, deskripsi = :deskripsi, 
                             harga = :harga, stok = :stok, stok_minimum = :stok_minimum, gambar_url = :gambar_url, 
                             is_available = :is_available WHERE id = :product_id AND himada_id = :himada_id";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':nama_produk', $nama_produk);
                    $stmt->bindParam(':kategori', $kategori);
                    $stmt->bindParam(':deskripsi', $deskripsi);
                    $stmt->bindParam(':harga', $harga);
                    $stmt->bindParam(':stok', $stok);
                    $stmt->bindParam(':stok_minimum', $stok_minimum);
                    $stmt->bindParam(':gambar_url', $gambar_url);
                    $stmt->bindParam(':is_available', $is_available);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':himada_id', $himada_id);
                    $stmt->execute();
                    
                    logActivity($user_id, 'product_edit', "Updated product: $nama_produk");
                    $message = 'Produk berhasil diperbarui!';
                } catch (Exception $e) {
                    $error = 'Gagal memperbarui produk: ' . $e->getMessage();
                }
                break;
                
            case 'delete':
                $product_id = (int)$_POST['product_id'];
                
                try {
                    // Get product name for logging
                    $query = "SELECT nama_produk FROM products WHERE id = :product_id AND himada_id = :himada_id";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':himada_id', $himada_id);
                    $stmt->execute();
                    $product = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($product) {
                        $query = "DELETE FROM products WHERE id = :product_id AND himada_id = :himada_id";
                        $stmt = $db->prepare($query);
                        $stmt->bindParam(':product_id', $product_id);
                        $stmt->bindParam(':himada_id', $himada_id);
                        $stmt->execute();
                        
                        logActivity($user_id, 'product_delete', "Deleted product: " . $product['nama_produk']);
                        $message = 'Produk berhasil dihapus!';
                    }
                } catch (Exception $e) {
                    $error = 'Gagal menghapus produk: ' . $e->getMessage();
                }
                break;
        }
    }
}

// Get products
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$category = isset($_GET['category']) ? sanitizeInput($_GET['category']) : '';
$status = isset($_GET['status']) ? sanitizeInput($_GET['status']) : '';

$query = "SELECT * FROM products WHERE himada_id = :himada_id";
$params = [':himada_id' => $himada_id];

if ($search) {
    $query .= " AND (nama_produk LIKE :search OR deskripsi LIKE :search)";
    $params[':search'] = "%$search%";
}

if ($category) {
    $query .= " AND kategori = :category";
    $params[':category'] = $category;
}

if ($status === 'available') {
    $query .= " AND is_available = 1";
} elseif ($status === 'unavailable') {
    $query .= " AND is_available = 0";
} elseif ($status === 'low_stock') {
    $query .= " AND stok <= stok_minimum";
}

$query .= " ORDER BY created_at DESC";

$stmt = $db->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get edit product if requested
$edit_product = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $query = "SELECT * FROM products WHERE id = :id AND himada_id = :himada_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $edit_id);
    $stmt->bindParam(':himada_id', $himada_id);
    $stmt->execute();
    $edit_product = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Admin <?php echo htmlspecialchars($himada['nama']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
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
        .products-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .products-filters {
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
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
        }
        
        .product-image {
            height: 200px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .product-status {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-available {
            background: #d4edda;
            color: #155724;
        }
        
        .status-unavailable {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-low-stock {
            background: #fff3cd;
            color: #856404;
        }
        
        .product-content {
            padding: 1.5rem;
        }
        
        .product-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }
        
        .product-category {
            display: inline-block;
            padding: 2px 8px;
            background: #e9ecef;
            border-radius: 12px;
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }
        
        .product-description {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 1rem;
            line-height: 1.4;
        }
        
        .product-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .product-stock {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 1rem;
        }
        
        .product-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            border: none;
            border-radius: 6px;
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
        
        .btn-small:hover {
            opacity: 0.8;
            transform: translateY(-1px);
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
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .form-group {
            margin-bottom: 1rem;
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
            .products-header {
                flex-direction: column;
                align-items: stretch;
            }
            
            .products-filters {
                flex-direction: column;
            }
            
            .products-grid {
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
                <li class="nav-item">
                    <a href="himada-dashboard.php" class="nav-link">
                        <span class="nav-icon">📊</span>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item active">
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
                <h1 class="page-title">Kelola Produk <?php echo htmlspecialchars($himada['nama']); ?></h1>
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
            
            <!-- Products Header -->
            <div class="products-header">
                <div class="products-filters">
                    <div class="filter-group">
                        <label>Cari Produk</label>
                        <input type="text" class="filter-input" id="searchInput" placeholder="Nama atau deskripsi..." value="">
                    </div>
                    <div class="filter-group">
                        <label>Kategori</label>
                        <select class="filter-input" id="categoryFilter">
                            <option value="">Semua Kategori</option>
                            <option value="makanan" <?php echo $category === 'makanan' ? 'selected' : ''; ?>>Makanan</option>
                            <option value="merchandise" <?php echo $category === 'merchandise' ? 'selected' : ''; ?>>Merchandise</option>
                            <option value="kaos" <?php echo $category === 'kaos' ? 'selected' : ''; ?>>Kaos</option>
                            <option value="gantungan_kunci" <?php echo $category === 'gantungan_kunci' ? 'selected' : ''; ?>>Gantungan Kunci</option>
                            <option value="oleh_oleh" <?php echo $category === 'oleh_oleh' ? 'selected' : ''; ?>>Oleh-oleh</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Status</label>
                        <select class="filter-input" id="statusFilter">
                            <option value="">Semua Status</option>
                            <option value="available" <?php echo $status === 'available' ? 'selected' : ''; ?>>Tersedia</option>
                            <option value="unavailable" <?php echo $status === 'unavailable' ? 'selected' : ''; ?>>Tidak Tersedia</option>
                            <option value="low_stock" <?php echo $status === 'low_stock' ? 'selected' : ''; ?>>Stok Menipis</option>
                        </select>
                    </div>
                </div>
                <button class="btn-primary" onclick="openAddModal()">
                    <span>➕</span> Tambah Produk
                </button>
            </div>
            
            <!-- Products Grid -->
            <?php if (empty($products)): ?>
                <div class="empty-state">
                    <div class="empty-icon">📦</div>
                    <h3>Belum Ada Produk</h3>
                    <p>Mulai tambahkan produk pertama untuk HIMADA <?php echo htmlspecialchars($himada['nama']); ?></p>
                    <button class="btn-primary" onclick="openAddModal()">Tambah Produk Pertama</button>
                </div>
            <?php else: ?>
                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <?php if ($product['gambar_url']): ?>
                                    <img src="<?php echo htmlspecialchars($product['gambar_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($product['nama_produk']); ?>"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div style="display: none; align-items: center; justify-content: center; height: 100%; color: #666;">
                                        📷 Gambar tidak tersedia
                                    </div>
                                <?php else: ?>
                                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #666;">
                                        📷 Tidak ada gambar
                                    </div>
                                <?php endif; ?>
                                
                                <?php
                                $status_class = 'status-available';
                                $status_text = 'Tersedia';
                                if (!$product['is_available']) {
                                    $status_class = 'status-unavailable';
                                    $status_text = 'Tidak Tersedia';
                                } elseif ($product['stok'] <= $product['stok_minimum']) {
                                    $status_class = 'status-low-stock';
                                    $status_text = 'Stok Menipis';
                                }
                                ?>
                                <div class="product-status <?php echo $status_class; ?>">
                                    <?php echo $status_text; ?>
                                </div>
                            </div>
                            
                            <div class="product-content">
                                <h3 class="product-name"><?php echo htmlspecialchars($product['nama_produk']); ?></h3>
                                <span class="product-category"><?php echo ucfirst($product['kategori']); ?></span>
                                <p class="product-description"><?php echo htmlspecialchars(substr($product['deskripsi'], 0, 100)) . (strlen($product['deskripsi']) > 100 ? '...' : ''); ?></p>
                                <div class="product-price"><?php echo formatCurrency($product['harga']); ?></div>
                                <div class="product-stock">
                                    Stok: <?php echo $product['stok']; ?> 
                                    (Min: <?php echo $product['stok_minimum']; ?>)
                                </div>
                                
                                <div class="product-actions">
                                    <button class="btn-small btn-edit" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($product)); ?>)">
                                        ✏️ Edit
                                    </button>
                                    <button class="btn-small btn-delete" onclick="confirmDelete(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['nama_produk']); ?>')">
                                        🗑️ Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <!-- Add/Edit Product Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <h2 id="modalTitle">Tambah Produk</h2>
            <form id="productForm" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="product_id" id="productId">
                
                <div class="form-group">
                    <label for="nama_produk">Nama Produk *</label>
                    <input type="text" name="nama_produk" id="nama_produk" required>
                </div>
                
                <div class="form-group">
                    <label for="kategori">Kategori *</label>
                    <select name="kategori" id="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="makanan">Makanan</option>
                        <option value="merchandise">Merchandise</option>
                        <option value="kaos">Kaos</option>
                        <option value="gantungan_kunci">Gantungan Kunci</option>
                        <option value="oleh_oleh">Oleh-oleh</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi *</label>
                    <textarea name="deskripsi" id="deskripsi" required placeholder="Jelaskan produk secara detail..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="harga">Harga (Rp) *</label>
                    <input type="number" name="harga" id="harga" min="0" step="1000" required>
                </div>
                
                <div class="form-group">
                    <label for="stok">Stok *</label>
                    <input type="number" name="stok" id="stok" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="stok_minimum">Stok Minimum *</label>
                    <input type="number" name="stok_minimum" id="stok_minimum" min="1" value="5" required>
                </div>

                <div class="form-group">
                    <label for="gambar">Gambar Produk</label>
                    <input type="file" name="gambar" id="gambar" accept="image/*">
                    <div id="gambarPreview"></div>
                </div>
                
                <div class="form-group" id="availabilityGroup" style="display: none;">
                    <label>
                        <input type="checkbox" name="is_available" id="is_available" checked>
                        Produk tersedia untuk dijual
                    </label>
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
            <p>Apakah Anda yakin ingin menghapus produk "<span id="deleteProductName"></span>"?</p>
            <p style="color: #dc3545; font-size: 0.9rem;">Tindakan ini tidak dapat dibatalkan.</p>
            
            <form id="deleteForm" method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="product_id" id="deleteProductId">
                
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
            document.getElementById('modalTitle').textContent = 'Tambah Produk';
            document.getElementById('formAction').value = 'add';
            document.getElementById('productForm').reset();
            document.getElementById('availabilityGroup').style.display = 'none';
            document.getElementById('productModal').classList.add('active');
        }
        
        function openEditModal(product) {
            document.getElementById('modalTitle').textContent = 'Edit Produk';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('productId').value = product.id;
            document.getElementById('nama_produk').value = product.nama_produk;
            document.getElementById('kategori').value = product.kategori;
            document.getElementById('deskripsi').value = product.deskripsi;
            document.getElementById('harga').value = product.harga;
            document.getElementById('stok').value = product.stok;
            document.getElementById('stok_minimum').value = product.stok_minimum;
            document.getElementById('is_available').checked = product.is_available == 1;
            document.getElementById('availabilityGroup').style.display = 'block';
            document.getElementById('productModal').classList.add('active');
        
        if (product.gambar_url) {
            document.getElementById('gambarPreview').innerHTML = `
                <small>
                Gambar saat ini: 
                <a href="${product.gambar_url}" target="_blank">Lihat</a>
                </small>`;
            } else {
            document.getElementById('gambarPreview').innerHTML = '';
         }
        
        // Auto-open edit modal if edit parameter exists
        <?php if ($edit_product): ?>
            openEditModal(<?php echo json_encode($edit_product); ?>);
        <?php endif; ?>
        }
        
        function closeModal() {
            document.getElementById('productModal').classList.remove('active');
        }
        
        function confirmDelete(productId, productName) {
            document.getElementById('deleteProductId').value = productId;
            document.getElementById('deleteProductName').textContent = productName;
            document.getElementById('deleteModal').classList.add('active');
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }
        
        // Filter functions
        function applyFilters() {
            const search = document.getElementById('searchInput').value;
            const category = document.getElementById('categoryFilter').value;
            const status = document.getElementById('statusFilter').value;
            
            const params = new URLSearchParams();
            if (search) params.set('search', search);
            if (category) params.set('category', category);
            if (status) params.set('status', status);
            
            window.location.href = 'products.php?' + params.toString();
        }
        
        // Event listeners
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
        
        document.getElementById('categoryFilter').addEventListener('change', applyFilters);
        document.getElementById('statusFilter').addEventListener('change', applyFilters);
        
        // Close modal when clicking outside
        document.getElementById('productModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
        
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
    <script>
document.getElementById('searchInput').addEventListener('input', function () {
    const query = this.value;
    const grid = document.querySelector('.products-grid');
    const emptyState = document.querySelector('.empty-state');

    if (query.length === 0) {
        location.reload(); // kalau kosong, reload halaman
        return;
    }

    fetch('search_products.php?search=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(products => {
            grid.innerHTML = '';
            if (products.length === 0) {
                grid.style.display = 'none';
                if (emptyState) emptyState.style.display = 'block';
                return;
            }

            grid.style.display = 'grid';
            if (emptyState) emptyState.style.display = 'none';

            products.forEach(product => {
                const card = document.createElement('div');
                card.className = 'product-card';
                card.innerHTML = `
                <div class="product-image">
                    ${product.gambar_url ? 
                        `<img src="${product.gambar_url}" alt="${product.nama_produk}" />` : 
                        `<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #666;">
                            📷 Tidak ada gambar
                        </div>`}
                    <div class="product-status ${product.is_available ? 'status-available' : 'status-unavailable'}">
                        ${product.is_available ? 'Tersedia' : 'Tidak Tersedia'}
                    </div>
                </div>
                <div class="product-content">
                    <h3 class="product-name">${product.nama_produk}</h3>
                    <span class="product-category">${product.kategori}</span>
                    <p class="product-description">${product.deskripsi.substring(0,100)}</p>
                    <div class="product-price">Rp ${parseInt(product.harga).toLocaleString()}</div>
                    <div class="product-stock">
                        Stok: ${product.stok} (Min: ${product.stok_minimum})
                    </div>
                    <div class="product-actions">
                        <button class="btn-small btn-edit" onclick='openEditModal(${JSON.stringify(product)})'>✏️ Edit</button>
                        <button class="btn-small btn-delete" onclick='confirmDelete(${product.id}, "${product.nama_produk}")'>🗑️ Hapus</button>
                    </div>
                </div>`;
                grid.appendChild(card);
            });
        })
        .catch(err => console.error(err));
});
</script>
</body>
</html>