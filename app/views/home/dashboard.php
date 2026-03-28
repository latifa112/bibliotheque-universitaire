<?php
// Définir les variables pour la sidebar
$activePage = 'dashboard';
$totalBooks = $totalBooks ?? 0;
$activeLoans = $activeLoans ?? 0;
$totalUsers = $totalUsers ?? 0;
$totalReservations = $totalReservations ?? 0;
?>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-book-open"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo number_format($stats['total_books']); ?></h3>
            <p><?php echo __('total_books'); ?></p>
        </div>
    </div>
    
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo number_format($stats['active_users'] ?? 0); ?></h3>
            <p><?php echo __('active_users'); ?></p>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-book-reader"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $stats['my_loans']; ?></h3>
            <p><?php echo __('my_loans'); ?></p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo $stats['reservations'] ?? 0; ?></h3>
            <p><?php echo __('reservations'); ?></p>
        </div>
    </div>
</div>

<!-- Section Bienvenue -->
<div class="welcome-section">
    <div class="welcome-card">
        <div class="welcome-content">
            <div class="welcome-text">
                <h2>
                    <i class="fas fa-hand-wave" style="margin-right: 0.75rem; color: var(--primary);"></i>
                    <?php echo __('welcome'); ?>, <?php echo $_SESSION['user_name']; ?>
                </h2>
                <div class="user-info-grid">
                    <div class="user-info-item">
                        <div class="info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="info-details">
                            <span class="info-label"><?php echo __('email'); ?></span>
                            <span class="info-value"><?php echo $_SESSION['user_email']; ?></span>
                        </div>
                    </div>
                    <div class="user-info-item">
                        <div class="info-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="info-details">
                            <span class="info-label"><?php echo __('role'); ?></span>
                            <span class="info-value role-<?php echo strtolower($_SESSION['user_role']); ?>">
                                <?php 
                                $roles = ['admin' => 'Administrateur', 'etudiant' => 'Étudiant', 'professeur' => 'Professeur'];
                                echo $roles[strtolower($_SESSION['user_role'])] ?? ucfirst($_SESSION['user_role']); 
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="user-info-item">
                        <div class="info-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="info-details">
                            <span class="info-label"><?php echo __('member_since'); ?></span>
                            <span class="info-value"><?php echo date('F Y'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="welcome-actions">
                <a href="/books" class="action-btn primary-btn">
                    <i class="fas fa-book-open"></i>
                    <span><?php echo __('explore_catalogue'); ?></span>
                    <i class="fas fa-arrow-right"></i>
                </a>
                <a href="/loans" class="action-btn loans-btn">
                    <i class="fas fa-book-reader"></i>
                    <span><?php echo __('view_my_loans'); ?></span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Nouveautés -->
<div class="section">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-star-of-life"></i>
            <?php echo __('new_arrivals'); ?>
        </h2>
    </div>
    <div class="books-grid">
        <?php foreach ($recent_books as $index => $book): ?>
        <div class="book-card">
            <div class="book-cover" style="background-image: url('<?php echo $book['cover_image']; ?>')">
                <?php if ($index == 0): ?>
                <div class="book-badge">
                    <i class="fas fa-crown"></i> Nouveau
                </div>
                <?php endif; ?>
            </div>
            <div class="book-info">
                <h4 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h4>
                <p class="book-author">
                    <i class="fas fa-user-pen"></i> <?php echo htmlspecialchars($book['author']); ?>
                </p>
                <div class="book-meta">
                    <span class="book-isbn">
                        <i class="fas fa-barcode"></i> <?php echo $book['isbn']; ?>
                    </span>
                    <span class="badge <?php echo $book['available_quantity'] > 0 ? 'badge-success' : 'badge-warning'; ?>">
                        <i class="fas <?php echo $book['available_quantity'] > 0 ? 'fa-check-circle' : 'fa-clock'; ?>"></i>
                        <?php echo $book['available_quantity'] > 0 ? __('available') : __('unavailable'); ?>
                    </span>
                </div>
                <div class="book-actions">
                    <?php if ($book['available_quantity'] > 0): ?>
                    <button class="book-action-btn borrow" onclick="borrowBook(<?php echo $book['id']; ?>)">
                        <i class="fas fa-book-reader"></i> <?php echo __('borrow'); ?>
                    </button>
                    <?php endif; ?>
                    <button class="book-action-btn reserve" onclick="reserveBook(<?php echo $book['id']; ?>)">
                        <i class="fas fa-calendar-check"></i> <?php echo __('reserve'); ?>
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Recommandations personnalisées -->
<div class="section recommendations-section">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-magic"></i>
            Recommandations personnalisées
        </h2>
        <div class="recommendation-badge">
            <i class="fas fa-robot"></i> Basé sur vos préférences
        </div>
    </div>
    <div class="recommendations-grid" id="recommendationsGrid">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i> Chargement des recommandations...
        </div>
    </div>
</div>

<style>
/* Welcome Section */
.welcome-section {
    margin-bottom: 2rem;
}

.welcome-card {
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(139, 92, 246, 0.05) 100%);
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    border-radius: 24px;
    padding: 2rem;
    transition: all 0.3s ease;
}

