<?php
require_once __DIR__ . '/../config/database.php';
startSession();

$db = (new Database())->getConnection();

$himada_id = getUserHimadaId();
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';

$query = "SELECT * FROM products WHERE himada_id = :himada_id";
$params = [':himada_id' => $himada_id];

if ($search) {
    $query .= " AND (nama_produk LIKE :search OR deskripsi LIKE :search)";
    $params[':search'] = "%$search%";
}

$query .= " ORDER BY created_at DESC";

$stmt = $db->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($products);
exit;
?>
