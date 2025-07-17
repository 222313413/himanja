<?php
require_once 'config/database.php';
startSession();

$database = new Database();
$db = $database->getConnection();

// Check user role and redirect accordingly
$user_role = getUserRole();
$is_logged_in = isLoggedIn();

// Get filter parameters
$himada_filter = isset($_GET['himada']) ? (int)$_GET['himada'] : null;
$category_filter = isset($_GET['category']) ? sanitizeInput($_GET['category']) : '';
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$sort = isset($_GET['sort']) ? sanitizeInput($_GET['sort']) : 'newest';

// Build query
$query = "SELECT p.*, h.nama as himada_nama, h.warna_tema, h.daerah_asal
          FROM products p 
          JOIN himada h ON p.himada_id = h.id 
          WHERE p.is_available = 1";

$params = [];

if ($himada_filter) {
    $query .= " AND p.himada_id = :himada_id";
    $params[':himada_id'] = $himada_filter;
}

if ($category_filter) {
    $query .= " AND p.kategori = :category";
    $params[':category'] = $category_filter;
}

if ($search) {
    $query .= " AND (p.nama_produk LIKE :search OR p.deskripsi LIKE :search OR h.nama LIKE :search)";
    $params[':search'] = "%$search%";
}

// Sorting
switch ($sort) {
    case 'price_low':
        $query .= " ORDER BY p.harga ASC";
        break;
    case 'price_high':
        $query .= " ORDER BY p.harga DESC";
        break;
    case 'name':
        $query .= " ORDER BY p.nama_produk ASC";
        break;
    case 'popular':
        $query .= " ORDER BY p.stok DESC";
        break;
    default:
        $query .= " ORDER BY p.created_at DESC";
}

