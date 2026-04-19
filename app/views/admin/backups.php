<?php
$activePage = 'backups';
$totalBooks = $totalBooks ?? 0;
$activeLoans = $activeLoans ?? 0;
$totalUsers = $totalUsers ?? 0;
$totalReservations = $totalReservations ?? 0;
?>

<div class="backups-container">
    <!-- Hero Section -->
    <div class="backups-hero">
        <div class="hero-content">
            <div class="hero-icon">
                <i class="fas fa-database"></i>
            </div>
            <div class="hero-text">
                <h1><?php echo __('backups'); ?></h1>
                <p><?php echo __('manage_backups'); ?></p>
            </div>
        </div>
        <div class="hero-stats">
            <div class="stat-card-mini">
                <i class="fas fa-file-archive"></i>
                <div class="stat-info">
                    <span class="stat-label"><?php echo __('total_backups'); ?></span>
                    <strong><?php echo count($backups); ?></strong>
                </div>
            </div>
            <div class="stat-card-mini">
                <i class="fas fa-database"></i>
                <div class="stat-info">
                    <span class="stat-label"><?php echo __('space_used'); ?></span>
                    <strong><?php echo $totalSize ?? '0 MB'; ?></strong>
                </div>
            </div>
            <div class="stat-card-mini">
                <i class="fas fa-clock"></i>
                <div class="stat-info">
                    <span class="stat-label"><?php echo __('last_backup'); ?></span>
                    <strong><?php echo $lastBackup ?? 'never'; ?></strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="backups-actions">
        <button class="btn-primary" onclick="createBackup()">
            <i class="fas fa-plus-circle"></i><?php echo __('create_backup'); ?>
        </button>
        <div class="backup-info">
            <i class="fas fa-info-circle"></i>
             <?php echo __('auto_backup_info'); ?>
        </div>
    </div>

    <!-- Liste des sauvegardes -->
  <div class="backups-list">
        <div class="list-header">
            <h3><i class="fas fa-history"></i> <?php echo __('backup_history'); ?></h3>
        </div>
        
        <?php if (empty($backups)): ?>
            <div class="empty-state">
                <i class="fas fa-database"></i>
                <h3><?php echo __('no_backups'); ?></h3>
                <p><?php echo __('create_first_backup'); ?></p>
            </div>
        <?php else: ?>
            <div class="backups-grid">
                <?php foreach ($backups as $backup): ?>
                <div class="backup-card">
                    <div class="backup-icon">
                        <i class="fas fa-file-archive"></i>
                    </div>
                    <div class="backup-info">
                        <div class="backup-name"><?php echo htmlspecialchars($backup['name']); ?></div>
                        <div class="backup-meta">
                            <span><i class="fas fa-calendar"></i> <?php echo $backup['date']; ?></span>
                            <span><i class="fas fa-hdd"></i> <?php echo $backup['size']; ?></span>
                        </div>
                    </div>
                    <div class="backup-actions">
                        <button class="btn-download" onclick="downloadBackup('<?php echo $backup['name']; ?>')" title="<?php echo __('download'); ?>">
                            <i class="fas fa-download"></i>
                        </button>
                        <button class="btn-delete" onclick="deleteBackup('<?php echo $backup['name']; ?>')" title="<?php echo __('delete'); ?>">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.backups-container {
    max-width: 1200px;
    margin: 0 auto;
}

/* Hero Section */
.backups-hero {
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(139, 92, 246, 0.05));
    border-radius: 32px;
    padding: 2rem;
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
    border: 1px solid var(--border-color);
}

.hero-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
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
    font-size: 0.7rem;
    opacity: 0.6;
    text-transform: uppercase;
}

.stat-info strong {
    font-size: 1.2rem;
    font-weight: 700;
}

/* Actions */
.backups-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.btn-primary {
    padding: 0.8rem 1.5rem;
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
    border-radius: 40px;
    color: white;
    cursor: pointer;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

.backup-info {
    padding: 0.6rem 1rem;
    background: rgba(99, 102, 241, 0.1);
    border-radius: 30px;
    color: var(--text-secondary);
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Liste des sauvegardes */
.backups-list {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 24px;
    overflow: hidden;
}

.list-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background: rgba(99, 102, 241, 0.05);
}

.list-header h3 {
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.backups-grid {
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.backup-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.02);
    border-radius: 16px;
    transition: all 0.3s;
}

.backup-card:hover {
    background: rgba(99, 102, 241, 0.05);
    transform: translateX(5px);
}

.backup-icon {
    width: 45px;
    height: 45px;
    background: rgba(99, 102, 241, 0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    color: var(--primary);
}

.backup-info {
    flex: 1;
}

.backup-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
}

.backup-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.7rem;
    color: var(--text-secondary);
}

.backup-meta span {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.backup-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-download, .btn-delete {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-download {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
}

.btn-download:hover {
    background: #3b82f6;
    color: white;
    transform: translateY(-2px);
}

.btn-delete {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.btn-delete:hover {
    background: #ef4444;
    color: white;
    transform: translateY(-2px);
}

.empty-state {
    text-align: center;
    padding: 3rem;
}

.empty-state i {
    font-size: 3rem;
    color: var(--primary);
    margin-bottom: 1rem;
}

.empty-state h3 {
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: var(--text-secondary);
}

@media (max-width: 768px) {
    .backups-hero {
        flex-direction: column;
        text-align: center;
    }
    
    .hero-content {
        flex-direction: column;
    }
    
    .backup-card {
        flex-wrap: wrap;
    }
    
    .backup-actions {
        width: 100%;
        justify-content: flex-end;
    }
}
</style>

<script>
async function createBackup() {
    try {
        const response = await fetch('/admin/backups/create', { method: 'POST' });
        const result = await response.json();
        alert(result.success ? '✅ ' + result.message : '❌ ' + result.message);
        if (result.success) location.reload();
    } catch (error) {
        alert('Erreur de connexion');
    }
}

function downloadBackup(filename) {
    window.location.href = '/admin/backups/download?file=' + encodeURIComponent(filename);
}

async function deleteBackup(filename) {
    if (!confirm('Supprimer cette sauvegarde ?')) return;
    
    try {
        const response = await fetch('/admin/backups/delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ file: filename })
        });
        const result = await response.json();
        alert(result.success ? '✅ ' + result.message : '❌ ' + result.message);
        if (result.success) location.reload();
    } catch (error) {
        alert('Erreur de connexion');
    }
}
</script>