.welcome-card:hover {
    border-color: var(--primary);
    box-shadow: var(--shadow-neon);
}

.welcome-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 2rem;
}

.welcome-text h2 {
    font-size: 1.75rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
}

.user-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.user-info-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.user-info-item:hover {
    background: rgba(99, 102, 241, 0.1);
    transform: translateX(5px);
}

.info-icon {
    width: 40px;
    height: 40px;
    background: var(--gradient-1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.info-details {
    display: flex;
    flex-direction: column;
}

.info-label {
    font-size: 0.7rem;
    opacity: 0.6;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.info-value {
    font-size: 0.9rem;
    font-weight: 500;
}

.role-admin {
    color: #6366f1;
}

.role-etudiant {
    color: #10b981;
}

.role-professeur {
    color: #f59e0b;
}

/* Action Buttons */
.welcome-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
}

.action-btn i {
    transition: transform 0.3s ease;
}

.action-btn:hover i {
    transform: translateX(5px);
}

.primary-btn {
    background: var(--gradient-1);
    color: white;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
}

.primary-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
}

.loans-btn {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.loans-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
}

/* Book Cards améliorés */
.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
}

.book-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--glass-border);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    height: 100%;
}

.book-card:hover {
    transform: translateY(-8px);
    border-color: var(--primary);
    box-shadow: var(--shadow-neon);
}

.book-cover {
    height: 280px;
    background-size: cover;
    background-position: center;
    position: relative;
}

.book-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: linear-gradient(135deg, #f59e0b, #ef4444);
    padding: 0.4rem 1rem;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    z-index: 10;
    backdrop-filter: blur(5px);
}

.book-info {
    padding: 1.5rem;
    background: rgba(15, 23, 42, 0.95);
    flex: 1;
    display: flex;
    flex-direction: column;
}

.book-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.book-author {
    font-size: 0.9rem;
    color: var(--primary);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.book-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 1rem 0;
    padding: 0.75rem 0;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    flex-wrap: wrap;
    gap: 0.5rem;
}

.book-isbn {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.badge {
    padding: 0.35rem 1rem;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.badge-success {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.badge-warning {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.book-actions {
    display: flex;
    gap: 0.75rem;
    margin-top: auto;
    padding-top: 1rem;
}

.book-action-btn {
    flex: 1;
    padding: 0.75rem;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.book-action-btn.borrow {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.book-action-btn.borrow:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

.book-action-btn.reserve {
    background: rgba(255, 255, 255, 0.08);
    color: var(--light);
    border: 1px solid var(--glass-border);
}

.book-action-btn.reserve:hover {
    background: rgba(99, 102, 241, 0.2);
    border-color: var(--primary);
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .welcome-content {
        flex-direction: column;
        text-align: center;
    }
    
    .user-info-grid {
        grid-template-columns: 1fr;
    }
    
    .welcome-actions {
        justify-content: center;
        width: 100%;
    }
    
    .action-btn {
        width: 100%;
        justify-content: center;
    }
    
    .books-grid {
        grid-template-columns: 1fr;
    }
    
    .book-meta {
        flex-direction: column;
        align-items: flex-start;
    }
}

/* Recommandations Section */
.recommendations-section {
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.08), rgba(139, 92, 246, 0.03));
}

.recommendation-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: rgba(99, 102, 241, 0.2);
    border-radius: 30px;
    font-size: 0.8rem;
    color: var(--primary);
}

.recommendations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.recommendation-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
}

.recommendation-card:hover {
    transform: translateY(-5px);
    border-color: var(--primary);
    box-shadow: var(--shadow-lg);
}

.recommendation-cover {
    height: 200px;
    background-size: cover;
    background-position: center;
    position: relative;
}

.recommendation-reason {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.8));
    padding: 1rem 0.75rem 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.7rem;
    color: white;
}

