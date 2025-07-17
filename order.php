<?php
require_once 'config/database.php';
requireLogin();

$database = new Database();
$db = $database->getConnection();

// Get all HIMADA for dropdown
$query = "SELECT id, nama, nama_lengkap FROM himada WHERE is_active = TRUE ORDER BY nama";
$stmt = $db->prepare($query);
$stmt->execute();
$himadas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$error = '';
$success = '';
$current_step = 1;
$order_data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'get_products':
                header('Content-Type: application/json');
                $himada_id = (int)$_POST['himada_id'];
                
                $query = "SELECT id, nama_produk, harga, stok, deskripsi, gambar_url 
                         FROM products 
                         WHERE himada_id = :himada_id AND is_available = TRUE AND stok > 0
                         ORDER BY nama_produk";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':himada_id', $himada_id);
                $stmt->execute();
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($products as &$product) {
                    if (strpos($product['gambar_url'], '../') === 0) {
                        $product['gambar_url'] = substr($product['gambar_url'], 3);
                    }
                }
                unset($product);

                echo json_encode($products);
                exit;
                
            case 'add_to_cart':
                // Handle add to cart logic
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }
                
                $himada_id = (int)$_POST['himada_id'];
                $product_id = (int)$_POST['product_id'];
                $quantity = (int)$_POST['quantity'];
                $notes = sanitizeInput($_POST['notes'] ?? '');
                
                // Validate product and stock
                $query = "SELECT nama_produk, harga, stok FROM products WHERE id = :product_id AND himada_id = :himada_id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':product_id', $product_id);
                $stmt->bindParam(':himada_id', $himada_id);
                $stmt->execute();
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($product && $product['stok'] >= $quantity) {
                    $cart_key = $himada_id . '_' . $product_id;
                    $_SESSION['cart'][$cart_key] = [
                        'himada_id' => $himada_id,
                        'product_id' => $product_id,
                        'nama_produk' => $product['nama_produk'],
                        'harga' => $product['harga'],
                        'quantity' => $quantity,
                        'notes' => $notes,
                        'subtotal' => $product['harga'] * $quantity
                    ];
                    
                    echo json_encode(['success' => true, 'message' => 'Produk ditambahkan ke keranjang']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Stok tidak mencukupi']);
                }
                exit;
                
            case 'process_order':
                // Process final order
                if (empty($_SESSION['cart'])) {
                    $error = 'Keranjang belanja kosong';
                    break;
                }
                
                try {
                    $db->beginTransaction();
                    
                    // Create order
                    $order_number = 'HMJ' . date('Ymd') . sprintf('%04d', rand(1, 9999));
                    $total_amount = array_sum(array_column($_SESSION['cart'], 'subtotal'));
                    $catatan_umum = sanitizeInput($_POST['catatan_umum'] ?? '');
                    
                    $query = "INSERT INTO orders (user_id, order_number, total_amount, catatan_umum) 
                             VALUES (:user_id, :order_number, :total_amount, :catatan_umum)";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':user_id', $_SESSION['user_id']);
                    $stmt->bindParam(':order_number', $order_number);
                    $stmt->bindParam(':total_amount', $total_amount);
                    $stmt->bindParam(':catatan_umum', $catatan_umum);
                    $stmt->execute();
                    
                    $order_id = $db->lastInsertId();
                    
                    // Create order items
                    foreach ($_SESSION['cart'] as $item) {
                        $query = "INSERT INTO order_items (order_id, himada_id, product_id, quantity, price, catatan_produk) 
                                 VALUES (:order_id, :himada_id, :product_id, :quantity, :price, :catatan_produk)";
                        $stmt = $db->prepare($query);
                        $stmt->bindParam(':order_id', $order_id);
                        $stmt->bindParam(':himada_id', $item['himada_id']);
                        $stmt->bindParam(':product_id', $item['product_id']);
                        $stmt->bindParam(':quantity', $item['quantity']);
                        $stmt->bindParam(':price', $item['harga']);
                        $stmt->bindParam(':catatan_produk', $item['notes']);
                        $stmt->execute();
                    }
                    
                    $db->commit();
                    
                    // Clear cart
                    unset($_SESSION['cart']);
                    
                    $success = "Pesanan berhasil dibuat dengan nomor: $order_number";
                    
                    
                } catch (Exception $e) {
                    $db->rollBack();
                    $error = 'Terjadi kesalahan saat memproses pesanan';
                    error_log("Order error: " . $e->getMessage());
                }
                break;

                case 'remove_from_cart':
                    if (!isset($_SESSION['cart'])) {
                        echo json_encode(['success' => false, 'message' => 'Keranjang kosong']);
                        exit;
                    }

                    $cart_key = $_POST['cart_key'] ?? '';
                    if (!$cart_key || !isset($_SESSION['cart'][$cart_key])) {
                        echo json_encode(['success' => false, 'message' => 'Item tidak ditemukan di keranjang']);
                        exit;
                    }

                    unset($_SESSION['cart'][$cart_key]);
                    echo json_encode(['success' => true, 'message' => 'Item dihapus dari keranjang']);
                    exit;

        }
    }
}

