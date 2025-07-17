<?php
// Script untuk fix database issues
require_once 'config/database.php';

echo "<h2>🔧 Database Fix Tool</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        switch ($action) {
            case 'reset_database':
                // Read and execute reset script
                $sql = file_get_contents('scripts/reset-database.sql');
                $statements = explode(';', $sql);
                
                foreach ($statements as $statement) {
                    $statement = trim($statement);
                    if (!empty($statement)) {
                        $db->exec($statement);
                    }
                }
                
                echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
                echo "✅ Database reset successfully!";
                echo "</div>";
                break;
                
            case 'fix_users':
                // Fix demo users
                $demo_users = [
                    ['username' => 'admin', 'email' => 'admin@stis.ac.id', 'password' => 'password', 'full_name' => 'Super Administrator', 'role' => 'super_admin'],
                    ['username' => 'admin_gist', 'email' => 'admin.gist@stis.ac.id', 'password' => 'password', 'full_name' => 'Admin GIST', 'role' => 'himada_admin'],
                    ['username' => 'mahasiswa', 'email' => 'mahasiswa@stis.ac.id', 'password' => 'password', 'full_name' => 'Mahasiswa Test', 'role' => 'user']
                ];
                
                foreach ($demo_users as $user_data) {
                    $hashed_password = password_hash($user_data['password'], PASSWORD_DEFAULT);
                    
                    // Check if user exists
                    $check_query = "SELECT id FROM users WHERE email = :email";
                    $check_stmt = $db->prepare($check_query);
                    $check_stmt->bindParam(':email', $user_data['email']);
                    $check_stmt->execute();
                    
                    if ($check_stmt->rowCount() > 0) {
                        // Update existing user
                        $update_query = "UPDATE users SET password = :password, is_active = 1, email_verified = 1 WHERE email = :email";
                        $update_stmt = $db->prepare($update_query);
                        $update_stmt->bindParam(':password', $hashed_password);
                        $update_stmt->bindParam(':email', $user_data['email']);
                        $update_stmt->execute();
                    } else {
                        // Create new user
                        $insert_query = "INSERT INTO users (username, email, password, full_name, role, is_active, email_verified) VALUES (:username, :email, :password, :full_name, :role, 1, 1)";
                        $insert_stmt = $db->prepare($insert_query);
                        $insert_stmt->execute([
                            ':username' => $user_data['username'],
                            ':email' => $user_data['email'],
                            ':password' => $hashed_password,
                            ':full_name' => $user_data['full_name'],
                            ':role' => $user_data['role']
                        ]);
                    }
                }
                
                echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
                echo "✅ Demo users fixed successfully!";
                echo "</div>";
                break;
        }
        
    } catch (Exception $e) {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "❌ Error: " . $e->getMessage();
        echo "</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Database Fix Tool</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .action-card { background: #f8f9fa; padding: 20px; margin: 10px 0; border-radius: 8px; border: 1px solid #dee2e6; }
        .btn { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; margin: 5px; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
    </style>
</head>
<body>
    <div class="action-card">
        <h3>🔄 Reset Entire Database</h3>
        <p>This will completely reset the database and recreate all tables with fresh data.</p>
        <form method="POST" onsubmit="return confirm('Are you sure? This will delete all existing data!')">
            <input type="hidden" name="action" value="reset_database">
            <button type="submit" class="btn btn-danger">Reset Database</button>
        </form>
    </div>
    
    <div class="action-card">
        <h3>👥 Fix Demo Users</h3>
        <p>This will create or update demo user accounts with correct passwords.</p>
        <form method="POST">
            <input type="hidden" name="action" value="fix_users">
            <button type="submit" class="btn">Fix Demo Users</button>
        </form>
    </div>
    
    <div class="action-card">
        <h3>🔗 Quick Links</h3>
        <a href="check-database.php" class="btn">Check Database Status</a>
        <a href="login.php" class="btn">Go to Login</a>
    </div>
</body>
</html>
