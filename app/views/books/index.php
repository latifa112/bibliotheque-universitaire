<?php
$activePage = 'books';
$totalBooks = $totalBooks ?? 0;
$activeLoans = $activeLoans ?? 0;
$totalUsers = $totalUsers ?? 0;
$totalReservations = $totalReservations ?? 0;
$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
$searchTerm = isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '';
// Calculer le nombre de livres disponibles
$availableCount = 0;
foreach ($books as $book) {
    if ($book['available_quantity'] > 0) $availableCount++;
}
?>

<div class="catalogue-container">
    <!-- Hero Section -->
    <div class="catalogue-hero">
        <div class="hero-content">
            <div class="hero-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="hero-text">
                <h1><?php echo __('catalogue'); ?></h1>
                <p><?php echo __('discover_collection'); ?> <?php echo $totalBooks; ?> <?php echo __('books'); ?></p>
            </div>
        </div>
        <div class="hero-stats">
            <div class="stat-card-mini">
                <i class="fas fa-book"></i>
                <div class="stat-info">
                    <span class="stat-label"><?php echo __('total_books'); ?></span>
                    <strong><?php echo $totalBooks; ?></strong>
                </div>
            </div>
            <div class="stat-card-mini">
                <i class="fas fa-check-circle"></i>
                <div class="stat-info">
                    <span class="stat-label"><?php echo __('available'); ?></span>
                    <strong><?php echo $availableCount; ?></strong>
                </div>
            </div>
            <?php if ($isAdmin): ?>
            <a href="/books/create" class="btn-add-modern">
                <i class="fas fa-plus-circle"></i>
                <span><?php echo __('add_book'); ?></span>
                <i class="fas fa-arrow-right"></i>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Barre de recherche -->
    <div class="search-section-modern">
        <div class="search-container-modern">
            <i class="fas fa-search search-icon-modern"></i>
            <input type="text" id="searchInput" placeholder="<?php echo __('search_books_placeholder'); ?>" value="<?php echo $searchTerm; ?>">
            <button class="search-btn-modern" onclick="searchNow()">
                <i class="fas fa-search"></i> <?php echo __('search'); ?>
            </button>
        </div>
        <div id="searchHint" class="search-hint" style="display: none;">
            <i class="fas fa-info-circle"></i> <?php echo __('auto_search_hint'); ?>
        </div>
    </div>

    <!-- Grille des livres -->
    <div class="books-grid-modern">
        <?php if (empty($books)): ?>
            <div class="empty-state-modern">
                <i class="fas fa-search"></i>
                <h3><?php echo __('no_books_found'); ?></h3>
                <p><?php echo __('no_results_for'); ?> "<?php echo $searchTerm; ?>"</p>
                <button onclick="window.location.href='/books'" class="btn-clear"><?php echo __('view_all_books'); ?></button>
            </div>
        <?php else: ?>
            <?php foreach ($books as $book): ?>
            <div class="book-card-modern">
                <div class="book-cover-modern" style="background-image: url('<?php echo $book['cover_image']; ?>')">
                    <div class="book-overlay">
                        <button class="quick-view" onclick="showBookDetails(<?php echo $book['id']; ?>)">
                            <i class="fas fa-eye"></i> <?php echo __('preview'); ?>
                        </button>
                    </div>
                    <?php if ($book['available_quantity'] > 0): ?>
                        <div class="availability available">
                            <i class="fas fa-check-circle"></i> <?php echo __('available'); ?>
                        </div>
                    <?php else: ?>
                        <div class="availability unavailable">
                            <i class="fas fa-times-circle"></i> <?php echo __('unavailable'); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="book-info-modern">
                    <h3 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                    <p class="book-author">
                        <i class="fas fa-user-pen"></i> <?php echo htmlspecialchars($book['author']); ?>
                    </p>
                    <div class="book-details-modern">
                        <span><i class="fas fa-barcode"></i> <?php echo $book['isbn']; ?></span>
                        <span><i class="fas fa-layer-group"></i> <?php echo $book['available_quantity']; ?>/<?php echo $book['quantity']; ?></span>
                    </div>
                    <?php if ($isAdmin): ?>
                    <div class="admin-actions-modern">
                        <button class="btn-edit" onclick="editBook(<?php echo $book['id']; ?>)">
                            <i class="fas fa-edit"></i> <?php echo __('edit'); ?>
                        </button>
                        <button class="btn-delete" onclick="deleteBook(<?php echo $book['id']; ?>)">
                            <i class="fas fa-trash-alt"></i> <?php echo __('delete'); ?>
                        </button>
                    </div>
                    <?php endif; ?>
                    <div class="book-actions-modern">
                        <?php if ($book['available_quantity'] > 0): ?>
                        <button class="btn-borrow" onclick="borrowBook(<?php echo $book['id']; ?>)">
                            <i class="fas fa-book-reader"></i> <?php echo __('borrow'); ?>
                        </button>
                        <?php else: ?>
                        <button class="btn-reserve" onclick="reserveBook(<?php echo $book['id']; ?>)">
                            <i class="fas fa-clock"></i> <?php echo __('reserve'); ?>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
