<?php
require_once 'config/database.php';
startSession();

$database = new Database();
$db = $database->getConnection();

$search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

$query = "SELECT p.*, h.nama AS himada_nama, h.warna_tema
          FROM products p
          JOIN himada h ON p.himada_id = h.id
          WHERE p.is_available = 1";

$params = [];

if ($search) {
    $query .= " AND (p.nama_produk LIKE :search OR p.deskripsi LIKE :search OR h.nama LIKE :search)";
    $params[':search'] = "%$search%";
}

$query .= " ORDER BY p.created_at DESC";

$stmt = $db->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($products)) {
    echo "<p>Tidak ada produk ditemukan.</p>";
    exit;
}

foreach ($products as $product): ?>
<div class="product-card">
    <div class="product-image">
        <?php if (!empty($product['gambar_url'])): ?>
            <img src="<?php echo htmlspecialchars($product['gambar_url']); ?>" alt="<?php echo htmlspecialchars($product['nama_produk']); ?>">
        <?php else: ?>
            <div style="background:#f8f9fa;height:200px;display:flex;align-items:center;justify-content:center;">📷 Tidak ada gambar</div>
        <?php endif; ?>

        <div class="product-badge" style="background:<?php echo htmlspecialchars($product['warna_tema']); ?>">
            <?php echo htmlspecialchars($product['himada_nama']); ?>
        </div>
    </div>

    <div class="product-content">
        <span class="product-category"><?php echo ucfirst($product['kategori']); ?></span>
        <h3 class="product-name"><?php echo htmlspecialchars($product['nama_produk']); ?></h3>
        <p class="product-description"><?php echo htmlspecialchars($product['deskripsi']); ?></p>

        <div class="product-footer">
            <div class="product-price">Rp <?php echo number_format($product['harga']); ?></div>
            <div class="product-stock">Stok: <?php echo $product['stok']; ?></div>
        </div>
    </div>
</div>
<?php endforeach; ?>
