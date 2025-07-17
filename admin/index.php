<?php
require_once __DIR__ . '/../config/database.php';
requireHimadaAdmin();

$database = new Database();
$db = $database->getConnection();

// Fetch HIMADA data
$query = "SELECT * FROM himada ORDER BY nama";
$stmt = $db->prepare($query);
$stmt->execute();
$himadas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch featured products
$query = "SELECT p.*, h.nama as himada_nama, h.warna_tema 
          FROM products p 
          JOIN himada h ON p.himada_id = h.id 
          WHERE p.is_available = 1 
          ORDER BY RAND() 
          LIMIT 6";
$stmt = $db->prepare($query);
$stmt->execute();
$featured_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check user role and redirect accordingly
$user_role = getUserRole();
$is_logged_in = isLoggedIn();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>H!MANJA - Himada Belanja | Jajan Lokal, Rasa Nasional, Akses Digital!</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/animations.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar">
            <div class="nav-container">

            <!--Logo-->
                <div class="nav-logo">
                    <div class="logo-icon">🛍️</div>
                    <span class="logo-text"><strong>H!MANJA</strong></span>
                </div>

                <!-- KANAN: MENU + AUTH -->
                <div class="nav-right">
                    <ul class="nav-menu">
                        <li><a href="#beranda" class="nav-link active">Beranda</a></li>
                        <li><a href="history.php" class="nav-link">H!Story</a></li>
                        <li><a href="products.php" class="nav-link">Produk</a></li>
                        <?php if ($is_logged_in): ?>
                            <li><a href="order.php" class="nav-link">H!PO</a></li>
                            <li><a href="my-orders.php" class="nav-link">H!Loot</a></li>
                        <?php endif; ?>
                        <li> <a href="#" class="nav-link search-trigger"> 🔍 Cari
                        <span class="search-shortcut">Ctrl+K</span>
                        </a>
                        </li>
                    </ul>

                <div class="nav-auth">
                    <?php if (isLoggedIn()): ?>
                        <span class="welcome-text">Halo, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</span>
                        <a href="logout.php" class="btn-logout">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn-login">Login</a>
                    <?php endif; ?>
                </div>
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
    <section id="beranda" class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">
                        Selamat datang di 
                        <span class="gradient-text">H!MANJA</span>
                    </h1>
                    <p class="hero-subtitle">
                        Platform jastip terpercaya untuk mahasiswa STIS
                    </p>
                    <div class="hero-tagline">
                        <span class="tagline-highlight">Jajan Lokal, Rasa Nasional, Akses Digital!</span>
                    </div>
                    <div class="hero-buttons">
                        <a href="products.php" class="btn-primary pulse">
                            🛒 Mulai Belanja
                        </a>
                        <a href="#himada-section" class="btn-secondary">
                            🗺️ Jelajahi HIMADA
                        </a>
                    </div>
                </div>
                <div class="hero-visual">
                    <div class="floating-elements">
                        <div class="float-item food">🍜</div>
                        <div class="float-item merch">👕</div>
                        <div class="float-item keychain">🔑</div>
                        <div class="float-item gift">🎁</div>
                        <div class="float-item map">🗺️</div>
                    </div>
                    <div class="hero-illustration">
                        <div class="indonesia-map">
                            <div class="map-dot" style="top: 30%; left: 20%;" data-himada="Aceh">📍</div>
                            <div class="map-dot" style="top: 45%; left: 15%;" data-himada="Sumut">📍</div>
                            <div class="map-dot" style="top: 55%; left: 18%;" data-himada="Sumbar">📍</div>
                            <div class="map-dot" style="top: 65%; left: 25%;" data-himada="Sumsel">📍</div>
                            <div class="map-dot" style="top: 70%; left: 30%;" data-himada="Lampung">📍</div>
                            <div class="map-dot" style="top: 60%; left: 45%;" data-himada="Jabar">📍</div>
                            <div class="map-dot" style="top: 65%; left: 50%;" data-himada="Jateng">📍</div>
                            <div class="map-dot" style="top: 70%; left: 55%;" data-himada="Jatim">📍</div>
                            <div class="map-dot" style="top: 80%; left: 60%;" data-himada="Bali">📍</div>
                            <div class="map-dot" style="top: 85%; left: 65%;" data-himada="NTB">📍</div>
                            <div class="map-dot" style="top: 90%; left: 70%;" data-himada="NTT">📍</div>
                            <div class="map-dot" style="top: 40%; left: 60%;" data-himada="Kalsel">📍</div>
                            <div class="map-dot" style="top: 35%; left: 65%;" data-himada="Kalteng">📍</div>
                            <div class="map-dot" style="top: 45%; left: 70%;" data-himada="Sulut">📍</div>
                            <div class="map-dot" style="top: 55%; left: 72%;" data-himada="Sulteng">📍</div>
                            <div class="map-dot" style="top: 65%; left: 68%;" data-himada="Sulsel">📍</div>
                            <div class="map-dot" style="top: 50%; left: 80%;" data-himada="Maluku">📍</div>
                            <div class="map-dot" style="top: 30%; left: 85%;" data-himada="Papua">📍</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-wave">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" class="shape-fill"></path>
                <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" class="shape-fill"></path>
                <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon">🏛️</div>
                    <div class="stat-number" data-target="20">0</div>
                    <div class="stat-label">HIMADA</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">🛍️</div>
                    <div class="stat-number" data-target="<?php echo count($featured_products) * 10; ?>">0</div>
                    <div class="stat-label">Produk</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">👥</div>
                    <div class="stat-number" data-target="2000">0</div>
                    <div class="stat-label">Mahasiswa</div>
                </div>
            </div>
        </div>
    </section>

    <!-- HIMADA Section -->
    <section id="himada-section" class="himada-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Jelajahi 20 HIMADA</h2>
                <p class="section-subtitle">Temukan kekayaan budaya dan kuliner dari seluruh Nusantara</p>
            </div>
            
            <div class="himada-grid">
                <?php foreach ($himadas as $himada): ?>
                <div class="himada-card" style="--card-color: <?php echo $himada['warna_tema']; ?>">
                    <div class="himada-header">
                        <div class="himada-icon">🏛️</div>
                        <h3 class="himada-name"><?php echo htmlspecialchars($himada['nama']); ?></h3>
                    </div>
                    <div class="himada-content">
                        <h4 class="himada-full-name"><?php echo htmlspecialchars($himada['nama_lengkap']); ?></h4>
                        <p class="himada-region">📍 <?php echo htmlspecialchars($himada['daerah_asal']); ?></p>
                        <p class="himada-description"><?php echo htmlspecialchars($himada['deskripsi']); ?></p>
                    </div>
                    <div class="himada-footer">
                        <a href="history.php?himada=<?php echo $himada['id']; ?>" class="btn-himada">
                            Selengkapnya
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="featured-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Produk Terkini</h2>
                <p class="section-subtitle">Cicipi kelezatan dari berbagai daerah</p>
            </div>
            
            <div class="products-grid">
                <?php foreach ($featured_products as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?php echo htmlspecialchars($product['gambar_url']); ?>" 
                             alt="<?php echo htmlspecialchars($product['nama_produk']); ?>"
                             onerror="this.src='/placeholder.svg?height=200&width=200'">
                        <div class="product-badge" style="background: <?php echo $product['warna_tema']; ?>">
                            <?php echo htmlspecialchars($product['himada_nama']); ?>
                        </div>
                    </div>
                    <div class="product-content">
                        <h3 class="product-name"><?php echo htmlspecialchars($product['nama_produk']); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars(substr($product['deskripsi'], 0, 100)) . '...'; ?></p>
                        <div class="product-footer">
                            <span class="product-price">Rp <?php echo number_format($product['harga'], 0, ',', '.'); ?></span>
                            <span class="product-stock">Stok: <?php echo $product['stok']; ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="section-cta">
                <a href="products.php" class="btn-primary">Lihat Semua Produk</a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">Siap Mulai Belanja?</h2>
                <p class="cta-subtitle">Bergabunglah dengan ribuan mahasiswa STIS yang sudah merasakan kemudahan H!MANJA</p>
                <div class="cta-buttons">
                    <?php if (!isLoggedIn()): ?>
                        <!-- REGISTRATION DISABLED - Show info message instead -->
                        <div class="registration-notice" style="background: rgba(255, 193, 7, 0.1); border: 1px solid #ffc107; border-radius: 10px; padding: 20px; margin-bottom: 20px; text-align: center;">
                            <p style="color: #856404; margin: 0 0 10px 0; font-weight: 600;">
                                📝 Pendaftaran Akun Baru Sementara Ditutup
                            </p>
                            <p style="color: #856404; margin: 0; font-size: 0.9rem;">
                                Untuk membuat akun baru, silakan hubungi administrator sistem atau gunakan akun demo yang tersedia.
                            </p>
                        </div>
                        <a href="login.php" class="btn-primary">Login ke Akun Anda</a>
                        <a href="switch-account.php" class="btn-secondary">Coba Akun Demo</a>
                    <?php else: ?>
                        <a href="order.php" class="btn-primary pulse">Mulai Pesan</a>
                        <a href="products.php" class="btn-secondary">Jelajahi Produk</a>
                    <?php endif; ?>
                </div>
            </div>
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
                        <li><a href="#beranda">Beranda</a></li>
                        <li><a href="history.php">H!Story</a></li>
                        <li><a href="products.php">Produk</a></li>
                        <li><a href="order.php">H!PO</a></li>
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

    <!-- Live Search Modal -->
    <div id="searchModal" class="search-modal">
        <div class="search-modal-content">
            <div class="search-header">
                <input type="text" id="liveSearch" placeholder="Cari produk, HIMADA, atau kategori..." autocomplete="off">
                <button class="search-close">&times;</button>
            </div>
            <div id="searchResults" class="search-results"></div>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/search.js"></script>
</body>
</html>