/* Tous tes styles CSS existants restent inchangés */
.catalogue-container {
    max-width: 1400px;
    margin: 0 auto;
}

.catalogue-hero {
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
    align-items: center;
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

.btn-add-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
    border-radius: 40px;
    color: white;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    position: relative;
    overflow: hidden;
}

.btn-add-modern::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-add-modern:hover::before {
    width: 300px;
    height: 300px;
}

.btn-add-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.5);
}

.btn-add-modern i:first-child {
    font-size: 1.1rem;
}

.btn-add-modern i:last-child {
    transition: transform 0.3s ease;
}

.btn-add-modern:hover i:last-child {
    transform: translateX(5px);
}

.search-section-modern {
    margin-bottom: 2rem;
}

.search-container-modern {
    position: relative;
    max-width: 100%;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 60px;
    border: 1px solid var(--border-color);
    transition: all 0.3s;
}

.search-container-modern:focus-within {
    border-color: var(--primary);
    box-shadow: 0 0 20px rgba(99, 102, 241, 0.2);
}

.search-icon-modern {
    position: absolute;
    left: 1.5rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary);
    font-size: 1.2rem;
}

.search-container-modern input {
    width: 100%;
    padding: 1.2rem 1.5rem 1.2rem 3.5rem;
    background: transparent;
    border: none;
    color: var(--text-primary);
    font-size: 1rem;
}

.search-container-modern input:focus {
    outline: none;
}

.search-btn-modern {
    position: absolute;
    right: 0.8rem;
    top: 50%;
    transform: translateY(-50%);
    padding: 0.6rem 1.5rem;
    background: var(--gradient-1);
    border: none;
    border-radius: 40px;
    color: white;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s;
}

.search-btn-modern:hover {
    transform: translateY(-50%) scale(1.02);
    box-shadow: 0 0 15px rgba(99, 102, 241, 0.5);
}

.search-hint {
    margin-top: 0.5rem;
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.4);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding-left: 0.5rem;
}

.books-grid-modern {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 2rem;
}

.book-card-modern {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 24px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.book-card-modern:hover {
    transform: translateY(-8px);
    border-color: var(--primary);
    box-shadow: var(--shadow-lg);
}

.book-cover-modern {
    height: 260px;
    background-size: cover;
    background-position: center;
    position: relative;
}

.book-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}

.book-card-modern:hover .book-overlay {
    opacity: 1;
}

.quick-view {
    padding: 0.6rem 1.2rem;
    background: var(--gradient-1);
    border: none;
    border-radius: 30px;
    color: white;
    cursor: pointer;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: transform 0.3s;
}

.quick-view:hover {
    transform: scale(1.05);
}

.availability {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.3rem 0.8rem;
    border-radius: 30px;
    font-size: 0.7rem;
    font-weight: 600;
    backdrop-filter: blur(5px);
}

