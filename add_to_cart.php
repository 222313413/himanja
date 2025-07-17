<?php
require_once 'config/database.php';
startSession();

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Silakan login dulu.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'Permintaan tidak valid.']);
    exit;
}

$product_id = (int)$_POST['product_id'];

$database = new Database();
$db = $database->getConnection();

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

// siapkan cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// cek apakah sudah ada di cart
if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] += 1;
} else {
    $_SESSION['cart'][$product_id] = [
        'id' => $product['id'],
        'nama' => $product['nama_produk'],
        'harga' => $product['harga'],
        'himada_id' => $product['himada_id'],
        'quantity' => 1
    ];
}

echo json_encode(['success' => true, 'message' => 'Produk ditambahkan ke keranjang.']);
