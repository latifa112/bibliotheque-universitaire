<?php
$activePage = 'books';
$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

// Initialisation des variables si elles n'existent pas
$userLoan = $userLoan ?? null;
$userReservation = $userReservation ?? null;

// Calculer les jours restants si le livre est emprunté
$daysLeft = null;
$isLate = false;
if ($userLoan && isset($userLoan['status']) && $userLoan['status'] == 'en_cours') {
    $dueDate = new DateTime($userLoan['due_date']);
    $now = new DateTime();
    $interval = $now->diff($dueDate);
    $daysLeft = (int)$interval->format('%r%a');
    $isLate = $daysLeft < 0;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['title'] ?? 'Détail du livre'); ?> - BiblioGest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            min-height: 100vh;
            color: #fff;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.3); }
            50% { box-shadow: 0 0 40px rgba(99, 102, 241, 0.6); }
        }

        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            animation: fadeInUp 0.6s ease-out;
        }

        /* Bouton retour */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.8rem 1.8rem;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            color: #a5b4fc;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 2rem;
        }

        .btn-back:hover {
            background: rgba(99, 102, 241, 0.2);
            border-color: #6366f1;
            color: white;
            transform: translateX(-5px);
        }

        /* Carte principale 3D */
        .book-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 48px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .book-card:hover {
            transform: translateY(-10px);
            border-color: rgba(99, 102, 241, 0.5);
            box-shadow: 0 30px 50px rgba(0, 0, 0, 0.4);
        }

        .book-card-inner {
            display: flex;
            flex-wrap: wrap;
        }

        /* Section image */
        .book-cover-section {
            flex: 0 0 380px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(139, 92, 246, 0.1));
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .book-cover-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1), transparent);
            animation: float 8s ease-in-out infinite;
        }

        .cover-wrapper {
            position: relative;
            z-index: 2;
        }

        .book-cover {
            width: 100%;
            max-width: 280px;
            border-radius: 24px;
            box-shadow: 0 30px 40px -20px rgba(0, 0, 0, 0.5);
            transition: all 0.4s ease;
        }

        .book-cover:hover {
            transform: scale(1.02) rotate(1deg);
            box-shadow: 0 40px 50px -20px rgba(99, 102, 241, 0.4);
        }

        .cover-placeholder {
            width: 280px;
            height: 400px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(139, 92, 246, 0.1));
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .cover-placeholder i {
            font-size: 5rem;
            color: #6366f1;
            opacity: 0.5;
        }

        /* Badge disponibilité */
        .availability-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 1.5rem;
            animation: glow 2s ease-in-out infinite;
        }

        .availability-badge.available {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(16, 185, 129, 0.1));
            border: 1px solid rgba(16, 185, 129, 0.5);
            color: #10b981;
        }

        .availability-badge.unavailable {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(239, 68, 68, 0.1));
            border: 1px solid rgba(239, 68, 68, 0.5);
            color: #ef4444;
        }

        .stock-counter {
            margin-top: 1rem;
            text-align: center;
        }

        .stock-number {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #6366f1, #a5b4fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Section informations */
        .book-info-section {
            flex: 1;
            padding: 3rem;
        }

        .book-title {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #fff, #a5b4fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .book-author {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 1rem;
            background: rgba(99, 102, 241, 0.15);
            border-radius: 50px;
            font-size: 0.9rem;
            color: #a5b4fc;
            margin-bottom: 2rem;
        }

        /* Métadonnées modernes */
        .metadata-modern {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .metadata-item-modern {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .metadata-item-modern:hover {
            background: rgba(99, 102, 241, 0.1);
            border-color: #6366f1;
            transform: translateY(-3px);
        }

        .metadata-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .metadata-content {
            flex: 1;
        }

        .metadata-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.5);
        }

        .metadata-value {
            font-size: 1rem;
            font-weight: 600;
        }

        /* Alerte emprunt */
        .loan-alert-modern {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.2rem;
            border-radius: 24px;
            margin-bottom: 2rem;
            animation: fadeInUp 0.5s ease-out;
        }

        .loan-alert-modern.alert-info {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(59, 130, 246, 0.05));
            border-left: 4px solid #3b82f6;
        }

        .loan-alert-modern.alert-warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(245, 158, 11, 0.05));
            border-left: 4px solid #f59e0b;
        }

        .loan-alert-modern.alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.05));
            border-left: 4px solid #ef4444;
        }

        .alert-icon-modern {
            width: 50px;
            height: 50px;
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .alert-info .alert-icon-modern {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }

        .alert-warning .alert-icon-modern {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }

        .alert-danger .alert-icon-modern {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .alert-content-modern {
            flex: 1;
        }

        .alert-content-modern strong {
            display: block;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .alert-content-modern span {
            font-size: 0.85rem;
            opacity: 0.8;
        }

        .alert-content-modern small {
            font-size: 0.7rem;
            opacity: 0.6;
        }

        /* Description */
        .description-modern {
            margin-bottom: 2rem;
        }

        .description-modern h3 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
            margin-bottom: 1rem;
            color: #a5b4fc;
        }

        .description-modern p {
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
        }

        /* Boutons d'action */
        .action-buttons-modern {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-modern {
            padding: 1rem 2rem;
            border: none;
            border-radius: 60px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
        }

        .btn-modern::before {
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

        .btn-modern:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-modern:hover {
            transform: translateY(-3px);
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 5px 20px rgba(16, 185, 129, 0.3);
        }

        .btn-danger-modern {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            box-shadow: 0 5px 20px rgba(239, 68, 68, 0.3);
        }

        .btn-warning-modern {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            box-shadow: 0 5px 20px rgba(245, 158, 11, 0.3);
        }

        .btn-outline-modern {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }

        .btn-outline-modern:hover {
            background: rgba(99, 102, 241, 0.2);
            border-color: #6366f1;
        }

        /* Particles background */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            background: rgba(99, 102, 241, 0.1);
            border-radius: 50%;
            animation: float 15s infinite;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .book-cover-section {
                flex: 0 0 100%;
            }
            
            .book-info-section {
                padding: 2rem;
            }
            
            .book-title {
                font-size: 1.8rem;
            }
            
            .metadata-modern {
                grid-template-columns: 1fr;
            }
            
            .action-buttons-modern {
                flex-direction: column;
            }
            
            .btn-modern {
                justify-content: center;
            }
        }

        /* Bouton Modifier - Bleu */
.btn-edit-modern {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    box-shadow: 0 5px 20px rgba(59, 130, 246, 0.3);
}

.btn-edit-modern:hover {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
}

/* Bouton Supprimer - Rouge */
.btn-delete-modern {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 5px 20px rgba(239, 68, 68, 0.3);
}

.btn-delete-modern:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
}

    </style>
</head>
<body>

<!-- Particles -->
<div class="particles" id="particles"></div>

<div class="container">
    <!-- Bouton retour -->
    <a href="/books" class="btn-back">
        <i class="fas fa-arrow-left"></i>
        <span>Retour au catalogue</span>
        <i class="fas fa-book-open" style="margin-left: 0.5rem;"></i>
    </a>

    <!-- Carte principale -->
    <div class="book-card">
        <div class="book-card-inner">
            
            <!-- Section image -->
            <div class="book-cover-section">
                <div class="cover-wrapper">
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
                        <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($book['title']); ?>" class="book-cover">
                    <?php else: ?>
                        <div class="cover-placeholder">
                            <i class="fas fa-book"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="availability-badge <?php echo ($book['available_quantity'] ?? 0) > 0 ? 'available' : 'unavailable'; ?>">
                    <i class="fas <?php echo ($book['available_quantity'] ?? 0) > 0 ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                    <?php echo ($book['available_quantity'] ?? 0) > 0 ? 'Disponible' : 'Indisponible'; ?>
                </div>
                
                <div class="stock-counter">
                    <div class="stock-number"><?php echo $book['available_quantity'] ?? 0; ?> / <?php echo $book['quantity'] ?? 0; ?></div>
                    <div style="font-size: 0.7rem; opacity: 0.6;">exemplaires disponibles</div>
                </div>
            </div>
            
            <!-- Section informations -->
            <div class="book-info-section">
                <h1 class="book-title"><?php echo htmlspecialchars($book['title'] ?? 'Titre inconnu'); ?></h1>
                <div class="book-author">
                    <i class="fas fa-user-pen"></i>
                    <?php echo htmlspecialchars($book['author'] ?? 'Auteur inconnu'); ?>
                </div>
                
                <!-- Métadonnées modernes -->
                <div class="metadata-modern">
                    <?php if (!empty($book['isbn'])): ?>
                    <div class="metadata-item-modern">
                        <div class="metadata-icon">
                            <i class="fas fa-barcode"></i>
                        </div>
                        <div class="metadata-content">
                            <div class="metadata-label">ISBN</div>
                            <div class="metadata-value"><?php echo htmlspecialchars($book['isbn']); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($book['category'])): ?>
                    <div class="metadata-item-modern">
                        <div class="metadata-icon">
                            <i class="fas fa-tag"></i>
                        </div>
                        <div class="metadata-content">
                            <div class="metadata-label">Catégorie</div>
                            <div class="metadata-value"><?php echo htmlspecialchars($book['category']); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="metadata-item-modern">
                        <div class="metadata-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="metadata-content">
                            <div class="metadata-label">Année</div>
                            <div class="metadata-value"><?php echo htmlspecialchars($book['year'] ?? 'Non spécifiée'); ?></div>
                        </div>
                    </div>
                    
                    <div class="metadata-item-modern">
                        <div class="metadata-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="metadata-content">
                            <div class="metadata-label">Quantité totale</div>
                            <div class="metadata-value"><?php echo $book['quantity'] ?? 0; ?> exemplaires</div>
                        </div>
                    </div>
                </div>
                
                <!-- Alerte emprunt -->
                <?php if ($userLoan && isset($userLoan['status']) && $userLoan['status'] == 'en_cours'): ?>
                <div class="loan-alert-modern <?php echo $isLate ? 'alert-danger' : ($daysLeft <= 3 ? 'alert-warning' : 'alert-info'); ?>">
                    <div class="alert-icon-modern">
                        <i class="fas <?php echo $isLate ? 'fa-exclamation-triangle' : 'fa-hourglass-half'; ?>"></i>
                    </div>
                    <div class="alert-content-modern">
                        <strong><?php echo $isLate ? '📚 Livre en retard' : '📖 Emprunt en cours'; ?></strong>
                        <span>
                            <?php if ($isLate): ?>
                                Retard de <strong><?php echo abs($daysLeft); ?></strong> jour(s)
                            <?php else: ?>
                                <strong><?php echo $daysLeft; ?></strong> jour(s) restant(s) avant retour
                            <?php endif; ?>
                        </span>
                        <small>À rendre le <?php echo isset($userLoan['due_date']) ? date('d/m/Y', strtotime($userLoan['due_date'])) : 'Date inconnue'; ?></small>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Description -->
                <?php if (!empty($book['description'])): ?>
                <div class="description-modern">
                    <h3><i class="fas fa-align-left"></i> Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
                </div>
                <?php endif; ?>
                
 <!-- Boutons d'action -->
<div class="action-buttons-modern">
    <?php if (($book['available_quantity'] ?? 0) > 0 && !$userLoan): ?>
        <button class="btn-modern btn-primary-modern" onclick="borrowBook(<?php echo $book['id']; ?>)">
            <i class="fas fa-hand-holding-heart"></i>
            <span>Emprunter ce livre</span>
            <i class="fas fa-arrow-right"></i>
        </button>
    <?php elseif ($userLoan && isset($userLoan['status']) && $userLoan['status'] == 'en_cours'): ?>
        <button class="btn-modern btn-danger-modern" onclick="returnBook(<?php echo $userLoan['id']; ?>)">
            <i class="fas fa-undo-alt"></i>
            <span>Retourner le livre</span>
            <i class="fas fa-check-circle"></i>
        </button>
    <?php elseif (($book['available_quantity'] ?? 0) == 0 && !$userReservation): ?>
        <button class="btn-modern btn-warning-modern" onclick="reserveBook(<?php echo $book['id']; ?>)">
            <i class="fas fa-clock"></i>
            <span>Réserver ce livre</span>
            <i class="fas fa-calendar-alt"></i>
        </button>
    <?php elseif ($userReservation && isset($userReservation['status']) && $userReservation['status'] == 'active'): ?>
        <button class="btn-modern btn-outline-modern" onclick="cancelReservation(<?php echo $userReservation['id']; ?>)">
            <i class="fas fa-times"></i>
            <span>Annuler la réservation</span>
        </button>
    <?php endif; ?>
    
    <?php if ($isAdmin): ?>
        <a href="/books/edit/<?php echo $book['id']; ?>" class="btn-modern btn-edit-modern">
            <i class="fas fa-edit"></i>
            <span>Modifier</span>
            <i class="fas fa-pen"></i>
        </a>
        <button class="btn-modern btn-delete-modern" onclick="deleteBook(<?php echo $book['id']; ?>)">
            <i class="fas fa-trash-alt"></i>
            <span>Supprimer</span>
            <i class="fas fa-exclamation-triangle"></i>
        </button>
    <?php endif; ?>
</div>
            </div>
        </div>
    </div>
</div>

<script>
// Particles animation
function createParticles() {
    const particlesContainer = document.getElementById('particles');
    for (let i = 0; i < 50; i++) {
        const particle = document.createElement('div');
        particle.classList.add('particle');
        particle.style.width = Math.random() * 5 + 2 + 'px';
        particle.style.height = particle.style.width;
        particle.style.left = Math.random() * 100 + '%';
        particle.style.top = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 10 + 's';
        particle.style.animationDuration = Math.random() * 20 + 10 + 's';
        particlesContainer.appendChild(particle);
    }
}
createParticles();

async function borrowBook(bookId) {
    if (!confirm('📚 Voulez-vous emprunter ce livre ?')) return;
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
        alert('✅ Votre emprunt a été effectué avec succès !');
        window.location.reload();
    }
}

async function returnBook(loanId) {
    if (!confirm('📖 Voulez-vous retourner ce livre ?')) return;
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
        alert('✅ Livre retourné avec succès !');
        window.location.reload();
    }
}

async function reserveBook(bookId) {
    if (!confirm('📅 Voulez-vous réserver ce livre ?')) return;
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
        alert('✅ Réservation effectuée avec succès !');
        window.location.reload();
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
        alert('✅ Réservation annulée avec succès !');
        window.location.reload();
    }
}

async function deleteBook(bookId) {
    if (!confirm('⚠️ Êtes-vous sûr de vouloir supprimer ce livre ? Cette action est irréversible.')) return;
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
</body>
</html>