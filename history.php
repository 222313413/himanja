<?php
session_start();
require_once 'config/database.php';

$is_logged_in = isLoggedIn();
$user_role = getUserRole();

// Check if specific HIMADA is requested
$specific_himada_id = isset($_GET['himada']) ? (int)$_GET['himada'] : null;
$is_specific_view = $specific_himada_id !== null;

// Get all active HIMADA with their stories from database
try {
    $database = new Database();
    $pdo = $database->getConnection();
    
    // Base query
    $base_query = "
        SELECT h.*, COUNT(DISTINCT u.id) as member_count,
               GROUP_CONCAT(
                    CONCAT(hs.id, '|', hs.judul, '|', hs.cerita, '|', IFNULL(hs.gambar_url, ''), '|', IFNULL(hs.fun_facts, ''))
                    SEPARATOR '###'
                    ) as stories
        FROM himada h 
        LEFT JOIN users u ON h.id = u.himada_id 
        LEFT JOIN himada_history hs ON h.id = hs.himada_id
        WHERE h.is_active = 1 
    ";
    
    // Add specific HIMADA filter if requested
    if ($is_specific_view) {
        $base_query .= " AND h.id = :himada_id ";
    }
    
    $base_query .= " GROUP BY h.id ORDER BY h.nama ASC";
    
    $stmt = $pdo->prepare($base_query);
    if ($is_specific_view) {
        $stmt->bindParam(':himada_id', $specific_himada_id, PDO::PARAM_INT);
    }
    $stmt->execute();
    $himada_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Process stories for each HIMADA
    foreach ($himada_list as &$himada) {
        $himada['story_list'] = [];
        if ($himada['stories']) {
            $stories = explode('###', $himada['stories']);
            foreach ($stories as $story) {
                if (!empty($story)) {
                    $parts = explode('|', $story, 5);
                    if (count($parts) >= 5) {
                        $himada['story_list'][] = [
                            'id' => $parts[0],
                            'judul' => $parts[1],
                            'cerita' => $parts[2],
                            'gambar_url' => isset($parts[3]) ? $parts[3] : null,
                            'fun_facts' => isset($parts[4]) ? explode('|', $parts[4]) : []
                        ];
                    }
                }
            }
        }
    }
    
    // If specific HIMADA requested but not found, redirect to general history
    if ($is_specific_view && empty($himada_list)) {
        header('Location: history.php');
        exit;
    }
    
} catch (Exception $e) {
    $himada_list = [];
    error_log("Error fetching HIMADA data: " . $e->getMessage());
}

