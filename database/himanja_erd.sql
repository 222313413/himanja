-- H!MANJA Entity Relationship Diagram (ERD)
-- Database: himanja_db

/*
===========================================
ENTITY RELATIONSHIP DIAGRAM (ERD) - H!MANJA
===========================================

ENTITIES:
1. users (Pengguna sistem)
2. himada (Himpunan Mahasiswa Daerah)
3. products (Produk yang dijual)
4. orders (Pesanan)
5. order_items (Detail item pesanan)
6. himart_posts (Postingan HIMArt)
7. stock_history (Riwayat perubahan stok)
8. notifications (Notifikasi)

RELATIONSHIPS:
1. users (1) -> (0..n) himada [himada_admin relationship]
   - Satu HIMADA bisa memiliki satu admin
   - Satu admin hanya mengelola satu HIMADA

2. himada (1) -> (0..n) products
   - Satu HIMADA bisa memiliki banyak produk
   - Satu produk hanya milik satu HIMADA

3. users (1) -> (0..n) products [created_by relationship]
   - Satu user (admin) bisa membuat banyak produk
   - Satu produk dibuat oleh satu user

4. users (1) -> (0..n) orders
   - Satu user bisa memiliki banyak pesanan
   - Satu pesanan milik satu user

5. orders (1) -> (1..n) order_items
   - Satu pesanan bisa memiliki banyak item
   - Satu item pesanan milik satu pesanan

6. products (1) -> (0..n) order_items
   - Satu produk bisa dipesan berkali-kali
   - Satu item pesanan merujuk ke satu produk

7. himada (1) -> (0..n) order_items
   - Satu HIMADA bisa memiliki banyak item pesanan
   - Satu item pesanan milik satu HIMADA

8. himada (1) -> (0..n) himart_posts
   - Satu HIMADA bisa memiliki banyak postingan HIMArt
   - Satu postingan HIMArt milik satu HIMADA

9. users (1) -> (0..n) himart_posts [created_by relationship]
   - Satu user bisa membuat banyak postingan
   - Satu postingan dibuat oleh satu user

10. products (1) -> (0..n) stock_history
    - Satu produk bisa memiliki banyak riwayat stok
    - Satu riwayat stok milik satu produk

11. users (1) -> (0..n) stock_history [created_by relationship]
    - Satu user bisa membuat banyak riwayat stok
    - Satu riwayat stok dibuat oleh satu user

12. users (1) -> (0..n) notifications
    - Satu user bisa menerima banyak notifikasi
    - Satu notifikasi ditujukan ke satu user

13. himada (1) -> (0..n) notifications
    - Satu HIMADA bisa memiliki banyak notifikasi
    - Satu notifikasi bisa terkait dengan satu HIMADA

BUSINESS RULES:
1. Hanya email @stis.ac.id yang bisa mendaftar
2. Setiap HIMADA hanya memiliki satu admin
3. Admin HIMADA hanya bisa mengelola produk HIMADA mereka sendiri
4. Admin HIMADA hanya bisa melihat pesanan untuk produk HIMADA mereka
5. Stok produk otomatis berkurang ketika pesanan dikonfirmasi
6. Notifikasi otomatis dikirim ketika ada pesanan baru atau stok menipis
7. Super admin bisa mengakses semua data
8. User biasa hanya bisa berbelanja, tidak bisa mengelola produk

INDEXES:
- idx_users_email: untuk pencarian berdasarkan email
- idx_users_role: untuk filter berdasarkan role
- idx_users_himada: untuk relasi user-himada
- idx_products_himada: untuk filter produk per HIMADA
- idx_products_available: untuk filter produk yang tersedia
- idx_orders_user: untuk riwayat pesanan user
- idx_orders_status: untuk filter status pesanan
- idx_order_items_order: untuk detail pesanan
- idx_order_items_himada: untuk pesanan per HIMADA
- idx_order_items_status: untuk status item pesanan
- idx_notifications_user: untuk notifikasi user
- idx_notifications_read: untuk filter notifikasi belum dibaca
- idx_stock_history_product: untuk riwayat stok produk

TRIGGERS:
1. update_stock_on_order_complete: Update stok otomatis saat pesanan selesai
2. check_low_stock: Notifikasi otomatis saat stok menipis
3. notify_new_order: Notifikasi otomatis saat ada pesanan baru

VIEWS:
1. v_himada_sales: Ringkasan penjualan per HIMADA
2. v_product_sales: Ringkasan penjualan per produk dengan status stok
*/

-- Contoh query untuk melihat struktur relasi:

-- 1. Melihat admin per HIMADA
SELECT h.nama as himada, u.full_name as admin_name, u.email
FROM himada h
LEFT JOIN users u ON h.id = u.himada_id AND u.role = 'himada_admin'
ORDER BY h.nama;

-- 2. Melihat produk per HIMADA dengan info pembuat
SELECT h.nama as himada, p.nama_produk, p.harga, p.stok, u.full_name as created_by
FROM products p
JOIN himada h ON p.himada_id = h.id
JOIN users u ON p.created_by = u.id
ORDER BY h.nama, p.nama_produk;

-- 3. Melihat pesanan dengan detail HIMADA
SELECT o.order_number, u.full_name as customer, oi.quantity, p.nama_produk, h.nama as himada
FROM orders o
JOIN users u ON o.user_id = u.id
JOIN order_items oi ON o.id = oi.order_id
JOIN products p ON oi.product_id = p.id
JOIN himada h ON oi.himada_id = h.id
ORDER BY o.created_at DESC;

-- 4. Melihat notifikasi per admin HIMADA
SELECT u.full_name as admin, h.nama as himada, n.title, n.message, n.created_at
FROM notifications n
JOIN users u ON n.user_id = u.id
LEFT JOIN himada h ON n.himada_id = h.id
WHERE u.role = 'himada_admin'
ORDER BY n.created_at DESC;
