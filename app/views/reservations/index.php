<?php
$activePage = 'reservations';
$totalBooks = $totalBooks ?? 0;
$activeLoans = $activeLoans ?? 0;
$totalUsers = $totalUsers ?? 0;
$totalReservations = $totalReservations ?? 0;
$activeReservations = $activeReservations ?? $totalReservations;
?>

<div class="reservations-container">
    <!-- Hero Section -->
    <div class="reservations-hero">
        <div class="hero-content">
            <div class="hero-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="hero-text">
                <h1><?php echo __('my_reservations'); ?></h1>
                <p><?php echo __('manage_reservations'); ?></p>
            </div>
        </div>
        <div class="hero-stats">
            <div class="stat-card-mini">
                <i class="fas fa-bookmark"></i>
                <div class="stat-info">
                    <span class="stat-label"><?php echo __('total_reservations'); ?></span>
                    <strong><?php echo $totalReservations ?? 0; ?></strong>
                </div>
            </div>
            <div class="stat-card-mini">
                <i class="fas fa-hourglass-half"></i>
                <div class="stat-info">
                    <span class="stat-label"><?php echo __('pending'); ?></span>
                    <strong><?php echo $activeReservations ?? 0; ?></strong>
                </div>
            </div>
            <div class="stat-card-mini">
                <i class="fas fa-check-circle"></i>
                <div class="stat-info">
                    <span class="stat-label"><?php echo __('expired_cancelled'); ?></span>
                    <strong><?php echo ($totalReservations ?? 0) - ($activeReservations ?? 0); ?></strong>
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
                <h3><?php echo __('no_reservations'); ?></h3>
                <p><?php echo __('no_reservations_message'); ?></p>
                <a href="/books" class="btn-explore">
                    <i class="fas fa-search"></i> <?php echo __('explore_catalogue'); ?>
                </a>
            </div>
        <?php else: ?>
            <div class="reservations-grid">
                <?php foreach ($reservations as $reservation): ?>
                <div class="reservation-card <?php echo $reservation['status'] == 'expiree' ? 'expired' : ($reservation['status'] == 'annulee' ? 'cancelled' : 'active'); ?>">
                    <div class="reservation-status-badge">
                        <?php if ($reservation['status'] == 'active'): ?>
                            <span class="badge-active"><i class="fas fa-hourglass-half"></i> <?php echo __('reservation_active'); ?></span>
                        <?php elseif ($reservation['status'] == 'expiree'): ?>
                            <span class="badge-expired"><i class="fas fa-times-circle"></i> <?php echo __('reservation_expired'); ?></span>
                        <?php else: ?>
                            <span class="badge-cancelled"><i class="fas fa-ban"></i> <?php echo __('reservation_cancelled_status'); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="reservation-book">
                        <div class="book-cover" style="background-image: url('<?php echo $reservation['cover_image']; ?>')"></div>
                        <div class="book-info">
                            <h3 class="book-title"><?php echo htmlspecialchars($reservation['title']); ?></h3>
                            <p class="book-author">
                                <i class="fas fa-user-pen"></i> <?php echo htmlspecialchars($reservation['author']); ?>
                            </p>
                            <p class="book-isbn">
                                <i class="fas fa-barcode"></i> <?php echo $reservation['isbn']; ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="reservation-dates">
                        <div class="date-item">
                            <i class="fas fa-calendar-plus"></i>
                            <div>
                                <span class="date-label"><?php echo __('reserved_on'); ?></span>
                                <strong><?php echo date('d/m/Y', strtotime($reservation['reservation_date'])); ?></strong>
                            </div>
                        </div>
                        <div class="date-item <?php echo strtotime($reservation['expiry_date']) < time() ? 'expired-date' : ''; ?>">
                            <i class="fas fa-calendar-times"></i>
                            <div>
                                <span class="date-label"><?php echo __('expires_on'); ?></span>
                                <strong><?php echo date('d/m/Y', strtotime($reservation['expiry_date'])); ?></strong>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($reservation['status'] == 'active'): ?>
                    <div class="reservation-actions">
                        <button class="btn-cancel" onclick="cancelReservation(<?php echo $reservation['id']; ?>)">
                            <i class="fas fa-times"></i> <?php echo __('cancel_reservation'); ?>
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Garde tous tes styles CSS inchangés */
.reservations-container {
    max-width: 1200px;
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

.empty-state h3 {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: var(--text-secondary);
    margin-bottom: 1.5rem;
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
    transition: all 0.3s ease;
}

.btn-explore:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-neon);
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
        flex-wrap: wrap;
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
    if (!confirm('<?php echo __('confirm_cancel_reservation'); ?>')) return;
    
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
        alert('<?php echo __('connection_error'); ?>');
    }
}
</script>