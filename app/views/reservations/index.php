<?php
$activePage = 'reservations';
$isAdmin = $isAdmin ?? false;
$totalBooks = $totalBooks ?? 0;
$activeLoans = $activeLoans ?? 0;
$totalUsers = $totalUsers ?? 0;
$totalReservations = $totalReservations ?? 0;
$activeReservations = $activeReservations ?? 0;
?>

<div class="reservations-container">
    <!-- Hero Section -->
    <div class="reservations-hero">
        <div class="hero-content">
            <div class="hero-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="hero-text">
                <h1><?php echo $isAdmin ? 'Gestion des réservations' : 'Mes réservations'; ?></h1>
                <p><?php echo $isAdmin ? 'Consultez toutes les réservations des utilisateurs' : 'Gérez vos réservations de livres'; ?></p>
            </div>
        </div>
        <div class="hero-stats">
            <div class="stat-card-mini">
                <i class="fas fa-bookmark"></i>
                <div class="stat-info">
                    <span class="stat-label">Total réservations</span>
                    <strong><?php echo $totalReservations; ?></strong>
                </div>
            </div>
            <div class="stat-card-mini">
                <i class="fas fa-hourglass-half"></i>
                <div class="stat-info">
                    <span class="stat-label">Actives</span>
                    <strong><?php echo $activeReservations; ?></strong>
                </div>
            </div>
            <div class="stat-card-mini">
                <i class="fas fa-check-circle"></i>
                <div class="stat-info">
                    <span class="stat-label">Expirées/Annulées</span>
                    <strong><?php echo $totalReservations - $activeReservations; ?></strong>
                </div>
            </div>
        </div>
    </div>

    <div class="reservations-content">
        <?php if (empty($reservations)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3>Aucune réservation</h3>
                <p>Vous n'avez pas encore de réservations.</p>
                <a href="/books" class="btn-explore">
                    <i class="fas fa-search"></i> Explorer le catalogue
                </a>
            </div>
        <?php else: ?>
            
            <?php if ($isAdmin): ?>
<!-- VUE ADMIN : TABLEAU (sans bouton annuler) -->
<div class="reservations-table-wrapper">
    <table class="reservations-table">
        <thead>
            <tr>
                <th><i class="fas fa-user"></i> Utilisateur</th>
                <th><i class="fas fa-envelope"></i> Email</th>
                <th><i class="fas fa-book"></i> Livre</th>
                <th><i class="fas fa-user-pen"></i> Auteur</th>
                <th><i class="fas fa-calendar-plus"></i> Date réservation</th>
                <th><i class="fas fa-calendar-check"></i> Date expiration</th>
                <th><i class="fas fa-chart-line"></i> Statut</th>
              </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $reservation): ?>
            <tr>
                <td>
                    <div class="user-info-cell">
                        <strong><?php echo htmlspecialchars(($reservation['first_name'] ?? '') . ' ' . ($reservation['last_name'] ?? '')); ?></strong>
                        <span class="user-role-badge <?php echo $reservation['user_role'] ?? 'etudiant'; ?>">
                            <?php 
                            $roleLabels = ['admin' => 'Admin', 'professeur' => 'Professeur', 'etudiant' => 'Étudiant'];
                            echo $roleLabels[$reservation['user_role'] ?? 'etudiant'] ?? 'Étudiant';
                            ?>
                        </span>
                    </div>
                </td>
                <td><?php echo htmlspecialchars($reservation['user_email'] ?? ''); ?></td>
                <td class="book-cell">
                    <div class="book-info-cell">
                        <div class="book-cover-small" style="background-image: url('<?php echo $reservation['cover_image'] ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=100'; ?>')"></div>
                        <div class="book-details-cell">
                            <strong><?php echo htmlspecialchars($reservation['title'] ?? 'Titre inconnu'); ?></strong>
                            <span class="book-isbn-small">ISBN: <?php echo htmlspecialchars($reservation['isbn'] ?? 'N/A'); ?></span>
                        </div>
                    </div>
                </td>
                <td><?php echo htmlspecialchars($reservation['author'] ?? 'Auteur inconnu'); ?></td>
                <td><?php echo date('d/m/Y', strtotime($reservation['reservation_date'])); ?></td>
                <td class="expiry-date <?php echo strtotime($reservation['expiry_date']) < time() ? 'text-danger' : ''; ?>">
                    <?php echo date('d/m/Y', strtotime($reservation['expiry_date'])); ?>
                    <?php if (strtotime($reservation['expiry_date']) < time() && $reservation['status'] == 'active'): ?>
                        <span class="expired-badge">Expirée</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php
                    $statusClass = '';
                    $statusIcon = '';
                    $statusText = '';
                    if ($reservation['status'] == 'active') {
                        if (strtotime($reservation['expiry_date']) < time()) {
                            $statusClass = 'status-expired';
                            $statusIcon = 'fa-times-circle';
                            $statusText = 'Expirée';
                        } else {
                            $statusClass = 'status-active';
                            $statusIcon = 'fa-clock';
                            $statusText = 'Active';
                        }
                    } elseif ($reservation['status'] == 'cancelled' || $reservation['status'] == 'annulee') {
                        $statusClass = 'status-cancelled';
                        $statusIcon = 'fa-ban';
                        $statusText = 'Annulée';
                    } else {
                        $statusClass = 'status-expired';
                        $statusIcon = 'fa-times-circle';
                        $statusText = 'Expirée';
                    }
                    ?>
                    <span class="status-badge <?php echo $statusClass; ?>">
                        <i class="fas <?php echo $statusIcon; ?>"></i> <?php echo $statusText; ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
                
            <?php else: ?>
                <!-- VUE UTILISATEUR : CARTES (avec bouton annuler) -->
                <div class="reservations-grid">
                    <?php foreach ($reservations as $reservation): ?>
                    <div class="reservation-card <?php echo $reservation['status'] == 'expiree' ? 'expired' : ($reservation['status'] == 'annulee' ? 'cancelled' : 'active'); ?>">
                        <div class="reservation-status-badge">
                            <?php if ($reservation['status'] == 'active'): ?>
                                <span class="badge-active"><i class="fas fa-hourglass-half"></i> Active</span>
                            <?php elseif ($reservation['status'] == 'expiree'): ?>
                                <span class="badge-expired"><i class="fas fa-times-circle"></i> Expirée</span>
                            <?php else: ?>
                                <span class="badge-cancelled"><i class="fas fa-ban"></i> Annulée</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="reservation-book">
                            <div class="book-cover" style="background-image: url('<?php echo $reservation['cover_image'] ?? 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=100'; ?>')"></div>
                            <div class="book-info">
                                <h3 class="book-title"><?php echo htmlspecialchars($reservation['title']); ?></h3>
                                <p class="book-author">
                                    <i class="fas fa-user-pen"></i> <?php echo htmlspecialchars($reservation['author']); ?>
                                </p>
                                <p class="book-isbn">
                                    <i class="fas fa-barcode"></i> <?php echo $reservation['isbn'] ?? 'N/A'; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="reservation-dates">
                            <div class="date-item">
                                <i class="fas fa-calendar-plus"></i>
                                <div>
                                    <span class="date-label">Réservé le</span>
                                    <strong><?php echo date('d/m/Y', strtotime($reservation['reservation_date'])); ?></strong>
                                </div>
                            </div>
                            <div class="date-item <?php echo strtotime($reservation['expiry_date']) < time() ? 'expired-date' : ''; ?>">
                                <i class="fas fa-calendar-times"></i>
                                <div>
                                    <span class="date-label">Expire le</span>
                                    <strong><?php echo date('d/m/Y', strtotime($reservation['expiry_date'])); ?></strong>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($reservation['status'] == 'active'): ?>
                        <div class="reservation-actions">
                            <button class="btn-cancel" onclick="cancelReservation(<?php echo $reservation['id']; ?>)">
                                <i class="fas fa-times"></i> Annuler la réservation
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
        <?php endif; ?>
    </div>
