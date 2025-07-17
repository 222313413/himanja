<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="logout-modal" style="display: none;">
    <div class="logout-modal-overlay" onclick="closeLogoutModal()"></div>
    <div class="logout-modal-content">
        <div class="logout-modal-header">
            <h3 class="logout-modal-title">
                <span class="logout-icon">👋</span>
                Konfirmasi Logout
            </h3>
            <button class="logout-modal-close" onclick="closeLogoutModal()">&times;</button>
        </div>
        
        <div class="logout-modal-body">
            <div class="logout-user-info">
                <div class="logout-avatar">
                    <span class="avatar-icon">👤</span>
                </div>
                <div class="logout-user-details">
                    <p class="logout-user-name"><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'User'); ?></p>
                    <p class="logout-user-email"><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></p>
                    <p class="logout-user-role">
                        <?php 
                        $role_labels = [
                            'super_admin' => '🔧 Super Admin',
                            'himada_admin' => '👨‍💼 Admin HIMADA',
                            'user' => '👨‍🎓 Mahasiswa'
                        ];
                        echo $role_labels[$_SESSION['role'] ?? 'user'] ?? '👤 User';
                        ?>
                    </p>
                </div>
            </div>
            
            <div class="logout-message">
                <p>Apakah Anda yakin ingin keluar dari akun ini?</p>
                <p class="logout-note">Anda akan diarahkan ke halaman login.</p>
            </div>
        </div>
        
        <div class="logout-modal-footer">
            <button class="btn-cancel" onclick="closeLogoutModal()">
                <span class="btn-icon">❌</span>
                <span class="btn-text">Batal</span>
            </button>
            <button class="btn-logout" onclick="confirmLogout()">
                <span class="btn-icon">🚪</span>
                <span class="btn-text">Ya, Logout</span>
            </button>
        </div>
    </div>
</div>

<style>
.logout-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease-out;
}

.logout-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
}

.logout-modal-content {
    position: relative;
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    max-width: 400px;
    width: 90%;
    overflow: hidden;
    animation: slideUp 0.3s ease-out;
}

.logout-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 25px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.logout-modal-title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    font-size: 1.2rem;
    font-weight: 600;
}

.logout-icon {
    font-size: 1.5rem;
}

.logout-modal-close {
    background: none;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: background-color 0.3s ease;
}

.logout-modal-close:hover {
    background: rgba(255, 255, 255, 0.2);
}

.logout-modal-body {
    padding: 25px;
}

.logout-user-info {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 12px;
}

.logout-avatar {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.logout-user-details {
    flex: 1;
}

.logout-user-name {
    font-weight: 600;
    font-size: 1.1rem;
    margin: 0 0 5px 0;
    color: #333;
}

.logout-user-email {
    font-size: 0.9rem;
    color: #666;
    margin: 0 0 5px 0;
}

.logout-user-role {
    font-size: 0.8rem;
    color: #888;
    margin: 0;
}

.logout-message {
    text-align: center;
    margin-bottom: 20px;
}

.logout-message p {
    margin: 0 0 10px 0;
    color: #333;
}

.logout-note {
    font-size: 0.9rem;
    color: #666;
}

.logout-modal-footer {
    display: flex;
    gap: 10px;
    padding: 0 25px 25px 25px;
}

.btn-cancel, .btn-logout {
    flex: 1;
    padding: 12px 20px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.btn-cancel {
    background: #f8f9fa;
    color: #6c757d;
    border: 2px solid #e9ecef;
}

.btn-cancel:hover {
    background: #e9ecef;
    color: #495057;
}

.btn-logout {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

.btn-logout:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
}

.btn-icon {
    font-size: 1rem;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { 
        opacity: 0;
        transform: translateY(30px) scale(0.9);
    }
    to { 
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@media (max-width: 480px) {
    .logout-modal-content {
        margin: 20px;
        width: calc(100% - 40px);
    }
    
    .logout-modal-footer {
        flex-direction: column;
    }
    
    .logout-user-info {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<script>
function showLogoutModal() {
    document.getElementById('logoutModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeLogoutModal() {
    document.getElementById('logoutModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function confirmLogout() {
    // Show loading state
    const logoutBtn = document.querySelector('.btn-logout');
    const originalContent = logoutBtn.innerHTML;
    logoutBtn.innerHTML = '<span class="loading-spinner"></span> Logging out...';
    logoutBtn.disabled = true;
    
    // Add loading spinner styles
    const style = document.createElement('style');
    style.textContent = `
        .loading-spinner {
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            display: inline-block;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
    
    // Redirect to logout after short delay
    setTimeout(() => {
        window.location.href = 'logout.php';
    }, 1000);
}

// Close modal when pressing Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeLogoutModal();
    }
});
</script>