// Get cart summary
$cart_summary = [];
$cart_total = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $himada_totals = [];
    foreach ($_SESSION['cart'] as $item) {
        if (!isset($himada_totals[$item['himada_id']])) {
            $himada_totals[$item['himada_id']] = [
                'items' => [],
                'total' => 0
            ];
        }
        $himada_totals[$item['himada_id']]['items'][] = $item;
        $himada_totals[$item['himada_id']]['total'] += $item['subtotal'];
        $cart_total += $item['subtotal'];
    }
    
    // Get HIMADA names
    foreach ($himada_totals as $himada_id => $data) {
        $query = "SELECT nama FROM himada WHERE id = :himada_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':himada_id', $himada_id);
        $stmt->execute();
        $himada_name = $stmt->fetch(PDO::FETCH_ASSOC)['nama'];
        
        $cart_summary[$himada_id] = [
            'himada_name' => $himada_name,
            'items' => $data['items'],
            'total' => $data['total']
        ];
    }
}

// Check user role and redirect accordingly
$user_role = getUserRole();
$is_logged_in = isLoggedIn();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan - H!MANJA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/order.css">
</head>
<style>
    .btn-remove-cart {
    background-color: #fbd1d9; /* pink pastel */
    color: #b91c1c; /* merah lembut untuk teks/icon */
    border: none;
    padding: 0.3rem 0.75rem;
    font-size: 0.9rem;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    }

    .btn-remove-cart:hover {
    background-color: #f8bfc9;
    color: #991b1b;
    transform: scale(1.05);
    }

    .btn-remove-cart:active {
    transform: scale(0.95);
    }

    .btn-remove-cart:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    }