.recommendation-info {
    padding: 1rem;
}

.recommendation-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.recommendation-author {
    font-size: 0.8rem;
    color: var(--primary);
    margin-bottom: 0.75rem;
}

.recommendation-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.5rem;
}

.btn-recommend-borrow, .btn-recommend-reserve {
    padding: 0.4rem 0.8rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.75rem;
    font-weight: 500;
    transition: all 0.3s;
}

.btn-recommend-borrow {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.btn-recommend-reserve {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.btn-recommend-borrow:hover, .btn-recommend-reserve:hover {
    transform: translateY(-2px);
}

.empty-recommendations {
    text-align: center;
    padding: 3rem;
    background: rgba(255,255,255,0.02);
    border-radius: 20px;
}

.empty-recommendations i {
    font-size: 3rem;
    color: var(--primary);
    margin-bottom: 1rem;
}

.loading-spinner {
    text-align: center;
    padding: 2rem;
    color: var(--primary);
}
</style>

<script>
async function borrowBook(bookId) {
    if (!confirm('<?php echo __('confirm'); ?>')) return;
    
    try {
        const response = await fetch('/loans/borrow', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({book_id: bookId})
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

function reserveBook(bookId) {
    alert('📅 <?php echo __('reserve'); ?>');
}

// Charger les recommandations
async function loadRecommendations() {
    try {
        const response = await fetch('/api/recommendations');
        const data = await response.json();
        
        if (data.success && data.recommendations.length > 0) {
            displayRecommendations(data.recommendations);
        } else {
            document.getElementById('recommendationsGrid').innerHTML = `
                <div class="empty-recommendations">
                    <i class="fas fa-lightbulb"></i>
                    <p>Empruntez quelques livres pour obtenir des recommandations personnalisées !</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Erreur:', error);
        document.getElementById('recommendationsGrid').innerHTML = `
            <div class="empty-recommendations">
                <i class="fas fa-exclamation-circle"></i>
                <p>Erreur de chargement des recommandations</p>
            </div>
        `;
    }
}

function displayRecommendations(books) {
    const grid = document.getElementById('recommendationsGrid');
    grid.innerHTML = books.map(book => `
        <div class="recommendation-card" data-book-id="${book.id}">
            <div class="recommendation-cover" style="background-image: url('${book.cover_image}')">
                <div class="recommendation-reason">
                    <i class="fas fa-${getReasonIcon(book.reason)}"></i>
                    <span>${book.reason}</span>
                </div>
            </div>
            <div class="recommendation-info">
                <h4 class="recommendation-title">${escapeHtml(book.title)}</h4>
                <p class="recommendation-author">${escapeHtml(book.author)}</p>
                <div class="recommendation-meta">
                    <span class="badge ${book.available_quantity > 0 ? 'badge-success' : 'badge-warning'}">
                        <i class="fas ${book.available_quantity > 0 ? 'fa-check-circle' : 'fa-clock'}"></i>
                        ${book.available_quantity > 0 ? 'Disponible' : 'Indisponible'}
                    </span>
                    ${book.available_quantity > 0 ? 
                        `<button class="btn-recommend-borrow" onclick="borrowBook(${book.id})">
                            <i class="fas fa-book-reader"></i> Emprunter
                        </button>` : 
                        `<button class="btn-recommend-reserve" onclick="reserveBook(${book.id})">
                            <i class="fas fa-clock"></i> Réserver
                        </button>`
                    }
                </div>
            </div>
        </div>
    `).join('');
}

function getReasonIcon(reason) {
    if (reason.includes('filière')) return 'graduation-cap';
    if (reason.includes('aimez')) return 'heart';
    if (reason.includes('populaire')) return 'fire';
    if (reason.includes('nouvelle')) return 'star';
    return 'magic';
}

// Charger les recommandations au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    loadRecommendations();
});
</script>