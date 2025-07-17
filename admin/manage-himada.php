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
            case 'add':
                $nama = sanitizeInput($_POST['nama']);
                $nama_lengkap = sanitizeInput($_POST['nama_lengkap']);
                $daerah_asal = sanitizeInput($_POST['daerah_asal']);
                $warna_tema = sanitizeInput($_POST['warna_tema']);
                $deskripsi = sanitizeInput($_POST['deskripsi']);
                $koordinat_lat = !empty($_POST['koordinat_lat']) ? (float)$_POST['koordinat_lat'] : null;
                $koordinat_lng = !empty($_POST['koordinat_lng']) ? (float)$_POST['koordinat_lng'] : null;
                $contact_email = sanitizeInput($_POST['contact_email']);
                $contact_phone = sanitizeInput($_POST['contact_phone']);
                
                try {
                    $query = "INSERT INTO himada (nama, nama_lengkap, daerah_asal, warna_tema, deskripsi, koordinat_lat, koordinat_lng, contact_email, contact_phone) 
                             VALUES (:nama, :nama_lengkap, :daerah_asal, :warna_tema, :deskripsi, :koordinat_lat, :koordinat_lng, :contact_email, :contact_phone)";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':nama', $nama);
                    $stmt->bindParam(':nama_lengkap', $nama_lengkap);
                    $stmt->bindParam(':daerah_asal', $daerah_asal);
                    $stmt->bindParam(':warna_tema', $warna_tema);
                    $stmt->bindParam(':deskripsi', $deskripsi);
                    $stmt->bindParam(':koordinat_lat', $koordinat_lat);
                    $stmt->bindParam(':koordinat_lng', $koordinat_lng);
                    $stmt->bindParam(':contact_email', $contact_email);
                    $stmt->bindParam(':contact_phone', $contact_phone);
                    $stmt->execute();
                    
                    logActivity(getUserId(), 'himada_add', "Added HIMADA: $nama");
                    $message = 'HIMADA berhasil ditambahkan!';
                } catch (Exception $e) {
                    $error = 'Gagal menambahkan HIMADA: ' . $e->getMessage();
                }
                break;
                
            case 'edit':
                $himada_id = (int)$_POST['himada_id'];
                $nama = sanitizeInput($_POST['nama']);
                $nama_lengkap = sanitizeInput($_POST['nama_lengkap']);
                $daerah_asal = sanitizeInput($_POST['daerah_asal']);
                $warna_tema = sanitizeInput($_POST['warna_tema']);
                $deskripsi = sanitizeInput($_POST['deskripsi']);
                $koordinat_lat = !empty($_POST['koordinat_lat']) ? (float)$_POST['koordinat_lat'] : null;
                $koordinat_lng = !empty($_POST['koordinat_lng']) ? (float)$_POST['koordinat_lng'] : null;
                $contact_email = sanitizeInput($_POST['contact_email']);
                $contact_phone = sanitizeInput($_POST['contact_phone']);
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                
                try {
                    $query = "UPDATE himada SET nama = :nama, nama_lengkap = :nama_lengkap, daerah_asal = :daerah_asal, 
                             warna_tema = :warna_tema, deskripsi = :deskripsi, koordinat_lat = :koordinat_lat, 
                             koordinat_lng = :koordinat_lng, contact_email = :contact_email, contact_phone = :contact_phone, 
                             is_active = :is_active WHERE id = :himada_id";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':nama', $nama);
                    $stmt->bindParam(':nama_lengkap', $nama_lengkap);
                    $stmt->bindParam(':daerah_asal', $daerah_asal);
                    $stmt->bindParam(':warna_tema', $warna_tema);
                    $stmt->bindParam(':deskripsi', $deskripsi);
                    $stmt->bindParam(':koordinat_lat', $koordinat_lat);
                    $stmt->bindParam(':koordinat_lng', $koordinat_lng);
                    $stmt->bindParam(':contact_email', $contact_email);
                    $stmt->bindParam(':contact_phone', $contact_phone);
                    $stmt->bindParam(':is_active', $is_active);
                    $stmt->bindParam(':himada_id', $himada_id);
                    $stmt->execute();
                    
                    logActivity(getUserId(), 'himada_edit', "Updated HIMADA: $nama");
                    $message = 'HIMADA berhasil diperbarui!';
                } catch (Exception $e) {
                    $error = 'Gagal memperbarui HIMADA: ' . $e->getMessage();
                }
                break;
                
            case 'delete':
                $himada_id = (int)$_POST['himada_id'];
                
                try {
                    // Get HIMADA name for logging
                    $query = "SELECT nama FROM himada WHERE id = :himada_id";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':himada_id', $himada_id);
                    $stmt->execute();
                    $himada = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($himada) {
                        // Check if HIMADA has products or users
                        $query = "SELECT COUNT(*) as product_count FROM products WHERE himada_id = :himada_id";
                        $stmt = $db->prepare($query);
                        $stmt->bindParam(':himada_id', $himada_id);
                        $stmt->execute();
                        $product_count = $stmt->fetch(PDO::FETCH_ASSOC)['product_count'];
                        
                        $query = "SELECT COUNT(*) as user_count FROM users WHERE himada_id = :himada_id";
                        $stmt = $db->prepare($query);
                        $stmt->bindParam(':himada_id', $himada_id);
                        $stmt->execute();
                        $user_count = $stmt->fetch(PDO::FETCH_ASSOC)['user_count'];
                        
                        if ($product_count > 0 || $user_count > 0) {
                            $error = 'Tidak dapat menghapus HIMADA yang masih memiliki produk atau user terkait.';
                        } else {
                            $query = "DELETE FROM himada WHERE id = :himada_id";
                            $stmt = $db->prepare($query);
                            $stmt->bindParam(':himada_id', $himada_id);
                            $stmt->execute();
                            
                            logActivity(getUserId(), 'himada_delete', "Deleted HIMADA: " . $himada['nama']);
                            $message = 'HIMADA berhasil dihapus!';
                        }
                    }
                } catch (Exception $e) {
                    $error = 'Gagal menghapus HIMADA: ' . $e->getMessage();
                }
                break;
        }
    }
}

