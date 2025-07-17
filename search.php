<?php
require_once 'config/database.php';
header('Content-Type: application/json');

// Enable CORS if needed
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

$database = new Database();
$db = $database->getConnection();

$query = isset($_GET['q']) ? sanitizeInput($_GET['q']) : '';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

if (empty($query) || strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

$results = [];

try {
    // Search in products
    $productQuery = "SELECT p.id, p.nama_produk as title, p.deskripsi as description, 
                            p.harga as price, p.gambar_url, h.nama as himada,
                            'product' as type,
                            CONCAT('products.php?id=', p.id) as url
                     FROM products p 
                     JOIN himada h ON p.himada_id = h.id 
                     WHERE p.is_available = 1 AND (
                         p.nama_produk LIKE :query OR 
                         p.deskripsi LIKE :query OR 
                         p.kategori LIKE :query
                     )
                     ORDER BY 
                         CASE 
                             WHEN p.nama_produk LIKE :exact_query THEN 1
                             WHEN p.nama_produk LIKE :start_query THEN 2
                             ELSE 3
                         END,
                         p.nama_produk
                     LIMIT :limit";
    
    $stmt = $db->prepare($productQuery);
    $searchTerm = "%{$query}%";
    $exactTerm = $query;
    $startTerm = "{$query}%";
    
    $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
    $stmt->bindParam(':exact_query', $exactTerm, PDO::PARAM_STR);
    $stmt->bindParam(':start_query', $startTerm, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results = array_merge($results, $products);
    
    // Search in HIMADA
    $himadaQuery = "SELECT id, nama as title, nama_lengkap as description, 
                           daerah_asal, 'himada' as type,
                           CONCAT('products.php?himada=', id) as url
                    FROM himada 
                    WHERE nama LIKE :query OR 
                          nama_lengkap LIKE :query OR 
                          daerah_asal LIKE :query
                    ORDER BY 
                        CASE 
                            WHEN nama LIKE :exact_query THEN 1
                            WHEN nama LIKE :start_query THEN 2
                            ELSE 3
                        END,
                        nama
                    LIMIT :limit";
    
    $stmt = $db->prepare($himadaQuery);
    $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
    $stmt->bindParam(':exact_query', $exactTerm, PDO::PARAM_STR);
    $stmt->bindParam(':start_query', $startTerm, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    $himadas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format HIMADA results
    foreach ($himadas as &$himada) {
        $himada['description'] = $himada['nama_lengkap'] . ' - ' . $himada['daerah_asal'];
        unset($himada['nama_lengkap'], $himada['daerah_asal']);
    }
    
    $results = array_merge($results, $himadas);
    
    // Search in HIMArt posts
    $himartQuery = "SELECT hp.id, hp.judul as title, hp.deskripsi as description,
                           h.nama as himada, 'himart' as type,
                           CONCAT('himart.php?id=', hp.id) as url
                    FROM himart_posts hp
                    JOIN himada h ON hp.himada_id = h.id
                    WHERE hp.is_active = 1 AND (
                        hp.judul LIKE :query OR 
                        hp.deskripsi LIKE :query
                    )
                    ORDER BY hp.created_at DESC
                    LIMIT :limit";
    
    $stmt = $db->prepare($himartQuery);
    $stmt->bindParam(':query', $searchTerm, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    $himart = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results = array_merge($results, $himart);
    
    // Search categories
    $categories = [
        'makanan' => 'Makanan Khas Daerah',
        'merchandise' => 'Merchandise HIMADA',
        'kaos' => 'Kaos dan Apparel',
        'gantungan_kunci' => 'Gantungan Kunci',
        'oleh_oleh' => 'Oleh-oleh Daerah'
    ];
    
    foreach ($categories as $key => $value) {
        if (stripos($key, $query) !== false || stripos($value, $query) !== false) {
            $results[] = [
                'id' => $key,
                'title' => $value,
                'description' => "Lihat semua produk kategori {$value}",
                'type' => 'category',
                'url' => "products.php?category={$key}"
            ];
        }
    }
    
    // Limit total results
    $results = array_slice($results, 0, $limit);
    
    // Log search query for analytics
    logSearchQuery($query, count($results));
    
    echo json_encode($results);
    
} catch (Exception $e) {
    error_log("Search error: " . $e->getMessage());
    echo json_encode(['error' => 'Search failed']);
}

function logSearchQuery($query, $resultCount) {
    // Simple search logging - you can enhance this
    $logFile = 'logs/search.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logEntry = date('Y-m-d H:i:s') . " - Query: '{$query}' - Results: {$resultCount}" . PHP_EOL;
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}
?>