// Get specific HIMADA data for hero section
$hero_himada = $is_specific_view && !empty($himada_list) ? $himada_list[0] : null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_specific_view && $hero_himada ? htmlspecialchars($hero_himada['nama']) . ' - ' : ''; ?>H!Story - Sejarah HIMADA | HIMANJA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        /* Hero Section */
        .hero-section {
            background: <?php echo $is_specific_view && $hero_himada ? 'linear-gradient(90deg, ' . $hero_himada['warna_tema'] . '40 0%, ' . $hero_himada['warna_tema'] . '80 100%)' : 'linear-gradient(135deg, #fbc2eb 100%, #a6c1ee 100%)'; ?>;
            color: purple;
            padding: 120px 0 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 2rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background: linear-gradient(80deg, #fff, #f0f8ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            opacity: 0.95;
            max-width: 700px;
            margin: 0 auto 2rem;
            line-height: 1.6;
            color : purple
        }

        .hero-description {
            background: rgba(255,255,255,0.15);
            padding: 2rem;
            border-radius: 20px;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255,255,255,0.2);
            max-width: 800px;
            margin: 0 auto;
            font-size: 1.1rem;
            line-height: 1.7;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255,255,255,0.2);
            color: purple;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .back-button:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }

        /* History Content */
        .history-content {
            padding: 80px 0;
            background: #f8fafc;
        }

        .section-title {
            text-align: center;
            font-size: 2.8rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 1rem;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            color: #64748b;
            margin-bottom: 4rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* HIMADA Grid */
        .himada-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .himada-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            transition: all 0.4s ease;
            border: 1px solid #e2e8f0;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .himada-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--himada-color, #667eea);
        }

        .himada-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .himada-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .himada-logo {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            background: var(--himada-color, #667eea);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            font-size: 1.3rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .himada-info h3 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1a202c;
            margin: 0 0 0.5rem 0;
        }

        .himada-info .himada-region {
            color: #64748b;
            font-size: 1rem;
            font-weight: 500;
        }

        .himada-description {
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }

        .himada-stats {
            display: flex;
            justify-content: space-between;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--himada-color, #667eea);
        }

        .stat-label {
            font-size: 0.85rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 500;
        }

        .click-hint {
            position: absolute;
            bottom: 1rem;
            right: 1.5rem;
            background: var(--himada-color, #667eea);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .himada-card:hover .click-hint {
            opacity: 1;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            backdrop-filter: blur(5px);
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            background: white;
            border-radius: 25px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            animation: slideUp 0.4s ease;
        }

        .modal-header {
            background: var(--himada-color, #667eea);
            color: white;
            padding: 2rem;
            border-radius: 25px 25px 0 0;
            position: relative;
        }

        .modal-header::before {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            right: 0;
            height: 20px;
            background: var(--himada-color, #667eea);
            clip-path: ellipse(100% 100% at 50% 0%);
        }

        .close-btn {
            position: absolute;
            top: 1rem;
            right: 1.5rem;
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            font-size: 1.5rem;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
        }

        .close-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        .modal-himada-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }

        .modal-himada-logo {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            font-size: 1.5rem;
        }

        .modal-himada-title {
            font-size: 2rem;
            font-weight: 800;
            margin: 0 0 0.5rem 0;
        }

        .modal-himada-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0;
        }

        .modal-body {
            padding: 2rem;
        }

        .story-section {
            margin-bottom: 2.5rem;
        }

        .story-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .story-content {
            color: #4a5568;
            line-height: 1.7;
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .story-image {
            width: 100%;
            max-width: 400px;
            height: 250px;
            object-fit: cover;
            border-radius: 15px;
            margin: 1rem 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .story-item {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 1rem;
            border-left: 4px solid var(--himada-color, #667eea);
        }

        .story-item-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 0.5rem;
        }

        .fun-facts {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 15px;
            border-left: 4px solid var(--himada-color, #667eea);
        }

        .fun-facts ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .fun-facts li {
            padding: 0.5rem 0;
            position: relative;
            padding-left: 2rem;
        }

        .fun-facts li::before {
            content: '✨';
            position: absolute;
            left: 0;
            top: 0.5rem;
        }

        .no-stories {
            text-align: center;
            padding: 3rem;
            color: #64748b;
            background: #f8fafc;
            border-radius: 15px;
            border: 2px dashed #cbd5e0;
        }

        .no-stories-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(50px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .hero-description {
                padding: 1.5rem;
                font-size: 1rem;
            }

            .section-title {
                font-size: 2.2rem;
            }

            .himada-grid {
                grid-template-columns: 1fr;
            }

            .modal-content {
                width: 95%;
                margin: 1rem;
            }

            .modal-header {
                padding: 1.5rem;
            }

            .modal-body {
                padding: 1.5rem;
            }

            .modal-himada-title {
                font-size: 1.5rem;
            }

            .story-image {
                height: 200px;
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
                        <li><a href="history.php" class="nav-link active">H!Story</a></li>
                        <li><a href="products.php" class="nav-link">Produk</a></li>
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
    <section class="hero-section">
        <div class="hero-content">
            <?php if ($is_specific_view && $hero_himada): ?>
                <a href="history.php" class="back-button">
                    ← Kembali ke Semua HIMADA
                </a>
                <h1 class="hero-title"><?php echo htmlspecialchars($hero_himada['nama']); ?></h1>
                <p class="hero-subtitle">
                    <?php echo htmlspecialchars($hero_himada['nama_lengkap']); ?>
                </p>
                <div class="hero-description">
                    <p><strong>📍 <?php echo htmlspecialchars($hero_himada['daerah_asal']); ?></strong></p>
                    <p><?php echo htmlspecialchars($hero_himada['deskripsi'] ?: 'HIMADA yang menghimpun mahasiswa dari ' . $hero_himada['daerah_asal'] . ' untuk mempererat persaudaraan dan melestarikan budaya daerah.'); ?></p>
                </div>
            <?php else: ?>
                <h1 class="hero-title">H!Story</h1>
                <p class="hero-subtitle">
                    Mengenal lebih dalam sejarah dan perjalanan Himpunan Mahasiswa Daerah (HIMADA) 
                    di Politeknik Statistika STIS
                </p>
                <div class="hero-description">
                    <p>
                        HIMADA (Himpunan Mahasiswa Daerah) merupakan organisasi kemahasiswaan yang menghimpun 
                        mahasiswa berdasarkan asal daerah. Di Politeknik Statistika STIS, terdapat 20 HIMADA 
                        yang mewakili berbagai daerah di Indonesia, mulai dari Sabang hingga Merauke.
                    </p>
                    <p>
                        Setiap HIMADA memiliki cerita unik, tradisi, dan kebudayaan yang memperkaya kehidupan 
                        kampus. Mereka tidak hanya menjadi wadah silaturahmi, tetapi juga pelestari budaya 
                        daerah dan jembatan persatuan dalam keberagaman.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- HIMADA Section -->
    <section class="history-content">
        <div class="container">
            <?php if ($is_specific_view): ?>
                <h2 class="section-title">🏛️ <?php echo htmlspecialchars($hero_himada['nama']); ?></h2>
                <p class="section-subtitle">
                    Cerita lengkap tentang <?php echo htmlspecialchars($hero_himada['nama']); ?>
                </p>
            <?php else: ?>
                <h2 class="section-title">🏛️ Keluarga Besar HIMADA</h2>
                <p class="section-subtitle">
                    Klik setiap HIMADA untuk mengenal lebih dekat cerita, budaya, dan keunikan mereka
                </p>
            <?php endif; ?>

            <div class="himada-grid">
                <?php 
                $colors = ['#4A90E2', '#50C878', '#FF6B6B', '#FFD93D', '#6C5CE7', '#FD79A8', '#00B894', '#E17055', '#74B9FF', '#A29BFE', '#FDCB6E', '#E84393', '#00CEC9', '#6C5CE7'];
                foreach ($himada_list as $index => $himada): 
                    $color = $himada['warna_tema'] ?: $colors[$index % count($colors)];
                    
                    // Generate initials
                    $words = explode(' ', $himada['nama']);
                    if (count($words) >= 2) {
                        $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                    } else {
                        $initials = strtoupper(substr($himada['nama'], 0, 2));
                    }

                    // Get first story for preview
                    $preview_text = $himada['deskripsi'] ?: 'HIMADA yang menghimpun mahasiswa dari ' . ($himada['daerah_asal'] ?: 'berbagai daerah') . ' untuk mempererat persaudaraan dan melestarikan budaya daerah.';
                    if (!empty($himada['story_list'])) {
                        $preview_text = $himada['story_list'][0]['cerita'];
                    }
                ?>
                    <div class="himada-card" 
                         style="--himada-color: <?= $color ?>"
                         onclick="openModal('<?= $himada['id'] ?>', '<?= $color ?>')">
                        <div class="himada-header">
                            <div class="himada-logo" style="background: <?= $color ?>">
                                <?= $initials ?>
                            </div>
                            <div class="himada-info">
                                <h3><?= htmlspecialchars($himada['nama']) ?></h3>
                                <div class="himada-region"><?= htmlspecialchars($himada['daerah_asal'] ?: 'Indonesia') ?></div>
                            </div>
                        </div>
                        
                        <p class="himada-description">
                            <?= htmlspecialchars(substr($preview_text, 0, 120)) ?>...
                        </p>
                        
                        <div class="click-hint" style="background: <?= $color ?>">
                            Klik untuk cerita lengkap →
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Modals for each HIMADA -->
    <?php foreach ($himada_list as $index => $himada): 
        $color = $himada['warna_tema'] ?: $colors[$index % count($colors)];
        $words = explode(' ', $himada['nama']);
        if (count($words) >= 2) {
            $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        } else {
            $initials = strtoupper(substr($himada['nama'], 0, 2));
        }
    ?>
        <div class="modal" id="modal-<?= $himada['id'] ?>">
            <div class="modal-content">
                <div class="modal-header" style="--himada-color: <?= $color ?>; background: <?= $color ?>">
                    <button class="close-btn" onclick="closeModal('<?= $himada['id'] ?>')">&times;</button>
                    <div class="modal-himada-info">
                        <div class="modal-himada-logo">
                            <?= $initials ?>
                        </div>
                        <div>
                            <h2 class="modal-himada-title"><?= htmlspecialchars($himada['nama']) ?></h2>
                            <p class="modal-himada-subtitle"><?= htmlspecialchars($himada['nama_lengkap'] ?: $himada['daerah_asal']) ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="modal-body">
                    <?php if (!empty($himada['story_list'])): ?>
                        <?php foreach ($himada['story_list'] as $story): ?>
                            <div class="story-section">
                                <h3 class="story-title">📖 <?= htmlspecialchars($story['judul']) ?></h3>
                                <div class="story-item" style="--himada-color: <?= $color ?>">
                                    <?php if (!empty($story['gambar_url'])): ?>
                                        <img src="<?= htmlspecialchars($story['gambar_url']) ?>" 
                                             alt="<?= htmlspecialchars($story['judul']) ?>"
                                             class="story-image"
                                             onerror="this.style.display='none'">
                                    <?php endif; ?>
                                    <p class="story-content"><?= nl2br(htmlspecialchars($story['cerita'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="story-section">
                            <h3 class="story-title">📖 Tentang <?= htmlspecialchars($himada['nama']) ?></h3>
                            <div class="story-item" style="--himada-color: <?= $color ?>">
                                <p class="story-content">
                                    <?= htmlspecialchars($himada['deskripsi'] ?: 'HIMADA yang menghimpun mahasiswa dari ' . ($himada['daerah_asal'] ?: 'berbagai daerah') . ' untuk mempererat persaudaraan dan melestarikan budaya daerah.') ?>
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
            <?php if (!empty($himada['story_list'])): ?>
                <?php foreach ($himada['story_list'] as $story): ?>
                    <?php if (!empty($story['fun_facts'])): ?>
                        <div class="story-section">
                            <h3 class="story-title">✨ Fun Facts: <?= htmlspecialchars($story['judul']) ?></h3>
                            <div class="fun-facts" style="--himada-color: <?= $color ?>">
                                <ul>
                                    <?php foreach ($story['fun_facts'] as $fact): ?>
                                        <li><?= htmlspecialchars($fact) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>

                    
                    <?php if (empty($himada['story_list'])): ?>
                        <div class="no-stories">
                            <div class="no-stories-icon">📚</div>
                            <h4>Cerita Sedang Dikumpulkan</h4>
                            <p>Admin HIMADA sedang menyiapkan cerita menarik untuk dibagikan. Pantau terus ya!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <div class="logo-icon">🛍️</div>
                        <span class="logo-text">H!MANJA</span>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3 class="footer-title">Menu</h3>
                    <ul class="footer-links">
                        <li><a href="index.php">Beranda</a></li>
                        <li><a href="#history">H!Story</a></li>
                        <li><a href="products.php">Produk</a></li>
                        <?php if ($is_logged_in): ?>
                            <li><a href="order.php">Pemesanan</a></li>
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
        // Modal functions
        function openModal(himadaId, color) {
            const modal = document.getElementById('modal-' + himadaId);
            if (modal) {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(himadaId) {
            const modal = document.getElementById('modal-' + himadaId);
            if (modal) {
                modal.classList.remove('show');
                document.body.style.overflow = 'auto';
            }
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal')) {
                e.target.classList.remove('show');
                document.body.style.overflow = 'auto';
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const openModal = document.querySelector('.modal.show');
                if (openModal) {
                    openModal.classList.remove('show');
                    document.body.style.overflow = 'auto';
                }
            }
        });

        // Auto-open modal if specific HIMADA is requested
        <?php if ($is_specific_view && !empty($himada_list)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                openModal('<?= $himada_list[0]['id'] ?>', '<?= $himada_list[0]['warna_tema'] ?: $colors[0] ?>');
            }, 500);
        });
        <?php endif; ?>

        // Animate cards on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe HIMADA cards
        document.querySelectorAll('.himada-card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = `all 0.6s ease ${index * 0.1}s`;
            observer.observe(card);
        });
    </script>
</body>
</html>
