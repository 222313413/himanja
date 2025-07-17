<?php
require_once 'config/database.php';
startSession();

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'Permintaan tidak valid.']);
    exit;
}

$product_id = (int)$_POST['product_id'];

$database = new Database();
$db = $database->getConnection();

// cek produk
$stmt = $db->prepare("SELECT * FROM products WHERE id = :id AND is_available = 1");
$stmt->execute([':id' => $product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan.']);
    exit;
}

if ($product['stok'] <= 0) {
    echo json_encode(['success' => false, 'message' => 'Stok produk habis.']);
    exit;
}

// mulai transaksi biar aman
$db->beginTransaction();

try {
    // buat order utama
    $order_stmt = $db->prepare("
        INSERT INTO orders (user_id, order_number, total_amount, status, payment_status, created_at)
        VALUES (:user_id, :order_number, :total_amount, 'pending', 'pending', NOW())
    ");
    $order_number = uniqid('ORD');
    $order_stmt->execute([
        ':user_id' => $_SESSION['user_id'],
        ':order_number' => $order_number,
        ':total_amount' => $product['harga'], // hanya 1 produk
    ]);

    $order_id = $db->lastInsertId();

    // masukkan ke order_items
    $item_stmt = $db->prepare("
        INSERT INTO order_items 
            (order_id, himada_id, product_id, quantity, price, status, created_at)
        VALUES 
            (:order_id, :himada_id, :product_id, 1, :price, 'pending', NOW())
    ");
    $item_stmt->execute([
        ':order_id' => $order_id,
        ':himada_id' => $product['himada_id'],
        ':product_id' => $product_id,
        ':price' => $product['harga']
    ]);

    // kurangi stok produk
    $update_stok = $db->prepare("UPDATE products SET stok = stok - 1 WHERE id = :id");
    $update_stok->execute([':id' => $product_id]);

    // commit transaksi
    $db->commit();

    echo json_encode(['success' => true, 'message' => 'Pesanan berhasil dibuat.']);

} catch (Exception $e) {
    $db->rollBack();
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan saat memproses pesanan.']);
}
