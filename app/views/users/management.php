<?php
$activePage = 'users';
$totalBooks = $totalBooks ?? 0;
$activeLoans = $activeLoans ?? 0;
$totalUsers = $totalUsers ?? 0;
$totalReservations = $totalReservations ?? 0;
?>

<div class="users-container">
    <!-- Hero Section -->
    <div class="users-hero">
        <div class="hero-content">
            <div class="hero-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="hero-text">
                <h1><?php echo __('user_management'); ?></h1>
                <p><?php echo __('manage_users'); ?></p>
            </div>
        </div>
        <div class="hero-stats">
            <div class="stat-card-mini">
                <i class="fas fa-users"></i>
                <div class="stat-info">
                    <span class="stat-label"><?php echo __('total_users'); ?></span>
                    <strong><?php echo $stats['total']; ?></strong>
                </div>
            </div>
            <div class="stat-card-mini">
                <i class="fas fa-check-circle"></i>
                <div class="stat-info">
                    <span class="stat-label"><?php echo __('active_users_count'); ?></span>
                    <strong><?php echo $stats['active']; ?></strong>
                </div>
            </div>
            <div class="stat-card-mini">
                <i class="fas fa-ban"></i>
                <div class="stat-info">
                    <span class="stat-label"><?php echo __('inactive_users'); ?></span>
                    <strong><?php echo $stats['inactive']; ?></strong>
                </div>
            </div>
            <div class="stat-card-mini">
                <i class="fas fa-crown"></i>
                <div class="stat-info">
                    <span class="stat-label"><?php echo __('admins'); ?></span>
                    <strong><?php echo $stats['admins']; ?></strong>
                </div>
            </div>
            <div class="stat-card-mini">
                <i class="fas fa-graduation-cap"></i>
                <div class="stat-info">
                    <span class="stat-label"><?php echo __('students'); ?></span>
                    <strong><?php echo $stats['students']; ?></strong>
                </div>
            </div>
            <div class="stat-card-mini">
                <i class="fas fa-chalkboard-user"></i>
                <div class="stat-info">
                    <span class="stat-label"><?php echo __('teachers'); ?></span>
                    <strong><?php echo $stats['teachers']; ?></strong>
                </div>
            </div>
        </div>
    </div>

    <div class="users-content">
        <div class="users-table-wrapper">
            <table class="users-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-user"></i> <?php echo __('user'); ?></th>
                        <th><i class="fas fa-envelope"></i> <?php echo __('email'); ?></th>
                        <th><i class="fas fa-user-tag"></i> <?php echo __('role'); ?></th>
                        <th><i class="fas fa-chart-line"></i> <?php echo __('statistics'); ?></th>
                        <th><i class="fas fa-calendar"></i> <?php echo __('member_since'); ?></th>
                        <th><i class="fas fa-circle"></i> <?php echo __('status'); ?></th>
                        <th><i class="fas fa-cog"></i> <?php echo __('action'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr class="user-row">
                        <td class="user-cell">
                            <div class="user-avatar-small">
                                <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
                            </div>
                            <div class="user-info-cell">
                                <strong><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong>
                                <span>@<?php echo htmlspecialchars($user['username']); ?></span>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="role-badge role-<?php echo $user['role']; ?>">
                                <i class="fas <?php echo $user['role'] == 'admin' ? 'fa-crown' : ($user['role'] == 'professeur' ? 'fa-chalkboard-user' : 'fa-graduation-cap'); ?>"></i>
                                <?php echo ucfirst($user['role']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="user-stats">
                                <span title="<?php echo __('total_loans_user'); ?>"><i class="fas fa-book"></i> <?php echo $user['total_loans'] ?? 0; ?></span>
                                <span title="<?php echo __('active_loans_user'); ?>"><i class="fas fa-hourglass-half"></i> <?php echo $user['active_loans'] ?? 0; ?></span>
                            </div>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $user['status']; ?>">
                                <i class="fas <?php echo $user['status'] == 'actif' ? 'fa-check-circle' : 'fa-ban'; ?>"></i>
                                <?php echo $user['status'] == 'actif' ? __('status_active') : __('status_inactive'); ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-toggle-status" onclick="toggleUserStatus(<?php echo $user['id']; ?>, '<?php echo $user['status']; ?>')" title="<?php echo $user['status'] == 'actif' ? __('deactivate') : __('activate'); ?>">
                                    <i class="fas <?php echo $user['status'] == 'actif' ? 'fa-ban' : 'fa-check-circle'; ?>"></i>
                                </button>
                                <button class="btn-delete-user" onclick="deleteUser(<?php echo $user['id']; ?>)" title="<?php echo __('delete_user'); ?>">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
/* Garde tous tes styles CSS inchangés */
.users-container {
    max-width: 1400px;
    margin: 0 auto;
}

.users-hero {
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(139, 92, 246, 0.05));
    border-radius: 32px;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid var(--border-color);
}

.hero-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.hero-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
}

