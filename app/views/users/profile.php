<?php
$activePage = 'profile';
?>

<div class="profile-container">
    <div class="profile-header">
        <div class="header-left">
            <a href="/dashboard" class="back-btn-modern">
                <i class="fas fa-arrow-left"></i>
                <span><?php echo __('dashboard'); ?></span>
            </a>
            <h1>
                <span class="title-icon">
                    <i class="fas fa-user-astronaut"></i>
                </span>
                <?php echo __('profile'); ?>
            </h1>
            <p class="subtitle"><?php echo __('view_edit_profile'); ?></p>
        </div>
        <div class="profile-stats-cards">
            <div class="stat-card-mini">
                <i class="fas fa-book-reader"></i>
                <div class="stat-info">
                    <span class="stat-label"><?php echo __('total_loans'); ?></span>
                    <strong><?php echo $total_loans; ?></strong>
                </div>
            </div>
            <div class="stat-card-mini">
                <i class="fas fa-hand-holding"></i>
                <div class="stat-info">
                    <span class="stat-label"><?php echo __('active_loans'); ?></span>
                    <strong><?php echo $active_loans; ?></strong>
                </div>
            </div>
            <div class="stat-card-mini">
                <i class="fas fa-calendar-alt"></i>
                <div class="stat-info">
                    <span class="stat-label"><?php echo __('member_since'); ?></span>
                    <strong><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></strong>
                </div>
            </div>
        </div>
    </div>

    <div class="profile-content">
        <div class="profile-left">
            <div class="avatar-section">
                <div class="avatar-large">
                    <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
                </div>
                <div class="avatar-info">
                    <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
                    <!-- Remplacer username par Rôle -->
                    <div class="role-container">
                        <span class="role-label">Rôle :</span>
                        <span class="role-badge role-<?php echo $user['role']; ?>">
                            <i class="fas <?php echo $user['role'] == 'admin' ? 'fa-crown' : ($user['role'] == 'professeur' ? 'fa-chalkboard-user' : 'fa-graduation-cap'); ?>"></i>
                            <?php 
                            $roles = ['admin' => 'Administrateur', 'etudiant' => 'Étudiant', 'professeur' => 'Professeur'];
                            echo $roles[$user['role']] ?? ucfirst($user['role']);
                            ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="info-section">
                <div class="section-title">
                    <i class="fas fa-chart-line"></i>
                    <h3><?php echo __('activity_stats'); ?></h3>
                </div>
                <div class="stats-detail">
                    <div class="stat-row">
                        <div class="stat-icon-small">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="stat-text">
                            <span><?php echo __('books_borrowed'); ?></span>
                            <strong><?php echo $total_loans; ?></strong>
                        </div>
                    </div>
                    <div class="stat-row">
                        <div class="stat-icon-small">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-text">
                            <span><?php echo __('active_loans'); ?></span>
                            <strong><?php echo $active_loans; ?></strong>
                        </div>
                    </div>
                    <div class="stat-row">
                        <div class="stat-icon-small">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-text">
                            <span><?php echo __('returns_made'); ?></span>
                            <strong><?php echo $total_loans - $active_loans; ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-right">
            <!-- Formulaire d'édition du profil -->
            <div class="edit-section">
                <div class="section-title">
                    <i class="fas fa-edit"></i>
                    <h3><?php echo __('edit_profile'); ?></h3>
                </div>
                
                <form id="editProfileForm" class="edit-profile-form">
                    <div class="form-row">
                        <div class="input-group floating-label">
                            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                            <label for="first_name"><?php echo __('first_name'); ?></label>
                            <i class="fas fa-user input-icon"></i>
                        </div>
                        <div class="input-group floating-label">
                            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                            <label for="last_name"><?php echo __('last_name'); ?></label>
                            <i class="fas fa-user input-icon"></i>
                        </div>
                    </div>


                    <div class="input-group floating-label">
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        <label for="email"><?php echo __('email'); ?></label>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>

                    <div class="form-actions-edit">
                        <button type="submit" class="btn-save-profile">
                            <i class="fas fa-save"></i> <?php echo __('save_changes'); ?>
                        </button>
                        <button type="button" class="btn-change-password" onclick="showPasswordModal()">
                            <i class="fas fa-key"></i> <?php echo __('change_password'); ?>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Bouton d'export RGPD -->
            <div class="rgpd-section">
                <div class="section-title">
                    <i class="fas fa-shield-alt"></i>
                    <h3><?php echo __('personal_data'); ?></h3>
                </div>
                <div class="rgpd-content">
                    <p><?php echo __('rgpd_info'); ?></p>
                    <a href="/profile/export" class="btn-export">
                        <i class="fas fa-download"></i> <?php echo __('export_data'); ?>
                    </a>
                </div>
            </div>

            <div class="action-buttons">
                <a href="/loans" class="btn-primary-action">
                    <i class="fas fa-book-reader"></i>
                    <span><?php echo __('view_my_loans'); ?></span>
                    <i class="fas fa-arrow-right"></i>
                </a>
                <a href="/books" class="btn-secondary-action">
                    <i class="fas fa-book"></i>
                    <span><?php echo __('explore_catalogue'); ?></span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour changer le mot de passe -->