</style>
<body>

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
                            <li><a href="order.php" class="nav-link active">H!PO</a></li>
                            <li><a href="my-orders.php" class="nav-link">H!Loot</a></li>
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

    <!-- Order Section -->
    <section class="order-section">
        <div class="container">
            <div class="order-header">
                <h1 class="order-title">🛒 Pemesanan H!MANJA</h1>
                <p class="order-subtitle">Pesan produk khas daerah dari HIMADA favorit Anda!</p>
            </div>

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

            <div class="order-container">
                <!-- Order Form -->
                <div class="order-form-section">
                    <div class="order-card">
                        <div class="card-header">
                            <h2 class="card-title">
                                <span class="card-icon">📝</span>
                                Formulir Pemesanan
                            </h2>
                        </div>
                        <div class="card-content">
                            <!-- Customer Info -->
                            <div class="customer-info">
                                <h3>Informasi Pemesan</h3>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <label>Nama Lengkap</label>
                                        <span><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <label>Kelas</label>
                                        <span><?php echo htmlspecialchars($_SESSION['kelas']); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <label>Email</label>
                                        <span><?php echo htmlspecialchars($_SESSION['email']); ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Selection -->
                            <div class="product-selection">
                                
                                <div class="selection-form">
                                    <div class="form-group">
                                        <label for="himada_select">Pilih HIMADA</label>
                                        <select id="himada_select" class="form-select">
                                            <option value="">-- Pilih HIMADA --</option>
                                            <?php foreach ($himadas as $himada): ?>
                                                <option value="<?php echo $himada['id']; ?>">
                                                    <?php echo htmlspecialchars($himada['nama'] . ' - ' . $himada['nama_lengkap']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div id="products_container" class="products-container" style="display: none;">
                                        <h4>Produk Tersedia</h4>
                                        <div id="products_list" class="products-list">
                                            <!-- Products will be loaded here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="order-summary-section">
                    <div class="order-card">
                        <div class="card-header">
                            <h2 class="card-title">
                                <span class="card-icon">🛒</span>
                                Keranjang Belanja
                            </h2>
                        </div>
                        <div class="card-content">
                            <div id="cart_summary">
                                <?php if (empty($cart_summary)): ?>
                                    <div class="empty-cart">
                                        <div class="empty-icon">🛒</div>
                                        <p>Keranjang belanja kosong</p>
                                        <small>Pilih produk dari HIMADA untuk mulai berbelanja</small>
                                    </div>
                                <?php else: ?>
                                <?php foreach ($data['items'] as $item): ?>
                                    <div class="cart-item">
                                        <div class="item-info">
                                            <span class="item-name"><?php echo htmlspecialchars($item['nama_produk']); ?></span>
                                            <span class="item-quantity">x<?php echo $item['quantity']; ?></span>
                                        </div>
                                        <div class="item-price">
                                            <?php echo formatCurrency($item['subtotal']); ?>
                                        </div>
                                        <button 
                                            class="btn-remove-cart" 
                                            data-cart-key="<?php echo $item['himada_id'].'_'.$item['product_id']; ?>">
                                            ❌ Hapus
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                                    
                                    <div class="cart-total">
                                        <h3>Total Keseluruhan: <?php echo formatCurrency($cart_total); ?></h3>
                                    </div>

                                    <form method="POST" class="checkout-form">
                                        <div class="form-group">
                                            <label for="catatan_umum">Catatan Tambahan</label>
                                            <textarea id="catatan_umum" name="catatan_umum" class="form-textarea" 
                                                     placeholder="Catatan khusus untuk pesanan Anda..."></textarea>
                                        </div>
                                        
                                        <button type="submit" name="action" value="process_order" class="btn-primary btn-checkout">
                                            <span class="btn-icon">💳</span>
                                            <span class="btn-text">Selesaikan Pesanan</span>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal_product_name">Nama Produk</h3>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="product-details">
                    <img id="modal_product_image" src="/placeholder.svg" alt="Product Image" class="product-image">
                    <div class="product-info">
                        <p id="modal_product_description" class="product-description"></p>
                        <p id="modal_product_price" class="product-price"></p>
                        <p id="modal_product_stock" class="product-stock"></p>
                    </div>
                </div>
                <form id="add_to_cart_form" class="add-to-cart-form">
                    <input type="hidden" id="modal_himada_id" name="himada_id">
                    <input type="hidden" id="modal_product_id" name="product_id">
                    
                    <div class="form-group">
                        <label for="quantity">Jumlah</label>
                        <div class="quantity-input">
                            <button type="button" class="qty-btn" onclick="changeQuantity(-1)">-</button>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="10">
                            <button type="button" class="qty-btn" onclick="changeQuantity(1)">+</button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Catatan Produk</label>
                        <textarea id="notes" name="notes" class="form-textarea" 
                                 placeholder="Catatan khusus untuk produk ini..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn-primary">
                        <span class="btn-icon">🛒</span>
                        <span class="btn-text">Tambah ke Keranjang</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/js/order.js"></script>
</body>
</html>
