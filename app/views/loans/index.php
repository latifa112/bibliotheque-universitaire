<?php
$activePage = 'loans';
$totalBooks = $totalBooks ?? 0;
$activeLoans = $activeLoans ?? 0;
$totalUsers = $totalUsers ?? 0;
$totalReservations = $totalReservations ?? 0;

$totalLoans = count($loans);
$activeLoansCount = 0;
foreach ($loans as $loan) {
    if ($loan['status'] == 'en_cours') $activeLoansCount++;
}
?>

<div class="loans-container">
    <!-- Hero Section -->
    <div class="loans-hero">
        <div class="hero-content">
            <div class="hero-icon">
                <i class="fas fa-book-reader"></i>
            </div>
            <div class="hero-text">
                <h1><?php echo __('loans'); ?></h1>
                <p><?php echo __('manage_loans'); ?></p>
            </div>
        </div>
        <div class="hero-stats">
            <div class="stat-card-mini">
                <i class="fas fa-book"></i>
                <div class="stat-info">
                    <span class="stat-label"><?php echo __('total_loans'); ?></span>
                    <strong><?php echo $totalLoans; ?></strong>
                </div>
            </div>
            <div class="stat-card-mini">
                <i class="fas fa-hourglass-half"></i>
                <div class="stat-info">
                    <span class="stat-label"><?php echo __('active_loans'); ?></span>
                    <strong><?php echo $activeLoansCount; ?></strong>
                </div>
            </div>
            <div class="stat-card-mini">
                <i class="fas fa-check-circle"></i>
                <div class="stat-info">
                    <span class="stat-label"><?php echo __('returns_made'); ?></span>
                    <strong><?php echo $totalLoans - $activeLoansCount; ?></strong>
                </div>
            </div>
        </div>
    </div>

    <div class="loans-content">
        <?php if (empty($loans)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3><?php echo __('no_loans'); ?></h3>
                <p><?php echo __('no_loans_message'); ?></p>
                <a href="/books" class="btn-explore">
                    <i class="fas fa-search"></i> <?php echo __('explore_catalogue'); ?>
                </a>
            </div>
        <?php else: ?>
            <div class="loans-table-wrapper">
                <table class="loans-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-book"></i> <?php echo __('book'); ?></th>
                            <th><i class="fas fa-user-pen"></i> <?php echo __('author'); ?></th>
                            <th><i class="fas fa-calendar-alt"></i> <?php echo __('loan_date'); ?></th>
                            <th><i class="fas fa-hourglass-end"></i> <?php echo __('due_date'); ?></th>
                            <th><i class="fas fa-chart-line"></i> <?php echo __('status'); ?></th>
                            <th><i class="fas fa-cog"></i> <?php echo __('action'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($loans as $loan): ?>
                        <tr class="loan-row <?php echo $loan['status'] == 'en_retard' ? 'overdue' : ''; ?>">
                            <td class="book-cell">
                                <div class="book-info-cell">
                                    <div class="book-cover-small" style="background-image: url('<?php echo $loan['cover_image'] ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=100'; ?>')"></div>
                                    <div class="book-details-cell">
                                        <strong><?php echo htmlspecialchars($loan['title']); ?></strong>
                                        <span class="book-isbn-small">ISBN: <?php echo $loan['isbn'] ?? 'N/A'; ?></span>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($loan['author']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($loan['loan_date'])); ?></td>
                            <td class="due-date <?php echo $loan['status'] == 'en_retard' ? 'text-danger' : ''; ?>">
                                <?php echo date('d/m/Y', strtotime($loan['due_date'])); ?>
                                <?php if ($loan['status'] == 'en_retard'): ?>
                                    <span class="overdue-badge"><?php echo __('late'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $statusClass = '';
                                $statusIcon = '';
                                $statusText = '';
                                if ($loan['status'] == 'en_cours') {
                                    $statusClass = 'status-active';
                                    $statusIcon = 'fa-spinner';
                                    $statusText = __('in_progress');
                                } elseif ($loan['status'] == 'en_retard') {
                                    $statusClass = 'status-overdue';
                                    $statusIcon = 'fa-exclamation-triangle';
                                    $statusText = __('late');
                                } elseif ($loan['status'] == 'retourne') {
                                    $statusClass = 'status-returned';
                                    $statusIcon = 'fa-check-circle';
                                    $statusText = __('returned');
                                }
                                ?>
                                <span class="status-badge <?php echo $statusClass; ?>">
                                    <i class="fas <?php echo $statusIcon; ?>"></i> <?php echo $statusText; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($loan['status'] == 'en_cours'): ?>
                                    <button class="btn-return" onclick="returnBook(<?php echo $loan['id']; ?>)">
                                        <i class="fas fa-undo-alt"></i> <?php echo __('return'); ?>
                                    </button>
                                <?php elseif ($loan['status'] == 'en_retard'): ?>
                                    <button class="btn-return-overdue" onclick="returnBook(<?php echo $loan['id']; ?>)">
                                        <i class="fas fa-undo-alt"></i> <?php echo __('return'); ?>
                                    </button>
                                <?php else: ?>
                                    <span class="returned-badge">
                                        <i class="fas fa-check"></i> <?php echo __('returned'); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.loans-container {
    max-width: 1200px;
    margin: 0 auto;
}

.loans-hero {
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
}

.stat-card-mini {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
}

.stat-card-mini:hover {
    transform: translateY(-2px);
    border-color: var(--primary);
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
}

.stat-info strong {
    font-size: 1.5rem;
    font-weight: 700;
}

.loans-table-wrapper {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 24px;
    overflow: hidden;
    overflow-x: auto;
}

.loans-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

.loans-table th {
    padding: 1.25rem 1rem;
    text-align: left;
    background: rgba(99, 102, 241, 0.05);
    color: var(--text-secondary);
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.loans-table th i {
    margin-right: 0.5rem;
    color: var(--primary);
}

.loans-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-primary);
}

.loan-row:hover td {
    background: rgba(99, 102, 241, 0.05);
}

.book-cell {
    min-width: 250px;
}

.book-info-cell {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.book-cover-small {
    width: 50px;
    height: 70px;
    background-size: cover;
    background-position: center;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.book-details-cell {
    display: flex;
    flex-direction: column;
}

.book-details-cell strong {
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
}

.book-isbn-small {
    font-size: 0.7rem;
    color: var(--text-secondary);
}

.due-date {
    position: relative;
}

.text-danger {
    color: #ef4444;
    font-weight: 600;
}

.overdue-badge {
    display: inline-block;
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
    border-radius: 20px;
    margin-left: 0.5rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 0.8rem;
    border-radius: 30px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-active {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.status-overdue {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.status-returned {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.btn-return, .btn-return-overdue {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 500;
    font-size: 0.8rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-return {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.btn-return-overdue {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.returned-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border-radius: 10px;
    font-size: 0.8rem;
    font-weight: 500;
}

.empty-state {
    text-align: center;
    padding: 4rem;
    background: var(--card-bg);
    border-radius: 24px;
    border: 1px solid var(--border-color);
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: rgba(99, 102, 241, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.empty-icon i {
    font-size: 2.5rem;
    color: var(--primary);
}

.btn-explore {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: var(--gradient-1);
    color: white;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 500;
}

@media (max-width: 768px) {
    .loans-hero {
        flex-direction: column;
        text-align: center;
    }
    
    .hero-content {
        flex-direction: column;
    }
    
    .hero-stats {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
async function returnBook(loanId) {
    if (!confirm('<?php echo __('confirm_return'); ?>')) return;
    
    try {
        const response = await fetch('/loans/return', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({loan_id: loanId})
        });
        const result = await response.json();
        
        if (result.success) {
            alert('✅ ' + result.message);
            location.reload();
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        alert('<?php echo __('error'); ?>');
    }
}
</script>