<div id="passwordModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-key"></i> <?php echo __('change_password'); ?></h2>
            <span class="modal-close" onclick="closePasswordModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="changePasswordForm">
                <div class="input-group floating-label">
                    <input type="password" id="current_password" name="current_password" required>
                    <label for="current_password"><?php echo __('current_password'); ?></label>
                    <i class="fas fa-lock input-icon"></i>
                </div>
                <div class="input-group floating-label">
                    <input type="password" id="new_password" name="new_password" required>
                    <label for="new_password"><?php echo __('new_password'); ?></label>
                    <i class="fas fa-key input-icon"></i>
                </div>
                <div class="input-group floating-label">
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <label for="confirm_password"><?php echo __('confirm_password'); ?></label>
                    <i class="fas fa-check-circle input-icon"></i>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closePasswordModal()"><?php echo __('cancel'); ?></button>
                    <button type="submit" class="btn-save"><?php echo __('change_password'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Styles supplémentaires pour le nouveau champ Rôle */
.role-container {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    margin-top: 0.5rem;
    flex-wrap: wrap;
}

.role-label {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.6);
    font-weight: 500;
}

.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 1rem;
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 600;
}

.role-admin {
    background: rgba(99, 102, 241, 0.2);
    color: #6366f1;
    border: 1px solid rgba(99, 102, 241, 0.3);
}

.role-etudiant {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.role-professeur {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.3);
}

/* Garde tous tes styles CSS inchangés */
.profile-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
    animation: fadeInUp 0.5s ease;
}

.profile-header {
    margin-bottom: 2rem;
}

.header-left {
    margin-bottom: 1.5rem;
}

.back-btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.5rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--glass-border);
    border-radius: 40px;
    color: var(--light);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
    margin-bottom: 1rem;
}

.back-btn-modern:hover {
    background: rgba(99, 102, 241, 0.2);
    border-color: var(--primary);
    transform: translateX(-5px);
}

.back-btn-modern i {
    font-size: 0.9rem;
}

.profile-header h1 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.title-icon {
    width: 48px;
    height: 48px;
    background: var(--gradient-1);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
}

.subtitle {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.9rem;
}

.profile-stats-cards {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.stat-card-mini {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--glass-border);
    border-radius: 16px;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    min-width: 160px;
}

.stat-card-mini:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
}