.hero-text h1 {
    font-size: 2rem;
    margin-bottom: 0.25rem;
    background: linear-gradient(135deg, #fff, #a5b4fc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-text p {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.hero-stats {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.stat-card-mini {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 0.75rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
}

.stat-card-mini:hover {
    transform: translateY(-2px);
    border-color: var(--primary);
}

.stat-card-mini i {
    font-size: 1.5rem;
    color: var(--primary);
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-label {
    font-size: 0.65rem;
    opacity: 0.6;
    text-transform: uppercase;
}

.stat-info strong {
    font-size: 1.2rem;
    font-weight: 700;
}

.users-table-wrapper {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 24px;
    overflow-x: auto;
    box-shadow: var(--shadow-sm);
}

.users-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 900px;
}

.users-table th {
    padding: 1.25rem 1rem;
    text-align: left;
    background: rgba(99, 102, 241, 0.05);
    color: var(--text-secondary);
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.users-table th i {
    margin-right: 0.5rem;
    color: var(--primary);
}

.users-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-primary);
}

.user-row:hover td {
    background: rgba(99, 102, 241, 0.05);
}

.user-cell {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.user-avatar-small {
    width: 40px;
    height: 40px;
    background: var(--gradient-1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: white;
}

.user-info-cell {
    display: flex;
    flex-direction: column;
}

.user-info-cell strong {
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.user-info-cell span {
    font-size: 0.7rem;
    color: var(--text-secondary);
}

.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.35rem 0.8rem;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 500;
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

.user-stats {
    display: flex;
    gap: 1rem;
}

.user-stats span {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.user-stats i {
    color: var(--primary);
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.35rem 0.8rem;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-actif {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.status-inactif {
    background: rgba(107, 114, 128, 0.2);
    color: #6b7280;
    border: 1px solid rgba(107, 114, 128, 0.3);
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-toggle-status, .btn-delete-user {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-toggle-status {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.btn-toggle-status:hover {
    background: #10b981;
    color: white;
    transform: translateY(-2px);
}

.btn-delete-user {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.btn-delete-user:hover {
    background: #ef4444;
    color: white;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .hero-content {
        flex-direction: column;
        text-align: center;
    }
    
    .hero-stats {
        justify-content: center;
    }
    
    .stat-card-mini {
        padding: 0.5rem 1rem;
    }
    
    .user-stats {
        flex-direction: column;
        gap: 0.25rem;
    }
}
</style>

<script>
async function toggleUserStatus(userId, currentStatus) {
    const newStatus = currentStatus === 'actif' ? 'inactif' : 'actif';
    const action = newStatus === 'actif' ? '<?php echo __('activate'); ?>' : '<?php echo __('deactivate'); ?>';
    
    if (!confirm(`Voulez-vous ${action} cet utilisateur ?`)) return;
    
    try {
        const response = await fetch('/users/toggle-status', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({user_id: userId, status: newStatus})
        });
        const result = await response.json();
        
        if (result.success) {
            alert('✅ ' + result.message);
            location.reload();
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        alert('<?php echo __('connection_error'); ?>');
    }
}

async function deleteUser(userId) {
    if (!confirm('<?php echo __('confirm_delete_user'); ?>')) return;
    
    try {
        const response = await fetch('/users/delete', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({user_id: userId})
        });
        const result = await response.json();
        
        if (result.success) {
            alert('✅ ' + result.message);
            location.reload();
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        alert('<?php echo __('connection_error'); ?>');
    }
}
</script>