</div>

<style>
.reservations-container {
    max-width: 1400px;
    margin: 0 auto;
}

.reservations-hero {
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
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);
}

.hero-text h1 {
    font-size: 2rem;
    margin-bottom: 0.25rem;
    background: linear-gradient(135deg, #fff, #fde68a);
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
    border-color: #f59e0b;
}

.stat-card-mini i {
    font-size: 1.5rem;
    color: #f59e0b;
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

.reservations-table-wrapper {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 24px;
    overflow: hidden;
    overflow-x: auto;
}

.reservations-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1000px;
}

.reservations-table th {
    padding: 1rem;
    text-align: left;
    background: rgba(99, 102, 241, 0.05);
    color: var(--text-secondary);
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.reservations-table th i {
    margin-right: 0.5rem;
    color: #f59e0b;
}

.reservations-table td {
    padding: 0.8rem 1rem;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-primary);
    vertical-align: middle;
}

.reservations-table tr:hover td {
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

.expiry-date {
    position: relative;
}

.text-danger {
    color: #ef4444;
    font-weight: 600;
}

.expired-badge {
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
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.status-expired {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.status-cancelled {
    background: rgba(107, 114, 128, 0.2);
    color: #6b7280;
    border: 1px solid rgba(107, 114, 128, 0.3);
}

.reservations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 1.5rem;
}

.reservation-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 24px;
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
}

.reservation-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary);
}