.stat-card-mini i {
    font-size: 1.8rem;
    color: var(--primary);
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-label {
    font-size: 0.7rem;
    opacity: 0.6;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.stat-info strong {
    font-size: 1.5rem;
    font-weight: 700;
}

.profile-content {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 2rem;
}

.profile-left {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.avatar-section {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--glass-border);
    border-radius: 24px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
}

.avatar-section:hover {
    border-color: var(--primary);
}

.avatar-large {
    width: 120px;
    height: 120px;
    background: var(--gradient-1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    font-weight: 700;
    margin: 0 auto 1.5rem;
    box-shadow: var(--shadow-neon);
}

.avatar-info h2 {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.info-section, .edit-section, .rgpd-section {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--glass-border);
    border-radius: 24px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.info-section:hover, .edit-section:hover, .rgpd-section:hover {
    border-color: rgba(99, 102, 241, 0.3);
}

.section-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.section-title i {
    font-size: 1.25rem;
    color: var(--primary);
}

.section-title h3 {
    font-size: 1.1rem;
    font-weight: 600;
}

.edit-profile-form {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.input-group {
    position: relative;
}

.input-group.floating-label input {
    width: 100%;
    padding: 1rem 1rem 1rem 2.8rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    color: var(--light);
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.input-group.floating-label input:focus {
    outline: none;
    border-color: var(--primary);
    background: rgba(255, 255, 255, 0.1);
}

.input-group.floating-label input:read-only {
    opacity: 0.7;
    cursor: not-allowed;
}

.input-group.floating-label label {
    position: absolute;
    left: 2.8rem;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    padding: 0 0.25rem;
    color: rgba(255, 255, 255, 0.5);
    transition: all 0.3s ease;
    pointer-events: none;
    font-size: 0.95rem;
}

.input-group.floating-label input:focus ~ label,
.input-group.floating-label input:not(:placeholder-shown) ~ label {
    top: 0;
    left: 2rem;
    font-size: 0.7rem;
    background: rgba(15, 23, 42, 0.9);
    padding: 0 0.5rem;
    color: var(--primary);
}

.input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary);
    font-size: 1rem;
    pointer-events: none;
}

.form-actions-edit {
    display: flex;
    gap: 1rem;
    margin-top: 0.5rem;
}

.btn-save-profile {
    flex: 1;
    padding: 0.875rem;
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
    border-radius: 12px;
    color: white;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-save-profile:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

.btn-change-password {
    flex: 1;
    padding: 0.875rem;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    color: var(--light);
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-change-password:hover {
    background: rgba(99, 102, 241, 0.2);
    border-color: var(--primary);
    transform: translateY(-2px);
}

.rgpd-content {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.rgpd-content p {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
    line-height: 1.5;
}

.btn-export {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 0.875rem 1.5rem;
    background: rgba(59, 130, 246, 0.2);
    border: 1px solid rgba(59, 130, 246, 0.3);
    border-radius: 12px;
    color: #3b82f6;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    width: 100%;
}

.btn-export:hover {
    background: #3b82f6;
    color: white;
    transform: translateY(-2px);
}

.btn-export i {
    font-size: 1rem;
}

.stats-detail {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.stat-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.stat-row:hover {
    background: rgba(99, 102, 241, 0.1);
    transform: translateX(5px);
}

.stat-icon-small {
    width: 40px;
    height: 40px;
    background: rgba(99, 102, 241, 0.2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: var(--primary);
}

.stat-text {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stat-text span {
    font-size: 0.9rem;
    opacity: 0.8;
}

.stat-text strong {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--primary);
}

.action-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

.btn-primary-action, .btn-secondary-action {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 1rem;
    border-radius: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.btn-primary-action {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.btn-primary-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
}

.btn-secondary-action {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid var(--glass-border);
    color: var(--light);
}

.btn-secondary-action:hover {
    background: rgba(99, 102, 241, 0.2);
    border-color: var(--primary);
    transform: translateY(-2px);
}

.modal {
    display: none;
    position: fixed;
    z-index: 2000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(10px);
}

.modal-content {
    background: linear-gradient(135deg, #1e293b, #0f172a);
    margin: 10% auto;
    width: 90%;
    max-width: 500px;
    border-radius: 24px;
    border: 1px solid var(--glass-border);
    animation: slideUp 0.3s ease;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid var(--glass-border);
}

.modal-header h2 {
    font-size: 1.3rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modal-close {
    font-size: 2rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.modal-close:hover {
    color: var(--danger);
    transform: scale(1.1);
}

.modal-body {
    padding: 1.5rem;
}

.modal-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.btn-cancel {
    flex: 1;
    padding: 0.875rem;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    color: var(--light);
    cursor: pointer;
}

.btn-save {
    flex: 1;
    padding: 0.875rem;
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
    border-radius: 12px;
    color: white;
    cursor: pointer;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .profile-content {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .profile-stats-cards {
        flex-direction: column;
    }
    
    .stat-card-mini {
        width: 100%;
    }
    
    .action-buttons, .form-actions-edit, .form-row {
        flex-direction: column;
    }
    
    .modal-content {
        width: 95%;
        margin: 20% auto;
    }
}
</style>

<script>
// Édition du profil
document.getElementById('editProfileForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    try {
        const response = await fetch('/profile/update', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });
        const result = await response.json();
        
        if (result.success) {
            alert('✅ <?php echo __('profile_updated'); ?>');
            location.reload();
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        alert('<?php echo __('connection_error'); ?>');
    }
});

// Modal mot de passe
function showPasswordModal() {
    document.getElementById('passwordModal').style.display = 'block';
}

function closePasswordModal() {
    document.getElementById('passwordModal').style.display = 'none';
}

// Changement de mot de passe
document.getElementById('changePasswordForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword !== confirmPassword) {
        alert('❌ Les mots de passe ne correspondent pas');
        return;
    }
    
    if (newPassword.length < 6) {
        alert('❌ Le mot de passe doit contenir au moins 6 caractères');
        return;
    }
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    try {
        const response = await fetch('/profile/password', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });
        const result = await response.json();
        
        if (result.success) {
            alert('✅ ' + result.message);
            closePasswordModal();
            document.getElementById('changePasswordForm').reset();
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        alert('Erreur de connexion');
    }
});

// Fermer le modal en cliquant en dehors
window.onclick = function(event) {
    const modal = document.getElementById('passwordModal');
    if (event.target == modal) {
        closePasswordModal();
    }
}
</script>