.availability.available {
    background: rgba(16, 185, 129, 0.9);
    color: white;
}

.availability.unavailable {
    background: rgba(239, 68, 68, 0.9);
    color: white;
}

.book-info-modern {
    padding: 1.5rem;
}

.book-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.book-author {
    font-size: 0.85rem;
    color: var(--primary);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.book-details-modern {
    display: flex;
    justify-content: space-between;
    font-size: 0.75rem;
    color: var(--text-secondary);
    padding: 0.5rem 0;
    border-top: 1px solid var(--border-color);
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 1rem;
}

.book-details-modern span {
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.admin-actions-modern {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}

.btn-edit, .btn-delete {
    flex: 1;
    padding: 0.5rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.75rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.3rem;
    transition: all 0.3s;
}

.btn-edit {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
}

.btn-edit:hover {
    background: #3b82f6;
    color: white;
}

.btn-delete {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.btn-delete:hover {
    background: #ef4444;
    color: white;
}

.book-actions-modern {
    display: flex;
    gap: 0.75rem;
}

.btn-borrow, .btn-reserve {
    flex: 1;
    padding: 0.6rem;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s;
}

.btn-borrow {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.btn-borrow:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.btn-reserve {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.btn-reserve:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
}

.empty-state-modern {
    text-align: center;
    padding: 4rem;
    background: var(--card-bg);
    border-radius: 24px;
    grid-column: 1 / -1;
}

.empty-state-modern i {
    font-size: 3rem;
    color: var(--primary);
    margin-bottom: 1rem;
}

.empty-state-modern h3 {
    font-size: 1.3rem;
    margin-bottom: 0.5rem;
}

.btn-clear {
    margin-top: 1rem;
    padding: 0.6rem 1.5rem;
    background: var(--gradient-1);
    border: none;
    border-radius: 30px;
    color: white;
    cursor: pointer;
}

@media (max-width: 768px) {
    .catalogue-hero {
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
    
    .books-grid-modern {
        grid-template-columns: 1fr;
    }
    
    .btn-add-modern {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
let searchTimeout;

function searchBooks() {
    const search = document.getElementById('searchInput').value;
    clearTimeout(searchTimeout);
    
    const hint = document.getElementById('searchHint');
    if (hint && search.trim().length >= 3) {
        hint.style.display = 'flex';
    }
    
    searchTimeout = setTimeout(() => {
        if (search.trim().length > 2) {
            window.location.href = '/books/search?q=' + encodeURIComponent(search.trim());
        } else if (search.trim().length === 0) {
            window.location.href = '/books';
        }
        if (hint) hint.style.display = 'none';
    }, 1000);
}

function searchNow() {
    const search = document.getElementById('searchInput').value.trim();
    if (search.length > 0) {
        window.location.href = '/books/search?q=' + encodeURIComponent(search);
    } else {
        window.location.href = '/books';
    }
}

async function borrowBook(bookId) {
    if (!confirm('<?php echo __('confirm_borrow'); ?>')) return;
    
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
        alert('<?php echo __('connection_error'); ?>');
    }
}

// FONCTION RÉSERVATION MODIFIÉE
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
            location.reload();
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        alert('Erreur de connexion');
    }
}

function editBook(bookId) {
    window.location.href = '/books/edit/' + bookId;
}

async function deleteBook(bookId) {
    if (!confirm('<?php echo __('confirm_delete_book'); ?>')) return;
    
    try {
        const response = await fetch('/books/delete/' + bookId, { method: 'DELETE' });
        const result = await response.json();
        
        if (result.success) {
            alert('✅ <?php echo __('book_deleted'); ?>');
            location.reload();
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        alert('<?php echo __('connection_error'); ?>');
    }
}

async function showBookDetails(bookId) {
    alert('<?php echo __('feature_coming_soon'); ?>');
}

document.getElementById('searchInput').addEventListener('input', function() {
    searchBooks();
});

document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        clearTimeout(searchTimeout);
        searchNow();
    }
});
</script>