// Get all HIMADA with statistics
$query = "SELECT h.*, 
                 COUNT(DISTINCT p.id) as product_count,
                 COUNT(DISTINCT u.id) as user_count,
                 COUNT(DISTINCT ua.id) as admin_count
          FROM himada h
          LEFT JOIN products p ON h.id = p.himada_id AND p.is_available = 1
          LEFT JOIN users u ON h.id = u.himada_id AND u.role = 'user'
          LEFT JOIN users ua ON h.id = ua.himada_id AND ua.role = 'himada_admin'
          GROUP BY h.id
          ORDER BY h.nama";
$stmt = $db->prepare($query);
$stmt->execute();
$himadas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get edit HIMADA if requested
$edit_himada = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $query = "SELECT * FROM himada WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $edit_id);
    $stmt->execute();
    $edit_himada = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola HIMADA - Super Admin H!MANJA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin-navbar.css">
    <style>
        /* Styles khusus untuk halaman manage-himada */
        .himada-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .himada-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            border-top: 4px solid var(--himada-color);
        }
        
        .himada-card:hover {
            transform: translateY(-5px);
        }
        
        .himada-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .himada-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            background: var(--himada-color);
        }
        
        .himada-info h3 {
            margin: 0 0 0.25rem 0;
            font-size: 1.2rem;
            color: #333;
        }
        
        .himada-info p {
            margin: 0;
            font-size: 0.9rem;
            color: #666;
        }
        
        .himada-status {
            margin-left: auto;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        
        .himada-description {
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: #666;
            line-height: 1.4;
        }
        
        .himada-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .stat-item {
            text-align: center;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .stat-number {
            font-size: 1.2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            font-size: 0.8rem;
            color: #666;
        }
        
        .himada-contact {
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.25rem;
            color: #666;
        }
        
        .himada-actions {
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
        
        .btn-view {
            background: #28a745;
            color: white;
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
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
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
        
        .color-preview {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            border: 1px solid #ddd;
            margin-left: 0.5rem;
        }
        
        .coordinate-group {
            display: flex;
            gap: 0.5rem;
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
        
        @media (max-width: 768px) {
            .himada-grid {
                grid-template-columns: 1fr;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .modal-content {
                margin: 1rem;
                width: calc(100% - 2rem);
            }
            
            .himada-stats {
                grid-template-columns: 1fr;
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
                <li class="nav-item active">
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
                <button class="sidebar-toggle" id="sidebarToggle">☰</button>
                <h1 class="page-title">Kelola HIMADA</h1>
            </div>
            <div class="header-right">
                <button class="btn-primary" onclick="openAddModal()">
                    <span>➕</span> Tambah HIMADA
                </button>
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
            
            <!-- HIMADA Grid -->
            <div class="himada-grid">
                <?php foreach ($himadas as $himada): ?>
                    <div class="himada-card" style="--himada-color: <?php echo $himada['warna_tema']; ?>">
                        <div class="himada-header">
                            <div class="himada-icon" style="background: <?php echo $himada['warna_tema']; ?>">
                                🏛️
                            </div>
                            <div class="himada-info">
                                <h3><?php echo htmlspecialchars($himada['nama']); ?></h3>
                                <p><?php echo htmlspecialchars($himada['daerah_asal']); ?></p>
                            </div>
                            <div class="himada-status <?php echo $himada['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                <?php echo $himada['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                            </div>
                        </div>
                        
                        <div class="himada-description">
                            <?php echo htmlspecialchars($himada['nama_lengkap']); ?>
                        </div>
                        
                        <div class="himada-stats">
                            <div class="stat-item">
                                <div class="stat-number"><?php echo $himada['product_count']; ?></div>
                                <div class="stat-label">Produk</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number"><?php echo $himada['admin_count']; ?></div>
                                <div class="stat-label">Admin</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number"><?php echo $himada['user_count']; ?></div>
                                <div class="stat-label">Mahasiswa</div>
                            </div>
                        </div>
                        
                        <?php if ($himada['contact_email'] || $himada['contact_phone']): ?>
                            <div class="himada-contact">
                                <?php if ($himada['contact_email']): ?>
                                    <div class="contact-item">
                                        <span>📧</span>
                                        <span><?php echo htmlspecialchars($himada['contact_email']); ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($himada['contact_phone']): ?>
                                    <div class="contact-item">
                                        <span>📱</span>
                                        <span><?php echo htmlspecialchars($himada['contact_phone']); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="himada-actions">
                            <button class="btn-small btn-view" onclick="viewHimada(<?php echo $himada['id']; ?>)">
                                👁️ Lihat
                            </button>
                            <button class="btn-small btn-edit" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($himada)); ?>)">
                                ✏️ Edit
                            </button>
                            <button class="btn-small btn-delete" onclick="confirmDelete(<?php echo $himada['id']; ?>, '<?php echo htmlspecialchars($himada['nama']); ?>')">
                                🗑️ Hapus
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
    
    <!-- Add/Edit HIMADA Modal -->
    <div id="himadaModal" class="modal">
        <div class="modal-content">
            <h2 id="modalTitle">Tambah HIMADA</h2>
            <form id="himadaForm" method="POST">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="himada_id" id="himadaId">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nama">Nama Singkat *</label>
                        <input type="text" name="nama" id="nama" required placeholder="Contoh: GIST">
                    </div>
                    
                    <div class="form-group">
                        <label for="warna_tema">Warna Tema *</label>
                        <div style="display: flex; align-items: center;">
                            <input type="color" name="warna_tema" id="warna_tema" value="#b2e7e8" required>
                            <div class="color-preview" id="colorPreview"></div>
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="nama_lengkap">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" required placeholder="Contoh: Gam Inong Statistik">
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="daerah_asal">Daerah Asal *</label>
                        <input type="text" name="daerah_asal" id="daerah_asal" required placeholder="Contoh: Aceh">
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" placeholder="Deskripsi singkat tentang HIMADA..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="koordinat_lat">Latitude</label>
                        <input type="number" name="koordinat_lat" id="koordinat_lat" step="0.000001" placeholder="Contoh: -6.2088">
                    </div>
                    
                    <div class="form-group">
                        <label for="koordinat_lng">Longitude</label>
                        <input type="number" name="koordinat_lng" id="koordinat_lng" step="0.000001" placeholder="Contoh: 106.8456">
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_email">Email Kontak</label>
                        <input type="email" name="contact_email" id="contact_email" placeholder="himada@stis.ac.id">
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_phone">Nomor Telepon</label>
                        <input type="tel" name="contact_phone" id="contact_phone" placeholder="081234567890">
                    </div>
                    
                    <div class="form-group full-width" id="activeGroup" style="display: none;">
                        <label>
                            <input type="checkbox" name="is_active" id="is_active" checked>
                            HIMADA aktif
                        </label>
                    </div>
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
            <p>Apakah Anda yakin ingin menghapus HIMADA "<span id="deleteHimadaName"></span>"?</p>
            <p style="color: #dc3545; font-size: 0.9rem;">Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait.</p>
            
            <form id="deleteForm" method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="himada_id" id="deleteHimadaId">
                
                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Batal</button>
                    <button type="submit" class="btn-delete">Hapus</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Sidebar toggle functionality
        const sidebarToggle = document.getElementById('sidebarToggle');
        const adminSidebar = document.getElementById('adminSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        sidebarToggle.addEventListener('click', function() {
            adminSidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });
        
        sidebarOverlay.addEventListener('click', function() {
            adminSidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });
        
        // Modal functions
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Tambah HIMADA';
            document.getElementById('formAction').value = 'add';
            document.getElementById('himadaForm').reset();
            document.getElementById('warna_tema').value = '#b2e7e8';
            updateColorPreview();
            document.getElementById('activeGroup').style.display = 'none';
            document.getElementById('himadaModal').classList.add('active');
        }
        
        function openEditModal(himada) {
            document.getElementById('modalTitle').textContent = 'Edit HIMADA';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('himadaId').value = himada.id;
            document.getElementById('nama').value = himada.nama;
            document.getElementById('nama_lengkap').value = himada.nama_lengkap;
            document.getElementById('daerah_asal').value = himada.daerah_asal;
            document.getElementById('warna_tema').value = himada.warna_tema;
            document.getElementById('deskripsi').value = himada.deskripsi || '';
            document.getElementById('koordinat_lat').value = himada.koordinat_lat || '';
            document.getElementById('koordinat_lng').value = himada.koordinat_lng || '';
            document.getElementById('contact_email').value = himada.contact_email || '';
            document.getElementById('contact_phone').value = himada.contact_phone || '';
            document.getElementById('is_active').checked = himada.is_active == 1;
            document.getElementById('activeGroup').style.display = 'block';
            updateColorPreview();
            document.getElementById('himadaModal').classList.add('active');
        }
        
        function closeModal() {
            document.getElementById('himadaModal').classList.remove('active');
        }
        
        function confirmDelete(himadaId, himadaName) {
            document.getElementById('deleteHimadaId').value = himadaId;
            document.getElementById('deleteHimadaName').textContent = himadaName;
            document.getElementById('deleteModal').classList.add('active');
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }
        
        function viewHimada(himadaId) {
            window.open('../products.php?himada=' + himadaId, '_blank');
        }
        
        function updateColorPreview() {
            const color = document.getElementById('warna_tema').value;
            document.getElementById('colorPreview').style.backgroundColor = color;
        }
        
        // Event listeners
        document.getElementById('warna_tema').addEventListener('change', updateColorPreview);
        
        // Close modal when clicking outside
        document.getElementById('himadaModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
        
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
        
        // Initialize color preview
        updateColorPreview();
        
        // Auto-open edit modal if edit parameter exists
        <?php if ($edit_himada): ?>
            openEditModal(<?php echo json_encode($edit_himada); ?>);
        <?php endif; ?>
    </script>
</body>
</html>
