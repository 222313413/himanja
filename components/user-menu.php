<?php
// User Menu Component - untuk ditaruh di header
if (!isLoggedIn()) return;

$user_name = $_SESSION['full_name'] ?? 'User';
$user_email = $_SESSION['email'] ?? '';
$user_role = $_SESSION['role'] ?? 'user';
$himada_name = $_SESSION['himada_nama'] ?? '';

$role_labels = [
    'super_admin' => 'Super Admin',
    'himada_admin' => 'Admin HIMADA',
    'user' => 'Mahasiswa'
];

$role_icons = [
    'super_admin' => '🔧',
    'himada_admin' => '👨‍💼',
    'user' => '👨‍🎓'
];
?>

<div class="user-menu">
    <div class="user-menu-trigger" onclick="toggleUserMenu()">
        <div class="user-avatar">
            <span class="avatar-icon"><?php echo $role_icons[$user_role] ?? '👤'; ?></span>
        </div>
        <div class="user-info">
            <span class="user-name"><?php echo htmlspecialchars($user_name); ?></span>
            <span class="user-role"><?php echo $role_labels[$user_role] ?? 'User'; ?></span>
        </div>
        <div class="dropdown-arrow">▼</div>
    </div>
    
    <div class="user-menu-dropdown" id="userMenuDropdown">
        <div class="dropdown-header">
            <div class="dropdown-user-info">
                <div class="dropdown-avatar">
                    <span class="avatar-icon"><?php echo $role_icons[$user_role] ?? '👤'; ?></span>
                </div>
                <div class="dropdown-user-details">
                    <p class="dropdown-user-name"><?php echo htmlspecialchars($user_name); ?></p>
                    <p class="dropdown-user-email"><?php echo htmlspecialchars($user_email); ?></p>
                    <?php if ($himada_name): ?>
                        <p class="dropdown-user-himada"><?php echo htmlspecialchars($himada_name); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="dropdown-divider"></div>
        
        <div class="dropdown-menu">
            <?php if ($user_role === 'super_admin'): ?>
                <a href="admin/dashboard.php" class="dropdown-item">
                    <span class="item-icon">🏠</span>
                    <span class="item-text">Dashboard Admin</span>
                </a>
            <?php elseif ($user_role === 'himada_admin'): ?>
                <a href="admin/himada-dashboard.php" class="dropdown-item">
                    <span class="item-icon">🏠</span>
                    <span class="item-text">Dashboard HIMADA</span>
                </a>
            <?php else: ?>
                <a href="dashboard.php" class="dropdown-item">
                    <span class="item-icon">🏠</span>
                    <span class="item-text">Dashboard</span>
                </a>
            <?php endif; ?>
            
            <a href="profile.php" class="dropdown-item">
                <span class="item-icon">👤</span>
                <span class="item-text">Profil Saya</span>
            </a>
            
            <?php if ($user_role !== 'super_admin'): ?>
                <a href="orders.php" class="dropdown-item">
                    <span class="item-icon">📦</span>
                    <span class="item-text">Pesanan Saya</span>
                </a>
            <?php endif; ?>
            
            <a href="settings.php" class="dropdown-item">
                <span class="item-icon">⚙️</span>
                <span class="item-text">Pengaturan</span>
            </a>
        </div>
        
        <div class="dropdown-divider"></div>
        
        <div class="dropdown-footer">
            <button class="dropdown-item logout-item" onclick="showLogoutModal()">
                <span class="item-icon">🚪</span>
                <span class="item-text">Logout</span>
                <span class="logout-badge">Alt+L</span>
            </button>
        </div>
    </div>
</div>

<style>
.user-menu {
    position: relative;
    display: inline-block;
}

.user-menu-trigger {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.user-menu-trigger:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-1px);
}

.user-avatar {
    width: 35px;
    height: 35px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.user-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.user-name {
    font-weight: 600;
    font-size: 0.9rem;
    color: white;
    line-height: 1.2;
}

.user-role {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.2;
}

.dropdown-arrow {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.8);
    transition: transform 0.3s ease;
}

.user-menu.active .dropdown-arrow {
    transform: rotate(180deg);
}

.user-menu-dropdown {
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    min-width: 280px;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px) scale(0.95);
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.user-menu.active .user-menu-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
}

.dropdown-header {
    padding: 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 16px 16px 0 0;
}

.dropdown-user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.dropdown-avatar {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
}

.dropdown-user-details {
    flex: 1;
}

.dropdown-user-name {
    font-weight: 600;
    font-size: 1rem;
    margin: 0 0 4px 0;
    color: #333;
}

.dropdown-user-email {
    font-size: 0.85rem;
    color: #666;
    margin: 0 0 2px 0;
}

.dropdown-user-himada {
    font-size: 0.75rem;
    color: #888;
    margin: 0;
    font-style: italic;
}

.dropdown-divider {
    height: 1px;
    background: #e9ecef;
    margin: 0;
}

.dropdown-menu {
    padding: 8px 0;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    background: none;
    width: 100%;
    cursor: pointer;
    font-size: 0.9rem;
}

.dropdown-item:hover {
    background: #f8f9fa;
    color: #667eea;
    transform: translateX(5px);
}

.item-icon {
    font-size: 1.1rem;
    width: 20px;
    text-align: center;
}

.item-text {
    flex: 1;
    font-weight: 500;
}

.dropdown-footer {
    padding: 8px 0;
    background: #f8f9fa;
    border-radius: 0 0 16px 16px;
}

.logout-item {
    color: #dc3545 !important;
    font-weight: 600;
    position: relative;
}

.logout-item:hover {
    background: #fff5f5 !important;
    color: #c82333 !important;
}

.logout-badge {
    font-size: 0.7rem;
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: 500;
}

@media (max-width: 768px) {
    .user-info {
        display: none;
    }
    
    .user-menu-dropdown {
        min-width: 250px;
        right: -10px;
    }
    
    .dropdown-item {
        padding: 15px 20px;
        font-size: 1rem;
    }
}
</style>

<script>
function toggleUserMenu() {
    const userMenu = document.querySelector('.user-menu');
    userMenu.classList.toggle('active');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const userMenu = document.querySelector('.user-menu');
    if (!userMenu.contains(event.target)) {
        userMenu.classList.remove('active');
    }
});

// Keyboard shortcut for logout (Alt + L)
document.addEventListener('keydown', function(event) {
    if (event.altKey && event.key === 'l') {
        event.preventDefault();
        showLogoutModal();
    }
});
</script>
