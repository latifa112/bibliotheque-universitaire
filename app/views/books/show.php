<?php
$activePage = 'books';
$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

// Calculer les jours restants si le livre est emprunté
$daysLeft = null;
$isLate = false;
if ($userLoan && $userLoan['status'] == 'en_cours') {
    $dueDate = new DateTime($userLoan['due_date']);
    $now = new DateTime();
    $interval = $now->diff($dueDate);
    $daysLeft = (int)$interval->format('%r%a');
    $isLate = $daysLeft < 0;
}
?>

<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 2rem;">
    
    <!-- Bouton retour stylisé -->
    <div style="margin-bottom: 2rem;">
        <a href="/books" class="btn-back-glass">
            <i class="fas fa-arrow-left"></i>
            <span>Retour au catalogue</span>
        </a>
    </div>

    <!-- Carte principale avec effet glassmorphism -->
    <div class="book-detail-card">
        <div class="book-detail-inner">
            
            <!-- Section image -->
            <div class="book-image-section">
                <div class="image-wrapper">
                    <?php 
                    $coverImage = $book['cover_image'] ?? '';
                    $imagePath = '';
                    
                    if (!empty($coverImage)) {
                        if (filter_var($coverImage, FILTER_VALIDATE_URL)) {
                            $imagePath = $coverImage;
                        } elseif (file_exists(ROOT . '/public/' . ltrim($coverImage, '/'))) {
                            $imagePath = $coverImage;
                        }
                    }
                    ?>
                    
                    <?php if ($imagePath): ?>
                        <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" class="book-image">
                    <?php else: ?>
                        <div class="image-placeholder">
                            <i class="fas fa-book"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="stock-info-card">
                    <div class="stock-label">Exemplaires disponibles</div>
                    <div class="stock-number <?php echo $book['quantity'] > 0 ? 'in-stock' : 'out-stock'; ?>">
                        <?php echo $book['quantity']; ?>
                    </div>
                    <div class="status-badge <?php echo $book['quantity'] > 0 ? 'status-available' : 'status-unavailable'; ?>">
                        <i class="fas <?php echo $book['quantity'] > 0 ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                        <?php echo $book['quantity'] > 0 ? 'Disponible' : 'Indisponible'; ?>
                    </div>
                </div>
            </div>
            
            <!-- Section informations -->
            <div class="book-info-section">
                <h1 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h1>
                <p class="book-author">
                    <i class="fas fa-user-pen"></i> <?php echo htmlspecialchars($book['author']); ?>
                </p>
                
                <!-- Métadonnées -->
                <div class="metadata-grid">
                    <?php if (!empty($book['isbn'])): ?>
                    <div class="metadata-item">
                        <i class="fas fa-barcode"></i>
                        <div>
                            <span class="metadata-label">ISBN</span>
                            <span class="metadata-value"><?php echo htmlspecialchars($book['isbn']); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($book['category'])): ?>
                    <div class="metadata-item">
                        <i class="fas fa-tag"></i>
                        <div>
                            <span class="metadata-label">Catégorie</span>
                            <span class="metadata-value"><?php echo htmlspecialchars($book['category']); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Alerte emprunt -->
                <?php if ($userLoan && $userLoan['status'] == 'en_cours'): ?>
                <div class="loan-alert <?php echo $isLate ? 'alert-danger' : ($daysLeft <= 3 ? 'alert-warning' : 'alert-info'); ?>">
                    <div class="alert-icon">
                        <i class="fas <?php echo $isLate ? 'fa-exclamation-triangle' : 'fa-hourglass-half'; ?>"></i>
                    </div>
                    <div class="alert-content">
                        <strong><?php echo $isLate ? 'Livre en retard' : 'Emprunt en cours'; ?></strong>
                        <span>
                            <?php if ($isLate): ?>
                                Retard de <strong><?php echo abs($daysLeft); ?></strong> jour(s)
                            <?php else: ?>
                                <strong><?php echo $daysLeft; ?></strong> jour(s) restant(s)
                            <?php endif; ?>
                        </span>
                        <small>À rendre le <?php echo date('d/m/Y', strtotime($userLoan['due_date'])); ?></small>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Description -->
                <?php if (!empty($book['description'])): ?>
                <div class="description-box">
                    <h3><i class="fas fa-align-left"></i> Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
                </div>
                <?php endif; ?>
                
                <!-- Boutons d'action -->
                <div class="action-buttons">
                    <?php if ($book['quantity'] > 0 && !$userLoan): ?>
                        <button class="btn btn-primary" onclick="borrowBook(<?php echo $book['id']; ?>)">
                            <i class="fas fa-hand-holding-heart"></i> Emprunter
                        </button>
                    <?php elseif ($userLoan && $userLoan['status'] == 'en_cours'): ?>
                        <button class="btn btn-danger" onclick="returnBook(<?php echo $userLoan['id']; ?>)">
                            <i class="fas fa-undo-alt"></i> Retourner
                        </button>
                    <?php elseif ($book['quantity'] == 0 && !$userReservation): ?>
                        <button class="btn btn-warning" onclick="reserveBook(<?php echo $book['id']; ?>)">
                            <i class="fas fa-clock"></i> Réserver
                        </button>
                    <?php elseif ($userReservation): ?>
                        <button class="btn btn-outline-danger" onclick="cancelReservation(<?php echo $userReservation['id']; ?>)">
                            <i class="fas fa-times"></i> Annuler la réservation
                        </button>
                    <?php endif; ?>
                    
                    <?php if ($isAdmin): ?>
                        <a href="/books/edit/<?php echo $book['id']; ?>" class="btn btn-outline-primary">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <button class="btn btn-outline-danger" onclick="deleteBook(<?php echo $book['id']; ?>)">
                            <i class="fas fa-trash-alt"></i> Supprimer
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Bouton retour glassmorphism */
.btn-back-glass {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.2rem;
    background: rgba(99, 102, 241, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(99, 102, 241, 0.3);
    border-radius: 40px;
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-back-glass:hover {
    background: rgba(99, 102, 241, 0.2);
    transform: translateX(-5px);
    border-color: var(--primary);
}

/* Carte principale */
.book-detail-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 24px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.book-detail-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}

.book-detail-inner {
    display: flex;
    flex-wrap: wrap;
}

/* Section image */
.book-image-section {
    flex: 0 0 320px;
    background: linear-gradient(135deg, #1a1a2e, #0f0f1a);
    padding: 2rem;
    text-align: center;
}

.image-wrapper {
    margin-bottom: 1.5rem;
}

.book-image {
    width: 100%;
    max-width: 250px;
    border-radius: 16px;
    box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.5);
    transition: transform 0.3s ease;
}

.book-image:hover {
    transform: scale(1.02);
}

.image-placeholder {
    width: 100%;
    max-width: 250px;
    height: 350px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.image-placeholder i {
    font-size: 4rem;
    color: var(--primary);
    opacity: 0.5;
}

.stock-info-card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 16px;
    padding: 1rem;
    text-align: center;
}

.stock-label {
    font-size: 0.7rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.stock-number {
    font-size: 2rem;
    font-weight: 700;
    margin: 0.25rem 0;
}

.stock-number.in-stock {
    color: var(--success);
}

.stock-number.out-stock {
    color: var(--danger);
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-available {
    background: rgba(16, 185, 129, 0.2);
    color: var(--success);
}

.status-unavailable {
    background: rgba(239, 68, 68, 0.2);
    color: var(--danger);
}

/* Section informations */
.book-info-section {
    flex: 1;
    padding: 2rem;
}

.book-title {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    background: linear-gradient(135deg, #fff, #a5b4fc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.book-author {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.3rem 0.8rem;
    background: rgba(99, 102, 241, 0.1);
    border-radius: 20px;
    font-size: 0.85rem;
    margin-bottom: 1.5rem;
}

/* Métadonnées */
.metadata-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.metadata-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.8rem;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.metadata-item:hover {
    border-color: var(--primary);
    background: rgba(99, 102, 241, 0.05);
}

.metadata-item i {
    font-size: 1.2rem;
    color: var(--primary);
}

.metadata-label {
    display: block;
    font-size: 0.65rem;
    color: var(--text-secondary);
    text-transform: uppercase;
}

.metadata-value {
    display: block;
    font-size: 0.85rem;
    font-weight: 500;
}

/* Alertes emprunt */
.loan-alert {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: 16px;
    margin-bottom: 1.5rem;
}

.alert-info {
    background: rgba(59, 130, 246, 0.1);
    border-left: 4px solid var(--info);
}

.alert-warning {
    background: rgba(245, 158, 11, 0.1);
    border-left: 4px solid var(--warning);
}

.alert-danger {
    background: rgba(239, 68, 68, 0.1);
    border-left: 4px solid var(--danger);
}

.alert-icon i {
    font-size: 1.5rem;
}

.alert-info .alert-icon i {
    color: var(--info);
}

.alert-warning .alert-icon i {
    color: var(--warning);
}

.alert-danger .alert-icon i {
    color: var(--danger);
}

.alert-content {
    flex: 1;
}

.alert-content strong {
    display: block;
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
}

.alert-content span {
    display: block;
    font-size: 0.8rem;
    margin-bottom: 0.25rem;
}

.alert-content small {
    font-size: 0.7rem;
    color: var(--text-secondary);
}

/* Description */
.description-box {
    margin-bottom: 1.5rem;
}

.description-box h3 {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    margin-bottom: 0.75rem;
    color: var(--primary);
}

.description-box p {
    line-height: 1.6;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Boutons d'action */
.action-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn {
    padding: 0.7rem 1.5rem;
    border: none;
    border-radius: 40px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    text-decoration: none;
    font-size: 0.85rem;
}

.btn-primary {
    background: linear-gradient(135deg, var(--success), #059669);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4);
}

.btn-danger {
    background: linear-gradient(135deg, var(--danger), #dc2626);
    color: white;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(239, 68, 68, 0.4);
}

.btn-warning {
    background: linear-gradient(135deg, var(--warning), #d97706);
    color: white;
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(245, 158, 11, 0.4);
}

.btn-outline-primary {
    background: transparent;
    border: 1px solid #3b82f6;
    color: #3b82f6;
}

.btn-outline-primary:hover {
    background: #3b82f6;
    color: white;
}

.btn-outline-danger {
    background: transparent;
    border: 1px solid var(--danger);
    color: var(--danger);
}

.btn-outline-danger:hover {
    background: var(--danger);
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .book-image-section {
        flex: 0 0 100%;
    }
    
    .book-info-section {
        padding: 1.5rem;
    }
    
    .book-title {
        font-size: 1.5rem;
    }
    
    .metadata-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn {
        justify-content: center;
    }
}
</style>

<script>
async function borrowBook(bookId) {
    if (!confirm('Voulez-vous emprunter ce livre ?')) return;
    try {
        const response = await fetch('/loans/borrow', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({book_id: bookId})
        });
        const result = await response.json();
        if (result.success) {
            alert('✅ ' + result.message);
            window.location.reload();
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        alert('Erreur de connexion');
    }
}

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
            window.location.reload();
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        alert('Erreur de connexion');
    }
}

async function reserveBook(bookId) {
    if (!confirm('Voulez-vous réserver ce livre ?')) return;
    try {
        const response = await fetch('/reservations/reserve', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({book_id: bookId})
        });
        const result = await response.json();
        if (result.success) {
            alert('✅ ' + result.message);
            window.location.reload();
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        alert('Erreur de connexion');
    }
}

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
            window.location.reload();
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        alert('Erreur de connexion');
    }
}

async function deleteBook(bookId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce livre ?')) return;
    try {
        const response = await fetch('/books/delete/' + bookId, { method: 'DELETE' });
        const result = await response.json();
        if (result.success) {
            alert('✅ ' + result.message);
            window.location.href = '/books';
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        alert('Erreur de connexion');
    }
}
</script>