$stmt = $db->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get all HIMADA for filter
$himada_query = "SELECT id, nama, warna_tema FROM himada WHERE is_active = 1 ORDER BY nama";
$himada_stmt = $db->prepare($himada_query);
$himada_stmt->execute();
$himadas = $himada_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get selected HIMADA info if filtered
$selected_himada = null;
if ($himada_filter) {
    $selected_query = "SELECT * FROM himada WHERE id = :id";
    $selected_stmt = $db->prepare($selected_query);
    $selected_stmt->bindParam(':id', $himada_filter);
    $selected_stmt->execute();
    $selected_himada = $selected_stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $selected_himada ? 'Produk ' . htmlspecialchars($selected_himada['nama']) : 'Semua Produk'; ?> - H!MANJA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/animations.css">
    <style>
        .products-hero {
            background: var(--gradient-primary);
            padding: 8rem 0 4rem;
            text-align: center;
            color: white;
        }
        
        .products-hero h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }
        
        .products-hero p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .himada-info {
            background: rgba(255,255,255,0.1);
            padding: 2rem;
            border-radius: 15px;
            margin-top: 2rem;
            backdrop-filter: blur(10px);
        }
        
        .filters-section {
            background: white;
            padding: 2rem 0;
            border-bottom: 1px solid #e9ecef;
            position: sticky;
            top: 80px;
            z-index: 100;
        }
        
        .filters-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .filters-left {
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
            padding: 0.75rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .filter-input:focus {
            outline: none;
            border-color: var(--soft-blue);
        }
        
        .products-count {
            color: #666;
            font-weight: 500;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            padding: 3rem 0;
        }
        
        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }
        
        .product-image {
            position: relative;
            height: 250px;
            overflow: hidden;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .product-card:hover .product-image img {
            transform: scale(1.1);
        }
        
        .product-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            color: white;
            backdrop-filter: blur(10px);
        }
        
        .stock-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            background: rgba(255,255,255,0.9);
            color: #333;
        }
        
        .stock-low {
            background: rgba(255,193,7,0.9);
            color: #856404;
        }
        
        .product-content {
            padding: 1.5rem;
        }
        
        .product-category {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: #f8f9fa;
            border-radius: 15px;
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 0.75rem;
            text-transform: capitalize;
        }
        
        .product-name {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
            line-height: 1.3;
        }
        
        .product-description {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
        }
        
        .product-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: #333;
        }
        
        .product-stock {
            font-size: 0.9rem;
            color: #666;
        }
        
        .btn-order {
            background: var(--gradient-primary);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            text-align: center;
        }
        
        .btn-order:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(178,231,232,0.4);
        }
        
        .btn-order:disabled {
            opacity: 0.5;
            cursor: not-allowed;
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
        
        .empty-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .empty-description {
            font-size: 1rem;
            margin-bottom: 2rem;
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
            .products-hero h1 {
                font-size: 2rem;
            }
            
            .filters-container {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filters-left {
                flex-direction: column;
            }
            
            .products-grid {
                grid-template-columns: 1fr;
                padding: 2rem 0;
            }
            
            .quick-filters {
                justify-content: center;
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

                <!-- KANAN: MENU + AUTH -->
                <div class="nav-right">
                    <ul class="nav-menu">
                        <li><a href="index.php" class="nav-link">Beranda</a></li>
                        <li><a href="history.php" class="nav-link">H!Story</a></li>
                        <li><a href="products.php" class="nav-link active">Produk</a></li>
                        <?php if ($is_logged_in): ?>
                            <li><a href="order.php" class="nav-link">H!PO</a></li>
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

    <!-- Hero Section -->
    <section class="products-hero">
        <div class="container">
            <?php if ($selected_himada): ?>
                <h1>Produk <?php echo htmlspecialchars($selected_himada['nama']); ?></h1>
                <p><?php echo htmlspecialchars($selected_himada['nama_lengkap']); ?> - <?php echo htmlspecialchars($selected_himada['daerah_asal']); ?></p>
                <div class="himada-info">
                    <p><?php echo htmlspecialchars($selected_himada['deskripsi']); ?></p>
                </div>
            <?php else: ?>
                <h1>Jelajahi Semua Produk</h1>
                <p>Temukan produk khas daerah dari 20 HIMADA di seluruh Indonesia</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="filters-section">
        <div class="container">
            <div class="filters-container">
                <div class="filters-left">
                    <div class="filter-group">
                        <label>Cari Produk</label>
                        <input type="text" class="filter-input" id="searchInput" placeholder="Nama produk atau HIMADA..." value="<?php echo htmlspecialchars($search); ?>">
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
                    
                    <div class="filter-group">
                        <label>Kategori</label>
                        <select class="filter-input" id="categoryFilter">
                            <option value="">Semua Kategori</option>
                            <option value="makanan" <?php echo $category_filter === 'makanan' ? 'selected' : ''; ?>>Makanan</option>
                            <option value="merchandise" <?php echo $category_filter === 'merchandise' ? 'selected' : ''; ?>>Merchandise</option>
                            <option value="kaos" <?php echo $category_filter === 'kaos' ? 'selected' : ''; ?>>Kaos</option>
                            <option value="gantungan_kunci" <?php echo $category_filter === 'gantungan_kunci' ? 'selected' : ''; ?>>Gantungan Kunci</option>
                            <option value="oleh_oleh" <?php echo $category_filter === 'oleh_oleh' ? 'selected' : ''; ?>>Oleh-oleh</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Urutkan</label>
                        <select class="filter-input" id="sortFilter">
                            <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Terbaru</option>
                            <option value="name" <?php echo $sort === 'name' ? 'selected' : ''; ?>>Nama A-Z</option>
                            <option value="price_low" <?php echo $sort === 'price_low' ? 'selected' : ''; ?>>Harga Terendah</option>
                            <option value="price_high" <?php echo $sort === 'price_high' ? 'selected' : ''; ?>>Harga Tertinggi</option>
                            <option value="popular" <?php echo $sort === 'popular' ? 'selected' : ''; ?>>Terpopuler</option>
                        </select>
                    </div>
                </div>
                
                <div class="products-count">
                    Ditemukan <?php echo count($products); ?> produk
                </div>
            </div>
            
            <!-- Quick Filters -->
            <div class="quick-filters">
                <a href="products.php" class="quick-filter <?php echo !$himada_filter && !$category_filter ? 'active' : ''; ?>">
                    🏠 Semua
                </a>
                <a href="products.php?category=makanan" class="quick-filter <?php echo $category_filter === 'makanan' ? 'active' : ''; ?>">
                    🍜 Makanan
                </a>
                <a href="products.php?category=merchandise" class="quick-filter <?php echo $category_filter === 'merchandise' ? 'active' : ''; ?>">
                    🎁 Merchandise
                </a>
                <a href="products.php?category=kaos" class="quick-filter <?php echo $category_filter === 'kaos' ? 'active' : ''; ?>">
                    👕 Kaos
                </a>
                <a href="products.php?category=gantungan_kunci" class="quick-filter <?php echo $category_filter === 'gantungan_kunci' ? 'active' : ''; ?>">
                    🔑 Gantungan Kunci
                </a>
                <a href="products.php?category=oleh_oleh" class="quick-filter <?php echo $category_filter === 'oleh_oleh' ? 'active' : ''; ?>">
                    🎁 Oleh-oleh
                </a>
            </div>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="products-section">
        <div class="container">
            <?php if (empty($products)): ?>
                <div class="empty-state">
                    <div class="empty-icon">🔍</div>
                    <h3 class="empty-title">Produk Tidak Ditemukan</h3>
                    <p class="empty-description">
                        <?php if ($search): ?>
                            Tidak ada produk yang cocok dengan pencarian "<?php echo htmlspecialchars($search); ?>"
                        <?php elseif ($selected_himada): ?>
                            HIMADA <?php echo htmlspecialchars($selected_himada['nama']); ?> belum memiliki produk
                        <?php else: ?>
                            Belum ada produk yang tersedia saat ini
                        <?php endif; ?>
                    </p>
                    <a href="products.php" class="btn-primary">Lihat Semua Produk</a>
                </div>
            <?php else: ?>
                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <?php if (!empty($product['gambar_url'])): ?>
                                    <<img src="<?php echo htmlspecialchars(ltrim(preg_replace('/^\.\.\//', '', $product['gambar_url']), '/')); ?>" 
                                        alt="<?php echo htmlspecialchars($product['nama_produk']); ?>"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div style="display: none; align-items: center; justify-content: center; height: 100%; background: #f8f9fa; color: #666;">
                                        📷 Gambar tidak tersedia
                                    </div>
                                <?php else: ?>
                                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; background: #f8f9fa; color: #666;">
                                        📷 Tidak ada gambar
                                    </div>
                                <?php endif; ?>
                                
                                <div class="product-badge" style="background: <?php echo $product['warna_tema']; ?>">
                                    <?php echo htmlspecialchars($product['himada_nama']); ?>
                                </div>
                                
                                <?php if ($product['stok'] <= $product['stok_minimum']): ?>
                                    <div class="stock-badge stock-low">
                                        Stok Terbatas
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="product-content">
                                <span class="product-category"><?php echo ucfirst($product['kategori']); ?></span>
                                <h3 class="product-name"><?php echo htmlspecialchars($product['nama_produk']); ?></h3>
                                <p class="product-description"><?php echo htmlspecialchars($product['deskripsi']); ?></p>
                                
                                <div class="product-footer">
                                    <div>
                                        <div class="product-price"><?php echo formatCurrency($product['harga']); ?></div>
                                        <div class="product-stock">Stok: <?php echo $product['stok']; ?></div>
                                    </div>
                                    
                                    <?php if ($is_logged_in): ?>
                                        <?php if ($product['stok'] > 0): ?>
                                            <button class="btn-order" onclick="orderProduct(<?php echo $product['id']; ?>)">
                                                🛒 Pesan
                                            </button>
                                        <?php else: ?>
                                            <button class="btn-order" disabled>
                                                Stok Habis
                                            </button>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <a href="login.php" class="btn-order">
                                            🔐 Login untuk Pesan
                                        </a>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
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
                        <li><a href="#products">Produk</a></li>
                        <?php if ($is_logged_in): ?>
                            <li><a href="order.php">H!PO</a></li>
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
        // Filter functions
        const searchInput = document.getElementById('searchInput');

        let debounceTimer;

        searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {
            const keyword = searchInput.value.trim();

            const params = new URLSearchParams(window.location.search);

            if (keyword) {
            params.set('search', keyword);
            } else {
            params.delete('search');
            }

            window.location.href = 'products.php?' + params.toString();
        }, 300);
        });

        function applyFilters() {
            const search = document.getElementById('searchInput').value;
            const himada = document.getElementById('himadaFilter').value;
            const category = document.getElementById('categoryFilter').value;
            const sort = document.getElementById('sortFilter').value;
            
            const params = new URLSearchParams();
            if (search) params.set('search', search);
            if (himada) params.set('himada', himada);
            if (category) params.set('category', category);
            if (sort && sort !== 'newest') params.set('sort', sort);
            
            window.location.href = 'products.php?' + params.toString();
        }
        
        
        document.getElementById('himadaFilter').addEventListener('change', applyFilters);
        document.getElementById('categoryFilter').addEventListener('change', applyFilters);
        document.getElementById('sortFilter').addEventListener('change', applyFilters);
        
        // Add animation to product cards
        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('animate-slide-up');
        });
    </script>

<script>
function orderProduct(productId) {
    if (!confirm('Yakin mau pesan produk ini?')) return;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'order_ajax.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert('✅ Pesanan berhasil: ' + response.message);
                    window.location.href = 'my-orders.php';
                } else {
                    alert('⚠️ Gagal: ' + response.message);
                }
            } catch (e) {
                alert('Terjadi kesalahan pada server.');
            }
        } else {
            alert('Gagal terhubung ke server.');
        }
    };

    xhr.send('product_id=' + encodeURIComponent(productId));
}
</script>


</body>
</html>
