<?php
$activePage = 'loans';
$isAdmin = $isAdmin ?? false;
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
                <h1><?php echo $isAdmin ? 'Gestion des emprunts' : 'Mes emprunts'; ?></h1>
                <p><?php echo $isAdmin ? 'Consultez tous les emprunts des utilisateurs' : 'Gérez vos emprunts et retours'; ?></p>
            </div>
        </div>
        <div class="hero-stats">
            <div class="stat-card-mini">
                <i class="fas fa-book"></i>
                <div class="stat-info">
                    <span class="stat-label">Total emprunts</span>
                    <strong><?php echo $totalLoans; ?></strong>
                </div>
            </div>
            <div class="stat-card-mini">
                <i class="fas fa-hourglass-half"></i>
                <div class="stat-info">
                    <span class="stat-label">En cours</span>
                    <strong><?php echo $activeLoansCount; ?></strong>
                </div>
            </div>
            <div class="stat-card-mini">
                <i class="fas fa-check-circle"></i>
                <div class="stat-info">
                    <span class="stat-label">Retournés</span>
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
                <h3>Aucun emprunt</h3>
                <p>Vous n'avez pas encore emprunté de livres.</p>
                <a href="/books" class="btn-explore">
                    <i class="fas fa-search"></i> Explorer le catalogue
                </a>
            </div>
        <?php else: ?>
            <div class="loans-table-wrapper">
                <table class="loans-table">
                    <thead>
                        <tr>
                            <?php if ($isAdmin): ?>
                                <th><i class="fas fa-user"></i> Utilisateur</th>
                                <th><i class="fas fa-envelope"></i> Email</th>
                            <?php endif; ?>
                            <th><i class="fas fa-book"></i> Livre</th>
                            <th><i class="fas fa-user-pen"></i> Auteur</th>
                            <th><i class="fas fa-calendar-alt"></i> Date emprunt</th>
                            <th><i class="fas fa-hourglass-end"></i> Date retour</th>
                            <th><i class="fas fa-chart-line"></i> Statut</th>
                            <th><i class="fas fa-cog"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($loans as $loan): ?>
                        <tr class="loan-row <?php echo $loan['status'] == 'en_retard' ? 'overdue' : ''; ?>">
                            <?php if ($isAdmin): ?>
                                <td>
                                    <div class="user-info-cell">
                                        <strong><?php echo htmlspecialchars(($loan['first_name'] ?? '') . ' ' . ($loan['last_name'] ?? '')); ?></strong>
                                        <span class="user-role-badge <?php echo $loan['user_role'] ?? 'etudiant'; ?>">
                                            <?php 
                                            $roleLabels = ['admin' => 'Admin', 'professeur' => 'Professeur', 'etudiant' => 'Étudiant'];
                                            echo $roleLabels[$loan['user_role'] ?? 'etudiant'] ?? 'Étudiant';
                                            ?>
                                        </span>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($loan['user_email'] ?? ''); ?></td>
                            <?php endif; ?>
                            <td class="book-cell">
                                <div class="book-info-cell">
                                    <div class="book-cover-small" style="background-image: url('<?php echo $loan['cover_image'] ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=100'; ?>')"></div>
                                    <div class="book-details-cell">
                                        <strong><?php echo htmlspecialchars($loan['title'] ?? 'Titre inconnu'); ?></strong>
                                        <span class="book-isbn-small">ISBN: <?php echo htmlspecialchars($loan['isbn'] ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($loan['author'] ?? 'Auteur inconnu'); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($loan['loan_date'])); ?></td>
                            <td class="due-date <?php echo $loan['status'] == 'en_retard' ? 'text-danger' : ''; ?>">
                                <?php echo date('d/m/Y', strtotime($loan['due_date'])); ?>
                                <?php if ($loan['status'] == 'en_retard'): ?>
                                    <span class="overdue-badge">Retard</span>
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
                                    $statusText = 'En cours';
                                } elseif ($loan['status'] == 'en_retard') {
                                    $statusClass = 'status-overdue';
                                    $statusIcon = 'fa-exclamation-triangle';
                                    $statusText = 'En retard';
                                } elseif ($loan['status'] == 'retourne') {
                                    $statusClass = 'status-returned';
                                    $statusIcon = 'fa-check-circle';
                                    $statusText = 'Retourné';
                                }
                                ?>
                                <span class="status-badge <?php echo $statusClass; ?>">
                                    <i class="fas <?php echo $statusIcon; ?>"></i> <?php echo $statusText; ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!$isAdmin && ($loan['status'] == 'en_cours' || $loan['status'] == 'en_retard')): ?>
                                    <button class="btn-return" onclick="returnBook(<?php echo $loan['id']; ?>)">
                                        <i class="fas fa-undo-alt"></i> Retourner
                                    </button>
                                <?php elseif ($isAdmin && $loan['status'] == 'en_cours'): ?>
                                    <span class="admin-badge">
                                        <i class="fas fa-eye"></i> En cours
                                    </span>
                                <?php elseif ($loan['status'] == 'retourne'): ?>
                                    <span class="returned-badge">
                                        <i class="fas fa-check"></i> Terminé
                                    </span>
                                <?php else: ?>
                                    <span class="returned-badge">
                                        <i class="fas fa-check"></i> Terminé
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
    max-width: 1400px;
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
    padding: 1rem;
    text-align: left;
    background: rgba(99, 102, 241, 0.05);
    color: var(--text-secondary);
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.loans-table th i {
    margin-right: 0.5rem;
    color: var(--primary);
}

.loans-table td {
    padding: 0.8rem 1rem;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-primary);
    vertical-align: middle;
}

.loan-row:hover td {
    background: rgba(99, 102, 241, 0.05);
}

.user-info-cell {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.user-role-badge {
    font-size: 0.65rem;
    padding: 0.15rem 0.5rem;
    border-radius: 20px;
    display: inline-block;
    width: fit-content;
}

.user-role-badge.admin {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.user-role-badge.professeur {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
}

.user-role-badge.etudiant {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.book-cell {
    min-width: 250px;
}

.book-info-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.book-cover-small {
    width: 40px;
    height: 56px;
    background-size: cover;
    background-position: center;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.book-details-cell {
    display: flex;
    flex-direction: column;
}

.book-details-cell strong {
    font-size: 0.9rem;
    margin-bottom: 0.2rem;
}

.book-isbn-small {
    font-size: 0.65rem;
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
    font-size: 0.65rem;
    padding: 0.15rem 0.5rem;
    border-radius: 20px;
    margin-left: 0.5rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.3rem 0.7rem;
    border-radius: 30px;
    font-size: 0.75rem;
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

.btn-return {
    padding: 0.4rem 0.8rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    font-size: 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.btn-return:hover {
    transform: translateY(-2px);
}

.returned-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.4rem 0.8rem;
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border-radius: 8px;
    font-size: 0.75rem;
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
    padding: 0.7rem 1.5rem;
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

.admin-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.4rem 0.8rem;
    background: rgba(99, 102, 241, 0.15);
    color: #6366f1;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 500;
}
</style>

<script>
async function returnBook(loanId) {
    if (!confirm('Voulez-vous retourner ce livre ?')) return;
    
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
        alert('Erreur de connexion');
    }
}
</script>