.reservation-card.active {
    border-left: 4px solid #10b981;
}

.reservation-card.expired {
    opacity: 0.7;
    border-left: 4px solid #ef4444;
}

.reservation-card.cancelled {
    opacity: 0.6;
    border-left: 4px solid #6b7280;
}

.reservation-status-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    z-index: 10;
}

.badge-active, .badge-expired, .badge-cancelled {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 0.8rem;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-active {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.badge-expired {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.badge-cancelled {
    background: rgba(107, 114, 128, 0.2);
    color: #6b7280;
    border: 1px solid rgba(107, 114, 128, 0.3);
}

.reservation-book {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
}

.book-cover {
    width: 80px;
    height: 110px;
    background-size: cover;
    background-position: center;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.book-info {
    flex: 1;
}

.book-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.book-author {
    font-size: 0.8rem;
    color: var(--primary);
    margin-bottom: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.book-isbn {
    font-size: 0.7rem;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.reservation-dates {
    display: flex;
    gap: 1rem;
    padding: 1rem 1.5rem;
    background: rgba(99, 102, 241, 0.05);
}

.date-item {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.date-item i {
    font-size: 1.2rem;
    color: var(--primary);
}

.date-item div {
    display: flex;
    flex-direction: column;
}

.date-label {
    font-size: 0.65rem;
    opacity: 0.6;
    text-transform: uppercase;
}

.date-item strong {
    font-size: 0.85rem;
    font-weight: 600;
}

.expired-date strong {
    color: #ef4444;
}

.reservation-actions {
    padding: 1rem 1.5rem 1.5rem;
}

.btn-cancel {
    width: 100%;
    padding: 0.75rem;
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    border-radius: 12px;
    color: #ef4444;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-cancel:hover {
    background: #ef4444;
    color: white;
    transform: translateY(-2px);
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
    .reservations-hero {
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
    
    .reservations-grid {
        grid-template-columns: 1fr;
    }
    
    .reservation-book {
        flex-direction: column;
        text-align: center;
    }
    
    .book-cover {
        margin: 0 auto;
    }
    
    .reservation-dates {
        flex-direction: column;
    }
}
</style>

<script>
async function cancelReservation(reservationId) {
    if (!confirm('Voulez-vous annuler cette réservation ?')) return;
    
    try {
        const response = await fetch('/reservations/cancel', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({reservation_id: reservationId})
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